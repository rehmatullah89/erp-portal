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
?>
				  <h2>
<?
	if (@strpos($_SESSION["Email"], "apparelco.com") === FALSE)
	{
		if ($FromDate != date("Y-m-d"))
		{
?>
				    <span style="float:right; padding-right:5px;">[ <a href="quonda/dashboard.php?FromDate=<?= date("Y-m-d") ?>&ToDate=<?= date("Y-m-d") ?>" style="color:#ffff00;">Today's Audits</a> ]</span>
<?
		}

		else
		{
?>
				    <span style="float:right; padding-right:5px;">[ <a href="quonda/dashboard.php" style="color:#ffff00;">Recent Audits</a> ]</span>
<?
		}
	}
?>
				    Audits (<?= formatDate($FromDate) ?>)
				  </h2>

	  			  <div style="padding-left:5px;">
	  			    <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  			      <tr>
					    <td width="11"><div style="height:11px; width:11px; background:#b6e500;"></div></td>
					    <td width="80">Custom</td>
<?
	$iIndex = 1;

	foreach ($sAuditStagesList as $sKey => $sValue)
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sValue == "Final")
			$sValue = "Firewall";

		if ( (@strpos($_SESSION["Email"], "pelknit.com") !== FALSE || @strpos($_SESSION["Email"], "fencepostproductions.com") !== FALSE) &&
			 !@in_array($sKey, array("B", "C", "O", "F")) )
			continue;
?>
							<td width="11"><div style="height:11px; width:11px; background:<?= $sStageColorsList[$sKey] ?>;"></div></td>
							<td width="80"><?= $sValue ?></td>
<?
		$iIndex ++;

		if (($iIndex % 9) == 0)
		{
?>
							<td></td>
						  </tr>
						</table>

	  			    	<table border="0" cellpadding="3" cellspacing="0" width="100%">
						  <tr>
<?
		}
	}
?>
					    <td></td>
					  </tr>
				    </table>
				    </div>

				    <hr />

<?
	// Measurement Defect Codes
	$sSQL = "SELECT id FROM tbl_defect_codes WHERE type_id='7'";
	$objDb->query($sSQL);

	$iCount            = $objDb->getCount( );
	$sMeasurementCodes = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sMeasurementCodes .= (",".$objDb->getField($i, 0));

	if ($sMeasurementCodes != "")
		$sMeasurementCodes = substr($sMeasurementCodes, 1);


	$sConditions = " WHERE qa.audit_date='$FromDate' AND qa.user_id=u.id ";

	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
		$sConditions .= " AND qa.audit_stage='F' AND qa.audit_result!='' ";

	else if (@strpos($_SESSION["Email"], "dkcompany.com") !== FALSE || @strpos($_SESSION["Email"], "hema.nl") !== FALSE)
		$sConditions .= " AND qa.audit_stage='F' AND qa.audit_result='P' ";

	else if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage' ";

	if ($Brand > 0)
		$sConditions .= " AND qa.style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}'))";

	else
	{
		if (@strpos($_SESSION["Email"], "apparelco.com") === FALSE)
			$sConditions .= " AND qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}'))";

		else
			$sConditions .= " AND (qa.style_id='0' OR qa.style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";
	}


	$sRegionSql   = "";

	if ($Region > 0)
	{
		$sRegionSql   = " AND u.country_id='$Region' ";
		$sConditions .= " AND u.country_id='$Region' ";
	}

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND qa.vendor_id IN ({$_SESSION['Vendors']}) ";


	$sSQL = "SELECT DISTINCT(u.id) AS _Id, GROUP_CONCAT(qa.vendor_id SEPARATOR ',') AS _Vendors, 'A' AS _Type, u.name AS _Name, '' AS _Users, u.picture AS _Picture, u.latitude AS _Latitude, u.longitude AS _Longitude, u.location_time AS _DateTime
	         FROM tbl_qa_reports qa, tbl_users u
	         $sConditions AND qa.group_id='0'
	         GROUP BY _Id

	         UNION

	         SELECT DISTINCT(g.id) AS _Id, GROUP_CONCAT(qa.vendor_id SEPARATOR ',') AS _Vendors, 'G' AS _Type, g.name AS _Name, g.users AS _Users, '' AS _Picture, u.latitude AS _Latitude, u.longitude AS _Longitude, u.location_time AS _DateTime
	         FROM tbl_qa_reports qa, tbl_users u, tbl_auditor_groups g
	         $sConditions AND g.id=qa.group_id AND qa.group_id > '0'
	         GROUP BY _Id ";

	if (@strpos($_SESSION["Email"], "apparelco.com") !== FALSE || $_SESSION['Guest'] == "Y")
	{
		if ($Vendor == 0 && $Brand == 0)
		{
			$sSQL .= "UNION

					  SELECT DISTINCT(us.user_id) AS _Id, '0' AS _Vendors, 'S' AS _Type, u.name AS _Name, '' AS _Users, u.picture AS _Picture, u.latitude AS _Latitude, u.longitude AS _Longitude, u.location_time AS _DateTime
					  FROM tbl_user_schedule us, tbl_users u
					  WHERE us.user_id=u.id AND ('$FromDate' BETWEEN us.from_date AND us.to_date) AND u.status='A' AND u.email LIKE '%@apparelco.com' AND
							u.designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (8,15,31,41)) $sRegionSql";
		}
	}

	$sSQL .= "ORDER BY _Name";

	$objDb->query($sSQL);

	$iAuditors = $objDb->getCount( );



	$sConditions = " WHERE audit_date='$FromDate' ";

	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE)
		$sConditions .= " AND audit_stage='F' AND audit_result!='' ";

	else if (@strpos($_SESSION["Email"], "dkcompany.com") !== FALSE || @strpos($_SESSION["Email"], "hema.nl") !== FALSE)
		$sConditions .= " AND audit_stage='F' AND audit_result='P' ";

	else if ($AuditStage != "")
		$sConditions .= " AND audit_stage='$AuditStage' ";

	if ($Brand > 0)
		$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE sub_brand_id='$Brand' AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}'))";

	else
	{
		if (@strpos($_SESSION["Email"], "apparelco.com") === FALSE)
			$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}'))";

		else
			$sConditions .= " AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";
	}

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";



	$iVendors = array( );

	$sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_qa_reports $sConditions";
	$objDb2->query($sSQL);

	$iCount2 = $objDb2->getCount( );

	for ($i = 0; $i < $iCount2; $i ++)
		$iVendors[] = $objDb2->getField($i, 0);

	$sVendors = @implode(",", $iVendors);



	$sSQL = "SELECT city,
	               (SELECT code FROM tbl_countries WHERE id=tbl_vendors.country_id) AS _Country,
	               GROUP_CONCAT(id SEPARATOR ',') AS _Vendors
	        FROM tbl_vendors
	        WHERE FIND_IN_SET(id, '$sVendors')
	        GROUP BY city
	        ORDER BY city, _Country";
	$objDb2->query($sSQL);

	$iCount2 = $objDb2->getCount( );
	$sCities = array( );

	for ($i = 0; $i < $iCount2; $i ++)
	{
		$sCity    = $objDb2->getField($i, "city");
		$sCountry = $objDb2->getField($i, "_Country");
		$sVendors = $objDb2->getField($i, "_Vendors");

		$sCities["{$sCity}, {$sCountry}"] = $sVendors;
	}


	$iDistinctAuditors = array( );
	$iDistinctGroups   = array( );
	$iActiveAuditors   = array( );
	$iScheduleUsers    = array( );
	$iGroupAuditors    = array( );

	for ($i = 0; $i < $iAuditors; $i ++)
	{
		$iAuditorId = $objDb->getField($i, '_Id');
		$sType      = $objDb->getField($i, '_Type');
		$sUsers     = $objDb->getField($i, '_Users');


		if ($sType == "S")
			$iScheduleUsers[] = $iAuditorId;

		else if ($sType == "A" && !@in_array($iAuditorId, $iDistinctAuditors))
			$iDistinctAuditors[] = $iAuditorId;

		else if ($sType == "G" && !@in_array($iAuditorId, $iDistinctGroups))
			$iDistinctGroups[] = $iAuditorId;


		if ($sType != "S")
		{
			if ($sType == "G")
			{
				$iUsers = @explode(",", $sUsers);

				foreach ($iUsers as $iUser)
				{
					if (!@in_array($iUser, $iActiveAuditors) && $iUser > 0)
						$iActiveAuditors[] = $iUser;

					if ($iUser > 0)
						$iGroupAuditors[] = $iUser;
				}
			}

			else
			{
				if (!@in_array($iAuditorId, $iActiveAuditors))
					$iActiveAuditors[] = $iAuditorId;
			}
		}


		if ($sType == "S")
			$sCities["MATRIX Sourcing"] = 0;
	}


	$iRows  = (($iAuditors == 0) ? 10 : (count($iDistinctAuditors) + count($iDistinctGroups)));
	$iRows += count($sCities);

	foreach ($iScheduleUsers as $iUser)
	{
		if (!@in_array($iUser, $iDistinctAuditors) && !@in_array($iUser, $iGroupAuditors))
			$iRows ++;
	}
?>
				    <div style="position:relative;">
						<div style="position:relative;">
						  <div align="right" style="padding:21px 0px 20px 0px;">
							<table border="0" cellpadding="0" cellspacing="0" width="768">
<?
	for ($i = 0; $i < $iRows; $i ++)
	{
?>
					  		<tr>
<?
		for ($j = 0; $j < 24; $j ++)
		{
?>
					    		<td width="32"><div style="height:34px; border-left:dotted 1px #999999; border-top:dotted 1px #999999;"></div></td>
<?
		}
?>
					  		</tr>
<?
	}
?>

					  		<tr>
<?
	for ($j = 0; $j < 24; $j ++)
	{
?>
					    		<td width="32"><div style="height:18px; border-left:dotted 1px #999999; border-top:dotted 1px #999999;"></div></td>
<?
	}
?>
							  </tr>
							</table>
						  </div>


						  <div style="position:absolute; right:0px; top:0px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							  <tr>
								<td width="150"></td>
								<td></td>

								<td width="768" style="border-bottom:solid 2px #666666;">

								  <table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
<?
	for ($i = 0; $i < 24; $i ++)
	{
?>
					          		  <td width="32"><small><?= substr(date("ga", mktime($i, 0, 0, date("m"), date("d"), date("Y"))), 0, -1) ?></small></td>
<?
	}
?>
									</tr>

									<tr>
<?
	for ($i = 0; $i < 24; $i ++)
	{
?>
					          		<td width="32"><div style="width:2px; height:6px; background:#666666;"></div></td>
<?
	}
?>
									</tr>
								  </table>

								</td>
							  </tr>

<?
	foreach ($sCities as $sCity => $sCityVendors)
	{
		$iVendorsList = @explode(",", $sCityVendors);
?>
						  <tr>
							<td align="right" bgcolor="#e6e6e6"><b><?= $sCity ?></b></td>
							<td style="border-right:solid 2px #666666;" height="35" bgcolor="#e6e6e6"></td>

							<td width="768" height="35">

							  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="25">
								<tr>
								  <td width="100%" bgcolor="#f3f3f3">&nbsp;</td>
								</tr>
							  </table>

							</td>
						  </tr>
<?
		for ($i = 0; $i < $iAuditors; $i ++)
		{
			$iAuditorId = $objDb->getField($i, '_Id');
			$sVendors   = $objDb->getField($i, '_Vendors');
			$sAuditor   = $objDb->getField($i, '_Name');
			$sType      = $objDb->getField($i, '_Type');
			$sUsers     = $objDb->getField($i, '_Users');
			$sPicture   = $objDb->getField($i, '_Picture');
			$sLatitude  = $objDb->getField($i, '_Latitude');
			$sLongitude = $objDb->getField($i, '_Longitude');
			$sDateTime  = $objDb->getField($i, '_DateTime');


			$bFound   = false;
			$iVendors = @explode(",", $sVendors);

			foreach ($iVendors as $iVendorId)
			{
				if (@in_array($iVendorId, $iVendorsList))
					$bFound = true;
			}


			if ($bFound == false)
				continue;

			if ($sType == "S" && @in_array($iAuditorId, $iActiveAuditors))
				continue;



			if ($sDateTime == "" || (strtotime(date("Y-m-d H:i:s")) - strtotime($sDateTime)) > 43200)
			{
				$sLatitude  = "31.3974864";
				$sLongitude = "74.2207633";
				$sLocation  = " - MATRIX Sourcing";
			}

			if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
				$sPicture = "default.jpg";

			$sPicture = (USERS_IMG_PATH.'thumbs/'.$sPicture);


			$sTooltip  = "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
			$sTooltip .= "  <tr valign='top'>";
			$sTooltip .= "    <td>";
			$sTooltip .= "      <center><div style='border:solid 1px #bbbbbb; padding:1px;'><img src='{$sPicture}' width='100' height='75' alt='' title='' /></div></center><br />";
	//		$sTooltip .= "      <b style='font-size:10px;'>Coorelation:<br />0.0%</b><br /><br />";
	//		$sTooltip .= "      <b style='font-size:10px;'>Productivity:<br />0.0%</b><br />";
			$sTooltip .= "    </td>";
			$sTooltip .= "    <td width='10'></td>";
			$sTooltip .= "    <td width='250'>";
			$sTooltip .= "      <div id='Map{$i}' style='250px; height:180px;'></div>";
			$sTooltip .= "    </td>";
			$sTooltip .= "  </tr>";
			$sTooltip .= "  </table>";


	//		$sLatitude  = "31.3974864";
	//		$sLongitude = "74.2207633";
	//		$sLocation  = " - MATRIX Sourcing";

			if ($sType == "A" || $sType == "G")
			{
				if ($sType == "A")
					$sSubSQL = " AND user_id='$iAuditorId' AND group_id='0' ";

				else if ($sType == "G")
					$sSubSQL = " AND group_id='$iAuditorId' ";

				$sSubSQL .= " AND FIND_IN_SET(vendor_id, '$sCityVendors') ";


				if ($sLatitude  == "31.3974864" && $sLongitude == "74.2207633")
				{
					$sSQL = "SELECT vendor_id FROM tbl_qa_reports WHERE audit_date='$FromDate' AND audit_result!='' $sSubSQL ORDER BY id DESC LIMIT 1";
					$objDb2->query($sSQL);

					if ($objDb2->getCount( ) == 0)
					{
						$sSQL = "SELECT vendor_id FROM tbl_qa_reports WHERE audit_date='$FromDate' $sSubSQL ORDER BY id DESC LIMIT 1";
						$objDb2->query($sSQL);
					}


					if ($objDb2->getCount( ) == 1)
					{
						$iVendorId = $objDb2->getField(0, 0);


						$sSQL = "SELECT vendor, latitude, longitude FROM tbl_vendors WHERE id='$iVendorId' AND latitude!='' AND longitude!=''";
						$objDb2->query($sSQL);

						if ($objDb2->getCount( ) == 1)
						{
							$sLocation  = (" - ".$objDb2->getField(0, 'vendor'));

							$sLatitude  = $objDb2->getField(0, 'latitude');
							$sLongitude = $objDb2->getField(0, 'longitude');
						}
					}
				}
			}
?>
						  <tr>
							<td align="right">
							  <span id="Auditor<?= $i ?>" style="display:block; height:15px; overflow:hidden;"><?= $sAuditor ?></span>

							  <script type="text/javascript">
							  <!--
								  new Tip('Auditor<?= $i ?>',
										  "<?= $sTooltip ?> ",
										  { title:'<?= $sAuditor ?><?= $sLocation ?>', stem:'topLeft', showOn:'mouseover', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:374, hideOn:{ element:'closeButton', event:'click' }, hideAfter:0.1  });

								  $('Auditor<?= $i ?>').observe('prototip:shown', function( )
								  {
									  var objMap<?= $i ?> = new GMap(document.getElementById("Map<?= $i ?>"));

									  objMap<?= $i ?>.addControl(new GLargeMapControl3D( ));
									  objMap<?= $i ?>.addControl(new GMapTypeControl( ));
									  objMap<?= $i ?>.setCenter(new GLatLng(<?= $sLatitude ?>, <?= $sLongitude ?>), 12);
									  objMap<?= $i ?>.addOverlay(new GMarker(new GLatLng(<?= $sLatitude ?>, <?= $sLongitude ?>)));
								  });
							  -->
							  </script>
<?
			if ($sType == "G")
			{
?>
							  <div style="height:15px; overflow:hidden;">
<?
				$sSQL = "SELECT id, name, LEFT(NAME, (IF(LOCATE(' ', NAME), LOCATE(' ', NAME), LOCATE('-', NAME)) - 1)) AS _Name, picture, latitude, longitude, location_time FROM tbl_users WHERE id IN ($sUsers) ORDER BY name";
				$objDb2->query($sSQL);

				$iCount2 = $objDb2->getCount( );

				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iUserId       = $objDb2->getField($j, 'id');
					$sName         = $objDb2->getField($j, 'name');
					$sShortName    = $objDb2->getField($j, '_Name');
					$sPicture      = $objDb2->getField($j, 'picture');
					$sSubLatitude  = $objDb2->getField($j, 'latitude');
					$sSubLongitude = $objDb2->getField($j, 'longitude');
					$sDateTime     = $objDb2->getField($j, 'location_time');


//					$sSubLocation = "";

					if ($sDateTime == "" || (strtotime(date("Y-m-d H:i:s")) - strtotime($sDateTime)) > 43200)
					{
						$sSubLatitude  = $sLatitude;
						$sSubLongitude = $sLongitude;
//						$sSubLocation  = $sLocation;
					}

					if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
						$sPicture = "default.jpg";

					$sPicture = (USERS_IMG_PATH.'thumbs/'.$sPicture);


					$sTooltip  = "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
					$sTooltip .= "  <tr valign='top'>";
					$sTooltip .= "    <td>";
					$sTooltip .= "      <center><div style='border:solid 1px #bbbbbb; padding:1px;'><img src='{$sPicture}' width='100' height='75' alt='' title='' /></div></center><br />";
	//				$sTooltip .= "      <b style='font-size:10px;'>Coorelation:<br />0.0%</b><br /><br />";
	//				$sTooltip .= "      <b style='font-size:10px;'>Productivity:<br />0.0%</b><br />";
					$sTooltip .= "    </td>";
					$sTooltip .= "    <td width='10'></td>";
					$sTooltip .= "    <td width='250'>";
					$sTooltip .= "      <div id='Map{$i}_{$j}' style='250px; height:180px;'></div>";
					$sTooltip .= "    </td>";
					$sTooltip .= "  </tr>";
					$sTooltip .= "  </table>";
?>
								  <span id="Auditor<?= $i ?>_<?= $j ?>" style="display:inline-block; height:10px; overflow:hidden; font-size:9px; color:#888888;"><?= $sShortName ?></span><?= (($j < ($iCount2 - 1)) ? ', ' : '') ?>

								  <script type="text/javascript">
								  <!--
									  new Tip('Auditor<?= $i ?>_<?= $j ?>',
											  "<?= $sTooltip ?> ",
											  { title:'<?= $sName ?><?= $sLocation ?>', stem:'topLeft', showOn:'mouseover', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:374, hideOn:{ element:'closeButton', event:'click' }, hideAfter:0.1  });

									  $('Auditor<?= $i ?>_<?= $j ?>').observe('prototip:shown', function( )
									  {
										  var objMap<?= $i ?>_<?= $j ?> = new GMap(document.getElementById("Map<?= $i ?>_<?= $j ?>"));

										  objMap<?= $i ?>_<?= $j ?>.addControl(new GLargeMapControl3D( ));
										  objMap<?= $i ?>_<?= $j ?>.addControl(new GMapTypeControl( ));
										  objMap<?= $i ?>_<?= $j ?>.setCenter(new GLatLng(<?= $sSubLatitude ?>, <?= $sSubLongitude ?>), 12);
										  objMap<?= $i ?>_<?= $j ?>.addOverlay(new GMarker(new GLatLng(<?= $sSubLatitude ?>, <?= $sSubLongitude ?>)));
									  });
								  -->
								  </script>
<?
				}
?>
							  </div>
<?
			}
?>
							</td>

							<td height="35" align="right" style="border-right:solid 2px #666666; overflow:hidden;">
							  <div style="width:3px; height:2px; background:#666666;"></div>
							</td>

							<td style="overflow:hidden;">

							  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="25">
								<tr bgcolor="#fcfcfc">
<?
			if ($sType == "S")
			{
				$sSQL = "SELECT id, TIME_TO_SEC(start_time) AS _StartTime, TIME_TO_SEC(end_time) AS _EndTime, location_id, details
						 FROM tbl_user_schedule
						 WHERE user_id='$iAuditorId' AND ('$FromDate' BETWEEN from_date AND to_date)
						 ORDER BY start_time";
				$objDb2->query($sSQL);

				$iCount2   = $objDb2->getCount( );
				$iPrevious = 0;

				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iScheduleId = $objDb2->getField($j, 'id');
					$iStartTime  = $objDb2->getField($j, '_StartTime');
					$iEndTime    = $objDb2->getField($j, '_EndTime');
					$iLocationId = $objDb2->getField($j, 'location_id');
					$sDetails    = $objDb2->getField($j, 'details');

					$sDetails = str_replace("\r\n", "<br />", $sDetails);
					$sDetails = str_replace("\n", "<br />", $sDetails);

					if ($iStartTime > $iPrevious)
					{
						$iTime  = ($iStartTime - $iPrevious);
						$iTime /= 60;
						$iWidth = @round($iTime * 0.534);
						$iWidth = (($iWidth < 2) ? 2 : $iWidth);
?>
					          	<td width="<?= $iWidth ?>"></td>
<?
					}

					$iTime       = ($iEndTime - $iStartTime);
					$iTime      /= 60;
					$iWidth      = @round($iTime * 0.534);
					$iWidth      = (($iWidth < 2) ? 2 : $iWidth);
					$sBackground = "#b6e600";
?>
								  <td width="<?= $iWidth ?>">
									<div id="Schedule_<?= $iScheduleId ?>" style="width:<?= $iWidth ?>px; background:<?= $sBackground ?>; border-left:dotted 1px #ffffff; height:25px; line-height:25px;"></div>

									<script type="text/javascript">
									<!--
										new Tip('Schedule_<?= $iScheduleId ?>',
												"<?= htmlentities(str_replace("\r\n", "<br />", $sDetails)) ?>",
												{ title:'<?= $sLocationsList[$iLocationId] ?>', stem:'topLeft', showOn:'mouseover', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:300, hideOn:{ element:'closeButton', event:'click' }, hideAfter:0.1  });
									-->
									</script>
								  </td>
<?
					$iPrevious = $iEndTime;
				}
			}


			if ($sType == "A" || $sType == "G")
			{
				if ($sType == "A")
					$sSubSQL = " AND user_id='$iAuditorId' AND group_id='0' ";

				else if ($sType == "G")
					$sSubSQL = " AND group_id='$iAuditorId' ";

				$sSubSQL .= " AND FIND_IN_SET(vendor_id, '$sCityVendors') ";


				$sSQL = "SELECT id, TIME_TO_SEC(start_time) AS _StartTime, TIME_TO_SEC(end_time) AS _EndTime, audit_code, vendor_id, brand_id, style_id, po_id,
								start_time, end_time, report_id, audit_stage, audit_result, total_gmts, approved, status, checked_gmts,
								(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line,
								(SELECT category_id FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Category,
								(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id) AS _Defects,
								(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature='1') AS _JkDefects,
								(SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id) AS _GfDefects,
								(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND code_id NOT IN ($sMeasurementCodes)) AS _DefectPics,
								(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature='1' AND code_id NOT IN ($sMeasurementCodes)) AS _JkDefectPics,
								(SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id AND code_id NOT IN ($sMeasurementCodes)) AS _GfDefectPics
						 FROM tbl_qa_reports
						 $sConditions $sSubSQL
						 ORDER BY start_time";
				$objDb2->query($sSQL);

				$iCount2   = $objDb2->getCount( );
				$iPrevious = 0;

				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iAuditId     = $objDb2->getField($j, 'id');
					$iStartTime   = $objDb2->getField($j, '_StartTime');
					$iEndTime     = $objDb2->getField($j, '_EndTime');
					$sAuditCode   = $objDb2->getField($j, 'audit_code');
					$iVendorId    = $objDb2->getField($j, 'vendor_id');
					$iBrandId     = $objDb2->getField($j, 'brand_id');
					$iStyleId     = $objDb2->getField($j, 'style_id');
					$iPoId        = $objDb2->getField($j, 'po_id');
					$sStartTime   = $objDb2->getField($j, 'start_time');
					$sEndTime     = $objDb2->getField($j, 'end_time');
					$iReportId    = $objDb2->getField($j, 'report_id');
					$sAuditStage  = $objDb2->getField($j, 'audit_stage');
					$sAuditResult = $objDb2->getField($j, 'audit_result');
					$sStatus      = $objDb2->getField($j, 'status');
					$sLine        = $objDb2->getField($j, '_Line');
					$iCategoryId  = $objDb2->getField($j, '_Category');
					$iQuantity    = $objDb2->getField($j, 'total_gmts');
					$iDefects     = $objDb2->getField($j, '_Defects');
					$iDefects    += $objDb2->getField($j, '_GfDefects');
					$iDefectPics  = $objDb2->getField($j, '_DefectPics');
					$iDefectPics += $objDb2->getField($j, '_GfDefectPics');
					$sApproved    = $objDb2->getField($j, 'approved');
					$iChecked     = $objDb2->getField($j, 'checked_gmts');


					$bOnGoing = (($iChecked > 0 && $sAuditResult == "") ? true : false);

					if ($iReportId == 10)
					{
						$iDefects     = $objDb2->getField($j, '_JkDefects');
						$iDefectPics  = $objDb2->getField($j, '_JkDefectPics');
					}

					if ($iBrandId == 0)
					{
						if ($iStyleId > 0)
							$iBrandId = getDbValue("sub_brand_id", "tbl_styles", "id='$iStyleId'");

						else if ($iPoId > 0)
							$iBrandId = getDbValue("brand_id", "tbl_po", "id='$iPoId'");
					}

					if ($iStartTime > $iPrevious)
					{
						$iTime  = ($iStartTime - $iPrevious);
						$iTime /= 60;
						$iWidth = @round($iTime * 0.534);
						$iWidth = (($iWidth < 2) ? 2 : $iWidth);
?>
					          <td width="<?= $iWidth ?>"></td>
<?
					}

					$iAuditCode = substr($sAuditCode, 1);


					$iTime       = ($iEndTime - $iStartTime);
					$iTime      /= 60;
					$iWidth      = @round($iTime * 0.534);
					$iWidth      = (($iWidth < 2) ? 2 : $iWidth);
					$sBackground = "#dddddd";
					$sPictures   = array( );

					@list($sYear, $sMonth, $sDay) = @explode("-", $FromDate);

					if ($sAuditStage != "")
					{
						$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*");
						$sPictures = @array_map("strtoupper", $sPictures);
						$sPictures = @array_unique($sPictures);

						if (count($sPictures) > 0)
						{
							$sTemp   = array( );
							$iLength = strlen($sAuditCode);

							foreach ($sPictures as $sPicture)
							{
								if (substr(@basename($sPicture), 0, ($iLength + 4)) == "{$sAuditCode}_001" ||
									substr(@basename($sPicture), 0, ($iLength + 4)) == "{$sAuditCode}_00_" ||
									strlen(@basename($sPicture)) < ($iLength + 6))
									continue;

								$sTemp[] = $sPicture;
							}

							$sPictures = $sTemp;
						}
					}

					$sTooltip  = "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
					$sTooltip .= "  <tr valign='top'>";
					$sTooltip .= "    <td>";
//					$sTooltip .= "      <b>Vendor:</b> {$sVendorsList[$iVendorId]}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>";
					$sTooltip .= ("     <b>Start Time:</b> ".formatTime($sStartTime)."<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");
					$sTooltip .= ("     <b>End Time:</b> ".formatTime($sEndTime)."<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>");

					if ($_SESSION['Guest'] != "Y")
						$sTooltip .= "      <b>Report Type:</b> {$sReportsList[$iReportId]}<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>";

					$sTooltip .= "      <b>Line:</b> {$sLine}";

					if ($iQuantity > 0)
						$sTooltip .= "      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Sample Size:</b> {$iQuantity}";

					if ($sApproved == "N")
						$sTooltip .= "<span id='Approve{$iAuditCode}'><div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b align='right'><a href='#' onclick='return approveAudit($iAuditCode);'>Approve</a></b></span>";

					else if ($sAuditResult == "" && strtotime($FromDate) >= strtotime(date("Y-m-d")))
					{
						$sTooltip .= "<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div>";
						$sTooltip .= "<span id='Live{$iAuditCode}'><b align='right'><a href='".SITE_URL."dashboard/progress.php?AuditCode={$sAuditCode}' target='_blank'>Live View</a></b></span>";

						if (getDbValue("COUNT(*)", "tbl_audit_subscriptions", "audit_id='$iAuditId' AND user_id='{$_SESSION['UserId']}'") == 0)
						{
							$sTooltip .= "&nbsp;|&nbsp;";
							$sTooltip .= "<span id='Subscribe{$iAuditCode}'><b align='right'><a href='quonda/subscribe-audit.php?Id={$iAuditId}' onclick='Tips.hideAll( );' class='lightview' rel='iframe' title='Subscribe Audit : {$sAuditCode} :: :: width: 500, height: 400'>Subscribe</a></b></span>";
						}
					}

					if ($sAuditResult != "" && (!@in_array($iReportId, array(3, 12)) || @in_array($iBrandId, array(32, 87, 119, 120, 121))))
						$sTooltip .= "<div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Report Extraction:</b><br /><a href='quonda/export-qa-report.php?Id={$iAuditId}&ReportId={$iReportId}&Brand={$iBrandId}&AuditStage={$sAuditStage}' onclick='Tips.hideAll( );'>Download QA Report</a>";


					if ($sAuditStage != "")
					{
						if ($iStyleId == 0 || $iPoId == 0)
							$sBackground = "#dddddd";

						else
							$sBackground = $sStageColorsList[$sAuditStage];

						if ($sStatus == "")
							$sStatus = $sAuditResult;

						switch ($sStatus)
						{
							case "P"  :  $sStatus = "Pass"; break;
							case "F"  :  $sStatus = "Fail"; break;
							case "H"  :  $sStatus = "Hold"; break;
							case "A"  :  $sStatus = "Pass"; break;
							case "B"  :  $sStatus = "Pass"; break;
							case "C"  :  $sStatus = "Fail"; break;
							case "LP" :  $sStatus = ("Likely to Pass at ".((@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE) ? "Firewall" : "Final")." Audit"); break;
							case "PF" :  $sStatus = ("Possible Failure at ".((@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE) ? "Firewall" : "Final")." Audit"); break;
							case "LF" :  $sStatus = ("Likely to Fail at ".((@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE) ? "Firewall" : "Final")." Audit"); break;
						}

						$sTooltip .= "      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Audit Result:</b> {$sStatus}";
					}

					if ($iDefects > 0)
						$sTooltip .= "      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Total Defects:</b> {$iDefects}";

					if (count($sPictures) > 0)
						$sTooltip .= ("      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Defect Pictures:</b> ".count($sPictures));

					if ($iDefectPics > 0)
						$sTooltip .= "      <div style='font-size:1px; line-height:1px; height:1px; overflow:hidden; background:#dddddd; padding:0px; margin:4px 0px 4px 0px;'></div><b>Pictures Required:</b> {$iDefectPics}";

					$sTooltip .= "    </td>";


					if (count($sPictures) > 0)
					{
						$sTooltip .= "    <td width='10'></td>";

						$sTooltip .= "    <td width='190'>";
						$sTooltip .= "      <table border='0' cellpadding='0' cellspacing='0' width='190'>";

						for ($k = 0; $k < 4;)
						{
							$sTooltip .= "        <tr valign='top'>";

							for ($l = 0; $l < 2; $l ++, $k ++)
							{
								$sTooltip .= "          <td width='95'>";

								if ($k < count($sPictures))
								{
									if (checkUserRights("qa-reports.php", "Quonda", "view"))
										$sTooltip .= ("<a href='quonda/qa-report-images.php?AuditCode=".$sAuditCode."' target='_blank'><img src='".(QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPictures[$k]))."' width='90' height='90' alt='' title='' style='border:solid 1px #888888;' /></a>");

									else
										$sTooltip .= ("<img src='".(QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sPictures[$k]))."' width='90' height='90' alt='' title='' style='border:solid 1px #888888;' />");
								}

								$sTooltip .= "          </td>";
							}

							$sTooltip .= "        </tr>";

							if ($k < 4)
							{
								$sTooltip .= "        <tr>";
								$sTooltip .= "          <td colspan='3' height='4'></td>";
								$sTooltip .= "        </tr>";
							}
						}

						$sTooltip .= "      </table>";
						$sTooltip .= "    </td>";
					}

					$sTooltip .= "  </tr>";
					$sTooltip .= "  </table>";
?>
								  <td width="<?= $iWidth ?>">
									<div id="Audit_<?= $sAuditCode ?>" style="width:<?= $iWidth ?>px; background:<?= $sBackground ?><?= (($sAuditResult != '') ? ' url(images/icons/done.png) 4px 4px no-repeat' : '') ?>; border-left:dotted 1px #ffffff; height:25px; line-height:25px;<?= ((($sAuditStage != "" && @strpos($_SESSION["Email"], "marksnspencer.com") === FALSE) || (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sAuditStage == "F")) ? ' cursor:pointer;' : '') ?>"<?= ((($sAuditStage != "" && @strpos($_SESSION["Email"], "marksnspencer.com") === FALSE) || (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sAuditStage == "F")) ? ' onclick="document.location=\''.SITE_URL.'quonda/quonda-graphs.php?AuditCode='.$sAuditCode.'&Vendor='.$iVendorId.'&FromDate='.$FromDate.'&ToDate='.$FromDate.'&Category='.$iCategoryId.'&Sector='.$sStageIdsList[$sAuditStage].'&Step=1\';"' : '') ?> <?= (($bOnGoing == true) ? ' class="blink"' : '') ?>>
<?
					if ($sApproved == "N")
					{
?>
				                  	<center><b id='Status<?= $iAuditCode ?>'>?</b></center>
<?
					}

					else if (count($sPictures) > 0)
					{
?>
				                  	<center><img src="images/icons/pictures.gif" width="16" height="16" vspace="5" alt="" title="" /></center>
<?
					}
?>
									</div>

									<script type="text/javascript">
									<!--
										new Tip('Audit_<?= $sAuditCode ?>',
												"<?= $sTooltip ?>",
												{ title:'<?= $sAuditCode ?> - <?= $sVendorsList[$iVendorId] ?>', stem:'topLeft', showOn:'mouseover', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:400, hideOn:{ element:'closeButton', event:'click' }, hideAfter:0.5  });
									-->
									</script>
								  </td>
<?
					$iPrevious = $iEndTime;
				}
			}
?>
								  <td></td>
								</tr>
							  </table>

							</td>
						  </tr>
<?
		}
	}


	if ($iAuditors == 0)
	{
		for ($i = 0; $i < 10; $i ++)
		{
?>
						  <tr>
							<td width="145" align="right">&nbsp;</td>
							<td style="border-right:solid 2px #666666;" height="35"></td>

							<td width="768">

							  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="25">
								<tr bgcolor="#fdfdfd">
								  <td width="100%" height="25"></td>
								</tr>
							  </table>

							</td>
						  </tr>
<?
		}
	}
?>
						  <tr>
							<td align="right"></td>
							<td style="border-right:solid 2px #666666;" height="5"></td>
							<td height="5"></td>
						  </tr>

						  <tr>
							<td></td>
							<td></td>

							<td style="border-top:solid 2px #666666;">

							  <table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
<?
	for ($i = 0; $i < 24; $i ++)
	{
?>
					          	<td width="32"><div style="width:2px; height:6px; background:#666666;"></div></td>
<?
	}
?>
								</tr>
							  </table>

							  <table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
<?
	for ($i = 0; $i < 24; $i ++)
	{
?>
					          	<td width="32"><small><?= substr(date("ga", mktime($i, 0, 0, date("m"), date("d"), date("Y"))), 0, -1) ?></small></td>
<?
	}
?>
								</tr>
							  </table>

							</td>
							</tr>
							</table>
						  </div>
						</div>

						<br />

				    </div>

				    <br />
