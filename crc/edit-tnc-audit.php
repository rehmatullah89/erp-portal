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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
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

	$Id   = IO::intValue('Id');
	$Step = IO::intValue("Step");

	$sSQL = "SELECT * FROM tbl_tnc_audits WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$Vendor    = $objDb->getField(0, "vendor_id");
		$Auditors  = $objDb->getField(0, "auditors");
		$AuditDate = $objDb->getField(0, "audit_date");

		$Auditors  = @explode(",", $Auditors);
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
  <script type="text/javascript" src="scripts/crc/edit-tnc-audit.js"></script>
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
			   <h1><img src="images/h1/crc/tnc-audits.jpg" width="153" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="crc/update-tnc-audit.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Step" value="<?= $Step ?>" />

<?
	if ($Step == 0)
	{
		$sVendorsList  = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
		$sAuditorsList = getList("tbl_users", "id", "name", "designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (5,15,41))");
?>
				<h2>Edit Audit</h2>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="70">Vendor</td>
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
					<td>Auditor(s)</td>
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
					<td>Audit Date</td>
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
				</table>

				<br />
<?
	}


	else if ($Step > 0)
	{
?>
				<h2 style="margin-bottom:0px;"><?= $Step ?>. <?= getDbValue("section", "tbl_tnc_sections", "id='$Step'") ?></h2>
<?

		$sCategoryList = getList("tbl_tnc_categories", "id", "category", "section_id='$Step'");
		$sClass        = array("evenRow", "oddRow");

		foreach ($sCategoryList as $iCategory => $sCategory)
		{
?>
				<h3><?= $sCategory ?></h3>
<?
                    ////////////// Temporary Start ///////////
                    /*    $sSQL0 = "SELECT id FROM tbl_tnc_points where section_id='$Step' AND category_id='$iCategory'";
						$objDb->query($sSQL0);
                        $iCount1 = $objDb->getCount( );
                        
                        if($iCount1 >0){
                            for ($i = 0; $i < $iCount1; $i ++)
                            {
                                $iPoint   = $objDb->getField($i, 'id');
                                $sSQL2 = "SELECT id FROM tbl_tnc_audit_details where point_id='$iPoint' and audit_id='$Id'";
                                $objDb2->query($sSQL2);
                                $iCount2 = $objDb2->getCount( );
                                if($iCount2 == 0){
                                    $sSQL3  = "INSERT INTO tbl_tnc_audit_details (audit_id, point_id, score, remarks) Values ('$Id', '$iPoint', '-1', '')";
                                    $objDb3->execute($sSQL3);
                                }
                            }
                        }*/
                    ////////////// Temporary End ///////////
                        
			$sSQL = "SELECT tad.id, tad.score, tad.not_applicable, tad.remarks, tp.point
					 FROM tbl_tnc_audit_details tad, tbl_tnc_points tp
					 WHERE tad.point_id=tp.id AND tad.audit_id='$Id' AND tp.category_id='$iCategory'
					 ORDER BY tp.position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
?>
			    <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
			      <tr bgcolor="#eaeaea">
					<td width="5%"><b>#</b></td>
					<td width="52%"><b>Point</b></td>
					<td width="8%" align="center"><b>Score</b></td>
					<td width="35%"><b>Remarks</b></td>
			      </tr>

<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPoint   = $objDb->getField($i, 'id');
				$sPoint   = $objDb->getField($i, 'point');
				$iScore   = $objDb->getField($i, 'score');
				$sRemarks = $objDb->getField($i, 'remarks');
?>

				  <tr valign="top" class="<?= $sClass[($i % 2)] ?>">
				    <td align="center"><?= ($i + 1) ?></td>

				    <td>
					  <input type="hidden" name="Point[]" value="<?= $iPoint ?>" />
					  <?= $sPoint ?>
				    </td>

				    <td align="center">
					  <select name="Score<?= $iPoint ?>">
					    <option value="-1">N/A</option>
					    <option value="1"<?= (($iScore == 1) ? " selected" : "") ?> style="background:#00ff00;">1</option>
					    <option value="0"<?= (($iScore == 0) ? " selected" : "") ?> style="background:#ff0000;">0</option>
					  </select>
				    </td>

				    <td><textarea name="Remarks<?= $iPoint ?>" rows="4" style="width:98%; height:100%;"><?= $sRemarks ?></textarea></td>
				  </tr>
<?
			}
?>
	            </table>
<?
		}
	}
?>
				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm('<?= $Step ?>');" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='crc/<?= (($Step == 0) ? 'tnc-audits.php' : ('edit-tnc-audit.php?Id='.$Id.'&Step='.($Step - 1))) ?>';" />
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