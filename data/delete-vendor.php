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

	if ($sUserRights['Delete'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id = IO::strValue('Id');

	$sAlbumPic = "";
	$sPictures = array( );

	$objDb->execute("BEGIN");

	$sSQL = "DELETE FROM tbl_vendors WHERE id='$Id'";
	$bFlag = $objDb->execute($sSQL);
/*
	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_post_shipment_advice WHERE po_id IN (SELECT id FROM tbl_po WHERE vendor_id='$Id')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_post_shipment_detail WHERE po_id IN (SELECT id FROM tbl_po WHERE vendor_id='$Id')";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_post_shipment_quantities WHERE po_id IN (SELECT id FROM tbl_po WHERE vendor_id='$Id')";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_pre_shipment_advice WHERE po_id IN (SELECT id FROM tbl_po WHERE vendor_id='$Id')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_pre_shipment_detail WHERE po_id IN (SELECT id FROM tbl_po WHERE vendor_id='$Id')";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_pre_shipment_quantities WHERE po_id IN (SELECT id FROM tbl_po WHERE vendor_id='$Id')";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_sales_samples WHERE po_id IN (SELECT id FROM tbl_po WHERE vendor_id='$Id')";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_qa_report_defects WHERE audit_id IN (SELECT id FROM tbl_qa_reports WHERE vendor_id='$Id')";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_qa_reports WHERE vendor_id='$Id'";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_lines WHERE vendor_id='$Id'";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_vsr WHERE po_id IN (SELECT id FROM tbl_po WHERE vendor_id='$Id')";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL  = "DELETE FROM tbl_po_quantities WHERE po_id IN (SELECT id FROM tbl_po WHERE vendor_id='$Id')";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_po_colors WHERE po_id IN (SELECT id FROM tbl_po WHERE vendor_id='$Id')";
		$objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_po WHERE vendor_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}
*/

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_forecasts WHERE vendor_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "DELETE FROM tbl_revised_forecasts WHERE vendor_id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$sSQL = "SELECT picture FROM tbl_vendor_profile_albums WHERE id='$Id'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sAlbumPic = $objDb->getField(0, 0);

			$sSQL = "DELETE FROM tbl_vendor_profile_albums WHERE id='$Id'";
			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == true)
			{
				$sSQL = "SELECT picture FROM tbl_vendor_profile_pictures WHERE album_id='$Id'";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
					$sPictures[] = $objDb->getField($i, 0);

				$sSQL = "DELETE FROM tbl_vendor_profile_pictures WHERE album_id='$Id'";
				$bFlag = $objDb->execute($sSQL);
			}
		}
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		@unlink($sBaseDir.VENDOR_ALBUMS_IMG_PATH.$sAlbumPic);

		for ($i = 0; $i < count($sPictures); $i ++)
		{
			@unlink($sBaseDir.VENDOR_PICS_IMG_PATH."enlarged/".$sPictures[$i]);
			@unlink($sBaseDir.VENDOR_PICS_IMG_PATH."thumbs/".$sPictures[$i]);
		}

		$_SESSION['Flag'] = "VENDOR_DELETED";
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>