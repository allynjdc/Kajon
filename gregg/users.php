<?php
function change_profile_image($user_id, $file_temp, $file_extn){
	$file_path = 'images/profile/' . substr(md5(time()), 0, 10) . '.' . $file_extn;
	move_uploaded_file($file_temp, $file_path);
	mysql_query("UPDATE `users` SET `profile` = '" . mysql_real_escape_string($file_path) . "' WHERE `user_id` = " . (int)$user_id);
}
function downloads_data($dNo){
	$data = array();
	$dNo = (int)$dNo;

	$func_num_args = func_num_args();
	$func_get_args = func_get_args();

	if($func_num_args >1){
		unset($func_get_args[0]);
		//$fields = '`' . implode('`, `', $func_get_args) . '`';
		$data = mysql_fetch_assoc(mysql_query("SELECT * FROM `downloads` WHERE `dNo` = $dNo"));

		return $data;
	}
}
function edit_downloads($update_data){
	global $dNo;
	$update = array();
	array_walk($update_data, 'array_sanitize');
	
	foreach($update_data as $field=>$data){
		$update[] = '`' . $field . '` = \'' . $data . '\'';
	}
	mysql_query("UPDATE `downloads` SET " . implode(', ', $update) ." WHERE `dNo` = $dNo");
}
function announcement_data($aNo){
	$data = array();
	$aNo = (int)$aNo;

	$func_num_args = func_num_args();
	$func_get_args = func_get_args();

	if($func_num_args >1){
		unset($func_get_args[0]);
		//$fields = '`' . implode('`, `', $func_get_args) . '`';
		$data = mysql_fetch_assoc(mysql_query("SELECT * FROM `announcement` WHERE `aNo` = $aNo"));

		return $data;
	}
}
function edit_announcements($update_data){
	global $aNo;
	$update = array();
	array_walk($update_data, 'array_sanitize');
	
	foreach($update_data as $field=>$data){
		$update[] = '`' . $field . '` = \'' . $data . '\'';
	}
	mysql_query("UPDATE `announcement` SET " . implode(', ', $update) ." WHERE `aNo` = $aNo");
}
function addpub_to_pending($user_id, $file_temp, $file_extn, $tags){
	$file_path = 'images/publicity/' . substr(md5(time()), 0, 10) . '.' . $file_extn;
	move_uploaded_file($file_temp, $file_path);
	date_default_timezone_set('Asia/Singapore');
	$date = date('Y-m-d H:i:s');
	if($_SESSION['usertype']==1){
		$imagepath = mysql_real_escape_string($file_path);
		mysql_query("INSERT INTO publicities (user_id, submitted_on, status, approved_on, tags, filepath) VALUES ('$user_id','$date','1','$date','$tags','$imagepath')");
	}
	else{
		$imagepath = mysql_real_escape_string($file_path);
		mysql_query("INSERT INTO publicities (user_id, submitted_on, status, tags, filepath) VALUES ('$user_id','$date','0','$tags','$imagepath')");	
	}
}

function has_access($user_id, $usertype){
	$user_id = (int)$user_id;
	$usertype = (int)$usertype;
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `user_id` = $user_id AND `usertype` = $usertype"), 0)==1) ? true: false;
}

function update_user($update_data){
	global $session_user_id;
	$update = array();
	array_walk($update_data, 'array_sanitize');
	
	foreach($update_data as $field=>$data){
		$update[] = '`' . $field . '` = \'' . $data . '\'';
	}
	mysql_query("UPDATE `users` SET " . implode(', ', $update) ." WHERE `user_id` = $session_user_id");
	//mysql_query("INSERT INTO `users` ($fields) VALUES ($data)");
}
function change_password($user_id, $password){
	$user_id = (int)$user_id;
	$password = md5($password);

	mysql_query("UPDATE `users` SET `password` = '$password' WHERE `user_id` = $user_id");	
}
function register_user($register_data){
	array_walk($register_data, 'array_sanitize');
	$register_data['password'] = md5($register_data['password']);
	
	$fields = '`' . implode('`, `', array_keys($register_data)) . '`';
	$data = '\'' . implode('\', \'', $register_data) . '\'';
	
	mysql_query("INSERT INTO `users` ($fields) VALUES ($data)");
}
function user_data($user_id){
	$data = array();
	$user_id = (int)$user_id;

	$func_num_args = func_num_args();
	$func_get_args = func_get_args();

	if($func_num_args >1){
		unset($func_get_args[0]);
		//$fields = '`' . implode('`, `', $func_get_args) . '`';
		$data = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `user_id` = $user_id"));

		return $data;
	}
}
function logged_in(){
		return (isset($_SESSION['user_id'])) ? true : false;
}
function user_exists($username){
	$username = sanitize($username);
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username'"), 0)==1) ? true : false;
}
function email_exists($email){
	$email = sanitize($email);
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email'"), 0)==1) ? true : false;
}
function user_active($username){
	$username = sanitize($username);
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `active`='1'"), 0)==1) ? true : false;
}
function user_id_from_username($username){
	$username = sanitize($username);
	return mysql_result(mysql_query("SELECT `user_id` FROM `users` WHERE `username` = '$username'"), 0, 'user_id');
}
function login($username, $password){
	$user_id =  user_id_from_username($username);

	$username = sanitize($username);
	$password = md5($password);

	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `password` = '$password'"), 0) == 1) ? user_id : false;
}
?>