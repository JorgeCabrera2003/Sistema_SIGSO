<?php 

    require_once("Componentes/head.php"); 
?>
<style>
     #tabla1 td,
     #tabla1 th {
       text-align: center;
     }
</style>
<body>

    <?php require_once("Componentes/menu.php");
    require_once("Componentes/modal_interconexion.php"); ?>

    <div class="pagetitle">
        <h1>Gestionar Interconexión</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item active"><a href="">Gestionar Interconexión</a>
                </li>
            </ol>
        </nav>
    </div> <!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gestionar Interconexión</h5>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-primary my-4" id="btn-registrar" title="Asignar una nueva Interconexión">
                                Asignar Interconexión
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table" id="tabla1" title="Campo de Consulta de Interconexión">
                                <thead>
                                    <tr>
                                        <th scope='col'>ID</th>
                                        <th scope='col'>Switch</th>
                                        <th scope='col'>Puerto Switch</th>
                                        <th scope='col'>Patch Panel</th>
                                        <th scope='col'>Puerto Patch Panel</th>
                                        <th scope='col'>Modificar / Eliminar</th>
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
    <script defer src="assets/js/interconexion.js"></script>
</body>
</html>