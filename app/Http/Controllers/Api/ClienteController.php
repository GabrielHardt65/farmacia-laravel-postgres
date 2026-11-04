<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class ClienteController extends Controller
{
    private function validarCpf(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $soma = 0;

            for ($i = 0; $i < $t; $i++) {
                $soma += (int) $cpf[$i] * (($t + 1) - $i);
            }

            $digito = ((10 * $soma) % 11) % 10;

            if ((int) $cpf[$t] !== $digito) {
                return false;
            }
        }

        return true;
    }

    private function regras(?int $id = null): array
    {
        return [
            'nome' => 'required|string|min:3|max:150',
            'cpf' => [
                'required',
                'string',
                'max:14',
                function ($attribute, $value, $fail) {
                    if (!$this->validarCpf($value)) {
                        $fail('O CPF informado é inválido.');
                    }
                },
                Rule::unique('pgsql.samaraferreira_bd.clientes', 'cpf')
                    ->ignore($id, 'id_cliente'),
            ],
            'data_nascimento' => 'nullable|date|after_or_equal:1900-01-01|before_or_equal:today',
            'telefone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:150',
            'programa_fidelidade' => 'sometimes|boolean',
            'pontos_fidelidade' => 'sometimes|integer|min:0',
        ];
    }

    public function index()
    {
        $clientes = Cliente::orderBy('nome')->get();

        return ClienteResource::collection($clientes);
    }

    public function store(Request $request)
    {
        $dados = $request->validate($this->regras());

        // Mantém o CPF em formato padronizado.
        $cpf = preg_replace('/\D/', '', $dados['cpf']);
        $dados['cpf'] = substr($cpf, 0, 3) . '.' .
            substr($cpf, 3, 3) . '.' .
            substr($cpf, 6, 3) . '-' .
            substr($cpf, 9, 2);

        $dados['programa_fidelidade'] = $dados['programa_fidelidade'] ?? false;
        $dados['pontos_fidelidade'] = $dados['pontos_fidelidade'] ?? 0;

        try {
            $cliente = Cliente::create($dados);

            return (new ClienteResource($cliente))
                ->response()
                ->setStatusCode(201);

        } catch (QueryException $e) {
            return response()->json([
                'mensagem' => 'Não foi possível cadastrar o cliente. Verifique se o CPF já está cadastrado.',
            ], 409);
        }
    }

    public function show(string $id)
    {
        $cliente = Cliente::findOrFail($id);

        return new ClienteResource($cliente);
    }

    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);

        $regras = $this->regras((int) $id);

        foreach ($regras as $campo => $regra) {
            $regras[$campo] = array_merge(
                ['sometimes'],
                is_array($regra) ? $regra : explode('|', $regra)
            );
        }

        $dados = $request->validate($regras);

        if (isset($dados['cpf'])) {
            $cpf = preg_replace('/\D/', '', $dados['cpf']);
            $dados['cpf'] = substr($cpf, 0, 3) . '.' .
                substr($cpf, 3, 3) . '.' .
                substr($cpf, 6, 3) . '-' .
                substr($cpf, 9, 2);
        }

        $cliente->update($dados);

        return new ClienteResource($cliente->fresh());
    }
    public function destroy(string $id)
    {
        $cliente = Cliente::findOrFail($id);

        try {
            $cliente->delete();

            return response()->noContent();

        } catch (QueryException $e) {
            return response()->json([
                'mensagem' => 'Não é possível excluir este cliente porque existem registros relacionados a ele.',
            ], 409);
        }
    }
}