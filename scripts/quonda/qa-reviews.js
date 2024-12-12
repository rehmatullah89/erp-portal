
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

function updateStatus(sAuditCode, iStatus)
{
	var sStatus = "LP";

	if (iStatus == 1)
		sStatus = "PF";

	else if (iStatus == 2)
		sStatus = "LF";


	$('Processing').show( );


	var sUrl = "ajax/quonda/update-audit-status.php";

	new Ajax.Request(sUrl, { method:'post', parameters:("AuditCode=" + sAuditCode + "&Status=" + sStatus), onFailure:_showError, onSuccess:_updateStatus });


	return false;
}


function _updateStatus(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		$('Processing').hide( );


		var sParams = sResponse.responseText.split('|-|');
		var iId     = sParams[1];

		if (sParams[0] == "OK")
		{
			$("Status" + iId).innerHTML     = "";
			$("StatusText" + iId).innerHTML = sParams[2];
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}