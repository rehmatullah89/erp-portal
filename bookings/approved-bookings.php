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
        $BookingId              = IO::strValue("BookingId");
        $SortBy                 = IO::strValue("SortBy");
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
        
//        
	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

                $BookingId              = IO::strValue("BookingId");
                $SortBy                 = IO::strValue("SortBy");
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
	}

        $sSamplePickList = getList("tbl_sample_picks", "id", "title", "status='A'");
        $iAuditorTypes   = explode(",", getDbValue("auditor_types", "tbl_users", "id='{$_SESSION['UserId']}'"));
        $sSuppliersList  = getList("tbl_suppliers", "id", "supplier", "id IN ({$_SESSION['Suppliers']})");
	$sVendorsList    = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList     = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
        $sShippingPorts  = getList("tbl_shipping_ports", "id", "port_name", "booking_form='Y'");
        //$sStages         = explode(",", getDbValue("stages", "tbl_reports", "id='39'"));
        //$sServicesList   = getList("tbl_audit_stages", "id", "stage", "code IN ('". implode("', '", $sStages)."')", "position");        
        $sServicesList      = getList("tbl_audit_services", "id", "service");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
    <script type="text/javascript" src="scripts/jquery.js"></script>  
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
			    <h1><img src="images/h1/quonda/approved-bookings.png" vspace="10" alt="" title="" /></h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>

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
					    <select name="Factory">
						  <option value="">All Factories</option>
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
                                  
                                  <td width="55">Sort By</td>
			          <td width="200">
					    <select name="SortBy">
                                                  <option value=""></option>
                                                  <option value="CD" <?=($SortBy == 'CD'?'selected':'')?>>Created Date</option>
                                                  <option value="MD" <?=($SortBy == 'MD'?'selected':'')?>>Modified Date</option>
                                                  <option value="RD" <?=($SortBy == 'RD'?'selected':'')?>>Requested Date</option>
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
        $sOrderBy    = " ORDER BY id DESC ";
        
        if($SortBy == 'CD')
            $sOrderBy    = " ORDER BY created_at DESC ";
        else if($SortBy == 'MD')
            $sOrderBy    = " ORDER BY modified_at DESC ";
        else if($SortBy == 'RD')
            $sOrderBy    = " ORDER BY inspection_date DESC ";

        $sConditions = " WHERE status='A' ";
        
        if ($Factory > 0)
		$sConditions .= " AND factory_id='$Factory' ";
        
	if ($BookingId != "")
		$sConditions .= " AND id LIKE '%$BookingId%' ";

	if ($Supplier > 0)
		$sConditions .= " AND vendor_id='$Supplier' ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_bookings", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_bookings $sConditions $sOrderBy LIMIT $iStart, $iPageSize";
    	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
                                        <td width="3%">&nbsp;</td>  
				      <td width="8%">#</td>
				      <td width="19%">Client</td>
				      <td width="20%">Factory</td>
				      <td width="15%">Inspection Date</td>
                                      <td width="22%">Services</td>
				      <td width="13%" class="center">Options</td>
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
                $iServices  		= explode(",", $objDb->getField($i,"services"));
                $iSamplePickFor         = explode(",", $objDb->getField($i,"sample_pick"));
                $sPorts                 = $objDb->getField($i,"ports");
                $sRemarks      		= $objDb->getField($i,"notes");
                $sStatus      		= $objDb->getField($i,"status");
                $sStatusComments	= $objDb->getField($i,"status_comments");            
                $iCreatedBy             = $objDb->getField($i,"created_by");
                $sContactPersonName     = $objDb->getField($i,"cp_name");
                $sContactPersonPhone    = $objDb->getField($i,"cp_phone");
                $sContactPersonEmail    = $objDb->getField($i,"cp_email");
                $sContactPersonFax      = $objDb->getField($i,"cp_fax");
                $sAssignedTo            = $objDb->getField($i,"assigned_to");
                
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
                                      <td width="3%" <?=($sAssignedTo > 0)?'style=background-color:#32CD32 !important':'' ?>>&nbsp;</td>
				      <td width="8%"><?= ("B".str_pad($iId, 5, '0', STR_PAD_LEFT)) ?></td>
				      <td width="19%"><span id="Brand_<?= $iId ?>"><?= $sBrandsList[$iBrand] ?></span></td>
				      <td width="20%"><span id="Vendor_<?= $iId ?>"><?= $sVendorsList[$iFactory] ?></span></td>
				      <td width="15%"><span id="InspectionDate_<?= $iId ?>"><?= $sReqInspectionDate ?></span></td>
                                      <td width="22%"><span id="Stage_<?= $iId ?>"><?= getDbValue("GROUP_CONCAT(service SEPARATOR ',')", "tbl_audit_services", "id IN ({$objDb->getField($i,"services")})") ?></span></td>
                                      <td width="13%" class="center">
<?

		if ($sUserRights['Edit'] == "Y")
		{
                    if($sAssignedTo > 0)
                    {
?>
                        <a href="bookings/edit-assigned-booking.php?Id=<?= $iId ?>&Factory=<?=$iFactory?>&Auditor=<?=$sAssignedTo?>&Brand=<?=$iBrand?>" class="lightview" title="Assign Auditor :: :: width: 950, height: 326" rel="iframe"><img src="images/icons/done.png" width="18" height="18" alt="Edit Assigned Auditor" title="Edit Assigned Auditor" /></a>
<?
                    }
                    else
                    {
?>
                        <a href="bookings/assign-booking.php?Id=<?= $iId ?>&Factory=<?=$iFactory?>&Brand=<?=$iBrand?>" class="lightview" title="Assign Booking :: :: width: 950, height: 326" rel="iframe"><img src="images/icons/attendance2.png" width="18" height="18" alt="Assign Booking" title="Assign Booking" /></a>
<?
                    }
?>
                                          <a href="bookings/view-comments.php?Id=<?= $iId ?>" class="lightview" title="Post Comments :: :: width: 900, height: 650" rel="iframe"><img src="images/icons/chat.gif" width="18" height="18" alt="Post Comments" title="Post Comments" /></a>
				        &nbsp;
<?
		}
?>
                                        <a href="bookings/export-booking-form.php?Id=<?= $iId ?>"><img src="images/icons/pdf.gif" width="16" height="16" hspace="1" alt="Booking Form" title="Booking Form" /></a>&nbsp;
                                        <a href="bookings/view-booking-form.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Booking Form : <?= "B".str_pad($iId, 5, '0', STR_PAD_LEFT); ?> :: :: width: 900, height: 650"><img src="images/icons/view.gif" width="16" height="16" hspace="1" alt="View" title="View" /></a>&nbsp;
				      </td>
				    </tr>
				  </table>
<!------ Contents Removed ------->
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