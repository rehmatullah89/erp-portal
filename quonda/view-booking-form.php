<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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
	**   Project Developer:                                                                      **
	**                                                                                           **
	**      Name  :  Rehmatullah Bhatti                                                          **
	**      Email :  rehmatullahbhatti@gmail.com                                                 **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id = IO::intValue('Id');

	$sSQL = "SELECT *
                    FROM tbl_bookings
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
                $iBrand          	= $objDb->getField(0,"brand_id");
                $iFactory        	= $objDb->getField(0,"factory_id");
                $iSupplier         	= $objDb->getField(0,"supplier_id");
                $sArticle        	= $objDb->getField(0,"article");
                $sIan        		= $objDb->getField(0,"ian");
                $iLotSize        	= $objDb->getField(0,"quantity");
                $iShipments      	= $objDb->getField(0,"shipments");
                $sReqInspectionDate     = $objDb->getField(0,"inspection_date");
                $iService  		= $objDb->getField(0,"service_id");
                $sSamplePickFor         = $objDb->getField(0,"sample_for");
                $sPorts                 = $objDb->getField(0,"ports");
                $sRemarks      		= $objDb->getField(0,"notes");
                $sReTest      		= $objDb->getField(0,"re_test");
                $sShippingDate		= $objDb->getField(0,"shipping_date");
                $sStatus 		= $objDb->getField(0,"status");
	}

	$sSuppliersList  = getList("tbl_suppliers", "id", "supplier");
	$sVendorsList    = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList     = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
        $sShippingPorts  = getList("tbl_shipping_ports", "id", "port_name", "booking_form='Y'");
        $sStages         = explode(",", getDbValue("stages", "tbl_reports", "id='39'"));
        $sServicesList   = getList("tbl_audit_stages", "id", "stage", "code IN ('". implode("', '", $sStages)."')");
        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:544px; height:544px;">
	  <h2>Booking Form</h2>

          <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="50%" valign="top">
				<table border="0" cellpadding="3" cellspacing="0" width="100%">

                                    <tr>
                                      <td width="160">Supplier<span class="mandatory">*</span></td>
					<td  width="20" align="center">:</td>

					<td>
                                            <?=$sSuppliersList[$iSupplier]?>
					</td>
				  </tr>

                                  <tr>
					<td>Buyer Name<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
                                            <?=$sBrandsList[$iBrand]?>
					</td>
				  </tr>
                                    
                                    <tr>
					<td>Factory Name<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
                                            <?=$sVendorsList[$iFactory]?>
					</td>
				  </tr>

                                  <tr>
					<td>Article Description<span class="mandatory">*</span></td>
					<td align="center">:</td>
                                        <td>
                                            <?=$sArticle?>
                                        </td>
                                  </tr>

                                  <tr>
					<td>IAN<span class="mandatory">*</span></td>
					<td align="center">:</td>
                                        <td>
                                            <?=$sIan?>
                                        </td>
                                  </tr>

                                  <tr>
					<td>Quantity of Shipment/ Lot<span class="mandatory">*</span></td>
					<td align="center">:</td>
                                        <td>
                                            <?=$iLotSize?>
                                        </td>
                                  </tr>

                                  <tr>
					<td>Number of Shipments</td>
					<td align="center">:</td>
                                        <td>
                                            <?=$iShipments?>
                                        </td>
                                  </tr>                                    
				</table>
                                        </td>
                                        <td valign="top" width="50%">
                                            <table border="0" cellpadding="3" cellspacing="0" width="100%" >
                                                    
                                                    <tr>
                                                        <td width="170">Requested Inspection Date<span class="mandatory">*</span></td>
                                                        <td width="20" align="center">:</td>

                                                        <td>
                                                          <?=$sReqInspectionDate?>
                                                        </td>
                                                    </tr>
                                                
                                                     <tr>
                                                        <td width="170">Planned Shipping Date<span class="mandatory">*</span></td>
                                                        <td width="20" align="center">:</td>

                                                        <td>
                                                          <?=$sShippingDate?>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>Sample Pick<span class="mandatory">*</span></td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <?=$sSamplePickFor?>
                                                        </td>
                                                    </tr>
                                                
                                                
                                                    <tr>
                                                        <td>Services<span class="mandatory">*</span></td>
                                                        <td align="center">:</td>

                                                        <td>
                                                            <?=$sServicesList[$iService]?>
                                                        </td>
                                                    </tr>
                                                
                                                <tr>
                                                    <td>Ports<span class="mandatory">*</span></td>
                                                    <td align="center">:</td>

                                                    <td>
                                                        <?
                                                        $iPorts = explode(",", $sPorts);
                                                        foreach($iPorts as $iPort)
                                                            echo $sShippingPorts[$iPort].",";
                                                        ?>
                                                    </td>

                                                </tr>
                                                    <tr>
                                                        <td>Notes<span class="mandatory">*</span></td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <?=$sRemarks?>
                                                        </td>
                                                    </tr>        
                                                 <tr>
                                                        <td>Status<span class="mandatory">*</span></td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <?=($sStatus == 'R')?'Rejected':($sStatus == 'A'?'Approved':'Pending')?>
                                                        </td>
                                                    </tr>        
                                            </table>
                                        </td>
                                    </tr>
                                </table><br/>
          <h2>Attachments</h2>
           <table border="0" cellpadding="3" cellspacing="0" width="100%">
                <?
                    $Counter = 1;
                    $BookingAttachments = getList("tbl_booking_files", "id", "file", "booking_id = '$Id'");
                    @list($sYear, $sMonth, $sDay) = @explode("-", $sReqInspectionDate);

                    foreach($BookingAttachments as $iAttachment => $sAttachment)
                    {
                        if ($sAttachment != "" && @file_exists($sBaseDir.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment))
                        {
?>
               <tr><td width="50"><?=$Counter?></td><td width="20">:</td><td>
                       <?
                        $exts = explode('.', $sAttachment);
                        $extension = end($exts);
                       if(@in_array(strtolower($extension), array('jpg','jpeg','gif','png')))
                       {
?>
                            <a class="lightview" href="<?=SITE_URL.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment?>"><?=$sAttachment?>
                            </a></td></tr>
<?
                       }else
                       {
                       ?>
                            <a  target="_blank" href="<?=SITE_URL.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment?>"><?=$sAttachment?></a></td></tr>
<?
                       }
                            $Counter++;
                        }
                    }
                ?>
          </table>
	  <br style="line-height:2px;" />
    </div>
<!--  Body Section Ends Here  -->

  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>