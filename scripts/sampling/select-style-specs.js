
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

var sPoints;

function getMPs( )
{
	sPoints = new Array( );

	var objList = $('MPs');

	for (var i = (objList.options.length - 1); i >= 0; i --)
		objList.options[i] = null;


	var objFV = new FormValidator("frmData");

	if ($('Brand').value == "")
		return;

	if ($('Category').value == "")
		return;

	var sUrl    = "ajax/sampling/get-mps-list.php";
	var sParams = $('frmData').serialize( );

	$('Processing').show( );
	$('frmData').disable( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getMPs });
}

function _getMPs(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			var objList = $('MPs');

			for (var i = (objList.options.length - 1); i >= 0; i --)
				objList.options[i] = null;


			var objList = $('SelectedMPs');

			for (var i = (objList.options.length - 1); i >= 0; i --)
				objList.options[i] = null;


			for (var i = 1; i < sParams.length; i ++)
			{
				var sOption = sParams[i].split("||");

				$('MPs').options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);

				sPoints[(i - 1)] = new Array(sOption[1], sOption[0]);
			}
		}

		else
			_showError(sParams[1]);

		$('Processing').hide( );
		$("frmData").enable( );
	}

	else
		_showError( );
}

function filter(sValue)
{
	var objMpsList         = $('MPs');
	var objSelectedMpsList = $('SelectedMPs');

	for (var i = (objMpsList.options.length - 1); i >= 0; i --)
		objMpsList.options[i] = null;

	for (var i = 0, j = 0; i < sPoints.length; i ++)
	{
		if (sPoints[i][0].toLowerCase( ).indexOf(sValue.toLowerCase( )) != -1)
		{
			var bFound = false;

			for (var k = 0; k < objSelectedMpsList.options.length; k ++)
			{
				if (objSelectedMpsList.options[k].text == sPoints[i][0])
				{
					bFound = true;
					break;
				}
			}

			if (bFound == false)
				objMpsList.options[j ++] = new Option(sPoints[i][0], sPoints[i][1], false, false);
		}
	}
}


function moveRight( )
{
	var iChecked = $('MPs').selectedIndex;

	if (iChecked == -1)
	{
		alert("Please select a Measurement Point to Add.");
	}

	else
	{
		var iCount = $('MPs').length;

		for (var i = 0; i < iCount; i ++)
		{
			if ($('MPs').options[i].selected != false)
			{
				$('SelectedMPs').options[$('SelectedMPs').length] = new Option($('MPs').options[i].text, $('MPs').options[i].value, false, false);
				$('SelectedMPs').selectedIndex = ($('SelectedMPs').length - 1);
		}
		}
	}

	filter("");
}


function moveLeft( )
{
	var iChecked = $('SelectedMPs').selectedIndex;

	if (iChecked == -1)
	{
		alert("Please select a Measurement Point to Remove.");
	}

	else
	{
		var iCount = $('SelectedMPs').length;

		for (i = (iCount - 1); i >= 0; i --)
		{
			if ($('SelectedMPs').options[i].selected != false)
				$('SelectedMPs').options[i] = null;
		}

		$('SelectedMPs').selectedIndex = -1;
	}

	filter("");
}


function moveUp( )
{
	var iCount   = $('SelectedMPs').length;
	var iIndex   = $('SelectedMPs').selectedIndex;
	var iSelected = 0;

	for (var i = 0; i < iCount; i ++)
	{
		if ($('SelectedMPs').options[i].selected == true)
			iSelected ++;
	}

	if (iSelected != 1)
	{
		alert("Please select one Measurement Point to Move.");

		return;
	}

	if (iIndex > 0)
	{
		var sText     = $('SelectedMPs').options[(iIndex - 1)].text;
		var sValue    = $('SelectedMPs').options[(iIndex - 1)].value;
		var bSelected = $('SelectedMPs').options[(iIndex - 1)].selected;

		$('SelectedMPs').options[(iIndex - 1)].text     = $('SelectedMPs').options[iIndex].text;
		$('SelectedMPs').options[(iIndex - 1)].value    = $('SelectedMPs').options[iIndex].value;
		$('SelectedMPs').options[(iIndex - 1)].selected = $('SelectedMPs').options[iIndex].selected;

		$('SelectedMPs').options[iIndex].text     = sText;
		$('SelectedMPs').options[iIndex].value    = sValue;
		$('SelectedMPs').options[iIndex].selected = bSelected;
	}
}


function moveDown( )
{
	var iCount   = $('SelectedMPs').length;
	var iIndex   = $('SelectedMPs').selectedIndex;
	var iSelected = 0;

	for (var i = 0; i < iCount; i ++)
	{
		if ($('SelectedMPs').options[i].selected == true)
			iSelected ++;
	}

	if (iSelected != 1)
	{
		alert("Please select one Measurement Point to Move.");

		return;
	}

	if (iIndex < (iCount - 1))
	{
		var sText     = $('SelectedMPs').options[(iIndex + 1)].text;
		var sValue    = $('SelectedMPs').options[(iIndex + 1)].value;
		var bSelected = $('SelectedMPs').options[(iIndex + 1)].selected;

		$('SelectedMPs').options[(iIndex + 1)].text     = $('SelectedMPs').options[iIndex].text;
		$('SelectedMPs').options[(iIndex + 1)].value    = $('SelectedMPs').options[iIndex].value;
		$('SelectedMPs').options[(iIndex + 1)].selected = $('SelectedMPs').options[iIndex].selected;

		$('SelectedMPs').options[iIndex].text     = sText;
		$('SelectedMPs').options[iIndex].value    = sValue;
		$('SelectedMPs').options[iIndex].selected = bSelected;
	}
}


function checkSelection( )
{
	var iCount = $('SelectedMPs').length;

	if (iCount == 0)
	{
		alert("Please select atleat One Measurement Point.");

		return false;
	}

	for (var i = 0; i < iCount; i ++)
		$('SelectedMPs').options[i].selected = true;

	return true;
}


function validateForm( )
{
	var objFV = new FormValidator("frmData");
	var bFlag = false;

	if (!objFV.validate("Category", "B", "Please select the Sampling Category."))
		return false;

	if (!checkSelection( ))
		return false;


	var sCheckboxes = $$("input.sizes");

	sCheckboxes.each(function(objElement)
	{
		if (objElement.checked == true)
			bFlag = true;
	});

	if (bFlag == false)
	{
		alert("Please select atleat One Size.");

		return false;
	}

	return true;
}