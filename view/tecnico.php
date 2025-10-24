<?php require_once("Componentes/head.php"); ?>

<body>
  <?php require_once("Componentes/menu.php");
  require_once("Componentes/modal_tecnico.php"); ?>

  <div class="pagetitle">
    <h1>Gestionar Técnicos</h1>
    <nav>
      
    </nav>
  </div> <!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
             <?php if(isset($permisos['tecnico']['registrar']['estado']) && $permisos['tecnico']['registrar']['estado'] == '1'){ ?>
            <button type="button" class="btn btn-primary mx-auto my-4" id="btn-registrar">
              Registrar Técnico
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
  <script defer src="assets/js/tecnico.js"></script>
  </div>
</body>

</html>
