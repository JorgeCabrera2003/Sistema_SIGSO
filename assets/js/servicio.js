let userData = {};
let tablaServicios;

// Función para cargar los datos del usuario
function loadUserData() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '?page=servicios',
            type: 'POST',
            data: { get_user_data: true },
            dataType: 'json',
            success: function (response) {
                userData = response;
                resolve();
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar datos del usuario:', error);
                reject(error);
            }
        });
    });
}

$(document).ready(async function () {
    await loadUserData();
    console.log('User Data:', userData);

    // Inicialización de la tabla de servicios
    inicializarTablaServicios();

    // Registrar entrada al módulo
    registrarEntrada();

    // Configurar eventos
    configurarEventos();

    // Cargar tipos de servicio para el modal
    cargarTiposServicio();

    // Evento para el formulario de reporte PDF
    $('#formReporteServicio').on('submit', function (e) {
        e.preventDefault();
        generarReportePDF();
    });
});

function inicializarTablaServicios() {
    tablaServicios = $('#tablaServicios').DataTable({
        ajax: {
            url: '?page=servicios',
            type: 'POST',
            data: function (d) {
                return $.extend({}, d, {
                    listar: 'listar',
                    usuario: JSON.stringify({
                        nombre_usuario: '<?= $_SESSION["user"]["nombre_usuario"] ?>',
                        cedula: '<?= $_SESSION["user"]["cedula"] ?>',
                        id_rol: '<?= $_SESSION["user"]["id_rol"] ?>'
                    }),
                    filtroEstado: $('#filtroEstado').val(),
                    filtroTipo: $('#filtroTipo').val(),
                    filtroFechaInicio: $('#filtroFechaInicio').val(),
                    filtroFechaFin: $('#filtroFechaFin').val()
                });
            },
            dataSrc: function (json) {
                if (json.resultado === 'success') {
                    return json.datos;
                } else {
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
                render: function (data) {
                    return data || 'N/A';
                }
            },
            {
                data: 'tipo_equipo',
                render: function (data) {
                    return data || 'N/A';
                }
            },
            {
                data: 'nombre_marca',
                render: function (data) {
                    return data || 'N/A';
                }
            },
            {
                data: 'serial',
                render: function (data) {
                    return data || 'N/A';
                }
            },
            {
                data: 'codigo_bien',
                render: function (data) {
                    return data || 'N/A';
                }
            },
            { data: 'motivo' },
            {
                data: 'fecha_solicitud',
                render: function (data) {
                    return data ? new Date(data).toLocaleString() : 'N/A';
                }
            },
            {
                data: 'tecnico',
                render: function (data) {
                    return data || 'Sin asignar';
                }
            },
            {
                data: 'estatus',
                render: function (data) {
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
                render: function (data, type, row) {
                    let botones = `
                        <button onclick="verDetalles(${row.codigo_hoja_servicio})" class="btn btn-info btn-sm" title="Ver Detalles">
                            <i class="fa-solid fa-eye"></i>
                        </button>`;

                    // Botón para tomar hoja (solo para técnicos y hojas no asignadas)
                    if ((userData.rol === 'TECNICO' || userData.rol === 'SUPERUSUARIO') &&
                        (!row.cedula_tecnico || row.cedula_tecnico === '') &&
                        row.estatus === 'A') {
                        botones += `
                            <button onclick="tomarHoja(${row.codigo_hoja_servicio})" class="btn btn-primary btn-sm ms-1" title="Tomar Servicio">
                                <i class="fa-solid fa-handshake-angle"></i>
                            </button>`;
                    }

                    // Botones para superusuario o técnico asignado
                    if (userData.rol === 'SUPERUSUARIO') {
                        botones += `
                            <button onclick="editarHoja(${row.codigo_hoja_servicio})" class="btn btn-warning btn-sm ms-1" title="Editar">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button onclick="eliminarHoja(${row.codigo_hoja_servicio})" class="btn btn-danger btn-sm ms-1" title="Eliminar">
                                <i class="fa-solid fa-trash"></i>
                            </button>`;
                    } else if (row.cedula_tecnico === userData.cedula && row.estatus === 'A') {
                        botones += `
                            <button onclick="finalizarHoja(${row.codigo_hoja_servicio})" class="btn btn-success btn-sm ms-1" title="Finalizar">
                                <i class="bi bi-check-circle"></i>
                            </button>`;
                    }

                    return `<div class="btn-group">${botones}</div>`;
                }
            }
        ],
        responsive: true,
        order: [[1, 'desc']],
        dom: '<"top"lf>rt<"bottom"ip><"clear">',
        processing: true,
        serverSide: false,
        language: {
            url: 'assets/js/Spanish.json'
        }
    });

    // Eventos para filtros
    $('#filtroEstado, #filtroTipo, #filtroFechaInicio, #filtroFechaFin').on('change', function () {
        tablaServicios.ajax.reload();
    });
}

function configurarEventos() {
    // Evento para el botón de nueva hoja de servicio
    $('#btn-registrar').on('click', abrirModalRegistro);

    // Evento para el botón de refrescar
    $('#btn-refrescar').on('click', refrescarTabla);

    // Evento para agregar nuevos detalles técnicos
    $('#btn-agregar-detalle').on('click', function() {
        $('#tablaDetallesModal tbody').append(`
            <tr>
                <td><input type="text" class="form-control componente" placeholder="Componente"></td>
                <td><input type="text" class="form-control detalle" placeholder="Detalle"></td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-agregar-material" title="Agregar Material">
                        <i class="fa-solid fa-box"></i>
                    </button>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btn-eliminar-detalle">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
    });

    // Evento delegado para botones de agregar material
    $(document).on('click', '.btn-agregar-material', function() {
        const $row = $(this).closest('tr');
        abrirModalMaterial($row);
    });

    // Evento delegado para eliminar detalles
    $(document).on('click', '.btn-eliminar-detalle', function() {
        $(this).closest('tr').remove();
    });

    // Evento para guardar los cambios en el modal
    $('#enviar').on('click', guardarHojaServicio);
}

function abrirModalMaterial($row) {
    // Crear modal si no existe
    if (!$('#modalMaterial').length) {
        $('body').append(`
            <div class="modal fade" id="modalMaterial" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Seleccionar Material</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Material</label>
                                <select class="form-select" id="selectMaterial">
                                    <option value="">Seleccione un material</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="cantidadMaterial" min="1" value="1">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" id="btnConfirmarMaterial">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        `);
    }

    // Cargar materiales disponibles
    cargarMaterialesDisponibles().then(() => {
        const modal = new bootstrap.Modal(document.getElementById('modalMaterial'));
        modal.show();

        // Configurar evento para confirmar material
        $('#btnConfirmarMaterial').off('click').on('click', function() {
            const idMaterial = $('#selectMaterial').val();
            const cantidad = $('#cantidadMaterial').val();
            
            if (!idMaterial) {
                mostrarError('Debe seleccionar un material');
                return;
            }
            
            if (!cantidad || cantidad < 1) {
                mostrarError('La cantidad debe ser mayor a cero');
                return;
            }
            
            // Agregar información del material a la fila
            const nombreMaterial = $('#selectMaterial option:selected').text();
            $row.find('.btn-agregar-material').replaceWith(`
                <span class="badge bg-info">
                    ${nombreMaterial} (${cantidad})
                    <input type="hidden" class="material-id" value="${idMaterial}">
                    <input type="hidden" class="material-cantidad" value="${cantidad}">
                </span>
            `);
            
            modal.hide();
        });
    });
}

function cargarMaterialesDisponibles() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '?page=materiales',
            type: 'POST',
            data: { listar_disponibles: true },
            dataType: 'json',
            success: function(response) {
                if (response.resultado === 'success') {
                    const $select = $('#selectMaterial');
                    $select.empty().append('<option value="">Seleccione un material</option>');
                    
                    response.datos.forEach(function(material) {
                        $select.append(`<option value="${material.id_material}">${material.nombre_material} (Stock: ${material.stock})</option>`);
                    });
                    resolve();
                } else {
                    reject(response.mensaje);
                }
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
}

function guardarHojaServicio() {
    const accion = $(this).attr('name');
    const formData = new FormData();

    formData.append(accion, accion);
    formData.append('codigo_hoja_servicio', $('#codigo_hoja_servicio').val());

    // Recoger datos básicos según la acción
    if (accion === 'registrar') {
        formData.append('nro_solicitud', $('#nro_solicitud').val());
        formData.append('id_tipo_servicio', $('#id_tipo_servicio').val());
    } else if (accion === 'actualizar') {
        formData.append('id_tipo_servicio', $('#id_tipo_servicio').val());
        formData.append('resultado_hoja_servicio', $('#resultado_hoja_servicio').val());
        formData.append('observacion', $('#observacion').val());
    }

    // Recoger detalles técnicos
    const detalles = [];
    $('#tablaDetallesModal tbody tr').each(function() {
        const detalle = {
            componente: $(this).find('.componente').val(),
            detalle: $(this).find('.detalle').val()
        };

        // Agregar información de material si existe
        const materialId = $(this).find('.material-id').val();
        if (materialId) {
            detalle.id_material = materialId;
            detalle.cantidad = $(this).find('.material-cantidad').val();
        }

        detalles.push(detalle);
    });

    formData.append('detalles', JSON.stringify(detalles));

    // Enviar datos al servidor
    $.ajax({
        url: '?page=servicios',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function() {
            $('#enviar').prop('disabled', true)
                       .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');
        },
        success: function(response) {
            if (response.resultado === 'success') {
                mostrarExito(response.mensaje);
                $('#modal1').modal('hide');
                tablaServicios.ajax.reload(null, false);
            } else {
                mostrarError(response.mensaje);
            }
        },
        error: function(xhr, status, error) {
            mostrarError('Error al procesar la solicitud: ' + error);
        },
        complete: function() {
            $('#enviar').prop('disabled', false)
                       .text(accion === 'registrar' ? 'Registrar' : 'Actualizar');
        }
    });
}

function abrirModalRegistro() {
    // Limpiar el modal
    $('#modal1')[0].reset();
    $('#codigo_hoja_servicio').val('');
    $('#modalTitleId').text('Nueva Hoja de Servicio');
    $('#enviar').text('Registrar').attr('name', 'registrar');
    $('#tablaDetallesModal tbody').empty();

    // Mostrar campos según acción
    $('#fila-resultado, #fila-observacion, #fila-detalles').hide();

    // Mostrar el modal
    $('#modal1').modal('show');
}

function verDetalles(codigo) {
    $.ajax({
        url: '?page=servicios',
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
                llenarModalDetalles(response.datos);
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

function llenarModalDetalles(datos) {
    // Información del solicitante
    $('#detalle-solicitante').text(datos.nombre_solicitante);
    $('#detalle-dependencia').text(datos.nombre_dependencia || 'N/A');
    $('#detalle-unidad').text(datos.nombre_unidad || 'N/A');
    $('#detalle-contacto').text(
        (datos.telefono_empleado || 'Sin teléfono') + ' | ' +
        (datos.correo_empleado || 'Sin correo')
    );

    // Información técnica
    $('#detalle-tecnico').text(datos.nombre_tecnico || 'Sin asignar');
    $('#detalle-tipo-servicio').text(datos.nombre_tipo_servicio || 'N/A');
    $('#detalle-estado').text(
        datos.estatus === 'A' ? 'Activo' :
        (datos.estatus === 'I' ? 'Finalizado' : 'Eliminado')
    );
    $('#detalle-fecha-resultado').text(
        datos.fecha_resultado ? new Date(datos.fecha_resultado).toLocaleString() : 'N/A'
    );

    // Información del equipo
    $('#detalle-tipo-equipo').text(datos.tipo_equipo || 'N/A');
    $('#detalle-marca').text(datos.nombre_marca || 'N/A');
    $('#detalle-serial').text(datos.serial || 'N/A');
    $('#detalle-codigo-bien').text(datos.codigo_bien || 'N/A');

    // Detalles de la solicitud
    $('#detalle-motivo').text(datos.motivo || 'No especificado');
    $('#detalle-fecha-solicitud').text(
        datos.fecha_solicitud ? new Date(datos.fecha_solicitud).toLocaleString() : 'N/A'
    );
    $('#detalle-resultado').text(datos.resultado_hoja_servicio || 'No especificado');
    $('#detalle-observacion').text(datos.observacion || 'No especificado');

    // Detalles técnicos
    const $tbody = $('#tablaDetalles tbody').empty();
    if (datos.detalles && datos.detalles.length > 0) {
        datos.detalles.forEach(function(detalle, index) {
            $tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${detalle.componente || ''}</td>
                    <td>${detalle.detalle || ''}</td>
                    <td>${detalle.id_movimiento_material ? 'Material #' + detalle.id_movimiento_material : 'N/A'}</td>
                </tr>
            `);
        });
    } else {
        $tbody.append(`
            <tr>
                <td colspan="4" class="text-center">No hay detalles registrados</td>
            </tr>
        `);
    }
}

function editarHoja(codigo) {
    $.ajax({
        url: '?page=servicios',
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
                $('#modalTitleId').text('Editar Hoja de Servicio');
                $('#enviar').text('Actualizar').attr('name', 'actualizar');

                // Mostrar campos adicionales
                $('#fila-resultado, #fila-observacion, #fila-detalles').show();

                // Cargar detalles técnicos
                cargarDetallesHoja(codigo);

                // Mostrar el modal
                $('#modal1').modal('show');
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
        url: '?page=servicios',
        type: 'POST',
        data: {
            consultar_detalles: 'consultar_detalles',
            codigo_hoja_servicio: codigo
        },
        dataType: 'json',
        success: function(response) {
            const $tbody = $('#tablaDetallesModal tbody').empty();

            if (response && response.length > 0) {
                response.forEach(function(detalle) {
                    $tbody.append(`
                        <tr>
                            <td><input type="text" class="form-control componente" value="${detalle.componente || ''}"></td>
                            <td><input type="text" class="form-control detalle" value="${detalle.detalle || ''}"></td>
                            <td>
                                ${detalle.id_movimiento_material ? 
                                    `<span class="badge bg-info">Material #${detalle.id_movimiento_material}</span>` : 
                                    `<button type="button" class="btn btn-sm btn-outline-primary btn-agregar-material" title="Agregar Material">
                                        <i class="bi bi-box-seam"></i>
                                    </button>`
                                }
                            </td>
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
                url: '?page=servicios',
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
                <select id="swal-resultado" class="form-select">
                    <option value="">Seleccione un resultado</option>
                    <option value="Buen_funcionamiento">Buen funcionamiento</option>
                    <option value="Operativo">Operativo</option>
                    <option value="Sin_funcionar">Sin funcionar</option>
                    <option value="Reparado">Reparado</option>
                    <option value="Reemplazado">Reemplazado</option>
                </select>
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
                url: '?page=servicios',
                type: 'POST',
                data: {
                    finalizar: 'finalizar',
                    codigo_hoja_servicio: codigo,
                    resultado_hoja_servicio: resultado,
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
                url: '?page=servicios',
                type: 'POST',
                data: {
                    eliminar: 'eliminar',
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
        url: '?page=servicios',
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

function generarReportePDF() {
    // Abrir el reporte en una nueva pestaña
    const form = document.getElementById('formReporteServicio');
    const win = window.open('', '_blank');
    const formData = new FormData(form);
    formData.append('generar_reporte', '1');
    
    // Crear un formulario temporal para enviar por POST
    const tempForm = document.createElement('form');
    tempForm.action = '?page=servicios';
    tempForm.method = 'POST';
    tempForm.target = win.name;
    
    for (const [key, value] of formData.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        tempForm.appendChild(input);
    }
    
    document.body.appendChild(tempForm);
    tempForm.submit();
    document.body.removeChild(tempForm);
}

function refrescarTabla() {
    tablaServicios.ajax.reload(null, false);
    mostrarExito('Tabla actualizada');
}

function registrarEntrada() {
    $.ajax({
        url: '?page=servicios',
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