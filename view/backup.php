<?php require_once("Componentes/head.php"); ?>

<body>
    <?php require_once("Componentes/menu.php"); ?>

    <div class="pagetitle">
        <h1>Gestión de Backups</h1>
        <nav>
            <ol class="breadcrumb">
                 
                <li class="breadcrumb-item active"><a href="">Gestión de Backups</a></li>
            </ol>
        </nav>
    </div> <!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                         <form method="POST" action="?page=backup">
                            <select class="form-select" name="base_datos" id="" required title="Seleccione la base de datos">
                                <option value="" hidden>Seleccione la base de datos</option>
                                <option value="sistema"><i class="fa fa-sign-in" aria-hidden="true"></i> Sistema</option>
                                <option value="usuario"><i class="fa fa-user" aria-hidden="true"></i> Usuario</option>
                            </select>
                            <button type="submit" name="generar" class="btn btn-primary my-4" title="Generar Backup">
                                Generar Backup
                            </button>
                        </form>
                        <div class="table-responsive">
                            <table class="table" id="tablaBackups">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre del Archivo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($backups['archivos'])): ?>
                                        <?php foreach ($backups['archivos'] as $index => $archivo): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= $archivo ?></td>
                                                <td>
                                                    <form method="POST" action="?page=backup" style="display:inline;">
                                                        <input type="hidden" name="filename" value="<?= $archivo ?>">
                                                        <button type="submit" name="eliminar" value="<?= $archivo ?>" class="btn btn-danger">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                        <button type="submit" name="importar" value="<?= $archivo ?>" class="btn btn-success">
                                                            <i class="fa-solid fa-upload"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3">No hay backups disponibles.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php require_once "Componentes/footer.php"; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
            <script>
// Pasar variable PHP a JS
const boton = document.querySelector('button[name="generar"]') || null;
const botonEliminar = document.querySelector('button[name="eliminar"]') || null;
const botonImportar = document.querySelector('button[name="importar"]') || null;

boton.addEventListener('click', function(event) {

    Swal.fire({
    title: 'Procesando',
    html: 'Por favor espera...',
    allowOutsideClick: false,
    didOpen: () => {
        Swal.showLoading();
    }
});

// Simular una operación que tarda 2 segundos
setTimeout(() => {
    Swal.fire({
        title: '¡Completado!',
        text: 'La operación se realizó con éxito',
        icon: 'success'
    });
}, 200);
});

botonEliminar.addEventListener('click', function(event) {

    Swal.fire({
    title: 'Procesando',
    html: 'Por favor espera...',
    allowOutsideClick: false,
    didOpen: () => {
        Swal.showLoading();
    }
});

// Simular una operación que tarda 2 segundos
setTimeout(() => {
    Swal.fire({
        title: '¡Completado!',
        text: 'La operación se realizó con éxito',
        icon: 'success'
    });
}, 200);
});

botonImportar.addEventListener('click', function(event) {

    Swal.fire({
    title: 'Procesando',
    html: 'Por favor espera...',
    allowOutsideClick: false,
    didOpen: () => {
        Swal.showLoading();
    }
});

// Simular una operación que tarda 2 segundos
setTimeout(() => {
    Swal.fire({
        title: '¡Completado!',
        text: 'La operación se realizó con éxito',
        icon: 'success'
    });
}, 1500);
});
</script>
</body>

</html>
