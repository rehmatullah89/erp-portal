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
	$AuditCode       = IO::strValue("AuditCode");
	$Report          = IO::strValue("Report");
	$Auditor         = IO::intValue("Auditor");
	$Group           = IO::intValue("Group");
	$Brand           = IO::intValue("Brand");
	$Vendor          = IO::intValue("Vendor");
	$FromDate        = IO::strValue("FromDate");
	$ToDate          = IO::strValue("ToDate");
	$Region          = IO::intValue("Region");
	$Approved        = IO::strValue("Approved");
	$Department      = IO::intValue("Department");
	$Maker           = IO::strValue("Maker");
	$LotNo           = IO::strValue("LotNo");
	$InspecType      = IO::strValue("InspecType");
	$PostId          = IO::strValue("PostId");
	$AuditQty        = IO::strValue("AuditQty");       
        $AuditType       = IO::strValue("AuditType");       
	$CheckLevel      = IO::strValue("CheckLevel");
	$InspectionLevel = IO::strValue("InspectionLevel");
	$Completed       = IO::strValue("Completed");
	$AuditStage      = "";

	if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE || @strpos($_SESSION["Email"], "dkcompany.com") !== FALSE || @strpos($_SESSION["Email"], "hema.nl") !== FALSE ||
	    @strpos($_SESSION["Email"], "kcmtar.com") !== FALSE || @strpos($_SESSION["Email"], "mister-lady.com") !== FALSE)
		$AuditStage = "F";


	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Auditor         = IO::strValue("Auditor");
		$Group           = IO::strValue("Group");
		$Department      = IO::strValue("Department");
		$Brand           = IO::intValue("Brand");
		$Vendor          = IO::intValue("Vendor");
		$Unit            = IO::strValue("Unit");
		$Report          = IO::strValue("Report");
		$Line            = IO::strValue("Line");
		$AuditDate       = IO::strValue("AuditDate");
		$StartHour       = IO::strValue("StartHour");
		$StartMinutes    = IO::strValue("StartMinutes");
		$EndHour         = IO::strValue("EndHour");
		$EndMinutes      = IO::strValue("EndMinutes");
		$AuditStage      = IO::strValue("AuditStage");
		$Po              = IO::intValue("Po");
		$OrderNo         = IO::strValue("OrderNo");
		$StyleNo         = IO::intValue("StyleNo");
		$Colors          = IO::getArray("Colors");
		$Sizes           = IO::getArray("Sizes");
		$SampleSize      = IO::intValue("SampleSize");
		$CheckLevel      = IO::strValue("CheckLevel");
		$InspectionLevel = IO::strValue("InspectionLevel");
                $AuditType       = IO::strValue("AuditType"); 
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
	//$sVendorsList        = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sGroupsList         = getList("tbl_auditor_groups", "id", "name");
	$sDepartmentsList    = getList("tbl_departments", "id", "department", "`code`!=''");
	$sGroupsList         = getList("tbl_auditor_groups", "id", "name");

	$sAuditStages        = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
        $sAuditTypes         = getList("tbl_audit_types", "id", "type", "", "position");
	$sAuditStagesList    = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')", "position");
        $sReportTypes        = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");        
	$sReportsList        = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReportTypes')");
        
        if (@in_array($_SESSION["UserType"], array("LEVIS")))
            $sBrandsList         = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})", "position");
        else    
            $sBrandsList         = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
            
	if (@strpos($_SESSION["Email"], "@gms-fashion") !== FALSE)
		$sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A' AND email LIKE '%@gms-fashion%'");

	if (@in_array($_SESSION["UserType"], array("MGF", "CONTROLIST", "GLOBALEXPORTS", "LEVIS", "HOHENSTEIN", "HYBRID", "JCREW", "MATRIX")))
		$sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A' AND user_type='{$_SESSION['UserType']}'");
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
			    <h1>Audit Codes</h1>

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

                if($_SESSION["UserType"] == "LEVIS")   
                {
?>
                    <tr>
                       <td width="95">Audit Type<span class="mandatory">*</span></td>
                       <td width="20" align="center">:</td>

                        <td>
                            <select name="AuditType" id="AuditTypeId" onchange="setAuditStageEmpty();">
                                <option value=""></option>
<?
			foreach ($sAuditTypes as $sKey => $sValue)
			{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $AuditType) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			}
?>
                            </select>
                        </td>
                    </tr>                               
<?
                }
                
		if(count($sReportsList) > 1 && $_SESSION["UserType"] != "LEVIS")
		{
?>
                         <tr>
						  <td width="95">Report Type<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
                            <select name="Report" id="ReportId" onchange="getListValues('ReportId', 'AuditStage', 'ReportTypeAuditStages');">
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
<?
		if (@in_array($_SESSION["UserType"], array("MGF","HOHENSTEIN")))
		{
                    if($_SESSION["UserType"] == "MGF")
                    {
?>
                        <tr>
                            <td width="95">Inspection Level</td>
                            <td width="20" align="center">:</td>
                            <td>
                              <select name="InspectionLevel">
                                    <option value="2"></option>
                                        <option value="1" <?= (($InspectionLevel == "1") ? " selected" : "") ?>>Level - I</option>
                                        <option value="2" <?= (($InspectionLevel == "2") ? " selected" : "") ?>>Level - II</option>
                                        <option value="3" <?= (($InspectionLevel == "3") ? " selected" : "") ?>>Level - III</option>
                                        <option value="4" <?= (($InspectionLevel == "4") ? " selected" : "") ?>>Level - S1</option>
                                        <option value="5" <?= (($InspectionLevel == "5") ? " selected" : "") ?>>Level - S2</option>
                                        <option value="6" <?= (($InspectionLevel == "6") ? " selected" : "") ?>>Level - S3</option>
                                        <option value="7" <?= (($InspectionLevel == "7") ? " selected" : "") ?>>Level - S4</option>
                              </select>
                            </td>
                        </tr>
<?
                    }
                    else
                    {
?>
                            <tr>
                               <td width="95">Sampling Plan</td>
                               <td width="20" align="center">:</td>
                               <td>
                                 <select name="CheckLevel" style="width:250px;">
                                       <option value="2"></option>
                                           <option value="1" <?= (($CheckLevel == "1") ? " selected" : "") ?>>I</option>
                                           <option value="2" <?= (($CheckLevel == "2") ? " selected" : "") ?>>II</option>
                                 </select>
                               </td>
                           </tr>
<?
                    }
?>			  
                        <tr>
                            <td width="80">AQL Level</td>
                            <td width="20" align="center">:</td>
                            <td>
                              <select name="AqlLevel">
                                    <option value="2.5"></option>
                                        <option value="1.5" <?= (($AqlLevel == "1.5") ? " selected" : "") ?>>1.5</option>
                                        <option value="2.5" <?= (($AqlLevel == "2.5") ? " selected" : "") ?>>2.5</option>
                                        <option value="4.0" <?= (($AqlLevel == "4.0") ? " selected" : "") ?>>4.0</option>
                                        <option value="6.5" <?= (($AqlLevel == "6.5") ? " selected" : "") ?>>6.5</option>
                              </select>
                            </td>
                        </tr>
<?
		}
                
                 if ($_SESSION["UserType"] == "CONTROLIST")
                 {
                    if(!@in_array("38", array($sReportTypes)))
                    {
?>
                        <tr>
                            <td width="95">Inspection Level</td>
                            <td width="20" align="center">:</td>
                            <td>
                              <select name="InspectionLevel">
                                    <option value="2"></option>
                                        <option value="1" <?= (($InspectionLevel == "1") ? " selected" : "") ?>>Level - I</option>
                                        <option value="2" <?= (($InspectionLevel == "2") ? " selected" : "") ?>>Level - II</option>
                              </select>
                            </td>
                        </tr>

                        <tr id="InspectionCheckId"></tr>  

<?                  }
                 }
                 else
                 {
?>
                        <tr id="InspectionLevelId"></tr>
                        <tr id="InspectionCheckId"></tr>
<?
                 }
?>
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
<?
		if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE")))
		{
?>
                                              <tr id="GroupId">
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

                       <tr style="display: none;">
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
<?
        }
?>
                       <tr>
						  <td>Brand<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
                            <select name="Brand" id="Brand"  onchange="getListValues('Brand', 'Vendor', 'BrandVendors');">
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
<?
		if (!@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "GLOBALEXPORTS")))
		{
?>
                                <select id="Vendor" name="Vendor"  onchange="setAutoPo(this.value); clearPos('');">
<?
                }		
		else
		{
?>
                                <select id="Vendor" name="Vendor"  onchange="getListValues('Vendor', 'Unit', 'VendorUnits'); getListValues('Vendor', 'Line', 'Lines'); setAutoPo(this.value);  clearPos('');">
<?
                }
?>                                                          
							  <option value=""></option>
<?
		if ($Brand > 0)
		{
			
			$sBrandVendors = getDbValue("vendors","tbl_brands", "id = '$Brand'");
                        
                        if (@in_array($_SESSION["UserType"], array("LEVIS")))
                            $sVendorsList = getList("tbl_vendors", "id", "DISTINCT(CONCAT(`code`,'-', `vendor`))", "id IN ({$_SESSION['Vendors']}) AND id IN ({$sBrandVendors}) AND parent_id='0' AND  AND sourcing='Y'");    
                        else
                            $sVendorsList = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND id IN ({$sBrandVendors}) AND parent_id='0' AND  AND sourcing='Y'");
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
<?
		if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "GLOBALEXPORTS")))
		{
?>
                        <tr id="UnitId">
					  	  <td>Unit</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Unit" id="Unit" onchange="getUnitLines('', 'Line');  clearPos('');">
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

                        <tr id="LineId">
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
<?
		}
?>
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
								<input type="text" id="OrderNo" name="OrderNo" value="<?= htmlentities($OrderNo, ENT_QUOTES) ?>" size="20" maxlength="50" autocomplete="off" class="textbox" />

								<div id="Choices_OrderNo" class="autocomplete" style="display:none;"></div>

								<script type="text/javascript">
								<!--
									function clearPos(sId)
									{
										jQuery("#OrderNo" + sId).val("");
										jQuery("#AdditionalPO" + sId).val("");
                    jQuery("#StyleNo" + sId).html("");
										jQuery("#StyleNoMultiple" + sId).html("");
										jQuery("#Colors" + sId).html("");
										jQuery("#Sizes" + sId).html("");
									}
								
								
									function setAutoPo(iVendor)
									{
                      var ibrand = document.getElementById("Brand").value;

								       new Ajax.Autocompleter("OrderNo", "Choices_OrderNo", ("ajax/get-purchase-orders.php?Vendor=" + iVendor+"&Brand="+ibrand), { paramName:"Keywords", minChars:3, afterUpdateElement:getPoId } );
								    }

<?
		if ($_POST)
		{
?>
									new Ajax.Autocompleter("OrderNo", "Choices_OrderNo", "ajax/get-purchase-orders.php?Vendor=<?= $Vendor ?>&Brand=<?=$Brand?>", { paramName:"Keywords", minChars:3, afterUpdateElement:getPoId } );
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
												jQuery("#StyleNoMultiple").html("");
                                                                                                
                                                                                                if(jQuery("#ReportId").val() != '44' && jQuery("#ReportId").val() != '45')
                                                                                                    jQuery("#StyleNo").get(0).options[0] = new Option("", "", false, false);

                                                                                                if(jQuery("#ReportId").val() == '54')
                                                                                                    jQuery("#StyleNoMultiple").get(0).options[0] = new Option("", "", false, false);                                                                                                  


												if (sResponse != "")
												{
													var sOptions = sResponse.split("||");
                                                                                                       
                                                                                                        if(jQuery("#ReportId").val() == '44' || jQuery("#ReportId").val() == '45')
                                                                                                            jQuery("#StyleNo").val(sOptions[0]);
                                                                                                        else if(jQuery("#ReportId").val() == '54') {
                                                                                                            var sOptions = sResponse.split("|-|");
                                                                                                            
                                                                                                            for (var i = 0; i < sOptions.length; i ++)
                                                                                                            {
                                                                                                                    var sOption = sOptions[i].split("||");
                                                                                                                    
                                                                                                                        jQuery("#StyleNoMultiple").get(0).options[(i)] = new Option(sOption[1], sOption[0], false, false);
                                                                                                            }                                                                                                          
                                                                                                        }
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
							<td id="AdditionalPoTd"><input type="text" name="AdditionalPO" id="AdditionalPO" value="" class="textbox" size="30" maxlength="200" /></td>
						</tr>  
<?
        if($_SESSION["UserType"] == "LEVIS")   
        {
?>
                                               <tr valign="top">
							<td>Item Number</td>
							<td align="center">:</td>
                                                        <td><input type="text" name="ItemNumber" id="ItemNumber" value="<?= getDbValue("item_number", "tbl_po", "id='$Po'");?>" class="textbox" size="30" maxlength="200" readonly/></td>
						</tr>  
                                              <tr valign="top">
                                                <td>Product Code</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="ProductCode" id="ProductCode" value="<?= getDbValue("product_code", "tbl_po", "id='$Po'");?>" class="textbox" size="30" maxlength="200" readonly/></td>
                                              </tr>
                                              <input type="hidden" name="StyleNo" id="StyleNo" value="<?= getDbValue("s.id", "tbl_styles s, tbl_po_colors pc", "s.id=pc.style_id AND pc.po_id='$Po'");?>"/>
                                              <input type="hidden" name="Colors[]" id="Colors" value="<?= getDbValue("color", "tbl_po_colors", "po_id='$Po' AND style_id='$iStyle'");?>"/>
<?
        }
        else{

          $multipleStyle = 'style="display: none;"';
          $singleStyle = '';

          if($Report == '54') {
            $multipleStyle = '';
            $singleStyle = 'style="display: none;"';
          }
?>
            <tr id="singleStyle" <?=$singleStyle?>>
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
						<tr id="multipleStyle" <?=$multipleStyle?>>
						  <td>Style No<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="StyleNo[]" id="StyleNoMultiple" size="5" multiple style="min-width:160px;">
							  <option value=""></option>
<?
	$sStyles = getList("tbl_styles s, tbl_po_colors pc", "DISTINCT(s.id)", "s.style", "s.id=pc.style_id AND pc.po_id='$Po'", "s.style");

  $selectedstyles = explode(",", $sStyleNos);

	foreach ($sStyles as $sKey => $sValue)
	{
?>
	  	        		      <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $selectedstyles)) ? ' selected' : '') ?>><?= $sValue ?></option>
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
<?
        }
?>
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
<?
                    if ($_SESSION["UserType"] == "CONTROLIST")
                    {
?>
                        <tr>
                            <td>Offered Quantity<span class="mandatory">*</span></td>
                            <td align="center">:</td>
                            <td id="OfferedQty"><input type="text" name="OfferedQty" id="OfferedQty" value="" class="textbox" size="20" maxlength="50" /></td>
                        </tr>  
<?                        
                    }
					
					else
					{
?>
                                              <tr id="SampleSizeId" class="SampleSize">
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
                                              
<?
                    }
?>
                                              
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
			          <td width="130"><input type="text" name="AuditCode" value="<?= $AuditCode ?>" class="textbox" maxlength="50" size="14" /></td>
<?
	if (@strpos($sReportTypes, ",") !== FALSE)
	{
?>
			          <td width="55">Report</td>

			          <td width="130">
					    <select name="Report">
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
<?
	if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE")))
	{
?>
			          <td width="45">Group</td>

			          <td width="130">
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
<?
	}
?>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>

			    <div id="SubSearchBar">
				  <table border="0" cellpadding="0" cellspacing="0">
				    <tr>
					  <td width="78">Region</td>

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
<!--
                                          <td width="20">Brand</td>
						  <td width="40">
                                                      <select name="Brand" id="Brand2"  onchange="getListValues('Brand2', 'Vendor2', 'BrandVendors');">
							  <option value=""></option>
<?
/*
	foreach ($sBrandsList as $iBrand => $sBrand)
	{
?>
			            	  <option value="<?= $iBrand ?>"<?= (($iBrand == $Brand) ? " selected" : "") ?>><?= $sBrand ?></option>
<?
	}
 
 */
?>
						    </select>
						  </td>
-->
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
	if ($_SESSION["UserType"] != "MGF")
	{
?>
			          <td width="75">Completed</td>

			          <td width="130">
					    <select name="Completed" style="width:90%;">
						  <option value="">Any Status</option>
			              <option value="Y"<?= (($Completed == "Y") ? " selected" : "") ?>>Yes</option>
						  <option value="N"<?= (($Completed == "N") ? " selected" : "") ?>>No</option>
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
					  <td width="55" align="center">To</td>
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


	$sSQL = "SELECT id, aql, brand_id, audit_result, audit_type_id, inspection_level, check_level, audit_quantity, audit_code, additional_pos, additional_styles, inspection_type, maker, user_id, group_id, department_id, vendor_id, unit_id, report_id, line_id, audit_date, audit_stage, start_time, end_time, po_id, style_id, colors, sizes, total_gmts, approved, cutting_lot_no, published,
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
<?
			if (!@in_array($_SESSION["UserType"], array("MGF")) && !@in_array("28", array($sReportTypes)) && !@in_array("37", array($sReportTypes)) && !@in_array("38", array($sReportTypes)))
			{
?>
				      <td width="10%">Line</td>
<?
			}
?>
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
		$sAdditionalStyles  = $objDb->getField($i, 'additional_styles');
		$iOfferedQty    = $objDb->getField($i, 'audit_quantity');
		$iInspecLevel   = $objDb->getField($i, 'inspection_level');
		$iCheckLevel    = $objDb->getField($i, 'check_level');
                $iAqlLevel      = $objDb->getField($i, 'aql');
                $iAuditType     = $objDb->getField($i, 'audit_type_id');
		$sAuditResult   = $objDb->getField($i, 'audit_result');
		$sPublished     = $objDb->getField($i, 'published');
		
		$iCountry = $sVendorCountriesList[$iVendor];
		$iHours   = $sCountryHoursList[$iCountry];
		
		$sStartTime = date("H:i:s", (strtotime($sStartTime) + ($iHours * 3600)));
		$sEndTime   = date("H:i:s", (strtotime($sEndTime) + ($iHours * 3600)));
					
		@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
		@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);


		$sOrderNo = "";
		$sStyleNo = "";

		if ($iPoId > 0)
			$sOrderNo = getDbValue("order_no", "tbl_po", "id='$iPoId'");

		if ($iStyleId > 0)
			$sStyleNo = getDbValue("style", "tbl_styles", "id='$iStyleId'");

    if($iReport == 54) {

      if($sAdditionalStyles != ""){

        $sStyleNos = $iStyleId.",".$sAdditionalStyles; 
      } else {
        $sStyleNos = $iStyleId;
      }

      $style = 'style="display:none;"';
    }
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>"<?= (($sApproved == 'N') ? ' style="background:#ffeaea;"' : '') ?> id="Record<?= $iId ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="10%"><?= $sAuditCode ?></td>
				      <td width="18%"><span id="Auditor_<?= $iId ?>"><?= $sUsersList[$iAuditor] ?><?= (($iGroup > 0) ? " (G)" : "") ?></span></td>
				      <td width="18%"><span id="Vendor_<?= $iId ?>"><?= $sVendorsList[$iVendor] ?></span></td>
<?
		if (!@in_array($_SESSION["UserType"], array("MGF")) && !@in_array("28", array($sReportTypes)) && !@in_array("37", array($sReportTypes)) && !@in_array("38", array($sReportTypes)))
		{
?>
				      <td width="10%"><span id="Line_<?= $iId ?>"><?= $sLine ?></span></td>
<?
		}
?>
				      <td width="10%"><span id="Date_<?= $iId ?>"><?= formatDate($sAuditDate) ?></span></td>
				      <td width="9%"><span id="StartTime_<?= $iId ?>"><?= (str_pad($iStartHour, 2, '0', STR_PAD_LEFT).":".str_pad($iStartMinutes, 2, '0', STR_PAD_LEFT)." ".$sStartAmPm) ?></span></td>
				      <td width="9%"><span id="EndTime_<?= $iId ?>"><?= (str_pad($iEndHour, 2, '0', STR_PAD_LEFT).":".str_pad($iEndMinutes, 2, '0', STR_PAD_LEFT)." ".$sEndAmPm) ?></span></td>

				      <td width="8%" class="center">
<?
		if ($sUserRights['Edit'] == "Y" && (($iAuditor == $_SESSION['UserId'] &&  @in_array($iReport, array(14,34)) && $sPublished != "Y") || $sAuditResult == "" || $_SESSION["UserType"] != "MGF") && ($_SESSION["UserType"] != "JCREW" || ($_SESSION["UserType"] == "JCREW" && $iReport == 46)))
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
                if($_SESSION["UserType"] == "LEVIS" || @in_array($iReport, array(44,45)))   
                {
?>
                    <tr>
                       <td width="95">Audit Type<span class="mandatory">*</span></td>
                       <td width="20" align="center">:</td>

						  <td>
                            <select name="AuditType" id="AuditTypeId<?= $iId ?>" onchange="setAutoSampleSize(document.getElementById('AuditStage'+'<?=$iId?>'), <?=$iId?>);">
                                <option value=""></option>
<?
			foreach ($sAuditTypes as $sKey => $sValue)
			{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $iAuditType) ? " selected" : "") ?>><?= $sValue ?></option>
<?
			}
?>
                            </select>
                          </td>
                    </tr>                               
<?
                }
                
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

$sAuditStages = getDbValue ("stages", "tbl_reports", "id = '$iReport'");

if($sAuditStages != "") {

  $sAuditStagesList    = getList("tbl_audit_stages", "code", "stage", "FIND_IN_SET(code, '$sAuditStages')", "position");
}

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
<?               
                 if ($_SESSION["UserType"] == "CONTROLIST" || @in_array($iReport, array(28,37,38)))
                 {
                        if($iReport != 38)
                        {
?>
                        <tr>
                            <td width="95">Inspection Level</td>
                            <td width="20" align="center">:</td>
                            <td>
                              <select name="InspectionLevel">
                                        <option value="2"></option>
                                        <option value="1" <?= (($iInspecLevel == "1") ? " selected" : "") ?>>Level - I</option>
                                        <option value="2" <?= (($iInspecLevel == "2") ? " selected" : "") ?>>Level - II</option>
                              </select>
                            </td>
                        </tr>
<?
                        }
                        if($iReport == 38)
                        {
?>
                        <tr>
                            <td width="95">Sample Check Level</td>
                            <td width="20" align="center">:</td>
                            <td>
                              <select name="CheckLevel">
                                    <option value="1"></option>
                                        <option value="1" <?= (($iCheckLevel == "1") ? " selected" : "") ?>>Check Level - I</option>
                                        <option value="2" <?= (($iCheckLevel == "2") ? " selected" : "") ?>>Check Level - II</option>
                              </select>
                            </td>
                        </tr>

<?
                        }
                 }
?>
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
<?
                if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE")) && !in_array($iReport, array(44,45,46)))
		{
?>
                                                        <tr id="GroupId<?=$i?>">
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
							  <tr id="DeptId<?= $i ?>">
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
<?
                }
?>
                                          <tr>
						  <td>Brand<span class="mandatory">*</span></td>
						  <td align="center">:</td>

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
<?
                if (!@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "GLOBALEXPORTS", "JCREW")) || @in_array($iReport, array(44,45)))
		{
?>                                                                
                                <select id="Vendor<?= $i ?>" name="Vendor" onchange="setAutoPo<?= $iId ?>(this.value,<?=$i?>); clearPos('<?= $iId ?>');">
<?
                }		
		else
		{
?>
                                <select id="Vendor<?= $i ?>" name="Vendor" onchange="getListValues('Vendor<?= $i ?>', 'Unit<?= $i ?>', 'VendorUnits'); getListValues('Vendor<?= $i ?>', 'Line<?= $i ?>', 'Lines'); setAutoPo<?= $iId ?>(this.value,<?=$i?>);  clearPos('<?= $iId ?>');">            
<?
                }
?>
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
<?
                if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "GLOBALEXPORTS")) && !in_array($iReport, array(44,45,46)))
		{
?>
                                                        <tr id="UnitId<?= $i ?>">
							    <td>Unit</td>
							    <td align="center">:</td>

							    <td>
								  <select name="Unit" id="Unit<?= $i ?>" onchange="getUnitLines('<?= $i ?>', 'Line<?= $i ?>');  clearPos('<?= $iId ?>');">
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

							  <tr id="LineId<?= $i ?>">
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
<?
	}
?>
<script type="text/javascript">
<!--
                        
jQuery( document ).ready(function() {
       
<?
        if ($iReport == 14 || $iReport == 33 || $iReport == 28 || $iReport == 37 || $iReport == 38)
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

    if(this.value == '14' || this.value == '33' || this.value == '28' || this.value == '37' || this.value == '38')
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
							    <td width="90">Order/Po No<span class="mandatory">*</span></td>
							    <td width="20" align="center">:</td>

							    <td>
							      <input type="hidden" id="Po<?= $iId ?>" name="Po" value="<?= $iPoId ?>" />
							      <input type="text" name="OrderNo" id="OrderNo<?= $iId ?>" value="<?= htmlentities($sOrderNo, ENT_QUOTES) ?>" size="20" maxlength="50" class="textbox" autocomplete="off" />

								<div id="Choices_OrderNo<?= $iId ?>" class="autocomplete" style="display:none;"></div>

								<script type="text/javascript">
								<!--
									function setAutoPo<?= $iId ?>(iVendor,id)
									{
                      var ibrand = document.getElementById("Brand"+id).value;
                      
								       new Ajax.Autocompleter("OrderNo<?= $iId ?>", "Choices_OrderNo<?= $iId ?>", ("ajax/get-purchase-orders.php?Vendor=" + iVendor+"&Brand="+ibrand), { paramName:"Keywords", minChars:3, afterUpdateElement:getPoId<?= $iId ?> } );
								    }

									new Ajax.Autocompleter("OrderNo<?= $iId ?>", "Choices_OrderNo<?= $iId ?>", "ajax/get-purchase-orders.php?Vendor=<?= $iVendor ?>&Brand=<?=$iBrand?>", { paramName:"Keywords", minChars:3, afterUpdateElement:getPoId<?= $iId ?> } );



								    function getPoId<?= $iId ?>(text, li)
								    {
								    	jQuery("#Po<?= $iId ?>").val(li.id);

                                                                            if((/*jQuery("#ReportId<?= $i ?>").val() == '14' ||*/ jQuery("#ReportId<?= $i ?>").val() == '34') && jQuery("#AuditStage<?= $iId ?>").val() == 'F')
                                                                            {
                                                                                jQuery.post("ajax/quonda/get-po-status.php",
                                                                                    { Pos:li.id },

                                                                                    function (sResponse)
                                                                                    {
                                                                                            if (sResponse != "" && sResponse == 'R')
                                                                                            {
                                                                                                    jQuery("#OrderNo<?= $iId ?>").val("");
                                                                                                    alert("VPO with Released status can not be assigned to final stage.");
                                                                                            }
                                                                                    },

                                                                                "text");
                                                                            }
                                                                            
                                                                            if(jQuery("#ReportId<?= $i ?>").val() == '44' || jQuery("#ReportId<?= $i ?>").val() == '45')
                                                                            {
                                                                                jQuery.post("ajax/quonda/get-po-productcode.php",
                                                                                    { Pos:li.id },

                                                                                    function (sResponse)
                                                                                    {
                                                                                            if (sResponse != "")
                                                                                            {
                                                                                                 var sOptions = sResponse.split("|-|");
                                                                                                jQuery("#ProductCode<?= $iId ?>").val(sOptions[0]);
                                                                                                jQuery("#ItemNumber<?= $iId ?>").val(sOptions[1]);    
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                jQuery("#ProductCode<?= $iId ?>").val("");
                                                                                                 jQuery("#ItemNumber<?= $iId ?>").val("");
                                                                                            }
                                                                                    },

                                                                                "text");
                                                                            }
                                                                            
										jQuery.post("ajax/quonda/get-styles-list.php",
											{ Pos:li.id },

											function (sResponse)
											{
                        jQuery("#StyleNo<?= $iId ?>").html("");
												jQuery("#StyleNoMultiple<?= $iId ?>").html("");
                                                                                                
                                                                                                if(jQuery("#ReportId<?= $i ?>").val() != '44' && jQuery("#ReportId<?= $i ?>").val() != '45')
                                                                                                    jQuery("#StyleNo<?= $iId ?>").get(0).options[0] = new Option("", "", false, false);

                                                                                                if(jQuery("#ReportId<?= $iId ?>").val() == '54')
                                                                                                    jQuery("#StyleNoMultiple<?= $iId ?>").get(0).options[0] = new Option("", "", false, false);

												if (sResponse != "")
												{
                                                                                                    
                                                                                                        var sOptions = sResponse.split("||");
                                                                                                       
                                                                                                        if(jQuery("#ReportId<?= $i ?>").val() == '44' || jQuery("#ReportId<?= $i ?>").val() == '45')
                                                                                                            jQuery("#StyleNo<?= $iId ?>").val(sOptions[0]);
                                                                                                        else if(jQuery("#ReportId<?= $i ?>").val() == '54') {
                                                                                                            var sOptions = sResponse.split("|-|");

                                                                                                            for (var i = 0; i < sOptions.length; i ++)
                                                                                                            {
                                                                                                                    var sOption = sOptions[i].split("||");
                                                                                                                    
                                                                                                                        jQuery("#StyleNoMultiple<?= $iId ?>").get(0).options[(i)] = new Option(sOption[1], sOption[0], false, false);
                                                                                                            }                                                                                                          
                                                                                                        }
                                                                                                        else
                                                                                                        {
                                                                                                            var sOptions = sResponse.split("|-|");
                                                                                                            
                                                                                                            for (var i = 0; i < sOptions.length; i ++)
                                                                                                            {
                                                                                                                    var sOption = sOptions[i].split("||");
                                                                                                                    
                                                                                                                    if(jQuery("#ReportId<?= $i ?>").val() == '46' || jQuery("#ReportId<?= $i ?>").val() == '39')
                                                                                                                        jQuery("#StyleNo<?= $iId ?>").get(0).options[(i)] = new Option(sOption[1], sOption[0], false, false);
                                                                                                                    else
                                                                                                                        jQuery("#StyleNo<?= $iId ?>").get(0).options[(i + 1)] = new Option(sOption[1], sOption[0], false, false);
                                                                                                            }
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

                                                                                                        if(jQuery("#ReportId<?= $i ?>").val() == '44' || jQuery("#ReportId<?= $i ?>").val() == '45')
                                                                                                            jQuery("#Colors<?= $iId ?>").val(sOptions[1]);
                                                                                                        else
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
											jQuery("#StyleNoMultiple<?= $iId ?>").html("");
                                                                                        
                                                                                        if(jQuery("#ReportId<?= $i ?>").val() != '44' && jQuery("#ReportId<?= $i ?>").val() != '45')
                                                                                            jQuery("#StyleNo<?= $iId ?>").get(0).options[0] = new Option("", "", false, false);
                                                                                        else{
                                                                                            jQuery("#ProductCode<?= $iId ?>").html("");
                                                                                            jQuery("#ItemNumber<?= $iId ?>").html("");
                                                                                        }
                                                                                        
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
                                                                jQuery("#AdditionalPO<?= $iId ?>").tokenInput("ajax/quonda/get-pos-list.php?Vendor="+jQuery('#Vendor<?= $i ?>').val()+"&Report="+jQuery("#ReportId<?= $i ?>").val()+"&Stage="+jQuery("#AuditStage<?= $iId ?>").val(),
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
																
                                                                jQuery('#Vendor<?= $iId ?>').change(function()
																{
                                                                    jQuery(".token-input-list-facebook").remove();
																	
                                                                    jQuery("#AdditionalPO<?= $iId ?>").tokenInput("ajax/quonda/get-pos-list.php?Vendor="+jQuery('#Vendor<?= $i ?>').val()+"&Report="+jQuery("#ReportId<?= $i ?>").val()+"&Stage="+jQuery("#AuditStage<?= $iId ?>").val(),
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
                                                        <?
        if($_SESSION["UserType"] == "LEVIS" || @in_array($iReport, array(44,45)))   
        {
?>
                                                <tr valign="top">
							<td>Item Number</td>
							<td align="center">:</td>
                                                        <td><input type="text" name="ItemNumber" id="ItemNumber<?= $iId ?>" value="<?= getDbValue("item_number", "tbl_po", "id='$iPoId'");?>" class="textbox" size="30" maxlength="200" readonly/></td>
						</tr>          
                                               <tr valign="top">
                                                <td>Product Code</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="ProductCode" id="ProductCode<?= $iId ?>" value="<?= getDbValue("product_code", "tbl_po", "id='$iPoId'");?>" class="textbox" size="30" maxlength="200" readonly/></td>
                                              </tr>          
                                              <input type="hidden" name="StyleNo" id="StyleNo<?= $iId ?>" value="<?= getDbValue("s.id", "tbl_styles s, tbl_po_colors pc", "s.id=pc.style_id AND pc.po_id='$iPoId'");?>"/>
                                              <input type="hidden" name="Colors[]" id="Colors<?= $iId ?>" value="<?= getDbValue("color", "tbl_po_colors", "po_id='$iPoId' AND style_id='$iStyleId'");?>"/>
<?
        }
        else
        {            

    $multipleStyle = 'style="display: none;"';
    $singleStyle = '';
    $sampleSizeStyle='';

    if($iReport == '54') {
      $multipleStyle = '';
      $singleStyle = 'style="display: none;"';
      $sampleSizeStyle = 'style="display: none;"';

      if($sAdditionalStyles != ""){

        $sStyleNos = $iStyleId.",".$sAdditionalStyles; 
      } else {
        $sStyleNos = $iStyleId;
      }      
    }
    
?>

							  <tr id="singleStyle" <?=$singleStyle?>>
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

                <tr id="multipleStyle" <?=$multipleStyle?>>
                  <td>Style No<span class="mandatory">*</span></td>
                  <td align="center">:</td>

                  <td>
                  <select name="StyleNo[]" id="StyleNoMultiple<?= $iId ?>" size="5" multiple style="min-width:160px;">
                    <option value=""></option>
<?
    $sStyles = getList("tbl_styles s, tbl_po_colors pc", "DISTINCT(s.id)", "s.style", "s.id=pc.style_id AND pc.po_id='$iPoId'", "s.style");

    $selectedstyles = explode(",", $sStyleNos);

    foreach ($sStyles as $sKey => $sValue)
    {
?>
                              <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $selectedstyles)) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
    }
?>
                      </select>
                  </td>
                </tr>

							  <tr valign="top">
							    <td>Colors
                                                                <?if($iReport != '26'){?>
                                                                    <span class="mandatory">*</span>
                                                                <?}?></td>
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
<?
        }
?>
							  <tr valign="top">
							    <td>Sizes
                                                                <?if($iReport != '26'){?>
                                                                    <span class="mandatory">*</span>
                                                                <?}?><br/><br/>[ <a href="./" onclick="selectAll('Sizes<?= $iId ?>'); return false;">Select All</a>]</td>
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
							  <tr id="SampleSizeId<?=$iId?>" class="SampleSize" <?=$sampleSizeStyle?>>
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
?>
						    	    <option value="0"<?= (($iSampleSize == 0) ? " selected" : "") ?>>Custom</option>
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
		
                                                            if($iReport == '26'){
                                                        ?>
                                                        <tr>
                                                            <td>Inspection Type<span class='mandatory'>*</span></td><td align='center'>:</td><td><select name='InspecType' value='<?= $sInspecType ?>'><option value='G' <?= (($sInspecType == 'G') ? " selected" : "") ?>>GREIGE</option><option value='P' <?= (($sInspecType == 'P') ? " selected" : "") ?>>DYED / PRINTED</option><option value='O' <?= (($sInspecType == 'O') ? " selected" : "") ?>>OTHER</option></select></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Maker<span class='mandatory'>*</span></td><td align='center'>:</td><td><input type='text' name='Maker' value='<?= $cMaker ?>' /></td>
                                                        </tr>      
                                                        <?
                                                            }else if($iReport == '15'){
                                                        ?>
                                                            <td>Lot No.</td><td align='center'>:</td><td><input type='text' name='LotNo' value='<?= $sLotNo ?>' /></td>
                                                        <?  }
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
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&AuditCode={$AuditCode}&Auditor={$Auditor}&Group={$Group}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&Approved={$Approved}&Department={$Department}&Report={$Report}&Completed={$Completed}");

    if ($iCount > 0 && ($AuditCode != "" || $Auditor != 0 || $Group != 0 || $Vendor != 0 || ($FromDate != "" && $ToDate != "") || $Region != 0 || $Completed != ""))
	{
?>

				<div class="buttonsBar" style="margin-top:4px;">
				  <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= (SITE_URL."quonda/export-audit-codes.php?AuditCode={$AuditCode}&Auditor={$Auditor}&Group={$Group}&Vendor={$Vendor}&FromDate={$FromDate}&ToDate={$ToDate}&Department={$Department}&Region={$Region}&Report={$Report}&Completed={$Completed}") ?>" />
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



                        
jQuery( document ).ready(function() {
    
    jQuery('#Vendor').change(function()
	{
		jQuery("#AdditionalPoTd").html("<input type='text' name='AdditionalPO' id='AdditionalPO' value='' class='textbox' size='30' maxlength='200' />");
		
		jQuery("#AdditionalPO").tokenInput("ajax/quonda/get-pos-list.php?Vendor="+jQuery('#Vendor').val()+"&Report="+jQuery("#ReportId").val()+"&Stage="+jQuery("#AuditStage").val(),
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
        jQuery( "#TNCSizes" ).find('span').remove();
        jQuery( "#TNCSSizes" ).find('span').remove();
        jQuery( "#AuditStage" ).val('F');
        jQuery( "#TNCInspecType" ).html("<td>Inspection Type<span class='mandatory'>*</span></td><td align='center'>:</td><td><select name='InspecType' value='<?= $InspecType ?>'><option value='G'>GREIGE</option><option value='P'>DYED / PRINTED</option><option value='O'>OTHER</option></select></td>");
        jQuery( "#TNCMaker" ).html("<td>Maker<span class='mandatory'>*</span></td><td align='center'>:</td><td><input type='text' name='Maker' value='<?= $Maker ?>' /></td>");
    }
	
    else
    {
        jQuery( "#TNCColor" ).html("Colors<span class='mandatory'>*</span>");
        jQuery( "#TNCSizes" ).html("Size<span class='mandatory'>*</span>");
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
        
    if (this.value == '14' || this.value == '34' || this.value == '28' || this.value == '37' || this.value == '38')
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

    if(this.value == '54') {
      jQuery( "#multipleStyle" ).show();
      jQuery( "#singleStyle" ).hide();
      jQuery( ".SampleSize" ).hide();
    } else {
      jQuery( "#multipleStyle" ).hide();
      jQuery( "#singleStyle" ).show();      
      jQuery( ".SampleSize" ).show();      
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