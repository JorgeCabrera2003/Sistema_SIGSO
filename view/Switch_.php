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
    require_once("Componentes/modal_Switch_.php"); ?>

    <div class="pagetitle">
        <h1>Gestionar Switch</h1>
        <nav>
            
        </nav>
    </div> <!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
 
                        <div class="d-flex justify-content-between">

                        <?php if (isset($permisos['switch']['registrar']['estado']) && $permisos['switch']['registrar']['estado'] == "1") { ?>

                            <button type="button" class="btn btn-primary my-4" id="btn-registrar" title="Agregar nuevo Switch">
                                Registrar Switch
                            </button>

                        <?php } ?>


                        <?php if (isset($permisos['switch']['restaurar']['estado']) && $permisos['switch']['restaurar']['estado'] == "1") { ?>

                            <button type="button" class="btn btn-primary my-4" id="btn-consultar-eliminados" title="Consulta los Switch Eliminados">
                                Switch Eliminados <i class="fa-solid fa-recycle"></i>
                            </button>

                        <?php } ?>

                        </div>

                        <div class="table-responsive">
                            <table class="table" id="tabla1" title="Campo de Consulta de Switch">
                                <thead>
                                    <tr>
                                        <?php foreach ($cabecera as $campo)
                                            echo "<th scope='col' style='text-align: center;'>$campo</th>"; ?>
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

    <!-- Modal Eliminados -->
    <div class="modal fade" id="modalEliminadas" tabindex="-1" role="dialog" aria-labelledby="modalEliminadasTitle" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white" id="modalEliminadasTitle">Switch Eliminados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title="Cerrar Modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="tablaEliminadas" title="Campo de Consulta de Switch">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Código Bien</th>
                                    <th>Cantidad de Puertos</th>
                                    <th>Serial</th>
                                    <th>Restaurar</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" title="Cerrar Modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ModalEliminados -->
    
    <?php require_once "Componentes/footer.php"; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script defer src="assets/js/Switch_.js"></script>
     <script src="assets/Select2/js/select2.min.js"></script>
    </div>
</body>
</html>