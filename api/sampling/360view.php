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
	$sSketchFile = $objDb->getField(0, 'sketch_file');
	$sDateTime   = $objDb->getField(0, 'created');

	if ($sSketchFile == "" || !@file_exists($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile))
		$sSketchFile = (SITE_URL.STYLES_SKETCH_DIR."default.jpg");

	else
	{
		if (!@file_exists($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile))
			createImage(($sBaseDir.STYLES_SKETCH_DIR.$sSketchFile), ($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile), 160, 160);

		$sSketchFile = (SITE_URL.STYLES_SKETCH_DIR.'thumbs/'.$sSketchFile);
	}


	if ($_POST)
		@include("save-comment.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>

  <script type="text/javascript" src="api/sampling/scripts/jquery.spritespin.js"></script>
  <script type="text/javascript" src="api/sampling/scripts/360view.js"></script>
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
      <td width="100%" bgcolor="#a60800"><h2>360 View of Actual Sample</h2></td>
    </tr>
  </table>

  <div id="StylesDetails">
    <div id="Scroller">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr>
	      <td align="center">
	        <img src="api/sampling/images/back.png" width="48" height="48" alt="" title="" id="Back" style="display:none;" />
	        <img src="api/sampling/images/zoom.png" width="48" height="48" vspace="10" alt="" title="" id="Zoom" style="display:none;" />
	      </td>

	      <td width="223"><div id="View360"></div></td>

	      <td align="center">
	        <img src="api/sampling/images/next.png" width="48" height="48" alt="" title="" id="Next" style="display:none;" />
	        <img src="api/sampling/images/rotate.png" width="48" height="48" vspace="10" alt="" title="" id="Rotate" style="display:none;" />
	      </td>
	    </tr>
	  </table>
    </div>
  </div>


  <div id="Actions">
    <div style="margin-bottom:10px;">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#a60800">
		  <td width="84%"><h2>Actions</h2></td>
	      <td width="8%"><a href="api/sampling/style-details.php?Id=<?= $Id ?>&User=<?= $User ?>&Brand=<?= $Brand ?>&Category=<?= $Category ?>&Season=<?= $Season ?>&Style=<?= $Style ?>"><img src="api/sampling/images/backward.png" height="24" alt="" title="" /></a></td>
	      <td width="8%"><a href="api/sampling/audits.php?Id=<?= $Id ?>&User=<?= $User ?>&Brand=<?= $Brand ?>&Category=<?= $Category ?>&Season=<?= $Season ?>&Style=<?= $Style ?>"><img src="api/sampling/images/forward.png" height="24" alt="" title="" /></a></td>
	    </tr>
	  </table>
    </div>

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr>
	    <td width="10"></td>
	    <td width="68"><div style="border:solid 1px #666666; padding:1px;"><img src="<?= $sSketchFile ?>" width="64" height="64" alt="" title="" /></div></td>
	    <td></td>
	    <td width="68"><a href="api/sampling/graphs.php?Id=<?= $Id ?>&User=<?= $User ?>&Brand=<?= $Brand ?>&Category=<?= $Category ?>&Season=<?= $Season ?>&Style=<?= $Style ?>"><img src="api/sampling/images/graphs.png" width="56" height="58" alt="" title="" /></a></td>
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
	$sSQL = "SELECT comments, `from`, `date` FROM tbl_style_comments WHERE style_id='$Id' AND stage='Physical Sample Evaluation' ORDER BY `date`";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sFrom     = $objDb->getField($i, "from");
		$sDate     = $objDb->getField($i, "date");
		$sComments = $objDb->getField($i, "comments");
?>
	      <tr valign="top">
	        <td width="25%" bgcolor="#<?= (($i % 2) == 1 ? 'eeeeee' : 'aaaaaa') ?>"><b><?= $sFrom ?></b><br /><small><?= formatDate($sDate) ?></small></td>
	        <td width="75%" bgcolor="#<?= (($i % 2) == 1 ? 'ffffff' : 'eeeeee') ?>"><?= nl2br($sComments) ?></td>
	      </tr>
<?
	}
?>
	    </table>
      </div>
    </div>
  </div>


  <!-- Picture Popup -->
  <div class="popup picture">
    <div id="Picture" style="background:#eeeeee;">
      <img src="" alt="" title="" />
    </div>
  </div>


  <!-- Add Comments -->
  <div class="popup add">
    <div id="AddComments">
	  <form name="frmComments" id="frmComments" method="post" action="api/sampling/style-details.php">
	    <input type="hidden" name="Id" value="<?= $Id ?>" />
	    <input type="hidden" name="User" value="<?= $User ?>" />
	    <input type="hidden" name="Stage" value="Physical Sample Evaluation" />

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

		  <tr valign="top">
		    <td><textarea name="Comments" id="Comments" style="font-size:13px; padding:3px; width:98%; height:150px; border:solid 1px #aaaaaa;"><?= $Comments ?></textarea></td>
		  </tr>
	    </table>

	    <div style="padding-left:8px;">
	      <input type="submit" id="BtnSave" value="Save Comments" class="button" style="background:#bbbbbb;" />
	      <input type="button" id="BtnCancel" value="Cancel" class="button" style="background:#bbbbbb;" />
	    </div>
	  </form>
    </div>
  </div>

</div>

<?
	$sSQL = "SELECT id,
	                (SELECT created FROM tbl_comment_sheets WHERE merchandising_id=tbl_merchandisings.id) AS _Created
	         FROM tbl_merchandisings
	         WHERE style_id='$Id'
	         ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount   = $objDb->getCount( );
	$s360Pics = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iMerchandisingId = $objDb->getField($i, 'id');
		$sDateTime        = $objDb->getField($i, '_Created');


		@list($sYear, $sMonth, $sDay) = @explode("-", substr($sDateTime, 0, 10));

		$sCode = ("M".str_pad($iMerchandisingId, 6, '0', STR_PAD_LEFT));

		$sPictures = @glob($sBaseDir.SAMPLING_360_DIR."{$sYear}/{$sMonth}/{$sDay}/thumbs/{$sCode}_*.*");

		for ($j = 0; $j < count($sPictures); $j ++)
			$s360Pics[$sCode][] = $sPictures[$j];
	}


	if (count($s360Pics) > 0)
	{
?>
<script type="text/javascript">
<!--
$(document).ready(function( )
{
	$("#View360").spritespin(
	{
		width       : 223,
		height      : 335,
		frames      : 24,
		animate     : true,
		touchable   : true,
		preloadHtml : "please wait while loading 360 view...",
		image       : [
<?
		foreach ($s360Pics as $sCodePics)
		{
			for ($i = 0; $i < count($sCodePics); $i ++)
			{
?>
						"<?= str_replace("../../", SITE_URL, $sCodePics[$i]) ?>"<?= (($i < (count($sCodePics) - 1)) ? ',' : '') ?>
<?
			}

			break;
		}
?>
					  ],

		onLoad      : function( ) { $("#Scroller img").css("display", "block"); }
	});
});
-->
</script>
<?
	}


	else
	{
?>
<script type="text/javascript">
<!--
$(document).ready(function( )
{
	$("#View360").html("No 360 View available");
});
-->
</script>
<?
	}
?>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>