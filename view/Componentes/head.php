<!DOCTYPE html>
<html lang="es">
  <head>
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">
    <link href="assets/Select2/css/select2.min.css" rel="stylesheet" />
    <link href="assets/Select2/css/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $titulo?></title>
    <!-- Bootstrap CSS (for structure only) -->
    <link
      href="assets/bootstrap/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <!-- Custom Ya estoy en esta wea!!!!! -->
    <link rel="stylesheet" href="assets/css/main.css" />
    <!-- Font Awesome for icons -->
    <link
      rel="stylesheet"
      href="vendor/fortawesome/font-awesome/css/all.min.css"
    />
    <link
      rel="stylesheet"
      href="assets/DataTables/datatables.css"
    />
    <script>
      const htmlElement = document.documentElement;

      const savedTheme = localStorage.getItem("theme");
      if (
        savedTheme === "dark" ||
        (!savedTheme && window.matchMedia("(prefers-color-scheme: dark)").matches)
      ) {
        htmlElement.classList.add("dark");
      }
    </script>
    <script src="vendor/components/jquery/jquery.min.js"></script>
    <script defer src="assets/js/main.js"></script>
    <script src="vendor/fortawesome/font-awesome/js/all.min.js"></script>
    <script src="assets/js/Chart.min.js"></script>
    <script defer src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/utils.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script src="vendor/datatables.net/datatables.net/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

     <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Luego Select2 CSS y JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Estilos para que Select2 luzca como los selects normales -->
    

   <style>

    #tabla1 td,
     #tabla1 th {
       text-align: center;
     }
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
    
  </head>