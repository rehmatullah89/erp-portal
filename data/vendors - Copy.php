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

	$PageId         = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Vendor         = IO::strValue("Vendor");
	$Category       = IO::intValue("Category");
	$Country        = IO::strValue("Country");
        $Departments    = IO::getArray("Departments"); 
        $MngrRep        = IO::strValue("MngrRep");
        $MngrRepEmail   = IO::strValue("MngrRepEmail");
        $TotalShifts    = IO::strValue("TotalShifts");
        $ProductionSteps= IO::strValue("ProductionSteps");
	$PostId         = IO::strValue("PostId");
        $Phone          = IO::strValue("Phone");
        $Fax            = IO::strValue("Fax");
        
        

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Sourcing                   = IO::strValue("Sourcing");
		$Pcc                        = IO::strValue("Pcc");
		$Parent                     = IO::intValue("Parent");
		$Vendor                     = IO::strValue("Vendor");
		$Code                       = IO::strValue("Code");
		$Company                    = IO::strValue("Company");
		$City                       = IO::strValue("City");
		$Address                    = IO::strValue("Address");
		$Category                   = IO::intValue("Category");
		$BtxDivision                = IO::strValue("BtxDivision");
		$Country                    = IO::strValue("Country");
		$DailyCapacity              = IO::strValue("DailyCapacity");
		$DailyKnittingCapacity      = IO::strValue("DailyKnittingCapacity");
		$DailyDyeingCapacity        = IO::strValue("DailyDyeingCapacity");
		$DailyCuttingCapacity       = IO::strValue("DailyCuttingCapacity");
		$DailyStitchingCapacity     = IO::strValue("DailyStitchingCapacity");
		$DailyPackingCapacity       = IO::strValue("DailyPackingCapacity");
		$DateOfFoundation           = IO::strValue("DateOfFoundation");
		$ProductRange               = IO::strValue("ProductRange");
		$Ownership                  = IO::strValue("Ownership");
		$ProductionCapability       = IO::strValue("ProductionCapability");
		$FactoryArea                = IO::strValue("FactoryArea");
		$ProductionCapacity         = IO::strValue("ProductionCapacity");
		$StitchingMachines          = IO::strValue("StitchingMachines");
		$ActiveCustomers            = IO::strValue("ActiveCustomers");
		$ApprovedCustomers          = IO::strValue("ApprovedCustomers");
		$PermanentEmployees         = IO::strValue("PermanentEmployees");
		$MaleEmployees              = IO::strValue("MaleEmployees");
		$FemaleEmployees            = IO::strValue("FemaleEmployees");
		$Certifications             = IO::strValue("Certifications");
		$ThirdPartyComplianceAudits = IO::strValue("ThirdPartyComplianceAudits");
		$AnnualTurnoverVolume       = IO::strValue("AnnualTurnoverVolume");
		$AnnualTurnoverValue        = IO::strValue("AnnualTurnoverValue");
		$BatchDr                    = IO::strValue("BatchDr");
		$CuttingDr                  = IO::strValue("CuttingDr");
		$FinalDr                    = IO::strValue("FinalDr");
		$OutputDr                   = IO::strValue("OutputDr");
		$SortingDr                  = IO::strValue("SortingDr");
		$StitchingDr                = IO::strValue("StitchingDr");
		$FinishingDr                = IO::strValue("FinishingDr");
		$OffLoomDr                  = IO::strValue("OffLoomDr");
		$StockDr                    = IO::strValue("StockDr");
		$PreFinalDr                 = IO::strValue("PreFinalDr");
		$UnitAuditTime              = IO::intValue("UnitAuditTime");
		$Latitude                   = IO::strValue("Latitude");
		$Longitude                  = IO::strValue("Longitude");
		$CapNo                      = IO::strValue("CapNo");
		$Brandix                    = IO::strValue("Brandix");
		$EtdManagers                = IO::getArray("EtdManagers");
		$Profile                    = IO::strValue("Profile");
                $Departments                = IO::getArray("Departments");
                $MngrRep                    = IO::strValue("MngrRep"); 
                $ProductionSteps            = IO::strValue("ProductionSteps");
                $Phone                      = IO::strValue("Phone");
                $Fax                        = IO::strValue("Fax");
	}

	$sCountriesList  = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sCategoriesList = getList("tbl_categories", "id", "category");
	$sManagersList   = getList("tbl_users", "id", "name", "id IN (13,33,39,56,84,233,313,588,687,680,587)");
	$sVendorsList    = getList("tbl_vendors", "id", "vendor", "parent_id='0'");

	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
		$sVendorsList = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND id IN ({$_SESSION['Vendors']})");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/vendors.js"></script>
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
			    <h1><img src="images/h1/data/vendors-listing.jpg" width="227" height="20" vspace="10" alt="" title="" /></h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-vendor.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Vendor</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="52%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Sourcing Vendor</td>
						  <td width="20" align="center">:</td>
						  <td><input type="checkbox" name="Sourcing" value="Y" <?= (($Sourcing == "Y") ? "checked" : "") ?> /></td>
					    </tr>

					    <tr>
						  <td>PCC Vendor</td>
						  <td align="center">:</td>
						  <td><input type="checkbox" name="Pcc" value="Y" <?= (($Pcc == "Y") ? "checked" : "") ?> /></td>
					    </tr>

					    <tr>
						  <td>Brandix Vendor</td>
						  <td align="center">:</td>
						  <td><input type="checkbox" name="Brandix" value="Y" <?= (($Brandix == "Y") ? "checked" : "") ?> /></td>
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
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Parent) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  		</select>
						  </td>
					    </tr>

					    <tr>
						  <td>Vendor<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Vendor" value="<?= $Vendor ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>Code</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Code" value="<?= $Code ?>" size="15" maxlength="25" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Company</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Company" value="<?= $Company ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>Address</td>
						  <td align="center">:</td>
						  <td><textarea name="Address" rows="5" cols="30" style="width:197px;"><?= $Address ?></textarea></td>
					    </tr>

					    <tr>
						  <td>City<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="City" value="<?= $City ?>" size="30" maxlength="100" class="textbox" /></td>
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
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Country) ? " selected" : "") ?>><?= $sValue ?></option>
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
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Part of BTX Division</td>
						  <td align="center">:</td>
						  <td><input type="checkbox" name="BtxDivision" value="Y" <?= (($BtxDivision == "Y") ? "checked" : "") ?> /></td>
					    </tr>

					    <tr>
						  <td>Daily Capacity<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="DailyCapacity" value="<?= $DailyCapacity ?>" size="15" maxlength="10" class="textbox" /> (No of Pcs)</td>
					    </tr>

					    <tr>
						  <td>Daily Knitting Capacity</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DailyKnittingCapacity" value="<?= $DailyKnittingCapacity ?>" size="15" maxlength="10" class="textbox" /> (Kgs)</td>
					    </tr>

					    <tr>
						  <td>Daily Dyeing Capacity</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DailyDyeingCapacity" value="<?= $DailyDyeingCapacity ?>" size="15" maxlength="10" class="textbox" /> (Kgs)</td>
					    </tr>

					    <tr>
						  <td>Daily Cutting Capacity</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DailyCuttingCapacity" value="<?= $DailyCuttingCapacity ?>" size="15" maxlength="10" class="textbox" /> (No of Pcs)</td>
					    </tr>

					    <tr>
						  <td>Daily Stitching Capacity</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DailyStitchingCapacity" value="<?= $DailyStitchingCapacity ?>" size="15" maxlength="10" class="textbox" /> (No of Pcs)</td>
					    </tr>

					    <tr>
						  <td>Daily Packing Capacity</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DailyPackingCapacity" value="<?= $DailyPackingCapacity ?>" size="15" maxlength="10" class="textbox" /> (No of Cartons)</td>
					    </tr>

					    <tr>
						  <td>Batch DR</td>
						  <td align="center">:</td>
						  <td><input type="text" name="BatchDr" value="<?= $BatchDr ?>" size="15" maxlength="5" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Cutting DR</td>
						  <td align="center">:</td>
						  <td><input type="text" name="CuttingDr" value="<?= $CuttingDr ?>" size="15" maxlength="5" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Final DR</td>
						  <td align="center">:</td>
						  <td><input type="text" name="FinalDr" value="<?= $FinalDr ?>" size="15" maxlength="5" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Output DR</td>
						  <td align="center">:</td>
						  <td><input type="text" name="OutputDr" value="<?= $OutputDr ?>" size="15" maxlength="5" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Sorting DR</td>
						  <td align="center">:</td>
						  <td><input type="text" name="SortingDr" value="<?= $SortingDr ?>" size="15" maxlength="5" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Stitching DR</td>
						  <td align="center">:</td>
						  <td><input type="text" name="StitchingDr" value="<?= $StitchingDr ?>" size="15" maxlength="5" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Finishing DR</td>
						  <td align="center">:</td>
						  <td><input type="text" name="FinishingDr" value="<?= $FinishingDr ?>" size="15" maxlength="5" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Off Loom DR</td>
						  <td align="center">:</td>
						  <td><input type="text" name="OffLoomDr" value="<?= $OffLoomDr ?>" size="15" maxlength="5" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Stock DR</td>
						  <td align="center">:</td>
						  <td><input type="text" name="StockDr" value="<?= $StockDr ?>" size="15" maxlength="5" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Pre-Final DR</td>
						  <td align="center">:</td>
						  <td><input type="text" name="PreFinalDr" value="<?= $PreFinalDr ?>" size="15" maxlength="5" class="textbox" /></td>
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
                                                          <option value="<?=$iDepartment?>" <?= @in_array($iDepartment ,$Departments)?'selected':''?>><?=$sDepartment?></option>
                                                          
<?
                                                           }
?>
                                                      </select>
                                                  </td>
					    </tr>
                                            
                                            <tr>
						  <td>Management Representative</td>
						  <td align="center">:</td>
						  <td><input type="text" name="MngrRep" value="<?= $MngrRep ?>" size="15" class="textbox" /></td>
					    </tr>
                                              
                                            <tr valign="top">
						  <td>Production Steps</td>
						  <td align="center">:</td>
						  <td><textarea name="ProductionSteps" rows="3" cols="30" style="width:197px;"><?= $ProductionSteps ?></textarea></td>
					    </tr>
                                            <tr valign="top">
						  <td>Phone</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Phone" value="<?= $Phone ?>" size="15" class="textbox" /></td>
					    </tr>
					  </table>

					</td>

					<td width="48%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="165">Date of Foundation</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="DateOfFoundation" value="<?= $DateOfFoundation ?>" size="30" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Product Range</td>
						  <td align="center">:</td>
						  <td><input type="text" name="ProductRange" value="<?= $ProductRange ?>" size="30" maxlength="250" class="textbox" /></td>
					    </tr>

					    <tr>
					  	  <td>Ownership</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Ownership" value="<?= $Ownership ?>" size="30" maxlength="250" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>Production Capability</td>
						  <td align="center">:</td>
						  <td><textarea name="ProductionCapability" rows="3" cols="30" style="width:197px;"><?= $ProductionCapability ?></textarea></td>
					    </tr>

					    <tr>
						  <td>Factory/Construction Area</td>
						  <td align="center">:</td>
						  <td><input type="text" name="FactoryArea" value="<?= $FactoryArea ?>" size="30" maxlength="250" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>Production Capacity</td>
						  <td align="center">:</td>
						  <td><input type="text" name="ProductionCapacity" value="<?= $ProductionCapacity ?>" size="30" maxlength="250" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Total Stitching Machines</td>
						  <td align="center">:</td>
						  <td><input type="text" name="StitchingMachines" value="<?= $StitchingMachines ?>" size="30" maxlength="250" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>Active Customers</td>
						  <td align="center">:</td>
						  <td><textarea name="ActiveCustomers" rows="3" cols="30" style="width:197px;"><?= $ActiveCustomers ?></textarea></td>
					    </tr>

					    <tr valign="top">
						  <td>Approved Customers</td>
						  <td align="center">:</td>
						  <td><textarea name="ApprovedCustomers" rows="3" cols="30" style="width:197px;"><?= $ApprovedCustomers ?></textarea></td>
					    </tr>

					    <tr>
						  <td>Permanent Employees</td>
						  <td align="center">:</td>
						  <td><input type="text" name="PermanentEmployees" value="<?= $PermanentEmployees ?>" size="15" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Male Employees (%)</td>
						  <td align="center">:</td>
						  <td><input type="text" name="MaleEmployees" value="<?= $MaleEmployees ?>" size="15" maxlength="5" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Female Employees (%)</td>
						  <td align="center">:</td>
						  <td><input type="text" name="FemaleEmployees" value="<?= $FemaleEmployees ?>" size="15" maxlength="5" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Certifications</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Certifications" value="<?= $Certifications ?>" size="30" maxlength="250" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>3rd Party Compliance Audits</td>
						  <td align="center">:</td>
						  <td><input type="text" name="ThirdPartyComplianceAudits" value="<?= $ThirdPartyComplianceAudits ?>" size="30" maxlength="250" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Annual Turnover (volume)</td>
						  <td align="center">:</td>
						  <td><input type="text" name="AnnualTurnoverVolume" value="<?= $AnnualTurnoverVolume ?>" size="15" maxlength="250" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Annual Turnover (value)</td>
						  <td align="center">:</td>
						  <td><input type="text" name="AnnualTurnoverValue" value="<?= $AnnualTurnoverValue ?>" size="15" maxlength="250" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Audit Time (per unit)</td>
						  <td align="center">:</td>
						  <td><input type="text" name="UnitAuditTime" value="<?= $UnitAuditTime ?>" size="15" maxlength="2" class="textbox" /> (minutes)</td>
					    </tr>

					    <tr>
						  <td>Latitude</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Latitude" id="Latitude" value="<?= $Latitude ?>" size="15" maxlength="35" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Longitude</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Longitude" id="Longitude" value="<?= $Longitude ?>" size="15" maxlength="35" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td colspan="3">* <a href="data/google-latlong.php?Lat=Latitude&Lon=Longitude" class="lightview" rel="iframe" title="Vendor Latitude/Longitude :: :: width:780, height:561">Find Latitude/Longitude</a></td>
					    </tr>

					    <tr>
						  <td>CAP Certification No</td>
						  <td align="center">:</td>
						  <td><input type="text" name="CapNo" id="CapNo" value="<?= $CapNo ?>" size="30" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>ETD Manager(s)</td>
						  <td align="center">:</td>

						  <td>
						    <select name="EtdManagers" multiple size="8">
<?
		foreach ($sManagersList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $EtdManagers)) ? " selected" : "") ?>><?= $sValue ?></option>
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
                                                            <option value="1" <?=($TotalShifts == '1')?'selected':'';?>> Shift 1</option>
                                                            <option value="2" <?=($TotalShifts == '2')?'selected':'';?>> Shift 2</option>
                                                            <option value="3" <?=($TotalShifts == '3')?'selected':'';?>> Shift 3</option>
                                                    </select>
						  </td>
					    </tr>
                                             
                                            <tr>
						  <td>Management Representative Email</td>
						  <td align="center">:</td>
						  <td><input type="text" name="MngrRepEmail" id="MngrRepEmail" value="<?= $MngrRepEmail ?>" size="30" class="textbox" /></td>
					    </tr>  
                                            <tr>
						  <td>Fax</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Fax" id="Fax" value="<?= $Fax ?>" size="30" class="textbox" /></td>
					    </tr>    
					  </table>

					</td>
				  </tr>
				</table>

				<div style="padding:10px;">
				  Vendor Profile<br />
				  <textarea name="Profile" rows="8" cols="30" style="width:99%;"><?= $Profile ?></textarea><br />
				</div>

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="55">Vendor</td>
			          <td width="170"><input type="text" name="Vendor" value="<?= $Vendor ?>" class="textbox" maxlength="50" /></td>
			          <td width="70">Category</td>

			          <td width="150">
					    <select name="Category">
						  <option value="">All Categories</option>
<?
	foreach ($sCategoriesList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td width="60">Country</td>

			          <td width="150">
					    <select name="Country">
						  <option value="">All Countries</option>
<?
	foreach ($sCountriesList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Country) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Vendor != "")
		$sConditions .= " AND (vendor LIKE '%$Vendor%' OR city LIKE '%$Vendor%') ";

	if ($Category != "")
		$sConditions .= " AND category_id='$Category' ";

	if ($Country > 0)
		$sConditions .= " AND country_id='$Country' ";

	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
		$sConditions .= " AND id IN ({$_SESSION['Vendors']}) ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_vendors", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_vendors $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="20%">Vendor</td>
				      <td width="10%">Code</td>
				      <td width="18%">Category</td>
				      <td width="15%">City</td>
				      <td width="17%">Country</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}


		$iId                         = $objDb->getField($i, 'id');
		$sSourcing                   = $objDb->getField($i, 'sourcing');
		$sPcc                        = $objDb->getField($i, 'pcc');
		$sBrandix                    = $objDb->getField($i, 'brandix');
		$iParent                     = $objDb->getField($i, 'parent_id');
		$sVendor                     = $objDb->getField($i, 'vendor');
		$sCode                       = $objDb->getField($i, 'code');
		$sCompany                    = $objDb->getField($i, 'company');
		$sCity                       = $objDb->getField($i, 'city');
		$sAddress                    = $objDb->getField($i, 'address');
		$iCategory                   = $objDb->getField($i, 'category_id');
		$sBtxDivision                = $objDb->getField($i, 'btx_division');
		$iCountry                    = $objDb->getField($i, 'country_id');
		$fDailyCapacity              = $objDb->getField($i, 'daily_capacity');
		$fDailyKnittingCapacity      = $objDb->getField($i, 'daily_knitting_capacity');
		$fDailyDyeingCapacity        = $objDb->getField($i, 'daily_dyeing_capacity');
		$fDailyCuttingCapacity       = $objDb->getField($i, 'daily_cutting_capacity');
		$fDailyStitchingCapacity     = $objDb->getField($i, 'daily_stitching_capacity');
		$fDailyPackingCapacity       = $objDb->getField($i, 'daily_packing_capacity');
		$sDateOfFoundation           = $objDb->getField($i, 'date_of_foundation');
		$sProductRange               = $objDb->getField($i, 'product_range');
		$sOwnership                  = $objDb->getField($i, 'ownership');
		$sProductionCapability       = $objDb->getField($i, 'production_capability');
		$sFactoryArea                = $objDb->getField($i, 'factory_area');
		$sProductionCapacity         = $objDb->getField($i, 'production_capacity');
		$sStitchingMachines          = $objDb->getField($i, 'stitching_machines');
		$sActiveCustomers            = $objDb->getField($i, 'active_customers');
		$sApprovedCustomers          = $objDb->getField($i, 'approved_customers');
		$iPermanentEmployees         = $objDb->getField($i, 'permanent_employees');
		$fMaleEmployees              = $objDb->getField($i, 'male_employees');
		$fFemaleEmployees            = $objDb->getField($i, 'female_employees');
		$sCertifications             = $objDb->getField($i, 'certifications');
		$sThirdPartyComplianceAudits = $objDb->getField($i, 'third_party_compliance_audits');
		$sAnnualTurnoverVolume       = $objDb->getField($i, 'annual_turnover_volume');
		$sAnnualTurnoverValue        = $objDb->getField($i, 'annual_turnover_value');
		$fBatchDr                    = $objDb->getField($i, 'batch_dr');
		$fCuttingDr                  = $objDb->getField($i, 'cutting_dr');
		$fFinalDr                    = $objDb->getField($i, 'final_dr');
		$fOutputDr                   = $objDb->getField($i, 'output_dr');
		$fSortingDr                  = $objDb->getField($i, 'sorting_dr');
		$fStitchingDr                = $objDb->getField($i, 'stitching_dr');
		$fFinishingDr                = $objDb->getField($i, 'finishing_dr');
		$fOffLoomDr                  = $objDb->getField($i, 'off_loom_dr');
		$fStockDr                    = $objDb->getField($i, 'stock_dr');
		$fPreFinalDr                 = $objDb->getField($i, 'pre_final_dr');
		$iUnitAuditTime              = $objDb->getField($i, 'unit_audit_time');
		$sLatitude                   = $objDb->getField($i, 'latitude');
		$sLongitude                  = $objDb->getField($i, 'longitude');
		$sCapNo                      = $objDb->getField($i, 'cap_no');
		$iEtdManagers                = @explode(",", $objDb->getField($i, 'etd_managers'));
		$sProfile                    = $objDb->getField($i, 'profile');
                $sDepartmentIds              = @explode(",", $objDb->getField($i, 'crc_departments'));
                $sManagerRep                 = $objDb->getField($i, 'manager_rep');
                $sManagerRepEmail            = $objDb->getField($i, 'manager_rep_email');
                $sTotalShifts                = $objDb->getField($i, 'total_shifts');
                $sProductionSteps            = $objDb->getField($i, 'production_steps');
                $sFax                        = $objDb->getField($i, 'fax');
                $sPhone                      = $objDb->getField($i, 'phone');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="20%"><span id="Vendor<?= $iId ?>"><?= $sVendor ?></span></td>
				      <td width="10%"><span id="Code<?= $iId ?>"><?= $sCode ?></span></td>
				      <td width="18%"><span id="Category<?= $iId ?>"><?= $sCategoriesList[$iCategory] ?></span></td>
				      <td width="15%"><span id="City<?= $iId ?>"><?= $sCity ?></span></td>
				      <td width="17%"><span id="Country<?= $iId ?>"><?= $sCountriesList[$iCountry] ?></span></td>

				      <td width="12%" class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-vendor.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Vendor?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="data/view-vendor.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Vendor : <?= $sVendor ?> :: :: width:700, height:550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

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
							    <td>Active Customers</td>
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
							    <td><input type="text" name="Latitude" id="Latitude<?= $iId ?>" value="<?= $sLatitude ?>" size="15" maxlength="35" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td>Longitude</td>
							    <td align="center">:</td>
							    <td><input type="text" name="Longitude" id="Longitude<?= $iId ?>" value="<?= $sLongitude ?>" size="15" maxlength="35" class="textbox" /></td>
							  </tr>

							  <tr>
							    <td colspan="3">* <a href="data/google-latlong.php?Lat=Latitude<?= $iId ?>&Lon=Longitude<?= $iId ?>" class="lightview" rel="iframe" title="Vendor Latitude/Longitude :: :: width:780, height:561">Find Latitude/Longitude</a></td>
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
								  <select name="EtdManagers" multiple size="8">
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
                                                        <td>Fax</td>
                                                        <td align="center">:</td>
                                                        <td><input type="text" name="Fax" id="Fax" value="<?= $sFax ?>" size="30" class="textbox" /></td>
                                                    </tr>
						    </table>

						  </td>
					    </tr>
					  </table>

					  <div style="padding:10px;">
					    Vendor Profile<br />
					    <textarea name="Profile" rows="8" cols="30" style="width:99%;"><?= $sProfile ?></textarea><br />
					  </div>

					  <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
					  <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
					  </form>

				    </div>
				  </div>

				  <div id="Msg<?= $iId ?>" class="msgOk" style="display:none;"></div>

<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Vendor Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Vendor={$Vendor}&Category={$Category}&Country={$Country}");
?>

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

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>