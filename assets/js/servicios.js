$(document).ready(function() {
    // Inicialización de componentes
    inicializarComponentes();
    configurarEventos();
    
    // Registrar entrada al módulo
    registrarEntrada();
});

function inicializarComponentes() {
    // Inicializar DataTable para hojas de servicio
    window.tablaHojasServicio = $('#tablaHojasServicio').DataTable({
        language: {
            "decimal": "",
            "emptyTable": "No hay datos disponibles",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron registros",
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
            url: '?page=servicio',
            type: 'POST',
            data: {consultar: 'consultar'},
            dataType: 'json',
            dataSrc: function(response) {
                if (response.resultado === 'success') {
                    return response.datos;
                } else {
                    mostrarError(response.mensaje || 'Error al cargar datos');
                    return [];
                }
            }
        },
        columns: [
            {data: 'codigo_hoja_servicio'},
            {data: 'nro_solicitud'},
            {data: 'nombre_tipo_servicio'},
            {data: 'nombre_solicitante'},
            {
                data: 'tipo_equipo',
                render: function(data) {
                    return data || 'N/A';
                }
            },
            {
                data: 'nombre_marca',
                render: function(data) {
                    return data || 'N/A';
                }
            },
            {
                data: 'serial',
                render: function(data) {
                    return data || 'N/A';
                }
            },
            {
                data: 'codigo_bien',
                render: function(data) {
                    return data || 'N/A';
                }
            },
            {data: 'motivo'},
            {
                data: 'fecha_solicitud',
                render: function(data) {
                    return data ? new Date(data).toLocaleString() : 'N/A';
                }
            },
            {
                data: 'codigo_hoja_servicio',
                render: function(data, type, row) {
                    let botones = `
                        <div class="btn-group">
                            <button class="btn btn-sm btn-info" onclick="consultarHoja("${data}")" title="Ver detalles">
                                <i class="fa-solid fa-eye"></i>
                            </button>`;
                    
                    if (row.estatus === 'A') {
                        botones += `
                            <button class="btn btn-sm btn-success" onclick="finalizarHoja("${data}")" title="Finalizar hoja">
                                <i class="fa-solid fa-check"></i>
                            </button>`;
                    }
                    
                    botones += `</div>`;
                    return botones;
                }
            }
        ]
    });
}

function configurarEventos() {
    // Evento para el botón de nueva hoja
    $('#btn-nueva-hoja').on('click', function() {
        mostrarModalNuevaHoja();
    });
    
    // Evento para el botón de refrescar
    $('#btn-refrescar').on('click', function() {
        recargarTabla();
    });
}

function mostrarModalNuevaHoja() {
    limpiarModalHoja();
    $('#modalHojaServicio').modal('show');
}

function limpiarModalHoja() {
    $('#formHojaServicio')[0].reset();
    $('#detallesContainer').empty();
    $('#codigoHoja').val('');
    $('.invalid-feedback').text('');
    $('.is-invalid').removeClass('is-invalid');
}

function consultarHoja(id) {
    $.ajax({
        url: '?page=servicio',
        type: 'POST',
        data: {
            consultar: 'consultar',
            codigo_hoja_servicio: id
        },
        dataType: 'json',
        beforeSend: function() {
            mostrarCarga();
        },
        success: function(response) {
            if (response.resultado === 'success') {
                mostrarDetallesHoja(response.datos);
            } else {
                mostrarError(response.mensaje || 'Error al consultar hoja');
            }
        },
        error: function(xhr, status, error) {
            mostrarError('Error en la solicitud: ' + error);
        },
        complete: function() {
            ocultarCarga();
        }
    });
}

function mostrarDetallesHoja(datos) {
    limpiarModalHoja();
    
    // Llenar datos básicos
    $('#codigoHoja').val(datos.codigo_hoja_servicio);
    $('#nroSolicitud').val(datos.nro_solicitud);
    $('#tipoServicio').val(datos.nombre_tipo_servicio);
    $('#solicitante').val(datos.nombre_solicitante);
    $('#dependencia').val(datos.nombre_dependencia);
    $('#unidad').val(datos.nombre_unidad);
    $('#equipo').val(datos.tipo_equipo || 'N/A');
    $('#marca').val(datos.nombre_marca || 'N/A');
    $('#serial').val(datos.serial || 'N/A');
    $('#codigoBien').val(datos.codigo_bien || 'N/A');
    $('#motivo').val(datos.motivo);
    $('#fechaSolicitud').val(new Date(datos.fecha_solicitud).toLocaleString());
    
    // Mostrar detalles técnicos si existen
    if (datos.detalles && datos.detalles.length > 0) {
        const $container = $('#detallesContainer');
        datos.detalles.forEach(detalle => {
            $container.append(`
                <div class="row mb-2">
                    <div class="col-md-5">
                        <input type="text" class="form-control" value="${detalle.componente}" readonly>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" value="${detalle.detalle}" readonly>
                    </div>
                </div>
            `);
        });
    }
    
    // Configurar botones según estado
    if (datos.estatus === 'A') {
        $('#btnFinalizar').show();
    } else {
        $('#btnFinalizar').hide();
    }
    
    $('#modalHojaServicio').modal('show');
}

function finalizarHoja(id) {
    Swal.fire({
        title: 'Finalizar Hoja de Servicio',
        html: `
            <form id="formFinalizar">
                <div class="form-group">
                    <label for="resultado">Resultado</label>
                    <select id="resultado" class="form-control" required>
                        <option value="">Seleccione un resultado</option>
                        <option value="Reparado">Reparado</option>
                        <option value="Reemplazado">Reemplazado</option>
                        <option value="No reparable">No reparable</option>
                        <option value="Enviado a servicio técnico">Enviado a servicio técnico</option>
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label for="observacion">Observaciones</label>
                    <textarea id="observacion" class="form-control" rows="3"></textarea>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Finalizar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const resultado = $('#resultado').val();
            const observacion = $('#observacion').val();
            
            if (!resultado) {
                Swal.showValidationMessage('El resultado es requerido');
                return false;
            }
            
            return {resultado, observacion};
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const {resultado, observacion} = result.value;
            
            $.ajax({
                url: '?page=servicio',
                type: 'POST',
                data: {
                    finalizar: 'finalizar',
                    codigo_hoja_servicio: id,
                    resultado_hoja_servicio: resultado,
                    observacion: observacion
                },
                dataType: 'json',
                beforeSend: function() {
                    mostrarCarga();
                },
                success: function(response) {
                    if (response.resultado === 'success') {
                        mostrarExito(response.mensaje);
                        recargarTabla();
                    } else {
                        mostrarError(response.mensaje);
                    }
                },
                error: function(xhr, status, error) {
                    mostrarError('Error al finalizar hoja: ' + error);
                },
                complete: function() {
                    ocultarCarga();
                }
            });
        }
    });
}

function registrarEntrada() {
    $.ajax({
        url: '?page=servicio',
        type: 'POST',
        data: {entrada: 'entrada'}
    });
}

function recargarTabla() {
    tablaHojasServicio.ajax.reload(null, false);
}

function mostrarCarga() {
    $('#loading').show();
}

function ocultarCarga() {
    $('#loading').hide();
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