const idiomaTabla = 'assets/js/datatable-plugin-es.js';

// EXPREGIONES REGULARES UNIFICADAS - ACTUALIZADAS
console.log("Cargando Expresiones Regulares Unificadas");
const patrones = {
  // Patrones básicos
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
  
  // Patrones para Bienes/Equipos
  codigoBien: /^[0-9a-zA-Z\-]{3,20}$/,
  descripcion: /^[0-9 a-zA-ZáéíóúüñÑçÇ -.,]{3,100}$/,
  serial: /^[0-9a-zA-ZáéíóúüñÑçÇ.-]{3,45}$/,
  tipoEquipo: /^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/,
  
  // PATRONES PARA MATERIAL - UNIFICADOS CON PHP
  id_material: /^[A-Z0-9\-_]{1,50}$/,
  nombre_material: /^[0-9a-zA-ZáéíóúüñÑçÇ\s\-.,()]{1,100}$/,
  id_oficina: /^[A-Z0-9]{1,30}$/,
  stock_material: /^[0-9]{1,6}$/,
  
  // Patrones para IDs generados
  id_generado: /^[A-Z0-9]{1,30}$/,
  
  // Patrones para nombres
  nombre_natural_largo: /^[0-9a-zA-ZáéíóúüñÑçÇ\s\-.,()]{1,100}$/,
  nombre_natural: /^[a-zA-ZáéíóúüñÑçÇ\s\-.]{1,65}$/,
  
  // Patrones para textos largos
  texto_largo: /^[0-9a-zA-ZáéíóúüñÑçÇ\s\-.,()!?]{1,200}$/,
  
  // Patrones para cédulas
  cedula: /^[VEJPGvejpg]\-\d{4,10}$/,
  
  // Patrones para estados
  estado_simple: /^[A-Za-z\s]{1,20}$/,
  
  // Patrones para fechas
  fecha: /^\d{4}-\d{2}-\d{2}$/,
  fecha_hora: /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/
};

// SISTEMA DE VALIDACIÓN REUTILIZABLE - MEJORADO Y UNIFICADO
const SistemaValidacion = {
  // Inicializar validación para un formulario
  inicializar: function (elements, callbackCambioEstado = null) {
    this.elementos = elements;
    this.callbackCambioEstado = callbackCambioEstado;

    $.each(elements, function (key, element) {
      if (element && element.length) {
        // Solo aplicar eventos de validación después de la primera interacción
        element.on('focus', function() {
          $(this).data('touched', true);
        });

        element.on('blur', SistemaValidacion.validarCampo);
        element.on('input', SistemaValidacion.validarCampo);

        // Aplicar autocapitalización a campos de texto
        if (key === 'descripcion' || key === 'tipo_equipo' || key === 'nombre' || key === 'nombre_material') {
          element.on('blur', function () {
            SistemaValidacion.autoCapitalizar($(this));
          });
        }
      }
    });

    // Validación inicial sin mostrar errores
    setTimeout(() => {
      const formularioValido = SistemaValidacion.verificarEstadoFormulario();
      if (callbackCambioEstado) {
        callbackCambioEstado(formularioValido);
      }
    }, 100);
  },

  // Validar campo individual - MEJORADO para no mostrar errores iniciales
  validarCampo: function () {
    const $campo = $(this);
    const valor = $campo.val() ? $campo.val().trim() : '';
    const id = this.id;
    let esValido = true;
    let mensajeError = '';

    // Solo mostrar errores si el campo ya fue interactuado
    const fueInteractuado = $campo.data('touched') || $campo.is(':focus');

    // Asignar patrones y mensajes según el campo - ACTUALIZADO CON PATRONES UNIFICADOS
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

      // VALIDACIONES PARA MATERIAL - UNIFICADAS CON PHP
      case 'nombre':
      case 'nombre_material':
        esValido = patrones.nombre_material.test(valor);
        mensajeError = 'El nombre debe tener 1-100 caracteres alfanuméricos';
        break;

      case 'stock':
        esValido = patrones.stock_material.test(valor);
        mensajeError = 'El stock debe ser un número entre 0 y 999999';
        break;

      case 'id_material':
        esValido = patrones.id_material.test(valor);
        mensajeError = 'ID no válido. Debe ser alfanumérico de 1-50 caracteres';
        break;

      // Validación para selects
      case 'id_categoria':
      case 'id_marca':
      case 'id_oficina':
      case 'ubicacion':
      case 'cedula_empleado':
      case 'id_unidad_equipo':
      case 'estado':
        esValido = valor !== "default" && valor !== "" && valor !== null;
        mensajeError = 'Debe seleccionar una opción válida';
        break;

      // Validación para cédulas
      case 'cedula_solicitante':
      case 'cedula_empleado':
      case 'cedula_tecnico':
        esValido = patrones.cedula.test(valor);
        mensajeError = 'La cédula debe tener formato V-12345678';
        break;

      // Validación para emails
      case 'correo_empleado':
      case 'email':
        esValido = patrones.email.test(valor);
        mensajeError = 'El formato del email no es válido';
        break;

      // Validación para teléfonos
      case 'telefono_empleado':
      case 'telefono':
        esValido = patrones.telefonoSimple.test(valor);
        mensajeError = 'El teléfono debe tener formato 0412-1234567';
        break;

      default:
        // Validación genérica para campos de texto
        if ($campo.attr('type') === 'text' || $campo.is('input')) {
          esValido = valor.length >= 1;
          mensajeError = 'Este campo es requerido';
        }
    }

    // Aplicar estilos de validación SOLO si el campo fue interactuado
    if (fueInteractuado) {
      SistemaValidacion.aplicarEstilos($campo, esValido, mensajeError);
    } else {
      // Limpiar estilos visuales si no ha sido interactuado
      SistemaValidacion.limpiarEstilosCampo($campo);
    }

    // Verificar estado general del formulario después de cada validación
    if (SistemaValidacion.callbackCambioEstado) {
      const formularioValido = SistemaValidacion.verificarEstadoFormulario();
      SistemaValidacion.callbackCambioEstado(formularioValido);
    }

    return esValido;
  },

  // Verificar estado general del formulario - MEJORADO
  verificarEstadoFormulario: function () {
    let esValido = true;

    $.each(this.elementos, function (key, elemento) {
      if (elemento && elemento.length) {
        // Solo validar campos visibles y habilitados
        if (elemento.is(':visible') && !elemento.prop('disabled')) {
          const valor = elemento.val() ? elemento.val().trim() : '';
          let campoValido = true;

          // Validación según tipo de campo - ACTUALIZADO CON PATRONES UNIFICADOS
          switch (elemento.attr('id')) {
            case 'codigo_bien':
              campoValido = patrones.codigoBien.test(valor);
              break;
            case 'descripcion':
              campoValido = patrones.descripcion.test(valor);
              break;
            case 'serial_equipo':
              campoValido = patrones.serial.test(valor);
              break;
            case 'tipo_equipo':
              campoValido = patrones.tipoEquipo.test(valor);
              break;
            
            // VALIDACIONES PARA MATERIAL - UNIFICADAS
            case 'nombre':
            case 'nombre_material':
              campoValido = patrones.nombre_material.test(valor);
              break;
            case 'stock':
              campoValido = patrones.stock_material.test(valor);
              break;
            case 'id_material':
              campoValido = patrones.id_material.test(valor);
              break;

            case 'id_categoria':
            case 'id_marca':
            case 'id_oficina':
            case 'ubicacion':
            case 'cedula_empleado':
            case 'id_unidad_equipo':
            case 'estado':
              campoValido = valor !== "default" && valor !== "" && valor !== null;
              break;
            
            case 'cedula_solicitante':
            case 'cedula_empleado':
            case 'cedula_tecnico':
              campoValido = patrones.cedula.test(valor);
              break;
            
            case 'correo_empleado':
            case 'email':
              campoValido = patrones.email.test(valor);
              break;
            
            case 'telefono_empleado':
            case 'telefono':
              campoValido = patrones.telefonoSimple.test(valor);
              break;

            default:
              if (elemento.attr('type') === 'text' || elemento.is('input')) {
                campoValido = valor.length >= 1;
              }
          }

          if (!campoValido) {
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
        $feedback.removeClass("invalid-feedback").addClass("valid-feedback").text("✓ Correcto");
      }
    } else {
      $elemento.removeClass("is-valid").addClass("is-invalid");
      if ($feedback.length) {
        $feedback.removeClass("valid-feedback").addClass("invalid-feedback").text(mensajeError);
      }
    }
  },

  // Limpiar estilos de un campo individual
  limpiarEstilosCampo: function ($elemento) {
    const id = $elemento.attr('id');
    const $feedback = $(`#s${id}`);

    $elemento.removeClass("is-valid is-invalid");
    if ($feedback.length) {
      $feedback.removeClass("invalid-feedback valid-feedback").text("");
    }
  },

  // Validar formulario completo - MEJORADO para validación silenciosa
  validarFormulario: function (elementos) {
    let esValido = true;
    let alMenosUnoInteractuado = false;

    $.each(elementos, function (key, elemento) {
      if (elemento && elemento.length && elemento.is(':visible') && !elemento.prop('disabled')) {
        // Verificar si el campo fue interactuado
        if (elemento.data('touched') || elemento.is(':focus')) {
          alMenosUnoInteractuado = true;
        }
        
        // Forzar validación de cada campo
        elemento.trigger('blur');
        if (elemento.hasClass('is-invalid') || !SistemaValidacion.validarCampo.call(elemento[0])) {
          esValido = false;
        }
      }
    });

    // Si ningún campo ha sido interactuado, limpiar estilos visuales
    if (!alMenosUnoInteractuado) {
      SistemaValidacion.limpiarValidacionVisual(elementos);
    }

    return esValido;
  },

  // Limpiar validación de formulario
  limpiarValidacion: function (elementos) {
    $.each(elementos, function (key, elemento) {
      if (elemento && elemento.length) {
        elemento.removeClass("is-valid is-invalid");
        elemento.removeData('touched'); // Remover marca de interacción
        const id = elemento.attr('id');
        const $feedback = $(`#s${id}`);
        if ($feedback.length) {
          $feedback.removeClass("invalid-feedback valid-feedback").text("");
        }
      }
    });
  },

  // Limpiar solo la validación visual (sin remover datos de interacción)
  limpiarValidacionVisual: function (elementos) {
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
  },

  // Validar formulario sin mostrar errores visuales (para validación interna)
  validarFormularioSilencioso: function (elementos) {
    let esValido = true;

    $.each(elementos, function (key, elemento) {
      if (elemento && elemento.length && elemento.is(':visible') && !elemento.prop('disabled')) {
        const valor = elemento.val() ? elemento.val().trim() : '';
        let campoValido = true;

        // Validación según tipo de campo - ACTUALIZADO CON PATRONES UNIFICADOS
        switch (elemento.attr('id')) {
          case 'codigo_bien':
            campoValido = patrones.codigoBien.test(valor);
            break;
          case 'descripcion':
            campoValido = patrones.descripcion.test(valor);
            break;
          case 'serial_equipo':
            campoValido = patrones.serial.test(valor);
            break;
          case 'tipo_equipo':
            campoValido = patrones.tipoEquipo.test(valor);
            break;
          
          // VALIDACIONES PARA MATERIAL - UNIFICADAS
          case 'nombre':
          case 'nombre_material':
            campoValido = patrones.nombre_material.test(valor);
            break;
          case 'stock':
            campoValido = patrones.stock_material.test(valor);
            break;
          case 'id_material':
            campoValido = patrones.id_material.test(valor);
            break;

          case 'id_categoria':
          case 'id_marca':
          case 'id_oficina':
          case 'ubicacion':
          case 'cedula_empleado':
          case 'id_unidad_equipo':
          case 'estado':
            campoValido = valor !== "default" && valor !== "" && valor !== null;
            break;
          
          case 'cedula_solicitante':
          case 'cedula_empleado':
          case 'cedula_tecnico':
            campoValido = patrones.cedula.test(valor);
            break;
          
          case 'correo_empleado':
          case 'email':
            campoValido = patrones.email.test(valor);
            break;
          
          case 'telefono_empleado':
          case 'telefono':
            campoValido = patrones.telefonoSimple.test(valor);
            break;

          default:
            if (elemento.attr('type') === 'text' || elemento.is('input')) {
              campoValido = valor.length >= 1;
            }
        }

        if (!campoValido) {
          esValido = false;
        }
      }
    });

    return esValido;
  }
};

// Función para limpiar validación visual global
function limpiarValidacionVisualGlobal() {
  $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
  $('.invalid-feedback, .valid-feedback').removeClass('invalid-feedback valid-feedback').text('');
}

// FUNCIONES DE UTILIDAD EXISTENTES - MANTENIDAS
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

// FUNCIONES DE FORMATO
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

// FUNCION PARA CAPITALIZAR TEXTO - VERSIÓN ROBUSTA
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

// FUNCIÓN PARA INICIALIZAR TOOLTIPS EN TODA LA PÁGINA
function inicializarTooltips() {
  $('[data-bs-toggle="tooltip"]').tooltip({
    trigger: 'hover',
    placement: 'top'
  });
}

// FUNCIÓN PARA MOSTRAR/OCULTAR LOADING
function mostrarLoading(mostrar = true) {
  if (mostrar) {
    $('body').append(`
      <div id="loading-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;display:flex;align-items:center;justify-content:center;">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
      </div>
    `);
  } else {
    $('#loading-overlay').remove();
  }
}

// FUNCIÓN PARA FORMATEAR FECHAS
function formatearFecha(fecha, formato = 'dd/mm/yyyy') {
  if (!fecha) return '';
  
  const date = new Date(fecha);
  if (isNaN(date.getTime())) return fecha;

  const dia = date.getDate().toString().padStart(2, '0');
  const mes = (date.getMonth() + 1).toString().padStart(2, '0');
  const anio = date.getFullYear();

  switch (formato) {
    case 'dd/mm/yyyy':
      return `${dia}/${mes}/${anio}`;
    case 'yyyy-mm-dd':
      return `${anio}-${mes}-${dia}`;
    case 'mm/dd/yyyy':
      return `${mes}/${dia}/${anio}`;
    default:
      return `${dia}/${mes}/${anio}`;
  }
}

// FUNCIÓN PARA VALIDAR EMAIL
function validarEmail(email) {
  return patrones.email.test(email);
}

// FUNCIÓN PARA GENERAR CÓDIGO ALEATORIO
function generarCodigoAleatorio(longitud = 8) {
  const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  let resultado = '';
  for (let i = 0; i < longitud; i++) {
    resultado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
  }
  return resultado;
}

// FUNCIÓN PARA COPIAR AL PORTAPAPELES
function copiarAlPortapapeles(texto) {
  navigator.clipboard.writeText(texto).then(() => {
    mensajes('success', 2000, 'Copiado', 'Texto copiado al portapapeles');
  }).catch(err => {
    console.error('Error al copiar: ', err);
    mensajes('error', 3000, 'Error', 'No se pudo copiar al portapapeles');
  });
}

// FUNCIÓN PARA DESCARGAR ARCHIVO
function descargarArchivo(contenido, nombreArchivo, tipo = 'text/plain') {
  const blob = new Blob([contenido], { type: tipo });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = nombreArchivo;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
}

// FUNCIÓN PARA OBTENER PARÁMETROS DE URL
function obtenerParametrosURL() {
  const params = new URLSearchParams(window.location.search);
  const resultado = {};
  for (const [key, value] of params) {
    resultado[key] = value;
  }
  return resultado;
}

// FUNCIÓN PARA ESTABLECER PARÁMETROS DE URL
function establecerParametrosURL(parametros) {
  const url = new URL(window.location);
  Object.keys(parametros).forEach(key => {
    url.searchParams.set(key, parametros[key]);
  });
  window.history.replaceState({}, '', url);
}

// FUNCIÓN PARA DEBOUNCE (EVITAR MÚLTIPLES LLAMADAS)
function debounce(func, wait, immediate) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      timeout = null;
      if (!immediate) func(...args);
    };
    const callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func(...args);
  };
}

// FUNCIÓN PARA THROTTLE (LIMITAR FRECUENCIA DE LLAMADAS)
function throttle(func, limit) {
  let inThrottle;
  return function(...args) {
    if (!inThrottle) {
      func.apply(this, args);
      inThrottle = true;
      setTimeout(() => inThrottle = false, limit);
    }
  }
}

// FUNCIONES ESPECÍFICAS PARA VALIDACIÓN DE MATERIAL
function validarMaterialCompleto() {
  const elementos = {
    nombre: $('#nombre'),
    ubicacion: $('#ubicacion'),
    stock: $('#stock'),
    id_material: $('#id_material')
  };
  
  return SistemaValidacion.validarFormularioSilencioso(elementos);
}

function limpiarValidacionMaterial() {
  const elementos = {
    nombre: $('#nombre'),
    ubicacion: $('#ubicacion'),
    stock: $('#stock'),
    id_material: $('#id_material')
  };
  
  SistemaValidacion.limpiarValidacion(elementos);
}

// INICIALIZAR COMPONENTES CUANDO EL DOCUMENTO ESTÉ LISTO
$(document).ready(function() {
  // Inicializar tooltips
  inicializarTooltips();
  
  // Prevenir envío doble de formularios
  $('form').on('submit', function() {
    const $submitBtn = $(this).find('button[type="submit"], input[type="submit"]');
    $submitBtn.prop('disabled', true);
    
    // Rehabilitar después de 5 segundos por si hay error
    setTimeout(() => {
      $submitBtn.prop('disabled', false);
    }, 5000);
  });
  
  // Auto-capitalizar campos de texto al perder foco
  $('input[type="text"]').on('blur', function() {
    const $this = $(this);
    if ($this.val()) {
      $this.val(capitalizarTexto($this.val()));
    }
  });

  // Validación en tiempo real para campos de material
  $('body').on('input', '#nombre, #nombre_material', function() {
    const $this = $(this);
    const valor = $this.val();
    if (valor && patrones.nombre_material.test(valor)) {
      $this.removeClass('is-invalid').addClass('is-valid');
    } else if (valor) {
      $this.removeClass('is-valid').addClass('is-invalid');
    }
  });

  $('body').on('input', '#stock', function() {
    const $this = $(this);
    const valor = $this.val();
    if (valor && patrones.stock_material.test(valor)) {
      $this.removeClass('is-invalid').addClass('is-valid');
    } else if (valor) {
      $this.removeClass('is-valid').addClass('is-invalid');
    }
  });

  $('body').on('input', '#id_material', function() {
    const $this = $(this);
    const valor = $this.val();
    if (valor && patrones.id_material.test(valor)) {
      $this.removeClass('is-invalid').addClass('is-valid');
    } else if (valor) {
      $this.removeClass('is-valid').addClass('is-invalid');
    }
  });

  // Mensaje de carga completada
  console.log("Utils cargado completamente - Validaciones unificadas activas");
});

// EXPORTAR FUNCIONES PARA USO GLOBAL (si es necesario en módulos)
if (typeof module !== 'undefined' && module.exports) {
  module.exports = {
    patrones,
    SistemaValidacion,
    capitalizarTexto,
    validarEmail,
    formatearFecha,
    generarCodigoAleatorio,
    debounce,
    throttle
  };
}