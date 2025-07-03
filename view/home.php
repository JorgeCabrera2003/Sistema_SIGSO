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
    <div class="col-md-6 mb-6">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Puntos de Red</h5>
          <i class="fa-solid fa-server text-muted"></i>
        </div>
        <div class="card-body">
          <h2 class="total-balance mb-3">30 Disponibles</h2>
          <p class="text-muted mb-4">
            Piso 5
          </p>
          <div class="account-list">
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Chequeados</span>
              <span class="account-balance">28</span>
            </div>
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">funcionando</span>
              <span class="account-balance">18</span>
            </div>
            <div class="account-item d-flex justify-content-between">
              <span class="account-name">Fuera de servicio</span>
              <span class="account-balance">10</span>
            </div>
            <span class="account-name">Gráfico</span>
            <div class="grafico-container" style="width: 100%; height: 200px;">
              <canvas id="miGrafico"></canvas>
            </div>
            <select class="form-select mt-2" id="tipoGraficoRed">
              <option value="bar">Barras</option>
              <option value="pie">Torta</option>
              <option value="line">Líneas</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-6">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Usuarios general</h5>
          <i class="fas fa-user text-muted"></i>
        </div>
        <div class="card-body">
          <h2 class="total-balance mb-3"><?php echo $cantidadUsuarios; ?> Disponibles</h2>
          <p class="text-muted mb-4">
            Total de usuarios registrados
          </p>
          <div class="account-list">
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Usuarios registrados</span>
              <span class="account-balance"><?php echo $cantidadUsuarios; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Empleados</span>
              <span class="account-balance"><?php echo $cantidadEmpleados; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between">
              <span class="account-name">Cantidad Oficinas</span>
              <span class="account-balance"><?php echo $cantidadOficina; ?></span>
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
  </div>

  <div class="mt-3 row">
    <div class="col-md-6 mb-6">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Técnico General</h5>
          <i class="fa-solid fa-server text-muted"></i>
        </div>
        <div class="card-body">
          <h2 class="total-balance mb-3">5 Disponibles</h2>
          <p class="text-muted mb-4">
            Piso 5
          </p>
          <div class="account-list">
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Más eficiente</span>
              <span class="account-balance">
                <?php echo isset($nombreTecnicoMasEficiente) ? $nombreTecnicoMasEficiente : 'N/A'; ?>
              </span>
            </div>
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Redes</span>
              <span class="account-balance">2</span>
            </div>
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Soporte</span>
              <span class="account-balance">1</span>
            </div>
            <div class="account-item d-flex justify-content-between">
              <span class="account-name">Electronica</span>
              <span class="account-balance">2</span>
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
    <div class="col-md-6 mb-6">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Usuarios general</h5>
          <i class="fas fa-user text-muted"></i>
        </div>
        <div class="card-body">
          <h2 class="total-balance mb-3"><?php echo $cantidadUsuarios; ?> Disponibles</h2>
          <p class="text-muted mb-4">
            Total de usuarios registrados
          </p>
          <div class="account-list">
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Usuarios registrados</span>
              <span class="account-balance"><?php echo $cantidadUsuarios; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between mb-2">
              <span class="account-name">Empleados</span>
              <span class="account-balance"><?php echo $cantidadEmpleados; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between">
              <span class="account-name">Cantidad Oficinas</span>
              <span class="account-balance"><?php echo $cantidadOficina; ?></span>
            </div>
            <div class="account-item d-flex justify-content-between" style="width: 100%; height: 200px;">
              <span class="account-name">Gráfico</span>
              <div class="grafico-container" style="width: 100%; height: 200px;">
                <canvas id="GraUsuario"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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