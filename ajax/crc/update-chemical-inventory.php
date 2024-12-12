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

	$Id      	    = IO::intValue("Id");
	$PreprationName     = IO::strValue("PreprationName");
	$FormulationName    = IO::strValue("FormulationName");
	$CompoundId         = IO::intValue("CompoundId");
        $LocationId         = IO::intValue("LocationId");
	$EinecsNo           = IO::strValue("EinecsNo");
	$HazardDataP        = IO::strValue("HazardDataP");
	$HazardDataH        = IO::strValue("HazardDataH");
	$HazardDataE        = IO::strValue("HazardDataE");
	$Concentration      = IO::strValue("Concentration");
	$SubstanceUsed      = IO::strValue("SubstanceUsed");
	$SupplierName       = IO::strValue("SupplierName");
	$SupplierEmail      = IO::strValue("SupplierEmail");
	$Consumption        = IO::strValue("Consumption");
	$QualityCheck       = IO::strValue("QualityCheck");
	$SdsPresent         = IO::strValue("SdsPresent");
	$Remarks            = IO::strValue("Remarks");
	$ResponsiblePerson  = IO::strValue("ResponsiblePerson");
	$UpdatedDate        = IO::strValue("UpdatedDate");
	$sError  = "";

	$sSQL = "SELECT id FROM tbl_chemicals_inventory WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Inventory ID. Please select the proper Inventory Compound to Edit.\n";
		exit( );
	}

	if ($CompoundId > 0)
	{
		$sSQL = "SELECT compound FROM tbl_chemical_compounds WHERE id='$CompoundId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Chemical Compound\n";

		else
			$sCompound = $objDb->getField(0, 0);
	}
	
	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = "SELECT * FROM tbl_chemicals_inventory WHERE prepration_name LIKE '$PreprationName' AND formulation_name LIKE '$FormulationName' AND compound_id='$CompoundId' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
                        
                        $sSQL  = "SELECT * FROM tbl_chemicals_inventory WHERE id='$Id'";
                        $objDb->query($sSQL);
                        
                        $prevPreprationName     = $objDb->getField(0, 'prepration_name');
                        $prevFormulationName    = $objDb->getField(0, 'formulation_name');
                        $prevCompoundId         = $objDb->getField(0, 'compound_id');
                        $prevLocationId         = $objDb->getField(0, 'location_id');
                        $prevEinecsNo           = $objDb->getField(0, 'einecs_no');
                        $prevHazardDataP        = $objDb->getField(0, 'hazard_data_p');
                        $prevHazardDataH        = $objDb->getField(0, 'hazard_data_h');
                        $prevHazardDataE        = $objDb->getField(0, 'hazard_data_e');
                        $prevConcentration      = $objDb->getField(0, 'concentration');
                        $prevSubstanceUsed      = $objDb->getField(0, 'substance_used');
                        $prevSupplierName       = $objDb->getField(0, 'supplier_name');
                        $prevSupplierEmail      = $objDb->getField(0, 'supplier_email');
                        $prevConsumption        = $objDb->getField(0, 'consumption');
                        $prevQualityCheck       = $objDb->getField(0, 'quality_check');
                        $prevSdsPresent         = $objDb->getField(0, 'sds_present');
                        $prevRemarks            = $objDb->getField(0, 'remarks');
                        $prevResponsiblePerson  = $objDb->getField(0, 'responsible_person');
                        $prevUpdatedDate        = $objDb->getField(0, 'updated_date');
                        $timestamp              = date('Y-m-d H:i:s');
                        
                        if($prevPreprationName != $PreprationName)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='prepration_name', previous_value='$prevPreprationName', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevFormulationName != $FormulationName)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='formulation_name', previous_value='$prevFormulationName', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevCompoundId != $CompoundId)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='compound_id', previous_value='$prevCompoundId', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevLocationId != $LocationId)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='compound_id', previous_value='$prevLocationId', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevEinecsNo != $EinecsNo)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='einecs_no', previous_value='$prevEinecsNo', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevHazardDataP != $HazardDataP)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='hazard_data_p', previous_value='$prevHazardDataP', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevHazardDataH != $HazardDataH)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='hazard_data_h', previous_value='$prevHazardDataH', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevHazardDataE != $HazardDataE)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='hazard_data_e', previous_value='$prevHazardDataE', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevConcentration != $Concentration)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='concentration', previous_value='$prevConcentration', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevSubstanceUsed != $SubstanceUsed)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='substance_used', previous_value='$prevSubstanceUsed', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevSupplierName != $SupplierName)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='supplier_name', previous_value='$prevSupplierName', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevSupplierEmail != $SupplierEmail)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='supplier_email', previous_value='$prevSupplierEmail', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevConsumption != $Consumption)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='consumption', previous_value='$prevConsumption', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevQualityCheck != $QualityCheck)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='quality_check', previous_value='$prevQualityCheck', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevSdsPresent != $SdsPresent)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='quality_check', sds_present='$prevSdsPresent', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevRemarks != $Remarks)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='remarks', previous_value='$prevRemarks', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevResponsiblePerson != $ResponsiblePerson)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='responsible_person', previous_value='$prevResponsiblePerson', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                        if($prevUpdatedDate != $UpdatedDate)
                            $objDb->execute("INSERT INTO tbl_chemicals_inventory_history SET field_name='updated_date', previous_value='$prevUpdatedDate', user_id='{$_SESSION['UserId']}', inventory_id='$Id', date='$timestamp'");
                            
                        
			$sSQL = "UPDATE tbl_chemicals_inventory SET prepration_name='$PreprationName', formulation_name='$FormulationName', compound_id='$CompoundId', location_id='$LocationId', einecs_no='$EinecsNo', hazard_data_p='$HazardDataP', hazard_data_h='$HazardDataH', hazard_data_e='$HazardDataE', concentration='$Concentration', substance_used='$SubstanceUsed', supplier_name='$SupplierName', supplier_email='$SupplierEmail', consumption='$Consumption', quality_check='$QualityCheck', sds_present='$SdsPresent', remarks='$Remarks', responsible_person='$ResponsiblePerson', updated_date='$UpdatedDate' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print "OK|-|$Id|-|<div>The selected Chemical Inventory list has been Updated successfully.</div>|-|$PreprationName|-|$FormulationName|-|$sCompound";

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Chemical Inventory list item already exists (with same Name) in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>