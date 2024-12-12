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
	$DefectType = IO::strValue("DefectType");
	$PostId     = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$DefectType   = IO::strValue("DefectType");
		$DefectTypeZh = IO::strValue("DefectTypeZh");
		$DefectTypeTr = IO::strValue("DefectTypeTr");
		$DefectTypeDe = IO::strValue("DefectTypeDe");
                $DefectTypeUr = IO::strValue("DefectTypeUr");
                
                $DefectTypeKh = IO::strValue("DefectTypeKh");
                $DefectTypePh = IO::strValue("DefectTypePh");
                $DefectTypeVn = IO::strValue("DefectTypeVn");
                $DefectTypeId = IO::strValue("DefectTypeId");
                
		$Color        = IO::strValue("Color");
	}
        
        $sStagesList = getList("tbl_audit_stages", "code", "stage");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/defect-types.js"></script>
  <script type="text/javascript" src="scripts/jscolor/jscolor.js"></script>
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
			    <h1>defect types</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-defect-type.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Defect Type</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="80">Defect Type<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="DefectType" value="<?= $DefectType ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>
				  
				  <tr>
				    <td>Chinese</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectTypeZh" value="<?= formValue($DefectTypeZh) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Turkish</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectTypeTr" value="<?= formValue($DefectTypeTr) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>
				
				  <tr>
				    <td>German</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectTypeDe" value="<?= formValue($DefectTypeDe) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>
                                    
                                  <tr>
				    <td>Urdu</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectTypeUr" value="<?= formValue($DefectTypeUr) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>
                                    
                                  <tr>
				    <td>Cambodian</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectTypeKh" value="<?= formValue($DefectTypeKh) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>
                                    
                                  <tr>
				    <td>Filipino</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectTypePh" value="<?= formValue($DefectTypePh) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>
                                    
                                  <tr>
				    <td>Vietnamese</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectTypeVn" value="<?= formValue($DefectTypeVn) ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>
                                    
                                  <tr>
				    <td>Indonesian</td>
				    <td align="center">:</td>
				    <td><input type="text" name="DefectTypeId" value="<?= formValue($DefectTypeId) ?>" size="30" maxlength="100" class="textbox" /></td>
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
                                        </select></td>
				  </tr>  

				  <tr>
					<td>Color<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Color" value="<?= $Color ?>" size="8" maxlength="7" class="textbox color {required:true,hash:true,caps:false}" /></td>
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
			          <td width="90">Defect Type</td>
			          <td width="150"><input type="text" name="DefectType" value="<?= $DefectType ?>" class="textbox" maxlength="50" size="20" /></td>
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

	if ($DefectType != "")
		$sConditions .= " WHERE `type` LIKE '%$DefectType%'";

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

		for ($i = 0; $i < $iCount; $i ++)
			$iReports[] = $objDb->getField($i, 0);

		$sReports = @implode(",", $iReports);



		if ($DefectType != "")
			$sConditions .= " AND ";

		else
			$sConditions .= " WHERE ";

		$sConditions .= " id IN (SELECT DISTINCT(type_id) FROM tbl_defect_codes WHERE FIND_IN_SET(report_id, '$sReports'))";
	}

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_defect_types", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_defect_types $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="70%">Defect Type</td>
				      <td width="10%">Color</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId           = $objDb->getField($i, 'id');
		$sDefectType   = $objDb->getField($i, 'type');
		$sDefectTypeZh = $objDb->getField($i, 'type_zh');
		$sDefectTypeTr = $objDb->getField($i, 'type_tr');
		$sDefectTypeDe = $objDb->getField($i, 'type_de');
                $sDefectTypeUr = $objDb->getField($i, 'type_ur');                
                $sDefectTypeKh = $objDb->getField($i, 'type_kh');
                $sDefectTypePh = $objDb->getField($i, 'type_ph');
                $sDefectTypeVn = $objDb->getField($i, 'type_vn');
                $sDefectTypeId = $objDb->getField($i, 'type_id');
                
		$sColor        = $objDb->getField($i, 'color');
                $sStages       = explode(",", $objDb->getField($i, 'stages'));  
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="70%"><span id="DefectType<?= $iId ?>"><?= $sDefectType ?></span></td>
				      <td width="10%"><div id="Color<?= $iId ?>" style="background:<?= $sColor ?>; width:20px; height:20px;"></div></td>

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
				        <a href="quonda/delete-defect-type.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Defect Type?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
						  <td width="80">Defect Type<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="DefectType" value="<?= $sDefectType ?>" size="30" maxlength="50" class="textbox" /></td>
						</tr>
						
					    <tr>
						  <td>Chinese</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DefectTypeZh" value="<?= $sDefectTypeZh ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Turkish</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DefectTypeTr" value="<?= $sDefectTypeTr ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>
						
					    <tr>
						  <td>German</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DefectTypeDe" value="<?= $sDefectTypeDe ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>
                                              
                                            <tr>
						  <td>Urdu</td>
						  <td align="center">:</td>
						  <td><input type="text" name="DefectTypeUr" value="<?= $sDefectTypeUr ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>  
                                              
                                             <tr>
                                                <td>Cambodian</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="DefectTypeKh" value="<?= formValue($sDefectTypeKh) ?>" size="30" maxlength="100" class="textbox" /></td>
                                              </tr>

                                              <tr>
                                                <td>Filipino</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="DefectTypePh" value="<?= formValue($sDefectTypePh) ?>" size="30" maxlength="100" class="textbox" /></td>
                                              </tr>

                                              <tr>
                                                <td>Vietnamese</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="DefectTypeVn" value="<?= formValue($sDefectTypeVn) ?>" size="30" maxlength="100" class="textbox" /></td>
                                              </tr>

                                              <tr>
                                                <td>Indonesian</td>
                                                <td align="center">:</td>
                                                <td><input type="text" name="DefectTypeId" value="<?= formValue($sDefectTypeId) ?>" size="30" maxlength="100" class="textbox" /></td>
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
						  <td>Color<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Color" value="<?= $sColor ?>" size="8" maxlength="7" class="textbox color {required:true,hash:true,caps:false}" /></td>
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
				      <td class="noRecord">No Defect Type Record Found!</td>
				    </tr>
				  </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&DefectType={$DefectType}");
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