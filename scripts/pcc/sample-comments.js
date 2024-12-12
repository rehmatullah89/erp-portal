
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
	var objFV = new FormValidator("frmComments");

	if (!objFV.validate("User", "B", "Please select the User."))
		return false;

	if (!objFV.validate("Date", "B", "Please select the Comment Date."))
		return false;

	if (!objFV.validate("Hours", "B", "Please select the Comment Time."))
		return false;

	if (!objFV.validate("Minutes", "B", "Please select the Comment Time."))
		return false;

	if (!objFV.validate("Seconds", "B", "Please select the Comment Time."))
		return false;

	if (!objFV.validate("Comments", "B", "Please enter the Comments."))
		return false;

	return true;
}