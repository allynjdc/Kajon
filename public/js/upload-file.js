$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN' : $('input[name="_token"]').val()
    }
});
$(document).ready(function(){
    
    // upload file AJAX
    $("#upload_form").off('submit').on('submit', function(e){
        e.preventDefault();
        var filename = $("#filename").val();
        filename = filename.replace("C:\\fakepath\\", "");
        var description = $("#description").val();
        var token = $('input[name="_token"]').val();
        $.ajax({
            method: 'POST',
            url: "{{ route('file.store') }}",
            data: new FormData($("#upload_form")[0]),
            processData: false,
            contentType: false,
            success:function(data){
                $("input[name='description']").val("");
                $("input[name='filename']").val("");
                $("#upload").modal('close');
                var msg = data[0].split('|');
                $("#upload_message > .modal-content > h4").html(msg[0]);
                $("#upload_message > .modal-content > p").html(msg[1]);
                $("#upload_message").modal('open');
                // console.log(data[1]["data"][0]);
                $(".drawer-header").html(
                    '<img src="{{asset(Auth::user()->profile_picture)}}" alt="user-img" />'+
                    '<h1>{{ Auth::user()->name }}\'s drawer</h1>'+
                    '<ul class="top-options">'+
                        '<li><a class="btn white waves-effect" href="\home\allfiles">Municipal Files</a></li>'+
                        '<li class="right"><a class="btn white waves-effect waves-light dropdown-button" id="sort-dropdown" href="#" data-activates="sort">Sorted by <i class="material-icons right">arrow_drop_down</i></a></li>'+
                        '<li><a class="btn light-green waves-effect upload-btn" href="#upload"><i class="material-icons left">note_add</i> Upload file</a></li>'+
                    '</ul>'
                );
                
                var own_files = "";
                console.log(data[1]["data"]);
                $.each(data[1]["data"], function(key,value){
                    var updated_at = value["updated_at"];
                    own_files += '<li class="drawer-item waves-effect" data-target="view-file" >'+
                                    '<i class="material-icons blue lighten-1">description</i>'+
                                    '<div>'+
                                        '<p class="title">'+value["filename"]+'</p>'+
                                        '<span class="date"> '+
                                            updated_at+
                                        ' </span>'+
                                    '</div>'+
                                    '<p class="description">'+
                                        value["description"]+
                                    '</p>'+
                                '</li>';
                });
                $("#your_files").html(own_files);
            }
        });
    });
});