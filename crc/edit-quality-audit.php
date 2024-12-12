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

	$Id   = IO::intValue('Id');
	$Step = IO::intValue("Step");


	$sSQL = "SELECT * FROM tbl_quality_audits WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$AuditDate = $objDb->getField(0, "audit_date");
		$Vendor    = $objDb->getField(0, "vendor_id");
		$Auditors  = $objDb->getField(0, "auditors");
		$Cutting   = $objDb->getField(0, "cutting");
		$Sewing    = $objDb->getField(0, "sewing");
		$Packing   = $objDb->getField(0, "packing");
		$Finishing = $objDb->getField(0, "finishing");

		$Auditors = @explode(",", $Auditors);
	}

	else
		redirect($_SERVER['HTTP_REFERER'], "ERROR");


	if (getDbValue("title", "tbl_quality_areas", "id='$Step'") == "")
		$Step = 0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/edit-quality-audit.js"></script>
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
			    <h1><img src="images/h1/crc/quality-audits.jpg" width="204" height="24" vspace="8" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="crc/update-quality-audit.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Step" value="<?= $Step ?>" />

<?
	if ($Step == 0)
	{
		$sVendorsList  = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
		$sAuditorsList = getList("tbl_users", "id", "name", "designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (5,15,41))");
?>
				<h2>Edit Quality Audit</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="70">Audit Date<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>

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
					<td>Vendor<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Vendor">
						<option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Auditor(s)<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Auditors[]" id="Auditors" multiple size="10" style="min-width:204px;">
<?
		foreach ($sAuditorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Auditors)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Cutting</td>
					<td align="center">:</td>
					<td><input type="text" name="Cutting" value="<?= $Cutting ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Sewing</td>
					<td align="center">:</td>
					<td><input type="text" name="Sewing" value="<?= $Sewing ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Packing</td>
					<td align="center">:</td>
					<td><input type="text" name="Packing" value="<?= $Packing ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Finishing</td>
					<td align="center">:</td>
					<td><input type="text" name="Finishing" value="<?= $Finishing ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>
				</table>
<?
	}

	else if ($Step > 0)
	{
?>
				<h2 style="margin-bottom:1px;"><?= $Step ?>. <?= getDbValue("title", "tbl_quality_areas", "id='$Step'") ?></h2>
<?
		$sSectionsList = getList("tbl_quality_sections", "id", "title", "", "position");


		foreach ($sSectionsList as $iSection => $sSection)
		{
			$sSQL = "SELECT qad.id, qp.point, qad.rating, qad.remarks
					 FROM tbl_quality_audit_details qad, tbl_quality_points qp
					 WHERE qad.audit_id='$Id' AND qad.point_id=qp.id AND qp.area_id='$Step' AND qp.section_id='$iSection'
					 ORDER BY qp.position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			if ($iCount == 0)
				continue;


?>
				<h3><?= $sSection ?></h3>

			      <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
				    <tr bgcolor="#eaeaea">
				      <td width="3%"><b>#</b></td>
				      <td width="55%"><b>Inspection Point</b></td>
				      <td width="10%" align="center"><b>Rating</b></td>
				      <td width="32%"><b>Remarks</b></td>
				    </tr>
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPoint   = $objDb->getField($i, 'id');
				$sPoint   = $objDb->getField($i, 'point');
				$iRating  = $objDb->getField($i, 'rating');
				$sRemarks = $objDb->getField($i, 'remarks');
?>
				    <tr valign="top" bgcolor="<?= ((($i % 2) == 1) ? '#fafafa' : '#ffffff') ?>">
				      <td align="center"><?= ($i + 1) ?></td>
				      <td><?= $sPoint ?></td>

				      <td align="center">
					    <input type="hidden" name="Point[]" value="<?= $iPoint ?>" />

					    <select name="Rating<?= $iPoint ?>">
			              <option value="" style="background:#e9e1c5;"></option>
			              <option value="1"<?= (($iRating == 1) ? " selected" : "") ?> style="background:#00ff00;">A</option>
			              <option value="2"<?= (($iRating == 2) ? " selected" : "") ?> style="background:#99cc00;">B</option>
			              <option value="3"<?= (($iRating == 3) ? " selected" : "") ?> style="background:#ff9900;">C</option>
			              <option value="4"<?= (($iRating == 4) ? " selected" : "") ?> style="background:#ff0000;">D</option>
					    </select>
				      </td>

				      <td><textarea name="Remarks<?= $iPoint ?>" rows="3" style="width:98%;"><?= $sRemarks ?></textarea></td>
				    </tr>
<?
			}
?>
			      </table>
<?
		}
	}
?>

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm('<?= $Step ?>');" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='crc/<?= (($Step == 0) ? 'quality-audits.php' : ('edit-quality-audit.php?Id='.$Id.'&Step='.($Step - 1))) ?>';" />
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>