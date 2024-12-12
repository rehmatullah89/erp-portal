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


	$iBrand    = $objDb->getField(0, 'sub_brand_id');
	$iSeason   = $objDb->getField(0, 'sub_season_id');
	$sStyle    = $objDb->getField(0, 'style');
	$sDateTime = $objDb->getField(0, 'created');


	if ($_POST)
		@include("save-comment.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("includes/meta-tags.php");
?>
  <script type="text/javascript" src="api/sampling/scripts/audits.js"></script>
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
      <td bgcolor="#ffffff"><h2 class="white black">Final Evaluation Planned: <?= formatDate($sDateTime, "M d, Y") ?></h2></td>
      <td width="100" bgcolor="#357517"><h2 class="green" align="center" style="padding-left:0px;">On-Time</h2></td>
    </tr>
  </table>

  <table border="0" cellspacing="0" cellpadding="0" width="102%">
    <tr valign="top">
      <td width="100%" bgcolor="#a60800"><h2>Audit Evaluations</h2></td>
    </tr>
  </table>

  <div id="Audits">
    <div id="Scroller">
	  <ul class="audits">
<?
	$sSQL = "SELECT id, sample_type_id, status, created, sample_sizes, sample_quantities, modified FROM tbl_merchandisings WHERE style_id='$Id' AND status!='W' ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount    = $objDb->getCount( );
	$sPictures = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iMerchandisingId  = $objDb->getField($i, 'id');
		$iSampleType       = $objDb->getField($i, "sample_type_id");
		$sStatus           = $objDb->getField($i, 'status');
		$sDateTime         = $objDb->getField($i, 'created');
		$sSampleSizes      = $objDb->getField($i, 'sample_sizes');
		$sSampleQuantities = $objDb->getField($i, 'sample_quantities');
		$iEntryTime        = @strtotime($objDb->getField($i, 'modified'));
		$iOrderTime        = @strtotime(date("2010-01-20 23:59:59"));
		$sOrderField       = "id";

		if ($iEntryTime > $iOrderTime)
			$sOrderField = "display_order";


		@list($sYear, $sMonth, $sDay) = @explode("-", substr($sDateTime, 0, 10));

		$sCode = ("M".str_pad($iMerchandisingId, 6, '0', STR_PAD_LEFT));

		$sPictures = @glob($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sCode."_*.*");
		$sTemp     = array( );

		foreach ($sPictures as $sPicture)
			$sTemp[] = str_replace("../../", SITE_URL, strtolower($sPicture));

		$sPictures = $sTemp;
?>
	    <li style="clear:both;" id="Audit<?= $i ?>">
	      <a href="<?= SITE_URL.substr($_SERVER['REQUEST_URI'], 1) ?>" class="<?= (($sStatus == "A") ? "approved" : "rejected") ?>">
	        <span><?= formatDate($sDateTime) ?></span>
	        <?= getDbValue("type", "tbl_sampling_types", "id='$iSampleType'") ?>
	      </a>

	      <div class="pictures" style="display:none; clear:both;">
	        <ul>
<?
		for ($j = 0; $j < count($sPictures); $j ++)
		{
?>
			  <li><img src="<?= $sPictures[$j] ?>" width="180" height="220" alt="" title="" /></li>
<?
		}

		if (count($sPictures) == 0)
		{
?>
			  <li>No Defect Image uploaded<br /><br /></li>
<?
		}
?>
			</ul>

			<div style="clear:both;"></div>
	      </div>

	      <div class="specs" style="display:none; clear:both;">
<?
		$iSampleQuantities = @explode(",", $sSampleQuantities);
		$iSampleSizes      = array( );


		$sSQL = "SELECT size FROM tbl_sampling_sizes WHERE id IN ($sSampleSizes) ORDER BY $sOrderField";

		if ($objDb2->query($sSQL) == true)
		{
			$iCount2 = $objDb2->getCount( );

			for ($j = 0; $j < $iCount2; $j ++)
				$iSampleSizes[] = $objDb2->getField($j, 0);
		}

		$iSizesCount      = count($iSampleSizes);
		$iQuantitiesCount = count($iSampleQuantities);
?>
			<table border="1" bordercolor="#ffffff" cellpadding="3" cellspacing="0" width="100%">
			  <tr bgcolor="#dddddd">
				<td width="230" rowspan="3">&nbsp;<b>Measurement Point</b></td>
				<td width="70" rowspan="3" align="center"><b>Tolerance</b></td>
				<td width="<?= ((@array_sum($iSampleQuantities) + $iQuantitiesCount) * 50) ?>" align="center" colspan="<?= (@array_sum($iSampleQuantities) + $iQuantitiesCount) ?>">&nbsp;<b>Sizes</b></td>
			  </tr>

			  <tr bgcolor="#dddddd">
<?
		for ($j = 0; $j < $iSizesCount; $j ++)
		{
?>
				<td width="<?= (($iSampleQuantities[$j] + 1 + $iQuantitiesCount) * 50) ?>" align="center" colspan="<?= ($iSampleQuantities[$j] + 1) ?>"><b><?= $iSampleSizes[$j] ?></b></td>
<?
		}
?>
			  </tr>

			  <tr bgcolor="#dddddd">
<?
		for ($j = 0; $j < $iSizesCount; $j ++)
		{
			for ($k = 0; $k <= $iSampleQuantities[$j]; $k ++)
			{
				$sHeading = $k;

				if ($k == 0)
					$sHeading = "Spec";
?>
				<td width="50" align="center"><b><?= $sHeading ?></b></td>
<?
			}
		}
?>
			  </tr>
<?
		$sSQL = "SELECT ms.data, ms.tolerance, CONCAT(mp.point_id, ' - ', mp.point) AS _Point
				 FROM tbl_measurement_specs ms, tbl_measurement_points mp
				 WHERE ms.point_id=mp.id AND ms.merchandising_id='$iMerchandisingId'
				 ORDER BY ms.id";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for($j = 0; $j < $iCount2; $j ++)
		{
			$sPoint     = $objDb2->getField($j, '_Point');
			$sTolerance = $objDb2->getField($j, 'tolerance');
			$sData      = $objDb2->getField($j, 'data');

			$sData = @explode(",", $sData);
?>

			  <tr bgcolor="#<?= ((($j % 2) == 0) ? 'eeeeee' : 'f6f6f6') ?>">
				<td width="230" align="left">&nbsp;<?= $sPoint ?></td>
				<td width="70" align="center"><?= $sTolerance ?></td>
<?
			$iIndex = 0;

			for ($k = 0; $k < $iSizesCount; $k ++)
			{
				for ($l = 0; $l <= $iSampleQuantities[$k]; $l ++)
				{
?>
				<td width="50" align="center"><?= $sData[$iIndex] ?></td>
<?
					$iIndex ++;
				}
			}
?>
			  </tr>
<?
		}
?>
			</table>
	      </div>
	    </li>
<?
	}
?>
	  </ul>

    </div>
  </div>


  <div id="Actions">
    <div style="margin-bottom:10px;">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#a60800">
		  <td width="84%"><h2>Actions</h2></td>
		  <td width="8%"><a href="api/sampling/360view.php?Id=<?= $Id ?>&User=<?= $User ?>&Brand=<?= $Brand ?>&Category=<?= $Category ?>&Season=<?= $Season ?>&Style=<?= $Style ?>"><img src="api/sampling/images/backward.png" height="24" alt="" title="" /></a></td>
		  <td width="8%"><a href="api/sampling/graphs.php?Id=<?= $Id ?>&User=<?= $User ?>&Brand=<?= $Brand ?>&Category=<?= $Category ?>&Season=<?= $Season ?>&Style=<?= $Style ?>"><img src="api/sampling/images/forward.png" height="24" alt="" title="" /></a></td>
	    </tr>
	  </table>
    </div>

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr>
	    <td></td>
	    <td width="66"><div id="Measurements" style="display:none;"><img src="api/sampling/images/measurements.png" width="54" height="34" alt="" title="" id="Specs" /></div></td>
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


  <!-- Picture Popup -->
  <div class="popup picture">
    <div id="Picture">
      <img src="" alt="" title="" />
    </div>
  </div>


  <!-- Specs -->
  <div class="popup specs">
    <div id="MeasurementSpecs">
      <div id="Scroller">

      </div>
    </div>
  </div>


</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>