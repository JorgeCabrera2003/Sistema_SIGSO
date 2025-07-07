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

    $('#modalDetalles').on('shown.bs.modal', function () {
        modalDetallesAbierto = true;
    }).on('hidden.bs.modal', function () {
        modalDetallesAbierto = false;
    });

    var $resultado = document.getElementById('resultado_hoja_servicio');
    var $btnFinalizar = document.getElementById('btn-finalizar-hoja-modal1');
    if ($resultado && $btnFinalizar) {
        $resultado.addEventListener('change', function () {
            if ($resultado.value) {
                $btnFinalizar.style.display = '';
            } else {
                $btnFinalizar.style.display = 'none';
            }
        });
        $resultado.dispatchEvent(new Event('change'));
    }

    $('#modal1').on('shown.bs.modal', function () {
        $(this).find('.modal-body').scrollTop(0);
    }).on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#tablaDetallesModal tbody').empty();
    });

    // Consultar permisos al cargar la página
    ConsultarPermisosServicios();
});

function inicializarTablaServicios() {
    tablaServicios = $('#tablaServicios').DataTable({
        ajax: {
            url: '?page=servicios',
            type: 'POST',
            data: function (d) {
                // Solo envía 'listar', no los filtros, para obtener todos los datos
                return {listar: 'listar'};
            },
            dataSrc: function (json) {
                if (json.resultado === 'success') {
                    // Aplica el filtrado en el frontend
                    let datos = json.datos;

                    // Filtro Estado
                    let filtroEstado = $('#filtroEstado').val();
                    if (filtroEstado && filtroEstado !== 'todos') {
                        datos = datos.filter(row => row.estatus === filtroEstado);
                    }

                    // Filtro Tipo
                    let filtroTipo = $('#filtroTipo').val();
                    if (filtroTipo && filtroTipo !== 'todos') {
                        datos = datos.filter(row => row.id_tipo_servicio == filtroTipo);
                    }

                    // Filtro Fecha
                    let filtroFechaInicio = $('#filtroFechaInicio').val();
                    let filtroFechaFin = $('#filtroFechaFin').val();
                    if (filtroFechaInicio) {
                        datos = datos.filter(row => row.fecha_solicitud && row.fecha_solicitud >= filtroFechaInicio);
                    }
                    if (filtroFechaFin) {
                        datos = datos.filter(row => row.fecha_solicitud && row.fecha_solicitud <= filtroFechaFin + " 23:59:59");
                    }

                    return datos;
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
                    let buttons = `
                        <div class="btn-group">
                            <button class="btn btn-sm btn-info" onclick="verDetalles(${row.codigo_hoja_servicio})" title="Ver Detalles">
                                <i class="fa fa-eye"></i>
                            </button>`;
                    if (row.estatus === 'A') {
                        buttons += `
                            <button class="btn btn-sm btn-primary" onclick="editarHoja(${row.codigo_hoja_servicio})" title="Editar">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="redireccionarHoja(${row.codigo_hoja_servicio})" title="Redireccionar">
                                <i class="fa fa-share"></i>
                            </button>`;
                    }

                    if (userData.id_rol == 1) {
                        buttons += `
                            <button class="btn btn-sm btn-danger" onclick="eliminarHoja(${row.codigo_hoja_servicio})" title="Eliminar">
                                <i class="fa fa-trash"></i>
                            </button>`;
                    }

                    buttons += `</div>`;
                    return buttons;
                },
                orderable: false
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


    if (!tipoServicio) {
        mostrarError('Debe seleccionar un tipo de servicio');
        return;
    }


    if (accion === 'finalizar' && !resultado) {
        mostrarError('Debe seleccionar un resultado al finalizar');
        return;
    }

    const detalles = [];
    let errorEnDetalles = false;

    $('#tablaDetallesModal tbody tr').each(function () {
        if (errorEnDetalles) return false;

        const componente = $(this).find('.componente').val()?.trim();
        const detalle = $(this).find('.detalle').val()?.trim();
        const usaMaterial = $(this).find('.usar-material').is(':checked');
        const material = usaMaterial ? $(this).find('.material-select').val() : null;
        const cantidad = usaMaterial ? $(this).find('.material-cantidad').val() : null;

        if (componente) {

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

    const datos = {
        peticion: accion,
        codigo_hoja_servicio: codigoHoja,
        nro_solicitud: nroSolicitud,
        id_tipo_servicio: tipoServicio,
        resultado_hoja_servicio: resultado,
        observacion: observacion,
        detalles: detalles.length > 0 ? detalles : []
    };


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
                enviarDatosServicio(datos, true);
            }
        });
    } else {
        enviarDatosServicio(datos, false);
    }
}

function enviarDatosServicio(datos, esFinalizar = false) {
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

                // Si es finalizar, abrir el PDF de la hoja de servicio
                if (esFinalizar && datos.codigo_hoja_servicio) {
                    abrirPDFHojaServicio(datos.codigo_hoja_servicio);
                }
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

function abrirPDFHojaServicio(codigoHoja) {

    const form = document.createElement('form');
    form.action = '?page=servicios';
    form.method = 'POST';
    form.target = '_blank';
    form.style.display = 'none';

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'pdf_hoja_servicio';
    input.value = codigoHoja;
    form.appendChild(input);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function abrirModalRegistro() {

    $('#modal1')[0].reset();
    $('#codigo_hoja_servicio').val('');
    $('#modalTitleId').text('Nueva Hoja de Servicio');
    $('#enviar').text('Registrar').attr('name', 'registrar');
    $('#tablaDetallesModal tbody').empty();
    $('#enviar').prop('disabled', false);

    $('#fila-resultado, #fila-observacion, #fila-detalles').hide();


    cargarTiposServicio();

    $('#modal1').modal('show');
}

function verDetalles(codigo) {
    $.ajax({
        url: '?page=servicios',
        type: 'POST',
        data: {consultar: true, codigo_hoja_servicio: codigo},
        dataType: 'json',
        success: function (resp) {
            if (resp.resultado === 'success' && resp.datos) {
                llenarModalDetalles(resp.datos);
                if (userData.id_rol == 1 && resp.datos.estatus === 'A') {
                    $('#btn-redireccionar').show().off('click').on('click', function () {
                        redireccionarHoja(codigo);
                    });
                } else {
                    $('#btn-redireccionar').hide();
                }

                $('#modalDetalles').modal('show');
            } else {
                mostrarError(resp.mensaje || 'No se encontraron datos');
            }
        },
        error: function () {
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

    // Sección Tecnico
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

    // Sección Detalles Tecnicos
    let detalles = datos.detalles || [];
    let tbody = $('#tablaDetalles tbody');
    tbody.empty();

    if (detalles.length > 0) {
        detalles.forEach(function (det, idx) {
            let material = det.id_material ?
                (det.material_info ?
                    `${det.material_info.nombre} (${det.cantidad})` :
                    `ID: ${det.id_material} (${det.cantidad})`) :
                'No aplica';

            tbody.append(`
                <tr>
                    <td>${idx + 1}</td>
                    <td>${det.componente || ''}</td>
                    <td>${det.detalle || ''}</td>
                    <td>${material}</td>
                </tr>
            `);
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


                cargarTiposServicio().then(function () {

                    $('#codigo_hoja_servicio').val(response.datos.codigo_hoja_servicio);
                    $('#nro_solicitud').val(response.datos.nro_solicitud);
                    $('#id_tipo_servicio').val(response.datos.id_tipo_servicio);
                    $('#resultado_hoja_servicio').val(response.datos.resultado_hoja_servicio || '');
                    $('#observacion').val(response.datos.observacion || '');

                    $('#modalTitleId').text('Editar Hoja de Servicio');
                    $('#enviar').text('Actualizar').attr('name', 'actualizar');

                    $('#modal1').modal('show');

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
        success: function (response) {
            let detalles = [];
            if (response.resultado === 'success' && response.datos && response.datos.detalles) {
                detalles = response.datos.detalles;
            } else if (Array.isArray(response)) {
                detalles = response;
            }
            const $tbody = $('#tablaDetallesModal tbody');
            $tbody.empty();

            if (detalles.length > 0) {
                detalles.forEach(function (det) {
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
        error: function () {
            $('#tablaDetallesModal tbody').empty();
            agregarFilaDetalle();
        }
    });
}

function redireccionarHoja(codigo) {
    // Primero cargar los tipos de servicio para el select de área
    cargarTiposServicio().then(function() {
        Swal.fire({
            title: 'Redireccionar Hoja de Servicio',
            html: `
                <div class="mb-3">
                    <label for="areaDestino" class="form-label">Área de destino</label>
                    <select id="areaDestino" class="form-select">
                        <option value="">Seleccione un área</option>
                        ${$('#id_tipo_servicio').html()}
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tecnicoDestino" class="form-label">Técnico asignado</label>
                    <select id="tecnicoDestino" class="form-select" disabled>
                        <option value="">Primero seleccione un área</option>
                    </select>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Redireccionar',
            cancelButtonText: 'Cancelar',
            focusConfirm: false,
            preConfirm: () => {
                const area = $('#areaDestino').val();
                if (!area) {
                    Swal.showValidationMessage('Debe seleccionar un área de destino');
                    return false;
                }
                const tecnico = $('#tecnicoDestino').val() || null;
                return {area_destino: area, tecnico_destino: tecnico};
            },
            didOpen: () => {
                // Evento para cargar técnicos cuando se selecciona un área
                $('#areaDestino').on('change', function() {
                    const areaId = $(this).val();
                    const $tecnicoSelect = $('#tecnicoDestino');

                    if (areaId) {
                        $tecnicoSelect.prop('disabled', true).html('<option value="">Cargando técnicos...</option>');

                        // Llamar al procedimiento almacenado para obtener técnicos por área
                        $.ajax({
                            url: '?page=servicios',
                            type: 'POST',
                            data: {
                                obtener_tecnicos_por_area: true,
                                area_id: areaId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.resultado === 'success' && response.datos && response.datos.length > 0) {
                                    let options = '<option value="">Seleccione un técnico (opcional)</option>';
                                    response.datos.forEach(tecnico => {
                                        options += `<option value="${tecnico.cedula_empleado}">${tecnico.nombre_empleado} ${tecnico.apellido_empleado || ''}</option>`;
                                    });
                                    $tecnicoSelect.html(options);
                                } else {
                                    $tecnicoSelect.html('<option value="">No hay técnicos disponibles</option>');
                                }
                                $tecnicoSelect.prop('disabled', false);
                            },
                            error: function() {
                                $tecnicoSelect.html('<option value="">Error al cargar técnicos</option>').prop('disabled', false);
                            }
                        });
                    } else {
                        $tecnicoSelect.html('<option value="">Primero seleccione un área</option>').prop('disabled', true);
                    }
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const datos = {
                    redireccionar: true,
                    codigo_hoja_servicio: codigo,
                    area_destino: result.value.area_destino,
                    tecnico_destino: result.value.tecnico_destino
                };

                $.ajax({
                    url: '?page=servicios',
                    type: 'POST',
                    data: datos,
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.showLoading();
                    },
                    success: function(response) {
                        if (response.resultado === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Redirección exitosa',
                                text: response.mensaje,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            tablaServicios.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error', response.mensaje || 'Error al redireccionar', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Error en la conexión al redireccionar', 'error');
                    }
                });
            }
        });
    });
}

function agregarFilaDetalle(componente = '', detalle = '', id_material = '', cantidad = '') {
    const usarMaterial = id_material && cantidad;
    const $row = $(`
        <tr>
            <td><input type="text" class="form-control componente" value="${componente}" placeholder="Componente" required></td>
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
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        </tr>
    `);

    cargarMaterialesDisponiblesParaFila($row.find('.material-select'), id_material);

    $('#tablaDetallesModal tbody').append($row);
}


function cargarMaterialesDisponiblesParaFila($select, idMaterialSeleccionado = null) {
    $.ajax({
        url: '?page=servicios',
        type: 'POST',
        data: {listar_materiales: true},
        dataType: 'json',
        success: function (response) {
            if (response.resultado === 'success') {
                $select.empty().append('<option value="">Seleccione material</option>');

                response.datos.forEach(function (material) {
                    $select.append($('<option>', {
                        value: material.id_material,
                        text: material.nombre_material + ' (Disponible: ' + material.stock + ')',
                        'data-stock': material.stock
                    }));
                });

                if (idMaterialSeleccionado) {
                    $select.val(idMaterialSeleccionado);


                    const $row = $select.closest('tr');
                    const $cantidadInput = $row.find('.material-cantidad');
                    const stock = $select.find('option:selected').data('stock');

                    if ($cantidadInput.val() > stock) {
                        mostrarError(`La cantidad (${$cantidadInput.val()}) excede el stock disponible (${stock})`);
                        $cantidadInput.val(stock);
                    }
                }
            }
        },
        error: function () {
            $select.empty().append('<option value="">Error al cargar materiales</option>');
        }
    });
}


$(document).on('change', '.usar-material', function () {
    const $row = $(this).closest('tr');
    const checked = $(this).is(':checked');

    $row.find('.material-select, .material-cantidad').prop('disabled', !checked);

    if (checked && $row.find('.material-select option').length <= 1) {
        cargarMaterialesDisponiblesParaFila($row.find('.material-select'));
    }
});

$(document).on('change', '.material-select', function () {
    const $row = $(this).closest('tr');
    const stock = $(this).find('option:selected').data('stock');
    const $cantidadInput = $row.find('.material-cantidad');

    if (stock && $cantidadInput.val() > stock) {
        mostrarError(`La cantidad (${$cantidadInput.val()}) excede el stock disponible (${stock})`);
        $cantidadInput.val(stock);
    }
});

$(document).on('click', '.btn-eliminar-detalle', function () {
    $(this).closest('tr').remove();
});

function recopilarDetalles() {
    const detalles = [];
    let errorEnDetalles = false;

    $('#tablaDetallesModal tbody tr').each(function () {
        const componente = $(this).find('.componente').val()?.trim();
        const detalle = $(this).find('.detalle').val()?.trim();
        const usaMaterial = $(this).find('.usar-material').is(':checked');
        const material = usaMaterial ? $(this).find('.material-select').val() : null;
        const cantidad = usaMaterial ? $(this).find('.material-cantidad').val() : null;

        if (componente) {

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

                // Validar stock
                const stock = $(this).find('.material-select option:selected').data('stock');
                if (cantidad > stock) {
                    mostrarError(`La cantidad (${cantidad}) excede el stock disponible (${stock}) para el componente: ${componente}`);
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

    return {detalles, errorEnDetalles};
}

$(document).on('click', '#modal1 #btn-agregar-detalle', function () {
    agregarFilaDetalle();
});

function cargarMaterialesDisponiblesParaFila($select, idMaterialSeleccionado = null) {
    $.ajax({
        url: '?page=servicios',
        type: 'POST',
        data: {listar_materiales: true},
        dataType: 'json',
        success: function (response) {
            if (response.resultado === 'success') {
                $select.empty().append('<option value="">Seleccione material</option>');

                response.datos.forEach(function (material) {
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
        error: function () {
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

    $('#tablaDetallesModal tbody').empty();
    agregarFilaDetalle();
}

function generarReportePDF() {

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

    const form = document.createElement('form');
    form.action = '?page=servicios';
    form.method = 'POST';
    form.target = '_blank';
    form.style.display = 'none';

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

function tomarHoja(codigo) {
    $.ajax({
        url: '?page=servicios',
        type: 'POST',
        data: {
            tomar_hoja: true,
            codigo_hoja_servicio: codigo
        },
        dataType: 'json',
        success: function (response) {
            if (response.resultado === 'success') {
                mostrarExito(response.mensaje);
                tablaServicios.ajax.reload(null, false);
            } else {
                mostrarError(response.mensaje);
            }
        },
        error: function () {
            mostrarError('Error al intentar tomar la hoja de servicio');
        }
    });
}

function finalizarHoja(codigo) {
    $('#modal1').modal('show');
    $('#modalTitleId').text('Finalizar Hoja de Servicio');
    $('#enviar').hide();
    $('#btn-finalizar-hoja-modal').show().attr('data-codigo', codigo);

    $('#fila-resultado, #fila-observacion').show();
    $('#fila-detalles').hide();

    $.ajax({
        url: '?page=servicios',
        type: 'POST',
        data: {
            consultar: true,
            codigo_hoja_servicio: codigo
        },
        dataType: 'json',
        success: function (response) {
            if (response.resultado === 'success') {
                $('#codigo_hoja_servicio').val(response.datos.codigo_hoja_servicio);
                $('#nro_solicitud').val(response.datos.nro_solicitud);
                $('#id_tipo_servicio').val(response.datos.id_tipo_servicio);
                $('#resultado_hoja_servicio').val(response.datos.resultado_hoja_servicio || '');
                $('#observacion').val(response.datos.observacion || '');
            }
        }
    });
}

$(document).on('click', '#btn-finalizar-hoja-modal', function () {

    let codigo = $(this).attr('data-codigo') || $('#codigo_hoja_servicio').val();
    const resultado = $('#resultado_hoja_servicio').val();
    const observacion = $('#observacion').val();

    if (!codigo) {
        mostrarError('No se pudo obtener el código de la hoja de servicio.');
        return;
    }

    if (!resultado) {
        mostrarError('Debe seleccionar un resultado para finalizar la hoja');
        return;
    }

    const datos = {
        peticion: 'finalizar',
        codigo_hoja_servicio: codigo,
        resultado_hoja_servicio: resultado,
        observacion: observacion
    };

    Swal.fire({
        title: '¿Confirmar finalización?',
        text: 'Esta acción marcará la hoja de servicio como finalizada',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, finalizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '?page=servicios',
                type: 'POST',
                data: datos,
                dataType: 'json',
                success: function (response) {
                    if (response.resultado === 'success') {
                        mostrarExito(response.mensaje);
                        $('#modal1').modal('hide');
                        if (typeof tablaServicios !== 'undefined') {
                            tablaServicios.ajax.reload(null, false);
                        }
                        if (response.codigo) {
                            abrirPDFHojaServicio(response.codigo);
                        }
                    } else {
                        mostrarError(response.mensaje || 'Error al finalizar la hoja');
                    }
                },
                error: function () {
                    mostrarError('Error en la solicitud al finalizar la hoja');
                }
            });
        }
    });
});

$('#resultado_hoja_servicio').on('change', function () {
    if ($(this).val()) {
        $('#btn-finalizar-hoja-modal').show();
    } else {
        $('#btn-finalizar-hoja-modal').hide();
    }
});


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
                success: function (response) {
                    if (response.resultado === 'success') {
                        mostrarExito(response.mensaje);
                        tablaServicios.ajax.reload(null, false);
                    } else {
                        mostrarError(response.mensaje || 'Error al eliminar la hoja de servicio');
                    }
                },
                error: function () {
                    mostrarError('Error al intentar eliminar la hoja de servicio');
                }
            });
        }
    });
}

function ConsultarPermisosServicios() {
    var datos = new FormData();
    datos.append('permisos', 'permisos');
    $.ajax({
        async: true,
        url: "?page=servicios",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "permisos_modulo") {
                    vistaPermisoServicios(lee.permisos);
                }
            } catch (e) {}
        }
    });
}

function vistaPermisoServicios(permisos = null) {

    if (!permisos || !permisos['hoja_servicio']) return;
    if (permisos['hoja_servicio']['eliminar'] && permisos['hoja_servicio']['eliminar']['estado'] == '0') {
        $('.btn-danger').hide();
    }

}

function vistaPermisoServicios(permisos = null) {

    if (!permisos || !permisos['hoja_servicio']) return;
    if (permisos['hoja_servicio']['eliminar'] && permisos['hoja_servicio']['eliminar']['estado'] == '0') {
        $('.btn-danger').hide();
    }
}

