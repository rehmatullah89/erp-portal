<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT *,
	                (SELECT category FROM tbl_categories WHERE id=tbl_suppliers.category_id) AS _Category,
	                (SELECT country FROM tbl_countries WHERE id=tbl_suppliers.country_id) AS _Country,
	                (SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM tbl_users WHERE FIND_IN_SET(id, tbl_suppliers.etd_managers)) AS _EtdManager
	         FROM tbl_suppliers WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sSourcing                   = $objDb->getField(0, 'sourcing');
		$sPcc                        = $objDb->getField(0, 'pcc');
		$sBrandix                    = $objDb->getField(0, 'brandix');
		$sSupplier                     = $objDb->getField(0, "supplier");
		$sCode                       = $objDb->getField(0, "code");
		$sCompany                    = $objDb->getField(0, "company");
		$sCity                       = $objDb->getField(0, "city");
		$sAddress                    = $objDb->getField(0, "address");
		$sCategory                   = $objDb->getField(0, "_Category");
		$sBtxDivision                = $objDb->getField(0, 'btx_division');
		$sCountry                    = $objDb->getField(0, "_Country");
		$fLeadTime1000               = $objDb->getField(0, 'lead_time_1000pcs');
		$fLeadTime2500               = $objDb->getField(0, 'lead_time_2500pcs');
		$fLeadTime5000               = $objDb->getField(0, 'lead_time_5000pcs');
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
		$sEtdManager                 = $objDb->getField(0, '_EtdManager');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">
	  <h2>Supplier Details</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	    <tr>
		  <td width="165">Sourcing Supplier</td>
		  <td width="20" align="center">:</td>
		  <td><?= (($sSourcing == "Y") ? "Yes" : "No") ?></td>
	    </tr>

	    <tr>
		  <td>PCC Supplier</td>
		  <td align="center">:</td>
		  <td><?= (($sPcc == "Y") ? "Yes" : "No") ?></td>
	    </tr>

	    <tr>
		  <td>Brandix Supplier</td>
		  <td align="center">:</td>
		  <td><?= (($sBrandix == "Y") ? "Yes" : "No") ?></td>
	    </tr>

	    <tr>
		  <td>Supplier</td>
		  <td align="center">:</td>
		  <td><?= $sSupplier ?></td>
	    </tr>

	    <tr>
		  <td>Code</td>
		  <td align="center">:</td>
		  <td><?= $sCode ?></td>
	    </tr>

	    <tr>
		  <td>Company</td>
		  <td align="center">:</td>
		  <td><?= $sCompany ?></td>
	    </tr>

 	    <tr valign="top">
		  <td>Address</td>
		  <td align="center">:</td>
		  <td><?= nl2br($sAddress) ?></td>
	    </tr>

	    <tr>
		  <td>City</td>
		  <td align="center">:</td>
		  <td><?= $sCity ?></td>
	    </tr>

	    <tr>
		  <td>Country</td>
		  <td align="center">:</td>
		  <td><?= $sCountry ?></td>
	    </tr>

	    <tr>
		  <td>Category</td>
		  <td align="center">:</td>
		  <td><?= $sCategory ?></td>
	    </tr>

	    <tr>
		  <td>Part of BTX Division</td>
		  <td align="center">:</td>
		  <td><?= (($sBtxDivision == "Y") ? "Yes" : "No") ?></td>
	    </tr>

		<tr>
		  <td>Lead Time / 1000 Pcs</td>
		  <td align="center">:</td>
		  <td><?= $fLeadTime1000 ?></td>
		</tr>

		<tr>
		  <td>Lead Time / 2500 Pcs</td>
		  <td align="center">:</td>
		  <td><?= $fLeadTime2500 ?></td>
		</tr>

		<tr>
		  <td>Lead Time / 5000 Pcs</td>
		  <td align="center">:</td>
		  <td><?= $fLeadTime5000 ?></td>
		</tr>

		<tr>
		  <td>Daily Capacity</td>
		  <td align="center">:</td>
		  <td><?= $fDailyCapacity ?> (No of Pcs)</td>
		</tr>

		<tr>
		  <td>Daily Knitting Capacity</td>
		  <td align="center">:</td>
		  <td><?= $fDailyKnittingCapacity ?> (Kgs)</td>
		</tr>

		<tr>
		  <td>Daily Dyeing Capacity</td>
		  <td align="center">:</td>
		  <td><?= $fDailyDyeingCapacity ?> (Kgs)</td>
		</tr>

		<tr>
		  <td>Daily Cutting Capacity</td>
		  <td align="center">:</td>
		  <td><?= $fDailyCuttingCapacity ?> (No of Pcs)</td>
		</tr>

		<tr>
		  <td>Daily Stitching Capacity</td>
		  <td align="center">:</td>
		  <td><?= $fDailyStitchingCapacity ?> (No of Pcs)</td>
		</tr>

		<tr>
		  <td>Daily Packing Capacity</td>
		  <td align="center">:</td>
		  <td><?= $fDailyPackingCapacity ?> (No of Cartons)</td>
		</tr>

	    <tr>
		  <td>Batch DR</td>
		  <td align="center">:</td>
		  <td><?= $fBatchDr ?></td>
	    </tr>

	    <tr>
		  <td>Cutting DR</td>
		  <td align="center">:</td>
		  <td><?= $fCuttingDr ?></td>
	    </tr>

	    <tr>
		  <td>Final DR</td>
		  <td align="center">:</td>
		  <td><?= $fFinalDr ?></td>
	    </tr>

	    <tr>
		  <td>Output DR</td>
		  <td align="center">:</td>
		  <td><?= $fOutputDr ?></td>
	    </tr>

	    <tr>
		  <td>Sorting DR</td>
		  <td align="center">:</td>
		  <td><?= $fSortingDr ?></td>
	    </tr>

	    <tr>
		  <td>Stitching DR</td>
		  <td align="center">:</td>
		  <td><?= $fStitchingDr ?></td>
	    </tr>

	    <tr>
		  <td>Finishing DR</td>
		  <td align="center">:</td>
		  <td><?= $fFinishingDr ?></td>
	    </tr>

	    <tr>
		  <td>Off Loom DR</td>
		  <td align="center">:</td>
		  <td><?= $fOffLoomDr ?></td>
	    </tr>

	    <tr>
		  <td>Stock DR</td>
		  <td align="center">:</td>
		  <td><?= $fStockDr ?></td>
	    </tr>

	    <tr>
		  <td>Pre-Final DR</td>
		  <td align="center">:</td>
		  <td><?= $fPreFinalDr ?></td>
	    </tr>

		<tr>
		  <td>Date of Foundation</td>
		  <td align="center">:</td>
		  <td><?= $sDateOfFoundation ?></td>
		</tr>

		<tr>
		  <td>Product Range</td>
		  <td align="center">:</td>
		  <td><?= $sProductRange ?></td>
		</tr>

		<tr>
		  <td>Ownership</td>
		  <td align="center">:</td>
		  <td><?= $sOwnership ?></td>
		</tr>

		<tr valign="top">
		  <td>Production Capability</td>
		  <td align="center">:</td>
		  <td><?= nl2br($sProductionCapability) ?></td>
		</tr>

		<tr>
		  <td>Factory/Construction Area</td>
		  <td align="center">:</td>
		  <td><?= $sFactoryArea ?></td>
		</tr>

		<tr valign="top">
		  <td>Production Capacity</td>
		  <td align="center">:</td>
		  <td><?= $sProductionCapacity ?></td>
		</tr>

		<tr>
		  <td>Total Stitching Machines</td>
		  <td align="center">:</td>
		  <td><?= $sStitchingMachines ?></td>
		</tr>

		<tr valign="top">
		  <td>Active Customers</td>
		  <td align="center">:</td>
		  <td><?= nl2br($sActiveCustomers) ?></td>
		</tr>

		<tr valign="top">
		  <td>Approved Customers</td>
		  <td align="center">:</td>
		  <td><?= nl2br($sApprovedCustomers) ?></td>
		</tr>

		<tr>
		  <td>Permanent Employees</td>
		  <td align="center">:</td>
		  <td><?= formatNumber($iPermanentEmployees, false) ?></td>
		</tr>

		<tr>
		  <td>Certifications</td>
		  <td align="center">:</td>
		  <td><?= $sCertifications ?></td>
		</tr>

		<tr>
		  <td>3rd Party Compliance Audits</td>
		  <td align="center">:</td>
		  <td><?= $sThirdPartyComplianceAudits ?></td>
		</tr>

		<tr>
		  <td>Annual Turnover (volume)</td>
		  <td align="center">:</td>
		  <td><?= $sAnnualTurnoverVolume ?></td>
		</tr>

		<tr>
		  <td>Annual Turnover (value)</td>
		  <td align="center">:</td>
		  <td><?= $sAnnualTurnoverValue ?></td>
		</tr>

		<tr>
		  <td>Audit Time (per unit)</td>
		  <td align="center">:</td>
		  <td><?= $iUnitAuditTime ?> minutes</td>
		</tr>

		<tr>
		  <td>Latitude</td>
		  <td align="center">:</td>
		  <td><?= $sLatitude ?></td>
		</tr>

		<tr>
		  <td>Longitude</td>
		  <td align="center">:</td>
		  <td><?= $sLongitude ?></td>
		</tr>

		<tr>
		  <td>CAP Certification No</td>
		  <td align="center">:</td>
		  <td><?= $sCapNo ?></td>
		</tr>

		<tr>
		  <td>ETD Manager</td>
		  <td align="center">:</td>
		  <td><?= $sEtdManager ?></td>
		</tr>

		<tr valign="top">
		  <td>Supplier Profile</td>
		  <td align="center">:</td>
		  <td><?= nl2br($sProfile) ?></td>
		</tr>
	  </table>
	</div>
<!--  Body Section Ends Here  -->


  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>