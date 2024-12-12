
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
	var objFV = new FormValidator("frmChangePassword");

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