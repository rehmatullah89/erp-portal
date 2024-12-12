
/*********************************************************************************************\
***********************************************************************************************
**                                                                                           **
**  Triple Tree Customer Portal                                                              **
**  Version 2.0                                                                              **
**                                                                                           **
**  http://portal.3-tree.com                                                                 **
**                                                                                           **
**  Copyright 2015 (C) Triple Tree                                                           **
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

function confirmSchedule(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (objFV.value("Complete") != "Y")
	{
		alert("Please use the Edit Option to provide the Required Information first.");

		if ($("Edit" + iId).style.display == "none")
			new Effect.SlideDown('Edit' + iId);
		
		return false;
	}

	return confirm('Are you SURE, You want to Confirm this Audit Schedule?');;
}


function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Auditor", "B", "Please select the Auditor."))
		return false;
		
	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;
		
	if (!objFV.validate("Report", "B", "Please select the Report Type."))
		return false;

	if (!objFV.validate("Line", "B", "Please select the Vendor Line."))
		return false;
		
	if (!objFV.validate("AuditDate", "B", "Please select the Audit Date."))
		return false;
		
	if (!objFV.validate("StartHour", "B", "Please select the Start Time (Hour)."))
		return false;
		
	if (!objFV.validate("StartMinutes", "B", "Please select the Start Time (Minutes)."))
		return false;
		
	if (!objFV.validate("StartAmPm", "B", "Please select the Start Time (AM/PM)."))
		return false;
		
	if (!objFV.validate("EndHour", "B", "Please select the End Time (Hour)."))
		return false;
		
	if (!objFV.validate("EndMinutes", "B", "Please select the End Time (Minutes)."))
		return false;
		
	if (!objFV.validate("EndAmPm", "B", "Please select the End Time (AM/PM)."))
		return false;

	$('Processing').show( );
	
	var sUrl    = "ajax/quonda/update-audit-schedule.php"; 
	var sParams = $('frmData' + iId).serialize( );
	
	var objForm = $("frmData" + iId); 
	objForm.disable( );
	
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_updateData });
}

function _updateData(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		var iId     = sParams[1];
		
		if (sParams[0] == "OK")
		{
			$('Msg' + iId).innerHTML = sParams[2];
			$('Msg' + iId).show( );
			$('Edit' + iId).hide( );

			setTimeout( 
				    function( )
				    { 			
					new Effect.SlideUp("Msg" + iId);

					$('Auditor' + iId).innerHTML   = sParams[3];
					$('Vendor' + iId).innerHTML    = sParams[4];
					$('Line' + iId).innerHTML      = sParams[5];
					$('Date' + iId).innerHTML      = sParams[6];
					$('StartTime' + iId).innerHTML = sParams[7];
					
					$('Complete' + iId).value = "Y";
				    },				    
				    
				    2000
				  );
		}
			
		else
			_showError(sParams[1]);
			
		$('Processing').hide( );
		
		var objForm = $("frmData" + iId); 
		objForm.enable( );
	}
	
	else
		_showError( );
}