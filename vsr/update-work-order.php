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

	@require_once("../requires/session.php");
	@require_once("../requires/production-status-method.php");

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id   = IO::intValue('Id');
	$Step = IO::intValue("Step");


	if ($Step == 1)
	{
		$objDb->execute("BEGIN");


		$sSQL  = ("SELECT * FROM tbl_vsr2 WHERE work_order_no='".IO::strValue("WorkOrder")."' AND brand_id='".IO::intValue("Brand")."' AND id!='$Id'");
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
		{
			$Colors = IO::getArray("Color");
			$Pos    = IO::strValue("Pos");
			$Styles = IO::strValue("Styles");

			$sSQL  = ("UPDATE tbl_vsr2 SET vendor_id     = '".IO::intValue("Vendor")."',
			                               brand_id      = '".IO::intValue("Brand")."',
			                               season_id     = '".IO::intValue("Season")."',
			                               work_order_no = '".IO::strValue("WorkOrder")."',
			                               pos           = '$Pos',
			                               styles        = '$Styles',
			                               colors        = '".@implode(",", $Colors)."',
			                               modified      = NOW( ),
			                               modified_by   = '{$_SESSION['UserId']}'
			          WHERE id='$Id'");
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL  = ("DELETE FROM tbl_vsr_details WHERE work_order_id='$Id' AND color_id NOT IN ('".@implode(",", $Colors)."')");
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$sSQL  = ("DELETE FROM tbl_vsr_data WHERE work_order_id='$Id' AND color_id NOT IN ('".@implode(",", $Colors)."')");
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true)
			{
				$iStyles   = @explode(",", $Styles);
				$iCategory = getDbValue("category_id", "tbl_styles", "id='{$iStyles[0]}'");
				$sStages   = getDbValue("stages", "tbl_style_categories", "id='$iCategory'");
				$iStages   = @explode(",", $sStages);


				$sSQL = "SELECT po_ref_no, vsl_date, po_issue_date, fabric, notes, final_date FROM tbl_vsr_details WHERE work_order_id='$Id'";
				$objDb->query($sSQL);

				$sPoRef       = addslashes($objDb->getField(0, "po_ref_no"));
				$sVslDate     = $objDb->getField(0, "vsl_date");
				$sPoIssueDate = $objDb->getField(0, "po_issue_date");
				$sFabric      = addslashes($objDb->getField(0, "fabric"));
				$sNotes       = addslashes($objDb->getField(0, "notes"));
				$sFinalAudit  = $objDb->getField(0, "final_date");

				$sVslDate     = (($sVslDate == "") ? "0000-00-00" : $sVslDate);
				$sPoIssueDate = (($sPoIssueDate == "") ? "0000-00-00" : $sPoIssueDate);
				$sFinalAudit  = (($sFinalAudit == "") ? "0000-00-00" : $sFinalAudit);


				for ($i = 0; $i < count($Colors); $i ++)
				{
					if (getDbValue("COUNT(*)", "tbl_vsr_details", "work_order_id='$Id' AND color_id='{$Colors[$i]}'") == 0)
					{
						$sSQL = ("INSERT INTO tbl_vsr_details SET work_order_id = '$Id',
																  color_id      = '{$Colors[$i]}',
																  po_ref_no     = '$sPoRef',
																  vsl_date      = '$sVslDate',
																  po_issue_date = '$sPoIssueDate',
																  fabric        = '$sFabric',
																  notes         = '$sNotes',
																  final_date    = '$sFinalAudit',
																  ship_qty      = '0',
																  comments      = '',
																  created       = NOW( ),
																  created_by    = '{$_SESSION['UserId']}',
																  modified      = NOW( ),
																  modified_by   = '{$_SESSION['UserId']}'");
						$bFlag = $objDb->execute($sSQL);

						if ($bFlag == true)
						{
							foreach ($iStages as $iStage)
							{
								$sSQL = "INSERT INTO tbl_vsr_data SET work_order_id = '$Id',
																	  color_id      = '{$Colors[$i]}',
																	  stage_id      = '$iStage',
																	  start_date    = '0000-00-00',
																	  end_date      = '0000-00-00',
																	  completed     = '0'";
								$bFlag = $objDb->execute($sSQL);

								if ($bFlag == false)
									break;
							}
						}
					}

					else
					{
						foreach ($iStages as $iStage)
						{
							if (getDbValue("COUNT(*)", "tbl_vsr_data", "work_order_id='$Id' AND color_id='{$Colors[$i]}' AND stage_id='$iStage'") == 0)
							{
								$sSQL = "INSERT INTO tbl_vsr_data SET work_order_id = '$Id',
																	  color_id      = '{$Colors[$i]}',
																	  stage_id      = '$iStage',
																	  start_date    = '0000-00-00',
																	  end_date      = '0000-00-00',
																	  completed     = '0'";
								$bFlag = $objDb->execute($sSQL);

								if ($bFlag == false)
									break;
							}
						}
					}

					if ($bFlag == true && $sFinalAudit != "0000-00-00")
					{
						$iPoId = getDbValue("po_id", "tbl_po_colors", "id='{$Colors[$i]}'");

//	 					if (getDbValue("final_audit_date", "tbl_vsr", "po_id='$iPoId'") == "0000-00-00")
						{
							$sSQL  = "UPDATE tbl_vsr SET final_audit_date='$sFinalAudit' WHERE po_id='$iPoId'";
							$objDb->execute($sSQL);
						}
					}

					if ($bFlag == false)
						break;
				}


				if ($bFlag == true)
				{
					$sSQL  = "UPDATE tbl_vsr_details SET po_id    = (SELECT po_id FROM tbl_po_colors WHERE id=tbl_vsr_details.color_id),
														 style_id = (SELECT style_id FROM tbl_po_colors WHERE id=tbl_vsr_details.color_id AND FIND_IN_SET(style_id, '$Styles'))
							  WHERE work_order_id='$Id'";
					$bFlag = $objDb->execute($sSQL);
				}
			}

			if ($bFlag == true)
			{
				$objDb->execute("COMMIT");

				for ($i = 0; $i < count($Colors); $i ++)
				{
					_FindProductionStatus($Color[$i],$Id);

				}

				$_SESSION['Flag'] = "WORK_ORDER_UPDATED";

				$Step ++;
			}

			else
			{
				$objDb->execute("ROLLBACK");

				$_SESSION['Flag'] = "DB_ERROR";
			}
		}

		else
			$_SESSION['Flag'] = "WORK_ORDER_EXISTS";
	}

	else if ($Step == 2)
	{
		$sStyles   = getDbValue("styles", "tbl_vsr2", "id='$Id'");
		$iStyles   = @explode(",", $sStyles);
		$iCategory = getDbValue("category_id", "tbl_styles", "id='{$iStyles[0]}'");
		$sStages   = getDbValue("stages", "tbl_style_categories", "id='$iCategory'");
		$iStages   = @explode(",", $sStages);


		$objDb->execute("BEGIN");

		foreach ($iStages as $iStage)
		{
			$sSQL = ("UPDATE tbl_vsr_data SET start_date = '".((IO::strValue("StartDate_{$iStage}") == '') ? '0000-00-00' : IO::strValue("StartDate_{$iStage}"))."',
											  end_date   = '".((IO::strValue("EndDate_{$iStage}") == '') ? '0000-00-00' : IO::strValue("EndDate_{$iStage}"))."',
											  completed  = '".IO::intValue("Completed_{$iStage}")."'
			          WHERE work_order_id='$Id' AND stage_id='$iStage'");
			$bFlag = $objDb->execute($sSQL);

			//_FindProductionStatus($Color[$i],$Id);

			if ($bFlag == false)
				break;
		}

		if ($bFlag == true && IO::strValue("FinalAudit") != "")
		{
			$sSQL = ("UPDATE tbl_vsr_details SET final_date='".IO::strValue("FinalAudit")."' WHERE work_order_id='$Id'");
			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true)
		{
			for ($i = 0; $i < count($Colors); $i ++)
				UpdateProductionStatus($Color[$i],$Id);
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect("work-orders.php", "WORK_ORDER_UPDATED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}


	header("Location: edit-work-order.php?Id={$Id}&Step={$Step}");


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>
