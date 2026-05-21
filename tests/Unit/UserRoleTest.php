<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Operador;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_identifies_admin_correctly()
    {
        $user = new User([
            'nombre' => 'Victor',
            'ap_paterno' => 'Rios',
            'email' => 'admin@vitae.com'
        ]);

        $this->assertTrue($user->esAdmin());
        $this->assertFalse($user->esEmpleado());
    }

    /** @test */
    public function it_identifies_employee_correctly()
    {
        $user = User::create([
            'nombre' => 'Operador Test',
            'ap_paterno' => 'Rios',
            'ap_materno' => 'Cortes',
            'email' => 'op@vitae.com',
            'telefono' => '9511234567',
            'password' => bcrypt('password')
        ]);

        Operador::create([
            'id_usuario' => $user->id_usuario,
            'salario_hora' => 50.00
        ]);

        $this->assertTrue($user->fresh()->esEmpleado());
        $this->assertFalse($user->fresh()->esAdmin());
    }

    /** @test */
    public function it_fails_to_identify_admin_if_it_has_a_role()
    {
        $user = User::create([
            'nombre' => 'No Soy Admin',
            'ap_paterno' => 'Lopez',
            'email' => 'empleado@vitae.com',
            'telefono' => '9519876543',
            'password' => bcrypt('password')
        ]);

        Operador::create([
            'id_usuario' => $user->id_usuario,
            'salario_hora' => 45.00
        ]);

        $this->assertFalse($user->fresh()->esAdmin());
    }
    
}