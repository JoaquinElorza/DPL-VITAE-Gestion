<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    protected $primaryKey = 'id_empresa';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'mision',
        'vision',
        'descripcion',
        'logo',
        'logo_nombre',
        'logo_tipo',
        'imagen',
        'imagen_nombre',
        'imagen_tipo',
        'telefono',
        'correo',
        'direccion',
        'costo_km',
    ];
}