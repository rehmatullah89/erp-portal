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
            $Sizes          = getDbValue("sizes", "tbl_qa_reports", "id='$Id'");            
            $SizesList      = getList("tbl_sizes", "id", "size", "id IN ($Sizes)");
            
            $sBarCodeFormat = getDbValue("barcode_format", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sResult        = getDbValue("ean_result", "tbl_qa_hohenstein", "audit_id='$Id'");
            $sComments      = getDbValue("ean_comments", "tbl_qa_hohenstein", "audit_id='$Id'");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3>Ean Code</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="EanCodeTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="60"><b>Size</b></td>
                  <td><b>Position</b></td>
                  <td width="180"><b>Code</b></td>
                  <td width="130"><b>Result</b></td>
            </tr>
<?
            $sSQL = "SELECT *
                        FROM tbl_qa_ean_codes
                        WHERE audit_id='$Id'";
            $objDb->query($sSQL);
            
            $iCount = $objDb->getCount( );
            
            if($iCount > 0)
            {
                for($i=0; $i<$iCount; $i++)
                {                    
                    $iSerial    = $objDb->getField($i, "serial");
                    $iSizeId    = $objDb->getField($i, "size_id");
                    $sPosition  = $objDb->getField($i, "position");
                    $sCode      = $objDb->getField($i, "code");
                    $sEanResult = $objDb->getField($i, "result");
                    
?>
                    <tr>
                        <td><?=$i+1?></td>
                        <td id="SizesRow">
                            <select name="Sizes[]">
                                <option value=""></option>
<?
                               foreach($SizesList as $iSize=> $sSize)
                               {
?>
                                <option value="<?=$iSize?>" <?=($iSize == $iSizeId?'selected':'')?>><?=$sSize?></option>
<?
                               }
?>
                            </select>
                        </td>
                        <td id="PositionRow"><input type="text" name="Positions[]" value="<?=$sPosition?>" class="textbox" size="30" /></td>
                        <td id="EanCodeRow"><input type="text" name="EanCodes[]" value="<?=$sCode?>" class="textbox" size="20" /></td>    
                        <td id="EanResultRow">
                            <select name="EanCodeResults[]">
                                <option value=""></option>
                                <option value="P" <?=($sEanResult == 'P'?'selected':'')?>>Pass</option>
                                <option value="F" <?=($sEanResult == 'F'?'selected':'')?>>Fail</option>
                            </select>
                        </td>
                    </tr>
<?
                }
            }
            else
            {
                $iCount = 1;
?>
           <tr>
                        <td><?=$i+1?></td>
                        <td id="SizesRow">
                            <select name="Sizes[]">
                                <option value=""></option>
<?
                               foreach($SizesList as $iSize=> $sSize)
                               {
?>
                                <option value="<?=$iSize?>" <?=($iSize == $iSizeId?'selected':'')?>><?=$sSize?></option>
<?
                               }
?>
                            </select>
                        </td>
                        <td id="PositionRow"><input type="text" name="Positions[]" class="textbox" size="30" /></td>
                        <td id="EanCodeRow"><input type="text" name="EanCodes[]" class="textbox" size="20" /></td>    
                        <td id="EanResultRow">
                            <select name="EanCodeResults[]">
                                <option value=""></option>
                                <option value="P" <?=($sEanResult == 'P'?'selected':'')?>>Pass</option>
                                <option value="F" <?=($sEanResult == 'F'?'selected':'')?>>Fail</option>
                            </select>
                        </td>
                    </tr>
<?
            }
?>
        </table>
        <br/>
        <input type="hidden" name="Counter" id="Counter" value="<?=$iCount?>">
            <a id="BtnAddRow" onclick="AddObservation()">Add [+]</a> / <a id="BtnDelRow" onclick="DeleteObservation()">Remove [-]</a>
            <br/><br/>
            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                      <td width="100">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                </tr>
                 <tr class="sdRowHeader">
                     <td colspan="3"><b>Ean Code Result & Comments</b></td>
                </tr>
                <tr>
                    <td width="120">Result</td>
                    <td width="20">:</td>
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
                    <td width="120">Barcode Format</td>
                    <td width="20">:</td>
                    <td id="ResultsRow">    
                        <select name="BarcodeFormat" required="">
                            <option value="">Select Format</option>
                            <option value="1" <?=($sBarCodeFormat == 1?'selected':'')?>>EAN-8</option>
                            <option value="2" <?=($sBarCodeFormat == 2?'selected':'')?>>EAN-13</option>
                        </select>
                    </td>
                </tr> 
                <tr>
                    <td width="120">Comments</td>
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
    function AddObservation() 
    {
        var table = document.getElementById("EanCodeTable");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);

        cell1.innerHTML = i;
        cell2.innerHTML = document.getElementById("SizesRow").innerHTML;
        cell3.innerHTML = '<input type="text" name="Positions[]" class="textbox" size="30" />';
        cell4.innerHTML = '<input type="text" name="EanCodes[]" class="textbox" size="20" />';
        cell5.innerHTML = document.getElementById("EanResultRow").innerHTML;
        i++;
        document.getElementById("CountRows").value = i;
    }

    function DeleteObservation() {
        var table = document.getElementById("EanCodeTable");
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