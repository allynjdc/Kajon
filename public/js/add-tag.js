$(document).ready(function(){
    $(document).on("input", "#add_tag_name", suggestTag);
    $(document).on("blur", "#add_tag_name", closeSuggestTag);
    $(document).on("click", "#add-tag-btn", addTag);
    $(document).on("click", "#remove-tag", removeTag);
    $(document).on("click", "#tag-suggest > li:not(.none)", changeTag);
});

function changeTag(){
	$("#tag-name").html($("#add_tag_name").val());
}

function suggestTag(){
	if ($("#add_tag_name").val()) {
		$("#tag-suggest").show();
	} else {
		$("#tag-suggest").hide();
	}
}

function closeSuggestTag(){
	$("#tag-suggest").hide();
}

function addTag(){
	if ($("#add_tag_name").val()) {
		$("#upload .add-tag-form").hide();
		$("#tag-name").show();
		$("#remove-tag").show();
		$("#tag-suggest").hide();

		$("#tag-name").html($("#add_tag_name").val());
	}
}

function removeTag(){
	$("#upload .add-tag-form").show();
	$("#tag-name").hide();	
	$("#remove-tag").hide();
}