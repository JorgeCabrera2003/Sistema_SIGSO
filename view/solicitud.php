<?php require_once("Componentes/head.php"); ?>

<body>
  <?php require_once("Componentes/menu.php"); ?>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1><?php echo $titulo ?></h1>
      <nav>
        
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
               
              <!-- Modal para crear/editar solicitudes -->
              <?php require_once "Componentes/modal_solicitud.php"; ?>
              
              <div class="d-flex justify-content-between mb-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSolicitud" id="btn-nueva-solicitud" title="Nueva Solicitud">
                  <i class="bi bi-plus-circle me-1"></i> Nueva Solicitud
                </button>


                <button type="button" class="btn btn-primary" id="btn-solicitudes-eliminadas" title="Solicitudes Eliminadas" data-bs-toggle="modal" data-bs-target="#modalEliminadas">
                  Solicitudes Eliminadas <i class="fa-solid fa-recycle"></i>
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

  <!-- Modal Solicitudes Eliminadas -->
  <div class="modal fade" id="modalEliminadas" tabindex="-1" role="dialog" aria-labelledby="modalEliminadasTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title text-white" id="modalEliminadasTitle">Solicitudes Eliminadas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table" id="tablaEliminadas">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Solicitante</th>
                  <th>Cédula</th>
                  <th>Dependencia</th>
                  <th>Motivo</th>
                  <th>reactivar</th>
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
  
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>
  <script src="https://cdn.datatables.net/plug-ins/1.11.5/i18n/Spanish.json"></script>
  <script src="assets/js/solicitud.js"></script>
</body>
</html>