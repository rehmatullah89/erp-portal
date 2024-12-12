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

	$HohIoNo    = IO::strValue("Po");
	$BookingNo  = IO::strValue("BookingNo");
	$PoId       = IO::intValue("PoId");
        
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
			    <h1><img src="images/h1/po-status.jpg" vspace="10" alt="" title="" /></h1>


			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="150">Hoh Internal Order No</td>
			          <td width="180"><input type="text" name="Po" value="<?= $HohIoNo ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td width="75">Booking No</td>
			          <td width="150"><input type="text" name="BookingNo" value="<?= $BookingNo ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>
<?
	if ($HohIoNo != "" || $BookingNo != "")
	{
		
                if($HohIoNo != "")
                    $sSQL = "SELECT id, inspection_date,
                                (SELECT GROUP_CONCAT(service SEPARATOR ',') FROM tbl_audit_services WHERE id IN (tbl_bookings.services)) as _Services,
                                (SELECT brand from tbl_brands WHERE id=tbl_bookings.brand_id) as _Client,
                                (SELECT supplier from tbl_suppliers WHERE id=tbl_bookings.supplier_id) as _Supplier,
                                (SELECT vendor from tbl_vendors WHERE id=tbl_bookings.factory_id) as _Factory
                                From tbl_bookings WHERE id IN (SELECT booking_id from tbl_qa_reports WHERE hoh_order_no LIKE '$HohIoNo')";
                else
                {
                    if (strpos($BookingNo, 'B') !== false || strpos($BookingNo, 'b') !== false)
                           $BookingNo = (int)(str_replace(['B','b'],"",$BookingNo));
                    
                    $HohIoNo = getDbValue("hoh_order_no", "tbl_qa_reports", "booking_id='$BookingNo'");
                    
                    $sSQL = "SELECT id, inspection_date,
                                (SELECT GROUP_CONCAT(service SEPARATOR ',') FROM tbl_audit_services WHERE id IN (tbl_bookings.services)) as _Services,  
                                (SELECT brand from tbl_brands WHERE id=tbl_bookings.brand_id) as _Client,
                                (SELECT supplier from tbl_suppliers WHERE id=tbl_bookings.supplier_id) as _Supplier,
                                (SELECT vendor from tbl_vendors WHERE id=tbl_bookings.factory_id) as _Factory
                             From tbl_bookings WHERE id='$BookingNo' AND assigned_to > 0";                    
                }

		$objDb->query($sSQL);
		$iCount = $objDb->getCount( );

                if ($iCount == 0)
		{
?>
			    <div class="tblSheet">
			      <h2>No Booking found!</h2>

			      <div style="padding:15px;">
			        Please provide the correct Booking No to check status.<br /><br />
			      </div>
			    </div>
<?
		}
                
		if ($iCount > 0)
		{
?>
                        <h2 style="margin:0px 1px 0px 0px;"> H.I.O. Bookings Summary </h2>    
                        <div class="tblSheet">    
                        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                            <tr class="headerRow" style="background:#aaaaaa;">
                                <td width="5%">#</td>
                                <td width="10%">Booking No</td>
                                <td width="20%">Client</td>
                                <td width="20%">Factory</td>				      				      
                                <td width="15%">Inspection Date</td>
                                <td width="20%">Services</td>
                                <td width="10%"  style="text-align: center;">Options</td>
                            </tr>
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iBooking       = $objDb->getField($i, 'id');
                                $sServices      = $objDb->getField($i, '_Services');
                                $sClient        = $objDb->getField($i, '_Client');
                                $sSupplier      = $objDb->getField($i, '_Supplier');
                                $sFactory       = $objDb->getField($i, '_Factory');
                                $sInspectionDate= $objDb->getField($i, 'inspection_date');
                                
?>
                            <tr class="evenRow" valign="top">
                                <td><?=$i+1?></td>
                                <td><a href="bookings/bookings.php?BookingId=<?= $iBooking ?>" target="_blank"><?= ("B".str_pad($iBooking, 5, '0', STR_PAD_LEFT)) ?></a></td>
                                <td><?=$sClient?></td>
                                <td><?=$sFactory?></td>
                                <td><?=$sInspectionDate?></td>
                                <td><?=$sServices?></td>
                                <td style="text-align: center;">                                    
                                    <a href="bookings/export-booking-form.php?Id=<?= $iBooking ?>"><img src="images/icons/pdf.gif" width="16" height="16" alt="Booking Form" title="Booking Form" /></a>
                                    <a href="bookings/view-booking-form.php?Id=<?= $iBooking ?>" class="lightview" rel="iframe" title="Booking Form : <?= "B".str_pad($iBooking, 5, '0', STR_PAD_LEFT); ?> :: :: width: 900, height: 650"><img src="images/icons/view.gif" width="16" height="16" hspace="1" alt="View" title="View" /></a>&nbsp;
                                </td>
                            </tr>
<?
                        }
?>
                        </table>
                        </div><br/>
<?
                }
	}

	$iCount = 1;

	if ($HohIoNo != "" && $PoId == 0)
	{
                $sSQL = "SELECT id
                         FROM tbl_hoh_orders
		         WHERE order_no LIKE '%$HohIoNo%'"; 
               
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );


		if ($iCount == 0)
		{
?>
			    <div class="tblSheet">
			      <h2>No Hoh Internal Order found!</h2>

			      <div style="padding:15px;">
			        Please provide the correct Hoh Internal Order number to check the order status.<br /><br />
			      </div>
			    </div>
<?
		}
                
                if ($iCount == 1) 
                    $PoId = $objDb->getField(0, 0);
	}

	else if ($HohIoNo == "" && ($BookingNo == "" || $BookingNo == 0))
	{
?>
			    <div class="tblSheet">
			      <h2>No Record found!</h2>

			      <div style="padding:15px;">
			        Please provide the correct Order No or Booking No to check status.<br /><br />
			      </div>
			    </div>
<?
	}

	if (($PoId > 0 || $HohIoNo != "") && $iCount > 0)
	{
            if($PoId >0 && ($sStyles == "0" || $sStyles == ""))
                $sSubSQL = " AND o.id = '$PoId' ";
            else if ($sStyles != "" && $sStyles != "0")
                $sSubSQL = " AND o.order_no LIKE '%$HohIoNo%' AND od.style_id IN ($sStyles)";
            else
                $sSubSQL = " AND o.order_no LIKE '%$HohIoNo%' ";
            
		$sSQL = "SELECT o.id, osd.id as _StyleDetailId, o.order_no, osd.style_id, od.size_id, od.color, od.ean, od.quantity,
		                (SELECT supplier FROM tbl_suppliers WHERE id=o.supplier_id) AS _Supplier,
                                (SELECT size FROM tbl_sampling_sizes WHERE id=od.size_id) AS _Size,
                                (SELECT concat(style_name,' - ', style) FROM tbl_styles WHERE id=osd.style_id) AS _Style,
                                (SELECT GROUP_CONCAT(season SEPARATOR ', ') FROM tbl_seasons WHERE id IN (SELECT sub_season_id FROM tbl_styles WHERE id IN (osd.style_id)) GROUP BY id) AS _Season,
		                (SELECT vendor FROM tbl_vendors WHERE id=o.vendor_id) AS _Vendor
		         FROM tbl_hoh_orders o, tbl_hoh_order_style_details osd, tbl_hoh_order_details od
		         WHERE o.id=osd.hoh_order_id AND osd.id=od.style_detail_id $sSubSQL";

                $objDb->query($sSQL);
                
                $iCount = $objDb->getCount( );
		
?>
			    <!-- PO Summary -->
			    <div class="tblSheet">
                                <h2 style="margin:0px 1px 0px 0px;">
				    H.I. Order Summary
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
				      <td width="12%">Order No</td>
				      <td width="15%">Supplier</td>
				      <td width="15%">Vendor</td>				      				      
                                      <td width="12%">Style</td>
                                      <td width="8%">Size</td>
                                      <td width="10%">Color</td>
                                      <td width="8%">Quantity</td>
                                      <td width="9%">Season</td>
                                      <td width="11%">Ean</td>
				    </tr>
<?
                for($i=0; $i< $iCount; $i++)
                {
                    $iOrderId       = $objDb->getField($i, 'id');
                    $iStyleDetailId = $objDb->getField($i, '_StyleDetailId');
                    $sOrderNo       = $objDb->getField($i, 'order_no');
                    $iStyle         = $objDb->getField($i, 'style_id');
                    $sStyle         = $objDb->getField($i, '_Style');
                    $iSize          = $objDb->getField($i, 'size_id');
                    $sSize          = $objDb->getField($i, '_Size');
                    $sEan           = $objDb->getField($i, 'ean');
                    $sColor         = $objDb->getField($i, 'color');
                    $sSupplier      = $objDb->getField($i, '_Supplier');
                    $sVendor        = $objDb->getField($i, '_Vendor');
                    $sSeason        = $objDb->getField($i, '_Season');
                    $iQuantity      = $objDb->getField($i, 'quantity');
                    
?>
                    <tr class="evenRow" valign="top">
                        <td><?= $sOrderNo ?></td>
                        <td><?= $sSupplier ?></td>
                        <td><?= $sVendor ?></td>
                        <td>
                            <table width="100%">
                                <tr><td><?= $sStyle ?></td><td width="2">&nbsp;</td><td width="20"><a href="view-hoh-specs.php?Id=<?= $iStyle ?>&OrderId=<?=$iOrderId?>&StyleDetailId=<?=$iStyleDetailId?>&SizeId=<?=$iSize?>" class="lightview" rel="iframe" title="Hoh Internal Order No : <?= $sOrderNo ?> :: :: width: 850, height: 520"><img src="images/icons/view.gif" width="16" height="16" hspace="2" alt="View Style Specs" title="View Style Specs" /></a></td></tr>
                            </table>
                        </td>
                        <td><?= $sSize ?></td>
                        <td><?= $sColor ?></td>
                        <td><?= number_format($iQuantity)?></td>
                        <td><?= str_replace(", ", "<br />", $sSeason) ?></td>
                        <td><?= $sEan ?></td>
                      </tr>
<?
                }

?>
				  </table>
			    </div>
			    <br />
                            
<?
		if ($HohIoNo != "")
		{                    
?>
                            <!-- Quonda Summary -->
			    <div class="tblSheet">
			      <h2 style="margin:0px 1px 0px 0px;">Inspections Summary</h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow" style="background:#aaaaaa;">
				      <td width="5%">#</td>
				      <td width="12%">Audit Code</td>
				      <td width="8%">Stage</td>
                                      <td width="9%">Booking No</td>
                                      <td width="20%">Services</td>
				      <td width="8%">Result</td>
				      <td width="14%">Audit Date</td>
				      <td width="8%">Quantity</td>
				      <td width="8%">Defects</td>
				      <td width="8%">DHU</td>
				    </tr>
<?
		$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
		$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");
		$sAuditCodes      = array( );

                $sAppendSql = "";
                if($BookingNo != "" && $BookingNo != 0)
                    $sAppendSql = " AND booking_id='$BookingNo' ";
                
		$sSQL = "SELECT id, audit_code, audit_stage, audit_result, audit_date, report_id, dhu, total_gmts, defective_gmts, booking_id,
                            (SELECT GROUP_CONCAT(service SEPARATOR ',') from tbl_audit_services s,tbl_bookings b WHERE FIND_IN_SET(s.id, b.services) AND tbl_qa_reports.booking_id = b.id) _Services   
                            FROM tbl_qa_reports
                            WHERE hoh_order_no LIKE '%$HohIoNo%' $sAppendSql AND published='Y' AND audit_result!=''";
               
                $objDb->query($sSQL);
                $iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iAuditId     = $objDb->getField($i, 'id');
			$iReportId    = $objDb->getField($i, "report_id");
			$sAuditCode   = $objDb->getField($i, 'audit_code');
                        $iBooking     = $objDb->getField($i, 'booking_id');
			$sAuditStage  = $objDb->getField($i, 'audit_stage');
			$sAuditResult = $objDb->getField($i, 'audit_result');
			$sAuditDate   = $objDb->getField($i, 'audit_date');
                        $sServices    = $objDb->getField($i, '_Services');
			$fDhu         = $objDb->getField($i, 'dhu');

			$sAuditCodes[] = $sAuditCode;

			switch ($sAuditResult)
			{
				case "P" : $sAuditResult = "Pass"; break;
				case "F" : $sAuditResult = "Fail"; break;
				case "H" : $sAuditResult = "Hold"; break;
			}

                        $iQuantity = $objDb->getField($i, "total_gmts");
                        $iDefects  = $objDb->getField($i, "defective_gmts");
			
?>
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($i + 1) ?></td>
<?
			if (checkUserRights("qa-reports.php", "Quonda", "view"))
			{
?>
				      <td>
				        <a href="quonda/qa-reports.php?AuditCode=<?= $sAuditCode ?>" target="_blank"><?= $sAuditCode ?></a>
				        <a href="quonda/export-qa-report.php?Id=<?= $iAuditId ?>&ReportId=<?= $iReportId ?>&Brand=<?= $iBrand ?>&AuditStage=<?= $sAuditStage ?>"><img src="images/icons/pdf.gif" width="16" height="16" align="right" alt="QA Report" title="QA Report" /></a>
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
                                      <td><a href="bookings/bookings.php?BookingId=<?= $iBooking ?>" target="_blank"><?= ("B".str_pad($iBooking, 5, '0', STR_PAD_LEFT)) ?></a><a href="bookings/export-booking-form.php?Id=<?= $iBooking ?>"><img src="images/icons/pdf.gif" width="16" height="16" align="right" alt="Booking Form" title="Booking Form" /></a></td>
                                      <td><?= $sServices ?></td>
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
		}

		if ($HohIoNo != "")
		{                    
?>
                            <!-- Child Labour Section -->
                            <br/>
			    <div class="tblSheet">
			      <h2 style="margin:0px 1px 0px 0px;">Child Labor Verification Summary</h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow" style="background:#aaaaaa;">
				      <td width="8%">#</td>
				      <td width="17%">Audit Code</td>
                                      <td width="28%">Factory</td>
				      <td width="15%">Child Labor Result</td>
				      <td width="18%">Contact Person</td>
				      <td width="15%">Audit Date</td>
				    </tr>
<?
		$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
		$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");

                $sAppendSql = "";
                if($BookingNo != "" && $BookingNo != 0)
                    $sAppendSql = " AND booking_id='$BookingNo' ";

		$sSQL = "SELECT id, audit_code, audit_date, report_id,
                                (SELECT vendor from tbl_vendors Where id=tbl_qa_reports.vendor_id) as _Vendor,
                                (SELECT child_labour_result from tbl_qa_hohenstein Where audit_id=tbl_qa_reports.id) as _Result,
                                (SELECT child_labour_site_person from tbl_qa_hohenstein Where audit_id=tbl_qa_reports.id) as _ContactPerson
                            FROM tbl_qa_reports
                            WHERE hoh_order_no LIKE '%$HohIoNo%' $sAppendSql AND published='Y' AND audit_result!=''";
               
                $objDb->query($sSQL);
                $iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iAuditId       = $objDb->getField($i, 'id');
			$iReportId      = $objDb->getField($i, "report_id");
			$sAuditCode     = $objDb->getField($i, 'audit_code');
                        $sAuditDate     = $objDb->getField($i, 'audit_date');
			$sVendor        = $objDb->getField($i, '_Vendor');
			$sChildResult   = $objDb->getField($i, '_Result');
                        $sContactPerson = $objDb->getField($i, '_ContactPerson');
			
			$fDhu         = $objDb->getField($i, 'dhu');

			switch ($sChildResult)
			{
				case "P" : $sChildResult = "Pass"; break;
				case "F" : $sChildResult = "Fail"; break;
				default : $sChildResult = "N/A"; break;
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
                                        <a href="quonda/export-qa-report.php?Id=<?= $iAuditId ?>&ReportId=<?= $iReportId ?>&ChildLabor=Y"><img src="images/icons/pdf.gif" width="16" height="16" align="right" alt="QA Report" title="QA Report" /></a>
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

				      <td><?= $sVendor ?></td>
				      <td><?= $sChildResult ?></td>
                                      <td><?= $sContactPerson ?></td>				      
				      <td><?= formatDate($sAuditDate) ?></td>
				    </tr>
<?
		}

		if ($iCount == 0)
		{
?>
				    <tr>
					  <td colspan="9">No Child Labor Record Found!</td>
				    </tr>
<?
		}
?>
				  </table>
			    </div>
<?
		}
?>

			    <br />				
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