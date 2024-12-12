<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**  Project Manager:                                                                         **
	**                                                                                           **
	**      Name  :  Muhammad Tahir Shahzad                                                      **
	**      Email :  mtahirshahzad@hotmail.com                                                   **
	**      Phone :  +92 333 456 0482                                                            **
	**      URL   :  http://www.mtshahzad.com                                                    **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@list($sInOut, $sTime) = @explode(" ", $sSms);

	$sInOut = strtoupper($sInOut);

	if (($sInOut != "IN" && $sInOut != "OUT") || @strpos($sTime, ":") === FALSE)
		$sBody = "Inavlid SMS Attendance Entry";

	@list($sTimeHr, $sTimeMin) = @explode(":", $sTime);

	$sTimeHr  = str_pad($sTimeHr, 2, '0', STR_PAD_LEFT);
	$sTimeMin = str_pad($sTimeMin, 2, '0', STR_PAD_LEFT);

	$sInOut      = $sInOut{0};
	$iUserId     = 0;
	$sUserMobile = $sSender;

	$sUserMobile = str_replace("+920", "", $sUserMobile);
	$sUserMobile = str_replace("00920", "", $sUserMobile);
	$sUserMobile = str_replace("+92", "", $sUserMobile);
	$sUserMobile = str_replace("0092", "", $sUserMobile);
	$sUserMobile = str_replace("+88", "", $sUserMobile);
	$sUserMobile = str_replace("0088", "", $sUserMobile);

	$sSQL = "SELECT id FROM tbl_users WHERE mobile LIKE '%$sUserMobile'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		$sBody = "Inavlid Mobile Number for Attendance Entry";

	else if ($objDb->getCount( ) > 1)
		$sBody = "Request failed. This Mobile Number is used by multiple users.";

	else
		$iUserId = $objDb->getField(0, 0);


	if ($iUserId != 0)
	{
		if (@in_array($iUserId, array(1,2,3,62,458,343)))
		{
			$sTimeIn  = "00:00:00";
			$sTimeOut = "00:00:00";

			if ($sInOut == "IN")
			{
				$sField  = "time_in";
				$sTimeIn = "{$sTimeHr}:{$sTimeMin}:00";
			}

			else
			{
				$sField   = "time_out";
				$sTimeOut = "{$sTimeHr}:{$sTimeMin}:00";
			}


			if (getDbValue("COUNT(1)", "tbl_attendance", "`date`=CURDATE( ) AND user_id='$iUserId'") == 1)
				$sSQL = "UPDATE tbl_attendance SET {$sField}='{$sTimeHr}:{$sTimeMin}:00' WHERE `date`=CURDATE( ) AND user_id='$iUserId'";

			else
				$sSQL = "INSERT INTO tbl_attendance (`date`, user_id, time_in, time_out, remarks) VALUES (CURDATE( ), '$iUserId', '$sTimeIn', '$sTimeOut', '')";

			if ($objDb->execute($sSQL) == true)
				$sBody = "Your Attendance entry has been posted successfully.";

			else
				$sBody = "An ERROR occured while posting your Attendance Entry.";
		}

		else
		{
			$iId = getNextId("tbl_sms_attendance");

			$sSQL = "INSERT INTO tbl_sms_attendance (id, `date`, user_id, in_out, `time`, status, remarks, date_time) VALUES ('$iId', CURDATE( ), '$iUserId', '$sInOut', '$sTimeHr:$sTimeMin:00', 'P', '', NOW( ))";

			if ($objDb->execute($sSQL) == true)
				$sBody = "Your Attendance entry has been posted successfully.";

			else
				$sBody = "An ERROR occured while posting your Attendance Entry.";
		}
	}


	$sStatus = $objSms->send($sSender, "", $sBody, "*** SMS Attendance ***", true);

	if ($bDebug == true)
	{
		print ("<b>*** SMS Attendance ***</b><br />");
		print ("SMS Contents: ".$sSms."<br />");
		print ("SMS Results: ".$sBody."<br />");
		print ("Mobile: ".$sSender."<br />");
		print ("Mail Status: ".$sStatus."<br />");
	}
?>