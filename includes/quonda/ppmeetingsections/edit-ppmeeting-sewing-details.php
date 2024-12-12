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
                <td width="100"><b>Color</b></td>
                <td width="100" rowspan="2" colspan="2">&nbsp;</td>
                <td colspan="2"><b>Sewing Thread</b></td>
                <td width="50" rowspan="2">&nbsp;</td>
                <td colspan="2"><b>Over Lock</b></td>
                <td colspan="2"><b>Button Hole</b></td>
            </tr>
<?
                $sSQL = "SELECT * FROM tbl_ppmeeting_sewing_details WHERE audit_id='$Id'";
                $objDb->query($sSQL);

                $sColor             = $objDb->getField(0, "color");
                $sSewingThread      = $objDb->getField(0, "sewing_thread");
                $sOverLock          = $objDb->getField(0, "over_lock");
                $sButtonHole        = $objDb->getField(0, "button_hole");                
                $sBtmSize           = $objDb->getField(0, "btm_size");
                $sTopColor          = $objDb->getField(0, "top_color");
                $sBtmColor          = $objDb->getField(0, "btm_color");
                $sTicketSize1       = $objDb->getField(0, "ticket_size1");
                $sColorSpclNdl      = $objDb->getField(0, "color_spcl_ndl");
                $sConsumpMachine    = $objDb->getField(0, "consum_machine"); 
                $sTicketSize2       = $objDb->getField(0, "ticket_size2");
                $sColorQty          = $objDb->getField(0, "color_qty");
                $sStitchSpi         = $objDb->getField(0, "stitch_spi");
?>         
            <tr class="sdRowHeader">
                <td><input type="text" name="color" class="textbox" size="8" maxlength="50" value="<?=$sColor?>" style="width:95%"></td>
                <td colspan="2"><input type="text" name="sewing_thread" class="textbox" size="8" maxlength="50" value="<?=$sSewingThread?>" style="width:95%"></td>
                <td colspan="2"><input type="text" name="over_lock" class="textbox" size="8" maxlength="50" value="<?=$sOverLock?>" style="width:95%"></td>
                <td colspan="2"><input type="text" name="button_hole" class="textbox" size="8" maxlength="50" value="<?=$sButtonHole?>" style="width:95%"></td>
            </tr>
            
            <tr class="sdRowHeader">
                <td>&nbsp;</td>
                <td>Top</td>
                <td>Btm</td>
                <td>Top Color</td>
                <td>Btm Color</td>
                <td>Tkt#</td>
                <td>Color#</td>
                <td>Consump</td>
                <td>Tkt#</td>
                <td>Color#</td>
            </tr>

            <tr class="sdRowHeader">
                <td>Needle</td>
                <td>Needle Pt.</td>
                <td>size</td>
                <td>Brand</td>
                <td>Comments</td>
                <td>Size</td>
                <td>Spcl. Needle</td>
                <td>Machine</td>
                <td>Size</td>
                <td>Qty</td>
            </tr>

            <tr class="evenRow">
                <td>Stitch SPI</td> 
                <td>Ball Point</td>
                <td><input type="text" name="btm_size" class="textbox" size="8" maxlength="50" value="<?=$sBtmSize?>" ></td>
                <td><input type="text" name="top_color" class="textbox" size="8" maxlength="50" value="<?=$sTopColor?>" ></td>
                <td><input type="text" name="btm_color" class="textbox" size="8" maxlength="50" value="<?=$sBtmColor?>" ></td>
                <td><input type="text" name="ticket_size1" class="textbox" size="8" maxlength="50" value="<?=$sTicketSize1?>" ></td>
                <td><input type="text" name="color_spcl_ndl" class="textbox" size="8" maxlength="50" value="<?=$sColorSpclNdl?>" ></td>
                <td><input type="text" name="consum_machine" class="textbox" size="8" maxlength="50" value="<?=$sConsumpMachine?>" ></td>
                <td><input type="text" name="ticket_size2" class="textbox" size="8" maxlength="50" value="<?=$sTicketSize2?>" ></td>
                <td><input type="text" name="color_qty" class="textbox" size="8" maxlength="50" value="<?=$sColorQty?>" ></td>
            </tr>
            <tr class="oddRow">
                <td><input type="text" name="stitch_spi" class="textbox" size="8" maxlength="50" value="<?=$sStitchSpi?>" ></td>
                <td colspan="9">&nbsp;</td>
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
