<?php require_once("Componentes/head.php"); ?>

<body>
  <?php require_once("Componentes/menu.php");
  require_once("Componentes/modal_tipo_servicio.php"); ?>


  
    <div class="pagetitle">
      <h1>Gestionar Tipos de Servicios</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="">Home</a></li>
          <li class="breadcrumb-item active"><a href="">Gestionar Tipos de Servicios</a>
          </li>
        </ol>
      </nav>
    </div>                                          <!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Gestionar Tipos de Servicios</h5>
              <?php if (isset($permisos['tipo_servicio']['registrar']['estado']) && $permisos['tipo_servicio']['registrar']['estado'] == 1){ ?>
              <button type="button" class="btn btn-primary mx-auto my-4" id="btn-registrar">
                Registrar Tipo de Servicio
              </button>
              <?php }?>
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

  </main><!-- End #main -->

  <?php require_once "Componentes/footer.php"; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>
  <script defer src="assets/js/tipo_servicio.js"></script>
  </div>
</body>

</html>