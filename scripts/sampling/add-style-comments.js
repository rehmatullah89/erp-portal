
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
	var objFV = new FormValidator("frmComments");

	if (!objFV.validate("From", "B", "Please select the From."))
		return false;

	if (objFV.value("From") == "Sampling Technician")
	{
		if (!objFV.validate("Technician", "B", "Please select the Sampling Technician."))
			return false;
	}

	else if (objFV.value("From") == "Quality Technician")
	{
		if (!objFV.validate("Technician", "B", "Please select the Quality Technician."))
			return false;
	}

	else if (objFV.value("From") == "Merchandiser")
	{
		if (!objFV.validate("Merchandiser", "B", "Please select the Merchandiser."))
			return false;
	}

	if (!objFV.validate("Date", "B", "Please select the Comment Date."))
		return false;

//	if (!objFV.validate("Comments", "B", "Please enter the Comments."))
//		return false;

	return true;
}


function showUsers(sType)
{
	document.getElementById("Technician").style.display = "none";
	document.getElementById("Merchandiser").style.display = "none";

	if (sType == "Sampling Technician" || sType == "Quality Technician")
		document.getElementById("Technician").style.display = "table-row";

	else if (sType == "Merchandiser")
		document.getElementById("Merchandiser").style.display = "table-row";


	if (sType == "Buyer" || sType == "")
		document.getElementById("Space").style.display = "block";

	else
		document.getElementById("Space").style.display = "none";
}