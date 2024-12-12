
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

function validateForm( )
{
	var objFV = new FormValidator("frmContact");

	if (!objFV.validate("Name", "B", "Please enter your Name."))
		return false;

	if (!objFV.validate("Email", "B,E", "Please enter your valid Email Address."))
		return false;

	if (!objFV.validate("Subject", "B", "Please enter the Message Subject."))
		return false;

	if (!objFV.validate("Message", "B", "Please enter your Message."))
		return false;

	if (!objFV.validate("SpamCode", "B,L(5)", "Please enter the valid Spam Protection Code as shown."))
		return false;

	return true;
}