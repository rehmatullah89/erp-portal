
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

var objRms;

function getRms(iBrandId, sRmsId)
{
	objRms = $(sRmsId);

	clearList(objRms);

	if (iBrandId == "")
		return;
	
	var sUrl    = "ajax/sampling/get-rms-list.php"; 
	var sParams = ("BrandId=" + iBrandId);
	
	$('Processing').show( );
	$('frmData').disable( );
	
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getRms });
}

function _getRms(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		
		if (sParams[0] == "OK")
		{
			for (var i = 1; i < sParams.length; i ++)
			{
				var sOption = sParams[i].split("||");

				objRms.options[i] = new Option(sOption[1], sOption[0], false, false);
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

function validateForm( )
{
	var objFV = new FormValidator("frmData");
	
	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;
		
	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;
		
	if (!objFV.validate("Rms", "B", "Please select the Brand RMS."))
		return false;
		
	if (!objFV.validate("Season", "B", "Please select the Season."))
		return false;
		
	if (!objFV.validate("Color", "B", "Please select the Color."))
		return false;
		
	if (!objFV.validate("Status", "B", "Please select the Lap Dip Status."))
		return false;

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);
	
	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;
		
	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;
		
	if (!objFV.validate("Rms", "B", "Please select the Brand RMS."))
		return false;
		
	if (!objFV.validate("Season", "B", "Please select the Season."))
		return false;
		
	if (!objFV.validate("Color", "B", "Please select the Color."))
		return false;
		
	if (!objFV.validate("Status", "B", "Please select the Lap Dip Status."))
		return false;

	return true;
}