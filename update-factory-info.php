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

	@require_once("requires/session.php");

	checkLogin(false);

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

        $Id                 = IO::intValue("Id");
	$CustomerName       = IO::strValue("CustomerName");
	$Address            = IO::strValue("Address");
	$FactoryCrName      = IO::strValue("FactoryCrName");
	$FactoryCrPhone     = IO::strValue("FactoryCrPhone");
	$FactoryCrEmail     = IO::strValue("FactoryCrEmail");
	$FactoryOwn         = IO::strValue("FactoryOwn");
	$TotalEmployees     = IO::intValue("TotalEmployees");
	$PermanentEmployees = IO::strValue("PermanentEmployees");
	$TemporaryEmployees = IO::strValue("TemporaryEmployees");
	$ContractEmployees  = IO::strValue("ContractEmployees");
	$PeakMonth          = IO::strValue("PeakMonth");
        $LowMonth           = IO::strValue("LowMonth");
        $ManufactAge        = IO::strValue("ManufactAge");
        $EmployeeTurnover   = IO::strValue("EmployeeTurnover");
        $RSLPolicy          = IO::strValue("RSLPolicy");
        $RSLCompliant       = IO::strValue("RSLCompliant");
        $Products           = IO::strValue("Products");
        $MajorBuyer         = IO::strValue("MajorBuyer");
        $ProductionCapacity = IO::strValue("ProductionCapacity");
        $TotalMachines      = IO::strValue("TotalMachines");
        $SubContractors     = IO::strValue("SubContractors");
        $Certifications     = IO::strValue("Certifications");
        $Practices          = IO::strValue("Practices");
        $ApprenticeProgram  = IO::strValue("ApprenticeProgram");
        $CommunicationChannel= IO::strValue("CommunicationChannel");
        $Documentation      = IO::strValue("Documentation");
        $FundBenefits       = IO::strValue("FundBenefits");
        $BuildingArea       = IO::strValue("BuildingArea");
        $PortionFacility    = IO::strValue("PortionFacility");
        $HazardousChemicals = IO::strValue("HazardousChemicals");
        $WasteWater         = IO::strValue("WasteWater");
        $Canteen            = IO::strValue("Canteen");
        $ChildCare          = IO::strValue("ChildCare");
        $Dormotories        = IO::strValue("Dormotories");

        $sSubQyery = "";
        
        if($CustomerName != "")
            $sSubQyery .= " , active_customers = '$CustomerName'";
        
        if($Address != "")
            $sSubQyery .= " , change_address = '$Address'";
        
        if($FactoryCrName != "")
            $sSubQyery .= " , factory_cr_name = '$FactoryCrName'";
        
        if($FactoryCrPhone != "")
            $sSubQyery .= " , factory_cr_phone = '$FactoryCrPhone'";
        
        if($FactoryCrEmail != "")
            $sSubQyery .= " , factory_cr_email = '$FactoryCrEmail'";
        
        if($FactoryOwn != "")
            $sSubQyery .= " , factory_ownership = '$FactoryOwn'";
        
        if($TotalEmployees != "")
            $sSubQyery .= " , total_employees = '$TotalEmployees'";
        
        if($PermanentEmployees != "")
            $sSubQyery .= " , permanent_employees = '$PermanentEmployees'";
        
        if($TemporaryEmployees != "")
            $sSubQyery .= " , temp_employees = '$TemporaryEmployees'";
        
        if($ContractEmployees != "")
            $sSubQyery .= " , contract_employees = '$ContractEmployees'";
        
        if($PeakMonth != "")
            $sSubQyery .= " , peak_season = '$PeakMonth'";
        
        if($LowMonth != "")
            $sSubQyery .= " , low_season = '$LowMonth'";
        
        if($ManufactAge != "")
            $sSubQyery .= " , manufact_age = '$ManufactAge'";
        
        if($EmployeeTurnover != "")
            $sSubQyery .= " , month_turnover = '$EmployeeTurnover'";
        
        if($RSLPolicy != "")
            $sSubQyery .= " , rsl_policy = '$RSLPolicy'";
        
        if($RSLCompliant != "")
            $sSubQyery .= " , rsl_compliant = '$RSLCompliant'";
        
        if($Products != "")
            $sSubQyery .= " , product_range = '$Products'";
        
        if($MajorBuyer != "")
            $sSubQyery .= " , major_buyer = '$MajorBuyer'";
        
        if($ProductionCapacity != "")
            $sSubQyery .= " , production_capacity = '$ProductionCapacity'";
        
        if($TotalMachines != "")
            $sSubQyery .= " , stitching_machines = '$TotalMachines'";
        
        if($SubContractors != "")
            $sSubQyery .= " , subcontractors = '$SubContractors'";
        
        if($Certifications != "")
            $sSubQyery .= " , certifications = '$Certifications'";
        
        if($Practices != "")
            $sSubQyery .= " , practices = '$Practices'";
        
        if($ApprenticeProgram != "")
            $sSubQyery .= " , apprentice_program = '$ApprenticeProgram'";
        
        if($CommunicationChannel != "")
            $sSubQyery .= " , communication_channel = '$CommunicationChannel'";
        
        if($Documentation != "")
            $sSubQyery .= " , documentation = '$Documentation'";
        
        if($FundBenefits != "")
            $sSubQyery .= " , fund_benefits = '$FundBenefits'";
        
        if($BuildingArea != "")
            $sSubQyery .= " , factory_area = '$BuildingArea'";
        
        if($PortionFacility != "")
            $sSubQyery .= " , portion_facility = '$PortionFacility'";
        
        if($HazardousChemicals != "")
            $sSubQyery .= " , hazardous_chemicals = '$HazardousChemicals'";
        
        if($WasteWater != "")
            $sSubQyery .= " , waste_water = '$WasteWater'";
        
        if($Canteen != "")
            $sSubQyery .= " , canteen = '$Canteen'";
        
        if($ChildCare != "")
            $sSubQyery .= " , child_care = '$ChildCare'";
        
        if($Dormotories != "")
            $sSubQyery .= " , dormotories = '$Dormotories'";
        
        
	if (md5(IO::strValue('SpamCode')) != $_SESSION['SpamCode'])
	{
		$_SESSION['Flag'] = "INVALID_SPAM_CODE";
		backToForm( );
	}

	if ($_SESSION['Flag'] == "" && $Id > 0)
	{
                $sSQL = "UPDATE tbl_vendors SET id='$Id' $sSubQyery WHERE id='$Id'";
                $bFlag = $objDb->execute($sSQL);

                if($bFlag == true)
                    redirect(SITE_URL, "INFORMATION_UPDATED");
	}
	else
            $_SESSION['Flag'] = "DB_ERROR";

	backToForm( );

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>