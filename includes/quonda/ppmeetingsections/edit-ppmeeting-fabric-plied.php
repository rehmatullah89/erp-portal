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
    if($Edit == 'Y')
    {
?>
<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="quonda/update-ppmeeting-section.php" class="frmOutline">
    
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="FabricPliedTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="150"><b>Parts</b></td>
                  <td><b>Horizontal Matching</b></td>
                  <td><b>Vertical Matching</b></td>
                  <td><b>100% Matching</b></td>
                  <td><b>In Pairs</b></td>
                  <td><b>Balance Only</b></td>
                  <td><b>Mirror /Chevron</b></td>
                  <td><b>Remarks</b></td>
            </tr>

            <tr>
                <td>1</td>
                <td><input type="text" name="Parts[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="HMatching[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="VMatching[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="HpMatching[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="InPairs[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="BalanceOnly[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="Mirror[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="Remarks[]" value="" class="textbox" size="20" style='width:95%;'></td>
            </tr>

        </table>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">
        <input type="hidden" name="CountRows" id="CountRows" value="1">
        <input type="submit" value="Submit" style="margin: 5px;">
        <a id="BtnAddRow" onclick="AddRow()">Add [+]</a> / <a id="BtnDelRow" onclick="DeleteRow()">Remove [-]</a>

</div>
<br/><br/>
<?
    }else{
?>
<a href="quonda/view-qa-report.php?Id=<?= $Id ?>" style="font-weight: bold; padding: 5px;"><img style="width:20px; line-height: 15px; margin-bottom: -5px;" src="images/icons/back.png">Back</a>
<?
    }
?>
<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
        <h3>List of Existing Fabric Plied Data</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="150"><b>Parts</b></td>
                  <td><b>Horizontal Matching</b></td>
                  <td><b>Vertical Matching</b></td>
                  <td><b>100% Matching</b></td>
                  <td><b>In Pairs</b></td>
                  <td><b>Balance Only</b></td>
                  <td><b>Mirror /Chevron</b></td>
                  <td><b>Remarks</b></td>
            </tr>
<?
             $sSQL = "SELECT * FROM tbl_ppmeeting_fabric_plied WHERE audit_id='$Id'";
             $objDb->query($sSQL);
             $iCount = $objDb->getCount( );

            if($iCount > 0)
            {
                for ($i = 0; $i < $iCount; $i++)
                {
                    $sParts         = $objDb->getField($i, "parts");
                    $sHMatching     = $objDb->getField($i, "h_matching");
                    $sVMatching     = $objDb->getField($i, "v_matching");
                    $sHpMatching    = $objDb->getField($i, "hp_matching");
                    $sInPairs       = $objDb->getField($i, "in_pairs");
                    $sBalanceOnly   = $objDb->getField($i, "balance_only");
                    $sMirror        = $objDb->getField($i, "mirror");
                    $sRemarks       = $objDb->getField($i, "remarks");
                    
?>
            <tr class="evenRow"><td><?= $i+1?></td><td><?=$sParts?></td><td><?=$sHMatching?></td><td><?=$sVMatching?></td><td><?=$sHpMatching?></td><td><?=$sInPairs?></td><td><?=$sBalanceOnly?></td><td><?= $sMirror?></td><td><?=$sRemarks?></td></tr>
<?
                }
            }
            else
            {
?>
            <tr><td colspan="8" align="center" style="color:lightgray; font-size: 24px;"> No Previous Information Exists!</td></tr>
<?
            }
?>
        </table>
    </form>    
    
</div>
<script type="text/javascript">
	    <!--

    var i=2;
    function AddRow() {
        var table = document.getElementById("FabricPliedTable");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        var cell7 = row.insertCell(6);
        var cell8 = row.insertCell(7);
        var cell9 = row.insertCell(8);

        cell1.innerHTML = i;        
        cell2.innerHTML = "<input type='text' class='textbox' name='Parts[]' value=''  style='width:95%;'/>";
        cell3.innerHTML = "<input type='text' class='textbox' name='HMatching[]' value=''  style='width:95%;'/>";
        cell4.innerHTML = "<input type='text' class='textbox' name='VMatching[]' value=''  style='width:95%;'/>";
        cell5.innerHTML = "<input type='text' class='textbox' name='HpMatching[]' value=''  style='width:95%;'/>";
        cell6.innerHTML = "<input type='text' class='textbox' name='InPairs[]' value=''  style='width:95%;'/>";
        cell7.innerHTML = "<input type='text' class='textbox' name='BalanceOnly[]' value=''  style='width:95%;'/>";
        cell8.innerHTML = "<input type='text' class='textbox' name='Mirror[]' value=''  style='width:95%;'/>";
        cell9.innerHTML = "<input type='text' class='textbox' name='Remarks[]' value=''  style='width:95%;'/>";
        i++;
        document.getElementById("CountRows").value = i;
    }

    function DeleteRow() {
        var table = document.getElementById("FabricPliedTable");
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
