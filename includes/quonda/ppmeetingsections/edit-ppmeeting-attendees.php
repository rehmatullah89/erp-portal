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
    
        <h3>Add New Meeting Attendee</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="AttendeesTable">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="200"><b>Name</b></td>
                  <td><b>Designation of Attendee</b></td>
            </tr>

            <tr>
                <td>1</td>
                <td><input type="text" name="Name[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="Designation[]" value="" class="textbox" size="20" style='width:95%;'></td>
            </tr>

        </table>
        <input type="hidden" name="Id" value="<?=$Id?>">
        <input type="hidden" name="SectionId" value="<?=$SectionId?>">
        <input type="hidden" name="CountRows" id="CountRows" value="1">
        <input type="submit" value="Submit" style="margin: 5px;">
        <a id="BtnAddRow" onclick="AddAttendee()">Add Attendee [+]</a> / <a id="BtnDelRow" onclick="DeleteAttendee()">Remove Attendee [-]</a>

</div>
<br/><br/>
<?
    }
    else{
?>
<a href="quonda/view-qa-report.php?Id=<?= $Id ?>" style="font-weight: bold; padding: 5px;"><img style="width:20px; line-height: 15px; margin-bottom: -5px;" src="images/icons/back.png">Back</a>
<?
    }
?>
<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
        <h3>List of Existing Meeting Attendees</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="200"><b>Name</b></td>
                  <td><b>Designation of Attendee</b></td>
            </tr>
<?
            $ppmeetingAttendees = getList("tbl_ppmeeting_attendees", "name", "designation", "audit_id='$Id'");
            
            $j=1;
            if(count($ppmeetingAttendees) > 0)
            {
                foreach($ppmeetingAttendees as $sAttendee => $sDesignation)
                {
?>
                    <tr class="evenRow"><td><?= $j++?></td><td><?=$sAttendee?></td><td><?=$sDesignation?></td></tr>
<?
                }
            }
            else
            {
?>
            <tr><td colspan="3" align="center" style="color:lightgray; font-size: 24px;"> No Attendee Exists!</td></tr>
<?
            }
?>
        </table>
    </form>    
    
</div>
<script type="text/javascript">
	    <!--

    var i=2;
    function AddAttendee() {
        var table = document.getElementById("AttendeesTable");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);

        cell1.innerHTML = i;
        cell2.innerHTML = "<input type='text' class='textbox' name='Name[]' value=''  style='width:95%;'/>";
        cell3.innerHTML = "<input type='text' class='textbox' name='Designation[]' value=''  style='width:95%;'/>";
        i++;
        document.getElementById("CountRows").value = i;
    }

    function DeleteAttendee() {
        var table = document.getElementById("AttendeesTable");
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
