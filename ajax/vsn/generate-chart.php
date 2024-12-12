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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Type     = IO::strValue("Type");
	$Mode     = IO::strValue("Mode");
	$Region   = IO::intValue("Region");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Vendors  = IO::strValue("Vendors");
	$Brands   = IO::strValue("Brands");
	$PoType   = IO::strValue("PoType");

	$iYear       = (int)@substr($ToDate, 0, 4);
	$sRegionSql  = "";
	$sVendorsSql = "";
	$sPoTypeSql  = "";

	if ($iYear == 0)
		$iYear = date("Y");


	if ($PoType != "")
		$sPoTypeSql = " AND po.order_type='$PoType' ";

	if ($Region > 0)
	{
		$sRegionSql  = " AND country_id='$Region' ";
		$sVendorsSql = " AND po.vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";
	}

	if ($Type != "")
	{
		$iQuantity = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$sMonths   = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");


		if ($Type == "Forecast")
		{
			$sColor = "a6bed1";


			$sSQL = "SELECT SUM(quantity), month
			         FROM tbl_forecasts
			         WHERE year='$iYear' AND FIND_IN_SET(brand_id, '0,{$Brands}') AND FIND_IN_SET(vendor_id, '0,{$Vendors}')
			               $sRegionSql
			         GROUP BY month
			         ORDER BY month";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iMonth = $objDb->getField($i, 1);

				$iQuantity[($iMonth - 1)] = $objDb->getField($i, 0);
			}
		}

		else if ($Type == "Expected")
		{
			$sColor = "c6a43a";

			if ($iYear <= date("Y"))
			{
				$sSQL = "SELECT COALESCE(SUM(pc.order_qty), 0), MONTH(pc.etd_required)
						   FROM tbl_po po, tbl_po_colors pc, tbl_styles s
						   WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
								 AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
								 AND (pc.etd_required BETWEEN '{$iYear}-01-01' AND '{$iYear}-12-31')
								 AND FIND_IN_SET(po.brand_id, '$Brands')
								 AND FIND_IN_SET(po.vendor_id, '$Vendors')
								 $sVendorsSql
								 $sPoTypeSql
						   GROUP BY MONTH(etd_required)
						   ORDER BY MONTH(etd_required)";
			}

			else
			{
				$sSQL = "SELECT SUM(quantity), month
				         FROM tbl_revised_forecasts
				         WHERE year='$iYear'
				               AND FIND_IN_SET(brand_id, '0,{$Brands}')
				               AND FIND_IN_SET(vendor_id, '0,{$Vendors}')
				               AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '0,{$Brands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))
				               $sRegionSql
				         GROUP BY month
				         ORDER BY month";
			}

			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iMonth = $objDb->getField($i, 1);

				$iQuantity[($iMonth - 1)] = $objDb->getField($i, 0);
			}


			if ($iYear == date("Y") && date("n") <= 10)
			{
				$iMonth = (date("n") + 2);

				$sSQL = "SELECT SUM(quantity), month
						 FROM tbl_revised_forecasts
						 WHERE FIND_IN_SET(brand_id, '0,{$Brands}')
						       AND FIND_IN_SET(vendor_id, '0,{$Vendors}')
						       AND year='$iYear'
						       AND (month BETWEEN '{$iMonth}' AND '12')
						       AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '0,{$Brands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))
						       $sRegionSql
						 GROUP BY month
						 ORDER BY month";
				$objDb->query($sSQL);

				$iCount = $objDb->getCount( );

				for ($i = 0; $i < $iCount; $i ++)
				{
					$iMonth = $objDb->getField($i, 1);

					if ($iQuantity[($iMonth - 1)] < $objDb->getField($i, 0))
						$iQuantity[($iMonth - 1)] = $objDb->getField($i, 0);
				}
			}
		}
?>
			<chart caption='Month wise Stats <?= $iYear ?>' numDivLines='10' formatNumberScale='0' showValues='0' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5'>
<?
		for ($i = 0; $i < 12; $i ++)
		{
?>
			  <set label='<?= $sMonths[$i] ?>' value='<?= $iQuantity[$i] ?>' color='<?= $sColor ?>' tooltext='<?= $sMonths[$i] ?>: <?= formatNumber($iQuantity[$i], false) ?>' />
<?
		}
?>
		  	</chart>
<?
	}


	else
	{
//		if ($Mode == "Brands" || $Mode == "Departments")
		{
			$iYears      = array();
			$iForecasts  = array(0, 0, 0, 0, 0);
			$iReviseds   = array(0, 0, 0, 0, 0);
			$iPlacements = array(0, 0, 0, 0, 0);

			$iStartYear = ($iYear - 3);
			$iEndYear   = ($iYear + 1);

			if ($iYear > date("Y"))
				$iEndYear = $iYear;

			for ($i = $iStartYear; $i <= $iEndYear; $i ++)
				$iYears[] = $i;


			$sSQL = "SELECT SUM(quantity), year
					 FROM tbl_forecasts
					 WHERE FIND_IN_SET(brand_id, '{$Brands}')
					       AND FIND_IN_SET(vendor_id, '0,{$Vendors}')
					       AND (year BETWEEN '{$iStartYear}' AND '{$iEndYear}')
					       AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$Brands}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))
					       $sRegionSql
					 GROUP BY year
					 ORDER BY year";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iQuantity = $objDb->getField($i, 0);
				$iYear     = $objDb->getField($i, 1);

				$iForecasts[($iYear - $iStartYear)] = $iQuantity;
			}


			$sSQL = "SELECT SUM(quantity), year
					 FROM tbl_revised_forecasts
					 WHERE FIND_IN_SET(brand_id, '{$Brands}')
					       AND FIND_IN_SET(vendor_id, '0,{$Vendors}')
					       AND (year BETWEEN '{$iStartYear}' AND '{$iEndYear}')
					       $sRegionSql
					 GROUP BY year
					 ORDER BY year";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iQuantity = $objDb->getField($i, 0);
				$iYear     = $objDb->getField($i, 1);

				$iReviseds[($iYear - $iStartYear)] = $iQuantity;
			}


			$sSQL = "SELECT COALESCE(SUM(pc.order_qty), 0), YEAR(pc.etd_required)
					 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
					 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
						   AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
						   AND (pc.etd_required BETWEEN '{$iStartYear}-01-01' AND '{$iEndYear}-12-31')
						   AND FIND_IN_SET(po.brand_id, '$Brands')
						   AND FIND_IN_SET(po.vendor_id, '$Vendors')
						   $sVendorsSql
						   $sPoTypeSql
					 GROUP BY YEAR(etd_required)
					 ORDER BY YEAR(etd_required)";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iQuantity = $objDb->getField($i, 0);
				$iYear     = $objDb->getField($i, 1);

				$iPlacements[($iYear - $iStartYear)] = $iQuantity;
			}
?>
		   <chart caption='Year wise Statistics' formatNumberScale='0' showValues='0' showLabels='1' chartBottomMargin='5' legendPosition='BOTTOM'>
		   <categories>
<?
			for ($i = 0; $i < count($iYears); $i ++)
			{
?>
		   <category label='<?= $iYears[$i] ?>' />
<?
			}
?>
		   </categories>

		  <dataset seriesName='Forecast' color='a6bed1'>
<?
			for ($i = 0; $i < count($iYears); $i ++)
			{
?>
		    <set value='<?= $iForecasts[$i] ?>' tooltext='Forecast{br}<?= $iYears[$i] ?>{br}<?= formatNumber($iForecasts[$i], false) ?>' link='javaScript:showIndividualStats("Forecast", "<?= $Mode ?>", "<?= $Brands ?>", "<?= $Vendors ?>", "<?= $iYears[$i] ?>-01-01", "<?= $iYears[$i] ?>-12-31", "<?= $Region ?>", "<?= $PoType ?>");' />
<?
			}
?>
		  </dataset>

		  <dataset seriesName='Placement and/or Expected' color='c6a43a'>
<?
			for ($i = 0; $i < count($iYears); $i ++)
			{
				$iQuantity = $iPlacements[$i];


				if ($iYears[$i] > date("Y"))
					$iQuantity = $iReviseds[$i];

				else if ($iYears[$i] == date("Y") && date("n") <= 10)
				{
					$iYear     = $iYears[$i];
					$iMonth    = (date("n") + 2);
					$sMonth    = str_pad($iMonth, 2, '0', STR_PAD_LEFT);

					$sPrevious = str_pad(($iMonth - 1), 2, '0', STR_PAD_LEFT);
					$iDays     = @cal_days_in_month(CAL_GREGORIAN, ($iMonth - 1), $iYear);


					$sSQL = "SELECT COALESCE(SUM(pc.order_qty), 0)
							 FROM tbl_po po, tbl_po_colors pc, tbl_styles s
							 WHERE po.id=pc.po_id AND pc.style_id=s.id AND po.order_nature='B'
							       AND FIND_IN_SET(s.category_id, '{$_SESSION['StyleCategories']}')
							       AND FIND_IN_SET(po.brand_id, '$Brands')
							       AND FIND_IN_SET(po.vendor_id, '$Vendors')
								   AND (pc.etd_required BETWEEN '{$iYear}-01-01' AND '{$iYear}-{$sPrevious}-{$iDays}')
								   $sVendorsSql
								   $sPoTypeSql";
					$objDb->query($sSQL);

					$iPreviousPlacement = $objDb->getField(0, 0);



					$sSQL = "SELECT SUM(quantity)
							 FROM tbl_revised_forecasts
							 WHERE FIND_IN_SET(brand_id, '{$Brands}')
							       AND FIND_IN_SET(vendor_id, '0,{$Vendors}')
							       AND year='$iYear' AND (month BETWEEN '{$iMonth}' AND '12')
							       $sRegionSql";
					$objDb->query($sSQL);

					$iRevisedPlacement = $objDb->getField(0, 0);


					$iQuantity = ($iPreviousPlacement + $iRevisedPlacement);
				}


				$iQuantity = (($iQuantity < 0) ? 0 : $iQuantity);
?>
		    <set value='<?= $iQuantity ?>' tooltext='Placements and/or Expected{br}<?= $iYears[$i] ?>{br}<?= formatNumber($iQuantity, false) ?>' link='javaScript:showIndividualStats("Expected", "<?= $Mode ?>", "<?= $Brands ?>", "<?= $Vendors ?>", "<?= $iYears[$i] ?>-01-01", "<?= $iYears[$i] ?>-12-31", "<?= $Region ?>", "<?= $PoType ?>");' />
<?
			}
?>
		  </dataset>
		  </chart>
<?
		}
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>