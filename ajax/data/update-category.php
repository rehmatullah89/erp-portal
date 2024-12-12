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

	$Id           = IO::intValue("Id");
	$Category     = IO::strValue("Category");
	$LeadTime1000 = IO::intValue("LeadTime1000");
	$LeadTime2500 = IO::intValue("LeadTime2500");
	$LeadTime5000 = IO::intValue("LeadTime5000");
	$sError       = "";

	$sSQL = "SELECT id FROM tbl_categories WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Category ID. Please select the proper Category to Edit.\n";
		exit( );
	}

	if ($Category == "")
		$sError .= "- Invalid Category\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}



	$sSQL  = "SELECT * FROM tbl_categories WHERE category LIKE '$Category' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = ("UPDATE tbl_categories SET category='$Category', lead_time_1000pcs='$LeadTime1000', lead_time_2500pcs='$LeadTime2500', lead_time_5000pcs='$LeadTime5000', knitting='".IO::intValue("Knitting")."', linking='".IO::intValue("Linking")."', dyeing='".IO::intValue("Dyeing")."', cutting='".IO::intValue("Cutting")."', print_embroidery='".IO::intValue("PrintEmbroidery")."', stitching='".IO::intValue("Stitching")."', washing='".IO::intValue("Washing")."', packing='".IO::intValue("Packing")."', weaving='".IO::intValue("Weaving")."', leather_import='".IO::intValue("LeatherImport")."', leather_inspection='".IO::intValue("LeatherInspection")."', lamination='".IO::intValue("Lamination")."', sorting='".IO::intValue("Sorting")."', bladder_attachment='".IO::intValue("BladderAttachment")."', finishing='".IO::intValue("Finishing")."', yarn='".IO::intValue("Yarn")."', quality='".IO::intValue("Quality")."', sizing='".IO::intValue("Sizing")."', lab_testing='".IO::intValue("LabTesting")."' WHERE id='$Id'");

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Category has been Updated successfully.</div>|-|$Category|-|".formatNumber($LeadTime1000, false)."|-|".formatNumber($LeadTime2500, false)."|-|".formatNumber($LeadTime5000, false));

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Category already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>