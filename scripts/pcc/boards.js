
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

	if (!objFV.validate("BoardType", "B", "Please select the Board Type."))
		return false;

	if (!objFV.validate("Company", "B", "Please select the Company."))
		return false;

	if (!objFV.validate("Name", "B", "Please enter the Board Name."))
		return false;

//	if (!objFV.validate("Avatar", "B", "Please select the Avatar."))
//		return false;

	if (objFV.value("Avatar") != "")
	{
		if (!checkImage(objFV.value("Avatar")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Avatar");
			objFV.select("Avatar");

			return false;
		}
	}

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("BoardType", "B", "Please select the Board Type."))
		return false;

	if (!objFV.validate("Company", "B", "Please select the Company."))
		return false;

	if (!objFV.validate("Name", "B", "Please enter the Board Name."))
		return false;

	if (objFV.value("Avatar") != "")
	{
		if (!checkImage(objFV.value("Avatar")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Avatar");
			objFV.select("Avatar");

			return false;
		}
	}

	return true;
}