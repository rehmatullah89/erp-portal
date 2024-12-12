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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");
	@require_once("../requires/image-functions.php");

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id                     = IO::intValue('AuditId');
        $cIsAnotherPSite        = IO::strValue('IsProductionSite');
        $sProductionTier        = implode("|-|", IO::getArray('SitetTier'));
        $sProductionSiteType    = implode("|-|", IO::getArray('SitetType'));
        $sProductionSiteName    = implode("|-|", IO::getArray('SitetName'));
        $sProductionSiteAddress = implode("|-|", IO::getArray('SitetAddress'));
        $cIsAnotherCompany      = IO::strValue('IsAnotherCompany');
        $sCompanyTier           = implode("|-|", IO::getArray('CompanyTier'));
        $sCompanyType           = implode("|-|", IO::getArray('CompanyType'));
        $sCompanyName           = implode("|-|", IO::getArray('CompanyName'));
        $sCompanyAddress        = implode("|-|", IO::getArray('CompanyAddress'));
        $iNoOfBuildings         = IO::intValue('NoOfBuildings');
        $sBuildingPurpose       = implode("|-|", IO::getArray('Purpose'));
        $sBuildingFloors        = implode("|-|", IO::getArray('Floors'));
        $sFireCertificates      = implode("|-|", IO::getArray('FireCertificate'));
        $sBuildingApprovals     = implode("|-|", IO::getArray('Approvals'));
        $iTotalFarms            = IO::strValue('TotalFarms');
        $iCustomerTurnOver      = IO::strValue('MainTurnOver');
        $sLastOrderDate         = IO::strValue('LastOrderDate');
        $sOtherFactoryInfo      = IO::strValue('OtherFactoryInfo');
	$bFlag = true;


	$_SESSION['Flag'] = "";
	$objDb->execute("BEGIN");

        $sSQL  = "DELETE from tbl_crc_audit_supply_chain WHERE audit_id='$Id'";
        $bFlag = $objDb->execute($sSQL);


	if ($bFlag == true)
        {
            
                $sSQL  = "INSERT INTO tbl_crc_audit_supply_chain SET audit_id = '$Id',
                                                                    is_another_production_site   = '$cIsAnotherPSite',
                                                                    production_tier              = '$sProductionTier',
                                                                    production_site_type         = '$sProductionSiteType',
                                                                    production_site_name         = '$sProductionSiteName',
                                                                    production_site_address      = '$sProductionSiteAddress',
                                                                    is_another_company           = '$cIsAnotherCompany',
                                                                    company_tier                 = '$sCompanyTier',
                                                                    company_type                 = '$sCompanyType',
                                                                    company_name                 = '$sCompanyName',
                                                                    company_address              = '$sCompanyAddress',
                                                                    no_of_buildings              = '$iNoOfBuildings',
                                                                    building_purpose             = '$sBuildingPurpose',
                                                                    building_floors              = '$sBuildingFloors',
                                                                    fire_certificate             = '$sFireCertificates',
                                                                    building_approvals           = '$sBuildingApprovals',
                                                                    total_farms                  = '$iTotalFarms',
                                                                    customers_turn_over          = '$iCustomerTurnOver',
                                                                    last_order_date              = '$sLastOrderDate',
                                                                    other_factory_info           = '$sOtherFactoryInfo'";
                $bFlag = $objDb->execute($sSQL);

	}


	if ($_SESSION['Flag'] == "")
	{
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

                        $_SESSION["Flag1122"] = "Updated Successfully"; 
//                        redirect($_SERVER['HTTP_REFERER'], "Updated Successfully!");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			$objDb->execute("ROLLBACK");
		}
	}

            header("Location: {$_SERVER['HTTP_REFERER']}");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>