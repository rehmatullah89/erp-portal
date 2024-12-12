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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT *,
	                (SELECT office FROM tbl_offices WHERE id=tbl_users.office_id) AS _Office,
	                (SELECT country FROM tbl_countries WHERE id=tbl_users.country_id) AS _Country
	         FROM tbl_users
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sName                   = $objDb->getField(0, "name");
		$sGender                 = $objDb->getField(0, "gender");
		$sDob                    = $objDb->getField(0, "dob");
		$sAddress                = $objDb->getField(0, "address");
		$sCity                   = $objDb->getField(0, "city");
		$sState                  = $objDb->getField(0, "state");
		$sZipCode                = $objDb->getField(0, "zip_code");
		$sCountry                = $objDb->getField(0, "_Country");
		$sEmail                  = $objDb->getField(0, "email");
		$sPhone                  = $objDb->getField(0, "phone");
		$sMobile                 = $objDb->getField(0, "mobile");
		$sOrganization           = $objDb->getField(0, "organization");
		$sJoiningDate            = $objDb->getField(0, "joining_date");
		$sCardId                 = $objDb->getField(0, "card_id");
		$iDesignation            = $objDb->getField(0, "designation_id");
		$sOffice                 = $objDb->getField(0, "_Office");
		$sPhoneExt               = $objDb->getField(0, "phone_ext");
		$sNicNo                  = $objDb->getField(0, "nic_no");
		$sMaritalStatus          = $objDb->getField(0, "marital_status");
		$sSpouseName             = $objDb->getField(0, "spouse_name");
		$iChildren               = $objDb->getField(0, "children");
		$sBloodGroup             = $objDb->getField(0, "blood_group");
		$sEmergencyName          = $objDb->getField(0, "emergency_name");
		$sEmergencyPhone         = $objDb->getField(0, "emergency_phone");
		$sEmergencyAddress       = $objDb->getField(0, "emergency_address");
		$sPersonalGoals          = $objDb->getField(0, "personal_goals");
		$sTrainingsRequired      = $objDb->getField(0, "trainings_required");
		$sPicture                = $objDb->getField(0, "picture");
		$sUsername               = $objDb->getField(0, "username");
		$sAdmin                  = $objDb->getField(0, "admin");
		$sGrievancesManager      = $objDb->getField(0, "grievances_manager");
		$sSurveyAdmin            = $objDb->getField(0, "survey_admin");
		$sAuditsManager          = $objDb->getField(0, "audits_manager");
		$sSkipImage              = $objDb->getField(0, "app_skip_image");
		$sShowUserSchedules      = $objDb->getField(0, "show_user_schedules");
		$sAuditor                = $objDb->getField(0, "auditor");
		$sAuditorLevel           = $objDb->getField(0, "auditor_level");
		$iAuditorType            = $objDb->getField(0, "auditor_type");
		$iAuditorCode            = $objDb->getField(0, "auditor_code");
		$sNonProductionSchedules = $objDb->getField(0, "non_production_schedules");
		$sAttendance             = $objDb->getField(0, "attendance");
		$sBasket                 = $objDb->getField(0, "basket");
		$sEmailAlerts            = $objDb->getField(0, "email_alerts");
		$sBrands                 = $objDb->getField(0, "brands");
		$sVendors                = $objDb->getField(0, "vendors");
		$sSuppliers              = $objDb->getField(0, "suppliers");
		$sStyleCategories        = $objDb->getField(0, "style_categories");
		$sReportTypes            = $objDb->getField(0, "report_types");
		$sAuditStages            = $objDb->getField(0, "audit_stages");
		$sDateTime               = $objDb->getField(0, "date_time");
		$sUserType               = $objDb->getField(0, "user_type");


		switch ($objDb->getField(0, "status"))
		{
			case "A" : $sStatus = "Active"; break;
			case "D" : $sStatus = "Disabled"; break;
			case "L" : $sStatus = "Left"; break;
			case "P" : $sStatus = "Account Not Approved"; break;
		}
		
		switch ($objDb->getField(0, "language"))
		{
			case "zh" : $sLanguage = "Chinese"; break;
			case "tr" : $sLanguage = "Turkish"; break;
			case "de" : $sLanguage = "German"; break;
			default   : $sLanguage = "English"; break;
		}		

		switch ($sAuditorLevel)
		{
			case "G" : $sAuditorLevel = "Green"; break;
			case "B" : $sAuditorLevel = "Blue"; break;
			case "Y" : $sAuditorLevel = "Yellow"; break;
			case "R" : $sAuditorLevel = "Red"; break;
		}
		
		switch ($iAuditorType)
		{
			case 1 : $sAuditorType = "3rd Party Auditor"; break;
			case 2 : $sAuditorType = "QMIP Auditor"; break;
			case 3 : $sAuditorType = "QMIP Corelation Auditor"; break;
			case 4 : $sAuditorType = "MCA"; break;
			case 5 : $sAuditorType = "FCA"; break;
			case 6 : $sAuditorType = "Compliance Auditor"; break;
		}
		

		switch ($sUserType)
		{
			case "MATRIX"        : $sUserType = "MATRIX Sourcing"; break;
			case "TRIPLETREE"    : $sUserType = "Triple Tree Solutions"; break;
			case "LULUSAR"       : $sUserType = "Lulusar"; break;
			case "CONTROLIST"    : $sUserType = "Controlist"; break;
			case "HYBRID"        : $sUserType = "Hybrid Apparel"; break;
			case "GLOBALEXPORTS" : $sUserType = "Global Exports"; break;
			case "GAIA"          : $sUserType = "GAI’A"; break;
		}


		if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
			$sPicture = "default.jpg";
	}


	$sSQL = "SELECT designation, reporting_to, department_id FROM tbl_designations WHERE id='$iDesignation'";
	$objDb->query($sSQL);

	$sDesignation  = $objDb->getField(0, 'designation');
	$iDepartment   = $objDb->getField(0, 'department_id');
	$iReportingTo  = $objDb->getField(0, 'reporting_to');

	$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");
	$sReportingTo = getDbValue("designation", "tbl_designations", "id='$iReportingTo'");
	
	$sLastLogin     = "N/A";
	$sLoginDateTime = getDbValue("MAX(login_date_time)", "tbl_user_stats", "user_id='$Id'");
	
	if ($sLoginDateTime != "" && $sLoginDateTime != "0000-00-00 00:00:00")
		$sLastLogin = formatDate($sLoginDateTime, "jS F Y h:i A");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin:0px 2px 0px 2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr valign="top">
		  <td width="100%">

			<h2>Personal Information</h2>
			<table border="0" cellpadding="3" cellspacing="0" width="95%" align="center">
			  <tr valign="top">
				<td width="120">Name</td>
				<td width="20" align="center">:</td>
				<td><?= $sName ?></td>

				<td width="162" rowspan="8" align="right">
				  <div id="ProfilePic">
				    <div id="Pic"><img src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" alt="<?= $sName ?>" title="<?= $sName ?>" /></div>
				  </div>
				</td>
			  </tr>

			  <tr>
				<td>Gender</td>
				<td align="center">:</td>
				<td><?= $sGender ?></td>
			  </tr>

			  <tr>
				<td>Date of Birth</td>
				<td align="center">:</td>
				<td><?= formatDate($sDob) ?></td>
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
				<td>State</td>
				<td align="center">:</td>
				<td><?= $sState ?></td>
			  </tr>

			  <tr>
				<td>Zip/Post Code</td>
				<td align="center">:</td>
				<td><?= $sZipCode ?></td>
			  </tr>

			  <tr>
				<td>Country</td>
				<td align="center">:</td>
				<td><?= $sCountry ?></td>
			  </tr>
			</table>

			<br />
			<h2>Contact Information</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="95%" align="center">
			  <tr>
				<td width="120">Email</td>
				<td width="20" align="center">:</td>
				<td><?= $sEmail ?></td>
			  </tr>

			  <tr>
				<td>Email Alerts</td>
				<td align="center">:</td>
				<td><?= (($sEmailAlerts == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>Phone</td>
				<td align="center">:</td>
				<td><?= $sPhone ?></td>
			  </tr>

			  <tr>
				<td>Mobile</td>
				<td align="center">:</td>
				<td><?= $sMobile ?></td>
			  </tr>
			</table>

<?
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
	{
?>
			<br />
			<h2>Company Information</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="95%" align="center">
			  <tr>
				<td width="120">Organization</td>
				<td width="20" align="center">:</td>
				<td><?= $sOrganization ?></td>
			  </tr>

			  <tr>
				<td>Joining Date</td>
				<td align="center">:</td>
				<td><?= formatDate($sJoiningDate) ?></td>
			  </tr>

			  <tr>
				<td>Attendance Card ID</td>
				<td align="center">:</td>
				<td><?= $sCardId ?></td>
			  </tr>

			  <tr>
				<td>Designation</td>
				<td align="center">:</td>
				<td><?= $sDesignation ?></td>
			  </tr>

			  <tr>
				<td>Reporting To</td>
				<td align="center">:</td>
				<td><?= $sReportingTo ?></td>
			  </tr>

			  <tr>
				<td>Department</td>
				<td align="center">:</td>
				<td><?= $sDepartment ?></td>
			  </tr>

			  <tr>
				<td>Office</td>
				<td align="center">:</td>
				<td><?= $sOffice ?></td>
			  </tr>

			  <tr>
				<td>Phone Ext</td>
				<td align="center">:</td>
				<td><?= $sPhoneExt ?></td>
			  </tr>
			</table>

			<br />
			<h2>Personal Profile (for Company)</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="95%" align="center">
			  <tr>
				<td width="120">NIC #</td>
				<td width="20" align="center">:</td>
				<td><?= $sNicNo ?></td>
			  </tr>

			  <tr>
				<td>Marital Status</td>
				<td align="center">:</td>
				<td><?= $sMaritalStatus ?></td>
			  </tr>

			  <tr>
				<td>Spouse Name</td>
				<td align="center">:</td>
				<td><?= $sSpouseName ?></td>
			  </tr>

			  <tr>
				<td>No of Children</td>
				<td align="center">:</td>
				<td><?= $iChildren ?></td>
			  </tr>

			  <tr>
				<td>Blood Group</td>
				<td align="center">:</td>
				<td><?= $sBloodGroup ?></td>
			  </tr>

			  <tr>
				<td>Emergency Name</td>
				<td align="center">:</td>
				<td><?= $sEmergencyName ?></td>
			  </tr>

			  <tr>
				<td>Emergency Phone</td>
				<td align="center">:</td>
				<td><?= $sEmergencyPhone ?></td>
			  </tr>

			  <tr valign="top">
				<td>Emergency Address</td>
				<td align="center">:</td>
				<td><?= nl2br($sEmergencyAddress) ?></td>
			  </tr>

			  <tr valign="top">
				<td>Personal Goals / Objectives</td>
				<td align="center">:</td>
				<td><?= nl2br($sPersonalGoals) ?></td>
			  </tr>

			  <tr valign="top">
				<td>Trainings Required / Recomended</td>
				<td align="center">:</td>
				<td><?= nl2br($sTrainingsRequired) ?></td>
			  </tr>
			</table>
<?
	}
?>

			<br />
			<h2>Account Details</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="95%" align="center">
			  <tr>
				<td width="120">Username</td>
				<td width="20" align="center">:</td>
				<td><?= $sUsername ?></td>
			  </tr>

			  <tr>
				<td>Status</td>
				<td align="center">:</td>
				<td><?= $sStatus ?></td>
			  </tr>
			  
			  <tr>
				<td>Language</td>
				<td align="center">:</td>
				<td><?= $sLanguage ?></td>
			  </tr>			  

			  <tr>
				<td>Signup Date / Time</td>
				<td align="center">:</td>
				<td><?= formatDate($sDateTime, "jS F Y h:i A") ?></td>
			  </tr>
			  
			  <tr>
				<td>Last Login</td>
				<td align="center">:</td>
				<td><?= $sLastLogin ?></td>
			  </tr>
			</table>

			<br />
			<h2>Access Details</h2>

			<table border="0" cellpadding="3" cellspacing="0" width="95%" align="center">
			  <tr>
				<td width="120">Administrator</td>
				<td width="20" align="center">:</td>
				<td><?= (($sAdmin == "Y") ? "Yes" : "No") ?></td>
			  </tr>

<?
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
	{
?>
			  <tr>
				<td>User Type</td>
				<td align="center">:</td>
				<td><?= $sUserType ?></td>
			  </tr>
			  
			  <tr>
				<td>Survey Admin</td>
				<td align="center">:</td>
				<td><?= (($sSurveyAdmin == "Y") ? "Yes" : "No") ?></td>
			  </tr>			  

			  <tr>
				<td>Grievances Manager</td>
				<td align="center">:</td>
				<td><?= (($sGrievancesManager == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>Attendance System</td>
				<td align="center">:</td>
				<td><?= (($sAttendance == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>Basket Emails</td>
				<td align="center">:</td>
				<td><?= (($sBasket == "Y") ? "Yes" : "No") ?></td>
			  </tr>

<?
	}
	
	
	$sBrandsList = "";

	$sSQL = "SELECT brand FROM tbl_brands WHERE id IN ($sBrands) ORDER BY brand";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sBrandsList .= (", ".$objDb->getField($i, 0));

		$sBrandsList = substr($sBrandsList, 2);
	}

	else
		$sBrandsList = "No Brand Assigned";
?>
			  <tr valign="top">
				<td>Brands</td>
				<td align="center">:</td>
				<td><?= $sBrandsList ?></td>
			  </tr>

<?
	$sVendorsList = "";

	$sSQL = "SELECT vendor FROM tbl_vendors WHERE id IN ($sVendors) ORDER BY vendor";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sVendorsList .= (", ".$objDb->getField($i, 0));

		$sVendorsList = substr($sVendorsList, 2);
	}

	else
		$sVendorsList = "No Vendor Assigned";
?>
			  <tr valign="top">
				<td>Vendors</td>
				<td align="center">:</td>
				<td><?= $sVendorsList ?></td>
			  </tr>
			  
<?
	$sSuppliersList = "";

	$sSQL = "SELECT supplier FROM tbl_vendors WHERE id IN ($sSuppliers) ORDER BY supplier";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSuppliersList .= (", ".$objDb->getField($i, 0));

		$sSuppliersList = substr($sSuppliersList, 2);
	}

	else
		$sSuppliersList = "No Supplier Assigned";
?>
			  <tr valign="top">
				<td>Suppliers</td>
				<td align="center">:</td>
				<td><?= $sSuppliersList ?></td>
			  </tr>

<?
	$sStyleCategoriesList = "";

	$sSQL = "SELECT category FROM tbl_style_categories WHERE id IN ($sStyleCategories) ORDER BY category";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sStyleCategoriesList .= (", ".$objDb->getField($i, 0));

		$sStyleCategoriesList = substr($sStyleCategoriesList, 2);
	}

	else
		$sStyleCategoriesList = "No Style Category Assigned";
?>
			  <tr valign="top">
				<td>Style Categories</td>
				<td align="center">:</td>
				<td><?= $sStyleCategoriesList ?></td>
			  </tr>

			  <tr>
				<td>Audits Manager</td>
				<td align="center">:</td>
				<td><?= (($sAuditsManager == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>QA Auditor</td>
				<td align="center">:</td>
				<td><?= (($sAuditor == "Y") ? ("Yes".(($sAuditorLevel != "" || $sAuditorType != "") ? (" (".(($sAuditorLevel != "") ? "{$sAuditorLevel} - " : "")."{$sAuditorType})") : "")) : "No") ?></td>
			  </tr>

<?
	if ($sAuditor == "Y")
	{
?>
			  <tr>
				<td>Auditor Code</td>
				<td align="center">:</td>
				<td><?= $iAuditorCode ?></td>
			  </tr>
<?
	}

	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
	{
?>
			  <tr>
				<td>Non-Prod. Schedules</td>
				<td align="center">:</td>
				<td><?= (($sNonProductionSchedules == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
				<td>User Schedules</td>
				<td align="center">:</td>
				<td><?= (($sShowUserSchedules == "Y") ? "Yes" : "No") ?> <small>(Show Non-Production Schedules on Dashboard)</small></td>
			  </tr>

			  <tr>
				<td>Skip Image (APP)</td>
				<td align="center">:</td>
				<td><?= (($sSkipImage == "Y") ? "Yes" : "No") ?></td>
			  </tr>

<?
	}
	
	
	
	$sReportTypesList = "";

	$sSQL = "SELECT report FROM tbl_reports WHERE id IN ($sReportTypes) ORDER BY report";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sReportTypesList .= (", ".$objDb->getField($i, 0));

		$sReportTypesList = substr($sReportTypesList, 2);
	}

	else
		$sReportTypesList = "No Report Type Assigned";
?>
			  <tr valign="top">
				<td>Report Types</td>
				<td align="center">:</td>
				<td><?= $sReportTypesList ?></td>
			  </tr>

<?
	$sAuditStagesList = "";

	$sSQL = "SELECT stage FROM tbl_audit_stages WHERE FIND_IN_SET(`code`, '$sAuditStages') ORDER BY stage";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sAuditStagesList .= (", ".$objDb->getField($i, 0));

		$sAuditStagesList = substr($sAuditStagesList, 2);
	}

	else
		$sAuditStagesList = "No Audit Stage Assigned";
?>
			  <tr valign="top">
				<td>Audit Stages</td>
				<td align="center">:</td>
				<td><?= $sAuditStagesList ?></td>
			  </tr>
			</table>

			<br />
		  </td>
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