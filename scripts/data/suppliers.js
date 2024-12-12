
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

	if (!objFV.validate("Supplier", "B", "Please enter the Supplier."))
		return false;
            
        if (!objFV.validate("Code", "B", "Please enter the Code."))
		return false;    

        if (!objFV.validate("Phone", "B", "Please select the Phone."))
		return false;        
                
	if (!objFV.validate("City", "B", "Please enter the City Name."))
		return false;

	if (!objFV.validate("Country", "B", "Please select the Country."))
		return false;

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Supplier", "B", "Please enter the Supplier."))
		return false;
            
        if (!objFV.validate("Code", "B", "Please enter the Code."))
		return false;    

        if (!objFV.validate("Phone", "B", "Please select the Phone."))
		return false;        
                
	if (!objFV.validate("City", "B", "Please enter the City Name."))
		return false;

	if (!objFV.validate("Country", "B", "Please select the Country."))
		return false;

	$('Processing').show( );

	var sUrl    = "ajax/data/update-supplier.php";
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

					$('Supplier' + iId).innerHTML   = sParams[3];
					$('Code' + iId).innerHTML     = sParams[4];
					$('Phone' + iId).innerHTML = sParams[5];
					$('Country' + iId).innerHTML  = sParams[6];
					$('City' + iId).innerHTML     = sParams[7];
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