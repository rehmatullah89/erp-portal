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


	$sSQL = "SELECT * FROM tbl_production_audits WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$Vendor         = $objDb->getField(0, "vendor_id");
		$Auditors       = $objDb->getField(0, "auditors");
		$AuditDate      = $objDb->getField(0, "audit_date");
		$AuditTime      = $objDb->getField(0, "audit_time");
		$Representative = $objDb->getField(0, "representative");


		$Auditors = @explode(",", $Auditors);

		@list($AuditHours, $AuditMinutes) = @explode(":", $AuditTime);
	}

	else
		redirect($_SERVER['HTTP_REFERER'], "ERROR");


	if (getDbValue("title", "tbl_production_categories", "id='$Step'") == "")
		$Step = 0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/edit-production-audit.js"></script>
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
			   <h1><img src="images/h1/crc/production-audits.jpg" width="267" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="crc/update-production-audit.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Step" value="<?= $Step ?>" />

<?
	if ($Step == 0)
	{
		$sVendorsList  = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
		$sAuditorsList = getList("tbl_users", "id", "name", "designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (5,15,41))");
?>
				<h2>Edit Production Audit</h2>

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
				</table>

<?
	}

	else if ($Step > 0)
	{
?>
				<h2><?= $Step ?>. <?= getDbValue("title", "tbl_production_categories", "id='$Step'") ?></h2>
<?
		$sSQL = "SELECT pad.*, pq.*
				 FROM tbl_production_audit_details pad, tbl_production_questions pq
				 WHERE pad.audit_id='$Id' AND pad.question_id=pq.id AND pq.category_id='$Step'
				 ORDER BY pq.position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iQuestion     = $objDb->getField($i, 'pad.id');
			$sQuestion     = $objDb->getField($i, 'pq.question');
			$iQuestionType = $objDb->getField($i, 'pq.question_type');
			$iNoOfOptions  = $objDb->getField($i, 'pq.no_of_options');
			$sOptions      = $objDb->getField($i, 'pq.options');
			$iWeightages   = $objDb->getField($i, 'pq.weightage');
			$iWeightage    = $objDb->getField($i, 'pad.weightage');
			$sDetails      = $objDb->getField($i, 'pad.details');


			$sOptions    = @explode("|-|", $sOptions);
			$iWeightages = @explode("|-|", $iWeightages);
			$sDetails    = @explode("|-|", $sDetails);
?>
				<h3><?= ($i + 1) ?>. <?= $sQuestion ?></h3>

				<input type="hidden" name="Question[]" value="<?= $iQuestion ?>" />

			      <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
			        <tr>
			          <td width="50">Score</td>

<?
			if ($iQuestionType == 1)
			{
?>
			          <td>
					    <select name="Weightage<?= $iQuestion ?>">
					      <option value=""></option>
<?
				for ($j = 0; $j < $iNoOfOptions; $j ++)
				{
?>
					      <option value="<?= $iWeightages[$j] ?>"<?= (($iWeightages[$j] == $iWeightage) ? " selected" : "") ?>><?= $sOptions[$j] ?></option>
<?
				}
?>
					    </select>
			          </td>
<?
			}

			else
			{
				for ($j = 0; $j < $iNoOfOptions; $j ++)
				{
?>
			          <td width="150" align="center">
			            <?= $sOptions[$j] ?> :

			            <select name="Weightage<?= $iQuestion ?>[]">
			              <option value=""></option>
			              <option value="1"<?= (($sDetails[$j] == 1) ? " selected" : "") ?>>1</option>
			              <option value="2"<?= (($sDetails[$j] == 2) ? " selected" : "") ?>>2</option>
			              <option value="3"<?= (($sDetails[$j] == 3) ? " selected" : "") ?>>3</option>
			              <option value="4"<?= (($sDetails[$j] == 4) ? " selected" : "") ?>>4</option>
			              <option value="5"<?= (($sDetails[$j] == 5) ? " selected" : "") ?>>5</option>
			            </select>
			          </td>
<?
				}
?>
			          <td></td>
<?
			}
?>
			        </tr>
			      </table>
<?
		}
	}
?>

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm('<?= $Step ?>');" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='crc/<?= (($Step == 0) ? 'production-audits.php' : ('edit-production-audit.php?Id='.$Id.'&Step='.($Step - 1))) ?>';" />
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