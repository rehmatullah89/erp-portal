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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id      = IO::intValue('Id');
	$Referer = IO::strValue("Referer");

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];


	$sSQL = "SELECT * FROM tbl_vendors WHERE id = '$Id'";
	$objDb->query($sSQL);
	
	$sSourcing                   = $objDb->getField(0, 'sourcing');
	$sPcc                        = $objDb->getField(0, 'pcc');
	$sBrandix                    = $objDb->getField(0, 'brandix');
	$iParent                     = $objDb->getField(0, 'parent_id');
	$sVendor                     = $objDb->getField(0, 'vendor');
	$sCode                       = $objDb->getField(0, 'code');
	$sCompany                    = $objDb->getField(0, 'company');
	$sCity                       = $objDb->getField(0, 'city');
	$sAddress                    = $objDb->getField(0, 'address');
	$iCategory                   = $objDb->getField(0, 'category_id');
	$sBtxDivision                = $objDb->getField(0, 'btx_division');
	$iCountry                    = $objDb->getField(0, 'country_id');
	$fDailyCapacity              = $objDb->getField(0, 'daily_capacity');
	$fDailyKnittingCapacity      = $objDb->getField(0, 'daily_knitting_capacity');
	$fDailyDyeingCapacity        = $objDb->getField(0, 'daily_dyeing_capacity');
	$fDailyCuttingCapacity       = $objDb->getField(0, 'daily_cutting_capacity');
	$fDailyStitchingCapacity     = $objDb->getField(0, 'daily_stitching_capacity');
	$fDailyPackingCapacity       = $objDb->getField(0, 'daily_packing_capacity');
	$sDateOfFoundation           = $objDb->getField(0, 'date_of_foundation');
	$sProductRange               = $objDb->getField(0, 'product_range');
	$sOwnership                  = $objDb->getField(0, 'ownership');
	$sProductionCapability       = $objDb->getField(0, 'production_capability');
	$sFactoryArea                = $objDb->getField(0, 'factory_area');
	$sProductionCapacity         = $objDb->getField(0, 'production_capacity');
	$sStitchingMachines          = $objDb->getField(0, 'stitching_machines');
	$sActiveCustomers            = $objDb->getField(0, 'active_customers');
	$sApprovedCustomers          = $objDb->getField(0, 'approved_customers');
	$iPermanentEmployees         = $objDb->getField(0, 'permanent_employees');
	$fMaleEmployees              = $objDb->getField(0, 'male_employees');
	$fFemaleEmployees            = $objDb->getField(0, 'female_employees');
	$sCertifications             = $objDb->getField(0, 'certifications');
	$sThirdPartyComplianceAudits = $objDb->getField(0, 'third_party_compliance_audits');
	$sAnnualTurnoverVolume       = $objDb->getField(0, 'annual_turnover_volume');
	$sAnnualTurnoverValue        = $objDb->getField(0, 'annual_turnover_value');
	$fBatchDr                    = $objDb->getField(0, 'batch_dr');
	$fCuttingDr                  = $objDb->getField(0, 'cutting_dr');
	$fFinalDr                    = $objDb->getField(0, 'final_dr');
	$fOutputDr                   = $objDb->getField(0, 'output_dr');
	$fSortingDr                  = $objDb->getField(0, 'sorting_dr');
	$fStitchingDr                = $objDb->getField(0, 'stitching_dr');
	$fFinishingDr                = $objDb->getField(0, 'finishing_dr');
	$fOffLoomDr                  = $objDb->getField(0, 'off_loom_dr');
	$fStockDr                    = $objDb->getField(0, 'stock_dr');
	$fPreFinalDr                 = $objDb->getField(0, 'pre_final_dr');
	$iUnitAuditTime              = $objDb->getField(0, 'unit_audit_time');
	$sLatitude                   = $objDb->getField(0, 'latitude');
	$sLongitude                  = $objDb->getField(0, 'longitude');
	$sCapNo                      = $objDb->getField(0, 'cap_no');
	$iEtdManagers                = @explode(",", $objDb->getField(0, 'etd_managers'));
	$sProfile                    = $objDb->getField(0, 'profile');
	$sDepartmentIds              = @explode(",", $objDb->getField(0, 'crc_departments'));
	$sManagerRep                 = $objDb->getField(0, 'manager_rep');
	$sManagerRepEmail            = $objDb->getField(0, 'manager_rep_email');
	$sTotalShifts                = $objDb->getField(0, 'total_shifts');
	$sProductionSteps            = $objDb->getField(0, 'production_steps');
	$sFax                        = $objDb->getField(0, 'fax');
	$sPhone                      = $objDb->getField(0, 'phone');
	$sTypeLevis                  = $objDb->getField(0, 'levis');
	$sTypeMgf                    = $objDb->getField(0, 'mgf');
	$sTypeGlobal                 = $objDb->getField(0, 'global');
	$pColor                      = $objDb->getField(0, 'primary_color');
	$iColor                      = $objDb->getField(0, 'icon_color');
        
        $ChangeAddress              = $objDb->getField(0, 'change_address');
        $FactoryCrName              = $objDb->getField(0, 'factory_cr_name');
        $FactoryCrPhone             = $objDb->getField(0, 'factory_cr_phone');
        $FactoryCrEmail             = $objDb->getField(0, 'factory_cr_email');
        $FactoryOwn                 = $objDb->getField(0, 'factory_ownership');  
        $TotalEmployees             = $objDb->getField(0, 'total_employees');
        $TemporaryEmployees         = $objDb->getField(0, 'temp_employees');
        $ContractualEmployees       = $objDb->getField(0, 'contract_employees');
        $PeakMonth                  = $objDb->getField(0, 'peak_season');
        $LowMonth                   = $objDb->getField(0, 'low_season');
        $ManufactAge                = $objDb->getField(0, 'manufact_age');  
        $EmployeeTurnover           = $objDb->getField(0, 'month_turnover');
        $RSLPolicy                  = $objDb->getField(0, 'rsl_policy');
        $RSLCompliant               = $objDb->getField(0, 'rsl_compliant');
        $MajorBuyer                 = $objDb->getField(0, 'major_buyer');
        $SubContractors             = $objDb->getField(0, 'subcontractors');
        $Practices                  = $objDb->getField(0, 'practices');
        $ApprenticeProgram          = $objDb->getField(0, 'apprentice_program');
        $CommunicationChannel       = $objDb->getField(0, 'communication_channel');
        $Documentation              = $objDb->getField(0, 'documentation');
        $FundBenefits               = $objDb->getField(0, 'fund_benefits');   
        $PortionFacility            = $objDb->getField(0, 'portion_facility');
        $HazardousChemicals         = $objDb->getField(0, 'hazardous_chemicals');
        $WasteWater                 = $objDb->getField(0, 'waste_water');
        $Canteen                    = $objDb->getField(0, 'canteen');
        $ChildCare                  = $objDb->getField(0, 'child_care');
        $Dormotories                = $objDb->getField(0, 'dormotories');  

	//$sEtdManagers    = getDbValue("GROUP_CONCAT(user_id SEPARATOR ',')", "tbl_etd_managers");
	$sCountriesList  = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sCategoriesList = getList("tbl_categories", "id", "category");
	$sManagersList   = getList("tbl_users", "id", "name", "status='A' AND etd_manager='Y'");        
	$sVendorsList    = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND id!='$Id'");

	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
		$sVendorsList = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND id IN ({$_SESSION['Vendors']}) AND id!='$Id'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/edit-style.js"></script>
</head>

<body>

<div id="MainDiv">
  <div id="PageLeftBorder">
    <div id="PageRightBorder">

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

      <div id="PageContents">

<!--  Header Section Starts Here  -->
<?
	@include($sBaseDir."includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include($sBaseDir."includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
	    <div id="Body">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1>Vendor Listing</h1>

			    <form name="frmData" id="frmData" method="post" action="data/update-vendor.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />

			    <h2>Edit Vendor</h2>
                                <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr valign="top">
						  <td width="52%">

						    <table border="0" cellpadding="3" cellspacing="0" width="100%">
							  <tr>
							    <td width="140">Sourcing Vendor</td>
							    <td width="20" align="center">:</td>
							    <td><input type="checkbox" name="Sourcing" value="Y" <?= (($sSourcing == "Y") ? "checked" : "") ?> /></td>
							  </tr>

							  <tr>
							    <td>PCC Vendor</td>
							    <td align="center">:</td>
							    <td><input type="checkbox" name="Pcc" value="Y" <?= (($sPcc == "Y") ? "checked" : "") ?> /></td>
							  </tr>

							  <tr>
							    <td>Brandix Vendor</td>
							    <td align="center">:</td>
							    <td><input type="checkbox" name="Brandix" value="Y" <?= (($sBrandix == "Y") ? "checked" : "") ?> /></td>
							  </tr>

							  <tr>
							    <td>Parent</td>
							    <td align="center">:</td>

							    <td>
								  <select name="Parent">
								    <option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            	  	  <option value="<?= $sKey ?>"<?= (($sKey == $iParent) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>

							  <tr>
							    <td>Vendor<span class="mandatory">*</span></td>
							    <td align="center">:</td>
							    <td><input type="text" name="Vendor" value="<?= $sVendor ?>" size="30" maxlength="100" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Code</td>
							    <td align="center">:</td>
							    <td><input type="text" name="Code" value="<?= $sCode ?>" size="15" maxlength="25" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Company</td>
							    <td align="center">:</td>
							    <td><input type="text" name="Company" value="<?= $sCompany ?>" size="30" maxlength="100" class="textbox" /></td>
							  </tr>

							  <tr valign="top">
							    <td>Address</td>
							    <td align="center">:</td>
							    <td><textarea name="Address" rows="5" cols="30"><?= $sAddress ?></textarea></td>
							  </tr>

							  <tr>
							    <td>City<span class="mandatory">*</span></td>
							    <td align="center">:</td>
							    <td><input type="text" name="City" value="<?= $sCity ?>" size="30" maxlength="100" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Country<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
								  <select name="Country">
								    <option value=""></option>
<?
		foreach ($sCountriesList as $sKey => $sValue)
		{
?>
			                  	    <option value="<?= $sKey ?>"<?= (($sKey == $iCountry) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>

							  <tr>
							    <td>Category<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
								  <select name="Category">
								    <option value=""></option>
<?
		foreach ($sCategoriesList as $sKey => $sValue)
		{
?>
			            	  	  <option value="<?= $sKey ?>"<?= (($sKey == $iCategory) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>

							  <tr>
							    <td>Part of BTX Division</td>
							    <td align="center">:</td>
							    <td><input type="checkbox" name="BtxDivision" value="Y" <?= (($sBtxDivision == "Y") ? "checked" : "") ?> /></td>
							  </tr>

							  <tr>
							    <td>Daily Capacity<span class="mandatory">*</span></td>
							    <td align="center">:</td>
							    <td><input type="text" name="DailyCapacity" value="<?= $fDailyCapacity ?>" size="15" maxlength="10" class="textbox" /> (No of Pcs)</td>
							  </tr>

							  <tr>
							    <td>Daily Knitting Capacity</td>
							    <td align="center">:</td>
							    <td><input type="text" name="DailyKnittingCapacity" value="<?= $fDailyKnittingCapacity ?>" size="15" maxlength="10" class="textbox" /> (Kgs)</td>
							  </tr>

							  <tr>
							    <td>Daily Dyeing Capacity</td>
							    <td align="center">:</td>
							    <td><input type="text" name="DailyDyeingCapacity" value="<?= $fDailyDyeingCapacity ?>" size="15" maxlength="10" class="textbox" /> (Kgs)</td>
							  </tr>

							  <tr>
							    <td>Daily Cutting Capacity</td>
							    <td align="center">:</td>
							    <td><input type="text" name="DailyCuttingCapacity" value="<?= $fDailyCuttingCapacity ?>" size="15" maxlength="10" class="textbox" /> (No of Pcs)</td>
							  </tr>

							  <tr>
							    <td>Daily Stitching Capacity</td>
							    <td align="center">:</td>
							    <td><input type="text" name="DailyStitchingCapacity" value="<?= $fDailyStitchingCapacity ?>" size="15" maxlength="10" class="textbox" /> (No of Pcs)</td>
							  </tr>

							  <tr>
							    <td>Daily Packing Capacity</td>
							    <td align="center">:</td>
							    <td><input type="text" name="DailyPackingCapacity" value="<?= $fDailyPackingCapacity ?>" size="15" maxlength="10" class="textbox" /> (No of Cartons)</td>
							  </tr>

							  <tr>
							    <td>Batch DR</td>
							    <td align="center">:</td>
							    <td><input type="text" name="BatchDr" value="<?= $fBatchDr ?>" size="15" maxlength="5" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Cutting DR</td>
							    <td align="center">:</td>
							    <td><input type="text" name="CuttingDr" value="<?= $fCuttingDr ?>" size="15" maxlength="5" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Final DR</td>
							    <td align="center">:</td>
							    <td><input type="text" name="FinalDr" value="<?= $fFinalDr ?>" size="15" maxlength="5" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Output DR</td>
							    <td align="center">:</td>
							    <td><input type="text" name="OutputDr" value="<?= $fOutputDr ?>" size="15" maxlength="5" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Sorting DR</td>
							    <td align="center">:</td>
							    <td><input type="text" name="SortingDr" value="<?= $fSortingDr ?>" size="15" maxlength="5" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Stitching DR</td>
							    <td align="center">:</td>
							    <td><input type="text" name="StitchingDr" value="<?= $fStitchingDr ?>" size="15" maxlength="5" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Finishing DR</td>
							    <td align="center">:</td>
							    <td><input type="text" name="FinishingDr" value="<?= $fFinishingDr ?>" size="15" maxlength="5" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Off Loom DR</td>
							    <td align="center">:</td>
							    <td><input type="text" name="OffLoomDr" value="<?= $fOffLoomDr ?>" size="15" maxlength="5" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Stock DR</td>
							    <td align="center">:</td>
							    <td><input type="text" name="StockDr" value="<?= $fStockDr ?>" size="15" maxlength="5" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Pre-Final DR</td>
							    <td align="center">:</td>
							    <td><input type="text" name="PreFinalDr" value="<?= $fPreFinalDr ?>" size="15" maxlength="5" class="textbox" /></td>
							  </tr>
                                                        
                                                <tr>
						  <td>Departments</td>
						  <td align="center">:</td>
                                                  <td>
                                                      <select name="Departments" multiple>
                                                          <option value=""></option>
<?
                                                           $sDepartments = getList("tbl_crc_departments", "id", "department");
                                                           
                                                           foreach($sDepartments as $iDepartment => $sDepartment)
                                                           {
?>
                                                          <option value="<?=$iDepartment?>" <?= @in_array($iDepartment ,$sDepartmentIds)?'selected':''?>><?=$sDepartment?></option>
                                                          
<?
                                                           }
?>
                                                      </select>
                                                  </td>
                                                </tr>

                                                  <tr>
                                                        <td>Management Representative</td>
                                                        <td align="center">:</td>
                                                        <td><input type="text" name="MngrRep" value="<?= $sManagerRep ?>" size="15" class="textbox" /></td>
                                                  </tr>  
                                                        
                                                  <tr valign="top">
                                                    <td>Production Steps</td>
                                                    <td align="center">:</td>
                                                    <td><textarea name="ProductionSteps" rows="3" cols="30" style="width:197px;"><?= $sProductionSteps ?></textarea></td>
                                                  </tr>   
                                                        
                                                    <tr valign="top">
                                                        <td>Phone</td>
                                                        <td align="center">:</td>
                                                        <td><input type="text" name="Phone" value="<?= $sPhone ?>" size="15" class="textbox" /></td>
                                                    </tr>    
						    </table>

						  </td>

						  <td width="48%">

						    <table border="0" cellpadding="3" cellspacing="0" width="100%">
							  <tr>
							    <td width="165">Date of Foundation</td>
							    <td width="20" align="center">:</td>
							    <td><input type="text" name="DateOfFoundation" value="<?= $sDateOfFoundation ?>" size="30" maxlength="50" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Product Range</td>
							    <td align="center">:</td>
							    <td><input type="text" name="ProductRange" value="<?= $sProductRange ?>" size="30" maxlength="250" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Ownership</td>
							    <td align="center">:</td>
							    <td><input type="text" name="Ownership" value="<?= $sOwnership ?>" size="30" maxlength="250" class="textbox" /></td>
							  </tr>

							  <tr valign="top">
							    <td>Production Capability</td>
							    <td align="center">:</td>
							    <td><textarea name="ProductionCapability" rows="3" cols="30" style="width:197px;"><?= $sProductionCapability ?></textarea></td>
							  </tr>

							  <tr>
							    <td>Factory/Construction Area</td>
							    <td align="center">:</td>
							    <td><input type="text" name="FactoryArea" value="<?= $sFactoryArea ?>" size="30" maxlength="250" class="textbox" /></td>
							  </tr>

							  <tr valign="top">
							    <td>Production Capacity</td>
							    <td align="center">:</td>
							    <td><input type="text" name="ProductionCapacity" value="<?= $sProductionCapacity ?>" size="30" maxlength="250" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Total Stitching Machines</td>
							    <td align="center">:</td>
							    <td><input type="text" name="StitchingMachines" value="<?= $sStitchingMachines ?>" size="30" maxlength="250" class="textbox" /></td>
							  </tr>

							  <tr valign="top">
							    <td>Active Customer(s)</td>
							    <td align="center">:</td>
							    <td><textarea name="ActiveCustomers" rows="3" cols="30" style="width:197px;"><?= $sActiveCustomers ?></textarea></td>
							  </tr>

							  <tr valign="top">
							    <td>Approved Customers</td>
							    <td align="center">:</td>
							    <td><textarea name="ApprovedCustomers" rows="3" cols="30" style="width:197px;"><?= $sApprovedCustomers ?></textarea></td>
							  </tr>

							  <tr>
							    <td>Permanent Employees</td>
							    <td align="center">:</td>
							    <td><input type="text" name="PermanentEmployees" value="<?= $iPermanentEmployees ?>" size="15" maxlength="10" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Male Employees (%)</td>
							    <td align="center">:</td>
							    <td><input type="text" name="MaleEmployees" value="<?= $fMaleEmployees ?>" size="15" maxlength="5" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Female Employees (%)</td>
							    <td align="center">:</td>
							    <td><input type="text" name="FemaleEmployees" value="<?= $fFemaleEmployees ?>" size="15" maxlength="5" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Certifications</td>
							    <td align="center">:</td>
							    <td><input type="text" name="Certifications" value="<?= $sCertifications ?>" size="30" maxlength="250" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>3rd Party Compliance Audits</td>
							    <td align="center">:</td>
							    <td><input type="text" name="ThirdPartyComplianceAudits" value="<?= $sThirdPartyComplianceAudits ?>" size="30" maxlength="250" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Annual Turnover (volume)</td>
							    <td align="center">:</td>
							    <td><input type="text" name="AnnualTurnoverVolume" value="<?= $sAnnualTurnoverVolume ?>" size="15" maxlength="250" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Annual Turnover (value)</td>
							    <td align="center">:</td>
							    <td><input type="text" name="AnnualTurnoverValue" value="<?= $sAnnualTurnoverValue ?>" size="15" maxlength="250" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Audit Time (per unit)</td>
							    <td align="center">:</td>
							    <td><input type="text" name="UnitAuditTime" value="<?= $iUnitAuditTime ?>" size="15" maxlength="2" class="textbox" /> (minutes)</td>
							  </tr>

							  <tr>
							    <td>Latitude</td>
							    <td align="center">:</td>
							    <td><input type="text" name="Latitude" id="Latitude<?= $Id ?>" value="<?= $sLatitude ?>" size="15" maxlength="35" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Longitude</td>
							    <td align="center">:</td>
							    <td><input type="text" name="Longitude" id="Longitude<?= $Id ?>" value="<?= $sLongitude ?>" size="15" maxlength="35" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td colspan="3">* <a href="data/google-latlong.php?Lat=Latitude<?= $Id ?>&Lon=Longitude<?= $Id ?>" class="lightview" rel="iframe" title="Vendor Latitude/Longitude :: :: width:780, height:561">Find Latitude/Longitude</a></td>
							  </tr>

							  <tr>
							    <td>CAP Certification No</td>
							    <td align="center">:</td>
							    <td><input type="text" name="CapNo" id="CapNo" value="<?= $sCapNo ?>" size="30" maxlength="50" class="textbox" /></td>
							  </tr>

							  <tr valign="top">
							    <td>ETD Manager(s)</td>
							    <td align="center">:</td>

							    <td>
								  <select name="EtdManagers[]" multiple size="8">
<?
		foreach ($sManagersList as $sKey => $sValue)
		{
?>
			            	  	    <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iEtdManagers)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>
                                                        
                                                  <tr valign="top">
						  <td>No Of Shifts</td>
						  <td align="center">:</td>

						  <td>
						    <select name="TotalShifts">
                                                            <option value="1" <?=($sTotalShifts == '1')?'selected':'';?>> Shift 1</option>
                                                            <option value="2" <?=($sTotalShifts == '2')?'selected':'';?>> Shift 2</option>
                                                            <option value="3" <?=($sTotalShifts == '3')?'selected':'';?>> Shift 3</option>
                                                    </select>
						  </td>
                                                  </tr>

                                                    <tr>
                                                          <td>Management Representative Email</td>
                                                          <td align="center">:</td>
                                                          <td><input type="text" name="MngrRepEmail" id="MngrRepEmail" value="<?= $sManagerRepEmail ?>" size="30" class="textbox" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Representative Picture (jpg)</td>
                                                        <td align="center">:</td>
                                                        <td><input type="file" name="RepPicture" id="Fax" value="" size="30" class="textbox" /></td>
                                                    </tr> 
                                                    <tr>
                                                        <td>Fax</td>
                                                        <td align="center">:</td>
                                                        <td><input type="text" name="Fax" id="Fax" value="<?= $sFax ?>" size="30" class="textbox" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="140">Vendor Type</td>
                                                        <td width="20" align="center">:</td>
                                                        <td>
                                                            <input type="checkbox" name="TypeLevis" value="Y" <?= (($sTypeLevis == "Y") ? "checked" : "") ?> />Levis &nbsp;
                                                            <input type="checkbox" name="TypeMgf" value="Y" <?= (($sTypeMgf == "Y") ? "checked" : "") ?> />Mgf &nbsp;
                                                            <input type="checkbox" name="TypeGlobal" value="Y" <?= (($sTypeGlobal == "Y") ? "checked" : "") ?> />Global &nbsp;
                                                        </td>
                                                      </tr>
																								    </tr>	                                                      
						    </table>

						  </td>
					    </tr>
					  </table>
                            
                            <br/>
                                <h2>Additional Information <span id="SpanId" style="width: 50px; border: 1px solid white; padding: 1px; cursor: pointer;">+</span></h2>
                                <div id="AdditionalInfoId" style="display: none;">
                                <table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="52%">

                                            <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                                <tr>
                                                    <td width="150">Factory Address (if changed?)</td>
                                                    <td width="20" align="center">:</td>
                                                    <td><textarea name="ChangeAddress" rows="5" cols="30" style="width:197px;"><?= $ChangeAddresss ?></textarea></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td>Factory CR Contact Name</td>
						  <td align="center">:</td>
						  <td><input type="text" name="FactoryCrName" value="<?= $FactoryCrName ?>" size="26" class="textbox" /></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td>Factory CR Contact Phone</td>
						  <td align="center">:</td>
						  <td><input type="text" name="FactoryCrPhone" value="<?= $FactoryCrPhone ?>" size="26" class="textbox" /></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td>Factory CR Contact Email</td>
						  <td align="center">:</td>
						  <td><input type="text" name="FactoryCrEmail" value="<?= $FactoryCrEmail ?>" size="26" class="textbox" /></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td>Factory Ownership</td>
						  <td align="center">:</td>
                                                  <td>
                                                      <select nme="FactoryOwn" style="width:200px;">
                                                          <option value=""></option>
                                                          <option <?=($FactoryOwn == 'O'?'selected':'')?> value="O">Owned</option>
                                                          <option <?=($FactoryOwn == 'R'?'selected':'')?> value="R">Rented</option>
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td>Total Employees</td>
						  <td align="center">:</td>
						  <td><input type="text" name="TotalEmployees" value="<?= $TotalEmployees ?>" size="26" class="textbox" /></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td>Temporary Employees</td>
						  <td align="center">:</td>
						  <td><input type="text" name="TemporaryEmployees" value="<?= $TemporaryEmployees ?>" size="26" class="textbox" /></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td>Contractual Employees</td>
						  <td align="center">:</td>
						  <td><input type="text" name="ContractualEmployees" value="<?= $ContractualEmployees ?>" size="26" class="textbox" /></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td>Peak Season Month</td>
						  <td align="center">:</td>
                                                  <td>
                                                      <select nme="PeakMonth" style="width: 200px;">
                                                          <option value=""></option>
                                                          <option <?=($PeakMonth == '1'?'selected':'')?> value="1">January</option>
                                                          <option <?=($PeakMonth == '2'?'selected':'')?> value="2">February</option>
                                                          <option <?=($PeakMonth == '3'?'selected':'')?> value="3">March</option>
                                                          <option <?=($PeakMonth == '4'?'selected':'')?> value="4">April</option>
                                                          <option <?=($PeakMonth == '5'?'selected':'')?> value="5">May</option>
                                                          <option <?=($PeakMonth == '6'?'selected':'')?> value="6">June</option>
                                                          <option <?=($PeakMonth == '7'?'selected':'')?> value="7">July</option>
                                                          <option <?=($PeakMonth == '8'?'selected':'')?> value="8">August</option>
                                                          <option <?=($PeakMonth == '9'?'selected':'')?> value="9">September</option>
                                                          <option <?=($PeakMonth == '10'?'selected':'')?> value="10">Ocober</option>
                                                          <option <?=($PeakMonth == '11'?'selected':'')?> value="11">November</option>
                                                          <option <?=($PeakMonth == '12'?'selected':'')?> value="12">December</option>                                                          
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td>Low Season Month</td>
						  <td align="center">:</td>
                                                  <td>
                                                      <select nme="LowMonth" style="width: 200px;">
                                                          <option value=""></option>
                                                          <option <?=($LowMonth == '1'?'selected':'')?> value="1">January</option>
                                                          <option <?=($LowMonth == '2'?'selected':'')?> value="2">February</option>
                                                          <option <?=($LowMonth == '3'?'selected':'')?> value="3">March</option>
                                                          <option <?=($LowMonth == '4'?'selected':'')?> value="4">April</option>
                                                          <option <?=($LowMonth == '5'?'selected':'')?> value="5">May</option>
                                                          <option <?=($LowMonth == '6'?'selected':'')?> value="6">June</option>
                                                          <option <?=($LowMonth == '7'?'selected':'')?> value="7">July</option>
                                                          <option <?=($LowMonth == '8'?'selected':'')?> value="8">August</option>
                                                          <option <?=($LowMonth == '9'?'selected':'')?> value="9">September</option>
                                                          <option <?=($LowMonth == '10'?'selected':'')?> value="10">Ocober</option>
                                                          <option <?=($LowMonth == '11'?'selected':'')?> value="11">November</option>
                                                          <option <?=($LowMonth == '12'?'selected':'')?> value="12">December</option>                                                          
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td>Age of Facility & Manufacturing Operations</td>
						  <td align="center">:</td>
						  <td><input type="text" name="ManufactAge" value="<?= $ManufactAge ?>" size="26" class="textbox" /></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td>Monthly Employee Turnover</td>
						  <td align="center">:</td>
						  <td><input type="text" name="EmployeeTurnover" value="<?= $EmployeeTurnover ?>" size="26" class="textbox" /></td>
                                                </tr>
                                               
                                                <tr valign="top">
						  <td>Is a copy of Restricted Substances List (RSL) policy available for review?</td>
						  <td align="center">:</td>
                                                  <td>
                                                      <select nme="RSLPolicy"  style="width: 200px;">
                                                          <option value=""></option>
                                                          <option <?=($RSLPolicy == 'Y'?'selected':'')?> value="Y">Available</option>
                                                          <option <?=($RSLPolicy == 'N'?'selected':'')?> value="N">Not Available</option>
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td>Is there a process to ensure RSL compliant materials are used?</td>
						  <td align="center">:</td>
                                                  <td>
                                                      <select nme="RSLCompliant"  style="width: 200px;">
                                                          <option value=""></option>
                                                          <option <?=($RSLCompliant == 'Y'?'selected':'')?> value="Y">Available</option>
                                                          <option <?=($RSLCompliant == 'N'?'selected':'')?> value="N">Not Available</option>
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td width="150">Major Buyer(s)</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="MajorBuyer" value="<?= $MajorBuyer ?>" size="26" class="textbox" /></td>
                                                </tr>
                                                
                                                <tr valign="top">
                                                    <td>Does the factory use subcontractors? i.e <span style="font-size: 8px;">(Fabric processing, embelishment, embroidery, Printing, Garment wash)</span></td>
						  <td align="center">:</td>
						  <td><textarea name="SubContractors" rows="5" cols="30" style="width:197px;"><?= $SubContractors ?></textarea></td>
                                                </tr>
                                                
                                            </table>
                                        </td>
                                      
                                        <td width="48%">

                                            <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                                <tr valign="top">
						  <td width="300">Beyond Compliance initiatives OR Best Practices?</td>
						  <td width="20" align="center">:</td>
                                                  <td>
                                                      <select name="Practices"  style="width: 50px;">
                                                          <option value=""></option>
                                                          <option <?=($Practices == 'C'?'selected':'')?> value="C">Beyond Compliance Initative</option>
                                                          <option <?=($Practices == 'B'?'selected':'')?> value="B">Best Practices</option>
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td width="150">Is there any apprentice program in the factory?</td>
						  <td width="20" align="center">:</td>
						  <td>
                                                      <select name="ApprenticeProgram">
                                                          <option value=""></option>
                                                          <option <?=($ApprenticeProgram == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                          <option <?=($ApprenticeProgram == 'N'?'selected':'')?> value="N">No</option>
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td width="150">Are there any formal/informal communication channels (Worker Committee or work Council)?</td>
						  <td width="20" align="center">:</td>
						  <td>
                                                      <select name="CommunicationChannel">
                                                          <option value=""></option>
                                                          <option <?=($CommunicationChannel == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                          <option <?=($CommunicationChannel == 'N'?'selected':'')?> value="N">No</option>
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td width="150">Do workers receive documented oriemntation at the time of hiring?</td>
						  <td width="20" align="center">:</td>
						  <td>
                                                      <select name="Documentation">
                                                          <option value=""></option>
                                                          <option <?=($Documentation == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                          <option <?=($Documentation == 'N'?'selected':'')?> value="N">No</option>
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td width="150">Does the factory provide Gratuity or PF benefits to its workers?</td>
						  <td width="20" align="center">:</td>
						  <td>
                                                      <select name="FundBenefits">
                                                          <option value=""></option>
                                                          <option <?=($FundBenefits == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                          <option <?=($FundBenefits == 'N'?'selected':'')?> value="N">No</option>
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td width="150">Are there multi-storey buildings where factory occupies only a portion of the facility?</td>
						  <td width="20" align="center">:</td>
						  <td>
                                                      <select name="PortionFacility">
                                                          <option value=""></option>
                                                          <option <?=($PortionFacility == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                          <option <?=($PortionFacility == 'N'?'selected':'')?> value="N">No</option>
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td width="150">Are there any hazardous chemicals used at this factory?</td>
						  <td width="20" align="center">:</td>
						  <td>
                                                      <select name="HazardousChemicals">
                                                          <option value=""></option>
                                                          <option <?=($HazardousChemicals == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                          <option <?=($HazardousChemicals == 'N'?'selected':'')?> value="N">No</option>
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td width="150">Does this factory generate any wastewater that requires treatment?</td>
						  <td width="20" align="center">:</td>
						  <td>
                                                      <select name="WasteWater">
                                                          <option value=""></option>
                                                          <option <?=($WasteWater == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                          <option <?=($WasteWater == 'N'?'selected':'')?> value="N">No</option>
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td width="150">Is there a canteen in the factory?</td>
						  <td width="20" align="center">:</td>
						  <td>
                                                      <select name="Canteen">
                                                          <option value=""></option>
                                                          <option <?=($Canteen == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                          <option <?=($Canteen == 'N'?'selected':'')?> value="N">No</option>
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td width="150">Does the factory provide childcare?</td>
						  <td width="20" align="center">:</td>
						  <td>
                                                      <select name="ChildCare">
                                                          <option value=""></option>
                                                          <option <?=($ChildCare == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                          <option <?=($ChildCare == 'N'?'selected':'')?> value="N">No</option>
                                                      </select>
                                                  </td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td width="150">Does the factory provide onsite or factory owned offiste dormotories?</td>
						  <td width="20" align="center">:</td>
						  <td>
                                                      <select name="Dormotories">
                                                          <option value=""></option>
                                                          <option <?=($Dormotories == 'Y'?'selected':'')?> value="Y">Yes</option>
                                                          <option <?=($Dormotories == 'N'?'selected':'')?> value="N">No</option>
                                                      </select>
                                                  </td>
                                                </tr>                                                
                                            </table>
                                        </td>
                                  </tr>
                                </table>
                                </div>
                                
                                <br/>

					  <div style="padding:10px;">
					    Vendor Profile<br />
					    <textarea name="Profile" rows="8" cols="30" style="width:99%;"><?= $sProfile ?></textarea><br />
					  </div>

			    <div class="buttonsBar">
			      <input type="submit" id="BtnSave" value="" class="btnSave" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnBack" onclick="document.location='./data/vendors.php';" />
			    </div>
			    </form>

			    <br />
			    <b>Note:</b> Fields marked with an asterisk (*) are required.<br/>
			  </td>
			</tr>
		  </table>

<?
	@include($sBaseDir."includes/my-profile.php");
?>
        </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include($sBaseDir."includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

      </div>
    </div>
  </div>
</div>

<!--  Bottom Bar Section Starts Here  -->
<?
	@include($sBaseDir."includes/bottom-bar.php");
?>
<!--  Bottom Bar Section Ends Here  -->

<script> 
    var button = document.getElementById('SpanId'); 

    button.onclick = function() {
        var div = document.getElementById('AdditionalInfoId');
        if (div.style.display !== 'none') {
            div.style.display = 'none';
            document.getElementById('SpanId').innerHTML = "+";
        }
        else {
            div.style.display = 'block';
            document.getElementById('SpanId').innerHTML = "-";
        }
    };
</script>
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>