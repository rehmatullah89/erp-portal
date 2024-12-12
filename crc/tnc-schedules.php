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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
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

	$PageId     = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Vendor     = IO::intValue("Vendor");
	$Auditor    = IO::intValue("Auditor");
        $Section    = IO::intValue("Section");
        $AuditType  = IO::strValue("AuditType");
        $Points     = IO::getArray("Points");
        $Brand      = IO::intValue("Brand");
	$FromDate   = IO::strValue("FromDate");
	$ToDate     = IO::strValue("ToDate");
        $Unit       = IO::strValue("Unit");
        $ddQuestion = IO::strValue("ddQuestions");
        $PostId     = IO::strValue("PostId");


	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Vendor     = IO::intValue("Vendor");
		$Auditor    = IO::intValue("Auditor");
		$AuditDate  = IO::strValue("AuditDate");
                $Section    = IO::intValue("Section");
                $AuditType  = IO::strValue("AuditType");
                $Points     = IO::getArray("Points");
                $Brand      = IO::intValue("Brand");
                $Unit       = IO::strValue("Unit");
        }


	$sVendorsList           = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sAuditorsList          = getList("tbl_users", "id", "name", "auditor_type='6'");
        $sAuditTypesList        = getList("tbl_crc_audit_types", "id", "type", "id>0", "position");
        $sBrandsList            = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
        $sSectionList           = getList("tbl_tnc_sections", "id", "section");
        $sParentSectionsList    = getList("tbl_tnc_sections", "id", "section", "parent_id='0'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/tnc-schedules.js"></script>
  
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
			    <h1>CRC Schedules</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
				<form name="frmData" id="frmData" method="post" action="crc/save-tnc-schedule.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Schedule New Audit</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%" id="Mytable">
                                    <tr>
					<td width="95">Brand</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Brand" id="Brand" style="width: 210px;" onchange="getListValues('Brand', 'Vendor', 'BrandVendorsRecomended'); resetSections();">
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
					<td >Vendor</td>
					<td  align="center">:</td>

					<td>
                                            <select name="Vendor" id="Vendor" style="width: 210px;" onchange="getListValues('Vendor', 'Unit', 'VendorUnits'); hideQuestionType();" >
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
                                  <tr id="UnitId">
					  	  <td>Unit</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Unit" id="Unit" style="width: 210px;">
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
 
                                    <tr valign="top">
					<td>Auditor</td>
					<td align="center">:</td>

					<td>
					  <select name="Auditor" id="Auditor" style="width: 210px;">
                                              <option value=""></option>
<?
		foreach ($sAuditorsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= ($sKey == $Auditor)? " selected" : ""; ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>
                                    
                                    <tr valign="top">
					<td>Audit Type</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditType" id="AuditType" style="width: 210px;" onchange="resetSections2();"> <!-- getQuestionsOptions(this.value); -->
                                              <option value=""></option>
<?
                                                foreach($sAuditTypesList as $AuditTypeCode => $sAuditType){
?>
                                              <option value="<?=$AuditTypeCode;?>"<?= (($AuditType == $AuditTypeCode) ? " selected" : "") ?>><?=$sAuditType?></option>
<?
                                                }
?>
					  </select>
					</td>
				    </tr>
                                    
                                    <tr valign="top">
					<td>Group Audit</td>
					<td align="center">:</td>

					<td>
					  <select name="GroupAudit" id="GroupAudit" style="width: 210px;" onchange="getPreviousAudits(this.value); resetSections2();">
                                              <option value="N">No</option>
                                              <option value="Y">Yes</option>
					  </select>
					</td>
				    </tr>
                                    
                                    <tr id="PreviousAuditBlock" valign="top" style="display:none;">
					<td>Parent Audit</td>
					<td align="center">:</td>

					<td>
                                            <select name="PreviousAudit" id="PreviousAudit" style="width: 210px;"></select>
					</td>
				    </tr>
				  
                                  <tr>
					<td width="60">Audit Date</td>
					<td width="20" align="center">:</td>
					<td>
					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="AuditDate" id="AuditDate" value="<?= $AuditDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>
                                  <tr id="AuditSectionId">
				    <td width="60">Audit Section</td>
				    <td width="20" align="center">:</td>

				    <td>
                                        <select name="Section" id="Section" style="width: 210px;" onchange="resetPoints()">
				        <option value=""></option>
<?
                            foreach ($sParentSectionsList as $iSection => $sSection)
                            {
?>
                                <option value="<?= $iSection ?>"<?= (($iSection == $Section) ? " selected" : "") ?>><?= $sSection ?></option>
<?                          }
?>  
                                      </select>
                                    </td>
				  </tr>  
                                    
                                    <tr valign="top" id="SelectQuestionsBlock">
					<td>Select Questions</td>
					<td align="center">:</td>

					<td>
					  <select name="ddQuestions" id="ddQuestions" style="width: 210px;" onchange="getPoints(document.getElementById('Section').value, this.value, 'Points');">
                                              <option value=""></option>
                                              <option value="A" <?=($ddQuestion == 'A')?'selected':''?>>All Categories/ All Questions</option>
                                              <option value="Q" <?=($ddQuestion == 'Q')?'selected':''?>>Selective Questionare</option>
                                              <option value="S" <?=($ddQuestion == 'S')?'selected':''?>>Selective Sections</option>
                                              <option id="toggleOpt" style="display: none;" value="F" <?=($ddQuestion == 'F')?'selected':''?>>Only Failed in Previous Audit</option>
					  </select>
					</td>
				    </tr>
                                    
                                    <tr style="display:none;" id="PointsBlock">
					<td>Audit Points</td>
					<td align="center">:</td>

					<td>
                                            <div id="toggleChecks" style="display:none;"><input type="checkbox"  onClick="checkAll(this)" checked/> Toggle All<br/></div>
					</td>
				    </tr>
                                    <tr  id="PointsBlock2">
                                        <td>&nbsp</td>
                                        <td colspan="2"><div id="Questions"></div></td>
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
			          <td width="52">Vendor</td>

			          <td width="180">
			            <select name="Vendor" id="Vendor" style="width:180px;">
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

			          <td width="55">Auditor</td>

			          <td width="180">
			            <select name="Auditor" id="Auditor" style="width: 90%">
			              <option value="">All Auditors</option>
<?
	foreach ($sAuditorsList as $sKey => $sValue)
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
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
					  <td width="52">From</td>
					  <td width="145"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:135px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="35"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="55" align="center">To</td>
					  <td width="145"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:135px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td>[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
			        </tr>
			      </table>
			      </div>
			    </form>


			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Vendor > 0)
		$sConditions .= " WHERE vendor_id='$Vendor' ";

	else
		$sConditions .= " WHERE vendor_id IN ({$_SESSION['Vendors']}) ";

	if ($Auditor > 0)
		$sConditions .= " AND auditor_id = '$Auditor' ";

	if ($FromDate != "" && $ToDate != "")
		$sConditions .= " AND (audit_date BETWEEN '$FromDate' AND '$ToDate') ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_crc_audits", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, vendor_id, audit_date, auditor_id, audit_type_id, total_score
                FROM tbl_crc_audits $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="5%">#</td>
                                      <td width="10%">Schedule #</td>
                                      <td width="17%">Audit Type</td>
				      <td width="18%">Vendor</td>
				      <td width="13%">Audit Date</td>
				      <td width="25%">Auditor</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
<?
		}


		$iId        = $objDb->getField($i, 'id');
		$iVendor    = $objDb->getField($i, 'vendor_id');
		$sAuditDate = $objDb->getField($i, 'audit_date');
		$iAuditor   = $objDb->getField($i, 'auditor_id');
                $iAuditType = $objDb->getField($i, 'audit_type_id');
                $iTotalScore= $objDb->getField($i, 'total_score');

?>

				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td><?= ($iStart + $i + 1) ?></td>
                                      <td><?= "C".str_pad($iId, 5, 0, STR_PAD_LEFT)?></td>
                                      <td><?= $sAuditTypesList[$iAuditType] ?></td>
				      <td><?= $sVendorsList[$iVendor] ?></td>
				      <td><?= formatDate($sAuditDate) ?></td>
				      <td><?= $sAuditorsList[$iAuditor] ?></td>

				      <td class="center">
<?
		if ($sUserRights['Edit'] == "Y" && $iTotalScore == 0)
		{
?>
				        <a href="crc/edit-tnc-schedule.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
                                        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y" && $iTotalScore == 0)
		{
?>
				        <a href="crc/delete-tnc-schedule.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Schedule?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="crc/view-tnc-schedule.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="CRC Audit Schedule :: :: width: 900, height: 650"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Audit Schedule Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Vendor={$Vendor}&Auditor={$Auditor}&FromDate={$FromDate}&ToDate={$ToDate}");
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