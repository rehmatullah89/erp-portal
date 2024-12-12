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

	if ($sUserRights['Add'] != "Y" && $sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );
	$objDb4      = new Database( );

	$Id      = ((IO::intValue('Id') > 0) ? IO::intValue('Id') : IO::intValue('AuditCode'));
	$Step    = IO::intValue("Step");
	$Sms     = IO::intValue("Sms");
	$Referer = urldecode(IO::strValue("Referer"));

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];


	$sSQL = "SELECT *,
	                (SELECT name FROM tbl_users WHERE id=tbl_qa_reports.user_id) AS _Auditor,
	                (SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor
	         FROM tbl_qa_reports
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$AuditCode             = $objDb->getField(0, "audit_code");
		$Group                 = $objDb->getField(0, "group_id");
		$ReportId              = $objDb->getField(0, "report_id");
		$Vendor                = $objDb->getField(0, "vendor_id");
		$PO                    = $objDb->getField(0, "po_id");
		$AdditionalPos         = $objDb->getField(0, "additional_pos");
		$Style                 = $objDb->getField(0, "style_id");
		$AuditDate             = $objDb->getField(0, "audit_date");
		$AuditStatus           = $objDb->getField(0, "audit_status");
		$AuditStage            = $objDb->getField(0, "audit_stage");
		$AuditResult           = $objDb->getField(0, "audit_result");
		$AuditType             = $objDb->getField(0, "audit_type");
		$BatchSize             = $objDb->getField(0, "batch_size");
		$PackedPercent         = $objDb->getField(0, "packed_percent");
		$Colors                = $objDb->getField(0, "colors");
		$Description           = $objDb->getField(0, "description");
		$Bundle                = $objDb->getField(0, "bundle");
                $LotNo                 = $objDb->getField(0, "cutting_lot_no");
		$Sizes                 = $objDb->getField(0, "sizes");
		$DyeLotNo              = $objDb->getField(0, "dye_lot_no");
		$AcceptablePointsWoven = $objDb->getField(0, "acceptable_points_woven");
		$InspectionType        = $objDb->getField(0, "inspection_type");
		$CutableFabricWidth    = $objDb->getField(0, "cutable_fabric_width");
		$StockStatus           = $objDb->getField(0, "stock_status");
		$RollsInspected        = $objDb->getField(0, "rolls_inspected");
		$Rolls                 = $objDb->getField(0, "no_of_rolls");
		$FabricWidth           = $objDb->getField(0, "fabric_width");
		$TotalGmts             = $objDb->getField(0, "total_gmts");
		$GmtsDefective         = $objDb->getField(0, "defective_gmts");
		$MaxDefects            = $objDb->getField(0, "max_defects");
		$BeautifulProducts     = $objDb->getField(0, "beautiful_products");
		$TotalCartons          = $objDb->getField(0, "total_cartons");
		$CartonsRejected       = $objDb->getField(0, "rejected_cartons");
		$PercentDecfective     = $objDb->getField(0, "defective_percent");
		$Standard              = $objDb->getField(0, "standard");
		$CartonsDhu            = $objDb->getField(0, "cartons_dhu");
		$ShipQty               = $objDb->getField(0, "ship_qty");
		$Knitted               = $objDb->getField(0, "knitted");
		$Dyed                  = $objDb->getField(0, "dyed");
		$Cutting               = $objDb->getField(0, "cutting");
		$Sewing                = $objDb->getField(0, "sewing");
		$Finishing             = $objDb->getField(0, "finishing");
		$Packing               = $objDb->getField(0, "packing");
		$FinalAuditDate        = $objDb->getField(0, "final_audit_date");
		$ReScreenQty           = $objDb->getField(0, "re_screen_qty");
		$CartonsRequired       = $objDb->getField(0, "cartons_required");
		$CartonsShipped        = $objDb->getField(0, "cartons_shipped");
		$ApprovedSample        = $objDb->getField(0, "approved_sample");
		$ShippingMark          = $objDb->getField(0, "shipping_mark");
		$PackingCheck	       = $objDb->getField(0, "packing_check");
		$ApprovedTrims         = $objDb->getField(0, "approved_trims");
		$ShadeBand   	       = $objDb->getField(0, "shade_band");
		$EmbApproval 	       = $objDb->getField(0, "emb_approval");
		$GsmWeight   	       = $objDb->getField(0, "gsm_weight");
		$Comments              = $objDb->getField(0, "qa_comments");
		$SpecsSheet1           = $objDb->getField(0, 'specs_sheet_1');
		$SpecsSheet2           = $objDb->getField(0, 'specs_sheet_2');
		$SpecsSheet3           = $objDb->getField(0, 'specs_sheet_3');
		$SpecsSheet4           = $objDb->getField(0, 'specs_sheet_4');
		$SpecsSheet5           = $objDb->getField(0, 'specs_sheet_5');
		$SpecsSheet6           = $objDb->getField(0, 'specs_sheet_6');
		$SpecsSheet7           = $objDb->getField(0, 'specs_sheet_7');
		$SpecsSheet8           = $objDb->getField(0, 'specs_sheet_8');
		$SpecsSheet9           = $objDb->getField(0, 'specs_sheet_9');
		$SpecsSheet10          = $objDb->getField(0, 'specs_sheet_10');

		$sAuditor              = $objDb->getField(0, "_Auditor");
		$sVendor               = $objDb->getField(0, "_Vendor");

		@list($Length, $Width, $Height, $Unit) = @explode("x", $objDb->getField(0, "carton_size"));


		if ($PercentDecfective == 0)
			$PercentDecfective = @round((($CartonsRejected / $TotalCartons) * 100), 2);
	}

	else
		redirect($Referer, "INVALID_AUDIT_CODE");

	if ($Unit == "")
		$Unit = "in";


	$iSizes           = @explode(",", $Sizes);
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");
	$sPos             = array( );
	$sSelectedPos     = $PO;

	if ($AdditionalPos != "")
		$sSelectedPos .= ",{$AdditionalPos}";


	$sSQL = "SELECT id, CONCAT(order_no, ' ', order_status) AS _Po
	         FROM tbl_po
	         WHERE vendor_id='$Vendor' AND FIND_IN_SET(id, '$sSelectedPos')
	         ORDER BY FIELD(id,{$sSelectedPos})";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iPo = $objDb->getField($i, 0);
		$sPo = $objDb->getField($i, 1);

		$sPos[] = array("id" => $iPo, "name" => $sPo);
	}


	//if ($MaxDefects == 0 && $Style > 0 && $TotalGmts > 0)
	$MaxDefects = 0;

	if ($Style > 0 && $TotalGmts > 0)
	{
		$iBrand = getDbValue("brand_id", "tbl_styles", "id='$Style'");
		$fAql   = getDbValue("aql", "tbl_brands", "id='$iBrand'");
		$fAql   = (($fAql == 0) ? 2.5 : $fAql);

		if (@isset($iAqlChart["{$TotalGmts}"]["{$fAql}"]))
			$MaxDefects = $iAqlChart["{$TotalGmts}"]["{$fAql}"];
	}


	// Defect Pictures
	@list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);


	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($AuditCode, 1)."_*.*");
   	$sPictures = @array_map("strtoupper", $sPictures);
   	$sPictures = @array_unique($sPictures);
	$sTemp     = array( );

	foreach ($sPictures as $sPicture)
		$sTemp[] = @basename($sPicture);

        $sPictures  = $sTemp;
	$sPicsDir   = (SITE_URL.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
	$sSpecsDir  = (SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/");
	$sQuondaDir = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
        
	$sPictures = $sTemp;
	$sPicsDir  = (SITE_URL.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/qmip/edit-qa-report.js.php?Id=<?= $Id ?>&ReportId=<?= $ReportId ?>&AuditDate=<?= $AuditDate ?>"></script>

  <script type="text/javascript" src="scripts/jquery.js"></script>
  <script type="text/javascript" src="scripts/jquery.tokeninput.js"></script>
  <link type="text/css" rel="stylesheet" href="css/jquery.tokeninput.css" />

  <script type="text/javascript">
  <!--
		jQuery.noConflict( );

		jQuery(document).ready(function($)
		{
<?
	if ($Step <= 1)
	{
?>
			$("#PO").tokenInput("ajax/qmip/get-pos-list.php?Vendor=<?= $Vendor ?>",
			{
			  queryParam         :  "Po",
			  minChars           :  3,
			  tokenLimit         :  50,
			  hintText           :  "Search the PO #",
			  noResultsText      :  "No matching PO found",
			  theme              :  "facebook",
			  preventDuplicates  :  true,
			  prePopulate        :  <?= @json_encode($sPos) ?>,
			  onAdd              :  function( ) {  updateStyles( );  },
			  onDelete           :  function( ) {  updateStyles( );  }
			});


			$(document).on("click", "img.deleteDefect", function( )
			{
				var iIndex = $(this).attr("rel");

				$("#DefectRecord" + iIndex).hide("blind");


				setTimeout(function( )
				{
					$("#DefectRecord" + iIndex).remove( );

					$("#QaDefects .defectRecords").each(function(iIndex)
					{
						$(this).attr("id", ("DefectRecord" + iIndex));

						$(this).find("input.defectId").attr("name", ("DefectId" + iIndex));
						$(this).find("input.defectId").attr("id", ("DefectId" + iIndex));

						$(this).find("td.serial").html(iIndex + 1);

						$(this).find("select.defectRoll").attr("name", ("Roll" + iIndex));
						$(this).find("select.defectRoll").attr("id", ("Roll" + iIndex));

						$(this).find("select.defectPanel").attr("name", ("Panel" + iIndex));
						$(this).find("select.defectPanel").attr("id", ("Panel" + iIndex));

						$(this).find("select.defectCode").attr("name", ("Code" + iIndex));
						$(this).find("select.defectCode").attr("id", ("Code" + iIndex));

						$(this).find("input.defectsCount").attr("name", ("Defects" + iIndex));
						$(this).find("input.defectsCount").attr("id", ("Defects" + iIndex));

						$(this).find("select.defectArea").attr("name", ("Area" + iIndex));
						$(this).find("select.defectArea").attr("id", ("Area" + iIndex));

						$(this).find("select.defectGrade").attr("name", ("Grade" + iIndex));
						$(this).find("select.defectGrade").attr("id", ("Grade" + iIndex));

						$(this).find("select.defectNature").attr("name", ("Nature" + iIndex));
						$(this).find("select.defectNature").attr("id", ("Nature" + iIndex));

						$(this).find("input.defectCap").attr("name", ("Cap" + iIndex));
						$(this).find("input.defectCap").attr("id", ("Cap" + iIndex));

						$(this).find("img.deleteDefect").attr("rel", iIndex);
					});


					$("#Count").val($("#QaDefects .defectRecords").length);
					$("#Sms").val("1");
				}, 500);
			});
<?
	}


	if ($Step <= 2)
	{
?>

			$("a.deletePic").click(function( )
			{
				var objLink = $(this);

				jQuery.post("ajax/qmip/delete-qa-image.php",
					{ File:$(this).attr("file"), AuditDate:$(this).attr("date") },

					function (sResponse)
					{
						if (sResponse == "DELETED")
							objLink.parent( ).remove( );

						else
							alert("An ERROR occured while Deleting the selected Image.");
					},

					"text");

			  	return false;
			});
<?
	}

	if ($Step == 0 || $Step == 3)
	{
?>

			$("a.deleteSpecs").click(function( )
			{
				var objLink = $(this);

				jQuery.post("ajax/qmip/delete-specs-sheet.php",
					{ Id:$(this).attr("rel"), Index:$(this).attr("index") },

					function (sResponse)
					{
						if (sResponse == "DELETED")
							objLink.parent( ).remove( );

						else
							alert("An ERROR occured while Deleting the Specs Sheet.");
					},

					"text");

			  	return false;
			});
<?
	}
?>
		});

<?
	if ($Step <= 1)
	{
?>
		function updateStyles( )
		{
			jQuery.post("ajax/quonda/get-styles-list.php",
				{ Pos:jQuery("#PO").val( ) },

				function (sResponse)
				{
					jQuery("#Style").html("");
					jQuery("#Style").get(0).options[0] = new Option("", "", false, false);


					if (sResponse != "")
					{
						var sOptions = sResponse.split("|-|");

						for (var i = 0; i < sOptions.length; i ++)
						{
							var sOption = sOptions[i].split("||");

							jQuery("#Style").get(0).options[(i + 1)] = new Option(sOption[1], sOption[0], false, false);
						}
					}

					jQuery("#Style").val("<?= $Style ?>");
				},

				"text");
		}
<?
	}
?>
  -->
  </script>
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
			    <h1>qa report entry form</h1>

<?
	if ($Step >= 1 && $Step <= 3)
	{
?>
			    <div class="tblSheet" style="margin-bottom:10px; padding:0px; background:#f3f3f3;">
			      <table border="0" cellpadding="10" cellspacing="0" width="100%">
			        <tr>
			          <td width="30%" style="font-size:16px;"<?= (($Step == 1) ? ' bgcolor="#d6d6d6"' : '') ?>><b>Step 1:</b> Audit Details</td>
			          <td width="30%" style="font-size:16px;"<?= (($Step == 2) ? ' bgcolor="#d6d6d6"' : '') ?>><b>Step 2:</b> Packing Images</td>
			          <td width="40%" style="font-size:16px;"<?= (($Step == 3) ? ' bgcolor="#d6d6d6"' : '') ?>><b>Step 3:</b> Lab Reports / Specs Sheets</td>
			        </tr>
			      </table>
			    </div>
<?
	}


	if ($Step <= 1)
	{
?>
			    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="qmip/save-qa-report.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="MAX_FILE_SIZE" value="26214400" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />
			    <input type="hidden" name="Report" id="Report" value="<?= $ReportId ?>" />
			    <input type="hidden" name="Sms" id="Sms" value="0" />
			    <input type="hidden" name="Step" id="Step" value="<?= $Step ?>" />

				<h2>Quality Inspection Report</h2>

<?
		if ($ReportId == 6)
			@include($sBaseDir."includes/quonda/edit-gf-report.php");

		else if ($ReportId == 7)
			@include($sBaseDir."includes/quonda/edit-ar-report.php");

		else if ($ReportId == 19)
			@include($sBaseDir."includes/quonda/edit-adidas-report.php");

		else if ($ReportId == 9)
			@include($sBaseDir."includes/quonda/edit-yarn-report.php");

		else if ($ReportId == 10)
			@include($sBaseDir."includes/quonda/edit-jako-report.php");

		else if ($ReportId == 11)
			@include($sBaseDir."includes/quonda/edit-ms-report.php");

		else if ($ReportId == 14)
			@include($sBaseDir."includes/quonda/edit-mgf-report.php");

		else if ($ReportId == 15)
			@include($sBaseDir."includes/quonda/edit-vendor-cutting-report.php");

		else if ($ReportId == 16 || $ReportId == 17)
			@include($sBaseDir."includes/quonda/edit-vendor-finishing-report.php");

		else if ($ReportId == 20 || $ReportId == 23)
			@include($sBaseDir."includes/quonda/edit-kik-report.php");

		else
			@include($sBaseDir."includes/quonda/edit-qa-report.php");
?>
				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
<?
		if ($Step == 1)
		{
?>
				  <input type="button" value="" class="btnCancel" title="Cancel" onclick="document.location='<?= $Referer ?>';" />
<?
		}

		else
		{
?>
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= $Referer ?>';" />
<?
		}

		if ($Step == 0 && ($ReportId == 6 || $ReportId == 7))
		{
?>
				  <input type="button" value="" class="btnExport" title="Export" onclick="document.location='<?= (SITE_URL."qmip/export-qa-report.php?Id=".$Id."&ReportId=".$ReportId) ?>';" />
<?
		}
?>
				</div>
			    </form>
<?
	}




	if ($Step == 0)
	{
?>
				<hr />
<?
	}




	if ($Step == 0 || $Step == 2)
	{
?>
			    <form name="frmPacking" id="frmPacking" method="post" enctype="multipart/form-data" action="qmip/save-qa-report-packing.php" class="frmOutline" onsubmit="$('BtnSavePacking').disabled=true;">
			    <input type="hidden" name="MAX_FILE_SIZE" value="26214400" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />
			    <input type="hidden" name="Sms" id="Sms" value="<?= $Sms ?>" />
			    <input type="hidden" name="Step" id="Step" value="<?= $Step ?>" />

				<h2>Packing Images</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="110">Image # 1</td>
					<td width="20" align="center">:</td>
					<td><input type="file" name="Packing1" value="" size="30" class="file" /></td>
				  </tr>

				  <tr>
					<td>Image # 2</td>
					<td align="center">:</td>
					<td><input type="file" name="Packing2" value="" size="30" class="file" /></td>
				  </tr>

				  <tr>
					<td>Image # 3</td>
					<td align="center">:</td>
					<td><input type="file" name="Packing3" value="" size="30" class="file" /></td>
				  </tr>

				  <tr>
					<td>Image # 4</td>
					<td align="center">:</td>
					<td><input type="file" name="Packing4" value="" size="30" class="file" /></td>
				  </tr>

				  <tr>
					<td>Image # 5</td>
					<td align="center">:</td>
					<td><input type="file" name="Packing5" value="" size="30" class="file" /></td>
				  </tr>
				</table>
<?
		$sPacking = array( );

		foreach ($sPictures as $sPicture)
		{
			if (@stripos($sPicture, "_PACK_") !== FALSE || @stripos($sPicture, "_001_") !== FALSE)
				$sPacking[] = $sPicture;
		}


		if (count($sPacking) > 0)
		{
?>
				<ul style="margin:20px 0px 0px 20px;">
<?
			foreach ($sPacking as $sPicture)
			{
?>
				  <li><a href="<?= $sPicsDir ?><?= $sPicture ?>" class="lightview"><?= str_ireplace(array("{$AuditCode}_PACK_", "{$AuditCode}_001_"), "", $sPicture) ?></a> &nbsp; - &nbsp; <a href="./" file="<?= $sPicture ?>" date="<?= $AuditDate ?>" class="deletePic"><b>x</b></a></li>
<?
			}
?>
				</ul>
<?
		}
?>
				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSavePacking" value="" class="btnSave" title="Save"  onclick="return validatePackingForm( );" />
<?
		if ($Step == 2)
		{
?>
				  <input type="button" value="" class="btnSkip" title="Skip" onclick="document.location='qmip/edit-qa-report.php?Id=<?= $Id ?>&Step=3&Sms=<?= $Sms ?>&Referer=<?= $Referer ?>';" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='qmip/edit-qa-report.php?Id=<?= $Id ?>&Step=1&Sms=<?= $Sms ?>&Referer=<?= $Referer ?>';" />
<?
		}

		else
		{
?>
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= $Referer ?>';" />
<?
		}
?>
				</div>
			    </form>
<?
	}


	if ($Step == 0)
	{
?>
				<hr />
<?
	}


	if ($Step == 0 || $Step == 3)
	{
?>

			    <form name="frmSpecs" id="frmSpecs" method="post" enctype="multipart/form-data" action="qmip/save-qa-report-specs.php" class="frmOutline" onsubmit="$('BtnSaveSpecs').disabled=true;">
			    <input type="hidden" name="MAX_FILE_SIZE" value="26214400" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Sms" id="Sms" value="<?= $Sms ?>" />
			    <input type="hidden" name="Step" id="Step" value="<?= $Step ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />


				<h2>Specs Sheets / Lab Reports</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="110">Specs Sheet # 1</td>
					<td width="20" align="center">:</td>
					<td><input type="file" name="SpecsSheet1" value="" size="30" class="file" /> &nbsp; <span><? if ($SpecsSheet1 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$SpecsSheet1)) { ?>( <a href="<?= SPECS_SHEETS_DIR.$SpecsSheet1 ?>" class="lightview">view</a> &nbsp;-&nbsp; <a href="./" class="deleteSpecs" rel="<?= $Id ?>" index="1"><b>x</b></a> )<? } ?></span></td>
				  </tr>

				  <tr>
					<td>Specs Sheet # 2</td>
					<td align="center">:</td>
					<td><input type="file" name="SpecsSheet2" value="" size="30" class="file" /> &nbsp; <span><? if ($SpecsSheet2 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$SpecsSheet2)) { ?>( <a href="<?= SPECS_SHEETS_DIR.$SpecsSheet2 ?>" class="lightview">view</a> &nbsp;-&nbsp; <a href="./" class="deleteSpecs" rel="<?= $Id ?>" index="2"><b>x</b></a> )<? } ?></span></td>
				  </tr>

				  <tr>
					<td>Specs Sheet # 3</td>
					<td align="center">:</td>
					<td><input type="file" name="SpecsSheet3" value="" size="30" class="file" /> &nbsp; <span><? if ($SpecsSheet3 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$SpecsSheet3)) { ?>( <a href="<?= SPECS_SHEETS_DIR.$SpecsSheet3 ?>" class="lightview">view</a> &nbsp;-&nbsp; <a href="./" class="deleteSpecs" rel="<?= $Id ?>" index="3"><b>x</b></a> )<? } ?></span></td>
				  </tr>

				  <tr>
					<td>Specs Sheet # 4</td>
					<td align="center">:</td>
					<td><input type="file" name="SpecsSheet4" value="" size="30" class="file" /> &nbsp; <span><? if ($SpecsSheet4 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$SpecsSheet4)) { ?>( <a href="<?= SPECS_SHEETS_DIR.$SpecsSheet4 ?>" class="lightview">view</a> &nbsp;-&nbsp; <a href="./" class="deleteSpecs" rel="<?= $Id ?>" index="4"><b>x</b></a> )<? } ?></span></td>
				  </tr>

				  <tr>
					<td>Specs Sheet # 5</td>
					<td align="center">:</td>
					<td><input type="file" name="SpecsSheet5" value="" size="30" class="file" /> &nbsp; <span><? if ($SpecsSheet5 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$SpecsSheet5)) { ?>( <a href="<?= SPECS_SHEETS_DIR.$SpecsSheet5 ?>" class="lightview">view</a> &nbsp;-&nbsp; <a href="./" class="deleteSpecs" rel="<?= $Id ?>" index="5"><b>x</b></a> )<? } ?></span></td>
				  </tr>

				  <tr>
					<td>Specs Sheet # 6</td>
					<td align="center">:</td>
					<td><input type="file" name="SpecsSheet6" value="" size="30" class="file" /> &nbsp; <span><? if ($SpecsSheet6 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$SpecsSheet6)) { ?>( <a href="<?= SPECS_SHEETS_DIR.$SpecsSheet6 ?>" class="lightview">view</a> &nbsp;-&nbsp; <a href="./" class="deleteSpecs" rel="<?= $Id ?>" index="6"><b>x</b></a> )<? } ?></span></td>
				  </tr>

				  <tr>
					<td>Specs Sheet # 7</td>
					<td align="center">:</td>
					<td><input type="file" name="SpecsSheet7" value="" size="30" class="file" /> &nbsp; <span><? if ($SpecsSheet7 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$SpecsSheet7)) { ?>( <a href="<?= SPECS_SHEETS_DIR.$SpecsSheet7 ?>" class="lightview">view</a> &nbsp;-&nbsp; <a href="./" class="deleteSpecs" rel="<?= $Id ?>" index="7"><b>x</b></a> )<? } ?></span></td>
				  </tr>

				  <tr>
					<td>Specs Sheet # 8</td>
					<td align="center">:</td>
					<td><input type="file" name="SpecsSheet8" value="" size="30" class="file" /> &nbsp; <span><? if ($SpecsSheet8 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$SpecsSheet8)) { ?>( <a href="<?= SPECS_SHEETS_DIR.$SpecsSheet8 ?>" class="lightview">view</a> &nbsp;-&nbsp; <a href="./" class="deleteSpecs" rel="<?= $Id ?>" index="8"><b>x</b></a> )<? } ?></span></td>
				  </tr>

				  <tr>
					<td>Specs Sheet # 9</td>
					<td align="center">:</td>
					<td><input type="file" name="SpecsSheet9" value="" size="30" class="file" /> &nbsp; <span><? if ($SpecsSheet9 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$SpecsSheet9)) { ?>( <a href="<?= SPECS_SHEETS_DIR.$SpecsSheet9 ?>" class="lightview">view</a> &nbsp;-&nbsp; <a href="./" class="deleteSpecs" rel="<?= $Id ?>" index="9"><b>x</b></a> )<? } ?></span></td>
				  </tr>

				  <tr>
					<td>Specs Sheet # 10</td>
					<td align="center">:</td>
					<td><input type="file" name="SpecsSheet10" value="" size="30" class="file" /> &nbsp; <span><? if ($SpecsSheet10 != "" && @file_exists($sBaseDir.SPECS_SHEETS_DIR.$SpecsSheet10)) { ?>( <a href="<?= SPECS_SHEETS_DIR.$SpecsSheet10 ?>" class="lightview">view</a> &nbsp;-&nbsp; <a href="./" class="deleteSpecs" rel="<?= $Id ?>" index="10"><b>x</b></a> )<? } ?></span></td>
				  </tr>
				</table>

				<input type="hidden" name="OldSpecsSheet1" value="<?= $SpecsSheet1 ?>" />
				<input type="hidden" name="OldSpecsSheet2" value="<?= $SpecsSheet2 ?>" />
				<input type="hidden" name="OldSpecsSheet3" value="<?= $SpecsSheet3 ?>" />
				<input type="hidden" name="OldSpecsSheet4" value="<?= $SpecsSheet4 ?>" />
				<input type="hidden" name="OldSpecsSheet5" value="<?= $SpecsSheet5 ?>" />
				<input type="hidden" name="OldSpecsSheet6" value="<?= $SpecsSheet6 ?>" />
				<input type="hidden" name="OldSpecsSheet7" value="<?= $SpecsSheet7 ?>" />
				<input type="hidden" name="OldSpecsSheet8" value="<?= $SpecsSheet8 ?>" />
				<input type="hidden" name="OldSpecsSheet9" value="<?= $SpecsSheet9 ?>" />
				<input type="hidden" name="OldSpecsSheet10" value="<?= $SpecsSheet10 ?>" />

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSaveSpecs" value="" class="btnSave" title="Save"  onclick="return validateSpecsForm( );" />
<?
		if ($Step == 3)
		{
?>
				  <input type="button" value="" class="btnSkip" title="Skip" onclick="document.location='qmip/send-qa-report-notifications.php?Id=<?= $Id ?>&Referer=<?= $Referer ?>';" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='qmip/edit-qa-report.php?Id=<?= $Id ?>&Step=2&Sms=<?= $Sms ?>&Referer=<?= $Referer ?>';" />
<?
		}

		else
		{
?>
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= $Referer ?>';" />
<?
		}
?>
				</div>
			    </form>
<?
	}
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
	$objDb3->close( );
	$objDb4->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>