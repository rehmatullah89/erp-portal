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
//	@require_once($sBaseDir."requires/chart.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Region = IO::intValue("Region");
	$Vendor = IO::intValue("Vendor");
	$Brand  = IO::intValue("Brand");
	$Date   = IO::strValue("Date");

	if ($Date == "")
		$Date = date("Y-m-d");

	$sVendors = $_SESSION['Vendors'];
	$sBrands  = $_SESSION['Brands'];


	$sRegionsList     = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "id IN ($sVendors) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "id IN ($sBrands)");
	$sLocationsList   = getList("tbl_visit_locations", "id", "location");
	$sAuditorsList    = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");

	$sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sReportsList     = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes') AND NOT FIND_IN_SET(id, '$sQmipReports')");

	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
	$sStageColorsList = getList("tbl_audit_stages", "code", "color");
	$sStageIdsList    = getList("tbl_audit_stages", "code", "id");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <style type="text/css">
  <!--
    v\:* { behavior:url(#default#VML); }
  -->
  </style>

  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=<?= GOOGLE_MAPS_KEY ?>"></script>
  <script type="text/javascript" src="scripts/quonda/qa-reviews.js"></script>
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
			    <h1>qa reviews</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
					  <td width="50">Region</td>

					  <td width="120">
					    <select name="Region">
						  <option value="">All Regions</option>
<?
	foreach ($sRegionsList as $sKey => $sValue)
	{
?>
	  	        		  <option value="<?= $sKey ?>"<?= (($sKey == $Region) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
					  </td>

			          <td width="54">Vendor</td>

			          <td width="200">
			            <select name="Vendor" style="max-width:180px;">
			              <option value="">All Vendors</option>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

			          <td width="45">Brand</td>

			          <td width="190">
			            <select name="Brand" style="max-width:180px;">
			              <option value="">All Brands</option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

					  <td width="40">Date</td>
					  <td width="78"><input type="text" name="Date" value="<?= $Date ?>" id="Date" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
					  <td width="40"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>
			    </form>


			    <div class="tblSheet" style="position:relative;">
				  <h2>Inline Audit Reports (<?= formatDate($Date) ?>)</h2>

<?
	if ($_SESSION['Admin'] != "Y")
	{
		$sSQL = "SELECT brands FROM tbl_departments WHERE FIND_IN_SET('{$_SESSION["UserId"]}', quality_managers)";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
			$sTemp  = "0";

			for ($i = 0; $i < $iCount; $i ++)
				$sTemp .= (",".$objDb->getField($i, 0));

			$iTemp   = @explode(",", $sTemp);
			$iTemp   = array_unique($iTemp);
			$sBrands = @implode(",", $iTemp);
		}

		else
			$sBrands = "0";
	}


	if ($sBrands == "0")
	{
?>
				  <div class="noRecord"><center>Only Quality Managers can access this section</center></div>
<?
	}

	else
	{
?>
				  <div style="position:absolute; left:166px; top:100px; z-index:1;">
				    <table border="0" cellpadding="0" cellspacing="0" style="border:dotted 1px #999999;">
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


		$sConditions = " WHERE qa.audit_date='$Date' AND qa.user_id=u.id AND qa.approved='Y' AND qa.audit_stage!='F' AND
		                       qa.audit_result!='' AND NOT FIND_IN_SET(qa.audit_result, 'P,A,B') AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports')
		                       AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";
		$sRegionSql  = "";

		if ($Region > 0)
		{
			$sRegionSql   = " AND u.country_id='$Region' ";
			$sConditions .= " AND u.country_id='$Region' ";
		}

		if ($Vendor > 0)
			$sConditions .= " AND qa.vendor_id='$Vendor' ";

		else
			$sConditions .= " AND qa.vendor_id IN ($sVendors) ";


		if ($Brand > 0)
		{
			$sSQL = "SELECT id FROM tbl_po WHERE brand_id='$Brand'";

			if ($Vendor > 0)
				$sSQL .= " AND vendor_id='$Vendor' ";

			else
				$sSQL .= " AND vendor_id IN ($sVendors) ";

			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$sPos   = "";

			for ($i = 0; $i < $iCount; $i ++)
				$sPos .= (",".$objDb->getField($i, 0));

			if ($sPos != "")
				$sPos = substr($sPos, 1);

			$sConditions .= " AND qa.po_id IN ($sPos) ";
		}

		else
		{
			if ($Vendor > 0)
			{
				$sSQL = "SELECT id FROM tbl_po WHERE vendor_id='$Vendor' AND brand_id IN ($sBrands)";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );
				$sPos   = "";

				for ($i = 0; $i < $iCount; $i ++)
					$sPos .= (",".$objDb->getField($i, 0));

				if ($sPos != "")
					$sPos = substr($sPos, 1);

				$sConditions .= " AND qa.po_id IN ($sPos) ";
			}

			else
				$sConditions .= " AND (qa.po_id='0' OR qa.po_id IN (SELECT id FROM tbl_po WHERE vendor_id IN ($sVendors) AND brand_id IN ($sBrands)))";
		}


		$sSQL = "SELECT DISTINCT(u.id) AS _Id, 'A' AS _Type, u.name AS _Name, '' AS _Users, u.picture AS _Picture, u.latitude AS _Latitude, u.longitude AS _Longitude, u.location_time AS _DateTime
				 FROM tbl_qa_reports qa, tbl_users u
				 $sConditions AND qa.group_id='0'

				 UNION

				 SELECT DISTINCT(g.id) AS _Id, 'G' AS _Type, g.name AS _Name, g.users AS _Users, '' AS _Picture, u.latitude AS _Latitude, u.longitude AS _Longitude, u.location_time AS _DateTime
				 FROM tbl_qa_reports qa, tbl_users u, tbl_auditor_groups g
				 $sConditions AND g.id=qa.group_id AND qa.group_id > '0'
				 GROUP BY g.id

				 ORDER BY _Name";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
?>
					  <tr>
<?
			for ($j = 0; $j < 16; $j ++)
			{
?>
					    <td width="45" height="35" style="border:dotted 1px #999999;"></td>
<?
			}
?>
					  </tr>
<?
		}
?>
				    </table>
		          </div>

				  <div style="position:relative; z-index:1000; top:0px;">
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

				    <hr />
				    <br />

				    <table border="0" cellpadding="0" cellspacing="0" width="99%">
<?
		$sConditions = " WHERE audit_date='$Date' AND approved='Y' AND audit_stage!='F' AND audit_result!='' AND NOT FIND_IN_SET(audit_result, 'P,A,B')
		                        AND FIND_IN_SET(report_id, '$sReportTypes') AND NOT FIND_IN_SET(report_id, '$sQmipReports') AND FIND_IN_SET(audit_stage, '$sAuditStages')";

		if ($Vendor > 0)
			$sConditions .= " AND vendor_id='$Vendor' ";

		else
			$sConditions .= " AND vendor_id IN ($sVendors) ";

		if ($Brand > 0)
		{
			$sSQL = "SELECT id FROM tbl_po WHERE brand_id='$Brand'";

			if ($Vendor > 0)
				$sSQL .= " AND vendor_id='$Vendor' ";

			else
				$sSQL .= " AND vendor_id IN ($sVendors) ";

			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$sPos   = "";

			for ($i = 0; $i < $iCount; $i ++)
				$sPos .= (",".$objDb->getField($i, 0));

			if ($sPos != "")
				$sPos = substr($sPos, 1);

			$sConditions .= " AND po_id IN ($sPos) ";
		}

		else
		{
			if ($Vendor > 0)
			{
				$sSQL = "SELECT id FROM tbl_po WHERE vendor_id='$Vendor' AND brand_id IN ($sBrands)";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );
				$sPos   = "";

				for ($i = 0; $i < $iCount; $i ++)
					$sPos .= (",".$objDb->getField($i, 0));

				if ($sPos != "")
					$sPos = substr($sPos, 1);

				$sConditions .= " AND po_id IN ($sPos) ";
			}

			else
				$sConditions .= " AND (po_id='0' OR po_id IN (SELECT id FROM tbl_po WHERE vendor_id IN ($sVendors) AND brand_id IN ($sBrands)))";
		}


		for ($i = 0; $i < $iCount; $i ++)
		{
			$iAuditorId = $objDb->getField($i, '_Id');
			$sAuditor   = $objDb->getField($i, '_Name');
			$sType      = $objDb->getField($i, '_Type');
			$sUsers     = $objDb->getField($i, '_Users');
			$sPicture   = $objDb->getField($i, '_Picture');
			$sLatitude  = $objDb->getField($i, '_Latitude');
			$sLongitude = $objDb->getField($i, '_Longitude');
			$sDateTime  = $objDb->getField($i, '_DateTime');

			if ($sDateTime == "" || (strtotime(date("Y-m-d H:i:s")) - strtotime($sDateTime)) > 43200)
			{
				$sLatitude  = "31.474634";
				$sLongitude = "74.260803";
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


			if ($sLatitude == "" || $sLongitude == "")
			{
				$sLatitude  = "31.474634";
				$sLongitude = "74.260803";
				$sLocation  = " - MATRIX Sourcing";
			}
?>
					  <tr>
					    <td width="17%" align="right">
					      <span id="Auditor<?= $i ?>"><?= $sAuditor ?></span><br />

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
				$sSQL = "SELECT id, name, picture, latitude, longitude, location_time FROM tbl_users WHERE id IN ($sUsers) ORDER BY name";
				$objDb2->query($sSQL);

				$iCount2 = $objDb2->getCount( );

				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iUserId    = $objDb2->getField($j, 'id');
					$sName      = $objDb2->getField($j, 'name');
					$sPicture   = $objDb2->getField($j, 'picture');
					$sLatitude  = $objDb2->getField($j, 'latitude');
					$sLongitude = $objDb2->getField($j, 'longitude');
					$sDateTime  = $objDb2->getField($j, 'location_time');


					$sLocation = "";

					if ($sDateTime == "" || (strtotime(date("Y-m-d H:i:s")) - strtotime($sDateTime)) > 43200)
					{
						$sLatitude  = "31.474634";
						$sLongitude = "74.260803";
						$sLocation  = " - MATRIX Sourcing";
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
					      <span id="Auditor<?= $i ?>_<?= $j ?>" style="font-size:9px; color:#888888;"><?= $sName ?></span><?= (($j < ($iCount2 - 1)) ? ', ' : '') ?>

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
								  objMap<?= $i ?>_<?= $j ?>.setCenter(new GLatLng(<?= $sLatitude ?>, <?= $sLongitude ?>), 12);
								  objMap<?= $i ?>_<?= $j ?>.addOverlay(new GMarker(new GLatLng(<?= $sLatitude ?>, <?= $sLongitude ?>)));
							  });
						  -->
						  </script>
<?
				}
			}
?>
					    </td>

					    <td width="1%" height="35"></td>

					    <td width="82%" style="border-left:solid 2px #666666;">

					      <table border="0" cellpadding="0" cellspacing="0" width="100%" height="25">
					        <tr bgcolor="#fcfcfc">
<?
			if ($sType == "A" || $sType == "G")
			{
				if ($sType == "A")
					$sSubSQL = " AND user_id='$iAuditorId' AND group_id='0' ";

				else if ($sType == "G")
					$sSubSQL = " AND group_id='$iAuditorId' ";


				$sSQL = "SELECT TIME_TO_SEC(start_time) AS _StartTime, TIME_TO_SEC(end_time) AS _EndTime, audit_code, vendor_id, status,
								start_time, end_time, report_id, audit_stage, audit_result, total_gmts,
								(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line,
								(SELECT category_id FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Category,
								(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature>'0') AS _Defects,
								(SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id) AS _GfDefects,
								(SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature>'0' AND code_id NOT IN ($sMeasurementCodes)) AS _DefectPics,
								(SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id AND code_id NOT IN ($sMeasurementCodes)) AS _GfDefectPics
						 FROM tbl_qa_reports
						 $sConditions $sSubSQL
						 ORDER BY start_time";
				$objDb2->query($sSQL);

				$iCount2   = $objDb2->getCount( );
				$iPrevious = 28800;

				for ($j = 0; $j < $iCount2; $j ++)
				{
					$iStartTime   = $objDb2->getField($j, '_StartTime');
					$iEndTime     = $objDb2->getField($j, '_EndTime');
					$sAuditCode   = $objDb2->getField($j, 'audit_code');
					$iVendorId    = $objDb2->getField($j, 'vendor_id');
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

					if ($iStartTime > $iPrevious)
					{
						$iTime  = ($iStartTime - $iPrevious);
						$iTime /= 60;
						$iWidth = @round($iTime * 0.766);
?>
					          <td width="<?= $iWidth ?>"></td>
<?
					}

					$iAuditCode = substr($sAuditCode, 1);


					$iTime       = ($iEndTime - $iStartTime);
					$iTime      /= 60;
					$iWidth      = @round($iTime * 0.766);
					$sBackground = "#dddddd";
					$sPictures   = array( );

					@list($sYear, $sMonth, $sDay) = @explode("-", $Date);

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
					$sTooltip .= "      <b>Vendor:</b> {$sVendorsList[$iVendorId]}<hr style='margin:2px 0px 2px 0px; _margin:2px 0px 2px 0px; #margin:2px 0px 2px 0px;' />";
					$sTooltip .= ("     <b>Start Time:</b> ".formatTime($sStartTime)."<hr style='margin:2px 0px 2px 0px; _margin:2px 0px 2px 0px; #margin:2px 0px 2px 0px;' />");
					$sTooltip .= ("     <b>End Time:</b> ".formatTime($sEndTime)."<hr style='margin:2px 0px 2px 0px; _margin:2px 0px 2px 0px; #margin:2px 0px 2px 0px;' />");
					$sTooltip .= "      <b>Report Type:</b> {$sReportsList[$iReportId]}<hr style='margin:2px 0px 2px 0px; _margin:2px 0px 2px 0px; #margin:2px 0px 2px 0px;' />";
					$sTooltip .= "      <b>Line:</b> {$sLine}";

					if ($iQuantity > 0)
						$sTooltip .= "      <hr style='margin:2px 0px 2px 0px; _margin:2px 0px 2px 0px; #margin:2px 0px 2px 0px;' /><b>Sample Size:</b> {$iQuantity}";

					if ($sAuditStage != "")
					{
						$sBackground = $sStageColorsList[$sAuditStage];

						$sTooltip .= "      <hr style='margin:2px 0px 2px 0px; _margin:2px 0px 2px 0px; #margin:2px 0px 2px 0px;' /><b>Audit Result:</b> {$sAuditResult}";
					}

					if ($iDefects > 0)
						$sTooltip .= "      <hr style='margin:2px 0px 2px 0px; _margin:2px 0px 2px 0px; #margin:2px 0px 2px 0px;' /><b>Total Defects:</b> {$iDefects}";

					if (count($sPictures) > 0)
						$sTooltip .= ("      <hr style='margin:2px 0px 2px 0px; _margin:2px 0px 2px 0px; #margin:2px 0px 2px 0px;' /><b>Defect Pictures:</b> ".count($sPictures));

					if ($iDefectPics > 0)
						$sTooltip .= "      <hr style='margin:2px 0px 2px 0px; _margin:2px 0px 2px 0px; #margin:2px 0px 2px 0px;' /><b>Pictures Required:</b> {$iDefectPics}";

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

					switch ($sStatus)
					{
						case "LP" : $sStatus = "Likely to Pass"; break;
						case "PF" : $sStatus = "Possible Failure"; break;
						case "LF" : $sStatus = "Likely to Fail"; break;
						default   : $sStatus = "Decision Pending";
					}

					if ($sUserRights['Add'] == "Y" && $sUserRights['Edit'] == "Y")
						$sTooltip .= "<hr style='margin:2px 0px 2px 0px; _margin:2px 0px 2px 0px; #margin:2px 0px 2px 0px;' /><b>Predication at Final Audit</b> (<span id='StatusText{$iAuditCode}'>{$sStatus}</span>)<br /><br /><a href='#' onclick='return updateStatus({$iAuditCode}, 0);'><img src='images/icons/likely-pass.png' width='24' height='32' alt='' title='' align='absmiddle' hspace='3' /> Likely to Pass</a> &nbsp; <a href='#' onclick='return updateStatus({$iAuditCode}, 1);'><img src='images/icons/possible-failure.png' width='24' height='32' alt='' title='' align='absmiddle' hspace='3' /> Possible Failure</a> &nbsp; <a href='#' onclick='return updateStatus({$iAuditCode}, 2);'><img src='images/icons/likely-fail.png' width='24' height='32' alt='' title='' align='absmiddle' hspace='3' /> Likely to Fail</a>";
?>
					          <td width="<?= $iWidth ?>">
					            <div id="Audit_<?= $sAuditCode ?>" style="width:<?= $iWidth ?>px; background:<?= $sBackground ?>; border-left:dotted 1px #ffffff; height:25px; line-height:25px;<?= ((($sAuditStage != "" && @strpos($_SESSION["Email"], "marksnspencer.com") === FALSE) || (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sAuditStage == "F")) ? ' cursor:pointer;' : '') ?>"<?= ((($sAuditStage != "" && @strpos($_SESSION["Email"], "marksnspencer.com") === FALSE) || (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sAuditStage == "F")) ? ' onclick="document.location=\''.SITE_URL.'quonda/quonda-graphs.php?AuditCode='.$sAuditCode.'&Vendor='.$iVendorId.'&Date='.$Date.'&ToDate='.$Date.'&Category='.$iCategoryId.'&Sector='.$sStageIdsList[$sAuditStage].'&Step=1\';"' : '') ?>>
<?
					if ($sStatus == "Decision Pending")
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
								            { title:'<?= $sAuditCode ?>', stem:'topLeft', showOn:'mouseover', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:400, hideOn:{ element:'closeButton', event:'click' }, hideAfter:0.5  });
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

		if ($iCount == 0)
		{
			for ($i = 0; $i < 5; $i ++)
			{
?>
					  <tr>
					    <td width="17%" align="right">&nbsp;</td>
					    <td width="1%" height="35"></td>

					    <td width="82%" style="border-left:solid 2px #666666;">

					      <table border="0" cellpadding="0" cellspacing="0" width="100%" height="25">
					        <tr bgcolor="#fcfcfc">
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
					    <td></td>

					    <td colspan="2" style="border-top:solid 2px #666666;">

					      <table border="0" cellpadding="0" cellspacing="0" width="98%">
					        <tr>
					          <td width="9"></td>
<?
		for ($i = 0; $i < 17; $i ++)
		{
?>
					          <td width="45" height="5" style="border-left:solid 1px #666666;"></td>
<?
		}
?>
					          <td></td>
					        </tr>
					      </table>

					      <table border="0" cellpadding="0" cellspacing="0" width="100%">
					        <tr>
					          <td width="45"><small>8am</small></td>
					          <td width="45"><small>9am</small></td>
					          <td width="45"><small>10am</small></td>
					          <td width="45"><small>11am</small></td>
					          <td width="45"><small>12pm</small></td>
					          <td width="45"><small>1pm</small></td>
					          <td width="45"><small>2pm</small></td>
					          <td width="45"><small>3pm</small></td>
					          <td width="45"><small>4pm</small></td>
					          <td width="45"><small>5pm</small></td>
					          <td width="45"><small>6pm</small></td>
					          <td width="45"><small>7pm</small></td>
					          <td width="45"><small>8pm</small></td>
					          <td width="45"><small>9pm</small></td>
					          <td width="45"><small>10pm</small></td>
					          <td width="45"><small>11pm</small></td>
					          <td width="45"><small>12am</small></td>
					          <td></td>
					        </tr>
					      </table>

					    </td>
					  </tr>
				    </table>

				    <br />
				  </div>
<?
	}
?>
			    </div>
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