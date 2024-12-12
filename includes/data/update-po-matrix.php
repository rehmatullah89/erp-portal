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

	$iMtarBrands = @explode(",", getDbValue("brands", "tbl_departments", "id='33'"));
	$Brand       = IO::intValue("Brand");


	$sSQL  = ("SELECT * FROM tbl_po WHERE order_no='".IO::strValue("OrderNo")."' AND order_status='".IO::strValue("OrderStatus")."' AND vendor_id='".IO::intValue("Vendor")."' AND brand_id=(SELECT sub_brand_id FROM tbl_styles WHERE id IN (".@implode(",", IO::getArray('Styles')).") LIMIT 1) AND id!='$Id'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$sCreated  = getDbValue("created", "tbl_po", "id='$Id'");
		$sModified = getDbValue("modified", "tbl_po", "id='$Id'");


		
		$OldCartonLabeling  = IO::strValue("OldCartonLabeling");
		$OldPdfFile         = IO::strValue("OldPdfFile");		
		$sCartonLabelingSql = "";
		$sPdfFileSql        = "";


		$objDb->execute("BEGIN");

		if ($_FILES['CartonLabeling']['name'] != "")
		{
			$sCartonLabeling = ($Id."-".IO::getFileName($_FILES['CartonLabeling']['name']));

			if (@move_uploaded_file($_FILES['CartonLabeling']['tmp_name'], ($sBaseDir.PO_DOCS_DIR.$sCartonLabeling)))
				$sCartonLabelingSql = ", carton_labeling='$sCartonLabeling' ";
		}

		if ($_FILES['PdfFile']['name'] != "")
		{
			$sPdfFile = ($Id."-".IO::getFileName($_FILES['PdfFile']['name']));

			if (@move_uploaded_file($_FILES['PdfFile']['tmp_name'], ($sBaseDir.PO_DOCS_DIR.$sPdfFile)))
				$sPdfFileSql = ", pdf='$sPdfFile' ";
		}
		
		
		
		$Styles    = IO::getArray('Styles');
		$Sizes     = IO::getArray('Sizes');
		$iQuantity = 0;


		$objDb->execute("BEGIN");

		$sSQL  = ("UPDATE tbl_po SET vendor_id='".IO::intValue("Vendor")."', brand_id='$Brand', order_no='".IO::strValue("OrderNo")."', order_status='".IO::strValue("OrderStatus")."', order_nature='".IO::strValue("OrderNature")."', order_type='".(($Brand == 32) ? IO::strValue("OrderType") : '')."', article_no='".IO::strValue("ArticleNo")."', category_id='".IO::intValue("Category")."', customer='".IO::strValue("Customer")."', vpo_no='".IO::strValue("VpoNo")."', customer_po_no='".IO::strValue("CustomerPoNo")."', customer_ship='".IO::strValue("CustomerShip")."', call_no='".IO::strValue("CallNo")."', terms_of_delivery='".IO::strValue("TermsOfDelivery")."', place_of_departure='".IO::strValue("PlaceOfDeparture")."', way_of_dispatch='".IO::strValue("WayOfDispatch")."', terms_of_payment='".IO::strValue("TermsOfPayment")."', looms='".((IO::intValue("Looms") == 0) ? 1 : IO::intValue("Looms"))."', size_set='".IO::strValue("SampleSize")."', lab_dips='".IO::strValue("LabDips")."', photo_sample='".IO::strValue("PhotoSample")."', pre_prod_sample='".IO::strValue("PreProductionSample")."', note='".IO::strValue("Note")."', styles='".@implode(",", $Styles)."', sizes='".@implode(",", $Sizes)."', vas_adjustment='".IO::floatValue('VasAdjustment')."', currency='".IO::strValue("Currency")."', bank_details='".@utf8_encode(IO::strValue("BankDetails"))."', shipping_address='".@utf8_encode(IO::strValue("ShippingAddress"))."', po_terms='".@utf8_encode(IO::strValue("PoTerms"))."', item_number='".IO::strValue("ItemNo")."', product_group='".IO::strValue("ProductGroup")."', quality='".IO::strValue("Quality")."', single_packing='".IO::strValue("SinglePacking")."', packing_size='".IO::intValue("PackagingSize")."', packing_color='".IO::intValue("PackagingColour")."', packing_carton='".IO::intValue("PackagingCarton")."', hanging_packing='".IO::strValue("HangingPacking")."', hs_code='".IO::strValue("HsCode")."', shipping_from_date='".((IO::strValue("ShippingFromDate") == "") ? "0000-00-00" : IO::strValue("ShippingFromDate"))."', shipping_to_date='".((IO::strValue("ShippingToDate") == "") ? "0000-00-00" : IO::strValue("ShippingToDate"))."', carton_instructions='".IO::strValue("CartonInstructions")."', modified=NOW( ), modified_by='{$_SESSION['UserId']}' $sCartonLabelingSql $sPdfFileSql WHERE id='$Id'");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL  = "DELETE FROM tbl_po_quantities WHERE po_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true)
		{
			$ColorsCount = IO::intValue("ColorsCount");
			$SizesCount  = count($Sizes);

			for ($i = 0; $i < $ColorsCount; $i ++)
			{
				$ColorId = IO::intValue("ColorId".$i);

				if ($ColorId > 0)
				{
					$sSQL = "SELECT etd_required FROM tbl_po_colors WHERE id='$ColorId'";
					$objDb->query($sSQL);

					$sOriginalEtd = $objDb->getField(0, 0);

					if ($sOriginalEtd != IO::strValue("DateOfShipment".$i))
					{
						$iId = getNextId("tbl_etd_revisions");

						$sSQL  = "INSERT INTO tbl_etd_revisions (id, po_id, original, revised, user_id, date_time) VALUES ('$iId', '$Id', '$sOriginalEtd', '".IO::strValue("DateOfShipment".$i)."', '{$_SESSION['UserId']}', NOW( ))";
						$bFlag = $objDb->execute($sSQL);
					}


					if (!@in_array($Brand, $iMtarBrands))
						$sSQL = "UPDATE tbl_po_colors SET color='".IO::strValue("Color".$i)."', line='".IO::strValue("Line".$i)."', price='".IO::floatValue("Price".$i)."', vsr_price='".IO::floatValue("Price".$i)."', style_id='".IO::intValue("Style".$i)."', destination_id='".IO::intValue("Destination".$i)."', etd_required='".IO::strValue("DateOfShipment".$i)."', posdd='".IO::strValue("DateOfShipment".$i)."', vsr_etd_required='".IO::strValue("DateOfShipment".$i)."' WHERE id='$ColorId'";

					else
						$sSQL = "UPDATE tbl_po_colors SET color='".IO::strValue("Color".$i)."', line='".IO::strValue("Line".$i)."', price='".IO::floatValue("Price".$i)."', style_id='".IO::intValue("Style".$i)."', destination_id='".IO::intValue("Destination".$i)."', etd_required='".IO::strValue("DateOfShipment".$i)."', posdd='".IO::strValue("DateOfShipment".$i)."' WHERE id='$ColorId'";
				}

				else
				{
					$ColorId = getNextId("tbl_po_colors");


					if (!@in_array($Brand, $iMtarBrands))
						$sSQL = "INSERT INTO tbl_po_colors (id, po_id, color, line, price, vsr_price, style_id, destination_id, etd_required, posdd, vsr_etd_required) VALUES ('$ColorId', '$Id', '".IO::strValue("Color".$i)."', '".IO::strValue("Line".$i)."', '".IO::floatValue("Price".$i)."', '".IO::floatValue("Price".$i)."', '".IO::intValue("Style".$i)."', '".IO::intValue("Destination".$i)."', '".IO::strValue("DateOfShipment".$i)."', '".IO::strValue("DateOfShipment".$i)."', '".IO::strValue("DateOfShipment".$i)."')";

					else
						$sSQL = "INSERT INTO tbl_po_colors (id, po_id, color, line, price, style_id, destination_id, etd_required, posdd) VALUES ('$ColorId', '$Id', '".IO::strValue("Color".$i)."', '".IO::strValue("Line".$i)."', '".IO::floatValue("Price".$i)."', '".IO::intValue("Style".$i)."', '".IO::intValue("Destination".$i)."', '".IO::strValue("DateOfShipment".$i)."', '".IO::strValue("DateOfShipment".$i)."')";
				}

				$bFlag = $objDb->execute($sSQL);


				if ($bFlag == true)
				{
					for ($j = 0; $j < $SizesCount; $j ++)
					{
						$Qty = IO::floatValue("Quantity".$i."_".$Sizes[$j]);

						if ($Qty > 0)
						{
							$sSQL = "INSERT INTO tbl_po_quantities (po_id, color_id, size_id, quantity) VALUES ('$Id', '$ColorId', '{$Sizes[$j]}', '$Qty')";
							$bFlag = $objDb->execute($sSQL);

							$iQuantity += $Qty;

							if ($bFlag == false)
								break;
						}
					}
				}

				if ($bFlag == false)
					break;
			}
		}


		if ($bFlag == true)
		{
			if (!@in_array($Brand, $iMtarBrands))
			{
				$sSQL  = "UPDATE tbl_po SET quantity=(SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$Id'),
											shipping_dates=(SELECT GROUP_CONCAT(DISTINCT(etd_required) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$Id' GROUP BY po_id),
											vsr_shipping_dates=(SELECT GROUP_CONCAT(DISTINCT(etd_required) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$Id' GROUP BY po_id),
											destinations=(SELECT GROUP_CONCAT(DISTINCT(destination_id) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$Id' GROUP BY po_id)
										WHERE id='$Id'";
			}

			else
			{
				$sSQL  = "UPDATE tbl_po SET quantity=(SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$Id'),
											shipping_dates=(SELECT GROUP_CONCAT(DISTINCT(etd_required) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$Id' GROUP BY po_id),
											destinations=(SELECT GROUP_CONCAT(DISTINCT(destination_id) SEPARATOR ',') FROM tbl_po_colors WHERE po_id='$Id' GROUP BY po_id)
										WHERE id='$Id'";
			}

			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true)
		{
			$sSQL = ("UPDATE tbl_po SET brand_id=(SELECT sub_brand_id FROM tbl_styles WHERE id IN (".@implode(",", $Styles).") LIMIT 1) WHERE id='$Id'");
			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true)
		{
			$sFactoryReasons = getDbValue("GROUP_CONCAT(id)", "tbl_etd_revision_reasons", "parent_id='92'");
			
			
			$sSQL = "SELECT id, etd_required FROM tbl_po_colors WHERE po_id='$Id'";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
			$iBrand = getDbValue("brand_id", "tbl_po", "id='$Id'");

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iColorId     = $objDb->getField($i, "id");
				$sEtdRequired = $objDb->getField($i, "etd_required");
				
				if ($iBrand == 32 || $iBrand == 43)
				{
					$sColorEtd = getDbValue("MIN(original_etd)", "tbl_etd_revision_requests_colors", "status='A' AND color_id='$iColorId' AND request_id IN (SELECT id FROM tbl_etd_revision_requests WHERE po_id='$Id' AND FIND_IN_SET(reason_id, '$sFactoryReasons'))");
					
					if ($sColorEtd != "" && strlen($sColorEtd) == 10 && strtotime($sColorEtd) < strtotime($sEtdRequired))
						$sEtdRequired = $sColorEtd;
					
					$iDay         = date("N", strtotime($sEtdRequired));			
					$sEtdRequired = date('Y-m-d', (strtotime($sEtdRequired) + (86400 * (7 - $iDay))));
				}				

				$iOrderQty  = getDbValue("COALESCE(SUM(quantity), 0)", "tbl_po_quantities", "po_id='$Id' AND color_id='$iColorId'");
				$iOnTimeQty = getDbValue("COALESCE(SUM(psq.quantity), 0)", "tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq", "psd.po_id=psq.po_id AND psd.po_id='$Id' AND psd.id=psq.ship_id AND psq.color_id='$iColorId' AND psd.handover_to_forwarder != '0000-00-00' AND NOT ISNULL(psd.handover_to_forwarder) AND psd.handover_to_forwarder<='$sEtdRequired'");

				$iOnTimeQty = (($iOnTimeQty > 0) ? $iOrderQty : 0);


				$sSQL  = "UPDATE tbl_po_colors SET order_qty='$iOrderQty', ontime_qty='$iOnTimeQty' WHERE id='$iColorId'";
				$bFlag = $objDb2->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}

		if ($bFlag == true)
		{
			$sSQL = "SELECT COALESCE(SUM(order_qty), 0), COALESCE(SUM(ontime_qty), 0) FROM tbl_po_colors WHERE po_id='$Id' AND etd_required <= CURDATE( )";
			$objDb->query($sSQL);

			$iOrderQty  = $objDb->getField(0, 0);
			$iOnTimeQty = $objDb->getField(0, 1);

			$fOtp = @round((($iOnTimeQty / $iOrderQty) * 100), 2);


			$sSQL  = "UPDATE tbl_pre_shipment_advice SET otp='$fOtp' WHERE po_id='$Id'";
			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true)
		{
			$sSQL  = ("INSERT INTO tbl_po_log (po_id, user_id, date_time, reason) VALUES ('$Id', '{$_SESSION['UserId']}', NOW( ), '".((IO::strValue("Reason") == '' && $Referer == 'add-purchase-order.php') ? 'PO Entry' : IO::strValue("Reason"))."')");
			$bFlag = $objDb->execute($sSQL);
		}


		if ($bFlag == true)
		{
			$_SESSION['Flag'] = "PO_SAVED";

			if ($sCreated == $sModified)
			{
				$iPoId = $Id;

				@include($sBaseDir."includes/data/po-entry-notification.php");
			}

			
			if ($sPdfFile != "" && $OldPdfFile != "" && $sPdfFile != $OldPdfFile)
				@unlink($sBaseDir.PO_DOCS_DIR.$OldPdfFile);
			
			if ($sCartonLabeling != "" && $OldCartonLabeling != "" && $sCartonLabeling != $OldCartonLabeling)
				@unlink($sBaseDir.PO_DOCS_DIR.$OldCartonLabeling);


			$objDb->execute("COMMIT");
		}

		else
		{
			if ($sCartonLabeling != "" && $sCartonLabeling != $OldCartonLabeling)
				@unlink($sBaseDir.PO_DOCS_DIR.$sCartonLabeling);

			if ($sPdfFile != "" && $sPdfFile != $OldPdfFile)
				@unlink($sBaseDir.PO_DOCS_DIR.$sPdfFile);

			
			$_SESSION['Flag'] = "DB_ERROR";

			$objDb->execute("ROLLBACK");
		}
	}

	else
		$_SESSION['Flag'] = "ORDER_NO_EXISTS";
?>