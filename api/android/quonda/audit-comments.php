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

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$User      = IO::strValue("User");
	$AuditCode = IO::strValue("AuditCode");


	$aResponse           = array( );
	$aResponse['Status'] = "ERROR";


	if ($User == "" || $AuditCode == "" || $AuditCode{0} != "S")
		$aResponse["Message"] = "Invalid Request";

	else
	{
		$sSQL = "SELECT id, name, status FROM tbl_users WHERE MD5(id)='$User'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 0)
			$aResponse["Message"] = "Invalid User";

		else if ($objDb->getField(0, "status") != "A")
			$aResponse["Message"] = "User Account is Disabled";

		else
		{
			$iUser = $objDb->getField(0, "id");
			$sName = $objDb->getField(0, "name");


			$sSQL = "SELECT * FROM tbl_qa_reports WHERE audit_code='$AuditCode'";
			$objDb->query($sSQL);

			$iAuditId               = $objDb->getField(0, "id");
			$iReportId              = $objDb->getField(0, "report_id");
			$iBrandId               = $objDb->getField(0, "brand_id");
			$iPoId                  = $objDb->getField(0, 'po_id');
			$sAdditionalPos         = $objDb->getField(0, 'additional_pos');			
			$sAuditResult           = $objDb->getField(0, "audit_result");
			$sDescription           = $objDb->getField(0, "description");
			$sBundle                = $objDb->getField(0, "bundle");
			$sBatchSize             = $objDb->getField(0, "batch_size");
			$fPackedPercent         = $objDb->getField(0, "packed_percent");
			$sDyeLotNo              = $objDb->getField(0, "dye_lot_no");
			$sInspectionType        = $objDb->getField(0, "inspection_type");
			$iGmtsDefective         = $objDb->getField(0, "defective_gmts");
			$iMaxDefects            = $objDb->getField(0, "max_defects");
			$iTotalCartons          = $objDb->getField(0, "total_cartons");
			$iCartonsRejected       = $objDb->getField(0, "rejected_cartons");
			$fPercentDecfective     = $objDb->getField(0, "defective_percent");
			$fStandard              = $objDb->getField(0, "standard");
			$sCartonSize  	        = $objDb->getField(0, "carton_size");
			$fCartonsDhu            = $objDb->getField(0, "cartons_dhu");
			$iShipQty               = $objDb->getField(0, "ship_qty");
			$fKnitted               = $objDb->getField(0, "knitted");
			$fDyed                  = $objDb->getField(0, "dyed");
			$iCutting               = $objDb->getField(0, "cutting");
			$iSewing                = $objDb->getField(0, "sewing");
			$iFinishing             = $objDb->getField(0, "finishing");
			$iPacking               = $objDb->getField(0, "packing");
			$sFinalAuditDate        = $objDb->getField(0, "final_audit_date");
			$iReScreenQty           = $objDb->getField(0, "re_screen_qty");
			$fCartonsRequired       = $objDb->getField(0, "cartons_required");
			$fCartonsShipped        = $objDb->getField(0, "cartons_shipped");
			$sApprovedSample        = $objDb->getField(0, "approved_sample");
			$sShippingMark          = $objDb->getField(0, "shipping_mark");
			$sPackingCheck	        = $objDb->getField(0, "packing_check");
			$sApprovedTrims         = $objDb->getField(0, "approved_trims");
			$sShadeBand   	        = $objDb->getField(0, "shade_band");
			$sEmbApproval 	        = $objDb->getField(0, "emb_approval");
			$sGsmWeight   	        = $objDb->getField(0, "gsm_weight");
			$sUnit        	        = $objDb->getField(0, "unit");
			$sMaker                 = $objDb->getField(0, 'maker');
			$sGacDate               = $objDb->getField(0, "gac_date");
			$sComments              = $objDb->getField(0, "qa_comments");

	
			$sGacDates = getList("tbl_po_colors", "DISTINCT(etd_required)", "DATE_FORMAT(etd_required, '%d-%b-%Y')", "po_id='$iPoId' OR ('$sAdditionalPos'!='' AND FIND_IN_SET(po_id, '$sAdditionalPos'))");
			
	
			$sAudit = array("AuditResult"       => $sAuditResult,
						    "BrandId"           => $iBrandId,
							"Description"       => $sDescription,
						    "Bundle"            => $sBundle,
						    "BatchSize"         => $sBatchSize,
						    "PackedPercent"     => $fPackedPercent,
						    "DyeLotNo"          => $sDyeLotNo,
						    "InspectionType"    => $sInspectionType,
						    "GmtsDefective"     => $iGmtsDefective,
						    "MaxDefects"        => $iMaxDefects,
						    "TotalCartons"      => $iTotalCartons,
						    "CartonsRejected"   => $iCartonsRejected,
						    "PercentDecfective" => $fPercentDecfective,
						    "Standard"          => $fStandard,
						    "CartonSize"        => $sCartonSize,
						    "CartonsDhu"        => $fCartonsDhu,
						    "ShipQty"           => $iShipQty,
						    "Knitted"           => $fKnitted,
						    "Dyed"              => $fDyed,
						    "Cutting"           => $iCutting,
						    "Sewing"            => $iSewing,
						    "Finishing"         => $iFinishing,
						    "Packing"           => $iPacking,
						    "FinalAuditDate"    => (($sFinalAuditDate == "0000-00-00") ? "" : $sFinalAuditDate),
						    "ReScreenQty"       => $iReScreenQty,
						    "CartonsRequired"   => $fCartonsRequired,
						    "CartonsShipped"    => $fCartonsShipped,
						    "ApprovedSample"    => $sApprovedSample,
							"ShippingMark"      => $sShippingMark,
						    "PackingCheck"      => $sPackingCheck,
						    "ApprovedTrims"     => $sApprovedTrims,
						    "ShadeBand"         => $sShadeBand,
						    "EmbApproval"       => $sEmbApproval,
						    "GsmWeight"         => $sGsmWeight,
						    "Unit"              => $sUnit,
						    "Maker"             => $sMaker,
							"GacDate"           => $sGacDate,
							"GacDates"          => $sGacDates,
						    "Comments"          => $sComments);
							
							
							
			if ($iReportId == 14 || $iReportId == 34)
			{				
				$sSQL = "SELECT * FROM tbl_mgf_reports WHERE audit_id='$iAuditId'";
				$objDb->query($sSQL);

				if ($objDb->getCount( ) == 1)
				{
					$sVpoNo                = $objDb->getField(0, "vpo_no");
					$sReInspection         = $objDb->getField(0, "reinspection");
					$sGarmentTest          = $objDb->getField(0, "garment_test");
					$sShadeBand            = $objDb->getField(0, "shade_band");
					$sQaFile               = $objDb->getField(0, "qa_file");
					$sFabricTest           = $objDb->getField(0, "fabric_test");
					$sPpMeeting            = $objDb->getField(0, "pp_meeting");
					$sFittingTorque        = $objDb->getField(0, "fitting_torque");
					$sColorCheck           = $objDb->getField(0, "color_check");
					$sAccessoriesCheck     = $objDb->getField(0, "accessories_check");
					$sMeasurementCheck     = $objDb->getField(0, "measurement_check");
					$sCapOthers            = $objDb->getField(0, "cap_others");
					$sCartonNo             = $objDb->getField(0, "carton_no");
					$iMeasurementSampleQty = $objDb->getField(0, "measurement_sample_qty");
					$iMeasurementDefectQty = $objDb->getField(0, "measurement_defect_qty");
					

					$sAudit["VpoNo"]                = $sVpoNo;
					$sAudit["GarmentTest"]          = $sGarmentTest;
					$sAudit["ShadeBand"]            = $sShadeBand;
					$sAudit["QaFile"]               = $sQaFile;
					$sAudit["FabricTest"]           = $sFabricTest;
					$sAudit["PpMeeting"]            = $sPpMeeting;
					$sAudit["FittingTorque"]        = $sFittingTorque;
					$sAudit["ColorCheck"]           = $sColorCheck;
					$sAudit["AccessoriesCheck"]     = $sAccessoriesCheck;
					$sAudit["MeasurementCheck"]     = $sMeasurementCheck;
					$sAudit["CapOthers"]            = $sCapOthers;
					$sAudit["CartonNo"]             = $sCartonNo;
					$sAudit["MeasurementSampleQty"] = $iMeasurementSampleQty;
					$sAudit["MeasurementDefectQty"] = $iMeasurementDefectQty;
					$sAudit["ReInspection"]         = $sReInspection;
				}
			}			
									
							
			foreach ($sAudit as $sKey => $sValue)
			{
				if (!isset($sAudit[$sKey]) || $sAudit[$sKey] == NULL)
					$sAudit[$sKey] = "";
			}

			$aResponse['Status'] = "OK";
			$aResponse['Audit']  = $sAudit;
		}
	}


	print @json_encode($aResponse);


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>