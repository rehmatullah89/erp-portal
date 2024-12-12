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

	$PageId     = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$AuditCode  = IO::strValue("AuditCode");
	$Auditor    = IO::intValue("Auditor");
	$Group      = IO::intValue("Group");
	$Vendor     = IO::intValue("Vendor");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
	$Region     = IO::intValue("Region");
	$Approved   = IO::strValue("Approved");
	$Department = IO::intValue("Department");
        $Maker      = IO::strValue("Maker");
        $InspecType = IO::strValue("InspecType");
        $PostId     = IO::strValue("PostId");
   	$AuditStage = "";

	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE || @strpos($_SESSION["Email"], "dkcompany.com") !== FALSE || @strpos($_SESSION["Email"], "hema.nl") !== FALSE ||
	    @strpos($_SESSION["Email"], "kcmtar.com") !== FALSE || @strpos($_SESSION["Email"], "mister-lady.com") !== FALSE)
		$AuditStage = "F";


	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Auditor      = IO::strValue("Auditor");
		$Group        = IO::strValue("Group");
		$Department   = IO::strValue("Department");
		$Vendor       = IO::strValue("Vendor");
		$Unit         = IO::strValue("Unit");
		$Report       = IO::strValue("Report");
		$Line         = IO::strValue("Line");
		$AuditDate    = IO::strValue("AuditDate");
		$StartHour    = IO::strValue("StartHour");
		$StartMinutes = IO::strValue("StartMinutes");
		$EndHour      = IO::strValue("EndHour");
		$EndMinutes   = IO::strValue("EndMinutes");
		$AuditStage   = IO::strValue("AuditStage");
		$Po           = IO::intValue("Po");
		$OrderNo      = IO::strValue("OrderNo");
		$StyleNo      = IO::intValue("StyleNo");
		$Colors       = IO::getArray("Colors");
		$Sizes        = IO::getArray("Sizes");
		$SampleSize   = IO::intValue("SampleSize");
	}

	if ($PageId == 1 && $AuditCode == "" && $Auditor == 0 && $Group == 0 && $Vendor == 0 && $Region == 0 && ($FromDate == "" || $ToDate == ""))
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") === FALSE && @strpos($_SESSION["Email"], "dkcompany.com") === FALSE && @strpos($_SESSION["Email"], "hema.nl") === FALSE &&
			@strpos($_SESSION["Email"], "kcmtar.com") === FALSE && @strpos($_SESSION["Email"], "mister-lady.com") === FALSE)
		{
			$FromDate = date("Y-m-d");
			$ToDate   = date("Y-m-d");
		}
	}


	$sRegionsList        = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");
	$sAllAuditorsList    = getList("tbl_users", "id", "name", "auditor='Y'");
	$sUsersList          = getList("tbl_users", "id", "name");
	$sVendorsList        = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sGroupsList         = getList("tbl_auditor_groups", "id", "name");
	$sDepartmentsList    = getList("tbl_departments", "id", "department", "`code`!=''");
	$sGroupsList         = getList("tbl_auditor_groups", "id", "name");

	$sAuditStages        = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList    = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')");

	$sReportTypes        = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sReportsList        = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')");
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
			    <h1><img src="images/h1/quonda/audit-codes.jpg" width="170" height="20" vspace="10" alt="" title="" /></h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-audit-code.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Create Audit Code</h2>

				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
				    <td width="50%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
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
						  <td>Group</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Group">
							  <option value=""></option>
<?
		foreach ($sGroupsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Group) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Department<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select id="Department" name="Department">
							  <option value=""></option>
<?
		foreach ($sDepartmentsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Department) ? " selected" : "") ?>><?= $sValue ?></option>
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
						    <select id="Vendor" name="Vendor" onchange="getListValues('Vendor', 'Unit', 'VendorUnits'); getListValues('Vendor', 'Line', 'Lines'); setAutoPo(this.value);">
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
					  	  <td>Unit</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Unit" id="Unit" onchange="getUnitLines('', 'Line');">
							  <option value=""></option>
<?
		$sUnitsList = array( );

		if ($Vendor > 0)
			$sUnitsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='$Vendor' AND sourcing='Y'");

		foreach ($sUnitsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Unit) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Line<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select id="Line" name="Line">
<?
		$sSQL = "SELECT id, line FROM tbl_lines WHERE vendor_id='$Vendor' AND unit_id='$Unit' ORDER BY line";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sKey   = $objDb->getField($i, 0);
			$sValue = $objDb->getField($i, 1);
?>
	  	        			  <option value="<?= $sKey ?>"<?= (($sKey == $Line) ? " selected" : "") ?>><?= $sValue ?></option>
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
						  <td width="80">Report Type<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
                                                      <select name="Report" id="TNCReport">
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

					    <tr>
						  <td>Audit Stage<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="AuditStage" id="AuditStage">
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

					    <tr>
					  	  <td>Order No<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
				            <div>
				                <input type="hidden" id="Po" name="Po" value="<?= $Po ?>" />
								<input type="text" id="OrderNo" name="OrderNo" value="<?= htmlentities($OrderNo, ENT_QUOTES) ?>" size="20" maxlength="50" autocomplete="off" class="textbox" />

								<div id="Choices_OrderNo" class="autocomplete" style="display:none;"></div>

								<script type="text/javascript">
								<!--
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


										jQuery.post("ajax/quonda/get-styles-list.php",
											{ Pos:li.id },

											function (sResponse)
											{
												jQuery("#StyleNo").html("");
												jQuery("#StyleNo").get(0).options[0] = new Option("", "", false, false);


												if (sResponse != "")
												{
													var sOptions = sResponse.split("|-|");

													for (var i = 0; i < sOptions.length; i ++)
													{
														var sOption = sOptions[i].split("||");

														jQuery("#StyleNo").get(0).options[(i + 1)] = new Option(sOption[1], sOption[0], false, false);
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
											jQuery("#StyleNo").get(0).options[0] = new Option("", "", false, false);

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
                                                <td><input type="text" name="AdditionalPO" id="AdditionalPO" value="" class="textbox" size="30" maxlength="200" /></td>
                                            </tr>  
					    
                                            <tr>
						  <td>Style No<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="StyleNo" id="StyleNo">
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
						    <select name="Colors[]" id="Colors" size="5" multiple style="min-width:160px;">
<?
	$sColors = getList("tbl_po_colors", "DISTINCT(color)", "color", "po_id='$Po'", "color");

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
						  <td id="TNCSizes">Sizes<span class="mandatory">*</span></td>
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

					    <tr>
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
?>
						      <option value="0"<?= ((IO::strValue("SampleSize") != "" && $SampleSize == 0) ? " selected" : "") ?>>Custom</option>
						    </select>
						  </td>
					    </tr>
                                            <tr id="TNCInspecType">
                                            </tr>
                                            <tr id="TNCMaker">
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
			          <td width="106"><input type="text" name="AuditCode" value="<?= $AuditCode ?>" class="textbox" maxlength="50" size="10" /></td>
			          <td width="55">Auditor</td>

			          <td width="220">
					    <select name="Auditor">
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

			          <td width="45">Group</td>

			          <td width="180">
					    <select name="Group">
						  <option value="">All Groups</option>
<?
	foreach ($sGroupsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Group) ? " selected" : "") ?>><?= $sValue ?></option>
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
					  <td width="50">Region</td>

					  <td width="130">
					    <select name="Region">
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

			          <td width="55">Vendor</td>

			          <td width="180">
					    <select name="Vendor" style="width:90%;">
						  <option value="">All Vendors</option>
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

					  <td width="40">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td>[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
				    </tr>
				  </table>
			    </div>
			    </form>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "WHERE FIND_IN_SET(report_id, '$sReportTypes') ";

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

	if ($Vendor > 0)
		$sConditions .= " AND vendor_id='$Vendor' ";

	else
		$sConditions .= " AND vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Region > 0)
		$sConditions .= " AND vendor_id IN (SELECT id FROM tbl_vendors WHERE country_id='$Region' AND parent_id='0' AND sourcing='Y') ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";

	if ($Approved != "")
		$sConditions .= " AND approved='$Approved' ";

	if ($Department > 0)
		$sConditions .= " AND department_id='$Department' ";

	if (@strpos($_SESSION["Email"], "apparelco.com") === FALSE && @strpos($_SESSION["Email"], "3-tree.com") === FALSE)
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


	$sSQL = "SELECT id, audit_code, additional_pos, inspection_type, maker, user_id, group_id, department_id, vendor_id, unit_id, report_id, line_id, audit_date, audit_stage, start_time, end_time, po_id, style_id, colors, sizes, total_gmts, approved,
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
				      <td width="8%">#</td>
				      <td width="10%">Audit Code</td>
				      <td width="18%">Auditor</td>
				      <td width="18%">Vendor</td>
				      <td width="10%">Line</td>
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
                $sAdditionalPO  = $objDb->getField($i, 'additional_pos');
                
		@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
		@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);


		$sOrderNo = "";
		$sStyleNo = "";

		if ($iPoId > 0)
			$sOrderNo = getDbValue("order_no", "tbl_po", "id='$iPoId'");

		if ($iStyleId > 0)
			$sStyleNo = getDbValue("style", "tbl_styles", "id='$iStyleId'");
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>"<?= (($sApproved == 'N') ? ' style="background:#ffeaea;"' : '') ?> id="Record<?= $iId ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="10%"><?= $sAuditCode ?></td>
				      <td width="18%"><span id="Auditor_<?= $iId ?>"><?= $sUsersList[$iAuditor] ?><?= (($iGroup > 0) ? " (G)" : "") ?></span></td>
				      <td width="18%"><span id="Vendor_<?= $iId ?>"><?= $sVendorsList[$iVendor] ?></span></td>
				      <td width="10%"><span id="Line_<?= $iId ?>"><?= $sLine ?></span></td>
				      <td width="10%"><span id="Date_<?= $iId ?>"><?= formatDate($sAuditDate) ?></span></td>
				      <td width="9%"><span id="StartTime_<?= $iId ?>"><?= (str_pad($iStartHour, 2, '0', STR_PAD_LEFT).":".str_pad($iStartMinutes, 2, '0', STR_PAD_LEFT)." ".$sStartAmPm) ?></span></td>
				      <td width="9%"><span id="EndTime_<?= $iId ?>"><?= (str_pad($iEndHour, 2, '0', STR_PAD_LEFT).":".str_pad($iEndMinutes, 2, '0', STR_PAD_LEFT)." ".$sEndAmPm) ?></span></td>

				      <td width="8%" class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="quonda/delete-audit-code.php?Id=<?= $iId ?>&AuditCode=<?= $sAuditCode ?>" onclick="return confirm('Are you SURE, You want to Delete this Audit Code? The QA Report will also be Deleted.');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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

					  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					    <tr valign="top">
						  <td width="50%">

						    <table border="0" cellpadding="3" cellspacing="0" width="100%">
							  <tr>
							    <td width="80">Auditor<span class="mandatory">*</span></td>
							    <td width="20" align="center">:</td>

							    <td>
								  <select name="Auditor">
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
							    <td>Group</td>
							    <td align="center">:</td>

							    <td>
								  <select name="Group">
								    <option value=""></option>
<?
		foreach ($sGroupsList as $sKey => $sValue)
		{
?>
			            	  	    <option value="<?= $sKey ?>"<?= (($sKey == $iGroup) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>

							  <tr>
							    <td>Department<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
								  <select id="Department<?= $i ?>" name="Department">
								    <option value=""></option>
<?
		foreach ($sDepartmentsList as $sKey => $sValue)
		{
?>
			            	  		<option value="<?= $sKey ?>"<?= (($sKey == $iDepartment) ? " selected" : "") ?>><?= $sValue ?></option>
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
								  <select id="Vendor<?= $i ?>" name="Vendor" onchange="getListValues('Vendor<?= $i ?>', 'Unit<?= $i ?>', 'VendorUnits'); getListValues('Vendor<?= $i ?>', 'Line<?= $i ?>', 'Lines'); setAutoPo<?= $iId ?>(this.value);">
								    <option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
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
							    <td>Unit</td>
							    <td align="center">:</td>

							    <td>
								  <select name="Unit" id="Unit<?= $i ?>" onchange="getUnitLines('<?= $i ?>', 'Line<?= $i ?>');">
								    <option value=""></option>
<?
		$sUnitsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='$iVendor' AND sourcing='Y'");

		foreach ($sUnitsList as $sKey => $sValue)
		{
?>
			            	  		<option value="<?= $sKey ?>"<?= (($sKey == $iUnit) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>

							  <tr>
							    <td>Line<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
								  <select id="Line<?= $i ?>" name="Line">
								    <option value=""></option>
<?
		$sSQL = "SELECT id, line FROM tbl_lines WHERE vendor_id='$iVendor' AND unit_id='$iUnit' ORDER BY line";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$sKey   = $objDb2->getField($j, 0);
			$sValue = $objDb2->getField($j, 1);
?>
	  	        			  		<option value="<?= $sKey ?>"<?= (($sKey == $iLine) ? " selected" : "") ?>><?= $sValue ?></option>
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
								 	  <td width="82"><input type="text" name="AuditDate" id="AuditDate<?= $i ?>" value="<?= $sAuditDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AuditDate<?= $i ?>'), 'yyyy-mm-dd', this);" /></td>
									  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('AuditDate<?= $i ?>'), 'yyyy-mm-dd', this);" /></td>
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
		for ($j = 0; $j <= 23; $j ++)
		{
?>
	  	        			  		<option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iStartHour == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
								  </select>

								  <select name="StartMinutes">
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
								  <select name="EndHour">
<?
		for ($j = 0; $j <= 23; $j ++)
		{
?>
	  	        			  		<option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iEndHour == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
								  </select>

								  <select name="EndMinutes">
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
							   <td width="80">Report Type<span class="mandatory">*</span></td>
							    <td width="20" align="center">:</td>

							    <td>
								  <select name="Report">
								    <option value=""></option>
<?
                $selectedValueId = "";
		foreach ($sReportsList as $sKey => $sValue)
		{
                    if($sKey == $iReport){
                        $selectedValueId = $sKey;
                    }
?>                  
			            	  		<option value="<?= $sKey ?>"<?= (($sKey == $iReport) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
								  </select>
							    </td>
							  </tr>

							  <tr>
							    <td>Audit Stage<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
								  <select name="AuditStage">
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
							    <td>Order No<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
							      <input type="hidden" id="Po<?= $iId ?>" name="Po" value="<?= $iPoId ?>" />
							      <input type="text" name="OrderNo" id="OrderNo<?= $iId ?>" value="<?= htmlentities($sOrderNo, ENT_QUOTES) ?>" size="20" maxlength="50" class="textbox" autocomplete="off" />

								<div id="Choices_OrderNo<?= $iId ?>" class="autocomplete" style="display:none;"></div>

								<script type="text/javascript">
								<!--
									function setAutoPo<?= $iId ?>(iVendor)
									{
								       new Ajax.Autocompleter("OrderNo<?= $iId ?>", "Choices_OrderNo<?= $iId ?>", ("ajax/get-purchase-orders.php?Vendor=" + iVendor), { paramName:"Keywords", minChars:3, afterUpdateElement:getPoId<?= $iId ?> } );
								    }

									new Ajax.Autocompleter("OrderNo<?= $iId ?>", "Choices_OrderNo<?= $iId ?>", "ajax/get-purchase-orders.php?Vendor=<?= $iVendor ?>", { paramName:"Keywords", minChars:3, afterUpdateElement:getPoId<?= $iId ?> } );



								    function getPoId<?= $iId ?>(text, li)
								    {
								    	jQuery("#Po<?= $iId ?>").val(li.id);


										jQuery.post("ajax/quonda/get-styles-list.php",
											{ Pos:li.id },

											function (sResponse)
											{
												jQuery("#StyleNo<?= $iId ?>").html("");
												jQuery("#StyleNo<?= $iId ?>").get(0).options[0] = new Option("", "", false, false);


												if (sResponse != "")
												{
													var sOptions = sResponse.split("|-|");

													for (var i = 0; i < sOptions.length; i ++)
													{
														var sOption = sOptions[i].split("||");

														jQuery("#StyleNo<?= $iId ?>").get(0).options[(i + 1)] = new Option(sOption[1], sOption[0], false, false);
													}
												}
											},

											"text");


										jQuery.post("ajax/quonda/get-po-colors.php",
											{ Po:li.id },

											function (sResponse)
											{
												jQuery("#Colors<?= $iId ?>").html("");


												if (sResponse != "")
												{
													var sOptions = sResponse.split("|-|");

													for (var i = 1; i < sOptions.length; i ++)
													{
														jQuery("#Colors<?= $iId ?>").get(0).options[(i - 1)] = new Option(sOptions[i], sOptions[i], false, false);
													}
												}
											},

											"text");


										jQuery.post("ajax/quonda/get-po-sizes.php",
											{ Po:li.id },

											function (sResponse)
											{
												jQuery("#Sizes<?= $iId ?>").html("");


												if (sResponse != "")
												{
													var sOptions = sResponse.split("|-|");

													for (var i = 1; i < sOptions.length; i ++)
													{
														var sOption = sOptions[i].split("|");

														jQuery("#Sizes<?= $iId ?>").get(0).options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);
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
											jQuery("#Po<?= $iId ?>").val("");

											jQuery("#StyleNo<?= $iId ?>").html("");
											jQuery("#StyleNo<?= $iId ?>").get(0).options[0] = new Option("", "", false, false);

											jQuery("#Colors<?= $iId ?>").html("");
											jQuery("#Sizes<?= $iId ?>").html("");
										}
									});
								-->
								</script>
							    </td>
							  </tr>
                                                        
                                                          <tr valign="top">
                                                            <td>Other POs</td>
                                                            <td align="center">:</td>
                                                            <td><input type="text" name="AdditionalPO" id="AdditionalPO<?= $iId ?>" value="" class="textbox" size="30" maxlength="200" /></td>
                                                            <?
                                                                $sSQL3 = "SELECT id, CONCAT(order_no, ' ', order_status) AS _Po FROM tbl_po WHERE vendor_id='$iVendor' AND FIND_IN_SET(id, '$sAdditionalPO')";
                                                                
                                                                $objDb3->query($sSQL3);

                                                                $iCount3 = $objDb3->getCount( );
                                                                $sPos = array();
                                                                for ($j = 0; $j < $iCount3; $j++)
                                                                {
                                                                        $iPo = $objDb3->getField($j, 0);
                                                                        $sPo = $objDb3->getField($j, 1);

                                                                        $sPos[] = array("id" => $iPo, "name" => $sPo);
                                                                }       
                                                            ?>
                                                            <script>
                                                                jQuery("#AdditionalPO<?= $iId ?>").tokenInput("ajax/quonda/get-pos-list.php?Vendor="+jQuery('#Vendor<?= $i ?>').val(),
                                                                    {
                                                                            queryParam         :  "Po",
                                                                            minChars           :  3,
                                                                            tokenLimit         :  50,
                                                                            hintText           :  "Search the PO #",
                                                                            noResultsText      :  "No matching PO found",
                                                                            theme              :  "facebook",
                                                                            preventDuplicates  :  true,
                                                                            prePopulate        :  <?= @json_encode($sPos) ?>,
                                                                            onAdd              :  function( ) {   },
                                                                            onDelete           :  function( ) {   }
                                                                });
                                                                jQuery('#Vendor<?= $iId ?>').change(function(){
                                                                    jQuery(".token-input-list-facebook").remove();
                                                                    jQuery("#AdditionalPO<?= $iId ?>").tokenInput("ajax/quonda/get-pos-list.php?Vendor="+jQuery('#Vendor<?= $i ?>').val(),
                                                                    {
                                                                            queryParam         :  "Po",
                                                                            minChars           :  3,
                                                                            tokenLimit         :  50,
                                                                            hintText           :  "Search the PO #",
                                                                            noResultsText      :  "No matching PO found",
                                                                            theme              :  "facebook",
                                                                            preventDuplicates  :  true,
                                                                            prePopulate        :  <?= @json_encode($sPos) ?>,
                                                                            onAdd              :  function( ) {   },
                                                                            onDelete           :  function( ) {   }
                                                                    });
                                                                }); 
                                                            </script>    
                                                          </tr>    
                                                        
							  <tr>
							    <td>Style No<span class="mandatory">*</span></td>
							    <td align="center">:</td>

							    <td>
								  <select name="StyleNo" id="StyleNo<?= $iId ?>">
								    <option value=""></option>
<?
		$sStyles = getList("tbl_styles s, tbl_po_colors pc", "DISTINCT(s.id)", "s.style", "s.id=pc.style_id AND pc.po_id='$iPoId'", "s.style");

		foreach ($sStyles as $sKey => $sValue)
		{
?>
	  	        		      	    <option value="<?= $sKey ?>"<?= (($sKey == $iStyleId) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
		}
?>
					        	  </select>
							    </td>
							  </tr>

							  <tr valign="top">
							    <td>Colors
                                                                <?if($selectedValueId != '26'){?>
                                                                    <span class="mandatory">*</span>
                                                                <?}?></td>
							    <td align="center">:</td>

							    <td>
						    	  <select name="Colors[]" id="Colors<?= $iId ?>" size="5" multiple style="min-width:160px;">
<?
		$sPoColors = @explode(",", $sColors);
		$sPoColors = @array_map("trim", $sPoColors);
		$sColors   = getList("tbl_po_colors", "DISTINCT(color)", "color", "po_id='$iPoId'", "color");

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
							    <td>Sizes
                                                                <?if($selectedValueId != '26'){?>
                                                                    <span class="mandatory">*</span>
                                                                <?}?></td>
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

							  <tr>
							    <td>Sample Size
                                                                <?if($selectedValueId != '26'){?>
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
?>
						    	    <option value="0"<?= (($iSampleSize == 0) ? " selected" : "") ?>>Custom</option>
						    	  </select>
							    </td>
							  </tr>

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
                                                            if($selectedValueId == '26'){
                                                        ?>
                                                        <tr>
                                                            <td>Inspection Type<span class='mandatory'>*</span></td><td align='center'>:</td><td><select name='InspecType' value='<?= $sInspecType ?>'><option value='G' <?= (($sInspecType == 'G') ? " selected" : "") ?>>GREIGE</option><option value='P' <?= (($sInspecType == 'P') ? " selected" : "") ?>>DYED / PRINTED</option><option value='O' <?= (($sInspecType == 'O') ? " selected" : "") ?>>OTHER</option></select></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Maker<span class='mandatory'>*</span></td><td align='center'>:</td><td><input type='text' name='Maker' value='<?= $cMaker ?>' /></td>
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
				      <td class="noRecord">No Audit Code Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&AuditCode={$AuditCode}&Auditor={$Auditor}&Group={$Group}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&Approved={$Approved}&Department={$Department}");
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



                        
jQuery( document ).ready(function() {
    
    jQuery('#Vendor').change(function(){
    jQuery(".token-input-list-facebook").remove();
    jQuery("#AdditionalPO").tokenInput("ajax/quonda/get-pos-list.php?Vendor="+jQuery('#Vendor').val(),
    {
            queryParam         :  "Po",
            minChars           :  3,
            tokenLimit         :  50,
            hintText           :  "Search the PO #",
            noResultsText      :  "No matching PO found",
            theme              :  "facebook",
            preventDuplicates  :  true,
            prePopulate        :  '',
            onAdd              :  function( ) {   },
            onDelete           :  function( ) {   }
    });
}); 
    
  if(jQuery("#TNCReport").val() == '26'){
      jQuery( "#TNCColor" ).find('span').remove();
      jQuery( "#TNCSizes" ).find('span').remove();
      jQuery( "#TNCSSizes" ).find('span').remove();
      jQuery( "#AuditStage" ).val('F');
      jQuery( "#TNCInspecType" ).html("<td>Inspection Type<span class='mandatory'>*</span></td><td align='center'>:</td><td><select name='InspecType' value='<?= $InspecType ?>'><option value='G'>GREIGE</option><option value='P'>DYED / PRINTED</option><option value='O'>OTHER</option></select></td>");
      jQuery( "#TNCMaker" ).html("<td>Maker<span class='mandatory'>*</span></td><td align='center'>:</td><td><input type='text' name='Maker' value='<?= $Maker ?>' /></td>");
  }
});

jQuery('#TNCReport').on('change', function() {
  if(this.value == '26'){
      jQuery( "#TNCColor" ).find('span').remove();
      jQuery( "#TNCSizes" ).find('span').remove();
      jQuery( "#TNCSSizes" ).find('span').remove();
      jQuery( "#AuditStage" ).val('F');
      jQuery( "#TNCInspecType" ).html("<td>Inspection Type<span class='mandatory'>*</span></td><td align='center'>:</td><td><select name='InspecType' value='<?= $InspecType ?>'><option value='G'>GREIGE</option><option value='P'>DYED / PRINTED</option><option value='O'>OTHER</option></select></td>");
      jQuery( "#TNCMaker" ).html("<td>Maker<span class='mandatory'>*</span></td><td align='center'>:</td><td><input type='text' name='Maker' value='<?= $Maker ?>' /></td>");
  }else{
      jQuery( "#TNCColor" ).html("Colors<span class='mandatory'>*</span>");
      jQuery( "#TNCSizes" ).html("Size<span class='mandatory'>*</span>");
      jQuery( "#TNCSSizes" ).html("Sample Size<span class='mandatory'>*</span>");
      jQuery( "#TNCInspecType" ).html("");
      jQuery( "#TNCMaker" ).html("");
      jQuery( "#AuditStage" ).val('');
  }
});
</script>
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
        $objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>