<?php require_once("Componentes/head.php"); ?>

<body>
  <?php require_once("Componentes/menu.php"); ?>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1><?php echo $titulo ?></h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="?page=home">Inicio</a></li>
          <li class="breadcrumb-item active"><?php echo $titulo ?></li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Listado de Solicitudes</h5>
              
              <!-- Modal para crear/editar solicitudes -->
              <?php require_once "Componentes/modal_solicitud.php"; ?>
              
              <div class="d-flex justify-content-between mb-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSolicitud">
                  <i class="bi bi-plus-circle me-1"></i> Nueva Solicitud
                </button>
                
                <button type="button" class="btn btn-secondary" id="btn-refrescar">
                  <i class="bi bi-arrow-clockwise me-1"></i> Actualizar
                </button>
              </div>

              <div class="table-responsive">
                <table class="table table-hover" id="tablaSolicitudes">
                  <thead class="table-light">
                    <tr>
                      <?php foreach ($cabecera as $campo): ?>
                        <th scope="col"><?php echo $campo ?></th>
                      <?php endforeach; ?>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>

              <hr>
              
              <form method="post" autocomplete="off" class="row g-3">
                <div class="col-md-4">
                  <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                  <input type="date" class="form-control" id="fechaInicio" name="inicio" 
                    max="<?php echo date('Y-m-d'); ?>" required>
                </div>
                
                <div class="col-md-4">
                  <label for="fechaFinal" class="form-label">Fecha Final</label>
                  <input type="date" class="form-control" id="fechaFinal" name="final" 
                    value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
                </div>
                
                <div class="col-md-4 d-flex align-items-end">
                  <button type="submit" name="reporte" formtarget="_blank" class="btn btn-primary">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Generar Reporte
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php require_once "Componentes/footer.php"; ?>
  
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>
  <script src="https://cdn.datatables.net/plug-ins/1.11.5/i18n/Spanish.json"></script>
  <script src="assets/js/solicitud.js"></script>
</body>
</html>