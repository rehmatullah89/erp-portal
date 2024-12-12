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

	@require_once("../requires/session.php");

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id       = IO::intValue("Id");
	$Referer  = urlencode(IO::strValue("Referer"));
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Looms    = IO::getArray("Looms");


	$objDb->execute("BEGIN");


	$sSQL = ("UPDATE tbl_loom_plan SET from_date   = '$FromDate',
	                                   to_date     = '$ToDate',
	                                   looms       = '".@implode(",", $Looms)."',
	                                   modified    = NOW( ),
	                                   modified_by = '{$_SESSION['UserId']}'
			  WHERE po_id='$Id'");
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL  = ("DELETE FROM tbl_loom_plan_details WHERE po_id='$Id' AND NOT FIND_IN_SET(loom_id, '".@implode(",", $Looms)."')");
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_loom_plan_details WHERE po_id='$Id' AND NOT (`date` BETWEEN '$FromDate' AND '$ToDate')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$iFromDate = strtotime($FromDate);
		$iToDate   = strtotime($ToDate);

		do
		{
			$sDate = date("Y-m-d", $iFromDate);

			for ($i = 0; $i < count($Looms); $i ++)
			{
				$iProduction = IO::intValue("Production{$Looms[$i]}_{$iFromDate}");

				if (getDbValue("COUNT(*)", "tbl_loom_plan_details", "po_id='$Id' AND `date`='$sDate' AND loom_id='{$Looms[$i]}'") == 1)
					$sSQL = "UPDATE tbl_loom_plan_details SET production='$iProduction' WHERE po_id='$Id' AND `date`='$sDate' AND loom_id='{$Looms[$i]}'";

				else
					$sSQL = "INSERT INTO tbl_loom_plan_details (po_id, `date`, loom_id, production) VALUES ('$Id', '$sDate', '{$Looms[$i]}', '$iProduction')";

				$bFlag = $objDb->execute($sSQL);

				if ($bFlag == false)
					break;
			}

			if ($bFlag == false)
				break;


			$iFromDate += 86400;
		}
		while ($iFromDate <= $iToDate);
	}


	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		redirect(urldecode($Referer), "LOOM_PLAN_UPDATED");
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";

		header("Location: edit-loom-plan.php?Id={$Id}&Referer={$Referer}");
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>