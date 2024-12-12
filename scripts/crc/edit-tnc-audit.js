
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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
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
		if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
			return false;

		if ($('Auditors').selectedIndex == -1)
		{
			alert("Please select at-least One Auditor.");

			return false;
		}

		if (!objFV.validate("AuditDate", "B", "Please select the Audit Date."))
			return false;
	}

	return true;
}