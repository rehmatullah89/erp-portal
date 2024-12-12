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
	@header("Content-type: text/html; charset=utf-8");

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
		$AuditQuantity         = $objDb->getField(0, "audit_quantity");
		$Group                 = $objDb->getField(0, "group_id");
		$ReportId              = $objDb->getField(0, "report_id");
		$Vendor                = $objDb->getField(0, "vendor_id");
                $AuditTypeId           = $objDb->getField(0, "audit_type_id");
		$PO                    = $objDb->getField(0, "po_id");
		$AdditionalPos         = $objDb->getField(0, "additional_pos");
		$Brand                 = $objDb->getField(0, "brand_id");
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
		$ShipmentDate          = $objDb->getField(0, "shipment_date");
		$Knitted               = $objDb->getField(0, "knitted");
		$Dyed                  = $objDb->getField(0, "dyed");
		$Cutting               = $objDb->getField(0, "cutting");
		$Sewing                = $objDb->getField(0, "sewing");
		$Finishing             = $objDb->getField(0, "finishing");
		$Packing               = $objDb->getField(0, "packing");
		$Washing               = $objDb->getField(0, "washing");
		$Pressing              = $objDb->getField(0, "pressing");
		$FinalAuditDate        = $objDb->getField(0, "final_audit_date");
		$ReScreenQty           = $objDb->getField(0, "re_screen_qty");
                $InspectedCartons      = $objDb->getField(0, "inspected_cartons");
		$CartonsRequired       = $objDb->getField(0, "cartons_required");
		$CartonsShipped        = $objDb->getField(0, "cartons_shipped");
		$ApprovedSample        = $objDb->getField(0, "approved_sample");
		$ShippingMark          = $objDb->getField(0, "shipping_mark");
		$PackingCheck	       = $objDb->getField(0, "packing_check");
                $CheckLevel	       = $objDb->getField(0, "check_level");
		$ApprovedTrims         = $objDb->getField(0, "approved_trims");
		$ShadeBand   	       = $objDb->getField(0, "shade_band");
		$EmbApproval 	       = $objDb->getField(0, "emb_approval");
		$GsmWeight   	       = $objDb->getField(0, "gsm_weight");
		$Comments              = $objDb->getField(0, "qa_comments");
		$Maker                 = $objDb->getField(0, 'maker');
		$Published             = $objDb->getField(0, 'published');
		$sAuditor              = $objDb->getField(0, "_Auditor");
		$sVendor               = $objDb->getField(0, "_Vendor");
                $sHohOrderNo           = $objDb->getField(0, "hoh_order_no");

		
		@list($Length, $Width, $Height, $Unit) = @explode("x", $objDb->getField(0, "carton_size"));


		if ($PercentDecfective == 0)
			$PercentDecfective = @round((($CartonsRejected / $TotalCartons) * 100), 2);
		

		$SpecsSheets = array( );
		
		for ($i = 1; $i <= 10; $i ++)
			$SpecsSheets[] = $objDb->getField(0, "specs_sheet_{$i}");
	}

	else
		redirect($Referer, "INVALID_AUDIT_CODE");
	
	
	if (($ReportId == 14 || $ReportId == 34) && $AuditResult == "" && $Comments == "")
		$Published = "N";
	
	if (($ReportId == 14 || $ReportId == 34) && IO::strValue("Options") == @md5($Id))
		$Published = "N";
	
	if (($ReportId == 14 || $ReportId == 34) && $Published == "Y" && $_SESSION["UserId"] > 1)
		redirect((SITE_URL."quonda/qa-reports.php"), "ACCESS_DENIED");


	if ($Unit == "")
		$Unit = "in";


	$iSizes           = @explode(",", $Sizes);
	$sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage", "code='$AuditStage' OR FIND_IN_SET(code, '$sAuditStages')");
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


	$MaxDefects = 0;

	if ($Style > 0 && $TotalGmts > 0)
	{
		$iBrand = getDbValue("brand_id", "tbl_styles", "id='$Style'");
		$fAql   = getDbValue("aql", "tbl_brands", "id='$iBrand'");
		$fAql   = (($fAql == 0) ? 2.5 : $fAql);

		if (@isset($iAqlChart["{$TotalGmts}"]["{$fAql}"]))
			$MaxDefects = $iAqlChart["{$TotalGmts}"]["{$fAql}"];
	}


	// Import Pictures
	@list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);

	$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($AuditCode, 1)."_*.*");
   	$sPictures = @array_map("strtoupper", $sPictures);
   	$sPictures = @array_unique($sPictures);
	
	$sTemp = array( );
	
	foreach ($sPictures as $sPicture)
		$sTemp[] = @basename($sPicture);

	$sPictures  = $sTemp;
	$sPicsDir   = (SITE_URL.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
	$sSpecsDir  = (SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/");
	$sQuondaDir = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
	

	$sDefectImages  = array();
	$sPackingImages = array();
	$sMiscImages    = array();
	$sExtraImages   = array();

	foreach ($sPictures as $sPicture)
	{
		if (@strpos($sPicture, "_PACK") !== FALSE || @strpos($sPicture, "_001_") !== FALSE)
			$sPackingImages[] = $sPicture;

		else if (@strpos($sPicture, "_MISC") !== FALSE)
			$sMiscImages[] = $sPicture;

		else
			$sDefectImages[] = $sPicture;
	}
		
	
	if (!@in_array($ReportId, array(6,26,30)) && !empty($sPictures)) //except GF,TNC & TOWEL
	{
		$sExistingImages     = array();
		$sExistingDefectIds  = "0";
		$sExistingImagesList = getList("tbl_qa_report_defects", "id", "picture", "audit_id='$Id' AND (picture IS NOT NULL OR picture != '')");         
	   
		foreach($sExistingImagesList as $iDefect => $sImage)
		{
			if (@file_exists($sQuondaDir.$sImage))
			{
				$sExistingImages[]   = strtoupper($sImage);
				$sExistingDefectIds .= ",{$iDefect}";
			}
		}
			
			
		$sRemainingImages = @array_diff($sDefectImages, $sExistingImages);
		
		
		$bFlag = $objDb->execute("BEGIN");
		
		foreach($sRemainingImages as $sPicture)
		{
			$iDefectImageParts = @explode("_", $sPicture);
			$sDefectCode       = @$iDefectImageParts[1];
			$iDefectArea       = @$iDefectImageParts[2];
			$iDefectCode       = getDbValue("id", "tbl_defect_codes", "report_id='$ReportId' AND code='$sDefectCode'");
			
			
			$iDefectId = getDbValue("id", "tbl_qa_report_defects", "(picture='' OR ISNULL(picture)) AND audit_id='$Id' AND code_id='$iDefectCode' AND area_id='$iDefectArea' AND id NOT IN ($sExistingDefectIds)");

			if ($iDefectId > 0)
			{                        
				$sSQL  = "UPDATE tbl_qa_report_defects SET picture='$sPicture' WHERE id='$iDefectId'";
				$bFlag = $objDb->execute($sSQL);

				if($bFlag == false)
					break;
			}
			
			else
				$sExtraImages[] = $sPicture;
		}

		
		if ($bFlag == true)
		{
			foreach($sPackingImages as $sPicture)
			{
				if (getDbValue("count(1)", "tbl_qa_report_images", "audit_id='$Id' AND image LIKE '$sPicture' AND `type`='P'") == 0)
				{
					$iImageId = getNextId("tbl_qa_report_images");
					
					$sSQL  = "INSERT INTO tbl_qa_report_images SET id='$iImageId', audit_id='$Id', image='$sPicture', `type`='P'";
					$bFlag = $objDb->execute($sSQL);     
					
					if ($bFlag == false)
						break;
				}
			}
		}
		
			
		if ($bFlag == true)
		{
			foreach($sMiscImages as $sPicture)
			{
				if (getDbValue("count(1)", "tbl_qa_report_images", "audit_id='$Id' AND image LIKE '$sPicture' AND `type`='M'") == 0)
				{
					$iImageId = getNextId("tbl_qa_report_images");
					
					$sSQL  = "INSERT INTO tbl_qa_report_images SET id='$iImageId', audit_id='$Id', image='$sPicture', `type`='M'";
					$bFlag = $objDb->execute($sSQL);                        
					
					if ($bFlag == false)
						break;
				}
			}
		
		}

		
		///Extra images
		if ($bFlag == true)
		{
			foreach ($sExtraImages as $sPicture)
			{
				$iDefectId         = 0;
				$iDefectImageParts = explode("_", $sPicture);
				$sDefectCode       = @$iDefectImageParts[1];
				$iDefectArea       = @$iDefectImageParts[2];
				$iDefectCode       = getDbValue("id", "tbl_defect_codes", "report_id='$Id' AND code='$sDefectCode'");
				
				if ($iDefectArea != "" && @is_numeric($iDefectArea))
					$iDefectId = getDbValue("id", "tbl_qa_report_defects", "(picture IS NULL OR picture='') AND audit_id='$Id' AND code_id='$iDefectCode' AND (area_id='$iDefectArea' OR area_id='0')");
				
				if ($iDefectId > 0)
				{                        
					$sSQL  = "UPDATE tbl_qa_report_defects SET picture='$sPicture' WHERE id='$iDefectId'";
					$bFlag = $objDb->execute($sSQL);

					if ($bFlag == false)
						break;
				}
				
				else
				{				
					$iImageId = getNextId("tbl_qa_report_images");
					$NewName  = str_replace($AuditCode, "{$AuditCode}_MISC_", $sPicture);
					$NewName  = str_replace(".JPG", "_{$iImageId}.JPG", $NewName);
					$NewName  = str_replace(".JPEG", "_{$iImageId}.JPEG", $NewName);
					
					if (@rename(($sQuondaDir.$sPicture), ($sQuondaDir.$NewName)))
					{
						$sSQL  = "INSERT INTO tbl_qa_report_images SET id='$iImageId', audit_id='$Id', image='$NewName', `type`='M'";
						$bFlag = $objDb->execute($sSQL);                        

						if ($bFlag == false)
							break;
					}                            
				}                        
			}                            
		}
			

		if ($bFlag == true)
			$objDb->execute("COMMIT");

		else
			$objDb->execute("ROLLBACK");
	}
	
	
	else if(@in_array($ReportId, array(6,26,30)) && !empty($sPictures)) //Including GF,TNC & TOWEL
	{
		$bFlag = $objDb->execute("BEGIN");

		if ($bFlag == true)
		{
			foreach($sPackingImages as $sPicture)
			{
				if (getDbValue("count(1)", "tbl_qa_report_images", "audit_id='$Id' AND image LIKE '$sPicture' AND `type`='P'") == 0)
				{
					$iImageId = getNextId("tbl_qa_report_images");
					
					$sSQL  = "INSERT INTO tbl_qa_report_images SET id='$iImageId', audit_id='$Id', image='$sPicture', `type`='P'";
					$bFlag = $objDb->execute($sSQL);     
					
					if ($bFlag == false)
						break;
				}                        
			}
		}

		
		if ($bFlag == true)
		{
			foreach($sMiscImages as $sPicture)
			{
				if (getDbValue("count(1)", "tbl_qa_report_images", "audit_id='$Id' AND image LIKE '$sPicture' AND `type`='M'") == 0)
				{
					$iImageId = getNextId("tbl_qa_report_images");
					
					$sSQL  = "INSERT INTO tbl_qa_report_images SET id='$iImageId', audit_id='$Id', image='$sPicture', `type`='M'";
					$bFlag = $objDb->execute($sSQL);                        
					
					if ($bFlag == false)
						break;
				}
			}		
		}
		
		
		if ($bFlag == true)
			$objDb->execute("COMMIT");
	
		else
			$objDb->execute("ROLLBACK");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/edit-qa-report.js.php?Id=<?= $Id ?>&ReportId=<?= $ReportId ?>&AuditDate=<?= $AuditDate ?>&Colors=<?=$Colors?>"></script>

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
			$("#PO").tokenInput("ajax/quonda/get-pos-list.php?Vendor=<?= $Vendor ?>",
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

			
				if ($("#DefectId" + iIndex).val( ) != "")
				{
					jQuery.post("ajax/quonda/delete-qa-defect.php",
						{ AuditDate:"<?= $AuditDate ?>", AuditId:"<?= $Id ?>", DefectId:$("#DefectId" + iIndex).val( ) },

						function (sResponse)
						{
							
						},

						"text");				
				}
				
				
				$("#DefectRecord" + iIndex).hide("blind");
				
				setTimeout(function( )
				{
					$("#DefectRecord" + iIndex).remove( );

					$("#QaDefects .defectRecords").each(function(iIndex)
					{
						var FIR = '<?= $ReportId?>';
						
						if(FIR != '26')
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
						}
					});
									
								   

				$("#Count").val($("#QaDefects .defectRecords").length);
				$("#Sms").val("1");
			}, 1000);				
			});
<?
	}


	if ($Step <= 2)
	{
?>

			$("a.deletePic").click(function( )
			{
				var objLink = $(this);

				jQuery.post("ajax/quonda/delete-qa-image.php",
					{ File:$(this).attr("file"), AuditDate:$(this).attr("date"), AuditId:$(this).attr("audit_id"), ImageId:$(this).attr("image_id") },

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

				jQuery.post("ajax/quonda/delete-specs-sheet.php",
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
  
  <style>  
    #Mytable tr:nth-child(even){
        background-color: #f2f2f2
    }
    
    #Mytable2 {
        font-size: 9px;
    }
  </style>    
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
			    <h1><img src="images/h1/quonda/qa-report-entry-form.jpg" width="308" height="23" alt="" title="" style="margin:9px 0px 8px 0px;" /></h1>

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
			    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="quonda/save-qa-report.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
			    <input type="hidden" name="Id" id="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />
			    <input type="hidden" name="Report" id="Report" value="<?= $ReportId ?>" />
			    <input type="hidden" name="Sms" id="Sms" value="0" />
			    <input type="hidden" name="Step" id="Step" value="<?= $Step ?>" />
				<input type="hidden" name="Published" id="Published" value="<?= $Published ?>" />

				<h2>Quality Inspection Report</h2>

<?
		if ($ReportId == 6)
			@include($sBaseDir."includes/quonda/edit-gf-report.php");

		else if ($ReportId == 7)
			@include($sBaseDir."includes/quonda/edit-ar-report.php");

		else if ($ReportId == 9)
			@include($sBaseDir."includes/quonda/edit-yarn-report.php");

		else if ($ReportId == 10)
			@include($sBaseDir."includes/quonda/edit-jako-report.php");

		else if ($ReportId == 11)
			@include($sBaseDir."includes/quonda/edit-ms-report.php");

		else if ($ReportId == 14 || $ReportId == 34)
			@include($sBaseDir."includes/quonda/edit-mgf-report.php");

		else if ($ReportId == 15)
			@include($sBaseDir."includes/quonda/edit-vendor-cutting-report.php");

		else if ($ReportId == 16 || $ReportId == 17)
			@include($sBaseDir."includes/quonda/edit-vendor-finishing-report.php");

		else if ($ReportId == 19)
			@include($sBaseDir."includes/quonda/edit-adidas-report.php");
                
		else if ($ReportId == 20 || $ReportId == 23)
			@include($sBaseDir."includes/quonda/edit-kik-report.php");
	
		else if ($ReportId == 25 && $AuditStage != 'F')
			@include($sBaseDir."includes/quonda/edit-inline-billabong-report.php");

		else if ($ReportId == 25 && $AuditStage == 'F')
			@include($sBaseDir."includes/quonda/edit-final-billabong-report.php");

		else if ($ReportId == 26)
			@include($sBaseDir."includes/quonda/edit-tnc-report.php");

		else if ($ReportId == 28)
			@include($sBaseDir."includes/quonda/edit-controlist-report.php");
		
		 else if ($ReportId == 29)
			@include($sBaseDir."includes/quonda/edit-leverstyle-report.php");
		 
		else if ($ReportId == 30)
			@include($sBaseDir."includes/quonda/edit-towel-report.php");

		else if ($ReportId == 31)
			@include($sBaseDir."includes/quonda/edit-hybrid-apparel-report.php");

		else if ($ReportId == 32 /*|| $ReportId == 39*/)
			@include($sBaseDir."includes/quonda/edit-arcadia-report.php");

		else if ($ReportId == 33)
			@include($sBaseDir."includes/quonda/edit-gms-report.php");
		
		else if ($ReportId == 35)
			@include($sBaseDir."includes/quonda/edit-timezone-report.php");
		
		else if ($ReportId == 36)
			@include($sBaseDir."includes/quonda/edit-hybrid-link-report.php");
		
		else if ($ReportId == 37)
			@include($sBaseDir."includes/quonda/edit-armedangels-report.php");

		else if ($ReportId == 38)
			@include($sBaseDir."includes/quonda/edit-tmclothing-report.php");
                
                else if ($ReportId == 39)
			@include($sBaseDir."includes/quonda/edit-hohenstein-report.php");
              
                else if ($ReportId == 40)
			@include($sBaseDir."includes/quonda/edit-ppmeeting-report.php");
                
                else if (in_array($ReportId, array(41,42)))
			@include($sBaseDir."includes/quonda/edit-qmip-report.php");
                
                else if (in_array($ReportId, array(44,45)))
			@include($sBaseDir."includes/quonda/edit-levis-report.php");

                else if (@in_array($ReportId, array(46,47)))
			@include($sBaseDir."includes/quonda/edit-jcrew-report.php");

                else if (@in_array($ReportId, array(48)))
			@include($sBaseDir."includes/quonda/edit-triburg-report.php");
        
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
				  <input type="button" value="" class="btnExport" title="Export" onclick="document.location='<?= (SITE_URL."quonda/export-qa-report.php?Id=".$Id."&ReportId=".$ReportId) ?>';" />
<?
		}
?>
				</div>
			    </form>
<?
	}


if ($ReportId != 39)
{

	if ($Step == 0)
	{
?>
				<hr />
<?
	}

	if ($Step == 0 || $Step == 2)
	{
?>
			    <form name="frmPacking" id="frmPacking" method="post" enctype="multipart/form-data" action="quonda/save-qa-report-packing.php" class="frmOutline" onsubmit="$('BtnSavePacking').disabled=true;">
			    <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />
			    <input type="hidden" name="Sms" id="Sms" value="<?= $Sms ?>" />
			    <input type="hidden" name="Step" id="Step" value="<?= $Step ?>" />
				<input type="hidden" name="Published" id="Published" value="<?= $Published ?>" />

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
		$sPackingImages = getList("tbl_qa_report_images", "id", "image", "audit_id='$Id' AND `type`='P'");
			
		if (count($sPackingImages) > 0)
		{
?>
				<ul style="margin:20px 0px 0px 20px;">
<?
			foreach ($sPackingImages as $iPicture => $sPicture)
			{
?>
				  <li><a href="<?= $sPicsDir ?><?= $sPicture ?>" class="lightview"><?= str_ireplace(array("{$AuditCode}_PACK_", "{$AuditCode}_001_"), "", $sPicture) ?></a> &nbsp; - &nbsp; <a href="./" file="<?= $sPicture ?>" date="<?= $AuditDate ?>" audit_id="<?=$Id?>" image_id="<?=$iPicture?>" class="deletePic"><b>x</b></a></li>
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
				  <input type="button" value="" class="btnSkip" title="Skip" onclick="document.location='quonda/edit-qa-report.php?Id=<?= $Id ?>&Step=3&Sms=<?= $Sms ?>&Referer=<?= $Referer ?><?= (($Published == "N") ? ("&Options=".@md5($Id)) : "") ?>';" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='quonda/edit-qa-report.php?Id=<?= $Id ?>&Step=1&Sms=<?= $Sms ?>&Referer=<?= $Referer ?><?= (($Published == "N") ? ("&Options=".@md5($Id)) : "") ?>';" />
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
}

if ($ReportId != 39)
{
	if ($Step == 0)
	{
?>
				<hr />
<?
	}
        
	if ($Step == 0 || $Step == 3)
	{
?>

			    <form name="frmSpecs" id="frmSpecs" method="post" enctype="multipart/form-data" action="quonda/save-qa-report-specs.php" class="frmOutline" onsubmit="$('BtnSaveSpecs').disabled=true;">
			    <input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Sms" id="Sms" value="<?= $Sms ?>" />
			    <input type="hidden" name="Step" id="Step" value="<?= $Step ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />


				<h2>Specs Sheets / Lab Reports</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
<?
		for ($i = 1; $i <= 10; $i ++)
		{
?>
				  <tr>
					<td width="110">Specs Sheet # <?= $i ?></td>
					<td width="20" align="center">:</td>

					<td>
					  <input type="file" name="SpecsSheet<?= $i ?>" value="" size="30" class="file" /> 
					  &nbsp; 
					  <span>
<?
			$SpecsSheet = $SpecsSheets[$i - 1];

			if ($SpecsSheet != "")
			{
				if (@file_exists($sBaseDir.SPECS_SHEETS_DIR.$SpecsSheet))
				{
?>
					  ( <a href="<?= (SPECS_SHEETS_DIR.$SpecsSheet) ?>" class="lightview">view</a> &nbsp;-&nbsp; <a href="./" class="deleteSpecs" rel="<?= $Id ?>" index="<?= $i ?>"><b>x</b></a> )
<?
				}
				
				else if (@file_exists($sBaseDir.$sSpecsDir.$SpecsSheet))
				{
?>
					  ( <a href="<?= ($sSpecsDir.$SpecsSheet) ?>" class="lightview">view</a> &nbsp;-&nbsp; <a href="./" class="deleteSpecs" rel="<?= $Id ?>" index="<?= $i ?>"><b>x</b></a> )
<?
				}
			}
?>
					  </span>
					</td>
				  </tr>
				  
				<input type="hidden" name="OldSpecsSheet<?= $i ?>" value="<?= $SpecsSheet ?>" />
<?
		}
?>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSaveSpecs" value="" class="btnSave" title="Save"  onclick="return validateSpecsForm( );" />
<?
		if ($Step == 3)
		{
?>
				  <input type="button" value="" class="btnSkip" title="Skip" onclick="document.location='quonda/send-qa-report-notifications.php?Id=<?= $Id ?>&Referer=<?= $Referer ?>';" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='quonda/edit-qa-report.php?Id=<?= $Id ?>&Step=2&Sms=<?= $Sms ?>&Referer=<?= $Referer ?><?= (($Published == "N") ? ("&Options=".@md5($Id)) : "") ?>';" />
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