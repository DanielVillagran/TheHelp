 // tree documentacion

 $(document).ready(function () {
    readdir('documentation','documentation','test');
 })

function readdir(path,name,type){
    var data_info = {path : path,name : name,type : type};
    $.ajax({
        url: "/support/readdir",
        type: "post",
        data: data_info,
        result: "JSON",
        success: function(data_json){
            if(data_json.file_exists){
                $("#"+data_json.name+"_child").html('');
                $("#doc_all").html('');
                jQuery.each(data_json.files, function(){
                    if (this.ext == 'directory') {
                        $("#"+data_json.name+"_child").append('<li class="'+this.name+'"><label class="tree-toggler nav-header element_path" onclick="readdir(\''+this.path+'\',\''+this.name+'\',\'test\')"><i class="'+this.image+'"></i>'+this.name+'</label><ul id="'+this.name+'_child" class="col-sm-12 col-md-12" style="display:block;list-style:none;"></ul></li>');
                    }else{
                        $("#"+data_json.name+"_child").append('<div class="col-sm-6 col-md-6"><div class="block"><a target="_blank" href="/assets/'+this.path+'"><img src="/assets/images/'+this.image+'">'+this.name+'</a></div></div>');
                    }
                });
            }else{
                $("."+data_json.name).remove();
                $("#Documentation_child").html('');
            }
        }
    }).fail(function(xhr){
                if( xhr.status >= 400 || xhr.status == 0)
                swal(xhr.status == 0 ? "No hay conexión a internet." : xhr.statusText, "", "warning")
              });
}