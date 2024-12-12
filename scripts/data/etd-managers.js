
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

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Manager", "B", "Please select the ETD Manager."))
		return false;

	if ($('Vendors').selectedIndex == -1)
	{
		alert("Please select at-least One Vendor.");

		return false;
	}

	if ($('Brands').selectedIndex == -1)
	{
		alert("Please select at-least One Brand.");

		return false;
	}

	/*if ($('Categories').selectedIndex == -1)
	{
		alert("Please select at-least One Category.");

		return false;
	}*/

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if ($('Vendors' + iId).selectedIndex == -1)
	{
		alert("Please select at-least One Vendor.");

		return false;
	}

	if ($('Brands' + iId).selectedIndex == -1)
	{
		alert("Please select at-least One Brand.");

		return false;
	}

	/*if ($('Categories' + iId).selectedIndex == -1)
	{
		alert("Please select at-least One Category.");

		return false;
	}*/


	$('Processing').show( );

	var sUrl    = "ajax/data/update-etd-manager.php";
	var sParams = $('frmData' + iId).serialize( );

	var objForm = $("frmData" + iId);
	objForm.disable( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_updateData });
}

function _updateData(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		var iId     = sParams[1];

		if (sParams[0] == "OK")
		{
			$('Msg' + iId).innerHTML = sParams[2];
			$('Msg' + iId).show( );
			$('Edit' + iId).hide( );

			setTimeout(
						function( )
						{
							new Effect.SlideUp("Msg" + iId);

							$('Vendors_' + iId).innerHTML    = sParams[3];
							$('Brands_' + iId).innerHTML     = sParams[4];
							$('Categories_' + iId).innerHTML = sParams[5];
						},

						2000
				  );
		}

		else if (sParams[0] == "INFO")
			_showError(sParams[2]);

		else
			_showError(sParams[1]);

		$('Processing').hide( );

		var objForm = $("frmData" + iId);
		objForm.enable( );
	}

	else
		_showError( );
}