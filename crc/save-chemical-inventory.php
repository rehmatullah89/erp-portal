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

	@require_once("../requires/session.php");

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$sSQL  = ("SELECT * FROM tbl_chemicals_inventory WHERE prepration_name LIKE '".IO::strValue("PreprationName")."' AND formulation_name LIKE '".IO::strValue("FormulationName")."' AND compound_id = '".IO::strValue("CompoundId")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_chemicals_inventory");

		$sSQL = ("INSERT INTO tbl_chemicals_inventory (id, prepration_name, formulation_name, compound_id, location_id, einecs_no, hazard_data_p, hazard_data_h, hazard_data_e, concentration, substance_used, supplier_name, supplier_email, consumption, quality_check, sds_present, remarks, responsible_person, updated_date, position) VALUES ('$iId', '".IO::strValue("PreprationName")."', '".IO::strValue("FormulationName")."', '".IO::intValue("CompoundId")."', '".IO::intValue("LocationId")."', '".IO::strValue("EinecsNo")."', '".IO::strValue("HazardDataP")."', '".IO::strValue("HazardDataH")."', '".IO::strValue("HazardDataE")."', '".IO::strValue("Concentration")."', '".IO::strValue("SubstanceUsed")."', '".IO::strValue("SupplierName")."', '".IO::strValue("SupplierEmail")."', '".IO::strValue("Consumption")."', '".IO::strValue("QualityCheck")."', '".IO::strValue("SdsPresent")."', '".IO::strValue("Remarks")."', '".IO::strValue("ResponsiblePerson")."', '".IO::strValue("UpdatedDate")."', '$iId')");
                
		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "CHEMICAL_INVENTORY_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "CHEMICAL_INVENTORY_EXISTS";


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>