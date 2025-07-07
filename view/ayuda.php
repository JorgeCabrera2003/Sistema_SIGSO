<?php require_once("Componentes/head.php"); ?>
<?php require_once("Componentes/modal_ayuda.php"); ?>
<body>
  <?php require_once("Componentes/menu.php"); ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Módulo de Ayuda</h1>
    </div>
      
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">

              <div style="position:relative;">
                <input type="text" id="buscador" class="form-control mb-2 mt-2" placeholder="Buscar...">
                <div id="resultado-busqueda"></div>
              </div>
              <hr>

              <h3 class="mb-4">Temas Populares</h3>
              <div class="row g-3">

                <div class="col-md-4 col-lg-2">
                  <div class="card h-100 shadow-sm text-center ayuda-card" data-tema="usuarios" onclick="mostrarAyuda(this)">
                    <div class="card-body text-center">
                      <i class="fa-solid fa-users-gear fa-2x mb-2"></i>
                      <h6 class="card-title mt-2">Usuarios</h6>
                      <p class="card-text small">Gestión del Usuario</p>
                    </div>
                  </div>
                </div>

                <div class="col-md-4 col-lg-2">
                  <div class="card h-100 shadow-sm text-center ayuda-card" data-tema="solicitudes" onclick="mostrarAyuda(this)">
                    <div class="card-body text-center">
                      <i class="fa-solid fa-file-lines fa-2x mb-2"></i>
                      <h6 class="card-title mt-2">Solicitudes</h6>
                      <p class="card-text small">Gestión de Solicitudes</p>
                    </div>
                  </div>
                </div>

                <div class="col-md-4 col-lg-2">
                  <div class="card h-100 shadow-sm text-center ayuda-card" data-tema="seguridad" onclick="mostrarAyuda(this)">
                    <div class="card-body">
                      <i class="fa-solid fa-shield-alt fa-2x mb-2"></i>
                      <h6 class="card-title mt-2">Seguridad del Sistema</h6>
                      <p class="card-text small">Funciones de Seguridad y Mantenimiento</p>
                    </div>
                  </div>
                </div>

                <div class="col-md-4 col-lg-2">
                  <div class="card h-100 shadow-sm text-center ayuda-card" data-tema="inventario" onclick="mostrarAyuda(this)">
                    <div class="card-body">
                      <i class="fa-solid fa-boxes-stacked fa-2x mb-2"></i>
                      <h6 class="card-title mt-2">Inventario</h6>
                      <p class="card-text small">Gestión de bienes, equipos y materiales</p>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-4 col-lg-2">
                  <div class="card h-100 shadow-sm text-center ayuda-card" data-tema="personal" onclick="mostrarAyuda(this)">
                    <div class="card-body">
                      <i class="fa-solid fa-building-user fa-2x mb-2"></i>
                      <h6 class="card-title mt-2">Personal e Infraestructura</h6>
                      <p class="card-text small">Gestión de empleados y Estructura Física</p>
                    </div>
                  </div>
                </div>

                <div class="col-md-4 col-lg-2">
                  <div class="card h-100 shadow-sm text-center ayuda-card" data-tema="redes" onclick="mostrarAyuda(this)">
                    <div class="card-body">
                      <i class="fa-solid fa-network-wired fa-2x mb-2"></i>
                      <h6 class="card-title mt-2">Redes</h6>
                      <p class="card-text small">Gestión de Red</p>
                    </div>
                  </div>
                </div>

              </div>
              <hr>
              <h3 class="mb-4">Preguntas Frecuentes</h3>
 <div class="accordion mb-4" id="faqAccordion">

                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingGeneral1">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general1" aria-expanded="false" aria-controls="general1">
                      ¿Cómo puedo crear una nueva solicitud?
                    </button>
                  </h2>
                  <div id="general1" class="accordion-collapse collapse" aria-labelledby="headingGeneral1" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      Para crear una nueva solicitud, dirígete al módulo de solicitudes y haz clic en "Nueva solicitud". Completa el formulario y guarda los cambios.
                    </div>
                  </div>
                </div>

                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingGeneral2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general2" aria-expanded="false" aria-controls="general2">
                      ¿Cómo puedo ver el estado de mi solicitud?
                    </button>
                  </h2>
                  <div id="general2" class="accordion-collapse collapse" aria-labelledby="headingGeneral2" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      Ingresa al módulo de solicitudes y revisa la columna "Estado" para ver el progreso de cada una.
                    </div>
                  </div>
                </div>

                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingGeneral3">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general3" aria-expanded="false" aria-controls="general3">
                      ¿Cómo puedo cancelar una solicitud?
                    </button>
                  </h2>
                  <div id="general3" class="accordion-collapse collapse" aria-labelledby="headingGeneral3" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      Selecciona la solicitud que deseas cancelar y haz clic en el botón "Cancelar". Confirma la acción en el cuadro de diálogo.
                    </div>
                  </div>
                </div>

                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingSolicitudes1">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#solicitudes1" aria-expanded="false" aria-controls="solicitudes1">
                      ¿Qué tipos de solicitudes puedo crear?
                    </button>
                  </h2>
                  <div id="solicitudes1" class="accordion-collapse collapse" aria-labelledby="headingSolicitudes1" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      Puedes crear solicitudes de soporte técnico, mantenimiento, acceso a sistemas y más, según los permisos de tu usuario.
                    </div>
                  </div>
                </div>

                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingUsuarios1">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#usuarios1" aria-expanded="false" aria-controls="usuarios1">
                      ¿Cómo puedo agregar un nuevo usuario?
                    </button>
                  </h2>
                  <div id="usuarios1" class="accordion-collapse collapse" aria-labelledby="headingUsuarios1" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      Ve al módulo de usuarios y haz clic en "Agregar usuario". Completa los datos requeridos y guarda.
                    </div>
                  </div>
                </div>

                <div class="accordion-item">
                  <h2 class="accordion-header" id="faqUsuario1">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsuario1" aria-expanded="false" aria-controls="collapseUsuario1">
                      ¿Cómo puedo editar mi información personal?
                    </button>
                  </h2>
                  <div id="collapseUsuario1" class="accordion-collapse collapse" aria-labelledby="faqUsuario1" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      Ve a tu perfil (avatar → Mi Perfil), selecciona "Editar Perfil" y modifica los campos nombre, apellido, correo y teléfono. Luego haz clic en "Guardar cambios".
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="faqUsuario2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsuario2" aria-expanded="false" aria-controls="collapseUsuario2">
                      ¿Por qué no puedo crear usuarios?
                    </button>
                  </h2>
                  <div id="collapseUsuario2" class="accordion-collapse collapse" aria-labelledby="faqUsuario2" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      La creación de usuarios requiere permisos específicos. Solo usuarios con rol de administrador o con permisos de "registrar" en el módulo de usuarios pueden crear nuevas cuentas.
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="faqUsuario3">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsuario3" aria-expanded="false" aria-controls="collapseUsuario3">
                      ¿Qué hago si mi contraseña no cumple los requisitos?
                    </button>
                  </h2>
                  <div id="collapseUsuario3" class="accordion-collapse collapse" aria-labelledby="faqUsuario3" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      Las contraseñas deben tener entre 8-45 caracteres e incluir letras, números y símbolos especiales. Evita usar solo tu nombre o cédula.
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="faqUsuario4">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsuario4" aria-expanded="false" aria-controls="collapseUsuario4">
                      ¿Puedo usar cualquier formato de imagen para mi perfil?
                    </button>
                  </h2>
                  <div id="collapseUsuario4" class="accordion-collapse collapse" aria-labelledby="faqUsuario4" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      El sistema acepta JPG, JPEG, PNG y GIF. La imagen se renombra automáticamente con tu cédula para mantener la organización.
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="faqUsuario5">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsuario5" aria-expanded="false" aria-controls="collapseUsuario5">
                      ¿Qué pasa si olvido mi contraseña?
                    </button>
                  </h2>
                  <div id="collapseUsuario5" class="accordion-collapse collapse" aria-labelledby="faqUsuario5" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      Debes contactar al administrador del sistema quien puede restablecer tu contraseña. No existe recuperación automática por email.
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="faqUsuario6">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsuario6" aria-expanded="false" aria-controls="collapseUsuario6">
                      ¿Por qué me obliga a cambiar la contraseña al iniciar sesión?
                    </button>
                  </h2>
                  <div id="collapseUsuario6" class="accordion-collapse collapse" aria-labelledby="faqUsuario6" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      Esto ocurre cuando tu contraseña es igual a tu cédula (contraseña por defecto). Es una medida de seguridad obligatoria.
                    </div>
                  </div>
                </div>

              </div>

            </div>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <?php require_once "Componentes/footer.php"; ?>
 <style>
    .ayuda-card {
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      transition: box-shadow 0.2s, border-color 0.2s;
      cursor: pointer;
      min-height: 180px;
    }
    .ayuda-card:hover {
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
      border-color: #0d6efd;
    }
    .ayuda-card i {
      color: #0d6efd;
    }
    .ayuda-card .card-title {
      font-weight: 600;
    }
    .ayuda-card .card-text {
      color: #6c757d;
    }

    #resultado-busqueda {
      position: absolute;
      z-index: 1000;
      width: 100%;
      max-width: 400px;
      background: #fff;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.10);
      margin-top: 2px;
      padding: 0;
      font-size: 1rem;
    }
    #resultado-busqueda ul {
      margin: 0;
      padding: 0;
    }
    #resultado-busqueda li {
      list-style: none;
      padding: 10px 16px;
      cursor: pointer;
      transition: background 0.15s;
    }
    #resultado-busqueda li:hover, #resultado-busqueda li.active {
      background: #f1f3f9;
    }
    #resultado-busqueda a {
      color: #222;
      text-decoration: none;
      display: block;
      width: 100%;
    }
  </style>
  <script src="assets/js/ayuda.js"></script>
</body>
</html>