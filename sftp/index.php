<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$localFile='../temp/mgf/pos.csv';
$remoteFile='/TEST/Outgoing/Inspection/pos.csv';
$host = "125.209.75.188";
$port = 22;
$user = "mgfsourcing";
$pass = "mgf2016#";
 
$connection = ssh2_connect($host, $port);
ssh2_auth_password($connection, $user, $pass);
$sftp = ssh2_sftp($connection);
echo $sftp;exit; 
$stream = fopen("ssh2.sftp://$sftp$remoteFile", 'w');
$file = file_get_contents($localFile);
fwrite($stream, $file);
fclose($stream);
?> 