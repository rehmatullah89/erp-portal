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

	$Brand      = IO::intValue("Brand");
	$StyleNo    = IO::intValue("StyleNo");
	$SampleType = IO::intValue("SampleType");


	$sBrandsList  = getList("tbl_brands", "id", "brand", "parent_id>'0' AND id IN ({$_SESSION['Brands']})");
	$sTypesList   = array( );
	$sSeasonsList = array( );

	if ($Brand > 0)
	{
		$iParent = getDbValue("parent_id", "tbl_brands", "id='$Brand'");

		$sTypesList   = getList("tbl_sampling_types", "id", "type", "brand_id='$iParent'", "position");
		$sSeasonsList = getList("tbl_seasons", "id", "season", "parent_id>'0' AND brand_id='$iParent'");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>

  <script type="text/javascript" src="scripts/reports/measurements-report.js"></script>
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
			  <td width="100%">
			    <h1>Measurements Report</h1>

			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" class="frmOutline" onsubmit="$('BtnSubmit').disabled=true;">
				<h2>Measurements Report</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
			      <tr>
			        <td width="90">Brand<span class="mandatory">*</span></td>
			        <td width="20" align="center">:</td>

			        <td>
			          <select name="Brand" id="Brand" onchange="getListValues('Brand', 'SampleType', 'SamplingTypes');  getStylesList('Brand', 'StyleNo');">
			            <option value="">All Brands</option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			          </select>
			        </td>
			      </tr>

				  <tr>
					<td>Style No<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="StyleNo" id="StyleNo">
						<option value=""></option>
<?
	$sSQL = "SELECT id, style, sub_season_id FROM tbl_styles WHERE sub_brand_id='$Brand' ORDER BY style";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey    = $objDb->getField($i, 'id');
		$sValue  = $objDb->getField($i, 'style');
		$iSeason = $objDb->getField($i, 'sub_season_id');
?>
	  	        		<option value="<?= $sKey ?>" <?= (($sKey == $StyleNo) ? 'selected' : '') ?>><?= $sValue ?> (<?= $sSeasonsList[$iSeason] ?>)</option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Sample Type<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="SampleType" id="SampleType">
						<option value=""></option>
<?
	foreach ($sTypesList as $sKey => $sValue)
	{
?>
	  	                  <option value="<?= $sKey ?>" <?= (($sKey == $SampleType) ? 'selected' : '') ?>><?= $sValue ?></option>
<?
	}
?>
	                  </select>
					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" value="" id="BtnSubmit" class="btnSubmit" title="Submit" onclick="return validateForm( );" />
				</div>
			    </form>
			  </td>
			</tr>
		  </table>

<?
	if ($_GET)
	{
?>
			    <br />

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
		$sClass      = array("evenRow", "oddRow");
		$sConditions = "";

		$sSQL = "SELECT id FROM tbl_merchandisings WHERE style_id='$StyleNo' AND sample_type_id='$SampleType' ";
		$objDb->query($sSQL);

		$iCount  = $objDb->getCount( );

		$sMerchandisingIds = "";

		for ($i = 0; $i < $iCount; $i ++)
			$sMerchandisingIds .= (",".$objDb->getField($i, 0));

		if ($sMerchandisingIds != "")
			$sMerchandisingIds = substr($sMerchandisingIds, 1);


		$sSQL = "SELECT merchandising_id, created FROM tbl_comment_sheets WHERE merchandising_id IN ($sMerchandisingIds) ORDER BY merchandising_id DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			if (($i % 10) == 0)
			{
?>
				    <tr class="headerRow">
				      <td width="7%">#</td>
				      <td width="13%">Style</td>
				      <td width="16%">Brand</td>
				      <td width="16%">Season</td>
				      <td width="18%">Sample Type</td>
				      <td width="10%">Wash</td>
				      <td width="12%">Date</td>
				      <td width="8%" class="center">Options</td>
				    </tr>
<?
			}

			$iId      = $objDb->getField($i, 'merchandising_id');
			$sCreated = $objDb->getField($i, "created");


			$sSQL = "SELECT style_id,
			                (SELECT type FROM tbl_sampling_types WHERE id=tbl_merchandisings.sample_type_id) AS _SampleType,
			                (SELECT wash FROM tbl_sampling_washes WHERE id=tbl_merchandisings.wash_id) AS _Wash
			         FROM tbl_merchandisings WHERE id='$iId'";
			$objDb2->query($sSQL);

			$iStyleId    = $objDb2->getField(0, "style_id");
			$sSampleType = $objDb2->getField(0, "_SampleType");
			$sWash       = $objDb2->getField(0, "_Wash");


			$sSQL = "SELECT style,
							(SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _SubBrand,
							(SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _SubSeason
					 FROM tbl_styles WHERE id='$iStyleId'";
			$objDb2->query($sSQL);

			$sStyle     = $objDb2->getField(0, 'style');
			$sSubBrand  = $objDb2->getField(0, '_SubBrand');
			$sSubSeason = $objDb2->getField(0, '_SubSeason');
?>


				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $sStyle ?></td>
				      <td><?= $sSubBrand ?></td>
				      <td><?= $sSubSeason ?></td>
				      <td><?= $sSampleType ?></td>
				      <td><?= $sWash ?></td>
				      <td><?= formatDate($sCreated) ?></td>

				      <td class="center">
				        <a href="reports/export-measurements-report.php?Id=<?= $iId ?>"><img src="images/icons/report.gif" width="16" height="16" alt="Measurements Report" title="Measurements Report" /></a>
<?
			if (checkUserRights("measurement-specs.php", "Sampling", "view"))
			{
?>
				        &nbsp;
				        <a href="sampling/view-measurement-specs.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Style # <?= $sStyle ?> &nbsp; (<?= $sSampleType ?>) :: :: width: 700, height: 550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
<?
			}
?>
				      </td>
				    </tr>
<?
		}

		if ($iCount == 0)
		{
?>
				    <tr>
				      <td class="noRecord">No Measurement Specs Record Found!</td>
				    </tr>
<?
		}
?>
			      </table>
			    </div>
<?
	}

	@include($sBaseDir."includes/my-profile.php");
?>
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