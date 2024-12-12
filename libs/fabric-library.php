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
	$objDb2      = new Database( );
	$objDb       = new Database( );

	$Id = IO::intValue("Id");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/swfobject.js"></script>
  <script type="text/javascript" src="scripts/slideshow.js.php?Id=0<?= $Id ?>"></script>
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
			    <h1>Fabric Library</h1>

<?
	if ($Id == 0)
	{
?>
			    <div class="tblSheet">
			      <img src="images/headers/libs/fabric-library.jpg" width="581" height="205" alt="" title="" /><br />

			      <div style="padding:10px 10px 25px 10px;">
			        Please select any of the fabric category from the list below to see the fabric pictures of that category.<br />
			        <br />

			        <table border="0" cellpadding="3" cellspacing="0" width="100%">
<?
		$sSQL = "SELECT id, category, picture FROM tbl_fabric_categories WHERE parent_id='0' ORDER BY category";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount;)
		{
?>
			          <tr>
<?
			for ($j = 0; $j < 3; $j ++)
			{
				if ($i < $iCount)
				{
					$iId       = $objDb->getField($i, 0);
					$sCategory = $objDb->getField($i, 1);
					$sPicture  = $objDb->getField($i, 2);

					if ($sPicture == "" || !@file_exists($sBaseDir.FABRIC_CATEGORIES_IMG_PATH.$sPicture))
						$sPicture = "default.jpg";
?>
			            <td width="33%">
						  <div class="fabricCategory" onclick="document.location='<?= SITE_URL ?>libs/fabric-library.php?Id=<?= $iId ?>';">
							<div><a href="libs/fabric-library.php?Id=<?= $iId ?>"><img src="<?= (FABRIC_CATEGORIES_IMG_PATH.$sPicture) ?>" width="160" height="120" alt="<?= $sCategory ?>" title="<?= $sCategory ?>" /></a></div>
							<b><?= $sCategory ?></b>
						  </div>
			            </td>
<?
					 $i ++;
				}

				else
				{
?>
			            <td width="33%"></td>
<?
				}
			}
?>
			          </tr>
<?
		}
?>
			        </table>
			      </div>
			    </div>
<?
	}

	else
	{
		$sSQL = "SELECT category FROM tbl_fabric_categories WHERE id='$Id'";
		$objDb->query($sSQL);
?>
			    <div class="tblSheet" style="padding-bottom:1px;">
			      <h2 style="margin-bottom:1px; margin-right:1px;"><?= $objDb->getField(0, 0) ?></h2>
<?
		// Update the XML file for Gallery
		$objHandle = @fopen(($sBaseDir."movies/images0".$Id.".xml"), "w+");

		@fwrite($objHandle, '<?xml version="1.0" encoding="UTF-8"?>'."\n");
		@fwrite($objHandle, '<gallery>'."\n");

		$sSQL = "SELECT * FROM tbl_fabric_categories WHERE (id='$Id' OR parent_id='$Id') AND id IN (SELECT DISTINCT(category_id) FROM tbl_fabric_pictures)";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iCategoryId  = $objDb->getField($i, 0);
			$sCategory    = $objDb->getField($i, 2);
			$sDescription = $objDb->getField($i, 3);
			$sPicture     = $objDb->getField($i, 4);

			if ($sPicture == "" || !@file_exists($sBaseDir.FABRIC_CATEGORIES_IMG_PATH.$sPicture))
				$sPicture = "default.jpg";

			@fwrite($objHandle, ('<album id="Category'.$iCategoryId.'" title="'.$sCategory.'" lgPath="'.$sBaseDir.FABRIC_PICS_IMG_PATH.'/enlarged/" tnPath="'.$sBaseDir.FABRIC_PICS_IMG_PATH.'/thumbs/" description="'.$sDescription.'" tn="'.$sBaseDir.FABRIC_CATEGORIES_IMG_PATH.$sPicture.'">'."\n"));


			$sSQL = "SELECT caption, picture FROM tbl_fabric_pictures WHERE category_id='$iCategoryId' ORDER BY id";
			$objDb2->query($sSQL);

			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
			{
				$sCaption = $objDb2->getField($j, 0);
				$sPicture = $objDb2->getField($j, 1);
				$sLink    = "";

				if ($sPicture == "" || !@file_exists($sBaseDir.FABRIC_PICS_IMG_PATH."/thumbs/".$sPicture) || !@file_exists($sBaseDir.FABRIC_PICS_IMG_PATH."/enlarged/".$sPicture))
					continue;

				if ($_SESSION['UserId'] != "")
					$sLink = ("download.php?File=".$sBaseDir.FABRIC_PICS_IMG_PATH."/enlarged/".$sPicture);

				@fwrite($objHandle, ('<img src="'.$sPicture.'" tn="'.$sPicture.'" caption="'.$sCaption.'" title="'.$sCaption.'" link="'.$sLink.'" target="_self" pause="" vidpreview="" />'."\n"));
			}

			@fwrite($objHandle, '</album>'."\n");
		}

		@fwrite($objHandle, '</gallery>');
		@fclose($objHandle);
?>
			      <div id="SlideShow"></div>
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
	if ($Id == 0)
		@include($sBaseDir."includes/contact-info.php");

	else
	{
?>
			    <h1><img src="images/h1/libs/fabric-categories.jpg" width="263" height="20" vspace="10" alt="" title="" /></h1>

			    <div class="tblSheet" style="min-height:178px;">
			      <table border="0" cellpadding="3" cellspacing="0" width="96%" align="center">
<?
		$sSQL = "SELECT id, category FROM tbl_fabric_categories WHERE parent_id='0' ORDER BY category";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sKey   = $objDb->getField($i, 0);
			$sValue = $objDb->getField($i, 1);
?>
			        <tr>
			          <td width="100%"><b>&raquo;</b> <a href="libs/fabric-library.php?Id=<?= $sKey ?>"><?= $sValue ?></a></td>
			        </tr>
<?
		}
?>
			      </table>
			    </div>
<?
	}
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