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

?>
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="120">Vendor</td>
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
				<td><?= ($sStyles == "")?getDbValue("CONCAT(style_name, '-', style)", "tbl_styles", "id='$iStyle'"):getDbValue("GROUP_CONCAT(CONCAT(style_name, '-', style) SEPARATOR ', ')", "tbl_styles", "id IN ($sStyles)"); ?></td>
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
			  <tr>
			    <td>Audit Stage</td>
			    <td align="center">:</td>
			    <td><?= $sAuditStagesList[$sAuditStage] ?></td>
			  </tr>
                          
                          <tr>
			    <td>HOH I.O. No.</td>
			    <td align="center">:</td>
			    <td><?= $sHohOrderNo ?></td>
			  </tr>

                          <tr>
			    <td>Sampling Plan</td>
			    <td align="center">:</td>
			    <td><?= ($CheckLevel == 1)?'Single':($CheckLevel == 2?'Double':'')?></td>
			  </tr>
                          
<?
	switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
		case "H" : $sAuditResult = "Hold"; break;
                case "S" : $sAuditResult = "Subject to Client Decision"; break;
	}
?>
                          <tr>
				<td>Audit Result</td>
				<td align="center">:</td>
				<td><?= $sAuditResult ?></td>
			  </tr>
		    </table>

			<br />
			<h2>Inspection Sections</h2>
<button class="accordion"><b>1- Product Conformity</b></button>
<div class="panel">
    <p>
        <?
            $sStatementList = getList("tbl_statements", "id", "statement", "FIND_IN_SET('1', sections)");
            $sConformities  = getList("tbl_qa_product_conformity", "serial", "observation", "audit_id='$Id'");
            
            $sPcResult    = getDbValue("product_conformity_result", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sPcComments  = getDbValue("product_conformity_comments", "tbl_qa_hohenstein", "audit_id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3>Observations/ Differences</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="ProductConformityTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td><b>Product Difference</b></td>
            </tr>
<?
            $iCounter = 1;
            if(count($sConformities) > 0)
            {
                foreach($sConformities as $iSerial => $sObservation)
                {
?>
                    <tr>
                        <td><?=$iCounter++?></td>
                        <td id="StatementsId"><?=$sObservation?></td>
                    </tr>
<?
                }
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
                    <td><?=($sPcResult == 'P')?"Pass":($sPcResult == 'F'?"Fail":'N/A')?></td>
                </tr>
                
                <tr>
                    <td width="80">Comments</td>
                    <td><?=$sPcComments?></td>
                </tr>

            </table>
	</div>
    </p>
</div>

<button class="accordion"><b>2- Weight Conformity</b></button>
<div class="panel">
    <p>
        <?
            @list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);
            $sWeightsDir = ($sBaseDir.CARTONS_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/");
             
            $iColors        = explode(",", $sColors);            
            $sColorResults  = getList("tbl_qa_weight_conformity", "color", "result", "audit_id='$Id'");
            
            $sWcResult    = getDbValue("weight_conformity_result", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sWcComments  = getDbValue("weight_conformity_comments", "tbl_qa_hohenstein", "audit_id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="WeightConformityTable">
<?
                $iCounter = 1;
           
                foreach($iColors as $sColor)
                {
?>
                    <tr>
                        <td><h2><?=$sColor?></h2></td>
                    </tr>
                    <tr id="ColorRow<?=$iCounter?>">
                        <td>
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="WeightConformityTable">
                            <tr class="sdRowHeader">
                                  <td width="20"><b>#</b></td>
                                  <td width="200"><b>Actual Weight</b></td>
                                  <td><b>Picture</b></td>
                            </tr>
<?
                                    $sSQL = "SELECT serial, weight, picture
                                                FROM tbl_qa_weight_details
                                                WHERE audit_id='$Id' AND color LIKE '$sColor'";
                                    $objDb->query($sSQL); 
                                    
                                    for($j=1; $j<=5; $j++)
                                    {
                                        $iSerial    = $objDb->getField($j-1, "serial");
                                        $fWeight    = $objDb->getField($j-1, "weight");
                                        $sPictures  = getList("tbl_qa_weight_pictures", "picture", "picture", "audit_id='$Id' AND color LIKE '$sColor' AND serial = '$j'");
                                        
                                        if($fWeight != "" && $fWeight != "0")
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
                                <td><?=($sColorResults["$sColor"] == "P"?"Pass":"Fail")?></td>
                            </tr>           
                            </table>
                        </td>
                    </tr>
<?
                    $iCounter ++;
                }
?>
        </table><br/>
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
                    <td><?=($sWcResult == 'P')?"Pass":($sWcResult == 'F'?"Fail":'N/A')?></td>
                </tr>
                
                <tr>
                    <td width="80">Comments</td>
                    <td><?=$sWcComments?></td>
                </tr>

            </table>
	</div>
    </p>
</div>

<button class="accordion"><b>3- EAN- Code</b></button>
<div class="panel">
    <p>
        <?
            $SizesList      = getList("tbl_sizes", "id", "size", "id IN ($Sizes)");            
            $sBarCodeFormat = getDbValue("barcode_format", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sEanCodeResult = getDbValue("ean_result", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sEanComments   = getDbValue("ean_comments", "tbl_qa_hohenstein", "audit_id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3>Ean Code</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="EanCodeTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="60"><b>Size</b></td>
                  <td><b>Position</b></td>
                  <td width="180"><b>Code</b></td>
                  <td width="130"><b>Result</b></td>
            </tr>
<?
            $sSQL = "SELECT *
                        FROM tbl_qa_ean_codes
                        WHERE audit_id='$Id'";
            $objDb->query($sSQL);
            
            $iCount = $objDb->getCount( );
            
            if($iCount > 0)
            {
                for($i=0; $i<$iCount; $i++)
                {                    
                    $iSerial    = $objDb->getField($i, "serial");
                    $iSizeId    = $objDb->getField($i, "size_id");
                    $sPosition  = $objDb->getField($i, "position");
                    $sCode      = $objDb->getField($i, "code");
                    $sEanResult = $objDb->getField($i, "result");
                    
?>
                    <tr>
                        <td><?=$i+1?></td>
                        <td id="SizesRow"><?=$SizesList[$iSizeId]?></td>
                        <td id="PositionRow"><?=$sPosition?></td>
                        <td id="EanCodeRow"><?=$sCode?></td>    
                        <td id="EanResultRow"><?=($sEanResult == 'P'?'Pass':'Fail')?></td>
                    </tr>
<?
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
                    <td><?=($sEanCodeResult == "P")?"Pass":($sEanCodeResult == "F"?"Fail":"N/A")?></td>
                </tr>
                <tr>
                    <td width="120">Barcode Format</td>
                    <td width="20">:</td>
                    <td id="ResultsRow"><?=($sBarCodeFormat == 1 ?"EAN-8":"EAN-13")?></td>
                </tr> 
                <tr>
                    <td width="120">Comments</td>
                    <td width="20">:</td>
                    <td><?=$sEanComments?></td>
                </tr>

            </table>
	</div>

    </p>
</div>

<?
    if($sAuditStage == 'F')
    {
?>
<button class="accordion"><b>4- Assortment</b></button>
<div class="panel">
    <p>
    <?
            $sSQL = "SELECT *
                    FROM tbl_qa_assortment
	         WHERE audit_id='$Id'";
        
	$objDb->query($sSQL);
        
	$iTotalAssorted  = $objDb->getField(0, "total_cartons_tested");
	$iWrongAssorted  = $objDb->getField(0, "wrong_assorted_cartons");
        $sAsResult         = $objDb->getField(0, "result");
        $sAsComments       = $objDb->getField(0, "comments");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3>Assortment</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="AssortmentTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td><b>Gross Weight (kg)</b></td>
                  <td width="80"><b>Length</b></td>
                  <td width="80"><b>Width</b></td>
                  <td width="80"><b>Height</b></td>
            </tr>
<?
            $sSQL = "SELECT *
                    FROM tbl_qa_assortment_details
	         WHERE audit_id='$Id'";
        
            $objDb->query($sSQL);
            
            $iCount = $objDb->getCount();
        
            if($iCount > 0)
            {
                for($i=0; $i<$iCount; $i++)
                {
                    $iCartonNo      = $objDb->getField($i, "carton_no");
                    $iGrossWeight   = $objDb->getField($i, "gross_weight");
                    $iLength        = $objDb->getField($i, "length");
                    $iWidth         = $objDb->getField($i, "width");
                    $iHeight        = $objDb->getField($i, "height");
?>
                    <tr>
                        <td><?=$i+1?></td>
                        <td id="WeightId"><?=$iGrossWeight?></td>
                        <td id="LengthId"><?=$iLength?></td>
                        <td id="WidthId"><?=$iWidth?></td>
                        <td id="HeightId"><?=$iHeight?></td>
                    </tr>
<?
                }
            }
?>
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
                    <td width="80">Total Cartons Tested</td>
                    <td width="20">:</td>
                    <td><?=$iTotalAssorted?></td>
                </tr>
                <tr>
                    <td width="80">Wrong Assorted Cartons</td>
                    <td width="20">:</td>
                    <td><?=$iWrongAssorted?></td>
                </tr>
                <tr>
                    <td width="80">Result</td>
                    <td width="20">:</td>
                    <td><?=($sAsResult == 'P')?"Pass":($sAsResult == 'F'?"Fail":"N/A")?></td>
                </tr>
                <tr>
                    <td width="80">Comments</td>
                    <td width="20">:</td>
                    <td><?=$sAsComments?></td>
                </tr>

            </table>
	</div>

    </p>
</div>

<button class="accordion"><b>5- Master Cartons</b></button>
<div class="panel">
    <p>
        <?
            $iTotalCartons = getDbValue("master_cartons", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sMcResult    = getDbValue("master_cartons_result", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sMcComments  = getDbValue("master_cartons_comments", "tbl_qa_hohenstein", "audit_id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3>Master Cartons</h3>
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
                    FROM tbl_qa_master_cartons
	         WHERE audit_id='$Id'";
        
            $objDb->query($sSQL);
            
            $iCount = $objDb->getCount();
        
            if($iCount > 0)
            {
                for($i=0; $i<$iCount; $i++)
                {
                    $iCartonNo      = $objDb->getField($i, "carton_no");
                    $iGrossWeight   = $objDb->getField($i, "gross_weight");
                    $iLength        = $objDb->getField($i, "length");
                    $iWidth         = $objDb->getField($i, "width");
                    $iHeight        = $objDb->getField($i, "height");
?>
                    <tr>
                        <td><?=$i+1?></td>
                        <td id="WeightId"><?=$iGrossWeight?></td>
                        <td id="LengthId"><?=$iLength?></td>
                        <td id="WidthId"><?=$iWidth?></td>
                        <td id="HeightId"><?=$iHeight?></td>
                    </tr>
<?
                }
            }
 ?>
        </table>
            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                      <td width="140">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                </tr>
                <tr class="sdRowHeader">
                     <td colspan="3"><b>Master Carton Result & Comments</b></td>
                </tr>
                
                <tr>
                    <td width="80">Total Cartons</td>
                    <td width="20">:</td>
                    <td><?=$iTotalCartons?></td>
                </tr>
                <tr>
                    <td width="80">Result</td>
                    <td width="20">:</td>
                    <td><?=($sMcResult == 'P')?'Pass':($sMcResult == 'F'?"Fail":"N/A")?></td>
                </tr>
                <tr>
                    <td width="80">Comments</td>
                    <td width="20">:</td>
                    <td><?=$sMcComments?></td>
                </tr>

            </table>
	</div>
    </p>
</div>

<?
    }
?>
<button class="accordion"><b><?=($sAuditStage == 'F'?6:4)?>- Child Labor</b></button>
<div class="panel">
    <p>
        <?
        $sSQL = "SELECT child_labour_site_name, child_labour_site_address, child_labour_site_phone, child_labour_site_fax, child_labour_site_email, child_labour_site_person, 
                    child_labour_conformance, child_labour_non_conformance, child_labour_comments, 
                    child_labour_recommendations, child_labour_deadline, child_labour_result
                FROM tbl_qa_hohenstein
                WHERE audit_id='$Id'";
        
	$objDb->query($sSQL);
        
        $sSiteName          = $objDb->getField(0, "child_labour_site_name");
        $sSiteFax           = $objDb->getField(0, "child_labour_site_fax");
        $sSiteAddress       = $objDb->getField(0, "child_labour_site_address");
        $sSiteEmail         = $objDb->getField(0, "child_labour_site_email");
        $sSitePhone         = $objDb->getField(0, "child_labour_site_phone");
        $sSitePerson        = $objDb->getField(0, "child_labour_site_person");
	$sCLConformance     = $objDb->getField(0, "child_labour_conformance");
	$sCLNonConformance  = $objDb->getField(0, "child_labour_non_conformance");
        $sCLComments        = $objDb->getField(0, "child_labour_comments");
        $sCLRecommendation  = $objDb->getField(0, "child_labour_recommendations");
        $sCLDeadLine        = $objDb->getField(0, "child_labour_deadline");
        $sCLResult          = $objDb->getField(0, "child_labour_result");
            
        $sChildLabourQuestions = getList("tbl_child_labour_questions", "id", "question", "status='A'", "position");
        $sChildLabourResults   = getList("tbl_qa_child_labour_details", "question_id", "answer", "audit_id='$Id'");
        $sChildLabourRemarks   = getList("tbl_qa_child_labour_details", "question_id", "remarks", "audit_id='$Id'");
?>
         <div style="margin: 10px;">
             <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <td width="120"><b>Site Name</b></td>
                    <td width="20">:</td>
                    <td><?=$sSiteName?></td>

                    <td width="130"><b>Site Fax</b></td>
                    <td width="20">:</td>
                    <td><?=$sSiteFax?></td>
                </tr>
                
                <tr>
                    <td width="80"><b>Site Address</b></td>
                    <td width="20">:</td>
                    <td  width="80"><?=$sSiteAddress?></td>
                      
                    <td width="80"><b>Site Email</b></td>
                    <td width="20">:</td>
                    <td  width="80"><?=$sSiteEmail?></td>
                </tr>
                
                <tr>
                    <td width="80"><b>Site Phone</b></td>
                    <td width="20">:</td>
                    <td><?=$sSitePhone?></td>
                      
                    <td width="80"><b>Site Contact Person</b></td>
                    <td width="20">:</td>
                    <td  width="80"><?=$sSitePerson?></td>
                </tr>
                
                <tr>
                    <td width="80"><b>Result</b></td>
                    <td width="20">:</td>
                    <td colspan="4"><?=($sCLResult == 'P'?'Pass':'Fail')?></td>
                </tr>
            </table>
         </div>
    
        <div id="MyMainDiv" style="<?=($sCLResult == 'P' || $sCLResult == '')?'display: none;':''?>">
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">	
            <h3>Child Labour Check</h3>            
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="250"><b>Section</b></td>
                  <td><b>Comments</b></td>
            </tr>
            <tr>
                <td>1</td>
                <td>Conformance</td>
                <td><?=$sCLConformance?></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Non-Conformance</td>
                <td><?=$sCLNonConformance?></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Any other comments</td>
                <td><?=$sCLComments?></td>
            </tr>
            <tr>
                <td>4</td>
                <td>Recommendation for corrective action</td>
                <td><?=$sCLRecommendation?></td>
            </tr>
            <tr>
                <td>5</td>
                <td>Deadline for implementiong corrective action</td>
                <td><?=$sCLDeadLine?></td>
            </tr>
        </table>
        <br/>
        <h3>Interview - Questions for Child Labor Inspection</h3>
         <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr class="sdRowHeader">
                  <td width="15"><b>#</b></td>
                  <td width="300"><b>Question</b></td>
                  <td width="110"><b>Result</b></td>
                  <td><b>Remarks</b></td>
            </tr>
<?
            $iCounter = 1;    
            foreach($sChildLabourQuestions as $iQuestion => $sQuestion)
            {
?>
                <tr>
                    <td><?=$iCounter++?></td>
                    <td><?=$sQuestion?></td>
                    <td><?=($sChildLabourResults[$iQuestion] == 'Y'?'Yes':'No')?></td>
                    <td><?=$sChildLabourRemarks[$iQuestion]?></td>
                </tr>
<?
            }
?>
         </table><br/>
         <h3>Child Labour Record Sheet</h3>
         <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="ChildLaborTable" style="text-align:center;">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="90"><b>Name</b></td>
                  <td width="75"><b>Birthday</b></td>
                  <td width="60"><b>Attending School</b></td>
                  <td width="60"><b>Present During Regular Class Session</b></td>
                  <td width="65"><b>Met in Non Hazardous Areas</b></td>
                  <td width="60"><b>Receives Education</b></td>
                  <td width="80"><b>Since When in the Company</b></td>
                  <td width="70"><b>Working under ILO Convention 138 exceptions</b></td>
                  <td><b>Comments</b></td>
            </tr>
<?      
            $sSQL = "SELECT *
                    FROM tbl_qa_child_labour
	         WHERE audit_id='$Id'";
        
            $objDb->query($sSQL);
            
            $iCount = $objDb->getCount();
        
            if($iCount > 0)
            {
                for($i=0; $i<$iCount; $i++)
                {
                    $sName                  = $objDb->getField($i, "name");
                    $sBirthMonth            = $objDb->getField($i, "birth_month");
                    $sBirthYear             = $objDb->getField($i, "birth_year");
                    $sAttendSchool          = $objDb->getField($i, "attended_school");
                    $sSchoolLessons         = $objDb->getField($i, "school_lessons");
                    $sNonHazerdous          = $objDb->getField($i, "non_hazaradous_areas");
                    $sEducation             = $objDb->getField($i, "education");
                    $sJoiningMonth          = $objDb->getField($i, "joining_month");
                    $sJoiningYear           = $objDb->getField($i, "joining_year");
                    $sWorkUnderIlo          = $objDb->getField($i, "working_under_ilo");
                    $sChildLabourComments   = $objDb->getField($i, "comments");
                    
?>
               <tr>
                <td><?=$i+1?></td>
                <td><?=$sName?></td>
                <td id="BirthdayId"><?=$sBirthMonth." / ".$sBirthYear?></td>
                <td id="AttendSchoolId"><?=($sAttendSchool == 'Y'?'Yes':'No')?></td>
                <td id="RegClassId"><?=($sSchoolLessons == 'Y'?'Yes':'No')?></td>
                <td id="HazardAreaId"><?=($sNonHazerdous == 'Y'?'Yes':'No')?></td>
                <td id="ReceiveEduId"><?=($sEducation == 'Y'?'Yes':'No')?></td>
                <td id="CompanyId">
                    <?=$sJoiningMonth." / ".$sJoiningYear?>
                </td>
                <td id="WorkingIloId"><?=($sWorkUnderIlo == 'Y'?'Yes':'No')?></td>
                <td><?=$sChildLabourComments?></td>
            </tr>
                    
<?
                }
            }
?>
         </table> 
	</div>
        </div>    

    </p>
</div>

<button class="accordion"><b><?=($sAuditStage == 'F'?7:5)?>- Signatures</b></button>
<div class="panel">
    <p>
        <?
            @list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);
            
            $sInspectorSignature = "";
            $sManufactureSignature = "";

            if (@file_exists($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_inspector.jpg") && @filesize($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_inspector.jpg"))
                    $sInspectorSignature = "{$sAuditCode}_inspector.jpg";

            if (@file_exists($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_manufacturer.jpg") && @filesize($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_manufacturer.jpg"))
                    $sManufactureSignature = "{$sAuditCode}_manufacturer.jpg";
            
            $sInspector     = getDbValue("signatures_inspector", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sManufacturer  = getDbValue("signatures_manufacturer", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sSigComments      = getDbValue("signatures_comments", "tbl_qa_hohenstein", "audit_id='$Id'");            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3>Signature info</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="MasterCartonsTable">
            <tr>
                <td width="100"><b>Inspector : </b></td>
                <td><?=$sInspector?></td>
                <td width="250">
<?
                        if($sInspectorSignature != "")
                        {
?>
                                <br/><a href="<?= SIGNATURES_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/".@basename($sInspectorSignature) ?>" class="lightview" rel="gallery[picture]" title="<?= utf8_encode($sInspectorSignature) ?> :: :: topclose: true"><?= @basename($sInspectorSignature) ?></a>
<?
                        }
?>
                </td>
            </tr>
                <tr>
                <td width="100"><b>Manufacturer : </b></td>
                <td><?=$sManufacturer?></td>
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
                <tr>
                    <td width="80"><b>Comments : </b></td>
                    <td><?=$sSigComments?></td>
                </tr>

        </table>
	</div>
    </p>
</div>

<button class="accordion"><b><?=($sAuditStage == 'F'?8:6)?>- Workmanship</b></button>
<div class="panel">
    <p>
        <h2 style="margin:0px;">Defects Details</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="50" align="center"><b>#</b></td>
				  <td><b>Code - Check Points</b></td>
				  <td width="100" align="center"><b>Defects</b></td>
				  <td width="200"><b>Area</b></td>
				  <td width="100" align="center"><b>Nature</b></td>
			    </tr>

<?
	$iDefects = 0;

	$sSQL = "SELECT * FROM tbl_qa_report_defects WHERE audit_id='$Id' ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for($i = 0; $i < $iCount; $i ++)
	{
		$iDefects += $objDb->getField($i, 'defects');

		$sSQL = ("SELECT code, defect FROM tbl_defect_codes WHERE id='".$objDb->getField($i, 'code_id')."'");
		$objDb2->query($sSQL);

		$sSQL = ("SELECT area FROM tbl_defect_areas WHERE id='".$objDb->getField($i, 'area_id')."'");
		$objDb3->query($sSQL);

		switch ($objDb->getField($i, "nature"))
		{
			case 1 : $sNature = "Major"; break;
			case 0 : $sNature = "Minor"; break;
			case 2 : $sNature = "Critical"; break;
		}
?>

			    <tr class="sdRowColor">
				  <td align="center"><?= ($i + 1) ?></td>
				  <td><?= $objDb2->getField(0, 0) ?> - <?= $objDb2->getField(0, 1) ?></td>
				  <td align="center"><?= $objDb->getField($i, 'defects') ?></td>
				  <td><?= $objDb3->getField(0, 0) ?></td>
				  <td align="center"><?= $sNature ?></td>
			    </tr>
<?
	}

	if ($iCount == 0)
	{
?>

			    <tr class="sdRowColor">
				  <td colspan="5" align="center">No Defect Found!</td>
			    </tr>
<?
	}
?>
			  </table>
			</div>
    </p>
</div>

<?
if($sAuditStage == 'F')
{
?>
<button class="accordion"><b>9- Carton Inspection Defects & Comments</b></button>
<div class="panel">
    <p>
        <!--- Packing Defects Starts------->
                                <h2 style="margin-bottom:0px;">9- Carton Inspection Defects & Comments</h2>
<?
                                    $sSQL = "SELECT packaging_result, packaging_comments, labeling_result, labeling_comments
                                            FROM tbl_qa_hohenstein
                                            WHERE audit_id='$Id'";
                                    $objDb->query($sSQL);

                                   $sPackingResult      = $objDb->getField(0, 'packaging_result');
                                   $sPackingComments    = $objDb->getField(0, 'packaging_comments');
                                   $sLabelingResult     = $objDb->getField(0, 'labeling_result');
                                   $sLabelingComments   = $objDb->getField(0, 'labeling_comments');
                                   
                                    $sSQL = "SELECT *
                                            FROM tbl_qa_packaging_defects
                                            WHERE audit_id='$Id'";
                                    $objDb->query($sSQL);

                                    $iPackagingCount = $objDb->getCount( );
                                   
?>
                                <div id="PackaginDefects">                                    

			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="55" align="center"><b>#</b></td>
					<td><b>Defect</b></td>
					<td width="100" align="center"><b>Sample No</b></td>
					<td width="200" align="center"><b>Picture</b></td>
			      </tr>
			    </table>
                                <?
                            $sPackagingDefectsList = getList("tbl_packaging_defects", "id", "CONCAT(code,' - ',defect)", "", "id");
?>
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="PDefectsTable">
<?
                        if($iPackagingCount > 0)
                        {
                                @list($sPkYear, $sPkMonth, $sPkDay) = @explode("-", $AuditDate);
                                $sPkPicsDir   = (SITE_URL.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                                $sPackagingDir = ($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                
                                for($i = 0; $i < $iPackagingCount; $i ++)
                                {
                                    $iTableId      = $objDb->getField($i, 'id');
                                    $iDefectCodeId = $objDb->getField($i, 'defect_code_id');
                                    $iSampleNumber = $objDb->getField($i, 'sample_no');
                                    $sDefectPicture= $objDb->getField($i, 'picture');
?>
                                    <tr id="PRowNo<?=$i+1?>">
                                        <td width="50" align="center"><b><?=$i+1?></b></td>
                                        <td><?=$sPackagingDefectsList[$iDefectCodeId]?></td>
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
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="55" align="center"><b>Result : </b></td>
                                        <td width="60"><?=($sPackingResult == 'P'?'Pass':'Fail')?></td>
					<td width="100" align="center"><b>Comments : </b></td>
                                        <td><?=$sPackingComments?></td>
			      </tr>                            
			    </table>
                                <!-- Packing Defects Ends --->

    </p>
</div>


<button class="accordion"><b>10- Sales Packaging Defects & Comments</b></button>
<div class="panel">
    <p>
        <!--- Labeling Defects Starts----->
                        <h2 style="margin-bottom:0px;">10- Sales Packaging Defects & Comments</h2>                        
<?
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
					<td width="50" align="center"><b>Delete</b></td>
			      </tr>
			    </table>
                                <?
                            $sLabelingDefectsList = getList("tbl_labeling_defects", "id", "CONCAT(code,' - ',defect)", "", "id");
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
                                        <td width="50" align="center"><b><?=$i+1?></b></td>
                                        <td><?=$sPDefect?></td>
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
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="55" align="center"><b>Result : </b></td>
                                        <td width="60"><?=($sLabelingResult == 'P'?'Pass':'Fail')?></td>
					<td width="100" align="center"><b>Comments : </b></td>
                                        <td><?=$sLabelingComments?></td>
			      </tr>                            
			    </table>
                                <!-- Labeling Defects Ends --->
    </p>
</div>
<?
}
?>

<button class="accordion"><b><?=($sAuditStage == 'F'?11:7)?>- Measurement Conformity</b></button>
<div class="panel">
    <p>
<?
	$iSizes  = @explode(",", $sSizes);

	if ($sSizes != "" && $iColors != "")
	{
		foreach ($iColors as $sColor)
		{
			foreach ($iSizes as $iSize)
			{
				$sSize         = getDbValue("size", "tbl_sizes", "id='$iSize'");
				$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");


				$sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings
						 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
						 WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSamplingSize' AND (qrs.color='$sColor' OR qrs.color='')
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
		    <h2 style="margin:0px;">Measurement Sheet (Size: <?= $sSize ?>, Color: <?= (($sColor == "") ? $sColors : $sColor) ?>)</h2>

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
		}
        }
?>
    </p>
</div>





			<br />
			<h2>Quantities & Comments</h2>
 <?
	$sSQL = "SELECT quantity FROM tbl_po WHERE id='$iPoId'";
	$objDb->query($sSQL);

	$iOrderQty = $objDb->getField(0, 0);

	if (count($sAdditionalPos) > 0)
	{
		$sSQL = "SELECT SUM(quantity) FROM tbl_po WHERE id IN ($sAdditionalPos)";
		$objDb->query($sSQL);

		$iOrderQty += $objDb->getField(0, 0);
	}
?>
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
                            <tr>
                                <td width="180">Order Qty</td>
                                <td width="20" align="center">:</td>
                                <td><?= $iOrderQty ?></td>
                            </tr>

                            <tr>
                                <td width="180">Ship Qty</td>
                                <td width="20" align="center">:</td>
                                <td><?= $ShipQty ?></td>
                            </tr>

                            <tr>
                                    <td width="140">Total GMTS Inspected<span class="mandatory">*</span></td>
                                    <td width="20" align="center">:</td>
                                    <td><?= $iTotalGmts ?> (Pcs)</td>
                            </tr>

                            <tr>
                                <td>Total Cartons Inspected</td>
                                <td align="center">:</td>
                                <td><?=$iCartonsInspected?></td>
                            </tr>
                                    

			  <tr valign="top">
			    <td>QA Comments</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>
