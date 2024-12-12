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
	**  Project Developer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmatullah Bhatti                                                          **
	**      Email :  rehmatullahbhatti@gmail.com                                                 **
	**      Phone :  +92 344 40 43 675                                                           **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/
?>
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
                  <td width="80"><b>Options</b></td>
            <input type="hidden" name="Id" value="<?=$Id?>">
            </tr>
<?
                $iCounter = 1;
                
                foreach($iColors as $sColor)
                {
?>
                    <tr>
                        <td><?=$iCounter?></td>
                        <td><b style="color: gray;"><?=$sColor?></b><input type="hidden" name="Color[]" value="<?=$sColor?>"></td>
                        <td><img src="images/icons/plus.png" id="Fabric_<?=$iCounter?>" title="Toggle Options" onclick="ToggleWeights('ColorRow',this.id);"/></td>
                    </tr>
                    <tr id="ColorRow<?=$iCounter?>" style="display:none;">
 
                        <td colspan="3">
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
                                    
                                    for($j=1; $j<=5; $j++)
                                    {
                                        $iSerial    = $objDb->getField($j-1, "serial");
                                        $fWeight    = $objDb->getField($j-1, "weight");
                                        $sPictures  = getList("tbl_qa_weight_pictures", "picture", "picture", "audit_id='$Id' AND `type`= 'F' AND color LIKE '$sColor' AND serial = '$j'");
?>
                                        <tr>
                                            <td><?=$j?></td>  
                                            <td><input type="text" name="Weight<?=$iCounter?>[]" size="10" value="<?=$fWeight?>"></td>
                                            <td><input type="file" name="File<?=$iCounter.'_'.$j?>[]" value="" multiple="">
<?
                                        if(!empty($sPictures))
                                        {
                                            foreach($sPictures as $sPicture)
                                            {
?>
                                                <br/><a href="<?= CARTONS_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/".@basename($sPicture) ?>" class="lightview" rel="gallery[picture]" title="<?= utf8_encode($sPicture) ?> :: :: topclose: true"><?= @basename($sPicture) ?></a>                                                
<?
                                                if ($sUserRights['Delete'] == "Y" && $sPicture != "")
                                                {
?>
                                                <a href="includes/quonda/delete-weight-image.php?File=<?= @basename($sPicture) ?>&AuditDate=<?= $sAuditDate ?>&AuditId=<?=$Id?>&Type=F" onclick="return confirm('Are you SURE, You want to Delete this Image?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
                                                }
                                            }
                                        }
?>
                                            </td>
                                        </tr>
<?
                                    }
?>
                            <tr class="sdRowHeader">
                                <td colspan="2"><b>Result</b> [<?=$sColor?>]</td>
                                <td>    
                                    <select name="ColorResult[]">
                                        <option value="">Select Result</option>
                                        <option value="P" <?=($sWeightResults["{$sColor}"] == 'P'?'selected':'')?>>Pass</option>
                                        <option value="F" <?=($sWeightResults["{$sColor}"] == 'F'?'selected':'')?>>Fail</option>
                                        <option value="N" <?=($sWeightResults["{$sColor}"] == 'N'?'selected':'')?>>N/A</option>
                                    </select>
                                </td>
                            </tr>           
                            </table>
                        </td>
                    </tr>
<?
                    $iCounter ++;
                }
?>
                    <input type="hidden" name="TotalColors" value="<?=$iCounter-1?>">  
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
                        <td><b style="color: gray;"><?=$sColor?></b><input type="hidden" name="PieceColor[]" value="<?=$sColor?>"></td>
                        <td><img src="images/icons/plus.png" id="Piece_<?=$iCounter?>" title="Toggle Options" onclick="ToggleWeights('PieceColorRow',this.id);"/></td>
                    </tr>
                    <tr id="PieceColorRow<?=$iCounter?>" style="display:none;">
 
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
?>
                                        <tr>
                                            <td><?=$j?></td>  
                                            <td><input type="text" name="PieceWeight<?=$iCounter?>[]" size="10" value="<?=$fWeight?>"></td>
                                            <td><input type="file" name="PieceFile<?=$iCounter.'_'.$j?>[]" value="" multiple="">
<?
                                        if(!empty($sPictures))
                                        {
                                            foreach($sPictures as $sPicture)
                                            {
?>
                                                <br/><a href="<?= CARTONS_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/".@basename($sPicture) ?>" class="lightview" rel="gallery[picture]" title="<?= utf8_encode($sPicture) ?> :: :: topclose: true"><?= @basename($sPicture) ?></a>                                                
<?
                                                if ($sUserRights['Delete'] == "Y" && $sPicture != "")
                                                {
?>
                                                <a href="includes/quonda/delete-weight-image.php?File=<?= @basename($sPicture) ?>&AuditDate=<?= $sAuditDate ?>&AuditId=<?=$Id?>&Type=P" onclick="return confirm('Are you SURE, You want to Delete this Image?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
                                                }
                                            }
                                        }
?>
                                            </td>
                                        </tr>
<?
                                    }
?>
                            <tr class="sdRowHeader">
                                <td colspan="2"><b>Result</b> [<?=$sColor?>]</td>
                                <td>    
                                    <select name="PColorResult[]">
                                        <option value="">Select Result</option>
                                        <option value="P" <?=($sPieceResults["{$sColor}"] == 'P'?'selected':'')?>>Pass</option>
                                        <option value="F" <?=($sPieceResults["{$sColor}"] == 'F'?'selected':'')?>>Fail</option>
                                        <option value="N" <?=($sPieceResults["{$sColor}"] == 'N'?'selected':'')?>>N/A</option>
                                    </select>
                                </td>
                            </tr>           
                            </table>
                        </td>
                    </tr>
<?
                    $iCounter ++;
                }
?>
                    <input type="hidden" name="TotalColors" value="<?=$iCounter-1?>">  
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
                    <td>    
                        <select name="Result" id="Result" required=''>
                            <option value="">Select Result</option>
                            <option value="P" <?=($sFabricResult == 'P'?'selected':'')?>>Pass</option>
                            <option value="F" <?=($sFabricResult == 'F'?'selected':'')?>>Fail</option>
                            <option value="N" <?=($sFabricResult == 'N'?'selected':'')?>>N/A</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td width="80">Comments</td>
                    <td>    
                        <textarea name="Comments" Style="width:98%;" rows="5"><?=$sFabricComments?></textarea>
                    </td>
                </tr>

            </table>
	</div>

<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript">
	    
	function validateForm( )
	{
		var objFV = new FormValidator("frmData");

        	return true;
	}
	
	
jQuery.noConflict();
    function ToggleWeights(RowName,MergedId) 
    {
        var fields      = MergedId.split('_');
        var FabricPiece = fields[0];
        var Id          = fields[1];

        jQuery('#'+RowName+Id).toggle('swing');
        
         var src = ((jQuery("#"+MergedId).attr('src') === 'images/icons/plus.png')? 'images/icons/minus.png' : 'images/icons/plus.png');
         jQuery("#"+MergedId).attr('src', src);
    }

 jQuery('#BtnSave').on('click',function(){
     
     if(jQuery("#Result").val() != "")
        jQuery("#frmData").submit();
     else 
         return false;
 });
</script> 