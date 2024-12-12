<?php
/*********************************************
		Support API (Update-User)
		By: Rehmat Ullah
		Email: rehmatullah@3-tree.com
		Date: 04/14/2017
**********************************************/

$url="http://support.3-tree.com/index.php?api=yes";
$api_key = "0C78A5EE61-E0F86FAD";

$Email					=  ($Email != ""?$Email:$sEmail);
$params['Email']		=	$Email;
$params['Password']		=	getDbVAlue("password", "tbl_users", "email Like '$Email'"); 
$params['Status']		=	getDbVAlue("status", "tbl_users", "email Like '$Email'");; //'A'

#**********************API REQUEST****************************
$user_array= array 
(
		"api"	=>	$api_key,
		"op"	=>  "update-user",
		"accounts"=>	array
		(
			"account" => array
			(	
				"email"			=>	$params['Email'],
				"password"		=>	$params['Password'],
				"status"		=>	$params['Status']
			)
		)
);


#pre-checks. CURLS and JSON extensions which are recquired for this example to work
function_exists('curl_version') or die('CURL support required');
function_exists('json_encode') or die('JSON support required');

#JSON ENCODE DATA 	
$user_string=json_encode($user_array);	

#set timeout
set_time_limit(30);

#CURL's Magic
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $user_string);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result=curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$res = json_decode($result,true);

/* DEBUG ONLY REMOVE FOR PRODUCTION */
//print_r($res);


?>