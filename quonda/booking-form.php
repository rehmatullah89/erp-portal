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
        $Service  		= IO::intValue("Service");
        $SamplePickFor          = IO::strValue("SamplePickFor");
        $Remarks      		= IO::strValue("Remarks");
        $ReTest      		= IO::strValue("ReTest");
	$PostId = IO::strValue("PostId");

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
                $Service  		= IO::intValue("Service");
                $SamplePickFor          = IO::strValue("SamplePickFor");
                $Remarks      		= IO::strValue("Remarks");
                $ReTest      		= IO::strValue("ReTest");
	}

        $sSuppliersList  = getList("tbl_suppliers", "id", "supplier");
	$sVendorsList    = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList     = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
        $sShippingPorts  = getList("tbl_shipping_ports", "id", "port_name", "booking_form='Y'");
        $sStages         = explode(",", getDbValue("stages", "tbl_reports", "id='39'"));
        $sServicesList   = getList("tbl_audit_stages", "id", "stage", "code IN ('". implode("', '", $sStages)."')", "position");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/jquery.js"></script>  
  <script type="text/javascript" src="scripts/quonda/booking-form.js"></script>
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
			    <h1><img src="images/h1/quonda/booking-form.png" width="170" height="29" vspace="10" alt="" title="" /></h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-booking-form.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add New Booking</h2>

                                <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="50%" valign="top">
				<table border="0" cellpadding="3" cellspacing="0" width="100%">

                                    <tr>
                                      <td width="170">Supplier<span class="mandatory">*</span></td>
					<td  width="20" align="center">:</td>

					<td>
                                            <select name="Vendor" style="width:242px;">
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
					</td>
				  </tr>

                                  <tr>
					<td>Buyer Name<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Brand">
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
					<td>Factory Name<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Factory">
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
                                            <input type="number" name="LotSize" value="<?= $LotSize ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                        </td>
                                  </tr>

                                  <tr>
					<td>Number of Shipments</td>
					<td align="center">:</td>
                                        <td>
                                            <input type="number" name="Shipments" value="<?= $Shipments ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                        </td>
                                  </tr>
                                  <tr>
                                    <td>Requested Inspection Date<span class="mandatory">*</span></td>
                                    <td align="center">:</td>

                                    <td>
                                      <table border="0" cellpadding="0" cellspacing="0" width="116">
                                            <tr>
                                              <td width="82"><input type="text" name="ReqInspectionDate" id="ReqInspectionDate" value="<?= (($ReqInspectionDate == "") ? date('Y-m-d') : $ReqInspectionDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ReqInspectionDate'), 'yyyy-mm-dd', this);" /></td>
                                              <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ReqInspectionDate'), 'yyyy-mm-dd', this);" /></td>
                                            </tr>
                                      </table>

                                    </td>
                                </tr>

                                <tr>
                                    <td>Planned Shipping Date<span class="mandatory">*</span></td>
                                    <td align="center">:</td>

                                    <td>
                                      <table border="0" cellpadding="0" cellspacing="0" width="116">
                                            <tr>
                                              <td width="82"><input type="text" name="ShippingDate" id="ShippingDate" value="<?= (($ShippingDate == "") ? date('Y-m-d') : $ShippingDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ShippingDate'), 'yyyy-mm-dd', this);" /></td>
                                              <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ShippingDate'), 'yyyy-mm-dd', this);" /></td>
                                            </tr>
                                      </table>

                                    </td>
                                </tr> 
                                <tr><td colspan="3">&nbsp;</td></tr>
                                <tr>
                                    <td colspan="3">Before Sending Booking Form, Please Read Our <a class="lightview" href="quonda/view-terms-conditions.php" rel="iframe" title="Terms & Conditions :: :: width: 900, height: 550">Terms & Conditions.</td>
                                </tr>
				</table>
                                        </td>
                                        <td valign="top" width="50%">
                                            <table border="0" cellpadding="3" cellspacing="0" width="100%" >
                                                    
                                                    <tr>
                                                        <td width="140">Sample Pick<span class="mandatory">*</span></td>
                                                        <td width="20" align="center">:</td>
                                                        <td>
                                                            <input type="radio" name="SamplePickFor" value="CHEM" <?=($SamplePickFor == 'CHEM'?'checkced':'')?> class="textbox" maxlength="50"/>CHEM &nbsp;
                                                            <input type="radio" name="SamplePickFor" value="FITTING" <?=($SamplePickFor == 'FITTING'?'checkced':'')?> class="textbox" maxlength="50"/>FITTING&nbsp;
                                                            <input type="radio" name="SamplePickFor" value="NONE" <?=($SamplePickFor == 'NONE'?'checkced':'')?> class="textbox" maxlength="50"/>NONE
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Services<span class="mandatory">*</span></td>
                                                        <td align="center">:</td>

                                                        <td>
                                                            <select name="Service" style="width:260px;">
                                                                <option value=""></option>
<?
                                                        foreach ($sServicesList as $sKey => $sValue)
                                                        {
?>
                                                           <option value="<?=$sKey?>" <?= ($sKey == $Service)?'selected':''?>><?=$sValue?></option>
<?
                                                        }
?>
                                                          </select>
                                                        </td>
                                                    </tr>
                                                
                                                <tr>
                                                    <td>Ports<span class="mandatory">*</span></td>
                                                    <td align="center">:</td>

                                                    <td>
                                                        <select name="Ports[]"  multiple style="width:250px;">
                                                            <option value=""></option>
<?
                            foreach ($sShippingPorts as $sKey => $sValue)
                            {
?>
                                                <option value="<?= $sKey ?>"<?= (@in_array($sKey, $Ports) ? " selected" : "") ?>><?= $sValue ?></option>
<?
                            }
?>
                                                      </select>
                                                    </td>

                                                </tr>
                                                
                                                    <tr>
                                                        <td>Notes</td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <textarea name="Remarks" cols="30" rows="3" style="width:245px;"><?=$Remarks?></textarea>
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
                                </table>
				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSendBooking" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    
<!--
			<hr />    <form name="frmImport" id="frmImport" method="post" action="quonda/import-bookings.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnImport').disabled=true;">
				<h2>Import Booking XML</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="70">Supplier<span class="mandatory">*</span></td>
					<td  width="20" align="center">:</td>
                                        <td>
                                            <select name="Supplier">
						<option value=""></option>
<?
//		foreach ($sSuppliersList as $sKey => $sValue)
		{
?>
			            <option value="<? //$sKey ?>"<? //(($sKey == $iVendor) ? " selected" : "") ?>><? //$sValue ?></option>
<?
		}
?>
					  </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="70">XML File<span class="mandatory">*</span></td>
					<td  width="20" align="center">:</td>
					<td><input type="file" name="XmlFile" id="XmlFile" /></td>
				  </tr>
				</table>

				<br />
				<div class="buttonsBar"><input type="submit" id="BtnImport" value="" class="btnImport" title="Import" onclick="return validateImportForm( );" /></div>
				</form> 
                            -->
                            <hr />

			   
<?
	}
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="65">Booking# </td>
			          <td width="180"><input type="text" name="BookingId" value="<?= $BookingId ?>" class="textbox" maxlength="50" floor="20" /></td>

			          <td width="55">Factory</td>
			          <td width="200">
					    <select name="Vendor">
						  <option value="">All Factories</option>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Supplier) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?

	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($BookingId != "")
		$sConditions .= " AND id LIKE '%$BookingId%' ";

	if ($Supplier > 0)
		$sConditions .= " AND vendor_id='$Supplier' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_bookings", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_bookings $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
    	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="20%">Buyer</td>
				      <td width="20%">Factory</td>
				      <td width="15%">Inspection Date</td>
                                      <td width="25%">Service</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId                    = $objDb->getField($i, 'id');
                $iBrand          	= $objDb->getField($i,"brand_id");
                $iFactory        	= $objDb->getField($i,"factory_id");
                $iSupplier         	= $objDb->getField($i,"supplier_id");
                $sArticle        	= $objDb->getField($i,"article");
                $sIan        		= $objDb->getField($i,"ian");
                $iLotSize        	= $objDb->getField($i,"quantity");
                $iShipments      	= $objDb->getField($i,"shipments");
                $sShippingDate  	= $objDb->getField($i,"shipping_date");
                $sReqInspectionDate     = $objDb->getField($i,"inspection_date");
                $iService  		= $objDb->getField($i,"service_id");
                $sSamplePickFor         = $objDb->getField($i,"sample_for");
                $sPorts                 = $objDb->getField($i,"ports");
                $sRemarks      		= $objDb->getField($i,"notes");
                $sStatus      		= $objDb->getField($i,"status");
                $sStatusComments	= $objDb->getField($i,"status_comments");                
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ("B".str_pad($iId, 5, '0', STR_PAD_LEFT)) ?></td>
				      <td width="20%"><span id="Brand_<?= $iId ?>"><?= $sBrandsList[$iBrand] ?></span></td>
				      <td width="20%"><span id="Vendor_<?= $iId ?>"><?= $sVendorsList[$iFactory] ?></span></td>
				      <td width="15%"><span id="InspectionDate_<?= $iId ?>"><?= $sReqInspectionDate ?></span></td>
                                      <td width="25%"><span id="Stage_<?= $iId ?>"><?= $sServicesList[$iService] ?></span></td>
				      <td width="12%" class="center">
<?

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}
?>
                                        <a href="quonda/export-booking-form.php?Id=<?= $iId ?>"><img src="images/icons/pdf.gif" width="16" height="16" hspace="1" alt="Booking Form" title="Booking Form" /></a>&nbsp;
                                        <a href="quonda/view-booking-form.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Booking Form : <?= "B".str_pad($iId, 5, '0', STR_PAD_LEFT); ?> :: :: width: 900, height: 650"><img src="images/icons/view.gif" width="16" height="16" hspace="1" alt="View" title="View" /></a>&nbsp;
<?
		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="quonda/delete-booking-form.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Booking Form?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" enctype="multipart/form-data" onsubmit="return false;" class="frmInfloorEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />
                                    <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="50%" valign="top">
				<table border="0" cellpadding="3" cellspacing="0" width="100%">

                                    <tr>
                                      <td width="170">Supplier<span class="mandatory">*</span></td>
					<td  width="20" align="center">:</td>

					<td>
					  <select name="Vendor">
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
					</td>
				  </tr>

                                  <tr>
					<td>Buyer Name<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Brand">
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
					<td>Factory Name<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Factory">
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
                                            <input type="number" name="LotSize" value="<?= $iLotSize ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                        </td>
                                  </tr>

                                  <tr>
					<td>Number of Shipments</td>
					<td align="center">:</td>
                                        <td>
                                            <input type="number" name="Shipments" value="<?= $iShipments ?>" class="textbox" maxlength="50" style="width: 220px;"/>
                                        </td>
                                  </tr>
                                    
                                    <tr>
                                        <td>Requested Inspection Date<span class="mandatory">*</span></td>
                                        <td width="20" align="center">:</td>

                                        <td>
                                          <table border="0" cellpadding="0" cellspacing="0" width="116">
                                                <tr>
                                                  <td width="82"><input type="text" name="ReqInspectionDate" id="ReqInspectionDate<?= $iId ?>" value="<?= (($sReqInspectionDate == "") ? date('Y-m-d') : $sReqInspectionDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ReqInspectionDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
                                                  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ReqInspectionDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
                                                </tr>
                                          </table>

                                        </td>
                                    </tr>

                                     <tr>
                                        <td>Planned Shipping Date<span class="mandatory">*</span></td>
                                        <td align="center">:</td>

                                        <td>
                                          <table border="0" cellpadding="0" cellspacing="0" width="116">
                                                <tr>
                                                  <td width="82"><input type="text" name="ShippingDate" id="ShippingDate<?= $iId ?>" value="<?= (($sShippingDate == "") ? date('Y-m-d') : $sShippingDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ShippingDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
                                                  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ShippingDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
                                                </tr>
                                          </table>

                                        </td>
                                    </tr>
                                    <br/><br/>
                                    <tr>
                                        <td colspan="2"></td>

                                        <td>
                                          <input type="submit" value="UPDATE BOOKING" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
                                          <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
                                        </td>
                                  </tr>
				</table>
                                        </td>
                                        <td valign="top" width="50%">
                                            <table border="0" cellpadding="3" cellspacing="0" width="100%" >
                                                    
                                                    <tr>
                                                        <td width="140">Sample Pick<span class="mandatory">*</span></td>
                                                        <td width="20" align="center">:</td>
                                                        <td>
                                                            <input type="radio" name="SamplePickFor" value="CHEM" <?=($sSamplePickFor == 'CHEM'?'checked="checked"':'')?> class="textbox" maxlength="50"/>CHEM &nbsp;
                                                            <input type="radio" name="SamplePickFor" value="FITTING" <?=($sSamplePickFor == 'FITTING'?'checked="checked"':'')?> class="textbox" maxlength="50"/>FITTING&nbsp;
                                                            <input type="radio" name="SamplePickFor" value="NONE" <?=($sSamplePickFor == 'NONE'?'checked="checked"':'')?> class="textbox" maxlength="50"/>NONE
                                                        </td>
                                                    </tr>
                                                
                                                
                                                    <tr>
                                                        <td>Services<span class="mandatory">*</span></td>
                                                        <td align="center">:</td>

                                                        <td>
                                                            <select name="Service" style="width:260px;">
                                                                <option value=""></option>
<?
                                                        foreach ($sServicesList as $sKey => $sValue)
                                                        {
?>
                                                           <option value="<?=$sKey?>" <?= ($sKey == $iService)?'selected':''?>><?=$sValue?></option>
<?
                                                        }
?>
                                                          </select>
                                                        </td>
                                                    </tr>
                                              
                                                
                                    <tr>
                                        <td>Ports<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
                                            <select name="Ports[]" id="Ports<?=$iId?>" multiple style="width:250px;">
						<option value=""></option>
<?
		foreach ($sShippingPorts as $sKey => $sValue)
		{
?>
                                                <option value="<?= $sKey ?>"<?= (@in_array($sKey, explode(",", $sPorts)) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
                                        
                                    </tr>
                                                    <tr>
                                                        <td>Notes</td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <textarea name="Remarks" cols="30" rows="2" style="width:245px;"><?=$sRemarks?></textarea>
                                                        </td>
                                                    </tr>
                                                
                                                    <tr>
                                                        <td>Status<span class="mandatory">*</span></td>
                                                        <td align="center">:</td>

                                                        <td>
                                                            <select name="Status" style="width:260px;">
                                                                <option value="P" <?= ($sStatus == 'P')?'selected':''?>>Pending</option>
                                                                <option value="A" <?= ($sStatus == 'A')?'selected':''?>>Approved</option>
                                                                <option value="R" <?= ($sStatus == 'R')?'selected':''?>>Rejected</option>
                                                          </select>
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td>Status Remarks</td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <textarea name="StatusComments" cols="30" rows="2" style="width:245px;"><?=$sStatusComments?></textarea>
                                                        </td>
                                                    </tr>
                                                
                                                    <tr>
                                                        <td>Attachments</td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <input type="file" name="Attachments[]" id="Attachments<?=$iId?>" class="textbox" style="width:245px;" multiple/><br/>                                                            
                                                            <span style="font-size: 8px; color: darkgray;">Allowed File Types(jpg, jpeg, gif, png, pdf, doc, docx, csv, xls, xlsx, txt, ppt, zip)</span>
                                                        </td>
                                                    </tr>
<?
                                            $BookingAttachments = getList("tbl_booking_files", "id", "file", "booking_id = '$iId'");
                                                     
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
                                                                                     <a class="lightview" id="M<?=$Counter.'-'.$iId?>" href="<?=SITE_URL.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sAttachment?>"><?=$sAttachment?></a>
                                                                                     <?
                                                                                     if ($sUserRights['Delete'] == "Y")
                                                                                     {
?>                              
                                                                                     <span style="cursor: pointer;" onclick='DeleteBookingImage("<?=$iId?>","File=<?= @basename($sAttachment) ?>&InspectionDate=<?= $sReqInspectionDate ?>&Id=<?=$iId?>&ImageId=<?=$iAttachment?>")'><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></span><br />
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
                                                                                         <span style="cursor: pointer;" onclick='DeleteBookingImage("<?=$iId?>","File=<?= @basename($sAttachment) ?>&InspectionDate=<?= $sReqInspectionDate ?>&Id=<?=$iId?>&ImageId=<?=$iAttachment?>")'><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></span><br />
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
                                </table>

					  </form>

				    </div>
				  </div>

				  <div id="Msg<?= $iId ?>" class="msgOk" style="display:none;"></div>

<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Booking Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&BookingId={$BookingId}&Vendor={$Supplier}");
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