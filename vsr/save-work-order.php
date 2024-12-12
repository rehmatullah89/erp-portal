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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$sSQL  = ("SELECT * FROM tbl_vsr2 WHERE work_order_no='".IO::strValue("WorkOrder")."' AND brand_id='".IO::intValue("Brand")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iWorkOrder = getNextId("tbl_vsr2");


		$Colors = IO::getArray("Color");
		$Pos    = IO::strValue("Pos");
		$Styles = IO::strValue("Styles");


		$objDb->execute("BEGIN");

		$sSQL  = ("INSERT INTO tbl_vsr2 (id, vendor_id, brand_id, season_id, work_order_no, pos, styles, colors, created, created_by, modified, modified_by)
		                         VALUES ('$iWorkOrder', '".IO::intValue("Vendor")."', '".IO::intValue("Brand")."', '".IO::intValue("Season")."', '".IO::strValue("WorkOrder")."', '$Pos', '$Styles', '".@implode(",", $Colors)."', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");
		$bFlag = $objDb->execute($sSQL);


		if ($bFlag == true)
		{
			$sStages = getDbValue("stages", "tbl_style_categories", ("id='".IO::intValue("Category")."'"));
			$iStages = @explode(",", $sStages);


			for ($i = 0; $i < count($Colors); $i ++)
			{
				$sSQL = ("INSERT INTO tbl_vsr_details SET work_order_id = '$iWorkOrder',
				                                          color_id      = '{$Colors[$i]}',
														  po_ref_no     = '".IO::strValue('PoRef')."',
														  vsl_date      = '".((IO::strValue('VslDate') == '') ? '0000-00-00' : IO::strValue('VslDate'))."',
														  po_issue_date = '".((IO::strValue('PoIssueDate') == '') ? '0000-00-00' : IO::strValue('PoIssueDate'))."',
														  fabric        = '".IO::strValue('Fabric')."',
														  notes         = '".IO::strValue('Notes')."',
														  final_date    = '".((IO::strValue('FinalAudit') == '') ? '0000-00-00' : IO::strValue('FinalAudit'))."',
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
						$sSQL = "INSERT INTO tbl_vsr_data SET work_order_id = '$iWorkOrder',
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

				if ($bFlag == true && $sFinalAudit != "0000-00-00")
				{
					$iPoId = getDbValue("po_id", "tbl_po_colors", "id='{$Colors[$i]}'");

//					if (getDbValue("final_audit_date", "tbl_vsr", "po_id='$iPoId'") == "0000-00-00")
					{
						$sSQL  = ("UPDATE tbl_vsr SET final_audit_date='".IO::strValue('FinalAudit')."' WHERE po_id='$iPoId'");
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
				          WHERE work_order_id='$iWorkOrder'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true && ($Pos == "" || $Pos == "0"))
			{
				$sSQL = "SELECT DISTINCT(po_id) FROM tbl_vsr_details WHERE work_order_id='$iWorkOrder'";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );
				$iPos   = array( );

				for ($i = 0; $i < $iCount; $i ++)
					$iPos[] = $objDb->getField($i, 0);

				$sPos = @implode(",", $iPos);


				$sSQL  = "UPDATE tbl_vsr2 SET pos='$sPos' WHERE id='$iWorkOrder'";
				$bFlag = $objDb->execute($sSQL);
			}

			if ($bFlag == true && ($Styles == "" || $Styles == "0"))
			{
				$sSQL = "SELECT DISTINCT(style_id) FROM tbl_vsr_details WHERE work_order_id='$iWorkOrder'";
				$objDb->query($sSQL);

				$iCount  = $objDb->getCount( );
				$iStyles = array( );

				for ($i = 0; $i < $iCount; $i ++)
					$iStyles[] = $objDb->getField($i, 0);

				$sStyles = @implode(",", $iStyles);


				$sSQL  = "UPDATE tbl_vsr2 SET styles='$sStyles' WHERE id='$iWorkOrder'";
				$bFlag = $objDb->execute($sSQL);
			}
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect("edit-work-order.php?Id={$iWorkOrder}&Step=2", "WORK_ORDER_SAVED");
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$_SESSION['Flag'] = "DB_ERROR";
		}
	}

	else
		$_SESSION['Flag'] = "WORK_ORDER_EXISTS";


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>