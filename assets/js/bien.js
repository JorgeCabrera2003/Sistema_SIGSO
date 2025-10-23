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
    if (typeof SistemaValidacion !== 'undefined') {
        SistemaValidacion.inicializar(elementosBien, manejarCambioEstadoBien);
    }

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
        
        // Limpiar validación visual al abrir el modal
        setTimeout(() => {
            limpiarValidacionVisual();
        }, 100);
    });

    // Ocultar checkbox/carrusel en Modificar/Eliminar
    function ocultarEquipoOpcional() {
        $("#row-registro-equipo").hide();
        $("#carruselEquipo").hide();
        $("#checkRegistrarEquipo").prop("checked", false);
        // Limpia campos del carrusel
        $("#serial_equipo").val('').prop('disabled', true);
        $("#tipo_equipo").val('').prop('disabled', true);
        $("#id_unidad_equipo").val('default').prop('disabled', true).trigger('change');
    }

    // Detectar cambio de acción en el modal
    $("#modal1").on("show.bs.modal", function () {
        if ($("#enviar").text() !== "Registrar") {
            ocultarEquipoOpcional();
        }
        
        // Limpiar validación visual cada vez que se abre el modal
        setTimeout(() => {
            limpiarValidacionVisual();
        }, 100);
    });

    // Mostrar/ocultar carrusel según el checkbox
    $("#checkRegistrarEquipo").on("change", function () {
        if ($(this).is(":checked")) {
            $("#carruselEquipo").show();
            // Habilitar campos de equipo
            $('#serial_equipo, #tipo_equipo, #id_unidad_equipo').prop('disabled', false);
        } else {
            $("#carruselEquipo").hide();
            // Limpia campos del carrusel y deshabilita
            $("#serial_equipo").val('').prop('disabled', true);
            $("#tipo_equipo").val('').prop('disabled', true);
            $("#id_unidad_equipo").val('default').prop('disabled', true).trigger('change');
        }
        // Re-validar formulario completo
        setTimeout(() => {
            if (typeof SistemaValidacion !== 'undefined') {
                SistemaValidacion.validarFormulario(elementosBien);
            }
        }, 100);
    });

    $("#enviar").on("click", async function () {
        var confirmacion = false;
        var envio = false;

        switch ($(this).text()) {
            case "Registrar":
                if (typeof SistemaValidacion !== 'undefined' && SistemaValidacion.validarFormulario(elementosBien)) {
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
                if (typeof SistemaValidacion !== 'undefined' && SistemaValidacion.validarFormulario(elementosBien)) {
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
                if ($("#codigo_bien").length && $("#codigo_bien").hasClass("is-valid")) {
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
        if (typeof SistemaValidacion !== 'undefined') {
            SistemaValidacion.limpiarValidacion(elementosBien);
        }
        ocultarEquipoOpcional();
        limpiarValidacionVisual();
    });

    // Forzar validación inicial cuando se abre el modal (sin mostrar errores)
    $('#modal1').on('shown.bs.modal', function () {
        setTimeout(() => {
            // Solo validar internamente sin mostrar errores visuales
            if (typeof SistemaValidacion !== 'undefined') {
                // Validar sin aplicar estilos visuales
                validarSinEstilos();
            }
        }, 100);
    });
});

// Función para limpiar la validación visual
function limpiarValidacionVisual() {
    $.each(elementosBien, function (key, elemento) {
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

// Función para validar sin aplicar estilos visuales
function validarSinEstilos() {
    let formularioValido = true;
    
    $.each(elementosBien, function (key, elemento) {
        if (elemento && elemento.length && elemento.is(':visible') && !elemento.prop('disabled')) {
            const valor = elemento.val() ? elemento.val().trim() : '';
            const id = elemento.attr('id');
            let esValido = true;
            
            // Validación interna sin mostrar errores
            switch (id) {
                case 'codigo_bien':
                    esValido = patrones.codigoBien.test(valor);
                    break;
                case 'descripcion':
                    esValido = patrones.descripcion.test(valor);
                    break;
                case 'serial_equipo':
                    esValido = patrones.serial.test(valor);
                    break;
                case 'tipo_equipo':
                    esValido = patrones.tipoEquipo.test(valor);
                    break;
                case 'id_categoria':
                case 'id_marca':
                case 'id_oficina':
                case 'cedula_empleado':
                case 'id_unidad_equipo':
                case 'estado':
                    esValido = valor !== "default" && valor !== "" && valor !== null;
                    break;
                default:
                    if (elemento.attr('type') === 'text' || elemento.is('input')) {
                        esValido = valor.length >= 1;
                    }
            }
            
            if (!esValido) {
                formularioValido = false;
            }
        }
    });
    
    return formularioValido;
}

function inicializarSelect2() {
    const select2Config = {
        dropdownParent: $('#modal1'),
        width: '100%'
    };

    // Solo inicializar Select2 si los elementos existen
    if ($("#id_oficina").length) $("#id_oficina").select2(select2Config);
    if ($("#cedula_empleado").length) $("#cedula_empleado").select2(select2Config);
    if ($("#id_categoria").length) $("#id_categoria").select2(select2Config);
    if ($("#id_marca").length) $("#id_marca").select2(select2Config);
    if ($("#estado").length) $("#estado").select2(select2Config);
    if ($("#id_unidad_equipo").length) $("#id_unidad_equipo").select2(select2Config);
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

async function reactivarBien(boton) {
    const confirmacion = await confirmarAccion("¿Reactivar Bien?", "¿Está seguro que desea reactivar este bien?", "question");

    if (confirmacion) {
        const linea = $(boton).closest('tr');
        const tabla = $('#tablaEliminados').DataTable();
        const datosFila = tabla.row(linea).data();
        const codigo = datosFila.codigo_bien; // Obtener desde los datos
        
        var datos = new FormData();
        datos.append('reactivar', 'reactivar');
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
                if (lee.resultado === accion || lee.estado === 1) {
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

                    case "consultar_unidades":
                    case "consultar_unidad_equipo":
                        selectUnidadEquipo(lee.datos);
                        break;

                    case "reactivar":
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

                    case "consultar_bienes_empleado":
                        // Manejar bienes por empleado si es necesario
                        console.log("Bienes del empleado:", lee.datos);
                        break;

                    case "filtrar_bien":
                        // Manejar filtrado de bienes
                        console.log("Bienes filtrados:", lee.datos);
                        break;

                    case "obtener_tipo_servicio":
                        // Manejar tipo de servicio
                        console.log("Tipo de servicio:", lee.id_tipo_servicio);
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
        complete: function () {
            // Cualquier limpieza que necesites hacer después de la petición
        },
    });
}

function capaValidar() {
    // Validación con formato en tiempo real para código_bien
    $("#codigo_bien").on("keypress", function (e) {
        validarKeyPress(/^[0-9a-zA-Z\-\b]*$/, e);
    });

    // Aplicar capitalización en tiempo real para descripción
    $("#descripcion").on("input", function () {
        const valor = $(this).val();
        if (valor && valor.length === 1) {
            $(this).val(valor.toUpperCase());
        }
    });

    // Aplicar capitalización al perder el foco
    $("#descripcion").on("blur", function () {
        if (typeof SistemaValidacion !== 'undefined') {
            SistemaValidacion.autoCapitalizar($(this));
        }
    });

    // Validación con formato en tiempo real para serial_equipo
    $("#serial_equipo").on("keypress", function (e) {
        validarKeyPress(/^[0-9a-zA-Z\-\b]*$/, e);
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

    // Sincronizar validación de Select2 con el sistema de validación
    $('select').on('change', function () {
        if (typeof SistemaValidacion !== 'undefined') {
            SistemaValidacion.validarCampo.call(this);
        }
    });
}

function selectTipoBien(arreglo) {
    if (!$("#id_categoria").length) return;

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
    if (!$("#id_marca").length) return;

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
    if (!$("#id_oficina").length) return;

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
    if (!$("#cedula_empleado").length) return;

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
                new Option(item.nombre + " " + item.apellido + " - " + item.cedula, item.cedula)
            );
        });
    } else {
        $("#cedula_empleado").append(
            new Option('No Hay Empleados', 'default')
        );
    }
    $("#cedula_empleado").val('default').trigger('change');
}

function consultarUnidadEquipo() {
    var datos = new FormData();
    datos.append('consultar_unidad_equipo', 'consultar_unidad_equipo');
    enviaAjax(datos);
}

function selectUnidadEquipo(arreglo) {
    if (!$("#id_unidad_equipo").length) return;

    $("#id_unidad_equipo").empty();
    if (Array.isArray(arreglo) && arreglo.length > 0) {
        $("#id_unidad_equipo").append(
            new Option('Seleccione una Unidad', 'default')
        );
        arreglo.forEach(item => {
            $("#id_unidad_equipo").append(
                new Option(item.nombre_unidad, item.id_unidad)
            );
        });
    } else {
        $("#id_unidad_equipo").append(
            new Option('No Hay Unidades', 'default')
        );
    }
    $("#id_unidad_equipo").val('default').trigger('change');
}

function vistaPermiso(permisos) {
    try {
        console.log("Permisos recibidos:", permisos);

        // Verificar que permisos existe y tiene la propiedad estado
        if (!permisos || typeof permisos.estado === 'undefined') {
            console.error("Permisos no definidos o sin propiedad estado:", permisos);
            return;
        }

        const estado = permisos.estado;
        console.log("Estado de permisos:", estado);

        // Mostrar/ocultar botones según permisos
        if (estado === 1) {
            $("#btn-registrar").show();
            $("#btn-consultar-eliminados").show();
        } else {
            $("#btn-registrar").hide();
            $("#btn-consultar-eliminados").hide();
        }

        // Manejar permisos específicos
        if (permisos.registrar === 1) {
            $("#btn-registrar").show();
        } else {
            $("#btn-registrar").hide();
        }

        if (permisos.eliminados === 1) {
            $("#btn-consultar-eliminados").show();
        } else {
            $("#btn-consultar-eliminados").hide();
        }

    } catch (error) {
        console.error("Error al cargar permisos:", error);
    }
}

function limpia() {
    // Limpiar campos del formulario
    $("#codigo_bien").val('');
    $("#id_categoria").val('default').trigger('change');
    $("#id_marca").val('default').trigger('change');
    $("#descripcion").val('');
    $("#estado").val('default').trigger('change');
    $("#id_oficina").val('default').trigger('change');
    $("#cedula_empleado").val('default').trigger('change');
    $("#serial_equipo").val('');
    $("#tipo_equipo").val('');
    $("#id_unidad_equipo").val('default').trigger('change');

    // Limpiar validación
    if (typeof SistemaValidacion !== 'undefined') {
        SistemaValidacion.limpiarValidacion(elementosBien);
    }
    
    // Limpiar validación visual
    limpiarValidacionVisual();

    // Ocultar sección de equipo
    $("#row-registro-equipo").hide();
    $("#carruselEquipo").hide();
    $("#checkRegistrarEquipo").prop("checked", false);
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
            {
                data: 'codigo_bien',
                render: function(data) {
                    return data || 'N/A';
                }
            },
            {data: 'nombre_categoria'},
            {data: 'nombre_marca'},
            {
                data: 'descripcion',
                render: function (data) {
                    return capitalizarTexto(data || '');
                }
            },
            {data: 'estado'},
            {data: 'nombre_oficina'},
            {
                data: 'empleado',
                render: function (data, type, row) {
                    return data || 'No asignado';
                }
            },
            {
                data: null,
                render: function (data, type, row) {
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
        language: {
            url: idiomaTabla,
        },
        order: [[1, 'asc']], // Ordenar por código de bien
        responsive: true
    });

    ConsultarPermisos();
}

function rellenar(pos, accion) {
    limpia();

    const linea = $(pos).closest('tr');
    const tabla = $('#tabla1').DataTable();
    const datosFila = tabla.row(linea).data();

    // Usar los nombres de campo correctos
    $("#codigo_bien").val(datosFila.codigo_bien);
    buscarSelect("#id_categoria", datosFila.nombre_categoria, "text");
    buscarSelect("#id_marca", datosFila.nombre_marca, "text");
    $("#descripcion").val(capitalizarTexto(datosFila.descripcion));
    buscarSelect("#estado", datosFila.estado, "text");
    buscarSelect("#id_oficina", datosFila.nombre_oficina, "text");

    // Usar 'empleado' en lugar de 'nombre_empleado'
    if (datosFila.empleado && datosFila.empleado !== 'No asignado') {
        buscarSelect("#cedula_empleado", datosFila.empleado, "text");
    } else {
        $("#cedula_empleado").val('').trigger('change');
    }

    if (accion == 0) {
        $("#modalTitleId").text("Modificar Bien");
        $("#enviar").text("Modificar");
    } else {
        $("#modalTitleId").text("Eliminar Bien");
        $("#enviar").text("Eliminar");
    }

    $('#enviar').prop('disabled', false);
    $("#modal1").modal("show");
    
    // Limpiar validación visual al abrir
    setTimeout(() => {
        limpiarValidacionVisual();
    }, 100);
}

function TablaEliminados(arreglo) {
    if ($.fn.DataTable.isDataTable('#tablaEliminados')) {
        $('#tablaEliminados').DataTable().destroy();
    }

    const tabla = $('#tablaEliminados').DataTable({
        data: arreglo,
        columns: [
            { 
                data: null,
                defaultContent: '',
                className: 'text-center'
            },
            { 
                data: 'codigo_bien',
                render: function(data) {
                    return data || 'N/A';
                }
            },
            { data: 'nombre_categoria' },
            { data: 'nombre_marca' },
            { 
                data: 'descripcion',
                render: function(data) {
                    return capitalizarTexto(data || '');
                }
            },
            { data: 'estado' },
            { data: 'nombre_oficina' },
            { 
                data: 'nombre_empleado',
                render: function(data) {
                    return data || 'No asignado';
                }
            },
            { 
                data: null,
                defaultContent: '<button class="btn btn-success btn-sm" title="Reactivar"><i class="bi bi-arrow-counterclockwise"></i></button>',
                className: 'text-center',
                orderable: false
            }
        ],
        language: {
            url: idiomaTabla
        },
        pageLength: 10,
        responsive: true,
        order: [[1, 'asc']], // Ordenar por código de bien
        createdRow: function (row, data, dataIndex) {
            $(row).find('button').tooltip({
                trigger: 'hover',
                placement: 'top'
            });
        }
    });

    // Agregar números de fila
    tabla.on('order.dt search.dt', function () {
        tabla.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    // Asignar evento de reactivación
    $('#tablaEliminados tbody').on('click', 'button', function () {
        reactivarBien(this);
    });
}

function buscarSelect(id, valor, tipo) {
    const select = $(id);
    if (!select.length) return;

    let encontrado = false;

    select.find('option').each(function () {
        const option = $(this);
        const optionValue = tipo === 'text' ? option.text().trim() : option.val();
        const valorBuscado = tipo === 'text' ? valor.toString().trim() : valor;

        if (optionValue === valorBuscado) {
            select.val(option.val()).trigger('change');
            encontrado = true;
            return false; // Salir del bucle
        }
    });

    if (!encontrado) {
        select.val('default').trigger('change');
    }
}

function capitalizarTexto(texto) {
    if (!texto) return '';
    return texto.charAt(0).toUpperCase() + texto.slice(1).toLowerCase();
}

function validarKeyPress(patron, e) {
    const char = String.fromCharCode(e.which);
    if (!patron.test(char)) {
        e.preventDefault();
    }
}

function ConsultarPermisos() {
    var datos = new FormData();
    datos.append('permisos_modulo', 'permisos_modulo');
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