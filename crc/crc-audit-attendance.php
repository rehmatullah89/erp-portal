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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id         = IO::intValue('Id');
        $Completed  = IO::strValue('Completed');

        $sSQL = "SELECT * FROM tbl_crc_attendance Where audit_id = '$Id'";
	$objDb->query($sSQL);

        $OmDate         = $objDb->getField($i, 'opening_date');
        $sStartingTime  = $objDb->getField($i, 'opening_time');
        $CmDate         = $objDb->getField($i, 'closing_date');
        $sClosingTime   = $objDb->getField($i, 'closing_time');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
    <style>
	.evenRow {
		background: #f6f4f5 none repeat scroll 0 0;
	}
	.oddRow {
		background: #dcdcdc none repeat scroll 0 0;
	}
	</style> 
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">
      <div id="RecordMsg" class="hidden" style="width:100%; <?=($_SESSION["Flag1122"] != "")?'background-color:#FFFACD;':'';?>"><?=($_SESSION["Flag1122"] != ""?"<span style='color:black; background-color:#FFFACD; font-size:14px; font-weight:bold;'>Attendees Updated Successfully!</span>":"")?></div>
    <form name="frmData1" id="frmData1" method="post" action="crc/update-crc-audit-attendance.php" class="frmOutline">
        <input type="hidden" name="AuditId" value="<?=$Id?>"/>
<!--  Body Section Starts Here  -->
	<div id="Body">
         <table border="0" cellpadding="2" cellspacing="2" width="100%">
             <tr><td width="48%"  valign="top">
	  <h2>Opening Meeting Attendance Sheet</h2>
<?
    if($Completed != 'Y')
    {
        print "<fieldset disabled>";
    }
?>
            <h3><table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                          <td width="40">Date</td>
                          <td width="78"><input type="text" name="OmDate" value="<?= $OmDate ?>" id="OmDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('OmDate'), 'yyyy-mm-dd', this);" /></td>
                          <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('OmDate'), 'yyyy-mm-dd', this);" /></td>
                          <td style="text-align:right;">Starting Time &nbsp;</td><td><input type="text" name="StartingTime1" size="5" value="<?=$sStartingTime?>"/></td>
                </tr>
            </table></h3><br/>
	  <table id="AttendeesTable1" border="0" cellpadding="2" cellspacing="2" width="100%" style="padding-bottom:10px;">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="200"><b>Name</b></td>
                  <td><b>Designation of Attendee</b></td>
            </tr>
<?
                $OMAttendeesList = getList("tbl_crc_attendance_details", "attendee", "designation", "audit_id='$Id' AND meeting_type= 'O'");
                $i =1;
                if(count($OMAttendeesList) > 0)
                {
                    foreach ($OMAttendeesList as $sAttendee => $sDesignation)
                    {
?>
                        <tr>
                            <td><?=$i++?></td>
                            <td><input type="text" name="OMName[]" value="<?=$sAttendee?>" class="textbox" size="20" style='width:95%;'></td>
                            <td><input type="text" name="OMDesignation[]" value="<?=$sDesignation?>" class="textbox" size="20" style='width:95%;'></td>
                        </tr>
<?
                    }
                }else{
?>
            <tr>
                <td>1</td>
                <td><input type="text" name="OMName[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="OMDesignation[]" value="" class="textbox" size="20" style='width:95%;'></td>
            </tr>
<?
                }
?>
        </table>
<?
            if($Completed == 'Y')
            {
?>
        <a id="BtnAddRow" onclick="AddAttendee('AttendeesTable1')">Add Attendee [+]</a> / <a id="BtnDelRow" onclick="DeleteAttendee('AttendeesTable1')">Remove Attendee [-]</a>
<?
            }
?>
        </td><td valign="top">
            <h2>Closing Meeting Attendance Sheet</h2>
             <h3><table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                          <td width="40">Date</td>
                          <td width="78"><input type="text" name="CmDate" value="<?= $CmDate ?>" id="CmDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('CmDate'), 'yyyy-mm-dd', this);" /></td>
                          <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('CmDate'), 'yyyy-mm-dd', this);" /></td>
                          <td style="text-align:right;">Starting Time &nbsp;</td><td><input type="text" name="StartingTime2" size="5" value="<?=$sClosingTime?>"/></td>
                </tr>
                 </table></h3><br/>      
	  <table id="AttendeesTable2" border="0" cellpadding="2" cellspacing="2" width="100%" style="padding-bottom:10px;">
            <tr class="sdRowHeader">
                  <td width="20"><b>#</b></td>
                  <td width="200"><b>Name</b></td>
                  <td><b>Designation of Attendee</b></td>
            </tr>
<?
                $CMAttendeesList = getList("tbl_crc_attendance_details", "attendee", "designation", "audit_id='$Id' AND meeting_type= 'C'");
                $i =1;
                if(count($CMAttendeesList) > 0)
                {
                    foreach ($CMAttendeesList as $sAttendee => $sDesignation)
                    {
?>
                        <tr>
                            <td><?=$i++?></td>
                            <td><input type="text" name="CMName[]" value="<?=$sAttendee?>" class="textbox" size="20" style='width:95%;'></td>
                            <td><input type="text" name="CMDesignation[]" value="<?=$sDesignation?>" class="textbox" size="20" style='width:95%;'></td>
                        </tr>
<?
                    }
                }else{
?>
            <tr>
                <td>1</td>
                <td><input type="text" name="CMName[]" value="" class="textbox" size="20" style='width:95%;'></td>
                <td><input type="text" name="CMDesignation[]" value="" class="textbox" size="20" style='width:95%;'></td>
            </tr>
<?
                }
?>
        </table>
<?
            if($Completed == 'Y')
            {
?>
        <a id="BtnAddRow" onclick="AddAttendee('AttendeesTable2')">Add Attendee [+]</a> / <a id="BtnDelRow" onclick="DeleteAttendee('AttendeesTable2')">Remove Attendee [-]</a>
<?
            }
?>
        </td></tr>
       </table><br/>      
<?
    if($Completed != 'Y')
    {
        print "</fieldset>";
    }
    else
    {
?>
          <input type="submit" value="Submit" style="margin: 10px; float: right;"/>
<?
    }
?>      
        <br/><br/>
</div>
    </form>    
    
</div>
</div>
<script type="text/javascript">
	    <!--

     function alertMsg() {
        document.getElementById("RecordMsg").innerHTML = "";
        <?$_SESSION["Flag1122"] = "";?>
     }
     setTimeout(alertMsg,3000);    
     
    function AddAttendee(TableName) {
        var table = document.getElementById(TableName);
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);

        cell1.innerHTML = rowCount;
        
        if(TableName == 'AttendeesTable1')
        {
            cell2.innerHTML = "<input type='text' class='textbox' name='OMName[]' value=''  style='width:95%;'/>";
            cell3.innerHTML = "<input type='text' class='textbox' name='OMDesignation[]' value=''  style='width:95%;'/>";
        }
        else {
            cell2.innerHTML = "<input type='text' class='textbox' name='CMName[]' value=''  style='width:95%;'/>";
            cell3.innerHTML = "<input type='text' class='textbox' name='CMDesignation[]' value=''  style='width:95%;'/>";
        }
    }

    function DeleteAttendee(TableName) {
        var table = document.getElementById(TableName);
        var rowCount = table.rows.length;
        
        if(rowCount > 2)
        {
            table.deleteRow(rowCount-1);
        }
    }
    -->
</script> 
</body>
</html>    