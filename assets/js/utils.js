const idiomaTabla = 'assets/js/datatable-plugin-es.js';

// Expresiones Regulares
console.log("Cargando Expresiones Regulares");
const patrones = {
  letras: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
  letrasConNumeros: /^[0-9 a-zA-ZÀ-ÿ\s]{1,100}$/,
  numeros: /^\d{1,20}$/,
  email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
  telefono: /^\(\d{3}\)\s\d{3}-\d{4}$/,
  telefonoSimple: /^\d{4}-\d{7,8}$/,
  username: /^[a-zA-Z0-9_]{4,20}$/,
  password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
  postalCode: /^\d{5}$/,
  direccion: /^[0-9 a-zA-ZÀ-ÿ\s-./#]{10,100}$/,
  nombreEnte: /^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,90}$/,
  responsable: /^[a-zA-ZÀ-ÿ\s-.]{4,65}$/
};

// Sistema de Validación Reutilizable
const SistemaValidacion = {
  // Inicializar validación para un formulario
  inicializar: function (elements, callbackCambioEstado = null) {
    this.elementos = elements;
    this.callbackCambioEstado = callbackCambioEstado;

    $.each(elements, function (key, element) {
      if (element.length) {
        element.on('blur', SistemaValidacion.validarCampo);
        element.on('input', SistemaValidacion.validarCampo);

        // Aplicar autocapitalización a campos de texto
        if (key === 'nombre' || key === 'responsable') {
          element.on('blur', function () {
            SistemaValidacion.autoCapitalizar($(this));
          });
        }
      }
    });
  },

  // Validar campo individual
  validarCampo: function () {
    const $campo = $(this);
    const valor = $campo.val().trim();
    const id = this.id;
    let esValido = true;
    let mensajeError = '';

    // Asignar patrones y mensajes según el campo
    switch (id) {
      case 'nombre':
        esValido = patrones.nombreEnte.test(valor);
        mensajeError = 'El nombre del ente debe tener de 4 a 90 carácteres';
        break;

      case 'responsable':
        esValido = patrones.responsable.test(valor);
        mensajeError = 'El nombre del responsable debe tener de 4 a 65 carácteres';
        break;

      case 'telefono':
        esValido = patrones.telefonoSimple.test(valor);
        mensajeError = 'El número debe tener el siguiente formato: ****-*******';
        break;

      case 'direccion':
        esValido = patrones.direccion.test(valor);
        mensajeError = 'La dirección del Ente debe tener de 10 a 100 carácteres';
        break;

      case 'tipo_ente':
        esValido = valor !== "default" && valor !== "";
        mensajeError = 'Debe seleccionar un tipo de ente';
        break;

      case 'id_ente':
        esValido = patrones.letrasConNumeros.test(valor);
        mensajeError = 'El ID debe contener solo letras y números';
        break;

      case 'nombre_cargo':
        esValido = patrones.letras.test(valor) && valor.length >= 3 && valor.length <= 45;
        mensajeError = 'El nombre del cargo debe tener de 3 a 45 caracteres (solo letras)';
        break;

      case 'id_cargo':
        esValido = patrones.letrasConNumeros.test(valor);
        mensajeError = 'El ID del cargo debe ser numérico';
        break;

      case 'nombre':
        esValido = patrones.letrasConNumeros.test(valor) && valor.length >= 4 && valor.length <= 45;
        mensajeError = 'El nombre de la unidad debe tener de 4 a 45 caracteres';
        break;

      case 'id_dependencia':
        esValido = valor !== "default" && valor !== "";
        mensajeError = 'Debe seleccionar una dependencia';
        break;

      case 'id_unidad':
        esValido = patrones.letrasConNumeros.test(valor);
        mensajeError = 'El ID de la unidad debe ser numérico';
        break;

      default:
        // Validación genérica para campos de texto
        if ($campo.attr('type') === 'text' || $campo.is('input')) {
          esValido = valor.length >= 1;
          mensajeError = 'Este campo es requerido';
        }
    }

    // Aplicar estilos de validación
    SistemaValidacion.aplicarEstilos($campo, esValido, mensajeError);

    // Verificar estado general del formulario después de cada validación
    if (SistemaValidacion.callbackCambioEstado) {
      const formularioValido = SistemaValidacion.verificarEstadoFormulario();
      SistemaValidacion.callbackCambioEstado(formularioValido);
    }

    return esValido;
  },

  // Verificar estado general del formulario
  verificarEstadoFormulario: function () {
    let esValido = true;

    $.each(this.elementos, function (key, elemento) {
      if (elemento.length && elemento.attr('id') !== 'id_ente') {
        if (elemento.hasClass('is-invalid') || !elemento.hasClass('is-valid')) {
          esValido = false;
        }
      }
    });

    return esValido;
  },

  // Aplicar autocapitalización
  autoCapitalizar: function ($elemento) {
  const valor = $elemento.val().trim();
  if (valor) {
    const capitalizado = capitalizarTexto(valor);
    $elemento.val(capitalizado);
    setTimeout(() => SistemaValidacion.validarCampo.call($elemento[0]), 100);
  }
},

  // Aplicar estilos de validación
  aplicarEstilos: function ($elemento, esValido, mensajeError) {
    const id = $elemento.attr('id');
    const $feedback = $(`#s${id}`);

    if (esValido) {
      $elemento.removeClass("is-invalid").addClass("is-valid");
      if ($feedback.length) {
        $feedback.removeClass("invalid-feedback").addClass("valid-feedback").text("");
      }
    } else {
      $elemento.removeClass("is-valid").addClass("is-invalid");
      if ($feedback.length) {
        $feedback.removeClass("valid-feedback").addClass("invalid-feedback").text(mensajeError);
      }
    }
  },

  // Validar formulario completo
  validarFormulario: function (elementos) {
    let esValido = true;

    $.each(elementos, function (key, elemento) {
      if (elemento.length) {
        // Forzar validación de cada campo
        elemento.trigger('blur');
        if (elemento.hasClass('is-invalid') || !SistemaValidacion.validarCampo.call(elemento[0])) {
          esValido = false;
        }
      }
    });

    return esValido;
  },

  // Limpiar validación de formulario
  limpiarValidacion: function (elementos) {
    $.each(elementos, function (key, elemento) {
      if (elemento.length) {
        elemento.removeClass("is-valid is-invalid");
        const id = elemento.attr('id');
        const $feedback = $(`#s${id}`);
        if ($feedback.length) {
          $feedback.removeClass("invalid-feedback valid-feedback").text("");
        }
      }
    });
  }
};

// Funciones de utilidad existentes
function validarKeyPress(er, e) {
  const key = e.keyCode;
  const tecla = String.fromCharCode(key);
  const a = er.test(tecla);
  if (!a) {
    e.preventDefault();
  }
}

function validarKeyUp(er, etiqueta, etiquetamensaje, mensaje) {
  const a = er.test(etiqueta.val());
  if (a) {
    $(etiqueta).removeClass("is-invalid");
    $(etiqueta).addClass("is-valid")
    $(etiquetamensaje).removeClass("invalid-feedback");
    $(etiquetamensaje).addClass("valid-feedback")
    etiquetamensaje.text("");
    return 1;
  } else {
    $(etiqueta).removeClass("is-valid");
    $(etiqueta).addClass("is-invalid")
    $(etiquetamensaje).removeClass("valid-feedback");
    $(etiquetamensaje).addClass("invalid-feedback")
    etiquetamensaje.text(mensaje);
    return 0;
  }
}

function estadoSelect(input, span, mensaje, estado) {
  if (estado === 1) {
    $(input).addClass("is-valid");
    $(input).removeClass("is-invalid");
    $(span).removeClass("invalid-feedback");
    $(span).addClass("valid-feedback");
    $(span).text("");
  } else {
    $(input).addClass("is-invalid");
    $(input).removeClass("is-valid");
    $(span).removeClass("valid-feedback");
    $(span).addClass("invalid-feedback");
    $(span).text(mensaje);
  }
}

function mensajes(icono, tiempo, titulo, mensaje) {
  if (icono === "error") {
    Swal.fire({
      icon: icono,
      timer: tiempo,
      title: titulo,
      text: mensaje,
      showConfirmButton: true,
      confirmButtonText: 'Aceptar',
    }).then(() => {
      $('#enviar').prop('disabled', false);
    });
  } else {
    Swal.fire({
      icon: icono,
      timer: tiempo,
      title: titulo,
      text: mensaje,
      showConfirmButton: true,
      confirmButtonText: 'Aceptar',
    });
  }
}

async function confirmarAccion(titulo, mensaje, icono) {
  let resultado = false;

  await Swal.fire({
    title: titulo,
    text: mensaje,
    icon: icono,
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      console.log("Confirmado");
      resultado = true;
    } else {
      console.log("Negado");
      resultado = false;
    }
  })

  return resultado;
}

function consultar() {
  $("#divtabla1").addClass("d-none");
  $("#spinnertabla1").removeClass("d-none");
  var peticion = new FormData();
  peticion.append('consultar', 'consultar');
  enviaAjax(peticion);
}

function registrarEntrada() {
  var peticion = new FormData();
  peticion.append('entrada', 'entrada');
  enviaAjax(peticion);
}

async function ConsultarPermisos() {
  var peticion = new FormData();
  peticion.append('permisos', 'permisos');
  enviaAjax(peticion);
}

async function buscarSelect(id_select, valor, opcion) {
  if (opcion === 'text') {
    let bool = false;

    $(`${id_select} option`).each(function () {
      if ($(this).text().trim() === valor.trim()) {
        $(this).prop('selected', true);
        $(this).change();
        bool = true;
      }
    })

    if (bool) {
      return true;
    } else {
      console.error("El valor '" + valor + "' no se encuentra en el campo select.")
    }

  } else if (opcion === 'value') {
    if ($(`${id_select} option[value="${valor}"]`).length > 0) {
      $(`${id_select}`).val(`${valor}`).change();
    } else {
      console.error("El valor " + valor + " no se encuentra en el campo select.");
    }

  } else {
    console.error("Opcion no Válida: " + opcion + "")
  }
  return true;
}

function selectEdificio(arreglo) {
  $("#id_edificio").empty();
  $("#id_edificio").append(new Option('Seleccione un Edificio', null));

  arreglo.forEach(item => {
    $("#id_edificio").append(new Option(item.nombre, item.id_edificio));
  });
}

// Funciones de formato
function formatearTelefono($input) {
  let numeros = $input.val().replace(/\D/g, '');
  numeros = numeros.substring(0, 10);

  if (numeros.length >= 6) {
    $input.val('(' + numeros.substring(0, 3) + ') ' + numeros.substring(3, 6) + '-' + numeros.substring(6));
  } else if (numeros.length >= 3) {
    $input.val('(' + numeros.substring(0, 3) + ') ' + numeros.substring(3));
  } else {
    $input.val(numeros);
  }
}

function formatearTelefonoSimple($input) {
  let numeros = $input.val().replace(/\D/g, '');

  if (numeros.length > 4) {
    $input.val(numeros.substring(0, 4) + '-' + numeros.substring(4));
  } else {
    $input.val(numeros);
  }
}

// Funcion para capitalizar texto - VERSIÓN ROBUSTA
function capitalizarTexto(texto) {
  if (!texto || typeof texto !== 'string') return texto;
  
  return texto
    .toLowerCase()
    .split(/(\s+)/) // Mantener los espacios múltiples
    .map(segmento => {
      // Si es un espacio, mantenerlo tal cual
      if (/^\s+$/.test(segmento)) return segmento;
      
      // Si es una palabra, capitalizar primera letra
      if (segmento.length > 0) {
        return segmento.charAt(0).toUpperCase() + segmento.slice(1);
      }
      
      return segmento;
    })
    .join('');
}

console.log("Terminado de cargar Expresiones Regulares");