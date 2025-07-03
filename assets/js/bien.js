$(document).ready(function () {
    consultar();
    registrarEntrada();
    capaValidar();
    consultarOficina();
    consultarEmpleado();
    consultarMarca();
    consultarTipoBien();

    // Inicializa Select2 al cargar la pagina
    $("#id_oficina").select2({
        dropdownParent: $('#modal1'),
        width: '100%'
    });
    $("#cedula_empleado").select2({
        dropdownParent: $('#modal1'),
        width: '100%'
    });
    $("#id_tipo_bien").select2({
        dropdownParent: $('#modal1'),
        width: '100%'
    });
    $("#id_marca").select2({
        dropdownParent: $('#modal1'),
        width: '100%'
    });
    $("#estado").select2({
        dropdownParent: $('#modal1'),
        width: '100%'
    });

    // Inicializa Select2 para unidad de equipo en el carrusel
    $("#id_unidad_equipo").select2({
        dropdownParent: $('#modal1'),
        width: '100%'
    });

    // Mostrar checkbox/carrusel solo en Registrar
    $("#btn-registrar").on("click", function () {
        limpia();
        $("#codigo_bien").parent().parent().show();
        $("#modalTitleId").text("Registrar Bien");
        $("#enviar").text("Registrar");
        // Mostrar checkbox y ocultar carrusel al abrir para registrar
        $("#row-registro-equipo").show();
        $("#checkRegistrarEquipo").prop("checked", false);
        $("#carruselEquipo").hide();
        consultarUnidadEquipo();
        $("#modal1").modal("show");
    });

    // Ocultar checkbox/carrusel en Modificar/Eliminar
    function ocultarEquipoOpcional() {
        $("#row-registro-equipo").hide();
        $("#carruselEquipo").hide();
        $("#checkRegistrarEquipo").prop("checked", false);
        // Limpia campos del carrusel
        $("#serial_equipo").val('');
        $("#tipo_equipo").val('');
    }

    // Detectar cambio de acción en el modal
    $("#modal1").on("show.bs.modal", function () {
        if ($("#enviar").text() !== "Registrar") {
            ocultarEquipoOpcional();
        }
    });

    // Mostrar/ocultar carrusel según el checkbox
    $("#checkRegistrarEquipo").on("change", function () {
        if ($(this).is(":checked")) {
            $("#carruselEquipo").show();
        } else {
            $("#carruselEquipo").hide();
            // Limpia campos del carrusel
            $("#serial_equipo").val('');
            $("#tipo_equipo").val('');
        }
    });

    $("#enviar").on("click", async function () {

        var confirmacion = false;
        var envio = false;

        switch ($(this).text()) {
            case "Registrar":
                if (validarenvio()) {
                    confirmacion = await confirmarAccion("Se registrará un Bien", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        var datos = new FormData();
                        datos.append('registrar', 'registrar');
                        datos.append('codigo_bien', $("#codigo_bien").val());
                        datos.append('id_tipo_bien', $("#id_tipo_bien").val());
                        datos.append('id_marca', $("#id_marca").val());
                        datos.append('descripcion', $("#descripcion").val());
                        datos.append('estado', $("#estado").val());
                        datos.append('cedula_empleado', $("#cedula_empleado").val());
                        datos.append('id_oficina', $("#id_oficina").val());
                        // Si el checkbox está activo, agrega los datos del equipo
                        if ($("#checkRegistrarEquipo").is(":checked")) {
                            datos.append('registrar_equipo', '1');
                            datos.append('serial_equipo', $("#serial_equipo").val());
                            datos.append('tipo_equipo', $("#tipo_equipo").val());
                            datos.append('id_unidad_equipo', $("#id_unidad_equipo").val());
                        }
                        // Enviar el bien y, si corresponde, el equipo
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
                                        // Si también se registró el equipo, muestra mensaje
                                        if (lee.equipo && lee.equipo.estado == 1) {
                                            mensajes("success", 5000, "Equipo registrado correctamente", null);
                                        } else if (lee.equipo && lee.equipo.estado == -1) {
                                            mensajes("error", 10000, "Error al registrar el equipo", lee.equipo.mensaje);
                                        }
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
                        envio = true;
                    }
                }
                break;
            case "Modificar":
                if (validarenvio()) {
                    confirmacion = await confirmarAccion("Se modificará un Bien", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        var datos = new FormData();
                        datos.append('modificar', 'modificar');
                        datos.append('codigo_bien', $("#codigo_bien").val());
                        datos.append('id_tipo_bien', $("#id_tipo_bien").val());
                        datos.append('id_marca', $("#id_marca").val());
                        datos.append('descripcion', $("#descripcion").val());
                        datos.append('estado', $("#estado").val());
                        datos.append('cedula_empleado', $("#cedula_empleado").val());
                        datos.append('id_oficina', $("#id_oficina").val());
                        enviaAjax(datos);
                        envio = true;
                    }
                }
                break;
            case "Eliminar":
                if (validarenvio()) {
                    confirmacion = await confirmarAccion("Se eliminará un Bien", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        var datos = new FormData();
                        datos.append('eliminar', 'eliminar');
                        datos.append('codigo_bien', $("#codigo_bien").val());
                        enviaAjax(datos);
                        envio = true;
                    }
                }
                break;
            default:
                mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text());
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

    $("#btn-consultar-eliminados").on("click", function () {
        consultarEliminadas();
        $("#modalEliminadas").modal("show");
    });
    // Limpia los campos y clases de validacion al cerrar el modal
    $('#modal1').on('hidden.bs.modal', function () {
        $("#id_oficina").val('default').removeClass('is-valid is-invalid').trigger('change');
        $("#cedula_empleado").val('default').removeClass('is-valid is-invalid').trigger('change');
        $("#id_tipo_bien").val('default').removeClass('is-valid is-invalid').trigger('change');
        $("#id_marca").val('default').removeClass('is-valid is-invalid').trigger('change');
        syncSelect2Validation("#id_oficina");
        syncSelect2Validation("#cedula_empleado");
        syncSelect2Validation("#id_tipo_bien");
        syncSelect2Validation("#id_marca");
        ocultarEquipoOpcional();
    });
});

function consultarEmpleado() {
    var datos = new FormData();
    datos.append('consultar_empleados', 'consultar_empleados');

    enviaAjax(datos);
}

function consultarOficina() {
    var datos = new FormData();
    datos.append('consultar_oficinas', 'consultar_oficinas');

    enviaAjax(datos);
}

function consultarTipoBien() {
    var datos = new FormData();
    datos.append('consultar_tipos_bien', 'consultar_tipos_bien');

    enviaAjax(datos);
}

function consultarMarca() {
    var datos = new FormData();
    datos.append('consultar_marcas', 'consultar_marcas');

    enviaAjax(datos);
}

function consultarEliminadas() {
    var datos = new FormData();
    datos.append('consultar_eliminadas', 'consultar_eliminadas');

    enviaAjax(datos);
}

async function restaurarBien(boton) {
    var confirmacion = false;
    var linea = $(boton).closest('tr');
    var codigo = $(linea).find('td:eq(1)').text();

    confirmacion = await confirmarAccion("¿Restaurar Bien?", "¿Está seguro que desea restaurar este bien?", "question");

    if (confirmacion) {
        var datos = new FormData();
        datos.append('restaurar', 'restaurar');
        datos.append('codigo_bien', codigo);
        enviaAjax(datos);
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
                console.log(lee);
                if (lee.resultado == "registrar") {
                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    consultar();

                } else if (lee.resultado == "consultar") {
                    crearDataTable(lee.datos)

                } else if (lee.resultado == "modificar") {
                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    consultar();

                } else if (lee.resultado == "eliminar") {
                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    consultar();

                } else if (lee.resultado == "consultar_eliminadas") {
                    TablaEliminados(lee.datos);

                } else if (lee.resultado == "consultar_tipos_bien") {
                    selectTipoBien(lee.datos);

                } else if (lee.resultado == "consultar_marcas") {
                    selectMarca(lee.datos);

                } else if (lee.resultado == "consultar_oficinas") {
                    selectOficina(lee.datos);

                } else if (lee.resultado == "consultar_empleados") {
                    selectEmpleado(lee.datos);

                } else if (lee.resultado == "restaurar") {
                    mensajes("success", null, "Bien restaurado", lee.mensaje);
                    consultarEliminadas();
                    consultar();

                } else if (lee.resultado == "permisos_modulo") {
                    vistaPermiso(lee.permisos);

                } else if (lee.resultado == "entrada") {
                    // No action needed
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
    $("#codigo_bien").on("keypress", function (e) {
        validarKeyPress(/^[0-9a-zA-Z\-\b]*$/, e);
    });
    $("#codigo_bien").on("keyup", function () {
        validarKeyUp(
            /^[0-9a-zA-Z\-]{3,20}$/, $(this), $("#scodigo_bien"),
            "El código debe tener de 3 a 20 caracteres (letras, números o guiones)"
        );
    });

    $("#descripcion").on("keypress", function (e) {
        validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.,\b]*$/, e);
    });
    $("#descripcion").on("keyup", function () {
        validarKeyUp(
            /^[0-9 a-zA-ZáéíóúüñÑçÇ -.,]{3,100}$/, $(this), $("#sdescripcion"),
            "La descripción debe tener de 3 a 100 caracteres"
        );
    });

    $("#id_tipo_bien").on("change", function () {
        if ($(this).val() === 'default') {

            estadoSelect(this, '#sid_tipo_bien', "Debe seleccionar un tipo de bien", 0);
        } else {

            estadoSelect(this, '#sid_tipo_bien', "", 1);
        }
    });

    $("#id_marca").on("change", function () {
        if ($(this).val() === 'default') {

            estadoSelect(this, '#sid_marca', "Debe seleccionar una marca", 0);
        } else {

            estadoSelect(this, '#sid_marca', "", 1);
        }
    });

    $("#estado").on("change", function () {
        if ($(this).val() === 'default') {

            estadoSelect(this, '#sestado', "Debe seleccionar un estado", 0);
        } else {

            estadoSelect(this, '#sestado', "", 1);
        }
    });

    $("#id_oficina").on("change", function () {
        if ($(this).val() === 'default') {

            estadoSelect(this, '#sid_oficina', "Seleccione una oficina", 0);
        } else {

            estadoSelect(this, '#sid_oficina', "", 1);
        }
    });

    $("#cedula_empleado").on("change", function () {
        if ($(this).val() === 'default') {

            estadoSelect(this, '#scedula_empleado', "Seleccione un empleado", 0);
        } else {

            estadoSelect(this, '#scedula_empleado', "", 1);
        }
    });
}

function validarenvio() {
    if (validarKeyUp(/^[0-9a-zA-Z\-]{3,20}$/, $("#codigo_bien"), $("#scodigo_bien"), "") == 0) {
        mensajes("error", 10000, "Verifica", "El código debe tener de 3 a 20 caracteres (letras, números o guiones)");
        return false;
    } else if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.,]{3,100}$/, $("#descripcion"), $("#sdescripcion"), "") == 0) {
        mensajes("error", 10000, "Verifica", "La descripción debe tener de 3 a 100 caracteres");
        return false;
    } else if ($("#id_tipo_bien").val() == "default") {
        mensajes("error", 10000, "Verifica", "Debe seleccionar un tipo de bien");
        return false;
    } else if ($("#estado").val() == "default") {
        mensajes("error", 10000, "Verifica", "Debe seleccionar un estado");
        return false;
    } else if ($("#id_oficina").val() == "default") {
        mensajes("error", 10000, "Verifica", "Debe seleccionar una oficina");
        return false;
    } else if ($("#cedula_empleado").val() == "default") {
        mensajes("error", 10000, "Verifica", "Debe seleccionar un empleado");
        return false;
    }
    return true;
}

function selectTipoBien(arreglo) {
    $("#id_tipo_bien").empty();
    if (Array.isArray(arreglo) && arreglo.length > 0) {
        $("#id_tipo_bien").append(
            new Option('Seleccione un Tipo de Bien', 'default')
        );
        arreglo.forEach(item => {
            $("#id_tipo_bien").append(
                new Option(item.nombre_tipo_bien, item.id_tipo_bien)
            );
        });
    } else {
        $("#id_tipo_bien").append(
            new Option('No Hay Tipos de Bien', 'default')
        );
    }
    $("#id_tipo_bien").val('default');
}

function selectMarca(arreglo) {
    $("#id_marca").empty();
    if (Array.isArray(arreglo) && arreglo.length > 0) {
        $("#id_marca").append(
            new Option('Seleccione una Marca', 'default')
        );
        arreglo.forEach(item => {
            $("#id_marca").append(
                new Option(item.nombre_marca, item.id_marca)
            );
        });
    } else {
        $("#id_marca").append(
            new Option('No Hay Marcas', 'default')
        );
    }
    $("#id_marca").val('default');
}

function selectOficina(arreglo) {
    $("#id_oficina").empty();
    if (Array.isArray(arreglo) && arreglo.length > 0) {
        $("#id_oficina").append(
            new Option('Seleccione una Oficina', 'default')
        );
        arreglo.forEach(item => {
            $("#id_oficina").append(
                new Option(item.nombre_oficina, item.id_oficina)
            );
        });
    } else {
        $("#id_oficina").append(
            new Option('No Hay Oficinas', 'default')
        );
    }
    $("#id_oficina").val('default');
    // Re-inicializa Select2 después de cargar las opciones
    $("#id_oficina").select2({
        dropdownParent: $('#modal1'),
        width: '100%'
    });
}

function selectEmpleado(arreglo) {
    $("#cedula_empleado").empty();
    if (Array.isArray(arreglo) && arreglo.length > 0) {
        $("#cedula_empleado").append(
            new Option('Seleccione un Empleado', 'default')
        );
        $("#cedula_empleado").append(
            new Option('No asignar Bien', '')
        );
        arreglo.forEach(item => {
            $("#cedula_empleado").append(
                new Option(item.nombre + " " + item.apellido, item.cedula)
            );
        });
    } else {
        $("#cedula_empleado").append(
            new Option('No Hay Empleados', 'default')
        );
    }
    $("#cedula_empleado").val('default');
}

function vistaPermiso(permisos = null) {

    if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {
        $('.modificar').remove();
        $('.eliminar').remove();
        $('.restaurar').remove();
    } else {
        if (permisos['bien']['modificar']['estado'] == '0') {
            $('.modificar').remove();
        }
        if (permisos['bien']['eliminar']['estado'] == '0') {
            $('.eliminar').remove();
        }
        if (permisos['bien']['restaurar']['estado'] == '0') {
            $('.restaurar').remove();
        }
    }
};

function crearDataTable(arreglo) {
    if ($.fn.DataTable.isDataTable('#tabla1')) {
        $('#tabla1').DataTable().destroy();
    }
    $('#tabla1').DataTable({
        data: arreglo,
        columns: [
            {
                data: null, render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'codigo_bien' },
            { data: 'nombre_tipo_bien' },
            { data: 'nombre_marca' },
            { data: 'descripcion' },
            { data: 'estado' },
            { data: 'nombre_oficina' },
            { data: 'empleado' },
            {
                data: null, render: function () {
                    const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar"><i class="fa-solid fa-pen-to-square "></i></button>
                    <button onclick="rellenar(this, 1)" class="btn btn-danger eliminar"><i class="fa-solid fa-trash"></i></button>`;
                    return botones;
                }
            }],
        language: {
            url: idiomaTabla,
        }
    });
    ConsultarPermisos();
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
            { data: 'nombre_tipo_bien' },
            { data: 'nombre_marca' },
            { data: 'descripcion' },
            { data: 'estado' },
            {
                data: null,
                render: function () {
                    return `<button onclick="restaurarBien(this)" class="btn btn-success restaurar">
                                            <i class="fa-solid fa-recycle"></i>
                                            </button>`;
                }
            }
        ],
        language: {
            url: idiomaTabla,
        }
    });
    ConsultarPermisos();
}

function limpia() {
    $("#codigo_bien").removeClass("is-valid is-invalid");
    $("#codigo_bien").val("");

    $("#descripcion").removeClass("is-valid is-invalid");
    $("#descripcion").val("");

    $("#id_tipo_bien").removeClass("is-valid is-invalid");
    $("#id_tipo_bien").val("default");

    $("#id_marca").removeClass("is-valid is-invalid");
    $("#id_marca").val("default");

    $("#estado").removeClass("is-valid is-invalid");
    $("#estado").val("");

    $("#id_oficina").removeClass("is-valid is-invalid");
    $("#id_oficina").val("default");

    $("#cedula_empleado").removeClass("is-valid is-invalid");
    $("#cedula_empleado").val("default");

    // Limpia campos del carrusel
    $("#serial_equipo").val('');
    $("#tipo_equipo").val('');

    $('#enviar').prop('disabled', false);
}


function rellenar(pos, accion) {
    linea = $(pos).closest('tr');

    limpia();

    $("#codigo_bien").val($(linea).find("td:eq(1)").text());
    buscarSelect("#id_tipo_bien", $(linea).find("td:eq(2)").text(), "text");
    buscarSelect("#id_marca", $(linea).find("td:eq(3)").text(), "text");
    $("#descripcion").val($(linea).find("td:eq(4)").text());
    buscarSelect("#estado", $(linea).find("td:eq(5)").text(), "text");
    buscarSelect("#id_oficina", $(linea).find("td:eq(6)").text(), "text");
    buscarSelect("#cedula_empleado", $(linea).find("td:eq(7)").text(), "text");

    if (accion == 0) {
        $("#modalTitleId").text("Modificar Bien")
        $("#enviar").text("Modificar");
    } else {
        $("#modalTitleId").text("Eliminar Bien")
        $("#enviar").text("Eliminar");
    }
    $('#enviar').prop('disabled', false);
    $("#modal1").modal("show");
}

// Helper para sincronizar clases de validacion con Select2
function syncSelect2Validation(selectId) {
    var $select = $(selectId);
    var $select2 = $select.next('.select2');
    var sel = $select2.find('.select2-selection');
    sel.removeClass('is-valid is-invalid');
    if ($select.hasClass('is-valid')) {
        sel.addClass('is-valid');
    } else if ($select.hasClass('is-invalid')) {
        sel.addClass('is-invalid');
    }
}

// Si tienes una función estadoSelect, modifícala así:
function estadoSelect(selector, spanSelector, mensaje, estado) {
    // ...existing code...
    var $select = $(selector);
    if (estado === 1) {
        $select.removeClass("is-invalid").addClass("is-valid");
        $(spanSelector).text("");
    } else {
        $select.removeClass("is-valid").addClass("is-invalid");
        $(spanSelector).text(mensaje);
    }
    // Sincroniza el estado visual con Select2
    syncSelect2Validation(selector);
}

// Si no tienes estadoSelect, llama syncSelect2Validation cada vez que cambies clases de validación en selects con Select2
// Por ejemplo, después de $("#id_oficina").addClass("is-valid") o .addClass("is-invalid"), llama:
// syncSelect2Validation("#id_oficina");

// Agrega esta función antes de $(document).ready o dentro, pero fuera de cualquier otro scope
function consultarUnidadEquipo() {
    var datos = new FormData();
    datos.append('consultar_unidades', 'consultar_unidades');
    $.ajax({
        async: true,
        url: "",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "consultar_unidades") {
                    selectUnidadEquipo(lee.datos);
                }
            } catch (e) { }
        }
    });
}

function selectUnidadEquipo(arreglo) {
    $("#id_unidad_equipo").empty();
    if (Array.isArray(arreglo) && arreglo.length > 0) {
        $("#id_unidad_equipo").append(
            new Option('Seleccione una unidad', 'default')
        );
        arreglo.forEach(item => {
            $("#id_unidad_equipo").append(
                new Option(item.nombre_unidad, item.id_unidad)
            );
        });
    } else {
        $("#id_unidad_equipo").append(
            new Option('No hay unidades', 'default')
        );
    }
    $("#id_unidad_equipo").val('default');
}

// Agrega esta función para consultar permisos, igual que en ente.js
function ConsultarPermisos() {
    var datos = new FormData();
    datos.append('permisos', 'permisos');
    $.ajax({
        async: true,
        url: "",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "permisos_modulo") {
                    vistaPermiso(lee.permisos);
                }
            } catch (e) { }
        }
    });
}