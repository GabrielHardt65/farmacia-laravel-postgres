
@extends('layouts.app')

@section('title', 'Editar Funcionário - Sistema Farmácia')
@section('page-title', 'Editar Funcionário')

@section('content')

<div class="clientes-container">
    <section class="clientes-section">

        <div class="section-header">
            <div>
                <h2>Editar Funcionário</h2>
                <p>Atualize os dados do funcionário.</p>
            </div>

            <a href="{{ route('funcionarios.index') }}" class="btn-secondary">
                Voltar
            </a>
        </div>

        @if ($errors->any())
            <div style="color: #b42318; margin-bottom: 20px;">
                @foreach ($errors->all() as $erro)
                    <p>{{ $erro }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('funcionarios.update', $funcionario->id_funcionario) }}"
              method="POST"
              class="funcionario-form">

            @csrf
            @method('PUT')

            <div class="funcionario-campos">

                <div>
                    <label>Nome completo</label>
                    <input type="text" name="nome"
                           value="{{ old('nome', $funcionario->nome) }}" required>
                </div>

                <div>
                    <label>CPF</label>
                    <input type="text" name="cpf" maxlength="14"
                           value="{{ old('cpf', $funcionario->cpf) }}" required>
                </div>

                <div>
                    <label>Cargo</label>
                    <input type="text" name="cargo"
                           value="{{ old('cargo', $funcionario->cargo) }}" required>
                </div>

                <div>
                    <label>Data de admissão</label>
                    <input type="date" name="data_admissao"
                           value="{{ old('data_admissao', \Carbon\Carbon::parse($funcionario->data_admissao)->format('Y-m-d')) }}"
                           required>
                </div>

                <div>
                    <label>Salário</label>
                    <input type="number" name="salario" step="0.01" min="0"
                           value="{{ old('salario', $funcionario->salario) }}" required>
                </div>

                <div>
                    <label>Telefone</label>
                    <input type="text" name="telefone"
                           value="{{ old('telefone', $funcionario->telefone) }}">
                </div>

                <div>
                    <label>E-mail</label>
                    <input type="email" name="email"
                           value="{{ old('email', $funcionario->email) }}">
                </div>

                <div>
                    <label>Login</label>
                    <input type="text" name="login"
                           value="{{ old('login', $funcionario->login) }}" required>
                </div>

                <div>
                    <label>Nova senha</label>
                    <input type="password" name="senha"
                           autocomplete="new-password"
                           placeholder="Deixe em branco para manter a atual">
                </div>

                <div>
                    <label>Supervisor</label>
                    <select name="id_supervisor">
                        <option value="">Sem supervisor</option>

                        @foreach ($supervisores as $supervisor)
                            <option value="{{ $supervisor->id_funcionario }}"
                                @selected(old('id_supervisor', $funcionario->id_supervisor) == $supervisor->id_funcionario)>
                                {{ $supervisor->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Responsável técnico</label>
                    <select name="e_responsavel_tecnico">
                        <option value="0"
                            @selected(old('e_responsavel_tecnico', (int) $funcionario->e_responsavel_tecnico) == 0)>
                            Não
                        </option>
                        <option value="1"
                            @selected(old('e_responsavel_tecnico', (int) $funcionario->e_responsavel_tecnico) == 1)>
                            Sim
                        </option>
                    </select>
                </div>

            </div>

            <div class="funcionario-form-acoes">
                <button type="submit" class="btn-finalizar">
                    Salvar alterações
                </button>

                <a href="{{ route('funcionarios.index') }}" class="btn-secondary">
                    Cancelar
                </a>
            </div>

        </form>

    </section>
</div>

<style>
    .funcionario-campos {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-top: 24px;
    }

    .funcionario-campos > div {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .funcionario-campos label {
        font-weight: 600;
        color: #35394b;
    }

    .funcionario-campos input,
    .funcionario-campos select {
        width: 100%;
        padding: 12px;
        border: 1px solid #dfe2ed;
        border-radius: 8px;
        background: #fffafc;
        font: inherit;
    }

    .funcionario-campos input:focus,
    .funcionario-campos select:focus {
        outline: none;
        border-color: #d83e80;
    }

    .funcionario-form-acoes {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 28px;
    }

    @media (max-width: 650px) {
        .funcionario-campos {
            grid-template-columns: 1fr;
        }
    }
</style>

@endsection
