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

	$PageId          = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Brand           = IO::intValue("Brand");
	$Vendor          = IO::intValue("Vendor");
	$AuditDate       = IO::strValue("AuditDate");
        $StartHour       = IO::strValue("StartHour");
        $StartMinutes    = IO::strValue("StartMinutes");
        $EndHour         = IO::strValue("EndHour");
        $EndMinutes      = IO::strValue("EndMinutes");
        $StyleId         = IO::intValue("StyleId");
        $StyleNo         = IO::strValue("StyleNo");
        $OrderNo         = IO::strValue("OrderNo");
        $OrderNos        = IO::getArray("OrderNo");
        $Colors          = IO::getArray("Colors");
        $Sizes           = IO::getArray("Sizes");
        $SampleSize      = IO::intValue("SampleSize");     
        $BookingCode     = IO::strValue("BookingCode");     
	$AuditStage      = "F";

        $iBookingManager = (int)getDbValue("COUNT(1)", "tbl_users", "id='{$_SESSION['UserId']}' AND FIND_IN_SET('15', auditor_types)");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Brand           = IO::intValue("Brand");
                $Vendor          = IO::intValue("Vendor");
                $AuditDate       = IO::strValue("AuditDate");
                $StartHour       = IO::strValue("StartHour");
                $StartMinutes    = IO::strValue("StartMinutes");
                $EndHour         = IO::strValue("EndHour");
                $EndMinutes      = IO::strValue("EndMinutes");
                $StyleId         = IO::intValue("StyleId");
                $StyleNo         = IO::strValue("StyleNo");
                $OrderNo         = IO::strValue("OrderNo");
                $OrderNos        = IO::getArray("OrderNo");
                $Colors          = IO::getArray("Colors");
                $Sizes           = IO::getArray("Sizes");
                $SampleSize      = IO::intValue("SampleSize"); 
                $BookingCode     = IO::strValue("BookingCode");     
	}
	

	$sBrandsList         = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
	$sAllVendorsList     = getList("tbl_vendors", "id", "vendor");
            
	if(@in_array($_SESSION["UserType"], array("TPH")) && @strpos($_SESSION["Email"], "@triumph.com") === FALSE)      
        {
                $sAuditorSubQuery = " AND ";
                $sMyVendors = explode(",", $_SESSION['Vendors']);

                if(count($sMyVendors) > 1)
                    $sAuditorSubQuery .= " ( ";
                
                $iIndex = 0;
                foreach($sMyVendors as $iMyVendor)
                {
                    if($iMyVendor != 0)
                    {
                        if($iIndex == 0)
                            $sAuditorSubQuery .= " FIND_IN_SET(".trim($iMyVendor).", vendors) ";
                        else
                            $sAuditorSubQuery .= " OR FIND_IN_SET(".trim($iMyVendor).", vendors) ";

                        $iIndex ++;    
                    } 
                }
                
                 if(count($sMyVendors) > 1)
                    $sAuditorSubQuery .= " ) ";
                 
                $sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A' AND email NOT LIKE '%@3-tree.com%' AND email NOT LIKE '%@triumph.com' AND email NOT LIKE '%@apparelco.com' AND user_type='{$_SESSION['UserType']}' $sAuditorSubQuery");
        }
        else
            $sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");
        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/jquery.js"></script>

  <script type="text/javascript">
  <!--
		jQuery.noConflict( );
  -->
  </script>

  <script type="text/javascript" src="scripts/quonda/bookings.js"></script>
  <script type="text/javascript" src="scripts/jquery.tokeninput.js"></script>
  <link type="text/css" rel="stylesheet" href="css/jquery.tokeninput.css" />
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
			    <h1>Bookings</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-booking.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Create Booking</h2>

				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
				    <td width="50%">

						<table id="ReportTable" border="0" cellpadding="3" cellspacing="0" width="100%">

                                            <tr>
                                                <td width="95">Brand<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
                                <select name="Brand" id="Brand" style="width: 115px;"  onchange="getListValues('Brand', 'Vendor', 'BrandVendors');">    
							  <option value=""></option>
<?
		foreach ($sBrandsList as $iBrand => $sBrand)
		{
?>
			            	  <option value="<?= $iBrand ?>"<?= (($iBrand == $Brand) ? " selected" : "") ?>><?= $sBrand ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr> 
                        
					    <tr>
						  <td>Vendor<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
                                                      <select id="Vendor" name="Vendor" style="width: 115px;" onchange="setAutoStyles(this.value); clearPos('');">
							  <option value=""></option>
<?
		if ($Brand > 0)
		{
			
			$sBrandVendors = getDbValue("vendors","tbl_brands", "id = '$Brand'");
                        $sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND id IN ({$sBrandVendors}) AND parent_id='0' AND sourcing='Y'");
		}
		
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Audit Date<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>

						    <table border="0" cellpadding="0" cellspacing="0" width="116">
							  <tr>
							    <td width="82"><input type="text" name="AuditDate" id="AuditDate" value="<?= (($AuditDate == "") ? date('Y-m-d') : $AuditDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
							    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
							  </tr>
 						    </table>

						  </td>
					    </tr>

					    <tr>
						  <td>Start Time<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="StartHour" style="width: 55px;">
<?
		for ($i = 0; $i <= 23; $i ++)
		{
?>
	  	        			  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$StartHour == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
						    </select>

						    <select name="StartMinutes" style="width: 55px;">
<?
		for ($i = 0; $i <= 59; $i += 5)
		{
?>
	  	        			  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$StartMinutes == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>End Time<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="EndHour" style="width: 55px;">
<?
		for ($i = 0; $i <= 23; $i ++)
		{
?>
	  	        			  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$EndHour == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
						    </select>

						    <select name="EndMinutes" style="width: 55px;">
<?
		for ($i = 0; $i <= 59; $i += 5)
		{
?>
	  	        			  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$EndMinutes == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>
                                                    <input type="hidden" name="SampleSize" id="SampleSize" value="0"/>
 
					  </table>

					</td>

					<td width="50%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
					  	  <td width="105">Style No<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
				            <div>
				                <input type="hidden" id="StyleId" name="StyleId" value="<?= $StyleId ?>" />
								<input type="text" id="StyleNo" name="StyleNo" value="<?= htmlentities($StyleNo, ENT_QUOTES) ?>" size="20" maxlength="50" autocomplete="off" class="textbox" />

								<div id="Choices_StyleNo" class="autocomplete" style="display:none;"></div>

								<script type="text/javascript">
								<!--
									function clearPos(sId)
									{
										jQuery("#OrderNo" + sId).val("");
										jQuery("#StyleNo" + sId).html("");
                                                                                jQuery("#Commissions" + sId).html("");
										jQuery("#Colors" + sId).html("");
										jQuery("#Sizes" + sId).html("");
									}
								
									function setAutoStyles(iVendor)
									{
                                                                                iBrand = document.getElementById("Brand").value;
                                                                                new Ajax.Autocompleter("StyleNo", "Choices_StyleNo", ("ajax/get-auto-styles.php?Brand="+iBrand+"&Vendor="+iVendor), { paramName:"Keywords", minChars:3, afterUpdateElement:getStyleId } );
                                                                        }

<?
		if ($_POST)
		{
?>
									new Ajax.Autocompleter("StyleNo", "Choices_StyleNo", "ajax/get-auto-styles.php?Vendor=<?= $Vendor ?>&Brand=<?=$Brand?>", { paramName:"Keywords", minChars:3, afterUpdateElement:getStyleId } );
<?
		}
?>
                                                                        function getStyleId(text, li)
                                                                        {
                                                                                        jQuery("#StyleId").val(li.id);
                                                                            
                                                                                        jQuery.post("ajax/quonda/get-auto-pos.php",
											{ Style:li.id },

											function (sResponse)
											{
												jQuery("#OrderNo").html("");
                                                                                                
												if (sResponse != "")
												{
													var sOptions = sResponse.split("||");
                                                                                                       
                                                                                                        var sOptions = sResponse.split("|-|");

                                                                                                        for (var i = 0; i < sOptions.length; i ++)
                                                                                                        {
                                                                                                            
                                                                                                            var sOption = sOptions[i].split("||");
                                                                                                            
                                                                                                            if(sOption.length > 0)
                                                                                                            {
                                                                                                                jQuery("#OrderNo").get(0).options[(i)] = new Option(sOption[1], sOption[0], false, false);
                                                                                                            }
                                                                                                        }
                                                                                                        jQuery("#OrderNo").get(0).options[0].remove();
												}
											},

											"text");

                                                                        }

									jQuery(document).on("keydown", "#StyleNo", function(e)
									{
										if (e.which == 8 || e.which == 46)
										{
											jQuery(this).val("");
											jQuery("#StyleId").val("");

											jQuery("#OrderNo").html("");
                                                                                        jQuery("#OrderNo").get(0).options[0] = new Option("", "", false, false);
                                                                                        
											jQuery("#Colors").html("");
											jQuery("#Sizes").html("");
                                                                                        
										}
									});
                                                                        
                                                                        function getPoColorSizes(Index)
                                                                        {
                                                                                jQuery.post("ajax/quonda/get-po-colors.php",
											{ Po: jQuery('#OrderNo'+Index).val() },

											function (sResponse)
											{
												jQuery("#Colors"+Index).html("");


												if (sResponse != "")
												{
                                                                                                        var sOptions = sResponse.split("|-|");

                                                                                                        for (var i = 1; i < sOptions.length; i ++)
                                                                                                        {
                                                                                                                jQuery("#Colors"+Index).get(0).options[(i - 1)] = new Option(sOptions[i], sOptions[i], false, false);
                                                                                                        }
                                                                                               
												}
											},

											"text");


                                                                                jQuery.post("ajax/quonda/get-po-sizes.php",
											{ Po: jQuery('#OrderNo'+Index).val() },

											function (sResponse)
											{
												jQuery("#Sizes"+Index).html("");


												if (sResponse != "")
												{
													var sOptions = sResponse.split("|-|");

													for (var i = 1; i < sOptions.length; i ++)
													{
														var sOption = sOptions[i].split("|");

														jQuery("#Sizes"+Index).get(0).options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);
													}
												}
											},

											"text");
                                                                                        
                                                                                jQuery.post("ajax/quonda/get-po-commissions.php",
											{ Po: jQuery('#OrderNo'+Index).val() },

											function (sResponse)
											{
												jQuery("#Commissions"+Index).html("");


												if (sResponse != "")
												{
                                                                                                        var sOptions = sResponse.split("|-|");

                                                                                                        for (var i = 1; i < sOptions.length; i ++)
                                                                                                        {
                                                                                                                jQuery("#Commissions"+Index).get(0).options[(i - 1)] = new Option(sOptions[i], sOptions[i], false, false);
                                                                                                        }
                                                                                               
												}
											},

											"text");        
                                                                        }
                                                                        
                                                                        function getCommissionColorSizes(Index)
                                                                        {
                                                                                jQuery.post("ajax/quonda/get-commission-colors.php",
											{ Po: jQuery('#OrderNo'+Index).val(), 
                                                                                          Commission: jQuery('#Commissions'+Index).val()  
                                                                                        },

											function (sResponse)
											{
												jQuery("#Colors"+Index).html("");


												if (sResponse != "")
												{
                                                                                                        var sOptions = sResponse.split("|-|");

                                                                                                        for (var i = 1; i < sOptions.length; i ++)
                                                                                                        {
                                                                                                                jQuery("#Colors"+Index).get(0).options[(i - 1)] = new Option(sOptions[i], sOptions[i], false, false);
                                                                                                        }
                                                                                               
												}
											},

											"text");


                                                                                jQuery.post("ajax/quonda/get-commission-sizes.php",
											{ Po: jQuery('#OrderNo'+Index).val(),
                                                                                          Commission: jQuery('#Commissions'+Index).val()  
                                                                                        },

											function (sResponse)
											{
												jQuery("#Sizes"+Index).html("");


												if (sResponse != "")
												{
													var sOptions = sResponse.split("|-|");

													for (var i = 1; i < sOptions.length; i ++)
													{
														var sOption = sOptions[i].split("|");

														jQuery("#Sizes"+Index).get(0).options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);
													}
												}
											},

											"text");
                                                                                       
                                                                        }
                                                                
                                                                
								-->
								</script>
						    </div>
						  </td>
					    </tr>

                                            <tr>
						  <td>Order No<span class="mandatory">*</span>
                                                  <!--<br/><br/>[ <a href="./" onclick="selectAll('OrderNo'); return false;">Select All</a>]</td>-->
						  <td align="center">:</td>

						  <td>
                                                      <select name="OrderNo[]" size="5" multiple id="OrderNo" onchange="getPoColorSizes('');" style="min-width:160px;">
<?
        $sOrdersList = getList("tbl_po", "id", "order_no", "FIND_IN_SET('$StyleId', styles)");
        
	foreach ($sOrdersList as $sKey => $sValue)
	{
?>
	  	        		      <option value="<?= $sKey ?>"<?= (@in_array($sKey, $OrderNos) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
	}
?>
					        </select>
						  </td>
					    </tr>

                                               <tr valign="top">
						  <td>Commission No</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Commissions[]" id="Commissions" onchange="getCommissionColorSizes('');" size="5" multiple style="min-width:160px;">
<?
	$sCommissions = getList("tbl_po_colors", "DISTINCT line", "line", "FIND_IN_SET(po_id, '$Po')", "color");

	foreach ($sCommissions as $sKey => $sValue)
	{
?>
	  	        		      <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Sizes)) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
	}
?>
					        </select>
						  </td>
					    </tr> 
                                              
					    <tr valign="top">
                                                <td id="TNCColor">Colors<span class="mandatory">*</span><br/><br/>[ <a href="./" onclick="selectAll('Colors'); return false;">Select All</a>]</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Colors[]" id="Colors" size="5" multiple style="min-width:160px;">
<?
        $Po      = @$OrderNos[0];
	$sColors = getList("tbl_po_colors", "DISTINCT(color)", "color", "po_id='$Po' AND style_id='$StyleId'", "color");

	foreach ($sColors as $sKey => $sValue)
	{
?>
	  	        		      <option value="<?= formValue($sValue) ?>"<?= ((@in_array($sValue, $Colors)) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
	}
?>
					        </select>
						  </td>
					    </tr>

					    <tr valign="top">
						  <td id="TNCSizes">Sizes<span class="mandatory">*</span><br/><br/>[ <a href="./" onclick="selectAll('Sizes'); return false;">Select All</a>]</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Sizes[]" id="Sizes" size="5" multiple style="min-width:160px;">
<?
	$sSizes = getList("tbl_sizes", "id", "size", "id IN(SELECT DISTINCT(size_id) FROM tbl_po_quantities WHERE po_id='$Po')", "position");

	foreach ($sSizes as $sKey => $sValue)
	{
?>
	  	        		      <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $Sizes)) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
	}
?>
					        </select>
						  </td>
					    </tr>
                                           
					  </table>

					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="100">Booking Code</td>
			          <td width="130"><input type="text" name="BookingCode" value="<?= $BookingCode ?>" class="textbox" maxlength="50" size="10" style="width: 85%;"/></td>

                                  <td width="55">Vendor</td>

			          <td width="130">
					    <select name="Vendor" id="Vendor2" style="width:90%;">
						  <option value="">All Vendors</option>
<?
    $sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
    
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>
                                  
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>
				
			    </form>

			    <div class="tblSheet">
<?
	$sVendorCountriesList = getList("tbl_vendors", "id", "country_id");
	$sCountryHoursList    = getList("tbl_countries", "id", "hours");
        $sUsersList           = getList("tbl_users", "id", "name");
        $sBrandsList          = getList("tbl_brands", "id", "brand");
	
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "WHERE id != '0' ";
	
	if ($BookingCode != "")
		$sConditions .= " AND id LIKE '%$BookingCode%' ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_bookings", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT tbl_bookings.*,
                        (SELECT audit_code from tbl_qa_reports Where booking_id=tbl_bookings.id) as _AuditCode
	         FROM tbl_bookings
	         $sConditions
	         ORDER BY id DESC
	         LIMIT $iStart, $iPageSize";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="4%">#</td>
				      <td width="6%">Booking Code</td>
                                      <td width="6%">Audit Code</td>
				      <td width="22%">Brand</td>
				      <td width="22%">Vendor</td>
				      <td width="9%">Audit Date</td>
				      <td width="9%">Start Time</td>
				      <td width="9%">End Time</td>
				      <td width="13%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId            = $objDb->getField($i, 'id');
                $sAuditCode     = $objDb->getField($i, '_AuditCode');
		$sBookingCode   = "B".str_pad($iId, 5, 0, STR_PAD_LEFT);
		$iAuditor       = $objDb->getField($i, 'auditor_id');
		$iVendor        = $objDb->getField($i, 'vendor_id');
		$iBrand         = $objDb->getField($i, 'brand_id');
                $sAuditDate     = $objDb->getField($i, 'inspection_date');
		$sStartTime     = $objDb->getField($i, 'start_time');
		$sEndTime       = $objDb->getField($i, 'end_time');
		$sPoIds         = $objDb->getField($i, 'pos');
		$iStyleId       = $objDb->getField($i, 'style_id');
		$sColors        = $objDb->getField($i, 'colors');
		$sSizes         = $objDb->getField($i, 'sizes');
                $sCommissions   = $objDb->getField($i, 'commissions');
		$iSampleSize    = $objDb->getField($i, 'sample_size');
		$sStatus        = $objDb->getField($i, 'status');
		
		$iApprovedBy     = $objDb->getField($i, 'approved_by');
                $iRejectedBy     = $objDb->getField($i, 'rejected_by');
                $iAssignedBy     = $objDb->getField($i, 'assigned_by');
                $iCreatedBy      = $objDb->getField($i, 'created_by');
                $iModifiedBy     = $objDb->getField($i, 'modified_by');
                
                $iApprovedAt     = $objDb->getField($i, 'approved_at');
                $iRejectedAt     = $objDb->getField($i, 'rejected_at');
                $iAssignedAt     = $objDb->getField($i, 'assigned_at');
                $iCreatedAt      = $objDb->getField($i, 'created_at');
                $iModifiedAt     = $objDb->getField($i, 'modified_at');
                
		
		$iCountry = $sVendorCountriesList[$iVendor];
		$iHours   = $sCountryHoursList[$iCountry];
		
		$sStartTime = date("H:i:s", (strtotime($sStartTime) + ($iHours * 3600)));
		$sEndTime   = date("H:i:s", (strtotime($sEndTime) + ($iHours * 3600)));
					
		@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
		@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);


		if ($sPoIds != "")
			$sOrderNo = getDbValue("GROUP_CONCAT(order_no SEPARATOR ', ')", "tbl_po", "FIND_IN_SET(id, '$sPoIds')");

		if ($iStyleId > 0)
			$sStyleNo = getDbValue("style", "tbl_styles", "id='$iStyleId'");
		
		$iAudit = (int)getDbValue("id", "tbl_qa_reports", "booking_id='$iId'");
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>"<?= (($sApproved == 'N') ? ' style="background:#ffeaea;"' : '') ?> id="Record<?= $iId ?>">
				      <td width="4%"><?= ($iStart + $i + 1) ?></td>
				      <td width="6%"><?= $sBookingCode ?></td>
                                      <td width="6%"><?= $sAuditCode ?></td>
				      <td width="22%"><span id="Brand_<?= $iId ?>"><?= $sBrandsList[$iBrand] ?><?= (($iGroup > 0) ? " (G)" : "") ?></span></td>
				      <td width="22%"><span id="Vendor_<?= $iId ?>"><?= $sAllVendorsList[$iVendor] ?></span></td>
				      <td width="9%"><span id="Date_<?= $iId ?>"><?= formatDate($sAuditDate) ?></span></td>
				      <td width="9%"><span id="StartTime_<?= $iId ?>"><?= (str_pad($iStartHour, 2, '0', STR_PAD_LEFT).":".str_pad($iStartMinutes, 2, '0', STR_PAD_LEFT)." ".$sStartAmPm) ?></span></td>
				      <td width="9%"><span id="EndTime_<?= $iId ?>"><?= (str_pad($iEndHour, 2, '0', STR_PAD_LEFT).":".str_pad($iEndMinutes, 2, '0', STR_PAD_LEFT)." ".$sEndAmPm) ?></span></td>

				      <td width="13%" class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
                    if(getDbValue("COUNT(1)", "tbl_qa_lot_sizes", "audit_id='$iAudit'") == 0 && $iBookingManager > 0)
                    {
?>
                                          <a href="quonda/toggle-booking-status.php?Id=<?= $iId ?>&Brand=<?=$sBrandsList[$iBrand];?>&Vendor=<?=$sAllVendorsList[$iVendor]?>&VendorId=<?=$iVendor?>&AuditDate=<?=formatDate($sAuditDate)?>&Auditor=<?=$iAuditor?>&Status=<?=$sStatus?>" class="lightview" rel="iframe" title="Booking Status Form : <?= "B".str_pad($iId, 5, '0', STR_PAD_LEFT); ?> :: :: width: 450, height: 300"><img src="images/icons/<?=(($sAuditCode && $sStatus == 'A') != ""?'closed':'working')?>.png" width="16" height="16" border="0" alt="Toggle Booking Status" title="Toggle Booking Status" /></a>  &nbsp;
                                        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" id="EditOpt<?=$iId?>" width="16" height="16" alt="Edit" title="Edit" /></a>
<?
					}
		}

		if ($sUserRights['Delete'] == "Y" && $iAudit == 0)
		{
?>
				        <a href="quonda/delete-booking.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Booking?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
                                        <a href="quonda/view-booking.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Booking # <?= $sBookingCode ?> :: :: width: 850, height: 550"><img src="images/icons/view.gif" width="16" height="16" hspace="1" alt="View" title="View" /></a>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					    <tr valign="top">
						  <td width="50%">

                                    <table id="MyTable" border="0" cellpadding="3" cellspacing="0" width="100%">
                                            <tr>
						  <td width="100">Brand<span class="mandatory">*</span></td>
						  <td width="20"align="center">:</td>

						  <td>

                                                    <select name="Brand" id="Brand<?= $i ?>"  onchange="getListValues('Brand<?= $i ?>', 'Vendor<?= $i ?>', 'BrandVendors');">
							  <option value=""></option>
<?
                foreach ($sBrandsList as $iBrandId => $sBrand)
		{
?>
			            	  <option value="<?= $iBrandId ?>"<?= (($iBrandId == $iBrand) ? " selected" : "") ?>><?= $sBrand ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr> 

							  <tr>
							    <td>Vendor<span class="mandatory">*</span></td>
							    <td align="center">:</td>
							    <td>
                                                                <select id="Vendor<?= $i ?>" name="Vendor" onchange="setAutoStyles<?= $iId ?>(this.value,<?=$i?>); clearPos('<?= $iId ?>');">
                                            <option value=""></option>
<?               
		$sBrandVendors  = getDbValue("GROUP_CONCAT(DISTINCT(vendor_id) SEPARATOR ',')", "tbl_po", "brand_id = '$iBrand'");
		$VendorsByBrand = getList("tbl_vendors", "id", "vendor", "id IN ($sBrandVendors) AND id IN ({$_SESSION['Vendors']})");

		foreach ($VendorsByBrand as $sKey => $sValue)
		{
?>
			            	  		<option value="<?= $sKey ?>"<?= (($sKey == $iVendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
                                        </select>
							    </td>
							  </tr>

							  <tr>
							    <td>Audit Date<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>

								  <table border="0" cellpadding="0" cellspacing="0" width="116">
								    <tr>
								 	  <td width="82"><input type="text" <?=($iReport == 34)?'disabled':'';?> name="AuditDate" id="AuditDate<?= $iId ?>" value="<?= $sAuditDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AuditDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
									  <td width="34">
<?
                                                                            if($iReport == 34){
?>
                                                                              &nbsp;
<?
                                                                            }else{
?>
                                                                              <img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('AuditDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
<?
                                                                            }
?>
								    </tr>
								  </table>

							    </td>
							  </tr>

							  <tr>
							    <td>Start Time<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
                                                                <select id="StartHour<?=$iId?>" name="StartHour" <?=($iReport == 34)?'disabled':'';?>>
<?
		for ($j = 0; $j <= 23; $j ++)
		{
?>
	  	        			  		<option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iStartHour == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
								  </select>

								  <select id="StartMinutes<?=$iId?>" name="StartMinutes" <?=($iReport == 34)?'disabled':'';?>>
<?
		for ($j = 0; $j <= 59; $j ++)
		{
?>
	  	        			  		<option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iStartMinutes == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>

							  <tr>
							    <td>End Time<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
								  <select id="EndHour<?=$iId?>" name="EndHour" <?=($iReport == 34)?'disabled':'';?>>
<?
		for ($j = 0; $j <= 23; $j ++)
		{
?>
	  	        			  		<option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iEndHour == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
								  </select>

								  <select id="EndMinutes<?=$iId?>" name="EndMinutes" <?=($iReport == 34)?'disabled':'';?>>
<?
		for ($j = 0; $j <= 59; $j ++)
		{
?>
	  	        			  		<option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iEndMinutes == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>
                                        <input type="hidden" name="SampleSize" value="<?=$iSampleSize?>"/>
<?
/*
?>
                                                    <tr id="SampleSizeId<?=$iId?>">
							    <td>Sample Size</td>
							    <td align="center">:</td>

							    <td>
								  <select name="SampleSize">
								    <option value=""></option>
<?
		foreach ($iAqlChart as $iQty => $iAql)
		{
?>
			            	  		<option value="<?= $iQty ?>"<?= (($iQty == $iSampleSize) ? " selected" : "") ?>><?= $iQty ?></option>
<?
		}

		if (!@isset($iAqlChart[$iSampleSize]) && $iSampleSize > 0)
		{
?>
						    	    <option value="<?= $iSampleSize ?>" selected><?= $iSampleSize ?></option>
<?
		}
                
                if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE")))
                {
?>
						    	    <option value="0"<?= (($iSampleSize == 0) ? " selected" : "") ?>>Custom</option>
<?
                }
?>
						    	  </select>
							    </td>
							  </tr>
<?
*/
?>
							  <tr>
							    <td></td>
							    <td></td>

							    <td>
								  <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
								  <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
 							    </td>
							  </tr>
						    </table>

						  </td>

						  <td width="50%">

						    <table border="0" cellpadding="3" cellspacing="0" width="100%">
							  <tr>
							    <td width="90">Style No<span class="mandatory">*</span></td>
							    <td width="20" align="center">:</td>

							    <td>
                                                                <input type="hidden" id="StyleId<?= $iId ?>" name="StyleId" value="<?= $iStyleId ?>" />
                                                                <input type="text" id="StyleNo<?= $iId ?>" name="StyleNo" value="<?= htmlentities(getDbValue("style", "tbl_styles", "id='$iStyleId'"), ENT_QUOTES) ?>" size="20" maxlength="50" autocomplete="off" class="textbox" />
                                                                
                                                                <div id="Choices_StyleNo<?= $iId ?>" class="autocomplete" style="display:none;"></div>

								<script type="text/javascript">
								<!--
									function setAutoStyles<?= $iId ?>(iVendor,id)
									{
                                                                            var ibrand = document.getElementById("Brand"+id).value;
                                                                            new Ajax.Autocompleter("StyleNo<?= $iId ?>", "Choices_StyleNo<?= $iId ?>", ("ajax/get-auto-styles.php?Vendor=" + iVendor+"&Brand="+ibrand), { paramName:"Keywords", minChars:3, afterUpdateElement:getStyleId<?= $iId ?> } );
                                                                        }

									new Ajax.Autocompleter("StyleNo<?= $iId ?>", "Choices_StyleNo<?= $iId ?>", "ajax/get-auto-styles.php?Vendor=<?= $iVendor ?>&Brand=<?=$iBrand?>", { paramName:"Keywords", minChars:3, afterUpdateElement:getStyleId<?= $iId ?> } );



                                                                        function getStyleId<?= $iId ?>(text, li)
                                                                        {
                                                                            jQuery("#StyleId<?= $iId ?>").val(li.id);

                                                                          	jQuery.post("ajax/quonda/get-auto-pos.php",
											{ Style: li.id },

											function (sResponse)
											{
												jQuery("#OrderNo<?= $iId ?>").html("");
                                                                                               
												if (sResponse != "")
												{
                                                                                                        var sOptions = sResponse.split("|-|");
                                                                                                            
                                                                                                        for (var i = 0; i < sOptions.length; i ++)
                                                                                                        {
                                                                                                                var sOption = sOptions[i].split("||");
                                                                                                                jQuery("#OrderNo<?= $iId ?>").get(0).options[(i)] = new Option(sOption[1], sOption[0], false, false);                                                                                                                
                                                                                                        }                                                                                                      
												}
											},

											"text");
                                                                        }


									jQuery(document).on("keydown", "#OrderNo<?= $iId ?>", function(e)
									{
										if (e.which == 8 || e.which == 46)
										{
											jQuery(this).val("");
											jQuery("#StyleId<?= $iId ?>").val("");
											jQuery("#OrderNo<?= $iId ?>").html("");
                                                                                        jQuery("#StyleNo<?= $iId ?>").get(0).options[0] = new Option("", "", false, false);
                                                                                        jQuery("#Colors<?= $iId ?>").html("");
											jQuery("#Sizes<?= $iId ?>").html("");
                                                                                        jQuery("#Commissions<?= $iId ?>").html("");
										}
									});
								-->
								</script>
							    </td>
							  </tr>

							  <tr>
							    <td>Order No<span class="mandatory">*</span>
                                                            <!--<br/><br/>[ <a href="./" onclick="selectAll('OrderNo<?= $iId ?>'); return false;">Select All</a>]</td>-->
							    <td align="center">:</td>

							    <td>
								<select name="OrderNo[]" size="5" multiple id="OrderNo<?= $iId ?>" onchange="getPoColorSizes(<?= $iId ?>);" style="min-width:160px;">
<?
		$sOrdersList = getList("tbl_po", "id", "order_no", "FIND_IN_SET('$iStyleId', styles)");
                
		foreach ($sOrdersList as $sKey => $sValue)
		{
?>
	  	        		      	    <option value="<?= $sKey ?>"<?= (@in_array($sKey, explode(",", $sPoIds)) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
		}
?>
                                                                </select>
							    </td>
							  </tr>
                                            <tr valign="top">
						  <td>Commission No</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Commissions[]" id="Commissions<?= $iId ?>" onchange="getCommissionColorSizes(<?= $iId ?>);" size="5" multiple style="min-width:160px;">
<?
        $iCommissions    = explode(",", $sCommissions);
	$sCommissionList = getList("tbl_po_colors", "DISTINCT line", "line", "FIND_IN_SET(po_id, '$sPoIds') AND line!='' ", "color");

	foreach ($sCommissionList as $sKey => $sValue)
	{
?>
	  	        		      <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $iCommissions)) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
	}
?>
					        </select>
						  </td>
					    </tr> 
							  <tr valign="top">
							    <td>Colors<span class="mandatory">*</span>
                                                            <br/><br/>[ <a href="./" onclick="selectAll('Colors<?= $iId ?>'); return false;">Select All</a>]</td>
							    <td align="center">:</td>

							    <td>
<?
		$sPoColors = @explode(",", $sColors);
		$sPoColors = @array_map("trim", $sPoColors);
		$sColors = getList("tbl_po_colors", "DISTINCT(color)", "color", "FIND_IN_SET(po_id, '$sPoIds') AND style_id='$iStyleId'", "color");
?>
						    	  <select name="Colors[]" id="Colors<?= $iId ?>" size="5" multiple style="min-width:160px;">
<?	
		foreach ($sColors as $sKey => $sValue)
		{
?>
	  	        		      	    <option value="<?= formValue($sValue) ?>"<?= ((@in_array($sValue, $sPoColors)) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
		}
?>
					       	 	  </select>
							    </td>
							  </tr>

							  <tr valign="top">
							    <td>Sizes<span class="mandatory">*</span>
                                                               <br/><br/>[ <a href="./" onclick="selectAll('Sizes<?= $iId ?>'); return false;">Select All</a>]</td>
							    <td align="center">:</td>

							    <td>
						    	  <select name="Sizes[]" id="Sizes<?= $iId ?>" size="5" multiple style="min-width:160px;">
<?
		$sPoSizes = @explode(",", $sSizes);
		$sPoSizes = @array_map("trim", $sPoSizes);
		$sSizes   = getList("tbl_sizes", "id", "size", "id IN (SELECT DISTINCT(size_id) FROM tbl_po_quantities WHERE FIND_IN_SET(po_id, '$sPoIds'))", "position");

		foreach ($sSizes as $sKey => $sValue)
		{
?>
	  	        		      	    <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $sPoSizes)) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
		}
?>
					       	 	  </select>
							    </td>
							  </tr>
                                                        
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
				      <td class="noRecord">No Booking Code Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&AuditCode={$BookingCode}&Auditor={$Auditor}&Group={$Group}&Vendor={$Vendor}&Parent={$Parent}&FromDate={$FromDate}&ToDate={$ToDate}&Approved={$Approved}&Department={$Department}&Report={$Report}&Completed={$Completed}");

    if ($iCount > 0 && ($BookingCode != "" || $Auditor != 0 || $Group != 0 || $Vendor != 0 || ($FromDate != "" && $ToDate != "") || $Region != 0 || $Completed != ""))
	{
?>

				<div class="buttonsBar" style="margin-top:4px;">
				  <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= (SITE_URL."quonda/export-audit-codes.php?AuditCode={$BookingCode}&Auditor={$Auditor}&Group={$Group}&Vendor={$Vendor}&Parent={$Parent}&FromDate={$FromDate}&ToDate={$ToDate}&Department={$Department}&Region={$Region}&Report={$Report}&Completed={$Completed}") ?>" />
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>