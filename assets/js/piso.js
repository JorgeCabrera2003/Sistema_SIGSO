$(document).ready(function () {
    consultar();
    registrarEntrada();
    capaValidar();

    $("#enviar").on("click", async function () {

        $('#enviar').prop('disabled', false);
        let confirmacion = false;

        switch ($(this).text()) {
            case "Registrar":
                if (validarenvio()) {

                    confirmacion = await confirmarAccion("Se registrará un Piso", "¿Está seguro?", "question");

                    if (confirmacion) {
                        var datos = new FormData();
                        datos.append('registrar', 'registrar');
                        datos.append('tipo_piso', $("#tipo_piso").val());
                        datos.append('nro_piso', $("#nro_piso").val());
                        enviaAjax(datos);
                    }
                }
                break;
            case "Modificar":
                if (validarenvio()) {
                    confirmacion = await confirmarAccion("Se modificará un Piso", "¿Está seguro?", "question");
                    if (confirmacion) {
                        var datos = new FormData();
                        datos.append('modificar', 'modificar');
                        datos.append('id_piso', $("#id_piso").val());
                        datos.append('tipo_piso', $("#tipo_piso").val());
                        datos.append('nro_piso', $("#nro_piso").val());
                        enviaAjax(datos);
                    }
                }
                break;
            case "Eliminar":
                if (validarenvio()) {
                    confirmacion = await confirmarAccion("Se eliminará un Piso", "¿Está seguro?", "warning");
                    if (confirmacion) {
                        var datos = new FormData();
                        datos.append('eliminar', 'eliminar');
                        datos.append('id_piso', $("#id_piso").val());
                        enviaAjax(datos);
                    }
                }
                break;

            default:
                mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text());;
        }
        if (!validarenvio()) {
            $('#enviar').prop('disabled', false);
        } else {
            $('#enviar').prop('disabled', true)
        };

        if (!confirmacion) {
            $('#enviar').prop('disabled', false);
        }
    });

    $("#btn-registrar").on("click", function () { //<---- Evento del Boton Registrar
        limpia();
        $("#modalTitleId").text("Registrar Piso");
        $("#enviar").text("Registrar");
        $("#modal1").modal("show");
    }); //<----Fin Evento del Boton Registrar

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

    $('#tipo_piso').on('change blur input focusout mouseleave', function () {

        const obj = validarSelect();

        if (obj.bool === 0) { }
    });

    $('#nro_piso').on('change blur input focusout mouseleave', function () {

        const obj = validarSelect();

        if (obj.bool === 0) { }
    });
}

function validarSelect() {

    let bool = null;
    let mensaje = "";

    const validar = { bool, mensaje };

    if ($('#tipo_piso').val() === 'default') {

        estadoSelect('#tipo_piso', '#stipo_piso', "Seleccione un tipo de Piso", 0);
        estadoSelect('#nro_piso', '#snro_piso', "", 0);
        validar.bool = 0;
        validar.mensaje = "Seleccione un tipo de Piso";

    } else if ($('#nro_piso').val() === 'default') {

        estadoSelect('#nro_piso', '#snro_piso', "Seleccione un número de Piso", 0);
        validar.bool = 0;
        validar.mensaje = "Seleccione un número de Piso";
    }

    else if ($('#nro_piso').val() === '0' && $('#tipo_piso').val() != 'Planta Baja') {

        estadoSelect('#nro_piso', '#snro_piso', "", 0);
        estadoSelect('#tipo_piso', '#stipo_piso', "Solo Planta Baja empieza en 0", 0);

        validar.bool = 0;
        validar.mensaje = "Solo Planta Baja empieza en 0";

    } else if ($('#nro_piso').val() != '0' && $('#tipo_piso').val() === 'Planta Baja') {

        estadoSelect('#nro_piso', '#snro_piso', "", 0);
        estadoSelect('#tipo_piso', '#stipo_piso', "Solo Planta Baja empieza en 0", 0);

        validar.bool = 0;
        validar.mensaje = "Solo Planta Baja empieza en 0";

    } else {

        estadoSelect('#nro_piso', '#snro_piso', "", 1);
        estadoSelect('#tipo_piso', '#stipo_piso', "", 1);

        validar.bool = 1;
        validar.mensaje = "";

    }

    return validar;
}

function validarenvio() {

    const obj = validarSelect();

    if (obj.bool == 0) {
        mensajes("error", 10000, "Verifica", obj.mensaje);
        return false;
    }
    return true;
}

function vistaPermiso(permisos = null) {

    if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {

        $('.modificar').remove();
        $('.eliminar').remove();

    } else {

        if (permisos['piso']['modificar']['estado'] == '0') {
            $('.modificar').remove();
        }

        if (permisos['piso']['eliminar']['estado'] == '0') {
            $('.eliminar').remove();
        }
    }
};

function crearDataTable(arreglo) {

    console.log(arreglo);
    if ($.fn.DataTable.isDataTable('#tabla1')) {
        $('#tabla1').DataTable().destroy();
    }
    $('#tabla1').DataTable({
        data: arreglo,
        columns: [
            { data: 'id_piso' },
            { data: 'tipo_piso' },
            { data: 'nro_piso' },
            {
                data: null, render: function () {
                    const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar"><i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" class="btn btn-danger eliminar"><i class="fa-solid fa-trash"></i></button>`;
                    return botones;
                }
            }],
        order: [
            [1, 'asc'],
            [2, 'asc']
        ],
        language: {
            url: idiomaTabla,
        }
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
            { data: 'id_piso' },
            { data: 'tipo_piso' },
            { data: 'nro_piso' },
            {
                data: null,
                render: function () {
                    return `<button onclick="restaurarPiso(this)" class="btn btn-success restaurar">
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

function restaurarPiso(boton) {
    var linea = $(boton).closest('tr');
    var id = $(linea).find('td:eq(0)').text();

    Swal.fire({
        title: '¿Restaurar Marca?',
        text: "¿Está seguro que desea restaurar esta marca?",
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
            datos.append('id_piso', id);

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
                            mensajes("success", null, "Piso restaurado", lee.mensaje);
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
                    mensajes("error", null, "Error", "No se pudo restaurar el piso");
                }
            });
        }
    });
}

function limpia() {
    $("#idPiso").remove();

    $("#tipo_piso").removeClass("is-valid is-invalid");
    $("#tipo_piso option:first-child").prop('selected', true);
    $("#stipo_piso").val("");

    $("#nro_piso").removeClass("is-valid is-invalid");
    $("#nro_piso option:first-child").prop('selected', true);
    $("#snro_piso").val("");


    $('#tipo_piso').prop('disabled', false);
    $('#nro_piso').prop('disabled', false);
    $('#enviar').prop('disabled', false);
}


function rellenar(pos, accion) {
    limpia();

    linea = $(pos).closest('tr');

    $("#idPiso").remove();
    $("#Fila1").prepend(`<div class="col-4" id="idPiso">
            <div class="form-floating mb-3">
              <input placeholder="" class="form-control" name="id_piso" type="text" id="id_piso" readOnly>
              <span id="sid_piso"></span>
              <label for="id_piso" class="form-label">ID del Piso</label>
            </div>`);


    $("#id_piso").val($(linea).find("td:eq(0)").text());

    buscarSelect('#tipo_piso', $(linea).find("td:eq(1)").text(), "value");
    buscarSelect('#nro_piso', $(linea).find("td:eq(2)").text(), "value");


    if (accion == 0) {
        $('#tipo_piso').prop('disabled', false);
        $('#nro_piso').prop('disabled', false);
        $("#modalTitleId").text("Modificar Piso")
        $("#enviar").text("Modificar");
    }
    else {

        $('#tipo_piso').prop('disabled', true);
        $('#nro_piso').prop('disabled', true);
        $("#modalTitleId").text("Eliminar Piso")
        $("#enviar").text("Eliminar");
    }
    $('#enviar').prop('disabled', false);
    $("#modal1").modal("show");
}