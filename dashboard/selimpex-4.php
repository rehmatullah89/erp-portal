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

	$Vendor     = 13;
	$AuditStage = IO::strValue("AuditStage");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");

	if ($FromDate == "")
		$FromDate = date("Y-m-d");

	if ($ToDate == "")
		$ToDate = date("Y-m-d");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionChartsExportComponent.js"></script>
  <script type="text/javascript" src="scripts/jquery.js"></script>
</head>

<body style="margin:0px; padding:20px; background:#ffffff;">
<?
	$sConditions = " AND FIND_IN_SET(qa.report_id, '$sQmipReports') ";

	if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND qa.audit_stage!='' ";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";
?>
		  <h1>Best Performers</h1>

		  <div style="padding:25px; font-size:24px;">

<?
	$sSQL = "SELECT (SELECT name FROM tbl_users WHERE id=qa.user_id) AS _Auditor,
					ROUND(((
						SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id))
						/
						COALESCE(SUM(IF(qa.total_gmts='0', qa.checked_gmts, qa.total_gmts)), 0)
					) * 100), 2) AS _Dr,

					SUM((SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id)) AS _Defects,
					COALESCE(SUM(IF(qa.total_gmts='0', qa.checked_gmts, qa.total_gmts)), 0) AS _Quantity
			 FROM tbl_qa_reports qa
			 WHERE (qa.checked_gmts>'0' OR qa.audit_result!='') AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') $sConditions
			 GROUP BY qa.user_id
			 HAVING _Quantity > '0'
			 ORDER BY _Dr
			 LIMIT 5";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditor  = $objDb->getField($i, "_Auditor");
		$fDr       = $objDb->getField($i, "_Dr");
		$iDefects  = $objDb->getField($i, "_Defects");
		$iQuantity = $objDb->getField($i, "_Quantity");

		print (($i + 1).". ".$sAuditor."<br /><small>(DR:".$fDr."% &nbsp;  Qty:".$iQuantity." &nbsp; Defects:".$iDefects.")</small><br><br>");
	}
?>
	      </div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>