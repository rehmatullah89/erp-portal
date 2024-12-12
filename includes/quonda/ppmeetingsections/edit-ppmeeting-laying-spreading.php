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

$sSQL = "SELECT * FROM tbl_ppmeeting_laying_spreading WHERE audit_id='$Id'";
$objDb->query($sSQL);

$sEdgeCutting       = $objDb->getField(0, 'edge_cutting');
$sEdgeTearing       = $objDb->getField(0, 'edge_tearing');
$sRelaxation        = $objDb->getField(0, 'relaxation');
$sTensionFree       = $objDb->getField(0, 'tension_free');
$sLotNoFollowed     = $objDb->getField(0, 'lot_no_followed');
$sDefectsRemove     = $objDb->getField(0, 'defects_remove');
$sLayingPins        = $objDb->getField(0, 'laying_pins');
$sRelayPins         = $objDb->getField(0, 'relay_pins');
$sBorderPrintPins   = $objDb->getField(0, 'border_print_pins');
$sFaceUp            = $objDb->getField(0, 'face_up');
$sFaceToFace        = $objDb->getField(0, 'face_to_face');
$sNapUp             = $objDb->getField(0, 'nap_up');
$sNapDown           = $objDb->getField(0, 'nap_down');
$sTwillDirection    = $objDb->getField(0, 'twill_direction');
$sMaxLayHeight      = $objDb->getField(0, 'max_lay_height');
$sMarkerNumber      = $objDb->getField(0, 'marker_number');
$sStandard          = $objDb->getField(0, 'standard');
$sMaxPly            = $objDb->getField(0, 'maximum_ply');
$sBundleSize        = $objDb->getField(0, 'bundle_size');
$sManualSpreading   = $objDb->getField(0, 'manual_spreading');
$sRequireBundleSize = $objDb->getField(0, 'require_bundle_size');
$sMachineSpreading  = $objDb->getField(0, 'machine_spreading');        

if($Edit != 'Y')
{
?>
    <a href="quonda/view-qa-report.php?Id=<?= $Id ?>" style="font-weight: bold; padding: 5px;"><img style="width:20px; line-height: 15px; margin-bottom: -5px;" src="images/icons/back.png">Back</a>
<?
}
?>
<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="quonda/update-ppmeeting-section.php">
    
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
            <tr class="evenRow">
                <td>Edge Cutting</td>
                <td width="50"><input type="checkbox" name="edge_cutting" value="Y" <?=($sEdgeCutting == 'Y'?'checked':'')?>></td>
                
                <td>Edge Tearing</td>
                <td width="50"><input type="checkbox" name="edge_tearing" value="Y" <?=($sEdgeTearing == 'Y'?'checked':'')?>></td>
                
                <td>Relaxation of 24 hours</td>
                <td width="50"><input type="checkbox" name="relaxation" value="Y" <?=($sRelaxation == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="oddRow">
                <td>Tension Free</td>
                <td width="50"><input type="checkbox" name="tension_free" value="Y" <?=($sTensionFree == 'Y'?'checked':'')?>></td>
                
                <td>Lot no. to be Followed</td>
                <td width="50"><input type="checkbox" name="lot_no_followed" value="Y" <?=($sLotNoFollowed == 'Y'?'checked':'')?>></td>
                
                <td>Major Defects to be Removed</td>
                <td width="50"><input type="checkbox" name="defects_remove" value="Y" <?=($sDefectsRemove == 'Y'?'checked':'')?>></td>

            </tr>
            <tr class="evenRow">
                <td>Laying on Pins</td>
                <td width="50"><input type="checkbox" name="laying_pins" value="Y" <?=($sLayingPins == 'Y'?'checked':'')?>></td>
                
                <td>Blocking & Relay on Pins</td>
                <td width="50"><input type="checkbox" name="relay_pins" value="Y" <?=($sRelayPins == 'Y'?'checked':'')?>></td>
                
                <td>Pins Only for Border Print</td>
                <td width="50"><input type="checkbox" name="border_print_pins" value="Y" <?=($sBorderPrintPins == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="oddRow">
                <td>Face Up</td>
                <td width="50"><input type="checkbox" name="face_up" value="Y" <?=($sFaceUp == 'Y'?'checked':'')?>></td>
                
                <td>Face to Face</td>
                <td width="50"><input type="checkbox" name="face_to_face" value="Y" <?=($sFaceToFace == 'Y'?'checked':'')?>></td>
                
                <td>Nap Up</td>
                <td width="50"><input type="checkbox" name="nap_up" value="Y" <?=($sNapUp == 'Y'?'checked':'')?>></td>

            </tr>
            <tr class="evenRow">
                <td>Nap Down</td>
                <td width="50"><input type="checkbox" name="nap_down" value="Y" <?=($sNapDown == 'Y'?'checked':'')?>></td>
                
                <td>Twill Direction</td>
                <td width="50"><input type="checkbox" name="twill_direction" value="Y" <?=($sTwillDirection == 'Y'?'checked':'')?>></td>
                
                <td>Maximum Lay Height 1.5"</td>
                <td width="50"><input type="checkbox" name="max_lay_height" value="Y" <?=($sMaxLayHeight == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="oddRow">
                <td>Marker Number</td>
                <td width="50"><input type="checkbox" name="marker_number" value="Y" <?=($sMarkerNumber == 'Y'?'checked':'')?>></td>
                
                <td>Standard</td>
                <td width="50"><input type="checkbox" name="standard" value="Y" <?=($sStandard == 'Y'?'checked':'')?>></td>
                
                <td>Maximum Ply 60</td>
                <td width="50"><input type="checkbox" name="maximum_ply" value="Y" <?=($sMaxPly == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="evenRow">
                <td>Bundle Size</td>
                <td width="50"><input type="checkbox" name="bundle_size" value="Y" <?=($sBundleSize == 'Y'?'checked':'')?>></td>
                
                <td>Manual Spreading</td>
                <td width="50"><input type="checkbox" name="manual_spreading" value="Y" <?=($sManualSpreading == 'Y'?'checked':'')?>></td>
                
                <td>Required Bundle Size 40</td>
                <td width="50"><input type="checkbox" name="require_bundle_size" value="Y" <?=($sRequireBundleSize == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="oddRow">
                <td>Machine Spreading</td>
                <td width="50"><input type="checkbox" name="machine_spreading" value="Y" <?=($sMachineSpreading == 'Y'?'checked':'')?>></td>
                <td colspan="4">&nbsp;</td>                
            </tr>
        </table>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">
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
