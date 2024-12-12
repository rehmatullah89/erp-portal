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

	$Id                         = IO::intValue("Id");
	$Sourcing                   = IO::strValue("Sourcing");
	$Pcc                        = IO::strValue("Pcc");
	$Brandix                    = IO::strValue("Brandix");
	$Parent                     = IO::intValue("Parent");
	$Vendor                     = IO::strValue("Vendor");
	$Code                       = IO::strValue("Code");
	$Company                    = IO::strValue("Company");
	$Address                    = @utf8_encode(IO::strValue("Address"));
	$City                       = IO::strValue("City");
	$Category                   = IO::intValue("Category");
	$BtxDivision                = ((IO::strValue("BtxDivision") == "Y") ? "Y" : "N");
	$Country                    = IO::intValue("Country");
	$DailyCapacity              = IO::floatValue("DailyCapacity");
	$DailyKnittingCapacity      = IO::floatValue("DailyKnittingCapacity");
	$DailyDyeingCapacity        = IO::floatValue("DailyDyeingCapacity");
	$DailyCuttingCapacity       = IO::floatValue("DailyCuttingCapacity");
	$DailyStitchingCapacity     = IO::floatValue("DailyStitchingCapacity");
	$DailyPackingCapacity       = IO::floatValue("DailyPackingCapacity");
	$DateOfFoundation           = IO::strValue("DateOfFoundation");
	$BatchDr                    = IO::floatValue("BatchDr");
	$CuttingDr                  = IO::floatValue("CuttingDr");
	$FinalDr                    = IO::floatValue("FinalDr");
	$OutputDr                   = IO::floatValue("OutputDr");
	$SortingDr                  = IO::floatValue("SortingDr");
	$StitchingDr                = IO::floatValue("StitchingDr");
	$FinishingDr                = IO::floatValue("FinishingDr");
	$OffLoomDr                  = IO::floatValue("OffLoomDr");
	$StockDr                    = IO::floatValue("StockDr");
	$PreFinalDr                 = IO::floatValue("PreFinalDr");
	$ProductRange               = @utf8_encode(IO::strValue("ProductRange"));
	$Ownership                  = IO::strValue("Ownership");
	$ProductionCapability       = @utf8_encode(IO::strValue("ProductionCapability"));
	$FactoryArea                = IO::strValue("FactoryArea");
	$ProductionCapacity         = @utf8_encode(IO::strValue("ProductionCapacity"));
	$StitchingMachines          = IO::strValue("StitchingMachines");
	$ActiveCustomers            = IO::strValue("ActiveCustomers");
	$ApprovedCustomers          = IO::strValue("ApprovedCustomers");
	$PermanentEmployees         = IO::intValue("PermanentEmployees");
	$MaleEmployees              = IO::floatValue("MaleEmployees");
	$FemaleEmployees            = IO::floatValue("FemaleEmployees");
	$Certifications             = IO::strValue("Certifications");
	$ThirdPartyComplianceAudits = IO::strValue("ThirdPartyComplianceAudits");
	$AnnualTurnoverVolume       = IO::strValue("AnnualTurnoverVolume");
	$AnnualTurnoverValue        = IO::strValue("AnnualTurnoverValue");
	$UnitAuditTime              = IO::intValue("UnitAuditTime");
	$Latitude                   = IO::strValue("Latitude");
	$Longitude                  = IO::strValue("Longitude");
	$CapNo                      = IO::strValue("CapNo");
	$EtdManagers                = @implode(",", IO::getArray("EtdManagers"));
	$Profile                    = @utf8_encode(IO::strValue("Profile"));
        $Departments                = @implode(",", IO::getArray("Departments"));
        $MngrRep                    = IO::strValue("MngrRep");
        $MngrRepEmail               = IO::strValue("MngrRepEmail");
        $TotalShifts                = IO::strValue("TotalShifts");
        $ProductionSteps            = IO::strValue("ProductionSteps");
        $Phone                      = IO::strValue("Phone");
        $Fax                        = IO::strValue("Fax");

	$sError                     = "";

	$sSQL = "SELECT id FROM tbl_vendors WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Vendor ID. Please select the proper Vendor to Edit.\n";
		exit( );
	}

	if ($Vendor == "")
		$sError .= "- Invalid Vendor\n";

	if ($Category > 0)
	{
		$sSQL = "SELECT category FROM tbl_categories WHERE id='$Category'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Category\n";

		else
			$sCategory = $objDb->getField(0, 0);
	}

	if ($Country > 0)
	{
		$sSQL = "SELECT country FROM tbl_countries WHERE id='$Country'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Country\n";

		else
			$sCountry = $objDb->getField(0, 0);
	}

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}

	//$sSQL  = "SELECT * FROM tbl_vendors WHERE parent_id='$Parent' AND (vendor LIKE '$Vendor' OR ('$Code'!='' AND code LIKE '$Code')) AND id!='$Id'";
	$sSQL  = "SELECT * FROM tbl_vendors WHERE parent_id='$Parent' AND vendor LIKE '$Vendor' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_vendors SET sourcing='$Sourcing', pcc='$Pcc', brandix='$Brandix', parent_id='$Parent', vendor='$Vendor', code='$Code', company='$Company', address='$Address', city='$City', category_id='$Category', btx_division='$BtxDivision', country_id='$Country', daily_capacity='$DailyCapacity', daily_knitting_capacity='$DailyKnittingCapacity', daily_dyeing_capacity='$DailyDyeingCapacity', daily_cutting_capacity='$DailyCuttingCapacity', daily_stitching_capacity='$DailyStitchingCapacity', daily_packing_capacity='$DailyPackingCapacity', batch_dr='$BatchDr', cutting_dr='$CuttingDr', final_dr='$FinalDr', output_dr='$OutputDr', sorting_dr='$SortingDr', stitching_dr='$StitchingDr', finishing_dr='$FinishingDr', off_loom_dr='$OffLoomDr', stock_dr='$StockDr', pre_final_dr='$PreFinalDr', date_of_foundation='$DateOfFoundation', product_range='$ProductRange', ownership='$Ownership', production_capability='$ProductionCapability', factory_area='$FactoryArea', production_capacity='$ProductionCapacity', stitching_machines='$StitchingMachines', active_customers='$ActiveCustomers', approved_customers='$ApprovedCustomers', permanent_employees='$PermanentEmployees', male_employees='$MaleEmployees', female_employees='$FemaleEmployees', certifications='$Certifications', third_party_compliance_audits='$ThirdPartyComplianceAudits', annual_turnover_volume='$AnnualTurnoverVolume', annual_turnover_value='$AnnualTurnoverValue', unit_audit_time='$UnitAuditTime', latitude='$Latitude', longitude='$Longitude', cap_no='$CapNo', etd_managers='$EtdManagers', profile='$Profile', crc_departments='$Departments', manager_rep='$MngrRep', manager_rep_email='$MngrRepEmail', total_shifts='$TotalShifts',  production_steps = '$ProductionSteps',  phone = '$Phone',  fax = '$Fax' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Vendor has been Updated successfully.</div>|-|$Vendor|-|$Code|-|$sCategory|-|$sCountry|-|$City");

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Vendor / Code / Country already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>