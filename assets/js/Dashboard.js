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

  $('#tipoGraficoRed').on('change', function () {
    tiposGraficos['miGrafico'] = $(this).val();
    renderGrafico('miGrafico', tiposGraficos['miGrafico'], datosGraficos['miGrafico']);
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
              let selectHtml = $('#pisoFiltrado')[0].outerHTML;
              let cardHtml = `
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Patch Panel</h5>
                    <i class="fa-solid fa-server text-muted"></i>
                  </div>
                  <div class="card-body">
                    ${selectHtml}
                    <div id="patchPanelInfo" class="mt-3">
                      <div class="alert alert-warning">No hay datos para este piso.</div>
                    </div>
                  </div>
                </div>
              `;
              $('#card-puntos-red').html(cardHtml);
              $('#pisoFiltrado').val(idPiso);
            }
          } catch (e) {
            let selectHtml = $('#pisoFiltrado')[0].outerHTML;
            let cardHtml = `
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h5 class="card-title mb-0">Patch Panel</h5>
                  <i class="fa-solid fa-server text-muted"></i>
                </div>
                <div class="card-body">
                  ${selectHtml}
                  <div id="patchPanelInfo" class="mt-3">
                    <div class="alert alert-danger">Error al procesar los datos.</div>
                  </div>
                </div>
              </div>
            `;
            $('#card-puntos-red').html(cardHtml);
            $('#pisoFiltrado').val(idPiso);
          }
        },
        error: function () {
          let selectHtml = $('#pisoFiltrado')[0].outerHTML;
          let cardHtml = `
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Patch Panel</h5>
                <i class="fa-solid fa-server text-muted"></i>
              </div>
              <div class="card-body">
                ${selectHtml}
                <div id="patchPanelInfo" class="mt-3">
                  <div class="alert alert-danger">Error en la consulta.</div>
                </div>
              </div>
            </div>
          `;
          $('#card-puntos-red').html(cardHtml);
          $('#pisoFiltrado').val(idPiso);
        }
      });
    } else {
      // Si no hay piso seleccionado, recarga la vista original
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
              let selectHtml = $('#pisoFiltradoSwitch')[0].outerHTML;
              let cardHtml = `
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Switches</h5>
                    <i class="fa-solid fa-server text-muted"></i>
                  </div>
                  <div class="card-body">
                    ${selectHtml}
                    <div id="switchPanelInfo" class="mt-3">
                      <div class="alert alert-warning">No hay datos para este piso.</div>
                    </div>
                  </div>
                </div>
              `;
              $('#card-switch-red').html(cardHtml);
              $('#pisoFiltradoSwitch').val(idPiso);
            }
          } catch (e) {
            let selectHtml = $('#pisoFiltradoSwitch')[0].outerHTML;
            let cardHtml = `
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h5 class="card-title mb-0">Switches</h5>
                  <i class="fa-solid fa-server text-muted"></i>
                </div>
                <div class="card-body">
                  ${selectHtml}
                  <div id="switchPanelInfo" class="mt-3">
                    <div class="alert alert-danger">Error al procesar los datos.</div>
                  </div>
                </div>
              </div>
            `;
            $('#card-switch-red').html(cardHtml);
            $('#pisoFiltradoSwitch').val(idPiso);
          }
        },
        error: function () {
          let selectHtml = $('#pisoFiltradoSwitch')[0].outerHTML;
          let cardHtml = `
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Switches</h5>
                <i class="fa-solid fa-server text-muted"></i>
              </div>
              <div class="card-body">
                ${selectHtml}
                <div id="switchPanelInfo" class="mt-3">
                  <div class="alert alert-danger">Error en la consulta.</div>
                </div>
              </div>
            </div>
          `;
          $('#card-switch-red').html(cardHtml);
          $('#pisoFiltradoSwitch').val(idPiso);
        }
      });
    } else {
      // Si no hay piso seleccionado, recarga la vista original
      location.reload();
    }
  });

  // Seleccionar por defecto piso 5 en ambos selects al cargar
  seleccionarPisoPorDefecto('#pisoFiltrado');
  seleccionarPisoPorDefecto('#pisoFiltradoSwitch');
});

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
          renderGrafico('miGrafico', tiposGraficos['miGrafico'], datosGraficos['miGrafico']);
          renderGrafico('hojas', tiposGraficos['hojas'], datosGraficos['hojas']);
        }
      } catch (e) {
        mensajes("error", null, "Error en JSON: " + e.message);
        console.log(respuesta);
      }
    },
    error: function (request, status, err) {
      mensajes("error", null, "Error: " + err);
    }
  });
}
