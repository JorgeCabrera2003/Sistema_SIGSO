<?php require_once("Componentes/head.php");?>

<body>
<?php require_once("Componentes/menu.php");?>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1><?php echo $titulo ?></h1>
      <nav>
       
      </nav>
    </div>                                 <!-- End Page Title -->

    <?php
    switch ($datos_hoja["tipo_s"]) {
      case 'Soporte Técnico':
        require_once "Componentes/tecnico.php";
        break;
      case 'Redes':
          require_once "Componentes/redes.php";
          break;
      case 'Telefonía':
            require_once "Componentes/telefonia.php";
            break;
      case 'Electrónica':
            require_once "Componentes/electronica.php";
            break;
    }
    ?>

    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php require_once "Componentes/footer.php"; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script defer src="js/hoja.js"></script>

</body>

</html>