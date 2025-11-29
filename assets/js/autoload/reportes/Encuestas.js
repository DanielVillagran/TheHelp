let chartCalificaciones = null;
let chartFacturados  = null;
let chartHC  = null;
$(document).ready(function () {
  pageLengthDatatable = 5;
  getDataCalificaciones();
  
});
$("#calificaciones_empresa").change(function () {
  $.ajax({
    url: "/Empresas/get_Empresas_sedes",
    type: 'POST',
    data: {
      id: $("#calificaciones_empresa").val()
    },
    dataType: 'json',
    beforeSend: function (e) {
      swal({
        title: "Cargando",
        showConfirmButton: false,
        imageUrl: "/assets/images/loader.gif"
      });
    },
    success: function (data) {
      swal.close();
      $("#calificaciones_sede").empty().append('  <option value="">Todos</option>' + data.select);

    }
  });
});
$("#form_filtros_calificaciones select, #form_filtros_calificaciones input").on("change", function () {
  getDataCalificaciones();
});
function getDataCalificaciones() {
  var data = $("#form_filtros_calificaciones").serialize();

  $.ajax({
    url: "/Reportes/get_graficas_encuestas",
    type: 'POST',
    data: data,
    dataType: 'json',
    beforeSend: function (e) {
      swal({
        title: "Cargando",
        showConfirmButton: false,
        imageUrl: "/assets/images/loader.gif"
      });
    },
    success: function (data) {
      inicializarChartCalificaciones(data);
      swal.close();
    }
  });
}
function inicializarChartCalificaciones(preguntas) {
  const ctx = document.getElementById("grafica_calificaciones").getContext("2d");

  const labels = preguntas.map(p => p.pregunta);
  const data = preguntas.map(p => parseFloat(p.promedio));

  if (window.chartPromedios) {
    window.chartPromedios.destroy();
  }

  window.chartPromedios = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Promedio',
        data: data,
        backgroundColor: 'rgba(33, 150, 243, 0.6)',
        borderColor: 'rgba(33, 150, 243, 1)',
        borderWidth: 1
      }]
    },
    options: {
      indexAxis: 'y', 
      scales: {
        x: {
          beginAtZero: true,
          max: 100,
          ticks: {
            stepSize: 1
          },
          title: {
            display: true,
            text: 'Promedio'
          }
        },
        y: {
          ticks: {
            autoSkip: false
          }
        }
      },
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: (context) => "Promedio: " + context.raw.toFixed(2)
          }
        }
      }
    }
  });
}

function obtenerDetalleFaltasCalificaciones(tipo) {
  var data = $("#form_filtros_calificaciones").serialize();
  data += "&tipo=" + encodeURIComponent(tipo);

  $.ajax({
    url: "/Reportes/get_calificaciones_detalle",
    type: 'POST',
    data: data,
    dataType: 'json',
    beforeSend: function (e) {
      swal({
        title: "Cargando",
        showConfirmButton: false,
        imageUrl: "/assets/images/loader.gif"
      });
    },
    success: function (data) {
      let id = "#tablaDetalle";
      if ($.fn.DataTable.isDataTable(id)) {
        $(id).DataTable().destroy();
      }
      $(id + ' thead').empty().append(data.head);
      $(id + ' tbody').empty().append(data.table);
      $(id).show();
      inicializarDatatable(id);
      swal.close();
    }
  });
}
