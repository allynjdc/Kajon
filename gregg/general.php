<?php
function logged_in_redirect(){
	if(logged_in()===true){
		header('Location: news.php');
		exit();
	}
}
function protectpage(){
	if(logged_in()===false){
		header('Location: restricted.php');
		exit();
	}
}
function admin_protect(){
	global $user_data;
	if(has_access($user_data['user_id'], 1)== false){
		header('Location: index.php');
		exit();
	}
}
function array_sanitize(&$item){
	$item = htmlentities(strip_tags(mysql_real_escape_string($item)));
}
function sanitize($data){
	return htmlentities(strip_tags(mysql_real_escape_string($data)));
}
function output_errors($errors){
	$output = array();
	foreach($errors as $error) {
		$output[] = '<li>'. $error . '</li>';
	}
	return "<span class='quoteAuthor textColor03'>" . '<ul>'  . implode('', $output) .  '</ul>' . "</span>";
}

?>