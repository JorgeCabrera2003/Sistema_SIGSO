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
}

async function rellenarSolicitud(pos, accion) {
    try {
        // Obtener la fila del registro
        const linea = $(pos).closest('tr');
        
        // Obtener los datos de la fila
        const idSolicitud = linea.find("td:eq(0)").text();
        const cedulaSolicitante = linea.find("td:eq(2)").text();
        const motivo = linea.find("td:eq(5)").text();
        const estado = linea.find("td:eq(6)").text();
        const dependencia = linea.find("td:eq(3)").text();
        const equipo = linea.find("td:eq(4)").text();
        const resultado = linea.find("td:eq(8)").text();

        // Configurar el modal según la acción
        if (accion === 0) { // Editar
            $("#modalSolicitudLabel").text("Editar Solicitud");
            $("#btnGuardar").text("Actualizar").attr("name", "modificar");
        } else { // Eliminar
            $("#modalSolicitudLabel").text("Eliminar Solicitud");
            $("#btnGuardar").text("Eliminar").attr("name", "eliminar");
        }

        // Limpiar y cargar datos básicos
        limpiarFormulario();
        $("#nroSolicitud").val(idSolicitud);
        $("#motivo").val(motivo);

        // Cargar áreas de servicio
        await cargarAreas();
        
        // Cargar dependencias y seleccionar la correcta
        await cargarDependencias();
        
        if (dependencia) {
            // Buscar y seleccionar la dependencia
            const dependenciaId = buscarSelect("#dependencia", dependencia, "text");
            
            if (dependenciaId) {
                // Cargar solicitantes para la dependencia seleccionada
                await cargarSolicitantes(dependenciaId);
                
                // Seleccionar el solicitante si existe
                if (cedulaSolicitante) {
                    buscarSelect("#solicitante", cedulaSolicitante, "value");
                }

                // Cargar equipos para la dependencia
                await cargarEquipos(dependenciaId);
                
                // Seleccionar el equipo si existe
                if (equipo && equipo !== "N/A") {
                    const equipoId = equipo.split(" - ")[0];
                    buscarSelect("#equipo", equipoId, "text");
                }
            }
        }

        // Seleccionar el área de servicio basado en el estado o resultado
        if (estado.includes("Proceso")) {
            $("#area").val("1"); // Soporte técnico
        } else if (estado.includes("Finalizado")) {
            if (resultado.includes("Redes")) {
                $("#area").val("2"); // Redes
            } else if (resultado.includes("Telefonía")) {
                $("#area").val("3"); // Telefonía
            } else {
                $("#area").val("4"); // Electrónica
            }
        }

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

function enviarFormulario() {
    if (validarFormulario()) {
        const formData = new FormData($('#formSolicitud')[0]);
        const accion = $('#btnGuardar').attr('name');
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
                    console.log('Solicitud procesada:', response);
                    $('#modalSolicitud').modal('hide');
                    mostrarExito(response.mensaje);
                    recargarTabla();
                } else {
                    mostrarError(response.mensaje || 'Error al procesar la solicitud');
                }
            },
            error: function (xhr, status, error) {
                mostrarError('Error en la solicitud: ' + error);
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
    ['#dependencia', '#solicitante', '#area'].forEach(selector => {
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