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

	$AuditCode = IO::strValue('AuditCode');
	$Referer   = urldecode(IO::strValue("Referer"));

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/qmip/qa-report-images.js"></script>
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
			    <h1>QA Report Images</h1>

			    <div class="tblSheet">
			      <br />

<?
	$sSQL = "SELECT audit_date, specs_sheet_1, specs_sheet_2, specs_sheet_3, specs_sheet_4, specs_sheet_5, specs_sheet_6, specs_sheet_7, specs_sheet_8, specs_sheet_9, specs_sheet_10 FROM tbl_qa_reports WHERE audit_code='$AuditCode'";
	$objDb->query($sSQL);

	$sAuditDate    = $objDb->getField(0, "audit_date");
	$sSpecsSheet1  = $objDb->getField(0, 'specs_sheet_1');
	$sSpecsSheet2  = $objDb->getField(0, 'specs_sheet_2');
	$sSpecsSheet3  = $objDb->getField(0, 'specs_sheet_3');
	$sSpecsSheet4  = $objDb->getField(0, 'specs_sheet_4');
	$sSpecsSheet5  = $objDb->getField(0, 'specs_sheet_5');
	$sSpecsSheet6  = $objDb->getField(0, 'specs_sheet_6');
	$sSpecsSheet7  = $objDb->getField(0, 'specs_sheet_7');
	$sSpecsSheet8  = $objDb->getField(0, 'specs_sheet_8');
	$sSpecsSheet9  = $objDb->getField(0, 'specs_sheet_9');
	$sSpecsSheet10 = $objDb->getField(0, 'specs_sheet_10');


	$sSpecsSheets = array( );

	if ($sSpecsSheet1 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet1))
		$sSpecsSheets[] = $sSpecsSheet1;

	if ($sSpecsSheet2 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet2))
		$sSpecsSheets[] = $sSpecsSheet2;

	if ($sSpecsSheet3 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet3))
		$sSpecsSheets[] = $sSpecsSheet3;

	if ($sSpecsSheet4 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet4))
		$sSpecsSheets[] = $sSpecsSheet4;

	if ($sSpecsSheet5 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet5))
		$sSpecsSheets[] = $sSpecsSheet5;

	if ($sSpecsSheet6 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet6))
		$sSpecsSheets[] = $sSpecsSheet6;

	if ($sSpecsSheet7 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet7))
		$sSpecsSheets[] = $sSpecsSheet7;

	if ($sSpecsSheet8 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet8))
		$sSpecsSheets[] = $sSpecsSheet8;

	if ($sSpecsSheet9 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet9))
		$sSpecsSheets[] = $sSpecsSheet9;

	if ($sSpecsSheet10 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet10))
		$sSpecsSheets[] = $sSpecsSheet10;



	@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);


	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($AuditCode, 1)."_*.*");
   	$sPictures = @array_map("strtoupper", $sPictures);
   	$sPictures = @array_unique($sPictures);

	if (count($sPictures) == 0)
	{
?>
				  <div class="noRecord">No Defect Image Found!</div>
				  <br />
<?
	}

	else
	{
?>
				  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="qaImages">
<?
		$sTemp = array( );

		foreach ($sPictures as $sPicture)
			$sTemp[] = $sPicture;

		$sPictures = $sTemp;


		for ($i = 0; $i < count($sPictures);)
		{
?>
	    			<tr valign="top">
<?
			for ($j = 0; $j < 5; $j ++, $i ++)
			{
				if ($i < count($sPictures))
				{
					$sName = @strtoupper($sPictures[$i]);
					$sName = @basename($sName, ".JPG");
					$sName = @basename($sName, ".GIF");
					$sName = @basename($sName, ".PNG");
					$sName = @basename($sName, ".BMP");

					if (@strpos($sName, " ") !== FALSE)
					{
						$sTitle = "<b>### Invalid File Name ###</b>";
						$bFlag  = false;
					}

					else if (@strpos($sName, "_PACK_") !== FALSE || @strpos($sName, "_001_") !== FALSE)
						$sTitle = "<b>Packing Image</b>";

					else if (@strpos($sName, "_LAB_") !== FALSE)
						$sTitle = "<b>Lab Report / Specs Sheet</b>";

					else if (@strpos($sName, "_MISC_") !== FALSE)
						$sTitle = "<b>Misc. Image</b>";

					else
					{
						$sParts = @explode("_", $sName);

						$sDefectCode = $sParts[1];
						$sAreaCode   = $sParts[2];
						$bFlag       = true;

						$sSQL = "SELECT report_id,
										(SELECT vendor FROM tbl_vendors WHERE id=qa.vendor_id) AS _Vendor,
										(SELECT order_no FROM tbl_po WHERE id=qa.po_id) AS _PO,
										(SELECT style FROM tbl_styles WHERE id=(SELECT style_id FROM tbl_po_colors WHERE po_id=qa.po_id LIMIT 1)) AS _Style,
										(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line
								 FROM tbl_qa_reports qa
								 WHERE audit_code='$AuditCode'";

						if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
						{
							$iReportId = $objDb->getField(0, "report_id");

							$sTitle  = $objDb->getField(0, "_Vendor");
							$sTitle .= (" <b>�</b> ".$objDb->getField(0, "_PO"));
							$sTitle .= (" <b>�</b> ".$objDb->getField(0, "_Style"));
							$sTitle .= (" <b>�</b> ".$objDb->getField(0, "_Line"));

							$sSQL = "SELECT defect,
											(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
									 FROM tbl_defect_codes dc
									 WHERE code='$sDefectCode' AND report_id='$iReportId'";

							if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
							{
								$sDefect = $objDb->getField(0, 0);

								$sTitle .= (" <b>�</b> ".$objDb->getField(0, 1));

								if ($iReportId != 4 && $iReportId != 6)
								{
									$sSQL = "SELECT area FROM tbl_defect_areas WHERE id='$sAreaCode'";

									if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
										$sTitle .= (" <b>�</b> ".$objDb->getField(0, 0));

									else
										$bFlag  = false;
								}

								$sTitle .= (" <b>�</b> ".$sDefect);
							}

							else
								$bFlag  = false;
						}

						else
						{
							$sTitle = "<b>### Invalid File Name ###</b>";
							$bFlag  = false;
						}
					}
?>
					  <td width="20%" align="center">
						<div class="qaPic">
						  <div><a href="<?= QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPictures[$i]) ?>" class="lightview" rel="gallery[defects]" title="<?= $sTitle ?> :: :: topclose: true"><img src="<?= QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPictures[$i]) ?>" alt="" title="" /></a></div>
						</div>

						<span id="Pic<?= $i ?>" name="Pic<?= $i ?>"<?= (($bFlag == true) ? '' : ' style="color:#ff0000;"') ?>><?= @strtoupper($sName) ?></span><br />
						<br />

						<div>
<?
					if ($sUserRights['Edit'] == "Y")
					{
?>
				          <a href="./" id="Edit<?= $i ?>" onclick="objEditor<?= $i ?>.enterEditMode( ); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				          &nbsp;
<?
					}

					if ($sUserRights['Delete'] == "Y")
					{
?>
				          <a id="Delete<?= $i ?>" href="qmip/delete-qa-image.php?File=<?= @basename($sPictures[$i]) ?>&AuditDate=<?= $sAuditDate ?>&Referer=<?= urlencode($Referer) ?>" onclick="return confirm('Are you SURE, You want to Delete this Image?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
					}
?>

						</div>

<?
					if ($sUserRights['Edit'] == "Y")
					{
?>
						<script type="text/javascript">
						<!--
						  var objEditor<?= $i ?> = new Ajax.InPlaceEditor('Pic<?= $i ?>', 'ajax/quonda/rename-qa-image.php', { cancelControl:'button', okText:' Ok ', cancelText:'Cancel', clickToEditText:'Click to Rename', highlightcolor:'#f1edcd', highlightendcolor:'#ffffff', callback:function(form, value) { return 'OldName=<?= $sName ?>&AuditDate=<?= $sAuditDate ?>&NewName=' + encodeURIComponent(value); }, onComplete:function( ) { $('Delete<?= $i ?>').href=('qmip/delete-qa-image.php?File=' + $('Pic<?= $i ?>').innerHTML + '.JPG&AuditDate=<?= $sAuditDate ?>&Referer=<?= urlencode($Referer) ?>'); }, onEnterEditMode:function(form, value) { $('Pic<?= $i ?>').focus( ); } });
						-->
						</script>
<?
					}
?>
					  </td>
<?
				}

				else
				{
?>
	      			  <td width="20%"></td>
<?
				}
		}
?>
					</tr>

					<tr>
					  <td colspan="5"><hr /></td>
					</tr>
<?
		}
?>
	  			  </table>
<?
	}


	if (count($sSpecsSheets) > 0)
	{
?>
				  <h2>Specs Sheets / Lab Reports</h2>

				  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="qaImages">
<?
		for ($i = 0; $i < count($sSpecsSheets);)
		{
?>
	    			<tr valign="top">
<?
			for ($j = 0; $j < 5; $j ++, $i ++)
			{
				if ($i < count($sSpecsSheets))
				{
?>
					  <td width="20%" align="center">
						<div class="qaPic">
						  <div><a href="<?= SPECS_SHEETS_DIR.$sSpecsSheets[$i] ?>" class="lightview" rel="gallery[defects]" title="Specs Sheet :: :: topclose: true"><img src="<?= SPECS_SHEETS_DIR.$sSpecsSheets[$i] ?>" alt="" title="" /></a></div>
						</div>

                        <span><?= $sSpecsSheets[$i] ?></span><br />
						<br />
					  </td>
<?
				}

				else
				{
?>
	      			  <td width="20%"></td>
<?
				}
			}
?>
					</tr>

					<tr>
					  <td colspan="5"><hr /></td>
					</tr>
<?
		}
?>
	  			  </table>
<?
	}
?>
				  &nbsp; [ <b><a href="<?= $Referer ?>">Back</a></b> ]<br />
				  <br />
			    </div>

			    <br />

                <form name="frmData" id="frmData" method="post" action="qmip/save-qa-images.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
	            <input type="hidden" name="AuditCode" value="<?= $AuditCode ?>" />
	            <input type="hidden" name="AuditDate" value="<?= $sAuditDate ?>" />
	            <input type="hidden" name="Referer" value="<?= $Referer ?>" />

				<h2>QA Report Images</h2>

<?
	if ($_SESSION['Message'] != "")
	{
?>
				<div class="error" style="padding:10px 10px 0px 10px;">
				  <?= $_SESSION['Message'] ?><br />
				</div>

				<hr />

<?
	}
?>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="70"><b>Audit Code</b></td>
					<td width="20" align="center">:</td>
					<td><b><?= $AuditCode ?></b></td>
				  </tr>

				  <tr>
				    <td>Image # 1</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image1" size="40" class="file" /></td>
				  </tr>

				  <tr>
				    <td>Image # 2</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image2" size="40" class="file" /></td>
				  </tr>

				  <tr>
				    <td>Image # 3</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image3" size="40" class="file" /></td>
				  </tr>

				  <tr>
				    <td>Image # 4</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image4" size="40" class="file" /></td>
				  </tr>

				  <tr>
				    <td>Image # 5</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image5" size="40" class="file" /></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save"  onclick="return validateForm( );" />
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
	$_SESSION['Message'] = "";

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>