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

	$Id      = IO::intValue('Id');
	$Referer = $_SERVER['HTTP_REFERER'];


	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_qa_reports.po_id) AS _PO,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
	                (SELECT name FROM tbl_auditor_groups WHERE id=tbl_qa_reports.group_id) AS _Group,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor
	         FROM tbl_qa_reports
	         WHERE id='$Id' AND vendor_id IN ({$_SESSION['Vendors']}) AND brand_id IN ({$_SESSION['Brands']})";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sAuditCode     = $objDb->getField(0, "audit_code");
		$iReportId      = $objDb->getField(0, "report_id");
		$iVendorId      = $objDb->getField(0, "vendor_id");
		$sVendor        = $objDb->getField(0, "_Vendor");
		$sAuditor       = $objDb->getField(0, "_Auditor");
		$sGroup         = $objDb->getField(0, "_Group");
		$iPoId          = $objDb->getField(0, "po_id");
		$sPO            = $objDb->getField(0, "_PO");
		$sAdditionalPos = $objDb->getField(0, "additional_pos");
		$iStyle         = $objDb->getField(0, "style_id");
		$sAuditStatus   = $objDb->getField(0, "audit_status");
		$sAuditStage    = $objDb->getField(0, "audit_stage");
		$sAuditResult   = $objDb->getField(0, "audit_result");
		$sComments      = $objDb->getField(0, "qa_comments");
		$fDhu           = $objDb->getField(0, "dhu");
	}
	
	else
		redirect($_SERVER['HTTP_REFERER'], "ERROR");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/send-qa-report.js"></script>
</head>

<body>

<div id="MainDiv">
  <div id="PageLeftBorder">
    <div id="PageRightBorder">

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

      <div id="PageContents">

<!--  Header Section Starts Here  -->
<?
	@include($sBaseDir."includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include($sBaseDir."includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
	    <div id="Body">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1><img src="images/h1/quonda/email-qa-report.jpg" width="229" height="24" alt="" title="" vspace="8" /></h1>

			    <form name="frmData" id="frmData" method="post" action="quonda/email-qa-report.php" class="frmOutline" onsubmit="$('BtnSubmit').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />
			    <input type="hidden" name="ReportId" value="<?= $iReportId ?>" />
			    <input type="hidden" name="AuditStage" value="<?= $sAuditStage ?>" />
			    <input type="hidden" name="Brand" value="<?= getDbValue("brand_id", "tbl_po", "id='$iPoId'") ?>" />

				<h2>Quality Inspection Report</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="90">Audit Code</td>
					<td width="20" align="center">:</td>
					<td><b><?= $sAuditCode ?></b></td>
				  </tr>

				  <tr>
					<td>Vendor</td>
					<td align="center">:</td>
					<td><?= $sVendor ?></td>
				  </tr>

				  <tr>
					<td>Auditor</td>
					<td align="center">:</td>
					<td><?= $sAuditor ?></td>
				  </tr>

				  <tr>
					<td>Group</td>
					<td align="center">:</td>
					<td><?= $sGroup ?></td>
				  </tr>

<?
	$sPos = "";

	$sSQL = "SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id IN ($sAdditionalPos) ORDER BY order_no";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (", ".$objDb->getField($i, 0));
	}
?>
				  <tr valign="top">
					<td>PO(s)</td>
					<td align="center">:</td>
					<td><?= ($sPO.$sPos) ?></td>
				  </tr>

				  <tr>
					<td>Style</td>
					<td align="center">:</td>
					<td><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
				  </tr>

				  <tr>
					<td>Audit Stage</td>
					<td align="center">:</td>
					<td><?= getDbValue("stage", "tbl_audit_stages", "code='$sAuditStage'") ?></td>
				  </tr>

<?
	switch ($sAuditStatus)
	{
		case "1st" : $sAuditStatus = "1st"; break;
		case "2nd" : $sAuditStatus = "2nd"; break;
		case "3rd" : $sAuditStatus = "3rd"; break;
		case "4th" : $sAuditStatus = "4th"; break;
		case "5th" : $sAuditStatus = "5th"; break;
		case "6th" : $sAuditStatus = "6th"; break;
	}
?>
				  <tr>
					<td>Audit Status</td>
					<td align="center">:</td>
					<td><?= $sAuditStatus ?></td>
				  </tr>

<?
	switch ($sAuditResult)
	{
		case "P" : $sResult = "Pass"; break;
		case "F" : $sResult = "Fail"; break;
		case "H" : $sResult = "Hold"; break;
	}
?>
				  <tr>
					<td>Audit Result</td>
					<td align="center">:</td>
					<td><?= $sResult ?></td>
				  </tr>

				  <tr>
					<td>D.H.U</td>
					<td align="center">:</td>
					<td><?= @round($fDhu, 2) ?>%</td>
				  </tr>

				  <tr valign="top">
					<td>QA Comments</td>
					<td align="center">:</td>
					<td><?= nl2br($sComments) ?></td>
				  </tr>
				</table>

				<br />
				<h2>Select Recipients</h2>
				&nbsp; ( <a href="#" onclick="return checkAll( );">Check ALL</a> | <a href="#" onclick="return clearAll( );">Clear ALL</a> )<br />
				<br />

				<table border="0" cellpadding="2" cellspacing="0" width="100%">
<?
	$sSQL = "SELECT id, name, email
	         FROM tbl_qa_emails
	         WHERE FIND_IN_SET('$iVendorId', vendors) AND (audit_stages='' OR FIND_IN_SET('$sAuditStage', audit_stages)) AND (audit_results='' OR FIND_IN_SET('$sAuditResult', audit_results))
	         ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iEmailId = $objDb->getField($i, "id");
		$sName    = $objDb->getField($i, "name");
		$sEmail   = $objDb->getField($i, "email");
?>

				  <tr>
					<td width="25"><input type="checkbox" class="recipients" name="Recipients[]" value="<?= $iEmailId ?>" /></td>
					<td><?= $sName ?> &lt;<?= $sEmail ?>&gt;</td>
				  </tr>
<?
	}
?>
				</table>


				<br />

				<div class="buttonsBar">
<?
	if ($iCount > 0)
	{
?>
				  <input type="submit" id="BtnSubmit" value="" class="btnSubmit" title="Submit" onclick="return validateForm( );" />
<?
	}
?>
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= $Referer ?>';" />
				</div>
			    </form>

			  </td>
			</tr>
		  </table>

<?
	@include($sBaseDir."includes/my-profile.php");
?>
        </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include($sBaseDir."includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

      </div>
    </div>
  </div>
</div>

<!--  Bottom Bar Section Starts Here  -->
<?
	@include($sBaseDir."includes/bottom-bar.php");
?>
<!--  Bottom Bar Section Ends Here  -->

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>