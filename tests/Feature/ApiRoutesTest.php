<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiRoutesTest extends TestCase
{
    public function test_api_de_clientes_rejeita_cadastro_invalido(): void
    {
        $response = $this->postJson('/api/clientes', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'nome',
                'cpf',
            ]);
    }

    public function test_api_de_produtos_rejeita_cadastro_invalido(): void
    {
        $response = $this->postJson('/api/produtos', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'nome',
                'id_categoria',
                'id_fabricante',
                'preco_unitario',
                'tipo_controle',
            ]);
    }
}