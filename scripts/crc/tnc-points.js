
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
**  Software Engineer:                                                                         **
**                                                                                           **
**      Name  :  Rehmat Ullah			                                                     **
**      Email :  rehmatullah@3-tree.com		                                                 **
**      Phone :  +92 344 404 3675                                                            **
**      URL   :  http://www.apparelco.com                                                    **
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

	if (!objFV.validate("Section", "B", "Please enter the Section."))
		return false;

	if (!objFV.validate("Category", "B", "Please enter the Category."))
		return false;

	if (!objFV.validate("Point", "B", "Please enter the Point."))
		return false;
        
        if (!objFV.validate("PointNo", "B", "Please enter the Point Number."))
		return false;

	return true;
}

function getCategories(iId, sChild)
{
	if (iId == "")
		return;
        
        var sUrl    = "ajax/crc/get-tnc-categories-list.php";
	var sParams = ("Id=" + iId + "&List=" + sChild);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getResponseList });
}


function _getResponseList(sResponse)
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

				$(sChild).options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);

			}

			$(sChild).enable( );
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}


function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Section", "B", "Please enter the Section."))
		return false;

	if (!objFV.validate("Category", "B", "Please enter the Category."))
		return false;

	if (!objFV.validate("Point", "B", "Please enter the Point."))
		return false;

        if (!objFV.validate("PointNo", "B", "Please enter the Point Number."))
		return false;

	$('Processing').show( );

	var sUrl    = "ajax/crc/update-tnc-point.php";
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
                                            $('Point' + iId).innerHTML    = sParams[3];
                                            $('Section' + iId).innerHTML  = sParams[4];
                                            $('DCategory' + iId).innerHTML = sParams[5];
                                            $('Nature' + iId).innerHTML   = sParams[6];
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