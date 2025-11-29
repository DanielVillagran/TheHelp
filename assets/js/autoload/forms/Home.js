let chartAsistencias = null;
let chartFacturados  = null;
let chartHC  = null;
$(document).ready(function () {
  pageLengthDatatable = 5;
  getDataAsistencias();
  getDataFacturados();
  getDataHC();
  getDataSatisfaccion();
});
$("#asistencias_empresa").change(function () {
  $.ajax({
    url: "/Empresas/get_Empresas_sedes",
    type: 'POST',
    data: {
      id: $("#asistencias_empresa").val()
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
      $("#asistencias_sede").empty().append('  <option value="">Todos</option>' + data.select);

    }
  });
});
$("#form_filtros_asistencias select, #form_filtros_asistencias input").on("change", function () {
  getDataAsistencias();
});
function getDataAsistencias() {
  var data = $("#form_filtros_asistencias").serialize();

  $.ajax({
    url: "/Reportes/get_asistencias_totales",
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
      inicializarChartAsistencias(data);
      swal.close();
    }
  });
}
function inicializarChartAsistencias(data) {
  const ctx = document.getElementById("grafica_asistencias").getContext("2d");

  const total = data.asistencias + data.faltas;

  if (chartAsistencias) {
    chartAsistencias.destroy();
    chartAsistencias = null;
  }

  if (total > 0) {
    chartAsistencias = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ["Asistencias", "Faltas"],
        datasets: [{
          data: [data.asistencias, data.faltas],
          backgroundColor: ["rgba(15, 65, 90, 0.85)", "rgba(236, 109, 10, 0.85)"]
        }]
      },
      options: {
        plugins: {
          datalabels: {
            color: '#FFF',
            font: { weight: 'bold', size: 12 },
            formatter: (value) => value + " (" + ((value / total) * 100).toFixed(1) + "%)"
          },
          legend: { position: 'bottom' }
        },
        onClick: function (event, elements) {
          if (elements.length > 0) {
            const index = elements[0].index;
            const label = this.data.labels[index];
            const value = this.data.datasets[0].data[index];

            // Aquí abres el modal y llenas la info
            $('#modalDetalle').modal('show');
            $('#modalDetalleLabel').text("Detalle de " + label);
            obtenerDetalleFaltasAsistencias(label);
          }
        }
      },
      plugins: [ChartDataLabels]
    });
  }
}
function obtenerDetalleFaltasAsistencias(tipo) {
  var data = $("#form_filtros_asistencias").serialize();
  data += "&tipo=" + encodeURIComponent(tipo);

  $.ajax({
    url: "/Reportes/get_asistencias_detalle",
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


$("#facturados_empresa").change(function () {
  $.ajax({
    url: "/Empresas/get_Empresas_sedes",
    type: 'POST',
    data: {
      id: $("#facturados_empresa").val()
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
      $("#facturados_sede").empty().append('  <option value="">Todos</option>' + data.select);

    }
  });
});
$("#form_filtros_facturados select, #form_filtros_facturados input").on("change", function () {
  getDataFacturados();
});

function getDataFacturados() {
  var data = $("#form_filtros_facturados").serialize();

  $.ajax({
    url: "/Reportes/get_facturados_totales",
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
      inicializarChartFacturados(data);
      swal.close();
    }
  });
}


function inicializarChartFacturados(data) {
  const ctx = document.getElementById("grafica_facturados").getContext("2d");

  const total = data.noFacturado + data.facturado;

  if (chartFacturados) {
    chartFacturados.destroy();
    chartFacturados = null;
  }

  if (total > 0) {
    chartFacturados = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ["Facturados", "Faltas"],
        datasets: [{
          data: [data.facturado, data.noFacturado],
          backgroundColor: ["rgba(15, 65, 90, 0.85)", "rgba(236, 109, 10, 0.85)"]
        }]
      },
      options: {
        plugins: {
          datalabels: {
            color: '#FFF',
            font: { weight: 'bold', size: 12 },
            formatter: (value) => {
              const moneda = new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: 'MXN',
                minimumFractionDigits: 2
              }).format(value);
              return `${moneda} (${((value / total) * 100).toFixed(1)}%)`;
            }
          },
          legend: { position: 'bottom' }
        },
        onClick: function (event, elements) {
          if (elements.length > 0) {
            const index = elements[0].index;
            const label = this.data.labels[index];
            const value = this.data.datasets[0].data[index];

            // Aquí abres el modal y llenas la info
            $('#modalDetalle').modal('show');
            $('#modalDetalleLabel').text("Detalle de " + label);
            obtenerDetalleFaltasFacturados(label);
          }
        }
      },
      plugins: [ChartDataLabels]
    });
  }
}
function obtenerDetalleFaltasFacturados(tipo) {
  var data = $("#form_filtros_facturados").serialize();
  data += "&tipo=" + encodeURIComponent(tipo);

  $.ajax({
    url: "/Reportes/get_facturados_detalle",
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


$("#hc_empresa").change(function () {
  $.ajax({
    url: "/Empresas/get_Empresas_sedes",
    type: 'POST',
    data: {
      id: $("#hc_empresa").val()
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
      $("#hc_sede").empty().append('  <option value="">Todos</option>' + data.select);

    }
  });
});
$("#form_filtros_hc select, #form_filtros_hc input").on("change", function () {
  getDataHC();
});

function getDataHC() {
  var data = $("#form_filtros_hc").serialize();

  $.ajax({
    url: "/Reportes/get_hc_totales",
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
      inicializarChartHC(data);
      swal.close();
    }
  });
}


function inicializarChartHC(data) {
  const ctx = document.getElementById("grafica_hc").getContext("2d");

  const total = data.cubiertos + data.vacantes;

  if (chartHC) {
    chartHC.destroy();
    chartHC = null;
  }

  if (total > 0) {
    chartHC = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ["Activos", "Vacantes"],
        datasets: [{
          data: [data.cubiertos, data.vacantes],
          backgroundColor: ["rgba(15, 65, 90, 0.85)", "rgba(236, 109, 10, 0.85)"]
        }]
      },
      options: {
        plugins: {
          datalabels: {
            color: '#FFF',
            font: { weight: 'bold', size: 12 },
            formatter: (value) => {
              const moneda = new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: 'MXN',
                minimumFractionDigits: 2
              }).format(value);
              return `${value} (${((value / total) * 100).toFixed(1)}%)`;
            }
          },
          legend: { position: 'bottom' }
        },
        onClick: function (event, elements) {
          if (elements.length > 0) {
            const index = elements[0].index;
            const label = this.data.labels[index];
            const value = this.data.datasets[0].data[index];

            // Aquí abres el modal y llenas la info
            $('#modalDetalle').modal('show');
            $('#modalDetalleLabel').text("Detalle de " + label);
            obtenerDetalleFaltasHC(label);
          }
        }
      },
      plugins: [ChartDataLabels]
    });
  }
}
function obtenerDetalleFaltasHC(tipo) {
  var data = $("#form_filtros_hc").serialize();
  data += "&tipo=" + encodeURIComponent(tipo);

  $.ajax({
    url: "/Reportes/get_hc_detalle",
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

function actualizarSemaforo(valor) {
  const el = document.getElementById('indice_satisfaccion');
  el.innerText = valor + '%';

  el.classList.remove('green', 'yellow', 'red');

  if (valor >= 85) {
      el.classList.add('green');
  } else if (valor >= 80) {
      el.classList.add('yellow');
  } else {
      el.classList.add('red');
  }
}

function getDataSatisfaccion() {
  var data = $("#form_filtros_satisfaccion").serialize();

  $.ajax({
    url: "/Reportes/get_satisfaccion_encuestas",
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
      const valor = parseFloat(data).toFixed(2);
      actualizarSemaforo(valor);
      swal.close();
    }
  });
}
$("#form_filtros_satisfaccion select, #form_filtros_satisfaccion input").on("change", function () {
  getDataSatisfaccion();
});