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

	$PageId     = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Code       = IO::strValue("Code");
	$Report     = IO::intValue("Report");
	$DefectType = IO::strValue("DefectType");
	$PostId     = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Report     = IO::intValue("Report");
		$DefectType = IO::strValue("DefectType");
		$Code       = IO::strValue("Code");
		$BuyerCode  = IO::strValue("BuyerCode");
		$Defect     = IO::strValue("Defect");
		$DefectZh   = IO::strValue("DefectZh");
		$DefectTr   = IO::strValue("DefectTr");
		$DefectDe   = IO::strValue("DefectDe");
                $DefectUr   = IO::strValue("DefectUr");                
                $DefectKh   = IO::strValue("DefectKh");
                $DefectPh   = IO::strValue("DefectPh");
                $DefectVn   = IO::strValue("DefectVn");
                $DefectId   = IO::strValue("DefectId");
	}


	$sStagesList      = getList("tbl_audit_stages", "code", "stage");
	$sReportsList     = getList("tbl_reports", "id", "report");
	$sDefectTypesList = getList("tbl_defect_types", "id", "type");
	$sReports         = "";

	if (@strpos($_SESSION["Email"], "@apparelco.com") === FALSE && @strpos($_SESSION["Email"], "@3-tree.com") === FALSE)
	{
		$iBrands  = @explode(",", $_SESSION["Brands"]);
		$iReports = array( );

		$sSQL = "SELECT id FROM tbl_reports WHERE ";

		for ($i = 0; $i < count($iBrands); $i ++)
		{
			$sSQL .= " FIND_IN_SET('{$iBrands[$i]}', brands) ";

			if ($i < (count($iBrands) - 1))
				$sSQL .= " OR ";
		}

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount == 0)
			$iReports[] = 1;

		for ($i = 0; $i < $iCount; $i ++)
			$iReports[] = $objDb->getField($i, 0);

		$sReports = @implode(",", $iReports);

		$sReportsList     = getList("tbl_reports", "id", "report", "FIND_IN_SET(id, '$sReports')");
		$sDefectTypesList = getList("tbl_defect_types", "id", "type", "id IN (SELECT DISTINCT(type_id) FROM tbl_defect_codes WHERE FIND_IN_SET(report_id, '$sReports'))");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/defect-codes.js"></script>
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
			    <h1>defect codes</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-defect-code.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Defect Code</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="80">Report<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Report">
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
					<td>Defect Type<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="DefectType">
						<option value=""></option>
<?
		foreach ($sDefectTypesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $DefectType) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Code<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Code" value="<?= $Code ?>" size="30" maxlength="15" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Buyer Code</td>
					<td align="center">:</td>
					<td><input type="text" name="BuyerCode" value="<?= $BuyerCode ?>" size="30" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Defect<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Defect" value="<?= formValue($Defect) ?>" size="30" maxlength="200" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Chinese</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectZh" value="<?= formValue($DefectZh) ?>" size="30" maxlength="200" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Turkish</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectTr" value="<?= formValue($DefectTr) ?>" size="30" maxlength="200" class="textbox" /></td>
				  </tr>
				
				  <tr>
				    <td>German</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectDe" value="<?= formValue($DefectDe) ?>" size="30" maxlength="200" class="textbox" /></td>
				  </tr>
					
				  <tr>
				    <td>Urdu</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectUr" value="<?= formValue($DefectUr) ?>" size="30" maxlength="200" class="textbox" /></td>
				  </tr>
                                    
                                    <tr>
				    <td>Cambodian</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectKh" value="<?= formValue($DefectKh) ?>" size="30" maxlength="200" class="textbox" /></td>
				  </tr>
                                    
                                    <tr>
				    <td>Filipino</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectPh" value="<?= formValue($DefectPh) ?>" size="30" maxlength="200" class="textbox" /></td>
				  </tr>
                                    
                                    <tr>
				    <td>Vietnamese</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectVn" value="<?= formValue($DefectVn) ?>" size="30" maxlength="200" class="textbox" /></td>
				  </tr>
                                    
                                    <tr>
				    <td>Indonesian</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectId" value="<?= formValue($DefectId) ?>" size="30" maxlength="200" class="textbox" /></td>
				  </tr>
					
				  <tr>
				    <td>Stages</td>
				    <td align="center">:</td>
					<td><select style="width: 230px; height: 150px;" name="Stages[]" multiple>
<?
		foreach($sStagesList as $iStage => $sStage)
		{
?>
					<option value="<?=$iStage?>"><?=$sStage?></option>
<?
		}
?>
						</select>
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

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="88">Defect Code</td>
			          <td width="180"><input type="text" name="Code" value="<?= $Code ?>" class="textbox" maxlength="10" /></td>
			          <td width="50">Report</td>

			          <td width="180">
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

			          <td width="88">Defect Type</td>

			          <td width="220">
					    <select name="DefectType">
						  <option value="">All Defect Types</option>
<?
	foreach ($sDefectTypesList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $DefectType) ? " selected" : "") ?>><?= $sValue ?></option>
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

	if ($Report > 0)
		$sConditions .= " AND report_id='$Report' ";

	if ($DefectType > 0)
		$sConditions .= " AND type_id='$DefectType' ";

	if ($Code != "")
		$sConditions .= " AND (`code` LIKE '%$Code%' OR buyer_code LIKE '%$Code%' OR defect LIKE '%$Code%') ";

	if ($sReports != "")
		$sConditions .= " AND FIND_IN_SET(report_id, '$sReports') ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_defect_codes", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_defect_codes $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="12%">Report</td>
				      <td width="18%">Defect Type</td>
				      <td width="12%">Code</td>
				      <td width="10%">Buyer Code</td>
				      <td width="35%">Defect</td>
				      <td width="9%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId         = $objDb->getField($i, 'id');
		$iReport     = $objDb->getField($i, 'report_id');
		$iDefectType = $objDb->getField($i, 'type_id');
		$sCode       = $objDb->getField($i, 'code');
		$sBuyerCode  = $objDb->getField($i, 'buyer_code');
		$sDefect     = $objDb->getField($i, 'defect');
		$sDefectZh   = $objDb->getField($i, 'defect_zh');
		$sDefectTr   = $objDb->getField($i, 'defect_tr');
		$sDefectDe   = $objDb->getField($i, 'defect_de');
		$sDefectUr   = $objDb->getField($i, 'defect_ur');                
                $sDefectKh   = $objDb->getField($i, 'defect_kh');
                $sDefectPh   = $objDb->getField($i, 'defect_ph');
                $sDefectVn   = $objDb->getField($i, 'defect_vn');
                $sDefectId   = $objDb->getField($i, 'defect_id');
                
		$sStages     = explode(",", $objDb->getField($i, 'stages')); 
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="4%"><?= ($iStart + $i + 1) ?></td>
				      <td width="12%"><span id="Report<?= $iId ?>"><?= $sReportsList[$iReport] ?></span></td>
				      <td width="18%"><span id="DefectType<?= $iId ?>"><?= $sDefectTypesList[$iDefectType] ?></span></td>
				      <td width="12%"><span id="Code<?= $iId ?>"><?= $sCode ?></span></td>
				      <td width="10%"><span id="BuyerCode<?= $iId ?>"><?= $sBuyerCode ?></span></td>
				      <td width="35%"><span id="Defect<?= $iId ?>"><?= $sDefect ?></span></td>

				      <td width="9%" class="center">
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
				        <a href="quonda/delete-defect-code.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Defect Code?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
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

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="80">Report<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Report">
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

					    <tr>
						  <td>Defect Type<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="DefectType">
<?
		foreach ($sDefectTypesList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $iDefectType) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Code<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Code" value="<?= $sCode ?>" size="30" maxlength="15" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Buyer Code</td>
						  <td align="center">:</td>
						  <td><input type="text" name="BuyerCode" value="<?= $sBuyerCode ?>" size="30" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Defect<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Defect" value="<?= formValue($sDefect) ?>" size="30" maxlength="200" class="textbox" /></td>
					    </tr>
						
					    <tr>
						  <td>Chinese</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DefectZh" value="<?= $sDefectZh ?>" size="30" maxlength="200" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Turkish</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DefectTr" value="<?= $sDefectTr ?>" size="30" maxlength="200" class="textbox" /></td>
					    </tr>
						
					    <tr>
						  <td>German</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DefectDe" value="<?= $sDefectDe ?>" size="30" maxlength="200" class="textbox" /></td>
					    </tr>
                                              
                                            <tr>
						  <td>Urdu</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DefectUr" value="<?= $sDefectUr ?>" size="30" maxlength="200" class="textbox" /></td>
					    </tr>  
                                              
                                            <tr>
                                                <td>Cambodian</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="DefectKh" value="<?= formValue($sDefectKh) ?>" size="30" maxlength="200" class="textbox" /></td>
                                              </tr>

                                                <tr>
                                                <td>Filipino</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="DefectPh" value="<?= formValue($sDefectPh) ?>" size="30" maxlength="200" class="textbox" /></td>
                                              </tr>

                                                <tr>
                                                <td>Vietnamese</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="DefectVn" value="<?= formValue($sDefectVn) ?>" size="30" maxlength="200" class="textbox" /></td>
                                              </tr>

                                                <tr>
                                                <td>Indonesian</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="DefectId" value="<?= formValue($sDefectId) ?>" size="30" maxlength="200" class="textbox" /></td>
                                              </tr>  
                                           
						<tr>
							<td>Stages</td>
							<td align="center">:</td>
							<td><select name="Stages[]" style="width: 230px; height: 150px;"  multiple>
<?
							foreach($sStagesList as $iStage => $sStage)
							{
?>
								<option value="<?=$iStage?>" <?=(in_array($iStage, $sStages)?'selected':'')?>><?=$sStage?></option>
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
				      <td class="noRecord">No Defect Code Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Report={$Report}&DefectType={$DefectType}&Code={$Code}");

	
	if ($Report > 0)
	{
?>
				<div class="buttonsBar" style="margin-top:4px;">
				  <input type="hidden" id="ExportUrl" name="ExportUrl" value="<?= (SITE_URL."quonda/export-defect-codes.php?Report={$Report}&DefectType={$DefectType}&Code={$Code}") ?>" />
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>