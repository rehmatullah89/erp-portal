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

    $sSQL = "SELECT * FROM tbl_ppmeeting_pressing_packing WHERE audit_id='$Id'";
    $objDb->query($sSQL);

    $sIroningPressing   = $objDb->getField(0, 'ironing_pressing');
    $sSpecialBuck       = $objDb->getField(0, 'special_buck');
    $sPakcingPackaging  = $objDb->getField(0, 'pakcing_packaging');
    $sPriceTickets      = $objDb->getField(0, 'price_tickets');
    $sSpecialTag        = $objDb->getField(0, 'special_tag');

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
            <tr class="header">
                <td width="70%"><h4>Essential Requirements & Details</h4></td>
                <td><h4>Placement</h4></td>
            </tr>
            <tr class="evenRow">
                <td>Pressing Ironing STD / Compared with PPS</td>
                <td><select name="ironing_pressing" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sIroningPressing == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sIroningPressing == 'N'?'selected':'')?>>Not In-Place</option></select></td>
            </tr>
            <tr class="oddRow">
                <td>Use of Special Buck or Form</td>
                <td><select name="special_buck"  style='width:95%;'><option value="">N/A</option><option value="I" <?=($sSpecialBuck == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sSpecialBuck == 'N'?'selected':'')?>>Not In-Place</option></select></td>
            </tr>
            <tr class="evenRow">
                <td>Packing & Packging Material In-house</td>
                <td><select name="pakcing_packaging" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sPakcingPackaging == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sPakcingPackaging == 'N'?'selected':'')?>>Not In-Place</option></select></td>
            </tr>
            <tr class="oddRow">
                <td>Price Tickets / Hang Tags In-house</td>
                <td><select name="price_tickets" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sPriceTickets == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sPriceTickets == 'N'?'selected':'')?>>Not In-Place</option></select></td>
            </tr>
            <tr class="evenRow">
                <td>Any Special Tag or Material</td>
                <td><select name="special_tag" style='width:95%;'><option value="">N/A</option><option value="I" <?=($sSpecialTag == 'I'?'selected':'')?>>In-Place</option><option value="N" <?=($sSpecialTag == 'N'?'selected':'')?>>Not In-Place</option></select></td>
            </tr>
        </table>
        <br/><br/>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">       
<?
        if($Edit == 'Y')
        {
?>
        <br/><br/><hr>    
        <input type="submit" value="Submit" style="margin: 5px;">
<?
        }
?>
    </form>
</div>

