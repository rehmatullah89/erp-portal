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

$sSQL = "SELECT * FROM tbl_ppmeeting_fabric_details WHERE audit_id='$Id'";
$objDb->query($sSQL);

$sMillInfo         = $objDb->getField(0, 'mill_info');
$sFabricCode       = $objDb->getField(0, 'fabric_code');
$sOneWay           = $objDb->getField(0, 'one_way');
$sYarnDyed         = $objDb->getField(0, 'yarn_dyed');
$sWovenType        = $objDb->getField(0, 'woven_type');
$sTwoWay           = $objDb->getField(0, 'two_way');
$sPrinted          = $objDb->getField(0, 'printed');
$sKnitType         = $objDb->getField(0, 'knit_type');
$sSolid            = $objDb->getField(0, 'solid');
$sPieceDyed        = $objDb->getField(0, 'piece_dyed');
$sPileFabric       = $objDb->getField(0, 'pile_fabric');
$sCheck            = $objDb->getField(0, 'check_fabric'); 
$sStripeYarnDyed   = $objDb->getField(0, 'stripe_yarn_dyed');
$sRelaxedSpread    = $objDb->getField(0, 'relaxed_spread');
$sInterlining      = $objDb->getField(0, 'interlining');
$sEngineered       = $objDb->getField(0, 'engineered');
$sGsmApproved      = $objDb->getField(0, 'gsm_approved');
        
if($Edit != 'Y')
{
?>
    <a href="quonda/view-qa-report.php?Id=<?= $Id ?>" style="font-weight: bold; padding: 5px;"><img style="width:20px; line-height: 15px; margin-bottom: -5px;" src="images/icons/back.png">Back</a>
<?
}
?>
<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="quonda/update-ppmeeting-section.php">
    
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr>
                <td width="130"><b>Fabric Mill Info</b></td>
                <td><input type="text" name="millInfo" value="<?=$sMillInfo?>" class="textbox" size="20" style='width:95%;'></td>
                <td width="130"><b>Fabric Code & Disc</b></td>
                <td><input type="text" name="fabricCode" value="<?=$sFabricCode?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
        </table>
      
        <br/><br/>
        <h3>Fabric Detail Checks</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
            <tr class="evenRow">
                <td>One Way</td>
                <td width="50"><input type="checkbox" name="oneWay" value="Y" <?=($sOneWay == 'Y'?'checked':'')?>></td>
                
                <td>Yarn Dyed</td>
                <td width="50"><input type="checkbox" name="yarnDyed" value="Y" <?=($sYarnDyed == 'Y'?'checked':'')?>></td>
                
                <td>Woven Type</td>
                <td width="50"><input type="checkbox" name="wovenType" value="Y" <?=($sWovenType == 'Y'?'checked':'')?>></td>
            </tr>
            
            <tr class="oddRow">
                <td>Two Way</td>
                <td><input type="checkbox" name="twoWay" value="Y" <?=($sTwoWay == 'Y'?'checked':'')?>></td>
                
                <td>Printed</td>
                <td><input type="checkbox" name="printed" value="Y" <?=($sPrinted == 'Y'?'checked':'')?>></td>
                
                <td>Knit Type</td>
                <td><input type="checkbox" name="knitType" value="Y" <?=($sKnitType == 'Y'?'checked':'')?>></td>
            </tr>
            
            <tr class="evenRow">
                <td>Solid</td>
                <td><input type="checkbox" name="solid" value="Y" <?=($sSolid == 'Y'?'checked':'')?>></td>
                
                <td>Piece Dyed</td>
                <td><input type="checkbox" name="pieceDyed" value="Y" <?=($sPieceDyed == 'Y'?'checked':'')?>></td>
                
                <td>Pile Fabric</td>
                <td><input type="checkbox" name="pileFabric" value="Y" <?=($sPileFabric == 'Y'?'checked':'')?>></td>
            </tr>
            
            <tr class="oddRow">
                <td>Check</td>
                <td><input type="checkbox" name="checkFab" value="Y" <?=($sCheck == 'Y'?'checked':'')?>></td>
                
                <td>Stripe/ Yarn Dyed</td>
                <td><input type="checkbox" name="stripeYarn" value="Y" <?=($sStripeYarnDyed == 'Y'?'checked':'')?>></td>
                
                <td>Relaxed before Spreading</td>
                <td><input type="checkbox" name="relaxedSpread" value="Y" <?=($sRelaxedSpread == 'Y'?'checked':'')?>></td>
            </tr>
            
            <tr class="evenRow">
                <td>Interlining</td>
                <td><input type="checkbox" name="interlining" value="Y" <?=($sInterlining == 'Y'?'checked':'')?>></td>
                
                <td>Engineered</td>
                <td><input type="checkbox" name="engineered" value="Y" <?=($sEngineered == 'Y'?'checked':'')?>></td>
                
                <td>GSM Approved</td>
                <td><input type="checkbox" name="gsmApproved" value="Y" <?=($sGsmApproved == 'Y'?'checked':'')?>></td>
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


