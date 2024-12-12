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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Post     = IO::strValue("Post");
	$Category = IO::intValue("Category");

	$sCategoriesList = getList("tbl_blog_categories", "id", "category");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
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
			    <h1>Matrix Blog</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="35">Post</td>
			          <td width="130"><input type="text" name="Post" value="<?= $Post ?>" class="textbox" maxlength="50" size="15" /></td>
			          <td width="65">Category</td>

			          <td width="150">
			            <select name="Category">
			              <option value="">All Categories</option>
<?
	foreach ($sCategoriesList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
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
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Post != "")
		$sConditions = " AND title LIKE '%$Post%' ";

	if ($Category > 0)
		$sConditions .= " AND category_id='$Category' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_blog", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_blog $sConditions ORDER BY display_order DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="6%">#</td>
				      <td width="45%">Title</td>
				      <td width="13%">Category</td>
				      <td width="16%">Date / Time</td>
				      <td width="19%" class="center">Options</td>
				    </tr>
<?
		}

		$iId           = $objDb->getField($i, 'id');
		$iDisplayOrder = $objDb->getField($i, 'display_order');
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $objDb->getField($i, 'title') ?></td>
				      <td><?= $sCategoriesList[$objDb->getField($i, 'category_id')] ?></td>
				      <td><?= formatDate($objDb->getField($i, 'date_time'), "d-M-Y H:i A") ?></td>

				      <td class="right">
<?
		if ($sUserRights['Edit'] == "Y")
		{
			if ($i == 0)
				$iNewOrder = (int)getDbValue("display_order", "tbl_blog", "display_order>'$iDisplayOrder'");

			else
				$iNewOrder = $objDb->getField(($i - 1), 'display_order');

			if ($iNewOrder > 0)
			{
?>
						<a href="data/update-blog-order.php?Id=<?= $iId ?>&CurOrder=<?= $iDisplayOrder ?>&NewOrder=<?= $iNewOrder ?>"><img src="images/icons/up.gif" width="16" height="16" hspace="2" alt="Up" title="Up" border="0" align="absmiddle"></a>
<?
			}


			if ($i == ($iCount - 1))
				$iNewOrder = (int)getDbValue("display_order", "tbl_blog", "display_order<'$iDisplayOrder'", "display_order DESC");

			else
				$iNewOrder = $objDb->getField(($i + 1), 'display_order');

			if ($iNewOrder > 0)
			{
?>
						<a href="data/update-blog-order.php?Id=<?= $iId ?>&CurOrder=<?= $iDisplayOrder ?>&NewOrder=<?= $iNewOrder ?>"><img src="images/icons/down.gif" width="16" height="16" hspace="2" alt="Down" title="Down" border="0" align="absmiddle"></a>
<?
			}
		}

		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="data/edit-blog-picture.php?Id=<?= $iId ?>&Dir=Thumbs" class="lightview" rel="iframe" title="Post: : <?= $objDb->getField($i, 'title') ?> :: :: width: 800, height: 600"><img src="images/icons/thumb.gif" width="16" height="16" hspace="2" alt="Select Thumb Pic" title="Select Thumb Pic" /></a>
				        <a href="data/edit-blog-picture.php?Id=<?= $iId ?>&Dir=Medium" class="lightview" rel="iframe" title="Post: : <?= $objDb->getField($i, 'title') ?> :: :: width: 800, height: 600"><img src="images/icons/medium.gif" width="16" height="16" hspace="2" alt="Select Enlarge Pic" title="Select Enlarge Pic" /></a>
				        <a href="data/edit-blog-post.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" hspace="2" alt="Edit" title="Edit" /></a>
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-blog-post.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Blog Post?\n\nAll Comments under this Post will also be Deleted?');"><img src="images/icons/delete.gif" width="16" height="16" hspace="2" alt="Delete" title="Delete" /></a>
<?
		}
?>
				        <a href="blog.php?PostId=<?= $iId ?>" target="_blank"><img src="images/icons/view.gif" width="16" height="16" hspace="2" alt="View" title="View" /></a>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Post Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Post={$Post}&Category={$Category}");
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