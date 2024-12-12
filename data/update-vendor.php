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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

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
        $TypeLevis                  = (IO::strValue("TypeLevis") != 'Y'?'N':IO::strValue("TypeLevis"));
        $TypeMgf                    = (IO::strValue("TypeMgf") != 'Y'?'N':IO::strValue("TypeMgf"));
        $TypeGlobal                 = (IO::strValue("TypeGlobal") != 'Y'?'N':IO::strValue("TypeGlobal"));
        $pColor                     = IO::strValue("pColor");
        $iColor                     = IO::strValue("iColor");
        $ChangeAddress              = IO::strValue("ChangeAddress");
        $FactoryCrName              = IO::strValue("FactoryCrName");
        $FactoryCrPhone             = IO::strValue("FactoryCrPhone");
        $FactoryCrEmail             = IO::strValue("FactoryCrEmail");
        $FactoryOwn                 = IO::strValue("FactoryOwn");  
        $TotalEmployees             = IO::strValue("TotalEmployees");
        $TemporaryEmployees         = IO::strValue("TemporaryEmployees");
        $ContractualEmployees       = IO::strValue("ContractualEmployees");         
        $PeakMonth                  = IO::strValue("PeakMonth");
        $LowMonth                   = IO::strValue("LowMonth");
        $ManufactAge                = IO::strValue("ManufactAge");       
        $EmployeeTurnover           = IO::strValue("EmployeeTurnover");
        $RSLPolicy                  = IO::strValue("RSLPolicy");        
        $RSLCompliant               = IO::strValue("RSLCompliant");
        $MajorBuyer                 = IO::strValue("MajorBuyer");
        $SubContractors             = IO::strValue("SubContractors");
        $Practices                  = IO::strValue("Practices");        
        $ApprenticeProgram          = IO::strValue("ApprenticeProgram");
        $CommunicationChannel       = IO::strValue("CommunicationChannel");     
        $Documentation              = IO::strValue("Documentation");
        $FundBenefits               = IO::strValue("FundBenefits");        
        $PortionFacility            = IO::strValue("PortionFacility");
        $HazardousChemicals         = IO::strValue("HazardousChemicals");
        $WasteWater                 = IO::strValue("WasteWater");
        $Canteen                    = IO::strValue("Canteen");        
        $ChildCare                  = IO::strValue("ChildCare");
        $Dormotories                = IO::strValue("Dormotories");        
                
        
	$sSQL  = "SELECT * FROM tbl_vendors WHERE parent_id='$Parent' AND vendor LIKE '$Vendor' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_vendors SET sourcing='$Sourcing', pcc='$Pcc', brandix='$Brandix', parent_id='$Parent', vendor='$Vendor', code='$Code', company='$Company', address='$Address', city='$City', category_id='$Category', btx_division='$BtxDivision', country_id='$Country', daily_capacity='$DailyCapacity', daily_knitting_capacity='$DailyKnittingCapacity', daily_dyeing_capacity='$DailyDyeingCapacity', daily_cutting_capacity='$DailyCuttingCapacity', daily_stitching_capacity='$DailyStitchingCapacity', daily_packing_capacity='$DailyPackingCapacity', batch_dr='$BatchDr', cutting_dr='$CuttingDr', final_dr='$FinalDr', output_dr='$OutputDr', sorting_dr='$SortingDr', stitching_dr='$StitchingDr', finishing_dr='$FinishingDr', off_loom_dr='$OffLoomDr', stock_dr='$StockDr', pre_final_dr='$PreFinalDr', date_of_foundation='$DateOfFoundation', product_range='$ProductRange', ownership='$Ownership', production_capability='$ProductionCapability', factory_area='$FactoryArea', production_capacity='$ProductionCapacity', stitching_machines='$StitchingMachines', active_customers='$ActiveCustomers', approved_customers='$ApprovedCustomers', permanent_employees='$PermanentEmployees', male_employees='$MaleEmployees', female_employees='$FemaleEmployees', certifications='$Certifications', third_party_compliance_audits='$ThirdPartyComplianceAudits', annual_turnover_volume='$AnnualTurnoverVolume', annual_turnover_value='$AnnualTurnoverValue', unit_audit_time='$UnitAuditTime', latitude='$Latitude', longitude='$Longitude', cap_no='$CapNo', etd_managers='$EtdManagers', profile='$Profile', crc_departments='$Departments', manager_rep='$MngrRep', manager_rep_email='$MngrRepEmail', total_shifts='$TotalShifts',  production_steps = '$ProductionSteps',  phone = '$Phone',  fax = '$Fax', levis='$TypeLevis', mgf='$TypeMgf', global='$TypeGlobal',primary_color='$pColor',icon_color='$iColor', change_address='$ChangeAddress', factory_cr_name='$FactoryCrName', factory_cr_phone='$FactoryCrPhone', factory_cr_email='$FactoryCrEmail', factory_ownership='$FactoryOwn', total_employees='$TotalEmployees', temp_employees='$TemporaryEmployees', contract_employees='$ContractualEmployees', peak_season='$PeakMonth', low_season='$LowMonth', manufact_age='$ManufactAge', month_turnover='$EmployeeTurnover', rsl_policy='$RSLPolicy', rsl_compliant='$RSLCompliant', major_buyer='$MajorBuyer', subcontractors='$SubContractors', practices='$Practices', apprentice_program='$ApprenticeProgram', communication_channel='$CommunicationChannel', documentation='$Documentation', fund_benefits='$FundBenefits', portion_facility='$PortionFacility', hazardous_chemicals='$HazardousChemicals', waste_water='$WasteWater', canteen='$Canteen', child_care='$ChildCare', dormotories='$Dormotories' WHERE id='$Id'";
                        
                        $Flag = $objDb->execute($sSQL);
                        
                        if ($Flag == true)
                        {


                          if($Parent && $Parent != 0){

                              $sSQL  = "UPDATE tbl_vendors SET primary_color = '$pColor',icon_color = '$iColor' WHERE id='$Parent'";
                            
                              $objDb->execute($sSQL);
                          }

                            $sFileName = $_FILES["RepPicture"]['name'];
                            
                            if ($sFileName != "")
                            {
                                    $exts = explode('.', $sFileName);
                                    $extension = end($exts);
                                    if(@in_array(strtolower($extension), array('jpg','jpeg')))
                                    {
                                        $sPicture = ("REP_PICTURE_".$Id.'.'.$extension);

                                        if (@move_uploaded_file($_FILES["RepPicture"]['tmp_name'], ($sBaseDir."files/representative/".$sPicture)))
                                        {
                                                $sSQL  = "UPDATE tbl_vendors SET rep_picture = '$sPicture' WHERE id='$Id'";
                                                $Flag = $objDb->execute($sSQL);
                                        }
                                    }
                                    else
                                        $_SESSION['Flag'] = "DB_ERROR";
                            }
                        }
                        
			if ($Flag == true)
				redirect($_SERVER['HTTP_REFERER'], "VENDOR_UPDATED");
			else
				$_SESSION['Flag'] = "DB_ERROR";
		}

		else
			$_SESSION['Flag'] = "VENDOR_EXISTS";
	}

	else
		$_SESSION['Flag'] = "DB_ERROR";


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>