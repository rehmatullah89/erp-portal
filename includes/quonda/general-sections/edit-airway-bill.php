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
            $sAirwayBill    = getDbValue("airway_bill_applicable", "tbl_qa_report_details", "audit_id='$Id'");
            $sBillNumber    = getDbValue("airway_bill_number", "tbl_qa_report_details", "audit_id='$Id'");
            $sComments      = getDbValue("airway_bill_comments", "tbl_qa_report_details", "audit_id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
                <table border="0" cellpadding="3" cellspacing="0" width="100%">
                    <tr>
                    <td width="240">Is Airway Bill No. Applicable for this?<span class="mandatory">*</span></td>
                    <td width="20" align="center">:</td>
                    <td>
                        <input type="radio" name="AirwayBill" value="Y" onchange="toggleBillDisplay(this.value);" <?=($sAirwayBill == 'Y'?'checked':'')?>> Yes &nbsp; <input type="radio" name="AirwayBill" value="N" onchange="toggleBillDisplay(this.value);"  <?=($sAirwayBill == 'N'?'checked':'')?>> No &nbsp;
                    </td>
                    </tr>
                        
                    <tr><td colspan="3">&nbsp;</td></tr>
                    
                    <tr id="ToggleRowId" style="<?=($sAirwayBill == 'Y'?'':'display:none;')?>">
                        <td width="140">Airway Bill No.<span class="mandatory">*</span></td>
                        <td width="20" align="center">:</td>
                        <td>
                            <input type="text" class="textbox" name="BillNumber" id="BillNumber" value="<?=$sBillNumber?>" />
                        </td>
                    </tr>

                    <tr valign="top">
                          <td width="140">Comments</td>
                          <td width="20" align="center">:</td>
                          <td><textarea name="AirwayComments" class="textarea" style="width:98%; height:80px;"><?= $sComments?></textarea></td>
                    </tr>
                </table>
	</div>
	
<script type="text/javascript">
<!--

    function toggleBillDisplay(Val)
    {
        if(Val == 'Y')
            document.getElementById("ToggleRowId").style.display = '';
        else
            document.getElementById("ToggleRowId").style.display = 'none';
    }

	-->
</script>	