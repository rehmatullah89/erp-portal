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

	$Id         = IO::intValue("Id");
	$Section    = IO::intValue("Section");
	$Category   = IO::intValue("Category");
	$Point      = IO::strValue("Point");
        $Comments   = IO::strValue("Comments");
        $PointNo    = IO::strValue("PointNo");
	$Nature     = IO::strValue("Nature");
        $Brands     = implode(",", IO::getArray("Brand"));
        $AuditTypes = implode(",", IO::getArray("AuditType"));
        
	$sError   = "";

	$sSQL = "SELECT id FROM tbl_tnc_points WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Point. Please select the proper Point to Edit.\n";
		exit( );
	}

	if ($Section > 0)
	{
		$sSQL = "SELECT section FROM tbl_tnc_sections WHERE id='$Section'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Section\n";

		else
			$sSection = $objDb->getField(0, 0);
	}

	else
		$sError .= "- Invalid Section\n";


	if ($Category > 0)
	{
		$sSQL = "SELECT category FROM tbl_tnc_categories WHERE id='$Category'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Category\n";

		else
			$sCategory = $objDb->getField(0, 0);
	}

	else
		$sError .= "- Invalid Category\n";


	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = "SELECT * FROM tbl_tnc_points WHERE point LIKE '$Point' AND section_id='$Section' AND category_id='$Category' AND point_no='$PointNo' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_tnc_points SET point='$Point', comments='$Comments', section_id='$Section', category_id='$Category', nature='$Nature', point_no='$PointNo', brands='$Brands', audit_types='$AuditTypes' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
			{
				switch ($Nature)
				{
					case "C" : $Nature = "Critical"; break;
					case "Z" : $Nature = "Zero Tolerance"; break;
					default  : $Nature = "Standard"; break;
				}

				print "OK|-|$Id|-|<div>The selected Point has been Updated successfully.</div>|-|$Point|-|$sSection|-|$sCategory|-|$Nature";
			}

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Point already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>