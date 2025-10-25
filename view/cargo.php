<?php require_once("Componentes/head.php"); ?>

<body>
    <?php require_once("Componentes/menu.php");
    require_once("Componentes/modal_cargo.php"); ?>

    <div class="pagetitle">
        <h1>Gestionar Cargos</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                         
                        <div class="d-flex justify-content-between">
                            <?php if (isset($permisos['cargo']['registrar']['estado']) && $permisos['cargo']['registrar']['estado'] == '1') { ?>
                                <button type="button" class="btn btn-primary my-4" id="btn-registrar-cargo">
                                    Registrar Cargo
                                </button>
                            <?php } ?>
                            <?php if (isset($permisos['cargo']['reactivar']['estado']) && $permisos['cargo']['reactivar']['estado'] == '1') { ?>
                                <button type="button" class="btn btn-primary my-4" id="btn-consultar-eliminados">
                                    Cargos Eliminadas <i class="fa-solid fa-recycle"></i>
                                </button>
                            <?php } ?>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="tablaCargos">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Acciones</th>
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

    <!-- ModalEliminados -->
    <div class="modal fade" id="modalEliminadas" tabindex="-1" role="dialog" aria-labelledby="modalEliminadasTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white" id="modalEliminadasTitle">Cargos Eliminados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="tablaEliminadas">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>reactivar</th>
                                </tr>
                            </thead>
                            <tbody>

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

    </main>
    <?php require_once "Componentes/footer.php"; ?>
    <script defer src="assets/js/cargo.js"></script>
</body>

</html>