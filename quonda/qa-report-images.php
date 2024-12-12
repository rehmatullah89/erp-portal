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
  <script type="text/javascript" src="scripts/quonda/qa-report-images.js"></script>
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
			    <h1>qa report images</h1>

			    <div class="tblSheet">
<?
	$sSQL = "SELECT id, report_id, audit_date, specs_sheet_1, specs_sheet_2, specs_sheet_3, specs_sheet_4, specs_sheet_5, specs_sheet_6, specs_sheet_7, specs_sheet_8, specs_sheet_9, specs_sheet_10 
	         FROM tbl_qa_reports
			 WHERE audit_code='$AuditCode'";
	$objDb->query($sSQL);

	$iAudit     = $objDb->getField(0, "id");
	$iReport    = $objDb->getField(0, "report_id");
	$sAuditDate = $objDb->getField(0, "audit_date");



    @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
	
	
	$sSpecsSheets = array( );
	$sSpecsDir    = ($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/");
	$sImagesDir   = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");

	for ($i = 1; $i <= 10; $i ++)
	{
		$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$i}");

		if ($sSpecsSheet != "")
		{
			if (@file_exists($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet))
				$sSpecsSheets[] = ($sBaseDir.SPECS_SHEETS_DIR.$sSpecsSheet);
			
			else if (@file_exists($sSpecsDir.$sSpecsSheet))
				$sSpecsSheets[] = ($sSpecsDir.$sSpecsSheet);
		}
	}
	


	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($AuditCode, 1)."_*.*");


	if (count($sPictures) == 0)
	{
?>
				  <div class="noRecord">No Defect Image Found!</div>
				  <br />
<?
	}

	else
	{
		for ($i = 0; $i < count($sPictures); $i ++)
		{
			$sFile    = $sPictures[$i];
			$sPicture = @basename($sFile);
			
			if (getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$iAudit' AND BINARY picture='$sPicture'") > 0)
				continue;
			
			if (getDbValue("COUNT(1)", "tbl_qa_report_images", "audit_id='$iAudit' AND BINARY image='$sPicture'") > 0)
				continue;
			
			
			$bFound = false;
			
			for ($j = 0; $j < $i; $j ++)
			{
				$sNextFile = $sPictures[$j];
				
				if (strtoupper($sFile) == strtoupper($sNextFile))
				{
					$bFound = true;
					break;
				}
			}
			
			
			if ($bFound == true)
				@unlink($sFile);
			
			else
			{
				if (getDbValue("COUNT(1)", "tbl_qa_report_defects", "audit_id='$iAudit' AND picture LIKE '$sPicture'") > 0)
				{
					$sSQL = "UPDATE tbl_qa_report_defects SET picture='$sPicture' WHERE audit_id='$iAudit' AND picture LIKE '$sPicture'";
					$objDb->execute($sSQL);
				}
				
				else if (getDbValue("COUNT(1)", "tbl_qa_report_images", "audit_id='$iAudit' AND image LIKE '$sPicture'") > 0)
				{
					$sSQL = "UPDATE tbl_qa_report_images SET image='$sPicture' WHERE audit_id='$iAudit' AND image LIKE '$sPicture'";
					$objDb->execute($sSQL);
				}					
				
				else
				{
					$sType = "M";
					
					if (stripos($sPicture, "_LAB_") !== FALSE)
						$sType = "L";
					
					if (stripos($sPicture, "_PACK_") !== FALSE)
						$sType = "P";
					
					$sSQL  = "INSERT INTO tbl_qa_report_images SET audit_id  = '$iAudit',
																   image     = '$sPicture',
																   type      = '$sType',
																   date_time = NOW( )";
					$objDb->execute($sSQL);
				}
			}
		}

			
		$sDefectImages  = getList("tbl_qa_report_defects", "id", "picture", "audit_id='$iAudit'");
		$sPackingImages = getList("tbl_qa_report_images", "id", "image", "audit_id='$iAudit' AND `type`='P'");
		$sMiscImages    = getList("tbl_qa_report_images", "id", "image", "audit_id='$iAudit' AND `type`='M'");                   

		
                if(count($sDefectImages)>0)
                {
?>
				  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="qaImages">
                    <tr><td colspan="5"><h2>Defect Images</h2></td></tr>
					
					<tr>
<?
                    $j = 0;
					
                    foreach ($sDefectImages as $iImageId => $sImageName)
                    {
                        if(!empty($sImageName) && @file_exists($sImagesDir.@basename($sImageName)))
                        {
                            if($j > 0 && ($j % 5) == 0)
                            {
?>
                            </tr><tr>             
<?
                            }
?>
                                <td width="20%" align="center">
<?
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
								$sTitle .= (" <b>-></b> ".$objDb->getField(0, "_PO"));
								$sTitle .= (" <b>-></b> ".$objDb->getField(0, "_Style"));
								$sTitle .= (" <b>-></b> ".$objDb->getField(0, "_Line"));

								$sSQL = "SELECT defect,
												(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
										 FROM tbl_defect_codes dc
										 WHERE code='$sDefectCode' AND report_id='$iReportId'";

								if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
								{
									$sDefect = $objDb->getField(0, 0);

									$sTitle .= (" <b>-></b> ".$objDb->getField(0, 1));

									if ($iReportId != 4 && $iReportId != 6 && intval($sAreaCode) > 0)
									{
										$sSQL = "SELECT area FROM tbl_defect_areas WHERE id='$sAreaCode'";

										if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
											$sTitle .= (" <b>-></b> ".$objDb->getField(0, 0));

										else
											$bFlag  = false;
									}

									$sTitle .= (" <b>-></b> ".$sDefect);
								}

								else
									$bFlag  = false;
							}

							else
							{
								$sTitle = "<b>### Invalid File Name ###</b>";
								$bFlag  = false;
							}
?>
									<div class="qaPic" style="padding: 5px;">
										<div><a href="<?= QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sImageName) ?>" class="lightview" rel="gallery[defects]" title="<?= utf8_encode($sTitle) ?> :: :: topclose: true"><img src="<?= QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sImageName) ?>" alt="" title="" /></a></div>
									</div>
									<span id="Pic<?= $i ?>" name="Pic<?= $i ?>" style="display:block; overflow:hidden; <?= (($bFlag == true) ? '' : ' color:#ff0000;') ?>"><?= @basename($sImageName) ?></span>
									<br />
                                </td>               
<?
							$j ++;
						}
                    }
?>
                   </tr>
				 </table>  
<? 
                }
				
                
                ///////////////// Packing Images //////////
                if(count($sPackingImages)>0)
                {
?>
				  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="qaImages">
                    <tr><td colspan="5"><h2>Packing Images</h2></td></tr><tr>                   
<?
                    $j = 0;
					
                    foreach ($sPackingImages as $iImageId => $sImageName)
                    {
                        if(!empty($sImageName))
                        {
                            
                            if(!@in_array($iReport, array(6,7,10,11,19,20,23,25,26,28,29,30,31,32,33,35,36,37,38)) && strtotime($sAuditDate) <= strtotime('2017-02-15') && getDbValue("COUNT(1)", "tbl_qa_report_images", "audit_id='$iAudit' AND `type`='P'") == 0)
                                $iImageId = 0;
                                    
                            if($j > 0 && ($j % 5) == 0)
                            {
?>
                            </tr><tr>             
<?
                            }
?>
                                <td width="20%" align="center">
						<div class="qaPic" style="padding: 5px;">
						  <div><a href="<?= QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sImageName) ?>" class="lightview" rel="gallery[defects]" title="<?= @basename($sImageName) ?> :: :: topclose: true"><img src="<?= QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sImageName) ?>" alt="" title="" /></a></div>
						</div>

						<span id="Pic<?= $i ?>" name="Pic<?= $i ?>" style="display:block; overflow:hidden; <?= (($bFlag == true) ? '' : ' color:#ff0000;') ?>"><?= str_ireplace(array("{$AuditCode}_PACK_", "{$AuditCode}_001_"), "", @basename(strtoupper($sImageName))) ?></span>

						<div>
<?
							if ($sUserRights['Delete'] == "Y")
							{
?>
				          <a id="Delete<?= $i ?>" href="quonda/delete-qa-image.php?File=<?= @basename($sImageName) ?>&AuditDate=<?= $sAuditDate ?>&AuditId=<?=$iAudit?>&ReportId=<?=$iReport?>&ImageId=<?=$iImageId?>&Referer=<?= urlencode($Referer) ?>" onclick="return confirm('Are you SURE, You want to Delete this Image?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a><br />
						  <br />
<?
							}
?>
						</div>
                                </td>               
<?
							$j ++;
                        }
                    }
?>
                   </tr>
                </table>   
<?
                }
				
				
				
                ///////////////// Misc Images //////////
                if(count($sMiscImages)>0)
                {
?>
				  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="qaImages">
                    <tr><td colspan="5"><h2>Miscellaneous Images</h2></td></tr><tr>                   
<?
                    $j=0;
					
                    foreach ($sMiscImages as $iImageId => $sImageName)
                    {
                        if(!empty(trim($sImageName)))
                        {
                            if(!@in_array($iReport, array(6,7,10,11,19,20,23,25,26,28,29,30,31,32,33,35,36,37,38)) && strtotime($sAuditDate) <= strtotime('2017-02-15') && getDbValue("COUNT(1)", "tbl_qa_report_images", "audit_id='$iAudit' AND `type`='M'") == 0)
                                $iImageId = 0;
                            
                            if($j > 0 && ($j % 5) == 0)
                            {
?>
                            </tr><tr>             
<?
                            }
?>
					<td width="20%" align="center">
						<div class="qaPic" style="padding: 5px;">
						  <div><a href="<?= QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sImageName) ?>" class="lightview" rel="gallery[defects]" title="<?= @basename($sImageName) ?> :: :: topclose: true"><img src="<?= QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sImageName) ?>" alt="" title="" /></a></div>
						</div>

						<span id="Pic<?= $i ?>" name="Pic<?= $i ?>" style="display:block; overflow:hidden; <?= (($bFlag == true) ? '' : ' color:#ff0000;') ?>"><?= str_ireplace(array("{$AuditCode}_MISC_", "{$AuditCode}_001_"), "", @basename(strtoupper($sImageName))) ?></span>

						<div>
<?
							if ($sUserRights['Delete'] == "Y")
							{
?>
				          <a id="Delete<?= $i ?>" href="quonda/delete-qa-image.php?File=<?= @basename($sImageName) ?>&AuditDate=<?= $sAuditDate ?>&AuditId=<?=$iAudit?>&ReportId=<?=$iReport?>&ImageId=<?=$iImageId?>&Referer=<?= urlencode($Referer) ?>" onclick="return confirm('Are you SURE, You want to Delete this Image?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a><br />
						  <br />
<?
							}
?>
						</div>
                                </td>               
<?
							$j ++;
                        }
                    }
?>
                   </tr>
                  </table> 
<?
                }
                /////////////end misc images //////////
	}
	
	
	
	
	
	$sPictures = @glob($sBaseDir.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($AuditCode, 1)."_*.*");

	for ($i = 0; $i < count($sPictures); $i ++)
	{
		$sFile    = $sPictures[$i];
		$sPicture = @basename($sFile);
		
		if (@in_array($sFile, $sSpecsSheets))
			continue;
		
		
		$bFound = false;
		
		for ($j = 0; $j < $i; $j ++)
		{
			$sNextFile = $sPictures[$j];
			
			if (strtoupper($sFile) == strtoupper($sNextFile))
			{
				$bFound = true;
				break;
			}
		}
		
		
		if ($bFound == true)
			@unlink($sFile);
		
		else
		{
			$iEmpty = 0;
			$bFound = false;

			
			$sSQL = "SELECT * FROM tbl_qa_reports WHERE id='$iAudit'";
			$objDb->query($sSQL);
			
			for ($j = 1; $j <= 10; $j ++)
			{
				$sSpecsSheet = $objDb->getField(0, "specs_sheet_{$j}");
				
				if (strtoupper($sSpecsSheet) == strtoupper($sPicture))
				{
					$sSQL = "UPDATE tbl_qa_reports SET specs_sheet_{$j}='$sPicture' WHERE id='$iAudit'";
					$objDb2->execute($sSQL);
					
					$sSpecsSheets[] = $sFile;
					
					$bFound = true;
					
					break;
				}

				if ($sSpecsSheet == "" && $iEmpty == 0)
					$iEmpty = $j;
			}
			
			
			if ($bFound == false)
			{
				if ($iEmpty > 0)
				{
					$sSQL = "UPDATE tbl_qa_reports SET specs_sheet_{$iEmpty}='$sPicture' WHERE id='$iAudit'";
					$objDb->execute($sSQL);
					
					$sSpecsSheets[] = $sFile;
				}
				
				else
				{
					$sSQL  = "INSERT INTO tbl_qa_report_images SET audit_id  = '$iAudit',
																   image     = '$sPicture',
																   type      = 'L',
																   date_time = NOW( )";
					$objDb->execute($sSQL);
				}
			}	
		}
	}

		
	$sLabImages = getList("tbl_qa_report_images", "id", "image", "audit_id='$iAudit' AND `type`='L'");

	foreach ($sLabImages as $sImage)
	{
		if (@file_exists($sSpecsDir.$sImage) && !@in_array(($sSpecsDir.$sImage), $sSpecsSheets))
			$sSpecsSheets[] = ($sSpecsDir.$sImage);
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
						  <div><a href="<?= $sSpecsSheets[$i] ?>" class="lightview" rel="gallery[defects]" title="Specs Sheet :: :: topclose: true"><img src="<?= $sSpecsSheets[$i] ?>" alt="" title="" /></a></div>
						</div>

                        <span><?= @basename($sSpecsSheets[$i]) ?></span><br />
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

                <form name="frmData" id="frmData" method="post" action="quonda/save-qa-images.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
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
					<td width="10%"><b>Audit Code</b></td>
					<td width="5%" align="center">:</td>
                                        <td width="25%"><b><?= $AuditCode ?></b></td>
                                        <td width="60%"><b>Image Type</b></td>
				  </tr>

				  <tr>
				    <td>Image # 1</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image1" size="40" class="file" /></td>
                                    <td><select name="Type1"><option value="M">Miscellaneous</option><option value="P">Packing</option></select></td>
				  </tr>

				  <tr>
				    <td>Image # 2</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image2" size="40" class="file" /></td>
                                    <td><select name="Type2"><option value="M">Miscellaneous</option><option value="P">Packing</option></select></td>
				  </tr>

				  <tr>
				    <td>Image # 3</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image3" size="40" class="file" /></td>
                                    <td><select name="Type3"><option value="M">Miscellaneous</option><option value="P">Packing</option></select></td>
				  </tr>

				  <tr>
				    <td>Image # 4</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image4" size="40" class="file" /></td>
                                    <td><select name="Type4"><option value="M">Miscellaneous</option><option value="P">Packing</option></select></td>
				  </tr>

				  <tr>
				    <td>Image # 5</td>
				    <td align="center">:</td>
				    <td><input type="file" name="Image5" size="40" class="file" /></td>
                                    <td><select name="Type5"><option value="M">Miscellaneous</option><option value="P">Packing</option></select></td>
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