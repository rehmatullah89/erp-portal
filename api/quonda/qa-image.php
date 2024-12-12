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

	$AuditCode  = IO::strValue("AuditCode");
	$Picture    = IO::strValue("Picture");

	$iAuditCode = intval(substr($AuditCode, 1));
	$sAuditDate = getDbValue("audit_date", "tbl_qa_reports", "id='$iAuditCode'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
</head>

<body style="background:#ffffff url('api/images/bg.jpg') 0px 50px; width:102%;">
<?
	@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	$sPicture = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$Picture);

	$sName  = @strtoupper($sPicture);
	$sName  = @basename($sName, ".JPG");
	$sName  = @basename($sName, ".GIF");
	$sName  = @basename($sName, ".PNG");
	$sName  = @basename($sName, ".BMP");


	@list($sAuditCode, $sDefectCode, $sAreaCode) = @explode("_", $sName);


	$sSQL = "SELECT report_id,
					(SELECT vendor FROM tbl_vendors WHERE id=qa.vendor_id) AS _Vendor,
					(SELECT order_no FROM tbl_po WHERE id=qa.po_id) AS _PO,
					(SELECT style FROM tbl_styles WHERE id=(SELECT style_id FROM tbl_po_colors WHERE po_id=qa.po_id LIMIT 1)) AS _Style
			 FROM tbl_qa_reports qa
			 WHERE id='$iAuditCode'";

	if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
	{
		$iReportId = $objDb->getField(0, 0);

		$sTitle  = $objDb->getField(0, 1);
		$sTitle .= (" &raquo; ".$objDb->getField(0, 2));
		$sTitle .= (" &raquo; ".$objDb->getField(0, 3));
		$sTitle .= "<br />";

		$sSQL = "SELECT defect,
						(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
				 FROM tbl_defect_codes dc
				 WHERE code='$sDefectCode' AND report_id='$iReportId'";

		if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
		{
			$sDefect = $objDb->getField(0, 0);

			$sTitle .= $objDb->getField(0, 1);


			if ($iReportId != 4 && $iReportId != 6)
			{
				$sSQL = "SELECT area FROM tbl_defect_areas WHERE id='$sAreaCode'";

				if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
					$sTitle .= (" &raquo; ".$objDb->getField(0, 0));
			}

			$sTitle .= (" &raquo; ".$sDefect);
		}
	}

	else
		$sTitle = "# Invalid File Name #";
?>
  <h1 style="margin:0px; background:#a60800; font-size:19px; color:#ffffff; height:auto; font-weight:normal; line-height:30px; padding:10px; text-transform:none;"><?= $sTitle ?></h1>
  <div style="border:solid 2px #ffffff;"><img src="<?= SITE_URL.str_replace('../', '', $sPicture) ?>" width="100%" alt="" title="" /></div>
  <div style="padding:10px 0px 10px 0px;" align="center"><input type="button" value=" Back " class="button" style="font-size:16px; padding:6px 20px 10px 20px;" onclick="document.location='<?= (SITE_URL."api/quonda/qa-report.php?AuditCode=".$AuditCode) ?>';" /></div>
</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>