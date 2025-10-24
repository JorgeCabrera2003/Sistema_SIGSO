// equipo.js - Versión Mejorada
$(document).ready(function () {
    // Elementos del formulario para Equipo
    const elementosEquipo = {
        id_equipo: $('#id_equipo'),
        tipo_equipo: $('#tipo_equipo'),
        serial: $('#serial'),
        codigo_bien: $('#codigo_bien'),
        id_dependencia: $('#id_dependencia'),
        id_unidad: $('#id_unidad')
    };

    // Función para manejar el cambio de estado del formulario
    function manejarCambioEstadoEquipo(formularioValido) {
        const accion = $("#enviar").text();

        if (accion === "Eliminar") {
            // Para eliminar solo validamos el ID del equipo
            const idValido = $("#id_equipo").length && $("#id_equipo").val().trim() !== "";
            $('#enviar').prop('disabled', !idValido);
        } else {
            // Para registrar y modificar validamos todos los campos requeridos
            $('#enviar').prop('disabled', !formularioValido);
        }
    }

    consultar();
    filtrarBien();
    cargarDependencia();
    registrarEntrada();
    capaValidar();

    // Inicializar sistema de validación con callback
    if (typeof SistemaValidacion !== 'undefined') {
        SistemaValidacion.inicializar(elementosEquipo, manejarCambioEstadoEquipo);
    }

    // Validar estado inicial del formulario
    manejarCambioEstadoEquipo(false);

    $("#enviar").on("click", async function () {
        var confirmacion = false;
        var envio = false;
        
        switch ($(this).text()) {
            case "Registrar":
                if (typeof SistemaValidacion !== 'undefined' && SistemaValidacion.validarFormulario(elementosEquipo)) {
                    confirmacion = await confirmarAccion("Se registrará un nuevo Equipo", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        enviarFormulario('registrar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
                }
                break;

            case "Modificar":
                if (typeof SistemaValidacion !== 'undefined' && SistemaValidacion.validarFormulario(elementosEquipo)) {
                    confirmacion = await confirmarAccion("Se modificará un Equipo", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        enviarFormulario('modificar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
                }
                break;

            case "Eliminar":
                // Validar solo el ID para eliminar
                if ($("#id_equipo").length && $("#id_equipo").val().trim() !== "") {
                    confirmacion = await confirmarAccion("Se eliminará un Equipo", "¿Está seguro de realizar la acción?", "warning");
                    if (confirmacion) {
                        enviarFormulario('eliminar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", "El ID del equipo no es válido.");
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
        // Ocultar campo ID al registrar
        $("#id_equipo").parent().parent().hide();
        $("#modalTitleId").text("Registrar Equipo");
        $("#enviar").text("Registrar");
        $("#modal1").modal("show");

        // Deshabilitar botón inicialmente
        $('#enviar').prop('disabled', true);

        // Limpiar validación visual al abrir el modal
        setTimeout(() => {
            limpiarValidacionVisual();
        }, 100);
    });

    $("#btn-consultar-eliminados").on("click", function () {
        consultarEliminadas();
        $("#modalEliminadas").modal("show");
    });

    // Limpia los campos y clases de validación al cerrar el modal
    $('#modal1').on('hidden.bs.modal', function () {
        if (typeof SistemaValidacion !== 'undefined') {
            SistemaValidacion.limpiarValidacion(elementosEquipo);
        }
        limpiarValidacionVisual();
    });

    // Forzar validación inicial cuando se abre el modal (sin mostrar errores)
    $('#modal1').on('shown.bs.modal', function () {
        setTimeout(() => {
            const accion = $("#enviar").text();

            // Para Modificar y Eliminar, validar inmediatamente sin mostrar errores
            if (accion === "Modificar" || accion === "Eliminar") {
                // Marcar todos los campos como interactuados para que muestren validación
                $.each(elementosEquipo, function (key, elemento) {
                    if (elemento && elemento.length) {
                        elemento.data('touched', true);
                    }
                });

                // Validar formulario completo (mostrará errores visuales)
                if (typeof SistemaValidacion !== 'undefined') {
                    SistemaValidacion.validarFormulario(elementosEquipo);
                }
            }
        }, 100);
    });
});

// Función para limpiar la validación visual
function limpiarValidacionVisual() {
    const elementos = {
        id_equipo: $('#id_equipo'),
        tipo_equipo: $('#tipo_equipo'),
        serial: $('#serial'),
        codigo_bien: $('#codigo_bien'),
        id_dependencia: $('#id_dependencia'),
        id_unidad: $('#id_unidad')
    };

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

function cargarDependencia() {
    var datos = new FormData();
    datos.append('cargar_dependencia', 'cargar_dependencia');
    enviaAjax(datos);
}

async function cargarUnidad(parametro = 0) {
    var datos = new FormData();
    datos.append('cargar_unidad', 'cargar_unidad');
    datos.append('id_dependencia', parametro);
    await enviaAjax(datos);
    return true;
}

function filtrarBien() {
    var datos = new FormData();
    datos.append('filtrar_bien', 'filtrar_bien')
    enviaAjax(datos);
}

function capaValidar() {
    // Validación con formato en tiempo real para tipo_equipo
    $("#tipo_equipo").on("keypress", function (e) {
        validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.\b]*$/, e);
    });

    // Aplicar capitalización en tiempo real para tipo_equipo
    $("#tipo_equipo").on("input", function () {
        const valor = $(this).val();
        if (valor && valor.length === 1) {
            $(this).val(valor.toUpperCase());
        }
    });

    // Aplicar capitalización al perder el foco
    $("#tipo_equipo").on("blur", function () {
        if (typeof SistemaValidacion !== 'undefined') {
            SistemaValidacion.autoCapitalizar($(this));
        }
    });

    // Validación con formato en tiempo real para serial
    $("#serial").on("keypress", function (e) {
        validarKeyPress(/^[0-9a-zA-ZáéíóúüñÑçÇ.-]*$/, e);
    });

    $("#codigo_bien").on("change", function () {
        if ($(this).val() == "default") {
            estadoSelect(this, "scodigo_bien", "Debe seleccionar un código de bien", 0)
        } else {
            estadoSelect(this, "scodigo_bien", "", 1)
        }
    });

    $("#id_unidad").on("change", function () {
        if ($(this).val() == "default" || $(this).val() == "") {
            estadoSelect(this, "sid_unidad", "Debe seleccionar una unidad", 0);
        } else {
            estadoSelect(this, "sid_unidad", "", 1);
        }
    });

    $("#id_dependencia").on("change", function () {
        if ($(this).val() == "default") {
            estadoSelect(this, "sid_dependencia", "Debe seleccionar una dependencia", 0);
            estadoSelect("#id_unidad", "sid_unidad", "", 0);
            cargarUnidad(0);
        } else {
            estadoSelect(this, "sid_dependencia", "", 1);
            cargarUnidad($(this).val());
        }
    });
}

function enviarFormulario(accion) {
    const formData = new FormData();
    formData.append(accion, accion);

    // Campos del equipo
    if (accion !== 'registrar') {
        formData.append('id_equipo', $("#id_equipo").val());
    }
    formData.append('tipo_equipo', $("#tipo_equipo").val());
    formData.append('serial', $("#serial").val());
    formData.append('codigo_bien', $("#codigo_bien").val());
    formData.append('id_unidad', $("#id_unidad").val());

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
                if (lee.resultado === accion || lee.estado === 1) {
                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    consultar();
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
            // Reactivar el texto del botón según la acción
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

async function enviaAjax(datos) {
    return await $.ajax({
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

                } else if (lee.resultado == "filtrar_bien") {
                    selectBien(lee.datos);

                } else if (lee.resultado == "consultar_dependencia") {
                    selectDependencia(lee.datos);

                } else if (lee.resultado == "consultar_unidad") {
                    selectUnidad(lee.datos);

                } else if (lee.resultado == "modificar") {
                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    consultar();

                } else if (lee.resultado == "eliminar") {
                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    consultar();

                } else if (lee.resultado == "detalle") {
                    TablaHistorial(lee.datos)

                } else if (lee.resultado == "entrada") {

                } else if (lee.resultado == "permisos_modulo") {
                    vistaPermiso(lee.permisos);

                } else if (lee.resultado == "error") {
                    mensajes("error", null, lee.mensaje, null);
                }
            } catch (e) {
                console.log("error", null, "Error en JSON Tipo: " + e.name + "\n" +
                    "Mensaje: " + e.message + "\n" +
                    "Posición: " + e.lineNumber);
            } console.log(lee);

        },
        error: function (request, status, err) {
            if (status == "timeout") {
                mensajes("error", null, "Servidor ocupado", "Intente de nuevo");
            } else {
                mensajes("error", null, "Ocurrió un error", "ERROR: <br/>" + request + status + err);
            }
        },
        complete: function () {

        },
    });
}

function selectBien(arreglo) {
    $("#codigo_bien").empty();
    if (Array.isArray(arreglo) && arreglo.length > 0) {
        $("#codigo_bien").append(
            new Option('Seleccione un Bien', 'default')
        );
        arreglo.forEach(item => {
            // Asegurarse de que el valor no sea undefined
            const valor = item.codigo_bien || 'default';
            const texto = item.nombre_bien || 'Bien sin nombre';
            $("#codigo_bien").append(
                new Option(texto, valor)
            );
        });
    } else {
        $("#codigo_bien").append(
            new Option('No Hay Bienes Disponibles', 'default')
        );
    }
    
    // Aplicar validación visual
    if ($("#codigo_bien").val() === "default") {
        $("#codigo_bien").removeClass("is-valid").addClass("is-invalid");
        $("#scodigo_bien").removeClass("valid-feedback").addClass("invalid-feedback").text("Debe seleccionar un código de bien");
    }
}

function selectDependencia(arreglo) {
    $("#id_dependencia").empty();
    if (Array.isArray(arreglo) && arreglo.length > 0) {

        $("#id_dependencia").append(
            new Option('Seleccione una Dependencia', 'default')
        );
        arreglo.forEach(item => {
            $("#id_dependencia").append(
                new Option(item.ente + " - " + item.nombre, item.id)
            );
        });
    } else {
        $("#id_dependencia").append(
            new Option('No Hay Dependencia', 'default')
        );
    }
}

async function selectUnidad(arreglo) {
    $("#id_unidad").removeClass("is-valid is-invalid");
    $("#id_unidad").val("");
    $("#id_unidad").empty();
    if (Array.isArray(arreglo) && arreglo.length > 0) {

        $("#id_unidad").append(
            new Option('Seleccione una Unidad', 'default')
        );
        arreglo.forEach(item => {
            $("#id_unidad").append(
                new Option(item.nombre_unidad, item.id_unidad)
            );
        });
    } else {
        $("#id_unidad").append(
            new Option('No Hay unidad', 'default')
        );
        estadoSelect("#id_unidad", "#sid_unidad", "", 0);
    }
    return true;
}

function vistaPermiso(permisos = null) {
    if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {
        $('.modificar').remove();
        $('.eliminar').remove();
        $('.restaurar').remove();
    } else {
        if (permisos['equipo']['modificar']['estado'] == '0') {
            $('.modificar').remove();
        }
        if (permisos['equipo']['eliminar']['estado'] == '0') {
            $('.eliminar').remove();
        }
        if (permisos['equipo']['restaurar']['estado'] == '0') {
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
                data: 'id_equipo', 
                visible: false // OCULTAR ID PERO MANTENERLO DISPONIBLE
            },
            { data: 'tipo_equipo' },
            { data: 'serial' },
            { data: 'codigo_bien' },
            { data: 'dependencia' },
            { data: 'nombre_unidad' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button onclick="rellenar(this, 0)" class="btn btn-update modificar">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button onclick="rellenar(this, 1)" class="btn btn-info historial">
                            <i class="fa-solid fa-clock"></i>
                        </button>
                        <button onclick="rellenar(this, 2)" class="btn btn-danger eliminar">
                            <i class="fa-solid fa-trash"></i>
                        </button>`;
                }
            }
        ],
        language: {
            url: idiomaTabla,
        },
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
}

function limpia() {
    filtrarBien();
    
    // Mostrar campo ID al limpiar
    $("#id_equipo").parent().parent().show();
    
    $("#id_equipo").removeClass("is-valid is-invalid");
    $("#id_equipo").val("");

    $("#tipo_equipo").removeClass("is-valid is-invalid");
    $("#tipo_equipo").val("");

    $("#serial").removeClass("is-valid is-invalid");
    $("#serial").val("");

    $("#codigo_bien").removeClass("is-valid is-invalid");

    $("#id_dependencia").removeClass("is-valid is-invalid");
    $("#id_dependencia").val("default");

    $("#id_unidad").removeClass("is-valid is-invalid");
    $("#id_unidad").empty();
    $("#id_unidad").append(
        new Option('Seleccione una Dependencia primero', 'default')
    );

    $('#enviar').prop('disabled', false);
}

// FUNCIÓN RELLENAR MEJORADA - CORREGIDA PARA CÓDIGO DE BIEN
function rellenar(pos, accion) {
    limpia();

    const linea = $(pos).closest('tr');
    const tabla = $('#tabla1').DataTable();
    const datosFila = tabla.row(linea).data();

    // Mostrar campo ID para Modificar/Eliminar
    $("#id_equipo").parent().parent().show();
    
    // Usar los datos directamente de DataTable (más confiable)
    $("#id_equipo").val(datosFila.id_equipo);
    $("#tipo_equipo").val(capitalizarTexto(datosFila.tipo_equipo));
    $("#serial").val(datosFila.serial);
    
    // CORRECCIÓN: Cargar el código de bien actual del equipo
    // Primero cargar todos los bienes disponibles
    filtrarBien();
    
    // Esperar a que se carguen los bienes y luego seleccionar el correcto
    setTimeout(() => {
        if (datosFila.codigo_bien && datosFila.codigo_bien !== 'N/A') {
            // Buscar y seleccionar el código de bien actual
            buscarSelect("#codigo_bien", datosFila.codigo_bien, "value");
        }
    }, 300);

    // Cargar dependencia y unidad
    cargarDependencia();
    
    setTimeout(async () => {
        // Buscar la dependencia por texto
        if (datosFila.dependencia) {
            buscarSelect("#id_dependencia", datosFila.dependencia, "text");
        }
        
        // Esperar a que cargue las unidades y luego buscar la unidad
        setTimeout(async () => {
            await cargarUnidad($("#id_dependencia").val());
            if (datosFila.nombre_unidad) {
                buscarSelect("#id_unidad", datosFila.nombre_unidad, "text");
            }
        }, 200);
    }, 200);

    if (accion == 0) {
        $("#modalTitleId").text("Modificar Equipo");
        $("#enviar").text("Modificar");
    } else if (accion == 1) {
        var datos = new FormData();
        datos.append('detalle', 'detalle');
        datos.append('id_equipo', datosFila.id_equipo);
        enviaAjax(datos);
        return; // No abrir modal principal para historial
    } else {
        $("#modalTitleId").text("Eliminar Equipo");
        $("#enviar").text("Eliminar");
    }

    // Habilitar el botón inmediatamente para Modificar/Eliminar ya que los datos vienen pre-validados
    $('#enviar').prop('disabled', false);
    $("#modal1").modal("show");

    // Marcar todos los campos como interactuados para mostrar validación inmediata
    setTimeout(() => {
        const elementosEquipo = {
            id_equipo: $('#id_equipo'),
            tipo_equipo: $('#tipo_equipo'),
            serial: $('#serial'),
            codigo_bien: $('#codigo_bien'),
            id_dependencia: $('#id_dependencia'),
            id_unidad: $('#id_unidad')
        };

        $.each(elementosEquipo, function (key, elemento) {
            if (elemento && elemento.length) {
                elemento.data('touched', true);
            }
        });

        // Validar formulario completo para mostrar estados visuales
        if (typeof SistemaValidacion !== 'undefined') {
            SistemaValidacion.validarFormulario(elementosEquipo);
        }
    }, 500); // Aumentado el timeout para esperar la carga de datos
}

// Función auxiliar para buscar en selects - MEJORADA
function buscarSelect(id, valor, tipo) {
    const select = $(id);
    if (!select.length) {
        console.error("Select no encontrado:", id);
        return false;
    }

    let encontrado = false;
    const options = select.find('option');

    // Si el valor está vacío o es inválido, no hacer nada
    if (!valor || valor === 'N/A' || valor === 'default') {
        console.warn("Valor inválido para buscar:", valor);
        return false;
    }

    options.each(function () {
        const option = $(this);
        const optionValue = tipo === 'text' ? option.text().trim() : option.val();
        const valorBuscado = valor.toString().trim();

        if (tipo === 'text') {
            // Para búsqueda por texto, comparar ignorando mayúsculas/minúsculas
            if (optionValue.toLowerCase() === valorBuscado.toLowerCase()) {
                select.val(option.val()).trigger('change');
                encontrado = true;
                console.log("Encontrado por texto:", optionValue, "=", valorBuscado);
                return false; // Salir del bucle
            }
        } else {
            // Para búsqueda por valor, comparación exacta
            if (optionValue === valorBuscado) {
                select.val(option.val()).trigger('change');
                encontrado = true;
                console.log("Encontrado por valor:", optionValue, "=", valorBuscado);
                return false; // Salir del bucle
            }
        }
    });

    if (!encontrado) {
        console.warn("Valor no encontrado en select:", valor, "tipo:", tipo);
        // Si no se encuentra, seleccionar el primer option que no sea "default"
        const firstValidOption = options.filter(function() {
            return $(this).val() !== "default" && $(this).val() !== "";
        }).first();
        
        if (firstValidOption.length) {
            select.val(firstValidOption.val()).trigger('change');
            console.log("Seleccionado primer option válido:", firstValidOption.val());
        }
    }

    return encontrado;
}

// Resto de funciones existentes (consultarEliminadas, TablaHistorial, restaurarEquipo) se mantienen igual...
function consultarEliminadas() {
    var datos = new FormData();
    datos.append('consultar_eliminadas', 'consultar_eliminadas');

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
                if (lee.resultado == "consultar_eliminadas") {
                    if ($.fn.DataTable.isDataTable('#tablaEliminadas')) {
                        $('#tablaEliminadas').DataTable().destroy();
                    }

                    $('#tablaEliminadas').DataTable({
                        data: Array.isArray(lee.datos) ? lee.datos : [],
                        columns: [
                            { data: 'id_equipo' },
                            { data: 'tipo_equipo' },
                            { data: 'serial' },
                            { data: 'codigo_bien' },
                            { data: 'nombre_unidad' },
                            {
                                data: null,
                                render: function () {
                                    return `<button onclick="restaurarEquipo(this)" class="btn btn-success restaurar">
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
            } catch (e) {
                console.error("Error procesando datos:", e);
            }
        },
        error: function (request, status, err) {
            mensajes("error", null, "Error al cargar equipos eliminados", "Intente nuevamente");
        }
    });
}

function TablaHistorial(arreglo = null) {
    if ($.fn.DataTable.isDataTable('#tablaDetalles')) {
        $('#tablaDetalles').DataTable().destroy();
    }

    if (Object.keys(arreglo).length == 0 || arreglo == null) {
        $('#id_equipoH').val("");
        $('#tipo_equipoH').val("");
        $('#serialH').val("");
        console.log(arreglo);
    } else {
        $('#id_equipoH').val(arreglo[0]['id_equipo']);
        $('#tipo_equipoH').val(arreglo[0]['tipo_equipo']);
        $('#serialH').val(arreglo[0]['serial']);
    }

    $('#tablaDetalles').DataTable({
        data: arreglo,
        columns: [
            { data: 'nro_solicitud' },
            { data: 'empleado' },
            { data: 'motivo' },
            { data: 'codigo_hoja_servicio' },
            { data: 'nombre_tipo_servicio' },
            { data: 'observacion'},
            { data: 'resultado_hoja_servicio'}
        ],
        language: {
            url: idiomaTabla,
        }
    });
    $("#modal1_HistorialEquipo").modal("show");
}

function restaurarEquipo(boton) {
    var linea = $(boton).closest('tr');
    var id = $(linea).find('td:eq(0)').text();

    Swal.fire({
        title: '¿Restaurar Equipo?',
        text: "¿Está seguro que desea restaurar este equipo?",
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
            datos.append('id_equipo', id);

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
                            mensajes("success", null, "Equipo restaurado", lee.mensaje);
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
                    mensajes("error", null, "Error", "No se pudo restaurar el equipo");
                }
            });
        }
    });
}