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

if($Edit != 'Y')
{
?>
    <a href="quonda/view-qa-report.php?Id=<?= $Id ?>" style="font-weight: bold; padding: 5px;"><img style="width:20px; line-height: 15px; margin-bottom: -5px;" src="images/icons/back.png">Back</a>
<?
}
?>
<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="quonda/update-ppmeeting-section.php">
    
        <table border="1" bordercolor="#ffffff" cellpadding="7" cellspacing="0" style="text-align: center;" width="100%">
            <tr class="sdRowHeader">
                <td width="70" rowspan="2"><b>Fabric</b></td>
                <td width="70" rowspan="2"><b>Fabric Color</b></td>
                <td width="70" rowspan="2"><b>Size</b></td>
                <td width="230" colspan="3"><b>Attaching</b></td>
                <td  colspan="4"><b>Attaching Method</b></td>
                <td width="70" rowspan="2"><b>pcs per Garment</b></td>
                <td width="70"><b>Extra</b></td>
            </tr>
            <tr class="sdRowHeader">
                <td width="50">Color</td>
                <td width="50">Tkt</td>
                <td width="50">Consump</td>
                <td width="40">Lock</td>
                <td width="40">Chain</td>
                <td width="40">Normal</td>
                <td width="40">Shrank</td>
                <td><b>Bttns</b></td>
            </tr>
<?
                $sSQL = "SELECT * FROM tbl_ppmeeting_button_holes WHERE audit_id='$Id'";
                $objDb->query($sSQL);

                $sFabric        = $objDb->getField(0, "fabric");
                $sBtnColor      = $objDb->getField(0, "btn_color");
                $sSize          = $objDb->getField(0, "size");
                $sColor         = $objDb->getField(0, "color");
                $sTkt           = $objDb->getField(0, "tkt");
                $sConsump       = $objDb->getField(0, "consump");
                $sLock          = $objDb->getField(0, "lock_attach");
                $sChain         = $objDb->getField(0, "chain");
                $sNormal        = $objDb->getField(0, "normal");
                $sShank         = $objDb->getField(0, "shank");
                $sPcsPerGarment = $objDb->getField(0, "pcs_per_garment");
                $sExtraBtns     = $objDb->getField(0, "extra_btns");
?>
            <tr>
                <td with="70"><input type="text" name="fabric" class="textbox" size="8" maxlength="50" value="<?=$sFabric?>" ></td>
                <td with="70"><input type="text" name="btn_color" class="textbox" size="8" maxlength="50" value="<?=$sBtnColor?>" ></td>
                <td with="70"><input type="text" name="size" class="textbox" size="8" maxlength="50" value="<?=$sSize?>" ></td>
                <td><input type="text" name="color" class="textbox" size="8" maxlength="50" value="<?=$sColor?>" ></td>
                <td><input type="text" name="tkt" class="textbox" size="8" maxlength="50" value="<?=$sTkt?>" ></td>
                <td><input type="text" name="consump" class="textbox" size="8" maxlength="50" value="<?=$sConsump?>" ></td>
                <td><input type="text" name="lock_attach" class="textbox" size="8" maxlength="50" value="<?=$sLock?>" ></td>
                <td><input type="text" name="chain" class="textbox" size="8" maxlength="50" value="<?=$sChain?>" ></td>
                <td><input type="text" name="normal" class="textbox" size="8" maxlength="50" value="<?=$sNormal?>" ></td>
                <td><input type="text" name="shank" class="textbox" size="8" maxlength="50" value="<?=$sShank?>" ></td>
                <td with="70"><input type="text" name="pcs_per_garment" class="textbox" size="8" maxlength="50" value="<?=$sPcsPerGarment?>" ></td>
                <td with="70"><input type="text" name="extra_btns" class="textbox" size="8" maxlength="50" value="<?=$sExtraBtns?>" ></td>
            </tr>
        </table>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">
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
