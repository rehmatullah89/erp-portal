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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT *,
	                (SELECT category FROM tbl_style_categories WHERE id=tbl_styles.category_id) AS _Category,
	                (SELECT brand FROM tbl_brands WHERE id=tbl_styles.brand_id) AS _Brand,
	                (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _SubBrand,
	                (SELECT season FROM tbl_seasons WHERE id=tbl_styles.season_id) AS _Season,
	                (SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _SubSeason,
	                (SELECT program FROM tbl_programs WHERE id=tbl_styles.program_id) AS _Program,
	                (SELECT name FROM tbl_users WHERE id=tbl_styles.created_by) AS _CreatedBy,
	                (SELECT name FROM tbl_users WHERE id=tbl_styles.modified_by) AS _ModifiedBy
			 FROM tbl_styles
			 WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sCategory    = $objDb->getField(0, "_Category");
		$sStyle       = $objDb->getField(0, "style");
		$sStyleName   = $objDb->getField(0, "style_name");
		$sReference   = $objDb->getField(0, "reference");
		$sBrand       = $objDb->getField(0, "_Brand");
		$sSubBrand    = $objDb->getField(0, "_SubBrand");
		$sSeason      = $objDb->getField(0, "_Season");
		$sSubSeason   = $objDb->getField(0, "_SubSeason");
		$sDesignNo    = $objDb->getField(0, "design_no");
		$sDesignName  = $objDb->getField(0, "design_name");
		$sBlockNo     = $objDb->getField(0, "block_no");
		$sDivision    = $objDb->getField(0, "division");		
		$iFabricWidth = $objDb->getField(0, 'fabric_width');
		$sSpecsFile   = $objDb->getField(0, 'specs_file');
		$iCarryOver   = $objDb->getField(0, 'carry_over_id');
		$sProgram     = $objDb->getField(0, '_Program');
		$sCreatedAt   = $objDb->getField(0, "created");
		$sCreatedBy   = $objDb->getField(0, "_CreatedBy");
		$sModifiedAt  = $objDb->getField(0, "modified");
		$sModifiedBy  = $objDb->getField(0, "_ModifiedBy");


		$sCarryOver = "No";

		if ($iCarryOver > 0)
			$sCarryOver = getDbValue("CONCAT(style, '(', (SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id), ')')", "tbl_styles", "id='$iCarryOver'");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:544px; height:544px;">
	  <h2>Style Details</h2>

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
		  <td>Sub-Brand</td>
		  <td align="center">:</td>
		  <td><?= $sSubBrand ?></td>
	    </tr>

	    <tr>
		  <td>Season</td>
		  <td align="center">:</td>
		  <td><?= $sSeason ?></td>
	    </tr>

	    <tr>
		  <td>Sub-Season</td>
		  <td align="center">:</td>
		  <td><?= $sSubSeason ?></td>
	    </tr>

	    <tr>
		  <td>Program</td>
		  <td align="center">:</td>
		  <td><?= $sProgram ?></td>
	    </tr>

	    <tr>
		  <td>Design No</td>
		  <td align="center">:</td>
		  <td><?= $sDesignNo ?></td>
	    </tr>

	    <tr>
		  <td>Design Name</td>
		  <td align="center">:</td>
		  <td><?= $sDesignName ?></td>
	    </tr>
		
	    <tr>
		  <td>Block No</td>
		  <td align="center">:</td>
		  <td><?= $sBlockNo ?></td>
	    </tr>

	    <tr>
		  <td>Division</td>
		  <td align="center">:</td>
		  <td><?= $sDivision ?></td>
	    </tr>

	    <tr>
		  <td>Carry Over</td>
		  <td align="center">:</td>
		  <td><?= $sCarryOver ?></td>
	    </tr>

	    <tr>
		  <td>Fabric Width</td>
		  <td align="center">:</td>
		  <td><?= $iFabricWidth ?> Yards</td>
	    </tr>
	  </table>


		<h2 style="margin:4px 0px 1px 0px;">Style Update Log</h2>

		<div class="tblSheet">
		<table width="100%" cellspacing="0" cellpadding="4" border="1" bordercolor="#ffffff">
		  <tr class="headerRow" style="background:#aaaaaa;">
			<td width="4%" class="center"><b>#</b></td>
			<td width="26%"><b>User</b></td>
			<td width="20%" class="center"><b>Date/Time</b></td>
			<td width="50%"><b>Remarks</b></td>
		  </tr>

<?
	$sSQL = "SELECT date_time, reason, specs_file, remarks,
					(SELECT name FROM tbl_users WHERE id=tbl_style_log.user_id) AS _User
			 FROM tbl_style_log
			 WHERE style_id='$Id'
			 ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sUser      = $objDb->getField($i, '_User');
		$sDateTime  = $objDb->getField($i, 'date_time');
		$sRemarks   = $objDb->getField($i, 'remarks');
		$sSpecsFile = $objDb->getField(($i + 1), 'specs_file');

		if ($i == ($iCount - 1))
			$sSpecsFile = $sLatestSpecsFile;
?>

			  <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
			    <td class="center"><?= ($i + 1) ?></td>
			    <td><?= $sUser ?></td>
			    <td class="center"><?= formatDate($sDateTime, "d-M-Y h:i A") ?></td>

			    <td>
<?
		if ($sSpecsFile != "" && @file_exists($sBaseDir.STYLES_SPECS_DIR.$sSpecsFile))
		{
?>
		          <a href="<?= STYLES_SPECS_DIR.$sSpecsFile ?>" target="_blank"><img src="images/icons/pdf.gif" width="16" height="16" hspace="2" alt="Specs File" title="Specs File" /></a>
<?
		}
?>
			      <?= $sRemarks ?>
			    </td>
			  </tr>
<?
	}

	if ($iCount == 0)
	{
?>
			  <tr class="<?= $sClass[1] ?>" valign="top">
				<td class="center">1</td>
				<td><?= $sCreatedBy ?></td>
				<td class="center"><?= formatDate($sCreatedAt, "d-M-Y h:i A") ?></td>

				<td>
<?
		if ($sSpecsFile != "" && @file_exists($sBaseDir.STYLES_SPECS_DIR.$sSpecsFile))
		{
?>
		          <a href="<?= STYLES_SPECS_DIR.$sSpecsFile ?>" target="_blank"><img src="images/icons/pdf.gif" width="16" height="16" hspace="2" alt="Specs File" title="Specs File" /></a>
<?
		}
?>
				  - Style Entry
				</td>
			  </tr>
<?
		if ($sCreatedAt != $sModifiedAt)
		{
?>
			  <tr class="<?= $sClass[0] ?>" valign="top">
			    <td class="center">2</td>
			    <td><?= $sModifiedBy ?></td>
			    <td class="center"><?= formatDate($sModifiedAt, "d-M-Y h:i A") ?></td>
			    <td>- Last Modified</td>
			  </tr>
<?
		}
	}
?>
		    </table>
		    </div>

		  </td>
	    </tr>
	  </table>
	</div>
<!--  Body Section Ends Here  -->


  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>