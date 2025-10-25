// Definir variables globales para validación
const regexValidacion = {
    nombre: /^[0-9a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ\s\-.]{4,45}$/,
    id: /^[A-Z0-9]{3,5}[A-Z0-9]{3}[0-9]{8}[0-9]{0,6}[0-9]{0,2}$/
};

const mensajesError = {
    nombre: {
        requerido: "El nombre de la dependencia es obligatorio",
        formato: "Solo se permiten letras, números, espacios, guiones y puntos",
        longitud: "El nombre debe tener entre 4 y 45 caracteres"
    },
    ente: {
        requerido: "Debe seleccionar un ente"
    },
    id_dependencia: {
        requerido: "El ID de dependencia es obligatorio",
        formato: "Solo se permiten números (máximo 11 dígitos)"
    }
};

$(document).ready(function () {
    consultar();
    consultarEnte();
    registrarEntrada();
    capaValidar();

    $("#enviar").on("click", async function () {
        var confirmacion = false;
        var envio = false;

        // Validar todos los campos antes de proceder
        const esValido = validarTodosCampos();

        if (!esValido) {
            mensajes("error", 5000, "Verifique los campos", "Existen errores en el formulario");
            return;
        }

        switch ($(this).text()) {
            case "Registrar":
                confirmacion = await confirmarAccion("Se registrará una Dependencia", "¿Está seguro de realizar la acción?", "question");
                if (confirmacion) {
                    var datos = new FormData();
                    datos.append('registrar', 'registrar');
                    datos.append('nombre', $("#nombre").val());
                    datos.append('ente', $("#ente").val());
                    enviaAjax(datos);
                    envio = true;
                }
                break;
            case "Modificar":
                confirmacion = await confirmarAccion("Se modificará una Dependencia", "¿Está seguro de realizar la acción?", "question");
                if (confirmacion) {
                    var datos = new FormData();
                    datos.append('modificar', 'modificar');
                    datos.append('id_dependencia', $("#id_dependencia").val());
                    datos.append('nombre', $("#nombre").val());
                    datos.append('ente', $("#ente").val());
                    enviaAjax(datos);
                    envio = true;
                }
                break;
            case "Eliminar":
                confirmacion = await confirmarAccion("Se eliminará una Dependencia", "¿Está seguro de realizar la acción?", "warning");
                if (confirmacion) {
                    var datos = new FormData();
                    datos.append('eliminar', 'eliminar');
                    datos.append('id_dependencia', $("#id_dependencia").val());
                    enviaAjax(datos);
                    envio = true;
                }
                break;
            default:
                mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text());
        }

        if (envio) {
            $('#enviar').prop('disabled', true);
        }
    });

    $("#btn-registrar").on("click", function () {
        limpia();
        $("#idDependencia").remove();
        $("#modalTitleId").text("Registrar Dependencia");
        $("#enviar").text("Registrar");
        $("#modal1").modal("show");

        // Habilitar solo el primer campo
        $("#ente").prop('disabled', false);
        $("#nombre").prop('disabled', true);
        $("#enviar").prop('disabled', true);
    });

    $("#btn-consultar-eliminados").on("click", function () {
        consultarEliminadas();
        $("#modalEliminadas").modal("show");
    });

});

function consultar() {
    var datos = new FormData();
    datos.append('consultar', 'consultar');
    enviaAjax(datos);
}

function consultarEliminadas() {
    var datos = new FormData();
    datos.append('consultar_eliminadas', 'consultar_eliminadas');
    enviaAjax(datos);
}

function consultarEnte() {
    var datos = new FormData();
    datos.append('cargar_ente', 'cargar_ente');
    enviaAjax(datos);
}

function registrarEntrada() {
    var datos = new FormData();
    datos.append('entrada', 'entrada');
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
        timeout: 10000,
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

                } else if (lee.resultado == "cargar_ente") {
                    selectEnte(lee.datos);

                } else if (lee.resultado == "entrada") {

                } else if (lee.resultado == "error") {
                    $("#modal1").modal("hide");
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
    // Validación del campo nombre
    $("#nombre").on("keypress", function (e) {
        validarKeyPress(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.\b]*$/, e);
    });

    $("#nombre").on("keyup", function () {
        validarCampo($(this), $("#snombre"), regexValidacion.nombre, mensajesError.nombre);
        habilitarSiguienteCampo($(this), "#enviar");
    });

    $("#nombre").on("blur", function () {
        validarCampo($(this), $("#snombre"), regexValidacion.nombre, mensajesError.nombre);
    });

    // Validación del select ente
    $('#ente').on('change', function () {
        validarSelect($(this), $("#sente"), mensajesError.ente);
        habilitarSiguienteCampo($(this), "#nombre");
    });

    // Validación del ID de dependencia (si existe)
    $(document).on('keyup', '#id_dependencia', function () {
        validarCampo($(this), $("#sid_dependencia"), regexValidacion.id, mensajesError.id_dependencia);
    });
}

function validarCampo(campo, span, regex, mensajesError) {
    const valor = campo.val().trim();

    if (!valor) {
        campo.removeClass('is-valid');
        campo.addClass('is-invalid');
        span.text(mensajesError.requerido);
        span.removeClass('is-valid');
        span.addClass('is-invalid');
        return false;
    }

    if (!regex.test(valor)) {
        campo.removeClass('is-valid');
        campo.addClass('is-invalid');

        if (valor.length < 4 || valor.length > 45) {
            span.text(mensajesError.longitud);
        } else {
            span.text(mensajesError.formato);
        }

        return false;
    }

    campo.removeClass('is-invalid');
    campo.addClass('is-valid');
    span.removeClass('is-invalid');
    span.addClass('is-valid');
    span.text('');
    return true;
}

function validarSelect(select, span, mensajesError) {
    if (select.val() === 'default' || !select.val()) {
        select.removeClass('is-valid');
        select.addClass('is-invalid');
        span.text(mensajesError.requerido);
        return false;
    }

    select.removeClass('is-invalid');
    select.addClass('is-valid');
    span.text('');
    return true;
}

function habilitarSiguienteCampo(campoActual, selectorSiguiente) {
    if (campoActual.hasClass('is-valid')) {
        $(selectorSiguiente).prop('disabled', false);

        // Si es el select ente, habilitar el campo nombre
        if (campoActual.attr('id') === 'ente') {
            $("#nombre").prop('disabled', false);
        }
    } else {
        $(selectorSiguiente).prop('disabled', true);

        // Si es el select ente, deshabilitar también el campo nombre
        if (campoActual.attr('id') === 'ente') {
            $("#nombre").prop('disabled', true);
            $("#enviar").prop('disabled', true);
        }
    }
}

function validarTodosCampos() {
    let esValido = true;

    // Validar ente
    if ($("#ente").is(":visible") && !validarSelect($("#ente"), $("#sente"), mensajesError.ente)) {
        esValido = false;
    }

    // Validar nombre
    if ($("#nombre").is(":visible") && !validarCampo($("#nombre"), $("#snombre"), regexValidacion.nombre, mensajesError.nombre)) {
        esValido = false;
    }

    // Validar ID de dependencia (si existe)
    if ($("#id_dependencia").length && !validarCampo($("#id_dependencia"), $("#sid_dependencia"), regexValidacion.id, mensajesError.id_dependencia)) {
        esValido = false;
    }

    return esValido;
}

function validarenvio() {
    return validarTodosCampos();
}

function selectEnte(arreglo) {
    $("#ente").empty();
    $("#ente").append(new Option('Seleccione un Ente', 'default'));

    arreglo.forEach(item => {
        $("#ente").append(new Option(item.nombre, item.id));
    });

    // Reiniciar estado de validación
    $("#ente").removeClass('is-valid is-invalid');
    $("#sente").text('');
}

function crearDataTable(arreglo) {
    console.log(arreglo);
    if ($.fn.DataTable.isDataTable('#tabla1')) {
        $('#tabla1').DataTable().destroy();
    }
    $('#tabla1').DataTable({
        data: arreglo,
        columns: [
            { data: 'id' },
            { data: 'nombre' },
            { data: 'ente' },
            {
                data: null, render: function () {
                    const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button onclick="rellenar(this, 1)" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>`;
                    return botones;
                }
            }],
        order: [
            [2, 'asc']
        ],
        language: {
            url: idiomaTabla
        }
    });
}

function iniciarTablaEliminadas(arreglo) {
    if ($.fn.DataTable.isDataTable('#tablaEliminadas')) {
        $('#tablaEliminadas').DataTable().destroy();
    }

    $('#tablaEliminadas').DataTable({
        data: arreglo,
        columns: [
            { data: 'id' },
            { data: 'nombre' },
            { data: 'ente' },
            {
                data: null,
                render: function () {
                    return `<button onclick="reactivarDependencia(this)" class="btn btn-success reactivar">
                            <i class="fa-solid fa-recycle"></i>
                            </button>`;
                }
            }
        ],
        order: [
            [2, 'asc']
        ],
        language: {
            url: idiomaTabla,
        }
    });
    ConsultarPermisos();
}

function limpia() {
    // Limpiar y resetear todos los campos
    $("input, select").each(function () {
        $(this).val("");
        $(this).removeClass("is-valid is-invalid");
        $(this).prop("readOnly", false);
        $(this).prop("disabled", false);
    });

    $("span.invalid-feedback").text("");

    // Restablecer estado inicial de habilitación
    $("#ente").val('default');
    $("#ente").prop('disabled', false);
    $("#nombre").prop('disabled', true);
    $("#enviar").prop('disabled', true);

    // Si existe el campo ID, eliminarlo
    $("#idDependencia").remove();
}

function rellenar(pos, accion) {
    limpia();

    let linea = $(pos).closest('tr');

    $("#idDependencia").remove();
    $("#Fila1").prepend(`<div class="col-4" id="idDependencia">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="id_dependencia" type="text" id="id_dependencia" readOnly>
              <span id="sid_dependencia"></span>
              <label for="id_dependencia" class="form-label">ID de la Dependencia</label>
            </div>`);

    $("#id_dependencia").val($(linea).find("td:eq(0)").text());
    $("#nombre").val($(linea).find("td:eq(1)").text());
    buscarSelect("#ente", $(linea).find("td:eq(2)").text(), "text");

    if (accion == 0) {
        $("#modalTitleId").text("Modificar Dependencia")
        $("#enviar").text("Modificar");
        // Para modificar, habilitar todos los campos
        $("#nombre").prop('disabled', false);
        $("#ente").prop('disabled', false);
    } else {
        $("#id_dependencia").prop("readOnly", true);
        $("#nombre").prop("readOnly", true);
        $('#ente').prop('disabled', true);
        $("#modalTitleId").text("Eliminar Dependencia")
        $("#enviar").text("Eliminar");
    }

    // Validar campos después de rellenar
    setTimeout(function () {
        if ($("#id_dependencia").length) {
            validarCampo($("#id_dependencia"), $("#sid_dependencia"), regexValidacion.id, mensajesError.id_dependencia);
        }
        validarCampo($("#nombre"), $("#snombre"), regexValidacion.nombre, mensajesError.nombre);
        validarSelect($("#ente"), $("#sente"), mensajesError.ente);

        // Habilitar el botón si todos los campos son válidos
        if (validarTodosCampos()) {
            $('#enviar').prop('disabled', false);
        }
    }, 100);

    $("#modal1").modal("show");
}

// Funciones auxiliares preexistentes
function validarKeyPress(regex, e) {
    var key = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (!regex.test(key)) {
        e.preventDefault();
        return false;
    }
}

function validarKeyUp(regex, campo, span, mensaje) {
    if (regex.test(campo.val())) {
        campo.removeClass('is-invalid');
        campo.addClass('is-valid');
        span.text('');
        return 1;
    } else {
        campo.removeClass('is-valid');
        campo.addClass('is-invalid');
        span.text(mensaje);
        return 0;
    }
}

function estadoSelect(select, span, mensaje, estado) {
    if (estado === 1) {
        $(select).removeClass('is-invalid');
        $(select).addClass('is-valid');
        $(span).text('');
    } else {
        $(select).removeClass('is-valid');
        $(select).addClass('is-invalid');
        $(span).text(mensaje);
    }
}

function buscarSelect(select, valor, tipo) {
    if (tipo === "text") {
        $(select).find("option").filter(function () {
            return $(this).text() === valor;
        }).prop('selected', true);
    } else {
        $(select).val(valor);
    }
    // Disparar evento change para activar validaciones
    $(select).trigger('change');
}

function reactivarDependencia(boton) {
    var linea = $(boton).closest('tr');
    var id = $(linea).find('td:eq(0)').text();

    Swal.fire({
        title: 'Reactivar Dependencia?',
        text: "¿Está seguro que desea reactivar esta dependencia?",
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
            datos.append('id_dependencia', id);

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
                            mensajes("success", null, "Dependencia restaurado", lee.mensaje);
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
                    mensajes("error", null, "Error", "No se pudo reactivar la dependencia");
                }
            });
        }
    });
}