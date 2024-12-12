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
            $sStatementList = getList("tbl_statements", "id", "statement", "FIND_IN_SET('1', sections)");
            $sConformities  = getList("tbl_qa_product_conformity", "serial", "observation", "audit_id='$Id'");
            
            $sResult    = getDbValue("product_conformity_result", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sComments  = getDbValue("product_conformity_comments", "tbl_qa_hohenstein", "audit_id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3>Observations/ Differences</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="ProductConformityTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td><b>Product Difference</b></td>
            </tr>
<?
            $iCounter = 1;
            if(count($sConformities) > 0)
            {
                foreach($sConformities as $iSerial => $sObservation)
                {
?>
                    <tr>
                        <td><?=$iCounter++?></td>
                        <td id="StatementsId">
                            <select name="Statements[]">
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
                    </tr>
<?
                }
            }
            else
            {
?>
            <tr>
                <td>1</td>
                <td id="StatementsId">
                    <select name="Statements[]">
                        <option value=""></option>
<?
                       foreach($sStatementList as $iStatement=> $sStatement)
                       {
?>
                        <option value="<?=$iStatement?>" <?=@(in_array($iStatement, IO::getArray("Statements"))?'selected':'')?>><?=$sStatement?></option>
<?
                       }
?>
                    </select>
                </td>
            </tr>

<?
            }
?>
        </table>
        <br/>
        <input type="hidden" name="Counter" id="Counter" value="<?=$iCounter?>">
            <a id="BtnAddRow" onclick="AddObservation()">Add [+]</a> / <a id="BtnDelRow" onclick="DeleteObservation()">Remove [-]</a>
            <br/><br/>
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
                        <select name="Result" required="">
                            <option value="">Select Result</option>
                            <option value="P" <?=($sResult == 'P'?'selected':'')?>>Pass</option>
                            <option value="F" <?=($sResult == 'F'?'selected':'')?>>Fail</option>
                            <option value="N" <?=($sResult == 'N'?'selected':'')?>>N/A</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td width="80">Comments</td>
                    <td>    
                        <textarea name="Comments" Style="width:98%;" rows="5"><?=$sComments?></textarea>
                    </td>
                </tr>

            </table>
	</div>
	<script type="text/javascript">
	    <!--

    var i=document.getElementById("Counter").value;
    function AddObservation() 
    {
        var table = document.getElementById("ProductConformityTable");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);

        cell1.innerHTML = i;
        cell2.innerHTML = document.getElementById("StatementsId").innerHTML;
        i++;
        document.getElementById("CountRows").value = i;
    }

    function DeleteObservation() {
        var table = document.getElementById("ProductConformityTable");
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