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

$sSQL = "SELECT * FROM tbl_ppmeeting_pocketing_lining WHERE audit_id='$Id'";
$objDb->query($sSQL);

$sBaseFabric        = $objDb->getField(0, 'base_fabric');
$sLayerColor        = $objDb->getField(0, 'layer_color');
$sPocketPrinting    = $objDb->getField(0, 'pocket_printing');
$sBackPrinting      = $objDb->getField(0, 'back_printing');
$Comments           = $objDb->getField(0, 'comments');

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
                <td width="230">Base Fabric/ Shell Color</td>
                <td width="98%"><input type="text" name="base_fabric" value="<?=$sBaseFabric?>" class="textbox" size="20" style='width:94%;'></td>
            </tr>
            
            <tr class="evenRow">
                <td>In Side Lining Layer Color</td>
                <td><input type="text" name="layer_color" value="<?=$sLayerColor?>" class="textbox" size="20" style='width:94%;'></td>
            </tr>
            
            <tr class="evenRow">
                <td>Printing on the Pocket or Waist Band</td>
                <td><input type="text" name="pocket_printing" value="<?=$sPocketPrinting?>" class="textbox" size="20" style='width:94%;'></td>
            </tr>
            
            <tr class="evenRow">
                <td>Printing on the Back or 1/2 Moon Patch</td>
                <td><input type="text" name="back_printing" value="<?=$sBackPrinting?>" class="textbox" size="20" style='width:94%;'></td>
            </tr>
            
            <tr class="evenRow">
                <td>Special Comments for Pocketing</td>
                <td colspan="2"><textarea name="comments" cols="50" rows="10" style='width:94%;'><?=$Comments?></textarea></td>
            </tr>

        </table>
        <br/><br/>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">
        
        <br/>
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
