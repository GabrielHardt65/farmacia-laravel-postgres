
@extends('layouts.app')

@section('title', 'Novo Cliente - Sistema Farmácia')
@section('page-title', 'Novo Cliente')

@section('content')

<div class="cliente-form-container">

    <section class="cliente-form-section">

        <div class="section-header">
            <div>
                <h2>Cadastro de Cliente</h2>
                <p>Preencha os dados para cadastrar um novo cliente.</p>
            </div>
        </div>

        @if ($errors->any())
            <div role="alert" style="padding: 14px; margin-bottom: 20px; border-radius: 8px; background-color: #fdeaea; color: #9b2424; border: 1px solid #f0bcbc;">
                <strong>Não foi possível cadastrar o cliente:</strong>
                <ul style="margin-top: 8px; padding-left: 20px;">
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="form-cliente" action="{{ route('clientes.store') }}" method="POST">
            @csrf

            <div class="cliente-form-grid">

                <div class="cliente-field">
                    <label for="nome">Nome completo</label>
                    <input type="text" id="nome" name="nome"
                        placeholder="Digite o nome completo"
                        value="{{ old('nome') }}"
                        minlength="3" maxlength="150" required>
                </div>

                <div class="cliente-field">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf"
                        placeholder="000.000.000-00"
                        value="{{ old('cpf') }}"
                        maxlength="14"
                        pattern="[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}"
                        title="Digite o CPF no formato 000.000.000-00"
                        inputmode="numeric" required>
                </div>

                <div class="cliente-field">
                    <label for="data_nascimento">Data de nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento"
                        value="{{ old('data_nascimento') }}"
                        min="1900-01-01"
                        max="{{ date('Y-m-d') }}" required>
                </div>

                <div class="cliente-field">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone"
                        placeholder="(00) 00000-0000"
                        value="{{ old('telefone') }}"
                        maxlength="15"
                        pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}"
                        title="Digite o telefone no formato (00) 00000-0000"
                        required>
                </div>

                <div class="cliente-field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email"
                        placeholder="cliente@email.com"
                        value="{{ old('email') }}"
                        maxlength="150" required>
                </div>

                <div class="cliente-field">
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" name="cidade"
                        placeholder="Digite a cidade"
                        value="{{ old('cidade') }}"
                        minlength="2" maxlength="100" required>
                </div>

                <div class="cliente-field">
                    <label for="estado">Estado</label>
                    <input type="text" id="estado" name="estado"
                        placeholder="PR"
                        value="{{ old('estado') }}"
                        maxlength="2" minlength="2"
                        pattern="[A-Za-z]{2}"
                        title="Digite a sigla do estado com duas letras"
                        required>
                </div>

            </div>

            <div class="cliente-field cliente-field-full">
                <label for="endereco">Endereço</label>
                <input type="text" id="endereco" name="endereco"
                    placeholder="Rua, número e complemento"
                    value="{{ old('endereco') }}"
                    minlength="5" maxlength="200" required>
            </div>

            <div class="cliente-form-actions">

                <a href="{{ route('clientes.index') }}" class="btn-outline">
                    Cancelar
                </a>

                <button type="submit" class="btn-finalizar">
                    Salvar Cliente
                </button>

            </div>

        </form>

    </section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const formulario = document.getElementById('form-cliente');
        const cpf = document.getElementById('cpf');
        const telefone = document.getElementById('telefone');
        const estado = document.getElementById('estado');

        cpf.addEventListener('input', function () {
            let valor = this.value.replace(/\D/g, '').slice(0, 11);

            valor = valor.replace(/^(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
            valor = valor.replace(/\.(\d{3})(\d)/, '.$1-$2');

            this.value = valor;
            this.setCustomValidity('');
        });

        telefone.addEventListener('input', function () {
            let valor = this.value.replace(/\D/g, '').slice(0, 11);

            if (valor.length > 2) {
                valor = '(' + valor.slice(0, 2) + ') ' + valor.slice(2);
            }

            if (valor.length > 10) {
                valor = valor.slice(0, 10) + '-' + valor.slice(10);
            }

            this.value = valor;
        });

        estado.addEventListener('input', function () {
            this.value = this.value
                .replace(/[^a-zA-Z]/g, '')
                .toUpperCase()
                .slice(0, 2);
        });

        function validarCPF(valor) {
            const numeros = valor.replace(/\D/g, '');

            if (numeros.length !== 11 || /^(\d)\1{10}$/.test(numeros)) {
                return false;
            }

            for (let tamanho = 9; tamanho < 11; tamanho++) {
                let soma = 0;

                for (let i = 0; i < tamanho; i++) {
                    soma += Number(numeros[i]) * (tamanho + 1 - i);
                }

                const resto = (soma * 10) % 11;
                const digito = resto === 10 ? 0 : resto;

                if (digito !== Number(numeros[tamanho])) {
                    return false;
                }
            }

            return true;
        }

        formulario.addEventListener('submit', function (event) {

            cpf.setCustomValidity('');

            if (!validarCPF(cpf.value)) {
                event.preventDefault();
                cpf.setCustomValidity('Digite um CPF válido.');
                cpf.reportValidity();
                return;
            }

            if (!formulario.reportValidity()) {
                event.preventDefault();
                return;
            }

            // Dados válidos: o navegador envia o formulário ao Laravel.
        });

    });
</script>

@endsection
