$(document).ready(function(){
	$(document).on("submit", "#delete_file", pass_id);
});

function pass_id(e){
	e.preventDefault();
	$.ajax({
		url: "home.blade.php",
		type: "GET",
		data: $(this).serialize(),
		success: function(data){
			url: "/file";
		}
	});
}