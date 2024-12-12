
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


function getSeasons( )
{
	for (var i = ($("Season").options.length - 1); i >= 0; i --)
		$("Season").options[i] = null;


	var sBrands = "0";

	for (var i = 0; i < $("Brand").options.length; i ++)
	{
		if ($("Brand").options[i].selected == true)
			sBrands += ("," + $("Brand").options[i].value);
	}


	if (sBrands == "0")
		return;

	$("Season").disable( );

	var sUrl    = "ajax/get-brand-seasons.php";
	var sParams = ("Id=" + sBrands + "&List=Season");

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getSeasons });
}


function _getSeasons(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			if (sParams[1] != "")
			{
				for (var i = 1; i < sParams.length; i ++)
				{
					var sOption = sParams[i].split("||");

					$("Season").options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);
				}
			}

			$("Season").enable( );
		}

		else if (sParams[1] != "")
			_showError(sParams[1]);
	}

	else
		_showError( );
}


function getCustomers( )
{
	for (var i = ($("Customer").options.length - 1); i >= 0; i --)
		$("Customer").options[i] = null;


	var sBrands = "0";

	for (var i = 0; i < $("Brand").options.length; i ++)
	{
		if ($("Brand").options[i].selected == true)
			sBrands += ("," + $("Brand").options[i].value);
	}


	if (sBrands == "0")
		return;

	$("Customer").disable( );

	var sUrl    = "ajax/get-brand-customers.php";
	var sParams = ("Id=" + sBrands + "&List=Customer");

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getCustomers });
}


function _getCustomers(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			if (sParams[1] != "")
			{
				for (var i = 1; i < sParams.length; i ++)
				{
					var sOption = sParams[i].split("||");

					$("Customer").options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);
				}
			}

			$("Customer").enable( );
		}

		else if (sParams[1] != "")
			_showError(sParams[1]);
	}

	else
		_showError( );
}