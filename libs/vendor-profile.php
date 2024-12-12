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

	$Id       = IO::intValue("Id");
	$Country  = IO::intValue("Country");
	$Category = IO::intValue("Category");

	if ($Id == 0)
		redirect("vendor-profiles.php", "DB_ERROR");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/swfobject.js"></script>
  <script type="text/javascript" src="scripts/slideshow.js.php?Id=<?= $Id ?>"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
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
			  <td width="585">
			    <h1>Vendor Profile</h1>

<?
	$sSQL = "SELECT * FROM tbl_vendors WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
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
	}

	else
		redirect("vendor-profiles.php", "DB_ERROR");
?>
			    <div class="tblSheet" style="padding-bottom:1px;">
			      <h2 style="margin-bottom:1px; margin-right:1px;"><?= $sVendor ?> &raquo; Picture Gallery</h2>
<?

	// Update the XML file for Gallery
	$objHandle = @fopen(($sBaseDir."movies/images".$Id.".xml"), "w+");

	@fwrite($objHandle, '<?xml version="1.0" encoding="UTF-8"?>'."\n");
	@fwrite($objHandle, '<gallery>'."\n");

	$sSQL = "SELECT * FROM tbl_vendor_profile_albums WHERE id IN (SELECT DISTINCT(album_id) FROM tbl_vendor_profile_pictures WHERE vendor_id='$Id')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iAlbumId     = $objDb->getField($i, 0);
		$sAlbum       = $objDb->getField($i, 1);
		$sDescription = $objDb->getField($i, 2);
		$sPicture     = $objDb->getField($i, 3);

		if ($sPicture == "" || !@file_exists($sBaseDir.VENDOR_ALBUMS_IMG_PATH.$sPicture))
			$sPicture = "default.jpg";

		@fwrite($objHandle, ('<album id="Album'.$iAlbumId.'" title="'.$sAlbum.'" lgPath="'.$sBaseDir.VENDOR_PICS_IMG_PATH.'enlarged/" tnPath="'.$sBaseDir.VENDOR_PICS_IMG_PATH.'thumbs/" description="'.$sDescription.'" tn="'.$sBaseDir.VENDOR_ALBUMS_IMG_PATH.$sPicture.'">'."\n"));


		$sSQL = "SELECT caption, picture FROM tbl_vendor_profile_pictures WHERE album_id='$iAlbumId' AND vendor_id='$Id' ORDER BY id";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$sCaption = $objDb2->getField($j, 0);
			$sPicture = $objDb2->getField($j, 1);
			$sLink    = "";

			if ($sPicture == "" || !@file_exists($sBaseDir.VENDOR_PICS_IMG_PATH."thumbs/".$sPicture) || !@file_exists($sBaseDir.VENDOR_PICS_IMG_PATH."enlarged/".$sPicture))
				continue;

			if ($_SESSION['UserId'] != "")
				$sLink = ("download.php?File=".$sBaseDir.VENDOR_PICS_IMG_PATH."enlarged/".$sPicture);

			@fwrite($objHandle, ('<img src="'.$sPicture.'" tn="'.$sPicture.'" caption="'.$sCaption.'" title="'.$sCaption.'" link="'.$sLink.'" target="_self" pause="" vidpreview="" />'."\n"));
		}

		@fwrite($objHandle, '</album>'."\n");
	}

	@fwrite($objHandle, '</gallery>');
	@fclose($objHandle);
?>
			      <div id="SlideShow"></div>
<!--			  
					<object type="application/x-shockwave-flash" data="movies/slideshow.swf" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="581" height="471">
						<param name='movie' value="movies/slideshow.swf"/>
						<param name='quality' value="best"/>
						<param name='bgcolor' value="#121212"/>
						<param name='allowfullscreen' value="true"/>
						<param name='wmode' value="transparent"/>
						<param name="menu" value="false" />
						<param name='FlashVars' value="https://sourcepro.3-tree.com/movies/param.xml.php?Id=<?= $Id ?>&initialURL=<?= urlencode("https://sourcepro.3-tree.com/libs/vendor-profile.php") ?>" />
						<param name='allowscriptaccess' value="sameDomain"/>
						
						<embed src="movies/slideshow.swf" FlashVars="https://sourcepro.3-tree.com/movies/param.xml.php?Id=<?= $Id ?>&initialURL=<?= urlencode("https://sourcepro.3-tree.com/libs/vendor-profile.php") ?>" wmode="transparent" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="581" height="471"></embed>
					</object>
-->
			    </div>

				<div style="height:5px;"></div>

			    <div class="tblSheet" style="padding-bottom:1px;">
			      <h2 style="margin-bottom:1px; margin-right:1px;"><?= $sVendor ?> Profile</h2>

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
                                      
                                                <tr>
                                                    <td bgcolor="<?= ODD_ROW_COLOR ?>">Factory Address (if changed?)</td>                                                    
                                                    <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $ChangeAddress ?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Factory CR Contact Name</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $FactoryCrName ?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Factory CR Contact Phone</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $FactoryCrPhone ?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Factory CR Contact Email</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $FactoryCrEmail ?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Factory Ownership</td>						  
                                                  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=($FactoryOwn == 'O')?'Owned':($FactoryOwn == 'R'?'Rented':'')?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Total Employees</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $TotalEmployees ?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Temporary Employees</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $TemporaryEmployees ?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Contractual Employees</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $ContractualEmployees ?></td>
                                                </tr>
<?
        $sMonthsList = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'Ocober',11=>'November',12=>'December');
?>
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Peak Season Month</td>						  
                                                  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=$sMonthsList[$PeakMonth]?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Low Season Month</td>						  
                                                  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=$sMonthsList[$LowMonth]?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Age of Facility & Manufacturing Operations</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $ManufactAge ?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Monthly Employee Turnover</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $EmployeeTurnover ?></td>
                                                </tr>
                                               
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Is a copy of Restricted Substances List (RSL) policy available for review?</td>						  
                                                  <td bgcolor="<?= EVEN_ROW_COLOR ?>"> <?=($RSLPolicy == 'Y'?'Available':'Not-Available')?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Is there a process to ensure RSL compliant materials are used?</td>						  
                                                  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=($RSLCompliant == 'Y'?'Available':'Not-Available')?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Major Buyer(s)</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $MajorBuyer ?></td>
                                                </tr>
                                                
                                                <tr valign="top">
                                                    <td bgcolor="<?= ODD_ROW_COLOR ?>">Does the factory use subcontractors? i.e <span style="font-size: 8px;">(Fabric processing, embelishment, embroidery, Printing, Garment wash)</span></td>						  
						    <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?= $SubContractors ?></td>
                                                </tr>
                                             
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Beyond Compliance initiatives OR Best Practices?</td>						  
                                                  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=($Practices == 'C')?'Beyond Compliance Initative':($Practices == 'B'?'Best Practices':'')?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Is there any apprentice program in the factory?</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=($ApprenticeProgram == 'Y'?'Yes':'No')?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Are there any formal/informal communication channels (Worker Committee or work Council)?</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=($CommunicationChannel == 'Y'?'Yes':'No')?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Do workers receive documented oriemntation at the time of hiring?</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=($Documentation == 'Y'?'Yes':'No')?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Does the factory provide Gratuity or PF benefits to its workers?</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=($FundBenefits == 'Y'?'Yes':'No')?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Are there multi-storey buildings where factory occupies only a portion of the facility?</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=($PortionFacility == 'Y'?'Yes':'No')?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Are there any hazardous chemicals used at this factory?</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=($HazardousChemicals == 'Y'?'Yes':'No')?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Does this factory generate any wastewater that requires treatment?</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=($WasteWater == 'Y'?'Yes':'No')?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Is there a canteen in the factory?</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=($Canteen == 'Y'?'Yes':'No')?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Does the factory provide childcare?</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=($ChildCare == 'Y'?'Yes':'No')?></td>
                                                </tr>
                                                
                                                <tr valign="top">
						  <td bgcolor="<?= ODD_ROW_COLOR ?>">Does the factory provide onsite or factory owned offsite dormotories?</td>						  
						  <td bgcolor="<?= EVEN_ROW_COLOR ?>"><?=($Dormotories == 'Y'?'Yes':'No')?></td>
                                                </tr> 
				  </table>
				</div>
<?
                                if($_SESSION['UserId'] != "")
                                {
?>
                                <br/>
                                <div class="tblSheet" style="padding-bottom:1px;">
                                <h2 style="margin-bottom:1px; margin-right:1px;">Send Email to Factory</h2>
                                <form name="frmAccount" id="frmAccount" method="post" action="libs/send-factory-email.php" class="frmOutline" onsubmit="$('BtnCreate').disable( );">
                                    <input type="hidden" name="Id" value="<?=$Id?>"/>
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					<tr>
					  <td bgcolor="<?= ODD_ROW_COLOR ?>" width="200">Email Address(es)</td>
                                          <td bgcolor="<?= EVEN_ROW_COLOR ?>"><input type="text" name="Email" style="width: 250px;" required=""/><br/><span style="font-size: 9px; color: gray;">Multiple email can be added using comma(,) separation. <br/>i.e (abc@email.com, xyz@email.com)</span></td>
					</tr>
                                        <tr>
					  <td bgcolor="<?= ODD_ROW_COLOR ?>" width="200">Any Other Message</td>
                                          <td bgcolor="<?= EVEN_ROW_COLOR ?>"><textarea name="Message" rows="3" style="width: 250px;"></textarea></td>
					</tr>
                                  </table>
                                    <div class="buttonsBar">
                                        <input type="submit" value="" class="btnSubmit" onclick="return validateForm( );" />
                                    </div>
                                </form>
                                </div>
                                
<?
                                }
?>


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



	$iComplianceAudit = getDbValue("id", "tbl_compliance_audits", "vendor_id='$Id'", "id DESC");
	$iComplianceScore = @round(getDbValue("AVG(IF(rating='1', '80', IF(rating='2', '79', IF(rating='3', '60', '40'))))", "tbl_compliance_audit_details", "audit_id='$iComplianceAudit'"));


	$iQualityAudit = getDbValue("id", "tbl_quality_audits", "vendor_id='$Id'", "id DESC");
	$iQualityScore = @round(getDbValue("AVG(IF(rating='1', '100', IF(rating='2', '75', IF(rating='3', '50', '25'))))", "tbl_quality_audit_details", "audit_id='$iQualityAudit'"));

	if ($iComplianceAudit > 0 && $iQualityAudit > 0)
	{
?>
				  <div class="tblSheet" style="height:465px;">
				    <div id="RadarChart">
				      <div id="VendorRadarChart">loading...</div>
				    </div>
				  </div>

					<script type="text/javascript">
					<!--
						var objChart = new FusionCharts("scripts/fusion-charts/power-charts/Radar.swf", "VendorSummary", "100%", "450", "0", "1");


						objChart.setXMLData("<chart caption='Overall Vendor Evaluation' bgColor='FFFFFF' radarFillColor='FFFFFF' plotFillAlpha='40' plotBorderThickness='2' anchorAlpha='100' numVDivLines='10' formatNumberScale='0' showValues='0' showLabels='1' labelDisplay='ROTATE' showLegend='0' chartBottomMargin='5' legendPosition='BOTTOM'>" +
											"<categories>" +
											"<category label='On-Time Delivery' />" +
											"<category label='Compliance' />" +
											"<category label='Development Capacity' />" +
											"<category label='Production Capacity' />" +
											"<category label='Quality' />" +
											"</categories>" +

											"<dataset seriesname='' color='00a3dc' anchorSides='10' anchorBorderColor='01526d' anchorBgAlpha='0' anchorRadius='2'>" +
											"<set value='50' />" +
											"<set value='<?= $iComplianceScore ?>' />" +
											"<set value='50' />" +
											"<set value='50' />" +
											"<set value='<?= $iQualityScore ?>' />" +
											"</dataset>" +
											"</chart>");


								    objChart.render("VendorRadarChart");
    						    -->
    						    </script>


<?
	}



	$sSQL = "SELECT * FROM tbl_compliance_audits WHERE vendor_id='$Id' ORDER BY id DESC LIMIT 1";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iAudit     = $objDb->getField(0, "id");
		$iAuditType = $objDb->getField(0, "type_id");

		$sAuditType = getDbValue("title", "tbl_compliance_types", "id='$iAuditType'");


		$sSections = array("Workforce Management" => array("Hiring Practices"                => "2,3",
														   "Factory Documentation"           => "1,7",
														   "Workers/Management Relationship" => "4,5",
														   "Work Hours"                      => "6",
														   "Total Compensation"              => "8,9"),

				           "HSE Management"       => array("Safety"                          => "10,13,14,15,20",
														   "Health"                          => "16,17,21,22",
														   "Environment"                     => "11,12,18,19"));

?>
			    <div class="tblSheet">
			      <h2 style="margin-bottom:1px;">Compliance Summary</h2>
			      <h3><?= $sAuditType ?></h3>

			      <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
			        <tr valign="top">
			          <td width="55%">
			            <h3>Audit Score</h3>

				        <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
<?
		$fScores   = array( );
		$fAvgScore = 0;

		foreach ($sSections as $sSection => $sSubSections)
		{
			$fSubScore = 0;


			foreach ($sSubSections as $sSubSection => $sQuestions)
			{
				$fScore     = getDbValue("AVG(IF(rating='1', '80', IF(rating='2', '79', IF(rating='3', '60', '40'))))", "tbl_compliance_audit_details", "audit_id='$iAudit' AND FIND_IN_SET(question_id, '$sQuestions')");
				$fScore     = @round($fScore, 2);

				$fSubScore += $fScore;

				$fScores[$sSubSection] = $fScore;
?>
				          <tr>
				            <td width="80%" align="left"><?= $sSubSection ?></td>
				            <td width="20%" align="center"><?= formatNumber($fScore) ?>%</td>
				          </tr>
<?
			}


			$fSubScore         /= count($sSubSections);
			$fScores[$sSection] = $fSubScore;
?>
				          <tr bgcolor="#dddddd">
				            <td width="80%" align="left"><?= $sSection ?></td>
				            <td width="20%" align="center"><?= formatNumber($fSubScore) ?>%</td>
				          </tr>
<?
		}


		$fAvgScore = @round(getDbValue("AVG(IF(rating='1', '80', IF(rating='2', '79', IF(rating='3', '60', '40'))))", "tbl_compliance_audit_details", "audit_id='$iAudit'"), 2);
		$sColor    = "#a8a9ad";

		if ($fAvgScore >= 80)
			$sColor = "#00a3dc";

		else if ($fAvgScore >= 61)
			$sColor = "#01526d";

		else if ($fAvgScore >= 41)
			$sColor = "#5f91a8";
?>

				          <tr bgcolor="#bbbbbb">
				            <td width="80%" align="left"><b>Average</b></td>
				            <td width="20%" align="center" bgcolor="<?= $sColor ?>"><a href="crc/view-compliance-audit.php?Id=<?= $iAudit ?>" class="lightview" rel="iframe" title="Compliance Audit :: :: width: 900, height: 650"><b style="color:#ffffff;"><?= formatNumber($fAvgScore) ?>%</b></a></td>
				          </tr>
				        </table>
			          </td>

			          <td width="45%">
			            <h3>Factory Rating Criteria</h3>
			            <b>80% & above%: [Green]</b><br />
			            <span style="font-size:10px;">fairly compliant , however there are certain  continuous improvements points.</span><br />
			            <br />
			            <b>61-79%: [Yellow]</b><br />
			            <span style="font-size:10px;">Fty current practices are compliant in most cases, however there are certain areas req. improvements and also req. a systematic approach to ensure sustainability.</span><br />
			            <br />
			            <b>41-60%:	[Orange]</b><br />
			            <span style="font-size:10px;">Base level compliance is in place and may be accepted conditionally with mgmt commitment to improve current practices significantly by taking immediate action.</span><br />
			            <br />
			            <b>0-40%: [Red]</b><br />
			            <span style="font-size:10px;">Far behind the requirements , permanent disability and substantial environmental impact.</span><br />
			          </td>
			        </tr>
			      </table>

			      <div style="padding:10px; font-size:10px;">
			        <b>Cuation Note:</b><br />
				    High score does indicate good factory shape, however, does not guarantee anything. Even a single violation can result in failure of a factory. <br />
				  </div>


				  <div class="tblSheet" style="height:465px;">
				    <div id="SummaryChart">
				      <div id="ComplianceSummaryChart">loading...</div>
				    </div>

				    <div id="DetailedChart" style="position:relative; display:none;">
				      <div id="ComplianceDetailedChart">loading...</div>

				      <div style="position:absolute; right:10px; top:10px;"><a href="#" onclick="showSummary( ); return false;"><b>&raquo; Back</b></a></div>
				    </div>
				  </div>


					<script type="text/javascript">
					<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "ComplianceSummary", "100%", "450", "0", "1");

						objChart.setXMLData("<chart caption='Compliance Summary' numVDivLines='10' yAxisMinValue='0' yAxisMaxValue='100' formatNumberScale='0' showValues='0' showLabels='1' labelDisplay='ROTATE' showLegend='0' chartBottomMargin='5' legendPosition='BOTTOM'>" +
											"<categories>" +
<?
		foreach ($sSections as $sSection => $sSubSections)
		{
			foreach ($sSubSections as $sSubSection => $sQuestions)
			{
?>
											"<category label='<?= $sSubSection ?>' />" +
<?
			}
		}
?>
											"</categories>" +

											"<dataset seriesName='Score'>" +
<?
  		foreach ($fScores as $sSection => $fScore)
  		{
  			$sColor = "#a8a9ad";

  			if ($fScore >= 80)
  				$sColor = "#00a3dc";

  			else if ($fScore >= 61)
  				$sColor = "#01526d";

  			else if ($fScore >= 41)
  				$sColor = "#5f91a8";
?>
											"<set value='<?= $fScore ?>' color='<?= $sColor ?>' link='javascript:showDetails(\"<?= $iAudit ?>\", \"<?= $sSection ?>\");' />" +
<?
  		}
?>
											"</dataset>" +

											"<trendlines>" +
											"  <line toolText='Average Score: (<?= $fAvgScore ?>%)' startValue='<?= $fAvgScore ?>' displayValue='Avg. <?= $fAvgScore ?>' color='#0000ff' />" +
											"</trendlines>" +
											"</chart>");


								    objChart.render("ComplianceSummaryChart");



								    function showSummary( )
								    {
											$("DetailedChart").hide( );
											$('SummaryChart').show( );
								    }


								    function showDetails(iAudit, sSection)
								    {
										var sUrl    = "ajax/libs/compliance-chart.php";
										var sParams = ("Audit=" + iAudit + "&Section=" + sSection);


										$('Processing').show( );

										new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_showDetails });
									}


									function _showDetails(sResponse)
									{
										if (sResponse.status == 200 && sResponse.statusText == "OK")
										{
											$('SummaryChart').hide( );
											$("DetailedChart").show( );


											var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "ComplianceDetailed", "100%", "450", "0", "1");

											objChart.setXMLData(sResponse.responseText);
											objChart.render("ComplianceDetailedChart");


											$('Processing').hide( );
										}
									}
    						    -->
    						    </script>
<?
	}





	$sSQL = "SELECT * FROM tbl_quality_audits WHERE vendor_id='$Id' ORDER BY id DESC LIMIT 1";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iAudit = $objDb->getField(0, "id");
?>
			    <div class="tblSheet">
			      <h2 style="margin-bottom:1px;">Quality Summary</h2>

				  <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
					<tr bgcolor="#eaeaea">
					  <td width="28%" align="left"><b>Area</b></td>
					  <td width="7%" align="center"><b>Unit</b></td>

					  <td width="30%">
					    <table border="1" bordercolor="#666666" cellpadding="4" cellspacing="0" width="100%">
					      <tr>
					        <td width="25%" align="center" bgcolor="#00a3dc"><b>A</b></td>
					        <td width="25%" align="center" bgcolor="#01526d"><b>B</b></td>
					        <td width="25%" align="center" bgcolor="#5f91a8"><b>C</b></td>
					        <td width="25%" align="center" bgcolor="#a8a9ad"><b>D</b></td>
					      </tr>
					    </table>
					  </td>

					  <td width="9%" align="center"><b>Total</b></td>
					  <td width="12%" align="center"><b>OSA Hit Rate</b></td>
					  <td width="11%" align="center"><b>Overall Level</b></td>
					  <td width="18%" align="left"><b>Comment</b></td>
					</tr>
				  </table>

				  <div id="QualityShow" style="padding:15px;"><center><a href="#" onclick="return showQualitySummary( );">Show Audit Summary</a></center></div>

				  <div id="QualityDetails" style="display:none;">
<?
		$sAreasList = getList("tbl_quality_areas", "id", "title", "", "position");
		$iIndex     = 0;
		$sGrades    = array( );

		foreach ($sAreasList as $iArea => $sArea)
		{
			$sSQL = "SELECT SUM(IF(qad.rating='1', '1', '0')) AS _GradeA,
					        SUM(IF(qad.rating='2', '1', '0')) AS _GradeB,
					        SUM(IF(qad.rating='3', '1', '0')) AS _GradeC,
					        SUM(IF(qad.rating='4', '1', '0')) AS _GradeD
					 FROM tbl_quality_audit_details qad, tbl_quality_points qp
					 WHERE qad.audit_id='$iAudit' AND qad.point_id=qp.id AND qp.area_id='$iArea'";
			$objDb->query($sSQL);

			$iGradeA = $objDb->getField(0, "_GradeA");
			$iGradeB = $objDb->getField(0, "_GradeB");
			$iGradeC = $objDb->getField(0, "_GradeC");
			$iGradeD = $objDb->getField(0, "_GradeD");

			$iTotalGrades = ($iGradeA + $iGradeB + $iGradeC + $iGradeD);
			$fGradeA      = @round((($iGradeA / $iTotalGrades) * 100), 2);
			$fGradeB      = @round((($iGradeB / $iTotalGrades) * 100), 2);
			$fGradeC      = @round((($iGradeC / $iTotalGrades) * 100), 2);
			$fGradeD      = @round((($iGradeD / $iTotalGrades) * 100), 2);
			$fTotalGrades = round(($fGradeA + $fGradeB + $fGradeC + $fGradeD), 2);

			$fOverallGrade = @round(($fGradeA + $fGradeB), 2);

			$bPass     = (($iGradeC > 0 || $iGradeD > 0) ? false : true);
			$sComments = '<span style="color:#5f91a8;">No Values Entered in the Technical Evaluation Sheet; Please Revise</span>';

			if ($iTotalGrades > 0)
			{
				if ($bPass == true)
					$sComments = '<span style="color:#00a3dc;">Evaluation Passed</span>';

				else
					$sComments = '<span style="color:#a8a9ad;">Immediate Action Required; Re-Evaluation Necessary</span>';
			}


			$sGrades[$iArea]["A"] = $fGradeA;
			$sGrades[$iArea]["B"] = $fGradeB;
			$sGrades[$iArea]["C"] = $fGradeC;
			$sGrades[$iArea]["D"] = $fGradeD;
?>
				  <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
					<tr valign="top" bgcolor="<?= ((($iIndex % 2) == 1) ? '#fafafa' : '#ffffff') ?>">
					  <td width="28%" align="left"><?= $sArea ?></td>
					  <td width="7%" align="center">[#]<br /><br />[%]</td>

					  <td width="30%">
					    <table border="1" bordercolor="#666666" cellpadding="4" cellspacing="0" width="100%">
					      <tr>
					        <td width="25%" align="center"><?= $iGradeA ?></td>
					        <td width="25%" align="center"><?= $iGradeB ?></td>
					        <td width="25%" align="center"><?= $iGradeC ?></td>
					        <td width="25%" align="center"><?= $iGradeD ?></td>
					      </tr>

					      <tr>
					        <td align="center" style="font-size:8px;"><?= $fGradeA ?></td>
					        <td align="center" style="font-size:8px;"><?= $fGradeB ?></td>
					        <td align="center" style="font-size:8px;"><?= $fGradeC ?></td>
					        <td align="center" style="font-size:8px;"><?= $fGradeD ?></td>
					      </tr>
					    </table>
					  </td>

					  <td width="9%" align="center"><?= $iTotalGrades ?><br /><br /><?= $fTotalGrades ?></td>
					  <td width="12%" align="center" style="font-size:10px;"><?= $fOverallGrade ?>%</td>
					  <td width="11%" align="center"><? if ($iTotalGrades > 0) { ?><img src="images/icons/<?= (($bPass == true) ? 'yes' : 'no') ?>.png" /><? } ?></td>
					  <td width="18%" align="left"><?= $sComments ?></td>
					</tr>
				  </table>
<?
			$iIndex ++;
		}


		$sSQL = "SELECT SUM(IF(rating='1', '1', '0')) AS _GradeA,
						SUM(IF(rating='2', '1', '0')) AS _GradeB,
						SUM(IF(rating='3', '1', '0')) AS _GradeC,
						SUM(IF(rating='4', '1', '0')) AS _GradeD
				 FROM tbl_quality_audit_details
				 WHERE audit_id='$iAudit'";
		$objDb->query($sSQL);

		$iGradeA = $objDb->getField(0, "_GradeA");
		$iGradeB = $objDb->getField(0, "_GradeB");
		$iGradeC = $objDb->getField(0, "_GradeC");
		$iGradeD = $objDb->getField(0, "_GradeD");

		$iTotalGrades = ($iGradeA + $iGradeB + $iGradeC + $iGradeD);
		$fGradeA      = @round((($iGradeA / $iTotalGrades) * 100), 2);
		$fGradeB      = @round((($iGradeB / $iTotalGrades) * 100), 2);
		$fGradeC      = @round((($iGradeC / $iTotalGrades) * 100), 2);
		$fGradeD      = @round((($iGradeD / $iTotalGrades) * 100), 2);


		$iMaxGrade = $iGradeA;

		if ($iMaxGrade < $iGradeB)
			$iMaxGrade = $iGradeB;

		if ($iMaxGrade < $iGradeC)
			$iMaxGrade = $iGradeC;

		if ($iMaxGrade < $iGradeD)
			$iMaxGrade = $iGradeD;



		if ($iMaxGrade == $iGradeA && $fGradeA >= 85)
			$sGrade = '<div style="background:#00a3dc; padding:10px; font-weight:bold; font-size:13px;">A Grade</div>';

		else if ($iMaxGrade == $iGradeA && $fGradeA < 85)
			$sGrade = '<div style="background:#01526d; padding:10px; font-weight:bold; font-size:13px;">B Grade</div>';

		else if ($iMaxGrade == $iGradeB)
			$sGrade = '<div style="background:#01526d; padding:10px; font-weight:bold; font-size:13px;">B Grade</div>';

		else if ($iMaxGrade == $iGradeC)
			$sGrade = '<div style="background:#5f91a8; padding:10px; font-weight:bold; font-size:13px;">C Grade</div>';

		else
			$sGrade = '<div style="background:#a8a9ad; padding:10px; font-weight:bold; font-size:13px;">D Grade</div>';
?>
				  </div>

				  <div id="QualityHide" style="display:none; padding:15px;"><center><a href="#" onclick="return hideQualitySummary( );">Hide Audit Summary</a></center></div>

				  <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
					<tr bgcolor="<?= ((($iIndex % 2) == 1) ? '#fafafa' : '#ffffff') ?>">
					  <td width="35%" align="left"><b style="padding:4px; display:block; line-height:22px;">Total Count &nbsp; A/B/C/D<br />% of A/B/C/D</b></td>

					  <td width="30%">
					    <table border="0" bordercolor="#666666" cellpadding="3" cellspacing="0" width="100%">
					      <tr>
					        <td width="25%" align="center" bgcolor="#00a3dc"><?= $iGradeA ?></td>
					        <td width="25%" align="center" bgcolor="#01526d"><?= $iGradeB ?></td>
					        <td width="25%" align="center" bgcolor="#5f91a8"><?= $iGradeC ?></td>
					        <td width="25%" align="center" bgcolor="#a8a9ad"><?= $iGradeD ?></td>
					      </tr>

					      <tr>
					        <td align="center" style="font-size:9px;"><?= $fGradeA ?></td>
					        <td align="center" style="font-size:9px;"><?= $fGradeB ?></td>
					        <td align="center" style="font-size:9px;"><?= $fGradeC ?></td>
					        <td align="center" style="font-size:9px;"><?= $fGradeD ?></td>
					      </tr>
					    </table>
					  </td>

					  <td width="35%" align="center"><a href="crc/view-quality-audit.php?Id=<?= $iAudit ?>" class="lightview" rel="iframe" title="Quality Audit :: :: width: 900, height: 650"><?= $sGrade ?></a></td>
					</tr>
			      </table>
			    </div>

			    <div class="tblSheet">
			      <div id="QualityChart">loading...</div>
			    </div>

				<script type="text/javascript">
				<!--
					function showQualitySummary( )
					{
						$('QualityShow').hide( );
						$('QualityHide').show( );
						$("QualityDetails").show( );

						return false;
					}


					function hideQualitySummary( )
					{
						$('QualityShow').show( );
						$('QualityHide').hide( );
						$("QualityDetails").hide( );

						return false;
					}


					var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3D.swf", "QualitySummary", "100%", "500", "0", "1");

					objChart.setXMLData("<chart caption='Quality Summary' areaOverColumns='0' stack100Percent='1' showPercentValues='1' formatNumberScale='0' showValues='0' showLabels='1' chartBottomMargin='5' showLegend='0' legendPosition='BOTTOM'>" +
										"<categories>" +
<?
		foreach ($sAreasList as $iArea => $sArea)
		{
			if ($sGrades[$iArea]["A"] == 0 && $sGrades[$iArea]["B"] == 0 && $sGrades[$iArea]["C"] == 0 && $sGrades[$iArea]["D"] == 0)
				continue;
?>
										"<category label='<?= $sArea ?>' />" +
<?
		}
?>
										"</categories>" +

										"<dataset seriesName='Grade A' color='#00a3dc'>" +
<?
  		foreach ($sAreasList as $iArea => $sArea)
  		{
			if ($sGrades[$iArea]["A"] == 0 && $sGrades[$iArea]["B"] == 0 && $sGrades[$iArea]["C"] == 0 && $sGrades[$iArea]["D"] == 0)
				continue;
?>
										"<set value='<?= $sGrades[$iArea]["A"] ?>' />" +
<?
  		}
?>
										"</dataset>" +

										"<dataset seriesName='Grade B' color='#01526d'>" +
<?
		foreach ($sAreasList as $iArea => $sArea)
		{
			if ($sGrades[$iArea]["A"] == 0 && $sGrades[$iArea]["B"] == 0 && $sGrades[$iArea]["C"] == 0 && $sGrades[$iArea]["D"] == 0)
				continue;
?>
										"<set value='<?= $sGrades[$iArea]["B"] ?>' />" +
<?
		}
?>
										"</dataset>" +

										"<dataset seriesName='Grade C' color='#5f91a8'>" +
<?
		foreach ($sAreasList as $iArea => $sArea)
		{
			if ($sGrades[$iArea]["A"] == 0 && $sGrades[$iArea]["B"] == 0 && $sGrades[$iArea]["C"] == 0 && $sGrades[$iArea]["D"] == 0)
				continue;
?>
										"<set value='<?= $sGrades[$iArea]["C"] ?>' />" +
<?
		}
?>
										"</dataset>" +

										"<dataset seriesName='Grade D' color='#a8a9ad'>" +
<?
		foreach ($sAreasList as $iArea => $sArea)
		{
			if ($sGrades[$iArea]["A"] == 0 && $sGrades[$iArea]["B"] == 0 && $sGrades[$iArea]["C"] == 0 && $sGrades[$iArea]["D"] == 0)
				continue;
?>
										"<set value='<?= $sGrades[$iArea]["D"] ?>' />" +
<?
		}
?>
										"</dataset>" +
										"</chart>");


					objChart.render("QualityChart");
				-->
				</script>
<?
	}





	$sSQL = "SELECT * FROM tbl_production_audits WHERE vendor_id='$Id' ORDER BY id DESC LIMIT 1";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iAudit = $objDb->getField(0, "id");


		$sCategoryList = getList("tbl_production_categories", "id", "title", "", "position");
		$sGrades       = array( );

		foreach ($sCategoryList as $iCat => $sCategory)
		{
			$sSQL = "SELECT SUM(IF(pad.weightage>'3', '1', '0')) AS _Green,
					        SUM(IF(pad.weightage>'1' and pad.weightage<'3', '1', '0')) AS _Yellow,
					        SUM(IF(pad.weightage<'2', '1', '0')) AS _Red
					 FROM tbl_production_audit_details pad, tbl_production_questions as paq
					 WHERE pad.audit_id='$iAudit' AND pad.question_id=paq.id AND paq.category_id='$iCat'";
			$objDb->query($sSQL);

			$iGreen  = $objDb->getField(0, "_Green");
			$iYellow = $objDb->getField(0, "_Yellow");
			$iRed    = $objDb->getField(0, "_Red");


			$iTotalGrades = ($iGreen + $iYellow + $iRed);

			$sGrades[$iCat]["Green"]  = $iGreen;
			$sGrades[$iCat]["Yellow"] = $iYellow;
			$sGrades[$iCat]["Red"]    = $iRed;

		}

		$iGreen  = getDbValue("COUNT(*)", "tbl_production_audit_details", "weightage='5' AND audit_id='$iAudit'");
		$iYellow = getDbValue("COUNT(*)", "tbl_production_audit_details", "weightage='3' AND audit_id='$iAudit'");
		$iRed    = getDbValue("COUNT(*)", "tbl_production_audit_details", "weightage='1' AND audit_id='$iAudit'");

		$iMax = $iGreen;

		if ($iMax < $iYellow)
			$iMax = $iYellow;

		if ($iMax < $iRed)
			$iMax = $iRed;
?>
			    <div class="tblSheet">
			      <h2 style="margin-bottom:1px;">Development & Production</h2>

				  <div class="tblSheet">
					<div id="ProductionChart">
					  <div id="ProductionSummaryChart">loading...</div>
					</div>
				  </div>


					<script type="text/javascript">
					<!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3D.swf", "ProductionSummary", "100%", "450", "0", "1");

						objChart.setXMLData("<chart caption='Score' numVDivLines='10' yAxisMinValue='0' yAxisMaxValue='<?= ($iMax + 5) ?>' formatNumberScale='0' showValues='0' showLabels='1' showLegend='1' chartBottomMargin='10' legendPosition='BOTTOM'>" +
											"<categories>" +
											<?
		foreach ($sCategoryList as $iCat => $sCategory)
		{
?>
										"<category label='<?= $sCategory ?>' />" +
<?
		}
?>
											"</categories>" +

											"<dataset seriesName='Green Standards' color='#00a3dc'>" +
<?
  		foreach ($sCategoryList as $iCat => $sCategory)
  		{
			if ($sGrades[$iCat]["Green"] == 0 && $sGrades[$iCat]["Yellow"] == 0 && $sGrades[$iCat]["Red"] == 0 )
				continue;
?>
										"<set value='<?= $sGrades[$iCat]['Green'] ?>' link='javascript:showProductionDetails(\"<?=$iCat?>\", \"green\");' />" +
<?
  		}
?>
										"</dataset>" +
										"<dataset seriesName='Yellow Standards' color='#01526d'>" +
<?
  		foreach ($sCategoryList as $iCat => $sCategory)
  		{
			if ($sGrades[$iCat]["Green"] == 0 && $sGrades[$iCat]["Yellow"] == 0 && $sGrades[$iCat]["Red"] == 0 )
				continue;
?>
										"<set value='<?= $sGrades[$iCat]['Yellow'] ?>' link='javascript:showProductionDetails(\"<?=$iCat?>\", \"yellow\");' />" +
<?
  		}
?>
										"</dataset>" +
										"<dataset seriesName='Red Standards' color='#a8a9ad'>" +
<?
  		foreach ($sCategoryList as $iCat => $sCategory)
  		{
			if ($sGrades[$iCat]["Green"] == 0 && $sGrades[$iCat]["Yellow"] == 0 && $sGrades[$iCat]["Red"] == 0 )
				continue;
?>
										"<set value='<?= $sGrades[$iCat]['Red'] ?>' link='javascript:showProductionDetails(\"<?=$iCat?>\", \"red\");' />" +
<?
  		}
?>
										"</dataset>"  +
											"</chart>");


								    objChart.render("ProductionSummaryChart");
									
									
									function showProductionDetails(iCategory, sColor)
									{
										Lightview.show({ href:("<?=SITE_URL?>crc/view-production-audit-performance.php?Id=<?= $iAudit ?>&Cat=" + iCategory + "&Standard=" + sColor) , rel:"iframe", options: { width: 800, height: 400 }});
									}
    						    -->
    						    </script>
<?
	}
?>
			    </div>
			  </td>

			  <td width="5"></td>

			  <td>
<?
	@include($sBaseDir."includes/sign-in.php");
?>

			    <div style="height:5px;"></div>

			    <h1>Vendors Listing</h1>

			    <div class="tblSheet">
			      <div class="right" style="padding:5px 5px 0px 0px;">
			        <b>Filter:</b> &nbsp;

			        <select onchange="document.location=this.value;">
			          <option value="<?= SITE_URL ?>libs/vendor-profile.php?Id=<?= $Id ?>&Category=<?= $Category ?>">All Regions</option>
<?
	$sConditions = "";

	if (strpos($_SESSION["Email"], "@kik.go.com") !== FALSE)
		$sConditions = " AND id IN (SELECT DISTINCT(vendor_id) FROM tbl_po WHERE brand_id='194') ";


	$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' ORDER BY country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCountryId = $objDb->getField($i, 0);
		$sCountry   = $objDb->getField($i, 1);

		$sSQL = "SELECT id, vendor,
		                (SELECT COUNT(*) FROM tbl_vendor_profile_pictures WHERE vendor_id=tbl_vendors.id) AS _Pictures
		         FROM tbl_vendors
		         WHERE country_id='$iCountryId' AND parent_id='0' AND sourcing='Y' $sConditions
		         ORDER BY vendor";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		if ($iCount2 == 0)
			continue;
?>
			          <option value="<?= SITE_URL ?>libs/vendor-profile.php?Id=<?= $Id ?>&Country=<?= $iCountryId ?>&Category=<?= $Category ?>"<?= (($iCountryId == $Country) ? ' selected' : '') ?>><?= $sCountry ?></option>
<?
	}
?>
			        </select>

			        <select onchange="document.location=this.value;">
			          <option value="<?= SITE_URL ?>libs/vendor-profile.php?Id=<?= $Id ?>&Country=<?= $Country ?>">All Categories</option>
<?
	$sSQL = "SELECT id, category FROM tbl_categories ORDER BY category";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCategoryId = $objDb->getField($i, 0);
		$sCategory   = $objDb->getField($i, 1);
?>
			          <option value="<?= SITE_URL ?>libs/vendor-profile.php?Id=<?= $Id ?>&Country=<?= $Country ?>&Category=<?= $iCategoryId ?>"<?= (($iCategoryId == $Category) ? ' selected' : '') ?>><?= $sCategory ?></option>
<?
	}
?>
			        </select>
			      </div>

<?
	$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' ORDER BY country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$bFlag  = false;

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCountryId = $objDb->getField($i, 0);
		$sCountry   = $objDb->getField($i, 1);


		$sCategorySql = "";

		if ($Category > 0)
			$sCategorySql = "AND category_id='$Category'";

		$sSQL = "SELECT id, vendor,
		                (SELECT COUNT(*) FROM tbl_vendor_profile_pictures WHERE vendor_id=tbl_vendors.id) AS _Pictures
		         FROM tbl_vendors
		         WHERE country_id='$iCountryId' AND parent_id='0' AND sourcing='Y' $sCategorySql $sConditions
		         ORDER BY vendor";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		if ($iCount2 == 0)
			continue;

		if ($Country == $iCountryId || $Country == 0)
			$bFlag = true;
?>
			      <div id="Country<?= $iCountryId ?>" style="display:<?= (($Country == $iCountryId || $Country == 0) ? 'block' : 'none') ?>; margin-top:10px;">
			        <h2 style="margin:0px 1px 5px 0px;"><?= $sCountry ?></h2>

			        <table border="0" cellpadding="3" cellspacing="0" width="95%" align="center">
<?
		for ($j = 0; $j < $iCount2;)
		{
?>
			          <tr>
<?
			for ($k = 0; $k < 2; $k ++)
			{
				if ($j < $iCount2)
				{
					$iVendorId = $objDb2->getField($j, 0);
					$sVendor   = $objDb2->getField($j, 1);
					$iPictures = $objDb2->getField($j, 2);
?>
			              <td width="50%"><b>&raquo;</b> <a href="libs/vendor-profile.php?Id=<?= $iVendorId ?>&Country=<?= $Country ?>&Category=<?= $Category ?>"<?= (($iPictures == 0) ? ' style="color:#a8a9ad;"' : '') ?>><?= $sVendor ?></a></td>
<?
				 	$j ++;
				}

				else
				{
?>
			              <td width="50%"></td>
<?
				}
			}
?>
			            </tr>
<?
		}
?>
			          </table>
			        </div>
<?
	}

	if ($bFlag == false)
	{
?>
                  <div style="padding-top:30px; color:#a8a9ad;"><center>No Vendor Found!</center></div>
<?
	}
?>
			      <br />
			    </div>
			  </td>
			</tr>
		  </table>
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