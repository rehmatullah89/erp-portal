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

	@require_once("requires/session.php");

	checkLogin( );

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objSpDb     = new SpDatabase( );

	$Po    = IO::strValue("Po");
	$Style = IO::strValue("Style");
	$PoId  = IO::intValue("PoId");

	$sClass = array("evenRow", "oddRow");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
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
			    <h1><img src="images/h1/po-status.jpg" width="136" height="20" vspace="10" alt="" title="" /></h1>


			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="65">Order No</td>
			          <td width="180"><input type="text" name="Po" value="<?= $Po ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td width="60">Style No</td>
			          <td width="150"><input type="text" name="Style" value="<?= $Style ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>
<?
	$iCount = 1;

	if ($Po != "" && $PoId == 0)
	{
		$sSQL = "SELECT id, order_no, CONCAT(order_no, ' ', order_status) AS _Po,
		                (SELECT brand FROM tbl_brands WHERE id=tbl_po.brand_id) AS _Brand,
		                (SELECT vendor FROM tbl_vendors WHERE id=tbl_po.vendor_id) AS _Vendor
		         FROM tbl_po
		         WHERE (order_no LIKE '%$Po%' OR CONCAT(order_no, ' ', order_status) LIKE '%$Po%')
		               AND vendor_id IN ({$_SESSION['Vendors']})
		               AND brand_id IN ({$_SESSION['Brands']})";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );


		if ($iCount == 0)
		{
?>
			    <div class="tblSheet">
			      <h2>No PO found!</h2>

			      <div style="padding:15px;">
			        Please provide the correct PO number to check the order status.<br /><br />
			      </div>
			    </div>
<?
		}

		else if ($iCount > 1)
		{
?>
			    <div class="tblSheet">
			      <h2>Multiple POs found</h2>

			      <div style="padding:15px;">
			        Please click on the desired PO to check its status.<br /><br />

			        <ol>
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPoId    = $objDb->getField($i, 'id');
				$sOrderNo = $objDb->getField($i, 'order_no');
				$sPo      = $objDb->getField($i, '_Po');
				$sBrand   = $objDb->getField($i, '_Brand');
				$sVendor  = $objDb->getField($i, '_Vendor');
?>
			          <li><a href="po-status.php?Po=<?= $sOrderNo ?>&PoId=<?= $iPoId ?>"><?= $sPo ?></a> - <b><?= $sBrand ?></b> - <?= $sVendor ?></li>
<?
			}
?>
			        </ol>
			      </div>
			    </div>
<?
		}

		else if ($iCount == 1)
			$PoId = $objDb->getField(0, 0);
	}


	else if ($Style != "" && $PoId == 0)
	{
		$sStyles   = "0";
		$bSampling = false;

		$sSQL = "SELECT id, style FROM tbl_styles WHERE style LIKE '%$Style%' AND sub_brand_id IN ({$_SESSION['Brands']})";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iStyle   = $objDb->getField($i, 'id');
				$sStyle   = $objDb->getField($i, 'style');
				$sStyles .= (",".$iStyle);


				$sSQL = "SELECT id, sent_2_sampling, `status`,
								(SELECT type FROM tbl_sampling_types WHERE id=tbl_merchandisings.sample_type_id) AS _SampleType,
								(SELECT created FROM tbl_comment_sheets WHERE merchandising_id=tbl_merchandisings.id) AS _Created
						 FROM tbl_merchandisings
						 WHERE style_id='$iStyle'";
				$objDb2->query($sSQL);

				$iCount2 = $objDb2->getCount( );

				if ($iCount2 > 0)
				{
					$bSampling = true;
?>
			    <!-- Sampling Summary -->
			    <div class="tblSheet">
			      <h2 style="margin:0px 1px 0px 0px;">Sampling Summary - <?= $sStyle ?></h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow" style="background:#aaaaaa;">
				      <td width="15%">Code</td>
				      <td width="20%">Sample Type</td>
				      <td width="15%">Request Date</td>
				      <td width="15%">Status</td>
				      <td width="15%">Report Date</td>
				      <td width="20%">Specs Report</td>
				    </tr>
<?
					for ($j = 0; $j < $iCount2; $j ++)
					{
						$iId         = $objDb2->getField($j, 'id');
						$sRequested  = $objDb2->getField($j, "sent_2_sampling");
						$sSampleType = $objDb2->getField($j, "_SampleType");
						$sStatus     = $objDb2->getField($j, "status");
						$sCreated    = $objDb2->getField($j, "_Created");


						@list($sYear, $sMonth, $sDay) = @explode("-", $sCreated);

						$sCode = ("M".str_pad($iId, 6, '0', STR_PAD_LEFT));

						$sPictures = @glob($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sCode."_*.*");
						$sPictures = @array_map("strtoupper", $sPictures);
						$sPictures = @array_unique($sPictures);
?>

				    <tr class="evenRow" valign="top">
				      <td><?= $sCode ?></td>
				      <td><?= $sSampleType ?></td>
				      <td><?= formatDate($sRequested) ?></td>
				      <td><?= (($sStatus == "A") ? "Approved" : (($sStatus == "R") ? "Rejected" : "Waiting")) ?></td>
				      <td><?= formatDate($sCreated) ?></td>

				      <td>
<?
						if (checkUserRights("measurements-report.php", "Reports", "view") && formatDate($sCreated) != "")
						{
?>
				        <a href="reports/export-measurements-report.php?Id=<?= $iId ?>"><img src="images/icons/report.gif" width="16" height="16" alt="Measurements Report" title="Measurements Report" align="absmiddle" /> Measurements</a>
				        &nbsp;
<?
						}

						for ($k = 0; $k < count($sPictures); $k ++)
						{
?>
						<span style="visibility:<?= (($k == 0) ? 'visible' : 'hidden') ?>;"><a href="<?= SAMPLING_PICS_DIR.$sPictures[$k] ?>" class="lightview" rel="gallery[set<?= $iId ?>]"><img src="images/icons/pictures.gif" width="16" height="16" alt="Pictures" title="Pictures" align="absmiddle" /> Pictures</a></span>
<?
						}
?>
				      </td>
				    </tr>
<?
					}
?>
				  </table>
			    </div>

			    <br />
<?
				}


/*
				$sSQL = "SELECT * FROM tbl_style_log WHERE style_id='$iStyle' AND reason='R'";
				$objDb2->query($sSQL);

				$iCount2 = $objDb2->getCount( );

				if ($iCount2 > 0)
				{
?>
			    <!-- Sampling Summary -->
			    <div class="tblSheet">
			      <h2 style="margin:0px 1px 0px 0px;">Style Specs Revision</h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow" style="background:#aaaaaa;">
				      <td width="80%">Remarks</td>
				      <td width="20%">Date / Time</td>
				    </tr>
<?
					for ($j = 0; $j < $iCount2; $j ++)
					{
						$sRemarks  = $objDb2->getField($j, "remarks");
						$sDateTime = $objDb2->getField($j, "date_time");
?>
				    <tr class="evenRow">
				      <td><?= $sRemarks ?></td>
				      <td><?= formatDate($sDateTime, "d-M-Y h:i A") ?></td>
				    </tr>
<?
					}
?>
				  </table>
				</div>

				<br />
<?
				}
*/
			}



			$sSQL = "SELECT id, order_no, CONCAT(order_no, ' ', order_status) AS _Po,
							(SELECT brand FROM tbl_brands WHERE id=tbl_po.brand_id) AS _Brand,
							(SELECT vendor FROM tbl_vendors WHERE id=tbl_po.vendor_id) AS _Vendor
					 FROM tbl_po
					 WHERE id IN (SELECT DISTINCT(po_id) FROM tbl_po_colors WHERE FIND_IN_SET(style_id, '$sStyles'))
					       AND vendor_id IN ({$_SESSION['Vendors']})
		                   AND brand_id IN ({$_SESSION['Brands']})";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
		}


		if ($iCount == 0 && $bSampling == false)
		{
?>
			    <div class="tblSheet">
			      <h2>No PO found!</h2>

			      <div style="padding:15px;">
			        Please provide the correct Style No to check the order status.<br /><br />
			      </div>
			    </div>
<?
		}

		else if ($iCount > 1)
		{
?>
			    <div class="tblSheet">
			      <h2>Multiple POs found</h2>

			      <div style="padding:15px;">
			        Please click on the desired PO to check its status.<br /><br />

			        <ol>
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPoId    = $objDb->getField($i, 'id');
				$sOrderNo = $objDb->getField($i, 'order_no');
				$sPo      = $objDb->getField($i, '_Po');
				$sBrand   = $objDb->getField($i, '_Brand');
				$sVendor  = $objDb->getField($i, '_Vendor');
?>
			          <li><a href="po-status.php?Po=<?= $sOrderNo ?>&PoId=<?= $iPoId ?>&Style=<?= $Style ?>"><?= $sPo ?></a> - <b><?= $sBrand ?></b> - <?= $sVendor ?></li>
<?
			}
?>
			        </ol>
			      </div>
			    </div>
<?
		}

		else if ($iCount == 1)
			$PoId = $objDb->getField(0, 0);
	}

	else if ($Po == "" && $Style == "")
	{
?>
			    <div class="tblSheet">
			      <h2>No Record found!</h2>

			      <div style="padding:15px;">
			        Please provide the correct PO No or Style No to check the order status.<br /><br />
			      </div>
			    </div>
<?
	}



	if ($PoId > 0 && $iCount == 1)
	{
		$sSQL = "CALL sp_po_summary('$PoId')";
		$objSpDb->query($sSQL);

		$sOrderNo     = $objSpDb->getField(0, '_OrderNo');
		$iQuantity    = $objSpDb->getField(0, 'quantity');
		$iStyles      = $objSpDb->getField(0, 'styles');
		$sBrand       = $objSpDb->getField(0, '_Brand');
		$sVendor      = $objSpDb->getField(0, '_Vendor');
		$sEtdRequired = $objSpDb->getField(0, '_EtdRequired');
		$sStyle       = $objSpDb->getField(0, '_Style');
		$sSeason      = $objSpDb->getField(0, '_Season');
		
			
		$sSQL = "SELECT hs_code, shipping_from_date, shipping_to_date, carton_instructions, carton_labeling, pdf, sizes, currency, brand_id FROM tbl_po WHERE id='$PoId'";
		$objDb->query($sSQL);

		$sHsCode              = $objDb->getField(0, "hs_code");
		$sShippingFromDate    = $objDb->getField(0, "shipping_from_date");
		$sShippingToDate      = $objDb->getField(0, "shipping_to_date");
		$sCartonInstructions  = $objDb->getField(0, "carton_instructions");
		$sCartonLabeling      = $objDb->getField(0, "carton_labeling");
		$sPdfFile             = $objDb->getField(0, "pdf");
		$sSizes               = $objDb->getField(0, "sizes");
		$sCurrency            = $objDb->getField(0, "currency");
		$iBrand               = $objDb->getField(0, "brand_id");
?>
			    <!-- PO Summary -->
			    <div class="tblSheet">
			      <h2 style="margin:0px 1px 0px 0px;">
				    PO Summary
<?
		if ($sPdfFile != "" && @file_exists(PO_DOCS_DIR.$sPdfFile))
		{
?>
                    <span style="color:#ffff00; font-weight:normal; margin:1px 5px 0px 0px; float:right;">PO File &nbsp; <a href="<?= (PO_DOCS_DIR.$sPdfFile) ?>" target="_blank"><img src="images/icons/pdf.gif" alt="" title="" align="right" /></a></span>
<?
		}
?>
				  </h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow" style="background:#aaaaaa;">
				      <td width="14%">Order No</td>
				      <td width="14%">Brand</td>
				      <td width="14%">Vendor</td>
				      <td width="15%">Style</td>
				      <td width="15%">Season</td>
				      <td width="10%">Quantity</td>
				      <td width="17%">ETD Required</td>
				    </tr>

				    <tr class="evenRow" valign="top">
				      <td><?= $sOrderNo ?></td>
				      <td><?= $sBrand ?></td>
				      <td><?= $sVendor ?></td>
				      <td><?// str_replace(", ", "<br />", $sStyle) ?>
                                          <table width="100%">
<?
                                        $iStylesList = explode(",", $iStyles);                                        
                                        foreach($iStylesList as $iPoStyle)
                                        {
                                            $sStyle = getDbValue('Style', 'tbl_styles', "id='$iPoStyle'");
?>
                                              <tr><td><?= $sStyle ?></td><td>&nbsp;</td><td><a href="sampling/view-style-specs.php?Id=<?= $iPoStyle ?>&Brand=<?=$iBrand?>" class="lightview" rel="iframe" title="Style : <?= $sStyle ?> :: :: width: 850, height: 520"><img src="images/icons/view.gif" width="16" height="16" hspace="2" alt="View Style Specs" title="View Style Specs" /></a></td></tr>
<?
                                        }
?>
                                          </table>
                                      </td>
				      <td><?= str_replace(", ", "<br />", $sSeason) ?></td>
				      <td><?= $iQuantity ?></td>
				      <td><?= str_replace(", ", "<br />", $sEtdRequired) ?></td>
				    </tr>
				  </table>
			    </div>

			    <br />
	
<?
	$sSQL = "SELECT size,
	                (SELECT COALESCE(SUM(quantity), 0) FROM tbl_po_quantities WHERE po_id='$PoId' AND size_id=tbl_sizes.id) AS _Quantity
	         FROM tbl_sizes
	         WHERE id IN ($sSizes)
	         ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
			    <div class="tblSheet">
			      <h2 style="margin:0px 1px 0px 0px;">PO Size wise Breakdown</h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	for ($i = 0; $i < $iCount;)
	{
?>
				    <tr class="headerRow" style="background:#aaaaaa;">
<?
		for ($j = 0; ($j < 8 && ($i + $j) < $iCount); $j ++)
		{
?>
				      <td width="104"><?= $objDb->getField(($i + $j), 'size') ?></td>
<?
		}
?>
				      <td></td>
					</tr>

				    <tr class="evenRow" valign="top">
<?
		for ($j = 0; ($j < 8 && $i < $iCount); $j ++, $i ++)
		{
?>
				      <td width="<?= @round((100 / $iCount), 2) ?>%"><?= $objDb->getField($i, '_Quantity') ?></td>
<?
		}
?>
				      <td></td>
					</tr>
<?
	}
?>
				  </table>
			    </div>

			    <br />	
				

			    <!-- ETD & FAD Revisions -->
			    <table width="100%" cellspacing="0" cellpadding="0" border="0">
			      <tr valign="top">
			        <td width="50%">
					  <div class="tblSheet" style="margin-right:2px;">
					    <h2 style="margin:0px 1px 0px 0px;">ETD Revisions</h2>

					    <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
						  <tr class="headerRow" style="background:#aaaaaa;">
						    <td width="30%"><b>Revised</b></td>
						    <td width="30%"><b>Original</b></td>
						    <td width="40%"><b>Date / Time</b></td>
						  </tr>
<?
		$sSQL = "SELECT original, revised, date_time FROM tbl_etd_revisions WHERE po_id='$PoId' ORDER BY id DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sOriginal = $objDb->getField($i, 0);
			$sRevised  = $objDb->getField($i, 1);
			$sDateTime = $objDb->getField($i, 2);
?>
						  <tr class="<?= $sClass[($i % 2)] ?>">
						    <td><?= formatDate($sRevised) ?></td>
						    <td><?= formatDate($sOriginal) ?></td>
						    <td><?= formatDate($sDateTime, "d-M-Y H:i A") ?></td>
						  </tr>
<?
		}

		if ($iCount == 0)
		{
?>
						  <tr>
						    <td colspan="3">No ETD Revision Found!</td>
						  </tr>
<?
		}
?>
					    </table>
					  </div>
					</td>

			        <td width="50%">
					  <div class="tblSheet" style="margin-left:2px;">
					    <h2 style="margin:0px 1px 0px 0px;">Final Audit Date Revisions</h2>

					    <table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
						  <tr class="headerRow" style="background:#aaaaaa;">
						    <td width="30%"><b>Revised</b></td>
						    <td width="30%"><b>Original</b></td>
						    <td width="40%"><b>Date / Time</b></td>
						  </tr>
<?
		$sSQL = "SELECT original, revised, date_time FROM tbl_fad_revisions WHERE po_id='$PoId' ORDER BY id DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sOriginal = $objDb->getField($i, 0);
			$sRevised  = $objDb->getField($i, 1);
			$sDateTime = $objDb->getField($i, 2);
?>
						  <tr class="<?= $sClass[($i % 2)] ?>">
						    <td><?= formatDate($sRevised) ?></td>
						    <td><?= formatDate($sOriginal) ?></td>
						    <td><?= formatDate($sDateTime, "d-M-Y H:i A") ?></td>
						  </tr>
<?
		}

		if ($iCount == 0)
		{
?>
						  <tr>
						    <td colspan="3">No Final Audit Date Revision Found!</td>
						  </tr>
<?
		}
?>
					    </table>
					  </div>
					</td>
				  </tr>
				</table>

				<br />


			    <!-- Sampling Summary -->
			    <div class="tblSheet">
			      <h2 style="margin:0px 1px 0px 0px;">Sampling Summary</h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow" style="background:#aaaaaa;">
				      <td width="15%">Code</td>
				      <td width="20%">Sample Type</td>
				      <td width="15%">Request Date</td>
				      <td width="15%">Status</td>
				      <td width="15%">Report Date</td>
				      <td width="20%">Specs Report</td>
				    </tr>
<?
		$sSQL = "SELECT id, sent_2_sampling, `status`,
						(SELECT type FROM tbl_sampling_types WHERE id=tbl_merchandisings.sample_type_id) AS _SampleType
				 FROM tbl_merchandisings
				 WHERE style_id IN ($iStyles)";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId         = $objDb->getField($i, 'id');
			$sRequested  = $objDb->getField($i, "sent_2_sampling");
			$sSampleType = $objDb->getField($i, "_SampleType");
			$sStatus     = $objDb->getField($i, "status");


			$sSQL = "SELECT created FROM tbl_comment_sheets WHERE merchandising_id='$iId'";
			$objDb2->query($sSQL);

			$sCreated = $objDb2->getField(0, "created");


			@list($sYear, $sMonth, $sDay) = @explode("-", $sCreated);

			$sCode = ("M".str_pad($iId, 6, '0', STR_PAD_LEFT));

			$sPictures = @glob($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sCode."_*.*");
			$sPictures = @array_map("strtoupper", $sPictures);
			$sPictures = @array_unique($sPictures);
?>

				    <tr class="evenRow" valign="top">
				      <td><?= $sCode ?></td>
				      <td><?= $sSampleType ?></td>
				      <td><?= formatDate($sRequested) ?></td>
				      <td><?= (($sStatus == "A") ? "Approved" : (($sStatus == "R") ? "Rejected" : "Waiting")) ?></td>
				      <td><?= formatDate($sCreated) ?></td>

				      <td>
<?
			if (checkUserRights("measurements-report.php", "Reports", "view") && formatDate($sCreated) != "")
			{
?>
				        <a href="reports/export-measurements-report.php?Id=<?= $iId ?>"><img src="images/icons/report.gif" width="16" height="16" alt="Measurements Report" title="Measurements Report" align="absmiddle" /> Measurements</a>
				        &nbsp;
<?
			}

			for ($j = 0; $j < count($sPictures); $j ++)
			{
?>
						<span style="visibility:<?= (($j == 0) ? 'visible' : 'hidden') ?>;"><a href="<?= SAMPLING_PICS_DIR.$sPictures[$j] ?>" class="lightview" rel="gallery[set<?= $iId ?>]"><img src="images/icons/pictures.gif" width="16" height="16" alt="Pictures" title="Pictures" align="absmiddle" /> Pictures</a></span>
<?
			}
?>
				      </td>
				    </tr>
<?
		}
?>
				  </table>
			    </div>

			    <br />

				<!-- Quonda Summary -->
			    <div class="tblSheet">
			      <h2 style="margin:0px 1px 0px 0px;">Quality Summary</h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow" style="background:#aaaaaa;">
				      <td width="8%">#</td>
				      <td width="12%">Audit Code</td>
				      <td width="12%">Stage</td>
				      <td width="12%">Type</td>
				      <td width="12%">Result</td>
				      <td width="12%">Audit Date</td>
				      <td width="12%">Quantity</td>
				      <td width="12%">Defects</td>
				      <td width="8%">DHU</td>
				    </tr>
<?
		$sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
		$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
		$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");
		$sAuditCodes      = array( );


		$sSQL = "SELECT *
		         FROM tbl_qa_reports
		         WHERE (po_id='$PoId' OR FIND_IN_SET('$PoId', additional_pos))
		               AND vendor_id IN ({$_SESSION['Vendors']})
		               AND FIND_IN_SET(report_id, '$sReportTypes')
		               AND FIND_IN_SET(audit_stage, '$sAuditStages')
		         ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iAuditId     = $objDb->getField($i, 'id');
			$iPoId        = $objDb->getField($i, 'po_id');
			$iReportId    = $objDb->getField($i, "report_id");
			$sAuditCode   = $objDb->getField($i, 'audit_code');
			$sAuditStage  = $objDb->getField($i, 'audit_stage');
			$sAuditType   = $objDb->getField($i, "audit_type");
			$sAuditResult = $objDb->getField($i, 'audit_result');
			$sAuditDate   = $objDb->getField($i, 'audit_date');
			$fDhu         = $objDb->getField($i, 'dhu');

			$sAuditCodes[] = $sAuditCode;

			switch ($sAuditResult)
			{
				case "P" : $sAuditResult = "Pass"; break;
				case "F" : $sAuditResult = "Fail"; break;
				case "H" : $sAuditResult = "Hold"; break;
			}

			switch ($sAuditType)
			{
				case "B"  : $sAuditType  = "Bulk"; break;
				case "BG" : $sAuditType = "B-Grade"; break;
				case "SS" : $sAuditType = "Sales Sample"; break;
			}

			if ($iReportId == 6)
			{
				$sSQL = "SELECT SUM(actual_1 + actual_2 + actual_3) FROM tbl_gf_rolls_info WHERE audit_id='$iAuditId'";
				$objDb2->query($sSQL);

				$iQuantity = $objDb2->getField(0, 0);


				$sSQL = "SELECT SUM(defects) FROM tbl_gf_report_defects WHERE audit_id='$iAuditId'";
				$objDb2->query($sSQL);

				$iDefects = $objDb2->getField(0, 0);
			}

			else
			{
				$iQuantity = $objDb->getField($i, "total_gmts");
				$iDefects  = $objDb->getField($i, "defective_gmts");
			}
?>
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($i + 1) ?></td>
<?
			if (checkUserRights("qa-reports.php", "Quonda", "view"))
			{
?>
				      <td>
				        <a href="quonda/qa-reports.php?AuditCode=<?= $sAuditCode ?>" target="_blank"><?= $sAuditCode ?></a>
<?
				if (@in_array($iReportId, array(1, 2, 4, 5, 6, 7, 8, 9, 10, 11))  || @in_array($iBrand, array(32, 87, 119, 120, 121)))
				{
?>
				        <a href="quonda/export-qa-report.php?Id=<?= $iAuditId ?>&ReportId=<?= $iReportId ?>&Brand=<?= $iBrand ?>&AuditStage=<?= $sAuditStage ?>"><img src="images/icons/pdf.gif" width="16" height="16" align="right" alt="QA Report" title="QA Report" /></a>
<?
				}
?>
				      </td>
<?
			}

			else
			{
?>
				      <td><?= $sAuditCode ?></td>
<?
			}
?>

				      <td><?= $sAuditStagesList[$sAuditStage] ?></td>
				      <td><?= $sAuditType ?></td>
				      <td><?= $sAuditResult ?></td>
				      <td><?= formatDate($sAuditDate) ?></td>
				      <td><?= $iQuantity ?></td>
				      <td><?= $iDefects ?></td>
				      <td><?= $fDhu ?>%</td>
				    </tr>
<?
		}

		if ($iCount == 0)
		{
?>
				    <tr>
					  <td colspan="9">No Audit Record Found!</td>
				    </tr>
<?
		}
?>
				  </table>
			    </div>
<?
		if (count($sAuditCodes) > 0)
		{
			@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

			$sPictures = array( );

			for ($i = 0; $i < count($sAuditCodes); $i ++)
			{
				$sAuditCode = $sAuditCodes[$i];

				$sAuditPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*");
				$sAuditPictures = @array_map("strtoupper", $sAuditPictures);
				$sAuditPictures = @array_unique($sAuditPictures);

				$sPictures = @array_merge($sPictures, $sAuditPictures);
			}


			$sTemp = array( );

			foreach ($sPictures as $sPicture)
				$sTemp[] = $sPicture;

			$sPictures = $sTemp;
?>
			    <br />

			    <div class="tblSheet">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    			<tr>
<?
			if (count($sPictures) > 6)
			{
?>
	    			  <td width="74" align="center"><img src="images/icons/back-arrow.gif" width="48" height="48" alt="Previous" title="Previous" onclick="objQuondaGlider.previous( ); return false;" style="cursor:pointer;" /></td>
<?
			}
?>
	    			  <td>
			            <div style="overflow:hidden; padding:5px 0px 5px 0px;">

						<div id="QuondaGlider">
						  <div class="scroller">
							<div class="content">
<?
			for ($i = 0; $i < count($sPictures); $i ++)
			{
				$sName       = @strtoupper($sPictures[$i]);
				$sName       = @basename($sName, ".JPG");
				$sName       = @basename($sName, ".GIF");
				$sName       = @basename($sName, ".PNG");
				$sName       = @basename($sName, ".BMP");
				$sParts      = @explode("_", $sName);
				$sDefectCode = intval($sParts[1]);
				$sAreaCode   = intval($sParts[2]);
				$bFlag       = true;

				$sSQL = "SELECT report_id,
								(SELECT vendor FROM tbl_vendors WHERE id=qa.vendor_id) AS _Vendor,
								(SELECT order_no FROM tbl_po WHERE id=qa.po_id) AS _PO,
								(SELECT style FROM tbl_styles WHERE id=(SELECT style_id FROM tbl_po_colors WHERE po_id=qa.po_id LIMIT 1)) AS _Style,
								(SELECT line FROM tbl_lines WHERE id=qa.line_id) AS _Line
						 FROM tbl_qa_reports qa
						 WHERE audit_code='{$sParts[0]}'";

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
?>
			                  <div class="section" id="section<?= $i ?>">
								<div class="qaPoPic">
								  <div><a href="<?= $sPictures[$i] ?>" class="lightview" rel="gallery[defects]" title="<?= $sTitle ?> :: :: topclose: true"><img src="<?= $sPictures[$i] ?>" alt="" title="" /></a></div>
								</div>

								<span<?= (($bFlag == true) ? '' : ' style="color:#ff0000;"') ?>><?= @strtoupper($sName) ?></span><br />
							  </div>
<?
			}
?>
							</div>
						  </div>
						</div>

						<script type="text/javascript">
						<!--
							var objQuondaGlider = new Glider('QuondaGlider', { duration:1.0, maxDisplay:6 });
						-->
						</script>
<?
			if (count($sPictures) > 6)
			{
?>
	    			  <td width="74" align="center"><img src="images/icons/next-arrow.gif" width="48" height="48" alt="Next" title="Next" onclick="objQuondaGlider.next( ); return false;" style="cursor:pointer;" /></td>
<?
			}
?>
	    			</tr>
	    		  </table>
			    </div>
<?
		}
?>

			    <br />

				<!-- Shipping Summary -->
			    <div class="tblSheet">
			      <h2 style="margin:0px 1px 0px 0px;">Shipping Summary</h2>
<?
		$sSQL = "SELECT *,
		                (SELECT terms FROM tbl_terms_of_delivery WHERE id=tbl_pre_shipment_detail.terms_of_delivery_id) AS _TermsOfDelivery,
		                (SELECT quantity FROM tbl_pre_shipment_advice WHERE po_id=tbl_pre_shipment_detail.po_id) AS _Quantity
		         FROM tbl_pre_shipment_detail
		         WHERE po_id='$PoId'
		         ORDER BY id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 1)
		{
?>
			      <h4 style="margin:10px 10px 5px 10px;">Shipment # 1</h4>
<?
		}
?>
			      <table width="98%" cellspacing="0" cellpadding="4" border="0" align="center">
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
			$sTermsOfPayment      = $objDb->getField($i, "terms_of_payment");
			$sTermsOfDelivery     = $objDb->getField($i, "_TermsOfDelivery");
			$sHandoverToForwarder = formatDate($objDb->getField($i, "handover_to_forwarder"));
			$sShippingDate        = formatDate($objDb->getField($i, "shipping_date"));
			$sInvoiceNo           = $objDb->getField($i, "invoice_no");
			$sLadingAirwayBill    = $objDb->getField($i, "lading_airway_bill");
			$iQuantity            = $objDb->getField($i, "_Quantity");
			$sInvoicePackingList  = $objDb->getField($i, 'invoice_packing_list');
?>
				    <tr>
				      <td width="150">
<?
			if ($sInvoiceNo != "" && checkUserRights("invoice-report.php", "Reports", "view"))
			{
?>
				        <a href="reports/export-invoice-report.php?Invoice=<?= urlencode($sInvoiceNo) ?>"><img src="images/icons/report.gif" width="16" height="16" alt="Invoice Report" title="Invoice Report" align="absmiddle" /> Invoice Report</a>
<?
			}
?>
				      </td>

				      <td width="20"></td>

				      <td>
<?
			if ($sInvoiceNo != "" && checkUserRights("inspection-certificate.php", "Reports", "view"))
			{
?>
				        <a href="reports/export-inspection-certificate.php?Invoice=<?= urlencode($sInvoiceNo) ?>&PoId=<?= $PoId ?>"><img src="images/icons/certificate.gif" width="16" height="16" alt="Inspection Certificate" title="Inspection Certificate" align="absmiddle" /> Inspection Certificate</a>
<?
			}
?>
				      </td>

				      <td width="150">
<?
			if ($sInvoicePackingList != "" && @file_exists($sBaseDir.PRE_SHIPMENT_DIR.$sInvoicePackingList))
			{
?>
				        <a href="<?= PRE_SHIPMENT_DIR.$sInvoicePackingList ?>" class="lightview pdf" title="Invoice Packing List [PO # <?= $sOrderNo ?>] :: :: width: 800, height: 600"><img src="images/icons/pdf.gif" width="16" height="16" alt="Invoice Packing List" title="Invoice Packing List" align="absmiddle" /> Invoice Packing List</a>
<?
			}
?>
				      </td>

				      <td width="20"></td>

				      <td>
<?
			if ($iQuantity > 0 && checkUserRights("pre-shipment-advice.php", "Shipping", "view"))
			{
?>
				        <a href="shipping/view-shipment-deviation.php?Id=<?= $PoId ?>" class="lightview" rel="iframe" title="Deviation [PO # <?= $sOrderNo ?>] :: :: width: 700, height: 450"><img src="images/icons/deviation.gif" width="16" height="16" alt="Deviation" title="Deviation" align="absmiddle" /> Deviation</a>
<?
			}
?>
				      </td>
				    </tr>

				    <tr>
				      <td width="150">Terms of Payment</td>
				      <td width="20" align="center">:</td>
				      <td><?= $sTermsOfPayment ?></td>
				      <td width="150">Terms of Delivery</td>
				      <td width="20" align="center">:</td>
				      <td><?= $sTermsOfDelivery ?></td>
				    </tr>

				    <tr>
				      <td>Handover to Forwarder</td>
				      <td align="center">:</td>
				      <td><?= $sHandoverToForwarder ?></td>
				      <td>Shipping Date</td>
				      <td align="center">:</td>
				      <td><?= $sShippingDate ?></td>
				    </tr>

				    <tr>
				      <td>Vendor Invoice #</td>
				      <td align="center">:</td>
				      <td><?= $sInvoiceNo ?></td>
				      <td>Bill of Lading / Airway Bill</td>
				      <td align="center">:</td>
				      <td><?= $sLadingAirwayBill ?></td>
				    </tr>

				    <tr>
				      <td>Quantity</td>
				      <td align="center">:</td>
				      <td colspan="4"><?= $iQuantity ?></td>
				    </tr>
<?
			if ($i < ($iCount - 1))
			{
?>
				    <tr>
					  <td colspan="6"><h4 style="margin-top:10px;">Shipment # <?= ($i + 2) ?></h4></td>
				    </tr>
<?
			}
		}

		if ($iCount == 0)
		{
?>
				    <tr>
					  <td colspan="6">No Shipping Record Found!</td>
				    </tr>
<?
		}
?>
				  </table>
			    </div>

			    <br />
				
<?
	$fOrderValue   = getDbValue("SUM((price * order_qty))", "tbl_po_colors", "po_id='$PoId'");
	$fInvoiceValue = $fOrderValue;
	$iParent       = getDbValue("parent_id", "tbl_brands", "id='$iBrand'");
	
	if ($iParent == 193 || $iParent == 235)
		$fInvoiceValue -= 470;
?>
			    <div class="tblSheet">
			      <h2 style="margin:0px 1px 0px 0px;">Invoice Verification</h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow" style="background:#aaaaaa;">
				      <td width="16%">Order Value</td>
				      <td width="16%">Invoice Value</td>
				      <td width="16%">HS Code</td>
				      <td width="16%">Article Number</td>
				      <td width="36%">Shipping Window</td>
				    </tr>

				    <tr class="evenRow" valign="top">
				      <td><?= $sCurrency ?> <?= formatNumber($fOrderValue) ?></td>
				      <td><?= $sCurrency ?> <?= formatNumber($fInvoiceValue) ?></td>
				      <td><?= $sHsCode ?></td>
				      <td><?= $sStyle ?></td>
				      <td><b>From:</b> <?= (($sShippingFromDate == "0000-00-00") ? "N/A" : formatDate($sShippingFromDate)) ?> - <b>To:</b> <?= (($sShippingToDate == "0000-00-00") ? "N/A" : formatDate($sShippingToDate)) ?></td>
				    </tr>
				  </table>
				  
<?
	if ($sCartonLabeling != "" && @file_exists(PO_DOCS_DIR.$sCartonLabeling))
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow" style="background:#aaaaaa;">
				      <td width="100%">Carton Labeling</td>
				    </tr>

				    <tr class="evenRow">
				      <td><img src="<?= (PO_DOCS_DIR.$sCartonLabeling) ?>" style="max-width:100%;" /></td>
				    </tr>
				  </table>
<?
	}
	
	
	if ($sCartonInstructions != "")
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow" style="background:#aaaaaa;">
				      <td width="100%">Packing/Carton Instructions</td>
				    </tr>

				    <tr class="evenRow">
				      <td><?= nl2br($sCartonInstructions) ?></td>
				    </tr>
				  </table>
<?
	}
?>
			    </div>

			    <br />				

<?
		$sSQL = "SELECT COALESCE(SUM(pq.quantity), 0)
		         FROM tbl_po_quantities pq, tbl_po_colors pc
		         WHERE pc.po_id=pq.po_id AND pq.color_id=pc.id AND pc.po_id='$PoId' AND pc.etd_required <= CURDATE( )";
		$objDb->query($sSQL);

		$iOrderQty = $objDb->getField(0, 0);


		$sSQL = "SELECT COALESCE(SUM(psq.quantity), 0)
				 FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
				 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id
				 AND po.id='$PoId' AND psd.handover_to_forwarder != '0000-00-00' AND NOT ISNULL(psd.handover_to_forwarder) AND pc.etd_required <= CURDATE( )
				 AND IF ( po.brand_id='32', (psd.handover_to_forwarder <= DATE_ADD(pc.etd_required, INTERVAL 2 DAY)),  (psd.handover_to_forwarder <= pc.etd_required) )";
		$objDb->query($sSQL);

		$iOnTimeQty = $objDb->getField(0, 0);


		$fOtp = @round((($iOnTimeQty / $iOrderQty) * 100), 2);
		$fOtp = (($fOtp > 100) ? 100 : $fOtp);



		$sSQL = "SELECT SUM(pq.quantity), SUM(psq.quantity)
		         FROM tbl_po_quantities pq, tbl_pre_shipment_quantities psq, tbl_po_colors pc
		         WHERE pq.color_id=pc.id AND psq.color_id=pc.id AND pq.po_id=pc.po_id AND psq.po_id=pc.po_id AND pc.po_id='$PoId' AND pc.etd_required <= CURDATE( )";
		$objDb->query($sSQL);

		$iOrderQty = $objDb->getField(0, 0);
		$iShipQty  = $objDb->getField(0, 1);

		$fDeviation = @round((@(($iShipQty / $iOrderQty) * 100) - 100), 2);
?>
			    <!-- VSR Summary -->
			    <div class="tblSheet">
			      <h2 style="margin:0px 1px 0px 0px;">VSR Summary</h2>

			      <table width="98%" cellspacing="0" cellpadding="4" border="0" align="center">
				    <tr>
				      <td width="150">OTP</td>
				      <td width="20" align="center">:</td>
				      <td><?= $fOtp ?>%</td>
				    </tr>

				    <tr>
				      <td>Deviation</td>
				      <td align="center">:</td>
				      <td><?= $fDeviation ?>%</td>
				    </tr>
				  </table>
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
	$objSpDb->close( );

	@ob_end_flush( );
?>