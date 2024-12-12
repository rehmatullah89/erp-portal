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
            $sAuditDate   = getDbValue("audit_date", "tbl_qa_reports", "id='$Id'"); 
            $shohOrderNo   = getDbValue("hoh_order_no", "tbl_qa_reports", "id='$Id'"); 
            @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
            $sWeightsDir = ($sBaseDir.$sBaseDir.CARTONS_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/");
             
            $sColors        = getDbValue("colors", "tbl_qa_reports", "id='$Id'");
            $iColors        = explode(",", $sColors);
            
            $sColorResults  = getList("tbl_qa_weight_conformity", "color", "result", "audit_id='$Id'");
            
            $sResult    = getDbValue("weight_conformity_result", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sComments  = getDbValue("weight_conformity_comments", "tbl_qa_hohenstein", "audit_id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3>Weight Conformity</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="WeightConformityTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td><b>Colors</b></td>
                  <td width="80"><b>Options</b></td>
            </tr>
<?
                $iCounter = 1;
           
                foreach($iColors as $sColor)
                {

                    $styleId = getDbValue('DISTINCT hiod.style_id', '`tbl_hoh_order_details` hiod INNER JOIN tbl_hoh_orders hio ON hio.id = hiod.hoh_order_id INNER JOIN tbl_hoh_order_style_details hiosd ON hiosd.id = hiod.style_detail_id', "hio.order_no ='$shohOrderNo' AND color='$sColor'");

                    $weight = getDbValue("weight","tbl_hoh_order_style_details","style_id='$styleId'");

?>
                    <tr>
                        <td><?=$iCounter?></td>
                        <td><?=$sColor?><input type="hidden" name="Color[]" value="<?=$sColor?>"></td>
                        <td><img src="images/icons/more.gif" id="<?=$iCounter?>" title="Toggle Options" onclick="ToggleWeights(this.id);"/></td>
                    </tr>
                    <tr id="ColorRow<?=$iCounter?>" style="display:none;">
 
                        <td colspan="3">
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                            <tr>                            
                                <td colspan="3">
                                <b>Expected Weight: <?=$weight?></b>
                                </td>
                            </tr> 
                            <tr>                            
                                <td colspan="3">
                                <b>Tolerance: -5/+5</b>
                                </td>
                            </tr> 
                            </table>                            
                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="WeightConformityTable">
  
                            <tr class="sdRowHeader">
                                  <td width="30"><b>#</b></td>
                                  <td><b>Actual Weight</b></td>
                                  <td width="400"><b>Picture</b></td>
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
                                        //$sPicture   = $objDb->getField($j-1, "picture");
                                        $sPictures  = getList("tbl_qa_weight_pictures", "picture", "picture", "audit_id='$Id' AND color LIKE '$sColor' AND serial = '$j'");
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
                                                <a href="includes/quonda/delete-weight-image.php?File=<?= @basename($sPicture) ?>&AuditDate=<?= $sAuditDate ?>&AuditId=<?=$Id?>" onclick="return confirm('Are you SURE, You want to Delete this Image?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
                                        <option value="P" <?=($sColorResults["$sColor"] == 'P'?'selected':'')?>>Pass</option>
                                        <option value="F" <?=($sColorResults["$sColor"] == 'F'?'selected':'')?>>Fail</option>
                                        <option value="N" <?=($sColorResults["$sColor"] == 'N'?'selected':'')?>>N/A</option>
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
                        <select name="Result" required="">
                            <option value="">Select Result</option>
                            <option value="P" <?=($sResult == 'P'?'selected':'')?>>Pass</option>
                            <option value="F" <?=($sResult == 'F'?'selected':'')?>>Fail</option>
                            <option value="N" <?=($sResult == 'N'?'selected':'')?>>N/A</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td width="80">Comments</td>
                    <td>    
                        <textarea name="Comments" Style="width:98%;" rows="5"><?=$sComments?></textarea>
                    </td>
                </tr>

            </table>
	</div>

            </td>
            </tr>
          </table>
            <div style="float: right; padding: 10px;">
            <input type="button" id="BtnSave" value="Save" title="Save" />
            <input type="button" value="Cancel"  title="Cancel" onclick="parent.hideLightview();" />
            </div>

  <script type="text/javascript" src="scripts/jquery.js"></script>
  <script type="text/javascript" src="scripts/jquery.tokeninput.js"></script>
  <link type="text/css" rel="stylesheet" href="css/jquery.tokeninput.css" />                
	<script type="text/javascript">
	    <!--

    function ToggleWeights(Id) 
    {
        var x = document.getElementById("ColorRow"+Id);
        if (x.style.display === "none") {
            x.style.display = "";
        } else {
            x.style.display = "none";
        }
    }

   
    -->

    jQuery('#BtnSave').on('click',function(){

        var countEmpty = 0;
        var countSelected = 0;

        jQuery('select[name^=ColorResult]').each(function(){
            
            if(jQuery(this).val()){
                countSelected++;
            } else {
                countEmpty++;
            }
        });

        if(countEmpty > 0){
            alert("Please select color result");
        } else {
            jQuery("#frmData").submit();
        }
    });

</script> 