
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

function resetLocations(sVisitType)
{
	if (sVisitType == "Visit")
		$('Locations').show( );
		
	else
		$('Locations').hide( );
}

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Date", "B", "Please select the Date."))
		return false;
		
	if (!objFV.validate("TimeOutHr", "B", "Please select the Time-Out (Hour)."))
		return false;
		
	if (!objFV.validate("TimeOutMin", "B", "Please select the Time-Out (Minutes)."))
		return false;
		
	if (!objFV.validate("TimeInHr", "B", "Please select the Time-In (Hour)."))
		return false;
		
	if (!objFV.validate("TimeInMin", "B", "Please select the Time-In (Minutes)."))
		return false;
		
	if (!objFV.validate("Employee", "B", "Please select the Employee."))
		return false;
		
	if (!objFV.validate("VisitType", "B", "Please select the Visit Type."))
		return false;
		
	if (objFV.value("VisitType") == "Visit")
	{
		if (!objFV.validate("Location1", "B", "Please select the Visit Location # 1."))
			return false;
			
		for (var i = 2; i <= 8; i ++)
		{
			if (objFV.value("Location" + (i - 1)) == objFV.value("Location" + i) && objFV.value("Location" + i) != "")
			{
				alert("Visiting Locations cannot be Same. Please select any other Visiting Location.");

				return false;
			}
		}
	}

	return true;
}

function resetVisitLocations(sVisitType, iId)
{
	if (sVisitType == "Visit")
		$('VisitLocations' + iId).show( );
		
	else
		$('VisitLocations' + iId).hide( );
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("TimeOutHr", "B", "Please select the Time-Out (Hour)."))
		return false;
		
	if (!objFV.validate("TimeOutMin", "B", "Please select the Time-Out (Minutes)."))
		return false;
		
	if (!objFV.validate("TimeInHr", "B", "Please select the Time-In (Hour)."))
		return false;
		
	if (!objFV.validate("TimeInMin", "B", "Please select the Time-In (Minutes)."))
		return false;
		
	if (!objFV.validate("VisitType", "B", "Please select the Visit Type."))
		return false;
		
	if (objFV.value("VisitType") == "Visit")
	{
		if (!objFV.validate("Location1", "B", "Please select the Visit Location # 1."))
			return false;
			
		for (var i = 2; i <= 8; i ++)
		{
			if (objFV.value("Location" + (i - 1)) == objFV.value("Location" + i) && objFV.value("Location" + i) != "")
			{
				alert("Visiting Locations cannot be Same. Please select any other Visiting Location.");

				return false;
			}
		}
	}

	$('Processing').show( );
	
	var sUrl    = "ajax/hr/update-visit.php"; 
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

					$('TimeIn' + iId).innerHTML  = sParams[3];
					$('TimeOut' + iId).innerHTML = sParams[4];
					$('Detail' + iId).innerHTML  = sParams[5];
				    },				    
				    
				    2000
				  );
		}
		
		else if (sParams[0] == "INFO")
			_showError(sParams[2]);
			
		else
			_showError(sParams[1]);
			
		$('Processing').hide( );
		
		var objForm = $("frmData" + iId); 
		objForm.enable( );
	}
	
	else
		_showError( );
}