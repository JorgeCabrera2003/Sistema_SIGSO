<?php require_once("Componentes/head.php"); ?>

<body>
  <?php require_once("Componentes/menu.php"); ?>

  <div class="pagetitle">
    <h1><?php echo $titulo ?></h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="">Dashboard</a></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <div class="row">
    <?php if ($permiso_interconexion || $permiso_punto_conexion || $permiso_switch) { ?>
      <div class="col-md-6 mb-6" id="card-puntos-red">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Patch Panel</h5>
            <i class="fa-solid fa-server text-muted"></i>
          </div>
          <div class="card-body">
            <select class="form-select mt-2" name="pisoFiltrado" id="pisoFiltrado">
              <option value="0">Seleccione un piso</option>
              <?php foreach ($piso as $pisoItem) : ?>
                <option value="<?php echo $pisoItem['id_piso']; ?>">
                  <?php echo $pisoItem['tipo_piso'] . ' ' . $pisoItem['nro_piso']; ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div id="patchPanelInfo" class="mt-3"></div>
            <div id="patchPanelLoading" class="mt-2" style="display:none;">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-6" id="card-switch-red">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Switches</h5>
            <i class="fa-solid fa-server text-muted"></i>
          </div>
          <div class="card-body">
            <select class="form-select mt-2" name="pisoFiltradoSwitch" id="pisoFiltradoSwitch">
              <option value="0">Seleccione un piso</option>
              <?php foreach ($piso as $pisoItem) : ?>
                <option value="<?php echo $pisoItem['id_piso']; ?>">
                  <?php echo $pisoItem['tipo_piso'] . ' ' . $pisoItem['nro_piso']; ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div id="switchPanelInfo" class="mt-3"></div>
            <div id="switchPanelLoading" class="mt-2" style="display:none;">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>

  <div class="mt-4 row">
    <?php if ($permiso_usuario) { ?>
    <div class="col-md-6 mb-6">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Usuarios general</h5>
          <i class="fas fa-user text-muted"></i>
        </div>
        <div class="card-body">
          <h2 class="total-balance mb-3"><?php echo $cantidadEmpleados['datos']['Total usuario']; ?> Usuarios Disponibles</h2>
          <p class="text-muted mb-4">
            Total de usuarios registrados
          </p>
          <div class="account-list">
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Empleados de OFITIC</span>
              <span class="account-balance"><?php echo $cantidadEmpleados['datos']['Total empleados OFITIC']; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Empleados</span>
              <span class="account-balance"><?php echo $cantidadEmpleados['datos']['Total empleados general']; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between">
              <span class="account-name">Cantidad Oficinas</span>
              <span class="account-balance"><?php echo $cantidadEmpleados['datos']['Total oficina']; ?></span>
            </div>
            <span class="account-name">Gráfico</span>
            <div class="grafico-container" style="width: 100%; height: 200px;">
              <canvas id="GraUsuario"></canvas>
            </div>
            <select class="form-select mt-2" id="tipoGraficoUsuario">
              <option value="bar">Barras</option>
              <option value="pie">Torta</option>
              <option value="line">Líneas</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
    <?php if ($permiso_tecnico) { ?>
    <div class="col-md-6 mb-6">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Técnico General</h5>
          <i class="fa-solid fa-server text-muted"></i>
        </div>
        <div class="card-body">
          <h2 class="total-balance mb-3"><?php echo $cantidadTecnicos['datos'][0]['Total tecnicos']; ?> Técnicos Disponibles</h2>
          <div class="account-list">
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Técnico más eficiente</span>
              <span class="account-balance">
                <?php echo $cantidadTecnicos['datos'][0]['Tecnico eficiente']; ?>
              </span>
            </div>
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Redes</span>
              <span class="account-balance"><?php echo $cantidadTecnicos['datos'][0]['Total redes']; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Soporte</span>
              <span class="account-balance"><?php echo $cantidadTecnicos['datos'][0]['Total soporte']; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between">
              <span class="account-name">Electronica</span>
              <span class="account-balance"><?php echo $cantidadTecnicos['datos'][0]['Total electronica']; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between">
              <span class="account-name">Telefonia</span>
              <span class="account-balance"><?php echo $cantidadTecnicos['datos'][0]['Total telefono']; ?></span>
            </div>
            <span class="account-name">Gráfico</span>
            <div class="grafico-container" style="width: 100%; height: 200px;">
              <canvas id="Graftecnicos"></canvas>
            </div>
            <select class="form-select mt-2" id="tipoGraficoTecnicos">
              <option value="bar">Barras</option>
              <option value="pie">Torta</option>
              <option value="line">Líneas</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
    <?php if ($permiso_hoja_servicio) { ?>
    <div class="col-md-6 mb-6">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Hojas servicio</h5>
          <i class="fas fa-user text-muted"></i>
        </div>
        <div class="card-body">
          <h2 class="total-balance mb-3"><?php echo $cantidadHojas['datos'][0]['Cantidad de hojas']; ?> Disponibles</h2>
          <p class="text-muted mb-4">
            Total de hojas procesadas
          </p>
          <div class="account-list">
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Área con más hojas</span>
              <span class="account-balance"><?php echo $cantidadHojas['datos'][0]['Área con más hojas']; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Hojas activos</span>
              <span class="account-balance"><?php echo $cantidadHojas['datos'][0]['Hojas activas']; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Hojas eliminadas</span>
              <span class="account-balance"><?php echo $cantidadHojas['datos'][0]['Hojas eliminadas']; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between">
              <span class="account-name">Hojas finalizadas</span>
              <span class="account-balance"><?php echo $cantidadHojas['datos'][0]['Hojas finalizadas']; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between" style="width: 100%; height: 200px;">
              <span class="account-name">Gráfico</span>
              <div class="grafico-container" style="width: 100%; height: 200px;">
                <canvas id="hojas"></canvas>
              </div>
            </div>
            <select class="form-select mt-4" id="tipoGraficoHojas">
              <option value="bar">Barras</option>
              <option value="pie">Torta</option>
              <option value="line">Líneas</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
    <?php if (!$permiso_interconexion && !$permiso_punto_conexion && !$permiso_usuario && !$permiso_tecnico && !$permiso_hoja_servicio && !$permiso_switch) { ?>
    <div class="col-md-12 mb-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">HOME</h5>
          <i class="fa-solid fa-server text-muted"></i>
        </div>
        <div class="card-body d-flex flex-column align-items-center">
          <p class="text-muted mb-4">
            <img src="assets/img/OFITIC.png" class="d-flex img-logo justify-content-center" style="width: 20.5em; border-radius: 2px" alt="">
          </p>
          <br><br><br><br>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>

  </main>

  <!-- ======= Footer ======= -->
  <?php require_once "Componentes/footer.php"; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>
  <script defer src="assets/js/Dashboard.js"></script>

</body>

</html>