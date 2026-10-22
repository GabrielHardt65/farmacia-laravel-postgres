-- =====================================================
-- AJUSTES NAS TABELAS
-- =====================================================

-- Produtos: foto e tipo de controle
ALTER TABLE produtos ADD COLUMN IF NOT EXISTS foto VARCHAR(255);
ALTER TABLE produtos ADD COLUMN IF NOT EXISTS tipo_controle VARCHAR(20) NOT NULL DEFAULT 'livre'
  CHECK (tipo_controle IN ('livre','antibiotico','tarja_vermelha','tarja_preta'));

-- Produtos que ja exigiam receita viram tarja vermelha 
UPDATE produtos SET tipo_controle = 'tarja_vermelha'
 WHERE exige_receita = true AND tipo_controle = 'livre';

-- Funcionarios: login e senha (a senha guarda o hash gerado pelo Laravel)
ALTER TABLE funcionarios ADD COLUMN IF NOT EXISTS login VARCHAR(50);
ALTER TABLE funcionarios ADD COLUMN IF NOT EXISTS senha VARCHAR(255);
UPDATE funcionarios SET login = 'func' || id_funcionario WHERE login IS NULL;
ALTER TABLE funcionarios ALTER COLUMN login SET NOT NULL;
ALTER TABLE funcionarios ADD CONSTRAINT funcionarios_login_key UNIQUE (login);

-- Vendas: parcelas. A forma de pagamento passa a ser opcional na criacao,
-- porque a venda nasce antes da tela de pagamento.
ALTER TABLE vendas ADD COLUMN IF NOT EXISTS parcelas INT NOT NULL DEFAULT 1
  CHECK (parcelas BETWEEN 1 AND 12);
ALTER TABLE vendas ALTER COLUMN id_forma_pagamento DROP NOT NULL;

-- Mdicos: UF do CRM
ALTER TABLE medicos ADD COLUMN IF NOT EXISTS uf CHAR(2);

-- Receitas: ligacao com a venda, CPF do paciente e tipo da receita
ALTER TABLE receitas ADD COLUMN IF NOT EXISTS id_venda INT REFERENCES vendas(id_venda);
ALTER TABLE receitas ADD COLUMN IF NOT EXISTS cpf_paciente VARCHAR(14);
ALTER TABLE receitas ADD COLUMN IF NOT EXISTS tipo_receita VARCHAR(20)
  CHECK (tipo_receita IN ('branca','controle_especial','azul','amarela'));

UPDATE receitas r SET id_venda = iv.id_venda
  FROM itens_venda iv WHERE iv.id_item_venda = r.id_item_venda AND r.id_venda IS NULL;
UPDATE receitas r SET cpf_paciente = c.cpf
  FROM vendas v JOIN clientes c ON c.id_cliente = v.id_cliente
 WHERE v.id_venda = r.id_venda AND r.cpf_paciente IS NULL;
UPDATE receitas SET tipo_receita = 'branca' WHERE tipo_receita IS NULL;
ALTER TABLE receitas ALTER COLUMN id_venda SET NOT NULL;
ALTER TABLE receitas ALTER COLUMN tipo_receita SET NOT NULL;

-- =====================================================
-- INDICES NOVOS
-- =====================================================

-- Busca de produto pelo nome (tela de nova venda)
CREATE INDEX IF NOT EXISTS idx_samaraferreira_nome_produtos ON produtos (nome);

-- Busca das receitas de uma venda (nota fiscal e lista de receitas)
CREATE INDEX IF NOT EXISTS idx_samaraferreira_idvenda_receitas ON receitas (id_venda);

-- =====================================================
-- 2 FUNCTIONS
-- =====================================================

-- Estoque disponivel de um produto (soma dos lotes que n venceram)
CREATE OR REPLACE FUNCTION fn_estoque_produto(p_id_produto INT)
RETURNS INT
LANGUAGE sql STABLE SET search_path = samaraferreira_bd AS $$
  SELECT COALESCE(SUM(quantidade_atual), 0)::INT
    FROM lotes
   WHERE id_produto = p_id_produto
     AND data_validade >= CURRENT_DATE;
$$;

-- Diz se a venda tem item controlado (decide se abre a tela de receita)
CREATE OR REPLACE FUNCTION fn_venda_exige_receita(p_id_venda INT)
RETURNS BOOLEAN
LANGUAGE sql STABLE SET search_path = samaraferreira_bd AS $$
  SELECT EXISTS (
    SELECT 1
      FROM itens_venda iv
      JOIN produtos p ON p.id_produto = iv.id_produto
     WHERE iv.id_venda = p_id_venda
       AND p.tipo_controle <> 'livre'
  );
$$;

-- =====================================================
-- 2 PROCEDURES
-- =====================================================

-- Fecha a venda: calcula subtotal, desconto das promocoes ativas, total, forma de pagamento e parcelas
CREATE OR REPLACE PROCEDURE sp_finalizar_venda(
  p_id_venda INT, p_id_forma_pagamento INT, p_parcelas INT)
LANGUAGE plpgsql SET search_path = samaraferreira_bd AS $$
DECLARE
  v_subtotal NUMERIC;
  v_desconto NUMERIC;
BEGIN
  SELECT COALESCE(SUM(subtotal), 0) INTO v_subtotal
    FROM itens_venda WHERE id_venda = p_id_venda;

  IF v_subtotal = 0 THEN
    RAISE EXCEPTION 'A venda % não tem itens', p_id_venda;
  END IF;

  -- Item controlado sem receita nao pode fechar
  IF EXISTS (
    SELECT 1 FROM itens_venda iv
      JOIN produtos p ON p.id_produto = iv.id_produto
     WHERE iv.id_venda = p_id_venda
       AND p.tipo_controle <> 'livre'
       AND NOT EXISTS (SELECT 1 FROM receitas r WHERE r.id_item_venda = iv.id_item_venda)
  ) THEN
    RAISE EXCEPTION 'Há item controlado sem receita na venda %', p_id_venda;
  END IF;

  -- Desconto: maior promocao ativa de cada produto
  SELECT COALESCE(SUM(iv.subtotal * COALESCE((
           SELECT MAX(pr.percentual_desconto)
             FROM produtos_promocoes pp
             JOIN promocoes pr ON pr.id_promocao = pp.id_promocao
            WHERE pp.id_produto = iv.id_produto
              AND CURRENT_DATE BETWEEN pr.data_inicio AND pr.data_fim
         ), 0) / 100), 0)
    INTO v_desconto
    FROM itens_venda iv
   WHERE iv.id_venda = p_id_venda;

  UPDATE vendas
     SET valor_total = v_subtotal - v_desconto,
         desconto_aplicado = v_desconto,
         id_forma_pagamento = p_id_forma_pagamento,
         parcelas = p_parcelas
   WHERE id_venda = p_id_venda;
END; $$;

-- Compra / entrada de estoque: registra um lote novo
CREATE OR REPLACE PROCEDURE sp_registrar_compra_lote(
  p_id_produto INT, p_id_fornecedor INT, p_numero_lote VARCHAR,
  p_data_fabricacao DATE, p_data_validade DATE,
  p_quantidade INT, p_preco_custo NUMERIC)
LANGUAGE plpgsql SET search_path = samaraferreira_bd AS $$
BEGIN
  IF p_quantidade <= 0 THEN
    RAISE EXCEPTION 'Quantidade deve ser maior que zero';
  END IF;
  IF p_data_validade <= p_data_fabricacao THEN
    RAISE EXCEPTION 'A validade deve ser depois da fabricacao';
  END IF;

  INSERT INTO lotes (id_produto, id_fornecedor, numero_lote, data_fabricacao,
                     data_validade, quantidade_recebida, quantidade_atual, preco_custo)
  VALUES (p_id_produto, p_id_fornecedor, p_numero_lote, p_data_fabricacao,
          p_data_validade, p_quantidade, p_quantidade, p_preco_custo);
END; $$;

-- =====================================================
-- 2 TRIGGERS
-- =====================================================

-- Ao inserir item de venda: confere o lote e baixa o estoque dele
CREATE OR REPLACE FUNCTION trg_baixa_estoque_lote() RETURNS TRIGGER
LANGUAGE plpgsql SET search_path = samaraferreira_bd AS $$
DECLARE v_lote lotes%ROWTYPE;
BEGIN
  SELECT * INTO v_lote FROM lotes WHERE id_lote = NEW.id_lote FOR UPDATE;
  IF NOT FOUND THEN
    RAISE EXCEPTION 'Lote % não existe', NEW.id_lote;
  END IF;
  IF v_lote.id_produto <> NEW.id_produto THEN
    RAISE EXCEPTION 'O lote % não pertence ao produto %', NEW.id_lote, NEW.id_produto;
  END IF;
  IF v_lote.data_validade < CURRENT_DATE THEN
    RAISE EXCEPTION 'O lote % está vencido', v_lote.numero_lote;
  END IF;
  IF v_lote.quantidade_atual < NEW.quantidade THEN
    RAISE EXCEPTION 'Estoque insuficiente no lote % (disponível: %)',
      v_lote.numero_lote, v_lote.quantidade_atual;
  END IF;

  UPDATE lotes SET quantidade_atual = quantidade_atual - NEW.quantidade
   WHERE id_lote = NEW.id_lote;
  RETURN NEW;
END; $$;

DROP TRIGGER IF EXISTS tg_baixa_estoque_lote ON itens_venda;
CREATE TRIGGER tg_baixa_estoque_lote
BEFORE INSERT ON itens_venda
FOR EACH ROW EXECUTE FUNCTION trg_baixa_estoque_lote();

-- Ao inserir receita: so aceita para item controlado, data nao futura e preenche sozinho o id_venda
CREATE OR REPLACE FUNCTION trg_valida_receita() RETURNS TRIGGER
LANGUAGE plpgsql SET search_path = samaraferreira_bd AS $$
DECLARE
  v_controle VARCHAR;
  v_venda INT;
BEGIN
  SELECT p.tipo_controle, iv.id_venda INTO v_controle, v_venda
    FROM itens_venda iv
    JOIN produtos p ON p.id_produto = iv.id_produto
   WHERE iv.id_item_venda = NEW.id_item_venda;

  IF NOT FOUND THEN
    RAISE EXCEPTION 'Item de venda % não existe', NEW.id_item_venda;
  END IF;
  IF v_controle = 'livre' THEN
    RAISE EXCEPTION 'Este item não exige receita';
  END IF;
  IF NEW.data_emissao > CURRENT_DATE THEN
    RAISE EXCEPTION 'A data da receita não pode ser futura';
  END IF;

  NEW.id_venda := v_venda;
  RETURN NEW;
END; $$;

DROP TRIGGER IF EXISTS tg_valida_receita ON receitas;
CREATE TRIGGER tg_valida_receita
BEFORE INSERT ON receitas
FOR EACH ROW EXECUTE FUNCTION trg_valida_receita();