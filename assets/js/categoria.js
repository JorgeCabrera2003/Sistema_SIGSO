// categoria.js - Versión Corregida

// Elementos del formulario para Categoría
const elementosCategoria = {
    nombre: $('#nombre'),
    tipo_servicio: $('#tipo_servicio'),
    id_categoria: $('#id_categoria')
};

// Función para manejar el cambio de estado del formulario
function manejarCambioEstadoCategoria(formularioValido) {
    const accion = $("#enviar").text();

    if (accion === "Eliminar") {
        // Para eliminar solo validamos el ID
        const idValido = $("#id_categoria").length && $("#id_categoria").hasClass("is-valid");
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
    consultarTipoServicio();

    // Inicializar sistema de validación con callback
    SistemaValidacion.inicializar(elementosCategoria, manejarCambioEstadoCategoria);

    // Validar estado inicial del formulario
    manejarCambioEstadoCategoria(false);

    $("#enviar").on("click", async function () {
        var confirmacion = false;
        var envio = false;

        switch ($(this).text()) {
            case "Registrar":
                if (SistemaValidacion.validarFormulario(elementosCategoria)) {
                    confirmacion = await confirmarAccion("Se registrará una Categoría", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        enviarFormulario('registrar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
                }
                break;

            case "Modificar":
                if (SistemaValidacion.validarFormulario(elementosCategoria)) {
                    confirmacion = await confirmarAccion("Se modificará una Categoría", "¿Está seguro de realizar la acción?", "question");
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
                if ($("#id_categoria").length && $("#id_categoria").hasClass("is-valid")) {
                    confirmacion = await confirmarAccion("Se eliminará una Categoría", "¿Está seguro de realizar la acción?", "warning");
                    if (confirmacion) {
                        enviarFormulario('eliminar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", "El ID de la categoría no es válido.");
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
        $("#idCategoria").remove(); // Asegurar que no existe el campo ID
        $("#modalTitleId").text("Registrar Categoría");
        $("#enviar").text("Registrar");
        $("#modal1").modal("show");

        // Deshabilitar botón inicialmente
        $('#enviar').prop('disabled', true);
    });

    $("#btn-consultar-eliminados").on("click", function () {
        consultarEliminadas();
        $("#modalEliminadas").modal("show");
    });

    // Forzar validación inicial cuando se abre el modal
    $('#modal1').on('shown.bs.modal', function () {
        setTimeout(() => {
            SistemaValidacion.validarFormulario(elementosCategoria);
        }, 100);
    });
});

function consultarTipoServicio() {
    var datos = new FormData();
    datos.append('consultar_tipoServicio', 'consultar_tipoServicio');
    enviaAjax(datos);
}

function consultarEliminadas() {
    var datos = new FormData();
    datos.append('consultar_eliminadas', 'consultar_eliminadas');
    enviaAjax(datos);
}

function enviarFormulario(accion) {
    const formData = new FormData();
    formData.append(accion, accion);
    formData.append('nombre', $("#nombre").val());
    
    // Manejar el valor del tipo de servicio
    let tipoServicioValue = $("#tipo_servicio").val();
    if (tipoServicioValue === "none") {
        tipoServicioValue = null; // Enviar null para "No asignar Servicio"
    } else if (tipoServicioValue === "default") {
        tipoServicioValue = ""; // Enviar vacío para "Seleccione un Tipo de Servicio"
    }
    formData.append('id_tipoServicio', tipoServicioValue);

    // Solo agregar ID para modificar y eliminar
    if (accion !== 'registrar' && $("#id_categoria").length) {
        formData.append('id_categoria', $("#id_categoria").val());
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
                } else if (lee.resultado === "error") {
                    mensajes("error", null, lee.mensaje, null);
                    $('#enviar').prop('disabled', false).text(accion === 'registrar' ? 'Registrar' : 
                                                              accion === 'modificar' ? 'Modificar' : 'Eliminar');
                }
            } catch (e) {
                mensajes("error", null, "Error en JSON Tipo: " + e.name + "\n" +
                    "Mensaje: " + e.message + "\n" +
                    "Posición: " + e.lineNumber);
                $('#enviar').prop('disabled', false).text(accion === 'registrar' ? 'Registrar' : 
                                                          accion === 'modificar' ? 'Modificar' : 'Eliminar');
            }
        },
        error: function (request, status, err) {
            if (status == "timeout") {
                mensajes("error", null, "Servidor ocupado", "Intente de nuevo");
            } else {
                mensajes("error", null, "Ocurrió un error", "ERROR: <br/>" + request + status + err);
            }
            $('#enviar').prop('disabled', false).text(accion === 'registrar' ? 'Registrar' : 
                                                      accion === 'modificar' ? 'Modificar' : 'Eliminar');
        },
        complete: function () {
            // Solo reactivar si no hubo éxito (ya que en éxito se cierra el modal)
            if (!$("#modal1").is(':visible')) {
                let buttonText = 'Registrar';
                if (accion === 'modificar') {
                    buttonText = 'Modificar';
                } else if (accion === 'eliminar') {
                    buttonText = 'Eliminar';
                }
                $('#enviar').prop('disabled', false).text(buttonText);
            }
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
        beforeSend: function () {},
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
                        iniciarTablaEliminadas(lee.datos);
                        break;

                    case "consultar_tipoServicio":
                        selectTipoServicio(lee.datos);
                        break;

                    case "entrada":
                        // No action needed
                        break;

                    case "permisos_modulo":
                        vistaPermiso(lee.permisos);
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
        complete: function () {},
    });
}

function capaValidar() {
    // Validación con formato en tiempo real para nombre
    $("#nombre").on("keypress", function (e) {
        validarKeyPress(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.\b]*$/, e);
    });

    // Aplicar capitalización en tiempo real
    $("#nombre").on("input", function () {
        const valor = $(this).val();
        if (valor.length === 1) {
            $(this).val(valor.toUpperCase());
        }
    });

    // Aplicar capitalización completa al perder el foco
    $("#nombre").on("blur", function () {
        SistemaValidacion.autoCapitalizar($(this));
    });

    // Validación para select de tipo servicio
    $("#tipo_servicio").on("change", function () {
        SistemaValidacion.validarCampo.call(this);
    });
}

function vistaPermiso(permisos = null) {
    if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {
        $('.modificar').remove();
        $('.eliminar').remove();
        $('.reactivar').remove();
    } else {
        if (permisos['categoria']['modificar']['estado'] == '0') {
            $('.modificar').remove();
        }

        if (permisos['categoria']['eliminar']['estado'] == '0') {
            $('.eliminar').remove();
        }

        if (permisos['categoria']['reactivar'] && permisos['categoria']['reactivar']['estado'] == '0') {
            $('.reactivar').remove();
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
                data: 'id_categoria',
                visible: false // Ocultar ID ya que es interno
            },
            {
                data: 'nombre_categoria',
                render: function (data) {
                    return capitalizarTexto(data || '');
                }
            },
            {
                data: 'servicio',
                render: function (data) {
                    return data ? capitalizarTexto(data) : "Ninguno";
                }
            },
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
                },
                orderable: false
            }
        ],
        order: [[1, 'asc']], // Ordenar por nombre
        language: {
            url: idiomaTabla,
        },
        responsive: true,
        pageLength: 10
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
                data: 'id_categoria',
                visible: false
            },
            {
                data: 'nombre_categoria',
                render: function (data) {
                    return capitalizarTexto(data || '');
                }
            },
            {
                data: 'servicio',
                render: function (data) {
                    return data ? capitalizarTexto(data) : "Ninguno";
                }
            },
            {
                data: null,
                render: function () {
                    return `<button onclick="reactivarCategoria(this)" class="btn btn-success reactivar" title="Reactivar">
                        <i class="fa-solid fa-recycle"></i>
                    </button>`;
                },
                orderable: false
            }
        ],
        order: [[1, 'asc']],
        language: {
            url: idiomaTabla,
        },
        responsive: true,
        pageLength: 10
    });

    ConsultarPermisos();
}

async function reactivarCategoria(boton) {
    const confirmacion = await confirmarAccion("¿Reactivar Categoría?", "¿Está seguro que desea reactivar esta categoría?", "question");

    if (confirmacion) {
        const linea = $(boton).closest('tr');
        const tabla = $('#tablaEliminadas').DataTable();
        const datosFila = tabla.row(linea).data();
        const id = datosFila.id_categoria;

        var datos = new FormData();
        datos.append('reactivar', 'reactivar');
        datos.append('id_categoria', id);

        $.ajax({
            url: "",
            type: "POST",
            data: datos,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(boton).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            },
            success: function (respuesta) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.estado == 1) {
                        mensajes("success", null, "Categoría reactivada", lee.mensaje);
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
                mensajes("error", null, "Error", "No se pudo reactivar la categoría");
            },
            complete: function () {
                $(boton).prop('disabled', false).html('<i class="fa-solid fa-recycle"></i>');
            }
        });
    }
}

function limpia() {
    SistemaValidacion.limpiarValidacion(elementosCategoria);

    $("#nombre").val("").prop("readOnly", false);
    $("#id_categoria").val("").prop("readOnly", false);
    $("#tipo_servicio").val("default").prop("disabled", false);

    // Remover campo ID si existe
    $("#idCategoria").remove();

    // Deshabilitar el botón al limpiar
    $('#enviar').prop('disabled', true);
}

function rellenar(pos, accion) {
    limpia();

    const linea = $(pos).closest('tr');
    const tabla = $('#tabla1').DataTable();
    const datosFila = tabla.row(linea).data();

    // Crear campo ID solo para Modificar/Eliminar
    $("#Fila1").prepend(`<div class="col-4" id="idCategoria">
        <div class="form-floating mb-3 mt-4">
            <input placeholder="" class="form-control" name="id_categoria" type="text" id="id_categoria" readOnly>
            <span id="sid_categoria"></span>
            <label for="id_categoria" class="form-label">ID de la Categoría</label>
        </div>
    </div>`);

    // Actualizar elementosCategoria para incluir el nuevo campo
    elementosCategoria.id_categoria = $('#id_categoria');

    // Usar los datos directamente de DataTable
    $("#id_categoria").val(datosFila.id_categoria);
    $("#nombre").val(capitalizarTexto(datosFila.nombre_categoria));
    
    // Buscar y seleccionar el tipo de servicio
    buscarSelect("#tipo_servicio", datosFila.servicio || "none", "text");

    if (accion == 0) {
        $("#modalTitleId").text("Modificar Categoría");
        $("#enviar").text("Modificar");
    } else {
        $("#nombre").prop('readOnly', true);
        $("#tipo_servicio").prop('disabled', true);
        $("#modalTitleId").text("Eliminar Categoría");
        $("#enviar").text("Eliminar");
    }

    // Habilitar el botón inmediatamente para Modificar/Eliminar
    $('#enviar').prop('disabled', false);
    $("#modal1").modal("show");
}

function selectTipoServicio(arreglo) {
    $("#tipo_servicio").empty();
    if (Array.isArray(arreglo) && arreglo.length > 0) {
        $("#tipo_servicio").attr('disabled', false);
        $("#tipo_servicio").append(
            new Option('Seleccione un Tipo de Servicio', 'default')
        );
        $("#tipo_servicio").append(
            new Option('No asignar Servicio', 'none')
        );
        arreglo.forEach(item => {
            $("#tipo_servicio").append(
                new Option(item.nombre_tipo_servicio, item.id_tipo_servicio)
            );
        });
    } else {
        $("#tipo_servicio").append(
            new Option('No hay Tipos de Servicio Disponibles', 'none')
        );
        $("#tipo_servicio").attr('disabled', true);
    }
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