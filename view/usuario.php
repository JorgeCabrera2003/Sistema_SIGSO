<?php require_once("Componentes/head.php");?>

<body>
<?php require_once("Componentes/menu.php");
      require_once("Componentes/modal_usuario.php");?>

  <main id="main" class="main">
          
    <div class="pagetitle">
      <h1>Gestión de Usuarios</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="">Home</a></li>
          <li class="breadcrumb-item active">Gestión de Usuarios</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body table-responsive py-3">
            <button class="btn btn-primary mb-4"  id="btn-registrar">Registrar Usuario</button>

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
  <script defer src="assets/js/usuarios.js"></script>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

</body>

</html>