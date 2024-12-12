
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

	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;

	if (!objFV.validate("Type", "B", "Please enter the Sampling Type."))
		return false;

	if (!objFV.validate("Color", "B", "Please select the Color."))
		return false;

	if (!objFV.validate("Priority", "B", "Please select the Priority."))
		return false;

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;

	if (!objFV.validate("Type", "B", "Please enter the Sampling Type."))
		return false;

	if (!objFV.validate("Color", "B", "Please select the Color."))
		return false;

	if (!objFV.validate("Priority", "B", "Please select the Priority."))
		return false;


	$('Processing').show( );

	var sUrl    = "ajax/sampling/update-type.php";
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

					$('Type' + iId).innerHTML         = sParams[3];
					$('Brand' + iId).innerHTML        = sParams[4];
					$('Final' + iId).innerHTML        = sParams[5];
					$('Color' + iId).style.background = sParams[6];
					$('Priority' + iId).innerHTML     = sParams[7];
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