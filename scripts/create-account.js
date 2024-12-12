
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

function checkUsername( )
{
	var sUsername = $F('Username');

	sUsername = sUsername.replaceAll(" ", ".");
	$('Username').value = sUsername;

	var sUrl    = "ajax/check-username.php";
	var sParams = ("Username=" + sUsername);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_checkUsername });
}


function _checkUsername(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		if (sResponse.responseText == "AVAILABLE")
		{
			$('UsernameResult').setStyle({ fontSize:'12px', fontWeight:'bold', color:'#0000ff' });
			$('UsernameResult').innerHTML = 'Available!';
		}

		else if (sResponse.responseText == "NOT_AVAILABLE")
		{
			$('UsernameResult').setStyle({ fontSize:'12px', fontWeight:'bold', color:'#ff0000' });
			$('UsernameResult').innerHTML = 'Not Available!';
		}
	}
}

function checkEmail( )
{
	var sUrl    = "ajax/check-email.php";
	var sParams = ("Email=" + $F('Email'));

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_checkEmail });
}


function _checkEmail(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		if (sResponse.responseText == "AVAILABLE")
		{
			$('EmailResult').setStyle({ fontSize:'11px', fontWeight:'bold', color:'#0000ff' });
			$('EmailResult').innerHTML = '';
		}

		else if (sResponse.responseText == "NOT_AVAILABLE")
		{
			$('EmailResult').setStyle({ fontSize:'11px', fontWeight:'bold', color:'#ff0000' });
			$('EmailResult').innerHTML = 'Already Used!';
		}
	}
}

function validateForm( )
{
	var objFV = new FormValidator("frmAccount");

	if (!objFV.validate("Name", "B", "Please enter your Full Name."))
		return false;

	if (!objFV.validate("City", "B", "Please enter your City."))
		return false;

	if (!objFV.validate("Country", "B", "Please select your Country."))
		return false;

	if (!objFV.validate("Email", "B,E", "Please enter your valid Email Address."))
		return false;

	if (!objFV.validate("Mobile", "B", "Please enter your Mobile Number."))
		return false;

	if (!objFV.validate("Username", "B,L(3)", "Please enter the valid Username (Min Length = 3)."))
		return false;

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

	if (!objFV.validate("SpamCode", "B,L(5)", "Please enter the valid Spam Protection Code as shown."))
		return false;

	return true;
}
