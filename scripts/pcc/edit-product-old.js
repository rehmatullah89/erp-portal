
/*********************************************************************************************\
***********************************************************************************************
**                                                                                           **
**  Triple Tree Customer Portal                                                              **
**  Version 2.0                                                                              **
**                                                                                           **
**  http://portal.3-tree.com                                                                 **
**                                                                                           **
**  Copyright 2015 (C) Triple Tree                                                           **
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


	var sUrl    = "ajax/pcc/get-styles.php";
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

	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

	if (!objFV.validate("Gender", "B", "Please select the Gender."))
		return false;
/*
	if (!objFV.validate("Category", "B", "Please select the Fabric."))
		return false;

	if (!objFV.validate("GarmentStyle", "B", "Please select the Garment Style."))
		return false;

	if (!objFV.validate("Fabric", "B", "Please select the Fabric."))
		return false;
*/
	return true;
}

function addPicture( )
{
	var iCount = parseInt($('Count').value);

	if (iCount < 20)
	{
		iCount ++;

		Effect.SlideDown('PictureBox' + iCount);

		$('Count').value = iCount;
	}
}


function deletePicture( )
{
	var iCount = parseInt($('Count').value);
	var iMax   = parseInt($('Max').value);

	if (iCount > iMax)
	{
		Effect.SlideUp('PictureBox' + iCount);

		iCount --;

		$('Count').value = iCount;
	}
}