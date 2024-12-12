
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

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Type", "B", "Please select the Delay Type."))
		return false;		
		
	if (!objFV.validate("Reason", "B", "Please select the Delay Reason."))
		return false;		

	return true;
}

function getReasons(sType)
{
	clearList($('Reason'));

	if (sType == "")
		return;
	
	var sUrl    = "ajax/get-delay-reasons.php"; 
	var sParams = ("Type=" + sType);
	
	$('Processing').show( );
	$('frmData').disable( );
	
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getReasons });
}

function _getReasons(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		
		if (sParams[0] == "OK")
		{
			for (var i = 1; i < sParams.length; i ++)
			{			
				var sOption = sParams[i].split("||");
			
				$('Reason').options[i] = new Option(sOption[1], sOption[0], false, false);

			}
		}
			
		else
			_showError(sParams[1]);
			
		$('Processing').hide( );
		$("frmData").enable( );
	}
	
	else
		_showError( );
}