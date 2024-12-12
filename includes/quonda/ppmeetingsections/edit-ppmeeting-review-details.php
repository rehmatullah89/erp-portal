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

$sSQL = "SELECT * FROM tbl_ppmeeting_review_details WHERE audit_id='$Id'";
$objDb->query($sSQL);

$bulkfabric         = $objDb->getField(0, 'bulk_fabric');
$bulktrim           = $objDb->getField(0, 'bulk_trim');
$producfactory      = $objDb->getField(0, 'production_factory');
$sampleroom         = $objDb->getField(0, 'sample_room');
$approvalComments   = explode('|-|', $objDb->getField(0, 'approval_comments'));
$fitRevision        = $objDb->getField(0, 'fit_revision');

if($Edit != 'Y')
{
?>
    <a href="quonda/view-qa-report.php?Id=<?= $Id ?>" style="font-weight: bold; padding: 5px;"><img style="width:20px; line-height: 15px; margin-bottom: -5px;" src="images/icons/back.png">Back</a>
<?
}
?>

<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="quonda/update-ppmeeting-section.php">
    
        <h3>a) Factory PPS Approval Comments</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
            <tr>
                <td>PPS made from bulk fabric?</td>
                <td><input type="checkbox" name="bulkFabric" value="Y" <?=($bulkfabric == 'Y'?'checked':'')?>></td>
                
                <td>PPS made with bulk trim?</td>
                <td><input type="checkbox" name="bulkTrim" value="Y" <?=($bulktrim == 'Y'?'checked':'')?>></td>
            </tr>
            
            <tr>
                <td>PPS made in production factory?</td>
                <td><input type="checkbox" name="producFactory" value="Y" <?=($producfactory == 'Y'?'checked':'')?>></td>
                
                <td>PPS produce from sample room?</td>
                <td><input type="checkbox" name="sampleRoom" value="Y" <?=($sampleroom == 'Y'?'checked':'')?>></td>
            </tr>
        </table>
        <br/><br/>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">
        
        <h3>b) PPS Approval Review Comments</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="ApprovalReviewTable">
<?
            if(!empty($approvalComments))
            {
                $k =1;
                foreach($approvalComments as $sApComment)
                {
?>
                <tr>
                    <td width="20"><?=$k++?></td>
                    <td><input type="text" name="approvalComments[]" value="<?=$sApComment?>" class="textbox" size="20" style='width:95%;'></td>                    
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
                <td><input type="text" name="approvalComments[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <input type="hidden" name="CountRows" id="CountRows" value="2">  
            </tr>
<?
            }
?>
            
        </table>
        <a id="BtnAddRow" onclick="AddApComment()">Add Comment [+]</a> / <a id="BtnDelRow" onclick="DeleteApComment()">Remove Comment [-]</a>
        
        <br/><br/><br/>
        <h3>c) Fit Revision</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr>
                <td><textarea name="fitRevision" cols="100" rows="10" style='width:95%;'><?=$fitRevision?></textarea></td>
            </tr>
        </table>
        
        <br/><br/>
        <h3>d) Sample Photographs (Front/Back)</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
            <tr>
                <td with="50%"><input type="file" name="frontImage" value=""><br/>
                <?
                                            $sfrontPicture = $objDb->getField(0, 'front_picture');

                                            if (!empty($sfrontPicture) && @file_exists($sBaseDir.QUONDA_PICS_DIR."ppmeeting/".$sfrontPicture))                        
                                            {
?>
                                                <span>&bull; (<a href="<?= SITE_URL.QUONDA_PICS_DIR."ppmeeting/".$sfrontPicture;?>" class="lightview"><?= $sfrontPicture ?></a>)&nbsp;</span>
<?
                                            }
		
?>
                </td>
                
                
                <td with="50%"><input type="file" name="backImage" value=""><br/>
                                <?
                                            $sbackPicture = $objDb->getField(0, 'back_picture');

                                            if (!empty($sbackPicture) && @file_exists($sBaseDir.QUONDA_PICS_DIR."ppmeeting/".$sbackPicture))                        
                                            {
?>
                                                <span>&bull; (<a href="<?= SITE_URL.QUONDA_PICS_DIR."ppmeeting/".$sbackPicture;?>" class="lightview"><?= $sbackPicture ?></a>)&nbsp;</span>
<?
                                            }
		
?>
                </td>
            </tr>
        </table>
        
        <br/><br/><hr>
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
        var table = document.getElementById("ApprovalReviewTable");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);

        cell1.innerHTML = i;
        cell2.innerHTML = "<input type='text' class='textbox' name='approvalComments[]' value=''  style='width:95%;'/>";
        i++;
        document.getElementById("CountRows").value = i;
    }

    function DeleteApComment() {
        var table = document.getElementById("ApprovalReviewTable");
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
