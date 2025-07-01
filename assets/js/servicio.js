let userData = {};
let tablaServicios;
let modalDetallesAbierto = false;

// Función para cargar los datos del usuario
function loadUserData() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '?page=servicios',
            type: 'POST',
            data: {get_user_data: true},
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

    // Configurar eventos del modal de detalles
    $('#modalDetalles').on('shown.bs.modal', function () {
        modalDetallesAbierto = true;
    }).on('hidden.bs.modal', function () {
        modalDetallesAbierto = false;
    });

    // Configurar eventos del modal principal
    $('#modal1').on('shown.bs.modal', function () {
        // Ajustar scrollbar si es necesario
        $(this).find('.modal-body').scrollTop(0);
    }).on('hidden.bs.modal', function () {
        // Limpiar el modal al cerrar
        $(this).find('form')[0].reset();
        $('#tablaDetallesModal tbody').empty();
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
            {data: 'nro_solicitud'},
            {data: 'nombre_tipo_servicio'},
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
            {data: 'motivo'},
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
            lengthMenu: "Mostrar _MENU_ registros por página",
            zeroRecords: "No se encontraron registros",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay registros disponibles",
            infoFiltered: "(filtrados de _MAX_ registros totales)",
            search: "Buscar:",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
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

    // Evento delegado para activar/desactivar select y cantidad según checkbox
    $(document).on('change', '.usar-material', function () {
        const $row = $(this).closest('tr');
        const checked = $(this).is(':checked');
        $row.find('.material-select, .material-cantidad').prop('disabled', !checked);
        if (checked && $row.find('.material-select option').length <= 1) {
            cargarMaterialesDisponiblesParaFila($row.find('.material-select'));
        }
    });

    // Evento delegado para eliminar detalles
    $(document).on('click', '.btn-eliminar-detalle', function () {
        $(this).closest('tr').remove();
    });

    // Evento para guardar los cambios en el modal
    $('#enviar').on('click', guardarHojaServicio);

    // Evento para cambiar el tipo de servicio
    $('#id_tipo_servicio').on('change', function () {
        const tipoServicioId = $(this).val();
        if (tipoServicioId) {
            cargarDetallesPorTipoServicio(tipoServicioId);
            $('#fila-detalles').show();
        } else {
            $('#fila-detalles').hide();
        }
    });

    // Evento para el botón de imprimir en el modal de detalles
    $('#btn-imprimir-detalles').on('click', function () {
        if (modalDetallesAbierto) {
            window.print();
        }
    });
}

function guardarHojaServicio() {
    const accion = $('#enviar').attr('name');
    const codigoHoja = $('#codigo_hoja_servicio').val();
    const nroSolicitud = $('#nro_solicitud').val();
    const tipoServicio = $('#id_tipo_servicio').val();
    const resultado = $('#resultado_hoja_servicio').val();
    const observacion = $('#observacion').val();

    // Validaciones básicas
    if (!tipoServicio) {
        mostrarError('Debe seleccionar un tipo de servicio');
        return;
    }

    // Validar resultado si se está finalizando
    if (accion === 'finalizar' && !resultado) {
        mostrarError('Debe seleccionar un resultado al finalizar');
        return;
    }

    // Recopilar detalles técnicos (opcionales)
    const detalles = [];
    let errorEnDetalles = false;

    $('#tablaDetallesModal tbody tr').each(function () {
        if (errorEnDetalles) return false; // Salir del bucle si hay error

        const componente = $(this).find('.componente').val()?.trim();
        const detalle = $(this).find('.detalle').val()?.trim();
        const usaMaterial = $(this).find('.usar-material').is(':checked');
        const material = usaMaterial ? $(this).find('.material-select').val() : null;
        const cantidad = usaMaterial ? $(this).find('.material-cantidad').val() : null;

        // Solo agregar si tiene componente
        if (componente) {
            // Validar material si está marcado
            if (usaMaterial) {
                if (!material) {
                    mostrarError('Debe seleccionar un material para el componente: ' + componente);
                    errorEnDetalles = true;
                    return false;
                }

                if (!cantidad || cantidad <= 0) {
                    mostrarError('La cantidad debe ser mayor a 0 para el componente: ' + componente);
                    errorEnDetalles = true;
                    return false;
                }
            }

            detalles.push({
                componente: componente,
                detalle: detalle || '',
                id_material: material,
                cantidad: cantidad ? parseInt(cantidad) : null
            });
        }
    });

    if (errorEnDetalles) return;

    // Preparar datos para enviar
    const datos = {
        peticion: accion,
        codigo_hoja_servicio: codigoHoja,
        nro_solicitud: nroSolicitud,
        id_tipo_servicio: tipoServicio,
        resultado_hoja_servicio: resultado,
        observacion: observacion,
        detalles: detalles.length > 0 ? detalles : [] // Enviar array vacío si no hay detalles
    };

    // Mostrar confirmación para acciones importantes
    if (accion === 'finalizar') {
        Swal.fire({
            title: '¿Confirmar finalización?',
            text: 'Esta acción marcará la hoja de servicio como finalizada',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, finalizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                enviarDatosServicio(datos);
            }
        });
    } else {
        enviarDatosServicio(datos);
    }
}

function enviarDatosServicio(datos) {
    // Enviar al servidor
    $.ajax({
        url: '?page=servicios',
        type: 'POST',
        data: datos,
        dataType: 'json',
        
        success: function (response) {
            if (response.resultado === 'success') {
                mostrarExito(response.mensaje);
                $('#modal1').modal('hide');
                tablaServicios.ajax.reload(null, false);

                // Bitácora solo para acciones exitosas
                if (datos.peticion === 'registrar') {
                    const msg = `(${userData.nombre_usuario}), Registró la hoja de servicio #${response.codigo || datos.codigo_hoja_servicio}`;
                    Bitacora(msg, "Servicio");
                } else if (datos.peticion === 'actualizar') {
                    const msg = `(${userData.nombre_usuario}), Actualizó la hoja de servicio #${datos.codigo_hoja_servicio}`;
                    Bitacora(msg, "Servicio");
                }
            } else {
                mostrarError(response.mensaje || 'Error desconocido al procesar la solicitud');
            }
        },
        error: function (xhr, status, error) {
            let mensaje = 'Error en la solicitud: ';

            try {
                const response = JSON.parse(xhr.responseText);
                mensaje += response.mensaje || error;
            } catch (e) {
                mensaje += error;
            }

            mostrarError(mensaje);
        },
        complete: function () {
            $('#enviar').prop('disabled', false)
                .text(datos.peticion === 'registrar' ? 'Registrar' :
                    datos.peticion === 'finalizar' ? 'Finalizar' : 'Actualizar');
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
    $('#enviar').prop('disabled', false);

    // Mostrar campos según acción
    $('#fila-resultado, #fila-observacion, #fila-detalles').hide();

    // Cargar tipos de servicio antes de mostrar el modal
    cargarTiposServicio();

    // Mostrar el modal
    $('#modal1').modal('show');
}

function verDetalles(codigo) {
    $.ajax({
        url: '?page=servicios',
        type: 'POST',
        data: { consultar: true, codigo_hoja_servicio: codigo },
        dataType: 'json',
        success: function(resp) {
            if (resp.resultado === 'success' && resp.datos) {
                llenarModalDetalles(resp.datos);
                $('#modalDetalles').modal('show');
            } else {
                mostrarError(resp.mensaje || 'No se encontraron datos');
            }
        },
        error: function() {
            mostrarError('Error al consultar detalles');
        }
    });
}

function llenarModalDetalles(datos) {
    // Sección Solicitante
    $('#detalle-solicitante').text(datos.nombre_solicitante || '');
    $('#detalle-dependencia').text(datos.nombre_dependencia || '');
    $('#detalle-unidad').text(datos.nombre_unidad || '');
    $('#detalle-contacto').text((datos.telefono_empleado || '') + ' / ' + (datos.correo_empleado || ''));

    // Sección Técnico
    $('#detalle-tecnico').text(datos.nombre_tecnico || 'Sin asignar');
    $('#detalle-tipo-servicio').text(datos.nombre_tipo_servicio || '');
    $('#detalle-estado').text(datos.estatus === 'A' ? 'Activa' : (datos.estatus === 'I' ? 'Finalizada' : 'Eliminada'));
    $('#detalle-fecha-resultado').text(datos.fecha_resultado || '');

    // Sección Equipo
    $('#detalle-tipo-equipo').text(datos.tipo_equipo || '');
    $('#detalle-marca').text(datos.nombre_marca || '');
    $('#detalle-serial').text(datos.serial || '');
    $('#detalle-codigo-bien').text(datos.codigo_bien || '');

    // Sección Solicitud
    $('#detalle-motivo').text(datos.motivo || '');
    $('#detalle-fecha-solicitud').text(datos.fecha_solicitud || '');
    $('#detalle-resultado').text(datos.resultado_hoja_servicio || '');
    $('#detalle-observacion').text(datos.observacion || '');

    // Sección Detalles Técnicos
    let detalles = datos.detalles || [];
    let tbody = $('#tablaDetalles tbody');
    tbody.empty();
    if (detalles.length > 0) {
        detalles.forEach(function(det, idx) {
            let material = det.id_material ? (det.material_info ? det.material_info.nombre + ' (' + det.cantidad + ')' : 'ID: ' + det.id_material + ' (' + det.cantidad + ')') : '';
            tbody.append(
                `<tr>
                    <td>${idx + 1}</td>
                    <td>${det.componente || ''}</td>
                    <td>${det.detalle || ''}</td>
                    <td>${material}</td>
                </tr>`
            );
        });
    } else {
        tbody.append('<tr><td colspan="4" class="text-center">Sin detalles técnicos</td></tr>');
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
        beforeSend: function () {
            // Mostrar spinner en el modal
            $('#modal1 .modal-body').html(`
                <div class="text-center my-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p>Cargando datos para edición...</p>
                </div>
            `);
        },
        success: function (response) {
            if (response.resultado === 'success') {
                // Restaurar el contenido original del modal con TODOS los campos
                $('#modal1 .modal-body').html(`
                    <input type="hidden" id="codigo_hoja_servicio">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="form-floating mb-3 mt-4">
                                <input placeholder="" class="form-control" name="nro_solicitud" type="text" id="nro_solicitud" readonly>
                                <span id="snro_solicitud"></span>
                                <label for="nro_solicitud" class="form-label">Número de Solicitud</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3 mt-4">
                                <select class="form-select" name="id_tipo_servicio" id="id_tipo_servicio">
                                    <option value="">Seleccione un tipo</option>
                                </select>
                                <span id="sid_tipo_servicio"></span>
                                <label for="id_tipo_servicio" class="form-label">Tipo de Servicio</label>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="form-floating mb-3 mt-4">
                                <select placeholder="" class="form-control" name="resultado_hoja_servicio" id="resultado_hoja_servicio">
                                    <option value="">Seleccione un resultado</option>
                                    <option value="Buen_funcionamiento">Buen funcionamiento</option>
                                    <option value="Operativo">Operativo</option>
                                    <option value="Sin_funcionar">Sin funcionar</option>
                                </select>
                                <span id="sresultado_hoja_servicio"></span>
                                <label for="resultado_hoja_servicio" class="form-label">Resultado</label>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="form-floating mb-3 mt-4">
                                <textarea class="form-control" name="observacion" id="observacion" style="height: 100px"></textarea>
                                <span id="sobservacion"></span>
                                <label for="observacion" class="form-label">Observación</label>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <h5>Detalles Técnicos</h5>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> Detalles técnicos del servicio
                            </div>
                            <div class="table-responsive">
                                <table class="table" id="tablaDetallesModal">
                                    <thead>
                                        <tr>
                                            <th>Componente</th>
                                            <th>Detalle</th>
                                            <th class="text-center">¿Usa material?</th>
                                            <th>Material</th>
                                            <th>Cantidad</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary mt-2" id="btn-agregar-detalle">
                                <i class="bi bi-plus-circle"></i> Agregar Detalle
                            </button>
                        </div>
                    </div>
                `);

                // Cargar tipos de servicio
                cargarTiposServicio().then(function() {
                    // Llenar el formulario con los datos
                    $('#codigo_hoja_servicio').val(response.datos.codigo_hoja_servicio);
                    $('#nro_solicitud').val(response.datos.nro_solicitud);
                    $('#id_tipo_servicio').val(response.datos.id_tipo_servicio);
                    $('#resultado_hoja_servicio').val(response.datos.resultado_hoja_servicio || '');
                    $('#observacion').val(response.datos.observacion || '');

                    // Configurar el modal
                    $('#modalTitleId').text('Editar Hoja de Servicio');
                    $('#enviar').text('Actualizar').attr('name', 'actualizar');

                    // Mostrar el modal
                    $('#modal1').modal('show');

                    // Cargar detalles técnicos
                    cargarDetallesHojaEdicion(codigo);
                });
            } else {
                mostrarError(response.mensaje || 'Error al cargar los datos para editar');
            }
        },
        error: function (xhr, status, error) {
            mostrarError('Error al cargar los datos para editar: ' + error);
        }
    });
}

function cargarDetallesHojaEdicion(codigo) {
    $.ajax({
        url: '?page=servicios',
        type: 'POST',
        data: {
            consultar: true,
            codigo_hoja_servicio: codigo
        },
        dataType: 'json',
        success: function(response) {
            let detalles = [];
            if (response.resultado === 'success' && response.datos && response.datos.detalles) {
                detalles = response.datos.detalles;
            } else if (Array.isArray(response)) {
                detalles = response; // fallback por si la respuesta es solo array
            }
            const $tbody = $('#tablaDetallesModal tbody');
            $tbody.empty();

            if (detalles.length > 0) {
                detalles.forEach(function(det) {
                    agregarFilaDetalle(
                        det.componente || '',
                        det.detalle || '',
                        det.id_material || '',
                        det.cantidad || ''
                    );
                });
            } else {
                agregarFilaDetalle();
            }
        },
        error: function() {
            $('#tablaDetallesModal tbody').empty();
            agregarFilaDetalle();
        }
    });
}

function agregarFilaDetalle(componente = '', detalle = '', id_material = '', cantidad = '') {
    const usarMaterial = id_material && cantidad;
    const $row = $(`
        <tr>
            <td><input type="text" class="form-control componente" value="${componente}" placeholder="Componente"></td>
            <td><input type="text" class="form-control detalle" value="${detalle}" placeholder="Detalle"></td>
            <td class="text-center">
                <input type="checkbox" class="form-check-input usar-material" ${usarMaterial ? 'checked' : ''}>
            </td>
            <td>
                <select class="form-select material-select" ${usarMaterial ? '' : 'disabled'}>
                    <option value="">Seleccione material</option>
                </select>
            </td>
            <td>
                <input type="number" class="form-control material-cantidad" min="1" value="${cantidad || 1}" ${usarMaterial ? '' : 'disabled'}>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm btn-eliminar-detalle">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `);

    // Cargar materiales y seleccionar el correspondiente si aplica
    cargarMaterialesDisponiblesParaFila($row.find('.material-select'), id_material);

    $('#tablaDetallesModal tbody').append($row);
}

// Evento delegado para el botón agregar detalle
$(document).on('click', '#modal1 #btn-agregar-detalle', function() {
    agregarFilaDetalle();
});

function cargarMaterialesDisponiblesParaFila($select, idMaterialSeleccionado = null) {
    $.ajax({
        url: '?page=servicios',
        type: 'POST',
        data: { listar_materiales: true },
        dataType: 'json',
        success: function(response) {
            if (response.resultado === 'success') {
                $select.empty().append('<option value="">Seleccione material</option>');
                
                response.datos.forEach(function(material) {
                    $select.append($('<option>', {
                        value: material.id_material,
                        text: material.nombre_material + ' (Disponible: ' + material.stock + ')'
                    }));
                });

                if (idMaterialSeleccionado) {
                    $select.val(idMaterialSeleccionado);
                }
            }
        },
        error: function() {
            $select.empty().append('<option value="">Error al cargar materiales</option>');
        }
    });
}

function cargarTiposServicio() {
    return $.ajax({
        url: '?page=servicios',
        type: 'POST',
        data: {
            listar_tipos: 'listar_tipos'
        },
        dataType: 'json',
        success: function (response) {
            if (response.resultado === 'success') {
                const $select = $('#id_tipo_servicio');
                $select.empty().append('<option value="" selected disabled>Seleccione un tipo</option>');

                response.datos.forEach(function (tipo) {
                    $select.append(`<option value="${tipo.id_tipo_servicio}">${tipo.nombre_tipo_servicio}</option>`);
                });
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar tipos de servicio:', error);
        }
    });
}

function cargarDetallesPorTipoServicio(tipoServicioId) {
    // Aquí puedes cargar detalles sugeridos según el tipo de servicio
    // Por ahora solo agregamos una fila vacía
    $('#tablaDetallesModal tbody').empty();
    agregarFilaDetalle();
}

function generarReportePDF() {
    // Validar fechas
    const fechaInicio = $('#fecha_inicio').val();
    const fechaFin = $('#fecha_fin').val();

    if (!fechaInicio || !fechaFin) {
        mostrarError('Debe seleccionar un rango de fechas');
        return;
    }

    if (new Date(fechaInicio) > new Date(fechaFin)) {
        mostrarError('La fecha de inicio no puede ser mayor a la fecha final');
        return;
    }

    // Crear un formulario temporal para enviar por POST
    const form = document.createElement('form');
    form.action = '?page=servicios';
    form.method = 'POST';
    form.target = '_blank';
    form.style.display = 'none';

    // Agregar campos al formulario
    const campos = {
        generar_reporte: '1',
        fecha_inicio: fechaInicio,
        fecha_fin: fechaFin,
        id_tipo_servicio: $('#reporte_tipo_servicio').val()
    };

    for (const key in campos) {
        if (campos.hasOwnProperty(key)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = campos[key];
            form.appendChild(input);
        }
    }

    // Agregar el formulario al documento y enviarlo
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
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
        timer: 3000,
        timerProgressBar: true
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
        timer: 5000,
        timerProgressBar: true
    });
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';

    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
}

// Funciones para acciones adicionales
function tomarHoja(codigo) {
    $.ajax({
        url: '?page=servicios',
        type: 'POST',
        data: {
            tomar_hoja: true,
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
        error: function() {
            mostrarError('Error al intentar tomar la hoja de servicio');
        }
    });
}

function finalizarHoja(codigo) {
    $('#modal1').modal('show');
    $('#modalTitleId').text('Finalizar Hoja de Servicio');
    $('#enviar').text('Finalizar').attr('name', 'finalizar');
    $('#codigo_hoja_servicio').val(codigo);
    $('#fila-resultado, #fila-observacion').show();
    $('#fila-detalles').hide();

    // Cargar datos existentes
    $.ajax({
        url: '?page=servicios',
        type: 'POST',
        data: {
            consultar: true,
            codigo_hoja_servicio: codigo
        },
        dataType: 'json',
        success: function(response) {
            if (response.resultado === 'success') {
                $('#nro_solicitud').val(response.datos.nro_solicitud);
                $('#id_tipo_servicio').val(response.datos.id_tipo_servicio);
                $('#resultado_hoja_servicio').val(response.datos.resultado_hoja_servicio || '');
                $('#observacion').val(response.datos.observacion || '');
            }
        }
    });
}

function eliminarHoja(codigo) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "Esta acción eliminará la hoja de servicio permanentemente",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '?page=servicios',
                type: 'POST',
                data: {
                    eliminar: true,
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
                error: function() {
                    mostrarError('Error al intentar eliminar la hoja de servicio');
                }
            });
        }
    });
}