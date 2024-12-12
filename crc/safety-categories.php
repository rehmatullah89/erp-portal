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

	$PageId = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Title  = IO::strValue("Title");
	$PostId = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Title = IO::strValue("Title");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/safety-categories.js"></script>
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
			    <h1>Safety Categories</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="crc/save-safety-category.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Audit Category</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="35">Title</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Title" value="<?= $Title ?>" size="30" maxlength="50" class="textbox" /></td>
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
			          <td width="38">Title</td>
			          <td width="160"><input type="text" name="Title" value="<?= $Title ?>" class="textbox" maxlength="250" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$sConditions = "";

	if ($Title != "")
		$sConditions = "WHERE title LIKE '%$Title%' ";


	$sSQL = "SELECT * FROM tbl_safety_categories $sConditions ORDER BY position";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="4%">#</td>
				      <td width="82%">Title</td>
				      <td width="14%" class="center">Options</td>
				    </tr>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$sTitle    = $objDb->getField($i, 'title');
		$iPosition = $objDb->getField($i, 'position');

		$iNextId           = $objDb->getField(($i + 1), 'id');
		$iPreviousId       = $objDb->getField(($i - 1), 'id');

		$iNextPosition     = $objDb->getField(($i + 1), 'position');
		$iPreviousPosition = $objDb->getField(($i - 1), 'position');
?>
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="4%"><?= ($iStart + $i + 1) ?></td>

				      <td width="82%">
				        <span id="Title<?= $i ?>"><?= $sTitle ?></span>

<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
						<script type="text/javascript">
						<!--
						    var objEditor<?= $i ?> = new Ajax.InPlaceEditor('Title<?= $i ?>', 'ajax/crc/update-safety-category.php', { cancelControl:'button', okText:'  Ok  ', cancelText:'Cancel', clickToEditText:'Click to Edit', externalControl:'Edit<?= $i ?>', highlightcolor:'<?= HOVER_ROW_COLOR ?>', highlightendcolor:'<?= $sColor[($i % 2)] ?>', callback:function(form, value) { return 'Id=<?= $iId ?>&Title=' + encodeURIComponent(value) }, onEnterEditMode:function(form, value) { $('Title<?= $i ?>').focus( ); } });
						-->
						</script>
<?
		}
?>
				      </td>

				      <td width="14%" class="center">
<?
		if ($i > 0 && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="crc/update-safety-category-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iPreviousId ?>&NewOrder=<?= $iPreviousPosition ?>"><img src="images/icons/up.gif" width="16" height="16" alt="Up" title="Up" border="0" align="absmiddle"></a>
						&nbsp;
<?
		}

		if ($i < ($iCount - 1) && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="crc/update-safety-category-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iNextId ?>&NewOrder=<?= $iNextPosition ?>"><img src="images/icons/down.gif" width="16" height="16" alt="Down" title="Down" border="0" align="absmiddle"></a>
						&nbsp;
<?
		}

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" id="Edit<?= $i ?>" onclick="objEditor<?= $i ?>.enterEditMode( ); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="crc/delete-safety-category.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Audit Category?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Audit Category Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Title={$Title}");
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