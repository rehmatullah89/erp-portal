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

	$sStatementList = getList("tbl_statements", "id", "statement", "FIND_IN_SET('1', sections)");
	$sConformities  = getList("tbl_qa_product_conformity", "serial", "observation", "audit_id='$Id'", "serial");
	
	$sResult    = getDbValue("product_conformity_result", "tbl_qa_report_details", "audit_id='$Id'");
	$sComments  = getDbValue("product_conformity_comments", "tbl_qa_report_details", "audit_id='$Id'");
    
        $iCounter   = count($sConformities);
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3 style="background:#c6c6c6;">Observations/ Differences</h3>
	
        <span id="OptionsId" style="display:none;">
            <? $sOptions = "<option value=''></option>";
            foreach($sStatementList as $iStatement=> $sStatement)
            {
                $sOptions .= "<option value='".$iStatement."'>{$sStatement}</option>";
            }
            print $sOptions; ?>
        </span>
        
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="ProductConformityTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td><b>Product Difference</b></td>
                  <td width="100"><b>Options</b></td>
            </tr>
<?
                $iInc = 0;                 
                foreach($sConformities as $iSerial => $sObservation)
		{
?>
			<tr id="RowId<?=$iInc?>">
				<td><?=$iInc+1?></td>
				<td>
					<select name="Statements[]" id="Statement<?= $iInc ?>" class="statement" style="width:90%;">
						<option value=""></option>
<?
					   foreach($sStatementList as $iStatement=> $sStatement)
					   {
?>
						<option value="<?=$iStatement?>" <?=("{$sStatement}" == "{$sObservation}"?'selected':'')?>><?=$sStatement?></option>
<?
					   }
?>
					</select>
				</td>                                
                                <td width="80"><a href="javascript:;"><img src="images/icons/delete.gif" onclick="deleteRow(<?=$iInc ?>)" alt="Delete" title="Delete" width="16" height="16"></a></td>
			</tr>
<?                  $iInc ++;
		}
?>
        </table>
        
		<div style="margin-right: 30px; float: right;">
                    <input type="hidden" name="Counter" id="Counter" value="<?=($iCounter>0)?($iCounter+1):1?>">
		  <input type="button" id="BtnAdd" value=" Add " onclick="AddNewStatement()" />
        </div>
		
            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                      <td width="80">&nbsp;</td>
                      <td>&nbsp;</td>
                </tr>
                 <tr class="sdRowHeader">
                     <td colspan="2"><b>Product Conformity Result & Comments</b></td>
                </tr>
                
                <tr>
                    <td width="80">Result</td>
                    <td>
                        <select name="Result" id="Result">
                            <option value="">Select Result</option>
                            <option value="P" <?=($sResult == 'P'?'selected':'')?>>Pass</option>
                            <option value="F" <?=($sResult == 'F'?'selected':'')?>>Fail</option>
                            <option value="N" <?=($sResult == 'N'?'selected':'')?>>N/A</option>
                        </select>
                    </td>
                </tr>
                
                <tr valign="top">
                    <td width="80">Comments</td>
                    <td><textarea name="Comments" Style="width:98%;" rows="5"><?=$sComments?></textarea></td>
                </tr>
            </table>
	</div>
	
	<script type="text/javascript" src="scripts/jquery.js"></script>
	
	<script type="text/javascript">
	<!--
		jQuery.noConflict();

		function validateForm( )
		{
			var objFV = new FormValidator("frmData");

			return true;
		}
		
		
		jQuery(document).on("change", ".statement", function( )
		{
			var iCount = 0;
			
			jQuery(".statement").each(function( )
			{
				if (jQuery(this).val( ) != "")
					iCount ++;
			});
			
			
			if (iCount > 0)
			{
				jQuery("#Result").val("F");
			}
			
			else
			{
				jQuery("#Result").val("P");
			}
		});
		
		
		var iIndex = document.getElementById("Counter").value;
		
		function AddNewStatement() 
		{
                	var table = document.getElementById("ProductConformityTable");
                        var rowCount = table.rows.length;
                        var row = table.insertRow(rowCount);

                        row.setAttribute("id", "RowId"+iIndex, 0);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);

                        cell1.innerHTML = iIndex;
                        cell2.innerHTML = '<select name="Statements['+(iIndex-1)+']" id="Statements'+(iIndex-1)+'" required="" style="width: 90%;">'+document.getElementById("OptionsId").innerHTML+'</select>';
                        cell3.innerHTML = '<a href="javascript:;"><img src="images/icons/delete.gif" onclick="deleteRow('+iIndex+')" alt="Delete" title="Delete" width="16" height="16">';

                        iIndex ++;        
                        document.getElementById("Counter").value = iIndex; 
		}

		
		function deleteRow(TableRowId)
                {
                    if(confirm("Are you sure you want to Delete?"))
                    {
                        jQuery("#RowId"+TableRowId).remove();
                    }
                }
                
    -->
</script> 