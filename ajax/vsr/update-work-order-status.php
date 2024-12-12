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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");
	@require_once("../../requires/production-status-method.php");

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id     = IO::strValue('id');
	$Value  = IO::strValue('value');
	$iStage = 0;

	@list($Field, $Color, $WorkOrder) = @explode("|", $Id);

	if (@strpos($Field, "start_date") !== FALSE || @strpos($Field, "end_date") !== FALSE || @strpos($Field, "completed") !== FALSE)
	{
		$iStage = str_replace(array("start_date", "end_date", "completed"), "", $Field);
		$Field  = str_replace($iStage, "", $Field);
	}

	if (strpos($Field, "_date") !== FALSE || $Field ==  "vsr_etd_required")
	{
		if ($Value != "")
		{
			@list($sMonth, $sDay, $sYear) = @explode("/", $Value);

			$Value = "{$sYear}-{$sMonth}-{$sDay}";
		}

		else
			$Value = "0000-00-00";
	}


	if (substr($Field, 0, 4) == "vsr_")
	{
		$sOldValue = getDbValue($Field, "tbl_po_colors", "id='$Color'");


		$sSQL = "UPDATE tbl_po_colors SET {$Field}='$Value' WHERE id='$Color'";

		if ($objDb->execute($sSQL) == true && $sOldValue != $Value)
		{
			if ($Field ==  "vsr_etd_required")
			{
				$Value     = formatDate($Value);
				$sOldValue = formatDate($sOldValue);
			}


			$iLogId = getNextId("tbl_vsr_log");

			$sSQL  = ("INSERT INTO tbl_vsr_log (id, work_order_id, color_id, field, old_value, new_value, user_id, date_time)
										VALUES ('$iLogId', '$WorkOrder', '$Color', '{$Field}', '$sOldValue', '$Value', '{$_SESSION['UserId']}', NOW( ))");
			$bFlag = $objDb->execute($sSQL);
		}


		if ($Field ==  "vsr_etd_required")
		{
			$sDate = getDbValue("DATE_FORMAT({$Field}, '%m/%d/%Y')", "tbl_po_colors", "id='$Color'");

			print (($sDate == "00/00/0000") ? "n/a" : $sDate);
		}

		else
			print formatNumber(getDbValue($Field, "tbl_po_colors", "id='$Color'"));
	}

	else if ($iStage > 0)
	{
		$sOldValue = getDbValue($Field, "tbl_vsr_data", "work_order_id='$WorkOrder' AND color_id='$Color' AND stage_id='$iStage'");


		$sSQL = "UPDATE tbl_vsr_data SET {$Field}='$Value' WHERE work_order_id='$WorkOrder' AND color_id='$Color' AND stage_id='$iStage'";


		if ($objDb->execute($sSQL) == true && $sOldValue != $Value)
		{
			if (strpos($Field, "_date") !== FALSE)
			{
				$Value     = formatDate($Value);
				$sOldValue = formatDate($sOldValue);
			}


			$iLogId = getNextId("tbl_vsr_log");

			$sSQL  = ("INSERT INTO tbl_vsr_log (id, work_order_id, color_id, field, old_value, new_value, user_id, date_time)
										VALUES ('$iLogId', '$WorkOrder', '$Color', '{$Field}-{$iStage}', '$sOldValue', '$Value', '{$_SESSION['UserId']}', NOW( ))");
			$bFlag = $objDb->execute($sSQL);
		}


		if (strpos($Field, "_date") !== FALSE)
		{
			$sDate = getDbValue("DATE_FORMAT({$Field}, '%m/%d/%Y')", "tbl_vsr_data", "work_order_id='$WorkOrder' AND color_id='$Color' AND stage_id='$iStage'");

			print (($sDate == "00/00/0000") ? "n/a" : $sDate);
		}

		else
			print getDbValue($Field, "tbl_vsr_data", "work_order_id='$WorkOrder' AND color_id='$Color' AND stage_id='$iStage'");
	}

	else
	{
		$sOldValue = getDbValue($Field, "tbl_vsr_details", "work_order_id='$WorkOrder' AND color_id='$Color'");


		$sSQL = "UPDATE tbl_vsr_details SET {$Field}='$Value' WHERE work_order_id='$WorkOrder' AND color_id='$Color'";

		if ($objDb->execute($sSQL) == true && $sOldValue != $Value)
		{
			if (strpos($Field, "_date") !== FALSE)
			{
				$Value     = formatDate($Value);
				$sOldValue = formatDate($sOldValue);
			}


			$iLogId = getNextId("tbl_vsr_log");

			$sSQL  = ("INSERT INTO tbl_vsr_log (id, work_order_id, color_id, field, old_value, new_value, user_id, date_time)
										VALUES ('$iLogId', '$WorkOrder', '$Color', '{$Field}', '$sOldValue', '$Value', '{$_SESSION['UserId']}', NOW( ))");
			$bFlag = $objDb->execute($sSQL);
		}


		if (strpos($Field, "_date") !== FALSE)
		{
			$sDate = getDbValue($Field, "tbl_vsr_details", "work_order_id='$WorkOrder' AND color_id='$Color'");

			if ($Field == "final_date" && $sDate != "0000-00-00")
			{
				$iPoId = getDbValue("po_id", "tbl_po_colors", "id='$Color'");

				$sSQL = "UPDATE tbl_vsr SET final_audit_date='$sDate' WHERE po_id='$iPoId'";
				$objDb->execute($sSQL);
			}

			print (($sDate == "0000-00-00") ? "n/a" : date("m/d/Y", strtotime($sDate)));
		}

		else
			print getDbValue($Field, "tbl_vsr_details", "work_order_id='$WorkOrder' AND color_id='$Color'");
	}

	UpdateProductionStatus($Color,$WorkOrder);

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>