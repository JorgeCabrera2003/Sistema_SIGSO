$(document).ready(function() {
    // Inicializar tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Registrar v  trada
    registrarEntrada();
    
    // Evento para cambio de piso
    $('#selectorPiso').on('change', function() {
        const idPiso = $(this).val();
        if (idPiso && idPiso !== "0") {
            cargarPatchPanels(idPiso);
            
            // Cargar switches si el toggle está activado
            if ($('#toggleSwitch').is(':checked')) {
                cargarSwitches(idPiso);
            }
        } else {
            $('#patchPanelsContainer').html(`
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Seleccione un piso para visualizar los patch panels
                    </div>
                </div>
            `);
            
            $('#switchesContainer').html(`
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Seleccione un piso para visualizar los switches
                    </div>
                </div>
            `);
        }
    });
    
    // Evento para toggle de switches
    $('#toggleSwitch').on('change', function() {
        const idPiso = $('#selectorPiso').val();
        if ($(this).is(':checked') && idPiso && idPiso !== "0") {
            cargarSwitches(idPiso);
        } else {
            $('#switchesContainer').html(`
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Los switches están ocultos
                    </div>
                </div>
            `);
        }
    });
});

function cargarPatchPanels(idPiso) {
    $('#patchPanelsContainer').html(`
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando patch panels...</p>
        </div>
    `);
    
    $.ajax({
        url: "",
        type: "POST",
        data: { obtener_patch_panels: true, id_piso: idPiso },
        success: function(respuesta) {
            try {
                const data = JSON.parse(respuesta);
                if (data.resultado === "success" && data.datos.length > 0) {
                    let html = '';
                    data.datos.forEach(panel => {
                        html += renderPatchPanel(panel);
                    });
                    $('#patchPanelsContainer').html(html);
                    
                    // Inicializar tooltips después de cargar el contenido
                    $('[data-bs-toggle="tooltip"]').tooltip();
                } else {
                    $('#patchPanelsContainer').html(`
                        <div class="col-12 text-center">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> No hay patch panels en este piso
                            </div>
                        </div>
                    `);
                }
            } catch (e) {
                $('#patchPanelsContainer').html(`
                    <div class="col-12 text-center">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> Error al cargar los patch panels
                        </div>
                    </div>
                `);
            }
        },
        error: function() {
            $('#patchPanelsContainer').html(`
                <div class="col-12 text-center">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> Error en la conexión
                    </div>
                </div>
            `);
        }
    });
}

function cargarSwitches(idPiso) {
    $('#switchesContainer').html(`
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando switches...</p>
        </div>
    `);
    
    $.ajax({
        url: "",
        type: "POST",
        data: { obtener_switches: true, id_piso: idPiso },
        success: function(respuesta) {
            try {
                const data = JSON.parse(respuesta);
                if (data.resultado === "success" && data.datos.length > 0) {
                    let html = '';
                    data.datos.forEach(switchItem => {
                        html += renderSwitch(switchItem);
                    });
                    $('#switchesContainer').html(html);
                    
                    // Inicializar tooltips después de cargar el contenido
                    $('[data-bs-toggle="tooltip"]').tooltip();
                } else {
                    $('#switchesContainer').html(`
                        <div class="col-12 text-center">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> No hay switches en este piso
                            </div>
                        </div>
                    `);
                }
            } catch (e) {
                $('#switchesContainer').html(`
                    <div class="col-12 text-center">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> Error al cargar los switches
                        </div>
                    </div>
                `);
            }
        },
        error: function() {
            $('#switchesContainer').html(`
                <div class="col-12 text-center">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> Error en la conexión
                    </div>
                </div>
            `);
        }
    });
}

function renderPatchPanel(panel) {
    const puertosOcupados = panel.puertos_ocupados || 0;
    const puertosDisponibles = panel.cantidad_puertos - puertosOcupados;
    const porcentajeOcupado = (puertosOcupados / panel.cantidad_puertos) * 100;
    
    let estadoClase = 'success'; // Disponible por defecto
    if (porcentajeOcupado > 75) {
        estadoClase = 'danger'; // Crítico
    } else if (porcentajeOcupado > 50) {
        estadoClase = 'warning'; // Advertencia
    }
    
    return `
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">${panel.codigo_bien}</h6>
                    <span class="badge bg-${estadoClase}">${puertosDisponibles} Disp.</span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Serial: ${panel.serial}</small>
                        <small class="text-muted">${panel.tipo_patch_panel}</small>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">${panel.nombre_marca} - ${panel.descripcion}</small>
                    </div>
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar bg-${estadoClase}" role="progressbar" 
                             style="width: ${porcentajeOcupado}%" 
                             aria-valuenow="${porcentajeOcupado}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="puertos-grid">
                        ${renderPuertos('patch', panel.codigo_bien, panel.cantidad_puertos, puertosOcupados)}
                    </div>
                </div>
            </div>
        </div>
    `;
}

function renderSwitch(switchItem) {
    const puertosOcupados = switchItem.puertos_ocupados || 0;
    const puertosDisponibles = switchItem.cantidad_puertos - puertosOcupados;
    const porcentajeOcupado = (puertosOcupados / switchItem.cantidad_puertos) * 100;
    
    let estadoClase = 'success'; // Disponible por defecto
    if (porcentajeOcupado > 75) {
        estadoClase = 'danger'; // Crítico
    } else if (porcentajeOcupado > 50) {
        estadoClase = 'warning'; // Advertencia
    }
    
    return `
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">${switchItem.codigo_bien}</h6>
                    <span class="badge bg-${estadoClase}">${puertosDisponibles} Disp.</span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Serial: ${switchItem.serial}</small>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">${switchItem.nombre_marca} - ${switchItem.descripcion}</small>
                    </div>
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar bg-${estadoClase}" role="progressbar" 
                             style="width: ${porcentajeOcupado}%" 
                             aria-valuenow="${porcentajeOcupado}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div class="puertos-grid">
                        ${renderPuertos('switch', switchItem.codigo_bien, switchItem.cantidad_puertos, puertosOcupados)}
                    </div>
                </div>
            </div>
        </div>
    `;
}

function renderPuertos(tipo, codigoBien, totalPuertos, ocupados) {
    let html = '';
    const columnas = 8; // Número de columnas en la cuadrícula
    
    for (let i = 1; i <= totalPuertos; i++) {
        const ocupado = i <= ocupados;
        const estado = ocupado ? 'Ocupado' : 'Disponible';
        const claseColor = ocupado ? 'danger' : 'success';
        const tooltipText = `Puerto ${i} - ${estado}`;
        
        html += `
            <div class="puerto-item">
                <button class="btn btn-sm btn-${claseColor} puerto-btn" 
                        data-bs-toggle="tooltip" data-bs-placement="top" 
                        title="${tooltipText}"
                        onclick="obtenerInfoPuerto('${tipo}', '${codigoBien}', ${i})">
                    <i class="fa-solid fa-ethernet"></i>
                    <small>${i}</small>
                </button>
            </div>
        `;
        
        // Agregar salto de línea después de cada fila completa
        if (i % columnas === 0 && i < totalPuertos) {
            html += '<div class="w-100"></div>';
        }
    }
    
    return html;
}

function obtenerInfoPuerto(tipo, codigoBien, numeroPuerto) {
    $.ajax({
        url: "",
        type: "POST",
        data: { 
            obtener_info_puerto: true, 
            tipo: tipo, 
            codigo_bien: codigoBien, 
            numero_puerto: numeroPuerto 
        },
        success: function(respuesta) {
            try {
                const data = JSON.parse(respuesta);
                if (data.resultado === "success") {
                    mostrarModalPuerto(tipo, codigoBien, numeroPuerto, data.datos);
                } else {
                    mostrarModalError('Error al obtener información del puerto');
                }
            } catch (e) {
                mostrarModalError('Error al procesar la información');
            }
        },
        error: function() {
            mostrarModalError('Error de conexión');
        }
    });
}

function mostrarModalPuerto(tipo, codigoBien, numeroPuerto, datos) {
    let modalContent = '';
    
    if (tipo === 'patch') {
        if (datos && Object.keys(datos).length > 0) {
            // Puerto ocupado
            modalContent = `
                <h6 class="text-danger">Puerto ${numeroPuerto} - OCUPADO</h6>
                <div class="mb-2">
                    <strong>Equipo conectado:</strong> ${datos.tipo_equipo || 'N/A'}
                </div>
                <div class="mb-2">
                    <strong>Serial del equipo:</strong> ${datos.equipo_serial || 'N/A'}
                </div>
                <div class="mb-2">
                    <strong>Descripción:</strong> ${datos.equipo_descripcion || 'N/A'}
                </div>
                <div class="mb-2">
                    <strong>Empleado asignado:</strong> ${datos.nombre_empleado || 'N/A'} ${datos.apellido_empleado || ''}
                </div>
                <div class="mb-2">
                    <strong>Oficina:</strong> ${datos.nombre_oficina || 'N/A'}
                </div>
                <div class="mb-2">
                    <strong>Piso:</strong> ${datos.tipo_piso || 'N/A'} ${datos.nro_piso || ''}
                </div>
            `;
        } else {
            // Puerto disponible
            modalContent = `
                <h6 class="text-success">Puerto ${numeroPuerto} - DISPONIBLE</h6>
                <p>Este puerto está disponible para conectar un equipo.</p>
            `;
        }
    } else {
        if (datos && Object.keys(datos).length > 0) {
            // Puerto de switch ocupado
            modalContent = `
                <h6 class="text-danger">Puerto ${numeroPuerto} - OCUPADO</h6>
                <div class="mb-2">
                    <strong>Conectado al Patch Panel:</strong> ${datos.patch_panel_serial || 'N/A'}
                </div>
                <div class="mb-2">
                    <strong>Puerto en Patch Panel:</strong> ${datos.puerto_patch_panel || 'N/A'}
                </div>
                <div class="mb-2">
                    <strong>Descripción:</strong> ${datos.patch_panel_descripcion || 'N/A'}
                </div>
                <div class="mb-2">
                    <strong>Piso:</strong> ${datos.tipo_piso || 'N/A'} ${datos.nro_piso || ''}
                </div>
            `;
        } else {
            // Puerto de switch disponible
            modalContent = `
                <h6 class="text-success">Puerto ${numeroPuerto} - DISPONIBLE</h6>
                <p>Este puerto está disponible para conectar a un patch panel.</p>
            `;
        }
    }
    
    $('#puertoModalBody').html(modalContent);
    $('#puertoModal').modal('show');
}

function mostrarModalError(mensaje) {
    $('#puertoModalBody').html(`
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> ${mensaje}
        </div>
    `);
    $('#puertoModal').modal('show');
}

function registrarEntrada() {
    const peticion = new FormData();
    peticion.append('entrada', 'entrada');
    $.ajax({
        url: "",
        type: "POST",
        contentType: false,
        data: peticion,
        processData: false
    });
}