jQuery(document).ready(function ($) {
    $( document ).ajaxError(function(event, jqxhr, settings, thrownError) {
      if( jqxhr.status >= 400 || jqxhr.status == 0)
        swal(jqxhr.status == 0 ? "No hay conexión a internet." : jqxhr.statusText, "", "warning")
    });
    $(".tagManager").tagsManager();
    var id_user = $.trim($('#id_user').val());
    load_my_tags();
    
    function load_my_tags() {
        $.ajax({
            url: '/user/tags',
            type: 'POST',
            data: {'id_contact': id_user},
            dataType: 'json',
            success: function (data) {
                if (data.result) {
                    var str_tags = '';
                    var tags_styles = new Array( 'error','warning', 'info', 'success', 'inverse');
                    var contador = -1;
                    $.each(data.info, function (idx, row) {
                        if (++contador === 6) {
                            contador = 0;
                        }
                        str_tags += '<span class="tm-tag tm-tag-' + tags_styles[contador] + '" id="tagnumber_' + row.id + '"> <span> ' + row.name + '</span><a id="tagnumber_' + row.id + '" class="tm-tag-remove" onClick="delete_tag(' + row.id + ')">x</a></span>'
                        //str_tags += '<span id="tagnumber_' + row.id + '" class="tm-tag tm-tag-' + tags_styles[contador] + '" onClick="delete_tag(' + row.id + ')">' + row.name + '</span>';
                        //str_tags += '<a href="#" class="tm-tag-remove" id="'+ row.id +'" tagidtoremove="'+ row.id +'">x</a>'
                    });
                    if (str_tags !== '') {
                        $('#tags_tags_1').append(str_tags);
                    }
                } else {
                }
            }
        });
    }

});
/*ELIMINA UNA ETIQUETA*/
function delete_tag(id) {
    id = id || 0;
    if (id > 0) {
        $.ajax({
            url: '/user/delete_tag',
            type: 'POST',
            data: {id_tag: id},
            dataType: 'json',
            beforeSend: function () {
            },
            success: function (data) {
                if (data.result == true) {
                    $('#tagnumber_' + id).remove();
                } else {
                    return false;
                }
            }
        });
    }
}
/*
jQuery(".tagManager").on('tm:splicing', function (e, tag) {
    alert(tag + " is almost removed!");
    //var id_user = $.trim($('#id_user').val());
    //alert(tag);

    $.ajax({
        url: '/user/delete_tags',
        type: 'POST',
        data: {name: tag},
        dataType: 'json',
        success: function (data) {
            if (data.result) {
                return true;
            } else {
                return false;
            }
        }
    });
});*/

/*GUARDA UNA ETIQUETA*/
jQuery(".tagManager").on('tm:pushing', function (e, tag) {
    var id_user = $.trim($('#id_user').val());
    //alert(tag);

    $.ajax({
        url: '/user/save_tags',
        type: 'POST',
        data: {name: tag, id_contact: id_user},
        dataType: 'json',
        success: function (data) {
            if (data.result) {
                
                return true;
            } else {
                return false;
            }
        }
    });

});


