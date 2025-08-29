$(document).ready(function () {
    consultar();
    capaValidar();

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
                }
                break;
            case "Eliminar":
                if (validarKeyUp(/^[A-Z0-9]{1,2}[A-Z0-9]{1,2}[0-9]{4}[0-9]{8}$/, $("#id_cargo"), $("#sid_cargo"), "") == 1) {
                    confirmacion = await confirmarAccion("Se eliminará un Cargo", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        var datos = new FormData();
                        datos.append('eliminar', 'eliminar');
                        datos.append('id_cargo', $("#id_cargo").val());
                        enviaAjax(datos);
                        envio = true;
                    }
                }
                break;
            default:
                mensajes("error", null, "Acción desconocida: " + $(this).text());
        }
        if (envio) {
            $('#enviar').prop('disabled', true);
        } else {
            $('#enviar').prop('disabled', false);
        }

        if (!confirmacion) {
            $('#enviar').prop('disabled', false);
        } else {
            $('#enviar').prop('disabled', true);
        }
    });

    $("#btn-registrar-cargo").on("click", function () {
        limpiarCargo();
        $("#modalCargoTitle").text("Registrar Cargo");
        $("#enviarCargo").text("Registrar");
        $("#modalCargo").modal("show");
    });

    $("#btn-consultar-eliminados").on("click", function () {
        consultarEliminadas();
        $("#modalEliminadas").modal("show");
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
    $("#nombre_cargo").on("keyup", function () {
        validarKeyUp(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{3,45}$/, $("#nombre_cargo"), $("#snombre_cargo"), "");
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
};

function iniciarTablaEliminadas(arreglo) {
    if ($.fn.DataTable.isDataTable('#tablaEliminadas')) {
        $('#tablaEliminadas').DataTable().destroy();
    }

    $('#tablaEliminadas').DataTable({
        data: arreglo,
        columns: [
            { data: 'id_cargo' },
            { data: 'nombre_cargo' },
            {
                data: null,
                render: function () {
                    return `<button onclick="restaurarCargo(this)" class="btn btn-success restaurar">
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
            { data: 'id_cargo' },
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

function restaurarCargo(boton) {
    var linea = $(boton).closest('tr');
    var id = $(linea).find('td:eq(0)').text();

    Swal.fire({
        title: '¿Restaurar Cargo?',
        text: "¿Está seguro que desea restaurar esta cargo?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, restaurar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var datos = new FormData();
            datos.append('restaurar', 'restaurar');
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
                    mensajes("error", null, "Error", "No se pudo restaurar el cargo");
                }
            });
        }
    });
}

function rellenar(pos, accion) {
    limpiarCargo();
    linea = $(pos).closest('tr');

    $("#idCargo").remove();
    $("#Fila1").prepend(`<div class="col-4" id="idCargo">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="id_cargo" type="text" id="id_cargo" readOnly>
              <span id="sid_cargo"></span>
              <label for="id_cargo" class="form-label">ID del Cargo</label>
            </div>`);

    $("#id_cargo").val($(linea).find("td:eq(0)").text());
    $("#nombre_cargo").val($(linea).find("td:eq(1)").text());

    $("#id_cargo").prop('readOnly', true);

    if (accion == 0) {
        $("#modalCargoTitle").text("Modificar Cargo")
        $("#enviarCargo").text("Modificar");
    }
    else {
        $("#nombre_cargo").prop('readOnly', true);
        $("#modalCargoTitle").text("Eliminar Cargo")
        $("#enviarCargo").text("Eliminar");

    }
    $('#enviarCargo').prop('disabled', false);
    $("#modalCargo").modal("show");
}

function limpiarCargo() {
    $("#idCargo").remove();
    $("#id_cargo").val("");
    $("#nombre_cargo").val("");

    $("#nombre_cargo").prop('readOnly', false);

    $('#enviar').prop('disabled', false);
}

function validarEnvioCargo() {

    if (validarKeyUp(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{3,45}$/, $("#nombre_cargo"), $("#snombre_cargo"), "") == 0) {
        mensajes("error", null, "El nombre del cargo debe tener al menos 3 caracteres");
        return false;
    }
    return true;
}
