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
    
    <?php
      // Determinar href del tema actual (utileria.php define $tema_actual)
      $theme_href = 'assets/css/temas/default.css';
      if (isset($tema_actual)) {
        switch ($tema_actual) {
          case 1: $theme_href = 'assets/css/temas/rosa.css'; break;
          case 2: $theme_href = 'assets/css/temas/azul.css'; break;
          case 3: $theme_href = 'assets/css/temas/verde.css'; break;
          case 4: $theme_href = 'assets/css/temas/rojo.css'; break;
          case 5: $theme_href = 'assets/css/temas/morado.css'; break;
          default: $theme_href = 'assets/css/temas/default.css'; break;
        }
      }
    ?>
    <link id="theme-stylesheet" rel="stylesheet" href="<?php echo $theme_href; ?>" />
    <script>
      // Aplicar tema guardado en localStorage si existe (permite persistencia inmediata entre vistas)
      (function(){
        try {
          const map = {
            0: 'assets/css/temas/default.css',
            1: 'assets/css/temas/rosa.css',
            2: 'assets/css/temas/azul.css',
            3: 'assets/css/temas/verde.css',
            4: 'assets/css/temas/rojo.css',
            5: 'assets/css/temas/morado.css'
          };
          const sel = localStorage.getItem('selectedTheme');
          if (sel !== null && typeof sel !== 'undefined') {
            const id = parseInt(sel, 10);
            if (!isNaN(id) && map[id]) {
              var link = document.getElementById('theme-stylesheet');
              if (link) link.href = map[id];
            }
          }
        } catch(e) {}
      })();
    </script>
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

    <!-- Loader styles: pantalla de carga global (funciona sin internet) -->
    <style>
      /* Overlay + spinner usando pseudo-elementos de body */
      html:not(.page-ready) > body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: #ffffff;
        z-index: 2000;
      }
      html:not(.page-ready) > body::after {
        content: "";
        position: fixed;
        left: 50%;
        top: 50%;
        width: 64px;
        height: 64px;
        margin-left: -32px;
        margin-top: -32px;
        border-radius: 50%;
        border: 8px solid #e9ecef;
        border-top-color: #8b1d21; /* color acorde al tema */
        animation: _sys_spin 1s linear infinite;
        z-index: 2001;
      }
      @keyframes _sys_spin { to { transform: rotate(360deg); } }

      /* Evita que se muestren transiciones o flashes en elementos hasta que la página esté lista */
      html:not(.page-ready) body * {
        transition: none !important;
      }
    </style>

    <!-- Estilo para botones de confirmación consistentes -->
    <style>
      .btn-confirm {
        background-color: #3085d6 !important; /* mismo color que usamos en confirm dialogs */
        border-color: #2774b8 !important;
        color: #fff !important;
      }
      .btn-confirm:hover, .btn-confirm:focus {
        background-color: #2774b8 !important;
        border-color: #1f5f98 !important;
        color: #fff !important;
      }
    </style>
    
  </head>
  <script>
    // Eliminador del loader (incluido en head para páginas que no cargan footer, p.ej. login)
    (function(){
      function setPageReady(){
        try{ document.documentElement.classList.add('page-ready'); }catch(e){}
      }
      if (document.readyState === 'complete'){
        setPageReady();
      } else {
        document.addEventListener('DOMContentLoaded', setPageReady);
        window.addEventListener('pageshow', setPageReady);
        window.addEventListener('load', setPageReady);
      }
      setTimeout(function(){ if (!document.documentElement.classList.contains('page-ready')) setPageReady(); }, 4000);
    })();
  </script>