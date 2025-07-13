let chartInstances = {};
let datosGraficos = {};
let tiposGraficos = {
  GraUsuario: 'bar',
  Graftecnicos: 'bar',
  miGrafico: 'bar',
  hojas: 'bar'
};

$(document).ready(function () {
  registrarEntrada();
  cargarGraficos();

  // Seleccionar por defecto el piso 5 si existe
  function seleccionarPisoPorDefecto(selector) {
    let $select = $(selector);
    let piso5 = $select.find('option').filter(function () {
      return $(this).text().trim().endsWith('5');
    }).first();
    if (piso5.length) {
      $select.val(piso5.val()).trigger('change');
    }
  }

  // Listeners individuales para cada <select>
  $('#tipoGraficoUsuario').on('change', function () {
    tiposGraficos['GraUsuario'] = $(this).val();
    renderGrafico('GraUsuario', tiposGraficos['GraUsuario'], datosGraficos['GraUsuario']);
  });

  $('#tipoGraficoTecnicos').on('change', function () {
    tiposGraficos['Graftecnicos'] = $(this).val();
    renderGrafico('Graftecnicos', tiposGraficos['Graftecnicos'], datosGraficos['Graftecnicos']);
  });

  $('#tipoGraficoHojas').on('change', function () {
    tiposGraficos['hojas'] = $(this).val();
    renderGrafico('hojas', tiposGraficos['hojas'], datosGraficos['hojas']);
  });

  // Evento para el select de pisos de Patch Panel
  $(document).on('change', '#pisoFiltrado', function () {
    let idPiso = $(this).val();
    if (idPiso && idPiso !== "0") {
      $('#patchPanelLoading').show();
      $.ajax({
        url: "",
        type: "POST",
        data: { pisoFiltrado: idPiso },
        success: function (respuesta) {
          $('#patchPanelLoading').hide();
          try {
            let data = JSON.parse(respuesta);
            if (data.resultado === "reporte_patch_panel" && data.datos && data.datos.length > 0) {
              let info = data.datos[0];
              let selectHtml = $('#pisoFiltrado')[0].outerHTML;
              let cardHtml = `
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Patch Panel - Piso Seleccionado</h5>
                    <i class="fa-solid fa-server text-muted"></i>
                  </div>
                  <div class="card-body">
                    ${selectHtml}
                    <div id="patchPanelInfo" class="mt-3">
                      <h2 class="total-balance mb-3">${info['Cantidad Disponible'] ?? 0} Disponibles</h2>
                      <div class="account-list">
                        <div class="account-item d-flex justify-content-between mb-2">
                          <span class="account-name">Total Puertos</span>
                          <span class="account-balance">${info['Cantidad Total'] ?? 0}</span>
                        </div>
                        <div class="account-item d-flex justify-content-between mb-2">
                          <span class="account-name">Puertos Ocupados</span>
                          <span class="account-balance">${info['Cantidad Ocupado'] ?? 0}</span>
                        </div>
                        <div class="account-item d-flex justify-content-between mb-2">
                          <span class="account-name">Puertos Disponibles</span>
                          <span class="account-balance">${info['Cantidad Disponible'] ?? 0}</span>
                        </div>
                        <div class="account-item d-flex justify-content-between mb-2">
                          <span class="account-name">Cantidad Patch Panel</span>
                          <span class="account-balance">${info['Cantidad Patch Panel'] ?? 0}</span>
                        </div>
                      </div>
                      <span class="account-name">Gráfico</span>
                      <div class="grafico-container" style="width: 100%; height: 200px;">
                        <canvas id="miGrafico"></canvas>
                      </div>
                      <select class="form-select mt-2" id="tipoGraficoRed">
                        <option value="bar">Barras</option>
                        <option value="pie">Torta</option>
                        <option value="line">Líneas</option>
                      </select>
                    </div>
                  </div>
                </div>
              `;
              $('#card-puntos-red').html(cardHtml);
              $('#pisoFiltrado').val(idPiso);

              let datosGrafico = {
                label: 'Patch Panel',
                labels: ['Total Puertos', 'Ocupados', 'Disponibles'],
                data: [
                  parseInt(info['Cantidad Total'] ?? 0),
                  parseInt(info['Cantidad Ocupado'] ?? 0),
                  parseInt(info['Cantidad Disponible'] ?? 0)
                ]
              };
              datosGraficos['miGrafico'] = datosGrafico;
              renderGrafico('miGrafico', tiposGraficos['miGrafico'], datosGrafico);

              $('#tipoGraficoRed').off('change').on('change', function () {
                tiposGraficos['miGrafico'] = $(this).val();
                renderGrafico('miGrafico', tiposGraficos['miGrafico'], datosGrafico);
              });
            } else {
              mostrarErrorCard('#card-puntos-red', idPiso, 'No hay datos para este piso.', 'warning');
            }
          } catch (e) {
            mostrarErrorCard('#card-puntos-red', idPiso, 'Error al procesar los datos.', 'danger');
          }
        },
        error: function () {
          mostrarErrorCard('#card-puntos-red', idPiso, 'Error en la consulta.', 'danger');
        }
      });
    } else {
      location.reload();
    }
  });

  // Evento para el select de pisos de Switch
  $(document).on('change', '#pisoFiltradoSwitch', function () {
    let idPiso = $(this).val();
    if (idPiso && idPiso !== "0") {
      $('#switchPanelLoading').show();
      $.ajax({
        url: "",
        type: "POST",
        data: { pisoFiltradoSwitch: idPiso },
        success: function (respuesta) {
          $('#switchPanelLoading').hide();
          try {
            let data = JSON.parse(respuesta);
            if (data.resultado === "reporte_switch_panel" && data.datos && data.datos.length > 0) {
              let info = data.datos[0];
              let selectHtml = $('#pisoFiltradoSwitch')[0].outerHTML;
              let cardHtml = `
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Switches - Piso Seleccionado</h5>
                    <i class="fa-solid fa-server text-muted"></i>
                  </div>
                  <div class="card-body">
                    ${selectHtml}
                    <div id="switchPanelInfo" class="mt-3">
                      <h2 class="total-balance mb-3">${info['Cantidad Disponible'] ?? 0} Disponibles</h2>
                      <div class="account-list">
                        <div class="account-item d-flex justify-content-between mb-2">
                          <span class="account-name">Total Puertos</span>
                          <span class="account-balance">${info['Cantidad Total'] ?? 0}</span>
                        </div>
                        <div class="account-item d-flex justify-content-between mb-2">
                          <span class="account-name">Puertos Ocupados</span>
                          <span class="account-balance">${info['Cantidad Ocupado'] ?? 0}</span>
                        </div>
                        <div class="account-item d-flex justify-content-between mb-2">
                          <span class="account-name">Puertos Disponibles</span>
                          <span class="account-balance">${info['Cantidad Disponible'] ?? 0}</span>
                        </div>
                        <div class="account-item d-flex justify-content-between mb-2">
                          <span class="account-name">Cantidad Switch</span>
                          <span class="account-balance">${info['Cantidad Switch'] ?? 0}</span>
                        </div>
                      </div>
                      <span class="account-name">Gráfico</span>
                      <div class="grafico-container" style="width: 100%; height: 200px;">
                        <canvas id="miGraficoSwitch"></canvas>
                      </div>
                      <select class="form-select mt-2" id="tipoGraficoSwitch">
                        <option value="bar">Barras</option>
                        <option value="pie">Torta</option>
                        <option value="line">Líneas</option>
                      </select>
                    </div>
                  </div>
                </div>
              `;
              $('#card-switch-red').html(cardHtml);
              $('#pisoFiltradoSwitch').val(idPiso);

              let datosGrafico = {
                label: 'Switches',
                labels: ['Total Puertos', 'Ocupados', 'Disponibles'],
                data: [
                  parseInt(info['Cantidad Total'] ?? 0),
                  parseInt(info['Cantidad Ocupado'] ?? 0),
                  parseInt(info['Cantidad Disponible'] ?? 0)
                ]
              };
              datosGraficos['miGraficoSwitch'] = datosGrafico;
              renderGrafico('miGraficoSwitch', tiposGraficos['miGrafico'], datosGrafico);

              $('#tipoGraficoSwitch').off('change').on('change', function () {
                tiposGraficos['miGrafico'] = $(this).val();
                renderGrafico('miGraficoSwitch', tiposGraficos['miGrafico'], datosGrafico);
              });
            } else {
              mostrarErrorCard('#card-switch-red', idPiso, 'No hay datos para este piso.', 'warning');
            }
          } catch (e) {
            mostrarErrorCard('#card-switch-red', idPiso, 'Error al procesar los datos.', 'danger');
          }
        },
        error: function () {
          mostrarErrorCard('#card-switch-red', idPiso, 'Error en la consulta.', 'danger');
        }
      });
    } else {
      location.reload();
    }
  });

  // Eventos para los reportes
  $(document).on('click', '#generarReporteBienes', generarReporteBienes);
  $(document).on('click', '#generarReporteSolicitudes', generarReporteSolicitudes);
  $(document).on('click', '#generarReporteMateriales', generarReporteMateriales);

  // Seleccionar por defecto piso 5 en ambos selects al cargar
  seleccionarPisoPorDefecto('#pisoFiltrado');
  seleccionarPisoPorDefecto('#pisoFiltradoSwitch');
});

function mostrarErrorCard(selector, idPiso, mensaje, tipo) {
  let selectHtml = $(selector + ' select')[0].outerHTML;
  let cardHtml = `
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">${selector === '#card-puntos-red' ? 'Patch Panel' : 'Switches'}</h5>
        <i class="fa-solid fa-server text-muted"></i>
      </div>
      <div class="card-body">
        ${selectHtml}
        <div id="${selector === '#card-puntos-red' ? 'patchPanelInfo' : 'switchPanelInfo'}" class="mt-3">
          <div class="alert alert-${tipo}">${mensaje}</div>
        </div>
      </div>
    </div>
  `;
  $(selector).html(cardHtml);
  $(selector + ' select').val(idPiso);
}

function cargarGraficos() {
  const peticion = new FormData();
  peticion.append('grafico', 'grafico');
  enviaAjax(peticion);
}

function renderGrafico(canvasId, tipo, datos) {
  if (chartInstances[canvasId]) {
    chartInstances[canvasId].destroy();
  }

  const ctx = document.getElementById(canvasId);
  if (!ctx) return;

  chartInstances[canvasId] = new Chart(ctx, {
    type: tipo,
    data: {
      labels: datos.labels,
      datasets: [{
        label: datos.label,
        data: datos.data,
        backgroundColor: datos.backgroundColor || [
          'rgba(75, 192, 192, 0.6)',
          'rgba(255, 159, 64, 0.6)',
          'rgba(153, 102, 255, 0.6)'
        ]
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'top'
        }
      },
      layout: {
        padding: 10
      }
    }
  });
}

function registrarEntrada() {
  const peticion = new FormData();
  peticion.append('entrada', 'entrada');
  enviaAjax(peticion);
}

function generarReporteBienes() {
  const formData = new FormData(document.getElementById('filtroBienes'));
  formData.append('filtro_bienes', 'true');
  
  $('#reporteBienesLoading').show();
  $.ajax({
    url: "",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function(respuesta) {
      $('#reporteBienesLoading').hide();
      try {
        const data = JSON.parse(respuesta);
        if (data.resultado === "reporte_bienes") {
          let html = '<div class="table-responsive"><table class="table table-striped"><thead><tr>';
          html += '<th>Código Bien</th><th>Tipo</th><th>Marca</th><th>Descripción</th><th>Estado</th><th>Oficina</th></tr></thead><tbody>';
          
          data.datos.forEach(item => {
            html += `<tr>
              <td>${item.codigo_bien || 'N/A'}</td>
              <td>${item.nombre_tipo_bien || 'N/A'}</td>
              <td>${item.nombre_marca || 'N/A'}</td>
              <td>${item.descripcion || 'N/A'}</td>
              <td>${item.estado || 'N/A'}</td>
              <td>${item.nombre_oficina || 'N/A'}</td>
            </tr>`;
          });
          
          html += '</tbody></table></div>';
          $('#reporteBienesResultado').html(html);
        } else {
          $('#reporteBienesResultado').html(`<div class="alert alert-warning">${data.mensaje || 'No se encontraron datos'}</div>`);
        }
      } catch (e) {
        $('#reporteBienesResultado').html('<div class="alert alert-danger">Error al procesar los datos</div>');
      }
    },
    error: function() {
      $('#reporteBienesLoading').hide();
      $('#reporteBienesResultado').html('<div class="alert alert-danger">Error en la consulta</div>');
    }
  });
}

function generarReporteSolicitudes() {
  const formData = new FormData(document.getElementById('filtroSolicitudes'));
  formData.append('filtro_solicitudes', 'true');
  
  $('#reporteSolicitudesLoading').show();
  $.ajax({
    url: "",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function(respuesta) {
      $('#reporteSolicitudesLoading').hide();
      try {
        const data = JSON.parse(respuesta);
        if (data.resultado === "reporte_solicitudes") {
          let html = '<div class="table-responsive"><table class="table table-striped"><thead><tr>';
          html += '<th>N° Solicitud</th><th>Técnico</th><th>Tipo Servicio</th><th>Estado</th><th>Fecha</th></tr></thead><tbody>';
          
          data.datos.forEach(item => {
            html += `<tr>
              <td>${item.nro_solicitud || 'N/A'}</td>
              <td>${item.nombre_tecnico || 'N/A'}</td>
              <td>${item.nombre_tipo_servicio || 'N/A'}</td>
              <td>${item.estado_solicitud || 'N/A'}</td>
              <td>${item.fecha_solicitud || 'N/A'}</td>
            </tr>`;
          });
          
          html += '</tbody></table></div>';
          $('#reporteSolicitudesResultado').html(html);
        } else {
          $('#reporteSolicitudesResultado').html(`<div class="alert alert-warning">${data.mensaje || 'No se encontraron datos'}</div>`);
        }
      } catch (e) {
        $('#reporteSolicitudesResultado').html('<div class="alert alert-danger">Error al procesar los datos</div>');
      }
    },
    error: function() {
      $('#reporteSolicitudesLoading').hide();
      $('#reporteSolicitudesResultado').html('<div class="alert alert-danger">Error en la consulta</div>');
    }
  });
}

function generarReporteMateriales() {
  const formData = new FormData(document.getElementById('filtroMateriales'));
  formData.append('filtro_materiales', 'true');
  
  $('#reporteMaterialesLoading').show();
  $.ajax({
    url: "",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function(respuesta) {
      $('#reporteMaterialesLoading').hide();
      try {
        const data = JSON.parse(respuesta);
        if (data.resultado === "reporte_materiales") {
          let html = '<div class="table-responsive"><table class="table table-striped"><thead><tr>';
          html += '<th>Material</th><th>Cantidad</th><th>Ubicación</th><th>Fecha</th></tr></thead><tbody>';
          
          data.datos.forEach(item => {
            html += `<tr>
              <td>${item.nombre_material || 'N/A'}</td>
              <td>${item.cantidad || 'N/A'}</td>
              <td>${item.nombre_oficina || 'N/A'}</td>
              <td>${item.fecha || 'N/A'}</td>
            </tr>`;
          });
          
          html += '</tbody></table></div>';
          $('#reporteMaterialesResultado').html(html);
        } else {
          $('#reporteMaterialesResultado').html(`<div class="alert alert-warning">${data.mensaje || 'No se encontraron datos'}</div>`);
        }
      } catch (e) {
        $('#reporteMaterialesResultado').html('<div class="alert alert-danger">Error al procesar los datos</div>');
      }
    },
    error: function() {
      $('#reporteMaterialesLoading').hide();
      $('#reporteMaterialesResultado').html('<div class="alert alert-danger">Error en la consulta</div>');
    }
  });
}

function enviaAjax(datos) {
  $.ajax({
    url: "",
    type: "POST",
    contentType: false,
    data: datos,
    processData: false,
    cache: false,
    timeout: 10000,
    success: function (respuesta) {
      try {
        const lee = JSON.parse(respuesta);
        if (lee.resultado === "grafico") {
          datosGraficos = lee.datos;

          // Renderiza todos los gráficos
          renderGrafico('GraUsuario', tiposGraficos['GraUsuario'], datosGraficos['GraUsuario']);
          renderGrafico('Graftecnicos', tiposGraficos['Graftecnicos'], datosGraficos['Graftecnicos']);
          renderGrafico('hojas', tiposGraficos['hojas'], datosGraficos['hojas']);
        }
      } catch (e) {
        console.error("Error en JSON: " + e.message);
        console.log(respuesta);
      }
    },
    error: function (request, status, err) {
      console.error("Error: " + err);
    }
  });
}