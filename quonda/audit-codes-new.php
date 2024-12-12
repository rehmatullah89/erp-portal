<?php
        /* 
            ** Modified By: Rehmat Ullah **
        */
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/jquery.js"></script>

  <!-- Date Picker ---->
  <link type="text/css" rel="stylesheet" href="css/jquery.sunny.datepick.css" />
  <script type="text/javascript" src="scripts/jquery.sunny.plugin.min.js"></script>  
  <script type="text/javascript" src="scripts/jquery.sunny.datepick.js"></script>
  <!-- --------- ---->
  
  <script type="text/javascript">
  <!--
		jQuery.noConflict( );
                
                (function($) {
                    $(function() {
                            $('#AuditDate').datepick({ 
                            minDate: 0,
                            dateFormat: 'yyyy-mm-dd',
                            showTrigger: '#calImg'});
                    });
                })(jQuery); 
  -->
  </script>
 
  <script type="text/javascript" src="scripts/quonda/audit-codes.js"></script>
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
			    <h1>audit codes</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-audit-code.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Create Audit Code</h2>

				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
				    <td width="50%">

						<table id="ReportTable" border="0" cellpadding="3" cellspacing="0" width="100%">
<?               
		if(count($sReportsList) > 1 && $_SESSION["UserType"] != "LEVIS")
		{
?>
                                            <tr>
						  <td width="95">Report Type<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
                            <select name="Report" id="ReportId">
							  <option value=""></option>
<?
			foreach ($sReportsList as $sKey => $sValue)
			{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Report) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			}
?>
						    </select>
						  </td>
					    </tr> 
<?                                    
		}

		else
		{
?>
                        <input type="hidden" name="Report" id="ReportId" value="<?=(($_SESSION["UserType"] == "LEVIS")?44:$sReportTypes)?>"/>        
<?
		}
?>
                                        <tr>
						  <td  width="95">Audit Stage<span class="mandatory">*</span></td>
						  <td  width="20" align="center">:</td>

						  <td>
<?
                if($_SESSION["UserType"] == "LEVIS")   
                {
?>
                             <select name="AuditStage" id="AuditStage" onchange="setAutoSampleSize(this, '');">                         
<?
                }
                else 
                {
?>
                            <select name="AuditStage" id="AuditStage">      
<?
                }
?>
						    
							  <option value=""></option>
<?
		foreach ($sAuditStagesList as $sKey => $sValue)
		{
			if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sValue == "Final")
				$sValue = "Firewall";
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $AuditStage) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>
                                            <tr id="InspectionLevelId"></tr>
                                            <tr id="InspectionCheckId"></tr>
                                            
					    <tr>
						  <td width="80">Auditor<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Auditor">
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
						  <td>Brand<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
<?
                            if($_SESSION["UserType"] == "JCREW")
                            {
?>
                                <select name="Brand" id="Brand"  onchange="getListValues('Brand', 'Factory', 'BrandFactories');">
<?
                            }
                            else
                            {
?>
                                <select name="Brand" id="Brand"  onchange="getListValues('Brand', 'Vendor', 'BrandVendors');">    
<?
                            }
?>
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

<?
		if ($_SESSION["UserType"] == "JCREW")
                {		
?>
                                            <tr>
						  <td>Vendor<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>

                                <select id="Factory" name="Factory"  onchange="getListValues('Factory', 'Vendor', 'FactoryVendors');">                                                         
                                    <option value=""></option>
<?
						$iFacoriesList = array();
						
                        if ($Brand > 0)
                        {
                                                $sVendorsSql  = "";
                                                $iUserVendors = @explode(",", $_SESSION['Vendors']);

                                                foreach ($iUserVendors as $iVendor)
                                                {
                                                        if ($sVendorsSql != "")
                                                                $sVendorsSql .= " OR ";

                                                        $sVendorsSql .= " FIND_IN_SET('$iVendor', vendors) ";
                                                }

                                                $iFacoriesList = getList("tbl_factories", "id", "parent", $sVendorsSql);									
                        }

                        foreach ($iFacoriesList as $sKey => $sValue)
                        {
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == IO::intValue("Factory")) ? " selected" : "") ?>><?= $sValue ?></option>
<?
                        }
?>
						    </select>
						  </td>
					    </tr>
<?
                }
?> 
                        
					    <tr>
						  <td>Factory<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
                                                      <select id="Vendor" name="Vendor" onchange="setAutoStyles(this.value); clearPos('');">
							  <option value=""></option>
<?
		if ($Brand > 0)
		{
			
			$sBrandVendors = getDbValue("vendors","tbl_brands", "id = '$Brand'");
                        
                        if ($_SESSION["UserType"] == "JCREW")
						{
							$sVendors = getDbValue("vendors", "tbl_factories", ("id='".IO::intValue("Factory")."'"));
							
                            $sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND id IN ({$sBrandVendors}) AND parent_id='0' AND sourcing='Y' AND id IN ($sVendors)");
						}
                        else
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
							    <td><input type="text" value="<?= (($AuditDate == "") ? date('Y-m-d') : $AuditDate) ?>" name="AuditDate" id="AuditDate" style="width: 85px;" readonly/></td>
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
                                                                                                jQuery("#OrderNo").get(0).options[0] = new Option("", "", false, false);

												if (sResponse != "")
												{
													var sOptions = sResponse.split("||");
                                                                                                       
                                                                                                        var sOptions = sResponse.split("|-|");

                                                                                                        for (var i = 0; i < sOptions.length; i ++)
                                                                                                        {
                                                                                                                var sOption = sOptions[i].split("||");
                                                                                                               
                                                                                                                jQuery("#OrderNo").get(0).options[(i)] = new Option(sOption[1], sOption[0], false, false);
                                                                                                        }
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
                                                                        
                                                                        function getColorSizes(Index)
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
                                                                        }
                                                                
                                                                
								-->
								</script>
						    </div>
						  </td>
					    </tr>

                                            <tr>
						  <td>Order No<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
                                                      <select name="OrderNo[]" size="5" multiple id="OrderNo" onchange="getColorSizes('');" style="min-width:160px;">
							  <option value=""></option>
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

                                              <tr id="SampleSizeId">
						  <td id="TNCSSizes">Sample Size<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="SampleSize" id="SampleSize">
							  <option value=""></option>
<?
						foreach ($iAqlChart as $iQty => $iAql)
						{
?>
			            	  <option value="<?= $iQty ?>"<?= (($iQty == $SampleSize) ? " selected" : "") ?>><?= $iQty ?></option>
<?
						}
                                                
                                                if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE")))
                                                {
?>
						      <option value="0"<?= ((IO::strValue("SampleSize") != "" && $SampleSize == 0) ? " selected" : "") ?>>Custom</option>
<?
                                                }
?>
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

			    <hr />
<?
	}
?>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="78">Audit Code</td>
			          <td width="130"><input type="text" name="AuditCode" value="<?= $AuditCode ?>" class="textbox" maxlength="50" size="10" style="width: 85%;"/></td>
<?
	if (@strpos($sReportTypes, ",") !== FALSE)
	{
?>
			          <td width="55">Report</td>

			          <td width="130">
					    <select name="Report" style="width: 90%;">
						  <option value="">All Reports</option>
<?
		foreach ($sReportsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Report) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					    </select>
			          </td>					  
<?
	}
?>
			          <td width="75">Auditor</td>

			          <td width="130">
					    <select name="Auditor" style="width: 90%;">
						  <option value="">All Auditors</option>
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

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
					  <td width="78">Region</td>

					  <td width="130">
					    <select name="Region" style="width: 90%;">
						  <option value="">All Regions</option>
<?
	foreach ($sRegionsList as $sKey => $sValue)
	{
?>
	  	        		  <option value="<?= $sKey ?>"<?= (($sKey == $Region) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
					  </td>
<?
                if ($_SESSION["UserType"] == "JCREW")
                {
?>
                    <td width="55">Vendor</td>

                    <td width="130">
                      <select name="Parent" id="Parent" style="width:115px;" onchange="getListValues('Parent', 'VendorId', 'ParentVendors');">
                        <option value="">All Vendors</option>
<?
                    $sParentsList = getList ("tbl_vendors v, tbl_factories f", "f.id", "f.parent", "FIND_IN_SET(v.id, f.vendors) AND v.id IN ({$_SESSION['Vendors']})");

                      foreach ($sParentsList as $sKey => $sValue)
                      {
?>
                        <option value="<?= $sKey ?>"<?= (($sKey == $Parent) ? " selected" : "") ?>><?= $sValue ?></option>
<?
                      }
?>
                      </select>
                    </td>
                    
                    <td width="60">Factory</td>

                    <td width="130">
                      <select name="Vendor" id="VendorId" style="width:115px;">
                        <option value="">All Factories</option>
<?
                      if($Parent != 0)
                          $sChildrenList = getList ("tbl_vendors v, tbl_factories f", "v.id", "v.vendor", "FIND_IN_SET(v.id, f.vendors) AND f.id='$Parent'");
                          
                      foreach ($sChildrenList as $sKey => $sValue)
                      {
?>
                        <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
                      }
?>
                      </select>
                    </td>
<?
                }
                else
                {
?>                                  
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
<?
                }
?>
					  <td></td>
				    </tr>
				  </table>
			    </div>
				
				
			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
					  <td width="78">From</td>
					  <td width="100"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:92px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="55" >To</td>
					  <td width="100"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:92px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td>[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
				    </tr>
				  </table>
			    </div>
			    </form>

			    <div class="tblSheet">
<?
	$sVendorCountriesList = getList("tbl_vendors", "id", "country_id");
	$sCountryHoursList    = getList("tbl_countries", "id", "hours");
	
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "WHERE FIND_IN_SET(report_id, '$sReportTypes') ";
	
	if ($Completed == "Y")
		$sConditions .= " AND audit_result!='' ";
	
	else if ($Completed == "N")
		$sConditions .= " AND (audit_result='' OR ISNULL(audit_result)) ";

	if ($AuditCode != "")
		$sConditions .= " AND audit_code LIKE '%$AuditCode%' ";

	if ($Auditor > 0)
	{
		if ($Group == 0)
			$sConditions .= " AND (user_id='$Auditor' OR (group_id>'0' AND group_id IN (SELECT id FROM tbl_auditor_groups WHERE FIND_IN_SET('$Auditor', users)))) ";

		else
			$sConditions .= " AND user_id='$Auditor' ";
	}

	if ($Group > 0)
		$sConditions .= " AND group_id='$Group' ";
	
	if ($Report > 0)
		$sConditions .= " AND report_id='$Report' ";

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";
                
        if($Parent > 0 && $Vendor == 0)
        {
            $sParentVendors = getDbValue("vendors", "tbl_factories", "id='$Parent'"); 
            $sConditions .= " AND FIND_IN_SET(vendor_id, '$sParentVendors') ";                
        }
        
	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Approved != "")
		$sConditions .= " AND approved='$Approved' ";

	if ($Department > 0)
		$sConditions .= " AND department_id='$Department' ";

	if (!@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE")))
	{
		$sConditions .= " AND po_id IN (SELECT id FROM tbl_po WHERE FIND_IN_SET(brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(vendor_id, '{$_SESSION['Vendors']}')) ";
		$sConditions .= " AND style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')) ";
	}

	else
		$sConditions .= " AND (style_id='0' OR style_id IN (SELECT id FROM tbl_styles WHERE FIND_IN_SET(sub_brand_id, '{$_SESSION['Brands']}') AND FIND_IN_SET(category_id, '{$_SESSION['StyleCategories']}')))";

	if ($AuditStage != "")
		$sConditions .= " AND audit_stage='$AuditStage' ";

	else
		$sConditions .= " AND FIND_IN_SET(audit_stage, '$sAuditStages') ";

	if (@strpos($_SESSION["Email"], "dkcompany.com") !== FALSE || @strpos($_SESSION["Email"], "hema.nl") !== FALSE)
		$sConditions .= " AND (audit_result='' OR audit_result='A' OR audit_result='B' OR audit_result='P') ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_qa_reports", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, aql, brand_id, created_at, audit_result, audit_type_id, inspection_level, check_level, audit_quantity, audit_code, additional_pos, inspection_type, maker, user_id, group_id, department_id, vendor_id, unit_id, report_id, line_id, audit_date, audit_stage, start_time, end_time, po_id, style_id, colors, sizes, total_gmts, approved, cutting_lot_no, published,
	                (SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line
	         FROM tbl_qa_reports
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
				      <td width="5%">#</td>
				      <td width="9%">Audit Code</td>
				      <td width="25%">Auditor</td>
				      <td width="25%"><?=($_SESSION["UserType"] == "JCREW"?'Factory':'Vendor')?></td>
				      <td width="10%">Audit Date</td>
				      <td width="9%">Start Time</td>
				      <td width="9%">End Time</td>
				      <td width="8%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId            = $objDb->getField($i, 'id');
		$sAuditCode     = $objDb->getField($i, 'audit_code');
		$iAuditor       = $objDb->getField($i, 'user_id');
		$iGroup         = $objDb->getField($i, 'group_id');
		$iDepartment    = $objDb->getField($i, 'department_id');
		$iVendor        = $objDb->getField($i, 'vendor_id');
		$iBrand         = $objDb->getField($i, 'brand_id');
		$iUnit          = $objDb->getField($i, 'unit_id');
		$iReport        = $objDb->getField($i, 'report_id');
		$iLine          = $objDb->getField($i, 'line_id');
		$sLine          = $objDb->getField($i, '_Line');
		$sAuditStage    = $objDb->getField($i, 'audit_stage');
		$sAuditDate     = $objDb->getField($i, 'audit_date');
		$sStartTime     = $objDb->getField($i, 'start_time');
		$sEndTime       = $objDb->getField($i, 'end_time');
		$iPoId          = $objDb->getField($i, 'po_id');
		$iStyleId       = $objDb->getField($i, 'style_id');
		$sColors        = $objDb->getField($i, 'colors');
		$sSizes         = $objDb->getField($i, 'sizes');
		$iSampleSize    = $objDb->getField($i, 'total_gmts');
		$sApproved      = $objDb->getField($i, 'approved');
		$sInspecType    = $objDb->getField($i, 'inspection_type');
		$cMaker         = $objDb->getField($i, 'maker');
		$sLotNo         = $objDb->getField($i, 'cutting_lot_no'); 
		$sAdditionalPO  = $objDb->getField($i, 'additional_pos');
		$iOfferedQty    = $objDb->getField($i, 'audit_quantity');
		$iInspecLevel   = $objDb->getField($i, 'inspection_level');
		$iCheckLevel    = $objDb->getField($i, 'check_level');
		$iAqlLevel      = $objDb->getField($i, 'aql');
		$iAuditType     = $objDb->getField($i, 'audit_type_id');
		$sAuditResult   = $objDb->getField($i, 'audit_result');
		$sPublished     = $objDb->getField($i, 'published');
                $sCreatedDate   = explode("-",date("Y-m-d", strtotime($objDb->getField($i, 'created_at'))));
		
		$iCountry = $sVendorCountriesList[$iVendor];
		$iHours   = $sCountryHoursList[$iCountry];
		
		$sStartTime = date("H:i:s", (strtotime($sStartTime) + ($iHours * 3600)));
		$sEndTime   = date("H:i:s", (strtotime($sEndTime) + ($iHours * 3600)));
					
		@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
		@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);


		$sOrderNo = "";
		$sStyleNo = "";

                $sSelectedPOs      = $iPoId.($sAdditionalPO != ""?", {$sAdditionalPO}":'');
                $iSelectedPOs = explode(",", $sSelectedPOs);
                
		if ($iPoId > 0)
			$sOrderNo = getDbValue("order_no", "tbl_po", "id='$iPoId'");

		if ($iStyleId > 0)
			$sStyleNo = getDbValue("style", "tbl_styles", "id='$iStyleId'");
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>"<?= (($sApproved == 'N') ? ' style="background:#ffeaea;"' : '') ?> id="Record<?= $iId ?>">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="9%"><?= $sAuditCode ?></td>
				      <td width="25%"><span id="Auditor_<?= $iId ?>"><?= $sUsersList[$iAuditor] ?><?= (($iGroup > 0) ? " (G)" : "") ?></span></td>
				      <td width="25%"><span id="Vendor_<?= $iId ?>"><?= $sAllVendorsList[$iVendor] ?></span></td>
				      <td width="10%"><span id="Date_<?= $iId ?>"><?= formatDate($sAuditDate) ?></span></td>
				      <td width="9%"><span id="StartTime_<?= $iId ?>"><?= (str_pad($iStartHour, 2, '0', STR_PAD_LEFT).":".str_pad($iStartMinutes, 2, '0', STR_PAD_LEFT)." ".$sStartAmPm) ?></span></td>
				      <td width="9%"><span id="EndTime_<?= $iId ?>"><?= (str_pad($iEndHour, 2, '0', STR_PAD_LEFT).":".str_pad($iEndMinutes, 2, '0', STR_PAD_LEFT)." ".$sEndAmPm) ?></span></td>

				      <td width="8%" class="center">
<?
		if ($sUserRights['Edit'] == "Y" && (($iAuditor == $_SESSION['UserId'] &&  @in_array($iReport, array(14,34,47)) && $sPublished != "Y") || $sAuditResult == "" || $_SESSION["UserType"] != "MGF") && ($_SESSION["UserType"] != "JCREW" || ($_SESSION["UserType"] == "JCREW" && $iReport == 46)))
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" id="EditOpt<?=$iId?>" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y" && $sAuditResult == "" && ($_SESSION["UserType"] != "JCREW" || ($_SESSION["UserType"] == "JCREW" && $iReport == 46)))
		{
?>
				        <a href="quonda/delete-audit-code.php?Id=<?= $iId ?>&AuditCode=<?= $sAuditCode ?>" onclick="return confirm('Are you SURE, You want to Delete this Audit Code?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />
                                          <input type="hidden" name="ThisReportId" id="ThisReportId<?= $iId ?>" value="<?= $iReport ?>" />

					  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					    <tr valign="top">
						  <td width="50%">

                                                      <table id="MyTable" border="0" cellpadding="3" cellspacing="0" width="100%">
<?

                
		if(count($sReportsList) > 1 && $_SESSION["UserType"] != "LEVIS")
		{
?>
                                                          <tr>
							   <td width="100">Report Type<span class="mandatory">*</span></td>
							    <td width="20" align="center">:</td>

							    <td>
                                                                <select name="Report" id="ReportId<?=$i?>">
								    <option value=""></option>
<?
			foreach ($sReportsList as $sKey => $sValue)
			{
?>                  
			            	  		<option value="<?= $sKey ?>"<?= (($sKey == $iReport) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			}
?>
								  </select>
							    </td>
							  </tr>
<?
                }else{
?>
                                          <input type="hidden" name="Report" id="ReportId<?=$i?>" value="<?=$iReport?>"/>                      
<?
		}
?>
                                                        <tr>
                                                              <td width="100">Audit Stage<span class="mandatory">*</span></td>
                                                              <td width="20" align="center">:</td>

							    <td>
<?
		if ($_SESSION["UserType"] ==  "LEVIS")
                {
?>
                        <select name="AuditStage" id="AuditStage<?=$iId?>" onchange="setAutoSampleSize(this, <?=$iId?>)">
<?
                }
                else
                {
?>
                        <select name="AuditStage" id="AuditStage<?=$iId?>">
<?
                }
?>
								    <option value=""></option>
<?
		foreach ($sAuditStagesList as $sKey => $sValue)
		{
			if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sValue == "Final")
				$sValue = "Firewall";
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $sAuditStage) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
								  </select>
 							    </td>
							  </tr>

							  <tr>
							    <td width="80">Auditor<span class="mandatory">*</span></td>
							    <td width="20" align="center">:</td>

							    <td>
                                                                <select id="Auditor<?=$iId?>" name="Auditor" <?=($iReport == 34)?'disabled':'';?>>
								    <option value=""></option>
<?
		$bAuditor = false;

		foreach ($sActiveAuditorsList as $sKey => $sValue)
		{
?>
			            	  		<option value="<?= $sKey ?>"<?= (($sKey == $iAuditor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			if ($sKey == $iAuditor)
				$bAuditor = true;
		}


		if ($bAuditor == false)
		{
?>
			            	  		<option value="<?= $iAuditor ?>" selected><?= getDbValue("name", "tbl_users", "id='$iAuditor'") ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>

                                            <tr>
						  <td>Brand<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
<?
                                            if($_SESSION["UserType"] == "JCREW")
                                            {
?>
                                                      <select name="Brand" id="Brand<?= $i ?>"  onchange="getListValues('Brand<?= $i ?>', 'Factory<?= $i ?>', 'BrandFactories');">  
<?
                                            }
                                            else
                                            {
?>
                                                      <select name="Brand" id="Brand<?= $i ?>"  onchange="getListValues('Brand<?= $i ?>', 'Vendor<?= $i ?>', 'BrandVendors');">
<?
                                            }
?>
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
                                          
<?
		if ($_SESSION["UserType"] == "JCREW")
                {		
?>
                                            <tr>
						  <td>Vendor<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>

                                <select id="Factory<?= $i ?>" name="Factory"  onchange="getListValues('Factory<?= $i ?>', 'Vendor<?= $i ?>', 'FactoryVendors');">                                                         
                                    <option value=""></option>
<?
                        $iFacoriesList = getList("tbl_factories", "id", "parent", "FIND_IN_SET('$iVendor', vendors)");

                        foreach ($iFacoriesList as $sKey => $sValue)
                        {
?>
			            	  <option value="<?= $sKey ?>" selected=""><?= $sValue ?></option>
<?
                        }
?>
						    </select>
						  </td>
					    </tr>
<?
                }
?> 
							  <tr>
							    <td>Factory<span class="mandatory">*</span></td>
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
<script type="text/javascript">
<!--
                        
jQuery( document ).ready(function() {
       
<?
        if ($iReport == 14 || $iReport == 33 || $iReport == 28 || $iReport == 37 || $iReport == 38 || $iReport == 47)
        {
?>
            jQuery( "#GroupId<?= $i ?>" ).hide();
            jQuery( "#DeptId<?= $i ?>" ).hide();
            jQuery( "#UnitId<?= $i ?>" ).hide();
            jQuery( "#LineId<?= $i ?>" ).hide();
<?
        }
?>
});

jQuery('#ReportId<?=$i?>').on('change', function() {

    if(this.value == '14' || this.value == '33' || this.value == '28' || this.value == '37' || this.value == '38' || this.value == '47')
	{
        
        jQuery( "#GroupId<?= $i ?>" ).hide();
        jQuery( "#DeptId<?= $i ?>" ).hide();
        jQuery( "#UnitId<?= $i ?>" ).hide();
        jQuery( "#LineId<?= $i ?>" ).hide();
        
    }else{
        
        jQuery( "#GroupId<?= $i ?>" ).show();
        jQuery( "#DeptId<?= $i ?>" ).show();
        jQuery( "#UnitId<?= $i ?>" ).show();
        jQuery( "#LineId<?= $i ?>" ).show();
    }
    
});
-->
</script>

							  <tr>
							    <td>Audit Date<span class="mandatory">*</span></td>
							    <td align="center">:</td>
                                                            <td>
                                                                <input type="text" value="<?= (($sAuditDate == "") ? date('Y-m-d') : $sAuditDate) ?>" name="AuditDate" id="AuditDate<?= $iId ?>" style="width: 85px;" readonly/>
<script type="text/javascript">
  <!--
		jQuery.noConflict( );
                
                (function($) {
                    $(function() {
                            $('#AuditDate<?= $iId ?>').datepick({ 
                            minDate: new Date(<?=@$sCreatedDate[0]?>,<?=@$sCreatedDate[1]-1?>,<?=@$sCreatedDate[2]?>),
                            dateFormat: 'yyyy-mm-dd',
                            showTrigger: '#calImg'});
                    });
                })(jQuery); 
  -->
</script>
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
                                                                                                jQuery("#OrderNo<?= $iId ?>").get(0).options[0] = new Option("", "", false, false);

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
                                                                                        
										}
									});
								-->
								</script>
							    </td>
							  </tr>

							  <tr>
							    <td>Order No<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
								<select name="OrderNo[]" size="5" multiple id="OrderNo<?= $iId ?>" onchange="getColorSizes(<?= $iId ?>);" style="min-width:160px;">
								    <option value=""></option>
<?
		$sOrdersList = getList("tbl_po", "id", "order_no", "FIND_IN_SET('$iStyleId', styles)");
                
		foreach ($sOrdersList as $sKey => $sValue)
		{
?>
	  	        		      	    <option value="<?= $sKey ?>"<?= (@in_array($sKey, $iSelectedPOs) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
		}
?>
                                                                </select>
							    </td>
							  </tr>

							  <tr valign="top">
							    <td>Colors<span class="mandatory">*</span><br/><br/>[ <a href="./" onclick="selectAll('Colors<?= $iId ?>'); return false;">Select All</a>]</td>
							    <td align="center">:</td>

							    <td>
<?
		$sPoColors = @explode(",", $sColors);
		$sPoColors = @array_map("trim", $sPoColors);
		
		if ($sAdditionalPO != "")
			$sColors = getList("tbl_po_colors", "DISTINCT(color)", "color", "(po_id='$iPoId' OR po_id IN ($sAdditionalPO)) AND style_id='$iStyleId'", "color");
		
		else
			$sColors = getList("tbl_po_colors", "DISTINCT(color)", "color", "po_id='$iPoId' AND style_id='$iStyleId'", "color");
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
		$sSizes   = getList("tbl_sizes", "id", "size", "id IN (SELECT DISTINCT(size_id) FROM tbl_po_quantities WHERE po_id='$iPoId')", "position");

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
<?
                    if ($_SESSION["UserType"] == "CONTROLIST" || @in_array($iReport, array(28,37,38)))
                    {
?>
                        <tr>
                            <td>Offered Quantity<span class="mandatory">*</span></td>
                            <td align="center">:</td>
                            <td><input type="text" name="OfferedQty" id="OfferedQty" value="<?= $iOfferedQty ?>" class="textbox" size="20" maxlength="50" /></td>
                        </tr>  
<?                        
                    }else{
                        
                        if($_SESSION["UserType"] == "LEVIS" && $iAuditType == 2)
                        {
?>      
                                              <input type='hidden' name="SampleSize" value="<?=$iSampleSize?>">
<?                            
                        }
                        else
                        {
?>
							  <tr id="SampleSizeId<?=$iId?>">
							    <td>Sample Size
                                                                <?if($iReport != '26'){?>
                                                                <span class="mandatory">*</span>
                                                                <?}?>
                                                            </td>
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
                        }
                    }
?>

<?
		if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE")))
		{
?>
							  <tr>
							    <td>Approved</td>
							    <td align="center">:</td>

							    <td>
								  <select name="Approved">
                                                                    <option value="Y"<?= (($sApproved == "Y") ? " selected" : "") ?>>Yes</option>
                                                                    <option value="N"<?= (($sApproved == "N") ? " selected" : "") ?>>No</option>
								  </select>
							    </td>
							  </tr>
<? 
		}
		
		else
		{
?>
							<input name="Approved" type="hidden" value="<?= $sApproved ?>" />
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
				      <td class="noRecord">No Audit Code Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&AuditCode={$AuditCode}&Auditor={$Auditor}&Group={$Group}&Vendor={$Vendor}&Parent={$Parent}&FromDate={$FromDate}&ToDate={$ToDate}&Approved={$Approved}&Department={$Department}&Report={$Report}&Completed={$Completed}");

    if ($iCount > 0 && ($AuditCode != "" || $Auditor != 0 || $Group != 0 || $Vendor != 0 || ($FromDate != "" && $ToDate != "") || $Region != 0 || $Completed != ""))
	{
?>

				<div class="buttonsBar" style="margin-top:4px;">
				  <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= (SITE_URL."quonda/export-audit-codes.php?AuditCode={$AuditCode}&Auditor={$Auditor}&Group={$Group}&Vendor={$Vendor}&Parent={$Parent}&FromDate={$FromDate}&ToDate={$ToDate}&Department={$Department}&Region={$Region}&Report={$Report}&Completed={$Completed}") ?>" />
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
<script>



                        
jQuery( document ).ready(function() 
{    
        if((jQuery("#ReportId").val() == '44' || jQuery("#ReportId").val() == '45') && jQuery("#AuditStage").val() != "")
        {
            setAutoSampleSize(document.getElementById("AuditStage"), '');
        }
        
	if(jQuery("#ReportId").val() == '26')
	{
		jQuery( "#TNCColor" ).find('span').remove();
		jQuery( "#TNCSizes" ).find('span').remove();
		jQuery( "#TNCSSizes" ).find('span').remove();
		jQuery( "#AuditStage" ).val('F');
		jQuery( "#TNCInspecType" ).html("<td>Inspection Type<span class='mandatory'>*</span></td><td align='center'>:</td><td><select name='InspecType' value='<?= $InspecType ?>'><option value='G'>GREIGE</option><option value='P'>DYED / PRINTED</option><option value='O'>OTHER</option></select></td>");
		jQuery( "#TNCMaker" ).html("<td>Maker<span class='mandatory'>*</span></td><td align='center'>:</td><td><input type='text' name='Maker' value='<?= $Maker ?>' /></td>");
	}

        if(jQuery("#ReportId").val() == '15')
	{
	  jQuery( "#LotNo" ).html("<td>Lot No.</td><td align='center'>:</td><td><input type='text' name='LotNo' value='<?= $LotNo ?>' /></td>");
	}
        
        if(jQuery("#ReportId").val() == '28' || jQuery("#ReportId").val() == '37')
        {   
            jQuery( "#SampleSizeId" ).html("<td>Offered Quantity<span class='mandatory'>*</span></td><td align='center'>:</td><td><input type='text' name='OfferedQty' id='OfferedQty' value='' class='textbox' size='20' maxlength='50' /></td>");
            jQuery( "#InspectionLevelId" ).html("<td>Insepection Level</td><td align='center'>:</td><td><select name='InspectionLevel'><option value='1'>Level -I</option><option value='2'>Level -II</option></select></td>");
        }
        
        if(jQuery("#ReportId").val() == '38')
        {   
            jQuery( "#SampleSizeId" ).html("<td>Offered Quantity<span class='mandatory'>*</span></td><td align='center'>:</td><td><input type='text' name='OfferedQty' id='OfferedQty' value='' class='textbox' size='20' maxlength='50' /></td>");
            jQuery( "#InspectionCheckId" ).html("<td>Sample Check Level</td><td align='center'>:</td><td><select name='CheckLevel'><option value='1'>Check Level -I</option><option value='2'>Check Level -II</option></select></td>");
        }
<?
	if ($_SESSION["UserType"] == "MGF" || @in_array("28", array($sReportTypes)) || @in_array("37", array($sReportTypes)) || @in_array("38", array($sReportTypes)))
	{
?>
		jQuery( "#ReportTable #GroupId" ).hide();
		jQuery( "#ReportTable #DeptId" ).hide();
		jQuery( "#ReportTable #UnitId" ).hide();
		jQuery( "#ReportTable #LineId" ).hide();
<?
    }
?>
});

jQuery('#ReportId').on('change', function()
 {
    if(this.value == '26')
    {
        jQuery( "#TNCColor" ).find('span').remove();
        /*jQuery( "#TNCSizes" ).find('span').remove();*/
        jQuery( "#TNCSSizes" ).find('span').remove();
        jQuery( "#AuditStage" ).val('F');
        jQuery( "#TNCInspecType" ).html("<td>Inspection Type<span class='mandatory'>*</span></td><td align='center'>:</td><td><select name='InspecType' value='<?= $InspecType ?>'><option value='G'>GREIGE</option><option value='P'>DYED / PRINTED</option><option value='O'>OTHER</option></select></td>");
        jQuery( "#TNCMaker" ).html("<td>Maker<span class='mandatory'>*</span></td><td align='center'>:</td><td><input type='text' name='Maker' value='<?= $Maker ?>' /></td>");
    }
	
    else
    {
        jQuery( "#TNCColor" ).html("Colors<span class='mandatory'>*</span>");
        /*jQuery( "#TNCSizes" ).html("Size<span class='mandatory'>*</span>");*/
        jQuery( "#TNCSSizes" ).html("Sample Size<span class='mandatory'>*</span>");
        jQuery( "#TNCInspecType" ).html("");
        jQuery( "#TNCMaker" ).html("");
        jQuery( "#AuditStage" ).val('');
    }
  
  
    if(this.value == '15')
    {
        jQuery( "#LotNo" ).html("<td>Lot No.</td><td align='center'>:</td><td><input type='text' name='LotNo' value='<?= $LotNo ?>' /></td>");
    }
    else
    {
        jQuery( "#LotNo" ).html("");
    }

    if(this.value == '38')
    {
        jQuery( "#InspectionCheckId" ).html("<td>Sample Check Level</td><td align='center'>:</td><td><select name='CheckLevel'><option value='1'>Check Level -I</option><option value='2'>Check Level -II</option></select></td>");
    }
    else
    {
        jQuery( "#InspectionCheckId" ).html("");
    }

    if(jQuery("#ReportId").val() == '28' || jQuery("#ReportId").val() == '37' || jQuery("#ReportId").val() == '38')
    {
        jQuery( "#SampleSizeId" ).html("<td>Offered Quantity<span class='mandatory'>*</span></td><td align='center'>:</td><td><input type='text' name='OfferedQty' id='OfferedQty' value='' class='textbox' size='20' maxlength='50' /></td>");
        
        if(jQuery("#ReportId").val() == '38')
            jQuery( "#InspectionLevelId" ).html("");
        else    
            jQuery( "#InspectionLevelId" ).html("<td>Insepection Level</td><td align='center'>:</td><td><select name='InspectionLevel'><option value='1'>Level -I</option><option value='2'>Level -II</option></select></td>");
        
    }
    else
    {
        var SampleSizes = "<td id='TNCSSizes'>Sample Size<span class='mandatory'>*</span></td><td align='center'>:</td><td><select name='SampleSize' id='SampleSize'><option value=''></option>";
						  
<?
						foreach ($iAqlChart as $iQty => $iAql)
						{
?>
                                            SampleSizes += "<option value="+"<?= $iQty ?>"+">"+ "<?= $iQty ?>" +"</option>";
<?
						}
?>                                          SampleSizes += "<option value='0' 'selected' >Custom</option></select></td>";
	jQuery( "#SampleSizeId" ).html(SampleSizes);				    
        jQuery( "#InspectionLevelId" ).html("");
    }
        
    if (this.value == '14' || this.value == '34' || this.value == '28' || this.value == '37' || this.value == '38' || this.value == '47')
	{
        jQuery( "#ReportTable #GroupId" ).hide();
        jQuery( "#ReportTable #DeptId" ).hide();
        jQuery( "#ReportTable #UnitId" ).hide();
        jQuery( "#ReportTable #LineId" ).hide();
    }
	
	else
	{
        jQuery( "#ReportTable #GroupId" ).show();
        jQuery( "#ReportTable #DeptId" ).show();
        jQuery( "#ReportTable #UnitId" ).show();
        jQuery( "#ReportTable #LineId" ).show();
    }  
});

</script>
</body>
</html>