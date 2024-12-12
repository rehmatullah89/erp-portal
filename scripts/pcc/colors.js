
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

	if (!objFV.validate("Season", "B", "Please select the Season."))
		return false;

	if (!objFV.validate("Pantone", "B", "Please enter the Pantone."))
		return false;

	if (!objFV.validate("Name", "B", "Please enter the Name."))
		return false;

	if (!objFV.validate("Red", "B,N", "Please enter the Red Value."))
		return false;

	if (!objFV.validate("Green", "B,N", "Please enter the Green Value."))
		return false;

	if (!objFV.validate("Blue", "B,N", "Please enter the Blue Value."))
		return false;

//	if (!objFV.validate("Image", "B", "Please select the Image."))
//		return false;

	if (objFV.value("Image") != "")
	{
		if (!checkImage(objFV.value("Image")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Image");
			objFV.select("Image");

			return false;
		}
	}

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Season", "B", "Please select the Season."))
		return false;

	if (!objFV.validate("Pantone", "B", "Please enter the Pantone."))
		return false;

	if (!objFV.validate("Name", "B", "Please enter the Name."))
		return false;

	if (!objFV.validate("Red", "B,N", "Please enter the Red Value."))
		return false;

	if (!objFV.validate("Green", "B,N", "Please enter the Green Value."))
		return false;

	if (!objFV.validate("Blue", "B,N", "Please enter the Blue Value."))
		return false;

	if (objFV.value("Image") != "")
	{
		if (!checkImage(objFV.value("Image")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Image");
			objFV.select("Image");

			return false;
		}
	}

	return true;
}