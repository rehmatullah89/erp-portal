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

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );


	$Id = IO::strValue('Id');


	$sSQL = "SELECT *, (SELECT name FROM tbl_users WHERE id=tbl_comment_sheets.modified_by) AS _Person FROM tbl_comment_sheets WHERE MD5(merchandising_id)='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$Id               = $objDb->getField(0, "merchandising_id");
		$sMerchComments   = $objDb->getField(0, "merch_comments");
		$sSpecComments    = $objDb->getField(0, "spec_comments");
		$sOtherComments   = $objDb->getField(0, "other_comments");
		$sFittingComments = $objDb->getField(0, "fitting_comments");
		$sNoteSuggestions = $objDb->getField(0, "note_suggestions");
		$sPerson          = $objDb->getField(0, "_Person");


		$sSQL = "SELECT style_id, `status`,
		                (SELECT type FROM tbl_sampling_types WHERE id=tbl_merchandisings.sample_type_id) AS _SampleType,
		                (SELECT wash FROM tbl_sampling_washes WHERE id=tbl_merchandisings.wash_id) AS _Wash
		         FROM tbl_merchandisings
		         WHERE id='$Id'";
		$objDb->query($sSQL);

		$iStyleId    = $objDb->getField(0, "style_id");
		$sStatus     = $objDb->getField(0, "status");
		$sSampleType = $objDb->getField(0, "_SampleType");
		$sWash       = $objDb->getField(0, "_Wash");
	}

	else
		redirect("./", "ERROR");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/sampling-comments.js"></script>
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
			    <h1><img src="images/h1/sampling/measurement-specs.jpg" width="285" height="20" vspace="10" alt="" title="" /></h1>

			    <div class="tblSheet">
			      <h2>Measurement Entry</h2>


<?
	$sSQL = "SELECT style, sub_brand_id,
	                (SELECT brand FROM tbl_brands WHERE id=tbl_styles.sub_brand_id) AS _Brand,
	                (SELECT season FROM tbl_seasons WHERE id=tbl_styles.sub_season_id) AS _Season
	         FROM tbl_styles
	         WHERE id='$iStyleId'";
	$objDb->query($sSQL);

	$sStyle  = $objDb->getField(0, 'style');
	$sBrand  = $objDb->getField(0, '_Brand');
	$iBrand  = $objDb->getField(0, 'sub_brand_id');
	$sSeason = $objDb->getField(0, '_Season');
?>
				  <table border="0" cellpadding="3" cellspacing="0" width="100%">
				    <tr>
					  <td width="90"><b>Style No</b></td>
					  <td width="20" align="center">:</td>
					  <td><?= $sStyle ?> (<?= $sBrand ?>, <?= $sSeason ?>)</td>
				    </tr>

				    <tr>
					  <td><b>Sample Type</b></td>
					  <td align="center">:</td>
					  <td><?= $sSampleType ?></td>
				    </tr>

				    <tr>
					  <td><b>Wash / Color</b></td>
					  <td align="center">:</td>
					  <td><?= $sWash ?></td>
				    </tr>
				  </table>

				  <br />
				  <h2>Measurement Details</h2>

<?
	$sSQL = "SELECT sample_sizes, sample_quantities, modified FROM tbl_merchandisings WHERE id='$Id'";
	$objDb->query($sSQL);

	$sSampleSizes      = $objDb->getField(0, 'sample_sizes');
	$sSampleQuantities = $objDb->getField(0, 'sample_quantities');
	$iEntryTime        = @strtotime($objDb->getField(0, 'modified'));
	$iOrderTime        = @strtotime(date("2010-01-20 23:59:59"));
	$sOrderField       = "id";

	if ($iEntryTime > $iOrderTime)
		$sOrderField = "display_order";

	if ($objDb->getCount( ) == 0 || $sSampleSizes == "" || $sSampleQuantities == "")
		redirect("./", "INVALID_MERCHANDISING_ENTRY");


	$iSampleQuantities = @explode(",", $sSampleQuantities);
	$iSampleSizes      = array( );


	$sSQL = "SELECT size FROM tbl_sampling_sizes WHERE id IN ($sSampleSizes) ORDER BY $sOrderField";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$iSampleSizes[] = $objDb->getField($i, 0);
	}

	$iSizesCount      = count($iSampleSizes);
	$iQuantitiesCount = count($iSampleQuantities);
?>
				  <div style="margin:0px 8px 0px 8px; overflow:hidden;">
				    <div style="width:auto; overflow:scroll; scroll:x-scroll;">
					  <table border="1" bordercolor="#ffffff" cellpadding="3" cellspacing="0" width="100%">
					    <tr class="sdRowHeader">
						  <td width="300" rowspan="3">&nbsp;<b>Measurement Point</b></td>
						  <td width="80" rowspan="3" align="center"><b>Tolerance</b></td>
						  <td width="<?= ((@array_sum($iSampleQuantities) + $iQuantitiesCount) * 50) ?>" align="center" colspan="<?= (@array_sum($iSampleQuantities) + $iQuantitiesCount) ?>">&nbsp;<b>Sizes</b></td>
					    </tr>

					    <tr class="sdRowHeader">
<?
	for ($i = 0; $i < $iSizesCount; $i ++)
	{
?>
						  <td width="<?= (($iSampleQuantities[$i] + 1 + $iQuantitiesCount) * 50) ?>" align="center" colspan="<?= ($iSampleQuantities[$i] + 1) ?>"><b><?= $iSampleSizes[$i] ?></b></td>
<?
	}
?>
					    </tr>

					    <tr class="sdRowHeader">
<?
	for ($i = 0; $i < $iSizesCount; $i ++)
	{
		for ($j = 0; $j <= $iSampleQuantities[$i]; $j ++)
		{
			$sHeading = $j;

			if ($j == 0)
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
	         WHERE ms.point_id=mp.id AND ms.merchandising_id='$Id'
	         ORDER BY ms.id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		$sPoint     = $objDb->getField($i, '_Point');
		$sTolerance = $objDb->getField($i, 'tolerance');
		$sData      = $objDb->getField($i, 'data');

		$sData = @explode(",", $sData);
?>

					    <tr class="sdRowColor">
						  <td width="300" align="left">&nbsp;<?= $sPoint ?></td>
						  <td width="80" align="center"><?= $sTolerance ?></td>
<?
		$iIndex = 0;

		for ($j = 0; $j < $iSizesCount; $j ++)
		{
			for ($k = 0; $k <= $iSampleQuantities[$j]; $k ++)
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
				  </div>

				  <br />
				  <h2 id="DefectDetails" style="margin-bottom:0px;">Defects Details</h2>

				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="sdRowHeader">
				  	  <td width="50" align="center"><b>#</b></td>
					  <td><b>Defect Class - Defect Code</b></td>
					  <td width="200"><b>Area</b></td>
					  <td width="100" align="center"><b>Defects</b></td>
				    </tr>
<?
	$iMainBrand = getDbValue("parent_id", "tbl_brands", "id='$iBrand'");


	$sSQL = "SELECT * FROM tbl_sampling_report_defects WHERE merchandising_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		$sSQL = ("SELECT code, defect FROM tbl_sampling_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL);

		$sSQL = ("SELECT id, area FROM tbl_sampling_defect_areas WHERE id='".$objDb->getField($i, 'area_id')."'");
		$objDb3->query($sSQL);
?>

				    <tr class="sdRowColor" valign="top">
					  <td align="center"><?= ($i + 1) ?></td>
					  <td><?= $objDb2->getField(0, 0) ?> - <?= $objDb2->getField(0, 1) ?></td>
					  <td><?= $objDb3->getField(0, 0) ?> - <?= $objDb3->getField(0, 1) ?></td>
					  <td align="center"><?= $objDb->getField($i, 'defects') ?></td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>

				    <tr class="sdRowColor">
					  <td colspan="3" align="center">No Defect Found!</td>
				    </tr>
<?
	}
?>
				  </table>

<?
	if ($iBrand == 124)
	{
		$sSQL = "SELECT * FROM tbl_ms_sampling WHERE merchandising_id='$Id'";
		$objDb->query($sSQL);

		$sDepartment           = $objDb->getField(0, "department");
		$sStrokeNo             = $objDb->getField(0, "stroke_no");
		$sDescription          = $objDb->getField(0, "description");
		$sBlockRef             = $objDb->getField(0, "block_ref");
		$sAmendments           = $objDb->getField(0, "amendments");
		$sModels               = $objDb->getField(0, "models");
		$sSupplier             = $objDb->getField(0, "supplier");
		$sFabricQuality        = $objDb->getField(0, "fabric_quality");
		$sBulkCutDate          = $objDb->getField(0, "bulk_cut_date");
		$sFactory              = $objDb->getField(0, "factory");
		$sSupplierTechnologist = $objDb->getField(0, "supplier_technologist");
		$sMsTechnologist       = $objDb->getField(0, "ms_technologist");
?>
				  <br />
				  <h2>For Marks & Spencer Sampling Report</h2>

				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr valign="top">
					  <td width="50%">

						<table border="0" cellpadding="3" cellspacing="0" width="100%">
						 <tr>
							<td width="130">Department</td>
							<td width="20" align="center">:</td>
							<td><?= $sDepartment ?></td>
						  </tr>

						  <tr>
							<td>Stroke No.</td>
							<td align="center">:</td>
							<td><?= $sStrokeNo ?></td>
						  </tr>

						  <tr>
							<td>Description</td>
							<td align="center">:</td>
							<td><?= $sDescription ?></td>
						  </tr>

						  <tr>
							<td>Block Ref.</td>
							<td align="center">:</td>
							<td><?= $sBlockRef ?></td>
						  </tr>

						  <tr>
							<td>Amendments to Block</td>
							<td align="center">:</td>
							<td><?= $sAmendments ?></td>
						  </tr>

						  <tr>
							<td>Models Used</td>
							<td align="center">:</td>
							<td><?= $sModels ?></td>
						  </tr>
						</table>

					  </td>

					  <td width="50%">

						<table border="0" cellpadding="3" cellspacing="0" width="100%">
						 <tr>
							<td width="130">Supplier</td>
							<td width="20" align="center">:</td>
							<td><?= $sSupplier ?></td>
						  </tr>

						  <tr>
							<td>Fabric Quality</td>
							<td align="center">:</td>
							<td><?= $sFabricQuality ?></td>
						  </tr>

						  <tr>
							<td>Bulk Cut Date</td>
							<td align="center">:</td>
							<td><?= $sBulkCutDate ?></td>
						  </tr>

						  <tr>
							<td>Factory</td>
							<td align="center">:</td>
							<td><?= $sFactory ?></td>
						  </tr>

						  <tr>
							<td>Supplier Technologist</td>
							<td align="center">:</td>
							<td><?= $sSupplierTechnologist ?></td>
						  </tr>

						  <tr>
							<td>M&S Technologist</td>
							<td align="center">:</td>
							<td><?= $sMsTechnologist ?></td>
						  </tr>
						</table>

					  </td>
					</tr>
				  </table>
<?
	}
?>

				  <br />
				  <h2>MATRIX Comments</h2>

				  <table border="0" cellpadding="5" cellspacing="0" width="100%">
				    <tr valign="top">
					  <td width="140">Merchant Comments</td>
					  <td width="20" align="center">:</td>
					  <td><?= nl2br($sMerchComments) ?></td>
				    </tr>

				    <tr valign="top">
					  <td>Spec Comments</td>
					  <td align="center">:</td>
					  <td><?= nl2br($sSpecComments) ?></td>
				    </tr>

				    <tr valign="top">
					  <td>Constructions / Quality /<br />Workmanship</td>
					  <td align="center">:</td>
					  <td><?= nl2br($sOtherComments) ?></td>
				    </tr>

				    <tr valign="top">
					  <td>Fitting Comments</td>
					  <td align="center">:</td>
					  <td><?= nl2br($sFittingComments) ?></td>
				    </tr>

				    <tr valign="top">
					  <td>Note / Suggestions</td>
					  <td align="center">:</td>
					  <td><?= nl2br($sNoteSuggestions) ?></td>
				    </tr>

<?
	switch ($sStatus)
	{
		case "A" : $sStatus = "Approved"; break;
		case "R" : $sStatus = "Rejected"; break;
		case "W" : $sStatus = "Waiting"; break;
	}
?>
				    <tr>
					  <td>Status</td>
					  <td align="center">:</td>
					  <td><?= $sStatus ?></td
				    </tr>

				    <tr>
					  <td>Person</td>
					  <td align="center">:</td>
					  <td><?= $sPerson ?></td
				    </tr>
				  </table>

				  <br />
				  <h2>Buyer Comments</h2>

<?
	$sSQL = "SELECT *,
	                (SELECT name FROM tbl_users WHERE id=tbl_measurement_comments.user_id) AS _User
	         FROM tbl_measurement_comments
	         WHERE merchandising_id='$Id'
	         ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
				  <div style="margin:0px 10px 10px 10px;">
			        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			          <tr class="sdRowHeader">
					    <td width="35" align="center"><b>#</b></td>
					    <td width="100"><b>From</b></td>
					    <td><b>Comments</b></td>
					    <td width="150"><b>Details</b></td>
			          </tr>
<?
		for ($i = 0; $i < $iCount; $i ++)
		{
?>
					  <tr class="sdRowColor" valign="top">
					    <td align="center"><?= ($i + 1) ?></td>

					    <td>
					      <b><?= $objDb->getField($i, "office") ?></b><br />
					      <br />
<?
			if ($objDb->getField($i, "status") == "A")
			{
?>
					      <span style="color:#00aa00;">Approved</span><br />
<?
			}

			else if ($objDb->getField($i, "status") == "R")
			{
?>
					      <span style="color:#ff0000;">Rejected</span><br />
<?
			}
?>
					    </td>

					    <td><?= nl2br($objDb->getField($i, "comments")) ?></td>

					    <td>
					      <?= formatDate($objDb->getField($i, "date_time"), "d-M-Y h:i A") ?><br />
					      <br />
					      <?= $objDb->getField($i, "ip_address") ?><br />
					      <i><?= $objDb->getField($i, "_User") ?></i><br />
					    </td>
					  </tr>
<?
		}
?>
				    </table>
				  </div>
<?
	}
?>

			      <form name="frmData" id="frmData" method="post" action="save-sampling-comments.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			      <input type="hidden" name="Id" value="<?= $Id ?>" />

				  <h2>Your Comments</h2>

				  <table border="0" cellpadding="3" cellspacing="0" width="100%">
				    <tr>
					  <td width="65">From</td>
					  <td width="20" align="center">:</td>

					  <td>
					    <select name="Office">
					      <option value=""></option>
<?
	$iMainBrand = getDbValue("parent_id", "tbl_brands", "id='$iBrand'");


	$sSQL = "SELECT id, office FROM tbl_brand_offices WHERE brand_id='$iMainBrand' ORDER BY office";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iOffice = $objDb->getField($i, 0);
		$sOffice = $objDb->getField($i, 1);
?>
					      <option value="<?= $iOffice ?>"><?= $sOffice ?></option>
<?
	}
?>
					    </select>
					  </td>
				    </tr>

				    <tr valign="top">
					  <td>Comments</td>
					  <td align="center">:</td>
					  <td><textarea name="Comments" rows="8" style="width:98%;"></textarea></td>
				    </tr>

				    <tr>
					  <td>Status</td>
					  <td align="center">:</td>

					  <td>
					    <select name="Status">
						  <option value=""></option>
						  <option value="A">Approved</option>
					  	  <option value="R">Rejected</option>
					    </select>
					  </td>
				    </tr>
				  </table>

				  <br />

				  <div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			      </form>
			    </div>
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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>