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

	$Video = IO::strValue("Video");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

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
			  <td width="585">
			    <h1>Videos</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="45">Video</td>
			          <td width="160"><input type="text" name="Video" value="<?= $Video ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet" style="padding-bottom:1px;">
<?
	$PageId      = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$iPageSize   = 10;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Video != "")
		$sConditions .= " WHERE title LIKE '%$Video%' ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_videos", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_videos $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sTitle       = $objDb->getField($i, 'title');
		$sDescription = $objDb->getField($i, 'description');
		$sVideo       = $objDb->getField($i, 'video');
?>
			      <h2><?= $sTitle ?></h2>

			      <div style="padding:10px;">
			        <?= nl2br($sDescription) ?><br />
			        <br />
			        <a href="libs/view-video.php?File=<?= $sVideo ?>" class="lightview" title="<?= $sTitle ?> :: :: width: 585, height: 484"><img src="images/icons/video.png" width="16" height="16" alt="Play" title="Play" align="absmiddle" /> Play Video</a>
			        &nbsp;
			        <a href="libs/download-video.php?File=<?= $sVideo ?>"><img src="images/icons/download.gif" width="16" height="16" alt="Download" title="Download" align="absmiddle" /> Download Video</a><br />
			      </div>
<?
	}


	if ($iCount == 0)
	{
?>
                  <div class="noRecord">No Video Available!</div>
<?
	}
?>
			    </div>
<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Video={$Video}");
?>
			  </td>

			  <td width="5"></td>

			  <td>
<?
	@include($sBaseDir."includes/sign-in.php");
?>

			    <div style="height:5px;"></div>

<?
	@include($sBaseDir."includes/contact-info.php");
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