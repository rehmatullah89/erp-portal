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
	
	@header("Content-type: text/html; charset=utf-8");

	
	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id    = IO::intValue('Id');
                
        $sSQL = "SELECT tbl_bookings.*,
                    (SELECT brand From tbl_brands Where id=tbl_bookings.brand_id) as _Brand,
                    (SELECT vendor From tbl_vendors Where id=tbl_bookings.vendor_id) as _Vendor,
                    (SELECT name From tbl_users Where id=tbl_bookings.auditor_id) as _Auditor,
                    (SELECT audit_code From tbl_qa_reports Where booking_id=tbl_bookings.id) as _AuditCode
                FROM tbl_bookings WHERE id='$Id'";
        
        $objDb->query($sSQL);

        $sBookingCode           = "B".str_pad($Id, 5, 0, STR_PAD_LEFT);
        $sAuditCode             = $objDb->getField(0,"_AuditCode");
        $iBrand                 = $objDb->getField(0,"brand_id");
        $iVendor                = $objDb->getField(0,"vendor_id");
        $sBrand                 = $objDb->getField(0,"_Brand");
        $sVendor                = $objDb->getField(0,"_Vendor");
        $sAuditor               = $objDb->getField(0,"_Auditor");
        $sAuditDate             = $objDb->getField(0,"inspection_date");
        $sStartTime             = $objDb->getField(0,"start_time");
        $sEndTime               = $objDb->getField(0,"end_time");
        $iStyleId               = $objDb->getField(0,"style_id");
        $sPos                   = $objDb->getField(0,"pos");
        $sColors                = $objDb->getField(0,"colors");
        $sCommissions           = $objDb->getField(0,"commissions");
        $sSizes                 = $objDb->getField(0,"sizes");
        $iSampleSize            = $objDb->getField(0,"sample_size");
        $iApprovedBy            = $objDb->getField(0,"approved_by");            
        $sApprovedAt            = $objDb->getField(0,"approved_at");
        $iAssignedBy            = $objDb->getField(0,"assigned_at");
        $sAssignedAt            = $objDb->getField(0,"assigned_by");
        $iRejectedBy            = $objDb->getField(0,"rejected_at");
        $sRejectedAt            = $objDb->getField(0,"rejected_by");
        $sStatus                = $objDb->getField(0,"status");
        
        switch ($sStatus)
        {
            case "A" : $sStatus = "Approved"; break;
            case "C" : $sStatus = "Cancelled/ Rejected"; break;
            case "P" : $sStatus = "Set to Pending"; break;
        }
		

		
	$sSQL = "SELECT SUM(pq.quantity)
	         FROM tbl_po_colors pc, tbl_po_quantities pq
			 WHERE pc.po_id=pq.po_id AND pc.style_id='$iStyleId' AND pc.id=pq.color_id AND pc.po_id IN ($sPos) AND FIND_IN_SET(pc.color, '$sColors') AND pq.size_id IN ($sSizes)";
			 
	if ($sCommissions != "")
		$sSQL .= " AND FIND_IN_SET(pc.line, '$sCommissions') ";
        else
            $sCommissions = "No commission number selected.";
	
	$objDb->query($sSQL);

	$iQuantity = $objDb->getField(0, 0);	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
	<style>
	.evenRow {
		background: #f6f4f5 none repeat scroll 0 0;
	}
	.oddRow {
		background: #dcdcdc none repeat scroll 0 0;
	}

	#Mytable tr:nth-child(even){
	   background-color: #f2f2f2
	}
		
	#Mytable2 {
	   font-size: 9px;
	}
	</style>    
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:544px; height:544px;">
	  <h2>Booking Details</h2>

	  <table border="0" cellpadding="5" cellspacing="2" width="100%">
	    	      <tr class="evenRow">
                          <td width="100">Booking Code</td>
                        <td  width="20" align="center">:</td>
                        <td><?= $sBookingCode ?></td>
                      </tr>
<?
            if($sAuditCode != "")
            {
?>              
                      <tr class="evenRow">
                        <td>Audit Code</td>
                        <td align="center">:</td>
                        <td><?= $sAuditCode ?></td>
                      </tr>    
<?
            }
            
            if($sAuditor != "")
            {
?>
                     <tr class="evenRow">
                        <td>Auditor</td>
                        <td align="center">:</td>
                        <td><?= $sAuditor ?></td>
                      </tr>
<?
            }
?>
                      <tr class="evenRow">
                        <td>Brand</td>
                        <td align="center">:</td>
                        <td><?= $sBrand ?></td>
                      </tr>
              
                      <tr class="evenRow">
                        <td>Vendor</td>
                        <td align="center">:</td>
                        <td><?= $sVendor ?></td>
                      </tr>
              
                      <tr class="evenRow">
                        <td>Audit Date</td>
                        <td align="center">:</td>
                        <td><?= formatDate($sAuditDate) ?></td>
                      </tr>
              
                     <tr class="evenRow">
                        <td>Start Time</td>
                        <td align="center">:</td>
                        <td><?= $sStartTime ?></td>
                      </tr>
              
                      <tr class="evenRow">
                        <td>End Time</td>
                        <td align="center">:</td>
                        <td><?= $sEndTime ?></td>
                      </tr>
              
                    <tr class="evenRow">
                        <td>Style</td>
                        <td align="center">:</td>
                        <td><?= getDbValue("style", "tbl_styles", "id='$iStyleId'") ?></td>
                      </tr>
              
                      <tr class="evenRow">
                        <td>Order No(s)</td>
                        <td align="center">:</td>
                        <td><?= getDbValue("GROUP_CONCAT(order_no SEPARATOR ', ')", "tbl_po", "FIND_IN_SET(id, '$sPos')") ?></td>
                      </tr>
					  
                     <tr class="evenRow">
                        <td>Quantity</td>
                        <td align="center">:</td>
                        <td><?= formatNumber($iQuantity, false) ?></td>
                      </tr>
              
                     <tr class="evenRow">
                        <td>Commission No</td>
                        <td align="center">:</td>
                        <td><?= $sCommissions ?></td>
                      </tr>
              
                      <tr class="evenRow">
                        <td>Colors</td>
                        <td align="center">:</td>
                        <td><?= $sColors ?></td>
                      </tr>
                      
                      <tr class="evenRow">
                        <td>Sizes</td>
                        <td align="center">:</td>
                        <td><?= getDbValue("GROUP_CONCAT(size SEPARATOR ', ')", "tbl_sizes", "FIND_IN_SET(id, '$sSizes')") ?></td>
                      </tr>
              
                      <tr class="evenRow">
                        <td>Status</td>
                        <td align="center">:</td>
                        <td><?= $sStatus ?></td>
                      </tr>
              
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>