
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
 
function checkDoubleSubmission( )
{
	$('BtnExport').disabled = true;
	
	setTimeout( function( ) { $('BtnExport').disabled = false; }, 10000);
}


function getEmployeesList( )
{
	clearList($("Employee"));
	
	var iCountryId = $F("Region");

	if (iCountryId == "")
		return;

	$("Employee").disable( );

	var sUrl    = "ajax/get-employees-list.php";
	var sParams = ("Id=" + iCountryId);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getEmployeesList });
}


function _getEmployeesList(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		
		if (sParams[0] == "OK")
		{
			for (var i = 1; i < sParams.length; i ++)
			{			
				var sOption = sParams[i].split("||");
			
				$("Employee").options[i] = new Option(sOption[1], sOption[0], false, false);
			}

			$("Employee").enable( );
		}
			
		else
			_showError(sParams[1]);
	}
	
	else
		_showError( );
}
