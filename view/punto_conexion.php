<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once("Componentes/head.php"); ?>
    <style>
        .puerto {
            flex: 1;
            min-width: 0;
            aspect-ratio: 1/1;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 2px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .grupo-separador {
            flex: 0 0 10px;
        }

        .puerto:hover {
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .puerto i {
            font-size: 1.2rem;
            margin-bottom: 3px;
        }

        .puerto small {
            font-size: 0.7rem;
        }

        .puerto.disponible {
            background-color: #d4edda;
            color: #155724;
        }

        .puerto.ocupado {
            background-color: #f8d7da;
            color: #721c24;
        }

        .puerto.danado {
            background-color: #fff3cd;
            color: #856404;
        }

        .dispositivo-card {
            transition: transform 0.3s ease;
        }

        .dispositivo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .puertos-container {
            width: 100%;
            overflow: visible;
            max-height: none;
        }

        .d-flex.flex-wrap {
            flex-wrap: nowrap !important;
            width: 100%;
        }

        .fila-puertos {
            width: 100%;
            display: flex;
            flex-wrap: nowrap;
        }

        .equipo-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .equipo-card:hover {
            background-color: #f8f9fa;
        }

        .equipo-item.selected .equipo-card {
            background-color: #e3f2fd;
            border-color: #2196f3;
        }

        .equipo-card.equipo-conectado {
            background-color: #ffeaea !important;
            border-left-color: #dc3545 !important;
            border-color: #dc3545 !important;
        }
        .equipo-card.equipo-conectado:hover {
            background-color: #ffd6d6 !important;
        }
        
        /* Estilos para el panel de filtros */
        #filtrosEquipos .card-body {
            padding: 1rem;
        }

        /* Estilos para el contador de equipos */
        #contadorEquipos {
            font-weight: 500;
        }

        /* Estilos para la barra de desplazamiento */
        #contenedorEquipos::-webkit-scrollbar {
            width: 8px;
        }

        #contenedorEquipos::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        #contenedorEquipos::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        #contenedorEquipos::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Estilos para los equipos */
        .equipo-card {
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }

        .equipo-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-left-color: #0d6efd;
        }

        .equipo-item.selected .equipo-card {
            background-color: #e3f2fd;
            border-color: #2196f3;
            border-left-color: #2196f3;
        }
    </style>
</head>
<body>
    <?php require_once("Componentes/menu.php"); ?>

    <div class="pagetitle mb-4">
        <h1>Gestión de Puntos de Conexión</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
                <li class="breadcrumb-item active">Puntos de Conexión</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="row">
        <!-- Filtros -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Filtros</h5>
                </div>
                <div class="card-body">
                    <form id="filtrosConexion">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="tipoDispositivo" class="form-label">Tipo de Dispositivo</label>
                                <select class="form-select" id="tipoDispositivo" name="tipoDispositivo">
                                    <option value="patch">Patch Panel</option>
                                    <option value="switch">Switch</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="pisoFiltro" class="form-label">Piso</label>
                                <select class="form-select" id="pisoFiltro" name="pisoFiltro">
                                    <option value="0">Seleccione un piso</option>
                                    <?php foreach ($pisos as $pisoItem) : ?>
                                        <option value="<?= $pisoItem['id_piso'] ?>">
                                            <?= $pisoItem['tipo_piso'] . ' ' . $pisoItem['nro_piso'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="oficinaFiltro" class="form-label">Oficina</label>
                                <select class="form-select" id="oficinaFiltro" name="oficinaFiltro">
                                    <option value="0">Todas las oficinas</option>
                                    <?php foreach ($oficinas as $oficina) : ?>
                                        <option value="<?= $oficina['id_oficina'] ?>">
                                            <?= $oficina['nombre_oficina'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-primary" onclick="cargarInfraestructura()">
                                    <i class="bi bi-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de dispositivos y equipos -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0" id="tituloDispositivos">Dispositivos de Red</h5>
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
                        <!-- El contenido se genera dinámicamente por JS -->
                    </div>
                    <!-- Controles de paginación para dispositivos -->
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="dispositivosPaginacion">
                                    <!-- Botones generados por JS -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de equipos -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Equipos Disponibles</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse"
                            data-bs-target="#filtrosEquipos" aria-expanded="false" aria-controls="filtrosEquipos">
                            <i class="bi bi-funnel"></i> Filtros
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros de equipos (colapsable) -->
                    <div class="collapse mb-3" id="filtrosEquipos">
                        <div class="card card-body">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label for="buscarEquipo" class="form-label">Buscar equipo:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="buscarEquipo"
                                            placeholder="Buscar por tipo, serial o descripción...">
                                        <button class="btn btn-outline-secondary" type="button" onclick="filtrarEquipos()">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="filtroTipo" class="form-label">Tipo:</label>
                                    <select class="form-select" id="filtroTipo">
                                        <option value="">Todos los tipos</option>
                                        <?php
                                        $tiposUnicos = [];
                                        foreach ($equipos as $equipo) {
                                            if (!in_array($equipo['tipo_equipo'], $tiposUnicos)) {
                                                $tiposUnicos[] = $equipo['tipo_equipo'];
                                                echo '<option value="' . $equipo['tipo_equipo'] . '">' . $equipo['tipo_equipo'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="filtroOficina" class="form-label">Oficina:</label>
                                    <select class="form-select" id="filtroOficina">
                                        <option value="">Todas las oficinas</option>
                                        <?php foreach ($oficinas as $oficina) : ?>
                                            <option value="<?= $oficina['id_oficina'] ?>">
                                                <?= $oficina['nombre_oficina'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="soloDisponibles">
                                        <label class="form-check-label" for="soloDisponibles">
                                            Mostrar disponibles
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="loadingEquipos" class="text-center my-5" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando equipos...</p>
                    </div>

                    <div id="contadorEquipos" class="mb-2 text-muted small"></div>

                    <div id="contenedorEquipos" class="row" style="max-height: 600px; overflow-y: auto;">
                        <?php
                        // Obtener IDs de equipos conectados
                        $equiposConectados = [];
                        foreach ($puntos_conexion ?? [] as $punto) {
                            if (!empty($punto['id_equipo'])) {
                                $equiposConectados[] = $punto['id_equipo'];
                            }
                        }
                        foreach ($equipos as $equipo):
                            $conectado = !empty($equipo['ocupado']) && $equipo['ocupado'] == 1;
                        ?>
                            <div class="col-12 mb-2 equipo-item"
                                data-id="<?= $equipo['id_equipo'] ?>"
                                data-tipo="<?= $equipo['tipo_equipo'] ?>"
                                data-serial="<?= $equipo['serial'] ?>"
                                data-descripcion="<?= htmlspecialchars($equipo['descripcion'] ?? '') ?>"
                                data-oficina="<?= $equipo['id_oficina'] ?? '' ?>"
                                data-disponible="<?= $conectado ? 'false' : 'true' ?>">
                                <div class="card equipo-card <?= $conectado ? 'equipo-conectado' : '' ?>">
                                    <div class="card-body p-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-computer me-2 fs-4"></i>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0"><?= $equipo['tipo_equipo'] ?></h6>
                                                <small class="text-muted">Serial: <?= $equipo['serial'] ?></small>
                                                <?php if (!empty($equipo['descripcion'])): ?>
                                                    <br><small class="text-muted"><?= $equipo['descripcion'] ?></small>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($conectado): ?>
                                                <span class="badge bg-danger ms-2">Ocupado</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Controles de paginación -->
                    <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="equiposPaginacion">
                                    <li class="page-item"><a class="page-link" href="#" id="equiposPrev">&laquo;</a></li>
                                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                        <li class="page-item <?= $i == 1 ? 'active' : '' ?>"><a class="page-link" href="#" data-pagina="<?= $i ?>"><?= $i ?></a></li>
                                    <?php endfor; ?>
                                    <li class="page-item"><a class="page-link" href="#" id="equiposNext">&raquo;</a></li>
                                </ul>
                            </nav>
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
                    <h5 class="modal-title" id="modalDetallesTitulo">Gestionar Puerto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalDetallesCuerpo">
                    <!-- Contenido dinámico -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnGestionarPuerto">Conectar</button>
                    <button type="button" class="btn btn-warning" id="btnMarcarDanado">Marcar como dañado</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "Componentes/footer.php"; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- JavaScript para la gestión de puntos de conexión -->
    <script>
        let equipoSeleccionado = null;
        let puertoSeleccionado = null;
        let dispositivoSeleccionado = null;
        let equiposOriginal = <?php echo json_encode($equipos); ?>;
        let equiposFiltrados = equiposOriginal.slice(); // array filtrado actual
        let equiposPorPagina = 6;
        let paginaActual = 1;
        let dispositivosOriginal = [];
        let dispositivosPorPagina = 2;
        let paginaActualDispositivo = 1;

        $(document).ready(function() {
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Seleccionar por defecto el piso 1 si existe
            $('#pisoFiltro').val(6);

            // Cargar infraestructura por defecto
            setTimeout(function() {
                cargarInfraestructura();
            }, 500);

            // Evento para seleccionar equipo
            $(document).on('click', '.equipo-item', function() {
                $('.equipo-item').removeClass('selected');
                $(this).addClass('selected');
                equipoSeleccionado = {
                    id: $(this).data('id'),
                    tipo: $(this).data('tipo'),
                    serial: $(this).data('serial')
                };

                // Si hay un puerto seleccionado, mostrar modal de conexión
                if (puertoSeleccionado) {
                    mostrarModalGestionPuerto();
                }
            });

            // Evento para el botón de gestionar puerto
            $('#btnGestionarPuerto').on('click', function() {
                gestionarPuerto();
            });

            // Marcar puerto como dañado
            $('#btnMarcarDanado').on('click', function() {
                if (!puertoSeleccionado) return;
                Swal.fire({
                    icon: 'warning',
                    title: '¿Está seguro?',
                    text: '¿Desea marcar este puerto como dañado?',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, marcar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "",
                            type: "POST",
                            data: {
                                marcar_danado: true,
                                codigo_patch_panel: puertoSeleccionado.codigo,
                                puerto_patch_panel: puertoSeleccionado.numero
                            },
                            success: function(respuesta) {
                                try {
                                    const data = JSON.parse(respuesta);
                                    if (data.estado === 1) {
                                        mostrarAlerta('success', data.mensaje);
                                        $('#modalDetalles').modal('hide');
                                        cargarInfraestructura();
                                        resetSelecciones();
                                    } else {
                                        mostrarAlerta('error', data.mensaje);
                                    }
                                } catch (e) {
                                    mostrarAlerta('error', 'Error al procesar la respuesta del servidor');
                                }
                            },
                            error: function() {
                                mostrarAlerta('error', 'Error de conexión con el servidor');
                            }
                        });
                    }
                });
            });

            // Inicializar paginación de equipos
            renderEquiposPaginados(equiposOriginal, paginaActual);
            actualizarPaginacionEquipos(equiposOriginal);
            
            // Inicializar contador
            actualizarContadorEquipos($('.equipo-item:visible').length, $('.equipo-item').length);

            // Evento para búsqueda en tiempo real
            $('#buscarEquipo').on('input', function() {
                filtrarEquipos();
            });

            // Eventos para los selects
            $('#filtroTipo, #filtroOficina').on('change', function() {
                filtrarEquipos();
            });

            // Evento para el switch de disponibilidad
            $('#soloDisponibles').on('change', function() {
                filtrarEquipos();
            });

            // Evento para el filtro de oficina en el panel principal
            $('#oficinaFiltro').on('change', function() {
                const idOficina = $(this).val();
                if (idOficina && idOficina !== "0") {
                    cargarEquiposPorOficina(idOficina);
                }
            });
        });

        function cargarInfraestructura() {
            const tipoDispositivo = $('#tipoDispositivo').val();
            const idPiso = $('#pisoFiltro').val();
            const idOficina = $('#oficinaFiltro').val();

            if (!idPiso || idPiso === "0") {
                mostrarAlerta('warning', 'Por favor seleccione un piso');
                return;
            }

            // Obtener información del piso seleccionado
            const pisoTexto = $('#pisoFiltro option:selected').text();
            $('#tituloDispositivos').text(`${tipoDispositivo === 'patch' ? 'Patch Panels' : 'Switches'} - ${pisoTexto}`);

            $('#loadingInfraestructura').show();
            $('#contenedorDispositivos').html('');

            // Realizar petición AJAX
            $.ajax({
                url: "",
                type: "POST",
                data: {
                    peticion: 'obtener_infraestructura_grafica',
                    tipo: tipoDispositivo,
                    id_piso: idPiso
                },
                success: function(respuesta) {
                    $('#loadingInfraestructura').hide();
                    try {
                        const data = JSON.parse(respuesta);
                        if (data.resultado === "success") {
                            mostrarDispositivos(data.datos, tipoDispositivo);
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

        function mostrarDispositivos(dispositivos, tipo) {
            dispositivosOriginal = dispositivos || [];
            paginaActualDispositivo = 1;
            renderDispositivosPaginados(dispositivosOriginal, paginaActualDispositivo, tipo);
            actualizarPaginacionDispositivos(dispositivosOriginal);
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

            const puertosPorFila = Math.ceil(totalPuertos / filas);
            let grupo = 6;
            if (totalPuertos % 6 !== 0 && totalPuertos % 8 === 0) {
                grupo = 8;
            }

            let html = '';

            for (let f = 0; f < filas; f++) {
                html += '<div class="d-flex mb-2 flex-wrap fila-puertos">';

                for (let i = f * puertosPorFila; i < (f + 1) * puertosPorFila && i < totalPuertos; i++) {
                    const puerto = puertos[i];

                    let claseEstado = 'disponible';
                    let icono = 'fa-ethernet';
                    let tooltip = 'Puerto disponible';

                    if (puerto.ocupado) {
                        claseEstado = 'ocupado';
                        tooltip = 'Puerto ocupado';
                        if (puerto.equipo_nombre) {
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

                    if ((i - f * puertosPorFila) % grupo === 0 && i % puertosPorFila !== 0) {
                        html += `<div class="grupo-separador"></div>`;
                    }

                    html += `<div class="puerto ${claseEstado}" 
                        data-bs-toggle="tooltip" 
                        data-bs-html="true"
                        data-bs-title="<div><strong>Puerto #${puerto.numero}</strong></div>
                                       <div><strong>Estado:</strong> ${tooltip}</div>
                                       ${infoExtra}"
                        onclick="seleccionarPuerto('${codigoDispositivo}', ${puerto.numero}, ${puerto.ocupado ? 'true' : 'false'}, '${tipoDispositivo}')">
                        <i class="fa-solid ${icono}"></i>
                        <small>${puerto.numero}</small>
                     </div>`;
                }

                html += '</div>';
            }

            return html;
        }

        function seleccionarPuerto(codigoDispositivo, numeroPuerto, ocupado, tipoDispositivo) {
            puertoSeleccionado = {
                codigo: codigoDispositivo,
                numero: numeroPuerto,
                ocupado: ocupado,
                tipo: tipoDispositivo
            };

            dispositivoSeleccionado = codigoDispositivo;

            // Si hay un equipo seleccionado, mostrar modal de conexión
            if (equipoSeleccionado) {
                mostrarModalGestionPuerto();
            } else {
                mostrarDetallesPuerto(codigoDispositivo, numeroPuerto, tipoDispositivo);
            }
        }

        function mostrarModalGestionPuerto() {
            if (!puertoSeleccionado || !equipoSeleccionado) return;

            // Verifica si el equipo ya está conectado
            let equipoId = equipoSeleccionado.id;
            let equipoOcupado = false;
            <?php
            // Genera array JS de equipos conectados
            $jsEquiposConectados = json_encode($equiposConectados);
            ?>
            let equiposConectados = <?php echo $jsEquiposConectados; ?>;
            if (equiposConectados.includes(equipoId)) {
                equipoOcupado = true;
            }

            // Si el equipo ya está conectado y se intenta conectar a otro puerto, mostrar error
            if (equipoOcupado && !puertoSeleccionado.ocupado) {
                mostrarAlerta('error', 'Este equipo ya está conectado a un puerto y no puede conectarse a otro.');
                return;
            }

            const titulo = puertoSeleccionado.ocupado ?
                `Desconectar Equipo del Puerto #${puertoSeleccionado.numero}` :
                `Conectar Equipo al Puerto #${puertoSeleccionado.numero}`;

            const contenido = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Información del Puerto</h6>
                        <p><strong>Dispositivo:</strong> ${dispositivoSeleccionado}</p>
                        <p><strong>Puerto:</strong> #${puertoSeleccionado.numero}</p>
                        <p><strong>Estado:</strong> ${puertoSeleccionado.ocupado ? 
                            '<span class="badge bg-danger">Ocupado</span>' : 
                            '<span class="badge bg-success">Disponible</span>'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Información del Equipo</h6>
                        <p><strong>Tipo:</strong> ${equipoSeleccionado.tipo}</p>
                        <p><strong>Serial:</strong> ${equipoSeleccionado.serial}</p>
                        <p><strong>ID:</strong> ${equipoSeleccionado.id}</p>
                        ${equipoOcupado ? '<span class="badge bg-danger">Ocupado</span>' : ''}
                    </div>
                </div>
            `;

            $('#modalDetallesTitulo').text(titulo);
            $('#modalDetallesCuerpo').html(contenido);
            $('#btnGestionarPuerto').text(puertoSeleccionado.ocupado ? 'Desconectar' : 'Conectar');

            $('#modalDetalles').modal('show');
        }

        function gestionarPuerto() {
            if (!puertoSeleccionado || !equipoSeleccionado) return;

            const accion = puertoSeleccionado.ocupado ? 'desconectar' : 'conectar';

            // Verificación adicional para evitar conectar un equipo ya conectado
            if (puertoSeleccionado.ocupado === false) {
                // Verificar si el equipo ya está conectado en otro puerto
                let equipoYaConectado = false;
                <?php
                // Generar array JS de equipos conectados desde PHP
                $jsEquiposConectados = json_encode($equiposConectados);
                ?>
                let equiposConectados = <?php echo $jsEquiposConectados; ?>;
                if (equiposConectados.includes(equipoSeleccionado.id)) {
                    equipoYaConectado = true;
                }
                
                if (equipoYaConectado) {
                    mostrarAlerta('error', 'Este equipo ya está conectado a otro puerto');
                    return;
                }
            }

            $.ajax({
                url: "",
                type: "POST",
                data: {
                    conectar_equipo: true,
                    accion: accion,
                    codigo_patch_panel: puertoSeleccionado.codigo,
                    puerto_patch_panel: puertoSeleccionado.numero,
                    id_equipo: equipoSeleccionado.id
                },
                success: function(respuesta) {
                    try {
                        const data = JSON.parse(respuesta);
                        if (data.estado === 1) {
                            mostrarAlerta('success', data.mensaje);
                            $('#modalDetalles').modal('hide');
                            cargarInfraestructura(); // Recargar la vista de dispositivos
                            resetSelecciones();
                            // Recargar equipos para actualizar badges
                            recargarEquipos();
                        } else {
                            mostrarAlerta('error', data.mensaje);
                        }
                    } catch (e) {
                        console.error("Error parsing JSON: ", e);
                        mostrarAlerta('error', 'Error al procesar la respuesta del servidor');
                    }
                },
                error: function() {
                    mostrarAlerta('error', 'Error de conexión con el servidor');
                }
            });
        }

        // Nueva función para recargar equipos y actualizar la vista
        function recargarEquipos() {
            $.ajax({
                url: "",
                type: "POST",
                data: { peticion: "consultar_equipos_estado" },
                success: function(respuesta) {
                    try {
                        const data = JSON.parse(respuesta);
                        if (data.resultado === "consultar" && Array.isArray(data.datos)) {
                            equiposOriginal = data.datos;
                            renderEquiposPaginados(equiposOriginal, paginaActual);
                            actualizarPaginacionEquipos(equiposOriginal);
                        }
                    } catch (e) {
                        // Si el backend no tiene este endpoint, puedes usar el endpoint normal
                        // o recargar la página como fallback
                        // location.reload();
                    }
                }
            });
        }

        function resetSelecciones() {
            equipoSeleccionado = null;
            puertoSeleccionado = null;
            dispositivoSeleccionado = null;
            $('.equipo-item').removeClass('selected');
        }

        function mostrarDetallesPuerto(codigoDispositivo, numeroPuerto, tipoDispositivo) {
            // Implementar similar al módulo de estadística si se desea
            console.log("Mostrar detalles del puerto", codigoDispositivo, numeroPuerto, tipoDispositivo);
        }

        function mostrarErrorInfraestructura(mensaje) {
            $('#contenedorDispositivos').html(`<div class="col-12 text-center">
                                                <div class="alert alert-danger">
                                                    <i class="bi bi-exclamation-triangle"></i> ${mensaje}
                                                </div>
                                              </div>`);
        }

        function mostrarAlerta(tipo, mensaje) {
            Swal.fire({
                icon: tipo,
                title: mensaje,
                showConfirmButton: false,
                timer: 3000
            });
        }

        // Función para filtrar equipos
        function filtrarEquipos() {
            const textoBusqueda = ($('#buscarEquipo').val() || '').toLowerCase();
            const tipoSeleccionado = ($('#filtroTipo').val() || '').toLowerCase();
            const oficinaSeleccionada = $('#filtroOficina').val();
            const soloDisponibles = $('#soloDisponibles').is(':checked');

            equiposFiltrados = equiposOriginal.filter(function(equipo) {
                let coincide = true;
                if (textoBusqueda &&
                    !(equipo.tipo_equipo && equipo.tipo_equipo.toLowerCase().includes(textoBusqueda)) &&
                    !(equipo.serial && equipo.serial.toLowerCase().includes(textoBusqueda)) &&
                    !(equipo.descripcion && equipo.descripcion.toLowerCase().includes(textoBusqueda))) {
                    coincide = false;
                }
                if (tipoSeleccionado && equipo.tipo_equipo.toLowerCase() !== tipoSeleccionado) {
                    coincide = false;
                }
                if (oficinaSeleccionada && equipo.id_oficina != oficinaSeleccionada) {
                    coincide = false;
                }
                if (soloDisponibles && equipo.ocupado == 1) {
                    coincide = false;
                }
                return coincide;
            });

            actualizarContadorEquipos(equiposFiltrados.length, equiposOriginal.length);
            paginaActual = 1;
            renderEquiposPaginados(equiposFiltrados, paginaActual);
            actualizarPaginacionEquipos(equiposFiltrados);
        }

        function actualizarContadorEquipos(visibles, totales) {
            let texto = '';
            if (visibles === totales) {
                texto = `Mostrando todos los ${totales} equipos`;
            } else {
                texto = `Mostrando ${visibles} de ${totales} equipos`;
            }
            $('#contadorEquipos').text(texto);
        }

        // Función para cargar equipos por oficina
        function cargarEquiposPorOficina(idOficina) {
            $('#loadingEquipos').show();

            $.ajax({
                url: "",
                type: "POST",
                data: {
                    peticion: 'obtener_equipos_oficina',
                    id_oficina: idOficina
                },
                success: function(respuesta) {
                    $('#loadingEquipos').hide();
                    try {
                        const data = JSON.parse(respuesta);
                        if (data.resultado === "success") {
                            actualizarListaEquipos(data.equipos);
                        } else {
                            mostrarAlerta('error', data.mensaje || 'Error al cargar los equipos');
                        }
                    } catch (e) {
                        console.error("Error parsing JSON: ", e);
                        mostrarAlerta('error', 'Error al procesar la respuesta del servidor');
                    }
                },
                error: function() {
                    $('#loadingEquipos').hide();
                    mostrarAlerta('error', 'Error de conexión con el servidor');
                }
            });
        }

        // Función para actualizar la lista de equipos
        function actualizarListaEquipos(equipos) {
            let html = '';

            // Obtener IDs de equipos conectados
            let equiposConectados = [];
            <?php
            $jsEquiposConectados = json_encode($equiposConectados);
            ?>
            equiposConectados = <?php echo $jsEquiposConectados; ?>;

            if (equipos.length === 0) {
                html = `<div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No se encontraron equipos
                    </div>
                </div>`;
            } else {
                equipos.forEach(equipo => {
                    const conectado = equiposConectados.includes(equipo.id_equipo);
                    html += `<div class="col-12 mb-2 equipo-item" 
                         data-id="${equipo.id_equipo}" 
                         data-tipo="${equipo.tipo_equipo}" 
                         data-serial="${equipo.serial}"
                         data-descripcion="${equipo.descripcion ? htmlspecialchars(equipo.descripcion) : ''}"
                         data-oficina="${equipo.id_oficina}"
                         data-disponible="${conectado ? 'false' : 'true'}">
                        <div class="card equipo-card ${conectado ? 'equipo-conectado' : ''}">
                            <div class="card-body p-2">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-computer me-2 fs-4"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">${equipo.tipo_equipo}</h6>
                                        <small class="text-muted">Serial: ${equipo.serial}</small>
                                        ${equipo.descripcion ? `<br><small class="text-muted">${equipo.descripcion}</small>` : ''}
                                    </div>
                                    ${conectado ? `<span class="badge bg-danger ms-2">Ocupado</span>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
            }

            $('#contenedorEquipos').html(html);
            actualizarContadorEquipos(equipos.length, equipos.length);
        }

        // Función helper para escape de HTML (similar a htmlspecialchars de PHP)
        function htmlspecialchars(str) {
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // PAGINACIÓN DE EQUIPOS
        function renderEquiposPaginados(equipos, pagina = 1) {
            const inicio = (pagina - 1) * equiposPorPagina;
            const fin = inicio + equiposPorPagina;
            const equiposPagina = equipos.slice(inicio, fin);
            let html = '';

            equiposPagina.forEach(function(equipo) {
                // Usa el campo ocupado para mostrar el badge y la clase
                const conectado = equipo.ocupado == 1;
                html += `<div class="col-12 mb-2 equipo-item"
                    data-id="${equipo.id_equipo}"
                    data-tipo="${equipo.tipo_equipo}"
                    data-serial="${equipo.serial}"
                    data-descripcion="${equipo.descripcion ? htmlspecialchars(equipo.descripcion) : ''}"
                    data-oficina="${equipo.id_oficina ?? ''}"
                    data-disponible="${conectado ? 'false' : 'true'}">
                    <div class="card equipo-card ${conectado ? 'equipo-conectado' : ''}">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-computer me-2 fs-4"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">${equipo.tipo_equipo}</h6>
                                    <small class="text-muted">Serial: ${equipo.serial}</small>
                                    ${equipo.descripcion ? `<br><small class="text-muted">${equipo.descripcion}</small>` : ''}
                                </div>
                                ${conectado ? `<span class="badge bg-danger ms-2">Ocupado</span>` : ''}
                            </div>
                        </div>
                    </div>
                </div>`;
            });
            $('#contenedorEquipos').html(html);
            actualizarContadorEquipos(equiposPagina.length, equipos.length);
            // Actualizar paginación activa
            $('#equiposPaginacion .page-item').removeClass('active');
            $('#equiposPaginacion .page-link[data-pagina="' + pagina + '"]').parent().addClass('active');
        }

        function actualizarPaginacionEquipos(equipos) {
            const totalPaginas = Math.ceil(equipos.length / equiposPorPagina);
            let pagHtml = `<li class="page-item"><a class="page-link" href="#" id="equiposPrev">&laquo;</a></li>`;
            for (let i = 1; i <= totalPaginas; i++) {
                pagHtml += `<li class="page-item ${i === paginaActual ? 'active' : ''}"><a class="page-link" href="#" data-pagina="${i}">${i}</a></li>`;
            }
            pagHtml += `<li class="page-item"><a class="page-link" href="#" id="equiposNext">&raquo;</a></li>`;
            $('#equiposPaginacion').html(pagHtml);
        }

        // Evento para paginación
        $(document).on('click', '#equiposPaginacion .page-link', function(e) {
            e.preventDefault();
            const pagina = $(this).data('pagina');
            if (pagina) {
                paginaActual = pagina;
                renderEquiposPaginados(equiposFiltrados, paginaActual);
            } else if ($(this).attr('id') === 'equiposPrev') {
                if (paginaActual > 1) {
                    paginaActual--;
                    renderEquiposPaginados(equiposFiltrados, paginaActual);
                }
            } else if ($(this).attr('id') === 'equiposNext') {
                const totalPaginas = Math.ceil(equiposFiltrados.length / equiposPorPagina);
                if (paginaActual < totalPaginas) {
                    paginaActual++;
                    renderEquiposPaginados(equiposFiltrados, paginaActual);
                }
            }
        });

        // PAGINACIÓN DE DISPOSITIVOS
        function renderDispositivosPaginados(dispositivos, pagina = 1, tipoDispositivo = 'patch') {
            const inicio = (pagina - 1) * dispositivosPorPagina;
            const fin = inicio + dispositivosPorPagina;
            const dispositivosPagina = dispositivos.slice(inicio, fin);
            let html = '';

            if (!dispositivosPagina || dispositivosPagina.length === 0) {
                html = `<div class="col-12 text-center">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> No se encontraron ${tipoDispositivo === 'patch' ? 'patch panels' : 'switches'} en este piso
                            </div>
                        </div>`;
            } else {
                dispositivosPagina.forEach(dispositivo => {
                    html += `<div class="col-md-12 col-lg-12 mb-4">
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
                                            ${generarPuertos(dispositivo.puertos, dispositivo.codigo_bien, tipoDispositivo)}
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

            // Actualizar paginación activa
            $('#dispositivosPaginacion .page-item').removeClass('active');
            $('#dispositivosPaginacion .page-link[data-pagina="' + pagina + '"]').parent().addClass('active');
        }

        function actualizarPaginacionDispositivos(dispositivos) {
            const totalPaginas = Math.ceil(dispositivos.length / dispositivosPorPagina);
            let pagHtml = `<li class="page-item"><a class="page-link" href="#" id="dispositivosPrev">&laquo;</a></li>`;
            for (let i = 1; i <= totalPaginas; i++) {
                pagHtml += `<li class="page-item ${i === paginaActualDispositivo ? 'active' : ''}"><a class="page-link" href="#" data-pagina="${i}">${i}</a></li>`;
            }
            pagHtml += `<li class="page-item"><a class="page-link" href="#" id="dispositivosNext">&raquo;</a></li>`;
            $('#dispositivosPaginacion').html(pagHtml);
        }

        // Evento para paginación de dispositivos
        $(document).on('click', '#dispositivosPaginacion .page-link', function(e) {
            e.preventDefault();
            const pagina = $(this).data('pagina');
            if (pagina) {
                paginaActualDispositivo = pagina;
                renderDispositivosPaginados(dispositivosOriginal, paginaActualDispositivo, $('#tipoDispositivo').val());
            } else if ($(this).attr('id') === 'dispositivosPrev') {
                if (paginaActualDispositivo > 1) {
                    paginaActualDispositivo--;
                    renderDispositivosPaginados(dispositivosOriginal, paginaActualDispositivo, $('#tipoDispositivo').val());
                }
            } else if ($(this).attr('id') === 'dispositivosNext') {
                const totalPaginas = Math.ceil(dispositivosOriginal.length / dispositivosPorPagina);
                if (paginaActualDispositivo < totalPaginas) {
                    paginaActualDispositivo++;
                    renderDispositivosPaginados(dispositivosOriginal, paginaActualDispositivo, $('#tipoDispositivo').val());
                }
            }
        });
    </script>
</body>
</html>