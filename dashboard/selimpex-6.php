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
  <script type="text/javascript" src="scripts/glider.js"></script>
</head>

<body style="margin:0px; padding:20px; background:#ffffff;">
<?
	$sPictures = array( );

	$sSQL = "SELECT DISTINCT(qa.audit_code), qa.audit_date
			 FROM tbl_qa_reports qa
			 WHERE qa.audit_type='B' AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') AND FIND_IN_SET(qa.report_id, '$sQmipReports') ";

	if ($AuditStage != "")
		$sSQL .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sSQL .= " AND qa.audit_stage!='' ";

	if ($Vendor > 0)
		$sSQL .= " AND qa.vendor_id='$Vendor' ";


	$sSQL .= " ORDER BY RAND( )";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditCode = $objDb->getField($i, 0);
		$sAuditDate = $objDb->getField($i, 1);

		@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

		$sAuditPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*");
   		$sAuditPictures = @array_map("strtoupper", $sAuditPictures);
   		$sAuditPictures = @array_unique($sAuditPictures);

		$sTemp = array( );

		foreach ($sAuditPictures as $sPicture)
			$sTemp[] = $sPicture;

		$sAuditPictures = $sTemp;


		for ($j = 0; $j < count($sAuditPictures); $j ++)
		{
			$sName  = @strtoupper($sAuditPictures[$j]);
			$sName  = @basename($sName, ".JPG");
			$sName  = @basename($sName, ".GIF");
			$sName  = @basename($sName, ".PNG");
			$sName  = @basename($sName, ".BMP");
			$sParts = @explode("_", $sName);

			$sPictures[] = $sAuditPictures[$j];
		}
	}


	$iPictures = count($sPictures);


	if ($iPictures == 0)
	{
?>
				<div style="padding:10px;">
				  No Defect Image Found!<br />
				</div>
<?
	}

	else
	{
?>
				<div style="padding:10px; position:relative;" id="Glider">

				<div class="scroller" style="width:1300px;">
				<div class="content">
<?
		$iIndex = 0;

		for ($iSlide = 1; $iSlide <= 3; $iSlide ++)
		{
?>
				<div class="section" id="section<?= $iSlide ?>" style="width:1300px;">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
			for ($i = 0; $i < 4; $i ++)
			{
?>
	    			<tr valign="top">
<?
				for ($j = 0; $j < 6; $j ++)
				{
					if ($iIndex < $iPictures)
					{
						$sName  = @strtoupper($sPictures[$iIndex]);
						$sName  = @basename($sName, ".JPG");
						$sName  = @basename($sName, ".GIF");
						$sName  = @basename($sName, ".PNG");
						$sName  = @basename($sName, ".BMP");
						$sParts = @explode("_", $sName);

						$sAuditCode   = $sParts[0];
						$sDefectCode  = $sParts[1];
						$sAreaCode    = $sParts[2];
						$sDefectTitle = "";


						$sSQL = "SELECT report_id,
										(SELECT vendor FROM tbl_vendors WHERE id=qa.vendor_id) AS _Vendor,
										(SELECT order_no FROM tbl_po WHERE id=qa.po_id) AS _PO,
										(SELECT style FROM tbl_styles WHERE id=(SELECT style_id FROM tbl_po_colors WHERE po_id=qa.po_id LIMIT 1)) AS _Style,
										(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line
								 FROM tbl_qa_reports qa
								 WHERE audit_code='$sAuditCode'";

						if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
						{
							$iReportId = $objDb->getField(0, 0);

							$sTitle  = $objDb->getField(0, 1);
							$sTitle .= (" <b>»</b> ".$objDb->getField(0, 2));
							$sTitle .= (" <b>»</b> ".$objDb->getField(0, 3));
							$sTitle .= (" <b>»</b> ".$objDb->getField(0, 4));


							$sSQL = "SELECT defect,
											(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
									 FROM tbl_defect_codes dc
									 WHERE code='$sDefectCode' AND report_id='$iReportId'";

							if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
							{
								$sDefect = $objDb->getField(0, 0);

								$sDefectTitle = $objDb->getField(0, 0);
								$sTitle      .= (" <b>»</b> ".$objDb->getField(0, 1));

								if ($iReportId != 4 && $iReportId != 6)
								{
									$sSQL = "SELECT area FROM tbl_defect_areas WHERE id='$sAreaCode'";

									if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
										$sTitle .= (" <b>»</b> ".$objDb->getField(0, 0));
								}

								$sTitle .= (" <b>»</b> ".$sDefect);
							}
						}

						else
							$sTitle = "<b>### Invalid File Name ###</b>";
?>
					  <td width="16.667%" align="center">
						<div class="qaPic" style="width:174px; height:147px;">
						  <div><a href="<?= $sPictures[$iIndex] ?>" class="lightview" rel="gallery[defects]" title="<?= $sTitle ?> :: :: topclose: true"><img src="<?= $sPictures[$iIndex] ?>" alt="" title="" style="width:170px; height:142px;" /></a></div>
						</div>

						<div style="overflow:hidden; line-height:20px; height:20px; text-align:center;"><?= $sDefectTitle ?></div>
					  </td>
<?
						$iIndex ++;
					}

					else
					{
?>
	      			  <td width="25%"></td>
<?
					}
				}
?>
					</tr>
<?
				if ($iIndex >= $iPictures)
					break;


				if ($i < 3)
				{
?>
					<tr>
					  <td colspan="6" height="25"></td>
					</tr>
<?
				}
			}
?>
	  			  </table>
	  			</div>


<?
			if ($iIndex >= $iPictures)
				break;
		}
?>
				</div>
				</div>

				</div>

				<script type="text/javascript">
				<!--
					var objGlider = new Glider('Glider', { frequency:8, autoGlide:true });
				-->
				</script>
<?
	}
?>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>