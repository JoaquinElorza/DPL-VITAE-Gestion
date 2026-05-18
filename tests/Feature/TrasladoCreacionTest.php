<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ambulancia;
use App\Models\Operador;
use App\Models\Paramedico;
use App\Models\TipoAmbulancia;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class TrasladoCreacionTest extends TestCase
{
    use RefreshDatabase; // Vacía de forma segura la BD de prueba en memoria después del test

    protected $admin;
    protected $cliente;
    protected $operador;
    protected $ambulancia;
    protected $paramedico;

    /**
     * Configuración de dependencias iniciales con Eloquent puro.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // 1. Crear el Tipo de Ambulancia básico
        $tipo = TipoAmbulancia::create([
            'nombre_tipo' => 'Básica',
            'costo_base' => 800.00
        ]);

        $this->ambulancia = Ambulancia::create([
            'placa' => 'XYZ-123-A',
            'estado' => 'Disponible',
            'id_tipo_ambulancia' => $tipo->id_tipo_ambulancia
        ]);

        // 2. Crear usuario Cliente (ID: 1)
        $userCliente = User::create([
            'nombre' => 'Carlos',
            'ap_paterno' => 'Cliente',
            'email' => 'cliente@test.com',
            'telefono' => '9510000000',
            'password' => Hash::make('password123'),
        ]);
        $this->cliente = Cliente::create(['id_usuario' => $userCliente->id_usuario]);

        // 3. Crear usuario Operador secundario (ID: 2)
        $userOperador = User::create([
            'nombre' => 'Juan',
            'ap_paterno' => 'Piloto',
            'email' => 'operador@test.com',
            'telefono' => '9510000001',
            'password' => Hash::make('password123'),
        ]);
        $this->operador = Operador::create([
            'id_usuario' => $userOperador->id_usuario,
            'salario_hora' => 50.00,
            'numero_licencia' => 'LIC123',
            'fecha_licencia' => now()->addYears(2)->toDateString()
        ]);

        // 4. Crear usuario Paramédico con su perfil vía Eloquent (ID: 3)
        $userParamedico = User::create([
            'nombre' => 'Pedro',
            'ap_paterno' => 'Médico',
            'email' => 'paramedico@test.com',
            'telefono' => '9510000002',
            'password' => Hash::make('password123'),
        ]);
        
        $this->paramedico = Paramedico::create([
            'id_usuario' => $userParamedico->id_usuario,
            'salario_hora' => 60.00,
            'categoria' => 'General'
        ]);

        // 5. Crear usuario Administrador que operará el panel (ID: 4)
        $this->admin = User::create([
            'nombre' => 'Admin',
            'ap_paterno' => 'Sistema',
            'email' => 'admin@test.com',
            'telefono' => '9510000003',
            'password' => Hash::make('password123'),
        ]);
    }

    /**
     * PRUEBA UNIFICADA DE LA RÚBRICA:
     * Evalúa el endpoint reglamentario POST /traslados, sus validaciones, 
     * transformaciones binarias y la persistencia en cascada de los modelos.
     */
    public function test_creacion_manual_de_traslado_almacena_todas_las_tablas_correctamente()
    {
        $payload = [
            'fecha_hora' => now()->toDateTimeString(),
            'id_ambulancia' => $this->ambulancia->id_ambulancia,
            'id_cliente' => $this->cliente->id_usuario,
            'id_operador' => $this->operador->id_usuario,
            'km_distancia' => 15.5,
            'horas_servicio' => 2,
            'oxigeno_lpm' => 2,
            'tipo_ambulancia' => 'premium',
            'paciente_nombre' => 'Samuel',
            'paciente_paterno' => 'Aragon',
            'paramedicos_ids' => [$this->paramedico->id_usuario]
        ];

        // Ejecutamos la petición saltando middlewares para evaluar la pura lógica del backend
        $response = $this->withoutMiddleware()
                         ->actingAs($this->admin)
                         ->postJson('/traslados', $payload);

        // 1. Validar respuesta exitosa de la API
        $response->assertStatus(200);
        $response->assertJsonStructure(['ok', 'servicio', 'traslado', 'paciente']);

        // 2. Validar persistencia en la tabla Servicio
        $this->assertDatabaseHas('servicio', [
            'tipo' => 'Traslado',
            'id_ambulancia' => $this->ambulancia->id_ambulancia,
            'id_operador' => $this->operador->id_usuario
        ]);

        // 3. Validar validaciones matemáticas y transformación de variables (premium -> 1)
        $this->assertDatabaseHas('traslados', [
            'km_distancia' => 15.5,
            'horas_servicio' => 2,
            'tipo_ambulancia_num' => 1
        ]);

        // 4. Validar creación automática del paciente enlazado
        $this->assertDatabaseHas('paciente', [
            'nombre' => 'Samuel',
            'ap_paterno' => 'Aragon'
        ]);
    }
}