<?php require_once("Componentes/head.php"); ?>

<body>
  <?php require_once("Componentes/menu.php");
  require_once("Componentes/modal_empleado.php"); ?>

  <div class="pagetitle">
    <h1>Gestionar Empleados</h1>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <!-- Botones de acción -->
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div>
                <?php if(isset($permisos['empleado']['registrar']['estado']) && $permisos['empleado']['registrar']['estado'] == '1'){ ?>
                <button type="button" class="btn btn-primary" id="btn-registrar">
                  <i class="bi bi-person-plus me-1"></i>Registrar Empleado
                </button>
                <?php }?>
              </div>
              <div>
                <?php if(isset($permisos['empleado']['reactivar']['estado']) && $permisos['empleado']['reactivar']['estado'] == '1'){ ?>
                <button type="button" class="btn btn-primary" id="btn-consultar-eliminados">
                  <i class="bi bi-archive me-1"></i>Empleados Eliminados
                </button>
                <?php }?>
              </div>
            </div>

            <!-- Tabla de empleados activos -->
            <div id="seccion-activos">
              <div class="table-responsive">
                <table class="table table-striped table-hover" id="tabla1">
                  <thead class="table-dark">
                    <tr>
                      <?php foreach ($cabecera as $campo)
                        echo "<th scope='col'>$campo</th>"; ?>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Los datos se cargan dinámicamente -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  </main><!-- End #main -->

  <!-- Modal para Empleados Eliminados -->
  <div class="modal fade" id="modalEliminados" tabindex="-1" role="dialog" aria-labelledby="modalEliminadosTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title text-white" id="modalEliminadosTitle">
            <i class="bi bi-archive me-2"></i>Empleados Eliminados
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="tablaEliminados">
              <thead class="table-dark">
                <tr>
                  <th>Cédula</th>
                  <th>Nombre</th>
                  <th>Apellido</th>
                  <th>Teléfono</th>
                  <th>Correo</th>
                  <th>Dependencia</th>
                  <th>Unidad</th>
                  <th>Cargo</th>
                  <th>Reactivar</th>
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

  <?php require_once "Componentes/footer.php"; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <script defer src="assets/js/empleado.js"></script>
</body>
</html>