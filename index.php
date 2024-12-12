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

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/index.js"></script>
 
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
			  <td width="585">
			    <h1>NewsLetter</h1>

<?
	$PageId     = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$iPageSize  = 6;
	$iPageCount = 0;

	$sSQL = "SELECT COUNT(*) FROM tbl_blog";
	$objDb->query($sSQL);

	$iTotalRecords = $objDb->getField(0, 0);

	if ($iTotalRecords > 0)
	{
		$iPageCount = @floor($iTotalRecords / $iPageSize);

		if (($iTotalRecords % $iPageSize) > 0)
			$iPageCount += 1;

		$iStart = (($PageId * $iPageSize) - $iPageSize);


		$sSQL = "SELECT id, title, picture, (SELECT category FROM tbl_blog_categories WHERE id=tbl_blog.category_id) AS _Category FROM tbl_blog ORDER BY display_order DESC LIMIT 1";
		$objDb->query($sSQL);

		$iId = $objDb->getField(0, "id");

		if ($PageId == 1)
		{
			$sCategory = strtoupper($objDb->getField(0, "_Category"));
			$sTitle    = strtoupper($objDb->getField(0, "title"));
			$sPicture  = $objDb->getField(0, "picture");

			if ($sPicture == "" || !@file_exists(BLOG_IMG_PATH.'medium/'.$sPicture))
				$sPicture = "default.jpg";
?>
				<div style="background:#eeeeee url('<?= BLOG_IMG_PATH.'medium/'.$sPicture ?>') no-repeat; width:585px; height:205px; position: relative; cursor:pointer;" onclick="document.location='<?= SITE_URL ?>blog.php?PostId=<?= $iId ?>';">
						<div class="bottom-left-cat"><?= ($sCategory) ?></div>
						<div class="bottom-left-title"><span><?= mb_strimwidth($sTitle, 0, 83, "..."); ?></span></div>
			</div>
			    <div style="height:5px;"></div>
<?
		}


		$sSQL = "SELECT id, title, picture, (SELECT category FROM tbl_blog_categories WHERE id=tbl_blog.category_id) AS _Category FROM tbl_blog WHERE id!='$iId' ORDER BY display_order DESC LIMIT $iStart, $iPageSize";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
?>

			    <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
		for ($i = 0; $i < $iCount; )
		{
?>

			      <tr>
<?
			$iWidths = array(290, 5, 290);

			for ($j = 0; $j < 3; $j ++)
			{
?>
			        <td width="<?= $iWidths[$j] ?>">
<?
				if ($j != 1 && $i < $iCount)
				{
					$iId       = $objDb->getField($i, "id");
					$sCategory = strtoupper($objDb->getField($i, "_Category"));
					$sTitle    = strtoupper($objDb->getField($i, "title"));
					$sPicture  = $objDb->getField($i, "picture");

					if ($sPicture == "" || !@file_exists(BLOG_IMG_PATH.'thumbs/'.$sPicture))
						$sPicture = "default.jpg";
?>
					  <div style="background:#eeeeee url('<?= BLOG_IMG_PATH.'thumbs/'.$sPicture ?>') no-repeat; width:290px; height:205px; position: relative; cursor:pointer;" onclick="document.location='<?= SITE_URL ?>blog.php?PostId=<?= $iId ?>';">
								<div class="bottom-left-cat"><?= ($sCategory) ?></div>
								<div class="bottom-left-title"><span><?= mb_strimwidth($sTitle, 0, 83, "..."); ?></span></div>

			       </div>
<?
					$i ++;
				}

				else if ($j != 1)
				{
?>
					  <div style="background:#eeeeee; width:290px; height:205px;"></div>
<?
				}
?>
			        </td>
<?
			}
?>
			      </tr>
<?
			if ($i < $iCount)
			{
?>
			      <tr>
			        <td colspan="3" height="5"></td>
			      </tr>
<?
			}
		}
?>
			    </table>

<?
		showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "", false);
	}

	else
	{
?>
                <div class="tblSheet">
                  <div class="noRecord">No Blog Post Found!</div>
                </div>
<?
	}
?>
			  </td>

			  <td width="5"></td>

			  <td>
<?
	@include($sBaseDir."includes/sign-in.php");
?>

			    <div style="height:5px;"></div>

<?
	@include($sBaseDir."includes/custom-feeds.php");
?>
			  </td>
			</tr>
		  </table>
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