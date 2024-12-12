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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body style="background:#ffffff;">

<div id="PopupDiv" style="width:100%; min-width:680px; background:#ffffff;">
<?
	$StyleId = IO::intValue("StyleId");
	$Style   = IO::strValue('Style');

	if ($StyleId > 0)
		$iStyleId = $StyleId;

	else
	{
		$sSQL = "SELECT id, style,
						(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand,
						(SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season
				 FROM tbl_styles
				 WHERE style LIKE '$Style'";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 1)
		{
?>
	  			  <h2>Select Style</h2>

			      <div style="padding:15px;">
			        <ol>
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iId     = $objDb->getField($i, 'id');
				$sStyle  = $objDb->getField($i, 'style');
				$sBrand  = $objDb->getField($i, '_Brand');
				$sSeason = $objDb->getField($i, "_Season");
?>
			          <li><a href="api/quonda/style.php?StyleId=<?= $iId ?>"><?= $sStyle ?></a> - <b><?= $sBrand ?> / <?= $sSeason ?></b></li>
<?
			}
?>
			        </ol>
			      </div>
<?
		}

		else
			$iStyleId = $objDb->getField(0, 0);
	}



	if ($iStyleId > 0)
	{
		$sSQL = "SELECT style, style_name, reference, fabric_width,
						(SELECT category FROM tbl_style_categories WHERE id=tbl_styles.category_id) AS _Category,
						(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand,
						(SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season
				 FROM tbl_styles
				 WHERE id='$iStyleId'";
		$objDb->query($sSQL);

		$sCategory    = $objDb->getField(0, "_Category");
		$sStyle       = $objDb->getField(0, "style");
		$sStyleName   = $objDb->getField(0, "style_name");
		$sReference   = $objDb->getField(0, "reference");
		$sBrand       = $objDb->getField(0, "_Brand");
		$sSeason      = $objDb->getField(0, "_Season");
		$iFabricWidth = $objDb->getField(0, 'fabric_width');
?>
	  <h2>Style Info</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	    <tr>
		  <td width="80">Category</td>
		  <td width="20" align="center">:</td>
		  <td><?= $sCategory ?></td>
	    </tr>

	    <tr>
		  <td>Style</td>
		  <td align="center">:</td>
		  <td><?= $sStyle ?></td>
	    </tr>

	    <tr>
		  <td>Style Name</td>
		  <td align="center">:</td>
		  <td><?= $sStyleName ?></td>
	    </tr>

	    <tr>
		  <td>Reference</td>
		  <td align="center">:</td>
		  <td><?= $sReference ?></td>
	    </tr>

	    <tr>
		  <td>Brand</td>
		  <td align="center">:</td>
		  <td><?= $sBrand ?></td>
	    </tr>

	    <tr>
		  <td>Season</td>
		  <td align="center">:</td>
		  <td><?= $sSeason ?></td>
	    </tr>

	    <tr>
		  <td>Fabric Width</td>
		  <td align="center">:</td>
		  <td><?= $iFabricWidth ?> Yards</td>
	    </tr>
	  </table>
<?
	}
?>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>