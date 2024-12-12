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
	**  Project Developer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmatullah Bhatti                                                          **
	**      Email :  rehmatullahbhatti@gmail.com                                                 **
	**      Phone :  +92 344 40 43 675                                                           **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
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
            $sAuditCode   = getDbValue("audit_code", "tbl_qa_reports", "id='$Id'"); 
            $sAuditDate   = getDbValue("audit_date", "tbl_qa_reports", "id='$Id'"); 
            @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);
            
            $sInspectorSignature = "";
            $sManufactureSignature = "";

            if (@file_exists($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_inspector.jpg") && @filesize($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_inspector.jpg"))
                    $sInspectorSignature = "{$sAuditCode}_inspector.jpg";

            if (@file_exists($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_manufacturer.jpg") && @filesize($sBaseDir.$sBaseDir.SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/"."{$sAuditCode}_manufacturer.jpg"))
                    $sManufactureSignature = "{$sAuditCode}_manufacturer.jpg";
        
            
            $sInspector     = getDbValue("signatures_inspector", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sManufacturer  = getDbValue("signatures_manufacturer", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sComments      = getDbValue("signatures_comments", "tbl_qa_hohenstein", "audit_id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3>Signature info</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="MasterCartonsTable">
            <tr>
                <td width="100"><b>Inspector:</b></td>
                <td><input type="text" name="Inspector" value="<?=$sInspector?>" size="20" required=""/></td>
                <td width="250"><input type="file" name="InspectorSign" value=""/>
<?
                        if($sInspectorSignature != "")
                        {
?>
                                <br/><a href="<?= SIGNATURES_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/".@basename($sInspectorSignature) ?>" class="lightview" rel="gallery[picture]" title="<?= utf8_encode($sInspectorSignature) ?> :: :: topclose: true"><?= @basename($sInspectorSignature) ?></a>
<?
                        }
?>
                </td>
            </tr>
                <tr>
                <td width="100"><b>Manufacturer:</b></td>
                <td><input type="text" name="Manufacturer" value="<?=$sManufacturer?>" size="20" required=""/></td>
                <td width="250"><input type="file" name="ManufactureSign" value=""/>
<?
                        if($sManufactureSignature != "")
                        {
?>
                                <br/><a href="<?= SIGNATURES_PICS_DIR."/".$sYear."/".$sMonth."/".$sDay."/".@basename($sManufactureSignature) ?>" class="lightview" rel="gallery[picture]" title="<?= utf8_encode($sManufactureSignature) ?> :: :: topclose: true"><?= @basename($sManufactureSignature) ?></a>
<?
                        }
?>
                </td>
            </tr>
        </table>
        <br/>
            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                      <td width="140">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                </tr>
                 <tr class="sdRowHeader">
                     <td colspan="3"><b>Signature Comments</b></td>
                </tr>                
                <tr>
                    <td width="80">Comments</td>
                    <td width="20">:</td>
                    <td>    
                        <textarea name="Comments" Style="width:98%;" rows="5"><?=$sComments?></textarea>
                    </td>
                </tr>

            </table>
	</div>