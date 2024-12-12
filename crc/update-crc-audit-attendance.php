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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/
	@require_once("../requires/session.php");
	@require_once("../requires/image-functions.php");

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id             = IO::intValue('AuditId');
        $OmDate         = IO::strValue('OmDate');
        $sStartingTime  = IO::strValue('StartingTime1');
        $CmDate         = IO::strValue('CmDate');
        $sClosingTime   = IO::strValue('StartingTime2');
        $bFlag = true;


	$_SESSION['Flag'] = "";
	$objDb->execute("BEGIN");

        $sSQL  = "DELETE from tbl_crc_attendance WHERE audit_id='$Id'";
        $bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
        {
                $sSQL  = "INSERT INTO tbl_crc_attendance SET audit_id = '$Id',
                                                            opening_date    = '$OmDate',
                                                            opening_time    = '$sStartingTime',
                                                            closing_date    = '$CmDate',
                                                            closing_time    = '$sClosingTime'";
                $bFlag = $objDb->execute($sSQL);

	}

        if ($bFlag == true)
        {
            $sSQL  = "DELETE from tbl_crc_attendance_details WHERE audit_id='$Id'";
            $bFlag = $objDb->execute($sSQL);
        }
        
        if ($bFlag == true)
        {
                $Ateendees    = IO::getArray("OMName");
                $Designations = IO::getArray("OMDesignation");

                if(!empty($Ateendees) && !empty($Designations))
                {
                    foreach($Ateendees as $key => $sAttendee)
                    {
                        if($sAttendee != "" && $Designations[$key] != "")
                        {
                              $sSQL  = ("INSERT INTO tbl_crc_attendance_details SET audit_id      = '$Id',
                                                                                    attendee      = '".$sAttendee."',
                                                                                    designation   = '".$Designations[$key]."',
                                                                                    meeting_type  = 'O'");
                              $bFlag = $objDb->execute($sSQL);

                              if($bFlag == false)
                              {
                                  break;
                              }
                        }
                    }

                }
        }
        
        if($bFlag == true)
        {
                $Ateendees    = IO::getArray("CMName");
                $Designations = IO::getArray("CMDesignation");

                if(!empty($Ateendees) && !empty($Designations))
                {
                    foreach($Ateendees as $key => $sAttendee)
                    {
                        if($sAttendee != "" && $Designations[$key] != "")
                        {
                              $sSQL  = ("INSERT INTO tbl_crc_attendance_details SET audit_id      = '$Id',
                                                                                    attendee      = '".$sAttendee."',
                                                                                    designation   = '".$Designations[$key]."',
                                                                                    meeting_type  = 'C'");
                              $bFlag = $objDb->execute($sSQL);

                              if($bFlag == false)
                              {
                                  break;
                              }
                        }
                    }

                }
        }
        
	if ($_SESSION['Flag'] == "")
	{
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

                        $_SESSION["Flag1122"] = "Updated Successfully"; 
//                        redirect($_SERVER['HTTP_REFERER'], "Updated Successfully!");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			$objDb->execute("ROLLBACK");
		}
	}

            header("Location: {$_SERVER['HTTP_REFERER']}");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>
