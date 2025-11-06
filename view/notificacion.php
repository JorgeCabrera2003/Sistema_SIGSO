<?php require_once("Componentes/head.php");?>

<body>
<?php require_once("Componentes/menu.php"); ?>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Notificaciones</h1>
      <nav>
        
      </nav>
    </div><!-- End Page Title -->

    <!-- Botón movido dentro de la tarjeta (más cercano a la tabla) y con estilo menos agresivo -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                  <button id="marcar-todas-page" class="btn btn-sm btn-outline-secondary">Marcar todas como leídas</button>
                </div>
               <div class="table-responsive">
                  <table class="table" id="tabla1">
                      <thead>
                          <tr>
                              <?php foreach ($cabecera as $campo) echo "<th scope='col'>$campo</th>"; ?>
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

  <!-- ======= Footer ======= -->
  <?php require_once "Componentes/footer.php"; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script defer src="assets/js/notificacion.js"></script>

</body>

</html>