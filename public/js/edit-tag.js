$(document).ready(function(){
    $(document).on("input", "#edit_tag_name", suggestTag_edit);
    $(document).on("blur", "#edit_tag_name", closeSuggestTag_edit);
    $(document).on("click", "#edit-tag-btn", editTag);
    $(document).on("click", "#edit-remove-tag", removeTag_edit);
});

function suggestTag_edit(){
	$("#edit-tag-suggest").show();
}

function closeSuggestTag_edit(){
	$("#edit-tag-suggest").hide();
}

function editTag(){
	if ($("#edit_tag_name").val()) {
		$("#update .add-tag-form").hide();
		$("#edited-tag-name").show();
		$("#edit-remove-tag").show();
		$("#edit-tag-suggest").hide();

		$("#edited-tag-name").html($("#edit_tag_name").val());
	}
}

function removeTag_edit(){
	$("#update .add-tag-form").show();
	$("#edited-tag-name").hide();	
	$("#edit-remove-tag").hide();
}