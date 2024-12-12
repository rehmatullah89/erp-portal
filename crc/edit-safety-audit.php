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


	$sSQL = "SELECT * FROM tbl_safety_audits WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$Vendor         = $objDb->getField(0, "vendor_id");
		$Auditors       = $objDb->getField(0, "auditors");
		$AuditDate      = $objDb->getField(0, "audit_date");
		$AuditTime      = $objDb->getField(0, "audit_time");
		$Representative = $objDb->getField(0, "representative");
		$SalariedStaff  = $objDb->getField(0, "salaried_staff");
		$ContractStaff  = $objDb->getField(0, "contract_staff");
		$MaleStaff      = $objDb->getField(0, "male_staff");
		$FemaleStaff    = $objDb->getField(0, "female_staff");

		$Auditors = @explode(",", $Auditors);

		@list($AuditHours, $AuditMinutes) = @explode(":", $AuditTime);
	}

	else
		redirect($_SERVER['HTTP_REFERER'], "ERROR");


	if (getDbValue("title", "tbl_safety_categories", "id='$Step'") == "")
		$Step = 0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/edit-safety-audit.js"></script>
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
			   <h1><img src="images/h1/crc/safety-audits.jpg" width="244" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="crc/update-safety-audit.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Step" value="<?= $Step ?>" />

<?
	if ($Step == 0)
	{
		$sVendorsList  = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
		$sAuditorsList = getList("tbl_users", "id", "name", "designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (5,15,41))");
		$sTypesList    = getList("tbl_safety_types", "id", "title");
?>
				<h2>Edit Safety Audit</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="110">Vendor<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

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
					<td>Representative<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Representative" value="<?= $Representative ?>" maxlength="50" size="25" class="textbox" /></td>
				  </tr>

 				  <tr>
					<td width="50">Audit Date<span class="mandatory">*</span></td>
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
					<td>Audit Time<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AuditHours">
						<option value="">HH</option>
<?
		for ($i = 0; $i < 24; $i ++)
		{
			$sHour = str_pad($i, 2, "0", STR_PAD_LEFT);
?>
						<option value="<?= $sHour ?>"<?= (($sHour == $AuditHours) ? " selected" : "") ?>><?= $sHour ?></option>
<?
		}
?>
					  </select>

					  <select name="AuditMinutes">
						<option value="">MM</option>
<?
		for ($i = 0; $i < 60; $i += 5)
		{
			$sMinutes = str_pad($i, 2, "0", STR_PAD_LEFT);
?>
						<option value="<?= $sMinutes ?>"<?= (($sMinutes == $AuditMinutes) ? " selected" : "") ?>><?= $sMinutes ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Salaried Staff</td>
					<td align="center">:</td>
					<td><input type="text" name="SalariedStaff" value="<?= $SalariedStaff ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Contract Staff</td>
					<td align="center">:</td>
					<td><input type="text" name="ContractStaff" value="<?= $ContractStaff ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Male Staff (%)</td>
					<td align="center">:</td>
					<td><input type="text" name="MaleStaff" value="<?= $MaleStaff ?>" size="12" maxlength="6" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Female Staff (%)</td>
					<td align="center">:</td>
					<td><input type="text" name="FemaleStaff" value="<?= $FemaleStaff ?>" size="12" maxlength="6" class="textbox" /></td>
				  </tr>
				</table>

<?
	}

	else if ($Step > 0)
	{
?>
				<h2><?= $Step ?>. <?= getDbValue("title", "tbl_safety_categories", "id='$Step'") ?></h2>
<?
		$sSQL = "SELECT cad.*, cq.title, cq.details
				 FROM tbl_safety_audit_details cad, tbl_safety_questions cq
				 WHERE cad.audit_id='$Id' AND cad.question_id=cq.id AND cq.category_id='$Step'
				 ORDER BY cq.position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iQuestion = $objDb->getField($i, 'cad.id');
			$sQuestion = $objDb->getField($i, 'title');
			$sDetails  = $objDb->getField($i, 'details');
			$iRating   = $objDb->getField($i, 'rating');
			$sComments = $objDb->getField($i, 'comments');

			$sPictures = array( );
			$sPicField = "";

			for ($j = 1; $j <= 15; $j ++)
			{
				$sPicture = $objDb->getField($i, "picture{$j}");

				if ($sPicture != "" && @file_exists($sBaseDir.SAFETY_AUDITD_DIR.$sPicture))
					$sPictures["picture{$j}"] = $sPicture;

				else if ($sPicField == "")
					$sPicField = "picture{$j}";
			}
?>
				<h3><?= ($i + 1) ?>. <?= $sQuestion ?></h3>

			      <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
				    <tr bgcolor="#eaeaea">
				      <td width="55%"><b>Requirements</b></td>
				      <td width="12%" align="center"><b>Performance</b></td>
				      <td width="33%"><b>Comments / Pictures</b></td>
				    </tr>

				    <tr valign="top">
				      <td><?= nl2br($sDetails) ?></td>

				      <td align="center">
					    <input type="hidden" name="Question[]" value="<?= $iQuestion ?>" />

					    <select name="Rating<?= $iQuestion ?>">
			              <option value="" style="background:#e9e1c5;">N/A</option>
			              <option value="1"<?= (($iRating == 1) ? " selected" : "") ?> style="background:#00ff00;">80%</option>
			              <option value="2"<?= (($iRating == 2) ? " selected" : "") ?> style="background:#99cc00;">61-79%</option>
			              <option value="3"<?= (($iRating == 3) ? " selected" : "") ?> style="background:#ff9900;">41-60%</option>
			              <option value="4"<?= (($iRating == 4) ? " selected" : "") ?> style="background:#ff0000;">0-40%</option>
					    </select>
				      </td>

				      <td>
				        <textarea name="Comments<?= $iQuestion ?>" rows="3" style="width:98%;"><?= $sComments ?></textarea>
<?
			if ($sPicField != "")
			{
?>
				        <br />
				        <br />
				        <input type="hidden" name="PicField<?= $iQuestion ?>" value="<?= $sPicField ?>" />
				        <input type="file" name="Picture<?= $iQuestion ?>" value="" size="25" /><br />
<?
			}
?>
				      </td>
				    </tr>

<?
			if (count($sPictures) > 0)
			{
?>
				    <tr>
				      <td colspan="3">
				        <div style="position:relative;">
<?
				foreach ($sPictures as $sField => $sPicture)
				{
?>
                          <div style="float:left; margin:10px 10px 0px 0px; text-align:center;"><a href="<?= (SAFETY_AUDITD_DIR.$sPicture) ?>" class="lightview"><img src="<?= (SAFETY_AUDITD_DIR.$sPicture) ?>" width="100" height="80" alt="" title="" /></a><br />[ <a href="crc/delete-safety-picture.php?Id=<?= $Id ?>&Question=<?= $iQuestion ?>&Field=<?= $sField ?>">Delete</a> ]</div>
<?
				}
?>
				        </div>
				      </td>
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
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='crc/<?= (($Step == 0) ? 'safety-audits.php' : ('edit-safety-audit.php?Id='.$Id.'&Step='.($Step - 1))) ?>';" />
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