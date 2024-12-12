
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

function getLoomsList(sParent, sList)
{
	clearList($(sList));

	var iVendorId = $F(sParent);

	if (iVendorId == "")
		return;

	$(sList).disable( );


	var sUrl    = "ajax/yarn/get-looms.php";
	var sParams = ("Vendor=" + iVendorId + "&List=" + sList);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getLoomsList });
}


function _getLoomsList(sResponse)
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

				$(sList).options[(i - 2)] = new Option(sOption[1], sOption[0], false, false);
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

	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

	if (!objFV.validate("Po", "B", "Please select the Po."))
		return false;

	if (!objFV.validate("FromDate", "B", "Please select the From Date."))
		return false;

	if (!objFV.validate("ToDate", "B", "Please select the To Date."))
		return false;

	if (!objFV.validate("Looms", "B", "Please select the Available Looms."))
		return false;

	return true;
}


function checkDoubleSubmission( )
{
	$('BtnExport').disabled = true;

	setTimeout( function( ) { $('BtnExport').disabled = false; }, 10000);
}
