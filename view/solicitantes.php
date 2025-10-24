<?php require_once("Componentes/head.php");?>

<body>
  <?php if (isset($msg)) require_once("Componentes/alert.php");?>
  <?php require_once("Componentes/menu.php");
      require_once("Componentes/modal_registrar_solicitante.php");?>

  <main id="main" class="main">
          
    <div class="pagetitle">
      <h1>Gestión de Solicitantes</h1>
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
            <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#crear_usuario">Registrar Nuevo Solicitante</button>
            <div class="table-responsive">
    <table class="table" id="tabla">
        <thead>
            <tr>
                <?php foreach ($cabecera as $campo) echo "<th scope='col'>$campo</th>"; ?>
                <th scope='col'></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $informacion){ ?>
            <tr>
                <?php foreach ($informacion as $key => $campo) echo ($key != 6)?"<td>$campo</td>":""; ?>
                <td>
                    <form method="post" autocomplete="off">
                    <?php if($informacion["6"]!="Super usuario"){ ?>
                    <button class="btn btn-sm btn-<?php echo $btn_color?>" type="submit" name="<?php echo $btn_name?>" value="<?php echo "$origen$informacion[$btn_value]"; ?>" title="Eliminar"><i class="bi bi-<?php echo $btn_icon?>"></i></button>
                    <?php  }?>
                  </form>
                </td>
            </tr>
            <?php  }?>
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
  <script src="js/solicitante.js"></script>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

</body>

</html>