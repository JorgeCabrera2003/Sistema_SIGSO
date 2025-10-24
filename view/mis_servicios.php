<?php require_once("Componentes/head.php");?>

<body>
<?php require_once("Componentes/menu.php"); 
require_once("Componentes/modal_solicitar.php");?>


  <main id="main" class="main">
    <?php require_once "Componentes/mi_servicio.php";?>
    <div class="pagetitle">
      <h1>Mis Solicitudes</h1>
      <nav>
       
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
               <button type="button" class="btn btn-primary mx-auto my-4" id="btn-solicitud">
      Hacer Solicitud
    </button>    
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
<script>
    // Asegúrate de que esta variable esté disponible globalmente
    const userCedula = '<?php echo $_SESSION['user']['cedula'] ?? ''; ?>';
</script>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script defer src="assets/js/mis_servicios.js"></script>

</body>

</html>