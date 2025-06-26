$(document).ready(function () {
    // Inicialización de la tabla de servicios
    inicializarTablaServicios();
    
    // Registrar entrada al módulo
    registrarEntrada();
    
    // Configurar eventos
    configurarEventos();
    
    // Cargar tipos de servicio para el modal
    cargarTiposServicio();
});

function inicializarTablaServicios() {
    window.tablaServicios = $('#tablaServicios').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '',
            type: 'POST',
            data: { 
                listar: 'listar',
                usuario: JSON.stringify({
                    nombre_usuario: '<?= $_SESSION["user"]["nombre_usuario"] ?>',
                    cedula: '<?= $_SESSION["user"]["cedula"] ?>',
                    id_rol: '<?= $_SESSION["user"]["id_rol"] ?>'
                })
            },
            dataSrc: function (json) {
                if (json.resultado === 'success') {
                    return json.datos;
                } else {
                    console.error('Error al cargar hojas:', json.mensaje);
                    mostrarError(json.mensaje || 'Error al cargar hojas de servicio');
                    return [];
                }
            }
        },
        columns: [
            { 
                data: null, 
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'nro_solicitud' },
            { data: 'nombre_tipo_servicio' },
            { 
                data: 'solicitante',
                render: function(data, type, row) {
                    return data || 'N/A';
                }
            },
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
            { data: 'motivo' },
            { 
                data: 'fecha_solicitud', 
                render: function(data) {
                    return data ? new Date(data).toLocaleString() : 'N/A';
                }
            },
            { 
                data: 'tecnico',
                render: function(data) {
                    return data || 'Sin asignar';
                }
            },
            { 
                data: 'estatus', 
                render: function(data) {
                    let badgeClass = 'secondary';
                    let text = 'Desconocido';
                    
                    if (data === 'A') {
                        badgeClass = 'info';
                        text = 'Activo';
                    } else if (data === 'I') {
                        badgeClass = 'success';
                        text = 'Finalizado';
                    } else if (data === 'E') {
                        badgeClass = 'danger';
                        text = 'Eliminado';
                    }
                    
                    return `<span class="badge bg-${badgeClass}">${text}</span>`;
                }
            },
            { 
                data: null, 
                render: function(data, type, row) {
                    let botones = '';
                    
                    // Botón ver detalles (todos pueden ver)
                    botones += `<button onclick="verDetalles(${row.codigo_hoja_servicio})" class="btn btn-info btn-sm" title="Ver Detalles">
                        <i class="bi bi-eye"></i>
                    </button>`;
                    
                    // Botones según permisos
                    if ("<?= $_SESSION['user']['id_rol'] == 5 ? 'true' : 'false' ?>") {
                        // Superusuario puede editar y eliminar
                        botones += `<button onclick="editarHoja(${row.codigo_hoja_servicio})" class="btn btn-warning btn-sm" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </button>`;
                        
                        botones += `<button onclick="eliminarHoja(${row.codigo_hoja_servicio})" class="btn btn-danger btn-sm" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>`;
                    } else if (row.estatus === 'A' && (!row.cedula_tecnico || row.cedula_tecnico === "<?= $_SESSION['user']['cedula'] ?>")) {
                        // Técnico puede tomar o finalizar hojas de su área
                        if (!row.cedula_tecnico) {
                            botones += `<button onclick="tomarHoja(${row.codigo_hoja_servicio})" class="btn btn-primary btn-sm" title="Tomar Servicio">
                                <i class="bi bi-hand-index-thumb"></i>
                            </button>`;
                        } else {
                            botones += `<button onclick="finalizarHoja(${row.codigo_hoja_servicio})" class="btn btn-success btn-sm" title="Finalizar">
                                <i class="bi bi-check-circle"></i>
                            </button>`;
                        }
                    }
                    
                    return `<div class="btn-group">${botones}</div>`;
                }
            }
        ],
        responsive: true,
        order: [[1, 'desc']],
        dom: '<"top"lf>rt<"bottom"ip><"clear">',
        processing: true,
        serverSide: false
    });
}

function configurarEventos() {
    // Evento para el botón de nueva hoja de servicio
    $('#btn-registrar').on('click', function() {
        abrirModalRegistro();
    });
    
    // Evento para el botón de refrescar
    $('#btn-refrescar').on('click', function() {
        refrescarTabla();
    });
}

function abrirModalRegistro() {
    // Limpiar el modal
    $('#modalHoja')[0].reset();
    $('#codigo_hoja_servicio').val('');
    $('#modalHojaLabel').text('Nueva Hoja de Servicio');
    $('#btnGuardar').text('Registrar').attr('name', 'registrar');
    
    // Mostrar campos según acción
    $('#fila-resultado, #fila-observacion, #fila-detalles').hide();
    
    // Mostrar el modal
    $('#modalHoja').modal('show');
}

function verDetalles(codigo) {
    $.ajax({
        url: '',
        type: 'POST',
        data: {
            consultar: 'consultar',
            codigo_hoja_servicio: codigo
        },
        dataType: 'json',
        beforeSend: function() {
            // Mostrar spinner de carga
        },
        success: function(response) {
            if (response.resultado === 'success') {
                // Llenar los datos en el modal de detalles
                $('#detalle-solicitante').text(response.datos.nombre_solicitante);
                $('#detalle-equipo').text(
                    `${response.datos.tipo_equipo || 'N/A'} - ${response.datos.nombre_marca || ''} (${response.datos.serial || 'N/A'})`
                );
                $('#detalle-motivo').text(response.datos.motivo);
                $('#detalle-fecha-solicitud').text(
                    new Date(response.datos.fecha_solicitud).toLocaleString()
                );
                $('#detalle-resultado').text(response.datos.resultado_hoja_servicio || 'No especificado');
                $('#detalle-observacion').text(response.datos.observacion || 'No especificado');
                
                // Limpiar y llenar la tabla de detalles
                $('#tablaDetalles tbody').empty();
                if (response.datos.detalles && response.datos.detalles.length > 0) {
                    response.datos.detalles.forEach(function(detalle) {
                        $('#tablaDetalles tbody').append(`
                            <tr>
                                <td>${detalle.componente || ''}</td>
                                <td>${detalle.detalle || ''}</td>
                            </tr>
                        `);
                    });
                } else {
                    $('#tablaDetalles tbody').append(`
                        <tr>
                            <td colspan="2" class="text-center">No hay detalles registrados</td>
                        </tr>
                    `);
                }
                
                // Mostrar el modal de detalles
                $('#modalDetalles').modal('show');
            } else {
                mostrarError(response.mensaje || 'Error al cargar los detalles');
            }
        },
        error: function(xhr, status, error) {
            mostrarError('Error al consultar los detalles: ' + error);
        }
    });
}

function editarHoja(codigo) {
    $.ajax({
        url: '',
        type: 'POST',
        data: {
            consultar: 'consultar',
            codigo_hoja_servicio: codigo
        },
        dataType: 'json',
        success: function(response) {
            if (response.resultado === 'success') {
                // Llenar el formulario con los datos
                $('#codigo_hoja_servicio').val(response.datos.codigo_hoja_servicio);
                $('#nro_solicitud').val(response.datos.nro_solicitud);
                $('#id_tipo_servicio').val(response.datos.id_tipo_servicio);
                $('#resultado_hoja_servicio').val(response.datos.resultado_hoja_servicio || '');
                $('#observacion').val(response.datos.observacion || '');
                
                // Configurar el modal
                $('#modalHojaLabel').text('Editar Hoja de Servicio');
                $('#btnGuardar').text('Actualizar').attr('name', 'actualizar');
                
                // Mostrar campos adicionales
                $('#fila-resultado, #fila-observacion, #fila-detalles').show();
                
                // Cargar detalles técnicos
                cargarDetallesHoja(codigo);
                
                // Mostrar el modal
                $('#modalHoja').modal('show');
            } else {
                mostrarError(response.mensaje || 'Error al cargar los datos para editar');
            }
        },
        error: function(xhr, status, error) {
            mostrarError('Error al cargar los datos para editar: ' + error);
        }
    });
}

function cargarDetallesHoja(codigo) {
    $.ajax({
        url: '',
        type: 'POST',
        data: {
            consultar_detalles: 'consultar_detalles',
            codigo_hoja_servicio: codigo
        },
        dataType: 'json',
        success: function(response) {
            $('#tablaDetallesModal tbody').empty();
            
            if (response && response.length > 0) {
                response.forEach(function(detalle) {
                    $('#tablaDetallesModal tbody').append(`
                        <tr>
                            <td><input type="text" class="form-control componente" value="${detalle.componente || ''}"></td>
                            <td><input type="text" class="form-control detalle" value="${detalle.detalle || ''}"></td>
                            <td><button type="button" class="btn btn-danger btn-sm btn-eliminar-detalle"><i class="bi bi-trash"></i></button></td>
                        </tr>
                    `);
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar detalles:', error);
        }
    });
}

function tomarHoja(codigo) {
    Swal.fire({
        title: '¿Tomar esta hoja de servicio?',
        text: "Se asignará esta hoja a su usuario",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, tomar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '',
                type: 'POST',
                data: {
                    tomar_hoja: 'tomar_hoja',
                    codigo_hoja_servicio: codigo
                },
                dataType: 'json',
                beforeSend: function() {
                    // Mostrar indicador de carga
                },
                success: function(response) {
                    if (response.resultado === 'success') {
                        mostrarExito(response.mensaje);
                        tablaServicios.ajax.reload(null, false);
                    } else {
                        mostrarError(response.mensaje);
                    }
                },
                error: function(xhr, status, error) {
                    mostrarError('Error al procesar la solicitud: ' + error);
                }
            });
        }
    });
}

function finalizarHoja(codigo) {
    Swal.fire({
        title: 'Finalizar Hoja de Servicio',
        html: `
            <div class="mb-3">
                <label for="swal-resultado" class="form-label">Resultado</label>
                <input type="text" id="swal-resultado" class="form-control" placeholder="Ingrese el resultado">
            </div>
            <div class="mb-3">
                <label for="swal-observacion" class="form-label">Observación</label>
                <textarea id="swal-observacion" class="form-control" placeholder="Ingrese observaciones"></textarea>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Finalizar',
        cancelButtonText: 'Cancelar',
        focusConfirm: false,
        preConfirm: () => {
            return {
                resultado: $('#swal-resultado').val(),
                observacion: $('#swal-observacion').val()
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { resultado, observacion } = result.value;
            
            if (!resultado || resultado.trim() === '') {
                mostrarError('El resultado es requerido');
                return;
            }
            
            $.ajax({
                url: '',
                type: 'POST',
                data: {
                    finalizar_hoja: 'finalizar_hoja',
                    codigo_hoja_servicio: codigo,
                    resultado: resultado,
                    observacion: observacion
                },
                dataType: 'json',
                success: function(response) {
                    if (response.resultado === 'success') {
                        mostrarExito(response.mensaje);
                        tablaServicios.ajax.reload(null, false);
                    } else {
                        mostrarError(response.mensaje);
                    }
                },
                error: function(xhr, status, error) {
                    mostrarError('Error al finalizar la hoja: ' + error);
                }
            });
        }
    });
}

function eliminarHoja(codigo) {
    Swal.fire({
        title: '¿Eliminar esta hoja de servicio?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '',
                type: 'POST',
                data: {
                    eliminar_hoja: 'eliminar_hoja',
                    codigo_hoja_servicio: codigo
                },
                dataType: 'json',
                success: function(response) {
                    if (response.resultado === 'success') {
                        mostrarExito(response.mensaje);
                        tablaServicios.ajax.reload(null, false);
                    } else {
                        mostrarError(response.mensaje);
                    }
                },
                error: function(xhr, status, error) {
                    mostrarError('Error al eliminar la hoja: ' + error);
                }
            });
        }
    });
}

function cargarTiposServicio() {
    $.ajax({
        url: '',
        type: 'POST',
        data: {
            listar_tipos: 'listar_tipos'
        },
        dataType: 'json',
        success: function(response) {
            if (response.resultado === 'success') {
                const $select = $('#id_tipo_servicio');
                $select.empty().append('<option value="" selected disabled>Seleccione un tipo</option>');
                
                response.datos.forEach(function(tipo) {
                    $select.append(`<option value="${tipo.id_tipo_servicio}">${tipo.nombre_tipo_servicio}</option>`);
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar tipos de servicio:', error);
        }
    });
}

function refrescarTabla() {
    tablaServicios.ajax.reload(null, false);
    mostrarExito('Tabla actualizada');
}

function registrarEntrada() {
    $.ajax({
        url: '',
        type: 'POST',
        data: {
            entrada: 'entrada'
        }
    });
}

function mostrarExito(mensaje) {
    Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: mensaje,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
}

function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });
}

// Evento para agregar nuevos detalles técnicos
$('#btn-agregar-detalle').on('click', function() {
    $('#tablaDetallesModal tbody').append(`
        <tr>
            <td><input type="text" class="form-control componente" placeholder="Componente"></td>
            <td><input type="text" class="form-control detalle" placeholder="Detalle"></td>
            <td><button type="button" class="btn btn-danger btn-sm btn-eliminar-detalle"><i class="bi bi-trash"></i></button></td>
        </tr>
    `);
});

// Evento delegado para eliminar detalles
$(document).on('click', '.btn-eliminar-detalle', function() {
    $(this).closest('tr').remove();
});

// Evento para guardar los cambios en el modal
$('#btnGuardar').on('click', function() {
    const accion = $(this).attr('name');
    const datos = new FormData();
    
    datos.append(accion, accion);
    datos.append('codigo_hoja_servicio', $('#codigo_hoja_servicio').val());
    
    if (accion === 'registrar') {
        datos.append('nro_solicitud', $('#nro_solicitud').val());
        datos.append('id_tipo_servicio', $('#id_tipo_servicio').val());
    } else if (accion === 'actualizar') {
        datos.append('resultado_hoja_servicio', $('#resultado_hoja_servicio').val());
        datos.append('observacion', $('#observacion').val());
        
        // Recoger detalles técnicos
        const detalles = [];
        $('#tablaDetallesModal tbody tr').each(function() {
            detalles.push({
                componente: $(this).find('.componente').val(),
                detalle: $(this).find('.detalle').val()
            });
        });
        
        datos.append('detalles', JSON.stringify(detalles));
    }
    
    $.ajax({
        url: '',
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function() {
            $('#btnGuardar').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');
        },
        success: function(response) {
            if (response.resultado === 'success') {
                mostrarExito(response.mensaje);
                $('#modalHoja').modal('hide');
                tablaServicios.ajax.reload(null, false);
            } else {
                mostrarError(response.mensaje);
            }
        },
        error: function(xhr, status, error) {
            mostrarError('Error al procesar la solicitud: ' + error);
        },
        complete: function() {
            $('#btnGuardar').prop('disabled', false).text(
                accion === 'registrar' ? 'Registrar' : 'Actualizar'
            );
        }
    });
});