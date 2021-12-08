function asign(id,user){
  var select="";
  $.ajax({
    url: '/support/get_for_report_select',
    type: 'POST',
    dataType: 'json',
    success: function (data) {
      select=data.select_asignado;
      swal({
        title: 'Selecciona al asignado a resolver la queja.',
        text: "<select id='selector' class='form-control'>"+
        select+
        "</select>",
        showCancelButton: true,
        html:true,
      },function () {
       $.ajax({
        url: '/support/asign_quejas',
        type: 'POST',
        data:{'id':id,'asign_id':$("#selector").val(),'user_id':user},
        dataType: 'json',
        success: function (data) {
          location.reload();
        }
      });
     });
    }
  });
  
}
function resolve(id,user){
  $.ajax({
        url: '/support/resolve_quejas',
        type: 'POST',
        data:{'id':id,'user_id':user},
        dataType: 'json',
        success: function (data) {
          location.reload();
        }
      });

}