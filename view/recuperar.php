<?php
require_once("Componentes/head.php") ?>

<body>
  <div class="container col-md-4 mb-4 d-flex justify-content-center align-items-center vh-100">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="card-title mb-0">Recuperar Contraseña</h1>
        <img style="width: 30%;" class="img-logo" src="assets/img/OFITIC.png">
      </div>
      <div class="card-body">
          <div class="col-12" id="perfil-preview" style="display:none;">
          </div>
        <form method="post" class="row g-3 needs-validation" id="recuperar-form">
          <div class="col-12" id="form-cedula">
            <label for="cedula" class="form-label">Cédula</label>
            <div class="input-group" id="cedula">
              <select class="form-select-sm input-group-text" id="particle" name="particle" >
                <option selected value="V-">V-</option>
                <option value="E-">E-</option>
              </select>
              <input type="number" class="form-control" aria-describedby="basic-addon3" name="CI" min="0" placeholder="Ingrese su cédula" >
              <button class="btn btn-primary" type="submit" id="btn-buscar">
                Buscar <i class="fa-solid fa-search"></i>
              </button>
            </div>
          </div>

            <!-- Formulario de Código de Recuperación -->
              <div class="col-12" id="form-codigo-recuperacion" style="display:none;">
                <label for="codigo_recuperacion" class="form-label">Código de recuperación</label>
                <div class="input-group" id="codigo_recuperacion_group">
                  <input type="text" class="form-control" name="codigo_recuperacion" id="codigo_recuperacion" maxlength="6" placeholder="Ingrese el código ">

                  <span id="scodigo_bien"></span>
                  
                  <button class="btn btn-primary" type="button" id="btn-enviar-codigo">
                    Solicitar código <i class="fa-solid fa-paper-plane"></i>
                  </button>

                  <div class="text-start col-12 d-flex justify-content-between align-items-center mt-2">
                  
                  <button class="btn btn-secondary mt-2" type="button" id="btn-volver-cedula" style="width:auto;">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>

                <button class="btn btn-light mt-2" type="button" id="btn-continuar" disabled>
                    Continuar <i class="fa-solid fa-arrow-right"></i>
                </button>


                </div>

                  
              </div>
              </div>

              <!-- Formulario de Nueva Clave -->
              <div class="col-12" id="form-nueva-clave" style="display:none;">
                <label for="nueva_clave" class="form-label">Nueva clave</label>
                <div class="input-group mb-2">
                  <input type="password" class="form-control" name="nueva_clave" id="nueva_clave" maxlength="20" placeholder="Ingrese su nueva clave">
                </div>
                <label for="confirmar_clave" class="form-label">Confirmar clave</label>
                <div class="input-group mb-2">
                  <input type="password" class="form-control" name="confirmar_clave" id="confirmar_clave" maxlength="20" placeholder="Confirme su nueva clave">
                </div>
                <div class="text-start col-12 d-flex justify-content-between align-items-center mt-2">
                  <button class="btn btn-secondary mt-2" type="button" id="btn-volver-cedula2" style="width:auto;">
                    <i class="fa-solid fa-arrow-left"></i>
                  </button>
                  <button class="btn btn-success mt-2" type="button" id="btn-guardar-clave">
                    Guardar <i class="fa-solid fa-floppy-disk"></i>
                  </button>
                </div>
              </div>
                

          
          <div class="col-12">
            <a href="?page=login" class="btn btn-link w-100 mt-2">Iniciar Sesión</a>
          </div>

        </form>
      </div>
    </div>
  </div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="assets/js/recuperar.js"></script>
</body>
</html>