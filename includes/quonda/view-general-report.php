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

        $AuditSections      = explode(",", getDbValue ("sections", "tbl_reports", "id = '$iReportId'"));
        $sStylesList        = getList("tbl_styles", "id", "style", "id IN ($Style)");
        $sScheduledSizeList = getList("tbl_sizes", "id", "size", "id IN ($sSizes)");
        
        $sScheduledColorsList = array();
        $colorArray = explode(",", $sColors);

        foreach ($colorArray as $colorSingle) {
            $sScheduledColorsList[$colorSingle] = $colorSingle;
        }
        
?>
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="90">Vendor</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sVendor ?></td>
			  </tr>

			  <tr>
			    <td>Auditor</td>
			    <td align="center">:</td>
			    <td><?= $sAuditor ?></td>
			  </tr>

			  <tr>
				<td>Style</td>
				<td align="center">:</td>
				<td><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
			  </tr>

<?
	$sPos = "";

	$sSQL = "SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id IN ($sAdditionalPos) ORDER BY order_no";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (", ".$objDb->getField($i, 0));
	}
?>
			  <tr valign="top">
			    <td>PO(s)</td>
			    <td align="center">:</td>
			    <td><?= ($sPO.$sPos) ?></td>
			  </tr>

			  <tr>
			    <td>Audit Stage</td>
			    <td align="center">:</td>
			    <td><?= $sAuditStagesList[$sAuditStage] ?></td>
			  </tr>

<?
	switch ($sAuditStatus)
	{
		case "1st" : $sAuditStatus = "1st"; break;
		case "2nd" : $sAuditStatus = "2nd"; break;
		case "3rd" : $sAuditStatus = "3rd"; break;
		case "4th" : $sAuditStatus = "4th"; break;
		case "5th" : $sAuditStatus = "5th"; break;
		case "6th" : $sAuditStatus = "6th"; break;
	}
?>
			  <tr>
			    <td>Audit Status</td>
			    <td align="center">:</td>
			    <td><?= $sAuditStatus ?></td>
			  </tr>

<?
	switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
		case "H" : $sAuditResult = "Hold"; break;
	}
?>
			  <tr>
			    <td>Audit Result</td>
			    <td align="center">:</td>
			    <td><?= $sAuditResult ?></td>
			  </tr>

			  <tr>
			   <td>Colors</td>
			    <td align="center">:</td>
			    <td><?= $sColors ?></td>
			  </tr>

<?
	$sSizeTitles = "";

	$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSizeTitles .= (", ".$objDb->getField($i, 0));

		$sSizeTitles = substr($sSizeTitles, 2);
	}
?>
			  <tr>
			   <td><?= (($iReportId != 8) ? 'Sizes' : 'Range') ?></td>
			    <td align="center">:</td>
			    <td><?= $sSizeTitles ?></td>
			  </tr>
		    </table>

		    <br />
                    
		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">                        
<?
                        $iSectionNo = 1;
                        
                        if (@in_array(1, $AuditSections))
                        {
?>
                        <h2><?= $iSectionNo++ ?> - Description / Quantity of Product</h2>
                                        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
          <tr class="sdRowHeader">
            <td width="10" align="center"><b>#</b></td>
            <td width="90" align="center"><b>Styles</b></td>
            <td width="90" align="center"><b>Po(s)</b></td>
            <td width="90" align="center"><b>Colors</b></td>
            <td width="90" align="center"><b>Sizes</b></td>
            <td width="50" align="center"><b>Lot Size</b></td>
            <td width="50" align="center"><b>Sample Size</b></td>
          </tr>
        </table>
        <?

            $sSQL = "SELECT * FROM tbl_qa_lot_sizes WHERE audit_id='$Id' ORDER BY id";

            $objDb->query($sSQL);

            $iLotCount = $objDb->getCount( );

            if($iLotCount > 0)
            {
                $qopCount           = 1;
                $sAdditionalPos     = getDbValue ("additional_pos", "tbl_qa_reports", "id = '$Id' ");
                $sAdditionalStyles  = getDbValue ("additional_styles", "tbl_qa_reports", "id = '$Id' ");
                $sColors            = getDbValue ("colors", "tbl_qa_reports", "id = '$Id' ");

                $totalLotSize = 0;
                $totalSampleSize = 0;
                
                for ($i=0; $i <$iLotCount; $i++) 
                {
                    $id             = $objDb->getField($i, 'id');
                    $dbStylesText   = $objDb->getField($i, 'styles');
                    $dbPosText      = $objDb->getField($i, 'pos');
                    $dbColorsText   = $objDb->getField($i, 'colors');
                    $dbLotSize      = $objDb->getField($i, 'lot_size');
                    $dbLotSampleSize= $objDb->getField($i, 'sample_size');
                    $dbSizes        = $objDb->getField($i, 'sizes')
            ?>

            <div id="qopDeletedDiv"></div>
                <table id="table<?=$qopCount?>" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" class="lotRow">
                  <tr>
                    <td width="10" align="center" class="lotIndex"><?=$qopCount?></td>
                    <td width="90" align="center">
                        <?= getDbValue("GROUP_CONCAT(style SEPARATOR ', ')", "tbl_styles", "id IN ($dbStylesText)")?>
                    </td>
                    <td width="90" align="center">
                        <?=getDbValue("GROUP_CONCAT(order_no SEPARATOR ', ')", "tbl_po", "id IN ($dbPosText)")?>
                    </td>
                    <td width="90" align="center">
                        <?= $dbColorsText?>
                    </td>
                    <td width="90" align="center">
                        <?= getDbValue("GROUP_CONCAT(size SEPARATOR ', ')", "tbl_sizes", "id IN ($dbSizes)")?>
                    </td>
                    <td width="50" align="center">
                        <?=$dbLotSize?>
                    </td>
                    <td width="50" align="center">
                        <?=$dbLotSampleSize?>
                    </td>                   
                  </tr>
                </table>

            <?  
            $qopCount++;
            }
        }
                        }
                        
                        if (@in_array(2, $AuditSections))
                        {
?>
                        <h2><?= $iSectionNo++ ?> - Product Conformity</h2>
<?                        
                            $sStatementList = getList("tbl_statements", "id", "statement", "FIND_IN_SET('1', sections)");
                            $sConformities  = getList("tbl_qa_product_conformity", "serial", "observation", "audit_id='$Id'", "serial");

                            $sResult    = getDbValue("product_conformity_result", "tbl_qa_report_details", "audit_id='$Id'");
                            $sConfComments  = getDbValue("product_conformity_comments", "tbl_qa_report_details", "audit_id='$Id'");
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3 style="background:#c6c6c6;">Observations/ Differences</h3>
	
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="ProductConformityTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td><b>Product Difference</b></td>
            </tr>
<?
                $iInc = 0;                 
                foreach($sConformities as $iSerial => $sObservation)
		{
?>
			<tr id="RowId<?=$iInc?>">
				<td><?=$iInc+1?></td>
				<td><?=$sObservation?></td>                                
			</tr>
<?                  $iInc ++;
		}
?>
        </table>
		
            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                      <td width="80">&nbsp;</td>
                      <td>&nbsp;</td>
                </tr>
                 <tr class="sdRowHeader">
                     <td colspan="2"><b>Product Conformity Result & Comments</b></td>
                </tr>
                
                <tr>
                    <td width="80">Result</td>
                    <td><?= (($sResult == "P" || $sResult == "") ? "Pass" : (($sResult == "F") ? "Fail" : "Not Applicable")) ?></td>
                </tr>
                
                <tr valign="top">
                    <td width="80">Comments</td>
                    <td><?=$sConfComments?></td>
                </tr>
            </table>
	</div>
<?
                        }
?>
            <h2 style="margin:0px;"><?= $iSectionNo++ ?> - Workmanship</h2>
<?
                        if($iReportId == 54)
                        {
                            
                            $iDefects = 0;

                            $sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
                            $objDb->query($sSQL);
                            $iCount = $objDb->getCount( );

                            $sSQL = "SELECT DISTINCT(type_id), (SELECT type FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) FROM tbl_defect_codes WHERE report_id='$iReportId' ORDER BY type_id";
                            $objDb2->query($sSQL);
                            $iCount2 = $objDb2->getCount( );

                            $sSQL = "SELECT * FROM tbl_defect_areas WHERE status='A' ORDER BY area";
                            $objDb4->query($sSQL);
                            $iCount4 = $objDb4->getCount( );
?>
				<input type="hidden" id="Count" name="Count" value="<?= $iCount ?>" />
			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="20" align="center"><b>#</b></td>
					<td><b>Code - Check Points</b></td>
					<td width="60" align="center"><b>Defects</b></td>
                                        <td width="60" align="center"><b>Lot#</b></td>
					<td width="100" align="center"><b>Area</b></td>
					<td width="80" align="center"><b>Nature</b></td>
					<td width="80" align="center"><b>Style</b></td>
					<td width="80" align="center"><b>Color</b></td>
					<td width="80" align="center"><b>Size</b></td>
					<td width="60" align="center"><b>Sample No.</b></td>
			      </tr>
			    </table>

			    <div id="QaDefects">
<?
	$sDefectCode = "";
	$sDefectArea = "";

	for($i = 0; $i < $iCount; $i ++)
	{
		$defectStyle    = $objDb->getField($i, 'style_id');
		$defectSize     = $objDb->getField($i, 'size_id');
		$defectColor    = $objDb->getField($i, 'color');
                $defectSampleNo = $objDb->getField($i, 'sample_no');
		$defectLotNo    = $objDb->getField($i, 'lot_no');

		if ($objDb->getField($i, 'nature') > 0)
			$iDefects += $objDb->getField($i, 'defects');
?>

				<div id="DefectRecord<?= $i ?>" class="defectRecords">
				  <div>
					<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					  <tr class="sdRowColor" valign="top">
						<td width="20" align="center" class="serial"><?= ($i + 1) ?></td>

						<td>
<?
		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iTypeId = $objDb2->getField($j, 0);
			$sType   = $objDb2->getField($j, 1);
                        
			$sSQL = "SELECT id, code, defect FROM tbl_defect_codes WHERE report_id='$iReportId' AND type_id='$iTypeId' ORDER BY code";
			$objDb3->query($sSQL);

			$iCount3 = $objDb3->getCount( );

			for ($k = 0; $k < $iCount3; $k ++)
			{
				$iCodeId = $objDb3->getField($k, 0);
				$sCode   = $objDb3->getField($k, 1);
				$sDefect = $objDb3->getField($k, 2);

				if ($iCodeId == $objDb->getField($i, 'code_id'))
                                    echo $sCode." - ".$sDefect;
			}
		}
?>
						</td>

						<td width="60" align="center"><?= $objDb->getField($i, 'defects') ?></td>
                                                
                                                <td width="60" align="center"><?=$defectLotNo?></td>
                                                  
						<td width="100" align="center">
<?
		for ($j = 0; $j < $iCount4; $j ++)
		{
			$iAreaId = $objDb4->getField($j, 0);
			$sArea   = $objDb4->getField($j, 1);

			$sAreaId = str_pad($iAreaId, 2, '0', STR_PAD_LEFT);
                        
                        if ($iAreaId == $objDb->getField($i, 'area_id'))
                            echo $sArea;
		}
?>

						</td>

						<td width="80" align="center"><?= (($objDb->getField($i, 'nature') == 1) ? "MAJOR" : (($objDb->getField($i, 'nature') == 2) ? "CRITICAL" : "MINOR")) ?></td>						
						<td width='80' align='center'><?=$sStylesList[$defectStyle]?></td>
						<td width='80' align='center'><?=$sScheduledColorsList[$defectColor]?></td>
						<td width='80' align='center'><?=$sScheduledSizeList[$defectSize]?></td>
						<td width='60' align='center'><?= $defectSampleNo?></td>
                                            </tr>

					</table>
				  </div>
				</div>
<?
	}
?>
				</div>
<?
                        }
                        else {
?>
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="60" align="center"><b>#</b></td>
				  <td><b>Code - Check Points</b></td>
				  <td width="70" align="center"><b>Defects</b></td>
				  <td width="70" align="center"><b>Sample #</b></td>
				  <td width="180"><b>Area</b></td>
				  <td width="70"><b>Nature</b></td>
			    </tr>

<?
	$iDefects = 0;

	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		if ($objDb->getField($i, 'nature') > 0)
			$iDefects += $objDb->getField($i, 'defects');


		$sSQL = ("SELECT code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL);

		$sSQL = ("SELECT area FROM tbl_defect_areas WHERE id='".$objDb->getField($i, 'area_id')."'");
		$objDb3->query($sSQL);
?>

			    <tr class="sdRowColor">
				  <td align="center"><?= ($i + 1) ?></td>
				  <td><?= $objDb2->getField(0, 0) ?> - <?= $objDb2->getField(0, 1) ?></td>
				  <td align="center"><?= $objDb->getField($i, 'defects') ?></td>
				  <td align="center"><?= $objDb->getField($i, 'sample_no') ?></td>
				  <td><?= $objDb3->getField(0, 0) ?></td>
				  <td><?= (($objDb->getField($i, 'nature') == 1) ? "MAJOR" : (($objDb->getField($i, 'nature') == 2) ? "CRITICAL" : "MINOR")) ?></td>
			    </tr>
<?
		if($objDb->getField($i, 'remarks') != "")
		{
?>
                <tr class="sdRowColor">
				  <td align="center"><b>Remarks</b></td>
				  <td colspan="5"><?= $objDb->getField($i, 'remarks') ?></td>
			    </tr>
<?
		}
	}

	if ($iCount == 0)
	{
?>

			    <tr class="sdRowColor">
				  <td colspan="6" align="center">No Defect Found!</td>
			    </tr>
<?
	}

//	if ($iGmtsDefective == 0)
//		$iGmtsDefective = $iDefects;
?>
			  </table>
<?
                        }
                        
                        if (@in_array(12, $AuditSections))
                        {
                                $iSizes  = @explode(",", $sSizes);
?>
            <h2 style="margin:0px;"><?= $iSectionNo++ ?> - Measurement Conformity</h2>
<?
                                        foreach ($iSizes as $iSize)
                                        {
                                                        $sSize         = getDbValue("size", "tbl_sizes", "id='$iSize'");
                                                        $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");


                                                        $sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings
                                                                         FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
                                                                         WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSamplingSize'
                                                                         ORDER BY qrs.sample_no, qrss.point_id";
                                                        $objDb->query($sSQL);

                                                        $iCount = $objDb->getCount( );

                                                        if ($iCount == 0)
                                                                continue;


                                                        $sSizeFindings = array( );

                                                        for($i = 0; $i < $iCount; $i ++)
                                                        {
                                                                $iSampleNo = $objDb->getField($i, 'sample_no');
                                                                $iPoint    = $objDb->getField($i, 'point_id');
                                                                $sFindings = $objDb->getField($i, 'findings');

                                                                $sSizeFindings["{$iSampleNo}-{$iPoint}"] = $sFindings;
                                                        }
?>
                                            <h3 style="margin:0px;">Measurement Sheet (Size: <?= $sSize ?>)</h3>

                                            <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
                                                  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                                                    <tr class="sdRowHeader">
                                                          <td width="40" align="center"><b>#</b></td>
                                                          <td><b>Measurement Point</b></td>
                                                          <td width="90" align="center"><b>Specs</b></td>
                                                          <td width="90" align="center"><b>Tolerance</b></td>
                                                          <td width="50" align="center"><b>1</b></td>
                                                          <td width="50" align="center"><b>2</b></td>
                                                          <td width="50" align="center"><b>3</b></td>
                                                          <td width="50" align="center"><b>4</b></td>
                                                          <td width="50" align="center"><b>5</b></td>
                                                    </tr>
<?
                                                        $sSQL = "SELECT point_id, specs,
                                                                                        (SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
                                                                                        (SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point
                                                                         FROM tbl_style_specs
                                                                         WHERE style_id='$iStyle' AND size_id='$iSamplingSize' AND version='0' AND specs!='0' AND specs!=''
                                                                         ORDER BY id";
                                                        $objDb->query($sSQL);

                                                        $iCount = $objDb->getCount( );

                                                        for($i = 0; $i < $iCount; $i ++)
                                                        {
                                                                $iPoint     = $objDb->getField($i, 'point_id');
                                                                $sSpecs     = $objDb->getField($i, 'specs');
                                                                $sPoint     = $objDb->getField($i, '_Point');
                                                                $sTolerance = $objDb->getField($i, '_Tolerance');
?>

                                                    <tr class="sdRowColor">
                                                          <td align="center"><?= ($i + 1) ?></td>
                                                          <td><?= $sPoint ?></td>
                                                          <td align="center"><?= $sSpecs ?></td>
                                                          <td align="center"><?= $sTolerance ?></td>
<?
                                                                for ($j = 1; $j <= 5; $j ++)
                                                                {
?>
                                                          <td align="center"><?= $sSizeFindings["{$j}-{$iPoint}"] ?></td>
<?
                                                                }
?>
                                                    </tr>
<?
                                                        }
?>
                                            </table>
                                            </div>
<?
                                                }
                                        
                            echo "<br/>";
                        }
                        
                        if (@in_array(3, $AuditSections))
                        {
?>
                            <h2 style="margin:0px;"><?= $iSectionNo++ ?> - Weight Conformity</h2>
<?
                             $sAuditDate     = getDbValue("audit_date", "tbl_qa_reports", "id='$Id'"); 
            @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
             
            $sColors            = getDbValue("colors", "tbl_qa_reports", "id='$Id'");
            $iColors            = explode(",", $sColors);
            
            $sWeightResults     = getList("tbl_qa_weight_conformity", "color", "result", "audit_id='$Id' AND `type`='F'"); 
            $sPieceResults      = getList("tbl_qa_weight_conformity", "color", "result", "audit_id='$Id' AND `type`='P'"); 
            
            $sFabricResult      = getDbValue("fabric_weight_conformity_result", "tbl_qa_report_details", "audit_id='$Id'");
            $sFabricComments    = getDbValue("fabric_weight_conformity_comments", "tbl_qa_report_details", "audit_id='$Id'");
            
            $sPieceResult       = getDbValue("piece_weight_conformity_result", "tbl_qa_report_details", "audit_id='$Id'");
            $sPieceComments     = getDbValue("piece_weight_conformity_comments", "tbl_qa_report_details", "audit_id='$Id'");
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3>1- Fabric Weight Conformity</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="WeightConformityTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td><b>Color</b></td>
            </tr>
<?
                $iCounter = 1;
                
                foreach($iColors as $sColor)
                {
?>
                    <tr>
                        <td><?=$iCounter?></td>
                        <td><b style="color: gray;"><?=$sColor?></b><input type="hidden" name="Color[]" value="<?=$sColor?>"></td>
                    </tr>
                    <tr id="ColorRow<?=$iCounter?>" >
 
                        <td colspan="2">
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                            <tr>                            
                                <td colspan="3" width="100%">
                                    <b>Fabric Weight: 160
                                </td>
                            </tr> 
                            <tr>                            
                                <td colspan="3">
                                <b>Tolerance in (%): -5/+5</b>
                                </td>
                            </tr> 
                            </table>                            
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="WeightConformityTable">
  
                            <tr class="sdRowHeader">
                                  <td width="30"><b>#</b></td>
                                  <td><b>Fabric Weight</b></td>
                                  <td width="400"><b>Picture</b></td>
                            </tr>
<?
                                $sSQL = "SELECT serial, weight
                                            FROM tbl_qa_weight_details
                                            WHERE audit_id='$Id' AND `type`= 'F' AND color LIKE '$sColor'";
                                $objDb->query($sSQL); 

                                $iFabCount = $objDb->getCount();

                                if($iFabCount > 0)
                                {
                                    for($j=1; $j<=5; $j++)
                                    {
                                        $iSerial    = $objDb->getField($j-1, "serial");
                                        $fWeight    = $objDb->getField($j-1, "weight");
                                        $sPictures  = getList("tbl_qa_weight_pictures", "picture", "picture", "audit_id='$Id' AND `type`= 'F' AND color LIKE '$sColor' AND serial = '$j'");
                                        
                                        if($fWeight != "")
                                        {
?>
                                        <tr>
                                            <td><?=$j?></td>  
                                            <td><?=$fWeight?></td>
                                            <td>
<?
                                        if(!empty($sPictures))
                                        {
                                            foreach($sPictures as $sPicture)
                                            {
?>
                                                <br/><a href="<?= CARTONS_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/".@basename($sPicture) ?>" class="lightview" rel="gallery[picture]" title="<?= utf8_encode($sPicture) ?> :: :: topclose: true"><?= @basename($sPicture) ?></a>                                                
<?
                                            }
                                        }
?>
                                            </td>
                                        </tr>
<?
                                        }
                                    }
                                }
?>
                            <tr class="sdRowHeader">
                                <td colspan="2"><b>Result</b> [<?=$sColor?>]</td>
                                <td><?=($sWeightResults["{$sColor}"] == 'P')?'Pass':($sWeightResults["{$sColor}"] == 'F'?'Fail':'N/A')?></td>
                            </tr>           
                            </table>
                        </td>
                    </tr>
<?
                    $iCounter ++;
                }
?>
        </table>
        <br/>
        <!--- Piece weight start---->
        <h3>2- Piece Weight Conformity</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="PieceWeightConformityTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td><b>Color</b></td>
                  <td width="80"><b>Options</b></td>
            </tr>
<?
                $iCounter = 1;
           
                foreach($iColors as $sColor)
                {
?>
                    <tr>
                        <td><?=$iCounter?></td>
                        <td colspan="2"><b style="color: gray;"><?=$sColor?></b></td>
                    </tr>
                    <tr id="PieceColorRow<?=$iCounter?>" >
 
                        <td colspan="3">
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                            <tr>                            
                                <td colspan="3">
                                    <b>Piece Weight: 160</b>
                                </td>
                            </tr> 
                            <tr>                            
                                <td colspan="3">
                                <b>Tolerance: -5/+5</b>
                                </td>
                            </tr> 
                            </table>                            
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="PieceWeightConformityTable">
  
                            <tr class="sdRowHeader">
                                  <td width="30"><b>#</b></td>
                                  <td><b>Piece Weight</b></td>
                                  <td width="400"><b>Picture</b></td>
                            </tr>
<?
                                    $sSQL = "SELECT serial, weight
                                                FROM tbl_qa_weight_details
                                                WHERE audit_id='$Id' AND `type`= 'P' AND color LIKE '$sColor'";
                                    
                                    $objDb->query($sSQL); 
                                    
                                    for($j=1; $j<=5; $j++)
                                    {
                                        $iSerial    = $objDb->getField($j-1, "serial");
                                        $fWeight    = $objDb->getField($j-1, "weight");
                                        $sPictures  = getList("tbl_qa_weight_pictures", "picture", "picture", "audit_id='$Id' AND color LIKE '$sColor' AND serial = '$j' AND `type`='P'");

                                        if($fWeight != "")
                                        {
?>
                                        <tr>
                                            <td><?=$j?></td>  
                                            <td><?=$fWeight?></td>
                                            <td>
<?
                                        if(!empty($sPictures))
                                        {
                                            foreach($sPictures as $sPicture)
                                            {
?>
                                                <br/><a href="<?= CARTONS_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/".@basename($sPicture) ?>" class="lightview" rel="gallery[picture]" title="<?= utf8_encode($sPicture) ?> :: :: topclose: true"><?= @basename($sPicture) ?></a>                                                
<?
                                            }
                                        }
?>
                                            </td>
                                        </tr>
<?
                                        }
                                    }
?>
                            <tr class="sdRowHeader">
                                <td colspan="2"><b>Result</b> [<?=$sColor?>]</td>
                                <td><?=($sPieceResults["{$sColor}"] == 'P')?'Pass':($sPieceResults["{$sColor}"] == 'F'?'Fail':'N/A')?></td>
                            </tr>           
                            </table>
                        </td>
                    </tr>
<?
                    $iCounter ++;
                }
?>
        </table>
        <br/>
        <!-- Piece Weight End ---->
            <br/><br/>
            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                      <td width="80">&nbsp;</td>
                      <td>&nbsp;</td>
                </tr>
                 <tr class="sdRowHeader">
                     <td colspan="2"><b>Weight Conformity Result & Comments</b></td>
                </tr>
                
                <tr>
                    <td width="80">Result</td>
                    <td><?=($sFabricResult == 'P')?'Pass':($sFabricResult == 'F'?'Fail':'N/A');?></td>
                </tr>
                
                <tr>
                    <td width="80">Comments</td>
                    <td><?=$sFabricComments?></td>
                </tr>

            </table>
	</div>
<?
                            
                        }
                        
                        if (@in_array(4, $AuditSections))
                        {
?>
                            <h2 style="margin:0px;"><?= $iSectionNo++ ?> - Ean Codes</h2>
<?
                            $Styles     = getDbValue("style_id", "tbl_qa_reports", "id='$Id'");
                            $Sizes      = getDbValue("sizes", "tbl_qa_reports", "id='$Id'");
                            $SizesList  = getList("tbl_sizes", "id", "size", "id IN ($Sizes)");

                            $sBarCodeFormat = getDbValue("barcode_format", "tbl_qa_report_details", "audit_id='$Id'");
                            $sResult        = getDbValue("ean_result", "tbl_qa_report_details", "audit_id='$Id'");
                            $sEanComments   = getDbValue("ean_comments", "tbl_qa_report_details", "audit_id='$Id'");

                            $iCounter      = 1;           
?>
  
<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
    <tr class="sdRowHeader">
      <td width="25" align="center"><b>#</b></td>
      <td width="80" align="center"><b>Style</b></td>
      <td width="50" align="center"><b>Size</b></td>
      <td width="80" align="center"><b>EAN</b></td>
      <td width="20" align="center"><b>Result</b></td>
    </tr>
   
<?
    $iStyles = explode(",", $Styles);
    $iSizes  = explode(",", $Sizes);
    $sEan    = "0123456789";
    $iId     = 0;
    
    foreach($iStyles as $iStyle)
    {
        foreach($iSizes as $iSize)
        {        
            $iId ++;
?>
        <tr class="sdRowColor">
          <td width="25" align="center"><?=$iCounter?></td>
          <td width="80" align="center"><?= getDbValue("style", "tbl_styles", "id='$iStyle'")?></td>
          <td width="50" align="center"><?=$SizesList[$iSize]?></td>
          <td width="80" align="center"><?=$sEan?></td>
          <td width="20" align="center"><?= getDbValue("result", "tbl_qa_ean_codes", "style_id='$iStyle' AND size_id='$iSize' AND audit_id='$Id'")?></td>
        </tr>
<?
            $iCounter++;
        }
    }

?>
        </table> 
            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                      <td width="100">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                </tr>
                 <tr class="sdRowHeader">
                     <td colspan="3"><b>Ean Code Result & Comments</b></td>
                </tr>
                <tr>
                    <td width="120">Result</td>
                    <td width="20">:</td>
                    <td><?=($sResult == 'P')?'Pass':($sResult == 'F'?'Fail':'N/A')?></td>
                </tr>
                <tr>
                    <td width="120">Barcode Format</td>
                    <td width="20">:</td>
                    <td id="ResultsRow"><?=($sBarCodeFormat == 1)?'EAN Code-8':($sBarCodeFormat == 2?'EAN Code-13':'')?></td>
                </tr> 
                <tr>
                    <td width="120">Comments</td>
                    <td width="20">:</td>
                    <td><?=$sEanComments?></td>
                </tr>                
            </table>                        
<?
                        }
                        
                        if (@in_array(5, $AuditSections))
                        {
                            echo "<h2 style='margin:0px;'>".$iSectionNo++." - Assortment</h2>";
                            
                            $sSQL = "SELECT *
                                        FROM tbl_qa_assortment
                                     WHERE audit_id='$Id'";

                            $objDb->query($sSQL);

                            $iTotalAssorted  = $objDb->getField(0, "total_cartons_tested");
                            $iWrongAssorted  = $objDb->getField(0, "wrong_assorted_cartons");
                            $sResult         = $objDb->getField(0, "result");
                            $sAssortComments = $objDb->getField(0, "comments");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3>Assortment</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="AssortmentTable">
                <tr>
                    <td width="150">Total Assorted Cartons</td>
                    <td width="20">:</td>
                    <td><?=$iTotalAssorted?></td>
                </tr>
                <tr>
                    <td width="140">Wrong Assorted Cartons</td>
                    <td width="20">:</td>
                    <td><?=$iWrongAssorted?></td>
                </tr>
        </table>

            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                      <td width="140">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                </tr>
                 <tr class="sdRowHeader">
                     <td colspan="3"><b>Assortment Result & Comments</b></td>
                </tr>

                <tr>
                    <td width="80">Result</td>
                    <td width="20">:</td>
                    <td><?=($sResult == 'P')?'Pass':($sResult == 'F'?'Fail':'N/A')?></td>
                </tr>
                <tr>
                    <td width="80">Comments</td>
                    <td width="20">:</td>
                    <td><?=$sAssortComments?></td>
                </tr>

            </table>
	</div>
<?
                        }
                        
                        if (@in_array(10, $AuditSections))
                        {
                            echo "<h2 style='margin:0px;'>".$iSectionNo++." - Sales / Packaging / Labeling</h2>";
                            echo "<h3>A- Packaging Defects</h3>";
                            
                                        $sSQL = "SELECT *
                                            FROM tbl_qa_packaging_defects
                                            WHERE audit_id='$Id'";
                                    $objDb->query($sSQL);

                                    $iLabelingCount = $objDb->getCount( );
                                   
?>
                                <div id="LabelingDefects">
				<input type="hidden" id="LCountRows" name="LCountRows" value="<?= $iLabelingCount ?>" />

			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="55" align="center"><b>#</b></td>
					<td><b>Defect</b></td>
					<td width="100" align="center"><b>Sample No</b></td>
					<td width="200" align="center"><b>Picture</b></td>
			      </tr>
			    </table>
                                <?
                            // $sLabelingDefectsList = getList("tbl_labeling_defects", "id", "CONCAT(code,' - ',defect)", "", "id");
                                $sLabelingDefectsList = getList("tbl_defect_codes", "id", "CONCAT(code, ' - ', defect)", "report_id='$iReportId' AND type_id='6'" , "id");
?>
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="LDefectsTable">
<?
                        if($iLabelingCount > 0)
                        {
                                @list($sPkYear, $sPkMonth, $sPkDay) = @explode("-", $AuditDate);
                                $sPkPicsDir   = (SITE_URL.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                                $sPackagingDir = ($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                
                                for($i = 0; $i < $iLabelingCount; $i ++)
                                {
                                    $iTableId      = $objDb->getField($i, 'id');
                                    $iDefectCodeId = $objDb->getField($i, 'defect_code_id');
                                    $iSampleNumber = $objDb->getField($i, 'sample_no');
                                    $sDefectPicture= $objDb->getField($i, 'picture');
?>
                                    <tr id="LRowNo<?=$i+1?>">
                                        <td width="55" align="center"><b><?=$i+1?></b></td>
                                        <td><?=$sLabelingDefectsList[$iDefectCodeId]?></td>
					<td width="100" align="center"><?=$iSampleNumber?></td>
                                        <td width="200" align="center">
<?
                                                if ($sDefectPicture != "" && @file_exists($sPackagingDir.$sDefectPicture))
                                                {
?>
                                            <br/><span>(<a href="<?= $sPkPicsDir ?><?= $sDefectPicture ?>" class="lightview"><?= $sDefectPicture ?></a>)&nbsp;</span>
<?
                                                }
?>
                                        </td>
			      </tr>
<?
                                }
                        }
?>
			    </table>
                                </div>
                                    
        <!-- Labeling Defects Starts -->
<?
                                echo "<h3>B- Labeling Defects</h3>";
                                
                                    $sSQL = "SELECT *
                                            FROM tbl_qa_labeling_defects
                                            WHERE audit_id='$Id'";
                                    $objDb->query($sSQL);

                                    $iLabelingCount = $objDb->getCount( );
                                   
?>
                                <div id="LabelingDefects">
				<input type="hidden" id="LCountRows" name="LCountRows" value="<?= $iLabelingCount ?>" />

			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="55" align="center"><b>#</b></td>
					<td><b>Defect</b></td>
					<td width="100" align="center"><b>Sample No</b></td>
					<td width="200" align="center"><b>Picture</b></td>
			      </tr>
			    </table>
                                <?
                            // $sLabelingDefectsList = getList("tbl_labeling_defects", "id", "CONCAT(code,' - ',defect)", "", "id");
                                $sLabelingDefectsList = getList("tbl_defect_codes", "id", "CONCAT(code, ' - ', defect)", "report_id='$iReportId' AND type_id='6'" , "id");
?>
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="LDefectsTable">
<?
                        if($iLabelingCount > 0)
                        {
                                @list($sPkYear, $sPkMonth, $sPkDay) = @explode("-", $AuditDate);
                                $sPkPicsDir   = (SITE_URL.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                                $sPackagingDir = ($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                
                                for($i = 0; $i < $iLabelingCount; $i ++)
                                {
                                    $iTableId      = $objDb->getField($i, 'id');
                                    $iDefectCodeId = $objDb->getField($i, 'defect_code_id');
                                    $iSampleNumber = $objDb->getField($i, 'sample_no');
                                    $sDefectPicture= $objDb->getField($i, 'picture');
?>
                                    <tr id="LRowNo<?=$i+1?>">
                                        <td width="55" align="center"><b><?=$i+1?></b></td>
                                        <td><?=$sLabelingDefectsList[$iDefectCodeId]?></td>
					<td width="100" align="center"><?=$iSampleNumber?></td>
                                        <td width="200" align="center">
<?
                                                if ($sDefectPicture != "" && @file_exists($sPackagingDir.$sDefectPicture))
                                                {
?>
                                            <br/><span>(<a href="<?= $sPkPicsDir ?><?= $sDefectPicture ?>" class="lightview"><?= $sDefectPicture ?></a>)&nbsp;</span>
<?
                                                }
?>
                                        </td>
			      </tr>
<?
                                }
                        }
?>
			    </table>
                                </div>
<?
                        }
                        
                        if (@in_array(6, $AuditSections))
                        {
                            echo "<h2 style='margin:0px;'>".$iSectionNo++." - Dimensions of Cartons</h2>";
                            
                            $iTotalCartons      = getDbValue("master_cartons", "tbl_qa_report_details", "audit_id='$Id'");
                            $sComments          = getDbValue("master_cartons_comments", "tbl_qa_report_details", "audit_id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="MasterCartonsTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td><b>Gross Weight (kg)</b></td>
                  <td width="80"><b>Length</b></td>
                  <td width="80"><b>Width</b></td>
                  <td width="80"><b>Height</b></td>
            </tr>
<?
            $sSQL = "SELECT *
                    FROM tbl_qa_carton_details
	         WHERE audit_id='$Id'";
            
            $objDb->query($sSQL);
            
            $iCount = $objDb->getCount();
                    
            if($iCount > 0)
            {                        
                        for($i=0; $i<$iCount; $i++)
                        {
?>
                            <tr>
                                <td><?=$i+1?><input type="hidden" name="TCartons[]" value=""></td>
                                <td><?=$objDb->getField($i, "gross_weight")?></td>
                                <td><?=$objDb->getField($i, "length")?></td>
                                <td><?=$objDb->getField($i, "width")?></td>
                                <td><?=$objDb->getField($i, "height")?></td>
                            </tr>
<?
                        }                        
            }
            else
                $iCount = 0;
?>
        </table>
            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                      <td width="140">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                </tr>
                
                <tr class="sdRowHeader">
                     <td colspan="3"><b>Result & Comments</b></td>
                </tr>
                
                <tr>
                    <td width="80">Total Cartons</td>
                    <td width="20">:</td>
                    <td><?=$iTotalCartons?></td>
                </tr>
                
                <tr>
                    <td width="80">Comments</td>
                    <td width="20">:</td>
                    <td><?=$sComments?></td>
                </tr>
            </table>

	</div>
        <br/>
<?
                        }
                        
                        if (@in_array(8, $AuditSections))
                        {
                            echo "<h2 style='margin:0px;'>".$iSectionNo++." - Signatures</h2>";
                            
                            $sAuditCode   = getDbValue("audit_code", "tbl_qa_reports", "id='$Id'"); 
                            $sAuditDate   = getDbValue("audit_date", "tbl_qa_reports", "id='$Id'"); 
                            @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

                            $sInspectorSignature = "";
                            $sManufactureSignature = "";

                            if (@file_exists($sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_inspector.jpg") && @filesize($sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_inspector.jpg"))
                                    $sInspectorSignature = "{$sAuditCode}_inspector.jpg";

                            if (@file_exists($sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_manufacturer.jpg") && @filesize($sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_manufacturer.jpg"))
                                    $sManufactureSignature = "{$sAuditCode}_manufacturer.jpg";


                            $sInspector     = getDbValue("signatures_inspector", "tbl_qa_reports", "id='$Id'");
                            $sManufacturer  = getDbValue("signatures_manufacturer", "tbl_qa_reports", "id='$Id'");
                            $sSigComments   = getDbValue("signatures_comments", "tbl_qa_reports", "id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3>Signature info</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="MasterCartonsTable">
            <tr>
                <td width="100"><b>Inspector:</b></td>
                <td><?=$sInspector?>
                <td width="250">
<?
                        if($sInspectorSignature != "")
                        {
?>
                                <br/><a href="<?= SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sInspectorSignature) ?>" class="lightview" rel="gallery[picture]" title="<?= utf8_encode($sInspectorSignature) ?> :: :: topclose: true"><?= @basename($sInspectorSignature) ?></a>
<?
                        }
?>
                </td>
            </tr>
                <tr>
                <td width="100"><b>Manufacturer:</b></td>
                <td><?=$sManufacturer?>
                <td width="250">
<?
                        if($sManufactureSignature != "")
                        {
?>
                                <br/><a href="<?= SIGNATURES_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/".@basename($sManufactureSignature) ?>" class="lightview" rel="gallery[picture]" title="<?= utf8_encode($sManufactureSignature) ?> :: :: topclose: true"><?= @basename($sManufactureSignature) ?></a>
<?
                        }
?>
                </td>
            </tr>
        </table>
        <br/>
            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                      <td width="140">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                </tr>
                 <tr class="sdRowHeader">
                     <td colspan="3"><b>Signature Comments</b></td>
                </tr>                
                <tr>
                    <td width="80">Comments</td>
                    <td width="20">:</td>
                    <td><?=$sSigComments?></td>
                </tr>

            </table>
	</div>
<?
                        }
                        
                        if (@in_array(13, $AuditSections))
                        {
                            echo "<h2 style='margin:0px;'>".$iSectionNo++." - Airway Bill for Samples</h2>";
                            
                            $sAirwayBill    = getDbValue("airway_bill_applicable", "tbl_qa_report_details", "audit_id='$Id'");
                            $sBillNumber    = getDbValue("airway_bill_number", "tbl_qa_report_details", "audit_id='$Id'");
                            $sAWBComments   = getDbValue("airway_bill_comments", "tbl_qa_report_details", "audit_id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
                <table border="0" cellpadding="3" cellspacing="0" width="100%">
                    <tr>
                    <td width="240">Is Airway Bill No. Applicable for this?<span class="mandatory">*</span></td>
                    <td width="20" align="center">:</td>
                    <td>
                        <?=($sAirwayBill == 'Y')?'Yes':($sAirwayBill == 'N'?'No':'')?> &nbsp;
                    </td>
                    </tr>
                        
                    <tr><td colspan="3">&nbsp;</td></tr>
                    
                    <tr id="ToggleRowId" style="<?=($sAirwayBill == 'Y'?'':'display:none;')?>">
                        <td width="140">Airway Bill No.<span class="mandatory">*</span></td>
                        <td width="20" align="center">:</td>
                        <td><?=$sBillNumber?></td>
                    </tr>

                    <tr valign="top">
                          <td width="140">Comments</td>
                          <td width="20" align="center">:</td>
                          <td><?= $sAWBComments?></td>
                    </tr>
                </table>
	</div>
<?
                        }
                        
                       ?>
			  <table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
			    <tr valign="top">
				  <td width="50%">

				    <h2>Work-ManShip</h2>

				    <table border="0" cellpadding="3" cellspacing="0" width="100%">
					  <tr>
					    <td width="140">Total GMTS Inspected</td>
					    <td width="20" align="center">:</td>
					    <td><?= $iTotalGmts ?> (Pcs)</td>
					  </tr>

					  <tr>
					    <td># of GMTS Defective</td>
					    <td align="center">:</td>
					    <td><?= $iGmtsDefective ?> (Pcs)</td>
					  </tr>

					  <tr>
					    <td>Max Allowable Defects</td>
					    <td align="center">:</td>
					    <td><?= $iMaxDefects ?></td>
					  </tr>

					  <tr>
					    <td>Number of Defects</td>
					    <td align="center">:</td>
					    <td><?= (int)$iDefects ?></td>
					  </tr>

					  <tr>
					    <td>D.H.U</td>
					    <td align="center">:</td>
					    <td><?= formatNumber($fDhu) ?>%</td>
					  </tr>
				    </table>

				  </td>

				  <td width="50%">

				    <h2>Assortment</h2>

				    <table border="0" cellpadding="3" cellspacing="0" width="100%">
					  <tr>
					    <td width="140">Total Cartons Inspected</td>
					    <td width="20" align="center">:</td>
					    <td><?= $iTotalCartons ?></td>
					  </tr>

					  <tr>
					    <td># of Cartons Rejected</td>
					    <td align="center">:</td>
					    <td><?= $iCartonsRejected ?></td>
					  </tr>

					  <tr>
					    <td>% Defective</td>
					    <td align="center">:</td>
					    <td><?= $fPercentDecfective ?></td>
					  </tr>

					  <tr>
					    <td>Acceptable Standard</td>
					    <td align="center">:</td>
					    <td><?= $fStandard ?> %</td>
					  </tr>

					  <tr>
					    <td>D.H.U</td>
					    <td align="center">:</td>
					    <td><?= @round(( ($fCartonsRejected / $fTotalCartons) * 100), 2) ?>%</td>
					  </tr>
				    </table>

				  </td>
			    </tr>
			  </table>
		    </div>

		    <br />
		    <h2>Quantities</h2>

<?
	$sSelectedPos = $iPoId;

	if ($sAdditionalPos != "")
		$sSelectedPos .= ",{$sAdditionalPos}";
	
	
	$sSQL = "SELECT SUM(pc.order_qty)
	         FROM tbl_po po, tbl_po_colors pc
			 WHERE po.id=pc.po_id AND FIND_IN_SET(po.id, '$sSelectedPos') AND '$sColors' LIKE CONCAT('%', REPLACE(pc.color, ',', ' '), '%') AND pc.style_id='$iStyle'";
	$objDb->query($sSQL);
	
	$iOrderQty = $objDb->getField(0, 0);
?>
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="140">Order Qty</td>
			    <td width="20" align="center">:</td>
			    <td><?= $iOrderQty ?></td>
			    <td width="140">Total Cartons Required</td>
			    <td width="20" align="center">:</td>
			    <td><?= $fCartonsRequired ?></td>
			  </tr>

			  <tr>
			    <td>Ship Qty</td>
			    <td align="center">:</td>
			    <td><?= $iShipQty ?></td>
			    <td>Total Cartons Shipped</td>
			    <td align="center">:</td>
			    <td><?= $fCartonsShipped ?></td>
			  </tr>

			  <tr>
			    <td>Re-Screen Qty</td>
			    <td align="center">:</td>
			    <td><?= $iReScreenQty ?></td>
			    <td>Deviation</td>
			    <td align="center">:</td>
			    <td><?= @round(( ($fCartonsShipped / $fCartonsRequired) * 100), 2) ?>%</td>
			  </tr>

			  <tr>
			    <td>Deviation</td>
			    <td align="center">:</td>
			    <td colspan="4"><?= @round(( ($iShipQty / $iOrderQty) * 100), 2) ?>%</td>
			  </tr>
		    </table>

		    <br />
		    <h2>Status & Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="140">Approved Sample</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sApprovedSample ?></td>
			  </tr>

			  <tr>
			    <td>Shipping Mark</td>
			    <td align="center">:</td>
			    <td><?= (($sShippingMark == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
			    <td>Packing Check</td>
			    <td align="center">:</td>
			    <td><?= (($sPackingCheck == "Y") ? "Yes" : "No") ?></td>
			  </tr>

			  <tr>
			    <td>Carton Size</td>
			    <td align="center">:</td>
			    <td><?= (float)$iLength ?> x <?= (float)$iWidth ?> x <?= (float)$iHeight ?> <?= $sUnit ?></td>
			  </tr>
<?
	if ($iReportId != 12)
	{
?>
			  <tr>
				<td>Knitted (%)</td>
				<td align="center">:</td>
				<td><?= (($fKnitted == 0) ? "Not Provided" : $fKnitted) ?></td>
			  </tr>

			  <tr>
				<td>Dyed (%)</td>
				<td align="center">:</td>
				<td><?= (($fDyed == 0) ? "Not Provided" : $fDyed) ?></td>
			  </tr>
<?
	}
?>

			  <tr>
				<td>Cutting</td>
				<td align="center">:</td>
				<td><?= (($iCutting == 0) ? "Not Provided" : $iCutting) ?></td>
			  </tr>

			  <tr>
				<td>Sewing</td>
				<td align="center">:</td>
				<td><?= (($iSewing == 0) ? "Not Provided" : $iSewing) ?></td>
			  </tr>

			  <tr>
				<td>Finishing</td>
				<td align="center">:</td>
				<td><?= (($iFinishing == 0) ? "Not Provided" : $iFinishing) ?></td>
			  </tr>

			  <tr>
				<td>Packing</td>
				<td align="center">:</td>
				<td><?= (($iPacking == 0) ? "Not Provided" : $iPacking) ?></td>
			  </tr>

			  <tr>
				<td>Final Audit Date</td>
				<td align="center">:</td>
				<td><?= (($sFinalAuditDate != "0000-00-00") ? formatDate($sFinalAuditDate) : "Not Provided") ?></td>
			  </tr>

			  <tr valign="top">
			    <td>QA Comments</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>
