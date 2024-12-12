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
	$objDb3      = new Database( );
	$objDb4      = new Database( );
	$objDb5      = new Database( );


	$Vendor   = IO::intValue("Vendor");
	$Brand    = IO::intValue("Brand");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Filter   = IO::strValue("Filter");

	if ($FromDate == "" || $ToDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
		$ToDate   = date("Y-m-d", mktime(0, 0, 0, date("m"), (date("d") + 14), date("Y")));
	}

	$sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y' AND id!='194'");
	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/reports/current-standing.js"></script>
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
			    <h1>Current Standing</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="52">Vendor</td>

			          <td width="200">
			            <select name="Vendor">
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
			            <select name="Brand">
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

					  <td width="40">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
			          <td width="45">Filter</td>

			          <td width="150">
			            <select name="Filter">
			              <option value="">All POs</option>
			              <option value="Late"<?= (($Filter == "Late") ? " selected" : "") ?>>Late</option>
			              <option value="WithFAD"<?= (($Filter == "WithFAD") ? " selected" : "") ?>>With FAD</option>
			              <option value="WithoutFAD"<?= (($Filter == "WithoutFAD") ? " selected" : "") ?>>With-out FAD</option>
			            </select>
			          </td>
				    </tr>
				  </table>
			    </div>
			    </form>


			    <div class="tblSheet">
<?
	$bRecords    = false;
	$sClass      = array("evenRow", "oddRow");
	$sVendorsSql = "";
	$sBrandsSql  = "";

	if ($Vendor > 0)
		$sVendorsSql = " AND id='$Vendor' ";

	else
		$sVendorsSql = " AND id IN ({$_SESSION['Vendors']}) "; // AND id!='194' ";


	if ($Brand > 0)
		$sBrandsSql = " WHERE id='$Brand' ";

	else
		$sBrandsSql = " WHERE id IN ({$_SESSION['Brands']}) ";


	$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' ORDER BY country";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iCountryId = $objDb->getField($i, "id");
		$sCountry   = $objDb->getField($i, "country");


		$sSQL = "SELECT id FROM tbl_vendors WHERE country_id='$iCountryId' AND parent_id='0' $sVendorsSql";
		$objDb2->query($sSQL);

		$iCount2  = $objDb2->getCount( );
		$sVendors = "";

		for ($j = 0; $j < $iCount2; $j ++)
			$sVendors .= (",".$objDb2->getField($j, 0));

		if ($sVendors != "")
			$sVendors = substr($sVendors, 1);
?>
				  <div id="Country<?= $iCountryId ?>">
				    <h2 style="margin-bottom:1px;" class="green"><?= $sCountry ?></h2>
<?
		$bAnyRecord = false;

		$sSQL = "SELECT id, brand, (SELECT name FROM tbl_users WHERE id=tbl_brands.manager) AS _Manager FROM tbl_brands $sBrandsSql ORDER BY brand";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iBrandId = $objDb2->getField($j, "id");
			$sBrand   = $objDb2->getField($j, "brand");
			$sManager = $objDb2->getField($j, "_Manager");


			$sSQL = "SELECT id FROM tbl_styles WHERE sub_brand_id='$iBrandId'";
			$objDb3->query($sSQL);

			$iCount3      = $objDb3->getCount( );
			$sBrandStyles = "";

			for ($k = 0; $k < $iCount3; $k ++)
				$sBrandStyles .= (",".$objDb3->getField($k, 0));

			if ($sBrandStyles != "")
				$sBrandStyles = substr($sBrandStyles, 1);


			$sSQL = "SELECT DISTINCT(po.id) FROM tbl_po po, tbl_po_colors pc WHERE po.id=pc.po_id AND po.vendor_id IN ($sVendors) AND pc.style_id IN ($sBrandStyles) AND (etd_required BETWEEN '$FromDate' AND '$ToDate') ORDER BY po.id";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			if ($iCount3 == 0)
				continue;

			$iBrandRecords = 0;
			$iRow          = 0;
?>
				    <div id="Brand<?= $iBrandId ?>_<?= $iCountryId ?>">
				      <h2 style="margin-bottom:1px; background:#444444; cursor:pointer;" onclick="Effect.toggle('PO_<?= $iCountryId ?>_<?= $iBrandId ?>', 'slide');"><?= $sBrand ?> &nbsp; <span id="Count<?= $iBrandId ?>_<?= $iCountryId ?>" style="font-size:12px; font-weight:normal;">(<?= $iCount3 ?>)</span><? if ($sManager != "") { ?> &nbsp; &nbsp; &nbsp; <span style="font-size:12px; font-weight:normal;"><?= $sManager ?></span> &nbsp; <a href="reports/export-current-standing.php?Vendor=<?= $Vendor ?>&Brand=<?= $iBrandId ?>&FromDate=<?= $FromDate ?>&ToDate=<?= $ToDate ?>&Filter=<?= $Filter ?>&Country=<?= $iCountryId ?>"><img src="images/icons/email.gif" width="16" height="16" alt="Email to the Brand Manager" title="Email to the Brand Manager" /></a><? } ?></h2>

 				      <div id="PO_<?= $iCountryId ?>_<?= $iBrandId ?>" style="display:<?= (($iCount2 == 1) ? 'block' : 'none') ?>;">
				        <div>
					      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
						    <tr class="headerRow">
						      <td width="15%"><b>PO</b></td>
						      <td width="15%"><b>Final Audit Date</b></td>
						      <td width="15%"><b>ETD Required</b></td>
						      <td width="15%"><b>Order Qty</b></td>
						      <td width="40%"><b>Delay Reason</b></td>
						    </tr>
<?
			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iPoId = $objDb3->getField($k, 0);


				$sOrderNo      = "";
				$sEtdRevisions = "";
				$sFadRevisions = "";
				$sDelayReason  = "";
				$sBgColor      = "";

				$sSQL = "SELECT etd_required FROM tbl_po_colors WHERE po_id='$iPoId' ORDER BY etd_required LIMIT 1";
				$objDb4->query($sSQL);

				$sEtdRequired = $objDb4->getField(0, 0);


				$sSQL = "SELECT final_audit_date FROM tbl_vsr WHERE po_id='$iPoId'";
				$objDb4->query($sSQL);

				$sFinalAuditDate = $objDb4->getField(0, 0);

				if ($sFinalAuditDate == "0000-00-00" || $sFinalAuditDate == "1970-01-01")
					$sFinalAuditDate = "";



				$iEtdRequired    = strtotime($sEtdRequired);
				$iFinalAuditDate = strtotime($sFinalAuditDate);

				if ($iBrandId == 32)
					$iEtdRequired += 172800;



				if ($Filter == "Late")
				{
					if ($sFinalAuditDate != "" && $iEtdRequired >= $iFinalAuditDate)
						continue;

					else
						$iBrandRecords ++;
				}

				else if ($Filter == "WithFAD")
				{
					if ($sFinalAuditDate == "")
						continue;

					else
						$iBrandRecords ++;
				}

				else if ($Filter == "WithoutFAD")
				{
					if ($sFinalAuditDate != "")
						continue;

					else
						$iBrandRecords ++;
				}

				else
					$iBrandRecords ++;


				$sSQL = "SELECT CONCAT(order_no, ' ', order_status), quantity FROM tbl_po WHERE id='$iPoId'";
				$objDb4->query($sSQL);

				$sOrderNo  = $objDb4->getField(0, 0);
				$iOrderQty = $objDb4->getField(0, 1);


				$sSQL = "SELECT reason_id, date_time FROM tbl_po_delay_reasons WHERE po_id='$iPoId' ORDER BY id DESC";
				$objDb4->query($sSQL);

				$iCount4 = $objDb4->getCount( );

				if ($iCount4 > 0)
				{
					$sDelayReason = '<table border=\"1\" bordercolor=\"#eeeeee\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">';

					for ($l = 0; $l < $iCount4; $l ++)
					{
						$iReason   = $objDb4->getField($l, 0);
						$sDateTime = $objDb4->getField($l, 1);

						$sSQL = "SELECT reason, (SELECT type FROM tbl_delay_types WHERE id=tbl_delay_reasons.type_id) AS _Type FROM tbl_delay_reasons WHERE id='$iReason'";
						$objDb5->query($sSQL);

						$sDelayReason = $objDb5->getField(0, 0);
						$sDelayType   = $objDb5->getField(0, 1);

						if ($l == 0)
						{
							$sDelayReason .= ('<tr bgcolor=\"#f6f6f6\">');
							$sDelayReason .= ('<td width=\"30%\"><b>Type</b></td>');
							$sDelayReason .= ('<td width=\"50%\"><b>Reason</b></td>');
							$sDelayReason .= ('<td width=\"20%\"><b>Date / Time</b></td>');
							$sDelayReason .= ('</tr>');
						}

						$sDelayReason .= ('<tr valign=\"top\">');
						$sDelayReason .= ('<td>'.$sDelayType.'</td>');
						$sDelayReason .= ('<td>'.$sDelayReason.'</td>');
						$sDelayReason .= ('<td>'.formatDate($sDateTime, "d-M-Y H:i A").'</td>');
						$sDelayReason .= ('</tr>');
					}

					$sDelayReason .= "</table>";
				}


				$sSQL = "SELECT original, revised, date_time FROM tbl_etd_revisions WHERE po_id='$iPoId' ORDER BY id DESC";
				$objDb4->query($sSQL);

				$iCount4 = $objDb4->getCount( );

				if ($iCount4 > 0)
				{
					$sEtdRevisions = '<table border=\"1\" bordercolor=\"#eeeeee\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">';

					for ($l = 0; $l < $iCount4; $l ++)
					{
						$sOriginal = $objDb4->getField($l, 0);
						$sRevised  = $objDb4->getField($l, 1);
						$sDateTime = $objDb4->getField($l, 2);


						if ($l == 0)
						{
							$sEtdRevisions .= ('<tr bgcolor=\"#f6f6f6\">');
							$sEtdRevisions .= ('<td width=\"30%\"><b>Revised</b></td>');
							$sEtdRevisions .= ('<td width=\"30%\"><b>Original</b></td>');
							$sEtdRevisions .= ('<td width=\"40%\"><b>Date / Time</b></td>');
							$sEtdRevisions .= ('</tr>');
						}

						$sEtdRevisions .= ('<tr valign=\"top\">');
						$sEtdRevisions .= ('<td>'.formatDate($sRevised).'</td>');
						$sEtdRevisions .= ('<td>'.formatDate($sOriginal).'</td>');
						$sEtdRevisions .= ('<td>'.formatDate($sDateTime, "d-M-Y H:i A").'</td>');
						$sEtdRevisions .= ('</tr>');
					}

					$sEtdRevisions .= "</table>";
				}


				$sSQL = "SELECT original, revised, date_time FROM tbl_fad_revisions WHERE po_id='$iPoId' ORDER BY id DESC";
				$objDb4->query($sSQL);

				$iCount4 = $objDb4->getCount( );

				if ($iCount4 > 0)
				{
					$sFadRevisions = '<table border=\"1\" bordercolor=\"#eeeeee\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\">';

					for ($l = 0; $l < $iCount4; $l ++)
					{
						$sOriginal = $objDb4->getField($l, 0);
						$sRevised  = $objDb4->getField($l, 1);
						$sDateTime = $objDb4->getField($l, 2);


						if ($l == 0)
						{
							$sFadRevisions .= ('<tr bgcolor=\"#f6f6f6\">');
							$sFadRevisions .= ('<td width=\"30%\"><b>Revised</b></td>');
							$sFadRevisions .= ('<td width=\"30%\"><b>Original</b></td>');
							$sFadRevisions .= ('<td width=\"40%\"><b>Date / Time</b></td>');
							$sFadRevisions .= ('</tr>');
						}

						$sFadRevisions .= ('<tr valign=\"top\">');
						$sFadRevisions .= ('<td>'.formatDate($sRevised).'</td>');
						$sFadRevisions .= ('<td>'.formatDate($sOriginal).'</td>');
						$sFadRevisions .= ('<td>'.formatDate($sDateTime, "d-M-Y H:i A").'</td>');
						$sFadRevisions .= ('</tr>');
					}

					$sFadRevisions .= "</table>";
				}


/*
				$sSQL = "SELECT SUM(ship_qty) FROM tbl_qa_reports WHERE audit_stage='F' AND audit_result='P' AND (po_id='$iPoId' OR FIND_IN_SET('$iPoId', additional_pos))";
				$objDb4->query($sSQL);

				$iShipQty = $objDb4->getField(0, 0);
*/

				if ($sFinalAuditDate == "")
					$sBgColor = 'style="background:#ffeaea;"';

				else if ($iEtdRequired < $iFinalAuditDate)
					$sBgColor = 'style="background:#eab7b7;"';
?>

						    <tr class="<?= $sClass[($iRow % 2)] ?>" <?= $sBgColor ?>>
						      <td><?= $sOrderNo ?></td>

						      <td>
<?
				if ($sUserRights['Edit'] == "Y")
				{
?>
							    <table border="0" cellpadding="0" cellspacing="0" width="100%">
								  <tr>
								    <td width="78"><input type="text" name="FinalAudit_<?= $iCountryId ?>_<?= $iBrandId ?>_<?= $k ?>" value="<?= $sFinalAuditDate ?>" id="FinalAudit_<?= $iCountryId ?>_<?= $iBrandId ?>_<?= $k ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FinalAudit_<?= $iCountryId ?>_<?= $iBrandId ?>_<?= $k ?>'), 'yyyy-mm-dd', this);" onchange="updateFinalAuditDate(<?= $iPoId ?>, this.value);" /></td>
								    <td><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FinalAudit_<?= $iCountryId ?>_<?= $iBrandId ?>_<?= $k ?>'), 'yyyy-mm-dd', this);" /></td>
								  </tr>
							    </table>
<?
				}

				else
				{
?>
							    <?= formatDate($sFinalAuditDate) ?>
<?
				}
?>
				              </td>

						      <td><?= formatDate($sEtdRequired) ?></td>
						      <td><?= formatNumber($iOrderQty, false) ?></td>

						      <td>
<?
				if ($iEtdRequired < $iFinalAuditDate)
				{
?>
                                <img src="images/icons/email.gif" width="16" height="16" hspace="5" align="right" alt="Email to Merchandiser" title="Email to Merchandiser" style="cursor:pointer;" onclick="sendEmail('<?= $iPoId ?>', '<?= $iBrandId ?>');" />
<?
				}

				if ($sDelayReason != "")
				{
?>
					            <img id="DelayTip_<?= $iCountryId ?>_<?= $iBrandId ?>_<?= $k ?>" src="images/icons/more.gif" width="16" height="16" hspace="5" align="right" alt="" title="" />

								<script type="text/javascript">
								<!--
									new Tip('DelayTip_<?= $iCountryId ?>_<?= $iBrandId ?>_<?= $k ?>',
											'<?= $sDelayReason ?>',
											{ title:'Delay Reasons', stem:'topLeft', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:560 });
									-->
								</script>
<?
				}

				if ($sEtdRevisions != "")
				{
?>
					            <img id="EtdTip_<?= $iCountryId ?>_<?= $iBrandId ?>_<?= $k ?>" src="images/icons/working.png" width="16" height="16" hspace="5" align="right" alt="" title="" />

								<script type="text/javascript">
								<!--
									new Tip('EtdTip_<?= $iCountryId ?>_<?= $iBrandId ?>_<?= $k ?>',
											'<?= $sEtdRevisions ?>',
											{ title:'ETD Revisions', stem:'topLeft', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:400 });
									-->
								</script>
<?
				}

				if ($sFadRevisions != "")
				{
?>
					            <img id="FadTip_<?= $iCountryId ?>_<?= $iBrandId ?>_<?= $k ?>" src="images/icons/closed.png" width="16" height="16" hspace="5" align="right" alt="" title="" />

								<script type="text/javascript">
								<!--
									new Tip('FadTip_<?= $iCountryId ?>_<?= $iBrandId ?>_<?= $k ?>',
											'<?= $sFadRevisions ?>',
											{ title:'Final Audit Revisions', stem:'topLeft', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:400 });
									-->
								</script>
<?
				}
?>
						      </td>
						    </tr>

<?
				$iRow ++;
			}

			if ($iBrandRecords > 0)
			{
				$bRecords   = true;
				$bAnyRecord = true;
			}
?>
				  	      </table>

				  	    </div>
				      </div>
				    </div>

	  			    <script type="text/javascript">
	  			    <!--
	  			       $('Count<?= $iBrandId ?>_<?= $iCountryId ?>').innerHTML = "(<?= $iBrandRecords ?>)";
<?
			if ($iBrandRecords == 0)
			{
?>
	  			       $('Brand<?= $iBrandId ?>_<?= $iCountryId ?>').hide( );
<?
			}
?>
	  			    -->
	  			    </script>
<?
		}
?>
	  			  </div>
<?
		if ($bAnyRecord == false)
		{
?>
	  			  <script type="text/javascript">
	  			  <!--
	  			     $('Country<?= $iCountryId ?>').hide( );
	  			  -->
	  			  </script>
<?
		}
	}


	if ($bRecords == false)
	{
?>
	  			  <div class="noRecord">No PO Record Found!</div>
<?
	}
?>
	  			</div>

<?
	if ($bRecords == true)
	{
?>
				<div class="buttonsBar" style="margin-top:4px;">
				  <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= (SITE_URL."reports/export-current-standing.php?Vendor={$Vendor}&Brand={$Brand}&FromDate={$FromDate}&ToDate={$ToDate}&Filter={$Filter}") ?>" />
				  <input type="button" value="" id="BtnExport" class="btnExport" title="Export" onclick="exportReport( );" />
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
	$objDb3->close( );
	$objDb4->close( );
	$objDb5->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>