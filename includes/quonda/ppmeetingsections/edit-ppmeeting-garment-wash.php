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

    $sSQL = "SELECT * FROM tbl_ppmeeting_garment_wash WHERE audit_id='$Id'";
    $objDb->query($sSQL);

    $sHandWashApproved      = $objDb->getField(0, 'hand_wash_approved');
    $sShadeBandApproed      = $objDb->getField(0, 'shade_band_approved');
    $sAbrasionStds          = $objDb->getField(0, 'abrasion_stds');
    $sGmtDyeingApproved     = $objDb->getField(0, 'gmt_dyeing_approved');
    $sWashingProcessingTa   = $objDb->getField(0, 'washing_processing_ta');
    $sBulkApprovalDate1     = $objDb->getField(0, 'bulk_approval_date1');
    $sBulkApprovalDate2     = $objDb->getField(0, 'bulk_approval_date2');
    $sWashComments1         = $objDb->getField(0, 'wash_comments1');
    $sSpecialTack           = $objDb->getField(0, 'special_tack');
    $sShadeLabels           = $objDb->getField(0, 'shade_labels');
    $sShrinkageCheck        = $objDb->getField(0, 'shrinkage_check');
    $sColorTest             = $objDb->getField(0, 'color_test');
    $sFabricGrouping        = $objDb->getField(0, 'fabric_grouping');
    $sMarkerGrouping        = $objDb->getField(0, 'marker_grouping');
    $sBeforeWashData        = $objDb->getField(0, 'before_wash_data');
    $sAfterWashData         = $objDb->getField(0, 'after_wash_data');            
    $sReviewWashSpec        = $objDb->getField(0, 'review_wash_spec');
    $sSpecialAdjustment     = $objDb->getField(0, 'spcial_adjustments');
    $sApplicationString     = $objDb->getField(0, 'application_strings');
    $sWashComments2         = $objDb->getField(0, 'wash_comments2');
    $sLaundryName           = $objDb->getField(0, 'laundry_name');
    $sDryProcess            = $objDb->getField(0, 'dry_process_treatment');
    $sWetProcess            = $objDb->getField(0, 'wet_process_treatment');
    $sTaCapacity            = $objDb->getField(0, 'ta_capacity');

if($Edit != 'Y')
{
?>
    <a href="quonda/view-qa-report.php?Id=<?= $Id ?>" style="font-weight: bold; padding: 5px;"><img style="width:20px; line-height: 15px; margin-bottom: -5px;" src="images/icons/back.png">Back</a>
<?
}
?>    
<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="quonda/update-ppmeeting-section.php">
    
        <h3>a) Garment Wash / Garment Dyeing & Special Finish</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
            <tr class="header">
                <td width="70%"><h4>Requirements & Details</h4></td>
                <td><h4>Placement</h4></td>
            </tr>
            <tr class="evenRow">
                <td>Approved STD for wash and Handfeel</td>
                <td><select name="hand_wash_approved" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sHandWashApproved == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sHandWashApproved == 'N'?'selected':'')?>>Not In-Place</option></select></td>
            </tr>
            <tr class="oddRow">
                <td>Approved Shade Band</td>
                <td><select name="shade_band_approved"  style='width:95%;'><option value="">N/A</option><option value="I" <?=($sShadeBandApproed == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sShadeBandApproed == 'N'?'selected':'')?>>Not In-Place</option></select></td>
            </tr>
            <tr class="evenRow">
                <td>Before & After Wash Seam Pucked / Abrasion STDs</td>
                <td><select name="abrasion_stds" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sAbrasionStds == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sAbrasionStds == 'N'?'selected':'')?>>Not In-Place</option></select></td>
            </tr>
            <tr class="oddRow">
                <td>Color STD & Color Strike Off Approval for GMT Dyeing</td>
                <td><select name="gmt_dyeing_approved" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sGmtDyeingApproved == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sGmtDyeingApproved == 'N'?'selected':'')?>>Not In-Place</option></select></td>
            </tr>
            <tr class="evenRow">
                <td>Washing & Processing T&A</td>
                <td><select name="washing_processing_ta" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sWashingProcessingTa == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sWashingProcessingTa == 'N'?'selected':'')?>>Not In-Place</option></select></td>
            </tr>
             <tr class="oddRow">
                <td>1<sup>st</sup> Bulk Approval Date</td>
                <td><select name="bulk_approval_date1" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sBulkApprovalDate1 == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sBulkApprovalDate1 == 'N'?'selected':'')?>>Not In-Place</option></select></td>
            </tr>
            <tr class="evenRow">
                 <td>2<sup>nd</sup> Bulk Approval Date</td>
                <td><select name="bulk_approval_date2" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sBulkApprovalDate2 == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sBulkApprovalDate2 == 'N'?'selected':'')?>>Not In-Place</option></select></td>
            </tr>
            <tr class="oddRow">
                <td>Special Wash Comments</td>
                <td><input type="text" name="wash_comments1" value="<?=$sWashComments1?>" class="textbox" size="20" style='width:88%;'></td>
            </tr>            
        </table>
        <br/><br/>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">
        
        <h3>b) In-House Controls & Testing for Wash Items </h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="ApprovalReviewTable">
        <tr class="header">
            <td width="70%"><h4>Requirements & Details</h4></td>
            <td><h4>Placement</h4></td>
        </tr>
        <tr class="oddRow">
            <td>Any Special Tack / Button Attaching Needed Before Washing</td>
            <td><select name="special_tack" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sSpecialTack == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sSpecialTack == 'N'?'selected':'')?>>Not In-Place</option></select></td>
        </tr>    
        <tr class="evenRow">
            <td>Shade Labels to be Attached to The Garment</td>
            <td><select name="shade_labels" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sShadeLabels == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sShadeLabels == 'N'?'selected':'')?>>Not In-Place</option></select></td>
        </tr>
        <tr class="oddRow">
            <td>Shrinkage Check for Bulk Wash  20% across Dye lots & 100% for every dye lots and rolls</td>
            <td><select name="shrinkage_check" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sShrinkageCheck == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sShrinkageCheck == 'N'?'selected':'')?>>Not In-Place</option></select></td>
        </tr>
        <tr class="evenRow">
            <td>In-House Color Fastness Test</td>
            <td><select name="color_test" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sColorTest == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sColorTest == 'N'?'selected':'')?>>Not In-Place</option></select></td>
        </tr>
        <tr class="oddRow">
            <td>Fabric Grouping for Lot & Shde Control</td>
            <td><select name="fabric_grouping" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sFabricGrouping == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sFabricGrouping == 'N'?'selected':'')?>>Not In-Place</option></select></td>
        </tr>
        <tr class="evenRow">
            <td>Pattern/ Marker Grouping for Wash & Lots</td>
            <td><select name="marker_grouping" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sMarkerGrouping == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sMarkerGrouping == 'N'?'selected':'')?>>Not In-Place</option></select></td>
        </tr>
        <tr class="oddRow">
            <td>Before Wash Spec / MSMT Data Recording</td>
            <td><select name="before_wash_data" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sBeforeWashData == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sBeforeWashData == 'N'?'selected':'')?>>Not In-Place</option></select></td>
        </tr>
        <tr class="evenRow">
            <td>After Wash Spec / MSMT Data Recording</td>
            <td><select name="after_wash_data" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sAfterWashData == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sAfterWashData == 'N'?'selected':'')?>>Not In-Place</option></select></td>
        </tr>
        <tr class="oddRow">
            <td>Review & Analysis of Before & After Wash Spec</td>
            <td><select name="review_wash_spec" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sReviewWashSpec == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sReviewWashSpec == 'N'?'selected':'')?>>Not In-Place</option></select></td>
        </tr>
        <tr class="evenRow">
            <td>Special Adjustments in Pattern (with TSAM Tech Involved)</td>
            <td><select name="spcial_adjustments" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sSpecialAdjustment == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sSpecialAdjustment == 'N'?'selected':'')?>>Not In-Place</option></select></td>
        </tr>
        <tr class="oddRow">
            <td>Application of Rafia Strings for Lot Size & Shade</td>
            <td><select name="application_strings" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sApplicationString == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sApplicationString == 'N'?'selected':'')?>>Not In-Place</option></select></td>
        </tr>
        <tr class="evenRow">
                <td>Special Wash Comments</td>
                <td><input type="text" name="wash_comments2" value="<?=$sWashComments2?>" class="textbox" size="20" style='width:88%;'></td>
        </tr>
        </table>
        <br/><br/>
        <h3>c) Laundry Details for Dry & Wet Processes</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr>
                <td width="30%">Laundy Name</td>
                <td><input type="text" name="laundry_name" value="<?=$sLaundryName?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
            <tr>
                <td>Dry Process Treatment</td>
                <td><input type="text" name="dry_process_treatment" value="<?=$sDryProcess?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
            <tr>
                <td>Wet Process Treatment</td>
                <td><input type="text" name="wet_process_treatment" value="<?=$sWetProcess?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
            <tr>
                <td>T&A / Capacity Allocated</td>
                <td><input type="text" name="ta_capacity" value="<?=$sTaCapacity?>" class="textbox" size="20" style='width:95%;'></td>
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

