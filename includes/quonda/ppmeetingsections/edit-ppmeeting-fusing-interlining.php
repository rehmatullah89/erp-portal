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
    
        <h3>Add New Fusing & Interlining Application Details</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="FusingInterTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="150"><b>Fabric</b></td>
                  <td width="100"><b>Component</b></td>
                  <td width="50"><b>Article</b></td>
                  <td width="70"><b>FBE. CLR</b></td>
                  <td width="50"><b>Tempre.</b></td>
                  <td width="50"><b>MC Belt Speed</b></td>
                  <td width="50"><b>Pressure</b></td>
                  <td width="70"><b>Grain Line</b></td>
            </tr>

            <tr>
                <td>1</td>
                <td><input type="text" name="Fabric[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="Component[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="Article[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="FbeClr[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="Temperature[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="McBeltSpeed[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="Pressure[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="GrainLine[]" value="" class="textbox" size="20" style='width:95%;'></td>                
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
        <h3>List of Fusing & Interlining Application Details</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="150"><b>Fabric</b></td>
                  <td width="100"><b>Component</b></td>
                  <td width="50"><b>Article</b></td>
                  <td width="70"><b>FBE. CLR</b></td>
                  <td width="50"><b>Tempre.</b></td>
                  <td width="50"><b>MC Belt Speed</b></td>
                  <td width="50"><b>Pressure</b></td>
                  <td width="70"><b>Grain Line</b></td>
            </tr>
<?
            $sSQL = "SELECT * FROM tbl_ppmeeting_fusing_interlining WHERE audit_id ='$Id'";

            $objDb->query($sSQL);

            $iCount = $objDb->getCount( );

            if($iCount > 0)
            {

                for ($i = 0; $i < $iCount; $i++)
                {
                     $sFabric       = $objDb->getField($i, "fabric");
                     $sComponents   = $objDb->getField($i, "component");
                     $sArticle      = $objDb->getField($i, "article");
                     $sFbeClr       = $objDb->getField($i, "fbe_clr");
                     $sTemperature  = $objDb->getField($i, "temperature");
                     $sMsBeltSpeed  = $objDb->getField($i, "mcbelt_speed");
                     $sPressure     = $objDb->getField($i, "pressure");
                     $sGrainLine    = $objDb->getField($i, "grain_line");
            
?>
                    <tr class="evenRow"><td><?= $i+1?></td><td><?=$sFabric?></td><td><?=$sComponents?></td><td><?=$sArticle?></td><td><?=$sFbeClr?></td><td><?=$sTemperature?></td><td><?=$sMsBeltSpeed?></td><td><?=$sPressure?></td><td><?=$sGrainLine?></td></tr>
<?
                }
            }
            else
            {
?>
            <tr><td colspan="9" align="center" style="color:lightgray; font-size: 24px;"> No Fusing & Interlining Details Exists!</td></tr>
<?
            }
?>
        </table>
    </form>    
    
</div>

  <td><input type="text" name="Fabric[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="Component[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="Article[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="FbeClr[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="Temperature[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="McBeltSpeed[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="Pressure[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="GrainLine[]" value="" class="textbox" size="20" style='width:95%;'></td>   
                
<script type="text/javascript">
	    <!--

    var i=2;
    function AddRow() {
        var table = document.getElementById("FusingInterTable");
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
        cell2.innerHTML = "<input type='text' class='textbox' name='Fabric[]' value=''  style='width:95%;'/>";
        cell3.innerHTML = "<input type='text' class='textbox' name='Component[]' value=''  style='width:95%;'/>";
        cell4.innerHTML = "<input type='text' class='textbox' name='Article[]' value=''  style='width:95%;'/>";
        cell5.innerHTML = "<input type='text' class='textbox' name='FbeClr[]' value=''  style='width:95%;'/>";
        cell6.innerHTML = "<input type='text' class='textbox' name='Temperature[]' value=''  style='width:95%;'/>";
        cell7.innerHTML = "<input type='text' class='textbox' name='McBeltSpeed[]' value=''  style='width:95%;'/>";
        cell8.innerHTML = "<input type='text' class='textbox' name='Pressure[]' value=''  style='width:95%;'/>";
        cell9.innerHTML = "<input type='text' class='textbox' name='GrainLine[]' value=''  style='width:95%;'/>";

        i++;
        document.getElementById("CountRows").value = i;
    }

    function DeleteRow() {
        var table = document.getElementById("FusingInterTable");
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
