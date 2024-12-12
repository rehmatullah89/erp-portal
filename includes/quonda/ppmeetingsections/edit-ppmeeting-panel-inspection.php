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

$sSQL = "SELECT * FROM tbl_ppmeeting_panel_inspection WHERE audit_id='$Id'";
$objDb->query($sSQL);

$sPanelInspection   = $objDb->getField(0, 'panel_inspection');
$sApprovepattern    = $objDb->getField(0, 'approve_pattern');
$sOther             = $objDb->getField(0, 'other');
     
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
                <td width="200">100% Panel Inspection Required</td>
                <td><input type="checkbox" name="panel_inspection" value="Y" <?=($sPanelInspection == 'Y'?'checked':'')?>></td>                
            </tr>
            <tr class="oddRow">
                <td>In-lay Conditions/ Top/ Middle & Bottom Panel need to Check with Approved Pattern</td>
                <td><input type="checkbox" name="approve_pattern" value="Y" <?=($sApprovepattern == 'Y'?'checked':'')?>></td>                
            </tr>
            <tr class="evenRow">
                <td>Other</td>
                <td><textarea name="other" style="width: 95%;" rows="5"><?=$sOther?></textarea></td>                
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
