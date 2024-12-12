
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
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("AirwayBill", "B", "Please enter the Airway Bill Number."))
		return false;

	if (!objFV.validate("Company", "B", "Please select the Courier Company."))
		return false;

	if (!objFV.validate("Type", "B", "Please select the Courier Type."))
		return false;

	if (!objFV.validate("Country", "B", "Please select the Sent/Received Country."))
		return false;

	if (!objFV.validate("Date", "B", "Please select the Courier Date."))
		return false;

	return true;
}

