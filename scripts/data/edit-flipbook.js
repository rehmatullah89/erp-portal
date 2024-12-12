
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

var sProducts = new Array( );
var sUsers    = new Array( );
var sPictures = new Array( );

document.observe('dom:loaded', function( )
{
	var objProducts = $('Products');

	for (var i = 0; i < objProducts.options.length; i ++)
		sProducts[i] = new Array(objProducts.options[i].text, objProducts.options[i].value);

	for (var i = 0; i < objProducts.options.length; i ++)
		sPictures[i] = new Array(objProducts.options[i].value, objProducts.options[i].getAttribute("rel"));


	var objSelectedProducts = $('SelectedProducts');

	for (var i = 0; i < objSelectedProducts.options.length; i ++)
		sPictures[(i + objProducts.options.length)] = new Array(objSelectedProducts.options[i].value, objSelectedProducts.options[i].getAttribute("rel"));


	var objUsers = $('Users');

	for (var i = 0; i < objUsers.options.length; i ++)
		sUsers[i] = new Array(objUsers.options[i].text, objUsers.options[i].value);
});


function showImage(sImage)
{
	$("Image").src = sImage;
}

function showSelectedImage( )
{
	var iCount = $('SelectedProducts').length;
	var sValue = "";

	for (var i = 0; i < iCount; i ++)
	{
		if ($('SelectedProducts').options[i].selected == true)
		{
			sValue = $('SelectedProducts').options[i].value;

			break;
		}
	}

	if (sValue != "")
	{
		for (var i = 0; i < sPictures.length; i ++)
		{
			if (sPictures[i][0] == sValue)
			{
				$("Image").src = sPictures[i][1];

				break;
			}
		}
	}
}


function moveProductRight( )
{
	var iChecked = $('Products').selectedIndex;

	if (iChecked == -1)
	{
		alert("Please select a Product to Add.");
	}

	else
	{
		var iCount = $('Products').length;

		for (var i = 0; i < iCount; i ++)
		{
			if ($('Products').options[i].selected != false)
				$('SelectedProducts').options[$('SelectedProducts').length] = new Option($('Products').options[i].text, $('Products').options[i].value, false, false);
		}

		for (i = (iCount - 1); i >= 0; i --)
		{
			if ($('Products').options[i].selected != false)
				$('Products').options[i] = null;
		}

		$('Products').selectedIndex = -1;
	}
}

function moveProductLeft( )
{
	var iChecked = $('SelectedProducts').selectedIndex;

	if (iChecked == -1)
	{
		alert("Please select a Product to Remove.");
	}

	else
	{
		var iCount = $('SelectedProducts').length;

		for (var i = 0; i < iCount; i ++)
		{
			if ($('SelectedProducts').options[i].selected != false)
				$('Products').options[$('Products').length] = new Option($('SelectedProducts').options[i].text, $('SelectedProducts').options[i].value, false, false);
		}

		for (i = (iCount - 1); i >= 0; i --)
		{
			if ($('SelectedProducts').options[i].selected != false)
				$('SelectedProducts').options[i] = null;
		}

		$('SelectedProducts').selectedIndex = -1;
	}
}


function moveUp( )
{
	var iCount   = $('SelectedProducts').length;
	var iIndex   = $('SelectedProducts').selectedIndex;
	var iSelected = 0;

	for (var i = 0; i < iCount; i ++)
	{
		if ($('SelectedProducts').options[i].selected == true)
			iSelected ++;
	}

	if (iSelected != 1)
	{
		alert("Please select one Product to Move.");

		return;
	}

	if (iIndex > 0)
	{
		var sText     = $('SelectedProducts').options[(iIndex - 1)].text;
		var sValue    = $('SelectedProducts').options[(iIndex - 1)].value;
		var bSelected = $('SelectedProducts').options[(iIndex - 1)].selected;

		$('SelectedProducts').options[(iIndex - 1)].text     = $('SelectedProducts').options[iIndex].text;
		$('SelectedProducts').options[(iIndex - 1)].value    = $('SelectedProducts').options[iIndex].value;
		$('SelectedProducts').options[(iIndex - 1)].selected = $('SelectedProducts').options[iIndex].selected;

		$('SelectedProducts').options[iIndex].text     = sText;
		$('SelectedProducts').options[iIndex].value    = sValue;
		$('SelectedProducts').options[iIndex].selected = bSelected;
	}
}


function moveDown( )
{
	var iCount   = $('SelectedProducts').length;
	var iIndex   = $('SelectedProducts').selectedIndex;
	var iSelected = 0;

	for (var i = 0; i < iCount; i ++)
	{
		if ($('SelectedProducts').options[i].selected == true)
			iSelected ++;
	}

	if (iSelected != 1)
	{
		alert("Please select one Product to Move.");

		return;
	}

	if (iIndex < (iCount - 1))
	{
		var sText     = $('SelectedProducts').options[(iIndex + 1)].text;
		var sValue    = $('SelectedProducts').options[(iIndex + 1)].value;
		var bSelected = $('SelectedProducts').options[(iIndex + 1)].selected;

		$('SelectedProducts').options[(iIndex + 1)].text     = $('SelectedProducts').options[iIndex].text;
		$('SelectedProducts').options[(iIndex + 1)].value    = $('SelectedProducts').options[iIndex].value;
		$('SelectedProducts').options[(iIndex + 1)].selected = $('SelectedProducts').options[iIndex].selected;

		$('SelectedProducts').options[iIndex].text     = sText;
		$('SelectedProducts').options[iIndex].value    = sValue;
		$('SelectedProducts').options[iIndex].selected = bSelected;
	}
}


function filterProducts(sValue)
{
	var objProducts = $('Products');
	var objSelected = $('SelectedProducts');

	for (var i = (objProducts.options.length - 1); i >= 0; i --)
		objProducts.options[i] = null;

	for (var i = 0, j = 0; i < sProducts.length; i ++)
	{
		if (sProducts[i][0].toLowerCase( ).indexOf(sValue.toLowerCase( )) != -1)
		{
			var bFound = false;

			for (var k = 0; k < objSelected.options.length; k ++)
			{
				if (objSelected.options[k].text == sProducts[i][0])
				{
					bFound = true;
					break;
				}
			}

			if (bFound == false)
				objProducts.options[j ++] = new Option(sProducts[i][0], sProducts[i][1], false, false);
		}
	}
}


function checkProductSelection( )
{
	var iCount = $('SelectedProducts').length;

	if (iCount == 0)
	{
		alert("Please select atleast One Product.");

		return false;
	}

	for (var i = 0; i < iCount; i ++)
		$('SelectedProducts').options[i].selected = true;

	return true;
}


function moveUserRight( )
{
	var iChecked = $('Users').selectedIndex;

	if (iChecked == -1)
	{
		alert("Please select a User to Add.");
	}

	else
	{
		var iCount = $('Users').length;

		for (var i = 0; i < iCount; i ++)
		{
			if ($('Users').options[i].selected != false)
				$('SelectedUsers').options[$('SelectedUsers').length] = new Option($('Users').options[i].text, $('Users').options[i].value, false, false);
		}

		for (i = (iCount - 1); i >= 0; i --)
		{
			if ($('Users').options[i].selected != false)
				$('Users').options[i] = null;
		}

		$('Users').selectedIndex = -1;
	}
}

function moveUserLeft( )
{
	var iChecked = $('SelectedUsers').selectedIndex;

	if (iChecked == -1)
	{
		alert("Please select a User to Remove.");
	}

	else
	{
		var iCount = $('SelectedUsers').length;

		for (var i = 0; i < iCount; i ++)
		{
			if ($('SelectedUsers').options[i].selected != false)
				$('Users').options[$('Users').length] = new Option($('SelectedUsers').options[i].text, $('SelectedUsers').options[i].value, false, false);
		}

		for (i = (iCount - 1); i >= 0; i --)
		{
			if ($('SelectedUsers').options[i].selected != false)
				$('SelectedUsers').options[i] = null;
		}

		$('SelectedUsers').selectedIndex = -1;
	}
}

function filterUsers(sValue)
{
	var objUsers = $('Users');
	var objSelected = $('SelectedUsers');

	for (var i = (objUsers.options.length - 1); i >= 0; i --)
		objUsers.options[i] = null;

	for (var i = 0, j = 0; i < sUsers.length; i ++)
	{
		if (sUsers[i][0].toLowerCase( ).indexOf(sValue.toLowerCase( )) != -1)
		{
			var bFound = false;

			for (var k = 0; k < objSelected.options.length; k ++)
			{
				if (objSelected.options[k].text == sUsers[i][0])
				{
					bFound = true;
					break;
				}
			}

			if (bFound == false)
				objUsers.options[j ++] = new Option(sUsers[i][0], sUsers[i][1], false, false);
		}
	}
}


function checkUserSelection( )
{
	var iCount = $('SelectedUsers').length;

	if (iCount == 0)
	{
		alert("Please select atleast One User.");

		return false;
	}

	for (var i = 0; i < iCount; i ++)
		$('SelectedUsers').options[i].selected = true;

	return true;
}



function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Title", "B", "Please enter the Flipbook Title."))
		return false;

	if (!objFV.validate("Color", "B", "Please select the Flipbook Text Color."))
		return false;

	if (!objFV.validate("Background", "B", "Please select the Flipbook Background Color."))
		return false;

	if (!objFV.validate("Left", "B", "Please enter the Box Left Position."))
		return false;

	if (!objFV.validate("Top", "B", "Please enter the Box Top Position."))
		return false;

	if (!objFV.validate("Width", "B", "Please enter the Box Width."))
		return false;

	if (objFV.value("FrontPicture") != "")
	{
		if (!checkImage(objFV.value("FrontPicture")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("FrontPicture");
			objFV.select("FrontPicture");

			return false;
		}
	}

	if (objFV.value("BackPicture") != "")
	{
		if (!checkImage(objFV.value("BackPicture")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("BackPicture");
			objFV.select("BackPicture");

			return false;
		}
	}


	if (checkUserSelection( ) == false || checkProductSelection( ) == false)
		return false;

	return true;
}