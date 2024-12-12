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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id        = IO::intValue("Id");
	$PointId   = IO::strValue("PointId");
	$Point     = @utf8_encode(IO::strValue("Point"));
        $PointZh   = (IO::strValue("PointZh"));
        $PointTr   = (IO::strValue("PointTr"));
        $PointDe   = (IO::strValue("PointDe"));
        $PointUr   = (IO::strValue("PointUr"));
        $PointRu   = (IO::strValue("PointRu"));
	$Tolerance = IO::strValue("Tolerance");
	$Category  = IO::strValue("Category");
	$Brand     = IO::strValue("Brand");
	$sError    = "";

	$sSQL = "SELECT id FROM tbl_measurement_points WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid MP ID. Please select the proper MP to Edit.\n";
		exit( );
	}

	if ($PointId == "")
		$sError .= "- Invalid Point ID\n";

	if ($Point == "")
		$sError .= "- Invalid Measurement Point\n";

	if ($Category > 0)
	{
		$sSQL = "SELECT category FROM tbl_sampling_categories WHERE id='$Category'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Category\n";

		else
			$sCategory = $objDb->getField(0, 0);
	}


	if ($Brand > 0)
	{
		$sSQL = "SELECT brand FROM tbl_brands WHERE id='$Brand'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Brand\n";

		else
			$sBrand = $objDb->getField(0, 0);
	}

	else
		$sError .= "- Invalid Brand\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = ("SELECT * FROM tbl_measurement_points WHERE point_id='$PointId' AND category_id='$Category' AND brand_id='$Brand' AND id!='$Id'");

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_measurement_points SET point_id='$PointId', point='$Point', point_zh='$PointZh', point_tr='$PointTr', point_de='$PointDe', point_ur='$PointUr', point_ru='$PointRu', tolerance='$Tolerance', category_id='$Category', brand_id='$Brand' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Measurement Point has been Updated successfully.</div>|-|$PointId|-|".stripslashes($Point)."|-|$Tolerance|-|$sCategory|-|$sBrand");

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Measurement Point already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>