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

$sSQL = "SELECT * FROM tbl_ppmeeting_special_centerline WHERE audit_id='$Id'";
$objDb->query($sSQL);

$sFrontCenterLine           = $objDb->getField(0, 'fron_centerline');
$sCuffCenterLine            = $objDb->getField(0, 'cuff_centerline');
$sBackCenterLine            = $objDb->getField(0, 'back_centerline');
$sCollarCenterLine          = $objDb->getField(0, 'collar_centerline');
$sSpecialColorCenterLine    = $objDb->getField(0, 'special_centerline');
$sSleeveCenterLine          = $objDb->getField(0, 'sleeve_centerline');

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
                <td>Front Center Line</td>
                <td width="50"><input type="checkbox" name="fron_centerline" value="Y" <?=($sFrontCenterLine == 'Y'?'checked':'')?>></td>
                
                <td>Cuff Center Line</td>
                <td width="50"><input type="checkbox" name="cuff_centerline" value="Y" <?=($sCuffCenterLine == 'Y'?'checked':'')?>></td>
            </tr>
            
            <tr class="oddRow">
                <td>Back Center Line</td>
                <td><input type="checkbox" name="back_centerline" value="Y" <?=($sBackCenterLine == 'Y'?'checked':'')?>></td>
                
                <td>Collar Center Line</td>
                <td><input type="checkbox" name="collar_centerline" value="Y" <?=($sCollarCenterLine == 'Y'?'checked':'')?>></td>
            </tr>
            
            <tr class="evenRow">
                <td>Special Color Center Line</td>
                <td><input type="checkbox" name="special_centerline" value="Y" <?=($sSpecialColorCenterLine == 'Y'?'checked':'')?>></td>
                
                <td>Sleeve Center Line</td>
                <td><input type="checkbox" name="sleeve_centerline" value="Y" <?=($sSleeveCenterLine == 'Y'?'checked':'')?>></td>
            </tr>           
        </table>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">
      <br/><br/>
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


