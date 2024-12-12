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

	$Id         = IO::intValue('Id');
        $Factory    = IO::intValue('Factory');
        $Brand      = IO::intValue('Brand');
        
        $iCountry           = getDbValue("country_id", "tbl_vendors", "id='$Factory'");
        $sAuditService      = getDbValue("COUNT(1)", "tbl_bookings", " id= '$Id' AND (FIND_IN_SET('55', services) OR FIND_IN_SET('59', services))");
        $sAuditTypes        = getList("tbl_audit_types", "id", "type", "", "position");
        $sReportTypes       = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");        
	$sReportsList       = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')");
        $sBrandsList        = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
        $sVendorsList       = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
        
        if ($_SESSION["UserType"] == "HOHENSTEIN")
            $sActiveAuditorsList = getList("tbl_users", "id", "name", "country_id='$iCountry' AND user_type='HOHENSTEIN' AND FIND_IN_SET(13, auditor_types)");
        else
            $sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A' AND FIND_IN_SET(13, auditor_types)");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <link type="text/css" rel="stylesheet" href="css/jquery.sunny.datepick.css" />
  <script type="text/javascript" src="scripts/jquery.js"></script>  
  <script type="text/javascript" src="scripts/jquery.sunny.plugin.min.js"></script>  
  <script type="text/javascript" src="scripts/jquery.sunny.datepick.js"></script> 

  <script type="text/javascript">
  <!--
		jQuery.noConflict( );
  -->
  </script>

  <script type="text/javascript" src="scripts/bookings/assign-booking.js"></script>
  <script type="text/javascript" src="scripts/jquery.tokeninput.js"></script>
  <link type="text/css" rel="stylesheet" href="css/jquery.tokeninput.css" />
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">
			    <form name="frmData" id="frmData" method="post" action="bookings/save-assign-booking.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Create Audit Code for Booking# <?= "B".str_pad($Id, 5, '0', STR_PAD_LEFT)?></h2>
                                <input type="hidden" name="BookingId" value="<?=$Id?>"/>
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
				    <td width="50%">
					<table id="ReportTable" border="0" cellpadding="3" cellspacing="0" width="100%">
                                                <tr>
						  <td width="95">Auditor<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
                                                      <input type="hidden" name="Report" id="ReportId" value="39"/>
                                                      <input type="hidden" name="AuditStage" id="AuditStage" value="<?=($sAuditService == 0?'ID':'F')?>"/>
						    <select name="Auditor" style="width:250px;">
							  <option value=""></option>
<?
		foreach ($sActiveAuditorsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Auditor) ? " selected" : "") ?>><?= $sValue ?></option>
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
                            <select name="Brand" id="Brand" style="width: 250px;">
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
						  <td>Factory<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
                                                      <select name="Vendor" onchange="setAutoPo(this.value); clearPos('');" style="width:250px;">
						  <option value=""></option>
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
					    </tr>
                                            
                                            <tr>
                                                <td width="95">Sampling Plan</td>
                                                <td width="20" align="center">:</td>
                                                <td>
                                                  <select name="SamplingPlan" style="width:250px;">
                                                        <option value="2"></option>
                                                            <option value="1" <?= (($SamplingPlan == "1") ? " selected" : "") ?>>I</option>
                                                            <option value="2" <?= (($SamplingPlan == "2") ? " selected" : "") ?>>II</option>
                                                  </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="80">AQL Level</td>
                                                <td width="20" align="center">:</td>
                                                <td>
                                                  <select name="AqlLevel" style="width:250px;">
                                                        <option value="2.5"></option>
                                                            <option value="1.5" <?= (($AqlLevel == "1.5") ? " selected" : "") ?>>1.5</option>
                                                            <option value="2.5" <?= (($AqlLevel == "2.5") ? " selected" : "") ?>>2.5</option>
                                                            <option value="4.0" <?= (($AqlLevel == "4.0") ? " selected" : "") ?>>4.0</option>
                                                            <option value="6.5" <?= (($AqlLevel == "6.5") ? " selected" : "") ?>>6.5</option>
                                                  </select>
                                                </td>
                                            </tr>
                                            <tr>
					  	  <td width="105">HOH I.O. No.</td>
						  <td width="20" align="center">:</td>

						  <td>
                                                      <input type="text" class="textbox" size="20" id="HohOrderNo" name="HohOrderNo" value="<?= $HohOrderNo ?>" style="width:245px;"/>
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
						    <select name="StartHour">
<?
		for ($i = 0; $i <= 23; $i ++)
		{
?>
	  	        			  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$StartHour == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
						    </select>

						    <select name="StartMinutes">
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
						    <select name="EndHour">
<?
		for ($i = 0; $i <= 23; $i ++)
		{
?>
	  	        			  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$EndHour == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
						    </select>

						    <select name="EndMinutes">
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
					  </table>

					</td>

					<td width="50%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
					  	  <td width="105">Order/Po No<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
				            <div>
				                <input type="hidden" id="Po" name="Po" value="<?= $Po ?>" />
								<input type="text" id="OrderNo" name="OrderNo" value="<?= htmlentities($OrderNo, ENT_QUOTES) ?>" size="20" maxlength="50" autocomplete="off" class="textbox" style="width:280px;" />

								<div id="Choices_OrderNo" class="autocomplete" style="display:none;"></div>

								<script type="text/javascript">
								<!--
									function clearPos(sId)
									{
										jQuery("#OrderNo" + sId).val("");
										jQuery("#AdditionalPO" + sId).val("");
										jQuery("#StyleNo" + sId).html("");
										jQuery("#Colors" + sId).html("");
										jQuery("#Sizes" + sId).html("");
									}
								
								
									function setAutoPo(iVendor)
									{
								       new Ajax.Autocompleter("OrderNo", "Choices_OrderNo", ("ajax/get-purchase-orders.php?Vendor=" + iVendor), { paramName:"Keywords", minChars:3, afterUpdateElement:getPoId } );
								    }

<?
		if ($_POST)
		{
?>
									new Ajax.Autocompleter("OrderNo", "Choices_OrderNo", "ajax/get-purchase-orders.php?Vendor=<?= $Vendor ?>", { paramName:"Keywords", minChars:3, afterUpdateElement:getPoId } );
<?
		}
?>


								    function getPoId(text, li)
								    {
								    	jQuery("#Po").val(li.id);
                                                                            
                                                                            if((/*jQuery("#ReportId").val() == '14' ||*/ jQuery("#ReportId").val() == '34') && jQuery("#AuditStage").val() == 'F')
                                                                            {
                                                                                jQuery.post("ajax/quonda/get-po-status.php",
                                                                                    { Pos:li.id },

                                                                                    function (sResponse)
                                                                                    {
                                                                                            if (sResponse != "" && sResponse == 'R')
                                                                                            {
                                                                                                    jQuery("#OrderNo").val("");
                                                                                                    alert("VPO with Released status can not be assigned to final stage.");
                                                                                            }
                                                                                    },

                                                                                "text");
                                                                            }
                                                                            
                                                                            if(jQuery("#ReportId").val() == '44' || jQuery("#ReportId").val() == '45')
                                                                            {
                                                                                jQuery.post("ajax/quonda/get-po-productcode.php",
                                                                                    { Pos:li.id },

                                                                                    function (sResponse)
                                                                                    {
                                                                                            if (sResponse != "")
                                                                                            {
                                                                                                var sOptions = sResponse.split("|-|");    
                                                                                                jQuery("#ProductCode").val(sOptions[0]);
                                                                                                jQuery("#ItemNumber").val(sOptions[1]);
                                                                                            }
                                                                                            else{
                                                                                                jQuery("#ProductCode").val("");
                                                                                                jQuery("#ItemNumber").val("");
                                                                                            }
                                                                                    },

                                                                                "text");
                                                                            }
										jQuery.post("ajax/quonda/get-styles-list.php",
											{ Pos:li.id },

											function (sResponse)
											{
												jQuery("#StyleNo").html("");
                                                                                                
                                                                                                if(jQuery("#ReportId").val() != '44' && jQuery("#ReportId").val() != '45')
                                                                                                    jQuery("#StyleNo").get(0).options[0] = new Option("", "", false, false);


												if (sResponse != "")
												{
													var sOptions = sResponse.split("||");
                                                                                                       
                                                                                                        if(jQuery("#ReportId").val() == '44' || jQuery("#ReportId").val() == '45')
                                                                                                            jQuery("#StyleNo").val(sOptions[0]);
                                                                                                        else
                                                                                                        {
                                                                                                            var sOptions = sResponse.split("|-|");
                                                                                                            
                                                                                                            for (var i = 0; i < sOptions.length; i ++)
                                                                                                            {
                                                                                                                    var sOption = sOptions[i].split("||");
                                                                                                                    
                                                                                                                    if(jQuery("#ReportId").val() == '46' || jQuery("#ReportId").val() == '39')
                                                                                                                        jQuery("#StyleNo").get(0).options[(i)] = new Option(sOption[1], sOption[0], false, false);
                                                                                                                    else
                                                                                                                        jQuery("#StyleNo").get(0).options[(i + 1)] = new Option(sOption[1], sOption[0], false, false);
                                                                                                            }
                                                                                                        }
												}
											},

											"text");


										jQuery.post("ajax/quonda/get-po-colors.php",
											{ Po:li.id },

											function (sResponse)
											{
												jQuery("#Colors").html("");


												if (sResponse != "")
												{
													var sOptions = sResponse.split("|-|");

                                                                                                        if(jQuery("#ReportId").val() == '44' || jQuery("#ReportId").val() == '45')
                                                                                                            jQuery("#Colors").val(sOptions[1]);
                                                                                                        else
                                                                                                            for (var i = 1; i < sOptions.length; i ++)
                                                                                                            {
                                                                                                                    jQuery("#Colors").get(0).options[(i - 1)] = new Option(sOptions[i], sOptions[i], false, false);
                                                                                                            }
												}
											},

											"text");


										jQuery.post("ajax/quonda/get-po-sizes.php",
											{ Po:li.id },

											function (sResponse)
											{
												jQuery("#Sizes").html("");

												if (sResponse != "")
												{
													var sOptions = sResponse.split("|-|");

													for (var i = 1; i < sOptions.length; i ++)
													{
														var sOption = sOptions[i].split("|");
                                                                                                                jQuery("#Sizes").get(0).options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);
													}
												}
											},

											"text");
								    }


									jQuery(document).on("keydown", "#OrderNo", function(e)
									{
										if (e.which == 8 || e.which == 46)
										{
											jQuery(this).val("");
											jQuery("#Po").val("");

											jQuery("#StyleNo").html("");
                                                                                        
                                                                                        if(jQuery("#ReportId").val() != '44' && jQuery("#ReportId").val() != '45')
                                                                                            jQuery("#StyleNo").get(0).options[0] = new Option("", "", false, false);
                                                                                        else
                                                                                        {
                                                                                            jQuery("#ProductCode").html("");
                                                                                            jQuery("#ItemNumber").html("");
                                                                                        }
                                                                                        
											jQuery("#Colors").html("");
											jQuery("#Sizes").html("");
                                                                                        
										}
									});
								-->
								</script>
						    </div>
						  </td>
					    </tr>
						
						<tr valign="top">
							<td>Other POs</td>
							<td align="center">:</td>
							<td id="AdditionalPoTd"><input type="text" name="AdditionalPO" id="AdditionalPO" value="" class="textbox" size="30" maxlength="200" style="width:280px;"/></td>
						</tr>  

						<tr>
						  <td>Style No<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="StyleNo" id="StyleNo" style="width:285px;">
							  <option value=""></option>
<?
	$sStyles = getList("tbl_styles s, tbl_po_colors pc", "DISTINCT(s.id)", "s.style", "s.id=pc.style_id AND pc.po_id='$Po'", "s.style");

	foreach ($sStyles as $sKey => $sValue)
	{
?>
	  	        		      <option value="<?= $sKey ?>"<?= (($sKey == $StyleNo) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
	}
?>
					        </select>
						  </td>
					    </tr>

					    <tr valign="top">
                                                <td id="TNCColor">Colors<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="Colors[]" id="Colors" size="5" multiple style="width:285px; height: 100px;">
<?
	$sColors = getList("tbl_po_colors", "DISTINCT(color)", "color", "po_id='$Po' AND style_id='$iStyle'", "color");

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
						    <select name="Sizes[]" id="Sizes" size="5" multiple style="width:285px; height: 100px;">
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

                                              <tr id="SampleSizeId">
						  <td id="TNCSSizes">Sample Size<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="SampleSize" id="SampleSize" style="width:285px;">
							  <option value=""></option>
<?
                                                $iAqlChart2 = array_reverse($iAqlChart, true);

						foreach ($iAqlChart2 as $iQty => $iAql)
						{
                                                    if($iQty<32)
                                                        continue;
?>
			            	  <option value="<?= $iQty ?>"<?= (($iQty == $SampleSize) ? " selected" : "") ?>><?= $iQty ?></option>
<?
						}
?>
						      <option value="0"<?= ((IO::strValue("SampleSize") != "" && $SampleSize == 0) ? " selected" : "") ?>>Custom</option>
						    </select>
						  </td>
					    </tr>

                                              
						<tr id="TNCInspecType">
						</tr>
						<tr id="TNCMaker">
						</tr>
						<tr id="LotNo">
						</tr>
					  </table>

					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

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