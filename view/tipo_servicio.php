<?php require_once("Componentes/head.php"); ?>

<body>
  <?php require_once("Componentes/menu.php");
  require_once("Componentes/modal_tipo_servicio.php"); ?>



  <div class="pagetitle">
    <h1>Gestionar Tipos de Servicios</h1>
    <nav>
    </nav>
  </div> <!-- End Page Title -->

  <section class="section mt-4">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Gestionar Tipos de Servicios</h5>
            <?php if (isset($permisos['tipo_servicio']['registrar']['estado']) && $permisos['tipo_servicio']['registrar']['estado'] == 1) { ?>
              <button type="button" class="btn btn-primary mx-auto my-4" id="btn-registrar">
                Registrar Tipo de Servicio
              </button>
            <?php } ?>
            <br>
            <div class="d-flex justify-content-center d-none" id="spinnertabla1">
              <div class="spinner-border" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
            </div>
            <div class="table-responsive" id="divtabla1">
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
            </div>
          </div>
        </div>

      </div>

    </div>
  </section>
  <!-- ModalEliminados -->
  <div class="modal fade" id="modalEliminadas" tabindex="-1" role="dialog" aria-labelledby="modalEliminadasTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title text-white" id="modalEliminadasTitle">Tipos de Servicios Eliminads</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table" id="tablaEliminadas">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Encargado</th>
                  <th>Restaurar</th>
                </tr>
              </thead>
              <tbody>

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

  <!-- ModalConfiguracion -->
  <div class="modal fade" id="modalConfigurar" tabindex="-1" role="dialog" aria-labelledby="modalConfigurarTitle"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title text-white" id="modalConfigurarTitle">Configurar Servicio</h5>
        </div>
        <div class="modal-body">
          <div class="grid" id="grid-configurar">

            <div class="row mt-4" id="">
              <div class="col-lg-12">
                <fieldset class="permission-group" id="divServicio">
                  <div class="row mb-3">
                    <div class="col-lg-8">
                      <legend class="group-header">
                        <label class="" for="servicios" id="titulo-configurar"></label>
                      </legend>
                    </div>
                    <div class="col-lg-4">
                      <button type="button" class="btn btn-secondary" id="agregar-config">Añadir</button>
                    </div>
                  </div>
                  <div class="row mt-4" id="spinner-configuracion">
                    <div class="col-lg-12">
                      <div class="d-flex justify-content-center d-none">
                        <div class="spinner-border" role="status">
                          <span class="visually-hidden">Cargando...</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="container div-configurar" id="div-configurar">


                  </div>
                </fieldset>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="retroceder-config">Retroceder</button>
          <button type="button" class="btn btn-primary" id="guardar-config">Guardar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- ModalConfiguracion -->
  </main><!-- End #main -->

  <?php require_once "Componentes/footer.php"; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>
  <script defer src="assets/js/tipo_servicio.js"></script>
  </div>
</body>

</html>