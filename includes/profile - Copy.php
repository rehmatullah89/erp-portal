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

	$sSQL = "SELECT name, gender, picture, organization, brands, vendors, designation_id,
					(SELECT country FROM tbl_countries WHERE id=tbl_users.country_id) AS _Country,
					(SELECT time_in FROM tbl_attendance WHERE `date`=CURDATE( ) AND user_id=tbl_users.id) AS _LoginTime
			 FROM tbl_users
			 WHERE id='{$_SESSION['UserId']}'";
	$objDb->query($sSQL);

	$sName         = $objDb->getField(0, "name");
	$sGender       = $objDb->getField(0, "gender");
	$sCountry      = $objDb->getField(0, "_Country");
	$sOrganization = $objDb->getField(0, "organization");
	$iDesignation  = $objDb->getField(0, "designation_id");
	$sBrands       = $objDb->getField(0, "brands");
	$sVendors      = $objDb->getField(0, "vendors");
	$sPicture      = $objDb->getField(0, "picture");
	$sLoginTime    = $objDb->getField(0, "_LoginTime");

	if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
		$sPicture = "default.jpg";


	$sSQL = "SELECT designation, department_id FROM tbl_designations WHERE id='$iDesignation'";
	$objDb->query($sSQL);

	$sDesignation = $objDb->getField(0, 'designation');
	$iDepartment  = $objDb->getField(0, 'department_id');

	$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");
?>
					  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					    <tr valign="top">
					      <td width="175">
					        <div id="ProfilePic" <?= (($sPicture != "default.jpg") ? ' onmouseover="$(\'Link\').show( );" onmouseout="$(\'Link\').hide( );"' : '') ?>>
					          <div id="Pic"><img src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" alt="<?= $sName ?>" title="<?= $sName ?>" /></div>
					          <div id="Link" style="display:none;">[ <a href="my-picture.php" class="lightview" rel="iframe" title="User : <?= $sName ?> :: :: width: 800, height: 600">Edit Picture</a> ]</div>
					        </div>
					      </td>

					      <td>
<?
	$sSQL = "SELECT id FROM tbl_users WHERE designation_id=(SELECT id FROM tbl_designations WHERE designation='HR Manager')";
	$objDb->query($sSQL);

	$iHrId = $objDb->getField(0, 0);
	$sHrId = str_pad($iHrId, 3, '0', STR_PAD_LEFT);

	if ($iHrId == $_SESSION['UserId'])
		$sSQL = "SELECT COUNT(*) FROM tbl_hr_messages WHERE status='0' AND recipients='$sHrId' AND parent_id='0'";

	else
		$sSQL = "SELECT COUNT(*) FROM tbl_hr_messages WHERE status='2' AND (parent_id='0' AND sender_id='{$_SESSION['UserId']}') OR (parent_id='0' AND recipients LIKE '%$Id%' AND LENGTH(recipients) > 3)";

	$objDb->query($sSQL);

	$iMsgCount = $objDb->getField(0, 0);


	$sSQL = "SELECT COUNT(*) FROM tbl_user_notifications WHERE user_id='{$_SESSION['UserId']}' AND status='N'";
	$objDb->query($sSQL);

	$iNotifications = $objDb->getField(0, 0);
?>
					        <span style="color:<?= (($iMsgCount > 0) ? '#ff0000' : '#000000') ?>;"><?= $iMsgCount ?></span> New HR Messages<br />
					        <div style="height:5px;"></div>
					        <span style="color:<?= (($iNotifications > 0) ? '#ff0000' : '#000000') ?>;"><?= $iNotifications ?></span> New Notifications<br />
					        <br />
<?
	if ($sLoginTime != "")
	{
?>
					        <b>Today's Login Time:</b> <?= formatTime($sLoginTime) ?><br />
<?
	}
?>
					      </td>
					    </tr>
					  </table>

					  <br />
					  <b class="red"><?= $sName ?></b><br />
					  <div style="height:5px;"></div>

					  <table border="0" cellpadding="2" cellspacing="0" width="100%">
					    <tr>
					      <td width="85"><b>Organization</b></td>
					      <td width="20" align="center">:</td>
					      <td><?= (($sOrganization == "") ? "N/A" : $sOrganization) ?></td>
					    </tr>

					    <tr>
					      <td><b>Designation</b></td>
					      <td align="center">:</td>
					      <td><?= (($sDesignation == "") ? "N/A" : $sDesignation) ?></td>
					    </tr>

<?
	$sBrandsList = "";

	$sSQL = "SELECT brand FROM tbl_brands WHERE id IN ($sBrands) ORDER BY brand";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sBrandsList .= (", ".$objDb->getField($i, "brand"));

		$sBrandsList = substr($sBrandsList, 2);
	}

	else
		$sBrandsList = "No Brand Assigned";
?>
					    <tr>
					      <td><b>Brands</b></td>
					      <td align="center">:</td>

					      <td>
<?
	if (strlen($sBrandsList) >= 25)
	{
		$sBrands = @explode(", ", $sBrandsList);
		$iCount  = count($sBrands);

		$sBrandsTip  = '<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">';

		for ($i = 0; $i < $iCount;)
		{
			$sBrandsTip .= '<tr valign=\"top\">';

			for ($j = 0; $j < 3; $j ++)
			{
				$sBrandsTip .= ('<td>');

				if ($i < $iCount)
				{
					$sBrandsTip .= ("&raquo; ".$sBrands[$i]);
					$i ++;
				}

				$sBrandsTip .= ('</td>');
			}

			$sBrandsTip .= '</tr>';
		}

		$sBrandsTip .= '</table>';
?>
					        <div>
					          <img id="BrandsTip" src="images/icons/more.gif" width="16" height="16" align="right" alt="" title="" />
					          <?= substr($sBrandsList, 0, 22) ?>...
					        </div>

							<script type="text/javascript">
							<!--
								new Tip('BrandsTip',
								        "<?= $sBrandsTip ?>",
								        { title:'Brands', stem:'topLeft', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:500 });
								-->
							</script>
<?
	}

	else
	{
?>
					        <?= $sBrandsList ?><br />
<?
	}
?>
					      </td>
					    </tr>

<?
	$sVendorsList = "";

	$sSQL = "SELECT vendor FROM tbl_vendors WHERE id IN ($sVendors) ORDER BY vendor";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sVendorsList .= (", ".$objDb->getField($i, "vendor"));

		$sVendorsList = substr($sVendorsList, 2);
	}

	else
		$sVendorsList = "No Vendor Assigned";
?>
					    <tr>
					      <td><b>Vendors</b></td>
					      <td align="center">:</td>

					      <td>
<?
	if (strlen($sVendorsList) >= 25)
	{
		$sVendors = @explode(", ", $sVendorsList);
		$iCount   = count($sVendors);

		$sVendorsTip  = '<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">';

		for ($i = 0; $i < $iCount;)
		{
			$sVendorsTip .= '<tr valign=\"top\">';

			for ($j = 0; $j < 5; $j ++)
			{
				$sVendorsTip .= ('<td>');

				if ($i < $iCount)
				{
					$sVendorsTip .= ("&raquo; ".$sVendors[$i]);
					$i ++;
				}

				$sVendorsTip .= ('</td>');
			}

			$sVendorsTip .= '</tr>';
		}

		$sVendorsTip .= '</table>';
?>
					        <div>
					          <img id="VendorsTip" src="images/icons/more.gif" width="16" height="16" align="right" alt="" title="" />
					          <?= substr($sVendorsList, 0, 22) ?>...
					        </div>

							<script type="text/javascript">
							<!--
								new Tip('VendorsTip',
								        "<?= $sVendorsTip ?>",
								        { title:'Vendors', stem:'topLeft', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:840 });
								-->
							</script>
<?
	}

	else
	{
?>
					        <?= $sVendorsList ?><br />
<?
	}
?>
					      </td>
					    </tr>
					  </table>

					  <div class="profileLinks">
					    [
					    <a href="my-account.php">Edit Profile</a>
					    |
					    <a href="do-sign-out.php">Signout</a>
					    ]
					  </div>
