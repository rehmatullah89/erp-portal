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

	$Report1    = IO::strValue("Report1");
	$OrderNo1   = IO::strValue("OrderNo1");
	$AuditCode1 = IO::strValue("AuditCode1");
	$Vendor1    = IO::intValue("Vendor1");
	$Brand1     = IO::intValue("Brand1");
	$FromDate1  = IO::strValue("FromDate1");
	$ToDate1    = IO::strValue("ToDate1");
	$Common1    = IO::strValue("Common1");

	$Report2    = IO::strValue("Report2");
	$OrderNo2   = IO::strValue("OrderNo2");
	$AuditCode2 = IO::strValue("AuditCode2");
	$Vendor2    = IO::intValue("Vendor2");
	$Brand2     = IO::intValue("Brand2");
	$FromDate2  = IO::strValue("FromDate2");
	$ToDate2    = IO::strValue("ToDate2");
	$Common2    = IO::strValue("Common2");


	$sVendorsList  = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList   = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
	$sDefectColors = getList("tbl_defect_types", "id", "color");

	$sReportTypes  = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStages  = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/reports-comparison.js"></script>
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
			    <h1>reports comparison</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">

			    <table border="0" cellpadding="0" cellspacing="0" width="100%">
			      <tr>
			        <td width="50%">
			          <div id="SearchBar" style="border-right:solid 1px #777777;">
					    <table border="0" cellpadding="0" cellspacing="0" width="100%">
						  <tr>
						    <td width="50">Report</td>

						    <td width="85">
							  <select name="Report1">
							    <option value=""></option>
			                    <option value="Matrix"<?= (($Report1 == "Matrix") ? " selected" : "") ?>>Matrix</option>
			                    <option value="CSC"<?= (($Report1 == "CSC") ? " selected" : "") ?>>CSC</option>
							  </select>
						    </td>

						    <td width="25">PO</td>
						    <td><input type="text" name="OrderNo1" id="OrderNo1" value="<?= $OrderNo1 ?>" class="textbox" maxlength="250" size="40" /></td>
						    <td width="25"><img src="images/icons/duplicate.gif" width="16" height="16" alt="Copy" title="Copy" style="cursor:pointer;" onclick="$('OrderNo2').value=$('OrderNo1').value;" /></td>
						  </tr>
						</table>
			          </div>

					  <div id="SubSearchBar" style="border-right:solid 1px #777777;">
					    <table border="0" cellpadding="0" cellspacing="0" width="100%">
						  <tr>
						    <td width="54">Vendor</td>

						    <td width="180">
							  <select name="Vendor1" style="width:170px;">
							    <option value="">All Vendors</option>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			                    <option value="<?= $sKey ?>"<?= (($sKey == $Vendor1) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
							  </select>
						    </td>

						    <td width="45">Brand</td>

						    <td width="160">
							  <select name="Brand1" style="width:150px;">
							    <option value="">All Brands</option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			              	    <option value="<?= $sKey ?>"<?= (($sKey == $Brand1) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
							  </select>
						    </td>
						  </tr>
					    </table>
					  </div>

					  <div id="SubSearchBar" style="border-right:solid 1px #777777;">
					    <table border="0" cellpadding="0" cellspacing="0">
						  <tr>
						    <td width="40">From</td>
						    <td width="78"><input type="text" name="FromDate1" value="<?= $FromDate1 ?>" id="FromDate1" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate1'), 'yyyy-mm-dd', this);" /></td>
						    <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate1'), 'yyyy-mm-dd', this);" /></td>
						    <td width="30" align="center">To</td>
						    <td width="78"><input type="text" name="ToDate1" value="<?= $ToDate1 ?>" id="ToDate1" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate1'), 'yyyy-mm-dd', this);" /></td>
						    <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate1'), 'yyyy-mm-dd', this);" /></td>
						    <td>[ <a href="#" onclick="$('FromDate1').value=''; $('ToDate1').value=''; return false;">Clear Dates</a> ]</td>
						  </tr>
					    </table>
					  </div>

<?
	if ($Report1 != $Report2)
	{
?>
					  <div id="SubSearchBar" style="border-right:solid 1px #777777;">
					    <table border="0" cellpadding="0" cellspacing="0">
						  <tr>
<?
		if ($Report1 == "CSC")
		{
?>
						    <td width="40"><input type="checkbox" name="Common1" value="Y" <?= (($Common1 == "Y") ? "checked" : "") ?> /></td>
						    <td>Take Common POs for Comparison</td>
<?
		}
?>
						    <td>&nbsp;</td>
						  </tr>
					    </table>
					  </div>
<?
	}
?>
			        </td>

			        <td width="50%">
			          <div id="SearchBar" style="border-left:solid 1px #777777;">
					    <table border="0" cellpadding="0" cellspacing="0" width="100%">
						  <tr>
						    <td width="50">Report</td>

						    <td width="80">
							  <select name="Report2">
							    <option value=""></option>
			                    <option value="Matrix"<?= (($Report2 == "Matrix") ? " selected" : "") ?>>Matrix</option>
			                    <option value="CSC"<?= (($Report2 == "CSC") ? " selected" : "") ?>>CSC</option>
							  </select>
						    </td>

						    <td width="22">PO</td>
						    <td><input type="text" name="OrderNo2" id="OrderNo2" value="<?= $OrderNo2 ?>" class="textbox" maxlength="250" size="28" /></td>
						    <td width="113" align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" onclick="return validateForm( );" /></td>
						  </tr>
						</table>
			          </div>

					  <div id="SubSearchBar" style="border-left:solid 1px #777777;">
					    <table border="0" cellpadding="0" cellspacing="0" width="100%">
						  <tr>
						    <td width="54">Vendor</td>

						    <td width="180">
							  <select name="Vendor2" style="width:170px;">
							    <option value="">All Vendors</option>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			                    <option value="<?= $sKey ?>"<?= (($sKey == $Vendor2) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
							  </select>
						    </td>

						    <td width="45">Brand</td>

						    <td width="160">
							  <select name="Brand2" style="width:150px;">
							    <option value="">All Brands</option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			              	    <option value="<?= $sKey ?>"<?= (($sKey == $Brand2) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
							  </select>
						    </td>
						  </tr>
					    </table>
					  </div>

					  <div id="SubSearchBar" style="border-left:solid 1px #777777;">
					    <table border="0" cellpadding="0" cellspacing="0">
						  <tr>
						    <td width="40">From</td>
						    <td width="78"><input type="text" name="FromDate2" value="<?= $FromDate2 ?>" id="FromDate2" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate2'), 'yyyy-mm-dd', this);" /></td>
						    <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate2'), 'yyyy-mm-dd', this);" /></td>
						    <td width="30" align="center">To</td>
						    <td width="78"><input type="text" name="ToDate2" value="<?= $ToDate2 ?>" id="ToDate2" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate2'), 'yyyy-mm-dd', this);" /></td>
						    <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate2'), 'yyyy-mm-dd', this);" /></td>
						    <td>[ <a href="#" onclick="$('FromDate2').value=''; $('ToDate2').value=''; return false;">Clear Dates</a> ]</td>
						  </tr>
					    </table>
					  </div>

<?
	if ($Report1 != $Report2)
	{
?>
					  <div id="SubSearchBar" style="border-left:solid 1px #777777;">
					    <table border="0" cellpadding="0" cellspacing="0">
						  <tr>
<?
		if ($Report2 == "CSC")
		{
?>
						    <td width="25"><input type="checkbox" name="Common2" value="Y" <?= (($Common2 == "Y") ? "checked" : "") ?> /></td>
						    <td>Take Common POs for Comparison</td>
<?
		}
?>
						    <td>&nbsp;</td>
						  </tr>
					    </table>
					  </div>
<?
	}
?>
			        </td>
			      </tr>
			    </table>
			    </form>

<?
	if ($_GET)
	{
		$sConditions1 = "";
		$sConditions2 = "";

		for ($iIndex = 0; $iIndex < 2; $iIndex ++)
		{
			if ($iIndex == 0)
			{
				$sReport    = $Report1;
				$sOrderNo   = $OrderNo1;
				$sAuditCode = $AuditCode1;
				$iVendor    = $Vendor1;
				$iBrand     = $Brand1;
				$sFromDate  = $FromDate1;
				$sToDate    = $ToDate1;
			}

			else if ($iIndex == 1)
			{
				$sReport    = $Report2;
				$sOrderNo   = $OrderNo2;
				$sAuditCode = $AuditCode2;
				$iVendor    = $Vendor2;
				$iBrand     = $Brand2;
				$sFromDate  = $FromDate2;
				$sToDate    = $ToDate2;
			}


			if ($sReport == "Matrix")
			{
				$sConditions = " AND qa.audit_type='B' AND FIND_IN_SET(qa.audit_result, 'P,A,B') AND qa.audit_stage='F' AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports')
				                 AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

				if ($sFromDate != "" && $sToDate != "")
					$sConditions .= " AND (qa.audit_date BETWEEN '$sFromDate' AND '$sToDate') ";

				if ($sOrderNo != "")
				{
					$sSQL = "SELECT id FROM tbl_po WHERE FIND_IN_SET(order_no, '$sOrderNo')";
					$objDb->query($sSQL);

					$iCount = $objDb->getCount( );


					$sConditions .= " AND ( ";

					for ($i = 0; $i < $iCount; $i ++)
					{
						$iPoId = $objDb->getField($i, 0);

						if ($i > 0)
							$sConditions .= " OR ";

						$sConditions .= " qa.po_id='$iPoId' OR FIND_IN_SET('$iPoId', qa.additional_pos) ";
					}

					$sConditions .= " ) ";
				}

				if ($iVendor > 0)
					$sConditions .= " AND qa.vendor_id='$iVendor' ";

				else
					$sConditions .= " AND qa.vendor_id IN ({$_SESSION['Vendors']}) ";

				if ($iBrand > 0)
				{
					if ($iVendor > 0)
						$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$iBrand' AND vendor_id='$iVendor') ";

					else
						$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$iBrand' AND vendor_id IN ({$_SESSION['Vendors']})) ";
				}

				else
				{
					if ($iVendor > 0)
						$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ({$_SESSION['Brands']}) AND vendor_id='$iVendor') ";

					else
						$sConditions .= " AND qa.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ({$_SESSION['Brands']}) AND vendor_id IN ({$_SESSION['Vendors']})) ";
				}

				if ($sAuditCode != "")
					$sConditions .= " AND qa.audit_code LIKE '%$sAuditCode%' ";
			}


			else
			{
				$sConditions = " AND ca.audit_result!='' AND FIND_IN_SET(ca.report_id, '$sReportTypes') AND NOT FIND_IN_SET(ca.report_id, '$sQmipReports') AND FIND_IN_SET(ca.audit_stage, '$sAuditStages') ";

				if ($sFromDate != "" && $sToDate != "")
					$sConditions .= " AND (ca.audit_date BETWEEN '$sFromDate' AND '$sToDate') ";

				if ($sOrderNo != "")
					$sConditions .= " AND ca.po_id IN (SELECT id FROM tbl_po WHERE FIND_IN_SET(order_no, '$sOrderNo')) ";

				if ($iVendor > 0)
					$sConditions .= " AND ca.vendor_id='$iVendor' ";

				else
					$sConditions .= " AND ca.vendor_id IN ({$_SESSION['Vendors']}) ";

				if ($iBrand > 0)
				{
					if ($iVendor > 0)
						$sConditions .= " AND ca.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$iBrand' AND vendor_id='$iVendor') ";

					else
						$sConditions .= " AND ca.po_id IN (SELECT id FROM tbl_po WHERE brand_id='$iBrand' AND vendor_id IN ({$_SESSION['Vendors']})) ";
				}

				else
				{
					if ($iVendor > 0)
						$sConditions .= " AND ca.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ({$_SESSION['Brands']}) AND vendor_id='$iVendor') ";

					else
						$sConditions .= " AND ca.po_id IN (SELECT id FROM tbl_po WHERE brand_id IN ({$_SESSION['Brands']}) AND vendor_id IN ({$_SESSION['Vendors']})) ";
				}
			}


			if ($iIndex == 0)
				$sConditions1 = $sConditions;

			else
				$sConditions2 = $sConditions;
		}



		$iQuantities1  = 0;
		$iDefects1     = array( );
		$sDefectTypes1 = array( );
		$iDefectTypes1 = array( );


		if ($Report1 == "Matrix")
		{
			if ($Common2 == "Y")
			{
				$sSQL = "SELECT dt.id, dt.type, COALESCE(SUM(qad.defects), 0) AS _Defects
						 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt, tbl_csc_audits ca
						 WHERE qa.id=qad.audit_id AND (ca.po_id=qa.po_id OR FIND_IN_SET(ca.po_id, qa.additional_pos)) AND qad.code_id=dc.id AND dc.type_id=dt.id $sConditions1
						 GROUP BY dc.type_id
						 ORDER BY dt.type";
			}

			else
			{
				$sSQL = "SELECT dt.id, dt.type, COALESCE(SUM(qad.defects), 0) AS _Defects
						 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
						 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id $sConditions1
						 GROUP BY dc.type_id
						 ORDER BY dt.type";
			}
		}

		else
		{
			$sSQL = "SELECT dt.id, dt.type, COALESCE(SUM(cad.defects), 0) AS _Defects
					 FROM tbl_csc_audits ca, tbl_csc_audit_defects cad, tbl_defect_codes dc, tbl_defect_types dt
					 WHERE ca.id=cad.audit_id AND cad.code_id=dc.id AND dc.type_id=dt.id $sConditions1
					 GROUP BY dc.type_id
					 ORDER BY dt.type";
		}

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iDefectTypes1[] = $objDb->getField($i, 0);
			$sDefectTypes1[] = $objDb->getField($i, 1);
			$iDefects1[]     = $objDb->getField($i, 2);
		}


		if ($Report1 == "Matrix")
		{
			if ($Common2 == "Y")
			{
				$sSQL = "SELECT COALESCE(SUM(qa.total_gmts), 0)
						 FROM tbl_qa_reports qa, tbl_csc_audits ca
						 WHERE (ca.po_id=qa.po_id OR FIND_IN_SET(ca.po_id, qa.additional_pos)) $sConditions1";
			}

			else
				$sSQL = "SELECT COALESCE(SUM(qa.total_gmts), 0) FROM tbl_qa_reports qa WHERE qa.id > 0 $sConditions1";
		}

		else
			$sSQL = "SELECT COALESCE(SUM(ca.sample_size), 0) FROM tbl_csc_audits ca WHERE ca.id > 0 $sConditions1";

		$objDb->query($sSQL);

		$iQuantities1 = $objDb->getField(0, 0);



		$iQuantities2  = 0;
		$iDefects2     = array( );
		$sDefectTypes2 = array( );
		$iDefectTypes2 = array( );


		if ($Report2 == "Matrix")
		{
			if ($Common1 == "Y")
			{
				$sSQL = "SELECT dt.id, dt.type, COALESCE(SUM(qad.defects), 0) AS _Defects
						 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt, tbl_csc_audits ca
						 WHERE qa.id=qad.audit_id AND (ca.po_id=qa.po_id OR FIND_IN_SET(ca.po_id, qa.additional_pos)) AND qad.code_id=dc.id AND dc.type_id=dt.id $sConditions2
						 GROUP BY dc.type_id
						 ORDER BY dt.type";
			}

			else
			{
				$sSQL = "SELECT dt.id, dt.type, COALESCE(SUM(qad.defects), 0) AS _Defects
						 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_defect_types dt
						 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id=dt.id $sConditions2
						 GROUP BY dc.type_id
						 ORDER BY dt.type";
			}
		}

		else
		{
			$sSQL = "SELECT dt.id, dt.type, COALESCE(SUM(cad.defects), 0) AS _Defects
					 FROM tbl_csc_audits ca, tbl_csc_audit_defects cad, tbl_defect_codes dc, tbl_defect_types dt
					 WHERE ca.id=cad.audit_id AND cad.code_id=dc.id AND dc.type_id=dt.id $sConditions2
					 GROUP BY dc.type_id
					 ORDER BY dt.type";
		}

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iDefectTypes2[] = $objDb->getField($i, 0);
			$sDefectTypes2[] = $objDb->getField($i, 1);
			$iDefects2[]     = $objDb->getField($i, 2);
		}


		if ($Report2 == "Matrix")
		{
			if ($Common1 == "Y")
			{
				$sSQL = "SELECT COALESCE(SUM(qa.total_gmts), 0)
						 FROM tbl_qa_reports qa, tbl_csc_audits ca
						 WHERE (ca.po_id=qa.po_id OR FIND_IN_SET(ca.po_id, qa.additional_pos)) $sConditions2";
			}

			else
				$sSQL = "SELECT COALESCE(SUM(qa.total_gmts), 0) FROM tbl_qa_reports qa WHERE qa.id > 0 $sConditions2";
		}

		else
			$sSQL = "SELECT COALESCE(SUM(ca.sample_size), 0) FROM tbl_csc_audits ca WHERE ca.id > 0 $sConditions2";

		$objDb->query($sSQL);

		$iQuantities2 = $objDb->getField(0, 0);



		$sDefectTypes    = array( );
		$iDefectTypes    = array( );
		$iReport1Defects = array( );
		$iReport2Defects = array( );


		for ($i = 0; $i < count($sDefectTypes1); $i ++)
		{
			$sDefectTypes[]    = $sDefectTypes1[$i];
			$iDefectTypes[]    = $iDefectTypes1[$i];
			$iReport1Defects[] = $iDefects1[$i];

			if (@array_search($iDefectTypes1[$i], $iDefectTypes2) !== FALSE)
			{
				$iIndex = @array_search($iDefectTypes1[$i], $iDefectTypes2);

				$iReport2Defects[] = $iDefects2[$iIndex];
			}

			else
				$iReport2Defects[] = 0;
		}


		for ($i = 0; $i < count($sDefectTypes2); $i ++)
		{
			if (@array_search($iDefectTypes2[$i], $iDefectTypes1) === FALSE)
			{
				$sDefectTypes[]    = $sDefectTypes2[$i];
				$iDefectTypes[]    = $iDefectTypes2[$i];
				$iReport2Defects[] = $iDefects2[$i];
				$iReport1Defects[] = 0;
			}
		}
?>
			    <div class="tblSheet">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				    <tr>
					  <td width="49%">
<?
		$fDhu1 = @round(((@array_sum($iDefects1) / $iQuantities1) * 100), 2);
		$fDhu2 = @round(((@array_sum($iDefects2) / $iQuantities2) * 100), 2);


		$objChart = new XYChart(454, 420);
		$objChart->setPlotArea(50, 50, 380, 250);

		$objTitle = $objChart->addTitle("\n Reports Comparison", "verdanab.ttf", 10);

		$objBarLayer = $objChart->addBarLayer3(array($fDhu1, $fDhu2));
		$objBarLayer->setBarShape(CircleShape);
		$objBarLayer->setAggregateLabelStyle( );
		$objBarLayer->setAggregateLabelFormat("{value}%");

		$objLabels = $objChart->xAxis->setLabels(array($Report1, $Report2));
		$objChart->yAxis->setLabelFormat("{value}");

		$objChart->addText(10, 340, ("Total Quantity = ".formatNumber($iQuantities1, false)), "verdana.ttf", 10);
		$objChart->addText(10, 360, ("Total Defects  = ".formatNumber(@array_sum($iDefects1), false)), "verdana.ttf", 10);

		$objChart->addText(265, 340, ("Total Quantity = ".formatNumber($iQuantities2, false)), "verdana.ttf", 10);
		$objChart->addText(265, 360, ("Total Defects  = ".formatNumber(@array_sum($iDefects2), false)), "verdana.ttf", 10);

		$objChart->xAxis->setWidth(2);
		$objChart->yAxis->setWidth(2);

		$sChart = $objChart->makeSession("Comparison");
?>
					    <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" />
				      </td>

					  <td width="2%" bgcolor="#f9f9f9"></td>

					  <td width="49%">
<?
		$objChart = new XYChart(454, 420);
		$objChart->setPlotArea(50, 50, 380, 250);

		$objTitle = $objChart->addTitle("\n                              Defect Classification", "verdanab.ttf", 10);

		$objChart->addLegend(15, 5, false);

		$objBarLayer = $objChart->addBarLayer2(Side);
		$objBarLayer->setBarShape(CircleShape);
		$objBarLayer->setBarGap(0.2, TouchBar);

		$objBarLayer->addDataSet($iReport1Defects, 0xff0000, $Report1);
		$objBarLayer->addDataSet($iReport2Defects, 0x7fff7f, $Report2);

		$objBarLayer->setAggregateLabelStyle( );
		$objBarLayer->setAggregateLabelFormat("{value}");

		$objLabels = $objChart->xAxis->setLabels($sDefectTypes);
		$objLabels->setFontAngle(45);
		$objChart->yAxis->setLabelFormat("{value}");

		$objChart->xAxis->setWidth(2);
		$objChart->yAxis->setWidth(2);

		$sChart = $objChart->makeSession("DefectClassMap");
?>
					    <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" />
					  </td>
				    </tr>

				    <tr bgcolor="#f9f9f9">
					  <td colspan="3" height="18"></td>
				    </tr>

				    <tr height="320" valign="top">
					  <td width="49%">
<?
		$sColors = array( );

		for ($i = 0; $i < count($iDefectTypes); $i ++)
			$sColors[] = hexdec(substr($sDefectColors[$iDefectTypes[$i]], 1));



		$objChart = new PieChart(454, (360 + (count($iDefects1) * 22)));

		$objChart->setDonutSize(227, 170, 170, 50);
		$objChart->addTitle("\n{$Report1} Defect Classification", "verdanab.ttf", 10);

		$objChart->setColors2(8, $sColors);
		$objChart->addLegend(15, 340);

		$objChart->set3D(25);
		$objChart->setData($iDefects1, $sDefectTypes1);

		$objChart->setLabelPos(-50);
		$objChart->setLabelFormat("{percent}%");
		$objChart->setLabelStyle("verdana.ttf", 10, 0x000000);

		$objChart->addText(10, 310, ("Total = ".@array_sum($iDefects1)), "verdanab.ttf", 10);

		$sChart = $objChart->makeSession("DefectClass1");

		$objChart->addExtraField($iDefectTypes1);

		$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], "Report1={$Report1}&OrderNo1={$OrderNo1}&AuditCode1={$AuditCode1}&Vendor1={$Vendor1}&Brand1={$Brand1}&FromDate1={$FromDate1}&ToDate1={$ToDate1}&Common1={$Common1}&Report2={$Report2}&OrderNo2={$OrderNo2}&AuditCode2={$AuditCode2}&Vendor2={$Vendor2}&Brand2={$Brand2}&FromDate2={$FromDate2}&ToDate2={$ToDate2}&Common2={$Common2}&DefectType={field0}", "title='[{value}] {label} ({percent}%)'");
?>
					    <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" usemap="#DefectClassMap1" />

					    <map name="DefectClassMap1">
						  <?= $sImageMap ?>
					    </map>
					  </td>

					  <td width="2%" bgcolor="#f9f9f9"></td>

					  <td width="49%">
<?
		$objChart = new PieChart(454, (360 + (count($iDefects2) * 22)));

		$objChart->setDonutSize(227, 170, 170, 50);
		$objChart->addTitle("\n{$Report2} Defect Classification", "verdanab.ttf", 10);

		$objChart->setColors2(8, $sColors);
		$objChart->addLegend(15, 340);

		$objChart->set3D(25);
		$objChart->setData($iDefects2, $sDefectTypes2);

		$objChart->setLabelPos(-50);
		$objChart->setLabelFormat("{percent}%");
		$objChart->setLabelStyle("verdana.ttf", 10, 0x000000);

		$objChart->addText(10, 310, ("Total = ".@array_sum($iDefects2)), "verdanab.ttf", 10);

		$sChart = $objChart->makeSession("DefectClass2");

		$objChart->addExtraField($iDefectTypes2);

		$sImageMap = $objChart->getHTMLImageMap($_SERVER['PHP_SELF'], "Report1={$Report1}&OrderNo1={$OrderNo1}&AuditCode1={$AuditCode1}&Vendor1={$Vendor1}&Brand1={$Brand1}&FromDate1={$FromDate1}&ToDate1={$ToDate1}&Common1={$Common1}&Report2={$Report2}&OrderNo2={$OrderNo2}&AuditCode2={$AuditCode2}&Vendor2={$Vendor2}&Brand2={$Brand2}&FromDate2={$FromDate2}&ToDate2={$ToDate2}&Common2={$Common2}&DefectType={field0}", "title='[{value}] {label} ({percent}%)'");
?>
					    <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" border="0" usemap="#DefectClassMap2" />

					    <map name="DefectClassMap2">
						  <?= $sImageMap ?>
					    </map>
					  </td>
				    </tr>

<?
		$DefectType = IO::intValue("DefectType");

		if ($DefectType > 0)
		{
?>
				    <tr bgcolor="#f9f9f9">
					  <td colspan="3" height="18"></td>
				    </tr>

				    <tr height="320" valign="top">
					  <td width="49%">
<?
			$iDefects     = array( );
			$sDefectCodes = array( );
			$sDefects     = array( );

			if ($Report1 == "Matrix")
			{
				if ($Common2 == "Y")
				{
					$sSQL = "SELECT dc.code, dc.defect, COALESCE(SUM(qad.defects), 0)
							 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_csc_audits ca
							 WHERE qa.id=qad.audit_id AND (ca.po_id=qa.po_id OR FIND_IN_SET(ca.po_id, qa.additional_pos)) AND qad.code_id=dc.id AND dc.type_id='$DefectType' $sConditions1
							 GROUP BY dc.id
							 ORDER BY dc.code";
				}

				else
				{
					$sSQL = "SELECT dc.code, dc.defect, COALESCE(SUM(qad.defects), 0)
							 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc
							 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id='$DefectType' $sConditions1
							 GROUP BY dc.id
							 ORDER BY dc.code";
				}
			}

			else
			{
				$sSQL = "SELECT dc.code, dc.defect, COALESCE(SUM(cad.defects), 0) AS _Defects
						 FROM tbl_csc_audits ca, tbl_csc_audit_defects cad, tbl_defect_codes dc
						 WHERE ca.id=cad.audit_id AND cad.code_id=dc.id AND dc.type_id='$DefectType' $sConditions1
						 GROUP BY dc.id
						 ORDER BY dc.code";
			}

			$objDb->query($sSQL);

			$iCount  = $objDb->getCount( );
			$sColors = array( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sDefectCodes[] = $objDb->getField($i, 0);
				$sDefects[]     = $objDb->getField($i, 1);
				$iDefects[]     = $objDb->getField($i, 2);
				$sColors[]      = hexdec(substr($sDefectColors[$DefectType], 1));
			}


			$sSQL = "SELECT type FROM tbl_defect_types WHERE id='$DefectType'";
			$objDb->query($sSQL);

			$sDefectType = $objDb->getField(0, 0);





			$objChart = new XYChart(454, (360 + ($iCount * 20)));
			$objChart->setPlotArea(50, 50, 380, 250);

			$objChart->setColors2(8, $sColors);
			$objChart->addTitle("\n{$sDefectType} Defects ($Report1)", "verdanab.ttf", 10);

			$objBarLayer = $objChart->addBarLayer3($iDefects);
			$objBarLayer->setBarShape(CircleShape);

			$objBarLayer->setAggregateLabelStyle( );
			$objBarLayer->setAggregateLabelFormat("{value}");

			$objLabels = $objChart->xAxis->setLabels($sDefectCodes);
			$objChart->yAxis->setLabelFormat("{value}");

			$objChart->xAxis->setWidth(2);
			$objChart->yAxis->setWidth(2);

			for ($i = 0; $i < $iCount; $i ++)
				$objChart->addText(10, (340 + ($i * 20)), ($sDefectCodes[$i]." = ".$sDefects[$i]), "verdana.ttf", 8);

			$sChart = $objChart->makeSession("DefectType1");
?>
					    <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" />
					  </td>

					  <td width="2%" bgcolor="#f9f9f9"></td>

					  <td width="49%">
<?
			$iDefects     = array( );
			$sDefectCodes = array( );
			$sDefects     = array( );

			if ($Report2 == "Matrix")
			{
				if ($Common1 == "Y")
				{
					$sSQL = "SELECT dc.code, dc.defect, COALESCE(SUM(qad.defects), 0)
							 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc, tbl_csc_audits ca
							 WHERE qa.id=qad.audit_id AND (ca.po_id=qa.po_id OR FIND_IN_SET(ca.po_id, qa.additional_pos)) AND qad.code_id=dc.id AND dc.type_id='$DefectType' $sConditions2
							 GROUP BY dc.id
							 ORDER BY dc.code";
				}

				else
				{
					$sSQL = "SELECT dc.code, dc.defect, COALESCE(SUM(qad.defects), 0)
							 FROM tbl_qa_reports qa, tbl_qa_report_defects qad, tbl_defect_codes dc
							 WHERE qa.id=qad.audit_id AND qad.code_id=dc.id AND dc.type_id='$DefectType' $sConditions2
							 GROUP BY dc.id
							 ORDER BY dc.code";
				}
			}

			else
			{
				$sSQL = "SELECT dc.code, dc.defect, COALESCE(SUM(cad.defects), 0) AS _Defects
						 FROM tbl_csc_audits ca, tbl_csc_audit_defects cad, tbl_defect_codes dc
						 WHERE ca.id=cad.audit_id AND cad.code_id=dc.id AND dc.type_id='$DefectType' $sConditions2
						 GROUP BY dc.id
						 ORDER BY dc.code";
			}

			$objDb->query($sSQL);

			$iCount  = $objDb->getCount( );
			$sColors = array( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$sDefectCodes[] = $objDb->getField($i, 0);
				$sDefects[]     = $objDb->getField($i, 1);
				$iDefects[]     = $objDb->getField($i, 2);
				$sColors[]      = hexdec(substr($sDefectColors[$DefectType], 1));
			}


			$sSQL = "SELECT type FROM tbl_defect_types WHERE id='$DefectType'";
			$objDb->query($sSQL);

			$sDefectType = $objDb->getField(0, 0);



			$objChart = new XYChart(454, (360 + ($iCount * 20)));
			$objChart->setPlotArea(50, 50, 380, 250);

			$objChart->addTitle("\n{$sDefectType} Defects ($Report2)", "verdanab.ttf", 10);
			$objChart->setColors2(8, $sColors);

			$objBarLayer = $objChart->addBarLayer3($iDefects);
			$objBarLayer->setBarShape(CircleShape);

			$objBarLayer->setAggregateLabelStyle( );
			$objBarLayer->setAggregateLabelFormat("{value}");

			$objLabels = $objChart->xAxis->setLabels($sDefectCodes);
			$objChart->yAxis->setLabelFormat("{value}");

			$objChart->xAxis->setWidth(2);
			$objChart->yAxis->setWidth(2);

			for ($i = 0; $i < $iCount; $i ++)
				$objChart->addText(10, (340 + ($i * 20)), ($sDefectCodes[$i]." = ".$sDefects[$i]), "verdana.ttf", 8);

			$sChart = $objChart->makeSession("DefectType2");
?>
					    <img src="requires/get-chart.php?<?= $sChart ?>" alt="" title="" />
					  </td>
					</tr>
<?
		}
?>
				  </table>
			    </div>
<?
	}
?>

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