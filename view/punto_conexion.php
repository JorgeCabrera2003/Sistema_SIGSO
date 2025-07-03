<?php 

    require_once("Componentes/head.php"); 
?>
<body>

    <?php require_once("Componentes/menu.php");
    require_once("Componentes/modal_punto_conexion.php"); ?>

    <div class="pagetitle">
        <h1>Gestionar Punto de Conexión</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item active"><a href="">Gestionar Punto de Conexión</a>
                </li>
            </ol>
        </nav>
    </div> <!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gestionar Punto de Conexión</h5>

                        <div class="d-flex justify-content-between">

                        <?php if (isset($permisos['punto_conexion']['registrar']['estado']) && $permisos['punto_conexion']['registrar']['estado'] == "1") { ?>

                            <button type="button" class="btn btn-primary my-4" id="btn-registrar" title="Asignar un nuevo Punto de Conexión">
                                Asignar Punto de Conexión
                            </button>
                        <?php } ?>

                        </div>

                        <div class="table-responsive">
                            <table class="table" id="tabla1" title="Campo de Consulta de Patch Panel">
                                <thead>
                                    <tr >
                                        <?php foreach ($cabecera as $campo)
                                            echo "<th scope='col'style='text-align: center;'>$campo</th>"; ?>
                                    </tr>
                                </thead>

                                <tbody>
                                </tbody>

                            </table>
                        </div>
<!-- <div class="text-end my-2">
    <form method="post" action="">
        <button type="submit" name="reporte_puntos" value="1" class="btn btn-primary">
            Generar PDF 
        </button>
    </form>
</div> -->
                    </div>
                    
                </div>

            </div>
        </div>
    </section>

    </main><!-- End #main -->
 
    <?php require_once "Componentes/footer.php"; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    <script defer src="assets/js/punto_conexion.js"></script>
    <script src="assets/Select2/js/select2.min.js"></script>
    </div>
</body>

</html>