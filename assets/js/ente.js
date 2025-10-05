// Elementos del formulario para Ente
const elementosEnte = {
  nombre: $('#nombre'),
  responsable: $('#responsable'),
  telefono: $('#telefono'),
  direccion: $('#direccion'),
  tipo_ente: $('#tipo_ente'),
  id_ente: $('#id_ente')
};

// Función para manejar el cambio de estado del formulario
function manejarCambioEstado(formularioValido) {
  const accion = $("#enviar").text();
  
  if (accion === "Eliminar") {
    // Para eliminar solo validamos el ID
    const idValido = $("#id_ente").length && $("#id_ente").hasClass("is-valid");
    $('#enviar').prop('disabled', !idValido);
  } else {
    // Para registrar y modificar validamos todos los campos
    $('#enviar').prop('disabled', !formularioValido);
  }
}

$(document).ready(function () {
  consultar();
  registrarEntrada();
  capaValidar();
  ConsultarPermisos();
  
  // Inicializar sistema de validación con callback
  SistemaValidacion.inicializar(elementosEnte, manejarCambioEstado);
  
  // Validar estado inicial del formulario
  manejarCambioEstado(false);

  $("#enviar").on("click", async function () {
    var confirmacion = false;
    var envio = false;

    switch ($(this).text()) {
      case "Registrar":
        if (validarenvio()) {
          confirmacion = await confirmarAccion("Se registrará un Ente", "¿Está seguro de realizar la acción?", "question");

          if (confirmacion) {
            var datos = new FormData();
            datos.append('registrar', 'registrar');
            datos.append('nombre', $("#nombre").val());
            datos.append('responsable', $("#responsable").val());
            datos.append('telefono', $("#telefono").val());
            datos.append('direccion', $("#direccion").val());
            datos.append('tipo_ente', $("#tipo_ente").val());
            enviaAjax(datos);
            envio = true;
          }
        } else {
          envio = false;
          mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
        }
        break;

      case "Modificar":
        if (validarenvio()) {
          confirmacion = await confirmarAccion("Se modificará un Ente", "¿Está seguro de realizar la acción?", "question");

          if (confirmacion) {
            var datos = new FormData();
            datos.append('modificar', 'modificar');
            datos.append('id_ente', $("#id_ente").val());
            datos.append('nombre', $("#nombre").val());
            datos.append('responsable', $("#responsable").val());
            datos.append('telefono', $("#telefono").val());
            datos.append('direccion', $("#direccion").val());
            datos.append('tipo_ente', $("#tipo_ente").val());
            enviaAjax(datos);
            envio = true;
          }
        } else {
          envio = false;
          mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
        }
        break;

      case "Eliminar":
        // Validar solo el ID para eliminar
        if ($("#id_ente").length && SistemaValidacion.validarCampo.call(document.getElementById('id_ente'))) {
          confirmacion = await confirmarAccion("Se eliminará un Ente", "¿Está seguro de realizar la acción?", "warning");

          if (confirmacion) {
            var datos = new FormData();
            datos.append('eliminar', 'eliminar');
            datos.append('id_ente', $("#id_ente").val());
            enviaAjax(datos);
            envio = true;
          }
        } else {
          envio = false;
          mensajes("error", 10000, "Error de Validación", "El ID del ente no es válido.");
        }
        break;

      default:
        mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text());
    }

    if (envio) {
      $('#enviar').prop('disabled', true);
    }

    if (!confirmacion) {
      $('#enviar').prop('disabled', false);
    }
  });

  $("#btn-registrar").on("click", function () {
    limpia();
    $("#idEnte").remove();
    $("#modalTitleId").text("Registrar Ente");
    $("#enviar").text("Registrar");
    $("#modal1").modal("show");
    // El botón se habilita automáticamente mediante el callback cuando los campos sean válidos
  });

  $("#btn-consultar-eliminados").on("click", function () {
    consultarEliminadas();
    $("#modalEliminadas").modal("show");
  });

  // Aplicar capitalización automática cuando el modal se muestra
  $('#modal1').on('shown.bs.modal', function () {
    // Forzar validación inicial cuando se abre el modal
    setTimeout(() => {
      SistemaValidacion.validarFormulario(elementosEnte);
    }, 100);
  });
});

function consultarEliminadas() {
  var datos = new FormData();
  datos.append('consultar_eliminadas', 'consultar_eliminadas');
  enviaAjax(datos);
}

function vistaPermiso(permisos = null) {
  if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {
    $('.modificar').remove();
    $('.eliminar').remove();
  } else {
    if (permisos['ente']['modificar']['estado'] == '0') {
      $('.modificar').remove();
    }

    if (permisos['ente']['eliminar']['estado'] == '0') {
      $('.eliminar').remove();
    }
  }
}

function enviaAjax(datos) {
  $.ajax({
    async: true,
    url: "",
    type: "POST",
    contentType: false,
    data: datos,
    processData: false,
    cache: false,
    beforeSend: function () { },
    timeout: 10000,
    success: function (respuesta) {
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado == "registrar") {
          $("#modal1").modal("hide");
          mensajes("success", 10000, lee.mensaje, null);
          consultar();

        } else if (lee.resultado == "consultar") {
          crearDataTable(lee.datos);

        } else if (lee.resultado == "consultar_eliminados") {
          iniciarTablaEliminadas(lee.datos);

        } else if (lee.resultado == "modificar") {
          $("#modal1").modal("hide");
          mensajes("success", 10000, lee.mensaje, null);
          consultar();

        } else if (lee.resultado == "eliminar") {
          $("#modal1").modal("hide");
          mensajes("success", 10000, lee.mensaje, null);
          consultar();

        } else if (lee.resultado == "entrada") {

        } else if (lee.resultado == "permisos_modulo") {
          vistaPermiso(lee.permisos);

        } else if (lee.resultado == "error") {
          mensajes("error", null, lee.mensaje, null);
        }
      } catch (e) {
        mensajes("error", null, "Error en JSON Tipo: " + e.name + "\n" +
          "Mensaje: " + e.message + "\n" +
          "Posición: " + e.lineNumber);
      }
    },
    error: function (request, status, err) {
      if (status == "timeout") {
        mensajes("error", null, "Servidor ocupado", "Intente de nuevo");
      } else {
        mensajes("error", null, "Ocurrió un error", "ERROR: <br/>" + request + status + err);
      }
    },
    complete: function () { },
  });
}

function capaValidar() {
  // Validación con formato en tiempo real
  $("#nombre").on("keypress", function (e) {
    validarKeyPress(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.\b]*$/, e);
  });

  $("#responsable").on("keypress", function (e) {
    validarKeyPress(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.\b]*$/, e);
  });

  // Aplicar capitalización en tiempo real para nombre y responsable
  $("#nombre").on("input", function() {
    // Capitalizar mientras escribe (opcional)
    const valor = $(this).val();
    if (valor.length === 1) {
      $(this).val(valor.toUpperCase());
    }
  });

  $("#responsable").on("input", function() {
    // Capitalizar mientras escribe (opcional)
    const valor = $(this).val();
    if (valor.length === 1) {
      $(this).val(valor.toUpperCase());
    }
  });

  $("#telefono").on("keypress", function (e) {
    validarKeyPress(/^[0-9-]*$/, e);
  });

  $("#telefono").on("input", function () {
    formatearTelefonoSimple($(this));
    // Validar automáticamente después de formatear
    setTimeout(() => SistemaValidacion.validarCampo.call(this), 100);
  });

  $("#direccion").on("keypress", function (e) {
    validarKeyPress(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.\b]*$/, e);
  });

  $("#tipo_ente").on("change", function () {
    SistemaValidacion.validarCampo.call(this);
  });
}

function validarenvio() {
  return SistemaValidacion.validarFormulario(elementosEnte);
}

function crearDataTable(arreglo) {
  if ($.fn.DataTable.isDataTable('#tabla1')) {
    $('#tabla1').DataTable().destroy();
  }
  
  $('#tabla1').DataTable({
    data: arreglo,
    columns: [
      { 
        data: 'id',
        visible: false
      },
      { data: 'nombre' },
      { data: 'nombre_responsable' },
      { data: 'telefono' },
      { data: 'direccion' },
      { data: 'tipo_ente' },
      {
        data: null, 
        render: function () {
          const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar"><i class="fa-solid fa-pen-to-square"></i></button>
          <button onclick="rellenar(this, 1)" class="btn btn-danger eliminar"><i class="fa-solid fa-trash"></i></button>`;
          return botones;
        }
      }
    ],
    order: [[1, 'asc']],
    language: { url: idiomaTabla }
  });
  ConsultarPermisos();
}

function iniciarTablaEliminadas(arreglo) {
  if ($.fn.DataTable.isDataTable('#tablaEliminadas')) {
    $('#tablaEliminadas').DataTable().destroy();
  }

  $('#tablaEliminadas').DataTable({
    data: arreglo,
    columns: [
      { 
        data: 'id',
        visible: false
      },
      { data: 'nombre' },
      { data: 'nombre_responsable' },
      { data: 'telefono' },
      { data: 'direccion' },
      { data: 'tipo_ente' },
      {
        data: null,
        render: function () {
          return `<button onclick="reactivarEnte(this)" class="btn btn-success reactivar">
                  <i class="fa-solid fa-recycle"></i>
                  </button>`;
        }
      }
    ],
    order: [[1, 'asc']],
    language: { url: idiomaTabla }
  });
  ConsultarPermisos();
}

function limpia() {
  SistemaValidacion.limpiarValidacion(elementosEnte);
  
  $("#nombre").val("");
  $("#responsable").val("");
  $("#telefono").val("");
  $("#direccion").val("");
  $("#tipo_ente").val("default");

  $("#nombre").prop("readOnly", false);
  $("#responsable").prop("readOnly", false);
  $("#telefono").prop("readOnly", false);
  $("#direccion").prop("readOnly", false);
  $("#tipo_ente").prop("disabled", false);

  // Deshabilitar el botón al limpiar (se habilitará automáticamente cuando los campos sean válidos)
  $('#enviar').prop('disabled', true);
}

function rellenar(pos, accion) {
  limpia();

  const linea = $(pos).closest('tr');
  const tabla = $('#tabla1').DataTable();
  const datosFila = tabla.row(linea).data();

  $("#idEnte").remove();
  $("#Fila1").prepend(`<div class="col-md-4" id="idEnte">
          <div class="form-floating mb-3 mt-4">
            <input placeholder="" class="form-control" name="id_ente" type="text" id="id_ente" readOnly>
            <span id="sid_ente"></span>
            <label for="id_ente" class="form-label">ID del Ente</label>
          </div>`);

  // Actualizar elementosEnte para incluir el nuevo campo
  elementosEnte.id_ente = $('#id_ente');

  // Usar los datos directamente de DataTable (más confiable)
  $("#id_ente").val(datosFila.id);
  $("#nombre").val(capitalizarTexto(datosFila.nombre));
  $("#responsable").val(capitalizarTexto(datosFila.nombre_responsable));
  $("#telefono").val(datosFila.telefono);
  $("#direccion").val(datosFila.direccion);
  buscarSelect("#tipo_ente", datosFila.tipo_ente, "text");

  if (accion == 0) {
    $("#modalTitleId").text("Modificar Ente")
    $("#enviar").text("Modificar");
  } else {
    $("#nombre").prop("readOnly", true);
    $("#responsable").prop("readOnly", true);
    $("#telefono").prop("readOnly", true);
    $("#direccion").prop("readOnly", true);
    $("#tipo_ente").prop("disabled", true);
    $("#modalTitleId").text("Eliminar Ente")
    $("#enviar").text("Eliminar");
  }
  
  // Habilitar el botón inmediatamente para Modificar/Eliminar ya que los datos vienen pre-validados
  $('#enviar').prop('disabled', false);
  $("#modal1").modal("show");
}

function reactivarEnte(boton) {
  const linea = $(boton).closest('tr');
  const tabla = $('#tablaEliminadas').DataTable();
  const datosFila = tabla.row(linea).data();
  const id = datosFila.id;

  Swal.fire({
    title: '¿Reactivar Ente?',
    text: "¿Está seguro que desea reactivar este ente?",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, reactivar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      const datos = new FormData();
      datos.append('reactivar', 'reactivar');
      datos.append('id_ente', id);

      $.ajax({
        url: "",
        type: "POST",
        data: datos,
        processData: false,
        contentType: false,
        success: function (respuesta) {
          try {
            const lee = JSON.parse(respuesta);
            if (lee.estado == 1) {
              mensajes("success", null, "Ente reactivado", lee.mensaje);
              consultarEliminadas();
              consultar();
            } else {
              mensajes("error", null, "Error", lee.mensaje);
            }
          } catch (e) {
            mensajes("error", null, "Error", "Error procesando la respuesta");
          }
        },
        error: function () {
          mensajes("error", null, "Error", "No se pudo reactivar el ente");
        }
      });
    }
  });
}