namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traslado extends Model
{
    protected $table = 'traslados';

    protected $fillable = [

        'id_servicio',

        'km_distancia',
        'horas_servicio',
        'oxigeno_lpm',
        'costo_padecimiento_num',
        'tipo_ambulancia_num',
        'num_paramedicos',

        'costo_distancia',
        'costo_horas',
        'costo_oxigeno',
        'costo_paramedicos',
        'costo_insumos',

        'precio_modelo',
        'precio_final',

        'cluster',

        'z_score',
        'es_outlier',

        'padecimientos',
        'observaciones_medicas',

        'usable_para_modelo',
    ];

    protected $casts = [

        'tipo_ambulancia_num' => 'boolean',

        'es_outlier' => 'boolean',

        'usable_para_modelo' => 'boolean',
    ];

    // =========================
    // RELACIONES
    // =========================

    public function servicio()
    {
        return $this->belongsTo(
            Servicio::class,
            'id_servicio'
        );
    }

        public function paciente()
    {
        return $this->hasOne(Paciente::class, 'id_servicio', 'id_servicio');
    }

    // =========================
    // HELPERS
    // =========================

    public function esPremium(): bool
    {
        return $this->tipo_ambulancia_num;
    }

    public function calcularCostoBase(): float
    {
        return
            $this->costo_distancia +
            $this->costo_horas +
            $this->costo_oxigeno +
            $this->costo_paramedicos +
            $this->costo_insumos +
            $this->costo_padecimiento_num;
    }
}