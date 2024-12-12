
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

function getDefectCodes( )
{
	for (var i = ($("DefectCode").options.length - 1); i > 0; i --)
		$("DefectCode").options[i] = null;	
	
	var iDefectType = $F("DefectType");
	var iReport     = $F("Report");
	
	if (iDefectType == "")
		return;

	$("DefectCode").disable( );

	var sUrl    = "ajax/quonda/get-defect-codes.php";
	var sParams = ("DefectType=" + iDefectType + "&Report=" + iReport);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getDefectCodes });
}

function _getDefectCodes(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		
		if (sParams[0] == "OK")
		{
			for (var i = 1; i < sParams.length; i ++)
			{			
				var sOption = sParams[i].split("||");
			
				$('DefectCode').options[i] = new Option(sOption[1], sOption[0], false, false);

			}

			$('DefectCode').enable( );
		}
			
		else
			_showError(sParams[1]);
	}
	
	else
		_showError( );
}