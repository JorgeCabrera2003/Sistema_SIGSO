<?php require_once("Componentes/head.php"); ?>

<body>
    <?php require_once("Componentes/menu.php"); ?>

    <div class="pagetitle mb-4">
        <h1>Reportes</h1>
        <nav>
            
        </nav>
    </div><!-- End Page Title -->

    <!-- SECCIONES DE REPORTES ESTADÍSTICOS -->
    <div class="row mb-4">
        <!-- Agrupar los reportes en filas de 2 columnas responsivas -->
        <div class="col-12">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Reporte de Eficiencia por Técnico</h5>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="generarReporteEficienciaTecnicos()">Generar</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="generarPDF('eficiencia_tecnicos')"><i class="fa fa-file-pdf"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <input type="date" id="fecha_inicio_eficiencia_tecnicos" class="form-control form-control-sm" placeholder="Fecha inicio">
                                </div>
                                <div class="col">
                                    <input type="date" id="fecha_fin_eficiencia_tecnicos" class="form-control form-control-sm" placeholder="Fecha fin">
                                </div>
                            </div>
                            <canvas id="chartEficienciaTecnicos" height="80"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Reporte de Tiempos de Respuesta</h5>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="generarReporteTiemposRespuesta()">Generar</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="generarPDF('tiempos_respuesta')"><i class="fa fa-file-pdf"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <input type="date" id="fecha_inicio_tiempos_respuesta" class="form-control form-control-sm" placeholder="Fecha inicio">
                                </div>
                                <div class="col">
                                    <input type="date" id="fecha_fin_tiempos_respuesta" class="form-control form-control-sm" placeholder="Fecha fin">
                                </div>
                            </div>
                            <canvas id="chartTiemposRespuesta" height="80"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Reporte de Utilización de Materiales</h5>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="generarReporteUtilizacionMateriales()">Generar</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="generarPDF('utilizacion_materiales')"><i class="fa fa-file-pdf"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <input type="date" id="fecha_inicio_utilizacion_materiales" class="form-control form-control-sm" placeholder="Fecha inicio">
                                </div>
                                <div class="col">
                                    <input type="date" id="fecha_fin_utilizacion_materiales" class="form-control form-control-sm" placeholder="Fecha fin">
                                </div>
                            </div>
                            <canvas id="chartUtilizacionMateriales" height="80"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Reporte de Estado de Equipos</h5>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="generarReporteEstadoEquipos()">Generar</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="generarPDF('estado_equipos')"><i class="fa fa-file-pdf"></i></button>
                            </div>
                        </div>
                        <div class="card-body py-2">
                            <div class="row mb-2">
                                <div class="col">
                                    <input type="date" id="fecha_inicio_estado_equipos" class="form-control form-control-sm" placeholder="Fecha inicio">
                                </div>
                                <div class="col">
                                    <input type="date" id="fecha_fin_estado_equipos" class="form-control form-control-sm" placeholder="Fecha fin">
                                </div>
                            </div>
                            <div class="mb-2">
                                <select id="filtroEstadoEquipo" class="form-select form-select-sm w-auto d-inline-block" onchange="generarReporteEstadoEquipos()">
                                    <option value="">Todos</option>
                                    <option value="Nuevo">Nuevo</option>
                                    <option value="Usado">Usado</option>
                                    <option value="Dañado">Dañado</option>
                                </select>
                            </div>
                            <canvas id="chartEstadoEquipos" height="40" style="max-height:140px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Reporte de Estado de Infraestructura</h5>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="generarReporteEstadoInfraestructura()">Generar</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="generarPDF('estado_infraestructura')"><i class="fa fa-file-pdf"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <input type="date" id="fecha_inicio_estado_infraestructura" class="form-control form-control-sm" placeholder="Fecha inicio">
                                </div>
                                <div class="col">
                                    <input type="date" id="fecha_fin_estado_infraestructura" class="form-control form-control-sm" placeholder="Fecha fin">
                                </div>
                            </div>
                            <canvas id="chartEstadoInfraestructura" height="80"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Reporte de Tendencias de Solicitudes</h5>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="generarReporteTendenciasSolicitudes()">Generar</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="generarPDF('tendencias_solicitudes')"><i class="fa fa-file-pdf"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <input type="date" id="fecha_inicio_tendencias_solicitudes" class="form-control form-control-sm" placeholder="Fecha inicio">
                                </div>
                                <div class="col">
                                    <input type="date" id="fecha_fin_tendencias_solicitudes" class="form-control form-control-sm" placeholder="Fecha fin">
                                </div>
                            </div>
                            <canvas id="chartTendenciasSolicitudes" height="80"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Reporte de Reincidencia de Problemas</h5>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="generarReporteReincidenciaProblemas()">Generar</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="generarPDF('reincidencia_problemas')"><i class="fa fa-file-pdf"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <input type="date" id="fecha_inicio_reincidencia_problemas" class="form-control form-control-sm" placeholder="Fecha inicio">
                                </div>
                                <div class="col">
                                    <input type="date" id="fecha_fin_reincidencia_problemas" class="form-control form-control-sm" placeholder="Fecha fin">
                                </div>
                            </div>
                            <canvas id="chartReincidenciaProblemas" height="80"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Reporte Ejecutivo de KPIs</h5>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="generarReporteKPIs()">Generar</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="generarPDF('kpis')"><i class="fa fa-file-pdf"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <input type="date" class="form-control form-control-sm" id="fecha_inicio_kpis" placeholder="Fecha inicio">
                                </div>
                                <div class="col">
                                    <input type="date" class="form-control form-control-sm" id="fecha_fin_kpis" placeholder="Fecha fin">
                                </div>
                            </div>
                            <canvas id="chartKPIs" height="80"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Reporte de Carga de Trabajo por Técnico</h5>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-1" onclick="generarReporteCargaTrabajo()">Generar</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="generarPDF('carga_trabajo')"><i class="fa fa-file-pdf"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <input type="date" class="form-control form-control-sm" id="fecha_inicio_carga_trabajo" placeholder="Fecha inicio">
                                </div>
                                <div class="col">
                                    <input type="date" class="form-control form-control-sm" id="fecha_fin_carga_trabajo" placeholder="Fecha fin">
                                </div>
                            </div>
                            <canvas id="chartCargaTrabajo" height="80"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin de SECCIONES DE REPORTES ESTADÍSTICOS -->

    <div class="row">
        <!-- Filtros -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                 </div>
                <div class="card-body">
                    <form id="filtrosInfraestructura">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="tipoDispositivo" class="form-label">Tipo de Dispositivo</label>
                                <select class="form-select" id="tipoDispositivo" name="tipoDispositivo">
                                    <option value="patch">Patch Panel</option>
                                    <option value="switch">Switch</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="pisoFiltro" class="form-label">Piso</label>
                                <select class="form-select" id="pisoFiltro" name="pisoFiltro">
                                    <option value="0">Seleccione un piso</option>
                                    <?php foreach ($piso as $pisoItem) : ?>
                                        <option value="<?= $pisoItem['id_piso'] ?>">
                                            <?= $pisoItem['tipo_piso'] . ' ' . $pisoItem['nro_piso'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-primary" onclick="cargarInfraestructura()">
                                    <i class="bi bi-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Información del Piso -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <h4 id="tituloPiso" class="card-title">Seleccione un piso para ver la infraestructura</h4>
                    <p id="infoPiso" class="card-text text-muted">Los puertos se mostrarán según su disponibilidad</p>
                </div>
            </div>
        </div>

        <!-- Contenedor de dispositivos -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Dispositivos de Red</h5>
                    <div class="d-flex">
                        <span class="badge bg-success me-2"><i class="fas fa-circle"></i> Disponible</span>
                        <span class="badge bg-danger me-2"><i class="fas fa-circle"></i> Ocupado</span>
                        <span class="badge bg-warning"><i class="fas fa-circle"></i> Dañado</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="loadingInfraestructura" class="text-center my-5" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando dispositivos...</p>
                    </div>
                    <div id="contenedorDispositivos" class="row">
                        <div class="col-12 text-center">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> Utilice los filtros para visualizar los dispositivos de red
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para detalles del puerto -->
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesTitulo">Detalles del Puerto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalDetallesCuerpo">
                    <!-- Contenido dinámico -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "Componentes/footer.php"; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- JavaScript para la gestión de infraestructura -->
    <script>
        $(document).ready(function() {
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Seleccionar por defecto el piso 5 si existe
            function seleccionarPisoPorDefecto() {
                let $select = $('#pisoFiltro');
                let piso5 = $select.find('option').filter(function() {
                    return $(this).text().trim().endsWith('5');
                }).first();

                if (piso5.length) {
                    $select.val(piso5.val());
                    // Cargar automáticamente la infraestructura del piso 5
                    setTimeout(function() {
                        cargarInfraestructura();
                    }, 500);
                }
            }

            seleccionarPisoPorDefecto();
        });

        function cargarInfraestructura() {
            const tipoDispositivo = $('#tipoDispositivo').val();
            const idPiso = $('#pisoFiltro').val();

            if (!idPiso || idPiso === "0") {
                mostrarAlerta('warning', 'Por favor seleccione un piso');
                return;
            }

            // Obtener información del piso seleccionado
            const pisoTexto = $('#pisoFiltro option:selected').text();
            $('#tituloPiso').html(`Infraestructura de ${tipoDispositivo === 'patch' ? 'Patch Panels' : 'Switches'} - ${pisoTexto}`);
            $('#infoPiso').text('Cargando información de puertos...');

            $('#loadingInfraestructura').show();
            $('#contenedorDispositivos').html('');

            // Realizar petición AJAX
            $.ajax({
                url: "",
                type: "POST",
                data: {
                    peticion: 'obtener_infraestructura',
                    tipo: tipoDispositivo,
                    id_piso: idPiso
                },
                success: function(respuesta) {
                    $('#loadingInfraestructura').hide();
                    try {
                        const data = JSON.parse(respuesta);
                        if (data.resultado === "success") {
                            mostrarDispositivos(data.datos, tipoDispositivo, pisoTexto);
                        } else {
                            mostrarErrorInfraestructura(data.mensaje || 'Error al cargar los datos');
                        }
                    } catch (e) {
                        console.error("Error parsing JSON: ", e);
                        mostrarErrorInfraestructura('Error al procesar la respuesta del servidor');
                    }
                },
                error: function() {
                    $('#loadingInfraestructura').hide();
                    mostrarErrorInfraestructura('Error de conexión con el servidor');
                }
            });
        }

        function mostrarDispositivos(dispositivos, tipo, pisoTexto) {
            let html = '';

            if (!dispositivos || dispositivos.length === 0) {
                html = `<div class="col-12 text-center">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> No se encontraron ${tipo === 'patch' ? 'patch panels' : 'switches'} en este piso
                            </div>
                        </div>`;
                $('#infoPiso').text('No hay dispositivos en este piso');
            } else {
                $('#infoPiso').text(`${dispositivos.length} ${tipo === 'patch' ? 'patch panels' : 'switches'} encontrados`);

                dispositivos.forEach(dispositivo => {
                    html += `<div class="col-md-6 col-lg-6 mb-4">
                                <div class="card h-100 dispositivo-card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">${dispositivo.nombre || 'Dispositivo'}</h6>
                                        <small class="text-muted">Serial: ${dispositivo.serial || 'N/A'}</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-4">
                                            <span class="badge bg-info">${dispositivo.cantidad_puertos} Puertos</span>
                                            <span class="badge bg-secondary">${dispositivo.puertos_ocupados} Ocupados</span>
                                        </div>
                                        <div class="puertos-container">
                                            ${generarPuertos(dispositivo.puertos, dispositivo.codigo_bien, tipo)}
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                });
            }

            $('#contenedorDispositivos').html(html);

            // Reinicializar tooltips para los nuevos elementos
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        function generarPuertos(puertos, codigoDispositivo, tipoDispositivo) {
            if (!puertos || puertos.length === 0) {
                return '<div class="alert alert-secondary text-center">No hay información de puertos</div>';
            }

            const totalPuertos = puertos.length;
            let filas = 1;

            if (totalPuertos >= 96) {
                filas = 4;
            } else if (totalPuertos > 24 && totalPuertos <= 48) {
                filas = 2;
            }

            // calcular cuántos puertos por fila
            const puertosPorFila = Math.ceil(totalPuertos / filas);

            // Definir tamaño del grupo (6 o 8)
            let grupo = 6;
            if (totalPuertos % 6 !== 0 && totalPuertos % 8 === 0) {
                grupo = 8;
            }

            let html = '';

            for (let f = 0; f < filas; f++) {
                html += '<div class="d-flex mb-2 flex-wrap fila-puertos">';

                for (let i = f * puertosPorFila; i < (f + 1) * puertosPorFila && i < totalPuertos; i++) {
                    const puerto = puertos[i];

                    // Determinar clase CSS según el estado
                    let claseEstado = 'disponible';
                    let icono = 'fa-ethernet';
                    let tooltip = 'Puerto disponible';

                    if (puerto.ocupado) {
                        claseEstado = 'ocupado';
                        tooltip = 'Puerto ocupado';
                        if (puerto.con_equipo) {
                            tooltip += ` - Equipo: ${puerto.equipo_nombre || 'N/A'}`;
                        }
                    } else if (puerto.danado) {
                        claseEstado = 'danado';
                        tooltip = 'Puerto dañado';
                    }

                    let infoExtra = '';
                    if (puerto.equipo_nombre) {
                        infoExtra += `<div><strong>Equipo:</strong> ${puerto.equipo_nombre}</div>`;
                    }
                    if (puerto.empleado_nombre) {
                        infoExtra += `<div><strong>Empleado:</strong> ${puerto.empleado_nombre}</div>`;
                    }
                    if (puerto.oficina_nombre) {
                        infoExtra += `<div><strong>Oficina:</strong> ${puerto.oficina_nombre}</div>`;
                    }

                    // separación cada X puertos (6 o 8 según corresponda)
                    if ((i - f * puertosPorFila) % grupo === 0 && i % puertosPorFila !== 0) {
                        html += `<div class="grupo-separador"></div>`;
                    }

                    html += `<div class="puerto ${claseEstado}" 
                        data-bs-toggle="tooltip" 
                        data-bs-html="true"
                        data-bs-title="<div><strong>Puerto #${puerto.numero}</strong></div>
                                       <div><strong>Estado:</strong> ${tooltip}</div>
                                       ${infoExtra}"
                        onclick="mostrarDetallesPuerto('${codigoDispositivo}', ${puerto.numero}, '${tipoDispositivo}')">
                        <i class="fa-solid ${icono}"></i>
                        <small>${puerto.numero}</small>
                     </div>`;
                }

                html += '</div>';
            }

            return html;
        }

        function mostrarDetallesPuerto(codigoDispositivo, numeroPuerto, tipoDispositivo) {
            $('#modalDetallesTitulo').text(`Detalles del Puerto #${numeroPuerto}`);
            $('#modalDetallesCuerpo').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div><p class="mt-2">Cargando detalles...</p></div>');

            $.ajax({
                url: "",
                type: "POST",
                data: {
                    peticion: 'detalles_puerto',
                    codigo_dispositivo: codigoDispositivo,
                    numero_puerto: numeroPuerto,
                    tipo: tipoDispositivo
                },
                success: function(respuesta) {
                    try {
                        const data = JSON.parse(respuesta);
                        if (data.resultado === "success") {
                            let html = `<div class="row">
                                            <div class="col-md-6">
                                                <h6>Información del Puerto</h6>
                                                <p><strong>Número:</strong> ${data.datos.numero || 'N/A'}</p>
                                                <p><strong>Estado:</strong> ${data.datos.ocupado ? '<span class="badge bg-danger">Ocupado</span>' : (data.datos.danado ? '<span class="badge bg-warning">Dañado</span>' : '<span class="badge bg-success">Disponible</span>')}</p>
                                                <p><strong>Dispositivo:</strong> ${data.datos.dispositivo_nombre || 'N/A'}</p>
                                            </div>`;

                            if (data.datos.ocupado && data.datos.con_equipo) {
                                html += `<div class="col-md-6">
                                            <h6>Información de Conexión</h6>
                                            <p><strong>Equipo:</strong> ${data.datos.equipo_nombre || 'N/A'}</p>
                                            <p><strong>Tipo:</strong> ${data.datos.equipo_tipo || 'N/A'}</p>
                                            <p><strong>Serial:</strong> ${data.datos.equipo_serial || 'N/A'}</p>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <h6>Información del Empleado</h6>
                                            <p><strong>Nombre:</strong> ${data.datos.empleado_nombre || 'N/A'}</p>
                                            <p><strong>Cédula:</strong> ${data.datos.empleado_cedula || 'N/A'}</p>
                                            <p><strong>Correo:</strong> ${data.datos.empleado_correo || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Ubicación</h6>
                                            <p><strong>Oficina:</strong> ${data.datos.oficina_nombre || 'N/A'}</p>
                                            <p><strong>Piso:</strong> ${data.datos.piso_nombre || 'N/A'}</p>
                                        </div>
                                    </div>`;
                            } else {
                                html += `<div class="col-md-6">
                                            <h6>Disponible para conexión</h6>
                                            <p>Este puerto está disponible para conectar nuevos equipos.</p>
                                            <button class="btn btn-sm btn-outline-primary">Reservar Puerto</button>
                                        </div>
                                    </div>`;
                            }

                            $('#modalDetallesCuerpo').html(html);
                        } else {
                            $('#modalDetallesCuerpo').html(`<div class="alert alert-danger">${data.mensaje || 'Error al cargar los detalles'}</div>`);
                        }
                    } catch (e) {
                        console.error("Error parsing JSON: ", e);
                        $('#modalDetallesCuerpo').html('<div class="alert alert-danger">Error al procesar la respuesta del servidor</div>');
                    }
                },
                error: function() {
                    $('#modalDetallesCuerpo').html('<div class="alert alert-danger">Error de conexión con el servidor</div>');
                }
            });

            $('#modalDetalles').modal('show');
        }

        function mostrarErrorInfraestructura(mensaje) {
            $('#contenedorDispositivos').html(`<div class="col-12 text-center">
                                                <div class="alert alert-danger">
                                                    <i class="bi bi-exclamation-triangle"></i> ${mensaje}
                                                </div>
                                              </div>`);
            $('#infoPiso').text('Error al cargar la información');
        }

        function mostrarAlerta(tipo, mensaje) {
            // Implementar función de alerta si es necesario
            console.log(`${tipo}: ${mensaje}`);
        }

        function generarPDF(tipoReporte) {
            const fechaInicio = $(`#fecha_inicio_${tipoReporte}`).val();
            const fechaFin = $(`#fecha_fin_${tipoReporte}`).val();

            // Validar fechas
            if (!fechaInicio || !fechaFin) {
                return mostrarAlerta('warning', 'Por favor seleccione las fechas de inicio y fin');
            }

            // Redirigir a la página de generación de PDF
            window.location.href = `generar_pdf.php?reporte=${tipoReporte}&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
        }

        // Asegura que renderChart esté definida antes de cualquier llamada
        function renderChart(canvasId, chartType, labels, data, label, colors) {
            if (typeof Chart === "undefined") {
                console.error("Chart.js no está cargado");
                return;
            }
            if (window.charts === undefined) window.charts = {};
            if (window.charts[canvasId] && typeof window.charts[canvasId].destroy === 'function') {
                window.charts[canvasId].destroy();
            }
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            window.charts[canvasId] = new Chart(ctx, {
                type: chartType,
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: colors || 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: true } }
                }
            });
        }

        function generarReporteEficienciaTecnicos() {
            var fecha_inicio = $('#fecha_inicio_eficiencia_tecnicos').val();
            var fecha_fin = $('#fecha_fin_eficiencia_tecnicos').val();
            $.post('', { peticion: 'reporte_eficiencia_tecnicos', fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
                try {
                    const res = JSON.parse(resp);
                    if (res.resultado === 'reporte_eficiencia_tecnicos') {
                        const labels = res.datos.map(x => x['Técnico'] + ' (' + x['Área'] + ')');
                        const data = res.datos.map(x => parseFloat(x['Tiempo Promedio (horas)'] || 0));
                        renderChart('chartEficienciaTecnicos', 'bar', labels, data, 'Horas promedio');
                    }
                } catch (e) {
                    console.error("Error en reporte eficiencia:", e);
                }
            }).fail(function(xhr, status, error) {
                console.error("AJAX Error eficiencia:", error);
            });
        }

        function generarReporteTiemposRespuesta() {
            var fecha_inicio = $('#fecha_inicio_tiempos_respuesta').val();
            var fecha_fin = $('#fecha_fin_tiempos_respuesta').val();
            $.post('', { peticion: 'reporte_tiempos_respuesta', fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
                try {
                    const res = JSON.parse(resp);
                    if (res.resultado === 'reporte_tiempos_respuesta') {
                        const labels = res.datos.map(x => x['nombre_tipo_servicio']);
                        const data = res.datos.map(x => parseFloat(x['tiempo_promedio_horas'] || 0));
                        renderChart('chartTiemposRespuesta', 'bar', labels, data, 'Horas promedio');
                    }
                } catch (e) {
                    console.error("Error en reporte tiempos respuesta:", e);
                }
            }).fail(function(xhr, status, error) {
                console.error("AJAX Error tiempos respuesta:", error);
            });
        }

        function generarReporteUtilizacionMateriales() {
            var fecha_inicio = $('#fecha_inicio_utilizacion_materiales').val();
            var fecha_fin = $('#fecha_fin_utilizacion_materiales').val();
            $.post('', { peticion: 'reporte_utilizacion_materiales', fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
                try {
                    const res = JSON.parse(resp);
                    if (res.resultado === 'reporte_utilizacion_materiales') {
                        // Validar que hay datos
                        if (Array.isArray(res.datos) && res.datos.length > 0) {
                            const labels = res.datos.map(x => x['Material'] + ' (' + x['Ubicación'] + ')');
                            const data = res.datos.map(x => parseInt(x['Cantidad Utilizada'] || 0));
                            renderChart('chartUtilizacionMateriales', 'doughnut', labels, data, 'Cantidad utilizada');
                        } else {
                            // Mostrar mensaje si no hay datos
                            $('#chartUtilizacionMateriales').replaceWith('<div id="chartUtilizacionMateriales" class="text-center text-muted">No hay datos de utilización de materiales.</div>');
                        }
                    } else {
                        $('#chartUtilizacionMateriales').replaceWith('<div id="chartUtilizacionMateriales" class="text-center text-danger">Error al cargar el reporte de materiales.</div>');
                    }
                } catch (e) {
                    console.error("Error en reporte utilización materiales:", e);
                    $('#chartUtilizacionMateriales').replaceWith('<div id="chartUtilizacionMateriales" class="text-center text-danger">Error al procesar la respuesta del servidor.</div>');
                }
            }).fail(function(xhr, status, error) {
                console.error("AJAX Error utilización materiales:", error);
                $('#chartUtilizacionMateriales').replaceWith('<div id="chartUtilizacionMateriales" class="text-center text-danger">Error de conexión al servidor.</div>');
            });
        }

        function generarReporteEstadoEquipos() {
            var estado = $('#filtroEstadoEquipo').val() || null;
            var fecha_inicio = $('#fecha_inicio_estado_equipos').val();
            var fecha_fin = $('#fecha_fin_estado_equipos').val();
            $.post('', { peticion: 'reporte_estado_equipos', estado: estado, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
                try {
                    const res = JSON.parse(resp);
                    if (res.resultado === 'reporte_estado_equipos') {
                        const labels = res.datos.map(x => x['descripcion'] + ' (' + x['estado'] + ')');
                        const data = res.datos.map(x => parseInt(x['cantidad'] || 0));
                        renderChart('chartEstadoEquipos', 'pie', labels, data, 'Cantidad');
                    }
                } catch (e) {
                    console.error("Error en reporte estado equipos:", e);
                }
            }).fail(function(xhr, status, error) {
                console.error("AJAX Error estado equipos:", error);
            });
        }

        function generarReporteEstadoInfraestructura() {
            var fecha_inicio = $('#fecha_inicio_estado_infraestructura').val();
            var fecha_fin = $('#fecha_fin_estado_infraestructura').val();
            $.post('', { peticion: 'reporte_estado_infraestructura', id_piso: 1, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
                try {
                    const res = JSON.parse(resp);
                    if (res.resultado === 'reporte_estado_infraestructura') {
                        const labels = ['Total', 'Ocupado', 'Disponible', 'Patch Panels'];
                        const datos = res.datos[0];
                        const data = [
                            parseInt(datos['Cantidad Total'] || 0),
                            parseInt(datos['Cantidad Ocupado'] || 0),
                            parseInt(datos['Cantidad Disponible'] || 0),
                            parseInt(datos['Cantidad Patch Panel'] || 0)
                        ];
                        renderChart('chartEstadoInfraestructura', 'bar', labels, data, 'Infraestructura');
                    }
                } catch (e) {
                    console.error("Error en reporte estado infraestructura:", e);
                }
            }).fail(function(xhr, status, error) {
                console.error("AJAX Error estado infraestructura:", error);
            });
        }

        function generarReporteTendenciasSolicitudes() {
            var fecha_inicio = $('#fecha_inicio_tendencias_solicitudes').val();
            var fecha_fin = $('#fecha_fin_tendencias_solicitudes').val();
            $.post('', { peticion: 'reporte_tendencias_solicitudes', fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
                try {
                    const res = JSON.parse(resp);
                    if (res.resultado === 'reporte_tendencias_solicitudes') {
                        const labels = res.datos.map(x => x['mes']);
                        const data = res.datos.map(x => parseInt(x['total'] || 0));
                        renderChart('chartTendenciasSolicitudes', 'line', labels, data, 'Solicitudes');
                    }
                } catch (e) {
                    console.error("Error en reporte tendencias solicitudes:", e);
                }
            }).fail(function(xhr, status, error) {
                console.error("AJAX Error tendencias solicitudes:", error);
            });
        }

        function generarReporteReincidenciaProblemas() {
            var fecha_inicio = $('#fecha_inicio_reincidencia_problemas').val();
            var fecha_fin = $('#fecha_fin_reincidencia_problemas').val();
            $.post('', { peticion: 'reporte_reincidencia_problemas', fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
                try {
                    const res = JSON.parse(resp);
                    if (res.resultado === 'reporte_reincidencia_problemas') {
                        const labels = res.datos.map(x => x['motivo']);
                        const data = res.datos.map(x => parseInt(x['veces_reportado'] || 0));
                        renderChart('chartReincidenciaProblemas', 'bar', labels, data, 'Reincidencias');
                    }
                } catch (e) {
                    console.error("Error en reporte reincidencia problemas:", e);
                }
            }).fail(function(xhr, status, error) {
                console.error("AJAX Error reincidencia problemas:", error);
            });
        }

        function generarReporteKPIs() {
            $.post('', { peticion: 'reporte_kpis' }, function(resp) {
                try {
                    const res = JSON.parse(resp);
                    if (res.resultado === 'reporte_kpis') {
                        const labels = ['Solicitudes', 'Hojas Finalizadas', 'Hojas Activas', 'Equipos Dañados', 'Materiales Bajo Stock'];
                        const datos = res.datos;
                        const data = [
                            parseInt(datos['total_solicitudes'] || 0),
                            parseInt(datos['hojas_finalizadas'] || 0),
                            parseInt(datos['hojas_activas'] || 0),
                            parseInt(datos['equipos_danados'] || 0),
                            parseInt(datos['materiales_bajo_stock'] || 0)
                        ];
                        renderChart('chartKPIs', 'bar', labels, data, 'KPIs');
                    }
                } catch (e) {
                    console.error("Error en reporte KPIs:", e);
                }
            }).fail(function(xhr, status, error) {
                console.error("AJAX Error KPIs:", error);
            });
        }

        function generarReporteCargaTrabajo() {
            $.post('', { peticion: 'reporte_carga_trabajo' }, function(resp) {
                try {
                    const res = JSON.parse(resp);
                    if (res.resultado === 'reporte_carga_trabajo') {
                        const labels = res.datos.map(x => x['nombre']);
                        const data = res.datos.map(x => parseInt(x['hojas_asignadas'] || 0));
                        renderChart('chartCargaTrabajo', 'bar', labels, data, 'Hojas asignadas');
                    }
                } catch (e) {
                    console.error("Error en reporte carga trabajo:", e);
                }
            }).fail(function(xhr, status, error) {
                console.error("AJAX Error carga trabajo:", error);
            });
        }

        // Funciones existentes para infraestructura (mantener compatibilidad)
        function cargarInfraestructura() {
            const tipoDispositivo = $('#tipoDispositivo').val();
            const idPiso = $('#pisoFiltro').val();

            if (!idPiso || idPiso === "0") {
                mostrarAlerta('warning', 'Por favor seleccione un piso');
                return;
            }

            // Obtener información del piso seleccionado
            const pisoTexto = $('#pisoFiltro option:selected').text();
            $('#tituloPiso').html(`Infraestructura de ${tipoDispositivo === 'patch' ? 'Patch Panels' : 'Switches'} - ${pisoTexto}`);
            $('#infoPiso').text('Cargando información de puertos...');

            $('#loadingInfraestructura').show();
            $('#contenedorDispositivos').html('');

            // Realizar petición AJAX
            $.ajax({
                url: "",
                type: "POST",
                data: {
                    peticion: 'obtener_infraestructura',
                    tipo: tipoDispositivo,
                    id_piso: idPiso
                },
                success: function(respuesta) {
                    $('#loadingInfraestructura').hide();
                    try {
                        const data = JSON.parse(respuesta);
                        if (data.resultado === "success") {
                            mostrarDispositivos(data.datos, tipoDispositivo, pisoTexto);
                        } else {
                            mostrarErrorInfraestructura(data.mensaje || 'Error al cargar los datos');
                        }
                    } catch (e) {
                        console.error("Error parsing JSON: ", e);
                        mostrarErrorInfraestructura('Error al procesar la respuesta del servidor');
                    }
                },
                error: function(xhr, status, error) {
                    $('#loadingInfraestructura').hide();
                    console.error("AJAX Error infraestructura:", error);
                    mostrarErrorInfraestructura('Error de conexión con el servidor');
                }
            });
        }

        function mostrarDispositivos(dispositivos, tipo, pisoTexto) {
            let html = '';

            if (!dispositivos || dispositivos.length === 0) {
                html = `<div class="col-12 text-center">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> No se encontraron ${tipo === 'patch' ? 'patch panels' : 'switches'} en este piso
                            </div>
                        </div>`;
                $('#infoPiso').text('No hay dispositivos en este piso');
            } else {
                $('#infoPiso').text(`${dispositivos.length} ${tipo === 'patch' ? 'patch panels' : 'switches'} encontrados`);

                dispositivos.forEach(dispositivo => {
                    html += `<div class="col-md-6 col-lg-6 mb-4">
                                <div class="card h-100 dispositivo-card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">${dispositivo.nombre || 'Dispositivo'}</h6>
                                        <small class="text-muted">Serial: ${dispositivo.serial || 'N/A'}</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-4">
                                            <span class="badge bg-info">${dispositivo.cantidad_puertos} Puertos</span>
                                            <span class="badge bg-secondary">${dispositivo.puertos_ocupados} Ocupados</span>
                                        </div>
                                        <div class="puertos-container">
                                            ${generarPuertos(dispositivo.puertos, dispositivo.codigo_bien, tipo)}
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                });
            }

            $('#contenedorDispositivos').html(html);

            // Reinicializar tooltips para los nuevos elementos
            if ($('[data-bs-toggle="tooltip"]').length > 0) {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        }

        function generarPuertos(puertos, codigoDispositivo, tipoDispositivo) {
            if (!puertos || puertos.length === 0) {
                return '<div class="alert alert-secondary text-center">No hay información de puertos</div>';
            }

            const totalPuertos = puertos.length;
            let filas = 1;

            if (totalPuertos >= 96) {
                filas = 4;
            } else if (totalPuertos > 24 && totalPuertos <= 48) {
                filas = 2;
            }

            // calcular cuántos puertos por fila
            const puertosPorFila = Math.ceil(totalPuertos / filas);

            // Definir tamaño del grupo (6 o 8)
            let grupo = 6;
            if (totalPuertos % 6 !== 0 && totalPuertos % 8 === 0) {
                grupo = 8;
            }

            let html = '';

            for (let f = 0; f < filas; f++) {
                html += '<div class="d-flex mb-2 flex-wrap fila-puertos">';

                for (let i = f * puertosPorFila; i < (f + 1) * puertosPorFila && i < totalPuertos; i++) {
                    const puerto = puertos[i];

                    // Determinar clase CSS según el estado
                    let claseEstado = 'disponible';
                    let icono = 'fa-ethernet';
                    let tooltip = 'Puerto disponible';

                    if (puerto.ocupado) {
                        claseEstado = 'ocupado';
                        tooltip = 'Puerto ocupado';
                        if (puerto.con_equipo) {
                            tooltip += ` - Equipo: ${puerto.equipo_nombre || 'N/A'}`;
                        }
                    } else if (puerto.danado) {
                        claseEstado = 'danado';
                        tooltip = 'Puerto dañado';
                    }

                    let infoExtra = '';
                    if (puerto.equipo_nombre) {
                        infoExtra += `<div><strong>Equipo:</strong> ${puerto.equipo_nombre}</div>`;
                    }
                    if (puerto.empleado_nombre) {
                        infoExtra += `<div><strong>Empleado:</strong> ${puerto.empleado_nombre}</div>`;
                    }
                    if (puerto.oficina_nombre) {
                        infoExtra += `<div><strong>Oficina:</strong> ${puerto.oficina_nombre}</div>`;
                    }

                    // separación cada X puertos (6 o 8 según corresponda)
                    if ((i - f * puertosPorFila) % grupo === 0 && i % puertosPorFila !== 0) {
                        html += `<div class="grupo-separador"></div>`;
                    }

                    html += `<div class="puerto ${claseEstado}" 
                        data-bs-toggle="tooltip" 
                        data-bs-html="true"
                        data-bs-title="<div><strong>Puerto #${puerto.numero}</strong></div>
                                       <div><strong>Estado:</strong> ${tooltip}</div>
                                       ${infoExtra}"
                        onclick="mostrarDetallesPuerto('${codigoDispositivo}', ${puerto.numero}, '${tipoDispositivo}')">
                        <i class="fa-solid ${icono}"></i>
                        <small>${puerto.numero}</small>
                     </div>`;
                }

                html += '</div>';
            }

            return html;
        }

        function mostrarDetallesPuerto(codigoDispositivo, numeroPuerto, tipoDispositivo) {
            $('#modalDetallesTitulo').text(`Detalles del Puerto #${numeroPuerto}`);
            $('#modalDetallesCuerpo').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div><p class="mt-2">Cargando detalles...</p></div>');

            $.ajax({
                url: "",
                type: "POST",
                data: {
                    peticion: 'detalles_puerto',
                    codigo_dispositivo: codigoDispositivo,
                    numero_puerto: numeroPuerto,
                    tipo: tipoDispositivo
                },
                success: function(respuesta) {
                    try {
                        const data = JSON.parse(respuesta);
                        if (data.resultado === "success") {
                            let html = `<div class="row">
                                            <div class="col-md-6">
                                                <h6>Información del Puerto</h6>
                                                <p><strong>Número:</strong> ${data.datos.numero || 'N/A'}</p>
                                                <p><strong>Estado:</strong> ${data.datos.ocupado ? '<span class="badge bg-danger">Ocupado</span>' : (data.datos.danado ? '<span class="badge bg-warning">Dañado</span>' : '<span class="badge bg-success">Disponible</span>')}</p>
                                                <p><strong>Dispositivo:</strong> ${data.datos.dispositivo_nombre || 'N/A'}</p>
                                            </div>`;

                            if (data.datos.ocupado && data.datos.con_equipo) {
                                html += `<div class="col-md-6">
                                            <h6>Información de Conexión</h6>
                                            <p><strong>Equipo:</strong> ${data.datos.equipo_nombre || 'N/A'}</p>
                                            <p><strong>Tipo:</strong> ${data.datos.equipo_tipo || 'N/A'}</p>
                                            <p><strong>Serial:</strong> ${data.datos.equipo_serial || 'N/A'}</p>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <h6>Información del Empleado</h6>
                                            <p><strong>Nombre:</strong> ${data.datos.empleado_nombre || 'N/A'}</p>
                                            <p><strong>Cédula:</strong> ${data.datos.empleado_cedula || 'N/A'}</p>
                                            <p><strong>Correo:</strong> ${data.datos.empleado_correo || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Ubicación</h6>
                                            <p><strong>Oficina:</strong> ${data.datos.oficina_nombre || 'N/A'}</p>
                                            <p><strong>Piso:</strong> ${data.datos.piso_nombre || 'N/A'}</p>
                                        </div>
                                    </div>`;
                            } else {
                                html += `<div class="col-md-6">
                                            <h6>Disponible para conexión</h6>
                                            <p>Este puerto está disponible para conectar nuevos equipos.</p>
                                            <button class="btn btn-sm btn-outline-primary">Reservar Puerto</button>
                                        </div>
                                    </div>`;
                            }

                            $('#modalDetallesCuerpo').html(html);
                        } else {
                            $('#modalDetallesCuerpo').html(`<div class="alert alert-danger">${data.mensaje || 'Error al cargar los detalles'}</div>`);
                        }
                    } catch (e) {
                        console.error("Error parsing JSON: ", e);
                        $('#modalDetallesCuerpo').html('<div class="alert alert-danger">Error al procesar la respuesta del servidor</div>');
                    }
                },
                error: function() {
                    $('#modalDetallesCuerpo').html('<div class="alert alert-danger">Error de conexión con el servidor</div>');
                }
            });

            $('#modalDetalles').modal('show');
        }

        function mostrarErrorInfraestructura(mensaje) {
            $('#contenedorDispositivos').html(`<div class="col-12 text-center">
                                                <div class="alert alert-danger">
                                                    <i class="bi bi-exclamation-triangle"></i> ${mensaje}
                                                </div>
                                              </div>`);
            $('#infoPiso').text('Error al cargar la información');
        }

        function mostrarAlerta(tipo, mensaje) {
            // Implementar función de alerta si es necesario
            console.log(`${tipo}: ${mensaje}`);
        }

        function generarPDF(tipoReporte) {
            const fechaInicio = $(`#fecha_inicio_${tipoReporte}`).val();
            const fechaFin = $(`#fecha_fin_${tipoReporte}`).val();

            // Validar fechas
            if (!fechaInicio || !fechaFin) {
                return mostrarAlerta('warning', 'Por favor seleccione las fechas de inicio y fin');
            }

            // Redirigir a la página de generación de PDF
            window.location.href = `generar_pdf.php?reporte=${tipoReporte}&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
        }
    </script>

    <style>
        /* ...existing code... */
        .puerto {
            flex: 0 0 32px;
            min-width: 32px;
            max-width: 32px;
            height: 32px;
            aspect-ratio: 1/1;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            margin: 2px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-size: 0.85rem;
            background-color: #f8f9fa;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            position: relative;
        }
        .puerto:hover {
            transform: scale(1.08);
            z-index: 2;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            border-color: #007bff;
        }
        .puerto i {
            font-size: 1.1rem;
            margin-bottom: 2px;
            display: block;
        }
        .puerto small {
            font-size: 0.7rem;
            line-height: 1;
        }
        .puerto.disponible {
            background-color: #e9fbe9;
            color: #198754;
            border-color: #b6e2b6;
        }
        .puerto.ocupado {
            background-color: #fbeaea;
            color: #dc3545;
            border-color: #e9b6b6;
        }
        .puerto.danado {
            background-color: #fff8e1;
            color: #856404;
            border-color: #ffe082;
        }
        .grupo-separador {
            flex: 0 0 8px;
            max-width: 8px;
            min-width: 8px;
            height: 32px;
            background: transparent;
        }
        .puertos-container {
            width: 100%;
            overflow-x: auto;
            padding-bottom: 2px;
        }
        .fila-puertos {
            width: 100%;
            display: flex;
            flex-wrap: nowrap;
            margin-bottom: 2px;
        }
        @media (max-width: 576px) {
            .puerto {
                flex: 0 0 24px;
                min-width: 24px;
                max-width: 24px;
                height: 24px;
                font-size: 0.7rem;
            }
            .grupo-separador {
                min-width: 4px;
                max-width: 4px;
                height: 24px;
            }
        }
    </style>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Declarar objeto global para almacenar instancias de gráficos
    var charts = {};
    $(document).ready(function() {
        // Inicializar tooltips solo si existen
        if ($('[data-bs-toggle="tooltip"]').length > 0) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Seleccionar por defecto el piso 5 si existe
        function seleccionarPisoPorDefecto() {
            let $select = $('#pisoFiltro');
            if ($select.length > 0) {
                let piso5 = $select.find('option').filter(function() {
                    return $(this).text().trim().endsWith('5');
                }).first();

                if (piso5.length) {
                    $select.val(piso5.val());
                    // Cargar automáticamente la infraestructura del piso 5
                    setTimeout(function() {
                        cargarInfraestructura();
                    }, 500);
                }
            }
        }

        seleccionarPisoPorDefecto();

        // Cargar reportes solo si los canvas existen
        if ($('#chartEficienciaTecnicos').length) generarReporteEficienciaTecnicos();
        if ($('#chartTiemposRespuesta').length) generarReporteTiemposRespuesta();
        if ($('#chartUtilizacionMateriales').length) generarReporteUtilizacionMateriales();
        if ($('#chartEstadoEquipos').length) generarReporteEstadoEquipos();
        if ($('#chartEstadoInfraestructura').length) generarReporteEstadoInfraestructura();
        if ($('#chartTendenciasSolicitudes').length) generarReporteTendenciasSolicitudes();
        if ($('#chartReincidenciaProblemas').length) generarReporteReincidenciaProblemas();
        if ($('#chartKPIs').length) generarReporteKPIs();
        if ($('#chartCargaTrabajo').length) generarReporteCargaTrabajo();
    });

    // Utilidad para destruir y crear gráficos
    function renderChart(canvasId, chartType, labels, data, label, colors) {
        if (typeof Chart === "undefined") {
            console.error("Chart.js no está cargado");
            return;
        }
        if (window.charts === undefined) window.charts = {};
        if (window.charts[canvasId] && typeof window.charts[canvasId].destroy === 'function') {
            window.charts[canvasId].destroy();
        }
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        window.charts[canvasId] = new Chart(ctx, {
            type: chartType,
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: colors || 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } }
            }
        });
    }

    function generarReporteEficienciaTecnicos() {
        var fecha_inicio = $('#fecha_inicio_eficiencia_tecnicos').val();
        var fecha_fin = $('#fecha_fin_eficiencia_tecnicos').val();
        $.post('', { peticion: 'reporte_eficiencia_tecnicos', fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
            try {
                const res = JSON.parse(resp);
                if (res.resultado === 'reporte_eficiencia_tecnicos') {
                    const labels = res.datos.map(x => x['Técnico'] + ' (' + x['Área'] + ')');
                    const data = res.datos.map(x => parseFloat(x['Tiempo Promedio (horas)'] || 0));
                    renderChart('chartEficienciaTecnicos', 'bar', labels, data, 'Horas promedio');
                }
            } catch (e) {
                console.error("Error en reporte eficiencia:", e);
            }
        }).fail(function(xhr, status, error) {
            console.error("AJAX Error eficiencia:", error);
        });
    }

    function generarReporteTiemposRespuesta() {
        var fecha_inicio = $('#fecha_inicio_tiempos_respuesta').val();
        var fecha_fin = $('#fecha_fin_tiempos_respuesta').val();
        $.post('', { peticion: 'reporte_tiempos_respuesta', fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
            try {
                const res = JSON.parse(resp);
                if (res.resultado === 'reporte_tiempos_respuesta') {
                    const labels = res.datos.map(x => x['nombre_tipo_servicio']);
                    const data = res.datos.map(x => parseFloat(x['tiempo_promedio_horas'] || 0));
                    renderChart('chartTiemposRespuesta', 'bar', labels, data, 'Horas promedio');
                }
            } catch (e) {
                console.error("Error en reporte tiempos respuesta:", e);
            }
        }).fail(function(xhr, status, error) {
            console.error("AJAX Error tiempos respuesta:", error);
        });
    }

    function generarReporteUtilizacionMateriales() {
        var fecha_inicio = $('#fecha_inicio_utilizacion_materiales').val();
        var fecha_fin = $('#fecha_fin_utilizacion_materiales').val();
        $.post('', { peticion: 'reporte_utilizacion_materiales', fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
            try {
                const res = JSON.parse(resp);
                if (res.resultado === 'reporte_utilizacion_materiales') {
                    // Validar que hay datos
                    if (Array.isArray(res.datos) && res.datos.length > 0) {
                        const labels = res.datos.map(x => x['Material'] + ' (' + x['Ubicación'] + ')');
                        const data = res.datos.map(x => parseInt(x['Cantidad Utilizada'] || 0));
                        renderChart('chartUtilizacionMateriales', 'doughnut', labels, data, 'Cantidad utilizada');
                    } else {
                        // Mostrar mensaje si no hay datos
                        $('#chartUtilizacionMateriales').replaceWith('<div id="chartUtilizacionMateriales" class="text-center text-muted">No hay datos de utilización de materiales.</div>');
                    }
                } else {
                    $('#chartUtilizacionMateriales').replaceWith('<div id="chartUtilizacionMateriales" class="text-center text-danger">Error al cargar el reporte de materiales.</div>');
                }
            } catch (e) {
                console.error("Error en reporte utilización materiales:", e);
                $('#chartUtilizacionMateriales').replaceWith('<div id="chartUtilizacionMateriales" class="text-center text-danger">Error al procesar la respuesta del servidor.</div>');
            }
        }).fail(function(xhr, status, error) {
            console.error("AJAX Error utilización materiales:", error);
            $('#chartUtilizacionMateriales').replaceWith('<div id="chartUtilizacionMateriales" class="text-center text-danger">Error de conexión al servidor.</div>');
        });
    }

    function generarReporteEstadoEquipos() {
        var estado = $('#filtroEstadoEquipo').val() || null;
        var fecha_inicio = $('#fecha_inicio_estado_equipos').val();
        var fecha_fin = $('#fecha_fin_estado_equipos').val();
        $.post('', { peticion: 'reporte_estado_equipos', estado: estado, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
            try {
                const res = JSON.parse(resp);
                if (res.resultado === 'reporte_estado_equipos') {
                    const labels = res.datos.map(x => x['descripcion'] + ' (' + x['estado'] + ')');
                    const data = res.datos.map(x => parseInt(x['cantidad'] || 0));
                    renderChart('chartEstadoEquipos', 'pie', labels, data, 'Cantidad');
                }
            } catch (e) {
                console.error("Error en reporte estado equipos:", e);
            }
        }).fail(function(xhr, status, error) {
            console.error("AJAX Error estado equipos:", error);
        });
    }

    function generarReporteEstadoInfraestructura() {
        var fecha_inicio = $('#fecha_inicio_estado_infraestructura').val();
        var fecha_fin = $('#fecha_fin_estado_infraestructura').val();
        $.post('', { peticion: 'reporte_estado_infraestructura', id_piso: 1, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
            try {
                const res = JSON.parse(resp);
                if (res.resultado === 'reporte_estado_infraestructura') {
                    const labels = ['Total', 'Ocupado', 'Disponible', 'Patch Panels'];
                    const datos = res.datos[0];
                    const data = [
                        parseInt(datos['Cantidad Total'] || 0),
                        parseInt(datos['Cantidad Ocupado'] || 0),
                        parseInt(datos['Cantidad Disponible'] || 0),
                        parseInt(datos['Cantidad Patch Panel'] || 0)
                    ];
                    renderChart('chartEstadoInfraestructura', 'bar', labels, data, 'Infraestructura');
                }
            } catch (e) {
                console.error("Error en reporte estado infraestructura:", e);
            }
        }).fail(function(xhr, status, error) {
            console.error("AJAX Error estado infraestructura:", error);
        });
    }

    function generarReporteTendenciasSolicitudes() {
        var fecha_inicio = $('#fecha_inicio_tendencias_solicitudes').val();
        var fecha_fin = $('#fecha_fin_tendencias_solicitudes').val();
        $.post('', { peticion: 'reporte_tendencias_solicitudes', fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
            try {
                const res = JSON.parse(resp);
                if (res.resultado === 'reporte_tendencias_solicitudes') {
                    const labels = res.datos.map(x => x['mes']);
                    const data = res.datos.map(x => parseInt(x['total'] || 0));
                    renderChart('chartTendenciasSolicitudes', 'line', labels, data, 'Solicitudes');
                }
            } catch (e) {
                console.error("Error en reporte tendencias solicitudes:", e);
            }
        }).fail(function(xhr, status, error) {
            console.error("AJAX Error tendencias solicitudes:", error);
        });
    }

    function generarReporteReincidenciaProblemas() {
        var fecha_inicio = $('#fecha_inicio_reincidencia_problemas').val();
        var fecha_fin = $('#fecha_fin_reincidencia_problemas').val();
        $.post('', { peticion: 'reporte_reincidencia_problemas', fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function(resp) {
            try {
                const res = JSON.parse(resp);
                if (res.resultado === 'reporte_reincidencia_problemas') {
                    const labels = res.datos.map(x => x['motivo']);
                    const data = res.datos.map(x => parseInt(x['veces_reportado'] || 0));
                    renderChart('chartReincidenciaProblemas', 'bar', labels, data, 'Reincidencias');
                }
            } catch (e) {
                console.error("Error en reporte reincidencia problemas:", e);
            }
        }).fail(function(xhr, status, error) {
            console.error("AJAX Error reincidencia problemas:", error);
        });
    }

    function generarReporteKPIs() {
        $.post('', { peticion: 'reporte_kpis' }, function(resp) {
            try {
                const res = JSON.parse(resp);
                if (res.resultado === 'reporte_kpis') {
                    const labels = ['Solicitudes', 'Hojas Finalizadas', 'Hojas Activas', 'Equipos Dañados', 'Materiales Bajo Stock'];
                    const datos = res.datos;
                    const data = [
                        parseInt(datos['total_solicitudes'] || 0),
                        parseInt(datos['hojas_finalizadas'] || 0),
                        parseInt(datos['hojas_activas'] || 0),
                        parseInt(datos['equipos_danados'] || 0),
                        parseInt(datos['materiales_bajo_stock'] || 0)
                    ];
                    renderChart('chartKPIs', 'bar', labels, data, 'KPIs');
                }
            } catch (e) {
                console.error("Error en reporte KPIs:", e);
            }
        }).fail(function(xhr, status, error) {
            console.error("AJAX Error KPIs:", error);
        });
    }

    function generarReporteCargaTrabajo() {
        $.post('', { peticion: 'reporte_carga_trabajo' }, function(resp) {
            try {
                const res = JSON.parse(resp);
                if (res.resultado === 'reporte_carga_trabajo') {
                    const labels = res.datos.map(x => x['nombre']);
                    const data = res.datos.map(x => parseInt(x['hojas_asignadas'] || 0));
                    renderChart('chartCargaTrabajo', 'bar', labels, data, 'Hojas asignadas');
                }
            } catch (e) {
                console.error("Error en reporte carga trabajo:", e);
            }
        }).fail(function(xhr, status, error) {
            console.error("AJAX Error carga trabajo:", error);
        });
    }
    </script>
</body>

</html>