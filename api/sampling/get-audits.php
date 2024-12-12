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

	$Id   = IO::intValue("Style");
	$User = IO::intValue("User");


	$sSQL = "SELECT * FROM tbl_styles WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect($_SERVER['HTTP_REFERER']);


	$iBrand    = $objDb->getField(0, 'sub_brand_id');
	$iSeason   = $objDb->getField(0, 'sub_season_id');
	$sStyle    = $objDb->getField(0, 'style');
	$sDateTime = $objDb->getField(0, 'created');
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
	  <ul class="audits">
<?
	$sSQL = "SELECT id, sample_type_id, status, created FROM tbl_merchandisings WHERE style_id='$Id' AND status!='W' ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount    = $objDb->getCount( );
	$sPictures = array( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iMerchandisingId = $objDb->getField($i, 'id');
		$iSampleType      = $objDb->getField($i, "sample_type_id");
		$sStatus          = $objDb->getField($i, 'status');
		$sDateTime        = $objDb->getField($i, 'created');


		@list($sYear, $sMonth, $sDay) = @explode("-", substr($sDateTime, 0, 10));

		$sCode = ("M".str_pad($iMerchandisingId, 6, '0', STR_PAD_LEFT));

		$sPictures = @glob($sBaseDir.SAMPLING_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".$sCode."_*.*");
		$sTemp     = array( );

		foreach ($sPictures as $sPicture)
			$sTemp[] = str_replace("../../", SITE_URL, strtolower($sPicture));

		$sPictures = $sTemp;
?>
	    <li style="clear:both;">
	      <a href="<?= SITE_URL.substr($_SERVER['REQUEST_URI'], 1) ?>" class="<?= (($sStatus == "A") ? "approved" : "rejected") ?>">
	        <span><?= formatDate($sDateTime) ?></span>
	        <?= getDbValue("type", "tbl_sampling_types", "id='$iSampleType'") ?>
	      </a>

	      <div class="pictures" style="display:none;">
	        <ul>
<?
		for ($j = 0; $j < count($sPictures); $j ++)
		{
?>
			  <li><img src="<?= $sPictures[$j] ?>" width="180" height="220" alt="" title="" /></li>
<?
		}
?>
			</ul>

			<div style="clear:both;"></div>
	      </div>
	    </li>
<?
	}
?>
	  </ul>

</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>