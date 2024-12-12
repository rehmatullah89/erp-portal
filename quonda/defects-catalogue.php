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

	$Report     = IO::intValue("Report");
	$DefectType = IO::intValue("DefectType");
	$AuditStage = IO::strValue("AuditStage");
	$DefectCode = IO::intValue("DefectCode");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$Vendor     = IO::intValue("Vendor");
	$Brand      = IO::intValue("Brand");
	$Region     = IO::intValue("Region");

	$sDefectTypesList = getList("tbl_defect_types", "id", "type");
	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");

	if (@strpos($_SESSION["Email"], "pelknit.com") !== FALSE || @strpos($_SESSION["Email"], "fencepostproductions.com") !== FALSE)
	{
		$Report           = 13;
		$sDefectTypesList = getList("tbl_defect_types", "id", "type", "id IN (SELECT DISTINCT(type_id) FROM tbl_defect_codes WHERE report_id='$Report')");
	}

	$sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sReportsList     = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes') AND NOT FIND_IN_SET(id, '$sQmipReports')");

	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");
	
	
	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, (date("n") - 3), date("d"), date("Y")));
		$ToDate   = date("Y-m-d");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/defects-catalogue.js"></script>
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
			    <h1>defects catalogue</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
<?
	if (@strpos($_SESSION["Email"], "pelknit.com") !== FALSE || @strpos($_SESSION["Email"], "fencepostproductions.com") !== FALSE)
	{
?>
					  <input type="hidden" name="Report" id="Report" value="<?= $Report ?>" />
<?
	}

	else
	{
?>
			          <td width="50">Report</td>

			          <td width="135">
					    <select name="Report" id="Report">
						  <option value="">All Reports</option>
<?
		foreach ($sReportsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Report) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					    </select>
			          </td>
<?
	}
?>

			          <td width="85">Defect Type</td>

			          <td width="185">
					    <select name="DefectType" id="DefectType" onchange="getDefectCodes( );" style="width:175px;">
						  <option value="">All Types</option>
<?
	foreach($sDefectTypesList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $DefectType) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

					  <td width="40">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="35" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
			          <td width="65">[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="82">Audit Stage</td>

			          <td width="110">
					    <select name="AuditStage">
						  <option value="">All Stages</option>
<?
	foreach ($sAuditStagesList as $sKey => $sValue)
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sValue == "Final")
			$sValue = "Firewall";

		if ( (@strpos($_SESSION["Email"], "pelknit.com") !== FALSE || @strpos($_SESSION["Email"], "fencepostproductions.com") !== FALSE) &&
			 !@in_array($sKey, array("B", "C", "O", "F")) )
			continue;
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $AuditStage) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

					  <td width="90">Defect Code</td>

			          <td>
			            <select id="DefectCode" name="DefectCode">
			              <option value="">All Defect Codes</option>
<?
	$sSQL = "SELECT id, CONCAT(`code`, ' - ', defect) FROM tbl_defect_codes WHERE type_id='$DefectType' AND report_id='$Report' ORDER BY `code`";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		  <option value="<?= $sKey ?>"<?= (($sKey == $DefectCode) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>
				    </tr>
				  </table>
				</div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="52">Vendor</td>

			          <td width="200">
			            <select name="Vendor" style="width:190px;">
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

			          <td width="180">
			            <select name="Brand" style="width:170px;">
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

					  <td width="50">Region</td>

					  <td>
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
				    </tr>
				  </table>
			    </div>
			    </form>

			    <div class="tblSheet">
			      <br />

<?
	$sPictures    = array( );
	$sDefectCodes = array( );
	$sConditions   = "";

	
	if ($Report > 0)
		$sConditions .= " AND qa.report_id='$Report' ";

	else
		$sConditions .= " AND FIND_IN_SET(qa.report_id, '$sReportTypes') AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports') ";

	if ($AuditStage != "")
		$sConditions .= " AND qa.audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(qa.audit_stage, '$sAuditStages') ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (qa.audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";

	else
		$sConditions .= " AND qa.vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
	{
		$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y'";
		$objDb->query($sSQL);

		$iCount   = $objDb->getCount( );
		$sVendors = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sVendors .= (",".$objDb->getField($i, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);

		$sConditions .= " AND qa.vendor_id IN ($sVendors) ";
	}

/*
	if ($Brand > 0)
		$sSQL = "SELECT id FROM tbl_po WHERE brand_id='$Brand'";

	else
		$sSQL = "SELECT id FROM tbl_po WHERE brand_id IN ({$_SESSION['Brands']})";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sPos   = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sPos .= (",".$objDb->getField($i, 0));

	if ($sPos != "")
		$sPos = substr($sPos, 1);

	$sConditions .= " AND po_id IN ($sPos) ";
*/
	if ($Brand > 0)
		$sConditions .= " AND qa.brand_id='$Brand' ";

	else
		$sConditions .= " AND qa.brand_id IN ({$_SESSION['Brands']}) ";
	
	
	if ($DefectType > 0)
	{
		$iTypeCodes = array( );
		
		if ($DefectCode > 0)
			$sSQL = "SELECT id, `code` FROM tbl_defect_codes WHERE type_id='$DefectType' AND id='$DefectCode'";

		else
			$sSQL = "SELECT id, `code` FROM tbl_defect_codes WHERE type_id='$DefectType'";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iTypeCodes[]   = $objDb->getField($i, 0);
			$sDefectCodes[] = $objDb->getField($i, 1);
		}

		
		$sTypeCodes = @implode(",", $iTypeCodes);

		$sSQL = "SELECT DISTINCT(qa.id) 
		         FROM tbl_qa_reports qa, tbl_qa_report_defects qad
				 WHERE qa.id=qad.audit_id AND qad.code_id IN ($sTypeCodes) $sConditions";


		$objDb->query($sSQL);

		$iCount      = $objDb->getCount( );
		$sAuditCodes = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sAuditCodes .= (",".$objDb->getField($i, 0));

		if ($sAuditCodes != "")
			$sAuditCodes = substr($sAuditCodes, 1);

		$sConditions .= " AND id IN ($sAuditCodes) ";
	}
	
	
	if ($sConditions != "")
		$sConditions = (" WHERE ".substr($sConditions, 5));


	$sSQL = "SELECT DISTINCT(qa.audit_code), qa.audit_date FROM tbl_qa_reports qa $sConditions ORDER BY qa.id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditCode = $objDb->getField($i, 0);
		$sAuditDate = $objDb->getField($i, 1);

		@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

		$sAuditPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*");
		$sAuditPictures = @array_map("strtoupper", $sAuditPictures);
		$sAuditPictures = @array_unique($sAuditPictures);

		for ($j = 0; $j < count($sAuditPictures); $j ++)
		{
			$sName  = @strtoupper($sAuditPictures[$j]);
			$sName  = @basename($sName, ".JPG");
			$sParts = @explode("_", $sName);

			if ($DefectType > 0)
			{
				if (@in_array($sParts[1], $sDefectCodes))
					$sPictures[] = $sAuditPictures[$j];
			}

			else
				$sPictures[] = $sAuditPictures[$j];


			if (count($sPictures) == 20)
				break;
		}

		if (count($sPictures) == 20)
			break;
	}

	if (count($sPictures) == 0)
	{
?>
				  <div class="noRecord">No Defect Image Found!</div>
				  <br />
<?
	}

	else
	{
?>
				  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="qaImages">
<?
		for ($i = 0; $i < count($sPictures);)
		{
?>
	    			<tr valign="top">
<?
			for ($j = 0; $j < 5; $j ++, $i ++)
			{
				if ($i < count($sPictures))
				{
					$sName = @strtoupper($sPictures[$i]);
					$sName = @basename($sName, ".JPG");

					if (@strpos($sName, " ") !== FALSE)
					{
						$sTitle = "<b>### Invalid File Name ###</b>";
						$bFlag  = false;
					}

					else
					{
						$sParts = @explode("_", $sName);

						$sAuditCode  = $sParts[0];
						$sDefectCode = $sParts[1];
						$sAreaCode   = intval($sParts[2]);
						$bFlag       = true;

						$sSQL = "SELECT report_id,
										(SELECT vendor FROM tbl_vendors WHERE id=qa.vendor_id) AS _Vendor,
										(SELECT order_no FROM tbl_po WHERE id=qa.po_id) AS _PO,
										(SELECT style FROM tbl_styles WHERE id=(SELECT style_id FROM tbl_po_colors WHERE po_id=qa.po_id LIMIT 1)) AS _Style,
										(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line
								 FROM tbl_qa_reports qa
								 WHERE audit_code='$sAuditCode'";

						if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
						{
							$iReportId = $objDb->getField(0, "report_id");

							$sTitle  = $objDb->getField(0, "_Vendor");
							$sTitle .= (" <b>�</b> ".$objDb->getField(0, "_PO"));
							$sTitle .= (" <b>�</b> ".$objDb->getField(0, "_Style"));
							$sTitle .= (" <b>�</b> ".$objDb->getField(0, "_Line"));

							$sSQL = "SELECT defect,
											(SELECT type FROM tbl_defect_types WHERE id=dc.type_id) AS _Type
									 FROM tbl_defect_codes dc
									 WHERE code='$sDefectCode' AND report_id='$iReportId'";

							if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
							{
								$sDefect = $objDb->getField(0, 0);

								$sTitle .= (" <b>�</b> ".$objDb->getField(0, 1));

								if ($iReportId != 4 && $iReportId != 6)
								{
									$sSQL = "SELECT area FROM tbl_defect_areas WHERE id='$sAreaCode'";

									if ($objDb->query($sSQL) == true && $objDb->getCount( ) == 1)
										$sTitle .= (" <b>�</b> ".$objDb->getField(0, 0));

									else
										$bFlag  = false;
								}

								$sTitle .= (" <b>�</b> ".$sDefect);
							}

							else
								$bFlag  = false;
						}

						else
						{
							$sTitle = "<b>### Invalid File Name ###</b>";
							$bFlag  = false;
						}
					}
?>
					  <td width="20%" align="center">
						<div class="qaPic">
						  <div><a href="<?= $sPictures[$i] ?>" class="lightview" rel="gallery[defects]" title="<?= $sTitle ?> :: :: topclose: true"><img src="<?= $sPictures[$i] ?>" alt="" title="" /></a></div>
						</div>

						<span<?= (($bFlag == true) ? '' : ' style="color:#ff0000;"') ?>><?= @strtoupper($sName) ?></span><br />
					  </td>
<?
				}

				else
				{
?>
	      			  <td width="20%"></td>
<?
				}
			}
?>
					</tr>
<?
			if ($i < count($sPictures))
			{
?>
					<tr>
					  <td colspan="5"><hr /></td>
					</tr>
<?
			}

			else
			{
?>
					<tr>
					  <td colspan="5"><br /></td>
					</tr>
<?
			}
		}
?>
	  			  </table>
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