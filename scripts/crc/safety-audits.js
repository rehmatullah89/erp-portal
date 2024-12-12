
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
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

	if ($('Auditors').selectedIndex == -1)
	{
		alert("Please select at-least One Auditor.");

		return false;
	}

	if (!objFV.validate("Representative", "B", "Please enter the Factory Representative."))
		return false;

	if (!objFV.validate("AuditDate", "B", "Please select the Audit Date."))
		return false;

	if (!objFV.validate("AuditHours", "B", "Please select the Audit Time (Hours)."))
		return false;

	if (!objFV.validate("AuditMinutes", "B", "Please select the Audit Time (Minutes)."))
		return false;

	return true;
}