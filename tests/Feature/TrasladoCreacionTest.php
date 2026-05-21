<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ambulancia;
use App\Models\Operador;
use App\Models\Paramedico;
use App\Models\TipoAmbulancia;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Servicio;
use App\Models\Traslado;
use App\Models\Paciente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class TrasladoCreacionTest extends TestCase
{
    use RefreshDatabase; // Vacía de forma segura la BD de prueba en memoria después del test

    protected $admin;
    protected $userCliente;
    protected $cliente;
    protected $userOperador;
    protected $operador;
    protected $userParam;
    protected $paramedico;
    protected $ambulancia;

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
        $this->userCliente = User::create([
            'nombre' => 'Carlos',
            'ap_paterno' => 'Cliente',
            'email' => 'cliente@test.com',
            'telefono' => '9510000000',
            'password' => Hash::make('password123'),
        ]);
        $this->cliente = Cliente::create(['id_usuario' => $this->userCliente->id_usuario]);

        // 3. Crear usuario Operador secundario (ID: 2)
        $this->userOperador = User::create([
            'nombre' => 'Juan',
            'ap_paterno' => 'Piloto',
            'email' => 'operador@test.com',
            'telefono' => '9510000001',
            'password' => Hash::make('password123'),
        ]);
        $this->operador = Operador::create([
            'id_usuario' => $this->userOperador->id_usuario,
            'salario_hora' => 50.00,
            'numero_licencia' => 'LIC123',
            'fecha_licencia' => now()->addYears(2)->toDateString()
        ]);

        // 4. Crear usuario Paramédico con su perfil vía Eloquent (ID: 3)
        $this->userParam = User::create([
            'nombre' => 'Pedro',
            'ap_paterno' => 'Médico',
            'email' => 'paramedico@test.com',
            'telefono' => '9510000002',
            'password' => Hash::make('password123'),
        ]);
        
        $this->paramedico = Paramedico::create([
            'id_usuario' => $this->userParam->id_usuario,
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

    public function test_despacho_completo_flujo_cotizacion_servicio_traslado()
    {
        // 1. Crear cotización como cliente
        $cotizacion = Cotizacion::create([
            'user_id'                   => $this->userCliente->id_usuario,
            'numero_guia'               => 'COT-TEST-000001',
            'nombre'                    => 'Carlos Cliente',
            'telefono'                  => '9510000000',
            'tipo_servicio'             => 'Traslado',
            'tipo_ambulancia_preferida' => 'Premium',
            'descripcion'               => 'Prueba de despacho completo',
            'fecha_requerida'           => now()->addDay()->toDateString(),
            'origen'                    => 'Centro',
            'destino'                   => 'Hospital',
            'lat_origen'                => 17.0654,
            'lng_origen'               => -96.7236,
            'lat_destino'              => 17.0730,
            'lng_destino'             => -96.7260,
            'personas'                 => 1,
            'padecimientos_paciente'   => 'Dolor abdominal',
            'estado'                   => 'Pendiente',
        ]);

        // 2. Admin acepta la cotización (solo saltamos middlewares de rol/verificación, mantenemos SubstituteBindings)
        $this->withoutMiddleware([
                \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
                \App\Http\Middleware\EsAdmin::class,
            ])
             ->actingAs($this->admin)
             ->post("/cotizaciones/{$cotizacion->id_cotizacion}/aceptar", [
                 'km_distancia'      => 12.5,
                 'costo_km_unitario' => 25.00,
                 'id_ambulancia'     => $this->ambulancia->id_ambulancia,
                 'id_operador'       => $this->operador->id_usuario,
                 'horas_servicio'    => 2,
                 'paramedicos_ids'   => [$this->paramedico->id_usuario],
                 'incluye'           => 'Traslado, paramédicos y oxígeno',
                 'respuesta'         => 'Cotización aceptada',
                 'nombre_paciente'   => 'Samuel',
                 'anticipo'          => 500.00,
             ]);

        $this->assertDatabaseHas('cotizaciones', [
            'id_cotizacion' => $cotizacion->id_cotizacion,
            'estado'        => 'Aceptada',
        ]);

        // 3. Cliente confirma la cotización
        $this->withoutMiddleware([
                \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
                \App\Http\Middleware\EsCliente::class,
            ])
             ->actingAs($this->userCliente)
             ->post("/mis-solicitudes/{$cotizacion->id_cotizacion}/confirmar", [
                 'comentario_cliente'   => 'Confirmo el servicio',
                 'paciente_nombre'      => 'Samuel',
                 'paciente_nacimiento'  => '1990-05-15',
                 'paciente_diagnostico' => 'Dolor abdominal agudo',
             ]);

        $this->assertDatabaseHas('cotizaciones', [
            'id_cotizacion'    => $cotizacion->id_cotizacion,
            'decision_cliente' => 'confirmada',
        ]);

        // 4. Operador despacha la reserva
        $this->withoutMiddleware([
                \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
                \App\Http\Middleware\EsEmpleado::class,
            ])
             ->actingAs($this->userOperador)
             ->post("/mi-panel/despachar/{$cotizacion->id_cotizacion}", [
                 'id_ambulancia' => $this->ambulancia->id_ambulancia,
                 'paramedicos'   => [$this->paramedico->id_usuario],
             ]);

        // 5. Validar que la cotización cambió a Despachada
        $this->assertDatabaseHas('cotizaciones', [
            'id_cotizacion' => $cotizacion->id_cotizacion,
            'estado'        => 'Despachada',
        ]);

        // 6. Validar que se creó el Servicio
        $this->assertDatabaseHas('servicio', [
            'id_ambulancia' => $this->ambulancia->id_ambulancia,
            'id_operador'   => $this->userOperador->id_usuario,
            'id_cliente'    => $this->userCliente->id_usuario,
            'estado'        => 'Activo',
            'tipo'          => 'Traslado',
        ]);

        // 7. Validar que se creó el Traslado
        $this->assertDatabaseHas('traslados', [
            'km_distancia'        => 12.5,
            'horas_servicio'      => 2,
            'num_paramedicos'     => 1,
            'tipo_ambulancia_num' => 1,
        ]);

        // 8. Validar que se creó el Paciente
        $this->assertDatabaseHas('paciente', [
            'nombre'     => 'Samuel',
            'ap_paterno' => 'S/P',
            'id_servicio' => Servicio::where('id_operador', $this->userOperador->id_usuario)->first()->id_servicio,
        ]);

        // 9. Validar que la ambulancia cambió a "En servicio"
        $this->assertDatabaseHas('ambulancia', [
            'id_ambulancia' => $this->ambulancia->id_ambulancia,
            'estado'        => 'En servicio',
        ]);
    }
}