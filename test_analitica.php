<?php
try { 
    $c = new App\Http\Controllers\AnaliticaController(); 
    $c->extraerDatosLimpios(); 
    echo 'Exito'; 
} catch (\Exception $e) { 
    echo 'Error: ' . $e->getMessage(); 
}
