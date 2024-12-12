
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

function selectAll(sClass)
{
	var sCheckboxes = $$("input." + sClass);

	sCheckboxes.each(function(objCheckbox)
	{
		objCheckbox.checked = true;
	});
}


function clearAll(sClass)
{
	var sCheckboxes = $$("input." + sClass);

	sCheckboxes.each(function(objCheckbox)
	{
		objCheckbox.checked = false;
	});
}


function getSeasonsList( )
{
	clearList($("Season"));

	var sBrands = "";

	$$("input.brand").each(function(objCheckbox)
	{
		if (objCheckbox.checked == true)
		{
			if (sBrands == "")
				sBrands = objCheckbox.value;

			else
				sBrands += ("," + objCheckbox.value);
		}
	});

	if (sBrands == "")
		return;

	$("Season").disable( );


	var sUrl    = "ajax/pcc/get-seasons.php";
	var sParams = ("Brands=" + sBrands);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getSeasonsList });
}


function _getSeasonsList(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			for (var i = 1; i < sParams.length; i ++)
			{
				var sOption = sParams[i].split("||");

				$("Season").options[i] = new Option(sOption[1], sOption[0], false, false);
			}

			$("Season").enable( );
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}


function validateForm( )
{
	var objFV = new FormValidator("frmComments");

	if (objFV.value("ProductId") == "")
	{
		alert("Invalid Product to make comments.")

		return false;
	}

	if (!objFV.validate("Comments", "B", "Please enter your Comments."))
		return false;

	return true;
}


function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Comments", "B", "Please enter your Comments."))
		return false;

	$('Processing').show( );

	var sUrl    = "ajax/pcc/update-comments.php";
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

					$('Comments' + iId).innerHTML = sParams[3];
				    },

				    2000
				  );
		}

		else
			_showError(sParams[1]);

		$('Processing').hide( );

		var objForm = $("frmData" + iId);
		objForm.enable( );
	}

	else
		_showError( );
}