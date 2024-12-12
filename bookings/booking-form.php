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

	$PageId                 = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
        $Brand          	= IO::intValue("Brand");
        $Ports          	= IO::getArray("Ports");
        $Factory        	= IO::intValue("Factory");
        $Supplier         	= IO::intValue("Vendor");
        $Article        	= IO::strValue("Article");
        $Ian        		= IO::strValue("Ian");
        $LotSize        	= IO::strValue("LotSize");
        $Shipments      	= IO::strValue("Shipments");
        $ShippingDate           = IO::strValue("ShippingDate");
        $ReqInspectionDate      = IO::strValue("ReqInspectionDate");
        $Services  		= IO::getArray("Services");
        $SamplePickFor          = IO::getArray("SamplePickFor");
        $Remarks      		= IO::strValue("Remarks");
        $ReTest      		= IO::strValue("ReTest");
	$PostId                 = IO::strValue("PostId");
        $ContactPersonName      = IO::strValue("ContactPersonName");
        $ContactPersonPhone     = IO::strValue("ContactPersonPhone");
        $ContactPersonEmail     = IO::strValue("ContactPersonEmail");
        $ContactPersonFax       = IO::strValue("ContactPersonFax");
        $FactoryPersonName      = IO::strValue("FactoryPersonName");
        $FactoryPersonEmail     = IO::strValue("FactoryPersonEmail");    
        $FactoryPersonPhone     = IO::strValue("FactoryPersonPhone");    
        $FactoryPersonFax       = IO::strValue("FactoryPersonFax");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

                $BookingId              = IO::strValue("BookingId");
                $Brand          	= IO::intValue("Brand");
                $Ports          	= IO::getArray("Ports");
                $Factory        	= IO::intValue("Factory");
                $Supplier         	= IO::intValue("Vendor");
                $Article        	= IO::strValue("Article");
                $Ian        		= IO::strValue("Ian");
                $LotSize        	= IO::strValue("LotSize");
                $Shipments      	= IO::strValue("Shipments");
                $ShippingDate           = IO::strValue("ShippingDate");
                $ReqInspectionDate      = IO::strValue("ReqInspectionDate");
                $Services  		= IO::getArray("Services");
                $SamplePickFor          = IO::getArray("SamplePickFor");
                $Remarks      		= IO::strValue("Remarks");
                $ReTest      		= IO::strValue("ReTest");
                $ContactPersonName      = IO::strValue("ContactPersonName");
                $ContactPersonPhone     = IO::strValue("ContactPersonPhone");
                $ContactPersonEmail     = IO::strValue("ContactPersonEmail");
                $ContactPersonFax       = IO::strValue("ContactPersonFax");
                $FactoryPersonName      = IO::strValue("FactoryPersonName");
                $FactoryPersonEmail     = IO::strValue("FactoryPersonEmail");    
                $FactoryPersonPhone     = IO::strValue("FactoryPersonPhone");    
                $FactoryPersonFax       = IO::strValue("FactoryPersonFax");
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
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1><img src="images/h1/quonda/booking-form.png" vspace="10" alt="" title="" /></h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="bookings/save-booking-form.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
                                <input type="hidden" name="EditForm" value="N" />
                                <input type="hidden" name="MaterialInfo" id="MaterialInfo" value="0" />
				<h2>Add New Booking</h2>

                                <table border="0" cellpadding="3" cellspacing="0" width="100%" style="margin-top: -10px;">
                                    <tr valign="top">
                                        <td width="50%" valign="top">
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
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
			            <option value="<?= $sKey ?>"<?= (($sKey == $Supplier) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
                                            <input type="hidden" name="PortRequired" id="PortRequired" value="<?=$sPortRequireList[$Supplier]?>">
					</td>
				  </tr>
                                    <tr>
                                            <td>Contact Person Name<span class="mandatory">*</span></td>
                                                <td align="center">:</td>
                                                <td>
                                                    <input type="text" name="ContactPersonName"  id="ContactPersonName" value="<?= $ContactPersonName ?>" class="textbox" maxlength="200" style="width: 220px;"/>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Contact Person Email<span class="mandatory">*</span></td>
                                                <td align="center">:</td>
                                                <td>
                                                    <input type="text" name="ContactPersonEmail" id="ContactPersonEmail" value="<?= $ContactPersonEmail ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                                </td>
                                            </tr>  

                                            <tr>
                                                <td>Contact Person Phone</td>
                                                <td align="center">:</td>
                                                <td>
                                                    <input type="text" name="ContactPersonPhone" id="ContactPersonPhone" value="<?= $ContactPersonPhone ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                                </td>
                                            </tr>  

                                            <tr>
                                                <td>Contact Person Fax</td>
                                                <td align="center">:</td>
                                                <td>
                                                    <input type="text" name="ContactPersonFax" id="ContactPersonFax" value="<?= $ContactPersonFax ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                                </td>
                                            </tr>
                                    
                                    <tr><td colspan="3"><h3 style="margin-left: -5px;padding-right: 10px;margin-right: 21px;">Factory Contact Information</h3></td></tr>                                  
                                    <tr>
					<td>Factory Name<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Factory" onchange="UpDateFactoryPersonInfo(this)" style="width:225px !important; max-width: 100% !important;">
						<option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Factory) ? " selected" : "") ?>><?= $sValue ?></option>
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
                                                    <input type="text" name="FactoryPersonName"  id="FactoryPersonName" value="<?= $FactoryPersonName ?>" class="textbox" maxlength="200" style="width: 220px;"/>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Contact Person Email<span class="mandatory">*</span></td>
                                                <td align="center">:</td>
                                                <td>
                                                    <input type="text" name="FactoryPersonEmail" id="FactoryPersonEmail" value="<?= $FactoryPersonEmail ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                                </td>
                                            </tr>  

                                            <tr>
                                                <td>Contact Person Phone</td>
                                                <td align="center">:</td>
                                                <td>
                                                    <input type="text" name="FactoryPersonPhone" id="FactoryPersonPhone" value="<?= $FactoryPersonPhone ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                                </td>
                                            </tr>  

                                            <tr>
                                                <td>Contact Person Fax</td>
                                                <td align="center">:</td>
                                                <td>
                                                    <input type="text" name="FactoryPersonFax" id="FactoryPersonFax" value="<?= $FactoryPersonFax ?>" class="textbox" maxlength="50" style="width: 220px;"/>
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
			            <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
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
                                            <input type="text" name="Article" value="<?= $Article ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                        </td>
                                  </tr>

                                  <tr>
					<td>IAN<span class="mandatory">*</span></td>
					<td align="center">:</td>
                                        <td>
                                            <input type="text" name="Ian" value="<?= $Ian ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                        </td>
                                  </tr>

                                  <tr>
					<td>Quantity of Shipment/ Lot<span class="mandatory">*</span></td>
					<td align="center">:</td>
                                        <td>
                                            <input type="number" name="LotSize" value="<?= $LotSize ?>" class="textbox" maxlength="50" min="0" style="width: 220px;"/>
                                        </td>
                                  </tr>

                                  <tr>
					<td>Number of Shipments</td>
					<td align="center">:</td>
                                        <td><input type="number" name="Shipments" value="<?= $Shipments ?>" class="textbox" maxlength="50" min="0" style="width: 220px;"/></td>
                                  </tr>
                                    
                                  <tr>
                                    <td>Requested Inspection Date<span class="mandatory">*</span></td>
                                    <td align="center">:</td>
                                    <td><input type="text" value="<?= (($ReqInspectionDate == "") ? date('Y-m-d') : $ReqInspectionDate) ?>" name="ReqInspectionDate" id="ReqInspectionDate" style="width: 219px;" readonly/></td>
                                </tr>

                                <tr>
                                    <td>Planned Shipping Date<span class="mandatory">*</span></td>
                                    <td align="center">:</td>

                                    <td><input type="text" value="<?= (($ShippingDate == "") ? date('Y-m-d') : $ShippingDate) ?>" name="ShippingDate" id="ShippingDate" style="width: 219px;" readonly/></td>
                                </tr>
                                   <tr><td colspan="3">&nbsp;</td></tr>
                                   <tr><td colspan="3">&nbsp;</td></tr>
                                    <tr>
                                        <td colspan="3">Before Sending Booking Form, Please Read Our <a class="lightview" href="bookings/view-terms-conditions.php" rel="iframe" title="Terms & Conditions :: :: width: 900, height: 550">Terms & Conditions.</td>
                                    </tr>
				</table>
                                        </td>
                                        <td valign="top" width="50%">
                                            <table border="0" cellpadding="3" cellspacing="0" width="100%" >   
                                                 <tr>
                                        <td width="160">Inspection Services<span class="mandatory">*</span></td>
                                        <td align="center" width="20">:</td>

                                        <td>
                                <div id="Services" class="multiSelect" style="width:245px; height:170px; overflow-y: scroll;  overflow-x: hidden;">
				    <table border="0" cellpadding="0" cellspacing="1" width="100%">
<?
                                            foreach ($sServicesList as $sKey => $sValue)
                                            {
?>
                                                <tr>
                                                    <td width="25"><input type="checkbox" name="Services[]" class="Services" onclick="ToggleDiv()" id="Services<?=$sKey?>" rel="<?=$sServiceMaterials[$sKey]?>" value="<?= $sKey ?>" <?= ((@in_array($sKey, $Services)) ? 'checked' : '') ?> /></td>
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
                                        <td>Sample Pick<span class="mandatory">*</span></td>
                                        <td  align="center">:</td>
                                        <td>
                                            
                                <div id="SamplePickFor" class="multiSelect" style="width:245px; height:80px; overflow-y: scroll;  overflow-x: hidden;">
				    <table border="0" cellpadding="0" cellspacing="1" width="100%">
<?
                                            foreach($sSamplePickList as $iSampleId => $sSampleValue)
                                            {
?>
                                                <tr>
                                                    <td width="25"><input type="checkbox" name="SamplePickFor[]" onclick="ToggleDiv()" rel="<?=$sSampleMaterials[$iSampleId]?>" class="SamplePickFor" id="SamplePickFor<?=$iSampleId?>" value="<?= $iSampleId ?>" <?= ((@in_array($iSampleId, $SamplePickFor)) ? 'checked' : '') ?> /></td>
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
                                                    <td>Ports</td>
                                                    <td width="20" align="center">:</td>

                                                    <td>                                                        
                                <div id="Ports" class="multiSelect" style="width:245px; height:95px; overflow-y: scroll; overflow-x: hidden;">
				    <table border="0" cellpadding="0" cellspacing="1" width="100%">
<?
                                            foreach ($sShippingPorts as $sKey => $sValue)
                                            {
?>
                                                <tr>
                                                      <td width="25"><input type="checkbox" name="Ports[]" id="Ports<?=$sKey?>" value="<?= $sKey ?>" <?= ((@in_array($sKey, $Ports)) ? 'checked' : '') ?> /></td>
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
                                                            <textarea name="Destinations" cols="30" rows="3" style="width:245px;"><?=$Destinations?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Notes</td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <textarea name="Remarks" cols="30" rows="6" style="width:245px;"><?=$Remarks?></textarea>
                                                        </td>
                                                    </tr>
                                                
                                                    <tr>
                                                        <td>Attachments</td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <input type="file" name="Attachments[]" id="Attachments" class="textbox" style="width:245px;" multiple/><br/>
                                                            <span style="font-size: 8px; color: darkgray;">Allowed File Types(jpg, jpeg, gif, png, pdf, doc, docx, csv, xls, xlsx, txt, ppt, zip)</span>
                                                        </td>
                                                    </tr>                                                    
                                            </table>
                                        </td>
                                    </tr>
                                    <tr id="MaterialsRow" style="display:none;">                                        
                                        <td colspan="2">
                                            <div> <h4>Material Conformity</h4>
                                                <table id="MaterialsTable" border="0" cellspacing="5" cellpadding="5" width="100%">
                                                    <tr class="sdRowHeader">
                                                        <td style="width:5%;"><b>#</b></td>
                                                        <td style="width:30%;"><b>Material/ Content</b></td>
                                                        <td style="width:25%;"><b>Colour</b></td>
                                                        <td style="width:40%;"><b>Remarks</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td><input type="text" name="Material[]" id="Material" class="textbox" maxlength="50" style='width:95%;'></td>
                                                        <td><input type="text" name="MaterialColor[]" id="MaterialColor" class="textbox" maxlength="50" style='width:95%;'/></td>
                                                        <td><input type="text" name="MaterialRemarks[]" id="MaterialRemarks" class="textbox" maxlength="50" style='width:95%;'/></td>
                                                    </tr>
                                                </table>
                                                <br/>
                                                <input type="hidden" name="CountRows" id="CountRows" value="1"/>
                                                <a id="BtnAddRow" onclick="AddNewRow()">Add Item [+]</a> / 
                                                <a id="BtnDelRow" onclick="DeleteRow()">Remove Item [-]</a>
                                            </div>
                                        </td>
                                    </tr>
                                   
                                </table>
				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSendBooking" title="Save" onclick="return validateForm( );" style="margin-left: 807px;"/></div>
			    </form>

                            <hr />			   
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

    var i=2;
    
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