
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

	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

	if (!objFV.validate("Type", "B", "Please select the Loom Type."))
		return false;

	if (!objFV.validate("Loom", "B", "Please enter the Loom Title."))
		return false;

	if (!objFV.validate("Efficiency", "B,N", "Please enter the Loom Efficiency."))
		return false;

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

	if (!objFV.validate("Type", "B", "Please select the Loom Type."))
		return false;

	if (!objFV.validate("Loom", "B", "Please enter the Loom Title."))
		return false;

	if (!objFV.validate("Efficiency", "B,N", "Please enter the Loom Efficiency."))
		return false;


	$('Processing').show( );

	var sUrl    = "ajax/yarn/update-loom.php";
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

					$('Loom' + iId).innerHTML       = sParams[3];
					$('Type' + iId).innerHTML       = sParams[4];
					$('Vendor' + iId).innerHTML     = sParams[5];
					$('Efficiency' + iId).innerHTML = sParams[6];
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