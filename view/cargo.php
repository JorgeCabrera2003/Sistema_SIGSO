<?php require_once("Componentes/head.php"); ?>

<body>
    <?php require_once("Componentes/menu.php");
    require_once("Componentes/modal_cargo.php"); ?>

    <div class="pagetitle">
        <h1>Gestionar Cargos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item active">Gestionar Cargos</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gestionar Cargos</h5>
                        <?php if (isset($permisos['cargo']['registrar']['estado']) && $permisos['cargo']['registrar']['estado'] == '1') { ?>
                        <button type="button" class="btn btn-primary my-4" id="btn-registrar-cargo">
                            Registrar Cargo
                        </button>
                        <?php } ?>
                        <div class="table-responsive">
                            <table class="table" id="tablaCargos">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once "Componentes/footer.php"; ?>
    <script defer src="assets/js/cargo.js"></script>
</body>

</html>
