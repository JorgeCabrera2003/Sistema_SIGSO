<?php require_once("Componentes/head.php"); ?>

<body>

  <?php require_once("Componentes/menu.php"); ?>

  <main id="main" class="main mb-5">
    <div class="pagetitle">
      <h1>Perfil</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="">Home</a></li>
          <li class="breadcrumb-item active">Perfil</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="<?php echo $foto; ?>" alt="Profile" class="rounded-circle"
                style="width: 200px; height: 200px; object-fit: cover;">
              <h2><?php echo $datos["nombres"]; ?></h2>
              <h3><?php // echo $datos["unidad"]; 
                  ?></h3>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">
                <li class="nav-item">
                  <button class="nav-link <?php echo $active1; ?>" data-bs-toggle="tab"
                    data-bs-target="#profile-overview">Información</button>
                </li>
                <li class="nav-item hidden">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Editar Perfil</button>
                </li>
                <li class="nav-item <?php echo $active3; ?>">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Cambiar
                    Contraseña</button>
                </li>
                <li class="nav-item <?php echo $active4; ?>">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tema">Temas</button>
                </li>
                <!--<li class="nav-item <?php echo $active3; ?>">
                  <button class="nav-link" data-bs-toggle="tab"
                    data-bs-target="#profile-notification">Notificaciones</button>
                </li> -->
              </ul>
              <div class="tab-content pt-2">
                <div class="tab-pane fade <?php echo $active2; ?> profile-overview" id="profile-overview">
                  <h5 class="card-title">Detalles</h5>
                  <div class="row mt-3">
                    <div class="col-lg-3 col-md-4 label">Nombre Completo</div>
                    <div class="col-lg-9 col-md-8 card-text"><?php echo $datos["nombres"] . " " . $datos["apellidos"]; ?></div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-lg-3 col-md-4 label">Cédula</div>
                    <div class="col-lg-9 col-md-8 card-text"><?php echo $datos["cedula"]; ?></div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-lg-3 col-md-4 label">Correo electronico</div>
                    <div class="col-lg-9 col-md-8 card-text"><?php echo $datos["correo"]; ?></div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-lg-3 col-md-4 label">Telefono</div>
                    <div class="col-lg-9 col-md-8 card-text"><?php echo $datos["telefono"]; ?></div>
                  </div>
                  <?php if (isset($datos["especialidad"])) {
                    echo ('<div class="row">
                              <div class="col-lg-3 col-md-4 label">Especialidad</div>
                              <div class="col-lg-9 col-md-8 card-text">' . $datos["especialidad"] . '</div>
                            </div>');
                  } ?>
                </div>
                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                  <!-- Profile Edit Form -->
                  <form method="POST" action="?page=users-profile" autocomplete="off" enctype="multipart/form-data">
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Foto de Perfil</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="<?php echo $foto; ?>" alt="Profile"
                          style="width: 100px; height: 100px; object-fit: cover;">
                        <div class="pt-2">
                          <input type="file" name="foto_perfil" id="foto_perfil" class="d-none" accept="image/*">
                          <button type="button" class="btn btn-primary btn-sm" title="Subir imagen"
                            onclick="document.getElementById('foto_perfil').click()">
                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                          </button>
                          <button type="submit" name="eliminarF" class="btn btn-danger btn-sm"
                            title="Remover mi imagen de perfil" onclick="removeProfileImage()">
                            <i class="fa-solid fa-trash-arrow-up"></i>
                          </button>
                          <div class="mt-2" id="nombre-archivo"></div>
                        </div>
                      </div>
                    </div>

                    <!-- Resto de los campos del formulario -->
                    <div class="row mb-3">
                      <label for="Nombre" class="col-md-4 col-lg-3 col-form-label">Nombre</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="Nombre" type="text" class="form-control" id="Nombre"
                          value="<?php echo $datos["nombres"]; ?>" required maxlength="50">
                        <span id="snombre"></span>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="apellido" class="col-md-4 col-lg-3 col-form-label">Apellido</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="Apellido" type="text" class="form-control" id="Apellido"
                          value="<?php echo $datos["apellidos"]; ?>" required maxlength="50">
                        <span id="sapellido"></span>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="correo" class="col-md-4 col-lg-3 col-form-label">Correo</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="Correo" type="text" class="form-control" id="Correo"
                          value="<?php echo $datos["correo"]; ?>" required maxlength="50">
                        <span id="scorreo"></span>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="telefono" class="col-md-4 col-lg-3 col-form-label">Telefono</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="Telefono" type="text" class="form-control" id="Telefono"
                          value="<?php echo $datos["telefono"]; ?>" required maxlength="50">
                        <span id="stelefono"></span>
                      </div>
                    </div>

                    <!-- ... resto de tus campos existentes ... -->

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary cambio" name="cambiar">Guardar cambios</button>
                    </div>
                  </form><!-- End Profile Edit Form -->
                </div>

                <div class="tab-pane fade pt-3" id="profile-settingss">

                </div>

                <div class="tab-pane fade <?php echo $active4; ?> pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form method="POST" action="" autocomplete="off">

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Contraseña Nueva</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password" class="form-control" id="newPassword" required
                          maxlength="20">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-ingrese nueva
                        contraseña</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewpassword" type="password" class="form-control" id="renewPassword" required
                          maxlength="20">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" name="passw" class="btn btn-primary cc" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                        data-bs-title="Confirmar y cambiar la contraseña" id="">Cambiar contraseña</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

                <div class="tab-pane fade <?php echo $active4; ?> pt-3" id="tema">
                  <!-- Change themes Form -->
                  <form method="POST" action="" autocomplete="off">
                    <div class="row mb-3">
                      <label for="cambiTema" class="col-md-4 col-lg-3 col-form-label">Selecciona un tema</label>
                      <div class="col-md-8 col-lg-9">
                        <select name="cambiTema" id="cambiTema" class="form-select">
                          <option value="0" <?php echo ($datos['tema'] == 0) ? 'selected' : ''; ?>>Tema por defecto</option>
                          <option value="1" <?php echo ($datos['tema'] == 1) ? 'selected' : ''; ?>>Scarly</option>
                          <option value="2" <?php echo ($datos['tema'] == 2) ? 'selected' : ''; ?>>Azul</option>
                          <option value="3" <?php echo ($datos['tema'] == 3) ? 'selected' : ''; ?>>Verde</option>
                          <option value="4" <?php echo ($datos['tema'] == 4) ? 'selected' : ''; ?>>Rojo</option>
                          <option value="5" <?php echo ($datos['tema'] == 5) ? 'selected' : ''; ?>>Morado</option>
                        </select>
                      </div>
                    </div>
                    <div class="text-center">
                      <button type="submit" name="cambiarTema" class="btn btn-primary">Aplicar Tema</button>
                    </div>
                  </form>
                </div>



                <!-- <div class="tab-pane fade <?php echo $active4; ?> pt-3" id="profile-notification">
                  <section class="section">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="card text-center">
                          <div class="card-header">
                            Módulo
                          </div>
                          <div class="card-body">
                            <h5 class="card-title">Título</h5>
                            <p class="card-text">Mensaje
                            </p>
                            <a href="#" class="btn btn-primary">Ver Evento</a>
                          </div>
                          <div class="card-footer text-body-secondary">
                            Tiempo
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="card text-center">
                          <div class="card-header">
                            Módulo
                          </div>
                          <div class="card-body">
                            <h5 class="card-title">Título</h5>
                            <p class="card-text">Mensaje
                            </p>
                            <a href="#" class="btn btn-primary">Ver Evento</a>
                          </div>
                          <div class="card-footer text-body-secondary">
                            Tiempo
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="card text-center">
                          <div class="card-header">
                            Módulo
                          </div>
                          <div class="card-body">
                            <h5 class="card-title">Título</h5>
                            <p class="card-text">Mensaje
                            </p>
                            <a href="#" class="btn btn-primary">Ver Evento</a>
                          </div>
                          <div class="card-footer text-body-secondary">
                            Tiempo
                          </div>
                        </div>
                      </div>
                    </div>
                  </section> -->

              </div>

            </div><!-- End Bordered Tabs -->

          </div>
        </div>

      </div>
      </div>
    </section>

  </main><!-- End #main -->
  <br><br>

  <!-- ======= Footer ======= -->
  <?php require_once "Componentes/footer.php"; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>
  <script src="assets/js/perfil.js"></script>
  <!-- Script para SweetAlert y manejo de formularios -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Mostrar alertas de PHP
    <?php if (isset($alert)): ?>
      Swal.fire({
        icon: '<?php echo $alert['type']; ?>',
        title: '<?php echo $alert['title']; ?>',
        text: '<?php echo $alert['message']; ?>',
        confirmButtonText: 'Aceptar'
      });
    <?php endif; ?>

    // Confirmación para eliminar foto de perfil
    function confirmDeletePhoto() {
      Swal.fire({
        title: '¿Eliminar foto de perfil?',
        text: "¿Estás seguro de que deseas eliminar tu foto de perfil?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          // Crear un formulario dinámico para enviar la petición de eliminación
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = '?page=users-profile';

          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'eliminarF';
          input.value = '1';

          form.appendChild(input);
          document.body.appendChild(form);
          form.submit();
        }
      });
    }

    // Confirmación para cambiar contraseña
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const newPassword = document.getElementById('newPassword').value;
      const renewPassword = document.getElementById('renewPassword').value;

      if (newPassword !== renewPassword) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Las contraseñas no coinciden',
          confirmButtonText: 'Aceptar'
        });
        return;
      }

      Swal.fire({
        title: '¿Cambiar contraseña?',
        text: "¿Estás seguro de que deseas cambiar tu contraseña?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });

    // Confirmación para guardar cambios en el perfil
    document.getElementById('profileForm').addEventListener('submit', function(e) {
      e.preventDefault();

      Swal.fire({
        title: '¿Guardar cambios?',
        text: "¿Estás seguro de que deseas guardar los cambios en tu perfil?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });

    // Mostrar nombre del archivo seleccionado
    document.getElementById('foto_perfil').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        document.getElementById('nombre-archivo').textContent = file.name;
      }
    });
  </script>

</body>

</html>