// Elementos del formulario para Cargo
const elementosCargo = {
  nombre_cargo: $('#nombre_cargo'),
  id_cargo: $('#id_cargo')
};

// Función para manejar el cambio de estado del formulario
function manejarCambioEstadoCargo(formularioValido) {
  const accion = $("#enviarCargo").text();
  
  if (accion === "Eliminar") {
    // Para eliminar solo validamos el ID
    const idValido = $("#id_cargo").length && $("#id_cargo").hasClass("is-valid");
    $('#enviarCargo').prop('disabled', !idValido);
  } else {
    // Para registrar y modificar validamos todos los campos
    $('#enviarCargo').prop('disabled', !formularioValido);
  }
}

$(document).ready(function () {
    consultar();
    capaValidar();

    // Inicializar sistema de validación con callback
    SistemaValidacion.inicializar(elementosCargo, manejarCambioEstadoCargo);
    
    // Validar estado inicial del formulario
    manejarCambioEstadoCargo(false);

    $("#enviarCargo").on("click", async function () {
        var confirmacion = false;
        var envio = false;

        switch ($(this).text()) {
            case "Registrar":
                if (validarEnvioCargo()) {
                    confirmacion = await confirmarAccion("Se registrará un Cargo", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        var datos = new FormData();
                        datos.append('registrar', 'registrar');
                        datos.append('nombre_cargo', $("#nombre_cargo").val());
                        enviaAjax(datos);
                        envio = true;
                    }
                } else {
                    envio = false;
                    mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
                }
                break;

            case "Modificar":
                if (validarEnvioCargo()) {
                    confirmacion = await confirmarAccion("Se modificará un Cargo", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        var datos = new FormData();
                        datos.append('modificar', 'modificar');
                        datos.append('id_cargo', $("#id_cargo").val());
                        datos.append('nombre_cargo', $("#nombre_cargo").val());
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
                if ($("#id_cargo").length && SistemaValidacion.validarCampo.call(document.getElementById('id_cargo'))) {
                    confirmacion = await confirmarAccion("Se eliminará un Cargo", "¿Está seguro de realizar la acción?", "warning");
                    if (confirmacion) {
                        var datos = new FormData();
                        datos.append('eliminar', 'eliminar');
                        datos.append('id_cargo', $("#id_cargo").val());
                        enviaAjax(datos);
                        envio = true;
                    }
                } else {
                    envio = false;
                    mensajes("error", 10000, "Error de Validación", "El ID del cargo no es válido.");
                }
                break;

            default:
                mensajes("error", null, "Acción desconocida: " + $(this).text());
        }

        if (envio) {
            $('#enviarCargo').prop('disabled', true);
        }

        if (!confirmacion) {
            $('#enviarCargo').prop('disabled', false);
        }
    });

    $("#btn-registrar-cargo").on("click", function () {
        limpiarCargo();
        $("#modalCargoTitle").text("Registrar Cargo");
        $("#enviarCargo").text("Registrar");
        $("#modalCargo").modal("show");
        // El botón se habilita automáticamente mediante el callback cuando los campos sean válidos
    });

    $("#btn-consultar-eliminados").on("click", function () {
        consultarEliminadas();
        $("#modalEliminadas").modal("show");
    });

    // Forzar validación inicial cuando se abre el modal
    $('#modalCargo').on('shown.bs.modal', function () {
        setTimeout(() => {
            SistemaValidacion.validarFormulario(elementosCargo);
        }, 100);
    });
});

function consultarEliminadas() {
    var datos = new FormData();
    datos.append('consultar_eliminados', 'consultar_eliminados');
    enviaAjax(datos);
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
        timeout: 10000, //tiempo maximo de espera por la respuesta del servidor
        success: function (respuesta) {
            console.log(respuesta);
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "registrar") {
                    $("#modalCargo").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    consultar();

                } else if (lee.resultado == "consultar") {
                    crearDataTable(lee.datos);

                } else if (lee.resultado == "consultar_eliminados") {
                    iniciarTablaEliminadas(lee.datos);

                } else if (lee.resultado == "modificar") {
                    $("#modalCargo").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    consultar();

                } else if (lee.resultado == "eliminar") {
                    $("#modalCargo").modal("hide");
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
    $("#nombre_cargo").on("keypress", function (e) {
        validarKeyPress(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.\b]*$/, e);
    });

    // Aplicar capitalización en tiempo real
    $("#nombre_cargo").on("input", function() {
        const valor = $(this).val();
        if (valor.length === 1) {
            $(this).val(valor.toUpperCase());
        }
    });
}

function vistaPermiso(permisos = null) {
    if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {
        $('.modificar').remove();
        $('.eliminar').remove();
    } else {
        if (permisos['cargo']['modificar']['estado'] == '0') {
            $('.modificar').remove();
        }

        if (permisos['cargo']['eliminar']['estado'] == '0') {
            $('.eliminar').remove();
        }
    }
}

function iniciarTablaEliminadas(arreglo) {
    if ($.fn.DataTable.isDataTable('#tablaEliminadas')) {
        $('#tablaEliminadas').DataTable().destroy();
    }

    $('#tablaEliminadas').DataTable({
        data: arreglo,
        columns: [
            { data: 'id_cargo', visible: false },
            { data: 'nombre_cargo' },
            {
                data: null,
                render: function () {
                    return `<button onclick="reactivarCargo(this)" class="btn btn-success reactivar">
                            <i class="fa-solid fa-recycle"></i>
                            </button>`;
                }
            }
        ],
        order: [
            [1, 'asc']
        ],
        language: {
            url: idiomaTabla,
        }
    });
    ConsultarPermisos();
}

function crearDataTable(arreglo) {
    if ($.fn.DataTable.isDataTable('#tablaCargos')) {
        $('#tablaCargos').DataTable().destroy();
    }
    $('#tablaCargos').DataTable({
        data: arreglo,
        columns: [
            { data: 'id_cargo', visible: false },
            { data: 'nombre_cargo' },
            {
                data: null,
                render: function () {
                    return `<button onclick="rellenar(this, 0)" class="btn btn-update modificar"><i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" class="btn btn-danger eliminar"><i class="fa-solid fa-trash"></i></button>`;
                }
            }
        ],
        order: [
            [1, 'asc']
        ],
        language: {
            url: idiomaTabla
        }
    });
    ConsultarPermisos();
}

function reactivarCargo(boton) {
    var linea = $(boton).closest('tr');
    var id = $(linea).find('td:eq(0)').text();

    Swal.fire({
        title: '¿Reactivar Cargo?',
        text: "¿Está seguro que desea reactivar esta cargo?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, reactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var datos = new FormData();
            datos.append('reactivar', 'reactivar');
            datos.append('id_cargo', id);

            $.ajax({
                url: "",
                type: "POST",
                data: datos,
                processData: false,
                contentType: false,
                success: function (respuesta) {
                    try {
                        var lee = JSON.parse(respuesta);
                        if (lee.estado == 1) {
                            mensajes("success", null, "Cargo restaurada", lee.mensaje);
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
                    mensajes("error", null, "Error", "No se pudo reactivar el cargo");
                }
            });
        }
    });
}

function rellenar(pos, accion) {
    limpiarCargo();
    const linea = $(pos).closest('tr');
    const tabla = $('#tablaCargos').DataTable();
    const datosFila = tabla.row(linea).data();

    $("#idCargo").remove();
    $("#Fila1").prepend(`<div class="col-4" id="idCargo">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="id_cargo" type="text" id="id_cargo" readOnly>
              <span id="sid_cargo"></span>
              <label for="id_cargo" class="form-label">ID del Cargo</label>
            </div>`);

    // Actualizar elementosCargo para incluir el nuevo campo
    elementosCargo.id_cargo = $('#id_cargo');

    // Usar los datos directamente de DataTable (más confiable)
    $("#id_cargo").val(datosFila.id_cargo);
    $("#nombre_cargo").val(capitalizarTexto(datosFila.nombre_cargo));

    if (accion == 0) {
        $("#modalCargoTitle").text("Modificar Cargo")
        $("#enviarCargo").text("Modificar");
    } else {
        $("#nombre_cargo").prop('readOnly', true);
        $("#modalCargoTitle").text("Eliminar Cargo")
        $("#enviarCargo").text("Eliminar");
    }
    
    // Habilitar el botón inmediatamente para Modificar/Eliminar ya que los datos vienen pre-validados
    $('#enviarCargo').prop('disabled', false);
    $("#modalCargo").modal("show");
}

function limpiarCargo() {
    SistemaValidacion.limpiarValidacion(elementosCargo);
    
    $("#idCargo").remove();
    $("#id_cargo").val("");
    $("#nombre_cargo").val("");
    $("#nombre_cargo").prop('readOnly', false);

    // Deshabilitar el botón al limpiar (se habilitará automáticamente cuando los campos sean válidos)
    $('#enviarCargo').prop('disabled', true);
}

function validarEnvioCargo() {
    return SistemaValidacion.validarFormulario(elementosCargo);
}