<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Ambulancia;
use App\Models\TipoAmbulancia;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServicioTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_service_can_be_created_with_all_required_dependencies()
    {
        $user = User::create([
            'nombre' => 'Cliente Test',
            'ap_paterno' => 'Rios',
            'email' => 'cliente_test@vitae.com',
            'telefono' => '9511234455',
            'password' => bcrypt('password')
        ]);

        $cliente = Cliente::create([
            'id_usuario' => $user->id_usuario
        ]);

        $tipo = TipoAmbulancia::create([
            'nombre_tipo' => 'Urgencias Avanzadas',
            'costo_base' => 1000
        ]);

        $ambulancia = Ambulancia::create([
            'placa' => 'VTR-2026',
            'estado' => 'Disponible',
            'id_tipo_ambulancia' => $tipo->id_tipo_ambulancia
        ]);

        $servicio = Servicio::create([
            'costo_total' => 1500.50,
            'estado' => 'Pendiente',
            'tipo' => 'Traslado',
            'fecha_hora' => now(),
            'id_ambulancia' => $ambulancia->id_ambulancia,
            'id_cliente' => $cliente->id_usuario, 
        ]);

        $this->assertDatabaseHas('servicio', [
            'id_cliente' => $cliente->id_usuario,
            'costo_total' => 1500.50
        ]);
    }
}