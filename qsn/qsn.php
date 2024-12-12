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
	@require_once($sBaseDir."requires/chart.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Mode     = IO::strValue("Mode");
	$Region   = IO::intValue("Region");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Year     = IO::intValue("Year");
	$Charts   = IO::intValue("Charts");
	$Vendors  = IO::getArray("Vendors");
	$Brands   = IO::getArray("Brands");


	$Mode     = (($Mode == "") ? "Vendors" : $Mode);
	$Charts   = (($Charts < 2) ? 2 : $Charts);


	if (count($Vendors) == 0)
		$sVendors = $_SESSION['Vendors'];

	else
		$sVendors = @implode(",", $Vendors);


	if (count($Brands) == 0)
		$sBrands = $_SESSION['Brands'];

	else
		$sBrands = @implode(",", $Brands);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/qsn/qsn.js"></script>
  <script type="text/javascript" src="scripts/glider.js"></script>
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
			    <h1><img src="images/h1/qsn/qsn.jpg" width="359" height="24" alt="" title="" style="margin:9px 0px 8px 0px;" /></h1>

			    <form name="frmSearch" id="frmSearch" method="post" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="doSearch( );">
			    <input type="hidden" name="Charts" id="Charts" value="<?= $Charts ?>" />

			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="40">Mode</td>

			          <td width="110">
			            <select id="Mode" name="Mode" onchange="refineSearch(this.value);">
			              <option value="Vendors"<?= (($Mode == "Vendors") ? " selected" : "") ?>>Vendors</option>
			              <option value="Brands"<?= (($Mode == "Brands") ? " selected" : "") ?>>Brands</option>
			              <option value="VendorsBrands"<?= (($Mode == "VendorsBrands") ? " selected" : "") ?>>Vendors & Brands</option>
			            </select>
			          </td>

					  <td width="50">Region</td>

					  <td width="105">
					    <select name="Region">
						  <option value="">All Regions</option>
<?
	$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' ORDER BY country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		  <option value="<?= $sKey ?>"<?= (($sKey == $Region) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
					  </td>

					  <td width="40">Year</td>

					  <td width="100">
					    <select name="Year" onchange="setYear(this.value);">
						  <option value="">All Years</option>
<?
	for ($i = 2008; $i <= (date("Y") + 1); $i ++)
	{
?>
	  	        		  <option value="<?= $i ?>"<?= (($i == $Year) ? " selected" : "") ?>><?= $i ?></option>
<?
	}
?>
					    </select>
					  </td>

					  <td width="40">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="70" align="center">[ <a href="./" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;" style="color:#eeeeee;">Clear</a> ]</td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" onclick="clearSubSearch( );" /></td>
			        </tr>
			      </table>
			    </div>

			    <div class="tblSheet">
			      <div style="margin:0px 1px 1px 0px;">
			        <table border="0" cellpadding="0" cellspacing="0" width="100%">
			          <tr>
			            <td width="255"><h1 class="darkGray small" style="margin:0px;"><img src="images/h1/qsn/core-statistics.jpg" width="161" height="15" vspace="8" alt="" title="" /></h1></td>
			            <td bgcolor="#888888"><b style="color:#ffffff; padding-left:10px;">REFINE YOUR SEARCH</b> &nbsp; <b>( <a href="./" onclick="checkAll( ); return false;" class="sheetLink">Check ALL</a> | <a href="./" onclick="clearAll( ); return false;" class="sheetLink">Clear ALL</a> )</b></td>
			          </tr>

			          <tr valign="top">
			            <td>

			              <div style="padding:5px;">
			                <table border="0" cellpadding="5" cellspacing="0" width="100%">
<?
	$sRegionSql = "";
	$sRegionPos = "";
	$sAllAudits = "";


	if ($Region != "")
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y' AND FIND_IN_SET(id, '$sVendors')";
		$objDb->query($sSQL);

		$iCount         = $objDb->getCount( );
		$sRegionVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sRegionVendors .= (",".$objDb->getField($i, 0));

		if ($sRegionVendors != "")
			$sRegionVendors = substr($sRegionVendors, 1);

		$sRegionSql = " AND vendor_id IN ($sRegionVendors) ";


		$sSQL = "SELECT id FROM tbl_po WHERE vendor_id IN ($sRegionVendors)";
		$objDb->query($sSQL);

		$iCount     = $objDb->getCount( );
		$sRegionPos = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sRegionPos .= (",".$objDb->getField($i, 0));

		if ($sRegionPos != "")
			$sRegionPos = substr($sRegionPos, 1);
	}



	$sSQL = "SELECT id FROM tbl_po WHERE brand_id IN ($sBrands) AND vendor_id IN ($sVendors) $sRegionSql";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sPos    = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));

	if ($sPos != "")
		$sPos = substr($sPos, 1);



	$sSQL = "SELECT id FROM tbl_qa_reports WHERE audit_type='B' AND audit_result!='' AND audit_stage!='' AND vendor_id IN ($sVendors) AND po_id IN ($sPos)";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sAllAudits .= (",".$objDb->getField($i, 0));

	if ($sAllAudits != "")
		$sAllAudits = substr($sAllAudits, 1);




	$sToday    = date("Y-m-d");
	$sLastWeek = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") - 7), date("Y")));

	$sSQL = "SELECT COALESCE(SUM(total_gmts), 0) AS _TotalGmts,
					SUM(
						 IF (report_id=10,
							 (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature='1'),
							 (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id)
							)
					   ) AS _TotalDefects
	         FROM tbl_qa_reports
	         WHERE id IN ($sAllAudits) AND (audit_date BETWEEN '$sLastWeek' AND '$sToday') AND report_id!='6'";
	$objDb->query($sSQL);

	$iTotalGmts    = $objDb->getField(0, 0);
	$iTotalDefects = $objDb->getField(0, 1);


	$sSQL = "SELECT SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=tbl_qa_reports.id) ) AS _TotalGmts,
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id) ) AS _TotalDefects
	         FROM tbl_qa_reports
	         WHERE id IN ($sAllAudits) AND (audit_date BETWEEN '$sLastWeek' AND '$sToday') AND report_id='6'";
	$objDb->query($sSQL);

	$iTotalGmts    += $objDb->getField(0, 0);
	$iTotalDefects += $objDb->getField(0, 1);


	$fDefectRate = @round((($iTotalDefects / $iTotalGmts) * 100), 2);
?>
			                  <tr valign="top">
			                    <td width="70%"><b>DR for Last Week</b></td>
			                    <td width="30%"><b style="color:#ff0000;"><?= formatNumber($fDefectRate) ?>%</b></td>
			                  </tr>

<?
	$sFirstDate = date("Y-m-d", mktime(0, 0, 0, (date("m") - 1), "01", date("Y")));

	$iLastMonth = ((date("m") == 1) ? 12 : (date("m") - 1));
	$iLastYear  = (($iLastMonth == 12) ? (date("Y") - 1) : date("Y"));
	$sLastMonth = str_pad($iLastMonth, 2, '0', STR_PAD_LEFT);
	$iDays      = @cal_days_in_month(CAL_GREGORIAN, $iLastMonth, $iLastYear);
	$sLastDate  = ($iLastYear."-".$sLastMonth."-".$iDays);


	$sSQL = "SELECT COALESCE(SUM(total_gmts), 0) AS _TotalGmts,
					SUM(
						 IF (report_id=10,
							 (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature='1'),
							 (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id)
							)
					   ) AS _TotalDefects
	         FROM tbl_qa_reports
	         WHERE id IN ($sAllAudits) AND (audit_date BETWEEN '$sFirstDate' AND '$sLastDate') AND report_id!='6'";
	$objDb->query($sSQL);

	$iTotalGmts    = $objDb->getField(0, 0);
	$iTotalDefects = $objDb->getField(0, 1);


	$sSQL = "SELECT SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=tbl_qa_reports.id) ) AS _TotalGmts,
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id) ) AS _TotalDefects
	         FROM tbl_qa_reports
	         WHERE id IN ($sAllAudits) AND (audit_date BETWEEN '$sFirstDate' AND '$sLastDate') AND report_id='6'";
	$objDb->query($sSQL);

	$iTotalGmts    += $objDb->getField(0, 0);
	$iTotalDefects += $objDb->getField(0, 1);


	$fDefectRate = @round((($iTotalDefects / $iTotalGmts) * 100), 2);
?>
			                  <tr valign="top">
			                    <td><b>DR for Last Month</b></td>
			                    <td><b style="color:#ff0000;"><?= formatNumber($fDefectRate) ?>%</b></td>
			                  </tr>

<?
	$sFirstDate = date("Y-m-d", mktime(0, 0, 0, (date("m") - 6), "01", date("Y")));

	$iLastMonth = ((date("m") == 1) ? 12 : (date("m") - 1));
	$iLastYear  = (($iLastMonth == 12) ? (date("Y") - 1) : date("Y"));
	$sLastMonth = str_pad($iLastMonth, 2, '0', STR_PAD_LEFT);
	$iDays      = @cal_days_in_month(CAL_GREGORIAN, $iLastMonth, $iLastYear);
	$sLastDate  = ($iLastYear."-".$sLastMonth."-".$iDays);

	$sSQL = "SELECT COALESCE(SUM(total_gmts), 0) AS _TotalGmts,
					SUM(
						 IF (report_id=10,
							 (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature='1'),
							 (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id)
							)
					   ) AS _TotalDefects
	         FROM tbl_qa_reports
	         WHERE id IN ($sAllAudits) AND (audit_date BETWEEN '$sFirstDate' AND '$sLastDate') AND report_id!='6'";
	$objDb->query($sSQL);

	$iTotalGmts    = $objDb->getField(0, 0);
	$iTotalDefects = $objDb->getField(0, 1);


	$sSQL = "SELECT SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=tbl_qa_reports.id) ) AS _TotalGmts,
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id) ) AS _TotalDefects
	         FROM tbl_qa_reports
	         WHERE id IN ($sAllAudits) AND (audit_date BETWEEN '$sFirstDate' AND '$sLastDate') AND report_id='6'";
	$objDb->query($sSQL);

	$iTotalGmts    += $objDb->getField(0, 0);
	$iTotalDefects += $objDb->getField(0, 1);


	$fDefectRate = @round((($iTotalDefects / $iTotalGmts) * 100), 2);
?>
			                  <tr valign="top">
			                    <td><b>DR for Last 6 Months</b></td>
			                    <td><b style="color:#ff0000;"><?= formatNumber($fDefectRate) ?>%</b></td>
			                  </tr>
			                </table>
			              </div>

			            </td>

			            <td bgcolor="#f6f6f6">
			              <div style="padding:10px;">

			                <table border="0" cellpadding="0" cellspacing="0" width="100%">
			                  <tr height="5">
			                    <td width="5" bgcolor="#494949"></td>
			                    <td width="8" bgcolor="#494949"></td>
			                    <td></td>
			                    <td width="8" bgcolor="#494949"></td>
			                    <td width="5" bgcolor="#494949"></td>
			                  </tr>

			                  <tr>
			                    <td width="5" bgcolor="#494949"></td>
			                    <td width="8"></td>

			                    <td>

			                      <div id="VendorsBlock" style="display:<?= (($Mode == "Vendors" || $Mode == "VendorsBrands") ? "block" : "none") ?>;">
			                        <div>
			                          <h4 style="padding-top:5px;">Vendors</h4>

			                          <table border="0" cellpadding="1" cellspacing="0" width="100%">
<?
	$sVendorsList = array( );
	$sBrandsList  = array( );

	$sSQL = "SELECT id, vendor FROM tbl_vendors WHERE id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y' ORDER BY vendor";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount;)
	{
?>
			                            <tr>
<?
		for ($j = 0; $j < 4; $j ++)
		{
			if ($i < $iCount)
			{
				$sKey   = $objDb->getField($i, 0);
				$sValue = $objDb->getField($i, 1);

				$sVendorsList[$sKey] = $sValue;
?>
			                              <td width="22"><input type="checkbox" class="vendors" name="Vendors[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, IO::getArray('Vendors'))) ? "checked" : "") ?> /></td>
			                              <td><?= $sValue ?></td>
<?
				 $i ++;
			}

			else
			{
?>
			                              <td width="22"></td>
			                              <td></td>
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
			                      </div>

			                      <div id="BrandsBlock" style="display:<?= (($Mode == "Brands" || $Mode == "VendorsBrands") ? "block" : "none") ?>;">
			                        <div>
			                          <h4 style="padding-top:5px;">Brands</h4>

			                          <table border="0" cellpadding="1" cellspacing="0" width="100%">
<?
	$sSQL = "SELECT id, brand FROM tbl_brands WHERE parent_id!=0 AND id IN ({$_SESSION['Brands']}) ORDER BY brand";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount;)
	{
?>
			                            <tr>
<?
		for ($j = 0; $j < 4; $j ++)
		{
			if ($i < $iCount)
			{
				$sKey   = $objDb->getField($i, 0);
				$sValue = $objDb->getField($i, 1);

				$sBrandsList[$sKey] = $sValue;
?>
			                              <td width="22"><input type="checkbox" class="brands" name="Brands[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, IO::getArray('Brands'))) ? "checked" : "") ?> /></td>
			                              <td><?= $sValue ?></td>
<?
				 $i ++;
			}

			else
			{
?>
			                              <td width="22"></td>
			                              <td></td>
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
			                      </div>

			                    </td>

			                    <td width="8"></td>
			                    <td width="5" bgcolor="#494949"></td>
			                  </tr>

			                  <tr height="5">
			                    <td width="5" bgcolor="#494949"></td>
			                    <td width="8" bgcolor="#494949"></td>
			                    <td></td>
			                    <td width="8" bgcolor="#494949"></td>
			                    <td width="5" bgcolor="#494949"></td>
			                  </tr>
			                </table>

			              </div>
			            </td>
			          </tr>
			       </table>
			     </div>
			    </div>

<?
	if ($_POST)
	{
		$iYear     = (int)@substr($ToDate, 0, 4);
		$sFromDate = $FromDate;
		$sToDate   = $ToDate;

		if ($iYear == 0)
		{
			$iYear = date("Y");

			$sFromDate = (date("Y")."-01-01");
			$sToDate   = (date("Y")."-12-31");
		}
?>
			    <br style="line-height:4px;" />

			    <div class="tblSheet">
                  <h1 class="darkGray small" style="margin:0px 1px 1px 0px;"><span>( Year : <?= $iYear ?> )</span><img src="images/h1/qsn/summary.jpg" width="217" height="15" vspace="8" alt="" title="" /></h1>

			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="150">

					    <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
 						  <tr class="headerRow">
						    <td width="100%"><b><?= strtoupper($Mode) ?></b></td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td><b>FINAL AUDITS</b></td>
						  </tr>

						  <tr bgcolor="#e6e6e6">
						    <td><b>INLINE AUDITS</b></td>
						  </tr>

						  <tr class="footerRow">
						    <td><b>DEFECT RATE</b></td>
						  </tr>
					    </table>

					  </td>

<?
		if ($Mode == "Vendors" || $Mode == "VendorsBrands")
			$iSelectionCount = count($_POST["Vendors"]);

		else
			$iSelectionCount = count($_POST["Brands"]);


		if ($iSelectionCount > 0)
		{
?>
					  <td <?= (($iSelectionCount < 6) ? ('width="'.($iSelectionCount * 123).'"') : '') ?>>
						<div id="Glider">
						  <div class="scroller">
							<div class="content">
<?
			for ($iIndex = 0; $iIndex < $iSelectionCount; $iIndex ++)
			{
				if ($Mode == "Vendors" || $Mode == "VendorsBrands")
				{
					$Id     = $_POST["Vendors"][$iIndex];
					$sTitle = $sVendorsList[$Id];


					$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports WHERE audit_stage='F' AND id IN ($sAllAudits) AND vendor_id='$Id' AND (audit_date BETWEEN '$sFromDate' AND '$sToDate')";
					$objDb->query($sSQL);

					$iTotalFinalAudits = $objDb->getField(0, 0);



					$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports WHERE audit_stage='F' AND (audit_result='F' OR audit_result='C') AND id IN ($sAllAudits) AND vendor_id='$Id' AND (audit_date BETWEEN '$sFromDate' AND '$sToDate')";
					$objDb->query($sSQL);

					$iFailFinalAudits = $objDb->getField(0, 0);



					$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports WHERE audit_stage!='F'  AND id IN ($sAllAudits) AND vendor_id='$Id' AND (audit_date BETWEEN '$sFromDate' AND '$sToDate')";
					$objDb->query($sSQL);

					$iTotalInlineAudits = $objDb->getField(0, 0);



					$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports WHERE audit_stage!='F' AND (audit_result='F' OR audit_result='C') AND id IN ($sAllAudits) AND vendor_id='$Id' AND (audit_date BETWEEN '$sFromDate' AND '$sToDate')";
					$objDb->query($sSQL);

					$iFailInlineAudits = $objDb->getField(0, 0);



					$sSQL = "SELECT COALESCE(SUM(total_gmts), 0) AS _TotalGmts,
									SUM(
										 IF (report_id=10,
											 (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature='1'),
											 (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id)
											)
									   ) AS _TotalDefects
							 FROM tbl_qa_reports
							 WHERE id IN ($sAllAudits) AND vendor_id='$Id' AND report_id!='6' AND (audit_date BETWEEN '$sFromDate' AND '$sToDate')";
					$objDb->query($sSQL);

					$iTotalGmts    = $objDb->getField(0, 0);
					$iTotalDefects = $objDb->getField(0, 1);


					$sSQL = "SELECT SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=tbl_qa_reports.id) ) AS _TotalGmts,
									SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id) ) AS _TotalDefects
							 FROM tbl_qa_reports
							 WHERE id IN ($sAllAudits) AND vendor_id='$Id' AND report_id='6' AND (audit_date BETWEEN '$sFromDate' AND '$sToDate')";
					$objDb->query($sSQL);

					$iTotalGmts    += $objDb->getField(0, 0);
					$iTotalDefects += $objDb->getField(0, 1);


					$fDefectRate = @round((($iTotalDefects / $iTotalGmts) * 100), 2);
				}


				else if ($Mode == "Brands")
				{
					$Id     = $_POST["Brands"][$iIndex];
					$sTitle = $sBrandsList[$Id];



					$sSQL = "SELECT id FROM tbl_po WHERE brand_id='$Id' AND vendor_id IN ($sVendors)";

					if ($Region != "")
						$sSQL .= $sRegionSql;

					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );
					$sPos    = "";

					for ($i = 0; $i < $iCount; $i ++)
						$sPos .= (",".$objDb->getField($i, 0));

					if ($sPos != "")
						$sPos = substr($sPos, 1);



					$sSQL = "SELECT id FROM tbl_qa_reports WHERE audit_type='B' AND audit_result!='' AND audit_stage!='' AND vendor_id IN ($sVendors) $sRegionSql AND po_id IN ($sPos) AND (audit_date BETWEEN '$sFromDate' AND '$sToDate')";
					$objDb->query($sSQL);

					$iCount  = $objDb->getCount( );
					$sAudits = "";

					for ($i = 0; $i < $iCount; $i ++)
						$sAudits .= (",".$objDb->getField($i, 0));

					if ($sAudits != "")
						$sAudits = substr($sAudits, 1);



					$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports WHERE audit_stage='F' AND id IN ($sAudits)";
					$objDb->query($sSQL);

					$iTotalFinalAudits = $objDb->getField(0, 0);


					$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports WHERE audit_stage='F' AND (audit_result='F' OR audit_result='C') AND id IN ($sAudits)";
					$objDb->query($sSQL);

					$iFailFinalAudits = $objDb->getField(0, 0);


					$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports WHERE audit_stage!='F' AND id IN ($sAudits)";
					$objDb->query($sSQL);

					$iTotalInlineAudits = $objDb->getField(0, 0);


					$sSQL = "SELECT COUNT(*) FROM tbl_qa_reports WHERE audit_stage!='F' AND (audit_result='F' OR audit_result='C') AND id IN ($sAudits)";
					$objDb->query($sSQL);

					$iFailInlineAudits = $objDb->getField(0, 0);




					$sSQL = "SELECT COALESCE(SUM(total_gmts), 0) AS _TotalGmts,
									SUM(
										 IF (report_id=10,
											 (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id AND nature='1'),
											 (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=tbl_qa_reports.id)
											)
									   ) AS _TotalDefects
							 FROM tbl_qa_reports
							 WHERE id IN ($sAudits) AND report_id!='6'";
					$objDb->query($sSQL);

					$iTotalGmts    = $objDb->getField(0, 0);
					$iTotalDefects = $objDb->getField(0, 1);


					$sSQL = "SELECT SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=tbl_qa_reports.id) ) AS _TotalGmts,
									SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=tbl_qa_reports.id) ) AS _TotalDefects
							 FROM tbl_qa_reports
							 WHERE id IN ($sAudits) AND report_id='6'";
					$objDb->query($sSQL);

					$iTotalGmts    += $objDb->getField(0, 0);
					$iTotalDefects += $objDb->getField(0, 1);


					$fDefectRate = @round((($iTotalDefects / $iTotalGmts) * 100), 2);
				}
?>

							  <div class="section" id="section<?= $iIndex ?>">
							    <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
								  <tr class="headerRow">
								    <td width="100%"><b title="<?= $sTitle ?>"><?= substr($sTitle, 0, 12) ?></b></td>
								  </tr>

								  <tr bgcolor="#f6f6f6">
								    <td><?= formatNumber($iTotalFinalAudits, false) ?> <span style="color:#ff0000;">(<?= formatNumber($iFailFinalAudits, false) ?>)</span></td>
								  </tr>

								  <tr bgcolor="#e6e6e6">
								    <td><?= formatNumber($iTotalInlineAudits, false) ?> <span style="color:#ff0000;">(<?= formatNumber($iFailInlineAudits, false) ?>)</span></td>
								  </tr>

								  <tr class="footerRow">
								    <td><b><?= formatNumber($fDefectRate) ?>%</b></td>
								  </tr>
							    </table>
							  </div>
<?
			}
?>
							</div>
						  </div>
						</div>

						<script type="text/javascript">
						<!--
							var objGlider = new Glider('Glider', { duration:1.0, maxDisplay:6 });
						-->
						</script>
					  </td>
<?
		}

		if ($iSelectionCount <= 6)
		{
?>
					  <td>

					    <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
 						  <tr class="headerRow">
						    <td width="100%"><b>&nbsp;</b></td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td>&nbsp;</td>
						  </tr>

						  <tr bgcolor="#e6e6e6">
						    <td>&nbsp;</td>
						  </tr>

						  <tr class="footerRow">
						    <td>&nbsp;</td>
						  </tr>
					    </table>

					  </td>
<?
		}

		if ($iSelectionCount > 6)
		{
?>
					  <td width="40">

					    <table border="1" bordercolor="#ffffff" cellpadding="8" cellspacing="0" width="100%">
 						  <tr class="headerRow">
						    <td width="100%" class="center"onclick="objGlider.next( ); return false;" style="cursor:pointer;"><a href="#" onclick="return false;" class="whiteLink">»</a></td>
						  </tr>

						  <tr bgcolor="#f6f6f6">
						    <td>&nbsp;</td>
						    <td></td>
						  </tr>

						  <tr bgcolor="#e6e6e6">
						    <td>&nbsp;</td>
						  </tr>

						  <tr class="footerRow">
						    <td class="center" onclick="objGlider.previous( ); return false;" style="cursor:pointer;"><a href="#" onclick="return false;" class="whiteLink">«</a></td>
						  </tr>
					    </table>

					  </td>
<?
		}
?>
					</tr>
				  </table>
			    </div>

			    <br style="line-height:4px;" />

			    <div class="tblSheet">
		          <h1 class="darkGray small" style="margin:0px 1px 1px 0px;"><span>( Year : <?= $iYear ?> )</span><img src="images/h1/qsn/quality-status-report.jpg" width="231" height="18" alt="" title="" style="margin-top:7px;" /></h1>

			      <table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
<?
		for ($iIndex = 1; $iIndex <= $Charts; $iIndex ++)
		{
?>
			        <tr valign="top" bgcolor="#494949">
			          <td width="50%">
			            <h1 class="green small" style="margin-bottom:1px;"><img src="images/h1/qsn/snapshot.jpg" width="92" height="15" alt="" title="" style="margin-top:6px;" /></h1>

<?
			@include($sBaseDir."includes/qsn/search-bar.php");
			@include($sBaseDir."includes/qsn/search-report.php");
?>

			          </td>

			          <td width="50%">
			            <h1 class="green small" style="margin-bottom:1px;"><img src="images/h1/qsn/comparision.jpg" width="117" height="15" alt="" title="" style="margin-top:6px;" /></h1>

<?
			$iIndex ++;

			@include($sBaseDir."includes/qsn/search-bar.php");
			@include($sBaseDir."includes/qsn/search-report.php");
?>

			          </td>
			        </tr>
<?
		}
?>
			      </table>

<?
		if ($Charts < 10 && $VendorBrand > 0)
		{
?>
			      <div style="background:#494949; margin:2px 1px 1px 0px;"><input type="submit" value="" class="btnDuplicate" title="Duplicate" onclick="duplicate( );" /></div>
<?
		}
?>
				</div>
<?
	}
?>

			    </form>
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