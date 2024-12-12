
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

	if (!objFV.validate("Gallery", "B", "Please select the Gallery."))
		return false;

	if (!objFV.validate("Name", "B", "Please enter the Name."))
		return false;

	if (!objFV.validate("Picture", "B", "Please select the Picture."))
		return false;

	if (objFV.value("Picture") != "")
	{
		if (!checkImage(objFV.value("Picture")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Picture");
			objFV.select("Picture");

			return false;
		}
	}

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Gallery", "B", "Please select the Gallery."))
		return false;

	if (!objFV.validate("Name", "B", "Please enter the Name."))
		return false;

	if (objFV.value("Picture") != "")
	{
		if (!checkImage(objFV.value("Picture")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Picture");
			objFV.select("Picture");

			return false;
		}
	}

	return true;
}