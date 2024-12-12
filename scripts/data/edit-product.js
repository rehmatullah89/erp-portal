
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

	if (!objFV.validate("Style", "B", "Please enter the Style."))
		return false;

	if (!objFV.validate("Color", "B", "Please enter the Color."))
		return false;

	if (!objFV.validate("Gender", "B", "Please enter the Gender."))
		return false;

	if (!objFV.validate("Fabric", "B", "Please enter the Fabric."))
		return false;

	if (!objFV.validate("FabricContents", "B", "Please enter the Fabric Contents."))
		return false;

	if (!objFV.validate("Weight", "B", "Please enter the Weight."))
		return false;

	if (!objFV.validate("Wash", "B", "Please enter the Wash."))
		return false;

	if (objFV.value("PictureLeft") != "")
	{
		if (!checkImage(objFV.value("PictureLeft")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("PictureLeft");
			objFV.select("PictureLeft");

			return false;
		}
	}

	if (objFV.value("PictureRight") != "")
	{
		if (!checkImage(objFV.value("PictureRight")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("PictureRight");
			objFV.select("PictureRight");

			return false;
		}
	}

	return true;
}