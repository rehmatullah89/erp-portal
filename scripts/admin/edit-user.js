
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

function validateCopyForm( )
{
	var objFV = new FormValidator("frmCopy");

	if (!objFV.validate("User", "B", "Please select the User Account."))
		return false;

	if (!confirm("Are you sure, You want to copy the Rights from selected User?"))
		return false;

	return true;
}



function resetPageRights(iIndex)
{
	if ($('View' + iIndex).checked == true && $('Add' + iIndex).checked == true && $('Edit' + iIndex).checked == true && $('Delete' + iIndex).checked == true)
		$('All' + iIndex).checked = true;

	else
		$('All' + iIndex).checked = false;

	if ($('Add' + iIndex).checked == true || $('Edit' + iIndex).checked == true || $('Delete' + iIndex).checked == true)
		$('View' + iIndex).checked = true;
}

function checkAllPageRights(iIndex)
{
	if ($('All' + iIndex).checked == true)
	{
		$('View' + iIndex).checked   = true;
		$('Add' + iIndex).checked    = true;
		$('Edit' + iIndex).checked   = true;
		$('Delete' + iIndex).checked = true;
	}

	else
	{
		$('View' + iIndex).checked   = false;
		$('Add' + iIndex).checked    = false;
		$('Edit' + iIndex).checked   = false;
		$('Delete' + iIndex).checked = false;
	}
}

function filterVendors(sFilter)
{
	objList = $("Vendors");

	for (var i = (objList.options.length - 1); i >= 0; i --)
		objList.options[i] = null;

	$("Vendors").disable( );


	$('Processing').show( );

	var sUrl    = "ajax/admin/get-brand-vendors.php";
	var sParams = ((sFilter == "Y") ? $('Brands').serialize( ) : "");

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_filterVendors });
}


function _filterVendors(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		for (var i = 0; i < sParams.length; i ++)
		{
			var sOption = sParams[i].split("||");

			$("Vendors").options[i] = new Option(sOption[1], sOption[0], false, false);

		}

		$("Vendors").enable( );
		$('Processing').hide( );
	}

	else
		_showError( );
}


function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Name", "B", "Please enter your Full Name."))
		return false;
/*
	if (!objFV.validate("City", "B", "Please enter your City."))
		return false;

	if (!objFV.validate("Country", "B", "Please select your Country."))
		return false;
*/
	if (objFV.value("Picture") != "")
	{
		if (!checkImage(objFV.value("Picture")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Picture");
			objFV.select("Picture");

			return false;
		}
	}

	if (objFV.value("Signature") != "")
	{
		if (!checkImage(objFV.value("Signature")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Signature");
			objFV.select("Signature");

			return false;
		}
	}

	if ($("Mobile"))
	{
		if (!objFV.validate("Mobile", "B", "Please enter your Mobile Number."))
			return false;
	}

	if (objFV.value("Password") != "")
	{
		if (!objFV.validate("Password", "B,L(3)", "Please enter the valid Password (Min Length = 3)."))
			return false;

		if (!objFV.validate("RetypePassword", "B,L(3)", "Please re-type the correct Password (Min Length = 3)."))
			return false;

		if (objFV.value("Password") != objFV.value("RetypePassword"))
		{
			alert("The Passwords does not MATCH. Please re-type the correct Password.");

			objFV.focus("RetypePassword");
			objFV.select("RetypePassword");

			return false;
		}
	}

	return true;
}