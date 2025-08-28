$(document).ready(function() {

  document.querySelectorAll(".theme-card").forEach(card => {
    card.addEventListener("click", function() {
      // Quitar borde activo de todos
      document.querySelectorAll(".theme-card").forEach(c => c.classList.remove("border-primary","shadow-lg"));
      document.querySelectorAll(".theme-card").forEach(c => c.classList.add("border-light"));

      // Activar el clicado
      this.classList.add("border-primary","shadow-lg");

      // Guardar valor en el input hidden
      document.getElementById("cambiTema").value = this.dataset.tema;
    });
  });


  // Deshabilitar los botones al cargar la página
  $(".cambio").prop("disabled", true);
  
  // Validaciones para el campo "Nombre"
  $("#Nombre").on("keypress", function(e) {
    validarKeyPress(/^[a-zA-ZáéíóúüñÑçÇ -.\b]*$/, e);
  });

  $("#Nombre").on("keyup", function() {
    validarKeyUp(
      /^[a-zA-ZáéíóúüñÑçÇ -.]{3,30}$/,
      $(this),
      $("#snombre"),
      "El Nombre debe tener al menos 3 letras"
    );
    habilitarBotonRegistrar1();
  });

  // Validaciones para el campo "apellido"
  $("#apellido").on("keypress", function(e) {
    validarKeyPress(/^[a-zA-ZáéíóúüñÑçÇ -.\b]*$/, e);
  });

  $("#apellido").on("keyup", function() {
    validarKeyUp(
      /^[a-zA-ZáéíóúüñÑçÇ -.]{3,30}$/,
      $(this),
      $("#sapellido"),
      "El apellido debe tener al menos 3 letras"
    );
    habilitarBotonRegistrar1();
  });

  // Validaciones para el campo "telefono"
  $("#telefono").on("keypress", function(e) {
    validarKeyPress(/^[0-9-.\b]*$/, e);
  });

  $("#telefono").on("keyup", function() {
    validarKeyUp(
      /^[0-9]{3,4}[-]{1}[0-9]{7,15}$/,
      $(this),
      $("#stelefono"),
      "El teléfono debe cumplir con el formato 0400-1234567"
    );
    habilitarBotonRegistrar2();
  });

  // Validaciones para el campo de contraseña
  $("#newPassword").on("keyup", function() {
    validarKeyUp(
      /^[a-zA-Z0-9áéíóúüñÑçÇ -.\b]{3,30}$/,
      $(this),
      $("#scontraseña"),
      "La contraseña debe tener entre 3 y 30 caracteres"
    );
    validarCoincidenciaContraseñas();
    habilitarBotonRegistrar3();
  });

  $("#renewPassword").on("keyup", function() {
    validarKeyUp(
      /^[a-zA-Z0-9áéíóúüñÑçÇ -.\b]{3,30}$/,
      $(this),
      $("#scontraseña"),
      "La contraseña debe tener entre 3 y 30 caracteres"
    );
    validarCoincidenciaContraseñas();
    habilitarBotonRegistrar3();
  });

  // Validaciones para el campo "correo"
  $("#correo").on("keyup", function() {
    validarCorreo(
      /^[a-zA-ZáéíóúüñÑçÇ0-9._-]+@[a-zA-ZáéíóúüñÑçÇ0-9.-]+\.[a-zA-ZáéíóúüñÑçÇ]{2,6}$/,
      $(this),
      $("#scorreo"),
      "Correo electrónico inválido"
    );
    habilitarBotonRegistrar2();
  });

  // Manejo de la imagen de perfil
  $("#foto_perfil").on("change", function(e) {
    const file = e.target.files[0];
    if (file) {
      // Validar tipo de archivo
      const validTypes = ["image/jpeg", "image/png", "image/gif"];
      if (!validTypes.includes(file.type)) {
        alert("Por favor, sube una imagen válida (JPEG, PNG o GIF)");
        $(this).val("");
        return;
      }

      // Validar tamaño de archivo (ejemplo: 2MB máximo)
      if (file.size > 2 * 1024 * 1024) {
        alert("La imagen no debe exceder los 2MB");
        $(this).val("");
        return;
      }

      // Mostrar vista previa
      const reader = new FileReader();
      reader.onload = function(event) {
        $(".rounded-circle").attr("src", event.target.result);
        $("#nombre-archivo").text(file.name);
      };
      reader.readAsDataURL(file);
      
      // Habilitar botón de guardar cambios
      $(".cambio").prop("disabled", false);
    }
  });

  // Función para eliminar la imagen
  window.removeProfileImage = function() {
    if (confirm('¿Estás seguro de que quieres eliminar tu foto de perfil?')) {
      $(".rounded-circle").attr("src", "ruta/a/imagen/por/defecto.jpg");
      $("#foto_perfil").val("");
      $("#nombre-archivo").text("Foto será eliminada");
      // Agregar un campo hidden para indicar que se debe eliminar la foto
      $('<input>').attr({
        type: 'hidden',
        name: 'eliminar_foto',
        value: '1'
      }).appendTo('form');
      
      // Habilitar botón de guardar cambios
      $(".cambio").prop("disabled", false);
    }
  };

  // Manejador de eventos para el botón de guardar cambios
  $(".cambio").on("click", function(e) {
    if (!validarenvio()) {
      e.preventDefault();
    }
  });
});

function validarenvio() {
  if (validarKeyUp(
    /^[a-zA-ZáéíóúüñÑçÇ -.]{3,30}$/,
    $("#Nombre"),
    $("#snombre"),
    "El Nombre debe tener al menos 3 letras"
  ) == 0) {
    alert("Verifique el Nombre");
    return false;
  }

  if (validarKeyUp(
    /^[a-zA-ZáéíóúüñÑçÇ -.]{3,30}$/,
    $("#apellido"),
    $("#sapellido"),
    "El apellido debe tener al menos 3 letras"
  ) == 0) {
    alert("Verifique el apellido");
    return false;
  }

  return true;
}

function validarKeyPress(er, e) {
  key = e.keyCode;
  tecla = String.fromCharCode(key);
  a = er.test(tecla);
  if (!a) {
    e.preventDefault();
  }
}

function validarKeyUp(er, etiqueta, etiquetamensaje, mensaje) {
  a = er.test(etiqueta.val());
  if (a) {
    etiquetamensaje.text("");
    return 1;
  } else {
    etiquetamensaje.text(mensaje);
    return 0;
  }
}

function validarCorreo(er, etiqueta, etiquetamensaje, mensaje) {
  a = er.test(etiqueta.val());
  if (a) {
    etiquetamensaje.text("");
    return 1;
  } else {
    etiquetamensaje.text(mensaje);
    return 0;
  }
}

function validarCoincidenciaContraseñas() {
  const pass1 = $("#newPassword").val();
  const pass2 = $("#renewPassword").val();
  
  if (pass1 !== "" && pass2 !== "" && pass1 !== pass2) {
    $("#scontraseña").text("Las contraseñas no coinciden");
    return 0;
  } else if (pass1 === pass2 && pass1 !== "") {
    $("#scontraseña").text("");
    return 1;
  }
  return 0;
}

function habilitarBotonRegistrar1() {
  const nombreValido = validarKeyUp(
    /^[a-zA-ZáéíóúüñÑçÇ -.]{3,30}$/,
    $("#Nombre"),
    $("#snombre"),
    ""
  );
  
  const apellidoValido = validarKeyUp(
    /^[a-zA-ZáéíóúüñÑçÇ -.]{3,30}$/,
    $("#apellido"),
    $("#sapellido"),
    ""
  );
  
  $(".cambio").prop("disabled", !(nombreValido && apellidoValido));
}

function habilitarBotonRegistrar2() {
  const telefonoValido = validarKeyUp(
    /^[0-9]{3,4}[-]{1}[0-9]{7,15}$/,
    $("#telefono"),
    $("#stelefono"),
    ""
  );
  
  const correoValido = validarCorreo(
    /^[a-zA-ZáéíóúüñÑçÇ0-9._-]+@[a-zA-ZáéíóúüñÑçÇ0-9.-]+\.[a-zA-ZáéíóúüñÑçÇ]{2,6}$/,
    $("#correo"),
    $("#scorreo"),
    ""
  );
  
  $(".cambio").prop("disabled", !(telefonoValido && correoValido));
}

function habilitarBotonRegistrar3() {
  const pass1 = $("#newPassword").val();
  const pass2 = $("#renewPassword").val();
  
  $(".cc").prop("disabled", pass1 === "" || pass2 === "" || pass1 !== pass2);
}