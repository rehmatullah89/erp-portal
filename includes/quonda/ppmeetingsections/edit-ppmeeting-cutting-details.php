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

$sSQL = "SELECT * FROM tbl_ppmeeting_cutting_details WHERE audit_id='$Id'";
$objDb->query($sSQL);

$sBlockCutting      = $objDb->getField(0, 'block_cutting');
$sBandKnifeCutting  = $objDb->getField(0, 'band_knife_cutting');
$sComputerCutting   = $objDb->getField(0, 'computer_cutting');
$sDieCutting        = $objDb->getField(0, 'die_cutting');
$sSpecialCutting    = $objDb->getField(0, 'special_cutting');
$sKnifeCutting      = $objDb->getField(0, 'knife_cutting');
     
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
                <td>Block Cutting Required</td>
                <td width="50"><input type="checkbox" name="block_cutting" value="Y" <?=($sBlockCutting == 'Y'?'checked':'')?>></td>
                
                <td>Band Knife Cutting</td>
                <td width="50"><input type="checkbox" name="band_knife_cutting" value="Y" <?=($sBandKnifeCutting == 'Y'?'checked':'')?>></td>                
            </tr>
            <tr class="oddRow">
                <td>Comuter Cutting</td>
                <td width="50"><input type="checkbox" name="computer_cutting" value="Y" <?=($sComputerCutting == 'Y'?'checked':'')?>></td>
                
                <td>Die Cutting</td>
                <td width="50"><input type="checkbox" name="die_cutting" value="Y" <?=($sDieCutting == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="evenRow">
                <td>Special Cutting</td>
                <td width="50"><input type="checkbox" name="special_cutting" value="Y" <?=($sSpecialCutting == 'Y'?'checked':'')?>></td>
                
                <td>Straight Knife Cutting</td>
                <td width="50"><input type="checkbox" name="knife_cutting" value="Y" <?=($sKnifeCutting == 'Y'?'checked':'')?>></td>                
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
