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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Title    = IO::strValue("Title");
	$Keywords = IO::strValue("Keywords");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Type     = IO::strValue("Type");
		$Parent   = IO::intValue("Parent");
		$Title    = IO::strValue("Title");
		$Keywords = IO::strValue("Keywords");
	}


	function showCategoriesTree($iParentId, $iSelected = 0, $sTrail = "")
	{
		if (!$objDb)
			$objDb = new Database( );

		if (!$objDb2)
			$objDb2 = new Database( );


		$sSQL = "SELECT id, title FROM tbl_library WHERE type='Category' AND parent_id='$iParentId'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId    = $objDb->getField($i, 'id');
			$sTitle = $objDb->getField($i, 'title');
?>
			            <option value="<?= $iId ?>"<?= (($iId == $iSelected) ? " selected" : "") ?>><?= $sTrail.$sTitle ?></option>
<?
			$sSQL = "SELECT COUNT(*) FROM tbl_library WHERE type='Category' AND parent_id='$iId'";
			$objDb2->query($sSQL);

			if ($objDb2->getField(0, 0) > 0)
				showCategoriesTree($iId, $iSelected, ($sTrail.$sTitle." � "));
		}
	}


	function showParent($iId)
	{
		if ($iId == 0)
			return "";

		if (!$objDb)
			$objDb = new Database( );

		$sParent = "";

		$sSQL = "SELECT title, parent_id FROM tbl_library WHERE id='$iId'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sTitle    = $objDb->getField(0, 'title');
			$iParentId = $objDb->getField(0, 'parent_id');


			if ($iParentId > 0)
				$sParent .= (showParent($iParentId)." � ".$sTitle);

			else
				$sParent = $sTitle;
		}

		return $sParent;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/library.js"></script>
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
			    <h1>Library</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-library-item.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
				<h2>Add Library Item</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="55">Type<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Type" onchange="if (this.value == 'Category') $('File').disable( ); else $('File').enable( );">
						<option value=""></option>
			            <option value="Image"<?= (($Type == "Image") ? " selected" : "") ?>>Image File</option>
			            <option value="Pdf"<?= (($Type == "Pdf") ? " selected" : "") ?>>PDF File</option>
			            <option value="Video"<?= (($Type == "Video") ? " selected" : "") ?>>Video File</option>
			            <option value="Presentation"<?= (($Type == "Presentation") ? " selected" : "") ?>>Presentation</option>
			            <option value="Category"<?= (($Type == "Category") ? " selected" : "") ?>>Category</option>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Parent</td>
					<td align="center">:</td>

					<td>
					  <select name="Parent">
						<option value=""></option>
<?
		showCategoriesTree(0, $Parent);
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Title<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Title" value="<?= $Title ?>" size="33" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Keywords</td>
					<td align="center">:</td>
					<td><input type="text" name="Keywords" value="<?= $Keywords ?>" size="33" maxlength="250" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>File</td>
					<td align="center">:</td>
					<td><input type="file" name="File" id="File" value="" size="27" class="file" /></td>
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
			          <td width="40">Title</td>
			          <td width="160"><input type="text" name="Title" value="<?= $Title ?>" class="textbox" maxlength="50" /></td>
			          <td width="70">Keywords</td>
			          <td width="160"><input type="text" name="Keywords" value="<?= $Keywords ?>" class="textbox" maxlength="50" /></td>
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

	if ($Title != "")
		$sConditions .= " AND title LIKE '%$Title%' ";

	if ($Keywords != "")
		$sConditions .= " AND keywords LIKE '%$Keywords%' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_library", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_library $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="20%">Title</td>
				      <td width="50%">Parent</td>
				      <td width="10%">Type</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$iParent   = $objDb->getField($i, 'parent_id');
		$sTitle    = $objDb->getField($i, 'title');
		$sKeywords = $objDb->getField($i, 'keywords');
		$sType     = $objDb->getField($i, 'type');
		$sFile     = $objDb->getField($i, 'file');

		$sDir  = "";

		switch ($sType)
		{
			case "Image"         :  $sDir = "images/"; break;
			case "Pdf"           :  $sDir = "pdf/"; break;
			case "Video"         :  $sDir = "videos/"; break;
			case "Presentation"  :  $sDir = "ppt/"; break;
		}
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="20%"><?= $sTitle ?></td>
				      <td width="50%"><?= showParent($iParent) ?></td>
				      <td width="10%"><?= $sType ?></td>

				      <td width="12%" class="center">
<?
		if ($sFile != "" && @file_exists($sBaseDir.LIBRARY_FILES_DIR.$sDir.$sFile))
		{
			if ($sType == "Pdf")
			{
?>
				        <a href="<?= LIBRARY_FILES_DIR.$sDir.$sFile ?>" class="lightview" title="<?= $sTitle ?> :: :: width: 800, height: 600"><img src="images/icons/pdf.gif" width="16" height="16" alt="PDF" title="PDF" /></a>
				        &nbsp;
<?
			}

			else if ($sType == "Image")
			{
?>
				        <a href="<?= LIBRARY_FILES_DIR.$sDir.$sFile ?>" class="lightview" title="<?= $sTitle ?>"><img src="images/icons/thumb.gif" width="16" height="16" alt="Image" title="Image" /></a>
				        &nbsp;
<?
			}

			else if ($sType == "Video")
			{
?>
				        <a href="data/view-library-video.php?File=<?= $sFile ?>" class="lightview" title="<?= $sTitle ?> :: :: width: 585, height: 484"><img src="images/icons/video.png" width="16" height="16" alt="Video" title="Video" /></a>
				        &nbsp;
<?
			}
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
				        <a href="data/delete-library-item.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Library Item?.');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" method="post" action="data/update-library-item.php" enctype="multipart/form-data" class="frmInlineEdit" onsubmit="$('BtnSave<?= $iId ?>').disabled=true;">
					  <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
					  <input type="hidden" name="Id" value="<?= $iId ?>" />
					  <input type="hidden" name="OldFile" value="<?= $sFile ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="55">Type<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Type" onchange="if (this.value == 'Category') $('File<?= $iId ?>').disable( ); else $('File<?= $iId ?>').enable( );">
							  <option value="Image"<?= (($sType == "Image") ? " selected" : "") ?>>Image File</option>
							  <option value="Pdf"<?= (($sType == "Pdf") ? " selected" : "") ?>>PDF File</option>
							  <option value="Video"<?= (($sType == "Video") ? " selected" : "") ?>>Video File</option>
							  <option value="Presentation"<?= (($sType == "Presentation") ? " selected" : "") ?>>Presentation</option>
							  <option value="Category"<?= (($sType == "Category") ? " selected" : "") ?>>Category</option>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Parent</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Parent">
							  <option value=""></option>
<?
		showCategoriesTree(0, $iParent);
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td width="70">Title<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Title" value="<?= $sTitle ?>" size="33" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Keywords</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Keywords" value="<?= $sKeywords ?>" size="33" maxlength="250" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>File</td>
						  <td align="center">:</td>
						  <td><input type="file" name="File" id="File<?= $iId ?>" value="" size="27" class="file" <?= (($sType == "Category") ? "disabled" : "") ?> /></td>
					    </tr>

					    <tr>
						  <td></td>
						  <td></td>

						  <td>
						    <input type="submit" id="BtnSave<?= $iId ?>" value="SAVE" class="btnSmall" onclick="return validateEditForm(<?= $iId ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
						  </td>
					    </tr>
					  </table>
					  </form>

				    </div>
				  </div>

<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Library Item Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Title={$Title}&Keywords={$Keywords}");
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