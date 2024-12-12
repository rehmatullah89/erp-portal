<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2008-15 (C) Triple Tree                                                        **
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

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$sAttendance = @unserialize($_POST["Attendance"]);
	$sVisits     = @unserialize($_POST["Visits"]);

/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Attendance Sync Alert - IN";
	$objEmail->Body    = @json_encode($sAttendance)."<br /><br />\n\n".@json_encode($sVisits);

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/


	$bFlag = $objDb->execute("BEGIN");

	foreach ($sAttendance as $iUserId => $sDetails)
	{
		$sDate     = $sDetails[0];
		$sTimeIn   = $sDetails[1];
		$sTimeOut  = $sDetails[2];
		$sRemarks  = $sDetails[3];
		$sDateTime = $sDetails[4];


		$sSQL = "SELECT * FROM tbl_attendance WHERE `date`='$sDate' AND user_id='$iUserId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$sSQL  = "INSERT INTO tbl_attendance (`date`, user_id, time_in, time_out, remarks, date_time) VALUES ('$sDate', '$iUserId', '$sTimeIn', '$sTimeOut', '$sRemarks', '$sDateTime')";
			$bFlag = $objDb->execute($sSQL);
		}

		else if ($objDb->getCount( ) == 1)
		{
			$sSQL = "UPDATE tbl_attendance SET date_time='$sDateTime' ";

			if ($sTimeIn != $objDb->getField(0, 'time_in'))
				$sSQL .= ", time_in='$sTimeIn' ";

			if ($sTimeOut != $objDb->getField(0, 'time_out'))
				$sSQL .= ", time_out='$sTimeOut' ";

			if ($sRemarks != $objDb->getField(0, 'remarks'))
				$sSQL .= ", remarks='$sRemarks' ";

			$sSQL .= " WHERE `date`='$sDate' AND user_id='$iUserId'";
			$bFlag = $objDb->execute($sSQL);
		}

		if ($bFlag == false)
			break;
	}


	if ($bFlag == true)
	{
		foreach ($sVisits as $iId => $sDetails)
		{
			$iUserId    = $sDetails[0];
			$sDate      = $sDetails[1];
			$sTimeOut   = $sDetails[2];
			$sTimeIn    = $sDetails[3];
			$sType      = $sDetails[4];
			$sLocations = $sDetails[5];
			$sDateTime  = $sDetails[6];


			$sSQL = "SELECT * FROM tbl_user_visits WHERE id='$iId'";
			$objDb->query($sSQL);

			if ($objDb->getCount( ) == 0)
			{
				$sSQL = "INSERT INTO tbl_user_visits (id, user_id, `date`, time_out, time_in, type, locations, date_time) VALUES ('$iId', '$iUserId', '$sDate', '$sTimeOut', '$sTimeIn', '$sType', '$sLocations', '$sDateTime')";
				$bFlag = $objDb->execute($sSQL);
			}

			else if ($objDb->getCount( ) == 1)
			{
				$sSQL = " UPDATE tbl_user_visits SET user_id='$iUserId', date_time='$sDateTime'";

				if ($sDate != $objDb->getField(0, 'date'))
					$sSQL .= ", `date`='$sDate' ";

				if ($sTimeIn != $objDb->getField(0, 'time_in'))
					$sSQL .= ", time_in='$sTimeIn' ";

				if ($sTimeOut != $objDb->getField(0, 'time_out'))
					$sSQL .= ", time_out='$sTimeOut' ";

				if ($sType != $objDb->getField(0, 'type'))
					$sSQL .= ", type='$sType' ";

				if ($sLocations != $objDb->getField(0, 'locations'))
					$sSQL .= ", locations='$sLocations' ";


				$sSQL .= " WHERE id='$iId'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == false)
				break;
		}
	}


	if ($bFlag == true)
		$objDb->execute("BEGIN");

	else
		$objDb->execute("ROLLBACK");



	$sSQL = "SELECT * FROM tbl_attendance WHERE `entry`='0' AND `date`=CURDATE( ) AND TIMESTAMPDIFF(MINUTE, date_time, NOW( )) <= 10";
	$objDb->query($sSQL);

	$iCount      = $objDb->getCount( );
	$sAttendance = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUserId   = $objDb->getField($i, 'user_id');
		$sDate     = $objDb->getField($i, 'date');
		$sTimeIn   = $objDb->getField($i, 'time_in');
		$sTimeOut  = $objDb->getField($i, 'time_out');
		$sRemarks  = $objDb->getField($i, 'remarks');
		$sDateTime = $objDb->getField($i, 'date_time');

		$sAttendance[$iUserId] = array($sDate, $sTimeIn, $sTimeOut, $sRemarks, $sDateTime);
	}


	$sSQL = "SELECT * FROM tbl_user_visits WHERE `date`=CURDATE( ) AND TIMESTAMPDIFF(MINUTE, date_time, NOW( )) <= 10";
	$objDb->query($sSQL);

	$iCount  = $objDb->getCount( );
	$sVisits = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId        = $objDb->getField($i, 'id');
		$iUserId    = $objDb->getField($i, 'user_id');
		$sDate      = $objDb->getField($i, 'date');
		$sTimeOut   = $objDb->getField($i, 'time_out');
		$sTimeIn    = $objDb->getField($i, 'time_in');
		$sType      = $objDb->getField($i, 'type');
		$sLocations = $objDb->getField($i, 'locations');
		$sDateTime  = $objDb->getField($i, 'date_time');

		$sVisits[$iId] = array($iUserId, $sDate, $sTimeOut, $sTimeIn, $sType, $sLocations, $sDateTime);
	}



	$sOutput = array( );

	$sOutput['Attendance'] = $sAttendance;
	$sOutput['Visits']     = $sVisits;


	print @serialize($sOutput);


/*
	$objEmail = new PHPMailer( );

	$objEmail->Subject = "Attendance Sync Alert - OUT";
	$objEmail->Body    = @json_encode($sAttendance)."<br /><br />\n\n".@json_encode($sVisits)."<br /><br />\n\n".$sSQL."<br><br>\n\n".$sError."<br/><br>\n\n".(($bFlag == false) ? "ERROR" : "OK");

	$objEmail->IsHTML(false);

	$objEmail->AddAddress("tahir.shahzad@apparelco.com", "MT Shahzad");
	$objEmail->Send( );
*/

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>