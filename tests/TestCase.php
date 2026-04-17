<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Administrator;

abstract class TestCase extends BaseTestCase
{
    /**
     * Helper para iniciar sesión como administrador en pruebas.
     */
    protected function loginAsAdmin($isSuper = true)
    {
        $admin = Administrator::factory()->create(['is_super_admin' => $isSuper]);
        $this->actingAs($admin, 'admin');
        return $admin;
    }
}
