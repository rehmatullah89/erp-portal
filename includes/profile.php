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

	$sSQL = "SELECT name, gender, picture, organization, brands, vendors, designation_id, auditor_type,
                                        (SELECT type FROM tbl_user_types WHERE id=tbl_users.auditor_type) AS _AuditorType,
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
        $sAuditorType  = $objDb->getField(0, "_AuditorType");
	$sLoginTime    = $objDb->getField(0, "_LoginTime");

	if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
		$sPicture = "default.jpg";

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

                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="height: 100%;">
                                                <tr valign="top"><td width="50">&nbsp;</td><td><div class="TitleName"><?= $sName ?> <span>(<?= (($sOrganization == "") ? "N/A" : $sOrganization) ?>)</span></div></td><td width="50">&nbsp;</td></tr>
                                                <tr><td colspan="3">&nbsp;</td></tr>
                                                <tr valign="top">
                                                      <td colspan="3" align="center">
                                                        <div id="ProfilePic" <?= (($sPicture != "default.jpg") ? ' onmouseover="$(\'Link\').show( );" onmouseout="$(\'Link\').hide( );"' : '') ?>>
                                                          <div id="Pic2">
                                                              <img class="imgA2" src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" alt="<?= $sName ?>" title="<?= $sName ?>" />                                                              
                                                          </div>
                                                            <img class="imgA1" src="images/over-layer.png"/>
                                                          <div id="Link" style="display:none;">[ <a href="my-picture.php" class="lightview" rel="iframe" title="User : <?= $sName ?> :: :: width: 800, height: 600">Edit Picture</a> ]</div>
                                                        </div>
                                                      </td>
                                                </tr>                                                
					  </table>
<div style="margin-top: 85px; position: relative;">
                        <div  class="BrandProfileTag"><img id="BrandsTip" src="images/icons/gray.png" align="right" alt="" title="" /><font style="float: right;">Brands</font></div> 
                        <div  class="AuditorProfileTag"><?=$sAuditorType?></div>
                        
                        <div class="IconsLeft">
                            <a href="do-sign-out.php"><img width="16" src="images/icons/sign-out.png" alt="Sign Out" title="Sign Out" /></a><br/><br/>
                            <a href="my-account.php"><img width="16" src="images/icons/edit.png" alt="Edit Profile" title="Edit Profile" /></a>
                        </div>
                        <div class="vendorProfileTag"><img id="VendorsTip" src="images/icons/gray.png" align="right" alt="" title="" /><font style="margin-top: 2px !important;">Vendors</font></div>                                                
</div>					  
                                        <script type="text/javascript">
                                            <!--
                                                    new Tip('BrandsTip',
                                                            "<?= $sBrandsTip ?>",
                                                            { title:'Brands', stem:'topLeft', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:500 });

                                                    new Tip('VendorsTip',
                                                            "<?= $sVendorsTip ?>",
                                                            { title:'Vendors', stem:'topLeft', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:840 });            
                                                    -->
                                        </script>

