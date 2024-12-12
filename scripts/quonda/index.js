
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

function validateViewReport( )
{
	var objFV = new FormValidator("frmViewReport");

	if (!objFV.validate("AuditCode", "B", "Please enter the Audit Code."))
		return false;
		
	return true;
}


function validateNewReport( )
{
	var objFV = new FormValidator("frmNewReport");

	if (!objFV.validate("AuditCode", "B", "Please select the Audit Code."))
		return false;
		
	return true;
}


function validateEditReport( )
{
	var objFV = new FormValidator("frmEditReport");

	if (!objFV.validate("AuditCode", "B", "Please enter the Audit Code."))
		return false;
		
	return true;
}