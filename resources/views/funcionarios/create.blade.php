
@extends('layouts.app')

@section('title', 'Novo Funcionário - Sistema Farmácia')
@section('page-title', 'Novo Funcionário')

@section('content')

<div class="funcionario-form-container">
    <section class="funcionario-form-section">

        <div class="section-header">
            <div>
                <h2>Cadastro de Funcionário</h2>
                <p>Preencha os dados para cadastrar um novo funcionário.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('funcionarios.store') }}" method="POST">
            @csrf

            <div class="funcionario-form-grid">

                <div class="funcionario-field">
                    <label for="nome">Nome completo</label>
                    <input type="text" id="nome" name="nome"
                           value="{{ old('nome') }}" required maxlength="150">
                </div>

                <div class="funcionario-field">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf"
                           value="{{ old('cpf') }}" placeholder="000.000.000-00"
                           required maxlength="14">
                </div>

                <div class="funcionario-field">
                    <label for="cargo">Cargo</label>
                    <input type="text" id="cargo" name="cargo"
                           value="{{ old('cargo') }}" required maxlength="60"
                           placeholder="Ex.: Atendente">
                </div>

                <div class="funcionario-field">
                    <label for="data_admissao">Data de admissão</label>
                    <input type="date" id="data_admissao" name="data_admissao"
                           value="{{ old('data_admissao') }}" required>
                </div>

                <div class="funcionario-field">
                    <label for="salario">Salário</label>
                    <input type="number" id="salario" name="salario"
                           value="{{ old('salario') }}" min="0" step="0.01" required>
                </div>

                <div class="funcionario-field">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone"
                           value="{{ old('telefone') }}" maxlength="20">
                </div>

                <div class="funcionario-field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}" maxlength="120">
                </div>

                <div class="funcionario-field">
                    <label for="id_supervisor">Supervisor</label>
                    <select id="id_supervisor" name="id_supervisor">
                        <option value="">Sem supervisor</option>
                        @foreach ($supervisores as $supervisor)
                            <option value="{{ $supervisor->id_funcionario }}"
                                @selected(old('id_supervisor') == $supervisor->id_funcionario)>
                                {{ $supervisor->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="funcionario-field">
                    <label for="login">Login de acesso</label>
                    <input type="text" id="login" name="login"
                           value="{{ old('login') }}" required maxlength="50">
                </div>

                <div class="funcionario-field">
                    <label for="senha">Senha de acesso</label>
                    <input type="password" id="senha" name="senha" required minlength="6">
                </div>

                <div class="funcionario-field">
                    <label>
                        <input type="checkbox" name="e_responsavel_tecnico"
                               value="1" @checked(old('e_responsavel_tecnico'))>
                        Responsável técnico
                    </label>
                </div>

            </div>

            <div class="funcionario-form-actions">
                <a href="{{ route('funcionarios.index') }}" class="btn-outline">
                    Cancelar
                </a>

                <button type="submit" class="btn-finalizar">
                    Salvar Funcionário
                </button>
            </div>
        </form>

    </section>
</div>

@endsection
