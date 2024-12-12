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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Category = IO::strValue("Category");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Category          = IO::strValue("Category");
		$LeadTime1000      = IO::strValue("LeadTime1000");
		$LeadTime2500      = IO::strValue("LeadTime2500");
		$LeadTime5000      = IO::strValue("LeadTime5000");
		$Knitting          = IO::strValue("Knitting");
		$Linking           = IO::strValue("Linking");
		$Yarn              = IO::strValue("Yarn");
		$Sizing            = IO::strValue("Sizing");
		$Weaving           = IO::strValue("Weaving");
		$LeatherImport     = IO::strValue("LeatherImport");
		$Dyeing            = IO::strValue("Dyeing");
		$LeatherInspection = IO::strValue("LeatherInspection");
		$Lamination        = IO::strValue("Lamination");
		$Cutting           = IO::strValue("Cutting");
		$PrintEmbroidery   = IO::strValue("PrintEmbroidery");
		$Sorting           = IO::strValue("Sorting");
		$BladderAttachment = IO::strValue("BladderAttachment");
		$Stitching         = IO::strValue("Stitching");
		$Washing           = IO::strValue("Washing");
		$Finishing         = IO::strValue("Finishing");
		$LabTesting        = IO::strValue("LabTesting");
		$Quality           = IO::strValue("Quality");
		$Packing           = IO::strValue("Packing");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/categories.js"></script>
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
			    <h1>Categories</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-category.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Category</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="170">Category<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Category" value="<?= $Category ?>" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Lead Time / 1000 Pcs</td>
					<td align="center">:</td>
					<td><input type="text" name="LeadTime1000" value="<?= $LeadTime1000 ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Lead Time / 2500 Pcs</td>
					<td align="center">:</td>
					<td><input type="text" name="LeadTime2500" value="<?= $LeadTime2500 ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Lead Time / 5000 Pcs</td>
					<td align="center">:</td>
					<td><input type="text" name="LeadTime5000" value="<?= $LeadTime5000 ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>
				</table>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="170">Knitting Lead Time</td>
					<td width="20" align="center">:</td>
					<td width="300"><input type="text" name="Knitting" value="<?= $Knitting ?>" size="12" maxlength="10" class="textbox" /></td>
					<td width="170">Print/Embroidery Lead Time</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="PrintEmbroidery" value="<?= $PrintEmbroidery ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Linking Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="Linking" value="<?= $Linking ?>" size="12" maxlength="10" class="textbox" /></td>
					<td>Sorting Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="Sorting" value="<?= $Sorting ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Yarn Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="Yarn" value="<?= $Yarn ?>" size="12" maxlength="10" class="textbox" /></td>
					<td>Bladder Attachment Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="BladderAttachment" value="<?= $BladderAttachment ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Sizing Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="Sizing" value="<?= $Sizing ?>" size="12" maxlength="10" class="textbox" /></td>
					<td>Stitching Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="Stitching" value="<?= $Stitching ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Weaving Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="Weaving" value="<?= $Weaving ?>" size="12" maxlength="10" class="textbox" /></td>
					<td>Washing Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="Washing" value="<?= $Washing ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Artificial Leather Import Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="LeatherImport" value="<?= $LeatherImport ?>" size="12" maxlength="10" class="textbox" /></td>
					<td>Finishing Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="Finishing" value="<?= $Finishing ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Dyeing Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="Dyeing" value="<?= $Dyeing ?>" size="12" maxlength="10" class="textbox" /></td>
					<td>Lab Testing Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="LabTesting" value="<?= $LabTesting ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Artificial Leather Inspection Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="LeatherInspection" value="<?= $LeatherInspection ?>" size="12" maxlength="10" class="textbox" /></td>
					<td>Quality Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="Quality" value="<?= $Quality ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Lamination Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="Lamination" value="<?= $Lamination ?>" size="12" maxlength="10" class="textbox" /></td>
					<td>Packing Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="Packing" value="<?= $Packing ?>" size="12" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Cutting Lead Time</td>
					<td align="center">:</td>
					<td><input type="text" name="Cutting" value="<?= $Cutting ?>" size="12" maxlength="10" class="textbox" /></td>
					<td></td>
					<td></td>
					<td></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="70">Category</td>
			          <td width="150"><input type="text" name="Category" value="<?= $Category ?>" class="textbox" maxlength="50" size="20" /></td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Category != "")
		$sConditions = " WHERE category LIKE '%$Category%' ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_categories", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_categories $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="20%">Category</td>
				      <td width="20%">Lead Time / 1000 Pcs</td>
				      <td width="20%">Lead Time / 2500 Pcs</td>
				      <td width="20%">Lead Time / 5000 Pcs</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId                = $objDb->getField($i, 'id');
		$sCategory          = $objDb->getField($i, 'category');
		$iLeadTime1000      = $objDb->getField($i, 'lead_time_1000pcs');
		$iLeadTime2500      = $objDb->getField($i, 'lead_time_2500pcs');
		$iLeadTime5000      = $objDb->getField($i, 'lead_time_5000pcs');
		$iKnitting          = $objDb->getField($i, 'knitting');
		$iLinking           = $objDb->getField($i, 'linking');
		$iYarn              = $objDb->getField($i, 'yarn');
		$iSizing            = $objDb->getField($i, 'sizing');
		$iWeaving           = $objDb->getField($i, 'weaving');
		$iLeatherImport     = $objDb->getField($i, 'leather_import');
		$iDyeing            = $objDb->getField($i, 'dyeing');
		$iLeatherInspection = $objDb->getField($i, 'leather_inspection');
		$iLamination        = $objDb->getField($i, 'lamination');
		$iCutting           = $objDb->getField($i, 'cutting');
		$iPrintEmbroidery   = $objDb->getField($i, 'print_embroidery');
		$iSorting           = $objDb->getField($i, 'sorting');
		$iBladderAttachment = $objDb->getField($i, 'bladder_attachment');
		$iStitching         = $objDb->getField($i, 'stitching');
		$iWashing           = $objDb->getField($i, 'washing');
		$iFinishing         = $objDb->getField($i, 'finishing');
		$iLabTesting        = $objDb->getField($i, 'lab_testing');
		$iQuality           = $objDb->getField($i, 'quality');
		$iPacking           = $objDb->getField($i, 'packing');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="20%"><span id="Category<?= $iId ?>"><?= $sCategory ?></span></td>
				      <td width="20%"><span id="LeadTime1000Pcs<?= $iId ?>"><?= formatNumber($iLeadTime1000, false) ?></span></td>
				      <td width="20%"><span id="LeadTime2500Pcs<?= $iId ?>"><?= formatNumber($iLeadTime2500, false) ?></span></td>
				      <td width="20%"><span id="LeadTime5000Pcs<?= $iId ?>"><?= formatNumber($iLeadTime5000, false) ?></span></td>

				      <td width="12%" class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-category.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Category?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="170">Category<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Category" value="<?= $sCategory ?>" size="30" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Lead Time / 1000 Pcs</td>
						  <td align="center">:</td>
						  <td><input type="text" name="LeadTime1000" value="<?= $iLeadTime1000 ?>" size="12" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Lead Time / 2500 Pcs</td>
						  <td align="center">:</td>
						  <td><input type="text" name="LeadTime2500" value="<?= $iLeadTime2500 ?>" size="12" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Lead Time / 5000 Pcs</td>
						  <td align="center">:</td>
						  <td><input type="text" name="LeadTime5000" value="<?= $iLeadTime5000 ?>" size="12" maxlength="10" class="textbox" /></td>
					    </tr>
					  </table>

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="170">Knitting Lead Time</td>
						  <td width="20" align="center">:</td>
						  <td width="300"><input type="text" name="Knitting" value="<?= $iKnitting ?>" size="12" maxlength="10" class="textbox" /></td>
						  <td width="170">Print/Embroidery Lead Time</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="PrintEmbroidery" value="<?= $iPrintEmbroidery ?>" size="12" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Linking Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Linking" value="<?= $iLinking ?>" size="12" maxlength="10" class="textbox" /></td>
						  <td>Sorting Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Sorting" value="<?= $iSorting ?>" size="12" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Yarn Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Yarn" value="<?= $iYarn ?>" size="12" maxlength="10" class="textbox" /></td>
						  <td>Bladder Attachment Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="BladderAttachment" value="<?= $iBladderAttachment ?>" size="12" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Sizing Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Sizing" value="<?= $iSizing ?>" size="12" maxlength="10" class="textbox" /></td>
						  <td>Stitching Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Stitching" value="<?= $iStitching ?>" size="12" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Weaving Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Weaving" value="<?= $iWeaving ?>" size="12" maxlength="10" class="textbox" /></td>
						  <td>Washing Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Washing" value="<?= $iWashing ?>" size="12" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Artificial Leather Import Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="LeatherImport" value="<?= $iLeatherImport ?>" size="12" maxlength="10" class="textbox" /></td>
						  <td>Finishing Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Finishing" value="<?= $iFinishing ?>" size="12" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Dyeing Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Dyeing" value="<?= $iDyeing ?>" size="12" maxlength="10" class="textbox" /></td>
						  <td>Lab Testing Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="LabTesting" value="<?= $iLabTesting ?>" size="12" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Artificial Leather Inspection Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="LeatherInspection" value="<?= $iLeatherInspection ?>" size="12" maxlength="10" class="textbox" /></td>
						  <td>Quality Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Quality" value="<?= $iQuality ?>" size="12" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Lamination Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Lamination" value="<?= $iLamination ?>" size="12" maxlength="10" class="textbox" /></td>
						  <td>Packing Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Packing" value="<?= $iPacking ?>" size="12" maxlength="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Cutting Lead Time</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Cutting" value="<?= $iCutting ?>" size="12" maxlength="10" class="textbox" /></td>
						  <td></td>
						  <td></td>
						  <td></td>
					    </tr>
					  </table>

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="100%">
						    <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
						  </td>
					    </tr>
					  </table>
					  </form>

				    </div>
				  </div>

				  <div id="Msg<?= $iId ?>" class="msgOk" style="display:none;"></div>

<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No category Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Category={$Category}");
?>

			  </td>
			</tr>
		  </table>

<?
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