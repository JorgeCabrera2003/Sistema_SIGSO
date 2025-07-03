<?php require_once("Componentes/head.php"); ?>

<body>
    <?php require_once("Componentes/menu.php");
    require_once("Componentes/modal_bien.php"); ?>

    <div class="pagetitle">
        <h1>Gestionar Bienes</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item active"><a href="">Gestionar Bienes</a>
                </li>
            </ol>
        </nav>
    </div> <!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gestionar Bienes</h5>
                        <div class="d-flex justify-content-between">
                            <?php if(isset($permisos['bien']['registrar']['estado']) && $permisos['bien']['registrar']['estado'] == '1') {?>
                            <button type="button" class="btn btn-primary my-4" id="btn-registrar">
                                Registrar Bien
                            </button>
                            <?php } ?>
                            <?php if(isset($permisos['bien']['restaurar']['estado']) && $permisos['bien']['restaurar']['estado'] == '1') {?>
                            <button type="button" class="btn btn-primary my-4" id="btn-consultar-eliminados">
                                Bienes Eliminados <i class="fa-solid fa-recycle"></i>
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
                    <h5 class="modal-title text-white" id="modalEliminadasTitle">Bienes Eliminados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="tablaEliminadas">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Código</th>
                                    <th>Tipo</th>
                                    <th>Marca</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Restaurar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Contenido dinámico -->
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
    <? require_once("Componentes/modal_bien.php"); ?>
    <?php require_once "Componentes/footer.php"; ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    <!-- Carga primero jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Luego DataTables CSS y JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"/>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <!-- Luego Select2 CSS y JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Estilos para que Select2 luzca como los selects normales -->
    <style>
    /* Ajusta el contenedor principal de Select2 */
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        height: calc(3.5rem + 2px); /* igual que .form-select de Bootstrap 5 */
        padding: 0.375rem 0.75rem;
        display: flex;
        align-items: center;
        font-size: 1rem;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default .select2-selection--single.select2-selection--focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #212529;
        line-height: normal;
        padding-left: 0;
        padding-right: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        right: 0.75rem;
        top: 0;
        width: 2.25rem;
    }
    /* Ajusta el dropdown para que luzca igual */
    .select2-container--default .select2-dropdown {
        border-radius: 0.375rem;
        border: 1px solid #ced4da;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #0d6efd;
        color: #fff;
    }
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #e9ecef;
        color: #212529;
    }
    /* Ajusta el ancho para que sea igual al select original */
    .select2-container {
        width: 100% !important;
    }
    /* Corrige el padding dentro de los formularios flotantes */
    .form-floating > .select2-container--default .select2-selection--single {
        height: calc(3.5rem + 2px);
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }
    /* Corrige el label flotante */
    .form-floating > label {
        z-index: 2;
        pointer-events: none;
        transition: all .1s ease-in-out;
    }

    /* Efecto de validación Bootstrap para Select2 */
    .select2-container--default .select2-selection--single.is-valid,
    .select2-container--default .select2-selection--multiple.is-valid {
        border-color: #198754 !important;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25) !important;
    }
    .select2-container--default .select2-selection--single.is-invalid,
    .select2-container--default .select2-selection--multiple.is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
    }
    /* Permite que el select2 tome el estado del select original */
    select.is-valid + .select2 .select2-selection {
        border-color: #198754 !important;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25) !important;
    }
    select.is-invalid + .select2 .select2-selection {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
    }
    </style>

    <script defer src="assets/js/bien.js"></script>
    </div>
</body>

</html>