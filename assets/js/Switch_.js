$(document).ready(function () {

    consultar();
    registrarEntrada();
    capaValidar();
    ConsultarPermisos();

    $("#enviar").on("click", async function () {

        $('#enviar').prop('disabled', false);

        let confirmacion = false;

        switch ($(this).text()) {

            case "Registrar":

                if (validarenvio()) {

                    confirmacion = await confirmarAccion("Se registrará un Switch", "¿Está seguro?", "question");

                    if (confirmacion) {

                        var datos = new FormData();
                        datos.append('registrar', 'registrar');
                        datos.append('codigo_bien', $("#codigo_bien").val());
                        datos.append('cantidad_puertos', $("#cantidad_puertos").val());
                        datos.append('serial_switch', $("#serial_switch").val());

                        enviaAjax(datos);

                    }
                }

            break;

            case "Modificar":

                if (validarenvio()) {

                    confirmacion = await confirmarAccion("Se modificará el Switch", "¿Está seguro?", "question");

                    if (confirmacion) {

                        var datos = new FormData();
                        datos.append('modificar', 'modificar');
                        datos.append('codigo_bien', $("#codigo_bien").val());
                        datos.append('cantidad_puertos', $("#cantidad_puertos").val());
                        datos.append('serial_switch', $("#serial_switch").val());

                        enviaAjax(datos);

                    }

                }

            break;

            case "Eliminar":

                if (validarenvio()) {

                    confirmacion = await confirmarAccion("Se eliminará el Switch", "¿Está seguro?", "warning");

                    if (confirmacion) {

                        var datos = new FormData();
                        datos.append('eliminar', 'eliminar');
                        datos.append('codigo_bien', $("#codigo_bien").val());

                        enviaAjax(datos);

                    }

                }

            break;

            default:

                mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text());

        }

        if (!validarenvio()) {
            $('#enviar').prop('disabled', false);
        } else {
            $('#enviar').prop('disabled', true);
        }

        if (!confirmacion) {
            $('#enviar').prop('disabled', false);
        }

    });

    $("#btn-registrar").on("click", function () { 

        limpia();
        actualizarSelectBien();
        $("#modalTitleId").text("Registrar Switch");
        $("#enviar").text("Registrar");
        $("#enviar").prop('disabled', false);
        $("#enviar").attr("title", "Registrar Switch");
        $("#modal1").modal("show");

    }); 

    $("#btn-consultar-eliminados").on("click", function () {
        consultarEliminadas();
        $("#modalEliminadas").modal("show");
    });

    $('#codigo_bien').select2({
		dropdownParent: $('#modal1'),
        width: '100%',
	});
});

function vistaPermiso(permisos = null) {

    if (!permisos || Object.keys(permisos).length == 0) {

        $('.modificar').remove();
        $('.eliminar').remove();

    } else {

        if (permisos['switch']['modificar']['estado'] == '0') {
            $('.modificar').remove();
        }

        if (permisos['switch']['eliminar']['estado'] == '0') {
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
        timeout: 10000, //tiempo maximo de espera por la respuesta del servidor

        success: function (respuesta) {

            console.log(respuesta);

            try {

                var lee = JSON.parse(respuesta);

                if (lee.resultado == "registrar") {

                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    actualizarSelectBien();
                    consultar();

                } else if (lee.resultado == "consultar") {

                    crearDataTable(lee.datos);

                } else if (lee.resultado == "modificar") {

                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    actualizarSelectBien();
                    consultar();

                } else if (lee.resultado == "eliminar") {
                    
                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    actualizarSelectBien();
                    consultar();

                } else if (lee.resultado == "consultar_eliminadas") {

                    TablaEliminados(lee.datos);

                } else if (lee.resultado == "restaurar") {

                    mensajes("success", 10000, lee.mensaje, null);
                    consultarEliminadas();
                    actualizarSelectBien();
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

    $("#codigo_bien").on("change", function () {
        if ($(this).val() == "default") {
            estadoSelect(this, "#scodigo_bien", "Debe seleccionar un Código de Bien", 0);
        } else {
            estadoSelect(this, "#scodigo_bien", "", 1);
        }
    });

    $("#cantidad_puertos").on("change", function () {
        if (!["8", "10", "16", "24", "28", "48", "52"].includes($(this).val())) {
            estadoSelect(this, "#scantidad_puertos", "Debe seleccionar una cantidad válida", 0);
        } else {
            estadoSelect(this, "#scantidad_puertos", "", 1);
        }
    });

    $("#serial_switch").on("keypress", function (e) {
        validarKeyPress(/^[0-9a-zA-ZáéíóúüñÑçÇ\/\-.,# ]*$/, e);
    });
    $("#serial_switch").on("keyup", function () {
        validarKeyUp(
            /^[0-9a-zA-ZáéíóúüñÑçÇ\/\-.,# ]{3,45}$/,
            $(this),
            $("#sserial_switch"),
            "El serial debe tener de 3 a 45 caracteres y solo caracteres válidos (/-.,#)"
        );
    });

}

function validarSelect() {

    let bool = null;
    let mensaje = "";

    const validar = { bool, mensaje };

    if ($('#codigo_bien').val() === 'default') {

        estadoSelect('#codigo_bien', '#scodigo_bien', "Seleccione un Bien", 0);

        validar.bool = 0;
        validar.mensaje = "Seleccione un Bien";

    } else {

        estadoSelect('#codigo_bien', '#scodigo_bien', "", 1);

        validar.bool = 1;
        validar.mensaje = "";

    }

    return validar;
}

function validarenvio() {
    let valido = true;

    if ($("#codigo_bien").val() == "default") {
        estadoSelect("#codigo_bien", "#scodigo_bien", "Debe seleccionar un Código de Bien", 0);
        mensajes("error", 10000, "Verifica", "Debe seleccionar un Código de Bien");
        valido = false;
    }

    if (!["8", "10", "16", "24", "28", "48", "52"].includes($("#cantidad_puertos").val())) {
        estadoSelect("#cantidad_puertos", "#scantidad_puertos", "Debe seleccionar una cantidad válida", 0);
        mensajes("error", 10000, "Verifica", "Debe seleccionar una cantidad válida");
        valido = false;
    }

    if (
        validarKeyUp(
            /^[0-9a-zA-ZáéíóúüñÑçÇ\/\-.,# ]{3,45}$/,
            $("#serial_switch"),
            $("#sserial_switch"),
            "El serial debe tener de 3 a 45 caracteres y solo caracteres válidos (/-.,#)"
        ) == 0
    ) {
        mensajes("error", 10000, "Verifica", "El serial debe tener de 3 a 45 caracteres y solo caracteres válidos (/-.,#)");
        valido = false;
    }

    return valido;
}

function crearDataTable(arreglo) {

    console.log(arreglo);

    if ($.fn.DataTable.isDataTable('#tabla1')) {
        $('#tabla1').DataTable().destroy();
    }

    $('#tabla1').DataTable({
        data: arreglo,
        columns: [
            { data: 'codigo_bien' },
            { data: 'cantidad_puertos' },
            { data: 'serial' },
            {
                data: null, render: function () {
                    const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar" title="Modificar Switch"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button onclick="rellenar(this, 1)" class="btn btn-danger eliminar" title="Eliminar Switch"><i class="fa-solid fa-trash"></i></button>`;
                    return botones;
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

function actualizarSelectBien() {
    $.ajax({
        url: '', 
        type: 'POST',
        data: { consultar_bien: 'consultar_bien' },
        success: function(respuesta) {
            try {
                let datos = JSON.parse(respuesta);
                if (Array.isArray(datos)) {
                    let $select = $("#codigo_bien");
                    $select.empty();
                    $select.append('<option selected value="default" disabled>Seleccione un Código de Bien</option>');
                    datos.forEach(function(bien) {
                        $select.append(`<option value="${bien.codigo_bien}">${bien.codigo_bien} - ${bien.descripcion}</option>`);
                    });
                }
            } catch (e) {

                mensajes("error", 5000, "Error", "No se pudo actualizar el listado de bienes.");

            }
        },
        error: function() {
            mensajes("error", 5000, "Error", "No se pudo conectar con el servidor.");
        }
    });
}

function consultarEliminadas() {

    var datos = new FormData();
    datos.append('consultar_eliminadas', 'consultar_eliminadas');

    enviaAjax(datos);
}

function TablaEliminados(arreglo) {

    if ($.fn.DataTable.isDataTable('#tablaEliminadas')) {
        $('#tablaEliminadas').DataTable().destroy();
    }

    $('#tablaEliminadas').DataTable({
        data: arreglo,
        columns: [
            {
                data: null, render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'codigo_bien' },
            { data: 'cantidad_puertos' },
            { data: 'serial' },
            {
                data: null,
                render: function () {
                    return `<button onclick="restaurarSwitch(this)" class="btn btn-success" title="Restaurar Switch Eliminado">
                                <i class="fa-solid fa-recycle"></i>
                            </button>`;
                }
            }
        ],
        language: {
            url: idiomaTabla,
        }
    });
}

async function restaurarSwitch(boton) {

    var confirmacion = false;
    var linea = $(boton).closest('tr');
    var codigo_bien = $(linea).find('td:eq(1)').text();

    confirmacion = await confirmarAccion("¿Restaurar Switch?", "¿Está seguro que desea restaurar este Switch?", "question");

    if (confirmacion) {

        var datos = new FormData();
        datos.append('restaurar', 'restaurar');
        datos.append('codigo_bien', codigo_bien);
        enviaAjax(datos);

    }

}

function limpia() {

    $("#codigo_bien").val("default");
    $("#cantidad_puertos").val("default");
    $("#serial_switch").val("");

    $('#codigo_bien').prop('disabled', false);
    $('#cantidad_puertos').prop('disabled', false);
    $('#serial_switch').prop('disabled', false);

    $("#codigo_bien").removeClass("is-valid is-invalid");
    $("#cantidad_puertos").removeClass("is-valid is-invalid");
    $("#serial_switch").removeClass("is-valid is-invalid");

    $("#codigo_bien option.opcion_temporal").remove();
}

function rellenar(pos, accion) {

    limpia();
    
    let linea = $(pos).closest('tr');
    let codigoBien = $(linea).find("td:eq(0)").text().trim();

    if ($("#codigo_bien option[value='" + codigoBien + "']").length === 0) {
        $("#codigo_bien").append(
            $("<option>", {
                value: codigoBien,
                text: codigoBien,
                class: "opcion_temporal"
            })
        );
    }

    $("#codigo_bien").val(codigoBien);
    
    $("#cantidad_puertos").val($(linea).find("td:eq(1)").text());
    $("#serial_switch").val($(linea).find("td:eq(2)").text());

    if (accion == 0) {

        $('#codigo_bien').prop('disabled', true);
        $('#cantidad_puertos').prop('disabled', false);
        $('#serial_switch').prop('disabled', false);
        $("#modalTitleId").text("Modificar Switch");
        $("#enviar").attr("title", "Modificar Switch");
        $("#enviar").text("Modificar");

    } else {

        $('#codigo_bien').prop('disabled', true);
        $('#cantidad_puertos').prop('disabled', true);
        $('#serial_switch').prop('disabled', true);
        $("#modalTitleId").text("Eliminar Switch");
        $("#enviar").attr("title", "Eliminar Switch");
        $("#enviar").text("Eliminar");
    }

    $('#enviar').prop('disabled', false);
    $("#modal1").modal("show");
}
