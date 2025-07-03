let chartInstances = {};
let datosGraficos = {};
let tiposGraficos = {
  GraUsuario: 'bar',
  Graftecnicos: 'bar',
  miGrafico: 'bar'
};

$(document).ready(function () {
  registrarEntrada();
  cargarGraficos();

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
