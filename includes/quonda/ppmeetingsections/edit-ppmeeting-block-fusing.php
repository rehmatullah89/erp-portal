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
    
        <h3>Add New Component</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="ComponentsTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="200"><b>Components / Parts</b></td>
                  <td><b>Article No./ Supplier Code</b></td>
                  <td><b>Fusable Type</b></td>
            </tr>

            <tr>
                <td>1</td>
                <td><input type="text" name="Component[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="ArticleNo[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="SupplierCode[]" value="" class="textbox" size="20" style='width:95%;'></td>
            </tr>

        </table>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">
        <input type="hidden" name="CountRows" id="CountRows" value="1">
        <input type="submit" value="Submit" style="margin: 5px;">
        
        <a id="BtnAddRow" onclick="AddComponent()">Add Component [+]</a> / <a id="BtnDelRow" onclick="DeleteComponent()">Remove Component [-]</a>

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
        <h3>List of Existing Components</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="200"><b>Components/ Parts</b></td>
                  <td><b>Article No / Supplier Code</td>
                  <td><b>Fusable Type</b></td>
            </tr>
<?
            $sSQL = "SELECT * FROM tbl_ppmeeting_block_fusing WHERE audit_id='$Id'";
            $objDb->query($sSQL);
            
            $iCount = $objDb->getCount();
           
            if($iCount > 0)
            {
                for ($i = 0; $i < $iCount; $i ++)
                {
                    $sComponent     = $objDb->getField($i, "component");
                    $sArticleNo     = $objDb->getField($i, "article_no");
                    $sSupplierCode  = $objDb->getField($i, "supplier_code");
                
?>
                    <tr class="evenRow"><td><?= $i+1?></td><td><?=$sComponent?></td><td><?=$sArticleNo?></td><td><?=$sSupplierCode?></td></tr>
<?
                }
            }
            else
            {
?>
            <tr><td colspan="4" align="center" style="color:lightgray; font-size: 24px;"> No Component Exists!</td></tr>
<?
            }
?>
        </table>
    </form>    
    
</div>
<script type="text/javascript">
	    <!--

    var i=2;
    function AddComponent() {
        var table = document.getElementById("ComponentsTable");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);

        cell1.innerHTML = i;
        cell2.innerHTML = "<input type='text' class='textbox' name='Component[]' value=''  style='width:95%;'/>";
        cell3.innerHTML = "<input type='text' class='textbox' name='ArticleNo[]' value=''  style='width:95%;'/>";
        cell4.innerHTML = "<input type='text' class='textbox' name='SupplierCode[]' value=''  style='width:95%;'/>";
        i++;
        document.getElementById("CountRows").value = i;
    }

    function DeleteComponent() {
        var table = document.getElementById("ComponentsTable");
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
