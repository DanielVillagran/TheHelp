var states = "";

$(document).ready(function() {
    if ($("#id").val() > 0) {
        get_info_clients($("#id").val());
        console.log(permissions);
    }
  
});
function cambio(module_id){
        (permissions.find((permission) => permission.module_id === module_id).respuesta==0)?permissions.find((permission) => permission.module_id === module_id).respuesta=1:permissions.find((permission) => permission.module_id === module_id).respuesta=0;
}
function get_info_clients(id) {
    console.log(id);
    $.ajax({
        type: "post",
        url: "/Clients/get_info_users",
        data: {
            id: id
        },
        dataType: "json",
        beforeSend: function() {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function(data) {
            swal.close();
            $("#pass").removeAttr("required"); 
            for (var key in data) {
                if(key!='user_passwd'){
                $('[name="users[' + key + ']"]').val($.trim(data[key]));
                }
            }
            
           
        }
    });
}

function save_client() {
    event.preventDefault();
    var data = $("#client_info").serializeArray();
    if($("#pass").val()==""){
    
    }else{
    data.find(item => item.name === 'users[user_passwd]').value =  Sha256.hash($("#pass").val());
    } 
    data.push({ name:'permisos', value:  JSON.stringify(permissions) });
    
    $.ajax({
        type: "post",
        url: "/clients/save_info",
        data: data,
        dataType: "json",
        beforeSend: function() {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function(data) {
            
            swal.close();
            location.href="/clients";
        }
    });
}

function format_date(date) {
    var formated_date = "";
    var array_date = date.split('T')[0].split('-');
    var array_hour = date.split('T')[1].split(':');
    formated_date += array_date[2] + "-" + array_date[1] + "-" + array_date[0] + " " + array_hour[0] + ":" + array_hour[1] + ":00";
    return formated_date;
}