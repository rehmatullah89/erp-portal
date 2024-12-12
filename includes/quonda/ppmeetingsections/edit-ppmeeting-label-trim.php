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

$sSQL = "SELECT * FROM tbl_ppmeeting_label_trim WHERE audit_id='$Id'";
$objDb->query($sSQL);

$sMainLabel         = $objDb->getField(0, 'mainlabel');
$sMainLabelColor1   = $objDb->getField(0, 'mainlabel_color1');
$sMainLabelColor2   = $objDb->getField(0, 'mainlabel_color2');
$sMainLabelAttach   = $objDb->getField(0, 'mainlabel_attachment'); //file
$sSizeLabel         = $objDb->getField(0, 'sizelabel');
$sSizeLabelColor1   = $objDb->getField(0, 'sizelabel_color1');
$sSizeLabelColor2   = $objDb->getField(0, 'sizelabel_color2');
$sSizeLabelAttach   = $objDb->getField(0, 'sizelabel_attachment');//file
$sCareLabel         = $objDb->getField(0, 'carelabel');
$sCareLabelColor1   = $objDb->getField(0, 'carelabel_color1');
$sCareLabelColor2   = $objDb->getField(0, 'carelabel_color2');
$sCareLabelAttach   = $objDb->getField(0, 'carelabel_attachment');//file
$sBarCodeSticker    = $objDb->getField(0, 'barcode_sticker'); // checkbox
$sCareLabelInstruct = $objDb->getField(0, 'carelabel_instructs');//file
$sContents          = $objDb->getField(0, 'contents');
$sPriceTicket       = $objDb->getField(0, 'price_ticket');
$sAttachAtSizeLabel = $objDb->getField(0, 'attachat_sizelabel');
$sAttachMethod      = $objDb->getField(0, 'attach_method');


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
            <tr class="headerRow" align="center">
                <td width="150"><h4>Label</h4></td>
                <td><h4>Placement</h4></td>
                <td colspan="2"><h4>Attaching Thread Color</h4></td>
                <td width="230"><h4>Attaching Method</h4></td>
            </tr>
            <tr class="evenRow">
                <td>Main Label</td>
                <td><input type="text" name="mainlabel" value="<?=$sMainLabel?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="mainlabel_color1" value="<?=$sMainLabelColor1?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="mainlabel_color2" value="<?=$sMainLabelColor2?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="file" name="mainlabel_attachment" value="">
<?
                if (!empty($sMainLabelAttach) && @file_exists($sBaseDir.QUONDA_PICS_DIR."ppmeeting/".$sMainLabelAttach))                        
                {
?>
                    <span> (<a href="<?= SITE_URL.QUONDA_PICS_DIR."ppmeeting/".$sMainLabelAttach;?>" class="lightview"><?= $sMainLabelAttach ?></a>)&nbsp;</span>
<?
                }
?>
                </td>
            </tr>
            <tr class="oddRow">
                <td>Size Label</td>
                <td><input type="text" name="sizelabel" value="<?=$sSizeLabel?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="sizelabel_color1" value="<?=$sSizeLabelColor1?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="sizelabel_color2" value="<?=$sSizeLabelColor2?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="file" name="sizelabel_attachment" value="">
                <?
                if (!empty($sSizeLabelAttach) && @file_exists($sBaseDir.QUONDA_PICS_DIR."ppmeeting/".$sSizeLabelAttach))                        
                {
?>
                    <span> (<a href="<?= SITE_URL.QUONDA_PICS_DIR."ppmeeting/".$sSizeLabelAttach;?>" class="lightview"><?= $sSizeLabelAttach ?></a>)&nbsp;</span>
<?
                }
?>
                </td>
            </tr>
            <tr class="evenRow">
                <td>Care Label</td>
                <td><input type="text" name="carelabel" value="<?=$sCareLabel?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="carelabel_color1" value="<?=$sCareLabelColor1?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="carelabel_color2" value="<?=$sCareLabelColor2?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="file" name="carelabel_attachment" value="">
<?
                if (!empty($sCareLabelAttach) && @file_exists($sBaseDir.QUONDA_PICS_DIR."ppmeeting/".$sCareLabelAttach))                        
                {
?>
                    <span> (<a href="<?= SITE_URL.QUONDA_PICS_DIR."ppmeeting/".$sCareLabelAttach;?>" class="lightview"><?= $sCareLabelAttach ?></a>)&nbsp;</span>
<?
                }
?>
                </td>
            </tr>
            <tr class="oddRow">
                <td colspan="2">Barcode Stickers/ Sorting ?</td>
                <td colspan="3"><input type="checkbox" name="barcode_sticker" value="Y" <?=($sBarcodeSticker == 'Y'?'checked':'')?>></td>
            </tr>
            <tr class="evenRow">
                <td colspan="2" align="center"><b>Care Label Instructions</b></td>
                <td colspan="3" align="center"><b>Contents</b></td>
            </tr>
            <tr class="oddRow">
                <td colspan="2" align="center"><input type="file" name="carelabel_instructs" value="">
                <?
                if (!empty($sCareLabelInstruct) && @file_exists($sBaseDir.QUONDA_PICS_DIR."ppmeeting/".$sCareLabelInstruct))                        
                {
?>
                    <span> (<a href="<?= SITE_URL.QUONDA_PICS_DIR."ppmeeting/".$sCareLabelInstruct;?>" class="lightview"><?= $sCareLabelInstruct ?></a>)&nbsp;</span>
<?
                }
?>
                </td>
                <td colspan="3" align="center"><textarea name="contents" cols="100" rows="10" style='width:95%;'><?=$sContents?></textarea></td>
            </tr>
            <tr class="evenRow" align="center">
                <td colspan="2"><b>Hang Tag/ Price Ticeket/ Disclaimer</b></td>
                <td><b>Placement</b></td>
                <td colspan="2"><b>Attaching Method</b></td>
            </tr>
            <tr class="oddRow">
                <td colspan="2" align="center"><input type="text" name="price_ticket" value="<?=$sPriceTicket?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="attachat_sizelabel" value="<?=$sAttachAtSizeLabel?>" class="textbox" size="20" style='width:95%;'></td>
                <td colspan="2" align="center"><input type="text" name="attach_method" value="<?=$sAttachMethod?>" class="textbox" size="20" style='width:95%;'></td>
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