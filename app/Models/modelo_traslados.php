<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class modelo_traslados extends Model
{
    
    protected $table = 'modelo_traslados';
    protected $primaryKey = 'id_modelo_servicio';

    protected $filliable = [
      'b0',
      'b_distancia',
      'b_horas',
      'b_oxigeno',
      'b_padecimiento',
      'b_ambulancia', 
    ];

}
