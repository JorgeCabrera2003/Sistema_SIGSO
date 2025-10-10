// piso.js - Versión Corregida

// Elementos del formulario para Piso
const elementosPiso = {
    tipo_piso: $('#tipo_piso'),
    nro_piso: $('#nro_piso'),
    id_piso: $('#id_piso')
};

// Función para manejar el cambio de estado del formulario
function manejarCambioEstadoPiso(formularioValido) {
    const accion = $("#enviar").text();

    if (accion === "Eliminar") {
        // Para eliminar solo validamos que exista un ID
        const idValido = $("#id_piso").length && $("#id_piso").val().trim() !== "";
        $('#enviar').prop('disabled', !idValido);
    } else {
        // Para registrar y modificar validamos todos los campos requeridos
        $('#enviar').prop('disabled', !formularioValido);
    }
}

// Función de validación personalizada para Piso
function validarPiso() {
    let esValido = true;
    let mensajeError = '';

    const tipoPiso = $("#tipo_piso").val();
    const nroPiso = $("#nro_piso").val();
    const accion = $("#enviar").text();

    // Si es eliminar, solo necesitamos el ID
    if (accion === "Eliminar") {
        const idValido = $("#id_piso").length && $("#id_piso").val().trim() !== "";
        return { esValido: idValido, mensajeError: "El ID del piso es requerido" };
    }

    // Validar que se haya seleccionado un tipo de piso
    if (tipoPiso === 'default' || tipoPiso === '') {
        estadoSelect('#tipo_piso', '#stipo_piso', "Seleccione un tipo de Piso", 0);
        esValido = false;
        mensajeError = "Seleccione un tipo de Piso";
    } else {
        estadoSelect('#tipo_piso', '#stipo_piso', "", 1);
    }

    // Validar que se haya seleccionado un número de piso
    if (nroPiso === 'default' || nroPiso === '') {
        estadoSelect('#nro_piso', '#snro_piso', "Seleccione un número de Piso", 0);
        esValido = false;
        mensajeError = "Seleccione un número de Piso";
    } else {
        estadoSelect('#nro_piso', '#snro_piso', "", 1);
    }

    // Validar reglas específicas de negocio
    if (esValido) {
        if (nroPiso === '0' && tipoPiso !== 'Planta Baja') {
            estadoSelect('#nro_piso', '#snro_piso', "", 0);
            estadoSelect('#tipo_piso', '#stipo_piso', "Solo Planta Baja empieza en 0", 0);
            esValido = false;
            mensajeError = "Solo Planta Baja empieza en 0";
        } else if (nroPiso !== '0' && tipoPiso === 'Planta Baja') {
            estadoSelect('#nro_piso', '#snro_piso', "", 0);
            estadoSelect('#tipo_piso', '#stipo_piso', "Solo Planta Baja empieza en 0", 0);
            esValido = false;
            mensajeError = "Solo Planta Baja empieza en 0";
        }
    }

    return { esValido, mensajeError };
}

$(document).ready(function () {
    consultar();
    registrarEntrada();
    capaValidar();

    // Inicializar sistema de validación con callback
    SistemaValidacion.inicializar(elementosPiso, manejarCambioEstadoPiso);

    // Validar estado inicial del formulario
    manejarCambioEstadoPiso(false);

    $("#enviar").on("click", async function () {
        var confirmacion = false;
        var envio = false;

        switch ($(this).text()) {
            case "Registrar":
                const validacionRegistrar = validarPiso();
                if (validacionRegistrar.esValido) {
                    confirmacion = await confirmarAccion("Se registrará un Piso", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        enviarFormulario('registrar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", validacionRegistrar.mensajeError || "Por favor corrija los errores en el formulario antes de enviar.");
                }
                break;

            case "Modificar":
                const validacionModificar = validarPiso();
                if (validacionModificar.esValido) {
                    confirmacion = await confirmarAccion("Se modificará un Piso", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        enviarFormulario('modificar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", validacionModificar.mensajeError || "Por favor corrija los errores en el formulario antes de enviar.");
                }
                break;

            case "Eliminar":
                // Para eliminar, solo validamos que exista el ID
                const idValido = $("#id_piso").length && $("#id_piso").val().trim() !== "";
                if (idValido) {
                    confirmacion = await confirmarAccion("Se eliminará un Piso", "¿Está seguro de realizar la acción?", "warning");
                    if (confirmacion) {
                        enviarFormulario('eliminar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", "El ID del piso no es válido.");
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
        $("#idPiso").remove();
        $("#modalTitleId").text("Registrar Piso");
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
                const idValido = $("#id_piso").length && $("#id_piso").val().trim() !== "";
                $('#enviar').prop('disabled', !idValido);
            } else {
                validarPiso();
            }
        }, 100);
    });
});

function consultarEliminadas() {
    var datos = new FormData();
    datos.append('consultar_eliminados', 'consultar_eliminados');
    enviaAjax(datos);
}

function enviarFormulario(accion) {
    const formData = new FormData();
    formData.append(accion, accion);
    
    // Solo agregar tipo_piso y nro_piso para registrar y modificar
    if (accion !== 'eliminar') {
        formData.append('tipo_piso', $("#tipo_piso").val());
        formData.append('nro_piso', $("#nro_piso").val());
    }

    // Agregar ID para modificar y eliminar
    if (accion !== 'registrar' && $("#id_piso").length) {
        formData.append('id_piso', $("#id_piso").val());
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

                    case "consultar_eliminados":
                        iniciarTablaEliminadas(lee.datos);
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
        complete: function () { },
    });
}

function capaValidar() {
    // Validación en tiempo real para los selects
    $("#tipo_piso, #nro_piso").on("change blur", function () {
        const accion = $("#enviar").text();
        
        if (accion !== "Eliminar") {
            validarPiso();
            
            // Verificar estado del formulario después de la validación
            const validacion = validarPiso();
            manejarCambioEstadoPiso(validacion.esValido);
        }
    });
}

function vistaPermiso(permisos = null) {
    if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {
        $('.modificar').remove();
        $('.eliminar').remove();
        $('.restaurar').remove();
    } else {
        if (permisos['piso']['modificar']['estado'] == '0') {
            $('.modificar').remove();
        }

        if (permisos['piso']['eliminar']['estado'] == '0') {
            $('.eliminar').remove();
        }

        if (permisos['piso']['restaurar'] && permisos['piso']['restaurar']['estado'] == '0') {
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
                data: 'id_piso',
                visible: false // Ocultar ID ya que es interno
            },
            {
                data: 'tipo_piso',
                render: function (data) {
                    return capitalizarTexto(data || '');
                }
            },
            {
                data: 'nro_piso',
                render: function (data) {
                    return data || '';
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
        order: [[1, 'asc'], [2, 'asc']], // Ordenar por tipo y número
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
                data: 'id_piso',
                visible: false
            },
            {
                data: 'tipo_piso',
                render: function (data) {
                    return capitalizarTexto(data || '');
                }
            },
            {
                data: 'nro_piso',
                render: function (data) {
                    return data || '';
                }
            },
            {
                data: null,
                render: function () {
                    return `<button onclick="reactivarPiso(this)" class="btn btn-success reactivar" title="Reactivar">
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

async function reactivarPiso(boton) {
    const confirmacion = await confirmarAccion("¿Reactivar Piso?", "¿Está seguro que desea reactivar este piso?", "question");

    if (confirmacion) {
        const linea = $(boton).closest('tr');
        const tabla = $('#tablaEliminadas').DataTable();
        const datosFila = tabla.row(linea).data();
        const id = datosFila.id_piso;

        var datos = new FormData();
        datos.append('reactivar', 'reactivar');
        datos.append('id_piso', id);

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
                        mensajes("success", null, "Piso reactivado", lee.mensaje);
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
                mensajes("error", null, "Error", "No se pudo reactivar el piso");
            },
            complete: function () {
                $(boton).prop('disabled', false).html('<i class="fa-solid fa-recycle"></i>');
            }
        });
    }
}

function limpia() {
    SistemaValidacion.limpiarValidacion(elementosPiso);

    $("#tipo_piso").val("default");
    $("#nro_piso").val("default");
    $("#id_piso").val("");

    $("#tipo_piso").prop("disabled", false);
    $("#nro_piso").prop("disabled", false);

    // Deshabilitar el botón al limpiar
    $('#enviar').prop('disabled', true);
}

function rellenar(pos, accion) {
    limpia();

    const linea = $(pos).closest('tr');
    const tabla = $('#tabla1').DataTable();
    const datosFila = tabla.row(linea).data();

    // Crear campo ID si no existe (IMPORTANTE para eliminar)
    if (!$("#idPiso").length) {
        $("#Fila1").prepend(`<div class="col-4" id="idPiso">
            <div class="form-floating mb-3 mt-4">
                <input placeholder="" class="form-control" name="id_piso" type="text" id="id_piso" readOnly>
                <span id="sid_piso"></span>
                <label for="id_piso" class="form-label">ID del Piso</label>
            </div>
        </div>`);

        // Actualizar elementosPiso para incluir el nuevo campo
        elementosPiso.id_piso = $('#id_piso');
    }

    // Usar los datos directamente de DataTable
    $("#id_piso").val(datosFila.id_piso);
    
    // Buscar y seleccionar los valores en los selects
    buscarSelect('#tipo_piso', datosFila.tipo_piso, "text");
    buscarSelect('#nro_piso', datosFila.nro_piso, "value");

    if (accion == 0) {
        $("#modalTitleId").text("Modificar Piso");
        $("#enviar").text("Modificar");
        
        // Validar inmediatamente para habilitar el botón
        setTimeout(() => {
            const validacion = validarPiso();
            manejarCambioEstadoPiso(validacion.esValido);
        }, 100);
    } else {
        $("#tipo_piso").prop('disabled', true);
        $("#nro_piso").prop('disabled', true);
        $("#modalTitleId").text("Eliminar Piso");
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