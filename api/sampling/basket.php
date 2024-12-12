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

	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$User   = IO::intValue("User");
	$Action = IO::strValue("Action");
	$Styles = IO::strValue("Styles");
	$SortBy = IO::strValue("SortBy");
	$SortBy = (($SortBy == "") ? "Etd" : $SortBy);


	if ($Styles != "")
	{
		$iStyles = @explode(",", $Styles);
		$bFlag   = true;
		$bAction = false;


		$objDb->execute("BEGIN");

		for ($i = 1; $i < count($iStyles); $i ++)
		{
			if ($Action == "Add")
			{
				if (getDbValue("COUNT(*)", "tbl_basket", "user_id='$User' AND style_id='{$iStyles[$i]}'") == 1)
					continue;


				$iId = getNextId("tbl_basket");

				$sSQL = "INSERT INTO tbl_basket (id, user_id, style_id, date_time) VALUES ('$iId', '$User', '{$iStyles[$i]}', NOW( ))";
			}

			else
				$sSQL = "DELETE FROM tbl_basket WHERE user_id='$User' AND style_id='{$iStyles[$i]}'";

			$bFlag = $objDb->execute($sSQL);

			if ($bFlag == false)
				break;


			$bAction = true;
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");


			if ($Action == "Add")
				$sMessage = "The selected Styles have been Added to the Basket successfully.";

			else
				$sMessage = "The selected Styles have been Removed from the Basket successfully.";
		}

		else
		{
			$objDb->execute("ROLLBACK");

			$sMessage = "An ERROR occured while processing your request. Please try again.";
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <META HTTP-EQUIV="Expires" CONTENT="0" />
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache" />
  <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache" />
  <META HTTP-EQUIV="Cache-Control" CONTENT="no-store" />

  <script type="text/javascript" src="api/sampling/scripts/basket.js"></script>
</head>

<body>

<div id="MainDiv">
  <input type="hidden" id="User" value="<?= $User ?>" />
  <input type="hidden" id="Refresh" value="N" />

<?
	$sStyles = "0";

	$sSQL = "SELECT id FROM tbl_styles WHERE id IN (SELECT style_id FROM tbl_basket WHERE user_id='$User') ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sStyles  .= (",".$objDb->getField($i, 'id'));



	$sSQL = "SELECT id, style, sketch_file, modified FROM tbl_styles WHERE FIND_IN_SET(id, '$sStyles')";

	if ($SortBy == "Etd")
		$sSQL .= "";

	else if ($SortBy == "Updated")
		$sSQL .= " ORDER BY modified DESC ";

	else if ($SortBy == "Delayed")
		$sSQL .= "";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
  <table border="0" cellspacing="0" cellpadding="0" width="102%">
    <tr>
      <td bgcolor="#a60800"><h1>My Basket</h1></td>

      <td width="170" bgcolor="#ffffff">

      </td>
    </tr>
  </table>


  <div id="StyleInfo">
    Total No of Styles: <?= $iCount ?><br />
  </div>


  <table border="0" cellspacing="0" cellpadding="0" width="102%">
    <tr valign="top">
      <td width="80" bgcolor="#ffffff"><h1 class="white medium">Sort By</h1></td>

<?
	$sPageUrl = (SITE_URL.substr($_SERVER['REQUEST_URI'], 1));

	if (@strpos($sPageUrl, "&SortBy=") !== FALSE)
	{
		$sPageUrl = str_replace("&SortBy=Etd", "&SortBy=", $sPageUrl);
		$sPageUrl = str_replace("&SortBy=Updated", "&SortBy=", $sPageUrl);
		$sPageUrl = str_replace("&SortBy=Delayed", "&SortBy=", $sPageUrl);
	}

	else
		$sPageUrl .= "&SortBy=";
?>
      <td bgcolor="#a60800">
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
          <tr>
            <td width="5"></td>
            <td width="16"><input type="radio" class="sortby" name="SortBy" value="<?= $sPageUrl ?>Etd" <?= (($SortBy == "Etd") ? "checked" : "") ?> /></td>
            <td width="46"><h2><a href="<?= $sPageUrl ?>Etd">ETD</a></h2></td>
            <td width="16"><input type="radio" class="sortby" name="SortBy" value="<?= $sPageUrl ?>Updated" <?= (($SortBy == "Updated") ? "checked" : "") ?> /></td>
            <td width="135"><h2><a href="<?= $sPageUrl ?>Updated">Recently Updated</a></h2></td>
            <td width="16"><input type="radio" class="sortby" name="SortBy" value="<?= $sPageUrl ?>Delayed" <?= (($SortBy == "Delayed") ? "checked" : "") ?> /></td>
            <td><h2><a href="<?= $sPageUrl ?>Delayed">Delayed</a></h2></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>


  <div id="StylesListing">
    <div id="Scroller">
      <ul>
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId         = $objDb->getField($i, 'id');
		$sStyle      = $objDb->getField($i, 'style');
		$sSketchFile = $objDb->getField($i, 'sketch_file');
		$sDateTime   = $objDb->getField($i, 'modified');

		if ($sSketchFile == "" || !@file_exists($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile))
			$sSketchFile = "default.jpg";
?>
	    <li>
	      <label for="Style<?= $i ?>" rel="<?= $sStyle ?>"><input type="checkbox" class="style" rel="<?= $User ?>" name="Style<?= $i ?>" id="Style<?= $i ?>" value="<?= $iId ?>" /> <span>select</span></label>
	      <a href="api/sampling/style-details.php?Id=<?= $iId ?>&User=<?= $User ?>&Brand=<?= $Brand ?>&Category=<?= $Category ?>&Season=<?= $Season ?>&Style=<?= $Style ?>"><img src="<?= (SITE_URL.STYLES_SKETCH_DIR.$sSketchFile) ?>" alt="" title="" /></a><br />
	      <b><?= $sStyle ?></b>
	      <span>Updated <?= showRelativeTime($sDateTime, "F d, Y") ?></span>
	    </li>
<?
	}

	if ($iCount == 0)
	{
?>
	    <li style="width:100%; font-size:13px;">No Style Added to Basket yet!</li>
<?
	}
?>
      </ul>
    </div>
  </div>


  <div id="ProtoWare">
    <div>
      <a href="<?= SITE_URL.substr($_SERVER['REQUEST_URI'], 1) ?>">www.3-tree.com</a>
      ProtoWare&reg;
    </div>

    <div id="RemoveFromBasket" class="hidden">
      <a href="api/sampling/basket.php?User=<?= $User ?>"><img src="api/sampling/images/remove-from-basket.png" width="135" height="25" vspace="4" /></a>
    </div>

    <div id="SendRequest" class="hidden"><img src="api/sampling/images/send.png" width="57" height="32" alt="" title="" /></div>
  </div>
</div>


<script type="text/javascript">
<!--
	$(document).ready(function( )
	{
<?
	if ($sMessage != "" && $bAction == true)
	{
?>
		Android.showMessage("<?= $sMessage ?>");
<?
	}
?>
		if ($("#Refresh").val( ) == "N")
			$("#Refresh").val("Y");

		else
		{
			$("#Refresh").val("N");

			document.location.reload( );
		}
	});
-->
</script>


</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>