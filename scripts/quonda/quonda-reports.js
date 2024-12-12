
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

function getLines(sVendor, sLine)
{
	var objLines = $(sLine);

	for (var i = (objLines.options.length - 1); i >= 0; i --)
		objLines.options[i] = null;


	var iVendor = $F(sVendor);
	var sUrl    = "ajax/get-vendor-lines.php";
	var sParams = ("Id=" + iVendor + "&List=" + sLine);

	if (iVendor == "")
		return;

	$(sLine).disable( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getLines });
}

function _getLines(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			var sChild = sParams[1];

			for (var i = 2; i < sParams.length; i ++)
			{
				var sOption = sParams[i].split("||");

				$(sChild).options[(i - 2)] = new Option(sOption[1], sOption[0], false, false);

			}

			$(sChild).enable( );
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}