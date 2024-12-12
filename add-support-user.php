<?php
/*********************************************
		Support API (Add-User)
		By: Rehmat Ullah
		Email: rehmatullah@3-tree.com
		Date: 04/14/2017
**********************************************/

$url="http://support.3-tree.com/index.php?api=yes";
$api_key = "0C78A5EE61-E0F86FAD";

$language = $objDb->getField(0, "language");
if($language == 'tr')
	$language = "turkish";
if($language == 'de')
	$language = "german";
else	
	$language = 'english';

$params['Name']			=	$objDb->getField(0, "name");
$params['Email']		=	$objDb->getField(0, "email");
$params['Password']		=	$objDb->getField(0, "password");
$params['Username']             =       $objDb->getField(0, "username");
$params['user_type']            =       $objDb->getField(0, "user_type");
$params['TimeZone']		=	"";
$params['Ip']			=	"125.209.75.179";
$params['Language']		=	$language;
$params['Status']		=	$Status;
$params['notes']		=	"Test notes";

#**********************API REQUEST****************************
$user_array= array 
(
		"api"	=>	$api_key,
		"op"	=>  "account",
		"accounts"=>	array
		(
			"account" => array
			(	
				"name"			=>	$params['Name'],
				"username"		=>	$params['Username'],
				"email"			=>	$params['Email'],
				"password"		=>	$params['Password'],
				"timezone"		=>	$params['TimeZone'],
				"ip"			=>	$params['Ip'],
				"language"		=>	$params['Language'],
				"notes"			=>	$params['notes'],
                                "status"		=>	$params['Status'],
				"user_type"     =>  $params['user_type']
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