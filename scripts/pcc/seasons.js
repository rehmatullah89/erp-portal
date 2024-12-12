
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

	if (!objFV.validate("Title", "B", "Please enter the Title."))
		return false;

//	if (!objFV.validate("Icon", "B", "Please select the Icon."))
//		return false;

	if (objFV.value("Icon") != "")
	{
		if (!checkImage(objFV.value("Icon")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Icon");
			objFV.select("Icon");

			return false;
		}
	}

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Title", "B", "Please enter the Title."))
		return false;

	if (objFV.value("Icon") != "")
	{
		if (!checkImage(objFV.value("Icon")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Icon");
			objFV.select("Icon");

			return false;
		}
	}

	return true;
}