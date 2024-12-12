
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

function validateForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Auditor", "B", "Please select the Auditor."))
		return false;
		
	if (!objFV.validate("Report", "B", "Please select the Report Type."))
		return false;

//	if (!objFV.validate("Line", "B", "Please select the Vendor Line."))
//		return false;
		
	if (!objFV.validate("AuditDate", "B", "Please select the Audit Date."))
		return false;
/*
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

*/	$('Processing').show( );
	
	var sUrl    = "ajax/quonda/save-audit-schedule.php"; 
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
			
			//new Effect.SlideUp("Schedule" + iId);

			setTimeout( 
				    function( )
				    { 			
					new Effect.SlideUp("Msg" + iId);
				    },				    
				    
				    3000
				  );
		}
			
		else
		{
			_showError(sParams[1]);
			
			var objForm = $("frmData" + iId); 
			objForm.enable( );
		}

		$('Processing').hide( );
	}
	
	else
		_showError( );
}