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

	$Id      = IO::intValue('Id');
	$Referer = IO::strValue("Referer");

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];
        
        $sSQL = "SELECT * FROM tbl_bookings WHERE id='$Id'";
    	$objDb->query($sSQL);

        $iBrand          	= $objDb->getField(0,"brand_id");
        $iFactory        	= $objDb->getField(0,"factory_id");
        $iSupplier         	= $objDb->getField(0,"supplier_id");
        $sArticle        	= $objDb->getField(0,"article");
        $sIan        		= $objDb->getField(0,"ian");
        $iLotSize        	= $objDb->getField(0,"quantity");
        $iShipments      	= $objDb->getField(0,"shipments");
        $sShippingDate  	= $objDb->getField(0,"shipping_date");
        $sReqInspectionDate     = $objDb->getField(0,"inspection_date");
        $iServices  		= explode(",", $objDb->getField(0,"services"));
        $iSamplePickFor         = explode(",", $objDb->getField(0,"sample_pick"));
        $sPorts                 = explode(",", $objDb->getField(0,"ports"));
        $sRemarks      		= $objDb->getField(0,"notes");
        $sStatus      		= $objDb->getField(0,"status");
        $sStatusComments	= $objDb->getField(0,"status_comments");            
        $iCreatedBy             = $objDb->getField(0,"created_by");
        $sContactPersonName     = $objDb->getField(0,"cp_name");
        $sContactPersonPhone    = $objDb->getField(0,"cp_phone");
        $sContactPersonEmail    = $objDb->getField(0,"cp_email");
        $sContactPersonFax      = $objDb->getField(0,"cp_fax");
        $ParcelSentBy           = $objDb->getField(0,"parcel_sent_by");
        $ParcelSentDate         = $objDb->getField(0,"parcel_sent_date");
        $ParcelAwbNumber        = $objDb->getField(0,"parcel_awb_number");
        $sDestinations          = $objDb->getField(0,"destinations");
        $sFactoryPersonName     = $objDb->getField(0,"fp_name");
        $sFactoryPersonPhone    = $objDb->getField(0,"fp_phone");
        $sFactoryPersonEmail    = $objDb->getField(0,"fp_email");
        $sFactoryPersonFax      = $objDb->getField(0,"fp_fax");
            
        if($iFactory >0)
        {
            $sSQL = "SELECT manager_rep, phone, manager_rep_email, fax from tbl_vendors WHERE id='$iFactory'";
            $objDb->query($sSQL);
            
            if($sFactoryPersonName == "")
                $sFactoryPersonName = $objDb->getField(0, "manager_rep");
            
            if($sFactoryPersonPhone == "")
                $sFactoryPersonPhone = $objDb->getField(0, "phone");
            
            if($sFactoryPersonEmail == "")
                $sFactoryPersonEmail = $objDb->getField(0, "manager_rep_email");
            
            if($sFactoryPersonFax == "")
                $sFactoryPersonFax = $objDb->getField(0, "fax");           
        }
        
        $sSamplePickList = getList("tbl_sample_picks", "id", "title", "status='A'");
        $sSampleMaterials= getList("tbl_sample_picks", "id", "materials", "status='A'");
        $iAuditorTypes   = explode(",", getDbValue("auditor_types", "tbl_users", "id='{$_SESSION['UserId']}'"));
        $sSuppliersList  = getList("tbl_suppliers", "id", "supplier", "id IN ({$_SESSION['Suppliers']})");
        $sPortRequireList= getList("tbl_suppliers", "id", "port_required", "id IN ({$_SESSION['Suppliers']})");
	$sVendorsList    = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList     = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
        $sShippingPorts  = getList("tbl_shipping_ports", "id", "port_name", "booking_form='Y'", "id");
        //$sStages         = explode(",", getDbValue("stages", "tbl_reports", "id='39'"));
        //$sServicesList   = getList("tbl_audit_stages", "id", "stage", "code IN ('". implode("', '", $sStages)."')", "position");
        //$sServiceMaterials = getList("tbl_audit_stages", "id", "materials", "code IN ('". implode("', '", $sStages)."')", "position");   
        $sUserServices      = explode(",", getDbValue("audit_services", "tbl_users", "id='{$_SESSION['UserId']}'"));
        $sServicesList      = getList("tbl_audit_services", "id", "service", "id IN ('". implode("', '", $sUserServices)."')", "position");
        $sServiceMaterials  = getList("tbl_audit_services", "id", "materials", "id IN ('". implode("', '", $sUserServices)."')", "position");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <link type="text/css" rel="stylesheet" href="css/jquery.sunny.datepick.css" />
  <script type="text/javascript" src="scripts/jquery.sunny.min.datepick.js"></script>  
  <script type="text/javascript" src="scripts/jquery.sunny.plugin.min.js"></script>  
  <script type="text/javascript" src="scripts/jquery.sunny.datepick.js"></script>  
  <script type="text/javascript" src="scripts/bookings/booking-form.js"></script>
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
                <table border="0" cellpadding="0" cellspacing="0" width="100%" valign="top">
			<tr valign="top">
			  <td width="100%">
			    <h1><img src="images/h1/quonda/booking-form.png" vspace="10" alt="" title="" /></h1>

<?
	if ($sUserRights['Edit'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="bookings/update-booking-form.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Edit Booking# <?= ("B".str_pad($Id, 5, '0', STR_PAD_LEFT)) ?></h2>
                                <input type="hidden" name="Id" value="<?= $Id ?>" />
                                <input type="hidden" name="EditForm" value="Y" />
                                <input type="hidden" name="Referer" value="<?=$Referer?>" />
<?
                                            if($sStatus != 'P' && !in_array(11 ,$iAuditorTypes))
                                            {
?>
                                              <fieldset disabled="disabled">
<?
                                            }
?>					  
                                    <table border="0" cellpadding="3" cellspacing="0" width="100%" valign="top"  style="margin-top:-10px;">
                                    <tr>
                            <td width="50%" valign="top">
                                <table border="0" cellpadding="3" cellspacing="0" width="100%" valign="top" style="margin-top: -30px;">
                                    <tr style="line-height: 0px;"><td width="170"></td><td width="20"></td><td></td></tr>
                                    <tr><td colspan="3"><h3 style="margin-left: -5px;padding-right: 10px;margin-right: 21px;">Supplier Contact Information</h3></td></tr>
                                    <tr>
                                      <td width="170">Supplier<span class="mandatory">*</span></td>
					<td  width="20" align="center">:</td>

					<td>
					  <select name="Vendor" onchange="UpDateContactPersonInfo(this)" style="width:225px !important; max-width: 100% !important;">
						<option value=""></option>
<?
		foreach ($sSuppliersList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $iSupplier) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
                                            <input type="hidden" name="PortRequired" id="PortRequired" value="<?=$sPortRequireList[$iSupplier]?>">
					</td>
				  </tr>
                                  <tr>
					<td>Contact Person Name<span class="mandatory">*</span></td>
					<td align="center">:</td>
                                        <td>
                                            <input type="text" name="ContactPersonName"  id="ContactPersonName" value="<?= $sContactPersonName ?>" class="textbox" maxlength="200" style="width: 220px;"/>
                                        </td>
                                    </tr>
                                                
                                    <tr>
					<td>Contact Person Email<span class="mandatory">*</span></td>
					<td align="center">:</td>
                                        <td>
                                            <input type="text" name="ContactPersonEmail" id="ContactPersonEmail" value="<?= $sContactPersonEmail ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                        </td>
                                    </tr>  
                                    
                                   <tr>
					<td>Contact Person Phone</td>
					<td align="center">:</td>
                                        <td>
                                            <input type="text" name="ContactPersonPhone" id="ContactPersonPhone" value="<?= $sContactPersonPhone ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                        </td>
                                  </tr>  
                                    
                                   <tr>
					<td>Contact Person Fax</td>
					<td align="center">:</td>
                                        <td>
                                            <input type="text" name="ContactPersonFax" id="ContactPersonFax" value="<?= $sContactPersonFax ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                        </td>
                                  </tr>  
                                    <tr><td colspan="3"><h3 style="margin-left: -5px;padding-right: 10px;margin-right: 21px;">Factory Contact Information</h3></td></tr>                                  
                                    <tr>
					<td>Factory Name<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Factory" style="width:225px !important; max-width: 100% !important;" onchange="UpDateFactoryPersonInfo(this);">
						<option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $iFactory) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
                                    </tr>
                                    <tr>
                                                <td>Contact Person Name<span class="mandatory">*</span></td>
                                                <td align="center">:</td>
                                                <td>
                                                    <input type="text" name="FactoryPersonName"  id="FactoryPersonName" value="<?= $sFactoryPersonName ?>" class="textbox" maxlength="200" style="width: 220px;"/>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Contact Person Email<span class="mandatory">*</span></td>
                                                <td align="center">:</td>
                                                <td>
                                                    <input type="text" name="FactoryPersonEmail" id="FactoryPersonEmail" value="<?= $sFactoryPersonEmail ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                                </td>
                                            </tr>  

                                            <tr>
                                                <td>Contact Person Phone</td>
                                                <td align="center">:</td>
                                                <td>
                                                    <input type="text" name="FactoryPersonPhone" id="FactoryPersonPhone" value="<?= $sFactoryPersonPhone ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                                </td>
                                            </tr>  

                                            <tr>
                                                <td>Contact Person Fax</td>
                                                <td align="center">:</td>
                                                <td>
                                                    <input type="text" name="FactoryPersonFax" id="FactoryPersonFax" value="<?= $sFactoryPersonFax ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                                </td>
                                            </tr>
                                    <tr><td colspan="3"><h3 style="margin-left: -5px;padding-right: 10px;margin-right: 21px;">Other Information</h3></td></tr>                                  
                                    
                                                                        <tr>
					<td>Client Name<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Brand" style="width:225px !important; max-width: 100% !important;">
						<option value=""></option>
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $iBrand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
                                    </tr>

                                    <tr>
                                          <td>Article Description<span class="mandatory">*</span></td>
                                          <td align="center">:</td>
                                          <td>
                                              <input type="text" name="Article" value="<?= $sArticle ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                          </td>
                                    </tr>

                                    <tr>
                                          <td>IAN<span class="mandatory">*</span></td>
                                          <td align="center">:</td>
                                          <td>
                                              <input type="text" name="Ian" value="<?= $sIan ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                          </td>
                                    </tr>

                                    <tr>
                                          <td>Quantity of Shipment/ Lot<span class="mandatory">*</span></td>
                                          <td align="center">:</td>
                                          <td>
                                              <input type="number" name="LotSize" value="<?= $iLotSize ?>" class="textbox" maxlength="50" min="0" style="width: 220px;"/>
                                          </td>
                                    </tr>

                                    <tr>
                                          <td>Number of Shipments</td>
                                          <td align="center">:</td>
                                          <td>
                                              <input type="number" name="Shipments" value="<?= $iShipments ?>" class="textbox" maxlength="50" min="0" style="width: 220px;"/>
                                          </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Requested Inspection Date<span class="mandatory">*</span></td>
                                        <td width="20" align="center">:</td>

                                        <td><input type="text" value="<?= (($sReqInspectionDate == "") ? date('Y-m-d') : $sReqInspectionDate) ?>" name="ReqInspectionDate" id="ReqInspectionDate" style="width: 219px;" readonly/></td>
                                    </tr>

                                    <tr>
                                        <td>Planned Shipping Date<span class="mandatory">*</span></td>
                                        <td align="center">:</td>

                                        <td><input type="text" value="<?= (($sShippingDate == "") ? date('Y-m-d') : $sShippingDate) ?>" name="ShippingDate" id="ShippingDate" style="width: 219px;" readonly/></td>
                                    </tr>
                                    <br/><br/>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td></td>
                                  </tr>
				</table>
                                        </td>
                                        <td valign="top" width="50%">
                                            <table border="0" cellpadding="3" cellspacing="0" width="100%" valign="top" >
                                                <tr>
                                        <td width="160">Inspection Services<span class="mandatory">*</span></td>
                                        <td width="20" align="center">:</td>

                                        <td>
                                <div id="Services" class="multiSelect" style="width:245px; height:175px; overflow-y: scroll; overflow-x: hidden;">
				    <table border="0" cellpadding="0" cellspacing="1" width="100%">
<?
                                            foreach ($sServicesList as $sKey => $sValue)
                                            {
?>
                                                <tr>
                                                      <td width="25"><input type="checkbox" name="Services[]" class="Services" onclick="ToggleDiv()" id="Services<?=$sKey?>" rel="<?=$sServiceMaterials[$sKey]?>" value="<?= $sKey ?>" <?= ((@in_array($sKey, $iServices)) ? 'checked' : '') ?> /></td>  
                                                      <td><label for="Services<?= $sKey ?>"><?= $sValue ?></label></td>
                                                </tr>
<?
                                            }
?>
                                    </table>
                                </div>
                                          </td>
                                      </tr>
                                    <tr>
                                        <td width="160">Sample Pick<span class="mandatory">*</span></td>
                                        <td width="20" align="center">:</td>
                                        <td>                                            
                                            <div id="SamplePickFor" class="multiSelect" style="width:245px; height:80px; overflow-y: scroll; overflow-x: hidden;">
				    <table border="0" cellpadding="0" cellspacing="1" width="100%">
<?
                                            foreach($sSamplePickList as $iSampleId => $sSampleValue)
                                            {
?>
                                                <tr>
                                                    <td width="25"><input type="checkbox" name="SamplePickFor[]" onclick="ToggleDiv()" rel="<?=$sSampleMaterials[$iSampleId]?>" class="SamplePickFor" id="SamplePickFor<?=$iSampleId?>" value="<?= $iSampleId ?>" <?= ((@in_array($iSampleId, $iSamplePickFor)) ? 'checked' : '') ?> /></td>
                                                      <td><label for="SamplePickFor<?= $iSampleId ?>"><?= $sSampleValue ?></label></td>
                                                </tr>
<?
                                            }
?>
                                    </table>
                                </div>
                                        </td>
                                    </tr>
                                                    
                                    <tr>
                                        <td width="140">Ports</td>
					<td width="20" align="center">:</td>

					<td>                                            
                                <div id="Ports" class="multiSelect" style="width:245px; height:95px; overflow-y: scroll; overflow-x: hidden;">
				    <table border="0" cellpadding="0" cellspacing="1" width="100%">
<?
                                            foreach ($sShippingPorts as $sKey => $sValue)
                                            {
?>
                                                <tr>
                                                      <td width="25"><input type="checkbox" name="Ports[]" id="Ports<?=$sKey?>" value="<?= $sKey ?>" <?= ((@in_array($sKey, $sPorts)) ? 'checked' : '') ?> /></td>
                                                      <td><label for="Ports<?= $sKey ?>"><?= $sValue ?></label></td>
                                                </tr>
<?
                                            }
?>
                                    </table>
                                </div>
					</td>
                                        
                                    </tr>
                                    <tr>
                                            <td>Countries of Destinations</td>
                                            <td align="center">:</td>
                                            <td>
                                                <textarea name="Destinations" cols="30" rows="2" style="width:247px;"><?=$sDestinations?></textarea>
                                            </td>
                                        </tr>
                                    <tr>
                                        <td>Notes</td>
                                        <td align="center">:</td>
                                        <td>
                                            <textarea name="Remarks" cols="30" rows="3" style="width:247px;"><?=$sRemarks?></textarea>
                                        </td>
                                    </tr>
                                              
                                                     
<?
                if(@in_array(11 ,$iAuditorTypes) || $iCreatedBy == $_SESSION['UserId'] || $sStatus != 'P')
                {
?>
                                                    <tr>
                                                        <td>Status<span class="mandatory">*</span></td>
                                                        <td align="center">:</td>

                                                        <td>
                                                            <select name="Status" style="width:253px; max-width: 100%;">
                                                                <option value="P" <?= ($sStatus == 'P')?'selected':''?>>Pending</option>
<?
                                                                if(@in_array(11 ,$iAuditorTypes) || $sStatus != 'P')
                                                                {
?>
                                                                <option value="A" <?= ($sStatus == 'A')?'selected':''?>>Approved</option>                                                                
                                                                <option value="R" <?= ($sStatus == 'R')?'selected':''?>>Rejected</option>
<?
                                                                }
                                                                
                                                                if(@in_array(11 ,$iAuditorTypes) || $iCreatedBy == $_SESSION['UserId'] || $sStatus != 'P')
                                                                {
?>
                                                                <option value="C" <?= ($sStatus == 'C')?'selected':''?>>Cancelled</option>
<?
                                                                }
?>
                                                          </select>
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td>Status Remarks</td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <textarea name="StatusComments" cols="30" rows="2" style="width:247px;"><?=$sStatusComments?></textarea>
                                                        </td>
                                                    </tr>
<?
                }
?>
                                                    <tr>
                                                        <td>Attachments</td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <input type="file" name="Attachments[]" id="Attachments" class="textbox" style="width:247px;" multiple/><br/>                                                            
                                                            <span style="font-size: 8px; color: darkgray;">Allowed File Types(jpg, jpeg, gif, png, pdf, doc, docx, csv, xls, xlsx, txt, ppt, zip)</span>
                                                        </td>
                                                    </tr>
<?
                                            $BookingAttachments = getList("tbl_booking_files", "id", "file", "booking_id = '$Id'");
                                                     
                                            if(count($BookingAttachments) > 0)
                                            {
?>
                                                    <tr>
                                                        <td colspan="3"><h3>Attachments</h3></td>                                                        
                                                    </tr>  
                                                    <tr>
                                                        <td colspan="3">
                                                            <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                                                     <?
                                                                         $Counter = 1;
                                                                         @list($sYear, $sMonth, $sDay) = @explode("-", $sReqInspectionDate);
                                                                          
                                                                         foreach($BookingAttachments as $iAttachment => $sAttachment)
                                                                         {
                                                                             if ($sAttachment != "" && @file_exists($sBaseDir.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment))
                                                                             {

?>
                                                                    <tr>
                                                                        <td width="50"><?=$Counter?></td>
                                                                        <td width="20">:</td>
                                                                             <td>
<?
                                                                                 $exts = explode('.', $sAttachment);
                                                                                 $extension = end($exts);
                                                                                if(@in_array(strtolower($extension), array('jpg','jpeg','gif','png')))
                                                                                {
?>
                                                                                     <a class="lightview" id="M<?=$Counter.'-'.$Id?>" href="<?=SITE_URL.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment?>"><?=$sAttachment?></a>
                                                                                     <?
                                                                                     if ($sUserRights['Delete'] == "Y")
                                                                                     {
?>                              
                                                                                     <span style="cursor: pointer;" onclick='DeleteBookingImage("<?=$Id?>","File=<?= @basename($sAttachment) ?>&InspectionDate=<?= $sReqInspectionDate ?>&Id=<?=$Id?>&ImageId=<?=$iAttachment?>")'><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></span><br />
<?
                                                                                     }
?>
                                                                             </td>
                                                                    </tr>
<?
                                                                                }else
                                                                                {
?>
                                                                                     <a  target="_blank" href="<?=SITE_URL.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment?>"><?=$sAttachment?></a>
<?
                                                                                     if ($sUserRights['Delete'] == "Y")
                                                                                     {
?>                              
                                                                                         <span style="cursor: pointer;" onclick='DeleteBookingImage("<?=$Id?>","File=<?= @basename($sAttachment) ?>&InspectionDate=<?= $sReqInspectionDate ?>&Id=<?=$Id?>&ImageId=<?=$iAttachment?>")'><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></span><br />
<?
                                                                                     }
?>
                                                                                     </td></tr>
<?
                                                                                }
                                                                                 $Counter++;
                                                                             }
                                                                         }
?>
                                                               </table>
                                                        </td>                                                        
                                                    </tr> 
<?
                                            }
?>
                                            </table>
                                        </td>
                                    </tr>
                                                                      
<?
                                
                $sSQL = "SELECT * FROM tbl_booking_materials WHERE booking_id='$Id'";
                $objDb->query($sSQL);                
                $iCount = $objDb->getCount();
                
                if($iCount > 0)
                {
?>
                      <input type="hidden" name="MaterialInfo" id="MaterialInfo" value="1" />
<?
                }else{
?>
                      <input type="hidden" name="MaterialInfo" id="MaterialInfo" value="0" />
<?
                }
?>
                                     <tr id="MaterialsRow" style="<?=($iCount==0)?'display:none;':''?>">
                                         <td colspan="3">
                                             <div> <h4>Material Conformity</h4>
                                                 <table id="MaterialsTable" border="0" cellspacing="5" cellpadding="5" width="100%">
                                                     <tr class="sdRowHeader">
                                                        <td style="width:5%;"><b>#</b></td>
                                                        <td style="width:30%;"><b>Material/ Content</b></td>
                                                        <td style="width:25%;"><b>Colour</b></td>
                                                        <td style="width:40%;"><b>Remarks</b></td>
                                                    </tr>
<?
                                                for($i=0; $i<$iCount; $i++)
                                                {
                                                    
                                                    $sMaterial          = $objDb->getField($i,"material");
                                                    $sMaterialColor     = $objDb->getField($i,"color");
                                                    $sMaterialRemarks   = $objDb->getField($i,"remarks");
?>
                                                     <tr>
                                                         <td><?=$i+1?></td>
                                                         <td><input type="text" name="Material[]" id="Material" value="<?=$sMaterial?>" class="textbox" maxlength="50" style='width:95%;'></td>
                                                         <td><input type="text" name="MaterialColor[]" value="<?=$sMaterialColor?>" id="MaterialColor" class="textbox" maxlength="50" style='width:95%;'/></td>
                                                        <td><input type="text" name="MaterialRemarks[]" value="<?=$sMaterialRemarks?>" id="MaterialColor" class="textbox" maxlength="50" style='width:95%;'/></td>
                                                     </tr>
<?
                                                }
?>
                                                 </table>
                                                 <br/>
                                                 <input type="hidden" name="CountRows" id="CountRows" value="<?=$iCount+1?>"/>
                                                 <a id="BtnAddRow" onclick="AddNewRow()">Add Item [+]</a> / 
                                                 <a id="BtnDelRow" onclick="DeleteRow()">Remove Item [-]</a>
                                                 <br/><br/>
                                             </div>
                                         </td>
                                     </tr>
                                     <!-- <tr id="MaterialTermsRow" style="<?//($iCount==0)?'display:none;':''?>">
                                        <td>&nbsp;</td><td>Before Sending Booking Form, Please Read Our <a class="lightview" href="bookings/view-material-terms-conditions.php" rel="iframe" title="Material Terms & Conditions :: :: width: 900, height: 300">Material Conformity Terms.</td>
                                     </tr> -->                                   
                      <tr><td colspan="2">&nbsp;</td></tr>
                                </table>
<?
                                            if($sStatus != 'P' && !in_array(11 ,$iAuditorTypes))
                                            {
?>
                                            </fieldset>
<?
                                            }
?>
					                                           
				    </div>
				  </div>                                
<div style="background-color:#494949;"><input type="button" value="" class="btnBack" onclick="document.location='<?= $Referer ?>';" style="display: inline;" /><input type="submit" id="BtnSave" value="" class="btnSendBooking" title="Save" onclick="return validateForm( );" style="display: inline;" /></div>
			    </form>

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
<script type="text/javascript">
	    <!--

    var i=document.getElementById("CountRows").value;
    
    function AddNewRow() 
    {    
        var table = document.getElementById("MaterialsTable");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var cell1  = row.insertCell(0);
        var cell2  = row.insertCell(1);
        var cell3  = row.insertCell(2);
        var cell4  = row.insertCell(3);

        cell1.innerHTML = i;
        cell2.innerHTML = "<input type='text' size='5' class='textbox' name='Material[]' value=''  style='width:95%;'/>";
        cell3.innerHTML = "<input type='text' size='5' class='textbox' name='MaterialColor[]' value=''  style='width:95%;'/>";
        cell4.innerHTML = "<input type='text' size='5' class='textbox' name='MaterialRemarks[]' value=''  style='width:95%;'/>";
        
        i++;
        document.getElementById("CountRows").value = i;
    }

    function DeleteRow() 
    {
        var table = document.getElementById("MaterialsTable");
        var rowCount = table.rows.length;
        
        if(rowCount > 2)
        {
            table.deleteRow(rowCount-1);
            i--;
            document.getElementById("CountRows").value = i;
        }
    }
    
    function ToggleDiv()
    {
        var SetVisible = false;
        var services = document.getElementsByClassName("Services");
        var samples  = document.getElementsByClassName("SamplePickFor");

	for(var i=0; i < services.length; i++){
            if(services[i].checked && services[i].getAttribute("rel") == 'Y')
            {
                SetVisible = true;
                document.getElementById("MaterialInfo").value = "1";
                document.getElementById("MaterialsRow").style.display = "";
                document.getElementById("MaterialTermsRow").style.display = "";
                return;
            }
	}	
        
        if(SetVisible == false)
        {
            for(var i=0; i < samples.length; i++)
            {
                if(samples[i].checked && samples[i].getAttribute("rel") == 'Y')
                {
                    SetVisible = true;
                    document.getElementById("MaterialInfo").value = "1";
                    document.getElementById("MaterialsRow").style.display = "";
                    document.getElementById("MaterialTermsRow").style.display = "";
                    return;
                }
            }
            
            document.getElementById("MaterialInfo").value = "0";
            document.getElementById("MaterialsRow").style.display = "none";
            document.getElementById("MaterialTermsRow").style.display = "none";
        }
    }
    -->
</script>                
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>