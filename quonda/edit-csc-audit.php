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

	if ($sUserRights['Add'] != "Y" && $sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$Id      = IO::intValue('Id');
	$Referer = urldecode(IO::strValue("Referer"));

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];

	$sSQL = "SELECT *,
	                (SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id=tbl_csc_audits.po_id) AS _OrderNo,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_csc_audits.vendor_id) AS _Vendor,
	                (SELECT brand FROM tbl_brands WHERE id=tbl_csc_audits.brand_id) AS _Brand
	         FROM tbl_csc_audits
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$OrderNo     = $objDb->getField(0, "_OrderNo");
		$Vendor      = $objDb->getField(0, "_Vendor");
		$Brand       = $objDb->getField(0, "_Brand");
		$Po          = $objDb->getField(0, "po_id");
		$AuditDate   = $objDb->getField(0, "audit_date");
		$AuditResult = $objDb->getField(0, "audit_result");
		$SampleSize  = $objDb->getField(0, "sample_size");
		$Quantity    = $objDb->getField(0, "quantity");


		$sSQL = "SELECT report_id FROM tbl_qa_reports WHERE po_id='$Po' OR FIND_IN_SET('$Po', additional_pos) LIMIT 1";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
			$ReportId = $objDb->getField(0, 0);

		else
			$ReportId = 1;
	}

	else
		redirect($Referer, "DB_ERROR");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/edit-csc-audit.js.php?Id=<?= $Id ?>&Po=<?= $Po ?>&ReportId=<?= $ReportId ?>"></script>
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
			    <h1>csc audits</h1>

			    <form name="frmData" id="frmData" method="post" action="quonda/update-csc-audit.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />

				<h2>CSC Audit Report</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="90">Vendor</td>
					<td width="20" align="center">:</td>
					<td><?= $Vendor ?></td>
				  </tr>

				  <tr>
					<td>Brand</td>
					<td align="center">:</td>
					<td><?= $Brand ?></td>
				  </tr>

				  <tr>
					<td>Order No</td>
					<td align="center">:</td>
					<td><?= $OrderNo ?></td>
				  </tr>

				  <tr>
					<td>Audit Date<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="AuditDate" id="AuditDate" value="<?= $AuditDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr>
					<td>Audit Result<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AuditResult">
						<option value=""></option>
						<option value="P">Pass</option>
						<option value="F">Fail</option>
						<option value="H">Hold</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditResult.value = "<?= $AuditResult ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Sample Size</td>
					<td align="center">:</td>
					<td><input type="text" name="SampleSize" value="<?= $SampleSize ?>" maxlength="10" size="5" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Quantity</td>
					<td align="center">:</td>
					<td><input type="text" name="Quantity" value="<?= $Quantity ?>" maxlength="10" size="5" class="textbox" /></td>
				  </tr>
				</table>

				<br />
				<h2 id="DefectDetails" style="margin-bottom:0px;">Defects Details</h2>
<?
	$sPoStylesList = array( );

	$sSQL = "SELECT DISTINCT(style_id),
	                (SELECT style FROM tbl_styles WHERE id=tbl_po_colors.style_id) AS _Style
	         FROM tbl_po_colors
	         WHERE po_id='$Po'
	         ORDER BY _Style";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sPoStylesList[$objDb->getField($i, 0)] = $objDb->getField($i, 1);


	$sSQL = "SELECT id, style_id, code_id, defects FROM tbl_csc_audit_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
				<input type="hidden" id="Count" name="Count" value="<?= $iCount ?>" />

			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="50" align="center"><b>#</b></td>
					<td width="200"><b>PO Style</b></td>
					<td><b>Code - Check Points</b></td>
					<td width="100" align="center"><b>Defects</b></td>
			      </tr>
			    </table>

			    <div id="QaDefects">
<?
	for($i = 0; $i < $iCount; $i ++)
	{
?>

				<div id="DefectRecord<?= $i ?>">
				  <div>
				    <input type="hidden" id="DefectId<?= $i ?>" name="DefectId<?= $i ?>" value="<?= $objDb->getField($i, 'id') ?>" />

					<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					  <tr class="sdRowColor" valign="top">
						<td width="50" align="center"><?= ($i + 1) ?></td>

						<td width="200">
					  <select name="Style<?= $i ?>">
						<option value=""></option>
<?
		foreach ($sPoStylesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $objDb->getField($i, 'style_id')) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
						</td>

						<td>
						  <select id="Code<?= $i ?>" name="Code<?= $i ?>">
							<option value=""></option>
<?

		$sSQL = "SELECT DISTINCT(type_id), (SELECT type FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) FROM tbl_defect_codes WHERE report_id='$ReportId' ORDER BY type_id";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iTypeId = $objDb2->getField($j, 0);
			$sType   = $objDb2->getField($j, 1);
?>
		        			<optgroup label="<?= $sType ?>">
<?
			$sSQL = "SELECT id, code, defect FROM tbl_defect_codes WHERE report_id='$ReportId' AND type_id='$iTypeId' ORDER BY code";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iCodeId = $objDb3->getField($k, 0);
				$sCode   = $objDb3->getField($k, 1);
				$sDefect = $objDb3->getField($k, 2);

?>
		        			  <option value="<?= $iCodeId ?>"><?= $sCode ?> - <?= $sDefect ?></option>
<?
			}
?>
		        			</optgroup>
<?
		}
?>
						  </select>

						  <script type="text/javascript">
						  <!--
							document.frmData.Code<?= $i ?>.value = "<?= $objDb->getField($i, 'code_id') ?>";
						  -->
						  </script>
						</td>

						<td width="100" align="center"><input type="text" id="Defects<?= $i ?>" name="Defects<?= $i ?>" value="<?= $objDb->getField($i, 'defects') ?>" maxlength="3" size="3" class="textbox" /></td>
					  </tr>
					</table>
				  </div>
				</div>
<?
	}
?>
				</div>

				<div class="qaButtons">
				  <input type="button" value="" class="btnAdd" title="Add Defect" onclick="addDefect( );" /><input type="button" value="" class="btnDelete" title="Delete Defect" onclick="deleteDefect( );" />
				</div>

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
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