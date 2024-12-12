<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	$sSQL = "SELECT * FROM tbl_vendors WHERE id='$Id'";
	$objDb->query($sSQL);

	$sVendor                     = $objDb->getField(0, "vendor");
	$sAddress                    = $objDb->getField(0, "address");
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
	$sCertifications             = $objDb->getField(0, 'certifications');
	$sThirdPartyComplianceAudits = $objDb->getField(0, 'third_party_compliance_audits');
	$sAnnualTurnoverVolume       = $objDb->getField(0, 'annual_turnover_volume');
	$sAnnualTurnoverValue        = $objDb->getField(0, 'annual_turnover_value');
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
	  <h2 style="margin-bottom:1px;">Vendor Profile</h2>

	  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
		<tr>
		  <td bgcolor="<?= ODD_ROW_COLOR ?>" width="200">Date of Foundation</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sDateOfFoundation ?></td>
		</tr>

		<tr valign="top">
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">Location</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= nl2br($sAddress) ?></td>
		</tr>

		<tr>
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">Product Range</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sProductRange ?></td>
		</tr>

		<tr>
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">Ownership</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sOwnership ?></td>
		</tr>

		<tr valign="top">
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">Production Capability</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= nl2br($sProductionCapability) ?></td>
		</tr>

		<tr>
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">Factory/Construction Area</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sFactoryArea ?></td>
		</tr>

		<tr valign="top">
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">Production Capacity</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sProductionCapacity ?></td>
		</tr>

		<tr>
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">Total Stitching Machines</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sStitchingMachines ?></td>
		</tr>

		<tr valign="top">
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">Active Customers</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= nl2br($sActiveCustomers) ?></td>
		</tr>

		<tr valign="top">
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">Approved Customers</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= nl2br($sApprovedCustomers) ?></td>
		</tr>

		<tr>
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">Permanent Employees</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= formatNumber($iPermanentEmployees, false) ?></td>
		</tr>

		<tr>
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">Certifications</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sCertifications ?></td>
		</tr>

		<tr>
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">3rd Party Compliance Audits</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sThirdPartyComplianceAudits ?></td>
		</tr>

		<tr>
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">Annual Turnover (volume)</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sAnnualTurnoverVolume ?></td>
		</tr>

		<tr>
		  <td bgcolor="<?= ODD_ROW_COLOR ?>">Annual Turnover (value)</td>
		  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $sAnnualTurnoverValue ?></td>
		</tr>
	  </table>
<?
	$sSQL = "SELECT c.title, vc.certificate, vc.from_date, vc.to_date
	         FROM tbl_certifications c, tbl_vendor_certifications vc
	         WHERE c.id=vc.certificate_id AND vc.vendor_id='$Id'
	         ORDER BY c.position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
			    <div class="tblSheet" style="padding-bottom:1px; margin-top:15px;">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="5%">#</td>
				      <td width="43%">Certification</td>
				      <td width="19%">From Date</td>
				      <td width="19%">To Date</td>
				      <td width="14%" class="center">Download</td>
				    </tr>

<?
		$sClass = array("evenRow", "oddRow");

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sCertification = $objDb->getField($i, 'title');
			$sCertificate   = $objDb->getField($i, 'certificate');
			$sFromDate      = $objDb->getField($i, 'from_date');
			$sToDate        = $objDb->getField($i, 'to_date');
?>
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td><?= ($i + 1) ?></td>
				      <td><?= $sCertification ?></td>
				      <td><?= formatDate($sFromDate) ?></td>
				      <td><?= formatDate($sToDate) ?></td>
				      <td class="center"><a href="<?= VENDOR_CERTIFICATIONS_DIR.$sDir.$sCertificate ?>"><img src="images/icons/pdf.gif" width="16" height="16" alt="PDF" title="PDF" /></a></td>
				    </tr>
<?
		}
?>
				  </table>
				</div>

<?
	}
?>
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