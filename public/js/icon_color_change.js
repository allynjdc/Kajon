$(document).ready(function(){
	$("input[type='file']").change(function(){
        if(fileSizeCheck(this.files[0].size)){
            $(this).parent().next().find(".name").html($(this).val().substring($(this).val().lastIndexOf("\\") + 1, $(this).val().length));
            $(this).parent().next().find(".name").css("color", "#555");
            var suffix = $(this).val().split(".");
            var color = "waves-effect waves-light " + selectColor(suffix[suffix.length-1]); 

            $(this).prev().attr( "class", color );
            $(this).prev().children().first().html(selectIcon(suffix[suffix.length-1]));

            if ($(this).val()) {
                $(this).parent().next().find(".name").show();
                $(this).parent().next().find("textarea").show();
            } else{
                $(this).parent().next().find(".name").hide();
                $(this).parent().next().find("textarea").hide();
            }
            $(this).parent().parent().next().next().find(".input-field").find(".submit").attr("disabled", false);
        }else{
            $(this).parent().next().find(".name").html("File limit is 25MB!");
            $(this).parent().next().find(".name").css("color", "red");
            $(this).parent().parent().next().next().find(".input-field").find(".submit").attr("disabled", true);
        }
    });
	
	setIconsAndColorsItems();
});

function fileSizeCheck(fileSize){
    inputSize = fileSize/1024;
    byteSize = "KB";
    if(inputSize / 1024 > 1){
        if(((inputSize / 1024) / 1024) > 1){
            inputSize = (Math.round(((inputSize / 1024) / 1024) * 100) / 100);
            byteSize = "GB";
        }else{
            inputSize = (Math.round((inputSize / 1024) * 100) / 100);
            byteSize = "MB";
        }
    }else{
        inputSize = (Math.round(inputSize * 100) / 100);
    }
    if(byteSize == "GB"){
        message = "File is too big";
        return false;
    }else if(byteSize == "MB"){
        if(inputSize > 25){
            message = "File is too big";
            return false;
        }else{
            message = "File can be accepted";
            return true;
        }
    }
    message = "File can be accepted";
    console.log(inputSize+byteSize+" "+message);
    return true;
}

function setIconsAndColorsItems(){
	$(".drawer-item").html(function(){
        var suffix = $(this).children().eq(1).children().eq(0).text().split(".");

        var icon = $(this).children().eq(0);

        icon.html(selectIcon(suffix[suffix.length-1]));

        icon.addClass(selectColor(suffix[suffix.length-1]));
    });
}

function selectIcon(suffix){
	if      (suffix == "docx") {return "description";}
	else if (suffix == "doc" ) {return "description";}
	else if (suffix == "xls" ) {return "grid_on";}
	else if (suffix == "xlsx") {return "grid_on";}
	else if (suffix == "ods" ) {return "grid_on";}
	else if (suffix == "odt" ) {return "grid_on";}
	else if (suffix == "pdf" ) {return "description";}
	else if (suffix == "jpeg") {return "photo";}
	else if (suffix == "jpg" ) {return "photo";}
	else if (suffix == "png" ) {return "photo";}
	else if (suffix == "ppt" ) {return "picture_in_picture";}
	else if (suffix == "pptx") {return "picture_in_picture";}
	else {return "note_add";}
}

function selectColor(suffix){
	if      (suffix == "docx") {return "blue lighten-1";}
    else if (suffix == "doc" ) {return "blue lighten-1";}
    else if (suffix == "xls" ) {return "light-green lighten-1";}
    else if (suffix == "xlsx") {return "light-green lighten-1";}
    else if (suffix == "ods" ) {return "green lighten-1";}
    else if (suffix == "odt" ) {return "green lighten-1";}
    else if (suffix == "pdf" ) {return "pink accent-3";}
    else if (suffix == "jpeg") {return "indigo lighten-1";}
    else if (suffix == "jpg" ) {return "indigo lighten-1";}
    else if (suffix == "png" ) {return "purple lighten-1";}
    else if (suffix == "ppt" ) {return "amber lighten-1";}
    else if (suffix == "pptx")  {return "amber lighten-1";}
    else {return "grey lighten-1";}
}