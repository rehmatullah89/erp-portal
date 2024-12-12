
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

	if (!objFV.validate("Auditor", "B", "Please select the Auditor."))
		return false;
		
	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;
		
	if (!objFV.validate("Report", "B", "Please select the Report Type."))
		return false;
		
	if (!objFV.validate("AuditStage", "B", "Please select the Audit Stage."))
		return false;
		
	if (!objFV.validate("AuditDate", "B", "Please select the Audit Date."))
		return false;
		
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
		
	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;
		
	if (!objFV.validate("StyleNo", "B", "Please select the Style No."))
		return false;
		
	return true;
}