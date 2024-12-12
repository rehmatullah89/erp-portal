
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

function getStylesList(sParent, sList)
{
	clearList($(sList));
	
	var iBrandId = $F(sParent);

	if (iBrandId == "")
		return;

	$(sList).disable( );
	

	var sUrl    = "ajax/sampling/get-styles.php";
	var sParams = ("Brand=" + iBrandId + "&List=" + sList);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getStylesList });
}


function _getStylesList(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		
		if (sParams[0] == "OK")
		{
			var sList = sParams[1];

			for (var i = 2; i < sParams.length; i ++)
			{			
				var sOption = sParams[i].split("||");
			
				$(sList).options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);
			}

			$(sList).enable( );
		}
			
		else
			_showError(sParams[1]);
	}
	
	else
		_showError( );
}


function validateForm( )
{
	var objFV = new FormValidator("frmData");
	
	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;
		
	if (!objFV.validate("StyleNo", "B", "Please select the Style No."))
		return false;

	if (!objFV.validate("SampleType", "B", "Please select the Sample Type."))
		return false;
		
	if (!objFV.validate("Wash", "B", "Please select the Color/Wash."))
		return false;
		
	if (!objFV.validate("RequiredDate", "B", "Please select the Customer Required Date."))
		return false;

	return true;
}