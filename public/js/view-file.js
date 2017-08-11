$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN' : $('meta[name="_token"]').attr('content')
    }
});

$(document).ready(function(){
    $(document).on("click", ".drawer-item", viewFile);
    $(document).on("click", "#delete-button", deleteFile);
    $(document).on("click", "#update-button", updateFile);
    $(document).off("submit", "#update_form").on("submit", "#update_form", updateToController);
});

function updateToController(e){
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    $("#update").modal("close");
    $("#view-file").modal("close");
    $.ajax({
        url: "/file/"+$("#view-file").attr("data-file-id"),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data){
            console.log(data);
            var msg = data[0].split("|");
            var file = data[1];
            $("#upload_message > .modal-content > h4").html(msg[0]);
            $("#upload_message > .modal-content > p").html(msg[1]);
            $("#upload_message").modal("open");
            if(window.location.pathname == "/home"){
                $("#all_files").html(data[2]);
                if(data[3] > 9){
                    addSeeMoreButton();
                }
                getTags();
            }
            if(window.location.pathname == "/home/allfiles"){
                getMunicipalTags();
            }
            $("#your_files").html(data[1]);
            // editInputClear();
            resetSort();
            setIconsAndColorsItems();
        },
        error: function(data){
            console.log(data.responseText);
        }
    });
}

function addYourFiles(){
    if(!$("#your_files").length){
        $(".none-text").remove();
        $("<small class='division-header'>Recent Files</small>").insertAfter("#your_header + hr");
        $("<ul class='file-drawer' id='your_files'></ul>").insertAfter("#your_header + hr + small");
    }
    if(!$("#all_files").length){
        $("<hr />").insertAfter("#your_files");
        $("<small class='division-header'>Your Files</small>").insertAfter("#your_files + hr");
        $("<div id='load-data'><ul class='file-drawer' id='all_files'></ul></div>").insertAfter("#your_files + hr + small");
    }
}

function inputClear(){
    $("#description").val("");
    $("input[name='filename']").val("");
    console.log($("input[name='filename']").val());
    $("#upload .file-input-details .name").html("Select a file");
    $("input[name='add_tag_name']").val("");
    $("input[name='public']").prop('checked', false);
    $("#upload .input-file > span").attr( "class", "waves-effect waves-light blue lighten-1");
    $("#upload .input-file > span > i").html("note_add");
}

function resetSort(){
    $("#sort-dropdown").html('Sorted by Date<i class="material-icons right">arrow_drop_down</i>');
    $("#date-sort").parent().addClass("active");
    $("#name-sort").parent().removeClass("active");
}

function getMunicipalTags(){
    $.ajax({
        method: 'GET',
        url: '/locationTags',
        success: function(data){
            console.log(data);
            var tags = "";
            $.each(data, function(key, value){
                tags += '<li class="tag-item waves-effect"><a href="/tag/'+value["id"]+'"><i class="material-icons red darken-1 white-text left">local_offer</i>'+value["name"]+'</a></li>';
            });
            $(".tag-drawer").html(tags);
        }, error: function(data){
            console.log(data.responseText);
        }
    });
}

function getTags(){
    console.log("HERE AT TAGS");
    $.ajax({
        method: 'GET',
        url: '/tags',
        success: function(data){
            var tags = "";
            $.each(data, function(key, value){
                tags += '<li class="tag-item waves-effect"><a href="/tag/'+value["id"]+'"><i class="material-icons red darken-1 white-text left">local_offer</i>'+value["name"]+'</a></li>';
            });
            $(".tag-drawer").html(tags);
            console.log(data);
        },
        error: function(data){
            console.log(data.responseText);
        }
    });
}

function addSeeMoreButton(){
    $("#all_files").append('<li class="show-more" id="remove-row"><a id="btn-more" data-updated_at="" data-id="" class="waves-effect waves-light"><span class="plus-icon">+</span> See more</a></li>');
    $("#all_files > li:not(#remove-row):not(.none-text)").addClass("allfile-item");
    console.log("updated at: "+$("#all_files > li:not(#remove-row)").last().data('updated_at'));
    console.log("file id: "+$("#all_files > li:not(#remove-row)").last().data('file-id'));
    $("#btn-more").attr("data-updated_at", $("#all_files > li:not(#remove-row)").last().data('updated_at'));
    $("#btn-more").attr("data-id", $("#all_files > li:not(#remove-row)").last().data('file-id'));
}

function viewFile(e){
    e.preventDefault();
    var icon = $(this).find(">:first-child").prop('outerHTML');
    $("#view-file .modal-content > :first-child").replaceWith(icon);
    $("#view-file .modal-content > :first-child").addClass("file-icon large");
    $("#view-file .description").html($(this).children(".description").html());
    $("#view-file .file-name").html($(this).find(".title").html());
    $("#view-file .date").html($(this).find(".date").html());
    if($(this).attr("data-owned-by-user") != 1){
        $(".crud-file").css("visibility", "hidden");
    }else{
        $(".crud-file").css("visibility", "visible");
        $("#view-file").attr("data-file-id", $(this).attr("data-file-id"));
    }
    if($(this).children(".tag").html()){
        $("#view-file").attr("data-tag", $(this).children(".tag").html());
    }
    $("#download-button").attr("href", "/file/"+$(this).attr("data-file-id")+"/download");
    $("#view-file").attr("data-public", $(this).attr("data-public"));
    $("#view-file").modal('open');
    // console.log($(this).attr("data-file-id"));
    // console.log($("#download-button").attr("href"));
}

function deleteFile(e){
    e.preventDefault();
    $("#delete-conf > .modal-content > h5").html("Are you sure you want to delete "+$("#view-file .file-name").html()+"?");
    $("#delete-conf form").attr("action", "/file/"+$("#view-file").attr("data-file-id"));
    $("#delete-conf").modal('open');
}

function updateFile(e){
    e.preventDefault();
    var string = $("#view-file > .modal-content > .description").html()+"";
    $("#update > .modal-content h2").html("Upload a revision for "+$("#view-file .file-name").html());
    console.log("Type Of: "+$.type($("#view-file").attr("data-public")));
    console.log("Value: "+$("#view-file").attr("data-public"));
    if(($("#view-file").attr("data-public") == "1")){
        ($("#edit_public").prop("checked", "true"));
    }else{
        ($("#edit_public").prop("checked", false));
    }
    $("#edit_tag_name").val($("#view-file").attr("data-tag"));
    $("#edit_description").val($.trim(string));
    $("#update").modal('open');
    $("label[for='edit_description']").addClass('active');
}

Materialize.updateTextFields = function() {
    var input_selector = 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea';
    $(input_selector).each(function(index, element) {
        var $this = $(this);
        if ($(element).val().length > 0 || $(element).is(':focus') || element.autofocus || $this.attr('placeholder') !== undefined) {
            $this.siblings('label').addClass('active');
        } else if ($(element)[0].validity) {
            $this.siblings('label').toggleClass('active', $(element)[0].validity.badInput === true);
        } else {
            $this.siblings('label').removeClass('active');
        }
    });
};

// function downloadFile(e){
//     e.preventDefault();
//     var id = $("#view-file").attr("data-file-id");
//     $("#view-file").modal("close");
//     $.ajax({
//         url: "/file/"+id+"/download",
//         type: "GET",
//         data: {_token: $('meta[name="_token"]').attr('content')},
//         processData: false,
//         contentType: false,
//         success: function(data){
//             if(data == "ERROR"){
//                 $("#upload_message > .modal-content > h4").html("Error!");
//                 $("#upload_message > .modal-content > p").html("Something went wrong! You might have tried to download a non-existent file.");
//             }else{
//                 $("#upload_message > .modal-content > h4").html("Success!");
//                 $("#upload_message > .modal-content > p").html("Please check your downloads folder to see if the file has been downloaded correctly by your browser.");
//             }
//             $("#upload_message").modal("open");
//         }
//     });
// }

// function downloadFile(e){
//     e.preventDefault();
//     var id = $("#view-file").attr("data-file-id");
//     $("#view-file").modal("close");
//     $.ajax({
//         url: "/file/"+id+"/download",
//         type: "GET",
//         data: {_token: $('meta[name="_token"]').attr('content')},
//         processData: false,
//         contentType: false,
//         success: function(data){
//             if(data == "ERROR"){
//                 $("#upload_message > .modal-content > h4").html("Error!");
//                 $("#upload_message > .modal-content > p").html("Something went wrong! You might have tried to download a non-existent file.");
//             }else{
//                 $("#upload_message > .modal-content > h4").html("Success!");
//                 $("#upload_message > .modal-content > p").html("Please check your downloads folder to see if the file has been downloaded correctly by your browser.");
//             }
//             $("#upload_message").modal("open");
//         }
//     });
// }