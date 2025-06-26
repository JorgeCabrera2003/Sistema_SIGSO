<?php require_once("Componentes/head.php"); ?>

<body>
    <?php require_once("Componentes/menu.php");
    require_once("Componentes/modal_hoja.php"); ?>

    <div class="pagetitle">
        <h1>Gestión de Hojas de Servicio</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="?page=inicio">Home</a></li>
                <li class="breadcrumb-item active">Gestión de Hojas de Servicio</li>
            </ol>
        </nav>
    </div> <!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Hojas de Servicio</h5>
                        
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
                            <div class="col-md-3">
                                <label for="filtroTipo" class="form-label">Tipo de Servicio</label>
                                <select id="filtroTipo" class="form-select">
                                    <option value="">Todos</option>
                                    <?php foreach ($tipos_servicio as $tipo): ?>
                                        <option value="<?= $tipo['id_tipo_servicio'] ?>"><?= $tipo['nombre_tipo_servicio'] ?></option>
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
                        
                        <!-- Botones de acción -->
                        <div class="d-flex justify-content-between mb-4">
                            <div>
                                <button type="button" class="btn btn-outline-secondary" id="btn-refrescar">
                                    <i class="bi bi-arrow-clockwise"></i> Refrescar
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="btn-exportar-excel">
                                    <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                                </button>
                            </div>
                            
                            <?php if ($_SESSION['user']['id_rol'] == 5): ?>
                                <button type="button" class="btn btn-primary" id="btn-registrar">
                                    <i class="bi bi-plus-circle"></i> Nueva Hoja de Servicio
                                </button>
                            <?php endif; ?>
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
                    <div class="row">
                        <div class="col-md-6">
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
                    
                    <!-- Sección de detalles técnicos -->
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
                    <?php if ($_SESSION['user']['id_rol'] == 5): ?>
                        <button type="button" class="btn btn-primary" id="btn-imprimir-detalles">
                            <i class="bi bi-printer"></i> Imprimir
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
    
    <!-- Scripts adicionales -->
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/pdfmake/pdfmake.min.js"></script>
    <script src="assets/vendor/pdfmake/vfs_fonts.js"></script>
    <script defer src="assets/js/servicio.js"></script>
</body>
</html>