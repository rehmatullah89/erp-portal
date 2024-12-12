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

	$PageId         = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Item           = IO::strValue("Item");
	$PostId         = IO::strValue("PostId");

	if ($PostId != "")
	{
            $_REQUEST = @unserialize($_SESSION[$PostId]);
            $Item     = IO::strValue("Item");
	}
        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/qa-checklist.js"></script>
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
			    <h1>QA Checklist</h1>
<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-qa-checklist.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add QA Checklist Item</h2>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="100">Item<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Item" value="<?= $Item ?>" maxlength="50" class="textbox"   style="width: 200px;" required="" /></td>
				  </tr>
                                    
                                  <tr>
					<td width="100">Field Type<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
                                        <td>
                                            <select name="FieldType" required=""   style="width: 200px;">
                                                <option value=""></option>                   
                                                <option value="CB">Check Box</option>
                                                <option value="YN">Radio (Yes/No/NA)</option>                                                
                                                <option value="TF">Text Field</option>
                                                <option value="NF">Number Field</option>
                                                <option value="CC">Checkbox with Comments</option>
                                            </select>
                                        </td>
				  </tr>
                                    
                                  <tr>
					<td width="100">Mandatory<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
                                        <td>
                                            <select name="Mandatory" required=""   style="width: 200px;">
                                                <option value=""></option>
                                                <option value="Y">Yes</option>
                                                <option value="N">No</option>                                                
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
			          <td width="110">Item</td>
			          <td width="150"><input type="text" name="Section" value="<?= $Item ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass = array("evenRow", "oddRow");
	$sColor = array(EVEN_ROW_COLOR, ODD_ROW_COLOR);

	if ($AuditType != "")
		$sConditions .= " WHERE `type` LIKE '%$AuditType%'";

	$sSQL = "SELECT * FROM tbl_qa_checklist $sConditions ORDER BY position";
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
				      <td width="50%">Item</td>
                                      <td width="20%">Field Type</td>
                                      <td width="12%">Mandatory</td>
				      <td width="13%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId        = $objDb->getField($i, 'id');
		$sItem      = $objDb->getField($i, 'item');
                $sFieldType = $objDb->getField($i, 'field_type');
                $sMandatory = $objDb->getField($i, 'mandatory');
		$iPosition  = $objDb->getField($i, 'position');

		$iNextId     = $objDb->getField(($i + 1), 'id');
		$iPreviousId = $objDb->getField(($i - 1), 'id');

		$iNextPosition     = $objDb->getField(($i + 1), 'position');
		$iPreviousPosition = $objDb->getField(($i - 1), 'position');
                
                if($sFieldType == 'YN')
                    $FieldType = 'Radio (Yes/No/NA)';
                else if($sFieldType == 'TF')
                    $FieldType = 'Text Field';
                else if($sFieldType == 'NF')
                    $FieldType = 'Number Field';
                else if($sFieldType == 'CB')
                    $FieldType = 'Check Box';
                else if($sFieldType == 'CC')
                    $FieldType = 'Checkbox with Comments';                
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="50%"><span id="Item<?= $iId ?>"><?= $sItem ?></span></td>
                                      <td width="20%"><span id="FieldType<?= $iId ?>"><?= $FieldType ?></span></td>
                                      <td width="12%"><span id="Mandatory<?= $iId ?>"><?= ($sMandatory == 'Y')?'Yes':($sMandatory == 'N'?'No':'') ?></span></td>
				      <td width="13%" class="center">
<?
		if ($i > 0 && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="quonda/update-qa-checklist-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iPreviousId ?>&NewOrder=<?= $iPreviousPosition ?>"><img src="images/icons/up.gif" width="16" height="16" alt="Up" title="Up" border="0" align="absmiddle"></a>
						&nbsp;
<?
		}

		if ($i < ($iCount - 1) && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="quonda/update-qa-checklist-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iNextId ?>&NewOrder=<?= $iNextPosition ?>"><img src="images/icons/down.gif" width="16" height="16" alt="Down" title="Down" border="0" align="absmiddle"></a>
						&nbsp;
<?
		}

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
				        <a href="quonda/delete-qa-checklist.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this QA Checklist Item?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
						  <td width="80">Item<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Item" value="<?= $sItem ?>" maxlength="50"   style="width: 200px;" class="textbox" /></td>
						</tr>
                                              
                                               <tr>
                                                    <td width="100">Field Type<span class="mandatory">*</span></td>
                                                    <td width="20" align="center">:</td>
                                                    <td>
                                                        <select name="FieldType" required=""  style="width: 200px;">
                                                            <option value=""></option>
                                                            <option value="CB" <?=($sFieldType == 'CB'?'selected':'')?>>Check Box</option>
                                                            <option value="YN" <?=($sFieldType == 'YN'?'selected':'')?>>Radio (Yes/No/NA)</option>
                                                            <option value="TF" <?=($sFieldType == 'TF'?'selected':'')?>>Text Field</option>
                                                            <option value="NF" <?=($sFieldType == 'NF'?'selected':'')?>>Number Field</option>
                                                            <option value="CC" <?=($sFieldType == 'CC'?'selected':'')?>>Checkbox with Comments</option>
                                                        </select>
                                                    </td>
                                              </tr>
                                              
                                              <tr>
                                                    <td width="100">Mandatory<span class="mandatory">*</span></td>
                                                    <td width="20" align="center">:</td>
                                                    <td>
                                                        <select name="Mandatory" required="" style="width: 200px;">
                                                            <option value=""></option>
                                                            <option value="Y" <?=($sMandatory == 'Y'?'selected':'')?>>Yes</option>
                                                            <option value="N" <?=($sMandatory == 'N'?'selected':'')?>>No</option>
                                                        </select>
                                                    </td>
                                              </tr>  

						<tr>
						  <td colspan="2"></td>

						  <td>
						    <input type="submit" value="SAVE" class="btnSmall" onclick="return validateEditForm(<?= $iId ?>);" />
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
				      <td class="noRecord">No QA Checklist Item Record Found!</td>
				    </tr>
                                </table>
<?
	}
?>
			    </div>

<?
	showPaging(1, 1, $iCount, 0, $iCount);
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