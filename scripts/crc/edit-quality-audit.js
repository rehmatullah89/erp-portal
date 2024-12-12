
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

function validateForm(sStep)
{
	var objFV = new FormValidator("frmData");

	if (sStep == "0")
	{
		if (!objFV.validate("AuditDate", "B", "Please select the Audit Date."))
			return false;

		if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
			return false;

		if ($('Auditors').selectedIndex == -1)
		{
			alert("Please select at-least One Auditor.");

			return false;
		}
	}

	return true;
}