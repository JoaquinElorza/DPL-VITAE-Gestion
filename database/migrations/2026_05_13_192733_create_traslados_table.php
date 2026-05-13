use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traslados', function (Blueprint $table) {

            $table->id();

            // relación principal
            $table->foreignId('id_servicio')
                ->constrained('servicios')
                ->onDelete('cascade');

            // =========================
            // VARIABLES OPERATIVAS
            // =========================

            $table->float('km_distancia');

            $table->float('horas_servicio');

            // litros por minuto
            $table->float('oxigeno_lpm')->default(0);

            /*
            Valor numérico asignado
            según gravedad/padecimiento
            */
            $table->float('costo_padecimiento_num')->default(0);

            /*
            0 = básica
            1 = premium
            */
            $table->boolean('tipo_ambulancia_num');

            $table->integer('num_paramedicos')->default(0);

            // =========================
            // COSTOS DESGLOSADOS
            // =========================

            $table->float('costo_distancia')->default(0);

            $table->float('costo_horas')->default(0);

            $table->float('costo_oxigeno')->default(0);

            $table->float('costo_paramedicos')->default(0);

            $table->float('costo_insumos')->default(0);

            // =========================
            // MINERÍA / IA
            // =========================

            /*
            Precio sugerido
            por el modelo predictivo
            */
            $table->float('precio_modelo')->nullable();

            /*
            Precio final real
            */
            $table->float('precio_final');

            /*
            cluster asignado
            bajo / medio / alto
            */
            $table->string('cluster')->nullable();

            /*
            puntuación Z
            para detectar outliers
            */
            $table->float('z_score')->nullable();

            $table->boolean('es_outlier')->default(false);

            // =========================
            // INFORMACIÓN MÉDICA
            // =========================

            $table->text('padecimientos')->nullable();

            $table->text('observaciones_medicas')->nullable();

            // =========================
            // DATOS DE ENTRENAMIENTO
            // =========================

            /*
            indica si este dato
            puede usarse para entrenar
            */
            $table->boolean('usable_para_modelo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traslados');
    }
};