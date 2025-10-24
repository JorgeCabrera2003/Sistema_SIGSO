<?php require_once("Componentes/head.php"); ?>

<body>
  <?php require_once("Componentes/menu.php");
  require_once("Componentes/modal_material.php"); ?>
  <?php require_once "Componentes/modal_historial_material.php"; ?>
  <div class="pagetitle">
    <h1>Materiales</h1>
    <nav>
      
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
             <div class="d-flex justify-content-between">
              <?php if (isset($permisos['material']['registrar']['estado']) && $permisos['material']['registrar']['estado'] == '1') { ?>
                <button type="button" class="btn btn-primary my-4" id="btn-registrar" title="Registrar Material">
                  Registrar Material
                </button>
              <?php } ?>
              <?php if (isset($permisos['material']['reactivar']['estado']) && $permisos['material']['reactivar']['estado'] == '1') { ?>
              <button type="button" class="btn btn-primary my-4" id="btn-consultar-eliminados">
                Materiales Eliminados <i class="fa-solid fa-recycle"></i>
              </button>
              <?php } ?>
            </div>
            <div class="table-responsive">
              <table class="table" id="tabla1">
                <thead>
                  <tr>
                    <?php foreach ($cabecera as $campo)
                      echo "<th scope='col'>$campo</th>"; ?>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <div class="d-grid gap-4 d-md-block">
                <form id="formReporte" method="post">
                  <div class="input-group">
                    <span class="input-group-text">Rango de fechas</span>
                    <input type="date" name="fecha_inicio" class="form-control" id="fecha_inicio" required
                      max="<?php echo date('Y-m-d'); ?>">
                    <span class="input-group-text">a</span>
                    <input type="date" name="fecha_fin" class="form-control" id="fecha_fin" required
                      max="<?php echo date('Y-m-d'); ?>">
                    <button title="Generar Reporte PDF" type="submit" class="btn btn-primary" id="btn-generar-reporte"
                      name="generar_reporte" target="_blank">
                      Reporte <i class="fa-solid fa-file-pdf"></i>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  </main><!-- End #main -->

  <!-- ModalEliminados -->
  <div class="modal fade" id="modalEliminadas" tabindex="-1" role="dialog" aria-labelledby="modalEliminadasTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title text-white" id="modalEliminadasTitle">Materiales Eliminados</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table" id="tablaEliminadas">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Ubicación</th>
                  <th>Stock</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <!-- Contenido dinámico -->
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- ModalEliminados -->

  <?php require_once "Componentes/footer.php"; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>
  <script defer src="assets/js/material.js"></script>
  </div>
</body>

</html>