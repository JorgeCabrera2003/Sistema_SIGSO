<?php require_once("Componentes/head.php"); ?>

<body>
    <?php
    require_once("Componentes/menu.php");
    // Obtener tipos de servicio para los selects

    require_once("Componentes/modal_hoja.php");
    ?>

    <div class="pagetitle">
        <h1>Gestión de Hojas de Servicio</h1>
        <nav>
            
        </nav>
    </div> <!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
 
                        <!-- Filtros para búsqueda avanzada -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="filtroEstado" class="form-label">Estado</label>
                                <select id="filtroEstado" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="A">Activas</option>
                                    <option value="I">Finalizadas</option>
                                    <option value="E">Eliminadas</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-none">
                                <label for="filtroTipo" class="form-label">Tipo de Servicio</label>
                                <select id="filtroTipo" class="form-select">
                                    <option value="">Todos</option>
                                    <?php foreach ($tipos_servicio as $tipo): ?>
                                        <option value="<?= $tipo['nombre_tipo_servicio'] ?>">
                                            <?= $tipo['nombre_tipo_servicio'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filtroFechaInicio" class="form-label">Fecha Inicio</label>
                                <input type="date" id="filtroFechaInicio" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="filtroFechaFin" class="form-label">Fecha Fin</label>
                                <input type="date" id="filtroFechaFin" class="form-control">
                            </div>
                        </div>


                        <!-- Tabla principal -->
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="tablaServicios">
                                <thead class="table-light">
                                    <tr>
                                        <?php foreach ($cabecera as $campo): ?>
                                            <th scope="col"><?= $campo ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Contenido dinámico -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Formulario de reporte PDF -->
                    <form id="formReporteServicio" method="post" target="_blank" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="ms-3 col-auto">
                                <input type="date" name="fecha_inicio" class="form-control" id="fecha_inicio" required max="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-auto">
                                <span class="input-group-text">a</span>
                            </div>
                            <div class="col-auto">
                                <input type="date" name="fecha_fin" class="form-control" id="fecha_fin" required max="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-auto">
                                <select name="id_tipo_servicio" class="form-select" id="reporte_tipo_servicio">
                                    <option value="">Todos los tipos</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary" name="generar_reporte">
                                    Reporte PDF <i class="fa-solid fa-file-pdf"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- Fin formulario reporte PDF -->
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Detalles Completo -->
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalDetallesTitle">Detalles de Hoja de Servicio</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Secciones bien diferenciadas -->
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Sección Solicitante -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0"><i class="bi bi-person"></i> Información del Solicitante</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Nombre:</div>
                                        <div class="col-sm-8" id="detalle-solicitante"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Dependencia:</div>
                                        <div class="col-sm-8" id="detalle-dependencia"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Unidad:</div>
                                        <div class="col-sm-8" id="detalle-unidad"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Contacto:</div>
                                        <div class="col-sm-8" id="detalle-contacto"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Sección Técnico -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0"><i class="bi bi-tools"></i> Información Técnica</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Técnico Asignado:</div>
                                        <div class="col-sm-8" id="detalle-tecnico"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Tipo de Servicio:</div>
                                        <div class="col-sm-8" id="detalle-tipo-servicio"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Estado:</div>
                                        <div class="col-sm-8" id="detalle-estado"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Fecha Resultado:</div>
                                        <div class="col-sm-8" id="detalle-fecha-resultado"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Sección Equipo -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0"><i class="bi bi-pc-display"></i> Información del Equipo</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Tipo de Equipo:</div>
                                        <div class="col-sm-8" id="detalle-tipo-equipo"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Marca:</div>
                                        <div class="col-sm-8" id="detalle-marca"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Serial:</div>
                                        <div class="col-sm-8" id="detalle-serial"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Código Bien:</div>
                                        <div class="col-sm-8" id="detalle-codigo-bien"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Sección Solicitud -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0"><i class="bi bi-chat-square-text"></i> Detalles de la Solicitud</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Motivo:</div>
                                        <div class="col-sm-8" id="detalle-motivo"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Fecha Solicitud:</div>
                                        <div class="col-sm-8" id="detalle-fecha-solicitud"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Resultado:</div>
                                        <div class="col-sm-8" id="detalle-resultado"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Observación:</div>
                                        <div class="col-sm-8" id="detalle-observacion"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sección Detalles Técnicos -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0"><i class="bi bi-list-check"></i> Detalles Técnicos</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm" id="tablaDetalles">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Componente</th>
                                            <th>Detalle</th>
                                            <th>Material Utilizado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Contenido dinámico -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <?php if ($_SESSION['user']['id_rol'] == 1 || $_SESSION['user']['id_rol'] == 3): ?>
                        <button type="button" class="btn btn-primary" id="btn-imprimir-detalles">
                            <i class="bi bi-printer"></i> Imprimir
                        </button>
                        <button type="button" class="btn btn-warning" id="btn-redireccionar" style="display:none;">
                            <i class="bi bi-share"></i> Redireccionar
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar acciones -->
    <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmacionTitle">Confirmar acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalConfirmacionBody">
                    ¿Está seguro de realizar esta acción?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btn-confirmar-accion">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "Componentes/footer.php"; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


    <script defer src="assets/js/servicio.js"></script>
    <script>
        // Mostrar el botón "Finalizar" solo si corresponde
        document.addEventListener('DOMContentLoaded', function() {
            // Compatibilidad modo oscuro para el modalDetalles
            $('#modalDetalles').on('show.bs.modal', function() {
                // Detectar modo oscuro del sistema o de Bootstrap
                var isDark = document.body.classList.contains('dark') || document.documentElement.classList.contains('dark');
                if (isDark) {
                    $('#modalDetalles').addClass('dark');
                } else {
                    $('#modalDetalles').removeClass('dark');
                }
                setTimeout(function() {
                    // Obtener datos del detalle desde el DOM
                    var estado = $('#detalle-estado').text().trim();
                    var tecnico = $('#detalle-tecnico').text().trim();
                    var userCedula = "<?php echo $_SESSION['user']['cedula']; ?>";
                    var userRol = "<?php echo $_SESSION['user']['id_rol']; ?>";
                    var tecnicoAsignado = tecnico !== '' && tecnico !== 'Sin asignar' && tecnico.indexOf(userCedula) !== -1;

                    // Solo mostrar si está activa y es técnico asignado o superusuario
                    if (estado === 'Activa' && (userRol == 1 || tecnicoAsignado)) {
                        $('#btn-finalizar-hoja').show();
                    } else {
                        $('#btn-finalizar-hoja').hide();
                    }
                }, 300);
            });
            // Evento para abrir el modal de finalizar
            $('#btn-finalizar-hoja').on('click', function() {
                // Obtener el código de la hoja de servicio del detalle
                var codigo = $('#tablaDetalles').closest('.modal-content').find('tbody tr:first td:first').text();
                // Si tienes el código en otro lado, ajústalo aquí
                if (!codigo || isNaN(codigo)) {
                    // Alternativamente, puedes guardar el código en un atributo data en el modal
                    codigo = $('#modalDetalles').data('codigo');
                }
                if (codigo) {
                    finalizarHoja(codigo);
                } else {
                    alert('No se pudo obtener el código de la hoja de servicio.');
                }
            });
        });
    </script>
</body>

</html>