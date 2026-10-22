--
-- PostgreSQL database dump
--

\restrict Ks0CEj0xbtdeY5l7OYnbW7AZiAXJceIUwOatAcaOF5xZ0zaJbctwiIgFrA26mfC

-- Dumped from database version 18.6 (fd34222)
-- Dumped by pg_dump version 18.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: samaraferreira_bd; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA samaraferreira_bd;


--
-- Name: fn_estoque_produto(integer); Type: FUNCTION; Schema: samaraferreira_bd; Owner: -
--

CREATE FUNCTION samaraferreira_bd.fn_estoque_produto(p_id_produto integer) RETURNS integer
    LANGUAGE sql STABLE
    SET search_path TO 'samaraferreira_bd'
    AS $$
  SELECT COALESCE(SUM(quantidade_atual), 0)::INT
    FROM lotes
   WHERE id_produto = p_id_produto
     AND data_validade >= CURRENT_DATE;
$$;


--
-- Name: fn_venda_exige_receita(integer); Type: FUNCTION; Schema: samaraferreira_bd; Owner: -
--

CREATE FUNCTION samaraferreira_bd.fn_venda_exige_receita(p_id_venda integer) RETURNS boolean
    LANGUAGE sql STABLE
    SET search_path TO 'samaraferreira_bd'
    AS $$
  SELECT EXISTS (
    SELECT 1
      FROM itens_venda iv
      JOIN produtos p ON p.id_produto = iv.id_produto
     WHERE iv.id_venda = p_id_venda
       AND p.tipo_controle <> 'livre'
  );
$$;


--
-- Name: sp_finalizar_venda(integer, integer, integer); Type: PROCEDURE; Schema: samaraferreira_bd; Owner: -
--

CREATE PROCEDURE samaraferreira_bd.sp_finalizar_venda(IN p_id_venda integer, IN p_id_forma_pagamento integer, IN p_parcelas integer)
    LANGUAGE plpgsql
    SET search_path TO 'samaraferreira_bd'
    AS $$
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


--
-- Name: sp_registrar_compra_lote(integer, integer, character varying, date, date, integer, numeric); Type: PROCEDURE; Schema: samaraferreira_bd; Owner: -
--

CREATE PROCEDURE samaraferreira_bd.sp_registrar_compra_lote(IN p_id_produto integer, IN p_id_fornecedor integer, IN p_numero_lote character varying, IN p_data_fabricacao date, IN p_data_validade date, IN p_quantidade integer, IN p_preco_custo numeric)
    LANGUAGE plpgsql
    SET search_path TO 'samaraferreira_bd'
    AS $$
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


--
-- Name: trg_baixa_estoque_lote(); Type: FUNCTION; Schema: samaraferreira_bd; Owner: -
--

CREATE FUNCTION samaraferreira_bd.trg_baixa_estoque_lote() RETURNS trigger
    LANGUAGE plpgsql
    SET search_path TO 'samaraferreira_bd'
    AS $$
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


--
-- Name: trg_valida_receita(); Type: FUNCTION; Schema: samaraferreira_bd; Owner: -
--

CREATE FUNCTION samaraferreira_bd.trg_valida_receita() RETURNS trigger
    LANGUAGE plpgsql
    SET search_path TO 'samaraferreira_bd'
    AS $$
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


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: avaliacoes; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.avaliacoes (
    id_avaliacao integer NOT NULL,
    id_cliente integer NOT NULL,
    id_produto integer NOT NULL,
    nota smallint NOT NULL,
    comentario text,
    data_avaliacao date DEFAULT CURRENT_DATE,
    CONSTRAINT avaliacoes_nota_check CHECK (((nota >= 1) AND (nota <= 5)))
);


--
-- Name: avaliacoes_id_avaliacao_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.avaliacoes_id_avaliacao_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: avaliacoes_id_avaliacao_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.avaliacoes_id_avaliacao_seq OWNED BY samaraferreira_bd.avaliacoes.id_avaliacao;


--
-- Name: categorias; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.categorias (
    id_categoria integer NOT NULL,
    nome character varying(100) NOT NULL,
    descricao text
);


--
-- Name: categorias_id_categoria_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.categorias_id_categoria_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: categorias_id_categoria_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.categorias_id_categoria_seq OWNED BY samaraferreira_bd.categorias.id_categoria;


--
-- Name: clientes; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.clientes (
    id_cliente integer NOT NULL,
    nome character varying(150) NOT NULL,
    cpf character varying(14) NOT NULL,
    data_nascimento date,
    telefone character varying(20),
    email character varying(120),
    programa_fidelidade boolean DEFAULT false,
    pontos_fidelidade integer DEFAULT 0,
    data_cadastro date DEFAULT CURRENT_DATE
);


--
-- Name: clientes_id_cliente_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.clientes_id_cliente_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: clientes_id_cliente_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.clientes_id_cliente_seq OWNED BY samaraferreira_bd.clientes.id_cliente;


--
-- Name: devolucoes; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.devolucoes (
    id_devolucao integer NOT NULL,
    id_item_venda integer NOT NULL,
    motivo character varying(150),
    data_devolucao date DEFAULT CURRENT_DATE,
    status character varying(20),
    valor_devolvido numeric(10,2),
    CONSTRAINT devolucoes_status_check CHECK (((status)::text = ANY ((ARRAY['solicitada'::character varying, 'aprovada'::character varying, 'rejeitada'::character varying, 'concluida'::character varying])::text[])))
);


--
-- Name: devolucoes_id_devolucao_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.devolucoes_id_devolucao_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: devolucoes_id_devolucao_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.devolucoes_id_devolucao_seq OWNED BY samaraferreira_bd.devolucoes.id_devolucao;


--
-- Name: enderecos; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.enderecos (
    id_endereco integer NOT NULL,
    id_cliente integer NOT NULL,
    tipo character varying(20),
    logradouro character varying(150) NOT NULL,
    numero character varying(10),
    complemento character varying(60),
    bairro character varying(80),
    cidade character varying(80) NOT NULL,
    estado character(2) NOT NULL,
    cep character varying(9),
    CONSTRAINT enderecos_tipo_check CHECK (((tipo)::text = ANY ((ARRAY['residencial'::character varying, 'cobranca'::character varying, 'entrega'::character varying])::text[])))
);


--
-- Name: enderecos_id_endereco_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.enderecos_id_endereco_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: enderecos_id_endereco_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.enderecos_id_endereco_seq OWNED BY samaraferreira_bd.enderecos.id_endereco;


--
-- Name: fabricantes; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.fabricantes (
    id_fabricante integer NOT NULL,
    nome character varying(150) NOT NULL,
    cnpj character varying(18),
    pais_origem character varying(60),
    telefone character varying(20),
    email character varying(120)
);


--
-- Name: fabricantes_id_fabricante_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.fabricantes_id_fabricante_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fabricantes_id_fabricante_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.fabricantes_id_fabricante_seq OWNED BY samaraferreira_bd.fabricantes.id_fabricante;


--
-- Name: formas_pagamento; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.formas_pagamento (
    id_forma_pagamento integer NOT NULL,
    descricao character varying(50) NOT NULL,
    taxa_percentual numeric(5,2) DEFAULT 0
);


--
-- Name: formas_pagamento_id_forma_pagamento_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.formas_pagamento_id_forma_pagamento_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: formas_pagamento_id_forma_pagamento_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.formas_pagamento_id_forma_pagamento_seq OWNED BY samaraferreira_bd.formas_pagamento.id_forma_pagamento;


--
-- Name: fornecedores; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.fornecedores (
    id_fornecedor integer NOT NULL,
    nome character varying(150) NOT NULL,
    cnpj character varying(18),
    telefone character varying(20),
    email character varying(120),
    prazo_entrega_dias integer,
    CONSTRAINT fornecedores_prazo_entrega_dias_check CHECK ((prazo_entrega_dias >= 0))
);


--
-- Name: fornecedores_id_fornecedor_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.fornecedores_id_fornecedor_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fornecedores_id_fornecedor_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.fornecedores_id_fornecedor_seq OWNED BY samaraferreira_bd.fornecedores.id_fornecedor;


--
-- Name: funcionarios; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.funcionarios (
    id_funcionario integer NOT NULL,
    nome character varying(150) NOT NULL,
    cpf character varying(14) NOT NULL,
    cargo character varying(60) NOT NULL,
    e_responsavel_tecnico boolean DEFAULT false,
    data_admissao date NOT NULL,
    salario numeric(10,2) NOT NULL,
    telefone character varying(20),
    email character varying(120),
    id_supervisor integer,
    login character varying(50) NOT NULL,
    senha character varying(255)
);


--
-- Name: funcionarios_id_funcionario_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.funcionarios_id_funcionario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: funcionarios_id_funcionario_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.funcionarios_id_funcionario_seq OWNED BY samaraferreira_bd.funcionarios.id_funcionario;


--
-- Name: itens_venda; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.itens_venda (
    id_item_venda integer NOT NULL,
    id_venda integer NOT NULL,
    id_produto integer NOT NULL,
    id_lote integer NOT NULL,
    quantidade integer NOT NULL,
    preco_unitario_venda numeric(10,2) NOT NULL,
    subtotal numeric(10,2) GENERATED ALWAYS AS (((quantidade)::numeric * preco_unitario_venda)) STORED,
    CONSTRAINT itens_venda_quantidade_check CHECK ((quantidade > 0))
);


--
-- Name: itens_venda_id_item_venda_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.itens_venda_id_item_venda_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: itens_venda_id_item_venda_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.itens_venda_id_item_venda_seq OWNED BY samaraferreira_bd.itens_venda.id_item_venda;


--
-- Name: lotes; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.lotes (
    id_lote integer NOT NULL,
    id_produto integer NOT NULL,
    id_fornecedor integer NOT NULL,
    numero_lote character varying(30) NOT NULL,
    data_fabricacao date NOT NULL,
    data_validade date NOT NULL,
    quantidade_recebida integer NOT NULL,
    quantidade_atual integer NOT NULL,
    preco_custo numeric(10,2) NOT NULL,
    CONSTRAINT lotes_quantidade_atual_check CHECK ((quantidade_atual >= 0)),
    CONSTRAINT lotes_quantidade_recebida_check CHECK ((quantidade_recebida >= 0))
);


--
-- Name: lotes_id_lote_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.lotes_id_lote_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: lotes_id_lote_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.lotes_id_lote_seq OWNED BY samaraferreira_bd.lotes.id_lote;


--
-- Name: medicos; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.medicos (
    id_medico integer NOT NULL,
    nome character varying(150) NOT NULL,
    crm character varying(20) NOT NULL,
    especialidade character varying(80),
    telefone character varying(20),
    uf character(2)
);


--
-- Name: medicos_id_medico_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.medicos_id_medico_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: medicos_id_medico_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.medicos_id_medico_seq OWNED BY samaraferreira_bd.medicos.id_medico;


--
-- Name: produtos; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.produtos (
    id_produto integer NOT NULL,
    nome character varying(150) NOT NULL,
    descricao text,
    id_categoria integer NOT NULL,
    id_fabricante integer NOT NULL,
    principio_ativo character varying(150),
    forma_farmaceutica character varying(50),
    exige_receita boolean DEFAULT false,
    preco_unitario numeric(10,2) NOT NULL,
    unidade_medida character varying(20),
    codigo_barras character varying(20),
    foto character varying(255),
    tipo_controle character varying(20) DEFAULT 'livre'::character varying NOT NULL,
    CONSTRAINT produtos_preco_unitario_check CHECK ((preco_unitario >= (0)::numeric)),
    CONSTRAINT produtos_tipo_controle_check CHECK (((tipo_controle)::text = ANY ((ARRAY['livre'::character varying, 'antibiotico'::character varying, 'tarja_vermelha'::character varying, 'tarja_preta'::character varying])::text[])))
);


--
-- Name: produtos_id_produto_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.produtos_id_produto_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: produtos_id_produto_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.produtos_id_produto_seq OWNED BY samaraferreira_bd.produtos.id_produto;


--
-- Name: produtos_promocoes; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.produtos_promocoes (
    id_produto integer NOT NULL,
    id_promocao integer NOT NULL
);


--
-- Name: promocoes; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.promocoes (
    id_promocao integer NOT NULL,
    nome character varying(100) NOT NULL,
    descricao text,
    id_categoria integer,
    data_inicio date NOT NULL,
    data_fim date NOT NULL,
    percentual_desconto numeric(5,2),
    CONSTRAINT promocoes_percentual_desconto_check CHECK (((percentual_desconto >= (0)::numeric) AND (percentual_desconto <= (100)::numeric)))
);


--
-- Name: promocoes_id_promocao_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.promocoes_id_promocao_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: promocoes_id_promocao_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.promocoes_id_promocao_seq OWNED BY samaraferreira_bd.promocoes.id_promocao;


--
-- Name: receitas; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.receitas (
    id_receita integer NOT NULL,
    id_item_venda integer NOT NULL,
    id_medico integer NOT NULL,
    numero_receita character varying(30),
    data_emissao date NOT NULL,
    validade_receita date,
    id_venda integer NOT NULL,
    cpf_paciente character varying(14),
    tipo_receita character varying(20) NOT NULL,
    CONSTRAINT receitas_tipo_receita_check CHECK (((tipo_receita)::text = ANY ((ARRAY['branca'::character varying, 'controle_especial'::character varying, 'azul'::character varying, 'amarela'::character varying])::text[])))
);


--
-- Name: receitas_id_receita_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.receitas_id_receita_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: receitas_id_receita_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.receitas_id_receita_seq OWNED BY samaraferreira_bd.receitas.id_receita;


--
-- Name: vendas; Type: TABLE; Schema: samaraferreira_bd; Owner: -
--

CREATE TABLE samaraferreira_bd.vendas (
    id_venda integer NOT NULL,
    id_cliente integer,
    id_funcionario integer NOT NULL,
    id_forma_pagamento integer,
    data_venda timestamp without time zone DEFAULT now() NOT NULL,
    valor_total numeric(10,2) DEFAULT 0 NOT NULL,
    desconto_aplicado numeric(10,2) DEFAULT 0,
    parcelas integer DEFAULT 1 NOT NULL,
    CONSTRAINT vendas_parcelas_check CHECK (((parcelas >= 1) AND (parcelas <= 12)))
);


--
-- Name: vendas_id_venda_seq; Type: SEQUENCE; Schema: samaraferreira_bd; Owner: -
--

CREATE SEQUENCE samaraferreira_bd.vendas_id_venda_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: vendas_id_venda_seq; Type: SEQUENCE OWNED BY; Schema: samaraferreira_bd; Owner: -
--

ALTER SEQUENCE samaraferreira_bd.vendas_id_venda_seq OWNED BY samaraferreira_bd.vendas.id_venda;


--
-- Name: vw_samaraferreira_clientes_medicamentos_controlados; Type: VIEW; Schema: samaraferreira_bd; Owner: -
--

CREATE VIEW samaraferreira_bd.vw_samaraferreira_clientes_medicamentos_controlados AS
 SELECT cl.nome AS cliente,
    cl.telefone,
    count(DISTINCT iv.id_item_venda) AS itens_controlados_comprados,
    count(DISTINCT r.id_medico) AS medicos_distintos_envolvidos
   FROM ((((samaraferreira_bd.clientes cl
     JOIN samaraferreira_bd.vendas v ON ((v.id_cliente = cl.id_cliente)))
     JOIN samaraferreira_bd.itens_venda iv ON ((iv.id_venda = v.id_venda)))
     JOIN samaraferreira_bd.produtos p ON (((p.id_produto = iv.id_produto) AND (p.exige_receita = true))))
     JOIN samaraferreira_bd.receitas r ON ((r.id_item_venda = iv.id_item_venda)))
  GROUP BY cl.nome, cl.telefone
 HAVING (count(DISTINCT iv.id_item_venda) > 1)
  ORDER BY (count(DISTINCT iv.id_item_venda)) DESC;


--
-- Name: vw_samaraferreira_efetividade_promocoes; Type: VIEW; Schema: samaraferreira_bd; Owner: -
--

CREATE VIEW samaraferreira_bd.vw_samaraferreira_efetividade_promocoes AS
 SELECT nome AS promocao,
    percentual_desconto,
    data_inicio,
    data_fim,
    ( SELECT COALESCE(sum(iv.quantidade), (0)::bigint) AS "coalesce"
           FROM ((samaraferreira_bd.itens_venda iv
             JOIN samaraferreira_bd.vendas v ON ((v.id_venda = iv.id_venda)))
             JOIN samaraferreira_bd.produtos_promocoes pp ON (((pp.id_produto = iv.id_produto) AND (pp.id_promocao = pr.id_promocao))))
          WHERE (((v.data_venda)::date >= pr.data_inicio) AND ((v.data_venda)::date <= pr.data_fim))) AS quantidade_vendida_durante_promocao,
    ( SELECT COALESCE(sum(iv.quantidade), (0)::bigint) AS "coalesce"
           FROM ((samaraferreira_bd.itens_venda iv
             JOIN samaraferreira_bd.vendas v ON ((v.id_venda = iv.id_venda)))
             JOIN samaraferreira_bd.produtos_promocoes pp ON (((pp.id_produto = iv.id_produto) AND (pp.id_promocao = pr.id_promocao))))
          WHERE (((v.data_venda)::date < pr.data_inicio) OR ((v.data_venda)::date > pr.data_fim))) AS quantidade_vendida_fora_promocao
   FROM samaraferreira_bd.promocoes pr
  ORDER BY ( SELECT COALESCE(sum(iv.quantidade), (0)::bigint) AS "coalesce"
           FROM ((samaraferreira_bd.itens_venda iv
             JOIN samaraferreira_bd.vendas v ON ((v.id_venda = iv.id_venda)))
             JOIN samaraferreira_bd.produtos_promocoes pp ON (((pp.id_produto = iv.id_produto) AND (pp.id_promocao = pr.id_promocao))))
          WHERE (((v.data_venda)::date >= pr.data_inicio) AND ((v.data_venda)::date <= pr.data_fim))) DESC;


--
-- Name: vw_samaraferreira_faturamento_por_funcionario; Type: MATERIALIZED VIEW; Schema: samaraferreira_bd; Owner: -
--

CREATE MATERIALIZED VIEW samaraferreira_bd.vw_samaraferreira_faturamento_por_funcionario AS
 SELECT f.nome AS funcionario,
    f.cargo,
    count(DISTINCT v.id_venda) AS total_vendas,
    sum(v.valor_total) AS faturamento_total,
    round(avg(v.valor_total), 2) AS ticket_medio
   FROM (samaraferreira_bd.vendas v
     JOIN samaraferreira_bd.funcionarios f ON ((f.id_funcionario = v.id_funcionario)))
  GROUP BY f.nome, f.cargo
  ORDER BY (sum(v.valor_total)) DESC
  WITH NO DATA;


--
-- Name: vw_samaraferreira_lotes_proximos_vencimento; Type: VIEW; Schema: samaraferreira_bd; Owner: -
--

CREATE VIEW samaraferreira_bd.vw_samaraferreira_lotes_proximos_vencimento AS
 SELECT p.nome AS produto,
    l.numero_lote,
    l.data_validade,
    l.quantidade_atual,
    fo.nome AS fornecedor,
    (l.data_validade - CURRENT_DATE) AS dias_para_vencer
   FROM ((samaraferreira_bd.lotes l
     JOIN samaraferreira_bd.produtos p ON ((p.id_produto = l.id_produto)))
     JOIN samaraferreira_bd.fornecedores fo ON ((fo.id_fornecedor = l.id_fornecedor)))
  WHERE ((l.quantidade_atual > 0) AND (l.data_validade <= (CURRENT_DATE + '60 days'::interval)) AND (l.id_produto IN ( SELECT DISTINCT itens_venda.id_produto
           FROM samaraferreira_bd.itens_venda)))
  ORDER BY l.data_validade;


--
-- Name: vw_samaraferreira_produtos_mais_vendidos_por_categoria; Type: VIEW; Schema: samaraferreira_bd; Owner: -
--

CREATE VIEW samaraferreira_bd.vw_samaraferreira_produtos_mais_vendidos_por_categoria AS
 SELECT c.nome AS categoria,
    p.nome AS produto,
    sum(iv.quantidade) AS quantidade_vendida,
    sum(iv.subtotal) AS valor_total_vendido
   FROM ((samaraferreira_bd.itens_venda iv
     JOIN samaraferreira_bd.produtos p ON ((p.id_produto = iv.id_produto)))
     JOIN samaraferreira_bd.categorias c ON ((c.id_categoria = p.id_categoria)))
  GROUP BY c.nome, p.nome
  ORDER BY c.nome, (sum(iv.quantidade)) DESC;


--
-- Name: avaliacoes id_avaliacao; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.avaliacoes ALTER COLUMN id_avaliacao SET DEFAULT nextval('samaraferreira_bd.avaliacoes_id_avaliacao_seq'::regclass);


--
-- Name: categorias id_categoria; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.categorias ALTER COLUMN id_categoria SET DEFAULT nextval('samaraferreira_bd.categorias_id_categoria_seq'::regclass);


--
-- Name: clientes id_cliente; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.clientes ALTER COLUMN id_cliente SET DEFAULT nextval('samaraferreira_bd.clientes_id_cliente_seq'::regclass);


--
-- Name: devolucoes id_devolucao; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.devolucoes ALTER COLUMN id_devolucao SET DEFAULT nextval('samaraferreira_bd.devolucoes_id_devolucao_seq'::regclass);


--
-- Name: enderecos id_endereco; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.enderecos ALTER COLUMN id_endereco SET DEFAULT nextval('samaraferreira_bd.enderecos_id_endereco_seq'::regclass);


--
-- Name: fabricantes id_fabricante; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.fabricantes ALTER COLUMN id_fabricante SET DEFAULT nextval('samaraferreira_bd.fabricantes_id_fabricante_seq'::regclass);


--
-- Name: formas_pagamento id_forma_pagamento; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.formas_pagamento ALTER COLUMN id_forma_pagamento SET DEFAULT nextval('samaraferreira_bd.formas_pagamento_id_forma_pagamento_seq'::regclass);


--
-- Name: fornecedores id_fornecedor; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.fornecedores ALTER COLUMN id_fornecedor SET DEFAULT nextval('samaraferreira_bd.fornecedores_id_fornecedor_seq'::regclass);


--
-- Name: funcionarios id_funcionario; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.funcionarios ALTER COLUMN id_funcionario SET DEFAULT nextval('samaraferreira_bd.funcionarios_id_funcionario_seq'::regclass);


--
-- Name: itens_venda id_item_venda; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.itens_venda ALTER COLUMN id_item_venda SET DEFAULT nextval('samaraferreira_bd.itens_venda_id_item_venda_seq'::regclass);


--
-- Name: lotes id_lote; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.lotes ALTER COLUMN id_lote SET DEFAULT nextval('samaraferreira_bd.lotes_id_lote_seq'::regclass);


--
-- Name: medicos id_medico; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.medicos ALTER COLUMN id_medico SET DEFAULT nextval('samaraferreira_bd.medicos_id_medico_seq'::regclass);


--
-- Name: produtos id_produto; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.produtos ALTER COLUMN id_produto SET DEFAULT nextval('samaraferreira_bd.produtos_id_produto_seq'::regclass);


--
-- Name: promocoes id_promocao; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.promocoes ALTER COLUMN id_promocao SET DEFAULT nextval('samaraferreira_bd.promocoes_id_promocao_seq'::regclass);


--
-- Name: receitas id_receita; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.receitas ALTER COLUMN id_receita SET DEFAULT nextval('samaraferreira_bd.receitas_id_receita_seq'::regclass);


--
-- Name: vendas id_venda; Type: DEFAULT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.vendas ALTER COLUMN id_venda SET DEFAULT nextval('samaraferreira_bd.vendas_id_venda_seq'::regclass);


--
-- Name: avaliacoes avaliacoes_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.avaliacoes
    ADD CONSTRAINT avaliacoes_pkey PRIMARY KEY (id_avaliacao);


--
-- Name: categorias categorias_nome_key; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.categorias
    ADD CONSTRAINT categorias_nome_key UNIQUE (nome);


--
-- Name: categorias categorias_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (id_categoria);


--
-- Name: clientes clientes_cpf_key; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.clientes
    ADD CONSTRAINT clientes_cpf_key UNIQUE (cpf);


--
-- Name: clientes clientes_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.clientes
    ADD CONSTRAINT clientes_pkey PRIMARY KEY (id_cliente);


--
-- Name: devolucoes devolucoes_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.devolucoes
    ADD CONSTRAINT devolucoes_pkey PRIMARY KEY (id_devolucao);


--
-- Name: enderecos enderecos_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.enderecos
    ADD CONSTRAINT enderecos_pkey PRIMARY KEY (id_endereco);


--
-- Name: fabricantes fabricantes_cnpj_key; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.fabricantes
    ADD CONSTRAINT fabricantes_cnpj_key UNIQUE (cnpj);


--
-- Name: fabricantes fabricantes_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.fabricantes
    ADD CONSTRAINT fabricantes_pkey PRIMARY KEY (id_fabricante);


--
-- Name: formas_pagamento formas_pagamento_descricao_key; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.formas_pagamento
    ADD CONSTRAINT formas_pagamento_descricao_key UNIQUE (descricao);


--
-- Name: formas_pagamento formas_pagamento_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.formas_pagamento
    ADD CONSTRAINT formas_pagamento_pkey PRIMARY KEY (id_forma_pagamento);


--
-- Name: fornecedores fornecedores_cnpj_key; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.fornecedores
    ADD CONSTRAINT fornecedores_cnpj_key UNIQUE (cnpj);


--
-- Name: fornecedores fornecedores_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.fornecedores
    ADD CONSTRAINT fornecedores_pkey PRIMARY KEY (id_fornecedor);


--
-- Name: funcionarios funcionarios_cpf_key; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.funcionarios
    ADD CONSTRAINT funcionarios_cpf_key UNIQUE (cpf);


--
-- Name: funcionarios funcionarios_login_key; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.funcionarios
    ADD CONSTRAINT funcionarios_login_key UNIQUE (login);


--
-- Name: funcionarios funcionarios_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.funcionarios
    ADD CONSTRAINT funcionarios_pkey PRIMARY KEY (id_funcionario);


--
-- Name: itens_venda itens_venda_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.itens_venda
    ADD CONSTRAINT itens_venda_pkey PRIMARY KEY (id_item_venda);


--
-- Name: lotes lotes_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.lotes
    ADD CONSTRAINT lotes_pkey PRIMARY KEY (id_lote);


--
-- Name: medicos medicos_crm_key; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.medicos
    ADD CONSTRAINT medicos_crm_key UNIQUE (crm);


--
-- Name: medicos medicos_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.medicos
    ADD CONSTRAINT medicos_pkey PRIMARY KEY (id_medico);


--
-- Name: produtos produtos_codigo_barras_key; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.produtos
    ADD CONSTRAINT produtos_codigo_barras_key UNIQUE (codigo_barras);


--
-- Name: produtos produtos_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.produtos
    ADD CONSTRAINT produtos_pkey PRIMARY KEY (id_produto);


--
-- Name: produtos_promocoes produtos_promocoes_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.produtos_promocoes
    ADD CONSTRAINT produtos_promocoes_pkey PRIMARY KEY (id_produto, id_promocao);


--
-- Name: promocoes promocoes_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.promocoes
    ADD CONSTRAINT promocoes_pkey PRIMARY KEY (id_promocao);


--
-- Name: receitas receitas_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.receitas
    ADD CONSTRAINT receitas_pkey PRIMARY KEY (id_receita);


--
-- Name: vendas vendas_pkey; Type: CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.vendas
    ADD CONSTRAINT vendas_pkey PRIMARY KEY (id_venda);


--
-- Name: idx_samaraferreira_datavalidade_lotes; Type: INDEX; Schema: samaraferreira_bd; Owner: -
--

CREATE INDEX idx_samaraferreira_datavalidade_lotes ON samaraferreira_bd.lotes USING btree (data_validade);


--
-- Name: idx_samaraferreira_idproduto_itensvenda; Type: INDEX; Schema: samaraferreira_bd; Owner: -
--

CREATE INDEX idx_samaraferreira_idproduto_itensvenda ON samaraferreira_bd.itens_venda USING btree (id_produto);


--
-- Name: idx_samaraferreira_idvenda_receitas; Type: INDEX; Schema: samaraferreira_bd; Owner: -
--

CREATE INDEX idx_samaraferreira_idvenda_receitas ON samaraferreira_bd.receitas USING btree (id_venda);


--
-- Name: idx_samaraferreira_nome_produtos; Type: INDEX; Schema: samaraferreira_bd; Owner: -
--

CREATE INDEX idx_samaraferreira_nome_produtos ON samaraferreira_bd.produtos USING btree (nome);


--
-- Name: itens_venda tg_baixa_estoque_lote; Type: TRIGGER; Schema: samaraferreira_bd; Owner: -
--

CREATE TRIGGER tg_baixa_estoque_lote BEFORE INSERT ON samaraferreira_bd.itens_venda FOR EACH ROW EXECUTE FUNCTION samaraferreira_bd.trg_baixa_estoque_lote();


--
-- Name: receitas tg_valida_receita; Type: TRIGGER; Schema: samaraferreira_bd; Owner: -
--

CREATE TRIGGER tg_valida_receita BEFORE INSERT ON samaraferreira_bd.receitas FOR EACH ROW EXECUTE FUNCTION samaraferreira_bd.trg_valida_receita();


--
-- Name: avaliacoes avaliacoes_id_cliente_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.avaliacoes
    ADD CONSTRAINT avaliacoes_id_cliente_fkey FOREIGN KEY (id_cliente) REFERENCES samaraferreira_bd.clientes(id_cliente);


--
-- Name: avaliacoes avaliacoes_id_produto_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.avaliacoes
    ADD CONSTRAINT avaliacoes_id_produto_fkey FOREIGN KEY (id_produto) REFERENCES samaraferreira_bd.produtos(id_produto);


--
-- Name: devolucoes devolucoes_id_item_venda_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.devolucoes
    ADD CONSTRAINT devolucoes_id_item_venda_fkey FOREIGN KEY (id_item_venda) REFERENCES samaraferreira_bd.itens_venda(id_item_venda);


--
-- Name: enderecos enderecos_id_cliente_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.enderecos
    ADD CONSTRAINT enderecos_id_cliente_fkey FOREIGN KEY (id_cliente) REFERENCES samaraferreira_bd.clientes(id_cliente);


--
-- Name: funcionarios funcionarios_id_supervisor_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.funcionarios
    ADD CONSTRAINT funcionarios_id_supervisor_fkey FOREIGN KEY (id_supervisor) REFERENCES samaraferreira_bd.funcionarios(id_funcionario);


--
-- Name: itens_venda itens_venda_id_lote_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.itens_venda
    ADD CONSTRAINT itens_venda_id_lote_fkey FOREIGN KEY (id_lote) REFERENCES samaraferreira_bd.lotes(id_lote);


--
-- Name: itens_venda itens_venda_id_produto_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.itens_venda
    ADD CONSTRAINT itens_venda_id_produto_fkey FOREIGN KEY (id_produto) REFERENCES samaraferreira_bd.produtos(id_produto);


--
-- Name: itens_venda itens_venda_id_venda_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.itens_venda
    ADD CONSTRAINT itens_venda_id_venda_fkey FOREIGN KEY (id_venda) REFERENCES samaraferreira_bd.vendas(id_venda);


--
-- Name: lotes lotes_id_fornecedor_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.lotes
    ADD CONSTRAINT lotes_id_fornecedor_fkey FOREIGN KEY (id_fornecedor) REFERENCES samaraferreira_bd.fornecedores(id_fornecedor);


--
-- Name: lotes lotes_id_produto_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.lotes
    ADD CONSTRAINT lotes_id_produto_fkey FOREIGN KEY (id_produto) REFERENCES samaraferreira_bd.produtos(id_produto);


--
-- Name: produtos produtos_id_categoria_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.produtos
    ADD CONSTRAINT produtos_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES samaraferreira_bd.categorias(id_categoria);


--
-- Name: produtos produtos_id_fabricante_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.produtos
    ADD CONSTRAINT produtos_id_fabricante_fkey FOREIGN KEY (id_fabricante) REFERENCES samaraferreira_bd.fabricantes(id_fabricante);


--
-- Name: produtos_promocoes produtos_promocoes_id_produto_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.produtos_promocoes
    ADD CONSTRAINT produtos_promocoes_id_produto_fkey FOREIGN KEY (id_produto) REFERENCES samaraferreira_bd.produtos(id_produto);


--
-- Name: produtos_promocoes produtos_promocoes_id_promocao_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.produtos_promocoes
    ADD CONSTRAINT produtos_promocoes_id_promocao_fkey FOREIGN KEY (id_promocao) REFERENCES samaraferreira_bd.promocoes(id_promocao);


--
-- Name: promocoes promocoes_id_categoria_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.promocoes
    ADD CONSTRAINT promocoes_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES samaraferreira_bd.categorias(id_categoria);


--
-- Name: receitas receitas_id_item_venda_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.receitas
    ADD CONSTRAINT receitas_id_item_venda_fkey FOREIGN KEY (id_item_venda) REFERENCES samaraferreira_bd.itens_venda(id_item_venda);


--
-- Name: receitas receitas_id_medico_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.receitas
    ADD CONSTRAINT receitas_id_medico_fkey FOREIGN KEY (id_medico) REFERENCES samaraferreira_bd.medicos(id_medico);


--
-- Name: receitas receitas_id_venda_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.receitas
    ADD CONSTRAINT receitas_id_venda_fkey FOREIGN KEY (id_venda) REFERENCES samaraferreira_bd.vendas(id_venda);


--
-- Name: vendas vendas_id_cliente_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.vendas
    ADD CONSTRAINT vendas_id_cliente_fkey FOREIGN KEY (id_cliente) REFERENCES samaraferreira_bd.clientes(id_cliente);


--
-- Name: vendas vendas_id_forma_pagamento_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.vendas
    ADD CONSTRAINT vendas_id_forma_pagamento_fkey FOREIGN KEY (id_forma_pagamento) REFERENCES samaraferreira_bd.formas_pagamento(id_forma_pagamento);


--
-- Name: vendas vendas_id_funcionario_fkey; Type: FK CONSTRAINT; Schema: samaraferreira_bd; Owner: -
--

ALTER TABLE ONLY samaraferreira_bd.vendas
    ADD CONSTRAINT vendas_id_funcionario_fkey FOREIGN KEY (id_funcionario) REFERENCES samaraferreira_bd.funcionarios(id_funcionario);


--
-- PostgreSQL database dump complete
--

\unrestrict Ks0CEj0xbtdeY5l7OYnbW7AZiAXJceIUwOatAcaOF5xZ0zaJbctwiIgFrA26mfC

