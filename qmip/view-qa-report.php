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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$Id = IO::intValue('Id');

	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _PO,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
	                (SELECT name FROM tbl_auditor_groups WHERE id=tbl_qa_reports.group_id) AS _Group,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iReportId              = $objDb->getField(0, "report_id");
		$sVendor                = $objDb->getField(0, "_Vendor");
		$sAuditor               = $objDb->getField(0, "_Auditor");
		$sGroup                 = $objDb->getField(0, "_Group");
		$iPoId                  = $objDb->getField(0, "po_id");
		$sPO                    = $objDb->getField(0, "_PO");
		$sAdditionalPos         = $objDb->getField(0, "additional_pos");
		$iStyle                 = $objDb->getField(0, "style_id");
		$sAuditStatus           = $objDb->getField(0, "audit_status");
		$sAuditStage            = $objDb->getField(0, "audit_stage");
		$sAuditResult           = $objDb->getField(0, "audit_result");
		$sAuditType             = $objDb->getField(0, "audit_type");
		$sColors                = $objDb->getField(0, "colors");
		$sDescription           = $objDb->getField(0, "description");
		$sBundle                = $objDb->getField(0, "bundle");
                $sLotNo                = $objDb->getField(0, "cutting_lot_no");
		$sBatchSize             = $objDb->getField(0, "batch_size");
		$fPackedPercent         = $objDb->getField(0, "packed_percent");
		$sSizes                 = $objDb->getField(0, "sizes");
		$sDyeLotNo              = $objDb->getField(0, "dye_lot_no");
		$sAcceptablePointsWoven = $objDb->getField(0, "acceptable_points_woven");
		$sCutableFabricWidth    = $objDb->getField(0, "cutable_fabric_width");
		$sStockStatus           = $objDb->getField(0, "stock_status");
		$iRollsInspected        = $objDb->getField(0, "rolls_inspected");
		$iRolls                 = $objDb->getField(0, "no_of_rolls");
		$iFabricWidth           = $objDb->getField(0, "fabric_width");
		$iTotalGmts             = $objDb->getField(0, "total_gmts");
		$iGmtsDefective         = $objDb->getField(0, "defective_gmts");
		$iMaxDefects            = $objDb->getField(0, "max_defects");
		$iBeautifulProducts     = $objDb->getField(0, "beautiful_products");
		$iTotalCartons          = $objDb->getField(0, "total_cartons");
		$iCartonsRejected       = $objDb->getField(0, "rejected_cartons");
		$fPercentDecfective     = $objDb->getField(0, "defective_percent");
		$fStandard              = $objDb->getField(0, "standard");
		$fCartonsDhu            = $objDb->getField(0, "cartons_dhu");
		$iShipQty               = $objDb->getField(0, "ship_qty");
		$fKnitted               = $objDb->getField(0, "knitted");
		$fDyed                  = $objDb->getField(0, "dyed");
		$iCutting               = $objDb->getField(0, "cutting");
		$iSewing                = $objDb->getField(0, "sewing");
		$iFinishing             = $objDb->getField(0, "finishing");
		$iPacking               = $objDb->getField(0, "packing");
		$sFinalAuditDate        = $objDb->getField(0, "final_audit_date");
		$iReScreenQty           = $objDb->getField(0, "re_screen_qty");
		$fCartonsRequired       = $objDb->getField(0, "cartons_required");
		$fCartonsShipped        = $objDb->getField(0, "cartons_shipped");
		$sApprovedSample        = $objDb->getField(0, "approved_sample");
		$sShippingMark          = $objDb->getField(0, "shipping_mark");
		$sPackingCheck	        = $objDb->getField(0, "packing_check");
		$sApprovedTrims         = $objDb->getField(0, "approved_trims");
		$sShadeBand   	        = $objDb->getField(0, "shade_band");
		$sEmbApproval 	        = $objDb->getField(0, "emb_approval");
		$sGsmWeight   	        = $objDb->getField(0, "gsm_weight");
		$sUnit        	        = $objDb->getField(0, "unit");
		$sComments              = $objDb->getField(0, "qa_comments");
		$fDhu                   = $objDb->getField(0, "dhu");

		@list($iLength, $iWidth, $iHeight, $sUnit) = @explode("x", $objDb->getField(0, "carton_size"));
	}

	if ($sUnit == "")
		$sUnit = "in";

	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");


	$iMaxDefects = 0;

	$iBrand = getDbValue("brand_id", "tbl_styles", "id='$iStyle'");
	$fAql   = getDbValue("aql", "tbl_brands", "id='$iBrand'");
	$fAql   = (($fAql == 0) ? 2.5 : $fAql);

	if (@isset($iAqlChart["{$iTotalGmts}"]["{$fAql}"]))
		$iMaxDefects = $iAqlChart["{$iTotalGmts}"]["{$fAql}"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:544px; height:544px;">
	  <h2>Quality Inspection Report</h2>

	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff">
		  <td width="100%">

<?
	if ($iReportId == 6)
		@include($sBaseDir."includes/quonda/view-gf-report.php");

	else if ($iReportId == 7)
		@include($sBaseDir."includes/quonda/view-ar-report.php");

	else if ($iReportId == 19)
		@include($sBaseDir."includes/quonda/view-adidas-report.php");

	else if ($iReportId == 9)
		@include($sBaseDir."includes/quonda/view-yarn-report.php");

	else if ($iReportId == 10)
		@include($sBaseDir."includes/quonda/view-jako-report.php");

	else if ($iReportId == 11)
		@include($sBaseDir."includes/quonda/view-ms-report.php");

	else if ($iReportId == 14)
		@include($sBaseDir."includes/quonda/view-mgf-report.php");

	else if ($iReportId == 15)
		@include($sBaseDir."includes/quonda/view-vendor-cutting-report.php");

	else if ($iReportId == 16 || $iReportId == 17)
		@include($sBaseDir."includes/quonda/view-vendor-finishing-report.php");

	else if ($iReportId == 20 || $iReportId == 23)
		@include($sBaseDir."includes/quonda/view-kik-report.php");

	else
		@include($sBaseDir."includes/quonda/view-qa-report.php");
?>

		  </td>
	    </tr>
	  </table>

	  <br style="line-height:2px;" />
    </div>
<!--  Body Section Ends Here  -->

  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>