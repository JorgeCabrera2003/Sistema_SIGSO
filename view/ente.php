<?php require_once("Componentes/head.php"); ?>

<body>
  <?php require_once("Componentes/menu.php");
  require_once("Componentes/modal_ente.php"); ?>



  <div class="pagetitle">
    <h1>Entes</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="">Home</a></li>
        <li class="breadcrumb-item active"><a href="">Entes</a>
        </li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Entes</h5>
            <div class="d-flex justify-content-between">
              <?php if (isset($permisos['ente']['registrar']['estado']) && $permisos['ente']['registrar']['estado'] == "1") { ?>
                <button type="button" class="btn btn-primary my-4" id="btn-registrar" title="Registrar Ente">
                  Registrar Ente
                </button>
              <?php } ?>
              <?php if (isset($permisos['ente']['reactivar']['estado']) && $permisos['ente']['reactivar']['estado'] == '1') { ?>
                <button type="button" class="btn btn-primary my-4" id="btn-consultar-eliminados">
                  Entes Eliminados <i class="fa-solid fa-recycle"></i>
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
          <h5 class="modal-title text-white" id="modalEliminadasTitle">Entes Eliminados</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table" id="tablaEliminadas">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Responsable</th>
                  <th>Teléfono</th>
                  <th>Ubicación</th>
                  <th>Tipo de Ente</th>
                  <th>Reactivar</th>
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

  </main><!-- End #main -->

  <?php require_once "Componentes/footer.php"; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>
  <script defer src="assets/js/ente.js"></script>
  </div>
</body>

</html>