<?php require_once("Componentes/head.php"); ?>

<body>
    <?php require_once("Componentes/menu.php");
    require_once("Componentes/modal_categoria.php"); ?>

    <div class="pagetitle">
        <h1>Gestionar Categorías</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item active"><a href="">Gestionar Categorías</a>
                </li>
            </ol>
        </nav>
    </div> <!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gestionar Categorías</h5>
                        <div class="d-flex justify-content-between">
                            <?php if (isset($permisos['tipo_bien']['registrar']['estado']) && $permisos['tipo_bien']['registrar']['estado'] == '1') {?>
                            <button type="button" class="btn btn-primary my-4" id="btn-registrar">
                                Registrar Categoría
                            </button>
                            <?php }?>
                            <?php if (isset($permisos['tipo_bien']['restaurar']['estado']) && $permisos['tipo_bien']['restaurar']['estado'] == '1') {?>
                            <button type="button" class="btn btn-primary my-4" id="btn-consultar-eliminados">
                                Categorías Eliminados <i class="fa-solid fa-recycle"></i>
                            </button>
                            <?php }?>
                        </div>
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

    <!-- ModalEliminados -->
    <div class="modal fade" id="modalEliminadas" tabindex="-1" role="dialog" aria-labelledby="modalEliminadasTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white" id="modalEliminadasTitle">Categorías Eliminados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="tablaEliminadas">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Restaurar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Hola papurri -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ModalEliminados -->
    
    <?php require_once "Componentes/footer.php"; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    <script defer src="assets/js/categoria.js"></script>
    </div>
</body>

</html>