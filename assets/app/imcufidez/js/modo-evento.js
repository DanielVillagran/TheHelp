var id = 0;
var calif = 0;
var respuestas = [];
$(document).ready(function () {
  var urlParams = new URLSearchParams(window.location.search);
  id = urlParams.get("id");
  console.log(id);
  $.ajax({
    url: server + "/WS/get_evento",
    type: "POST",
    data: { id: id },
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      $("#titulo").empty().append(data.nombre);
      $("#descripcion").empty().append(data.descripcion);
      $("#elementos").empty().append(data.elemento);
      $("#imagen").css(
        "background-image",
        "url(https://zumpango.vmcomp.com.mx/assets/" + data.logo + ")"
      );
    },
  });
});
function calificar(califi) {
  calif = califi;
  $("#div_comentario").show();
}
function enviar() {
  $.ajax({
    url: server + "/WS/add_calificacion",
    type: "POST",
    data: { id: id, comentario: $("#comentario").val(), calif: calif },
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      $(".d-calificacion").hide();
      $(".d-form-calificacion").hide();
      $(".p-calificar").fadeIn();
    },
  });
}
function participar() {
  $("#modalTelefono").modal("toggle");
  //$("#participar").hide();
  //$("#participando").show();
}
$("#form_participar").submit(function (e) {
  e.preventDefault();
  $.ajax({
    url: server + "/WS/add_participacion",
    type: "POST",
    data: { id: id, telefono: $("#telefono").val() },
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      $("#modalTelefono").modal("hide");
      $("#participar").hide();
      $("#participando").show();
    },
  });
});
function si(id) {
  if (respuestas.find((fruta) => fruta.id === id) === undefined) {
    respuestas.push({ id: id, respuesta: 1 });
  } else {
    respuestas.find((fruta) => fruta.id === id).respuesta = 1;
  }
  console.log(respuestas);
  $("#no_" + id + " img").attr("src", "images/icons/icon-close-black.svg");
  $("#no_" + id).removeClass("active-no");
  $("#si_" + id).addClass("active-si");
  $("#si_" + id)
    .find("img")
    .attr("src", "images/icons/icon-check-si.svg");
}
function no(id) {
  //respuestas[id]=0;
  if (respuestas.find((fruta) => fruta.id === id) === undefined) {
    respuestas.push({ id: id, respuesta: 0 });
  } else {
    respuestas.find((fruta) => fruta.id === id).respuesta = 0;
  }
  console.log(respuestas);

  $("#si_" + id + " img").attr("src", "images/icons/icon-check-si-black.svg");
  $("#si_" + id).removeClass("active-si");
  $("#no_" + id).addClass("active-no");
  $("#no_" + id)
    .find("img")
    .attr("src", "images/icons/icon-close.svg");
}
function enviar_encuesta() {
  $.ajax({
    url: server + "/WS/send_encuesta",
    type: "POST",
    data: { id: id, respuestas: respuestas },
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      $("#encuestas").hide();
      $("#calificado").show();
    },
  });
}
