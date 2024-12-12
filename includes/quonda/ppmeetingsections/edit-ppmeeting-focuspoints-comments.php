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

$sSQL = "SELECT * FROM tbl_ppmeeting_foucspoints_comments WHERE audit_id='$Id'";
$objDb->query($sSQL);

$fitSample         = $objDb->getField(0, 'fit_sample_approved');
$fitSampleComments = $objDb->getField(0, 'fit_sample_comments');
$ppSample          = $objDb->getField(0, 'pp_sample_approved');
$ppSampleComments  = $objDb->getField(0, 'pp_sample_comments');
$sizeSet           = $objDb->getField(0, 'size_set_approved');
$sizeSetComments   = $objDb->getField(0, 'size_set_comments');
$patternCorrection = $objDb->getField(0, 'pattern_correction');
$patternComments   = $objDb->getField(0, 'pattern_comments');
$techPack          = $objDb->getField(0, 'tech_pack');
$techPackComments  = $objDb->getField(0, 'techpack_comments');
$Comments          = $objDb->getField(0, 'comments');
$ProducKeyPoints   = explode('|-|', $objDb->getField(0, 'production_key_points'));


if($Edit != 'Y')
{
?>
    <a href="quonda/view-qa-report.php?Id=<?= $Id ?>" style="font-weight: bold; padding: 5px;"><img style="width:20px; line-height: 15px; margin-bottom: -5px;" src="images/icons/back.png">Back</a>
<?
}
?>
<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="quonda/update-ppmeeting-section.php">

        <h3>a) Production Key Focus Points</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="productionKeyPointTable">
<?
            if(!empty($ProducKeyPoints))
            {
                $k =1;
                foreach($ProducKeyPoints as $sProducKeyPoint)
                {
?>
                <tr>
                    <td width="20"><?=$k++?></td>
                    <td><input type="text" name="producKeyPoint[]" value="<?=$sProducKeyPoint?>" class="textbox" size="20" style='width:95%;'></td>                    
                </tr>
<?
                }
?>
                <input type="hidden" name="CountRows" id="CountRows" value="<?=$k?>">  
<?
            }
            else
            {
?>
            <tr>
                <td width="20">1</td>
                <td><input type="text" name="producKeyPoint[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <input type="hidden" name="CountRows" id="CountRows" value="2">  
            </tr>
<?
            }
?>
            
        </table>
        <a id="BtnAddRow" onclick="AddApComment()">Add Comment [+]</a> / <a id="BtnDelRow" onclick="DeleteApComment()">Remove Comment [-]</a>
        
        <br/><br/><br/>
                
        <h3>b) Sample Approval / Comments Update</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
            <tr>
                <td width="200">Fit Sample Approved</td>
                <td width="50"><input type="checkbox" name="fitSample" value="Y" <?=($fitSample == 'Y'?'checked':'')?>></td>
                <td><input type="text" name="fitSampleComments" value="<?=$fitSampleComments?>" class="textbox" size="20" style='width:93%;'></td>
            </tr>
            
            <tr>
                <td>Pre-Production Sample Approved</td>
                <td><input type="checkbox" name="ppSample" value="Y" <?=($ppSample == 'Y'?'checked':'')?>></td>
                <td><input type="text" name="ppSampleComments" value="<?=$ppSampleComments?>" class="textbox" size="20" style='width:93%;'></td>
            </tr>
            
            <tr>
                <td>Size Set Approved</td>
                <td><input type="checkbox" name="sizeSet" value="Y" <?=($sizeSet == 'Y'?'checked':'')?>></td>
                <td><input type="text" name="sizeSetComments" value="<?=$sizeSetComments?>" class="textbox" size="20" style='width:93%;'></td>
            </tr>
            
            <tr>
                <td>Any Pattern Correction</td>
                <td><input type="checkbox" name="patternCorrection" value="Y" <?=($patternCorrection == 'Y'?'checked':'')?>></td>
                <td><input type="text" name="patternComments" value="<?=$patternComments?>" class="textbox" size="20" style='width:93%;'></td>
            </tr>
            
            <tr>
                <td>Tech Pack Attached?</td>
                <td><input type="checkbox" name="techPack" value="Y" <?=($techPack == 'Y'?'checked':'')?>></td>
                <td><input type="text" name="techPackComments" value="<?=$techPackComments?>" class="textbox" size="20" style='width:93%;'></td>
            </tr>
            
            <tr>
                <td>Comments</td>
                <td colspan="2"><textarea name="comments" cols="50" rows="10" style='width:94%;'><?=$Comments?></textarea></td>
            </tr>

        </table>
        <br/><br/>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">
        
        <br/>
<?
        if($Edit == 'Y')
        {
?>
        <input type="submit" value="Submit" style="margin: 5px;">
<?
        }
?>
    </form>
</div>

<script type="text/javascript">
	    <!--

    var i=document.getElementById("CountRows").value;
    function AddApComment() {
        var table = document.getElementById("productionKeyPointTable");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);

        cell1.innerHTML = i;
        cell2.innerHTML = "<input type='text' class='textbox' name='producKeyPoint[]' value=''  style='width:95%;'/>";
        i++;
        document.getElementById("CountRows").value = i;
    }

    function DeleteApComment() {
        var table = document.getElementById("productionKeyPointTable");
        var rowCount = table.rows.length;
        
        if(rowCount > 1)
        {
            table.deleteRow(rowCount-1);
            i--;
            document.getElementById("CountRows").value = i;
        }
    }
    -->
</script> 
