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

$sSQL = "SELECT * FROM tbl_ppmeeting_garment_construction WHERE audit_id='$Id'";
$objDb->query($sSQL);

$sApprovedSample    = $objDb->getField(0, 'approved_sample');
$sSpecialMarking    = $objDb->getField(0, 'special_marking');
$sSpecialMethod     = $objDb->getField(0, 'special_method');
$sStayStitch        = $objDb->getField(0, 'stay_stitch');
$sTackingOperation  = $objDb->getField(0, 'tacking_operation');
$sExtraOperation    = $objDb->getField(0, 'extra_operation');
$sActionPlan        = $objDb->getField(0, 'action_plan');
$sDetailChecks      = $objDb->getField(0, 'detail_checks');
$sKeyPoints         = $objDb->getField(0, 'key_points');

if($Edit != 'Y')
{
?>
    <a href="quonda/view-qa-report.php?Id=<?= $Id ?>" style="font-weight: bold; padding: 5px;"><img style="width:20px; line-height: 15px; margin-bottom: -5px;" src="images/icons/back.png">Back</a>
<?
}
?>
<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="quonda/update-ppmeeting-section.php">
    
        <h3>a) Factory PPS Approval Comments</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
            <tr>
                <td width="50%"><h4>Line Planning & Work Study Details</h4></td>
                <td><h4>Status</h4></td>
            </tr>
            <tr class="evenRow">
                <td>Approved Sample Followed While Making PP Sample</td>
                <td><input type="checkbox" name="approved_sample" value="Y" <?=($sApprovedSample == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="oddRow">
                <td>If there is any Special Marking Required</td>
                <td><input type="checkbox" name="special_marking" value="Y" <?=($sSpecialMarking == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="evenRow">
                <td>If there is any Special Method Required</td>
                <td><input type="checkbox" name="special_method" value="Y" <?=($sSpecialMethod == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="oddRow">
                <td>If there is any Stay Stitch or Crack Stitch Required</td>
                <td><input type="checkbox" name="stay_stitch" value="Y" <?=($sStayStitch == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="evenRow">
                <td>If there is any Tacking Opertion Needed for Bulk</td>
                <td><input type="checkbox" name="tacking_operation" value="Y" <?=($sTackingOperation == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="oddRow">
                <td>If there is any Extra Opertion Needed to Add in the Actual Process BreakDown</td>
                <td><input type="checkbox" name="extra_operation" value="Y" <?=($sExtraOperation == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="evenRow">
                <td>Application Action Plan in Bulk for Highlighted "Yes/Checked"</td>
                <td><textarea name="action_plan" cols="100" rows="6" style='width:92%;'><?=$sActionPlan?></textarea></td>
            </tr>
        </table>
        <br/><br/>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">
        
        <h3>b) SQC (Special Quality Control)</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="ApprovalReviewTable">
            <tr>
                <td width="40%">Details from Tech Team for 100% In-line & End-line Check</td>
                <td><textarea name="detail_checks" cols="100" rows="6" style='width:95%;'><?=$sDetailChecks?></textarea></td>
            </tr>
            <tr>
                <td>Details from Tech Team for Key Point MSMTS Control / In-line & End-line</td>
                <td><textarea name="key_points" cols="100" rows="6" style='width:95%;'><?=$sKeyPoints?></textarea></td>
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
