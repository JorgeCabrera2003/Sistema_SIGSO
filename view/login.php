<?php require_once("Componentes/head.php") ?>

<body class="bg-light" style="position: relative;">
  <style>
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-image: url('assets/img/gobernacion.jpg');
      background-size: cover;
      background-position: center;
      filter: blur(5px);
      z-index: -1;
    }
  </style>
  <div class="container col-md-4 mb-4 d-flex justify-content-center align-items-center vh-100">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h1 class="card-title mb-0">Iniciar Sesion</h1>
        <img style="width: 30%;" class="img-logo" src="assets/img/OFITIC.png">
      </div>
      <div class="card-body">

            <?php if (isset($error_login) && $error_login): ?>

              <div class="alert alert-danger" role="alert" style="margin-bottom: 10px;">
                La cédula o la contraseña son incorrectas.
              </div>

            <?php endif; ?>

        <form method="post" class="row g-3 needs-validation">

          <div class="col-12">
            <label for="cedula" class="form-label">Cedula</label>
            <div class="input-group" id="cedula">
              <select class="form-select-sm input-group-text" id="particle" name="particle" >
                <option selected value="V-">V-</option>
                <option value="E-">E-</option>
              </select>
              <input type="number" class="form-control" aria-describedby="basic-addon3" name="CI" min="0">
            </div>
          </div>

          <div class="col-12">
            <label for="yourPassword" class="form-label">Contraseña</label>
            <div class="input-group">
              <input type="password" name="password" class="form-control" id="yourPassword" maxlength="20">
              <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                <i class="fa fa-eye"></i>
              </button>
            </div>
            <div class="invalid-feedback">Por favor ingresa tu contraseña!</div>
          </div>
          <div class="col-12">
            
          </div>
          <div class="col-12">
            
            <div class="col-12">
              <div class="d-flex justify-content-center mb-3">
                <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_sitekey; ?>"></div>
              </div>
            </div>

            <button class="btn btn-primary w-100" type="submit">
              Iniciar <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
            <a href="?page=recuperar" class="btn btn-link w-100 mt-2">¿Olvidaste tu Contraseña?</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script>
    var loginError = <?php echo isset($error_login) && $error_login ? 'true' : 'false'; ?>;
    
    // Función para mostrar/ocultar contraseña
    $(document).ready(function() {
      $("#togglePassword").click(function() {
        const passwordInput = $("#yourPassword");
        const icon = $(this).find("i");
        
        if (passwordInput.attr("type") === "password") {
          passwordInput.attr("type", "text");
          icon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
          passwordInput.attr("type", "password");
          icon.removeClass("fa-eye-slash").addClass("fa-eye");
        }
      });

      $("form").on("submit", function (e) {
        
        var cedula = $("input[name='CI']").val().trim();
        var clave = $("input[name='password']").val().trim();

        
        if (cedula === "" || clave === "") {

            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Completa el formulario",
                text: "Debes ingresar su cédula y/o contraseña.",
                confirmButtonText: "Aceptar"
            });

            return false;
        }

        if (typeof grecaptcha !== "undefined" && grecaptcha.getResponse() === "") {

            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Completa el reCAPTCHA",
                text: "Verifica que No eres un Robot.",
                confirmButtonText: "Aceptar"
            });
            return false;
        }

      });
    });
  </script>

  <script src="assets/js/login.js"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>