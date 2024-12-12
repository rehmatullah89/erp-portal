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
            $iTotalCartons = getDbValue("master_cartons", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sResult    = getDbValue("master_cartons_result", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sComments  = getDbValue("master_cartons_comments", "tbl_qa_hohenstein", "audit_id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="MasterCartonsTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td><b>Gross Weight (kg)</b></td>
                  <td width="80"><b>Length</b></td>
                  <td width="80"><b>Width</b></td>
                  <td width="80"><b>Height</b></td>
            </tr>
<?
            $sSQL = "SELECT *
                    FROM tbl_qa_master_cartons
	         WHERE audit_id='$Id'";
        
            $objDb->query($sSQL);
            
            $iCount = $objDb->getCount();
        
            if($iCount > 0)
            {
                for($i=0; $i<$iCount; $i++)
                {
                    $iCartonNo      = $objDb->getField($i, "carton_no");
                    $iGrossWeight   = $objDb->getField($i, "gross_weight");
                    $iLength        = $objDb->getField($i, "length");
                    $iWidth         = $objDb->getField($i, "width");
                    $iHeight        = $objDb->getField($i, "height");
?>
                    <tr>
                        <td><?=$i+1?><input type="hidden" name="TCartons[]" value="<?=$i+1?>"></td>
                        <td id="WeightId"><input type="text" size="8" name="GrossWeight[]" value="<?=$iGrossWeight?>" /></td>
                        <td id="LengthId"><input type="text" size="5" name="Length[]" value="<?=$iLength?>"/></td>
                        <td id="WidthId"><input type="text" size="5" name="Width[]" value="<?=$iWidth?>"/></td>
                        <td id="HeightId"><input type="text" size="5" name="Height[]" value="<?=$iHeight?>"/></td>
                    </tr>
<?
                }
            }
            else
            {
                $iCount = 0;
?>
            <tr>
                <td><?=$iCount+1?><input type="hidden" name="TCartons[]" value="<?=$i+1?>"></td>
                <td id="WeightId"><input type="text" size="8" name="GrossWeight[]" value=""/></td>
                <td id="LengthId"><input type="text" size="5" name="Length[]" value=""/></td>
                <td id="WidthId"><input type="text" size="5" name="Width[]" value=""/></td>
                <td id="HeightId"><input type="text" size="5" name="Height[]" value=""/></td>
            </tr>
<?
            }
?>
        </table>
        <br/>
        <input type="hidden" name="Counter" id="Counter" value="<?=$iCount+1?>">
            <a id="BtnAddRow" onclick="AddMasterCartons()">Add [+]</a> / <a id="BtnDelRow" onclick="DeleteMasterCartons()">Remove [-]</a>
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

    var i=document.getElementById("Counter").value;
    function AddMasterCartons() 
    {
        var table = document.getElementById("MasterCartonsTable");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);

        cell1.innerHTML = i;
        cell2.innerHTML = '<input type="hidden" name="TCartons[]" value=""><input type="text" size="8" name="GrossWeight[]" value="" />';
        cell3.innerHTML = '<input type="text" size="5" name="Length[]" value=""/>';
        cell4.innerHTML = '<input type="text" size="5" name="Width[]" value=""/>';
        cell5.innerHTML = '<input type="text" size="5" name="Height[]" value=""/>';
        i++;
        document.getElementById("CountRows").value = i;
    }

    function DeleteMasterCartons() {
        var table = document.getElementById("MasterCartonsTable");
        var rowCount = table.rows.length;
        
        if(rowCount > 2)
        {
            table.deleteRow(rowCount-1);
            i--;
            document.getElementById("CountRows").value = i;
        }
    }
    -->
</script> 