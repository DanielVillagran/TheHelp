$("#icon-preview").hide();

var states = "";
$(document).ready(function () {
  if ($("#id").val() != 0) {
    get_info_Eventos($("#id").val());
    
  }

  $(".input-file-icon").change(function () {
    var t = $(this).val();
    var labelText = "Img: " + t.substr(12, t.length);
    $(this).prev("label").text(labelText);
  });

  $(".btn-nuevo-servicio").click(function () {
    $("#modalNuevopregunta").modal("show");
  });
  
  $("#tipo").change(function () {
    $("#google").attr("required", false);
    $("#google_div").hide();
    $("#preguntas_div").hide();
    if ($("#tipo").val() == 4) {
      $("#preguntas_div").show();
    } else if ($("#tipo").val() == 2) {
      $("#google").attr("required", "required");
      $("#google_div").show();
    }
  });
});

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $("#icon-preview").attr("src", e.target.result);
    };

    reader.readAsDataURL(input.files[0]);
  }
}

$(".input-file-icon").change(function () {
  readURL(this);
  $("#icon-preview").show();
});

$(".input-contacto-principal").focusin(function () {
  if ($(".input-contacto-principal").val() == "") {
    $(".floating-label-no-required").addClass("floating-label-active");
  }
});

$(".input-contacto-principal").focusout(function () {
  if ($(".input-contacto-principal").val() == "") {
    $(".floating-label-no-required").removeClass("floating-label-active");
  }
});
function editar_pregunta(id) {
  $.ajax({
    type: "post",
    url: "/Eventos/get_info_Pregunta",
    data: {
      id: id,
    },
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      $("#modalNuevopregunta").modal("toggle");
      $("#descripcion").val(data.pregunta);
      $("#id_pregunta").val(id);
      $("#eliminar_s").show();
    },
  });
}
function eliminar_pregunta() {
  $.ajax({
    type: "post",
    url: "/Eventos/delete_pregunta",
    data: {
      id: $("#id_pregunta").val(),
    },
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      limpiar_form_modal();
      $("#modalNuevopregunta").modal("hide");
      get_preguntas_list();
    },
  });
}

function get_info_Eventos(id) {
  console.log(id);
  $.ajax({
    type: "post",
    url: "/Eventos/get_info_Eventos",
    data: {
      id: id,
    },
    dataType: "json",
    beforeSend: function () {
      swal({
        title: "Cargando",
        showConfirmButton: false,
        imageUrl: "/assets/images/loader.gif",
      });
    },
    success: function (data) {
      swal.close();
      for (var key in data) {
        if (key != "logo") {
          $('[name="users[' + key + ']"]').val($.trim(data[key]));
        }
      }
      $("#preguntas_div").hide();
      console.log($("#tipo").val());
      if ($("#tipo").val() == 4) {
        $("#preguntas_div").show();
        get_preguntas_list();
      }else if ($("#tipo").val() == 3) {
        $("#participantes_div").show();
        get_participantes_list();
      }else if ($("#tipo").val() == 1) {
        $("#calificaciones_div").show();
        get_calificaciones_list();
      } else if ($("#tipo").val() == 2) {
        $("#google").attr("required", "required");
        $("#google_div").show();
      }
      if (data.logo != null) {
        $(".current_logo").html('<img src="/assets/' + data.logo + '"/>');
      }
    },
  });
}

function get_preguntas_list() {
  $.ajax({
    type: "post",
    url: "/Eventos/get_preguntas_list",
    data: {
      id: $("#id").val(),
    },
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      swal.close();
      $("#details_grid > tbody").empty().append(data.table);
    },
  });
}
function get_participantes_list() {
  $.ajax({
    type: "post",
    url: "/Eventos/get_participantes_list",
    data: {
      id: $("#id").val(),
    },
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      swal.close();
      $("#participantes_list > tbody").empty().append(data.table);
    },
  });
}
function get_calificaciones_list() {
  $.ajax({
    type: "post",
    url: "/Eventos/get_calificaciones_list",
    data: {
      id: $("#id").val(),
    },
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      swal.close();
      $("#calificaciones_list > tbody").empty().append(data.table);
    },
  });
}

function save_Eventos() {
  event.preventDefault();
  var data = new FormData(document.getElementById("Eventos_info"));
  $.ajax({
    type: "post",
    url: "/Eventos/save_info",
    data: data,
    processData: false,
    contentType: false,
    beforeSend: function () {
      swal({
        title: "Cargando",
        showConfirmButton: false,
        imageUrl: "/assets/images/loader.gif",
      });
    },
    success: function (data) {
      swal.close();
      if ($("#id").val() != 0) {
        location.href = "/Eventos";
      } else {
        location.href = "/Eventos/edit/" + data.insert_id;
      }
    },
  });
}
function limpiar_form_modal() {
  $("#descripcion").val("");
  $("#id_pregunta").val(0);
  $("#eliminar_s").hide();
}
function agregar_pregunta() {
  var data = {
    id: $("#id").val(),
    descripcion: $("#descripcion").val(),
    id_pregunta: $("#id_pregunta").val(),
  };
  $.ajax({
    type: "post",
    url: "/Eventos/save_pregunta",
    data: data,
    beforeSend: function () {
      swal({
        title: "Cargando",
        showConfirmButton: false,
        imageUrl: "/assets/images/loader.gif",
      });
    },
    success: function (data) {
      swal.close();
      limpiar_form_modal();
      $("#modalNuevopregunta").modal("hide");
      get_preguntas_list();
      //location.href="/Eventos";
    },
  });
}

function format_date(date) {
  var formated_date = "";
  var array_date = date.split("T")[0].split("-");
  var array_hour = date.split("T")[1].split(":");
  formated_date +=
    array_date[2] +
    "-" +
    array_date[1] +
    "-" +
    array_date[0] +
    " " +
    array_hour[0] +
    ":" +
    array_hour[1] +
    ":00";
  return formated_date;
}
function descarga_excel_pre(){
  window.open("/Eventos/get_preguntas_list_csv/"+$("#id").val());


}
function descarga_excel_calif(){
  window.open("/Eventos/get_calif_list_csv/"+$("#id").val());

}
function descarga_excel_par(){
  window.open("/Eventos/get_part_list_csv/"+$("#id").val());
}
