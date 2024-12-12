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

	$File = IO::strValue("File");
	$Type = IO::strValue("Type");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/tree.js"></script>

  <link type="text/css" rel="stylesheet" href="css/tree.css" />
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
			    <h1>Library</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="30">File</td>
			          <td width="160"><input type="text" name="File" value="<?= $File ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td width="40">Type</td>

					  <td>
					    <select name="Type">
						  <option value="">Any Type</option>
			              <option value="Image"<?= (($Type == "Image") ? " selected" : "") ?>>Image File</option>
			              <option value="Pdf"<?= (($Type == "Pdf") ? " selected" : "") ?>>PDF File</option>
			              <option value="Video"<?= (($Type == "Video") ? " selected" : "") ?>>Video File</option>
			              <option value="Presentation"<?= (($Type == "Presentation") ? " selected" : "") ?>>Presentation File</option>
					    </select>
					  </td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet" style="padding-bottom:1px;">
			      <h2 style="margin-bottom:1px;">MATRIX Library</h2>

			      <ul class="tree fully-open">
<?
	function showTree($iParentId, $sSearchFile = "", $sSearchType = "")
	{
		if (!$objDb)
			$objDb = new Database( );

		if (!$objDb2)
			$objDb2 = new Database( );


		$sSQL = "SELECT id, title, file, type, keywords FROM tbl_library WHERE parent_id='$iParentId' ORDER BY type, id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iId       = $objDb->getField($i, 'id');
			$sTitle    = $objDb->getField($i, 'title');
			$sType     = $objDb->getField($i, 'type');
			$sFile     = $objDb->getField($i, 'file');
			$sKeywords = $objDb->getField($i, 'keywords');

			if ($_SESSION['UserId'] == '' || (@strpos(strtolower($_SESSION['Email']), "@apparelco.com") === FALSE && @strpos(strtolower($_SESSION['Email']), "@3-tree.com") === FALSE))
				continue;
?>
			        <li>
<?
			if ($sType == "Category")
			{
?>
			          <span><?= $sTitle ?></span>
<?
				$sSQL = "SELECT COUNT(*) FROM tbl_library WHERE parent_id='$iId' ORDER BY type, id";
				$objDb2->query($sSQL);

				if ($objDb2->getField(0, 0) > 0)
				{
?>
			          <ul>
<?
					showTree($iId, $sSearchFile, $sSearchType);
?>
			          </ul>
<?
				}
			}

			else
			{
				$sCss = "";

				if ($sSearchFile != "")
				{
					$sSQL = "SELECT * FROM tbl_library WHERE id='$iId' AND (title LIKE '%$sSearchFile%' OR keywords LIKE '%$sSearchFile%')";
					$objDb2->query($sSQL);

					if ($objDb2->getCount( ) == 1)
					{
						if ($sType == $sSearchType || $sSearchType == "")
							$sCss = ' style="background-color:#ffff00; padding:5px 6px 3px 22px; background-position:2px 2px;" ';
					}
				}


				if ($sType == "Pdf")
				{
?>
			          <a href="<?= LIBRARY_FILES_DIR.'pdf/'.$sFile ?>" class="lightview pdf" title="<?= $sTitle ?> :: :: width: 800, height: 600"<?= $sCss ?>><?= $sTitle ?></a>
<?
				}

				else if ($sType == "Video")
				{
?>
			          <a href="libs/view-library-video.php?File=<?= $sFile ?>" class="lightview video" title="<?= $sTitle ?> :: :: width: 585, height: 484"<?= $sCss ?>><?= $sTitle ?></a>
<?
				}

				else if ($sType == "Image")
				{
?>
			          <a href="<?= LIBRARY_FILES_DIR.'images/'.$sFile ?>" class="lightview image" title="<?= $sTitle ?>"<?= $sCss ?>><?= $sTitle ?></a>
<?
				}

				else if ($sType == "Presentation")
				{
?>
			          <a href="<?= LIBRARY_FILES_DIR.'ppt/'.$sFile ?>" class="ppt" title="<?= $sTitle ?>"<?= $sCss ?>><?= $sTitle ?></a>
<?
				}
			}
?>
			        </li>
<?
		}
	}

	showTree(0, $File, $Type);


	if ($_SESSION['UserId'] == '' || (@strpos(strtolower($_SESSION['Email']), "@apparelco.com") === FALSE && @strpos(strtolower($_SESSION['Email']), "@3-tree.com") === FALSE))
	{
?>
			        <li>You don't have rigths to access this section.</li>
<?
	}
?>
			      </ul>
			    </div>
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