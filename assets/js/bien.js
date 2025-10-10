// bien.js - Versión Mejorada

// Elementos del formulario para Bien
const elementosBien = {
    codigo_bien: $('#codigo_bien'),
    id_categoria: $('#id_categoria'),
    id_marca: $('#id_marca'),
    descripcion: $('#descripcion'),
    estado: $('#estado'),
    id_oficina: $('#id_oficina'),
    cedula_empleado: $('#cedula_empleado'),
    serial_equipo: $('#serial_equipo'),
    tipo_equipo: $('#tipo_equipo'),
    id_unidad_equipo: $('#id_unidad_equipo')
};

// Función para manejar el cambio de estado del formulario
function manejarCambioEstadoBien(formularioValido) {
    const accion = $("#enviar").text();
    
    if (accion === "Eliminar") {
        // Para eliminar solo validamos el código del bien
        const idValido = $("#codigo_bien").length && $("#codigo_bien").hasClass("is-valid");
        $('#enviar').prop('disabled', !idValido);
    } else {
        // Para registrar y modificar validamos todos los campos requeridos
        $('#enviar').prop('disabled', !formularioValido);
    }
}

$(document).ready(function () {
    consultar();
    registrarEntrada();
    capaValidar();
    cargarDatosIniciales();

    // Inicializar sistema de validación con callback
    SistemaValidacion.inicializar(elementosBien, manejarCambioEstadoBien);
    
    // Validar estado inicial del formulario
    manejarCambioEstadoBien(false);

    // Inicializa Select2 para todos los selects
    inicializarSelect2();

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
        
        // Deshabilitar botón inicialmente
        $('#enviar').prop('disabled', true);
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
            // Habilitar validación para campos de equipo
            $('#serial_equipo, #tipo_equipo, #id_unidad_equipo').prop('disabled', false);
        } else {
            $("#carruselEquipo").hide();
            // Limpia campos del carrusel y deshabilita validación
            $("#serial_equipo").val('').removeClass('is-valid is-invalid');
            $("#tipo_equipo").val('').removeClass('is-valid is-invalid');
            $("#id_unidad_equipo").val('default').removeClass('is-valid is-invalid').trigger('change');
            $('#serial_equipo, #tipo_equipo, #id_unidad_equipo').prop('disabled', true);
        }
        // Re-validar formulario completo
        setTimeout(() => {
            SistemaValidacion.validarFormulario(elementosBien);
        }, 100);
    });

    $("#enviar").on("click", async function () {
        var confirmacion = false;
        var envio = false;

        switch ($(this).text()) {
            case "Registrar":
                if (SistemaValidacion.validarFormulario(elementosBien)) {
                    confirmacion = await confirmarAccion("Se registrará un Bien", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        enviarFormulario('registrar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
                }
                break;

            case "Modificar":
                if (SistemaValidacion.validarFormulario(elementosBien)) {
                    confirmacion = await confirmarAccion("Se modificará un Bien", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        enviarFormulario('modificar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
                }
                break;

            case "Eliminar":
                // Validar solo el código para eliminar
                if ($("#codigo_bien").length && SistemaValidacion.validarCampo.call(document.getElementById('codigo_bien'))) {
                    confirmacion = await confirmarAccion("Se eliminará un Bien", "¿Está seguro de realizar la acción?", "warning");
                    if (confirmacion) {
                        enviarFormulario('eliminar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", "El código del bien no es válido.");
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

    $("#btn-consultar-eliminados").on("click", function () {
        consultarEliminadas();
        $("#modalEliminadas").modal("show");
    });

    // Limpia los campos y clases de validación al cerrar el modal
    $('#modal1').on('hidden.bs.modal', function () {
        SistemaValidacion.limpiarValidacion(elementosBien);
        ocultarEquipoOpcional();
    });

    // Forzar validación inicial cuando se abre el modal
    $('#modal1').on('shown.bs.modal', function () {
        setTimeout(() => {
            SistemaValidacion.validarFormulario(elementosBien);
        }, 100);
    });
});

function inicializarSelect2() {
    const select2Config = {
        dropdownParent: $('#modal1'),
        width: '100%'
    };

    $("#id_oficina").select2(select2Config);
    $("#cedula_empleado").select2(select2Config);
    $("#id_categoria").select2(select2Config);
    $("#id_marca").select2(select2Config);
    $("#estado").select2(select2Config);
    $("#id_unidad_equipo").select2(select2Config);
}

function cargarDatosIniciales() {
    consultarOficina();
    consultarEmpleado();
    consultarMarca();
    consultarTipoBien();
}

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
    const confirmacion = await confirmarAccion("¿Restaurar Bien?", "¿Está seguro que desea restaurar este bien?", "question");

    if (confirmacion) {
        const linea = $(boton).closest('tr');
        const codigo = $(linea).find('td:eq(1)').text();
        
        var datos = new FormData();
        datos.append('restaurar', 'restaurar');
        datos.append('codigo_bien', codigo);
        enviaAjax(datos);
    }
}

function enviarFormulario(accion) {
    const formData = new FormData();
    formData.append(accion, accion);
    
    // Campos base del bien
    formData.append('codigo_bien', $("#codigo_bien").val());
    formData.append('id_categoria', $("#id_categoria").val());
    formData.append('id_marca', $("#id_marca").val());
    formData.append('descripcion', $("#descripcion").val());
    formData.append('estado', $("#estado").val());
    formData.append('cedula_empleado', $("#cedula_empleado").val());
    formData.append('id_oficina', $("#id_oficina").val());

    // Si el checkbox está activo, agrega los datos del equipo (solo para registrar)
    if (accion === 'registrar' && $("#checkRegistrarEquipo").is(":checked")) {
        formData.append('registrar_equipo', '1');
        formData.append('serial_equipo', $("#serial_equipo").val());
        formData.append('tipo_equipo', $("#tipo_equipo").val());
        formData.append('id_unidad_equipo', $("#id_unidad_equipo").val());
    }

    $.ajax({
        async: true,
        url: "",
        type: "POST",
        contentType: false,
        data: formData,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#enviar').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');
        },
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado === accion) {
                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    consultar();
                    
                    // Si también se registró el equipo, muestra mensaje
                    if (lee.equipo && lee.equipo.estado == 1) {
                        mensajes("success", 5000, "Equipo registrado correctamente", null);
                    } else if (lee.equipo && lee.equipo.estado == -1) {
                        mensajes("error", 10000, "Error al registrar el equipo", lee.equipo.mensaje);
                    }
                } else if (lee.resultado === "error") {
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
        complete: function () {
            // Restaurar el texto del botón según la acción
            let buttonText = 'Registrar';
            if (accion === 'modificar') {
                buttonText = 'Modificar';
            } else if (accion === 'eliminar') {
                buttonText = 'Eliminar';
            }
            $('#enviar').prop('disabled', false).text(buttonText);
        },
    });
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
                
                switch (lee.resultado) {
                    case "registrar":
                    case "modificar":
                    case "eliminar":
                        $("#modal1").modal("hide");
                        mensajes("success", 10000, lee.mensaje, null);
                        consultar();
                        break;
                        
                    case "consultar":
                        crearDataTable(lee.datos);
                        break;
                        
                    case "consultar_eliminadas":
                        TablaEliminados(lee.datos);
                        break;
                        
                    case "consultar_tipos_bien":
                        selectTipoBien(lee.datos);
                        break;
                        
                    case "consultar_marcas":
                        selectMarca(lee.datos);
                        break;
                        
                    case "consultar_oficinas":
                        selectOficina(lee.datos);
                        break;
                        
                    case "consultar_empleados":
                        selectEmpleado(lee.datos);
                        break;
                        
                    case "restaurar":
                        mensajes("success", null, "Bien restaurado", lee.mensaje);
                        consultarEliminadas();
                        consultar();
                        break;
                        
                    case "permisos_modulo":
                        vistaPermiso(lee.permisos);
                        break;
                        
                    case "entrada":
                        // No action needed
                        break;
                        
                    case "error":
                        mensajes("error", null, lee.mensaje, null);
                        break;
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
    // Validación con formato en tiempo real para código_bien
    $("#codigo_bien").on("keypress", function (e) {
        validarKeyPress(/^[0-9a-zA-Z\-\b]*$/, e);
    });

    // Aplicar capitalización en tiempo real para descripción
    $("#descripcion").on("input", function() {
        const valor = $(this).val();
        if (valor.length === 1) {
            $(this).val(valor.toUpperCase());
        }
    });

    // Aplicar capitalización al perder el foco
    $("#descripcion").on("blur", function () {
        SistemaValidacion.autoCapitalizar($(this));
    });

    // Validación con formato en tiempo real para serial_equipo
    $("#serial_equipo").on("keypress", function (e) {
        validarKeyPress(/^[0-9a-zA-Z\-\b]*$/, e);
    });

    // Aplicar capitalización en tiempo real para tipo_equipo
    $("#tipo_equipo").on("input", function() {
        const valor = $(this).val();
        if (valor.length === 1) {
            $(this).val(valor.toUpperCase());
        }
    });

    // Aplicar capitalización al perder el foco
    $("#tipo_equipo").on("blur", function () {
        SistemaValidacion.autoCapitalizar($(this));
    });

    // Sincronizar validación de Select2 con el sistema de validación
    $('select').on('change', function() {
        SistemaValidacion.validarCampo.call(this);
    });
}

function selectTipoBien(arreglo) {
    $("#id_categoria").empty();
    if (Array.isArray(arreglo) && arreglo.length > 0) {
        $("#id_categoria").append(
            new Option('Seleccione un Tipo de Bien', 'default')
        );
        arreglo.forEach(item => {
            $("#id_categoria").append(
                new Option(item.nombre_categoria, item.id_categoria)
            );
        });
    } else {
        $("#id_categoria").append(
            new Option('No Hay Tipos de Bien', 'default')
        );
    }
    $("#id_categoria").val('default').trigger('change');
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
    $("#id_marca").val('default').trigger('change');
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
    $("#id_oficina").val('default').trigger('change');
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
    $("#cedula_empleado").val('default').trigger('change');
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
}

function crearDataTable(arreglo) {
    if ($.fn.DataTable.isDataTable('#tabla1')) {
        $('#tabla1').DataTable().destroy();
    }
    
    $('#tabla1').DataTable({
        data: arreglo,
        columns: [
            {
                data: null, 
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'codigo_bien' },
            { data: 'nombre_categoria' },
            { data: 'nombre_marca' },
            { data: 'descripcion' },
            { data: 'estado' },
            { data: 'nombre_oficina' },
            { data: 'empleado' },
            {
                data: null, 
                render: function () {
                    const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar" title="Modificar">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button onclick="rellenar(this, 1)" class="btn btn-danger eliminar" title="Eliminar">
                        <i class="fa-solid fa-trash"></i>
                    </button>`;
                    return botones;
                }
            }
        ],
        language: {
            url: idiomaTabla,
        },
        order: [[1, 'asc']],
        responsive: true
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
                data: null, 
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'codigo_bien' },
            { data: 'nombre_categoria' },
            { data: 'nombre_marca' },
            { data: 'descripcion' },
            { data: 'estado' },
            {
                data: null,
                render: function () {
                    return `<button onclick="restaurarBien(this)" class="btn btn-success restaurar" title="Restaurar">
                        <i class="fa-solid fa-recycle"></i>
                    </button>`;
                }
            }
        ],
        language: {
            url: idiomaTabla,
        },
        order: [[1, 'asc']],
        responsive: true
    });
    
    ConsultarPermisos();
}

function limpia() {
    SistemaValidacion.limpiarValidacion(elementosBien);
    
    $("#codigo_bien").val("");
    $("#descripcion").val("");
    $("#id_categoria").val("default").trigger('change');
    $("#id_marca").val("default").trigger('change');
    $("#estado").val("");
    $("#id_oficina").val("default").trigger('change');
    $("#cedula_empleado").val("default").trigger('change');

    // Limpia campos del carrusel
    $("#serial_equipo").val('');
    $("#tipo_equipo").val('');
    $("#id_unidad_equipo").val('default').trigger('change');

    // Deshabilitar el botón al limpiar
    $('#enviar').prop('disabled', true);
}

function rellenar(pos, accion) {
    limpia();
    
    const linea = $(pos).closest('tr');
    const tabla = $('#tabla1').DataTable();
    const datosFila = tabla.row(linea).data();

    // Usar los datos directamente de DataTable (más confiable)
    $("#codigo_bien").val(datosFila.codigo_bien);
    buscarSelect("#id_categoria", datosFila.nombre_categoria, "text");
    buscarSelect("#id_marca", datosFila.nombre_marca, "text");
    $("#descripcion").val(capitalizarTexto(datosFila.descripcion));
    buscarSelect("#estado", datosFila.estado, "text");
    buscarSelect("#id_oficina", datosFila.nombre_oficina, "text");
    buscarSelect("#cedula_empleado", datosFila.empleado, "text");

    if (accion == 0) {
        $("#modalTitleId").text("Modificar Bien");
        $("#enviar").text("Modificar");
    } else {
        $("#modalTitleId").text("Eliminar Bien");
        $("#enviar").text("Eliminar");
    }
    
    // Habilitar el botón inmediatamente para Modificar/Eliminar ya que los datos vienen pre-validados
    $('#enviar').prop('disabled', false);
    $("#modal1").modal("show");
}

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
            } catch (e) { 
                console.error("Error al cargar unidades:", e);
            }
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
    $("#id_unidad_equipo").val('default').trigger('change');
}

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
            } catch (e) { 
                console.error("Error al cargar permisos:", e);
            }
        }
    });
}