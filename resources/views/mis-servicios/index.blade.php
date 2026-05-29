<x-layouts.app title="Mis Servicios Asignados">
    
    <style>
        .service-card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border-left: 6px solid var(--urgency-color, #393395);
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(57, 51, 149, 0.1) !important;
            border-color: rgba(57, 51, 149, 0.1);
        }
        .icon-box {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            flex-shrink: 0;
        }
        .btn-modern {
            background: linear-gradient(135deg, #393395 0%, #8A2BE2 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
            transition: all 0.4s ease;
            z-index: -1;
        }
        .btn-modern:hover::before {
            left: 0;
        }
        .btn-modern:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(138, 43, 226, 0.25);
        }
    </style>

    <div class="container-fluid mt-2">
        
        <!-- HERO BANNER -->
        <div class="card border-0 mb-4 shadow-sm" style="background: linear-gradient(135deg, #191970 0%, #393395 100%); color: white; border-radius: 1.25rem; overflow: hidden;">
            <div class="card-body p-4 p-md-5 position-relative">
                <!-- Abstract shapes for glassmorphism background -->
                <div class="position-absolute top-0 end-0 rounded-circle" style="width: 250px; height: 250px; background: rgba(138, 43, 226, 0.6); filter: blur(50px); transform: translate(30%, -30%); pointer-events: none;"></div>
                <div class="position-absolute bottom-0 start-0 rounded-circle" style="width: 180px; height: 180px; background: rgba(255, 127, 80, 0.3); filter: blur(40px); transform: translate(-20%, 30%); pointer-events: none;"></div>
                
                <div class="position-relative" style="z-index: 2;">
                    <h2 class="fw-bold text-white mb-2" style="letter-spacing: -0.5px;"><i class="bx bx-run me-2"></i>Tus Traslados Asignados</h2>
                    <p class="mb-0 fs-6" style="color: rgba(255,255,255,0.85);">
                        @if($servicios->count() > 0)
                            Tienes <strong>{{ $servicios->count() }} servicio(s)</strong> programado(s) en tu agenda. ¡Buen turno!
                        @else
                            Tu agenda está libre por ahora. Mantente atento.
                        @endif
                    </p>
                </div>
            </div>
        </div>



        @if($servicios->isEmpty())
            <!-- EMPTY STATE -->
            <div class="card border-0 rounded-4 shadow-sm text-center py-5">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 100px; height: 100px; background: rgba(138, 43, 226, 0.05);">
                            <i class="bx bx-coffee fs-1" style="color: #8A2BE2;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Todo tranquilo por aquí</h5>
                    <p class="text-muted mb-0">No tienes ningún traslado asignado en este momento.<br>Te notificaremos cuando haya algo nuevo.</p>
                </div>
            </div>
        @else
            <!-- SERVICIOS GRID -->
            <div class="row g-4">
                @foreach($servicios as $servicio)
                    @php
                        $now = \Carbon\Carbon::now();
                        $fecha = $servicio->fecha_hora ? \Carbon\Carbon::parse($servicio->fecha_hora) : null;
                        $textoUrgencia = 'Sin fecha límite';
                        $colorUrgencia = '#393395'; // default primary
                        $bgBadgeUrgencia = 'rgba(57, 51, 149, 0.1)';
                        $textBadgeUrgencia = '#393395';
                        
                        if ($fecha) {
                            if ($fecha->isPast()) {
                                $textoUrgencia = 'Servicio Retrasado (' . $fecha->diffForHumans() . ')';
                                $colorUrgencia = '#F08080'; // Coral
                                $bgBadgeUrgencia = 'rgba(240, 128, 128, 0.15)';
                                $textBadgeUrgencia = '#F08080';
                            } elseif ($fecha->isToday()) {
                                $horasFaltantes = $now->diffInHours($fecha, false);
                                if($horasFaltantes <= 2 && $horasFaltantes >= 0) {
                                    $textoUrgencia = '¡Pronto! En ' . $now->diff($fecha)->format('%h h %i min');
                                    $colorUrgencia = '#FFB020'; // Warning
                                    $bgBadgeUrgencia = 'rgba(255, 176, 32, 0.15)';
                                    $textBadgeUrgencia = '#FFB020';
                                } else {
                                    $textoUrgencia = 'Hoy a las ' . $fecha->format('H:i');
                                    $colorUrgencia = '#11998E'; // Green
                                    $bgBadgeUrgencia = 'rgba(17, 153, 142, 0.1)';
                                    $textBadgeUrgencia = '#11998E';
                                }
                            } else {
                                $textoUrgencia = 'Próximo ' . $fecha->diffForHumans();
                                $colorUrgencia = '#8A2BE2'; // Violet
                                $bgBadgeUrgencia = 'rgba(138, 43, 226, 0.1)';
                                $textBadgeUrgencia = '#8A2BE2';
                            }
                        }
                    @endphp

                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 rounded-4 shadow-sm service-card bg-white" style="--urgency-color: {{ $colorUrgencia }};">
                            
                            <!-- Card Header (Urgencia & Tipo) -->
                            <div class="card-header bg-transparent border-0 pt-4 pb-2 d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="badge rounded-pill fw-semibold mb-2" style="background-color: {{ $bgBadgeUrgencia }}; color: {{ $textBadgeUrgencia }}; padding: 0.4em 0.8em; font-size: 0.75rem;">
                                        <i class="bx bx-time-five me-1"></i>{{ $textoUrgencia }}
                                    </span>
                                    <div class="text-muted small fw-medium">
                                        {{ $fecha ? $fecha->format('d M, Y - H:i') : 'Sin fecha asignada' }}
                                    </div>
                                </div>
                                <span class="badge bg-light text-secondary rounded-pill border" style="font-size: 0.7rem;">
                                    <i class="bx bx-category-alt me-1"></i>{{ $servicio->tipo }}
                                </span>
                            </div>

                            <!-- Card Body (Info) -->
                            <div class="card-body pt-2 pb-4 d-flex flex-column">
                                <h5 class="fw-bold mb-4 d-flex align-items-center" style="color: #191970;">
                                    <div class="icon-box me-3" style="background: linear-gradient(135deg, rgba(138,43,226,0.1), rgba(138,43,226,0.2)); color: #8A2BE2;">
                                        <i class="bx bx-user fs-4"></i>
                                    </div>
                                    <span class="text-truncate">{{ $servicio->paciente?->nombre ?? 'Paciente no especificado' }}</span>
                                </h5>
                                
                                <div class="d-flex flex-column gap-3 mb-4 flex-grow-1">
                                    <!-- Destino -->
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box me-3" style="background-color: rgba(240, 128, 128, 0.1); color: #F08080;">
                                            <i class="bx bx-map-pin fs-5"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <small class="text-muted d-block mb-0" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Destino</small>
                                            <div class="fw-medium text-dark text-truncate">{{ $servicio->paciente?->direccion?->colonia?->nombre_colonia ?? 'Ubicación pendiente' }}</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Unidad -->
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box me-3" style="background-color: rgba(57, 51, 149, 0.08); color: #393395;">
                                            <i class="bx bx-bus fs-5"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <small class="text-muted d-block mb-0" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Unidad Asignada</small>
                                            <div class="fw-medium text-dark text-truncate">{{ $servicio->ambulancia?->numero_economico ?? 'Sin unidad' }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botón Acción -->
                                <div class="d-grid mt-auto pt-2">
                                    <a href="{{ route('mis-servicios.show', $servicio->id_servicio) }}" class="btn btn-modern rounded-pill py-2 fw-semibold">
                                        Ver detalles <i class="bx bx-right-arrow-alt ms-1 align-middle fs-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>