<?php
$c = new App\Http\Controllers\AnaliticaController();
$view = $c->extraerDatosLimpios();
$data = $view->getData();
print_r([
    'totalServicios' => $data['totalServicios'],
    'labelsGrafica' => $data['labelsGrafica']->toArray(),
    'valoresGrafica' => $data['valoresGrafica']->toArray(),
    'labelsMeses' => $data['labelsMeses']->toArray(),
    'valoresMeses' => $data['valoresMeses']->toArray(),
]);
