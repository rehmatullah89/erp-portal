
<?php

/* Set the correct include path to 'phpseclib'. Note that you will need 
   to change the path below depending on where you save the 'phpseclib' lib.
   The following is valid when the 'phpseclib' library is in the same 
   directory as the current file.
 */
set_include_path(get_include_path() . PATH_SEPARATOR . './phpseclib0.3.0');

include('phpseclib0.3.0/Net/SFTP.php');

/* Change the following directory path to your specification */
$local_directory = '../temp/mgf/';
$remote_directory = '/TEST/Outgoing/Inspection/';

/* Add the correct FTP credentials below */
$sftp = new Net_SFTP('125.209.75.188');
if (!$sftp->login('mgfsourcing', 'mgf2016#')) 
{
    exit('Login Failed');
} 


/* We save all the filenames in the following array */
$files_to_upload = array();

/* Open the local directory form where you want to upload the files */
if ($handle = opendir($local_directory)) 
{
    /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) 
    {
        if ($file != "." && $file != "..") 
        {
            $files_to_upload[] = $file;
        }
    }

    closedir($handle);
}

if(!empty($files_to_upload))
{
    /* Now upload all the files to the remote server */
    foreach($files_to_upload as $file)
    {
          /* Upload the local file to the remote server 
             put('remote file', 'local file');
           */
          $success = $sftp->put($remote_directory . $file, 
                                $local_directory . $file, 
                                 NET_SFTP_LOCAL_FILE);
    }
}


?>