<?php require_once("Componentes/head.php"); ?>

<body>
    <?php require_once("Componentes/menu.php"); ?>

    <div class="pagetitle mb-4">
        <h1>Reportes Estadísticos</h1>
        <nav>
        </nav>
    </div><!-- End Page Title -->

    <!-- Filtros Generales -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Filtros Generales</h5>
        </div>
        <div class="card-body">
            <form id="filtrosGenerales">
                <div class="row">
                    <div class="col-md-3">
                        <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                    </div>
                    <div class="col-md-3">
                        <label for="fechaFin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary" onclick="aplicarFiltrosGenerales()">
                            <i class="bi bi-funnel"></i> Aplicar Filtros
                        </button>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-secondary" onclick="resetearFiltros()">
                            <i class="bi bi-arrow-counterclockwise"></i> Resetear
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Tarjeta de Patch Panel -->
        <?php if ($permiso_interconexion || $permiso_punto_conexion) : ?>
        <div class="col-md-6 mb-4" id="card-puntos-red">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Patch Panel</h5>
                    <i class="fa-solid fa-server text-muted"></i>
                </div>
                <div class="card-body">
                    <select class="form-select mb-3" name="pisoFiltrado" id="pisoFiltrado">
                        <option value="0">Seleccione un piso</option>
                        <?php foreach ($piso as $pisoItem) : ?>
                            <option value="<?= $pisoItem['id_piso'] ?>">
                                <?= $pisoItem['tipo_piso'] . ' ' . $pisoItem['nro_piso'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="patchPanelLoading" class="text-center my-3" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando datos...</p>
                    </div>
                    <div id="patchPanelInfo">
                        <div class="alert alert-info">Seleccione un piso para ver el reporte</div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tarjeta de Switches -->
        <?php if ($permiso_switch) : ?>
        <div class="col-md-6 mb-4" id="card-switch-red">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Switches</h5>
                    <i class="fa-solid fa-network-wired text-muted"></i>
                </div>
                <div class="card-body">
                    <select class="form-select mb-3" name="pisoFiltradoSwitch" id="pisoFiltradoSwitch">
                        <option value="0">Seleccione un piso</option>
                        <?php foreach ($piso as $pisoItem) : ?>
                            <option value="<?= $pisoItem['id_piso'] ?>">
                                <?= $pisoItem['tipo_piso'] . ' ' . $pisoItem['nro_piso'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="switchPanelLoading" class="text-center my-3" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando datos...</p>
                    </div>
                    <div id="switchPanelInfo">
                        <div class="alert alert-info">Seleccione un piso para ver el reporte</div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tarjeta de Usuarios -->
        <?php if ($permiso_usuario) : ?>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Usuarios</h5>
                    <i class="fas fa-users text-muted"></i>
                </div>
                <div class="card-body">
                    <h2 class="total-balance mb-3">
                        <?= number_format($cantidadEmpleados['datos']['Total usuario']) ?> Usuarios
                    </h2>
                    <div class="account-list">
                        <div class="account-item d-flex justify-content-between mb-2">
                            <span class="account-name">Empleados de OFITIC</span>
                            <span class="account-balance">
                                <?= number_format($cantidadEmpleados['datos']['Total empleados OFITIC']) ?>
                            </span>
                        </div>
                        <div class="account-item d-flex justify-content-between mb-2">
                            <span class="account-name">Total Empleados</span>
                            <span class="account-balance">
                                <?= number_format($cantidadEmpleados['datos']['Total empleados general']) ?>
                            </span>
                        </div>
                        <div class="account-item d-flex justify-content-between">
                            <span class="account-name">Oficinas</span>
                            <span class="account-balance">
                                <?= number_format($cantidadEmpleados['datos']['Total oficina']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <canvas id="GraUsuario" height="250"></canvas>
                    </div>
                    <div class="mt-3">
                        <select class="form-select" id="tipoGraficoUsuario">
                            <option value="bar">Gráfico de Barras</option>
                            <option value="pie">Gráfico Circular</option>
                            <option value="line">Gráfico de Líneas</option>
                            <option value="doughnut">Gráfico de Donut</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tarjeta de Técnicos -->
        <?php if ($permiso_tecnico) : ?>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Técnicos</h5>
                    <i class="fas fa-user-cog text-muted"></i>
                </div>
                <div class="card-body">
                    <h2 class="total-balance mb-3">
                        <?= number_format($cantidadTecnicos['datos'][0]['Total tecnicos']) ?> Técnicos
                    </h2>
                    <div class="account-list">
                        <div class="account-item d-flex justify-content-between mb-2">
                            <span class="account-name">Soporte Técnico</span>
                            <span class="account-balance">
                                <?= number_format($cantidadTecnicos['datos'][0]['Total soporte']) ?>
                            </span>
                        </div>
                        <div class="account-item d-flex justify-content-between mb-2">
                            <span class="account-name">Redes</span>
                            <span class="account-balance">
                                <?= number_format($cantidadTecnicos['datos'][0]['Total redes']) ?>
                            </span>
                        </div>
                        <div class="account-item d-flex justify-content-between mb-2">
                            <span class="account-name">Telefonía</span>
                            <span class="account-balance">
                                <?= number_format($cantidadTecnicos['datos'][0]['Total telefono']) ?>
                            </span>
                        </div>
                        <div class="account-item d-flex justify-content-between">
                            <span class="account-name">Electrónica</span>
                            <span class="account-balance">
                                <?= number_format($cantidadTecnicos['datos'][0]['Total electronica']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <canvas id="Graftecnicos" height="250"></canvas>
                    </div>
                    <div class="mt-3">
                        <select class="form-select" id="tipoGraficoTecnicos">
                            <option value="bar">Gráfico de Barras</option>
                            <option value="pie">Gráfico Circular</option>
                            <option value="line">Gráfico de Líneas</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tarjeta de Hojas de Servicio -->
        <?php if ($permiso_hoja_servicio) : ?>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Hojas de Servicio</h5>
                    <i class="fas fa-file-alt text-muted"></i>
                </div>
                <div class="card-body">
                    <h2 class="total-balance mb-3">
                        <?= number_format($cantidadHojas['datos'][0]['Cantidad de hojas']) ?> Registradas
                    </h2>
                    <div class="account-list">
                        <div class="account-item d-flex justify-content-between mb-2">
                            <span class="account-name">Área con más hojas</span>
                            <span class="account-balance">
                                <?= htmlspecialchars($cantidadHojas['datos'][0]['Área con más hojas']) ?>
                            </span>
                        </div>
                        <div class="account-item d-flex justify-content-between mb-2">
                            <span class="account-name">Hojas activas</span>
                            <span class="account-balance">
                                <?= number_format($cantidadHojas['datos'][0]['Hojas activas']) ?>
                            </span>
                        </div>
                        <div class="account-item d-flex justify-content-between mb-2">
                            <span class="account-name">Hojas finalizadas</span>
                            <span class="account-balance">
                                <?= number_format($cantidadHojas['datos'][0]['Hojas finalizadas']) ?>
                            </span>
                        </div>
                        <div class="account-item d-flex justify-content-between">
                            <span class="account-name">Hojas eliminadas</span>
                            <span class="account-balance">
                                <?= number_format($cantidadHojas['datos'][0]['Hojas eliminadas']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <canvas id="hojas" height="250"></canvas>
                    </div>
                    <div class="mt-3">
                        <select class="form-select" id="tipoGraficoHojas">
                            <option value="bar">Gráfico de Barras</option>
                            <option value="pie">Gráfico Circular</option>
                            <option value="doughnut">Gráfico de Donut</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tarjeta de Reporte de Bienes -->
        <?php if ($permiso_bien) : ?>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Reporte de Bienes</h5>
                    <i class="fas fa-laptop text-muted"></i>
                </div>
                <div class="card-body">
                    <form id="filtroBienes">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipoBien" class="form-label">Tipo de Bien</label>
                                <select class="form-select" id="tipoBien" name="tipoBien">
                                    <option value="">Todos los tipos</option>
                                    <?php foreach ($tiposBien as $tipo) : ?>
                                        <option value="<?= $tipo['id_tipo_bien'] ?>">
                                            <?= htmlspecialchars($tipo['nombre_tipo_bien']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estadoBien" class="form-label">Estado</label>
                                <select class="form-select" id="estadoBien" name="estadoBien">
                                    <option value="">Todos los estados</option>
                                    <option value="Nuevo">Nuevo</option>
                                    <option value="Usado">Usado</option>
                                    <option value="Dañado">Dañado</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="oficinaBien" class="form-label">Oficina</label>
                                <select class="form-select" id="oficinaBien" name="oficinaBien">
                                    <option value="">Todas las oficinas</option>
                                    <?php foreach ($oficinas as $oficina) : ?>
                                        <option value="<?= $oficina['id_oficina'] ?>">
                                            <?= htmlspecialchars($oficina['nombre_oficina']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" onclick="generarReporteBienes()">
                                    <i class="bi bi-file-earmark-bar-graph"></i> Generar Reporte
                                </button>
                            </div>
                        </div>
                    </form>
                    <div id="reporteBienesLoading" class="text-center my-3" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Generando reporte...</p>
                    </div>
                    <div id="reporteBienesResultado" class="mt-3">
                        <div class="alert alert-info">
                            Seleccione los filtros y haga clic en "Generar Reporte"
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tarjeta de Reporte de Solicitudes -->
        <?php if ($permiso_solicitud) : ?>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Reporte de Solicitudes</h5>
                    <i class="fas fa-tasks text-muted"></i>
                </div>
                <div class="card-body">
                    <form id="filtroSolicitudes">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tecnicoSolicitud" class="form-label">Técnico</label>
                                <select class="form-select" id="tecnicoSolicitud" name="tecnicoSolicitud">
                                    <option value="">Todos los técnicos</option>
                                    <?php foreach ($tecnicos as $tecnico) : ?>
                                        <option value="<?= $tecnico['cedula_empleado'] ?>">
                                            <?= htmlspecialchars($tecnico['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipoServicio" class="form-label">Tipo de Servicio</label>
                                <select class="form-select" id="tipoServicio" name="tipoServicio">
                                    <option value="">Todos los servicios</option>
                                    <?php foreach ($tiposServicio as $servicio) : ?>
                                        <option value="<?= $servicio['id_tipo_servicio'] ?>">
                                            <?= htmlspecialchars($servicio['nombre_tipo_servicio']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estadoSolicitud" class="form-label">Estado</label>
                                <select class="form-select" id="estadoSolicitud" name="estadoSolicitud">
                                    <option value="">Todos los estados</option>
                                    <option value="A">Activas</option>
                                    <option value="I">Finalizadas</option>
                                    <option value="E">Eliminadas</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" onclick="generarReporteSolicitudes()">
                                    <i class="bi bi-file-earmark-bar-graph"></i> Generar Reporte
                                </button>
                            </div>
                        </div>
                    </form>
                    <div id="reporteSolicitudesLoading" class="text-center my-3" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Generando reporte...</p>
                    </div>
                    <div id="reporteSolicitudesResultado" class="mt-3">
                        <div class="alert alert-info">
                            Seleccione los filtros y haga clic en "Generar Reporte"
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tarjeta de Reporte de Materiales -->
        <?php if ($permiso_material) : ?>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Reporte de Materiales</h5>
                    <i class="fas fa-boxes text-muted"></i>
                </div>
                <div class="card-body">
                    <form id="filtroMateriales">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="material" class="form-label">Material</label>
                                <select class="form-select" id="material" name="material">
                                    <option value="">Todos los materiales</option>
                                    <?php foreach ($materiales as $material) : ?>
                                        <option value="<?= $material['id_material'] ?>">
                                            <?= htmlspecialchars($material['nombre_material']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="oficinaMaterial" class="form-label">Ubicación</label>
                                <select class="form-select" id="oficinaMaterial" name="oficinaMaterial">
                                    <option value="">Todas las ubicaciones</option>
                                    <?php foreach ($oficinas as $oficina) : ?>
                                        <option value="<?= $oficina['id_oficina'] ?>">
                                            <?= htmlspecialchars($oficina['nombre_oficina']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fechaInicioMaterial" class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" id="fechaInicioMaterial" name="fechaInicioMaterial">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fechaFinMaterial" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" id="fechaFinMaterial" name="fechaFinMaterial">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" onclick="generarReporteMateriales()">
                                    <i class="bi bi-file-earmark-bar-graph"></i> Generar Reporte
                                </button>
                            </div>
                        </div>
                    </form>
                    <div id="reporteMaterialesLoading" class="text-center my-3" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Generando reporte...</p>
                    </div>
                    <div id="reporteMaterialesResultado" class="mt-3">
                        <div class="alert alert-info">
                            Seleccione los filtros y haga clic en "Generar Reporte"
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Vista cuando no hay permisos -->
        <?php if (!$permiso_interconexion && !$permiso_punto_conexion && !$permiso_usuario && 
                  !$permiso_tecnico && !$permiso_hoja_servicio && !$permiso_switch && 
                  !$permiso_bien && !$permiso_solicitud && !$permiso_material) : ?>
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Bienvenido al Sistema</h5>
                    <i class="fas fa-home text-muted"></i>
                </div>
                <div class="card-body text-center">
                    <img src="assets/img/OFITIC.png" class="img-fluid mb-4" style="max-width: 300px;" alt="Logo OFITIC">
                    <h4 class="mb-3">Sistema de Gestión de Servicios OFITIC</h4>
                    <p class="text-muted">No tienes permisos para visualizar los reportes. Contacta al administrador.</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    </main>

    <!-- ======= Footer ======= -->
    <?php require_once "Componentes/footer.php"; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- JavaScript para las gráficas y filtros -->
    <script src="assets/js/estadistica.js"></script>

</body>
</html>