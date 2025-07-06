$(document).ready(function () {
    // Inicialización
    inicializarComponentes();
    cargarDatosIniciales();
    configurarEventos();
});

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
            url: '?page=solicitud', // URL correcta
            type: 'POST',
            data: function (d) {
                d.consultar = 'consultar'; // Parámetro esperado por el backend
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
            <button class="btn btn-sm btn-warning" onclick="rellenarSolicitud(this, 0)" title="Editar solicitud">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <button class="btn btn-sm btn-danger" onclick="rellenarSolicitud(this, 1)" title="Eliminar solicitud">
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
    // Nuevo: inicializar select2 para técnico
    $('#tecnico').select2({
        dropdownParent: $('#modalSolicitud'),
        width: '100%',
        placeholder: 'Seleccione un técnico'
    });

    // Cuando cambia el solicitante, cargar equipos de ese solicitante
    $('#solicitante').on('change', function () {
        const cedula = $(this).val();
        if (cedula) {
            cargarEquiposPorSolicitante(cedula);
        } else {
            $('#equipo').empty().append('<option value="">No especificar equipo</option>').val('').trigger('change');
        }
    });

    // Cuando cambia el área, cargar técnicos del área
    $('#area').on('change', function () {
        const areaId = $(this).val();
        if (areaId) {
            cargarTecnicosPorArea(areaId);
        } else {
            $('#tecnico').empty().append('<option value="" selected disabled>Seleccione un técnico</option>').val('').trigger('change');
        }
    });
}

function cargarDatosIniciales() {
    // Cargar dependencias
    cargarDependencias();
}

function configurarEventos() {
    // Evento para el botón de refrescar
    $('#btn-refrescar').on('click', function () {
        recargarTabla();
    });

    // Evento para cambio de dependencia
    $('#dependencia').on('change', function () {
        const dependenciaId = $(this).val();
        if (dependenciaId) {
            cargarSolicitantes(dependenciaId);
            cargarEquipos(dependenciaId);
        } else {
            $('#solicitante').empty().append('<option value="" selected disabled>Seleccione un solicitante</option>');
            $('#equipo').empty().append('<option value="" selected>No especificar equipo</option>');
        }
    });

    // Evento para enviar el formulario
    $('#formSolicitud').on('submit', function (e) {
        e.preventDefault();
        enviarFormulario();
    });

    // Validación del campo motivo
    $('#motivo').on('input', function () {
        validarMotivo($(this).val());
    });

    // Reemplazar botón de actualizar por solicitudes eliminadas
    $('#btn-solicitudes-eliminadas').on('click', function () {
        consultarEliminadas();
        $('#modalEliminadas').modal('show');
    });
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
            { data: 'nro_solicitud' },
            { data: 'solicitante' },
            { data: 'cedula' },
            { data: 'dependencia' },
            { data: 'motivo' },
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
                    // Si tienes una función para recargar la tabla principal, llama aquí
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

async function rellenarSolicitud(pos, accion) {
    try {
        const linea = $(pos).closest('tr');
        const idSolicitud = linea.find("td:eq(0)").text();

        // Consultar datos completos de la solicitud y hoja de servicio
        const response = await $.ajax({
            url: '',
            type: 'POST',
            data: { action: 'consultar_por_id', id: idSolicitud },
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
        } else { // Eliminar
            $("#modalSolicitudLabel").text("Eliminar Solicitud");
            $("#btnGuardar").text("Eliminar").attr("name", "eliminar");
        }

        limpiarFormulario();

        // Cargar dependencias y seleccionar la correspondiente
        await cargarDependencias();
        if (datos.id_dependencia) {
            $('#dependencia').val(datos.id_dependencia).trigger('change');
            // Cargar solicitantes y equipos para la dependencia
            await cargarSolicitantes(datos.id_dependencia);
            await cargarEquipos(datos.id_dependencia);
        }

        // Seleccionar solicitante y equipo si existen
        if (datos.cedula_solicitante) {
            $('#solicitante').val(datos.cedula_solicitante);
        }
        if (datos.id_equipo) {
            $('#equipo').val(datos.id_equipo);
        }

        // Cargar áreas y seleccionar la correspondiente
        await cargarAreas();
        if (datos.id_tipo_servicio) {
            $('#area').val(datos.id_tipo_servicio);
        }

        // Rellenar otros campos
        $("#nroSolicitud").val(datos.nro_solicitud);
        $("#motivo").val(datos.motivo);

        // Mostrar el modal
        $("#modalSolicitud").modal("show");

    } catch (error) {
        console.error("Error al rellenar solicitud:", error);
        mostrarError("Ocurrió un error al cargar los datos de la solicitud");
    }
}

function buscarSelect(selector, valor, tipoBusqueda = "text") {
    const $select = $(selector);
    let encontrado = false;
    let valorEncontrado = null;

    $select.find("option").each(function() {
        const optionText = $(this).text();
        const optionValue = $(this).val();

        if ((tipoBusqueda === "text") && optionText.includes(valor)) {
            $(this).prop("selected", true);
            encontrado = true;
            valorEncontrado = optionValue;
            return false; // Salir del each
        } else if (tipoBusqueda === "value" && optionValue === valor) {
            $(this).prop("selected", true);
            encontrado = true;
            valorEncontrado = optionValue;
            return false; // Salir del each
        }
    });

    // Disparar evento change si es necesario
    if (encontrado) {
        $select.trigger("change");
    } else {
        $select.val($select.find("option:first").val());
    }

    return valorEncontrado;
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

async function cargarEquipos(dependenciaId) {
    try {
        const response = await $.ajax({
            url: '',
            type: 'POST',
            data: {
                action: 'load_equipos',
                dependencia_id: dependenciaId
            },
            dataType: 'json'
        });

        const $select = $('#equipo');
        $select.empty().append('<option value="" selected>No especificar equipo</option>');

        if (response && response.datos) {
            response.datos.forEach(equipo => {
                $select.append(`<option value="${equipo.id_equipo}">${equipo.serial} - ${equipo.tipo_equipo}</option>`);
            });
        }
    } catch (error) {
        console.error('Error al cargar equipos:', error);
        throw error;
    }
}

async function cargarAreas() {
    try {
        const response = await $.ajax({
            url: '',
            type: 'POST',
            data: {action: 'load_areas'},
            dataType: 'json'
        });

        if (response && response.datos) {
            const $select = $('#area');
            $select.empty().append('<option value="" selected disabled>Seleccione un área</option>');

            response.datos.forEach(area => {
                $select.append(`<option value="${area.id_tipo_servicio}">${area.nombre_tipo_servicio}</option>`);
            });
        }
    } catch (error) {
        console.error('Error al cargar áreas:', error);
        throw error;
    }
}

    $('#btn-nueva-solicitud').on('click', function () {
        limpiarFormulario();
        $('#modalSolicitud').modal('show');
        $('#modalSolicitudLabel').text('Nueva Solicitud');
        $('#btnGuardar').text('Guardar').attr('name', 'registrar');
    });

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
                    texto += ` (${tec.hojas_mes} hojas finalizadas este mes)`;
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
        $select.val('').trigger('change');
    } catch (error) {
        $select.empty().append('<option value="">Error al cargar técnicos</option>');
        $select.val('').trigger('change');
        mostrarError("Error al cargar técnicos del área");
    }
}

function enviarFormulario() {
    if (validarFormulario()) {
        const formData = new FormData($('#formSolicitud')[0]);
        const accion = $('#btnGuardar').attr('name');
        
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
                $('#btnGuardar').prop('disabled', false).text(accion === 'registrar' ? 'Guardar' : 'Actualizar');
            }
        });
    }
}

function validarFormulario() {
    let valido = true;

    // Validar motivo
    if (!validarMotivo($('#motivo').val())) {
        valido = false;
    }

    // Validar selects requeridos
    ['#dependencia', '#solicitante', '#area', '#tecnico'].forEach(selector => {
        if (!$(selector).val()) {
            $(selector).addClass('is-invalid');
            valido = false;
        } else {
            $(selector).removeClass('is-invalid');
        }
    });

    if (!valido) {
        mostrarError('Complete todos los campos requeridos');
    }

    return valido;
}

function validarMotivo(texto) {
    const regex = /^[\w\sáéíóúüñÑçÇ.,-]{3,200}$/;
    const valido = regex.test(texto);

    if (valido) {
        $('#motivo').removeClass('is-invalid').addClass('is-valid');
    } else {
        $('#motivo').removeClass('is-valid').addClass('is-invalid');
        $('#motivo').next('.invalid-feedback').text(
            texto.length < 3 ? 'El motivo debe tener al menos 3 caracteres' :
                texto.length > 200 ? 'El motivo no puede exceder 200 caracteres' :
                    'Solo se permiten letras, números y los símbolos ,.-'
        );
    }

    return valido;
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
}

function registrarEntrada() {
    $.ajax({
        url: '',
        type: 'POST',
        data: {entrada: 'entrada'}
    });
}

async function confirmarEliminacion(id) {
    const confirmado = await Swal.fire({
        title: '¿Eliminar Solicitud?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if (confirmado.isConfirmed) {
        $.ajax({
            url: '',
            type: 'POST',
            data: {
                eliminar: 'eliminar',
                nrosol: id
            },
            dataType: 'json',
            success: function (response) {
                if (response.resultado === 'success') {
                    mostrarExito(response.mensaje);
                    recargarTabla();
                } else {
                    mostrarError(response.mensaje);
                }
            },
            error: function (xhr, status, error) {
                mostrarError('Error al eliminar la solicitud: ' + error);
            }
        });
    }
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

// Nueva función: cargar equipos por solicitante (como en mis_servicios)
async function cargarEquiposPorSolicitante(cedula) {
    const $select = $('#equipo');
    $select.empty().append('<option value="">Cargando equipos...</option>');
    $select.val('').trigger('change');

    try {
        const response = await $.ajax({
            type: 'POST',
            url: '?page=mis_servicios',
            data: {
                peticion: 'consultar_equipos_empleado',
                cedula_empleado: cedula
            },
            dataType: 'json'
        });

        $select.empty();
        $select.append('<option value="">No especificar equipo</option>');

        if (response && Array.isArray(response.datos) && response.datos.length > 0) {
            response.datos.forEach(equipo => {
                let texto = equipo.tipo_equipo || 'Equipo';
                if (equipo.serial) texto += ` (${equipo.serial})`;
                if (equipo.descripcion) texto += ` - ${equipo.descripcion}`;
                $select.append(
                    new Option(texto, equipo.id_equipo)
                );
            });
        } else {
            $select.append('<option value="">No tiene equipos asignados</option>');
        }

        // Re-inicializa select2 para refrescar opciones
        $select.select2({
            dropdownParent: $('#modalSolicitud'),
            width: '100%',
            placeholder: 'Seleccione un equipo (opcional)',
            allowClear: true
        });
        $select.val('').trigger('change');
    } catch (error) {
        console.error('Error al cargar equipos:', error);
        $select.empty().append('<option value="">Error al cargar equipos</option>');
        $select.val('').trigger('change');
        mostrarError("Error al cargar equipos del solicitante");
    }
}