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
        $iTotalCartons      = getDbValue("master_cartons", "tbl_qa_report_details", "audit_id='$Id'");
        $sComments          = getDbValue("master_cartons_comments", "tbl_qa_report_details", "audit_id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="MasterCartonsTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td><b>Gross Weight (kg)</b></td>
                  <td width="80"><b>Length</b></td>
                  <td width="80"><b>Width</b></td>
                  <td width="80"><b>Height</b></td>
                  <td width="80"><b>Options</b></td>
            </tr>
<?
            $sSQL = "SELECT *
                    FROM tbl_qa_carton_details
	         WHERE audit_id='$Id'";
            
            $objDb->query($sSQL);
            
            $iCount = $objDb->getCount();
                    
            if($iCount > 0)
            {                        
                        for($i=0; $i<$iCount; $i++)
                        {
?>
                            <tr>
                                <td><?=$i+1?><input type="hidden" name="TCartons[]" value=""></td>
                                <td><input type="text" size="8" name="GrossWeight[]" value="<?=$objDb->getField($i, "gross_weight")?>" /></td>
                                <td><input type="text" size="5" name="Length[]" value="<?=$objDb->getField($i, "length")?>"/></td>
                                <td><input type="text" size="5" name="Width[]" value="<?=$objDb->getField($i, "width")?>"/></td>
                                <td><input type="text" size="5" name="Height[]" value="<?=$objDb->getField($i, "height")?>"/></td>
                                <td><a href="javascript:;"><img src="images/icons/delete.gif" onclick="DeleteMasterCartons(<?=$i+1?>)" alt="Delete" title="Delete" width="16" height="16"></a></td>
                            </tr>
<?
                        }                        
            }
            else
                $iCount = 0;
?>
        </table>
        <br/>
        <input type="hidden" name="Counter" id="Counter" value="<?=$iCount?>">
        
        <div style="float: right; margin-right: 25px;">
        <input id="BtnAddRow" type="button" value="+ Add" title="Add" onclick="AddMasterCartons();" />
        </div>
            <br/><br/>
            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                      <td width="140">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                </tr>
                
                <tr class="sdRowHeader">
                     <td colspan="3"><b>Result & Comments</b></td>
                </tr>
                
                <tr>
                    <td width="80">Total Cartons</td>
                    <td width="20">:</td>
                    <td><input type="text" name="TotalCartons" size="10" value="<?=$iTotalCartons?>" /></td>
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
	<script type="text/javascript">
	    <!--

	function validateForm( )
	{
		var objFV = new FormValidator("frmData");

		return true;
	}

	
    function AddMasterCartons() 
    {
        var i=document.getElementById("Counter").value;
                    
        var table = document.getElementById("MasterCartonsTable");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);

        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);

        var Index = parseInt(i) + parseInt(1);
        cell1.innerHTML = Index;
        cell2.innerHTML = '<input type="hidden" name="TCartons[]" value=""><input type="text" size="8" name="GrossWeight[]" value="" />';
        cell3.innerHTML = '<input type="text" size="5" name="Length[]" value=""/>';
        cell4.innerHTML = '<input type="text" size="5" name="Width[]" value=""/>';
        cell5.innerHTML = '<input type="text" size="5" name="Height[]" value=""/>';
        cell6.innerHTML = '<a href="javascript:;"><img src="images/icons/delete.gif" onclick="DeleteMasterCartons('+Index+')" alt="Delete" title="Delete" width="16" height="16">';
        
        i = parseInt(i) + parseInt(1);            
        
        document.getElementById("Counter").value = i;
    }
    
    function DeleteMasterCartons(TableRowId)
    {
        if(confirm("Are you sure you want to Delete?"))
        {
            var table = document.getElementById("MasterCartonsTable");
            
            table.deleteRow(TableRowId);
            
            document.getElementById("Counter").value = document.getElementById("Counter").value - parseInt(1);
        }
    }
    -->
</script> 