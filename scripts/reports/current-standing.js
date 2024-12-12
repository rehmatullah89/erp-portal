
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

function sendEmail(iPoId, iBrandId)
{
	var sUrl    = "ajax/reports/notify-merchandiser.php"; 
	var sParams = ("PoId=" + iPoId + "&BrandId=" + iBrandId);
	
	$('Processing').show( );
	
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_sendEmail }); 
}


function _sendEmail(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
			alert(sParams[1]);

		else
			_showError(sParams[1]);
			
		$('Processing').hide( );
	}
	
	else
		_showError( );
}


function updateFinalAuditDate(iPoId, sDate)
{
	var sUrl    = "ajax/reports/save-final-audit-date.php"; 
	var sParams = ("PoId=" + iPoId + "&Date=" + sDate);
	
	$('Processing').show( );
	
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_updateFinalAuditDate }); 
}

function _updateFinalAuditDate(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		$('Processing').hide( );
	}
	
	else
		_showError( );
}