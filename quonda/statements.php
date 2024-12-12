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

	$PageId        = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Statement     = IO::strValue("Statement");
        $Sections      = IO::getArray("Sections");
	$PostId        = IO::strValue("PostId");

        $sSectionsList = getList("tbl_statement_sections", "id", "section");
        
	if ($PostId != "")
	{
            $_REQUEST       = @unserialize($_SESSION[$PostId]);
            $Statement      = IO::strValue("Statement");
            $Sections       = IO::getArray("Sections");
	}
        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/statements.js"></script>
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
			    <h1>statements</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="quonda/save-statement.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add New Statement</h2>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="100">Section<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
                                        <td>
                                            <select name="Sections[]" multiple style="height: 150px; width: 210px;">
<?
                                            foreach($sSectionsList as $iSection => $sSection)
                                            {
?>
                                                <option value="<?=$iSection?>" <?= @in_array($iSection, $Sections)?'selected':''?>><?=$sSection?></option>
<?
                                            }
?>                                                
                                            </select>
                                        </td>
				  </tr>
                                  <tr>
					<td width="100">Statement<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
                                        <td><textarea name="Statement" cols="60" rows="5"><?= $Statement ?></textarea></td>
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
			          <td width="80">Statement</td>
			          <td width="180"><input type="text" name="Statement" value="<?= $Statement ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass = array("evenRow", "oddRow");
	$sColor = array(EVEN_ROW_COLOR, ODD_ROW_COLOR);

        $sConditions = " WHERE id != '0' ";
        
	if ($Statement != "")
		$sConditions .= " AND `statement` LIKE '%$Statement%'";
        
       
	$sSQL = "SELECT * FROM tbl_statements $sConditions ORDER BY position";
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
                                  <td width="45%">Statement</td>
                                  <td width="30%">Sections</td>
                                  <td width="20%" class="center">Options</td>
                                </tr>
                              </table>
<?
		}

		$iId         = $objDb->getField($i, 'id');
		$sStatement  = $objDb->getField($i, 'statement');
                $sSections   = $objDb->getField($i, 'sections');
		$iPosition   = $objDb->getField($i, 'position');

		$iNextId     = $objDb->getField(($i + 1), 'id');
		$iPreviousId = $objDb->getField(($i - 1), 'id');

		$iNextPosition     = $objDb->getField(($i + 1), 'position');
		$iPreviousPosition = $objDb->getField(($i - 1), 'position');
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="45%"><span id="Statement<?= $iId ?>"><?= $sStatement ?></span></td>
                                      <td width="30%"><span id="Sections<?= $iId ?>"><?= getDbValue("GROUP_CONCAT(section SEPARATOR ', ')", "tbl_statement_sections", "id IN ($sSections)") ?></span></td>
				      <td width="20%" class="center">
<?
		if ($i > 0 && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="quonda/update-statement-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iPreviousId ?>&NewOrder=<?= $iPreviousPosition ?>"><img src="images/icons/up.gif" width="16" height="16" alt="Up" title="Up" border="0" align="absmiddle"></a>
						&nbsp;
<?
		}

		if ($i < ($iCount - 1) && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="quonda/update-statement-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iNextId ?>&NewOrder=<?= $iNextPosition ?>"><img src="images/icons/down.gif" width="16" height="16" alt="Down" title="Down" border="0" align="absmiddle"></a>
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
				        <a href="quonda/delete-statement.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Statement?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
					<td width="100">Section<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
                                        <td>
                                            <select name="Sections[]" multiple style="height: 150px; width: 210px;">
<?
                                            foreach($sSectionsList as $iSection => $sSection)
                                            {
?>
                                                <option value="<?=$iSection?>" <?= @in_array($iSection, explode(",", $sSections))?'selected':''?>><?=$sSection?></option>
<?
                                            }
?>                                                
                                            </select>
                                        </td>
				  </tr>
                                  <tr>
					<td width="100">Statement<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
                                        <td><textarea name="Statement"  cols="60" rows="5"><?= $sStatement ?></textarea></td>
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
				      <td class="noRecord">No Predefined Statements Record Found!</td>
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