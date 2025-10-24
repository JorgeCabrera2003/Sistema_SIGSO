<?php require_once("Componentes/head.php");?>

<body>
<?php require_once("Componentes/menu.php");
      require_once("Componentes/modal_rol.php");?>
      

  <main id="main" class="main">
          
    <div class="pagetitle">
      <h1>Roles y Permisos</h1>
      <nav>
        
      </nav>
    </div><!-- End Page Title -->
    <?php 
      if (isset($confirmacion)) {
    ?>
      <div class="alert alert-<?php echo $color; ?> alert-dismissible fade show" role="alert">
         <strong><?php echo $confirmacion; ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php 
      }
     ?>
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body table-responsive py-3">
              <?php if (isset($permisos['rol']['registrar']['estado']) && $permisos['rol']['registrar']['estado'] == "1") { ?>
            <button class="btn btn-primary mb-4" id="btn-registrar">Registrar Rol</button>
                <?php }?>
            <div class="table-responsive">
                <table class="table display" id="tabla1">
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

  <?php require_once "Componentes/footer.php"; ?>
  <script defer src="assets/js/rol.js"></script>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

</body>

</html>