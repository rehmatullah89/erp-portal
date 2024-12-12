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

	$sForm[$iIndex]['Label']    = "Sub Contractor";
	$sForm[$iIndex]['Field']    = "SubContractor";
	$sForm[$iIndex]['Value']    = $sSubContractor;
	$iIndex ++;

	$sForm[$iIndex]['Label']    = "Item";
	$sForm[$iIndex]['Field']    = "Item";
	$sForm[$iIndex]['Value']    = $sItem;
	$iIndex ++;


	$sSQL = "SELECT SUM(quantity) FROM tbl_po_quantities WHERE po_id='$Id'";
	$objDb->query($sSQL);

	$iQuantity = $objDb->getField(0, 0);

	$sForm[$iIndex]['Label']    = "Quantity";
	$sForm[$iIndex]['Field']    = "Quantity";
	$sForm[$iIndex]['Value']    = formatNumber($iQuantity, false);
	$sForm[$iIndex]['Type']     = "READONLY";
	$iIndex ++;

	$sForm[$iIndex]['Label']    = "Price";
	$sForm[$iIndex]['Field']    = "Price";
	$sForm[$iIndex]['Value']    = formatNumber($fPrice);
	$iIndex ++;


	$sSQL = "SELECT etd_required FROM tbl_po_colors WHERE po_id='$Id' AND style_id='$iStyleId' LIMIT 1";
	$objDb->query($sSQL);

	$sEtdRequired = $objDb->getField(0, 0);

	$sForm[$iIndex]['Label']  = "Original ETD";
	$sForm[$iIndex]['Field']  = "EtdRequired";
	$sForm[$iIndex]['Value']  = formatDate($sEtdRequired);
	$sForm[$iIndex]['Type']   = "READONLY";
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "Revised ETD";
	$sForm[$iIndex]['Field'] = "RevisedEtd";
	$sForm[$iIndex]['Value'] = $sRevisedEtd;
	$sForm[$iIndex]['Type']  = "DATE";
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "Mode";
	$sForm[$iIndex]['Field'] = "Mode";
	$sForm[$iIndex]['Value'] = $sMode;
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "Trims";
	$sForm[$iIndex]['Field'] = "Trims";
	$sForm[$iIndex]['Value'] = $sTrims;
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "Yarn/Fabric";
	$sForm[$iIndex]['Field'] = "YarnFabric";
	$sForm[$iIndex]['Value'] = $sYarnFabric;
	$iIndex ++;

	$sForm[$iIndex]['Label']  = "Knitting";
	$sForm[$iIndex]['Field']  = "Knitting";
	$sForm[$iIndex]['Value']  = $iKnitting;
	$sForm[$iIndex]['Type']   = "DROPDOWN";
	$sForm[$iIndex]['Values'] = array(0, 50, 100);
	$sForm[$iIndex]['Labels'] = array("Not Started", "Started", "Completed");
	$iIndex ++;

	$sForm[$iIndex]['Label']  = "Dyeing";
	$sForm[$iIndex]['Field']  = "Dyeing";
	$sForm[$iIndex]['Value']  = $iDyeing;
	$sForm[$iIndex]['Type']   = "DROPDOWN";
	$sForm[$iIndex]['Values'] = array(0, 50, 100);
	$sForm[$iIndex]['Labels'] = array("Not Started", "Started", "Completed");
	$iIndex ++;

	$sForm[$iIndex]['Label']  = "Cutting";
	$sForm[$iIndex]['Field']  = "Cutting";
	$sForm[$iIndex]['Value']  = $iCutting;
	$sForm[$iIndex]['Type']   = "DROPDOWN";
	$sForm[$iIndex]['Values'] = array(0, 50, 100);
	$sForm[$iIndex]['Labels'] = array("Not Started", "Started", "Completed");
	$iIndex ++;

	$sForm[$iIndex]['Label']  = "Print/Embroidery";
	$sForm[$iIndex]['Field']  = "PrintEmbroidery";
	$sForm[$iIndex]['Value']  = $iPrintEmbroidery;
	$sForm[$iIndex]['Type']   = "DROPDOWN";
	$sForm[$iIndex]['Values'] = array(0, 50, 100);
	$sForm[$iIndex]['Labels'] = array("Not Started", "Started", "Completed");
	$iIndex ++;

	$sForm[$iIndex]['Label']  = "Linking";
	$sForm[$iIndex]['Field']  = "Linking";
	$sForm[$iIndex]['Value']  = $iLinking;
	$sForm[$iIndex]['Type']   = "DROPDOWN";
	$sForm[$iIndex]['Values'] = array(0, 50, 100);
	$sForm[$iIndex]['Labels'] = array("Not Started", "Started", "Completed");
	$iIndex ++;

	$sForm[$iIndex]['Label']  = "Washing";
	$sForm[$iIndex]['Field']  = "Washing";
	$sForm[$iIndex]['Value']  = $iWashing;
	$sForm[$iIndex]['Type']   = "DROPDOWN";
	$sForm[$iIndex]['Values'] = array(0, 50, 100);
	$sForm[$iIndex]['Labels'] = array("Not Started", "Started", "Completed");
	$iIndex ++;

	$sForm[$iIndex]['Label']  = "Packing";
	$sForm[$iIndex]['Field']  = "Packing";
	$sForm[$iIndex]['Value']  = $iPacking;
	$sForm[$iIndex]['Type']   = "DROPDOWN";
	$sForm[$iIndex]['Values'] = array(0, 50, 100);
	$sForm[$iIndex]['Labels'] = array("Not Started", "Started", "Completed");
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "Final Audit Date";
	$sForm[$iIndex]['Field'] = "FinalAuditDate";
	$sForm[$iIndex]['Value'] = $sFinalAuditDate;
	$sForm[$iIndex]['Type']  = "DATE";
	$iIndex ++;

	$sForm[$iIndex]['Label'] = "Production Status";
	$sForm[$iIndex]['Field'] = "ProductionStatus";
	$sForm[$iIndex]['Value'] = $sProductionStatus;
	$iIndex ++;

	$sForm[$iIndex]['Label']   = "Remarks";
	$sForm[$iIndex]['Field']   = "Remarks";
	$sForm[$iIndex]['Value']   = $sRemarks;
	$sForm[$iIndex]['Type']    = "TEXTAREA";
	$sForm[$iIndex]['Rows']    = "5";
	$sForm[$iIndex]['Columns'] = "110";
?>