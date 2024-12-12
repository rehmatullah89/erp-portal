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

	$sSQL  = ("SELECT * FROM tbl_suppliers WHERE parent_id='".IO::intValue("Parent")."' AND (supplier LIKE '".IO::strValue("Supplier")."' OR ('".IO::strValue("Code")."'!='' AND code LIKE '".IO::strValue("Code")."'))");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_suppliers");

		$sSQL = ("INSERT INTO tbl_suppliers (id, sourcing, pcc, brandix, parent_id, supplier, code, company, city, address, category_id, btx_division, country_id, daily_capacity, daily_knitting_capacity, daily_dyeing_capacity, daily_cutting_capacity, daily_stitching_capacity, daily_packing_capacity, batch_dr, cutting_dr, final_dr, output_dr, sorting_dr, stitching_dr, finishing_dr, off_loom_dr, stock_dr, pre_final_dr, date_of_foundation, product_range, ownership, production_capability, factory_area, production_capacity, stitching_machines, active_customers, approved_customers, permanent_employees, male_employees, female_employees, certifications, third_party_compliance_audits, annual_turnover_volume, annual_turnover_value, unit_audit_time, latitude, longitude, cap_no, etd_managers, profile, crc_departments, manager_rep, manager_rep_email, total_shifts, production_steps, phone, fax)
		                           VALUES ('$iId', '".IO::strValue("Sourcing")."', '".IO::strValue("Pcc")."', '".IO::strValue("Brandix")."', '".IO::intValue("Parent")."', '".IO::strValue("Supplier")."', '".IO::strValue("Code")."', '".IO::strValue("Company")."', '".IO::strValue("City")."', '".@utf8_encode(IO::strValue("Address"))."', '".IO::intValue("Category")."', '".((IO::strValue("BtxDivision") == "Y") ? "Y" : "N")."', '".IO::intValue("Country")."', '".IO::floatValue("DailyCapacity")."', '".IO::floatValue("DailyKnittingCapacity")."', '".IO::floatValue("DailyDyeingCapacity")."', '".IO::floatValue("DailyCuttingCapacity")."', '".IO::floatValue("DailyStitchingCapacity")."', '".IO::floatValue("DailyPackingCapacity")."', '".IO::floatValue("BatchDr")."', '".IO::floatValue("CuttingDr")."', '".IO::floatValue("FinalDr")."', '".IO::floatValue("OutputDr")."', '".IO::floatValue("SortingDr")."', '".IO::floatValue("StitchingDr")."', '".IO::floatValue("FinishingDr")."', '".IO::floatValue("OffLoomDr")."', '".IO::floatValue("StockDr")."', '".IO::floatValue("PreFinalDr")."', '".IO::strValue("DateOfFoundation")."', '".@utf8_encode(IO::strValue("ProductRange"))."', '".IO::strValue("Ownership")."', '".@utf8_encode(IO::strValue("ProductionCapability"))."', '".IO::strValue("FactoryArea")."', '".@utf8_encode(IO::strValue("ProductionCapacity"))."', '".IO::strValue("StitchingMachines")."', '".@utf8_encode(IO::strValue("ActiveCustomers"))."', '".@utf8_encode(IO::strValue("ApprovedCustomers"))."', '".IO::intValue("PermanentEmployees")."', '".IO::floatValue("MaleEmployees")."', '".IO::floatValue("FemaleEmployees")."', '".IO::strValue("Certifications")."', '".IO::strValue("ThirdPartyComplianceAudits")."', '".IO::strValue("AnnualTurnoverVolume")."', '".IO::strValue("AnnualTurnoverValue")."', '".IO::intValue("UnitAuditTime")."', '".IO::strValue("Latitude")."', '".IO::strValue("Longitude")."', '".IO::strValue("CapNo")."', '".@implode(",", IO::getArray("EtdManagers"))."', '".@utf8_encode(IO::strValue("Profile"))."', '".@implode(",", IO::getArray("Departments"))."', '".IO::strValue("MngrRep")."', '".IO::strValue("MngrRepEmail")."', '".IO::intValue("TotalShifts")."', '".IO::strValue("ProductionSteps")."', '".IO::strValue("Phone")."', '".IO::strValue("Fax")."')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "SUPPLIER_ADDED");

		else{
                    
                        echo $sSQL; exit;
			$_SESSION['Flag'] = "DB_ERROR";
                        
                }
	}

	else
		$_SESSION['Flag'] = "SUPPLIER_EXISTS";


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>