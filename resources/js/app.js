const dinheiro = valor =>
    Number(valor || 0).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });

const lerVenda = () => {
    try {
        return JSON.parse(sessionStorage.getItem('farmacia_venda') || 'null');
    } catch {
        return null;
    }
};

const salvarVenda = venda => {
    sessionStorage.setItem('farmacia_venda', JSON.stringify(venda));
};

async function requisicao(url, metodo = 'GET', dados = null) {
    const opcoes = {
        method: metodo,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    };

    if (dados) {
        opcoes.body = JSON.stringify(dados);
    }

    const resposta = await fetch(url, opcoes);
    const resultado = await resposta.json().catch(() => ({}));

    if (!resposta.ok) {
        const erros = resultado.errors
            ? Object.values(resultado.errors).flat().join(' ')
            : resultado.message;

        throw new Error(erros || 'Não foi possível realizar a operação.');
    }

    return resultado;
}

// TELA DE VENDA
const paginaVenda = document.querySelector('#pagina-venda');

if (paginaVenda) {
    const apiClientes = paginaVenda.dataset.apiClientes;
    const apiProdutos = paginaVenda.dataset.apiProdutos;
    const apiVendas = paginaVenda.dataset.apiVendas;

    let cliente = null;
    let produtosEncontrados = [];
    let venda = lerVenda();

    const mensagem = document.querySelector('#mensagem-venda');
    const seletor = document.querySelector('#resultado-produtos');

    function avisar(texto, erro = false) {
        mensagem.textContent = texto;
        mensagem.style.color = erro ? '#b42318' : '#16803c';
    }

    function atualizarTela() {
        if (!venda) return;

        document.querySelector('#numero-venda').textContent =
            `#${venda.id_venda}`;

        document.querySelector('#resultado-cliente').textContent =
            venda.clienteNome
                ? `Cliente selecionado: ${venda.clienteNome}`
                : 'Venda sem cliente identificado.';

        const corpo = document.querySelector('#itens-venda');
        corpo.innerHTML = '';

        if (!venda.itens || venda.itens.length === 0) {
            corpo.innerHTML =
                '<tr class="empty-row"><td colspan="5">Nenhum produto adicionado.</td></tr>';
        } else {
            venda.itens.forEach(item => {
                const linha = document.createElement('tr');

                [
                    item.nome,
                    item.lote,
                    item.quantidade,
                    dinheiro(item.preco),
                    dinheiro(item.preco * item.quantidade)
                ].forEach(valor => {
                    const celula = document.createElement('td');
                    celula.textContent = valor;
                    linha.appendChild(celula);
                });

                corpo.appendChild(linha);
            });
        }

        const subtotal = (venda.itens || []).reduce(
            (soma, item) => soma + item.preco * item.quantidade, 0
        );

        document.querySelector('#subtotal-venda').textContent = dinheiro(subtotal);
        document.querySelector('#total-venda').textContent = dinheiro(subtotal);

        const link = document.querySelector('#ir-pagamento');
        const temItens = venda.itens && venda.itens.length > 0;

        link.style.pointerEvents = temItens ? 'auto' : 'none';
        link.style.opacity = temItens ? '1' : '.5';
    }

    document.querySelector('#buscar-cliente').addEventListener('click', async () => {
        const cpf = document.querySelector('#cpf').value.trim();

        if (!cpf) {
            avisar('Digite o CPF do cliente.', true);
            return;
        }

        try {
            const resultado = await requisicao(
                `${apiClientes}?cpf=${encodeURIComponent(cpf)}`
            );

            cliente = resultado.data;

            if (venda) {
                venda.id_cliente = cliente.id_cliente;
                venda.clienteNome = cliente.nome;
                salvarVenda(venda);
            }

            document.querySelector('#resultado-cliente').textContent =
                `Cliente encontrado: ${cliente.nome}`;

            avisar('Cliente localizado.');
        } catch (erro) {
            cliente = null;
            avisar(erro.message, true);
        }
    });

    document.querySelector('#buscar-produto').addEventListener('click', async () => {
        const busca = document.querySelector('#produto').value.trim();

        if (!busca) {
            avisar('Digite o nome ou código de barras do produto.', true);
            return;
        }

        try {
            const resultado = await requisicao(
                `${apiProdutos}?busca=${encodeURIComponent(busca)}`
            );

            produtosEncontrados = resultado.data || [];
            seletor.innerHTML = '';

            if (produtosEncontrados.length === 0) {
                seletor.innerHTML = '<option value="">Nenhum produto disponível.</option>';
                avisar('Nenhum produto com estoque disponível foi encontrado.', true);
                return;
            }

            produtosEncontrados.forEach((produto, indice) => {
                const opcao = document.createElement('option');
                opcao.value = indice;

                opcao.textContent =
                    `${produto.nome} | Lote ${produto.numero_lote} | ` +
                    `Estoque ${produto.quantidade_atual} | ` +
                    `Validade ${produto.data_validade} | ${dinheiro(produto.preco_unitario)}`;

                seletor.appendChild(opcao);
            });

            avisar(`${produtosEncontrados.length} opção(ões) encontrada(s).`);
        } catch (erro) {
            avisar(erro.message, true);
        }
    });

    document.querySelector('#adicionar-produto').addEventListener('click', async () => {
        const indice = seletor.value;
        const produto = produtosEncontrados[Number(indice)];
        const quantidade = Number(
            document.querySelector('#quantidade-produto').value
        );

        if (!produto) {
            avisar('Pesquise e selecione um produto.', true);
            return;
        }

        if (!Number.isInteger(quantidade) || quantidade < 1) {
            avisar('Informe uma quantidade válida.', true);
            return;
        }

        if (quantidade > Number(produto.quantidade_atual)) {
            avisar('A quantidade solicitada supera o estoque disponível.', true);
            return;
        }

        const botao = document.querySelector('#adicionar-produto');
        botao.disabled = true;

        try {
            if (!venda) {
                const criada = await requisicao(apiVendas, 'POST', {
                    id_funcionario: 1,
                    id_cliente: cliente ? cliente.id_cliente : null
                });

                venda = {
                    id_venda: criada.id_venda,
                    id_cliente: cliente ? cliente.id_cliente : null,
                    clienteNome: cliente ? cliente.nome : null,
                    itens: []
                };

                salvarVenda(venda);
            }

            await requisicao(
                `${apiVendas}/${venda.id_venda}/itens`,
                'POST',
                {
                    id_produto: produto.id_produto,
                    id_lote: produto.id_lote,
                    quantidade: quantidade
                }
            );

            venda.itens.push({
                nome: produto.nome,
                lote: produto.numero_lote,
                id_produto: produto.id_produto,
                id_lote: produto.id_lote,
                quantidade: quantidade,
                preco: Number(produto.preco_unitario)
            });

            salvarVenda(venda);
            atualizarTela();

            document.querySelector('#produto').value = '';
            document.querySelector('#quantidade-produto').value = 1;
            seletor.innerHTML = '<option value="">Pesquise outro produto</option>';

            avisar('Produto adicionado à venda com sucesso.');
        } catch (erro) {
            avisar(erro.message, true);
        } finally {
            botao.disabled = false;
        }
    });

    if (venda) {
        cliente = venda.id_cliente
            ? { id_cliente: venda.id_cliente, nome: venda.clienteNome }
            : null;
        atualizarTela();
    }
}

// TELA DE PAGAMENTO
const paginaPagamento = document.querySelector('#pagina-pagamento');

if (paginaPagamento) {
    const venda = lerVenda();
    const mensagem = document.querySelector('#mensagem-pagamento');

    if (!venda || !venda.id_venda || !venda.itens?.length) {
        mensagem.textContent = 'Nenhuma venda válida foi encontrada. Volte e inicie uma venda.';
        document.querySelector('#finalizar-venda').disabled = true;
    } else {
        const subtotal = venda.itens.reduce(
            (soma, item) => soma + item.preco * item.quantidade, 0
        );

        document.querySelector('#pagamento-numero').textContent =
            `#${venda.id_venda}`;

        document.querySelector('#pagamento-cliente').textContent =
            venda.clienteNome || 'Não informado';

        document.querySelector('#pagamento-subtotal').textContent = dinheiro(subtotal);
        document.querySelector('#pagamento-total').textContent = dinheiro(subtotal);
    }

    document.querySelector('#finalizar-venda').addEventListener('click', async event => {
        if (!venda || !venda.id_venda || !venda.itens?.length) return;

        const botao = event.currentTarget;
        botao.disabled = true;
        mensagem.textContent = 'Finalizando venda...';

        try {
            const resultado = await requisicao(
                `${paginaPagamento.dataset.apiVendas}/${venda.id_venda}/finalizar`,
                'POST',
                {
                    id_forma_pagamento: Number(
                        document.querySelector('#forma-pagamento').value
                    ),
                    parcelas: Number(
                        document.querySelector('#parcelas').value
                    )
                }
            );

            const dados = resultado.data || resultado;

            venda.valor_total = Number(dados.valor_total ?? 0);
            venda.desconto_aplicado = Number(dados.desconto_aplicado ?? 0);
            venda.id_forma_pagamento = Number(
                document.querySelector('#forma-pagamento').value
            );
            venda.parcelas = Number(
                document.querySelector('#parcelas').value
            );
            venda.finalizada = true;

            salvarVenda(venda);

            window.location.href = paginaPagamento.dataset.urlComprovante;
        } catch (erro) {
            mensagem.textContent = erro.message;
            botao.disabled = false;
        }
    });
}

// TELA DE COMPROVANTE
const paginaComprovante = document.querySelector('#pagina-comprovante');

if (paginaComprovante) {
    const venda = lerVenda();
    const mensagem = document.querySelector('#mensagem-comprovante');

    if (!venda || !venda.finalizada) {
        mensagem.textContent = 'Não foi encontrada uma venda finalizada nesta sessão.';
        document.querySelector('#nova-venda').addEventListener('click', () => {
            sessionStorage.removeItem('farmacia_venda');
        });
    } else {
        document.querySelector('#comprovante-numero').textContent =
            `#${venda.id_venda}`;

        document.querySelector('#comprovante-data').textContent =
            new Date().toLocaleString('pt-BR');

        document.querySelector('#comprovante-cliente').textContent =
            venda.clienteNome || 'Não informado';

        const corpo = document.querySelector('#comprovante-itens');
        corpo.innerHTML = '';

        venda.itens.forEach(item => {
            const linha = document.createElement('tr');

            [
                item.nome,
                item.quantidade,
                dinheiro(item.preco),
                dinheiro(item.preco * item.quantidade)
            ].forEach(valor => {
                const celula = document.createElement('td');
                celula.textContent = valor;
                linha.appendChild(celula);
            });

            corpo.appendChild(linha);
        });

        const subtotal = venda.itens.reduce(
            (soma, item) => soma + item.preco * item.quantidade, 0
        );

        const total = venda.valor_total || subtotal;
        const formas = {
            1: 'Dinheiro',
            2: 'Cartão de Débito',
            3: 'Cartão de Crédito',
            4: 'PIX',
            5: 'Convênio'
        };

        document.querySelector('#comprovante-subtotal').textContent = dinheiro(subtotal);
        document.querySelector('#comprovante-desconto').textContent =
            dinheiro(venda.desconto_aplicado);
        document.querySelector('#comprovante-pagamento').textContent =
            formas[venda.id_forma_pagamento] || 'Não informado';
        document.querySelector('#comprovante-total').textContent = dinheiro(total);

        document.querySelector('#nova-venda').addEventListener('click', () => {
            sessionStorage.removeItem('farmacia_venda');
        });
    }
}