// oficina.js - Versión Corregida

// Elementos del formulario para Oficina
const elementosOficina = {
    nombre: $('#nombre'),
    id_piso: $('#id_piso'),
    id_oficina: $('#id_oficina')
};

// Función para manejar el cambio de estado del formulario
function manejarCambioEstadoOficina(formularioValido) {
    const accion = $("#enviar").text();

    if (accion === "Eliminar") {
        // Para eliminar solo validamos el ID
        const idValido = $("#id_oficina").length && $("#id_oficina").val().trim() !== "";
        $('#enviar').prop('disabled', !idValido);
    } else {
        // Para registrar y modificar validamos todos los campos requeridos
        $('#enviar').prop('disabled', !formularioValido);
    }
}

// Función de validación personalizada para Oficina
function validarOficina() {
    let esValido = true;
    let mensajeError = '';

    const nombre = $("#nombre").val().trim();
    const idPiso = $("#id_piso").val();
    const accion = $("#enviar").text();

    // Si es eliminar, solo necesitamos el ID
    if (accion === "Eliminar") {
        const idValido = $("#id_oficina").length && $("#id_oficina").val().trim() !== "";
        return { esValido: idValido, mensajeError: "El ID de la oficina es requerido" };
    }

    // Validar nombre
    if (!nombre || nombre.length < 3 || nombre.length > 45) {
        SistemaValidacion.aplicarEstilos($("#nombre"), false, "El nombre debe tener entre 3 y 45 caracteres");
        esValido = false;
        mensajeError = "El nombre de la oficina debe tener de 3 a 45 caracteres";
    } else if (!patrones.letrasConNumeros.test(nombre)) {
        SistemaValidacion.aplicarEstilos($("#nombre"), false, "El nombre solo puede contener letras, números y espacios");
        esValido = false;
        mensajeError = "El nombre contiene caracteres no válidos";
    } else {
        SistemaValidacion.aplicarEstilos($("#nombre"), true, "");
    }

    // Validar piso
    if (idPiso === 'default' || idPiso === '' || idPiso === null) {
        estadoSelect('#id_piso', '#sid_piso', "Debe seleccionar un piso", 0);
        esValido = false;
        if (!mensajeError) mensajeError = "Debe seleccionar un piso";
    } else {
        estadoSelect('#id_piso', '#sid_piso', "", 1);
    }

    return { esValido, mensajeError };
}

$(document).ready(function () {
    consultar();
    registrarEntrada();
    capaValidar();
    cargarPiso();

    // Inicializar sistema de validación con callback
    SistemaValidacion.inicializar(elementosOficina, manejarCambioEstadoOficina);

    // Validar estado inicial del formulario
    manejarCambioEstadoOficina(false);

    $("#enviar").on("click", async function () {
        // Deshabilitar temporalmente el botón para evitar múltiples clics
        $('#enviar').prop('disabled', true);
        
        var confirmacion = false;
        var envio = false;

        try {
            switch ($(this).text()) {
                case "Registrar":
                    const validacionRegistrar = validarOficina();
                    if (validacionRegistrar.esValido) {
                        confirmacion = await confirmarAccion("Se registrará una Oficina", "¿Está seguro de realizar la acción?", "question");
                        if (confirmacion) {
                            await enviarFormulario('registrar');
                            envio = true;
                        }
                    } else {
                        mensajes("error", 10000, "Error de Validación", validacionRegistrar.mensajeError || "Por favor corrija los errores en el formulario antes de enviar.");
                    }
                    break;

                case "Modificar":
                    const validacionModificar = validarOficina();
                    if (validacionModificar.esValido) {
                        confirmacion = await confirmarAccion("Se modificará una Oficina", "¿Está seguro de realizar la acción?", "question");
                        if (confirmacion) {
                            await enviarFormulario('modificar');
                            envio = true;
                        }
                    } else {
                        mensajes("error", 10000, "Error de Validación", validacionModificar.mensajeError || "Por favor corrija los errores en el formulario antes de enviar.");
                    }
                    break;

                case "Eliminar":
                    // Para eliminar, solo validamos que exista el ID
                    const idValido = $("#id_oficina").length && $("#id_oficina").val().trim() !== "";
                    if (idValido) {
                        confirmacion = await confirmarAccion("Se eliminará una Oficina", "¿Está seguro de realizar la acción?", "warning");
                        if (confirmacion) {
                            await enviarFormulario('eliminar');
                            envio = true;
                        }
                    } else {
                        mensajes("error", 10000, "Error de Validación", "El ID de la oficina no es válido.");
                    }
                    break;

                default:
                    mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text());
            }
        } catch (error) {
            console.error("Error en el proceso:", error);
            mensajes("error", null, "Error", "Ocurrió un error inesperado");
        } finally {
            // Solo re-habilitar si no se envió o si fue cancelado
            if (!envio || !confirmacion) {
                // Pequeño delay para evitar problemas de UI
                setTimeout(() => {
                    const accion = $("#enviar").text();
                    if (accion === "Eliminar") {
                        const idValido = $("#id_oficina").length && $("#id_oficina").val().trim() !== "";
                        $('#enviar').prop('disabled', !idValido);
                    } else {
                        const validacion = validarOficina();
                        $('#enviar').prop('disabled', !validacion.esValido);
                    }
                }, 500);
            }
            // Si se envió, el botón se re-habilita en el complete de la petición AJAX
        }
    });

    $("#btn-registrar").on("click", function () {
        limpia();
        $("#id_oficina").parent().parent().remove();
        $("#nombre").parent().parent().show();
        $("#id_piso").parent().parent().show();
        $("#modalTitleId").text("Registrar Oficina");
        $("#enviar").text("Registrar");
        $("#modal1").modal("show");

        // Deshabilitar botón inicialmente
        $('#enviar').prop('disabled', true);
    });

    $("#btn-consultar-eliminados").on("click", function () {
        consultarEliminadas();
        $("#modalEliminadas").modal("show");
    });

    // Forzar validación cuando se abre el modal
    $('#modal1').on('shown.bs.modal', function () {
        setTimeout(() => {
            const accion = $("#enviar").text();
            if (accion === "Eliminar") {
                // Para eliminar, solo necesitamos habilitar el botón si hay ID
                const idValido = $("#id_oficina").length && $("#id_oficina").val().trim() !== "";
                $('#enviar').prop('disabled', !idValido);
            } else {
                const validacion = validarOficina();
                manejarCambioEstadoOficina(validacion.esValido);
            }
        }, 100);
    });
});

function cargarPiso() {
    var datos = new FormData();
    datos.append('consultar_pisos', 'consultar_pisos');
    enviaAjax(datos);
}

function consultarEliminadas() {
    var datos = new FormData();
    datos.append('consultar_eliminadas', 'consultar_eliminadas');
    enviaAjax(datos);
}

function enviarFormulario(accion) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append(accion, accion);
        
        // Solo agregar nombre e id_piso para registrar y modificar
        if (accion !== 'eliminar') {
            formData.append('nombre', $("#nombre").val());
            formData.append('id_piso', $("#id_piso").val());
        }

        // Agregar ID para modificar y eliminar
        if (accion !== 'registrar' && $("#id_oficina").length) {
            formData.append('id_oficina', $("#id_oficina").val());
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
                    if (lee.resultado === accion || lee.estado == 1) {
                        $("#modal1").modal("hide");
                        mensajes("success", 10000, lee.mensaje, null);
                        consultar();
                        resolve(lee);
                    } else if (lee.resultado === "error") {
                        mensajes("error", null, lee.mensaje, null);
                        reject(lee.mensaje);
                    } else {
                        mensajes("error", null, "Error", "Respuesta inesperada del servidor");
                        reject("Respuesta inesperada");
                    }
                } catch (e) {
                    mensajes("error", null, "Error en JSON Tipo: " + e.name + "\n" +
                        "Mensaje: " + e.message + "\n" +
                        "Posición: " + e.lineNumber);
                    console.error("Error:", e);
                    console.log("Respuesta del servidor:", respuesta);
                    reject(e);
                }
            },
            error: function (request, status, err) {
                if (status == "timeout") {
                    mensajes("error", null, "Servidor ocupado", "Intente de nuevo");
                } else {
                    mensajes("error", null, "Ocurrió un error", "ERROR: <br/>" + request + status + err);
                }
                reject(err);
            },
            complete: function () {
                // reactivar el texto del botón según la acción
                let buttonText = 'Registrar';
                if (accion === 'modificar') {
                    buttonText = 'Modificar';
                } else if (accion === 'eliminar') {
                    buttonText = 'Eliminar';
                }
                $('#enviar').prop('disabled', false).text(buttonText);
            },
        });
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
                        iniciarTablaEliminadas(lee.datos);
                        break;

                    case "consultar_pisos":
                        selectPiso(lee.datos);
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
                console.log("Respuesta del servidor:", respuesta);
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
    // Validación con formato en tiempo real para nombre
    $("#nombre").on("keypress", function (e) {
        validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.\b]*$/, e);
    });

    // Aplicar capitalización en tiempo real
    $("#nombre").on("input", function () {
        const valor = $(this).val();
        if (valor.length === 1) {
            $(this).val(valor.toUpperCase());
        }
        // Validar en tiempo real
        setTimeout(() => {
            const validacion = validarOficina();
            manejarCambioEstadoOficina(validacion.esValido);
        }, 100);
    });

    // Aplicar capitalización completa al perder el foco
    $("#nombre").on("blur", function () {
        SistemaValidacion.autoCapitalizar($(this));
        // Validar después de capitalizar
        setTimeout(() => {
            const validacion = validarOficina();
            manejarCambioEstadoOficina(validacion.esValido);
        }, 200);
    });

    // Validación en tiempo real para el piso
    $("#id_piso").on("change blur", function () {
        setTimeout(() => {
            const validacion = validarOficina();
            manejarCambioEstadoOficina(validacion.esValido);
        }, 100);
    });
}

// Las funciones restantes se mantienen igual...
function vistaPermiso(permisos = null) {
    if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {
        $('.modificar').remove();
        $('.eliminar').remove();
        $('.reactivar').remove();
    } else {
        if (permisos['oficina']['modificar']['estado'] == '0') {
            $('.modificar').remove();
        }

        if (permisos['oficina']['eliminar']['estado'] == '0') {
            $('.eliminar').remove();
        }

        if (permisos['oficina']['reactivar'] && permisos['oficina']['reactivar']['estado'] == '0') {
            $('.reactivar').remove();
        }
    }
}

function selectPiso(arreglo) {
    $("#id_piso").empty();
    if (Array.isArray(arreglo) && arreglo.length > 0) {
        $("#id_piso").append(new Option('Seleccione un Piso', 'default'));
        arreglo.forEach(item => {
            $("#id_piso").append(
                new Option(item.tipo_piso + " " + item.nro_piso, item.id_piso)
            );
        });
    } else {
        $("#id_piso").append(new Option('No Hay Pisos', 'default'));
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
                data: 'id_oficina',
                visible: false
            },
            {
                data: 'nombre_oficina',
                render: function (data) {
                    return capitalizarTexto(data || '');
                }
            },
            {
                data: null,
                render: function (row) {
                    return `${row.tipo_piso} ${row.nro_piso}`;
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
        order: [[1, 'asc']],
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
                data: 'id_oficina',
                visible: false
            },
            {
                data: 'nombre_oficina',
                render: function (data) {
                    return capitalizarTexto(data || '');
                }
            },
            {
                data: 'nro_piso'
            },
            {
                data: null,
                render: function () {
                    return `<button onclick="reactivarOficina(this)" class="btn btn-success reactivar" title="Reactivar">
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

async function reactivarOficina(boton) {
    const confirmacion = await confirmarAccion("¿Reactivar Oficina?", "¿Está seguro que desea reactivar esta oficina?", "question");

    if (confirmacion) {
        const linea = $(boton).closest('tr');
        const tabla = $('#tablaEliminadas').DataTable();
        const datosFila = tabla.row(linea).data();
        const id = datosFila.id_oficina;

        var datos = new FormData();
        datos.append('reactivar', 'reactivar');
        datos.append('id_oficina', id);

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
                        mensajes("success", null, "Oficina reactivada", lee.mensaje);
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
                mensajes("error", null, "Error", "No se pudo reactivar la oficina");
            },
            complete: function () {
                $(boton).prop('disabled', false).html('<i class="fa-solid fa-recycle"></i>');
            }
        });
    }
}

function limpia() {
    SistemaValidacion.limpiarValidacion(elementosOficina);

    $("#nombre").val("").prop("readOnly", false);
    $("#id_piso").val("default").prop("disabled", false);
    $("#id_oficina").val("");

    // Deshabilitar el botón al limpiar
    $('#enviar').prop('disabled', true);
}

function rellenar(pos, accion) {
    limpia();

    const linea = $(pos).closest('tr');
    const tabla = $('#tabla1').DataTable();
    const datosFila = tabla.row(linea).data();

    // Crear campo ID si no existe (IMPORTANTE para eliminar)
    if (!$("#id_oficina").length) {
        $("#Fila1").prepend(`<div class="col-4">
            <div class="form-floating mb-3 mt-4">
                <input placeholder="" class="form-control" name="id_oficina" type="text" id="id_oficina" readonly>
                <span id="sid_oficina"></span>
                <label for="id_oficina" class="form-label">ID Oficina</label>
            </div>
        </div>`);

        // Actualizar elementosOficina para incluir el nuevo campo
        elementosOficina.id_oficina = $('#id_oficina');
    }

    // Usar los datos directamente de DataTable
    $("#id_oficina").val(datosFila.id_oficina);
    $("#nombre").val(capitalizarTexto(datosFila.nombre_oficina));
    
    // Buscar y seleccionar el piso
    const pisoTexto = `${datosFila.tipo_piso} ${datosFila.nro_piso}`;
    buscarSelect("#id_piso", pisoTexto, "text");

    if (accion == 0) {
        $("#modalTitleId").text("Modificar Oficina");
        $("#enviar").text("Modificar");
        
        // Validar inmediatamente para habilitar el botón
        setTimeout(() => {
            const validacion = validarOficina();
            manejarCambioEstadoOficina(validacion.esValido);
        }, 100);
    } else {
        $("#nombre").prop('readOnly', true);
        $("#id_piso").prop('disabled', true);
        $("#modalTitleId").text("Eliminar Oficina");
        $("#enviar").text("Eliminar");
        
        // Para eliminar, habilitar inmediatamente el botón ya que tenemos el ID
        setTimeout(() => {
            $('#enviar').prop('disabled', false);
        }, 100);
    }
    
    $("#modal1").modal("show");
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