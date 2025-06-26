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