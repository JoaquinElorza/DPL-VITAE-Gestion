<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Ambulancia;
use App\Models\TipoAmbulancia;
use App\Models\Traslado;
use App\Models\Paciente;
use App\Models\modelo_traslados;
use App\Models\Operador;
use App\Services\CalculadoraTrasladosService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class MineriaDatosTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $traslados;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'nombre' => 'Admin', 'ap_paterno' => 'Sistema',
            'email' => 'admin@test.com', 'password' => bcrypt('password'),
        ]);

        Operador::create([
            'id_usuario' => $this->admin->id_usuario,
            'salario_hora' => 0,
        ]);

        $tipo = TipoAmbulancia::create(['nombre_tipo' => 'Básica', 'costo_base' => 800]);
        $ambulancia = Ambulancia::create([
            'placa' => 'TEST-01', 'estado' => 'Disponible',
            'id_tipo_ambulancia' => $tipo->id_tipo_ambulancia,
        ]);

        $userCliente = User::create([
            'nombre' => 'Cliente', 'ap_paterno' => 'Test',
            'email' => 'cliente@test.com', 'password' => bcrypt('password'),
        ]);
        Cliente::create(['id_usuario' => $userCliente->id_usuario]);

        // Crear 10 traslados con datos variados para las pruebas
        $this->traslados = collect();
        for ($i = 0; $i < 10; $i++) {
            $servicio = Servicio::create([
                'costo_total' => 1000 + ($i * 100),
                'estado' => $i < 8 ? 'Finalizado' : 'Pendiente',
                'fecha_hora' => now()->subDays($i),
                'tipo' => 'Traslado',
                'id_ambulancia' => $ambulancia->id_ambulancia,
                'id_cliente' => $userCliente->id_usuario,
            ]);

            $esOutlier = ($i === 9);
            $cluster = match (true) {
                $i < 3 => 'bajo',
                $i < 6 => 'medio',
                default => 'alto',
            };

            $traslado = Traslado::create([
                'id_servicio' => $servicio->id_servicio,
                'km_distancia' => $i === 9 ? 999.99 : (5 + $i * 2.5),
                'horas_servicio' => $i === 9 ? 48 : (1 + $i * 0.5),
                'oxigeno_lpm' => $i * 0.5,
                'costo_padecimiento_num' => $i * 50,
                'tipo_ambulancia_num' => $i % 2,
                'num_paramedicos' => 1 + ($i % 3),
                'precio_final' => 1000 + ($i * 150),
                'cluster' => $cluster,
                'z_score' => $esOutlier ? 4.5 : ($i < 8 ? rand(-150, 150) / 100 : null),
                'es_outlier' => $esOutlier,
                'usable_para_modelo' => $i < 8,
                'observaciones_medicas' => $i < 8 ? 'Observación normal' : null,
            ]);

            $this->traslados->push($traslado);
        }
    }

    /** @test */
    public function hu06_deteccion_de_outliers_marca_registros_fuera_de_rango()
    {
        $outliers = Traslado::where('es_outlier', true)->get();

        $this->assertCount(1, $outliers);
        $this->assertTrue($outliers->first()->es_outlier);
        $this->assertEquals(999.99, $outliers->first()->km_distancia);
        $this->assertEquals(48, $outliers->first()->horas_servicio);
        $this->assertGreaterThan(3, $outliers->first()->z_score);
    }

    /** @test */
    public function hu06_registros_normales_no_son_marcados_como_outliers()
    {
        $normales = Traslado::where('es_outlier', false)->get();

        $this->assertGreaterThan(0, $normales->count());
        foreach ($normales as $t) {
            $this->assertFalse($t->es_outlier);
        }
    }

    /** @test */
    public function hu07_clustering_clasifica_traslados_en_bajo_medio_alto()
    {
        $clusters = Traslado::select('cluster')
            ->whereNotNull('cluster')
            ->distinct()
            ->pluck('cluster')
            ->toArray();

        $this->assertContains('bajo', $clusters);
        $this->assertContains('medio', $clusters);
        $this->assertContains('alto', $clusters);

        $conteo = Traslado::whereNotNull('cluster')
            ->select('cluster', DB::raw('count(*) as total'))
            ->groupBy('cluster')
            ->pluck('total', 'cluster');

        $this->assertEquals(10, $conteo->sum());
        $this->assertTrue($conteo->has('bajo'));
        $this->assertTrue($conteo->has('medio'));
        $this->assertTrue($conteo->has('alto'));
    }

    /** @test */
    public function hu08_scope_limpio_filtra_solo_datos_validos_para_entrenamiento()
    {
        $limpios = Traslado::limpio()->get();

        foreach ($limpios as $t) {
            $this->assertFalse($t->es_outlier);
            $this->assertTrue($t->usable_para_modelo);
            $this->assertNotNull($t->precio_final);
            $this->assertNotNull($t->km_distancia);
            $this->assertNotNull($t->horas_servicio);
        }

        $this->assertEquals(8, $limpios->count());
    }

    /** @test */
    public function hu08_outliers_no_aparecen_en_dataset_limpio()
    {
        $limpios = Traslado::limpio()->get();
        $idsLimpios = $limpios->pluck('id_servicio')->toArray();

        $outlier = $this->traslados->firstWhere('es_outlier', true);
        $this->assertNotNull($outlier);
        $this->assertNotContains($outlier->id_servicio, $idsLimpios);
    }

    /** @test */
    public function hu09_calculadora_devuelve_precio_sugerido_con_coeficientes_por_defecto()
    {
        $calculadora = new CalculadoraTrasladosService();

        $precio = $calculadora->calcular([
            'km_distancia' => 10,
            'horas_servicio' => 2,
            'oxigeno_lpm' => 3,
            'costo_padecimiento_num' => 200,
            'tipo_ambulancia_num' => 1,
        ]);

        $this->assertIsFloat($precio);
        $this->assertGreaterThan(0, $precio);

        $esperado = 1000.00
            + (25.00 * 10)
            + (150.00 * 2)
            + (5.00 * (3 * 120))
            + (300.00 * 200)
            + (500.00 * 1);

        $this->assertEquals(round($esperado, 2), $precio);
    }

    /** @test */
    public function hu11_coeficientes_se_guardan_y_consultan_en_modelo_traslados()
    {
        $modelo = modelo_traslados::create([
            'b0' => 1200.50,
            'b_distancia' => 30.00,
            'b_horas' => 180.00,
            'b_oxigeno' => 6.50,
            'b_padecimiento' => 350.00,
            'b_ambulancia' => 600.00,
        ]);

        $this->assertDatabaseHas('modelo_traslados', [
            'id_modelo_servicio' => $modelo->id_modelo_servicio,
            'b0' => 1200.50,
            'b_distancia' => 30.00,
        ]);

        $consultado = modelo_traslados::find($modelo->id_modelo_servicio);
        $this->assertEquals(1200.50, $consultado->b0);
        $this->assertEquals(30.00, $consultado->b_distancia);
        $this->assertEquals(180.00, $consultado->b_horas);
        $this->assertEquals(6.50, $consultado->b_oxigeno);
        $this->assertEquals(350.00, $consultado->b_padecimiento);
        $this->assertEquals(600.00, $consultado->b_ambulancia);
    }

    /** @test */
    public function hu11_se_pueden_actualizar_coeficientes_y_consulta_el_ultimo()
    {
        modelo_traslados::create([
            'b0' => 1000, 'b_distancia' => 25, 'b_horas' => 150,
            'b_oxigeno' => 5, 'b_padecimiento' => 300, 'b_ambulancia' => 500,
        ]);

        $ultimo = modelo_traslados::orderBy('id_modelo_servicio', 'desc')->first();
        $this->assertNotNull($ultimo);
        $this->assertEquals(1000, $ultimo->b0);
    }

    /** @test */
    public function hu12_endpoint_cotizaciones_usando_calculadora_devuelve_200()
    {
        $payload = [
            'fecha_hora' => now()->toDateTimeString(),
            'id_ambulancia' => Ambulancia::first()->id_ambulancia,
            'id_cliente' => Cliente::first()->id_usuario,
            'id_operador' => $this->admin->id_usuario,
            'km_distancia' => 15.5,
            'horas_servicio' => 2,
            'oxigeno_lpm' => 2,
            'tipo_ambulancia' => 'premium',
            'paciente_nombre' => 'Samuel',
            'paciente_paterno' => 'Aragon',
        ];

        $response = $this->withoutMiddleware()
            ->actingAs($this->admin)
            ->postJson('/traslados', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure(['ok', 'servicio', 'traslado', 'paciente']);
        $response->assertJson(['ok' => true]);
    }

    /** @test */
    public function hu12_precio_calculado_se_almacena_en_traslado_y_servicio()
    {
        $payload = [
            'fecha_hora' => now()->toDateTimeString(),
            'id_ambulancia' => Ambulancia::first()->id_ambulancia,
            'id_cliente' => Cliente::first()->id_usuario,
            'id_operador' => $this->admin->id_usuario,
            'km_distancia' => 20,
            'horas_servicio' => 3,
            'tipo_ambulancia' => 'basica',
            'paciente_nombre' => 'Maria',
        ];

        $response = $this->withoutMiddleware()
            ->actingAs($this->admin)
            ->postJson('/traslados', $payload);

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertNotNull($data['traslado']['precio_modelo']);
        $this->assertNotNull($data['traslado']['precio_final']);
        $this->assertGreaterThan(0, $data['traslado']['precio_modelo']);
        $this->assertGreaterThan(0, $data['servicio']['costo_total']);
        $this->assertEquals(0, $data['traslado']['tipo_ambulancia_num']);
    }

    /** @test */
    public function hu13_dashboard_analitico_muestra_indicadores()
    {
        $response = $this->withoutVite()
            ->withoutMiddleware()
            ->actingAs($this->admin)
            ->get('/analitica-gerencial');

        $response->assertStatus(200);
        $response->assertSee('Total Servicios');
        $response->assertSee('Ingresos Totales');
        $response->assertSee('Ticket Promedio');
    }

    /** @test */
    public function hu14_endpoint_analitica_retorna_datos_correctos()
    {
        $this->withoutVite()
            ->withoutMiddleware()
            ->actingAs($this->admin)
            ->get('/analitica-gerencial')
            ->assertStatus(200);
    }

    /** @test */
    public function hu15_historial_de_traslados_contiene_todos_los_registros()
    {
        $todos = Traslado::with('servicio')->get();

        $this->assertCount(10, $todos);

        $conPrecio = Traslado::whereNotNull('precio_final')->count();
        $this->assertEquals(10, $conPrecio);

        $conCluster = Traslado::whereNotNull('cluster')->count();
        $this->assertGreaterThan(0, $conCluster);
    }

    /** @test */
    public function hu15_tendencias_precios_muestran_datos_historicos()
    {
        $precios = Traslado::where('usable_para_modelo', true)
            ->join('servicio', 'traslados.id_servicio', '=', 'servicio.id_servicio')
            ->orderBy('servicio.fecha_hora')
            ->pluck('precio_final');

        $this->assertCount(8, $precios);
        $this->assertGreaterThan($precios->last(), $precios->first());
    }
}
