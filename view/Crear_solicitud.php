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
              <h2><?php echo $datos["Nombre"]; ?></h2>
              <h3><?php echo $datos["Rol"]; ?></h3>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Información</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Editar Perfil</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Cambiar Contraseña</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">

 
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Nombre Completo</div>
                    <div class="col-lg-9 col-md-8"><?php  echo $datos["Nombre"]." ".$datos["Apellido"]; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Cédula</div>
                    <div class="col-lg-9 col-md-8"><?php  echo $datos["Cedula"]; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Departamento/Área</div>
                    <div class="col-lg-9 col-md-8">OFITIC</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Cargo</div>
                    <div class="col-lg-9 col-md-8"><?php  echo $datos["Cargo"]; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Condición Laboral</div>
                    <div class="col-lg-9 col-md-8"><?php  echo $datos["Condicion_l"]; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Rol</div>
                    <div class="col-lg-9 col-md-8"><?php  echo $datos["Rol"]; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Teléfono</div>
                    <div class="col-lg-9 col-md-8"><?php  echo $datos["Tlf"]; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Correo</div>
                    <div class="col-lg-9 col-md-8"><?php echo $datos["Correo"];?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Fecha de Nacimiento</div>
                    <div class="col-lg-9 col-md-8"><?php echo $datos["Fecha_nac"];?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Usuario</div>
                    <div class="col-lg-9 col-md-8"><?php echo $datos["Nombre_usuario"];?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Contraseña</div>
                    <div class="col-lg-9 col-md-8"><?php echo $datos["Contraseña"];?></div>
                  </div>

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
                        <input name="Name" type="text" class="form-control" id="Name" value="<?php echo $datos["Nombre"];?>" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="LastName" class="col-md-4 col-lg-3 col-form-label">Apellido</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="LastName" type="text" class="form-control" id="LastName" value="<?php echo $datos["Apellido"];?>" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="company" class="col-md-4 col-lg-3 col-form-label">Fecha de nacimineto</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="Fecha_nac" type="text" class="form-control" id="fecha_nac" value="<?php echo $datos["Fecha_nac"];?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="phone" type="text" class="form-control" id="Phone" value="(436) 486-3538 x29071">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="Email" value="<?php echo $datos["Correo"];?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Nombre Usuario</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="user" type="text" class="form-control" id="Usuario" value="<?php echo $datos["Nombre_usuario"];?>">
                      </div>
                    </div>


                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" name="cambiar">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-settings">

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form method="POST" action="" autocomplete="off">

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Contraseña Nueva</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password" class="form-control" id="newPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-ingrese nueva contraseña</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" name="passw" class="btn btn-primary">Change Password</button>
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
  <script src="js/solicitud.js"></script>
  <script src="js/solicitudes.js"></script>

</body>

</html>