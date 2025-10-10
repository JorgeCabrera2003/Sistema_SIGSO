// solicitud.js - Versión Mejorada
let areaSeleccionadaManualmente = false;

// Elementos del formulario para Solicitud
const elementosSolicitud = {
    dependencia: $('#dependencia'),
    solicitante: $('#solicitante'),
    equipo: $('#equipo'),
    area: $('#area'),
    tecnico: $('#tecnico'),
    motivo: $('#motivo'),
    nroSolicitud: $('#nroSolicitud')
};

// Función para manejar el cambio de estado del formulario
function manejarCambioEstadoSolicitud(formularioValido) {
    const accion = $("#btnGuardar").text();
    
    if (accion === "Eliminar") {
        // Para eliminar solo validamos el número de solicitud
        const idValido = $("#nroSolicitud").length && $("#nroSolicitud").val().trim() !== '';
        $('#btnGuardar').prop('disabled', !idValido);
    } else {
        // Para registrar y modificar validamos todos los campos requeridos
        $('#btnGuardar').prop('disabled', !formularioValido);
    }
}

$(document).ready(function () {
    // Arrays de palabras clave para cada área de servicio
    const palabrasClave = {
        soporte: ['computador', 'pc', 'equipo', 'laptop', 'monitor', 'teclado', 'mouse', 'impresora', 'software', 'windows', 'office', 'excel', 'word', 'encender', 'apagar', 'reiniciar', 'pantalla', 'sonido', 'altavoz', 'bocina', 'disco', 'ram', 'procesador', 'virus', 'antivirus', 'lentitud', 'internet', 'navegador', 'chrome', 'firefox', 'aplicación', 'programa', 'instalar', 'desinstalar'],
        electronica: ['circuito', 'soldadura', 'multímetro', 'osciloscopio', 'fuente', 'alimentación', 'voltaje', 'corriente', 'resistencia', 'capacitor', 'diodo', 'transistor', 'placa', 'pcb', 'protoboard', 'arduino', 'raspberry', 'microcontrolador', 'sensor', 'actuador', 'motor', 'reparación', 'corto circuito', 'quemado', 'falla eléctrica'],
        telefonia: ['teléfono', 'celular', 'central', 'pbx', 'extensión', 'tono', 'llamada', 'marcar', 'auricular', 'handset', 'voip', 'ip', 'sip', 'conmutador', 'interno', 'externo', 'fax', 'conferencia', 'buzón', 'mensaje', 'contestador', 'inalámbrico', 'inalambrico', 'inalámbrica', 'inalambrica', 'senitel'],
        redes: ['red', 'wifi', 'ethernet', 'cable', 'conexión', 'conexion', 'ip', 'dns', 'dhcp', 'router', 'switch', 'acceso', 'inalámbrico', 'inalambrico', 'punto', 'acceso', 'velocidad', 'ping', 'latencia', 'caída', 'caida', 'intermitente', 'cortes', 'proxy', 'vpn', 'firewall', 'servidor', 'dominio']
    };

    // --- INICIALIZACIÓN ---
    inicializarComponentes();
    cargarDatosIniciales();
    configurarEventos(palabrasClave);
    
    // Inicializar sistema de validación con callback
    SistemaValidacion.inicializar(elementosSolicitud, manejarCambioEstadoSolicitud);
    
    // Validar estado inicial del formulario
    manejarCambioEstadoSolicitud(false);
});

// --- FUNCIONES DE CONFIGURACIÓN Y EVENTOS ---
function configurarEventos(palabrasClave) {
    $('#btn-refrescar').on('click', recargarTabla);

    $('#btn-solicitudes-eliminadas').on('click', () => {
        consultarEliminadas();
        $('#modalEliminadas').modal('show');
    });

    $('#btnNuevaSolicitud').on('click', () => {
        // Resetea el tracker para la nueva solicitud
        areaSeleccionadaManualmente = false;

        // 1. Resetear el formulario base
        $('#formSolicitud')[0].reset();

        // 2. Limpiar validación
        SistemaValidacion.limpiarValidacion(elementosSolicitud);

        // 3. Limpiar los select2 que no deben tener valor por defecto
        $('#dependencia, #solicitante, #equipo, #tecnico').val(null).trigger('change');

        // 4. Configurar el modal para una nueva solicitud
        $('#modalSolicitudLabel').text('Nueva Solicitud');
        $('#btnGuardar').text('Guardar').attr('name', 'registrar');
        gestionarEstadoCampos(true);
        
        // Deshabilitar botón inicialmente
        $('#btnGuardar').prop('disabled', true);
        
        // Cargar áreas disponibles
        cargarAreas();
    });

    $('#dependencia').on('change', function () {
        const dependenciaId = $(this).val();
        if (dependenciaId) {
            // Validar campo
            SistemaValidacion.validarCampo.call(this);
            // Habilitar siguiente campo
            $('#solicitante').prop('disabled', false);
            cargarSolicitantes(dependenciaId);
        } else {
            $('#solicitante').prop('disabled', true);
            $('#equipo').prop('disabled', true);
            $('#solicitante').empty().append('<option value="" selected disabled>Seleccione un solicitante</option>').trigger('change');
            $('#equipo').empty().append('<option value="">No especificar equipo</option>').trigger('change');
            SistemaValidacion.validarCampo.call(this);
        }
    });

    $('#solicitante').on('change', function () {
        const cedula = $(this).val();
        const nro_solicitud = $('#nroSolicitud').val();
        if (cedula) {
            // Validar campo
            SistemaValidacion.validarCampo.call(this);
            // Habilitar siguiente campo
            $('#equipo').prop('disabled', false);
            $('#area').prop('disabled', false);
            cargarEquiposPorSolicitante(cedula, nro_solicitud);
        } else {
            $('#equipo').prop('disabled', true);
            $('#area').prop('disabled', true);
            $('#tecnico').prop('disabled', true);
            $('#equipo').empty().append('<option value="">No especificar equipo</option>').trigger('change');
            SistemaValidacion.validarCampo.call(this);
        }
    });

    $('#equipo').on('change', function () {
        SistemaValidacion.validarCampo.call(this);
        // Habilitar área si aún no está habilitada
        if ($(this).val() && $('#area').prop('disabled')) {
            $('#area').prop('disabled', false);
        }
    });

    $('#area').on('change', function (event) {
        // Chequeo de seguridad para el evento
        if (event && event.originalEvent) { 
            areaSeleccionadaManualmente = true;
        }
        const areaId = $(this).val();
        if (areaId) {
            // Validar campo
            SistemaValidacion.validarCampo.call(this);
            // Habilitar siguiente campo
            $('#tecnico').prop('disabled', false);
            $('#motivo').prop('disabled', false);
            cargarTecnicosPorArea(areaId);
        } else {
            $('#tecnico').prop('disabled', true);
            $('#motivo').prop('disabled', true);
            $('#tecnico').empty().append('<option value="" selected disabled>Seleccione un técnico</option>').trigger('change');
            SistemaValidacion.validarCampo.call(this);
        }
    });

    $('#tecnico').on('change', function () {
        SistemaValidacion.validarCampo.call(this);
    });

    $('#motivo').on('input', function () {
        // Aplicar capitalización en tiempo real para la primera letra
        const valor = $(this).val();
        if (valor.length === 1) {
            $(this).val(valor.toUpperCase());
        }
        
        SistemaValidacion.validarCampo.call(this);
        
        if (!areaSeleccionadaManualmente) {
            const areaId = detectarAreaPorMotivo($(this).val(), palabrasClave);
            if (areaId) {
                $('#area').val(areaId).trigger('change');
            }
        }
    });

    $('#motivo').on('blur', function () {
        // Aplicar capitalización completa al perder el foco
        SistemaValidacion.autoCapitalizar($(this));
    });

    $('#formSolicitud').on('submit', e => {
        e.preventDefault();
        enviarFormulario();
    });

    // Validación con formato en tiempo real para motivo
    $("#motivo").on("keypress", function (e) {
        // Expresión regular corregida - sin \b dentro de la clase de caracteres
        validarKeyPress(/^[0-9a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ\s.,-]*$/, e);
    });
}

// --- FUNCIONES DE LÓGICA ---
function detectarAreaPorMotivo(motivo, palabrasClave) {
    if (!motivo || motivo.trim().length < 3) return null;
    
    const texto = motivo.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    const conteo = { soporte: 0, electronica: 0, telefonia: 0, redes: 0 };

    for (const area in palabrasClave) {
        palabrasClave[area].forEach(palabra => {
            const palabraNormalizada = palabra.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            const regex = new RegExp(`\\b${palabraNormalizada}\\b`, 'gi');
            const matches = texto.match(regex);
            if (matches) {
                conteo[area] += matches.length;
            }
        });
    }

    let areaSeleccionada = null;
    let maxCoincidencias = 0;
    for (const area in conteo) {
        if (conteo[area] > maxCoincidencias) {
            maxCoincidencias = conteo[area];
            areaSeleccionada = area;
        }
    }

    // Solo retornar si hay al menos una coincidencia
    if (maxCoincidencias === 0) return null;

    // Mapear nombres de áreas a IDs según lo que exista en la base de datos
    const mapAreaToId = { 
        'soporte': 1, 
        'redes': 2, 
        'telefonia': 3, 
        'electronica': 4 
    };
    
    return mapAreaToId[areaSeleccionada];
}

function inicializarComponentes() {
    // Inicializar DataTable
    window.tablaSolicitudes = $('#tablaSolicitudes').DataTable({
        language: {
            "decimal": "",
            "emptyTable": "No hay datos disponibles en la tabla",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron registros coincidentes",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        order: [[0, 'desc']],
        responsive: true,
        processing: true,
        serverSide: false,
        ajax: {
            url: '?page=solicitud',
            type: 'POST',
            data: function (d) {
                d.consultar = 'consultar';
            },
            dataType: 'json',
            dataSrc: function (response) {
                if (response.resultado === 'consultar') {
                    return response.datos;
                } else {
                    console.error('Error al cargar solicitudes:', response.mensaje || 'Respuesta inesperada');
                    return [];
                }
            },
            error: function (xhr, error, thrown) {
                console.error('Error en AJAX:', xhr.responseText);
                return [];
            }
        },
        columns: [
            {data: 'ID'},
            {data: 'Solicitante'},
            {data: 'Cedula'},
            {data: 'Dependencia'},
            {
                data: 'Equipo',
                render: function (data) {
                    return data || 'N/A';
                }
            },
            {data: 'Motivo'},
            {
                data: 'Estado',
                render: function (data) {
                    return `<span class="badge bg-${getBadgeColor(data)}">${data}</span>`;
                }
            },
            {
                data: 'Inicio',
                render: function (data) {
                    return data ? new Date(data).toLocaleString() : 'N/A';
                }
            },
            {
                data: 'Resultado',
                render: function (data) {
                    return data || 'N/A';
                }
            },
            {
                data: 'ID',
                render: function (data, type, row) {
                    return `<div class="btn-group">
                        <button class="btn btn-sm btn-warning modificar" onclick="rellenarSolicitud(this, 0)" title="Editar solicitud">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn btn-sm btn-danger eliminar" onclick="rellenarSolicitud(this, 1)" title="Eliminar solicitud">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>`;
                },
                orderable: false
            }
        ]
    });

    // Registrar entrada al módulo
    registrarEntrada();

    // Inicializar select2 para todos los selects del modal
    $('#dependencia').select2({
        dropdownParent: $('#modalSolicitud'),
        width: '100%',
        placeholder: 'Seleccione una dependencia'
    });
    
    $('#solicitante').select2({
        dropdownParent: $('#modalSolicitud'),
        width: '100%',
        placeholder: 'Seleccione un solicitante'
    });
    
    $('#equipo').select2({
        dropdownParent: $('#modalSolicitud'),
        width: '100%',
        placeholder: 'Seleccione un equipo (opcional)',
        allowClear: true
    });
    
    $('#area').select2({
        dropdownParent: $('#modalSolicitud'),
        width: '100%',
        placeholder: 'Seleccione un área'
    });
    
    $('#tecnico').select2({
        dropdownParent: $('#modalSolicitud'),
        width: '100%',
        placeholder: 'Seleccione un técnico'
    });

    // Deshabilitar campos inicialmente (excepto dependencia)
    $('#solicitante, #equipo, #area, #tecnico, #motivo').prop('disabled', true);
}

function cargarDatosIniciales() {
    cargarDependencias();
    cargarAreas();
}

// --- NUEVAS FUNCIONES PARA PAPELERA DE SOLICITUDES ---
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
        success: function (respuesta) {
            try {
                var lee = typeof respuesta === "string" ? JSON.parse(respuesta) : respuesta;
                if (lee.resultado === "consultar_eliminadas") {
                    TablaEliminados(lee.datos);
                } else if (lee.resultado === "error") {
                    mostrarError(lee.mensaje || "Error al consultar solicitudes eliminadas");
                } else {
                    mostrarError("Respuesta inesperada al consultar eliminados");
                }
            } catch (e) {
                mostrarError("Error procesando solicitudes eliminadas: " + (e.message || e));
            }
        },
        error: function (xhr) {
            mostrarError("Error de red al consultar eliminados");
        }
    });
}

// Renderizar tabla de eliminados y botón de restaurar
function TablaEliminados(arreglo) {
    if ($.fn.DataTable.isDataTable('#tablaEliminadas')) {
        $('#tablaEliminadas').DataTable().destroy();
    }
    $('#tablaEliminadas tbody').empty();
    $('#tablaEliminadas').DataTable({
        data: arreglo,
        columns: [
            {data: 'nro_solicitud'},
            {data: 'solicitante'},
            {data: 'cedula'},
            {data: 'dependencia'},
            {data: 'motivo'},
            {
                data: null,
                render: function (data, type, row) {
                    return `<button class="btn btn-success btn-restaurar-solicitud" data-nrosol="${row.nro_solicitud}">
                        <i class="fa-solid fa-recycle"></i>
                    </button>`;
                }
            }
        ],
        language: {
            "decimal": "",
            "emptyTable": "No hay datos disponibles en la tabla",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron registros coincidentes",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });
}

// --- FUNCIONES DE CARGA DE DATOS ---
function cargarAreas(areaActual = null) {
    $.ajax({
        url: '?page=solicitud',
        type: 'POST',
        data: { action: 'load_areas' },
        dataType: 'json',
        success: function (response) {
            if (response.resultado === 'consultar_areas') {
                const $areaSelect = $('#area');
                $areaSelect.empty().append('<option value="" selected disabled>Seleccione un área</option>');
                
                // Usar las áreas que vienen de la base de datos
                response.datos.forEach(area => {
                    $areaSelect.append(`<option value="${area.id_tipo_servicio}">${area.nombre_servicio}</option>`);
                });
                
                if (areaActual) {
                    $areaSelect.val(areaActual).trigger('change');
                }
            } else {
                mostrarError('Error al cargar áreas: ' + (response.mensaje || 'Respuesta inesperada'));
            }
        },
        error: function (xhr) {
            mostrarError('Error de red al cargar áreas: ' + xhr.responseText);
        }
    });
}

// Evento para restaurar solicitud
$(document).on('click', '.btn-restaurar-solicitud', function () {
    var nro_solicitud = $(this).data('nrosol');
    Swal.fire({
        title: '¿Restaurar Solicitud?',
        text: "¿Está seguro que desea restaurar esta solicitud?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, restaurar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            restaurarSolicitud(nro_solicitud);
        }
    });
});

function restaurarSolicitud(nro_solicitud) {
    var datos = new FormData();
    datos.append('restaurar', 'restaurar');
    datos.append('nrosol', nro_solicitud);
    $.ajax({
        url: "",
        type: "POST",
        data: datos,
        processData: false,
        contentType: false,
        success: function (respuesta) {
            try {
                var lee = typeof respuesta === "string" ? JSON.parse(respuesta) : respuesta;
                if (lee.resultado === "restaurar" && lee.bool) {
                    Swal.fire('¡Restaurado!', 'La solicitud ha sido restaurada.', 'success');
                    consultarEliminadas();
                    if (typeof recargarTabla === "function") recargarTabla();
                } else {
                    Swal.fire('Error', lee.mensaje || "No se pudo restaurar la solicitud", 'error');
                }
            } catch (e) {
                Swal.fire('Error', "Error procesando la respuesta", 'error');
            }
        },
        error: function () {
            Swal.fire('Error', "No se pudo restaurar la solicitud", 'error');
        }
    });
}

function gestionarEstadoCampos(habilitar) {
    const campos = ['#dependencia', '#solicitante', '#equipo', '#area', '#tecnico', '#motivo'];

    campos.forEach(selector => {
        const elemento = $(selector);
        if (elemento.hasClass('select2-hidden-accessible')) {
            elemento.prop('disabled', !habilitar).trigger('change');
        } else {
            elemento.prop('disabled', !habilitar);
        }
    });
}

async function rellenarSolicitud(pos, accion) {
    try {
        const linea = $(pos).closest('tr');
        const idSolicitud = linea.find("td:eq(0)").text();
        const estado = linea.find("td:eq(6)").text().trim();

        // Agregar campo oculto para el estado
        if (!$('#estado_solicitud').length) {
            $('#formSolicitud').append('<input type="hidden" id="estado_solicitud" name="estado">');
        }
        $('#estado_solicitud').val(estado);

        // Consultar datos completos de la solicitud y hoja de servicio
        const response = await $.ajax({
            url: '',
            type: 'POST',
            data: {action: 'consultar_por_id', id: idSolicitud},
            dataType: 'json'
        });

        if (!response || !response.datos) {
            mostrarError("No se encontraron datos de la solicitud.");
            return;
        }

        const datos = response.datos;

        // Configurar el modal según la acción
        if (accion === 0) { // Editar
            $("#modalSolicitudLabel").text("Editar Solicitud");
            $("#btnGuardar").text("Actualizar").attr("name", "modificar");
            gestionarEstadoCampos(true);
        } else { // Eliminar
            $("#modalSolicitudLabel").text("Eliminar Solicitud");
            $("#btnGuardar").text("Eliminar").attr("name", "eliminar");
            gestionarEstadoCampos(false);
        }

        limpiarFormulario();

        // Cargar dependencias y seleccionar la correspondiente
        await cargarDependencias();
        if (datos.id_dependencia) {
            $('#dependencia').val(datos.id_dependencia).trigger('change');

            // Esperar a que se carguen los solicitantes
            await cargarSolicitantes(datos.id_dependencia);

            // Seleccionar solicitante si existe
            if (datos.cedula_solicitante) {
                $('#solicitante').val(datos.cedula_solicitante).trigger('change');

                // Cargar equipos específicos del solicitante, pasando el nro de solicitud actual
                await cargarEquiposPorSolicitante(datos.cedula_solicitante, datos.nro_solicitud);
                if (datos.id_equipo) {
                    $('#equipo').val(datos.id_equipo).trigger('change');
                }
            }
        }

        // Cargar áreas y seleccionar la correspondiente
        await cargarAreas();
        if (datos.id_tipo_servicio) {
            $('#area').val(datos.id_tipo_servicio).trigger('change');

            // Cargar técnicos del área seleccionada y luego seleccionar el técnico guardado
            await cargarTecnicosPorArea(datos.id_tipo_servicio);
            if (datos.cedula_tecnico) {
                setTimeout(function() {
                    $('#tecnico').val(datos.cedula_tecnico).trigger('change');
                }, 100);
            }
        }

        // Rellenar otros campos
        $("#nroSolicitud").val(datos.nro_solicitud);
        $("#motivo").val(datos.motivo);

        // Forzar validación de todos los campos
        setTimeout(() => {
            SistemaValidacion.validarFormulario(elementosSolicitud);
        }, 200);

        // Mostrar el modal
        $("#modalSolicitud").modal("show");

    } catch (error) {
        console.error("Error al rellenar solicitud:", error);
        mostrarError("Ocurrió un error al cargar los datos de la solicitud");
    }
}

function recargarTabla() {
    tablaSolicitudes.ajax.reload(null, false);
}

function cargarSolicitudes() {
    tablaSolicitudes.ajax.reload();
}

async function cargarDependencias() {
    try {
        const response = await $.ajax({
            url: '',
            type: 'POST',
            data: {action: 'load_dependencias'},
            dataType: 'json'
        });

        if (response && response.datos) {
            const $select = $('#dependencia');
            $select.empty().append('<option value="" selected disabled>Seleccione una dependencia</option>');

            response.datos.forEach(dep => {
                $select.append(`<option value="${dep.id}">${dep.nombre}</option>`);
            });
        }
    } catch (error) {
        console.error('Error al cargar dependencias:', error);
        throw error;
    }
}

async function cargarSolicitantes(dependenciaId) {
    try {
        const response = await $.ajax({
            url: '',
            type: 'POST',
            data: {
                action: 'load_solicitantes',
                dependencia_id: dependenciaId
            },
            dataType: 'json'
        });

        const $select = $('#solicitante');
        $select.empty().append('<option value="" selected disabled>Seleccione un solicitante</option>');

        if (response && response.datos) {
            response.datos.forEach(sol => {
                const nombreCompleto = `${sol.nombre || ''} (${sol.cedula || ''})`.trim();
                $select.append(`<option value="${sol.cedula}">${nombreCompleto}</option>`);
            });
        }
    } catch (error) {
        console.error('Error al cargar solicitantes:', error);
        throw error;
    }
}

// Nueva función para cargar técnicos por área
async function cargarTecnicosPorArea(areaId) {
    const $select = $('#tecnico');
    $select.empty().append('<option value="">Cargando técnicos...</option>');
    $select.val('').trigger('change');
    try {
        const response = await $.ajax({
            url: '',
            type: 'POST',
            data: {
                action: 'load_tecnicos_por_area',
                area_id: areaId
            },
            dataType: 'json'
        });
        $select.empty().append('<option value="" selected disabled>Seleccione un técnico</option>');
        if (response && response.resultado === 'success' && Array.isArray(response.datos)) {
            response.datos.forEach(tec => {
                let texto = tec.nombre;
                if (typeof tec.hojas_mes !== 'undefined') {
                    texto += ` (${tec.hojas_mes} hojas activas este mes)`;
                }
                $select.append(new Option(texto, tec.cedula_empleado));
            });
        } else {
            $select.append('<option value="">No hay técnicos disponibles</option>');
        }
        $select.select2({
            dropdownParent: $('#modalSolicitud'),
            width: '100%',
            placeholder: 'Seleccione un técnico'
        });

        // Si hay técnicos, seleccionar el primero por defecto
        if (response && response.resultado === 'success' && response.datos.length > 0) {
            const primerTecnico = response.datos[0].cedula_empleado;
            $select.val(primerTecnico).trigger('change');
        } else {
            $select.val('').trigger('change');
        }
    } catch (error) {
        $select.empty().append('<option value="">Error al cargar técnicos</option>');
        $select.val('').trigger('change');
        mostrarError("Error al cargar técnicos del área");
    }
}

function enviarFormulario() {
    const accion = $('#btnGuardar').attr('name');

    // Solo validar si la acción no es 'eliminar'
    if (accion !== 'eliminar' && !validarFormulario()) {
        return;
    }

    const formData = new FormData($('#formSolicitud')[0]);

    // Asegurarnos de incluir el nroSolicitud aunque esté oculto
    if ($('#nroSolicitud').val()) {
        formData.append('nroSolicitud', $('#nroSolicitud').val());
    }
    // Asegurarnos de incluir el técnico seleccionado
    if ($('#tecnico').val()) {
        formData.set('tecnico', $('#tecnico').val());
    }

    formData.append(accion, accion);

    $.ajax({
        url: '',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function () {
            $('#btnGuardar').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');
        },
        success: function (response) {
            if (response.resultado === 'success') {
                $('#modalSolicitud').modal('hide');
                mostrarExito(response.mensaje || 'Operación realizada con éxito');
                recargarTabla();
            } else {
                mostrarError(response.mensaje || 'Error al procesar la solicitud');
            }
        },
        error: function (xhr, status, error) {
            try {
                const response = JSON.parse(xhr.responseText);
                mostrarError(response.mensaje || 'Error en la solicitud');
            } catch (e) {
                mostrarError('Error en la solicitud: ' + error);
            }
        },
        complete: function () {
            // Restaurar el texto del botón según la acción
            let buttonText = 'Guardar';
            if (accion === 'modificar') {
                buttonText = 'Actualizar';
            } else if (accion === 'eliminar') {
                buttonText = 'Eliminar';
            }
            $('#btnGuardar').prop('disabled', false).text(buttonText);
        }
    });
}

function validarFormulario() {
    return SistemaValidacion.validarFormulario(elementosSolicitud);
}

function getBadgeColor(estado) {
    if (!estado) return 'secondary';

    switch (estado.toLowerCase()) {
        case 'pendiente': return 'warning';
        case 'en proceso': return 'info';
        case 'finalizado': return 'success';
        default: return 'secondary';
    }
}

function limpiarFormulario() {
    $('#formSolicitud')[0].reset();
    $('#nroSolicitud').val('');
    $('#solicitante').empty().append('<option value="" selected disabled>Seleccione una dependencia primero</option>');
    $('#equipo').empty().append('<option value="" selected>No especificar equipo</option>');
    $('#tecnico').empty().append('<option value="" selected disabled>Seleccione un técnico</option>');
    $('#motivo').removeClass('is-valid is-invalid');
    
    // Limpiar validación
    SistemaValidacion.limpiarValidacion(elementosSolicitud);
    
    // Deshabilitar campos secuenciales
    $('#solicitante, #equipo, #area, #tecnico, #motivo').prop('disabled', true);
    $('#dependencia').prop('disabled', false);
}

function registrarEntrada() {
    $.ajax({
        url: '',
        type: 'POST',
        data: {entrada: 'entrada'}
    });
}

function mostrarExito(mensaje) {
    Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: mensaje,
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}

function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje,
        toast: true,
        position: 'top-end'
    });
}

// Nueva función: cargar equipos por solicitante
async function cargarEquiposPorSolicitante(cedula, nro_solicitud = null) {
    const $select = $('#equipo');
    $select.empty().append('<option value="">Cargando equipos...</option>');
    $select.val('').trigger('change');

    try {
        const ajaxData = {
            peticion: 'consultar_equipos_empleado',
            cedula_empleado: cedula
        };

        if (nro_solicitud) {
            ajaxData.nro_solicitud = nro_solicitud;
        }

        const response = await $.ajax({
            type: 'POST',
            url: '?page=mis_servicios',
            data: ajaxData,
            dataType: 'json'
        });

        $select.empty();
        $select.append('<option value="">No especificar equipo</option>');

        if (response && Array.isArray(response.datos) && response.datos.length > 0) {
            response.datos.forEach(equipo => {
                let texto = equipo.serial;
                if (equipo.tipo_equipo) {
                    texto += ` - ${equipo.tipo_equipo}`;
                }
                $select.append(new Option(texto, equipo.id_equipo));
            });
        } else {
            $select.append('<option value="">No hay equipos asignados</option>');
        }

        $select.select2({
            dropdownParent: $('#modalSolicitud'),
            width: '100%',
            placeholder: 'Seleccione un equipo (opcional)',
            allowClear: true
        });

        $select.val('').trigger('change');

    } catch (error) {
        console.error('Error al cargar equipos del solicitante:', error);
        $select.empty().append('<option value="">Error al cargar equipos</option>');
        $select.val('').trigger('change');
        mostrarError("Error al cargar equipos del solicitante");
    }
}