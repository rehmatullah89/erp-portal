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
    
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="ElasticTapeTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="200"><b>Fabric Color</b></td>
                  <td><b>Elastic Color</b></td>
                  <td><b>Width of Elastic</b></td>
                  <td><b>Consumption</b></td>
                  <td><b>Pre-Shrunk</b></td>
                  <td><b>Placement</b></td>
            </tr>

            <tr>
                <td>1</td>
                <td><input type="text" name="FabColor[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="ElasticColor[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="ElasticWidth[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="Conusumption[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="PreShrunk[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="Placement[]" value="" class="textbox" size="20" style='width:95%;'></td>
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
        <h3>List of Existing Elastic Tape Application Data</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="200"><b>Fabric Color</b></td>
                  <td><b>Elastic Color</b></td>
                  <td><b>Width of Elastic</b></td>
                  <td><b>Consumption</b></td>
                  <td><b>Pre-Shrunk</b></td>
                  <td><b>Placement</b></td
            </tr>
<?
             $sSQL = "SELECT * FROM tbl_ppmeeting_elastic_tape WHERE audit_id='$Id'";
             $objDb->query($sSQL);
             $iCount = $objDb->getCount( );

            if($iCount > 0)
            {
                for ($i = 0; $i < $iCount; $i++)
                {
                    $sFacricColor       = $objDb->getField($i, "fabric_color");
                    $sElasticColor      = $objDb->getField($i, "elastic_color");
                    $sElasticWidth      = $objDb->getField($i, "elastic_width");
                    $sConusumption      = $objDb->getField($i, "consumption");
                    $sPreShrunk         = $objDb->getField($i, "pre_shrunk");
                    $sPlacement         = $objDb->getField($i, "plcement");
                    
?>
                    <tr class="evenRow"><td><?= $i+1?></td><td><?=$sFacricColor?></td><td><?=$sElasticColor?></td><td><?=$sElasticWidth?></td><td><?=$sConusumption?></td><td><?=$sPreShrunk?></td><td><?=$sPlacement?></td></tr>
<?
                }
            }
            else
            {
?>
            <tr><td colspan="7" align="center" style="color:lightgray; font-size: 24px;"> No Previous Information Exists!</td></tr>
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
        var table = document.getElementById("ElasticTapeTable");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        var cell7 = row.insertCell(6);

        cell1.innerHTML = i; 
        cell2.innerHTML = "<input type='text' class='textbox' name='FabColor[]' value=''  style='width:95%;'/>";
        cell3.innerHTML = "<input type='text' class='textbox' name='ElasticColor[]' value=''  style='width:95%;'/>";
        cell4.innerHTML = "<input type='text' class='textbox' name='ElasticWidth[]' value=''  style='width:95%;'/>";
        cell5.innerHTML = "<input type='text' class='textbox' name='Conusumption[]' value=''  style='width:95%;'/>";
        cell6.innerHTML = "<input type='text' class='textbox' name='PreShrunk[]' value=''  style='width:95%;'/>";
        cell7.innerHTML = "<input type='text' class='textbox' name='Placement[]' value=''  style='width:95%;'/>";
        i++;
        document.getElementById("CountRows").value = i;
    }

    function DeleteRow() {
        var table = document.getElementById("ElasticTapeTable");
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
