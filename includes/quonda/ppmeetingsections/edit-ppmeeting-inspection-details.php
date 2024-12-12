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
<?

$sSQL = "SELECT * FROM tbl_ppmeeting_inspection_details WHERE audit_id='$Id'";
$objDb->query($sSQL);

//section#A
$sFabric                = explode('|-|', $objDb->getField(0, 'fabric'));
$sInspecLevel           = explode('|-|', $objDb->getField(0, 'inspection_level'));
$sResult                = explode('|-|', $objDb->getField(0, 'result'));
$sFabricPoints          = explode('|-|', $objDb->getField(0, 'fabric_points'));
//section#B
$sWeavingDefect         = $objDb->getField(0, 'weaving_defect');
$sMiskPack              = $objDb->getField(0, 'misc_pack');
$sShadingEnd            = $objDb->getField(0, 'shading_end');
$sEndOutrun             = $objDb->getField(0, 'end_outrun');
$sSlubs                 = $objDb->getField(0, 'slubs');
$sShadingBack           = $objDb->getField(0, 'shading_back');
$sStainMarks            = $objDb->getField(0, 'stain_marks');     
$sdoublePick            = $objDb->getField(0, 'double_pick');
$sShadingSide           = $objDb->getField(0, 'shading_side');     
$sShadeBars             = $objDb->getField(0, 'shade_bars');
$sKnots                 = $objDb->getField(0, 'knots');
$sBowing                = $objDb->getField(0, 'bowing');
$sContamination         = $objDb->getField(0, 'contamination');          
$sSpot                  = $objDb->getField(0, 'spot');
$sSnaging               = $objDb->getField(0, 'snaging');           
$sThinFabric            = $objDb->getField(0, 'thin_fabric');
$sHoles                 = $objDb->getField(0, 'holes');
$sGrainPrint            = $objDb->getField(0, 'grain_print');
$sRejectedQtyDetails    = $objDb->getField(0, 'rejected_qty_details');    
$sPreCalculatedLoss     = $objDb->getField(0, 'pre_calculated_loss');       
$sBlackTest             = $objDb->getField(0, 'color_black_test');
$sWearerTest            = $objDb->getField(0, 'wearer_test');
$sSpecialTest           = $objDb->getField(0, 'special_test');
$sAnyUpdate             = $objDb->getField(0, 'any_update');
//section#C
$RejectedPoints         = explode('|-|', $objDb->getField(0, 'rejectedPoint'));
$sInspectionYardage     = $objDb->getField(0, 'inspection_yardage');
$sYarnPlaces            = $objDb->getField(0, 'yarn_places');
$sComments              = $objDb->getField(0, 'comments');
//section#D
$sNFabricResult         = $objDb->getField(0, 'nfabric_result');       
$sNGarmentResult        = $objDb->getField(0, 'ngarment_result');
$sIFabricResult         = $objDb->getField(0, 'ifabric_result');
$sIGarmentResult        = $objDb->getField(0, 'igarment_result');       
$sShrinkSteamShellWarp  = $objDb->getField(0, 'shrink_steam_shell_warp');
$sShrinkSteamShellWeft  = $objDb->getField(0, 'shrink_steam_shell_weft');
$sShrinkPressShellWarp  = $objDb->getField(0, 'shrink_press_shell_warp');
$sShrinkPressShellWeft  = $objDb->getField(0, 'shrink_press_shell_weft');
$sShrinkSteamLaceWarp   = $objDb->getField(0, 'shrink_steam_lace_warp');
$sShrinkSteamLaceWeft   = $objDb->getField(0, 'shrink_steam_lace_weft');
$sShrinkPressLaceWarp   = $objDb->getField(0, 'shrink_press_lace_warp');
$sShrinkPressLaceWeft   = $objDb->getField(0, 'shrink_press_lace_weft');
$sRequiredTest          = $objDb->getField(0, 'required_test');
$sCompleteTest          = $objDb->getField(0, 'complete_test');
$sInCompleteTest        = $objDb->getField(0, 'incomplete_test');
//section#E
$sBlockFusing           = $objDb->getField(0, 'block_fusing');
$sBTmLeveling           = $objDb->getField(0, 'btm_leveling');
$sTailorsChalks         = $objDb->getField(0, 'tailors_chalks');
$sPenStains             = $objDb->getField(0, 'pen_stains');
$sStickerMarks          = $objDb->getField(0, 'sticker_marks');
$sPassStickerMarks      = $objDb->getField(0, 'pass_sticker_marks');

if($Edit != 'Y')
{
?>
    <a href="quonda/view-qa-report.php?Id=<?= $Id ?>" style="font-weight: bold; padding: 5px;"><img style="width:20px; line-height: 15px; margin-bottom: -5px;" src="images/icons/back.png">Back</a>
<?
}
?>
<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="quonda/update-ppmeeting-section.php">
    
        <h3>a) Fabric Inspection Details (Internal By Factory)</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
            <tr class="evenRow">
                <td width="50"><b>#</b></td>
                <td><b>Fabric</b></td>
                <td width="120"><b>Inspection Level</b></td>
                <td width="100"><b>Result</b></td>
                <td><b>Total Fabric Point Count</b></td>
            </tr>
<?
            for($i=0; $i<5; $i++){
?>
            <tr>
                <td><?=$i+1?></td>
                <td><input type="text" name="fabric[]" value="<?=$sFabric[$i]?>" class="textbox" size="20" style='width:95%;'></td>                    
                <td><select name="inspection_level[]"><option value=""></option><option value="2.5" <?=($sInspecLevel[$i] == '2.5')?'selected':''?>>2.5</option><option value="4.0" <?=($sInspecLevel[$i] == '4.0')?'selected':''?>>4.0</option></select></td>
                <td><select name="result[]"><option value=""></option><option value="P" <?=($sResult[$i] == 'P')?'selected':''?>>Pass</option><option value="F" <?=($sResult[$i] == 'F')?'selected':''?>>Fail</option></select></td>
                <td><input type="text" name="fabric_points[]" value="<?=$sFabricPoints[$i]?>" class="textbox" size="20" style='width:95%;'></td>                    
            </tr>
<?
            }
?>
        </table>
        <br/><br/>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">
        
        <h3>b) Identified Fabric Defect</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
        <tr class="evenRow">
            <td><b>Fabric</b></td>
            <td align="center" width="30"><b>1</b></td>
            <td align="center" width="30"><b>2</b></td>
            <td align="center" width="30"><b>3</b></td>
            <td><b>Fabric</b></td>
            <td align="center" width="30"><b>1</b></td>
            <td align="center" width="30"><b>2</b></td>
            <td align="center" width="30"><b>3</b></td>
            <td><b>Fabric</b></td>
            <td align="center" width="30"><b>1</b></td>
            <td align="center" width="30"><b>2</b></td>
            <td align="center" width="30"><b>3</b></td>
        </tr>
        <tr>
            <td>Weaving Defects</td><td><input type="radio" name="weaving_defect" value="1" <?=($sWeavingDefect == '1'?'checked':'')?>></td><td><input type="radio" name="weaving_defect" value="2" <?=($sWeavingDefect == '2'?'checked':'')?>></td><td><input type="radio" name="weaving_defect" value="3" <?=($sWeavingDefect == '3'?'checked':'')?>></td>
            <td>Misc Packs</td><td><input type="radio" name="misc_pack" value="1" <?=($sMiskPack == '1'?'checked':'')?>></td><td><input type="radio" name="misc_pack" value="2" <?=($sMiskPack == '2'?'checked':'')?>></td><td><input type="radio" name="misc_pack" value="3" <?=($sMiskPack == '3'?'checked':'')?>></td>
            <td>Shading End to End</td><td><input type="radio" name="shading_end" value="1" <?=($sShadingEnd == '1'?'checked':'')?>></td><td><input type="radio" name="shading_end" value="2" <?=($sShadingEnd == '2'?'checked':'')?>></td><td><input type="radio" name="shading_end" value="3" <?=($sShadingEnd == '3'?'checked':'')?>></td>
        </tr>
        <tr>
            <td>End Out Run</td><td><input type="radio" name="end_outrun" value="1" <?=($sEndOutrun == '1'?'checked':'')?>></td><td><input type="radio" name="end_outrun" value="2" <?=($sEndOutrun == '2'?'checked':'')?>></td><td><input type="radio" name="end_outrun" value="3" <?=($sEndOutrun == '3'?'checked':'')?>></td>
            <td>Slubs</td><td><input type="radio" name="slubs" value="1" <?=($sSlubs == '1'?'checked':'')?>></td><td><input type="radio" name="slubs" value="2" <?=($sSlubs == '2'?'checked':'')?>></td><td><input type="radio" name="slubs" value="3" <?=($sSlubs == '3'?'checked':'')?>></td>
            <td>Shading Back Ground</td><td><input type="radio" name="shading_back" value="1" <?=($sShadingBack == '1'?'checked':'')?>></td><td><input type="radio" name="shading_back" value="2" <?=($sShadingBack == '2'?'checked':'')?>></td><td><input type="radio" name="shading_back" value="3" <?=($sShadingBack == '3'?'checked':'')?>></td>
        </tr>
        <tr>
            <td>Stain Marks</td><td><input type="radio" name="stain_marks" value="1" <?=($sStainMarks == '1'?'checked':'')?>></td><td><input type="radio" name="stain_marks" value="2" <?=($sStainMarks == '2'?'checked':'')?>></td><td><input type="radio" name="stain_marks" value="3" <?=($sStainMarks == '3'?'checked':'')?>></td>
            <td>Double Pick</td><td><input type="radio" name="double_pick" value="1" <?=($sdoublePick == '1'?'checked':'')?>></td><td><input type="radio" name="double_pick" value="2" <?=($sdoublePick == '2'?'checked':'')?>></td><td><input type="radio" name="double_pick" value="3" <?=($sdoublePick == '3'?'checked':'')?>></td>
            <td>Shading Side to Side</td><td><input type="radio" name="shading_side" value="1" <?=($sShadingSide == '1'?'checked':'')?>></td><td><input type="radio" name="shading_side" value="2" <?=($sShadingSide == '2'?'checked':'')?>></td><td><input type="radio" name="shading_side" value="3" <?=($sShadingSide == '3'?'checked':'')?>></td>
        </tr>
        <tr>
            <td>Shade Bars</td><td><input type="radio" name="shade_bars" value="1" <?=($sShadeBars == '1'?'checked':'')?>></td><td><input type="radio" name="shade_bars" value="2" <?=($sShadeBars == '2'?'checked':'')?>></td><td><input type="radio" name="shade_bars" value="3" <?=($sShadeBars == '3'?'checked':'')?>></td>
            <td>Knots</td><td><input type="radio" name="knots" value="1" <?=($sKnots == '1'?'checked':'')?>></td><td><input type="radio" name="knots" value="2" <?=($sKnots == '2'?'checked':'')?>></td><td><input type="radio" name="knots" value="3" <?=($sKnots == '3'?'checked':'')?>></td>
            <td>Bowing</td><td><input type="radio" name="bowing" value="1" <?=($sBowing == '1'?'checked':'')?>></td><td><input type="radio" name="bowing" value="2" <?=($sBowing == '2'?'checked':'')?>></td><td><input type="radio" name="bowing" value="3" <?=($sBowing == '3'?'checked':'')?>></td>
        </tr>
        <tr>
            <td>Contamination</td><td><input type="radio" name="contamination" value="1" <?=($sContamination == '1'?'checked':'')?>></td><td><input type="radio" name="contamination" value="2" <?=($sContamination == '2'?'checked':'')?>></td><td><input type="radio" name="contamination" value="3" <?=($sContamination == '3'?'checked':'')?>></td>
            <td>Spot</td><td><input type="radio" name="spot" value="1" <?=($sSpot == '1'?'checked':'')?>></td><td><input type="radio" name="spot" value="2" <?=($sSpot == '2'?'checked':'')?>></td><td><input type="radio" name="spot" value="3" <?=($sSpot == '3'?'checked':'')?>></td>
            <td>Snaging</td><td><input type="radio" name="snaging" value="1" <?=($sSnaging == '1'?'checked':'')?>></td><td><input type="radio" name="snaging" value="2" <?=($sSnaging == '2'?'checked':'')?>></td><td><input type="radio" name="snaging" value="3" <?=($sSnaging == '3'?'checked':'')?>></td>
        </tr>
        <tr>
            <td>Thin Fabric</td><td><input type="radio" name="thin_fabric" value="1" <?=($sThinFabric == '1'?'checked':'')?>></td><td><input type="radio" name="thin_fabric" value="2" <?=($sThinFabric == '2'?'checked':'')?>></td><td><input type="radio" name="thin_fabric" value="3" <?=($sThinFabric == '3'?'checked':'')?>></td>
            <td>Holes</td><td><input type="radio" name="holes" value="1" <?=($sHoles == '1'?'checked':'')?>></td><td><input type="radio" name="holes" value="2" <?=($sHoles == '2'?'checked':'')?>></td><td><input type="radio" name="holes" value="3" <?=($sHoles == '3'?'checked':'')?>></td>
            <td>Skew/Off Grain Printing</td><td><input type="radio" name="grain_print" value="1" <?=($sGrainPrint == '1'?'checked':'')?>></td><td><input type="radio" name="grain_print" value="2" <?=($sGrainPrint == '2'?'checked':'')?>></td><td><input type="radio" name="grain_print" value="3" <?=($sGrainPrint == '3'?'checked':'')?>></td>
        </tr>
        </table>
        
        <br/><br/>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr>
                <td width="130">Rejected Qtys Details</td>
                <td><input name="rejected_qty_details" value="<?=$sRejectedQtyDetails?>" type="text" size="20" style='width:95%;'></td>
                <td width="200">Pre Calculated Loss for Re-Cutting</td>
                <td><input name="pre_calculated_loss" value="<?=$sPreCalculatedLoss?>" type="text" size="20" style='width:95%;'></td>
            </tr>
        </table>

        <br/><br/>
        <u><b>Special Comments for Fabric Follow-up</b></u>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr>
                <td>Color Black Sepcial test requirement</td>
                <td width="30"><input type="checkbox" name="color_black_test" value="Y" <?=($sBlackTest == 'Y'?'checked':'')?>></td>
                <td>Wearer's test required</td>
                <td width="30"><input type="checkbox" name="wearer_test" value="Y" <?=($sWearerTest == 'Y'?'checked':'')?>></td>
                <td>Special Testing Requirement</td>
                <td width="30"><input type="checkbox" name="special_test" value="Y" <?=($sSpecialTest == 'Y'?'checked':'')?>></td>
            </tr>
            <tr>
                <td width="100">Update if any?</td>
                <td colspan="5"><input name="any_update" value="<?=$sAnyUpdate?>" type="text" size="20" style='width:98%;'></td>
            </tr>
        </table>
        
        <br/><br/>
        <h3>c) How to Proceed with Rejected Qty?</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="rejectedPointsTable">
<?
            if(!empty($RejectedPoints))
            {
                $k =1;
                foreach($RejectedPoints as $sRejectedPoint)
                {
?>
                <tr>
                    <td width="20"><?=$k++?></td>
                    <td><input type="text" name="rejectedPoint[]" value="<?=$sRejectedPoint?>" class="textbox" size="20" style='width:95%;'></td>                    
                </tr>
<?
                }
?>
                <input type="hidden" name="CountRows" id="CountRows" value="<?=$k?>">  
<?
            }
            else
            {
?>
            <tr>
                <td width="20">1</td>
                <td><input type="text" name="rejectedPoint[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <input type="hidden" name="CountRows" id="CountRows" value="2">  
            </tr>
<?
            }
?>
            
        </table>
        <a id="BtnAddRow" onclick="AddRejComment()">Add [+]</a> / <a id="BtnDelRow" onclick="DeleteRejComment()">Remove [-]</a>
        
        <br/><br/>
        <u><b>if yes Details?</b></u>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr>
                <td width="110">Inspection Yardage</td>
                <td><input type="text" name="inspection_yardage" value="<?=$sInspectionYardage?>" class="textbox" size="20" style='width:95%;'></td>
                <td width="175">No. of Places with foreign yarn</td>
                <td><input type="text" name="yarn_places" value="<?=$sYarnPlaces?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
            <tr>
                <td>Comments</td>
                <td colspan="3"><textarea name="comments" cols="50" rows="10" style='width:98%;'><?=$sComments?></textarea></td>
            </tr>
        </table>
        <br/><br/>
        
        <h3>d) Fabric Test Result</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr class="evenRow">
                <td align="center" colspan="4" style="border-bottom: 1px solid black !important; border-right-width: 5px;"><b>Nominated Lab</b></td>
                <td align="center" colspan="4" style="border-bottom: 1px solid black !important; border-left-width: 5px;"><b>In-house Lab</b></td>
            </tr>
            <tr>
                <td width="150">Fabric Test Result</td> 
                <td><select name="nfabric_result"><option value=""></option><option value="P" <?=($sNFabricResult == 'P'?'selected':'')?>>Pass</option><option value="F" <?=($sNFabricResult == 'F'?'selected':'')?>>Fail</option></select></td>
                <td width="150">Garment Test Result</td>
                <td><select name="ngarment_result"><option value=""></option><option value="P" <?=($sNGarmentResult == 'P'?'selected':'')?>>Pass</option><option value="F" <?=($sNGarmentResult == 'F'?'selected':'')?>>Fail</option></select></td>    
                <td width="150" style="border-left-width: 5px;">Fabric Test Result</td>
                <td><select name="ifabric_result"><option value=""></option><option value="P" <?=($sIFabricResult == 'P'?'selected':'')?>>Pass</option><option value="F" <?=($sIFabricResult == 'F'?'selected':'')?>>Fail</option></select></td>
                <td width="150">Garment Test Result</td>
                <td><select name="igarment_result"><option value=""></option><option value="P" <?=($sIGarmentResult == 'P'?'selected':'')?>>Pass</option><option value="F" <?=($sIGarmentResult == 'F'?'selected':'')?>>Fail</option></select></td>    
            </tr>
        </table>
        <br/><br/>
         <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr class="evenRow">
                <td><b>Fabric Test Result Points</b></td>
                <td><b>Warp</b></td>
                <td><b>Weft</b></td>
            </tr>
            <tr>
                <td>Fabric Shrinkage to Steam Shell</td>
                <td><input type="text" name="shrink_steam_shell_warp" value="<?=$sShrinkSteamShellWarp?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="shrink_steam_shell_weft" value="<?=$sShrinkSteamShellWeft?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
            <tr>
                <td>Fabric Shrinkage to Pressing Shell</td>
                <td><input type="text" name="shrink_press_shell_warp" value="<?=$sShrinkPressShellWarp?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="shrink_press_shell_weft" value="<?=$sShrinkPressShellWeft?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
            <tr>
                <td>Fabric Shrinkage to Steam Lace</td>
                <td><input type="text" name="shrink_steam_lace_warp" value="<?=$sShrinkSteamLaceWarp?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="shrink_steam_lace_weft" value="<?=$sShrinkSteamLaceWeft?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
            <tr>
                <td>Fabric Shrinkage to Presssing Lace</td>
                <td><input type="text" name="shrink_press_lace_warp" value="<?=$sShrinkPressLaceWarp?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="shrink_press_lace_weft" value="<?=$sShrinkPressLaceWeft?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
         </table>   
        <br/><br/>
        
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr class="evenRow">
                <td width="130"><b>Do you have list of required test</b></td>
                <td width="80"><input type="radio" name="required_test" value="F" <?=($sRequiredTest == 'F'?'checked':'')?>>Fabric</td>
                <td width="80"><input type="radio" name="required_test" value="T" <?=($sRequiredTest == 'T'?'checked':'')?>>Trim</td>
                <td width="80"><input type="radio" name="required_test" value="G" <?=($sRequiredTest == 'G'?'checked':'')?>>Garment</td>
            </tr>
            <tr class="evenRow">
                <td><b>Testing Compelte</b></td>
                <td width="80"><input type="radio" name="complete_test" value="F" <?=($sCompleteTest == 'F'?'checked':'')?>>Fabric</td>
                <td width="80"><input type="radio" name="complete_test" value="T" <?=($sCompleteTest == 'T'?'checked':'')?>>Trim</td>
                <td width="80"><input type="radio" name="complete_test" value="G" <?=($sCompleteTest == 'G'?'checked':'')?>>Garment</td>
            </tr>
            <tr class="evenRow">
                <td><b>Missing /In-complete Test</b></td>
                <td colspan="3"><input type="text" name="incomplete_test" value="<?=$sInCompleteTest?>" class="textbox" size="20" style='width:98%;'></td>
            </tr>
        </table>
        <br/><br/>
        
        <h3>e) Interlining & Fusing Recommended</h3> 
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr>
                <td>Recommended Block Fusing</td>
                <td width="100"><input type="checkbox" name="block_fusing" value="Y" <?=($sBlockFusing == 'Y'?'checked':'')?>></td>
            </tr>
            <tr>
                <td>Recommended BTM Leaveling on Fabric</td>
                <td><input type="checkbox" name="btm_leveling" value="Y" <?=($sBTmLeveling == 'Y'?'checked':'')?>></td>
            </tr>
            <tr>
                <td>Tailors Chalk for Marking on Fabric</td>
                <td><input type="checkbox" name="tailors_chalks" value="Y" <?=($sTailorsChalks == 'Y'?'checked':'')?>></td>
            </tr>
            <tr>
                <td>Disappearing Pen Remaining Stains</td>
                <td><input type="checkbox" name="pen_stains" value="Y" <?=($sPenStains == 'Y'?'checked':'')?>></td>
            </tr>
            <tr>
                <td>Sticker Gum Marks Remaining on the Fabric</td>
                <td><input type="checkbox" name="sticker_marks" value="Y" <?=($sStickerMarks == 'Y'?'checked':'')?>></td>
            </tr>
            <tr>
                <td>Fabric with the Stickers can be Passed through Fusing Machine</td>
                <td><input type="checkbox" name="pass_sticker_marks" value="Y" <?=($sPassStickerMarks == 'Y'?'checked':'')?>></td>
            </tr>
        </table>
        
        <br/><br/><hr>
<?
        if($Edit == 'Y')
        {
?>
        <input type="submit" value="Submit" style="margin: 5px;">
<?
        }
?>
    </form>
</div>
    

<script type="text/javascript">
	    <!--

    var i=document.getElementById("CountRows").value;
    function AddRejComment() {
        var table = document.getElementById("rejectedPointsTable");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);

        cell1.innerHTML = i;
        cell2.innerHTML = "<input type='text' class='textbox' name='rejectedPoint[]' value=''  style='width:95%;'/>";
        i++;
        document.getElementById("CountRows").value = i;
    }

    function DeleteRejComment() {
        var table = document.getElementById("rejectedPointsTable");
        var rowCount = table.rows.length;
        
        if(rowCount > 1)
        {
            table.deleteRow(rowCount-1);
            i--;
            document.getElementById("CountRows").value = i;
        }
    }
    -->
</script> 
