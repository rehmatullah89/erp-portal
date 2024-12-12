
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

function exportReport( )
{
	$('BtnExport').disabled = true;
	
	document.location = $('ExportUrl').value;
	
	setTimeout( function( ) { $('BtnExport').disabled = false; }, 10000);
}

function validateForm( )
{
	var objFV   = new FormValidator("frmData");
	var sParent = objFV.text("Parent");
	
	if (!objFV.validate("Reason", "B", "Please enter the Reason."))
		return false;

	if (sParent == "" || sParent.indexOf("»") == -1)
	{
		if (!objFV.validate("Code", "B,C,L(1)", "Please enter the Reason Code (1 Alphabet only)."))
			return false;
			
		if (objFV.length("Code") != 1)
		{
			alert("Please enter the Reason Code (1 Alphabet only)");
			
			objFV.focus("Code");
			objFV.select("Code");			
			
			return false;
		}
	}
	
	else
	{
		if (!objFV.validate("Code", "B,L(2)", "Please enter the Reason Code (2 Chars only)."))
			return false;
			
		if (objFV.length("Code") != 2)
		{
			alert("Please enter the Reason Code (1 Alphabet only)");
			
			objFV.focus("Code");
			objFV.select("Code");			
			
			return false;
		}
	}
		
	return true;
}

function validateEditForm(iId)
{
	var objFV   = new FormValidator("frmData" + iId);
	var sParent = objFV.text("Parent");
	
	if (!objFV.validate("Reason", "B", "Please enter the Reason."))
		return false;

	if (sParent == "" || sParent.indexOf("»") == -1)
	{
		if (!objFV.validate("Code", "B,C,L(1)", "Please enter the Reason Code (1 Alphabet only)."))
			return false;
			
		if (objFV.length("Code") != 1)
		{
			alert("Please enter the Reason Code (1 Alphabet only)");
			
			objFV.focus("Code");
			objFV.select("Code");			
			
			return false;
		}
	}
	
	else
	{
		if (!objFV.validate("Code", "B,L(2)", "Please enter the Reason Code (2 Chars only)."))
			return false;
			
		if (objFV.length("Code") != 2)
		{
			alert("Please enter the Reason Code (1 Alphabet only)");
			
			objFV.focus("Code");
			objFV.select("Code");			
			
			return false;
		}
	}

	$('Processing').show( );
		
	var sUrl    = "ajax/data/update-etd-revision-reason.php"; 
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

					$('Code' + iId).innerHTML    = sParams[3];
					$('Reason' + iId).innerHTML  = sParams[4];
					$('Parent' + iId).innerHTML  = sParams[5];
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


function validateEmailForm( )
{
	var objFV = new FormValidator("frmEmail");
	
	if (!objFV.validate("User", "B", "Please select a User to email."))
		return false;
		
	return true;
}