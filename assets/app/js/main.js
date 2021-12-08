//const server='http://zumpango.local:8890';
const server = "https://zumpango.vmcomp.com.mx";
var sharedPreferences = null;
$(".p-participar").hide();
$(".p-calificar").hide();
$(".d-form-calificacion").hide();
$(document).on("deviceready", function () {
  sharedPreferences=window.plugins.SharedPreferences.getInstance();
  var key = "colonia";
  FCMPlugin.subscribeToTopic("ventas");
  FCMPlugin.onNotification(
    function (data) {
      if (data.wasTapped) {
        if (data.url) {
          window.open(data.url);
        }
      } else {
      }
    },
    function (msg) {
      console.log("onNotification callback successfully registered: " + msg);
    },
    function (err) {
      console.log("Error registering onNotification callback: " + err);
    }
  );
  sharedPreferences.get(
    key,
    function (value) {
      //console.log(value);
      $.ajax({
        url: server + "/WS/get_colonia",
        type: "POST",
        data: {colonia:value},
        dataType: "json",
        beforeSend: function () {},
        success: function (data) {
          if(!data.can_denuncia){
            $("#div_denuncia").hide();
          }
         
        },
      });
      $("#noti").hide();
      $("#noti_span").hide();
    },
    function (error) {
      window.location.href = "para-comenzar.html";
    }
  );
});
$(".num-directorio").click(function () {
  var $tempElement = $("<input>");
  $("body").append($tempElement);
  $tempElement.val($(this).find("span").text()).select();
  document.execCommand("Copy");
  $tempElement.remove();
  //alert("copiado");
  $(this).tooltip("show");

  setTimeout(
      function () {
          $('.num-directorio').tooltip('hide');
      }, 800);
});

$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip({
    trigger: "manual",
  });
  $("#sidebar").load("components/sidebar.html");
  $.ajax({
    url: server + "/WS/get_noticias",
    type: "POST",
    data: {"product": "",tipo:1},
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      //swal.close();
      ////console.log(data);
      //console.log(data);
      $("#div_noticias").empty().append(data);
    },
  });
  $.ajax({
    url: server + "/WS/get_notis",
    type: "POST",
    data: {"product": "",tipo:1},
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      //swal.close();
      ////console.log(data);
      //console.log(data);
      if(data==0){
        $(".noti-home").hide();
      }else{
        $(".noti-home").empty().append(data);
        $(".noti-home").show();
      }
      
    },
  });
});

/*  COPIAR NUMERO DIRECTORIO */

$(".num-directorio").click(function () {
  var $tempElement = $("<input>");
  $("body").append($tempElement);
  $tempElement.val($(this).find("span").text()).select();
  document.execCommand("Copy");
  $tempElement.remove();
  //alert("copiado");
  $(this).tooltip("show");

  setTimeout(function () {
    $(".num-directorio").tooltip("hide");
  }, 800);
});

/*  COPIAR NUMERO DIRECTORIO */

/*  SWITCH AJUSTES */

$(function () {
  $(".btn-switch-noti").change(function () {
    if ($(this).is(":checked")) {
      $(".dropdown-select.select-colonia").show();
      $(".dropdown-select.select-colonia").attr(
        "style",
        "pointer-events: all;"
      );
      $(".row-colonia").css("opacity", "1");
    } else {
      $(".dropdown-select.select-colonia").attr(
        "style",
        "pointer-events: none;"
      );
      $(".row-colonia").css("opacity", ".5");
    }
  });
});

/*  SWITCH AJUSTES */

/*  ITEMS LINKS SERVICIOS */

$(".btn-name-servicios").click(function () {
  if ($(this).hasClass("active-name-servicios")) {
    $(this).removeClass("active-name-servicios");
  } else {
    if ($(".btn-name-servicios").hasClass("active-name-servicios")) {
      $(".btn-name-servicios").removeClass("active-name-servicios");
    }
    $(this).addClass("active-name-servicios");
  }
});

$(".row-item-servicios").click(function () {
  $(this).find(".p-ver-servicios").toggleClass("active-ver-servicios");
});

/*  ITEMS LINKS SERVICIOS */

/*  VOLVER A INICIO */

$(".btn-volver-inicio").click(function () {
  window.location.href = "index.html";
});

/*  VOLVER A INICIO */

/*  BOTON PARTICIPAR */

$(".btn-participar").click(function () {
  $(this).hide();
  $(".p-participar").fadeIn();
});

/*  BOTON PARTICIPAR */

/*  BOTON CALIFICAR */

$(".btn-calificar").click(function () {
  $(".d-calificacion").hide();
  $(".d-form-calificacion").hide();
  $(".p-calificar").fadeIn();
});

/*  BOTON CALIFICAR */

/*  RADIO STAR CALIFICACION */

$("label").click(function () {
  $(this).parent().find("label").css({
    "background-color": "rgba(0,0,0,.15)",
  });
  $(this).css({
    "background-color": "#F3B71B",
  });
  $(this).nextAll().css({
    "background-color": "#F3B71B",
  });
});

$(".star label").click(function () {
  $(this).parent().find("label").css({
    color: "rgba(0,0,0,.15)",
  });
  $(this).css({
    color: "#F3B71B",
  });
  $(this).nextAll().css({
    color: "#F3B71B",
  });
  $(this).css({
    "background-color": "transparent",
  });
  $(this).nextAll().css({
    "background-color": "transparent",
  });
});

$(".radio-rate").click(function () {
  if ($(".d-form-calificacion").is(":visible")) {
  } else {
    $(".d-form-calificacion").fadeIn();
  }
});

/*  RADIO STAR CALIFICACION */

/*  BOTON SI NO

$(".btn-lg-si").click(function () {
    
    
    $(".btn-lg-no img").attr("src", "images/icons/icon-close-black.svg");
    $(".btn-lg-no").removeClass("active-no");
    $(this).addClass("active-si");
    $(this).find("img").attr("src", "images/icons/icon-check-si.svg");
    
});


$(".btn-lg-no").click(function () {
    $(".btn-lg-si img").attr("src", "images/icons/icon-check-si-black.svg");
    $(".btn-lg-si").removeClass("active-si");
    $(this).addClass("active-no");
    $(this).find("img").attr("src", "images/icons/icon-close.svg");
});


BOTON SI NO */

$(".row-si-no-1 .btn").click(function () {
  if ($(this).hasClass("btn-lg-si")) {
    $(".row-si-no-1 .btn-lg-no img").attr(
      "src",
      "images/icons/icon-close-black.svg"
    );
    $(".row-si-no-1 .btn-lg-no").removeClass("active-no");
    $(this).addClass("active-si");
    $(this).find("img").attr("src", "images/icons/icon-check-si.svg");
  } else if ($(this).hasClass("btn-lg-no")) {
    $(".row-si-no-1 .btn-lg-si img").attr(
      "src",
      "images/icons/icon-check-si-black.svg"
    );
    $(".row-si-no-1 .btn-lg-si").removeClass("active-si");
    $(this).addClass("active-no");
    $(this).find("img").attr("src", "images/icons/icon-close.svg");
  }
});

$(".row-si-no-2 .btn").click(function () {
  if ($(this).hasClass("btn-lg-si")) {
    $(".row-si-no-2 .btn-lg-no img").attr(
      "src",
      "images/icons/icon-close-black.svg"
    );
    $(".row-si-no-2 .btn-lg-no").removeClass("active-no");
    $(this).addClass("active-si");
    $(this).find("img").attr("src", "images/icons/icon-check-si.svg");
  } else if ($(this).hasClass("btn-lg-no")) {
    $(".row-si-no-2 .btn-lg-si img").attr(
      "src",
      "images/icons/icon-check-si-black.svg"
    );
    $(".row-si-no-2 .btn-lg-si").removeClass("active-si");
    $(this).addClass("active-no");
    $(this).find("img").attr("src", "images/icons/icon-close.svg");
  }
});

$(".row-si-no-3 .btn").click(function () {
  if ($(this).hasClass("btn-lg-si")) {
    $(".row-si-no-3 .btn-lg-no img").attr(
      "src",
      "images/icons/icon-close-black.svg"
    );
    $(".row-si-no-3 .btn-lg-no").removeClass("active-no");
    $(this).addClass("active-si");
    $(this).find("img").attr("src", "images/icons/icon-check-si.svg");
  } else if ($(this).hasClass("btn-lg-no")) {
    $(".row-si-no-3 .btn-lg-si img").attr(
      "src",
      "images/icons/icon-check-si-black.svg"
    );
    $(".row-si-no-3 .btn-lg-si").removeClass("active-si");
    $(this).addClass("active-no");
    $(this).find("img").attr("src", "images/icons/icon-close.svg");
  }
});
$('.btn-metodo-pago').click(function () {
  if ($(".btn-tarjeta").hasClass("active-metodo")) {
      $(".btn-tarjeta").removeClass("active-metodo");
  }else{
      $(this).addClass("active-metodo");
  }
  
  if ($(".btn-efectivo").hasClass("active-metodo")) {
      $(".btn-efectivo").removeClass("active-metodo");
  }else{
      $(this).addClass("active-metodo");
  }
});
