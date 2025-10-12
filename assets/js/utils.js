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
  responsable: /^[a-zA-ZÀ-ÿ\s-.]{4,65}$/,
  codigoBien: /^[0-9a-zA-Z\-]{3,20}$/,
  descripcion: /^[0-9 a-zA-ZáéíóúüñÑçÇ -.,]{3,100}$/,
  serial: /^[0-9a-zA-ZáéíóúüñÑçÇ.-]{3,45}$/,
  tipoEquipo: /^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/
};

// Sistema de Validación Reutilizable
const SistemaValidacion = {
  // Inicializar validación para un formulario
  inicializar: function (elements, callbackCambioEstado = null) {
    this.elementos = elements;
    this.callbackCambioEstado = callbackCambioEstado;

    $.each(elements, function (key, element) {
      if (element && element.length) {
        element.on('blur', SistemaValidacion.validarCampo);
        element.on('input', SistemaValidacion.validarCampo);

        // Aplicar autocapitalización a campos de texto
        if (key === 'descripcion' || key === 'tipo_equipo') {
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
    const valor = $campo.val() ? $campo.val().trim() : '';
    const id = this.id;
    let esValido = true;
    let mensajeError = '';

    // Asignar patrones y mensajes según el campo
    switch (id) {
      case 'codigo_bien':
        esValido = patrones.codigoBien.test(valor);
        mensajeError = 'El código del bien debe tener de 3 a 20 caracteres (letras, números y guiones)';
        break;

      case 'descripcion':
        esValido = patrones.descripcion.test(valor);
        mensajeError = 'La descripción debe tener de 3 a 100 caracteres';
        break;

      case 'serial_equipo':
        esValido = patrones.serial.test(valor);
        mensajeError = 'El serial debe tener de 3 a 45 caracteres';
        break;

      case 'tipo_equipo':
        esValido = patrones.tipoEquipo.test(valor);
        mensajeError = 'El tipo de equipo debe tener de 3 a 45 caracteres';
        break;

      case 'id_categoria':
      case 'id_marca':
      case 'id_oficina':
      case 'cedula_empleado':
      case 'id_unidad_equipo':
      case 'estado':
        esValido = valor !== "default" && valor !== "" && valor !== null;
        mensajeError = 'Debe seleccionar una opción válida';
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
      if (elemento && elemento.length) {
        // Solo validar campos visibles y habilitados
        if (elemento.is(':visible') && !elemento.prop('disabled')) {
          if (elemento.hasClass('is-invalid') || (!elemento.hasClass('is-valid') && elemento.val() && elemento.val() !== "default")) {
            esValido = false;
          }
        }
      }
    });

    return esValido;
  },

  // Aplicar autocapitalización
  autoCapitalizar: function ($elemento) {
    const valor = $elemento.val() ? $elemento.val().trim() : '';
    if (valor) {
      const capitalizado = capitalizarTexto(valor);
      $elemento.val(capitalizado);
      setTimeout(() => {
        if ($elemento[0]) {
          SistemaValidacion.validarCampo.call($elemento[0]);
        }
      }, 100);
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
      if (elemento && elemento.length && elemento.is(':visible') && !elemento.prop('disabled')) {
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
      if (elemento && elemento.length) {
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
  const valor = etiqueta.val() ? etiqueta.val().trim() : '';
  const a = er.test(valor);
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
  return enviaAjax(peticion);
}

async function buscarSelect(id_select, valor, opcion) {
  if (!$(id_select).length) {
    console.error("El selector " + id_select + " no existe");
    return false;
  }

  if (opcion === 'text') {
    let bool = false;

    $(`${id_select} option`).each(function () {
      if ($(this).text().trim() === valor.trim()) {
        $(this).prop('selected', true);
        $(id_select).trigger('change');
        bool = true;
        return false; // break the loop
      }
    })

    if (bool) {
      return true;
    } else {
      console.error("El valor '" + valor + "' no se encuentra en el campo select.")
    }

  } else if (opcion === 'value') {
    if ($(`${id_select} option[value="${valor}"]`).length > 0) {
      $(`${id_select}`).val(`${valor}`).trigger('change');
      return true;
    } else {
      console.error("El valor " + valor + " no se encuentra en el campo select.");
    }

  } else {
    console.error("Opcion no Válida: " + opcion + "")
  }
  return false;
}

function selectEdificio(arreglo) {
  if (!$("#id_edificio").length) return;
  
  $("#id_edificio").empty();
  $("#id_edificio").append(new Option('Seleccione un Edificio', 'default'));

  if (Array.isArray(arreglo)) {
    arreglo.forEach(item => {
      $("#id_edificio").append(new Option(item.nombre, item.id_edificio));
    });
  }
}

// Funciones de formato
function formatearTelefono($input) {
  if (!$input.length) return;
  
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
  if (!$input.length) return;
  
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