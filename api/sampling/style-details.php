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

	$Id       = IO::intValue("Id");
	$User     = IO::intValue("User");
	$Style    = IO::strValue("Style");
	$Brand    = IO::strValue("Brand");
	$Season   = IO::strValue("Season");
	$Category = IO::strValue("Category");


	$sSQL = "SELECT * FROM tbl_styles WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect($_SERVER['HTTP_REFERER']);


	$iBrand      = $objDb->getField(0, 'sub_brand_id');
	$iSeason     = $objDb->getField(0, 'sub_season_id');
	$sStyle      = $objDb->getField(0, 'style');
	$sSpecsFile  = $objDb->getField(0, 'specs_file');
	$sSketchFile = $objDb->getField(0, 'sketch_file');
	$sMPs        = $objDb->getField(0, 'measurement_points');
	$sSizes      = $objDb->getField(0, 'sizes');
	$sDateTime   = $objDb->getField(0, 'created');

	if ($sSketchFile == "" || !@file_exists($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile))
		$sSketchFile = "default.jpg";


	$sMsSizes = array( );
	$iSizes   = @explode(",", $sSizes);

	$sSQL = "SELECT id, size FROM tbl_sampling_sizes WHERE id IN ($sSizes) ORDER BY display_order";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sMsSizes[$objDb->getField($i, 0)] = $objDb->getField($i, 1);



	if ($_POST)
		@include("save-comment.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <script type="text/javascript" src="api/sampling/scripts/style-details.js"></script>
</head>

<body>

<div id="MainDiv">
  <table border="0" cellspacing="0" cellpadding="0" width="102%">
    <tr valign="top">
      <td width="170" bgcolor="#a60800"><h1 class="medium"><?= getDbValue("brand", "tbl_brands", "id='$iBrand'") ?></h1></td>
      <td bgcolor="#ffffff"><h1 class="white medium"><?= getDbValue("season", "tbl_seasons", "id='$iSeason'") ?></h1></td>
    </tr>

    <tr>
      <td bgcolor="#ffb935"><h1 class="orange medium"><?= $sStyle ?></h1></td>
      <td bgcolor="#797979"></td>
    </tr>
  </table>

  <table border="0" cellspacing="0" cellpadding="0" width="102%">
    <tr valign="top">
      <td bgcolor="#ffffff"><h2 class="white black">Ex-Factory Date: <?= formatDate($sDateTime, "M d, Y") ?></h2></td>
      <td width="100" bgcolor="#357517"><h2 class="green" align="center" style="padding-left:0px;">On-Time</h2></td>
    </tr>
  </table>

  <table border="0" cellspacing="0" cellpadding="0" width="102%">
    <tr valign="top">
      <td width="100%" bgcolor="#a60800"><h2>Samples Requested: <?= @implode(", ", $sMsSizes) ?></h2></td>
    </tr>
  </table>

  <div id="StylesDetails">
    <div id="Scroller">
	  <div id="StyleSketch">
		<center><a href="api/sampling/360view.php?Id=<?= $Id ?>&User=<?= $User ?>&Brand=<?= $Brand ?>&Category=<?= $Category ?>&Season=<?= $Season ?>&Style=<?= $Style ?>"><img src="<?= (SITE_URL.STYLES_SKETCH_DIR.$sSketchFile) ?>" height="335" alt="" title="" /></a></center>
	  </div>
    </div>
  </div>


  <div id="Actions">
    <div style="margin-bottom:10px;">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#a60800">
		  <td width="84%"><h2>Actions</h2></td>
		  <td width="8%"><a href="api/sampling/styles.php?User=<?= $User ?>&Brand=<?= $Brand ?>&Category=<?= $Category ?>&Season=<?= $Season ?>&Style=<?= $Style ?>"><img src="api/sampling/images/backward.png" height="24" alt="" title="" /></a></td>
		  <td width="8%"><a href="api/sampling/360view.php?Id=<?= $Id ?>&User=<?= $User ?>&Brand=<?= $Brand ?>&Category=<?= $Category ?>&Season=<?= $Season ?>&Style=<?= $Style ?>"><img src="api/sampling/images/forward.png" height="24" alt="" title="" /></a></td>
	    </tr>
	  </table>
    </div>

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr>
	    <td></td>

	    <td width="94">
<?
	if ($sSpecsFile != "" && @file_exists($sBaseDir.STYLES_SPECS_DIR.$sSpecsFile))
	{
?>
		  <a href="<?= STYLES_SPECS_DIR.$sSpecsFile ?>" target="_blank"><img src="api/sampling/images/pdf.png" width="84" height="59" alt="" title="" /></a>
<?
	}

	else
	{
?>
	   	  <img src="api/sampling/images/pdf.png" width="84" height="59" alt="" title="" />
<?
	}
?>
	    </td>

	    <td width="89"><img src="api/sampling/images/email.png" width="77" height="60" alt="" title="" /></td>
	    <td width="66"><img src="api/sampling/images/measurements.png" width="54" height="34" alt="" title="" id="Specs" /></td>
	    <td width="73"><img src="api/sampling/images/comments.png" width="63" height="60" alt="" title="" id="Show" /></td>
	    <td width="69"><img src="api/sampling/images/add-comments.png" width="59" height="57" alt="" title="" id="Add" /></td>
	  </tr>
    </table>
  </div>

  <div id="ProtoWare">
    <div>
      <a href="<?= SITE_URL.substr($_SERVER['REQUEST_URI'], 1) ?>">www.3-tree.com</a>
      ProtoWare&reg;
    </div>
  </div>


  <!-- Show Comments -->
  <div class="popup show">
    <div id="ShowComments">
      <div id="Scroller">
	    <table border="0" cellpadding="8" cellspacing="0" width="100%">
<?
	$sSQL = "SELECT comments, `from`, `date` FROM tbl_style_comments WHERE style_id='$Id' AND stage='Tech Pack' ORDER BY `date`";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sFrom     = $objDb->getField($i, "from");
		$sDate     = $objDb->getField($i, "date");
		$sNature   = $objDb->getField($i, "nature");
		$sComments = $objDb->getField($i, "comments");
?>
	      <tr valign="top">
	        <td width="25%" bgcolor="#<?= (($i % 2) == 1 ? 'eeeeee' : 'aaaaaa') ?>"><b><?= $sFrom ?></b><br /><small><?= formatDate($sDate) ?></small></td>
	        <td width="75%" bgcolor="#<?= (($i % 2) == 1 ? 'ffffff' : 'eeeeee') ?>"><?= (($sNature != "") ? "<b>{$sNature}</b><br />" : "") ?><?= nl2br($sComments) ?></td>
	      </tr>
<?
	}
?>
	    </table>
      </div>
    </div>
  </div>


  <!-- Add Comments -->
  <div class="popup add">
    <div id="AddComments">
	  <form name="frmComments" id="frmComments" method="post" action="api/sampling/style-details.php">
	    <input type="hidden" name="Id" value="<?= $Id ?>" />
	    <input type="hidden" name="User" value="<?= $User ?>" />
	    <input type="hidden" name="Stage" value="Tech Pack" />

	    <table border="0" cellpadding="8" cellspacing="0" width="100%">
		  <tr>
		    <td width="100%">
			  <select name="From" id="From" style="font-size:13px; padding:3px;<?= (($_POST && $_POST['From'] == '') ? ' border:solid 1px #ff0000;' : '') ?>">
			    <option value=""></option>
			    <option value="Buyer"<?= (($From == "Buyer") ? " selected" : "") ?>>Buyer</option>
			    <option value="Merchandiser"<?= (($From == "Merchandiser") ? " selected" : "") ?>>Merchandiser</option>
			    <option value="Sampling Technician"<?= (($From == "Sampling Technician") ? " selected" : "") ?>>Sampling Technician</option>
			    <option value="Quality Technician"<?= (($From == "Quality Technician") ? " selected" : "") ?>>Quality Technician</option>
			  </select>
		    </td>
		  </tr>

		  <tr>
		    <td>
			  <select name="Nature" id="Nature">
			    <option value=""></option>
			    <option value="Merchant Comments"<?= (($Nature == "Merchant Comments") ? " selected" : "") ?>>Merchant Comments</option>
			    <option value="Spec Comments"<?= (($Nature == "Spec Comments") ? " selected" : "") ?>>Spec Comments</option>
			    <option value="Constructions/Quality/Workmanship"<?= (($Nature == "Constructions/Quality/Workmanship") ? " selected" : "") ?>>Constructions/Quality/Workmanship</option>
			    <option value="Fitting Comments"<?= (($Nature == "Fitting Comments") ? " selected" : "") ?>>Fitting Comments</option>
			    <option value="Note/Suggestions"<?= (($Nature == "Note/Suggestions") ? " selected" : "") ?>>Note/Suggestions</option>
			  </select>
		    </td>
		  </tr>

		  <tr valign="top">
		    <td><textarea name="Comments" id="Comments" style="font-size:13px; padding:3px; width:98%; height:125px; border:solid 1px #aaaaaa;"><?= $Comments ?></textarea></td>
		  </tr>
	    </table>

	    <div style="padding-left:8px;">
	      <input type="submit" id="BtnSave" value="Save Comments" class="button" style="background:#bbbbbb;" />
	      <input type="button" id="BtnCancel" value="Cancel" class="button" style="background:#bbbbbb;" />
	    </div>
	  </form>
    </div>
  </div>


  <!-- Specs -->
  <div class="popup specs">
    <div id="MeasurementSpecs">
      <div id="Scroller"<?= ((count($sMsSizes) > 2) ? (' style="width:'.((count($sMsSizes) * 60) + 240).'px;"') : '') ?>>
		  <table border="1" bordercolor="#ffffff" cellpadding="3" cellspacing="0" width="100%">
		    <tr bgcolor="#dddddd">
			  <td width="240">&nbsp;<b>Measurement Point</b></td>
<?
	foreach ($sMsSizes as $iSize => $sSize)
	{
?>
			  <td width="60" align="center"><b><?= $sSize ?></b></td>
<?
	}
?>
			</tr>
<?
	$sSpecs = array( );

	$sSQL = "SELECT point_id, size_id, specs FROM tbl_style_specs WHERE style_id='$Id' AND version='0' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
		$sSpecs[$objDb->getField($i, 'point_id')][$objDb->getField($i, 'size_id')] = $objDb->getField($i, 'specs');


	$sSQL = "SELECT DISTINCT(ss.point_id), CONCAT(mp.point_id, ' - ', mp.point) AS _Point
	         FROM tbl_style_specs ss, tbl_measurement_points mp
	         WHERE ss.point_id=mp.id AND ss.style_id='$Id' AND ss.version='0'
	         ORDER BY ss.id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		$iPoint = $objDb->getField($i, 'point_id');
		$sPoint = $objDb->getField($i, '_Point');
?>

			<tr bgcolor="#<?= ((($i % 2) == 0) ? 'eeeeee' : 'f6f6f6') ?>">
			  <td align="left">&nbsp;<?= $sPoint ?></td>
<?
		foreach ($sMsSizes as $iSize => $sSize)
		{
?>
			  <td align="center"><?= $sSpecs[$iPoint][$iSize] ?></td>
<?
		}
?>
			</tr>
<?
	}
?>
		  </table>
      </div>
    </div>
  </div>

</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>