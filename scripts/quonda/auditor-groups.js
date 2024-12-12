
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

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Name", "B", "Please enter the Group Name."))
		return false;
		
	if (!objFV.validate("Code", "B", "Please enter the Group Code."))
		return false;		
		
	if ($('Auditors').selectedIndex == -1)
	{
		alert("Please select the Group Members.");

		return false;
	}
	
	var iLength   = $('Auditors').length;
	var iSelected = 0;
	
	for (var i = 0; i < iLength; i ++)
	{
		if ($('Auditors').options[i].selected == true)
			iSelected ++;
	}
	
	if (iSelected < 2)
	{
		alert("Please select atleast 2 Auditors to make a Group.");
		
		return false;
	}

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Name", "B", "Please enter the Group Name."))
		return false;
		
	if (!objFV.validate("Code", "B", "Please enter the Group Code."))
		return false;		
		
	if ($('Auditors' + iId).selectedIndex == -1)
	{
		alert("Please select the Group Members.");

		return false;
	}
	
	var iLength   = $('Auditors' + iId).length;
	var iSelected = 0;
	
	for (var i = 0; i < iLength; i ++)
	{
		if ($('Auditors' + iId).options[i].selected == true)
			iSelected ++;
	}
	
	if (iSelected < 2)
	{
		alert("Please select atleast 2 Auditors to make a Group.");
		
		return false;
	}

		
	$('Processing').show( );
	
	var sUrl    = "ajax/quonda/update-auditor-group.php"; 
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

					$('Name_' + iId).innerHTML     = sParams[3];
					$('Code_' + iId).innerHTML     = sParams[4];
					$('Auditors_' + iId).innerHTML = sParams[5];
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