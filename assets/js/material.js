// Elementos del formulario para Material
const elementosMaterial = {
    nombre: $('#nombre'),
    ubicacion: $('#ubicacion'),
    stock: $('#stock'),
    id_material: $('#id_material')
};

// Función para manejar el cambio de estado del formulario
function manejarCambioEstadoMaterial(formularioValido) {
    const accion = $("#enviar").text();

    if (accion === "Eliminar") {
        // Para eliminar solo necesitamos que el ID exista, no que tenga validación visual
        const idExiste = $("#id_material").length && $("#id_material").val().trim() !== '';
        $('#enviar').prop('disabled', !idExiste);
    } else {
        // Para registrar y modificar validamos todos los campos requeridos
        $('#enviar').prop('disabled', !formularioValido);
    }
}

$(document).ready(function () {
    consultar();
    registrarEntrada();
    capaValidar();

    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha_inicio').max = hoy;
    document.getElementById('fecha_fin').max = hoy;

    // Validar que fecha inicio <= fecha fin
    document.getElementById('fecha_inicio').addEventListener('change', function () {
        const fechaFin = document.getElementById('fecha_fin');
        if (this.value > fechaFin.value) {
            fechaFin.value = this.value;
        }
        fechaFin.min = this.value;
    });

    document.getElementById('fecha_fin').addEventListener('change', function () {
        const fechaInicio = document.getElementById('fecha_inicio');
        if (this.value < fechaInicio.value) {
            fechaInicio.value = this.value;
        }
    });

    // Inicializar sistema de validación con callback
    if (typeof SistemaValidacion !== 'undefined') {
        SistemaValidacion.inicializar(elementosMaterial, manejarCambioEstadoMaterial);
    }

    // Validar estado inicial del formulario
    manejarCambioEstadoMaterial(false);

    $("#enviar").on("click", async function () {
        var confirmacion = false;
        var envio = false;

        switch ($(this).text()) {
            case "Registrar":
                if (typeof SistemaValidacion !== 'undefined' && SistemaValidacion.validarFormulario(elementosMaterial)) {
                    confirmacion = await confirmarAccion("Se registrará un Material", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        enviarFormulario('registrar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
                }
                break;

            case "Modificar":
                if (typeof SistemaValidacion !== 'undefined' && SistemaValidacion.validarFormulario(elementosMaterial)) {
                    confirmacion = await confirmarAccion("Se modificará un Material", "¿Está seguro de realizar la acción?", "question");
                    if (confirmacion) {
                        enviarFormulario('modificar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
                }
                break;

            case "Eliminar":
                // Para eliminar solo verificamos que el ID exista
                if ($("#id_material").length && $("#id_material").val().trim() !== '') {
                    confirmacion = await confirmarAccion("Se eliminará un Material", "¿Está seguro de realizar la acción?", "warning");
                    if (confirmacion) {
                        enviarFormulario('eliminar');
                        envio = true;
                    }
                } else {
                    mensajes("error", 10000, "Error de Validación", "El ID del material no es válido.");
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
        $("#idMaterial").remove();
        $("#modalTitleId").text("Registrar Material");
        $("#enviar").text("Registrar");
        
        // Habilitar todos los campos para registrar
        $("#nombre").prop("readonly", false);
        $("#ubicacion").prop("disabled", false);
        $("#stock").prop("readonly", false);
        
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
            SistemaValidacion.limpiarValidacion(elementosMaterial);
        }
        limpiarValidacionVisual();
        
        // Asegurarse de que todos los campos estén habilitados al cerrar
        $("#nombre").prop("readonly", false);
        $("#ubicacion").prop("disabled", false);
        $("#stock").prop("readonly", false);
    });

    // Forzar validación inicial cuando se abre el modal (sin mostrar errores)
    $('#modal1').on('shown.bs.modal', function () {
        setTimeout(() => {
            const accion = $("#enviar").text();

            // Para Modificar y Eliminar, validar inmediatamente sin mostrar errores
            if (accion === "Modificar" || accion === "Eliminar") {
                // Marcar todos los campos como interactuados para que muestren validación
                $.each(elementosMaterial, function (key, elemento) {
                    if (elemento && elemento.length) {
                        elemento.data('touched', true);
                    }
                });

                // Validar formulario completo (mostrará errores visuales)
                if (typeof SistemaValidacion !== 'undefined') {
                    SistemaValidacion.validarFormulario(elementosMaterial);
                }
            }
        }, 100);
    });
});

// Función para limpiar la validación visual
function limpiarValidacionVisual() {
    $.each(elementosMaterial, function (key, elemento) {
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

function enviarFormulario(accion) {
    const formData = new FormData();
    formData.append(accion, accion);

    // Campos del material
    formData.append('nombre', $("#nombre").val());
    formData.append('ubicacion', $("#ubicacion").val());
    formData.append('stock', $("#stock").val());

    // Para modificar y eliminar, enviar el ID
    if (accion === 'modificar' || accion === 'eliminar') {
        formData.append('id_material', $("#id_material").val());
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
                // Verificar si la respuesta es HTML antes de intentar parsear como JSON
                if (respuesta.trim().startsWith('<!DOCTYPE') || respuesta.trim().startsWith('<html') || respuesta.includes('<!DOCTYPE html>')) {
                    console.error("El servidor devolvió HTML en lugar de JSON");
                    console.error("Respuesta recibida (primeros 500 caracteres):", respuesta.substring(0, 500));

                    // Intentar obtener más información del error
                    const errorMatch = respuesta.match(/<title>(.*?)<\/title>/);
                    const errorTitle = errorMatch ? errorMatch[1] : 'Error desconocido';

                    mensajes("error", null, "Error del Servidor",
                        "El servidor devolvió una página HTML. Esto puede deberse a:\n" +
                        "1. Error de sintaxis en PHP\n" +
                        "2. Sesión expirada\n" +
                        "3. Permisos insuficientes\n\n" +
                        "Error: " + errorTitle);
                    return;
                }

                var lee = JSON.parse(respuesta);
                if (lee.resultado === accion || lee.estado === 1) {
                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    consultar();
                } else if (lee.resultado === "error") {
                    mensajes("error", null, lee.mensaje, null);
                }
            } catch (e) {
                console.error("Error procesando respuesta:", e);
                console.error("Respuesta recibida:", respuesta.substring(0, 200));

                mensajes("error", null, "Error procesando respuesta",
                    "Tipo: " + e.name + "\n" +
                    "Mensaje: " + e.message + "\n" +
                    "Verifique la consola para más detalles.");
            }
        },
        error: function (request, status, err) {
            if (status == "timeout") {
                mensajes("error", null, "Servidor ocupado", "Intente de nuevo");
            } else {
                mensajes("error", null, "Error de conexión",
                    "Estado: " + status + "\n" +
                    "Error: " + err + "\n" +
                    "Detalles: " + request.status + " - " + request.statusText);
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

async function reactivarMaterial(boton) {
    const confirmacion = await confirmarAccion("¿Reactivar Material?", "¿Está seguro que desea reactivar este material?", "question");

    if (confirmacion) {
        const linea = $(boton).closest('tr');
        const tabla = $('#tablaEliminadas').DataTable();
        const datosFila = tabla.row(linea).data();
        const id_material = datosFila.id_material;

        var datos = new FormData();
        datos.append('reactivar', 'reactivar');
        datos.append('id_material', id_material);
        enviaAjax(datos);
    }
}

function consultarEliminadas() {
    var datos = new FormData();
    datos.append('consultar_eliminadas', 'consultar_eliminadas');
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
            try {
                // Verificar si la respuesta es HTML antes de intentar parsear como JSON
                if (respuesta.trim().startsWith('<!DOCTYPE') || respuesta.trim().startsWith('<html') || respuesta.includes('<!DOCTYPE html>')) {
                    console.error("El servidor devolvió HTML en lugar de JSON. Verificando la petición...");
                    console.error("Datos enviados:", Array.from(datos.entries()));
                    console.error("Respuesta recibida (primeros 500 caracteres):", respuesta.substring(0, 500));

                    // Intentar obtener más información del error
                    const errorMatch = respuesta.match(/<title>(.*?)<\/title>/);
                    const errorTitle = errorMatch ? errorMatch[1] : 'Error desconocido';

                    mensajes("error", null, "Error del Servidor",
                        "El servidor devolvió una página HTML. Esto puede deberse a:\n" +
                        "1. Error de sintaxis en PHP\n" +
                        "2. Sesión expirada\n" +
                        "3. Permisos insuficientes\n\n" +
                        "Error: " + errorTitle);
                    return;
                }

                var lee = JSON.parse(respuesta);
                console.log("Respuesta procesada:", lee);

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

                    case "detalle":
                        TablaHistorial(lee.datos);
                        break;

                    case "consultar_eliminadas":
                        TablaEliminados(lee.datos);
                        break;

                    case "reactivar":
                        mensajes("success", null, "Material reactivado", lee.mensaje);
                        consultarEliminadas();
                        consultar();
                        break;

                    case "entrada":
                        // No action needed
                        break;

                    case "permisos_modulo":
                        console.log("PERMISOS COMPLETOS:", lee.permisos);
                        console.log("PERMISOS MATERIAL:", lee.permisos.material);
                        vistaPermiso(lee.permisos);
                        break;

                    case "error":
                        mensajes("error", null, lee.mensaje, null);
                        break;

                    default:
                        console.warn("Caso no manejado en enviaAjax:", lee.resultado);
                        break;
                }
            } catch (e) {
                console.error("Error procesando respuesta:", e);
                console.error("Respuesta recibida:", respuesta.substring(0, 200));

                mensajes("error", null, "Error procesando respuesta",
                    "Tipo: " + e.name + "\n" +
                    "Mensaje: " + e.message + "\n" +
                    "Verifique la consola para más detalles.");
            }
        },
        error: function (request, status, err) {
            if (status == "timeout") {
                mensajes("error", null, "Servidor ocupado", "Intente de nuevo");
            } else {
                mensajes("error", null, "Error de conexión",
                    "Estado: " + status + "\n" +
                    "Error: " + err + "\n" +
                    "Detalles: " + request.status + " - " + request.statusText);
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

    // Aplicar capitalización en tiempo real para nombre
    $("#nombre").on("input", function () {
        const valor = $(this).val();
        if (valor && valor.length === 1) {
            $(this).val(valor.toUpperCase());
        }
    });

    // Aplicar capitalización al perder el foco
    $("#nombre").on("blur", function () {
        if (typeof SistemaValidacion !== 'undefined') {
            SistemaValidacion.autoCapitalizar($(this));
        }
    });

    // Validación con formato en tiempo real para stock
    $("#stock").on("keypress", function (e) {
        validarKeyPress(/^[0-9\b]*$/, e);
    });

    // Sincronizar validación de Select con el sistema de validación
    $('#ubicacion').on('change', function () {
        if (typeof SistemaValidacion !== 'undefined') {
            // Marcar como interactuado para mostrar validación
            $(this).data('touched', true);
            SistemaValidacion.validarCampo.call(this);
        }
    });

    // Validación especial para el campo ID en modo Eliminar
    $('#id_material').on('input change', function() {
        const accion = $("#enviar").text();
        if (accion === "Eliminar") {
            const idExiste = $(this).val().trim() !== '';
            $('#enviar').prop('disabled', !idExiste);
        }
    });
}

function vistaPermiso(permisos = null) {
    try {
        console.log("Permisos recibidos:", permisos);

        // Verificar que permisos existe y tiene la propiedad material
        if (!permisos || !permisos.material) {
            console.error("Permisos no definidos o sin propiedad material:", permisos);
            
            // Fallback: ocultar todos los botones de acción en la tabla
            $('.modificar').remove();
            $('.eliminar').remove();
            $('.historial').remove();
            $('.reactivar').remove();
            return;
        }

        const permisosMaterial = permisos.material;
        console.log("Permisos de material:", permisosMaterial);

        // Mostrar/ocultar botones según permisos
        if (permisosMaterial.ver && permisosMaterial.ver.estado == "1") {
            // Mostrar módulo completo
            if (permisosMaterial.registrar && permisosMaterial.registrar.estado == "1") {
                $("#btn-registrar").show();
            } else {
                $("#btn-registrar").hide();
            }

            if (permisosMaterial.reactivar && permisosMaterial.reactivar.estado == "1") {
                $("#btn-consultar-eliminados").show();
            } else {
                $("#btn-consultar-eliminados").hide();
            }

            // Controlar botones en la tabla principal
            if (permisosMaterial.modificar && permisosMaterial.modificar.estado == "0") {
                $('.modificar').remove();
            }
            if (permisosMaterial.eliminar && permisosMaterial.eliminar.estado == "0") {
                $('.eliminar').remove();
            }
            if (permisosMaterial.historial && permisosMaterial.historial.estado == "0") {
                $('.historial').remove();
            }
            if (permisosMaterial.reactivar && permisosMaterial.reactivar.estado == "0") {
                $('.reactivar').remove();
            }
        } else {
            // Ocultar todo si no tiene permiso de ver
            $("#btn-registrar").hide();
            $("#btn-consultar-eliminados").hide();
            $('.modificar').remove();
            $('.eliminar').remove();
            $('.historial').remove();
            $('.reactivar').remove();
        }

    } catch (error) {
        console.error("Error al cargar permisos:", error);
        
        // Fallback en caso de error
        $('.modificar').remove();
        $('.eliminar').remove();
        $('.historial').remove();
        $('.reactivar').remove();
    }
}

function crearDataTable(arreglo) {
    console.log(arreglo);
    if ($.fn.DataTable.isDataTable('#tabla1')) {
        $('#tabla1').DataTable().destroy();
    }

    $('#tabla1').DataTable({
        data: arreglo,
        columns: [
            { 
                data: 'id_material',
                visible: false 
            },
            { data: 'nombre_material' },
            { data: 'nombre_oficina' },
            { data: 'stock' },
            {
                data: null,
                render: function () {
                    const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar" title="Modificar">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button onclick="rellenar(this, 1)" class="btn btn-info historial" title="Historial">
                                        <i class="fa-solid fa-clock"></i>
                                    </button>
                                    <button onclick="rellenar(this, 2)" class="btn btn-danger eliminar" title="Eliminar">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>`;
                    return botones;
                },
                orderable: false
            }
        ],
        language: {
            url: idiomaTabla,
        },
        order: [[1, 'asc']], // Ordenar por nombre de material
        responsive: true
    });

    ConsultarPermisos();
}

function limpia() {
    // Limpiar campos del formulario
    $("#nombre").val('');
    $("#ubicacion").val('');
    $("#stock").val('');
    $("#id_material").val('');

    // Limpiar validación
    if (typeof SistemaValidacion !== 'undefined') {
        SistemaValidacion.limpiarValidacion(elementosMaterial);
    }

    // Limpiar validación visual
    limpiarValidacionVisual();

    // Remover campo ID si existe
    $("#idMaterial").remove();

    // Asegurarse de que todos los campos estén habilitados
    $("#nombre").prop("readonly", false);
    $("#ubicacion").prop("disabled", false);
    $("#stock").prop("readonly", false);
}

function rellenar(pos, accion) {
    limpia();

    const linea = $(pos).closest('tr');
    const tabla = $('#tabla1').DataTable();
    const datosFila = tabla.row(linea).data();

    // Agregar campo ID oculto
    $("#idMaterial").remove();
    $("#Fila1").prepend(`<div class="col-4" id="idMaterial">
        <div class="form-floating mb-3 mt-4">
            <input placeholder="" class="form-control" name="id_material" type="text" id="id_material" readonly>
            <span id="sid_material"></span>
            <label for="id_material" class="form-label">ID del Material</label>
        </div>
    </div>`);

    // Llenar campos con datos de la fila
    $("#id_material").val(datosFila.id_material);
    $("#nombre").val(datosFila.nombre_material);
    buscarSelect("#ubicacion", datosFila.nombre_oficina, "text");
    $("#stock").val(datosFila.stock);

    if (accion == 0) {
        // MODIFICAR - Todos los campos editables
        $("#modalTitleId").text("Modificar Material");
        $("#enviar").text("Modificar");
        $("#nombre").prop("readonly", false);
        $("#ubicacion").prop("disabled", false);
        $("#stock").prop("readonly", false);
        $('#enviar').prop('disabled', false);
        $("#modal1").modal("show");
    } else if (accion == 1) {
        // HISTORIAL - No abre modal de formulario
        var datos = new FormData();
        datos.append('id_material', datosFila.id_material);
        datos.append('detalle', 'detalle');
        $("#modalTitleId_HistorialMaterial").text("Detalle Material");
        $("#modal1_HistorialMaterial").modal("show");
        enviaAjax(datos);
    } else {
        // ELIMINAR - Todos los campos editables también
        $("#modalTitleId").text("Eliminar Material");
        $("#enviar").text("Eliminar");
        $("#nombre").prop("readonly", false);
        $("#ubicacion").prop("disabled", false);
        $("#stock").prop("readonly", false);
        $('#enviar').prop('disabled', false);
        $("#modal1").modal("show");
    }

    // Marcar todos los campos como interactuados para mostrar validación inmediata
    setTimeout(() => {
        $.each(elementosMaterial, function (key, elemento) {
            if (elemento && elemento.length) {
                elemento.data('touched', true);
            }
        });

        // Validar formulario completo para mostrar estados visuales
        if (typeof SistemaValidacion !== 'undefined') {
            SistemaValidacion.validarFormulario(elementosMaterial);
        }
    }, 100);
}

function TablaEliminados(arreglo) {
    if ($.fn.DataTable.isDataTable('#tablaEliminadas')) {
        $('#tablaEliminadas').DataTable().destroy();
    }

    $('#tablaEliminadas').DataTable({
        data: arreglo,
        columns: [
            { 
                data: 'id_material',
                visible: false 
            },
            { data: 'nombre_material' },
            { data: 'ubicacion' },
            { data: 'stock' },
            {
                data: null,
                render: function () {
                    return `<button onclick="reactivarMaterial(this)" class="btn btn-success reactivar" title="Reactivar">
                                <i class="fa-solid fa-recycle"></i>
                            </button>`;
                },
                orderable: false
            }
        ],
        language: {
            url: idiomaTabla,
        },
        order: [[1, 'asc']], // Ordenar por nombre de material
        responsive: true
    });
}

function TablaHistorial(arreglo) {
    if ($.fn.DataTable.isDataTable('#tablaDetalles')) {
        $('#tablaDetalles').DataTable().destroy();
    }

    $('#tablaDetalles').DataTable({
        data: arreglo,
        columns: [
            { data: 'id_movimiento_material' },
            { data: 'nombre_material' },
            { data: 'accion' },
            { data: 'cantidad' },
            { data: 'descripcion' }
        ],
        language: {
            url: idiomaTabla,
        },
        order: [[0, 'desc']], // Ordenar por ID de movimiento descendente
        responsive: true
    });
    $("#modal1_HistorialMaterial").modal("show");
}

function buscarSelect(id, valor, tipo) {
    const select = $(id);
    if (!select.length) return;

    let encontrado = false;

    select.find('option').each(function () {
        const option = $(this);
        const optionValue = tipo === 'text' ? option.text().trim() : option.val();

        if (tipo === 'text') {
            if (optionValue === valor.toString().trim()) {
                select.val(option.val()).trigger('change');
                encontrado = true;
                return false; // Salir del bucle
            }
        } else {
            if (optionValue === valor) {
                select.val(option.val()).trigger('change');
                encontrado = true;
                return false; // Salir del bucle
            }
        }
    });

    if (!encontrado) {
        select.val('').trigger('change');
    }
}

function ConsultarPermisos() {
    var datos = new FormData();
    datos.append('permisos_modulo', 'permisos_modulo');
    console.log("Solicitando permisos...");
    enviaAjax(datos);
}

function registrarEntrada() {
    var datos = new FormData();
    datos.append('entrada', 'entrada');
    enviaAjax(datos);
}

function consultar() {
    var datos = new FormData();
    datos.append('consultar', 'consultar');
    enviaAjax(datos);
}