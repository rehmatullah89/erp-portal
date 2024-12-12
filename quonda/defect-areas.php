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
	$DefectArea = IO::strValue("DefectArea");
	$PostId     = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$DefectArea   = IO::strValue("DefectArea");
		$DefectAreaZh = IO::strValue("DefectAreaZh");
		$DefectAreaTr = IO::strValue("DefectAreaTr");
		$DefectAreaDe = IO::strValue("DefectAreaDe");
                $DefectAreaUr = IO::strValue("DefectAreaUr");                
                $DefectAreaKh = IO::strValue("DefectAreaKh");
                $DefectAreaPh = IO::strValue("DefectAreaPh");
                $DefectAreaVn = IO::strValue("DefectAreaVn");
                $DefectAreaId = IO::strValue("DefectAreaId");
	}
        
        $sStagesList    = getList("tbl_audit_stages", "code", "stage");
        $sReportsList   = getList("tbl_reports", "id", "report");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/defect-areas.js"></script>
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
			    <h1>defect areas</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-defect-area.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Defect Area</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="80">Defect Area<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="DefectArea" value="<?= $DefectArea ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Chinese</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectAreaZh" value="<?= formValue($DefectAreaZh) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Turkish</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectAreaTr" value="<?= formValue($DefectAreaTr) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>German</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectAreaDe" value="<?= formValue($DefectAreaDe) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>				  
                                  <tr>
				    <td>Urdu</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectAreaUr" value="<?= formValue($DefectAreaUr) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>	
                                    
                                    <tr>
				    <td>Cambodian</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectAreaKh" value="<?= formValue($DefectAreaKh) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>	
                                    <tr>
				    <td>Filipino</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectAreaPh" value="<?= formValue($DefectAreaPh) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>	
                                    
                                    <tr>
				    <td>Vietnamese</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectAreaVn" value="<?= formValue($DefectAreaVn) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>
                                    
                                    <tr>
				    <td>Indonesian</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectAreaId" value="<?= formValue($DefectAreaId) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>	
                                    
                                  <tr>
				    <td>Stages</td>
				    <td align="center">:</td>
                                    <td><select name="Stages[]" style="width:230px;" multiple>
<?
                                        foreach($sStagesList as $iStage => $sStage)
                                        {
?>
                                            <option value="<?=$iStage?>"><?=$sStage?></option>
<?
                                        }
    
?>
                                        </select></td>
				  </tr>
                                  <tr>
				    <td>Reports</td>
				    <td align="center">:</td>
                                    <td><select name="Reports[]" style="width:230px;" multiple>
<?
                                        foreach($sReportsList as $iReport => $sReport)
                                        {
?>
                                            <option value="<?=$iReport?>"><?=$sReport?></option>
<?
                                        }
    
?>
                                        </select></td>
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
			          <td width="90">Defect Area</td>
			          <td width="150"><input type="text" name="DefectArea" value="<?= $DefectArea ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass     = array("evenRow", "oddRow");
	$sColor     = array(EVEN_ROW_COLOR, ODD_ROW_COLOR);
	$iPageSize  = PAGING_SIZE;
	$iPageCount = 0;

	if ($DefectArea != "")
		$sConditions .= " WHERE area LIKE '%$DefectArea%'";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_defect_areas", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_defect_areas $sConditions ORDER BY area LIMIT $iStart, $iPageSize";
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
				      <td width="70%">Defect Area</td>
				      <td width="10%">Status</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>	
<?
		}

		$iId           = $objDb->getField($i, 'id');
		$sDefectArea   = $objDb->getField($i, 'area');
		$sDefectAreaZh = $objDb->getField($i, 'area_zh');
		$sDefectAreaTr = $objDb->getField($i, 'area_tr');
		$sDefectAreaDe = $objDb->getField($i, 'area_de');		
                $sDefectAreaUr = $objDb->getField($i, 'area_ur');	                
                $sDefectAreaKh = $objDb->getField($i, 'area_kh');	
                $sDefectAreaPh = $objDb->getField($i, 'area_ph');	
                $sDefectAreaVn = $objDb->getField($i, 'area_vn');	
                $sDefectAreaId = $objDb->getField($i, 'area_id');
                
                $sStages       = explode(",", $objDb->getField($i, 'stages'));  
                $sReports      = explode(",", $objDb->getField($i, 'reports'));  
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="70%"><span id="DefectArea<?= $iId ?>"><?= $sDefectArea ?></span></td>
				      <td width="10%"><?= (($objDb->getField($i, 'status') == "A") ? "Active" : "In-Active") ?></td>

				      <td width="12%" class="center">
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
				        <a href="quonda/delete-defect-area.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Defect Area?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
						  <td width="80">Defect Area<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="DefectArea" value="<?= $sDefectArea ?>" size="30" maxlength="50" class="textbox" /></td>
						</tr>
						
					    <tr>
						  <td>Chinese</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DefectAreaZh" value="<?= $sDefectAreaZh ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Turkish</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DefectAreaTr" value="<?= $sDefectAreaTr ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>
						
					    <tr>
						  <td>German</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DefectAreaDe" value="<?= $sDefectAreaDe ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>
                                              
                                            <tr>
						  <td>Urdu</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DefectAreaUr" value="<?= $sDefectAreaUr ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>  
                                              
                                             <tr>
                                                <td>Cambodian</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="DefectAreaKh" value="<?= formValue($sDefectAreaKh) ?>" size="30" maxlength="100" class="textbox" /></td>
                                              </tr>	
                                              
                                                <tr>
                                                <td>Filipino</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="DefectAreaPh" value="<?= formValue($sDefectAreaPh) ?>" size="30" maxlength="100" class="textbox" /></td>
                                              </tr>	

                                                <tr>
                                                <td>Vietnamese</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="DefectAreaVn" value="<?= formValue($sDefectAreaVn) ?>" size="30" maxlength="100" class="textbox" /></td>
                                              </tr>

                                              <tr>
                                                <td>Indonesian</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="DefectAreaId" value="<?= formValue($sDefectAreaId) ?>" size="30" maxlength="100" class="textbox" /></td>
                                              </tr>	  
                                              
                                            <tr>
                                            <td>Stages</td>
                                            <td align="center">:</td>
                                            <td><select name="Stages[]" multiple>
<?
                                                    foreach($sStagesList as $iStage => $sStage)
                                                    {
?>
                                                <option value="<?=$iStage?>" <?=(in_array($iStage, $sStages)?'selected':'')?>><?=$sStage?></option>
<?
                                                    }
    
?>
                                            </select></td>
                                            </tr>                                              
                                            <tr>
                                            <td>Reports</td>
                                            <td align="center">:</td>
                                            <td><select name="Reports[]" multiple>
<?
                                        foreach($sReportsList as $iReport => $sReport)
                                        {
?>
                                            <option value="<?=$iReport?>" <?=(in_array($iReport, $sReports)?'selected':'')?>><?=$sReport?></option>
<?
                                        }
    
?>
                                                    </select></td>
                                              </tr>  
						<tr>
						  <td colspan="2"></td>

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
				      <td class="noRecord">No Defect Area Record Found!</td>
				    </tr>
				  </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&DefectArea={$DefectArea}");
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