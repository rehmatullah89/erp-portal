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

	$iId = IO::strValue("Id");

	$sSQL = "SELECT * FROM tbl_categories WHERE id=(SELECT category_id FROM tbl_vendors WHERE id=(SELECT vendor_id FROM tbl_po WHERE id='$iId') AND parent_id='0' AND sourcing='Y')";
	$objDb->query($sSQL);

	$sCategory          = $objDb->getField(0, 'category');
	$iKnitting          = $objDb->getField(0, 'knitting');
	$iLinking           = $objDb->getField(0, 'linking');
	$iYarn              = $objDb->getField(0, 'yarn');
	$iSizing            = $objDb->getField(0, 'sizing');
	$iWeaving           = $objDb->getField(0, 'weaving');
	$iLeatherImport     = $objDb->getField(0, 'leather_import');
	$iDyeing            = $objDb->getField(0, 'dyeing');
	$iLeatherInspection = $objDb->getField(0, 'leather_inspection');
	$iLamination        = $objDb->getField(0, 'lamination');
	$iCutting           = $objDb->getField(0, 'cutting');
	$iPrintEmbroidery   = $objDb->getField(0, 'print_embroidery');
	$iSorting           = $objDb->getField(0, 'sorting');
	$iBladderAttachment = $objDb->getField(0, 'bladder_attachment');
	$iStitching         = $objDb->getField(0, 'stitching');
	$iWashing           = $objDb->getField(0, 'washing');
	$iFinishing         = $objDb->getField(0, 'finishing');
	$iLabTesting        = $objDb->getField(0, 'lab_testing');
	$iQuality           = $objDb->getField(0, 'quality');
	$iPacking           = $objDb->getField(0, 'packing');


	$sStages = array( );

	$sStages[0]['Category']  = "Knitting";
	$sStages[0]['LeadTime']  = $iKnitting;
	$sStages[0]['Color']     = "#a2c0f3";

	$sStages[1]['Category']  = "Linking";
	$sStages[1]['LeadTime']  = $iLinking;
	$sStages[1]['Color']     = "#7f96be";

	$sStages[2]['Category']  = "Yarn";
	$sStages[2]['LeadTime']  = $iYarn;
	$sStages[2]['Color']     = "#637594";

	$sStages[3]['Category']  = "Sizing";
	$sStages[3]['LeadTime']  = $iSizing;
	$sStages[3]['Color']     = "#dad42f";

	$sStages[4]['Category']  = "Weaving";
	$sStages[4]['LeadTime']  = $iWeaving;
	$sStages[4]['Color']     = "#266ee9";

	$sStages[5]['Category']  = "Leather Import";
	$sStages[5]['LeadTime']  = $iLeatherImport;
	$sStages[5]['Color']     = "#1e55b3";

	$sStages[6]['Category']  = "Dyeing";
	$sStages[6]['LeadTime']  = $iDyeing;
	$sStages[6]['Color']     = "#133671";

	$sStages[7]['Category']  = "Leather Inspection";
	$sStages[7]['LeadTime']  = $iLeatherInspection;
	$sStages[7]['Color']     = "#b2cfe0";

	$sStages[8]['Category']  = "Lamination";
	$sStages[8]['LeadTime']  = $iLamination;
	$sStages[8]['Color']     = "#7e94a1";

	$sStages[9]['Category']  = "Cutting";
	$sStages[9]['LeadTime']  = $iCutting;
	$sStages[9]['Color']     = "#b6e500";

	$sStages[10]['Category'] = "Print/Embroidery";
	$sStages[10]['LeadTime'] = $iPrintEmbroidery;
	$sStages[10]['Color']    = "#54636b";

	$sStages[11]['Category'] = "Sorting";
	$sStages[11]['LeadTime'] = $iSorting;
	$sStages[11]['Color']    = "#1a81bc";

	$sStages[12]['Category'] = "Bladder Attachment";
	$sStages[12]['LeadTime'] = $iBladderAttachment;
	$sStages[12]['Color']    = "#1aafbc";

	$sStages[13]['Category'] = "Stitching";
	$sStages[13]['LeadTime'] = $iStitching;
	$sStages[13]['Color']    = "#712ba3";

	$sStages[14]['Category'] = "Washing";
	$sStages[14]['LeadTime'] = $iWashing;
	$sStages[14]['Color']    = "#c8ab23";

	$sStages[15]['Category'] = "Finishing";
	$sStages[15]['LeadTime'] = $iFinishing;
	$sStages[15]['Color']    = "#aad2d6";

	$sStages[16]['Category'] = "Lab Testing";
	$sStages[16]['LeadTime'] = $iLabTesting;
	$sStages[16]['Color']    = "#97c9be";

	$sStages[17]['Category'] = "Quality";
	$sStages[17]['LeadTime'] = $iQuality;
	$sStages[17]['Color']    = "#62847c";

	$sStages[18]['Category'] = "Packing";
	$sStages[18]['LeadTime'] = $iPacking;
	$sStages[18]['Color']    = "#999999";


	$iTotalDays = 3;
	$iTotalDays += $iKnitting;
	$iTotalDays += $iLinking;
	$iTotalDays += $iYarn;
	$iTotalDays += $iSizing;
	$iTotalDays += $iWeaving;
	$iTotalDays += $iLeatherImport;
	$iTotalDays += $iDyeing;
	$iTotalDays += $iLeatherInspection;
	$iTotalDays += $iLamination;
	$iTotalDays += $iCutting;
	$iTotalDays += $iPrintEmbroidery;
	$iTotalDays += $iSorting;
	$iTotalDays += $iBladderAttachment;
	$iTotalDays += $iStitching;
	$iTotalDays += $iWashing;
	$iTotalDays += $iFinishing;
	$iTotalDays += $iLabTesting;
	$iTotalDays += $iQuality;
	$iTotalDays += $iPacking;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/vsr/view-vsr-details.js"></script>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">

	  <div style="padding:10px; background:#fbfbfb;">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		  <tr valign="top">
			<td width="49%">

			  <h2>T&A Calculated</h2>
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
				  <td width="120"></td>
				  <td width="20"></td>
				  <td width="102"></td>
				  <td width="102"></td>
				  <td width="10"></td>
				  <td></td>
				</tr>
<?
	$sEtdRequired = getDbValue("etd_required", "tbl_po_colors", "po_id='$iId'", "etd_required ASC");
	$sStartDate   = date("Y-m-d", (strtotime($sEtdRequired) - ($iTotalDays * 24 * 60 * 60)));

	for ($i = 0; $i < count($sStages); $i ++)
	{
		if ($sStages[$i]['LeadTime'] > 0)
		{
			$sEndDate    = date("Y-m-d", (strtotime($sStartDate) + (($sStages[$i]['LeadTime'] - 1) * 24 * 60 * 60)));
			$iPercentage = getPercentage($sStartDate, $sEndDate, $sStages[$i]['LeadTime']);


			if ($sStages[$i]['Category'] == "Dyeing" && getDbValue("dyeing", "tbl_vsr", "po_id='$iId'") == 0 && getDbValue("dyeing_start_date", "tbl_vsr", "po_id='$iId'") == "0000-00-00" && getDbValue("dyeing_end_date", "tbl_vsr", "po_id='$iId'") == "0000-00-00")
				continue;

			if ($sStages[$i]['Category'] == "Washing" && getDbValue("washing", "tbl_vsr", "po_id='$iId'") == 0 && getDbValue("washing_start_date", "tbl_vsr", "po_id='$iId'") == "0000-00-00" && getDbValue("washing_end_date", "tbl_vsr", "po_id='$iId'") == "0000-00-00")
				continue;
?>

				<tr>
				  <td><?= $sStages[$i]['Category'] ?></td>
				  <td align="center">:</td>
				  <td colspan="2"><div style="background:#f0f0f0; border:solid 1px #666666; padding:1px; height:10px;"><div style="width:<?= ($iPercentage * 2) ?>px; height:10px; background:<?= $sStages[$i]['Color'] ?>;"></div></div></td>
				  <td></td>
				  <td><?= formatNumber($iPercentage) ?> %</td>
				</tr>

				<tr>
				  <td colspan="2"></td>
				  <td><i style="font-size:8px;"><?= formatDate($sStartDate) ?></i></td>
				  <td align="right"><i style="font-size:8px;"><?= formatDate($sEndDate) ?></i></td>
				  <td></td>
				  <td></td>
				</tr>

				<tr>
				  <td colspan="6" height="10"></td>
				</tr>
<?
			$sStartDate = date("Y-m-d", (strtotime($sStartDate) + ($sStages[$i]['LeadTime'] * 24 * 60 * 60)));
		}
	}
?>
			  </table>

			  <br />
			  <h2 style="margin-bottom:0px;">Discussions</h2>

			  <div id="Discussions<?= $iId ?>">
<?
	$sSQL = "SELECT user_id, comments, date_time FROM tbl_vsr_comments WHERE po_id='$iId' ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUserId   = $objDb->getField($i, "user_id");
		$sComments = $objDb->getField($i, "comments");
		$sDateTime = $objDb->getField($i, "date_time");


		$sSQL = "SELECT name, picture FROM tbl_users WHERE id='$iUserId'";
		$objDb2->query($sSQL);

		$sName    = $objDb2->getField(0, "name");
		$sPicture = $objDb2->getField(0, "picture");

		if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
			$sPicture = "default.jpg";
?>
				<div style="background:#<?= ((($i % 2) == 1) ? 'eaeaea' : 'f3f3f3') ?>; padding:10px; margin-bottom:10px;">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr valign="top">
					  <td width="82" align="center">
						<div id="ProfilePic" style="margin:0px 0px 5px 0px; width:82px;">
						  <div id="Pic"><img src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" alt="<?= $sName ?>" title="<?= $sName ?>" style="width:78px; height:59px;" /></div>
						</div>

						<i style="color:#999999; font-size:10px;"><?= $sName ?></i><br />
					  </td>

					  <td width="20"></td>

					  <td>
						<h4 style="font-size:11px;"><?= formatDate($sDateTime, "l, jS F, Y   h:i A") ?></h4>
						<?= nl2br($sComments) ?><br />
					  </td>
					</tr>
				  </table>
				</div>
<?
	}

	if ($iCount == 0)
	{
?>
				<div id="NoRecord<?= $iId ?>" class="noRecord" style="font-size:17px;">No comments posted yet!</div>
<?
	}
?>
			  </div>

			  <br />
			  <h3>Post Your Comments</h3>

			  <div style="background:#dddddd; padding:0px 5px 5px 5px;">
				<textarea name="Comments<?= $iId ?>" id="Comments<?= $iId ?>" style="width:98.5%; height:80px;"></textarea>
			  </div>

			  <div class="buttonsBar"><input type="button" id="BtnSave<?= $iId ?>" value="" class="btnSave" onclick="saveComments(<?= $iId ?>);" /></div>
			</td>

			<td width="2%"></td>

			<td width="49%">
			  <h2>T&A Actual</h2>
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
				  <td width="120"></td>
				  <td width="20"></td>
				  <td width="102"></td>
				  <td width="102"></td>
				  <td width="10"></td>
				  <td></td>
				</tr>

<?
	$sSQL = "SELECT * FROM tbl_vsr WHERE po_id='$iId'";
	$objDb->query($sSQL);

	$sStages[0]['Percentage']  = $objDb->getField(0, 'knitting');
	$sStages[0]['StartDate']   = $objDb->getField(0, 'knitting_start_date');
	$sStages[0]['EndDate']     = $objDb->getField(0, 'knitting_end_date');

	$sStages[1]['Percentage']  = $objDb->getField(0, 'linking');
	$sStages[1]['StartDate']   = $objDb->getField(0, 'linking_start_date');
	$sStages[1]['EndDate']     = $objDb->getField(0, 'linking_end_date');

	$sStages[2]['Percentage']  = $objDb->getField(0, 'yarn');
	$sStages[2]['StartDate']   = $objDb->getField(0, 'yarn_start_date');
	$sStages[2]['EndDate']     = $objDb->getField(0, 'yarn_end_date');

	$sStages[3]['Percentage']  = $objDb->getField(0, 'sizing');
	$sStages[3]['StartDate']   = $objDb->getField(0, 'sizing_start_date');
	$sStages[3]['EndDate']     = $objDb->getField(0, 'sizing_end_date');

	$sStages[4]['Percentage']  = $objDb->getField(0, 'weaving');
	$sStages[4]['StartDate']   = $objDb->getField(0, 'weaving_start_date');
	$sStages[4]['EndDate']     = $objDb->getField(0, 'weaving_end_date');

	$sStages[5]['Percentage']  = $objDb->getField(0, 'leather_import');
	$sStages[5]['StartDate']   = $objDb->getField(0, 'leather_import_start_date');
	$sStages[5]['EndDate']     = $objDb->getField(0, 'leather_import_end_date');

	$sStages[6]['Percentage']  = $objDb->getField(0, 'dyeing');
	$sStages[6]['StartDate']   = $objDb->getField(0, 'dyeing_start_date');
	$sStages[6]['EndDate']     = $objDb->getField(0, 'dyeing_end_date');

	$sStages[7]['Percentage']  = $objDb->getField(0, 'leather_inspection');
	$sStages[7]['StartDate']   = $objDb->getField(0, 'leather_inspection_start_date');
	$sStages[7]['EndDate']     = $objDb->getField(0, 'leather_inspection_end_date');

	$sStages[8]['Percentage']  = $objDb->getField(0, 'lamination');
	$sStages[8]['StartDate']   = $objDb->getField(0, 'lamination_start_date');
	$sStages[8]['EndDate']     = $objDb->getField(0, 'lamination_end_date');

	$sStages[9]['Percentage']  = $objDb->getField(0, 'cutting');
	$sStages[9]['StartDate']   = $objDb->getField(0, 'cutting_start_date');
	$sStages[9]['EndDate']     = $objDb->getField(0, 'cutting_end_date');

	$sStages[10]['Percentage'] = $objDb->getField(0, 'print_embroidery');
	$sStages[10]['StartDate']  = $objDb->getField(0, 'print_embroidery_start_date');
	$sStages[10]['EndDate']    = $objDb->getField(0, 'print_embroidery_end_date');

	$sStages[11]['Percentage'] = $objDb->getField(0, 'sorting');
	$sStages[11]['StartDate']  = $objDb->getField(0, 'sorting_start_date');
	$sStages[11]['EndDate']    = $objDb->getField(0, 'sorting_end_date');

	$sStages[12]['Percentage'] = $objDb->getField(0, 'bladder_attachment');
	$sStages[12]['StartDate']  = $objDb->getField(0, 'bladder_attachment_start_date');
	$sStages[12]['EndDate']    = $objDb->getField(0, 'bladder_attachment_end_date');

	$sStages[13]['Percentage'] = $objDb->getField(0, 'stitching');
	$sStages[13]['StartDate']  = $objDb->getField(0, 'stitching_start_date');
	$sStages[13]['EndDate']    = $objDb->getField(0, 'stitching_end_date');

	$sStages[14]['Percentage'] = $objDb->getField(0, 'washing');
	$sStages[14]['StartDate']  = $objDb->getField(0, 'washing_start_date');
	$sStages[14]['EndDate']    = $objDb->getField(0, 'washing_end_date');

	$sStages[15]['Percentage'] = $objDb->getField(0, 'finishing');
	$sStages[15]['StartDate']  = $objDb->getField(0, 'finishing_start_date');
	$sStages[15]['EndDate']    = $objDb->getField(0, 'finishing_end_date');

	$sStages[16]['Percentage'] = $objDb->getField(0, 'lab_testing');
	$sStages[16]['StartDate']  = $objDb->getField(0, 'lab_testing_start_date');
	$sStages[16]['EndDate']    = $objDb->getField(0, 'lab_testing_end_date');

	$sStages[17]['Percentage'] = $objDb->getField(0, 'quality');
	$sStages[17]['StartDate']  = $objDb->getField(0, 'quality_start_date');
	$sStages[17]['EndDate']    = $objDb->getField(0, 'quality_end_date');

	$sStages[18]['Percentage'] = $objDb->getField(0, 'packing');
	$sStages[18]['StartDate']  = $objDb->getField(0, 'packing_start_date');
	$sStages[18]['EndDate']    = $objDb->getField(0, 'packing_end_date');

	for ($i = 0; $i < count($sStages); $i ++)
	{
		if ($sStages[$i]['LeadTime'] > 0)
		{
			if ($sStages[$i]['Percentage'] == 0 && $sStages[$i]['StartDate'] == "0000-00-00" && $sStages[$i]['EndDate'] == "0000-00-00")
				continue;
?>
				<tr>
				  <td><?= $sStages[$i]['Category'] ?></td>
				  <td align="center">:</td>
				  <td colspan="2"><div style="background:#f0f0f0; border:solid 1px #666666; padding:1px; height:10px;"><div style="width:<?= ($sStages[$i]['Percentage'] * 2) ?>px; height:10px; background:<?= $sStages[$i]['Color'] ?>;"></div></div></td>
				  <td></td>
				  <td><?= formatNumber($sStages[$i]['Percentage']) ?> %</td>
				</tr>

				<tr>
				  <td colspan="2"></td>
				  <td><i style="font-size:8px;"><?= formatDate($sStages[$i]['StartDate']) ?>&nbsp;</i></td>
				  <td align="right"><i style="font-size:8px;">&nbsp;<?= formatDate($sStages[$i]['EndDate']) ?></i></td>
				  <td></td>
				  <td></td>
				</tr>

				<tr>
				  <td colspan="6" height="10"></td>
				</tr>

<?
		}
	}
?>
			  </table>

			  <br />
			  <h2 style="margin-bottom:0px;">VSR Sheet Remarks</h2>
<?
	$sSQL = "SELECT user_id, remarks, date_time FROM tbl_vsr_remarks WHERE po_id='$iId' ORDER BY id DESC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUserId   = $objDb->getField($i, "user_id");
		$sRemarks  = $objDb->getField($i, "remarks");
		$sDateTime = $objDb->getField($i, "date_time");

		if ($iUserId > 0)
		{
			$sSQL = "SELECT name, picture FROM tbl_users WHERE id='$iUserId'";
			$objDb2->query($sSQL);

			$sName    = $objDb2->getField(0, "name");
			$sPicture = $objDb2->getField(0, "picture");

			if ($sPicture == "" || !@file_exists($sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
				$sPicture = "default.jpg";
		}

		else
		{
			$sName    = "Unknown";
			$sPicture = "default.jpg";
		}
?>
				<div style="background:#<?= ((($i % 2) == 1) ? 'eaeaea' : 'f3f3f3') ?>; padding:10px; margin-bottom:10px;">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr valign="top">
					  <td width="82" align="center">
						<div id="ProfilePic" style="margin:0px 0px 5px 0px; width:82px;">
						  <div id="Pic"><img src="<?= USERS_IMG_PATH.'thumbs/'.$sPicture ?>" alt="<?= $sName ?>" title="<?= $sName ?>" style="width:78px; height:59px;" /></div>
						</div>

						<i style="color:#999999; font-size:10px;"><?= $sName ?></i><br />
					  </td>

					  <td width="20"></td>

					  <td>
						<h4 style="font-size:11px;"><?= formatDate($sDateTime, "l, jS F, Y   h:i A") ?></h4>
						<?= nl2br($sRemarks) ?><br />
					  </td>
					</tr>
				  </table>
				</div>
<?
	}

	if ($iCount == 0)
	{
?>
			  <div class="noRecord" style="font-size:17px;">No remarks posted yet!</div>
<?
	}
?>
			</td>
		  </tr>
		</table>
	  </div>
    </div>
<!--  Body Section Ends Here  -->

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