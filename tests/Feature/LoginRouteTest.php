<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginRouteTest extends TestCase
{
    public function test_pagina_de_login_carrega_corretamente(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}