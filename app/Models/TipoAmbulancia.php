<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAmbulancia extends Model
{
    use SoftDeletes;

    protected $table = 'tipo_ambulancia';
    protected $primaryKey = 'id_tipo_ambulancia';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo',
        'descripcion',
        'costo_base',
    ];

    // RELACIÓN CORRECTA (1 tipo -> muchas ambulancias)
    public function ambulancias()
    {
        return $this->hasMany(Ambulancia::class, 'id_tipo_ambulancia', 'id_tipo_ambulancia');
    }

    // Scope útil (evita whereHas en controllers)
    public function scopeConDisponibles($query)
    {
        return $query->whereHas('ambulancias', function ($q) {
            $q->where('estado', 'Disponible');
        });
    }
}