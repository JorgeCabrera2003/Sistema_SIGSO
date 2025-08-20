<?php require_once("Componentes/head.php"); ?>

<body>
    <?php require_once("Componentes/menu.php"); ?>

    <div class="pagetitle mb-4">
        <h1>Gestión de Infraestructura de Red</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
                <li class="breadcrumb-item active">Infraestructura</li>
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
    </script>

    <style>
        .puerto {
            flex: 1;
            /* que todos los puertos ocupen el mismo espacio */
            min-width: 0;
            /* evitar que se rompa el contenedor */
            aspect-ratio: 1/1;
            /* mantiene forma cuadrada */
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
            /* espacio fijo entre grupos */
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
            /* sin scroll */
            max-height: none;
        }

        .d-flex.flex-wrap {
            flex-wrap: nowrap !important;
            /* evitar saltos de fila */
            width: 100%;
        }

        .fila-puertos {
            width: 100%;
            display: flex;
            flex-wrap: nowrap;
            /* una sola fila */
        }
    </style>
</body>

</html>