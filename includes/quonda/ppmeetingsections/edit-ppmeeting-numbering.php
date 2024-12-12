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

$sSQL = "SELECT * FROM tbl_ppmeeting_numbering WHERE audit_id='$Id'";
$objDb->query($sSQL);

$sPartsNumbering    = $objDb->getField(0, 'parts_numbering');
$sFaceSide          = $objDb->getField(0, 'face_side');
$sSpecialNumbering  = $objDb->getField(0, 'special_numbering');
$sReverseSide       = $objDb->getField(0, 'reverse_side');
$sSpecialCutMarks   = $objDb->getField(0, 'special_cut_marks');
$sSpecialNotches    = $objDb->getField(0, 'spcial_notches');
     
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
                <td>All Part to be numbered</td>
                <td width="50"><input type="checkbox" name="parts_numbering" value="Y" <?=($sPartsNumbering == 'Y'?'checked':'')?>></td>
                
                <td>On Face Side</td>
                <td width="50"><input type="checkbox" name="face_side" value="Y" <?=($sFaceSide == 'Y'?'checked':'')?>></td>                
            </tr>
            <tr class="oddRow">
                <td>Special Tab for Numbering</td>
                <td width="50"><input type="checkbox" name="special_numbering" value="Y" <?=($sSpecialNumbering == 'Y'?'checked':'')?>></td>
                
                <td>Reverse Side</td>
                <td width="50"><input type="checkbox" name="reverse_side" value="Y" <?=($sReverseSide == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="evenRow">
                <td>Special Cut Marks Needed</td>
                <td width="50"><input type="checkbox" name="special_cut_marks" value="Y" <?=($sSpecialCutMarks == 'Y'?'checked':'')?>></td>
                
                <td>Special "V" Notches Needed</td>
                <td width="50"><input type="checkbox" name="spcial_notches" value="Y" <?=($sSpecialNotches == 'Y'?'checked':'')?>></td>                
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
