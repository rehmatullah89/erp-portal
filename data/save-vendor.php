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

	$sSQL  = ("SELECT * FROM tbl_vendors WHERE parent_id='".IO::intValue("Parent")."' AND (vendor LIKE '".IO::strValue("Vendor")."' OR ('".IO::strValue("Code")."'!='' AND code LIKE '".IO::strValue("Code")."'))");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_vendors");

                $sTypeLevis = (IO::strValue("TypeLevis") != 'Y'?'N':IO::strValue("TypeLevis"));
                $sTypeMgf   = (IO::strValue("TypeMgf") != 'Y'?'N':IO::strValue("TypeMgf"));
                $sTypeGlobal= (IO::strValue("TypeGlobal") != 'Y'?'N':IO::strValue("TypeGlobal"));
                
		$sSQL = ("INSERT INTO tbl_vendors (id, sourcing, pcc, brandix, parent_id, vendor, code, company, city, address, category_id, btx_division, country_id, daily_capacity, daily_knitting_capacity, daily_dyeing_capacity, daily_cutting_capacity, daily_stitching_capacity, daily_packing_capacity, batch_dr, cutting_dr, final_dr, output_dr, sorting_dr, stitching_dr, finishing_dr, off_loom_dr, stock_dr, pre_final_dr, date_of_foundation, product_range, ownership, production_capability, factory_area, production_capacity, stitching_machines, active_customers, approved_customers, permanent_employees, male_employees, female_employees, certifications, third_party_compliance_audits, annual_turnover_volume, annual_turnover_value, unit_audit_time, latitude, longitude, cap_no, etd_managers, profile, crc_departments, manager_rep, manager_rep_email, total_shifts, production_steps, phone, fax, levis, mgf, global,primary_color,icon_color, change_address, factory_cr_name, factory_cr_phone, factory_cr_email, factory_ownership, total_employees, temp_employees, contract_employees, peak_season, low_season, manufact_age, month_turnover, rsl_policy, rsl_compliant, major_buyer, subcontractors, practices, apprentice_program, communication_channel, documentation, fund_benefits, portion_facility, hazardous_chemicals, waste_water, canteen, child_care, dormotories)
		                           VALUES ('$iId', '".IO::strValue("Sourcing")."', '".IO::strValue("Pcc")."', '".IO::strValue("Brandix")."', '".IO::intValue("Parent")."', '".IO::strValue("Vendor")."', '".IO::strValue("Code")."', '".IO::strValue("Company")."', '".IO::strValue("City")."', '".@utf8_encode(IO::strValue("Address"))."', '".IO::intValue("Category")."', '".((IO::strValue("BtxDivision") == "Y") ? "Y" : "N")."', '".IO::intValue("Country")."', '".IO::floatValue("DailyCapacity")."', '".IO::floatValue("DailyKnittingCapacity")."', '".IO::floatValue("DailyDyeingCapacity")."', '".IO::floatValue("DailyCuttingCapacity")."', '".IO::floatValue("DailyStitchingCapacity")."', '".IO::floatValue("DailyPackingCapacity")."', '".IO::floatValue("BatchDr")."', '".IO::floatValue("CuttingDr")."', '".IO::floatValue("FinalDr")."', '".IO::floatValue("OutputDr")."', '".IO::floatValue("SortingDr")."', '".IO::floatValue("StitchingDr")."', '".IO::floatValue("FinishingDr")."', '".IO::floatValue("OffLoomDr")."', '".IO::floatValue("StockDr")."', '".IO::floatValue("PreFinalDr")."', '".IO::strValue("DateOfFoundation")."', '".@utf8_encode(IO::strValue("ProductRange"))."', '".IO::strValue("Ownership")."', '".@utf8_encode(IO::strValue("ProductionCapability"))."', '".IO::strValue("FactoryArea")."', '".@utf8_encode(IO::strValue("ProductionCapacity"))."', '".IO::strValue("StitchingMachines")."', '".@utf8_encode(IO::strValue("ActiveCustomers"))."', '".@utf8_encode(IO::strValue("ApprovedCustomers"))."', '".IO::intValue("PermanentEmployees")."', '".IO::floatValue("MaleEmployees")."', '".IO::floatValue("FemaleEmployees")."', '".IO::strValue("Certifications")."', '".IO::strValue("ThirdPartyComplianceAudits")."', '".IO::strValue("AnnualTurnoverVolume")."', '".IO::strValue("AnnualTurnoverValue")."', '".IO::intValue("UnitAuditTime")."', '".IO::strValue("Latitude")."', '".IO::strValue("Longitude")."', '".IO::strValue("CapNo")."', '".@implode(",", IO::getArray("EtdManagers"))."', '".@utf8_encode(IO::strValue("Profile"))."', '".@implode(",", IO::getArray("Departments"))."', '".IO::strValue("MngrRep")."', '".IO::strValue("MngrRepEmail")."', '".IO::intValue("TotalShifts")."', '".IO::strValue("ProductionSteps")."', '".IO::strValue("Phone")."', '".IO::strValue("Fax")."', '".$sTypeLevis."', '".$sTypeMgf."', '".$sTypeGlobal."','".IO::strValue("pColor")."','".IO::strValue("iColor")."', '".IO::strValue("ChangeAddress")."', '".IO::strValue("FactoryCrName")."', '".IO::strValue("FactoryCrPhone")."', '".IO::strValue("FactoryCrEmail")."', '".IO::strValue("FactoryOwn")."', '".IO::strValue("TotalEmployees")."', '".IO::strValue("TemporaryEmployees")."', '".IO::strValue("ContractualEmployees")."', '".IO::strValue("PeakMonth")."', '".IO::strValue("LowMonth")."', '".IO::strValue("ManufactAge")."', '".IO::strValue("EmployeeTurnover")."', '".IO::strValue("RSLPolicy")."', '".IO::strValue("RSLCompliant")."', '".IO::strValue("MajorBuyer")."', '".IO::strValue("SubContractors")."', '".IO::strValue("Practices")."', '".IO::strValue("ApprenticeProgram")."', '".IO::strValue("CommunicationChannel")."', '".IO::strValue("Documentation")."', '".IO::strValue("FundBenefits")."', '".IO::strValue("PortionFacility")."', '".IO::strValue("HazardousChemicals")."', '".IO::strValue("WasteWater")."', '".IO::strValue("Canteen")."', '".IO::strValue("ChildCare")."', '".IO::strValue("Dormotories")."')");

                $Flag = $objDb->execute($sSQL);
                
                if ($Flag == true)
                {
                    $sFileName = $_FILES["RepPicture"]['name'];
                    if ($sFileName != "")
                    {
                            $exts = explode('.', $sFileName);
                            $extension = end($exts);

                            if(@in_array(strtolower($extension), array('jpg','jpeg')))
                            {
                                $sPicture = ("REP_PICTURE_".$iId.'.'.$extension);

                                if (@move_uploaded_file($_FILES["RepPicture"]['tmp_name'], ($sBaseDir."files/representative/".$sPicture)))
                                {
                                        $sSQL  = "UPDATE tbl_vendors SET rep_picture = '$sPicture' WHERE id='$iId'";
                                        $Flag = $objDb->execute($sSQL);
                                }
                            }
                            else
                                $_SESSION['Flag'] = "DB_ERROR";
                    }
                }
                
                if ($Flag == true)
                    redirect($_SERVER['HTTP_REFERER'], "VENDOR_ADDED");
		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "VENDOR_EXISTS";


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>