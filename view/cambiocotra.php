
<?php require_once("Componentes/head.php");?>

<body>
<?php require_once("Componentes/menu.php");?>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Perfil</h1>
      <nav>
        
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="img/profile-img.jpg" alt="Profile" class="rounded-circle">
              <h2><?php echo $datos["nombre"]; ?></h2>
              <h3><?php echo $datos["unidad"]; ?></h3>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link <?php echo $active1; ?>" data-bs-toggle="tab" data-bs-target="#profile-overview">Información</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Editar Perfil</button>
                </li>

                <li class="nav-item <?php echo $active3; ?>">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Cambiar Contraseña</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade <?php echo $active2; ?> profile-overview" id="profile-overview">

 
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Nombre Completo</div>
                    <div class="col-lg-9 col-md-8"><?php  echo $datos["nombre"]." ".$datos["apellido"]; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Cédula</div>
                    <div class="col-lg-9 col-md-8"><?php  echo $datos["cedula"]; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Unidad</div>
                    <div class="col-lg-9 col-md-8"><?php  echo $datos["unidad"]; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Dependencia</div>
                    <div class="col-lg-9 col-md-8"><?php  echo $datos["dependencia"]; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Correo electronico</div>
                    <div class="col-lg-9 col-md-8"><?php  echo $datos["correo"]; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col- md-4 label ">Telefono</div>
                    <div class="col-lg-9 col-md-8"><?php  echo $datos["telefono"]; ?></div>
                  </div>

                  <?php if (isset($datos["especialidad"])) {
                      echo('<div class="row">
                              <div class="col-lg-3 col-md-4 label">Especialidad</div>
                              <div class="col-lg-9 col-md-8">'.$datos["especialidad"].'</div>
                            </div>');
                  } ?>

                  

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form method="POST" action="?page=users-profile" autocomplete="off">
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Foto de Perfil</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="img/profile-img.jpg" alt="Profile">
                        <div class="pt-2">
                          <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                          <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Name" class="col-md-4 col-lg-3 col-form-label">Nombre</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="Nombre" type="text" class="form-control" id="Nombre" value="<?php echo $datos["nombre"];?>" required>
                        <span id="snombre"></span>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="apellido" class="col-md-4 col-lg-3 col-form-label">Apellido</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="apellido" type="text" class="form-control" id="apellido" value="<?php echo $datos["apellido"];?>" required>
                        <span id="sapellido"></span>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="telefono" class="col-md-4 col-lg-3 col-form-label">Telefono</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="telefono" type="text" class="form-control" id="telefono" value="<?php echo $datos["telefono"];?>">
                        <span id="stelefono"></span>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="correo" class="col-md-4 col-lg-3 col-form-label">Correo</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="correo" type="correo" class="form-control" id="correo" value="<?php echo $datos["correo"];?>">
                        <span id="scorreo"></span>
                      </div>
                    </div>


                    <div class="text-center">
                      <button type="submit" class="btn btn-primary cambio" name="cambiar">Guardar cambios</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-settingss">

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form method="POST" action="" autocomplete="off">

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Contraseña Nueva</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password" class="form-control" id="newPassword" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-ingrese nueva contraseña</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewpassword" type="password" class="form-control" id="renewPassword" required>
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" name="passw" class="btn btn-primary">Cambiar contraseña</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php require_once "Componentes/footer.php"; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="js/perfil.js"></script>

</body>

</html