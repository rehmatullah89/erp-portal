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
$sSQL = "SELECT * FROM tbl_ppmeeting_machine_layout WHERE audit_id='$Id'";
$objDb->query($sSQL);

$sLinesAllocated        = $objDb->getField(0, "lines_allocated");
$sMachineLine           = $objDb->getField(0, "machine_line");
$sTargetPerHourDay      = $objDb->getField(0, "target_per_hourday");
$sCuttingDate           = $objDb->getField(0, "cutting_date");
$sSewingDate            = $objDb->getField(0, "sewing_date");
$sFinishingDate         = $objDb->getField(0, "finishing_date");
$sRemarksBeading        = $objDb->getField(0, "remarks_beading");

$sOperationApplication  = explode("|-|", $objDb->getField(0, "operation_application"));
$sFolderAttachment      = explode("|-|", $objDb->getField(0, "folder_attachment"));
$sMachineType           = explode("|-|", $objDb->getField(0, "machine_type"));
$sTotalLinesAllocated   = explode("|-|", $objDb->getField(0, "total_lines_allocated"));
$sAttachmentRequired    = explode("|-|", $objDb->getField(0, "attchments_required"));
$sSpecialManual         = explode("|-|", $objDb->getField(0, "special_manual"));

$sPilotDate             = $objDb->getField(0, "pilot_date");
$sWashReviewDate        = $objDb->getField(0, "wash_review_date");
$sOutputReviewDate      = $objDb->getField(0, "output_review_date");
$sBulkReviewDate        = $objDb->getField(0, "bulk_review_date");
$sPrintReviewDate       = $objDb->getField(0, "print_review_date");
$sColorsReviewDate      = $objDb->getField(0, "colors_review_date");
$sIroningReviewDate     = $objDb->getField(0, "ironing_review_date");

$sRiskPoints            = explode("|-|", $objDb->getField(0, "risk_points"));
$sActionPlan            = explode("|-|", $objDb->getField(0, "action_plans"));
$sOwners                = explode("|-|", $objDb->getField(0, "owners"));
$sDates                 = explode("|-|", $objDb->getField(0, "dates"));
$sExecutive             = $objDb->getField(0, "executive");

if($Edit != 'Y')
{
?>
    <a href="quonda/view-qa-report.php?Id=<?= $Id ?>" style="font-weight: bold; padding: 5px;"><img style="width:20px; line-height: 15px; margin-bottom: -5px;" src="images/icons/back.png">Back</a>
<?
}
?>
<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="quonda/update-ppmeeting-section.php" class="frmOutline">
    
        <h3>a) Machine Layout & Target for Production per Hour/ Day</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="AttendeesTable">
            <tr class="sdRowHeader">
                  <td><b># of Lines Allocated</b></td>
                  <td><b># Of Machine / per Line</b></td>
                  <td><b>Target per Hour/ Per Day</b></td>
                  <td><b>Cutting Start Date</b></td>
                  <td><b>Sewing Start Date</b></td>
                  <td><b>Ironing Pressing Finish Date</b></td>
                  <td><b>Remarks for Subcon Wash/ Embro/ Beading</b></td>
            </tr>

            <tr>
                <td><input type="text" name="lines_allocated" value="<?=$sLinesAllocated?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="machine_line" value="<?=$sMachineLine?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="target_per_hourday" value="<?=$sTargetPerHourDay?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="cutting_date" value="<?=$sCuttingDate?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="sewing_date" value="<?=$sSewingDate?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="finishing_date" value="<?=$sFinishingDate?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="remarks_beading" value="<?=$sRemarksBeading?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
        </table>

        <br/><br/>
        <h3>b) Special Folders/ Attachment Machinery Requirement</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="AttendeesTable">
            <tr class="sdRowHeader">
                  <td><b>Operation & Application</b></td>
                  <td><b>Folder / Attachment Type</b></td>
                  <td><b>Maching Type</b></td>
                  <td><b>Total # of Lines Allocated</b></td>
                  <td><b>Total Attachments Required</b></td>
                  <td><b>Any Special Manual Turning Ironing or Creasing template Required</b></td>
            </tr>
<?
    for($i=0; $i< 3 ; $i++)
    {
?>
            <tr>
                <td><input type="text" name="operation_application[]" value="<?=$sOperationApplication[$i]?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="folder_attachment[]" value="<?=$sFolderAttachment[$i]?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="machine_type[]" value="<?=$sMachineType[$i]?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="total_lines_allocated[]" value="<?=$sTotalLinesAllocated[$i]?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="attchments_required[]" value="<?=$sAttachmentRequired[$i]?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="special_manual[]" value="<?=$sSpecialManual[$i]?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
<?
    }
?>
        </table>
         <br/><br/>
        <h3>c) Pilot Run/ Mandatory for every style above 3000 pcs or Full Size Run under Production Condition for Order less than 3000pcs</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="AttendeesTable">
            <tr class="evenRow">
                <td width="200">Sewing Pilot run Attachment</td><td width="50">Date:</b></td><td><input type="text" name="pilot_date" value="<?=$sPilotDate?>" class="textbox" size="20" style='width:95%;'></td>
                  <td width="200">Wash 1<sup>st</sup> Output Review</td><td width="50">Date:</b></td><td><input type="text" name="wash_review_date" value="<?=$sWashReviewDate?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
            <tr class="oddRow">
                <td width="200">1<sup>st</sup>Output Review</td><td width="50">Date:</td><td><input type="text" name="output_review_date" value="<?=$sOutputReviewDate?>" class="textbox" size="20" style='width:95%;'></td>
                <td width="200">Wash 1<sup>st</sup> Bulk Wash Handfeel Review</td><td width="50">Date:</td><td><input type="text" name="bulk_review_date" value="<?=$sBulkReviewDate?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
            <tr class="evenRow">
                <td width="200">Any Bulk Print Review</td><td width="50">Date:</td><td><input type="text" name="print_review_date" value="<?=$sPrintReviewDate?>" class="textbox" size="20" style='width:95%;'></td>
                <td width="200">1<sup>st</sup> Bulk Colors Review</td><td width="50">Date:</td><td><input type="text" name="colors_review_date" value="<?=$sColorsReviewDate?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
            <tr class="oddRow">
                <td width="200">Ironing & Pressing STD Review</td><td width="50">Date:</td><td><input type="text" name="ironing_review_date" value="<?=$sIroningReviewDate?>" class="textbox" size="20" style='width:95%;'></td>
                <td colspan="3"></td>
            </tr>
        </table>
        <br/><br/>
         
        <h3>d) Risk Management Points Identified in Meeting with Action Plan & Owner</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="AttendeesTable">
            <tr class="sdRowHeader">
                  <td><b>Point of Risk Identified</b></td>
                  <td><b>Plan of Action / Time Line</b></td>
                  <td><b>Owner</b></td>
                  <td><b>Date</b></td>
            </tr>
<?
    for($i=0; $i< 3 ; $i++)
    {
?>
            <tr>
                <td><input type="text" name="risk_points[]" value="<?=$sRiskPoints[$i]?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="action_plans[]" value="<?=$sActionPlan[$i]?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="owners[]" value="<?=$sOwners[$i]?>" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="dates[]" value="<?=$sDates[$i]?>" class="textbox" size="20" style='width:95%;'></td>
            </tr>
<?
    }
?>
            <tr>
                <td>Executive Higlights (Vendor/Factory)</td><td colspan="3"><input type="text" name="executive" value="<?=$sExecutive?>" class="textbox" size="20" style='width:98%;'></td>
            </tr>   
        </table>
          <br/><br/>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">
<?
        if($Edit == 'Y')
        {
?>
        <input type="submit" value="Submit" style="margin: 5px;">
<?
        }
?>
</div>
<br/><br/>

