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


	$sSQL  = ("SELECT * FROM tbl_loom_plan WHERE po_id='".IO::intValue("Po")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$Po       = IO::intValue("Po");
		$FromDate = IO::strValue("FromDate");
		$ToDate   = IO::strValue("ToDate");
		$Looms    = IO::getArray("Looms");


		$objDb->execute("BEGIN");


		$sSQL = ("INSERT INTO tbl_loom_plan (po_id, from_date, to_date, looms, created, created_by, modified, modified_by)
		                             VALUES ('$Po', '$FromDate', '$ToDate', '".@implode(",", $Looms)."', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$iFromDate = strtotime($FromDate);
			$iToDate   = strtotime($ToDate);

			do
			{
				$sDate = date("Y-m-d", $iFromDate);

				for ($i = 0; $i < count($Looms); $i ++)
				{
					$sSQL = ("INSERT INTO tbl_loom_plan_details (po_id, `date`, loom_id, production) VALUES ('$Po', '$sDate', '{$Looms[$i]}', '0')");
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

			redirect("edit-loom-plan.php?Id={$Po}&Referer={$_SESSION['HTTP_REFERER']}", "LOOM_PLAN_ADDED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "LOOM_PLAN_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>