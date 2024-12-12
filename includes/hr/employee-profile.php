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

	$sSQL = "SELECT *,
	                (SELECT country FROM tbl_countries WHERE id=tbl_users.country_id) AS _Country
	         FROM tbl_users
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sName              = $objDb->getField(0, "name");
		$sGender            = $objDb->getField(0, "gender");
		$sDob               = $objDb->getField(0, "dob");
		$sAddress           = $objDb->getField(0, "address");
		$sCity              = $objDb->getField(0, "city");
		$sState             = $objDb->getField(0, "state");
		$sZipCode           = $objDb->getField(0, "zip_code");
		$sCountry           = $objDb->getField(0, "_Country");
		$sEmail             = $objDb->getField(0, "email");
		$sPhone             = $objDb->getField(0, "phone");
		$sMobile            = $objDb->getField(0, "mobile");
		$sOrganization      = $objDb->getField(0, "organization");
		$sJoiningDate       = $objDb->getField(0, "joining_date");
		$sCardId            = $objDb->getField(0, "card_id");
		$iDesignation       = $objDb->getField(0, "designation_id");
		$sNicNo             = $objDb->getField(0, "nic_no");
		$sMaritalStatus     = $objDb->getField(0, "marital_status");
		$sSpouseName        = $objDb->getField(0, "spouse_name");
		$iChildren          = $objDb->getField(0, "children");
		$sBloodGroup        = $objDb->getField(0, "blood_group");
		$sEmergencyName     = $objDb->getField(0, "emergency_name");
		$sEmergencyPhone    = $objDb->getField(0, "emergency_phone");
		$sEmergencyAddress  = $objDb->getField(0, "emergency_address");
		$sPersonalGoals     = $objDb->getField(0, "personal_goals");
		$sTrainingsRequired = $objDb->getField(0, "trainings_required");
		$sPicture           = $objDb->getField(0, "picture");
		$sUsername          = $objDb->getField(0, "username");
		$sAdmin             = $objDb->getField(0, "admin");
		$sBrands            = $objDb->getField(0, "brands");
		$sVendors           = $objDb->getField(0, "vendors");
		$sDateTime          = $objDb->getField(0, "date_time");

		switch ($objDb->getField(0, "status"))
		{
			case "A" : $sStatus = "Active"; break;
			case "D" : $sStatus = "Disabled"; break;
			case "L" : $sStatus = "Left"; break;
			case "P" : $sStatus = "Account Not Approved"; break;
		}

		if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
			$sPicture = "default.jpg";


		$sSQL = "SELECT designation, department_id, reporting_to FROM tbl_designations WHERE id='$iDesignation'";
		$objDb->query($sSQL);

		$sDesignation = $objDb->getField(0, 'designation');
		$iDepartment  = $objDb->getField(0, 'department_id');
		$iReportingTo = $objDb->getField(0, 'reporting_to');

		$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");
		$sReportingTo = getDbValue("designation", "tbl_designations", "id='$iReportingTo'");
	}
?>
				  <table border="0" cellpadding="3" cellspacing="0" width="95%" align="center">
				    <tr>
				  	  <td width="120">Gender</td>
					  <td width="20" align="center">:</td>
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
					  <td>Department</td>
					  <td align="center">:</td>
					  <td><?= $sDepartment ?></td>
				    </tr>

				    <tr>
					  <td>Reporting To</td>
					  <td align="center">:</td>
					  <td><?= $sReportingTo ?></td>
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
					  <td>SignUp Date / Time</td>
					  <td align="center">:</td>
					  <td><?= formatDate($sDateTime, "dS F Y h:i A") ?></td>
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
				  </table>
