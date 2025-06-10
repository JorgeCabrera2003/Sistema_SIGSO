$(document).ready(function () {
    // Inicialización
    consultar();
    registrarEntrada();
    inicializarEventos();
    capaValidar();

    // Configuración del modal
    $('#solicitud').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const modal = $(this);
        const esEdicion = button.data('accion') === 'editar';

        if (esEdicion) {
            cargarDatosSolicitud(button.data('id'));
            modal.find('.modal-title').text('Editar Solicitud');
            $("#enviar2").text("Modificar").attr('name', 'modificar');
        } else {
            limpiarFormularioModal();
            modal.find('.modal-title').text('Nueva Solicitud');
            $("#enviar2").text("Registrar").attr('name', 'registrar');
        }
    });
});

// Función para inicializar eventos
function inicializarEventos() {
    // Evento para el botón de enviar en el modal
    $("#enviar2").on("click", function (e) {
        e.preventDefault();
        const accion = $(this).attr('name');
        const formularioValido = validarFormularioModal();

        if (formularioValido) {
            const datos = new FormData($('#solicitud form')[0]);
            datos.append(accion, accion);
            
            // Datos específicos para modificación
            if (accion === "modificar") {
                datos.append('nrosol', $("#nro").val());
            }

            enviaAjax(datos);
        }
    });

    // Evento para cambio de dependencia en el modal
    $("#dependencia2").on("change", function () {
        const dependenciaId = $(this).val();
        if (dependenciaId) {
            cargarEquiposPorDependencia(dependenciaId);
            cargarSolicitantesPorDependencia(dependenciaId);
        } else {
            $("#equipo2").empty().append('<option value="" selected>Seleccionar</option>');
            $("#solicitante2").empty().append('<option value="" selected hidden>Seleccionar solicitante</option>');
        }
        habilitarBotonEnviar();
    });
}

// Función para consultar solicitudes
function consultar() {
    const datos = new FormData();
    datos.append('consultar', 'consultar');
    enviaAjax(datos);
}

// Función para registrar entrada al módulo
function registrarEntrada() {
    const datos = new FormData();
    datos.append('entrada', 'entrada');
    enviaAjax(datos);
}

// Función para cargar datos de una solicitud específica
function cargarDatosSolicitud(id) {
    $.ajax({
        url: '',
        type: 'POST',
        data: { action: 'consultar_por_id', id: id },
        dataType: 'json',
        success: function(response) {
            if (response.resultado === "success") {
                const solicitud = response.datos;
                
                // Llenar campos básicos
                $("#nro").val(solicitud.nro_solicitud);
                $("#motivo2").val(solicitud.motivo);
                
                // Seleccionar área
                $("#area2").val(solicitud.id_area);
                
                // Cargar dependencia y luego los selects dependientes
                cargarDependencias().then(() => {
                    if (solicitud.id_dependencia) {
                        $("#dependencia2").val(solicitud.id_dependencia).trigger('change');
                        
                        // Esperar a que se carguen los selects dependientes
                        setTimeout(() => {
                            if (solicitud.cedula_solicitante) {
                                $("#solicitante2").val(solicitud.cedula_solicitante);
                            }
                            if (solicitud.serial_equipo) {
                                $("#equipo2").val(solicitud.serial_equipo);
                            }
                            habilitarBotonEnviar();
                        }, 500);
                    }
                });
            } else {
                mensajes("error", null, "Error al cargar datos", response.mensaje);
            }
        },
        error: function(xhr, status, error) {
            mensajes("error", null, "Error al cargar datos", error);
        }
    });
}

// Función para cargar dependencias
function cargarDependencias() {
    return $.ajax({
        url: '',
        type: 'POST',
        data: { action: 'load_dependencias' },
        dataType: 'json',
        success: function(response) {
            const $select = $('#dependencia2');
            $select.empty().append('<option value="" selected hidden>Seleccionar</option>');
            
            if (response && response.length > 0) {
                response.forEach(dep => {
                    $select.append(`<option value="${dep.id}">${dep.nombre}</option>`);
                });
            } else {
                $select.append('<option value="" selected hidden>No hay dependencias registradas</option>');
            }
        },
        error: function() {
            mensajes('error', null, 'Error al cargar dependencias');
        }
    });
}

// Función para cargar equipos por dependencia
function cargarEquiposPorDependencia(dependenciaId, equipoSeleccionado = '') {
    if (!dependenciaId) return;

    $.ajax({
        url: '',
        type: 'POST',
        data: { action: 'load_equipos', dependencia_id: dependenciaId },
        dataType: 'json',
        success: function(response) {
            const $select = $('#equipo2');
            $select.empty().append('<option value="" selected>Seleccionar</option>');
            
            if (response && response.length > 0) {
                response.forEach(equipo => {
                    const selected = equipo.serial == equipoSeleccionado ? 'selected' : '';
                    $select.append(`<option value="${equipo.serial}" ${selected}>${equipo.serial} - ${equipo.tipo}</option>`);
                });
            } else {
                $select.append('<option value="" selected>No hay equipos registrados</option>');
            }
        },
        error: function() {
            mensajes('error', null, 'Error al cargar equipos');
        }
    });
}

// Función para cargar solicitantes por dependencia
function cargarSolicitantesPorDependencia(dependenciaId, solicitanteSeleccionado = '') {
    if (!dependenciaId) return;

    $.ajax({
        url: '',
        type: 'POST',
        data: { action: 'load_solicitantes', dependencia_id: dependenciaId },
        dataType: 'json',
        success: function(response) {
            const $select = $('#solicitante2');
            $select.empty().append('<option value="" selected hidden>Seleccionar solicitante</option>');
            
            if (response && response.length > 0) {
                response.forEach(solicitante => {
                    const selected = solicitante.cedula == solicitanteSeleccionado ? 'selected' : '';
                    $select.append(`<option value="${solicitante.cedula}" ${selected}>${solicitante.nombre} - ${solicitante.cedula}</option>`);
                });
            } else {
                $select.append('<option value="" selected hidden>No hay solicitantes registrados</option>');
            }
            
            // Validar si hay un solicitante seleccionado previamente
            if (solicitanteSeleccionado && $select.find(`option[value="${solicitanteSeleccionado}"]`).length > 0) {
                $select.val(solicitanteSeleccionado);
            }
            
            habilitarBotonEnviar();
        },
        error: function() {
            mensajes('error', null, 'Error al cargar solicitantes');
        }
    });
}

// Capa de validación
function capaValidar() {
    // Validación para el motivo en el modal
    $("#motivo2").on("keypress", function (e) {
        validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.,\b]*$/, e);
    });
    
    $("#motivo2").on("keyup", function () {
        validarKeyUp(
            /^[0-9 a-zA-ZáéíóúüñÑçÇ -.,]{3,200}$/, 
            $(this), 
            $("#smotivo2"), 
            "El motivo debe tener entre 3 y 200 caracteres"
        );
        habilitarBotonEnviar();
    });
    
    // Validación para los selects en el modal
    $("#area2, #dependencia2, #solicitante2").on("change", function() {
        $(this).removeClass('is-invalid');
        habilitarBotonEnviar();
    });
}

// Función para validar el formulario del modal
function validarFormularioModal() {
    let valido = true;
    
    // Validar motivo
    if (validarKeyUp(
        /^[0-9 a-zA-ZáéíóúüñÑçÇ -.,]{3,200}$/, 
        $("#motivo2"), 
        $("#smotivo2"), 
        ""
    ) == 0) {
        $("#motivo2").addClass('is-invalid');
        valido = false;
    }
    
    // Validar área
    if ($("#area2").val() === "") {
        $("#area2").addClass('is-invalid');
        valido = false;
    }
    
    // Validar dependencia
    if ($("#dependencia2").val() === "") {
        $("#dependencia2").addClass('is-invalid');
        valido = false;
    }
    
    // Validar solicitante
    if ($("#solicitante2").val() === "") {
        $("#solicitante2").addClass('is-invalid');
        valido = false;
    }
    
    if (!valido) {
        mensajes("error", 10000, "Verifica", "Complete todos los campos requeridos");
        return false;
    }
    
    return true;
}

// Función para habilitar/deshabilitar el botón de enviar en el modal
function habilitarBotonEnviar() {
    const motivoValido = validarKeyUp(
        /^[0-9 a-zA-ZáéíóúüñÑçÇ -.,]{3,200}$/, 
        $("#motivo2"), 
        $("#smotivo2"), 
        ""
    );
    
    const selectsValidos = (
        $("#area2").val() !== "" &&
        $("#dependencia2").val() !== "" &&
        $("#solicitante2").val() !== ""
    );
    
    $("#enviar2").prop("disabled", !(motivoValido && selectsValidos));
}

// Función para enviar datos via AJAX
function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        timeout: 10000,
        beforeSend: function() {
            // Mostrar loader si es necesario
        },
        success: function(respuesta) {
            try {
                const response = JSON.parse(respuesta);
                
                switch(response.resultado) {
                    case "success":
                        $("#solicitud").modal("hide");
                        mensajes("success", 10000, response.mensaje, null);
                        consultar();
                        break;
                        
                    case "consultar":
                        crearDataTable(response.datos);
                        break;
                        
                    case "error":
                        mensajes("error", null, response.mensaje, null);
                        break;
                        
                    case "entrada":
                        // No action needed
                        break;
                        
                    default:
                        mensajes("error", null, "Respuesta no reconocida", null);
                }
            } catch (e) {
                mensajes("error", null, "Error en JSON", `${e.name}: ${e.message}`);
            }
        },
        error: function(xhr, status, error) {
            if (status === "timeout") {
                mensajes("error", null, "Servidor ocupado", "Intente de nuevo");
            } else {
                mensajes("error", null, "Error en la solicitud", error);
            }
        },
        complete: function() {
            // Ocultar loader si es necesario
        }
    });
}

// Función para crear la tabla DataTable (sin modificaciones)
function crearDataTable(datos) {
    if ($.fn.DataTable.isDataTable('#tabla1')) {
        $('#tabla1').DataTable().destroy();
    }
    
    $('#tabla1').DataTable({
        data: datos,
        columns: [
            { data: 'ID' },
            { data: 'Solicitante' },
            { data: 'Cedula' },
            { data: 'Equipo' },
            { data: 'Motivo' },
            { data: 'Estado' },
            { data: 'Inicio' },
            { data: 'Resultado' },
            {
                data: null, 
                render: function(data, type, row) {
                    return `
                        <button onclick="rellenar(this, 'editar')" class="btn btn-update" title="Modificar">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button onclick="rellenar(this, 'eliminar')" class="btn btn-danger" title="Eliminar">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        }
    });
}

// Función para rellenar el formulario al editar
function rellenar(boton, accion) {
    const linea = $(boton).closest('tr');
    const id = $(linea).find('td:eq(0)').text();
    
    if (accion === 'editar') {
        // Mostrar modal con datos para editar
        $('#solicitud').modal('show');
        cargarDatosSolicitud(id);
    } else {
        // Confirmar eliminación
        confirmarEliminacion(id);
    }
}

// Función para confirmar eliminación
async function confirmarEliminacion(id) {
    const confirmado = await confirmarAccion(
        "¿Eliminar Solicitud?", 
        "¿Está seguro que desea eliminar esta solicitud?", 
        "warning"
    );
    
    if (confirmado) {
        const datos = new FormData();
        datos.append('eliminar', 'eliminar');
        datos.append('nrosol', id);
        enviaAjax(datos);
    }
}

// Función para limpiar el formulario del modal
function limpiarFormularioModal() {
    $("#nro").val("");
    $("#motivo2").val("").removeClass("is-valid is-invalid");
    $("#area2").val("").removeClass("is-valid is-invalid");
    $("#dependencia2").val("").removeClass("is-valid is-invalid");
    $("#equipo2").empty().append('<option value="" selected>Seleccionar</option>');
    $("#solicitante2").empty().append('<option value="" selected hidden>Seleccionar solicitante</option>');
    $("#enviar2").prop("disabled", true);
}

// Funciones auxiliares de validación
function validarKeyPress(expresion, e) {
    const key = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (!expresion.test(key)) {
        e.preventDefault();
        return false;
    }
}

function validarKeyUp(expresion, campo, span, mensaje) {
    const valor = campo.val().trim();
    if (expresion.test(valor)) {
        campo.removeClass("is-invalid").addClass("is-valid");
        span.text("");
        return 1;
    } else {
        campo.removeClass("is-valid").addClass("is-invalid");
        span.text(mensaje);
        return 0;
    }
}

// Función para mostrar mensajes al usuario
function mensajes(tipo, tiempo, titulo, mensaje) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: tiempo || 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    
    Toast.fire({
        icon: tipo,
        title: titulo,
        text: mensaje || ''
    });
}

// Función para confirmar acciones
async function confirmarAccion(titulo, texto, icono) {
    const result = await Swal.fire({
        title: titulo,
        text: texto,
        icon: icono,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
    });
    
    return result.isConfirmed;
}