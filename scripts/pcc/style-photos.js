
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

	if (!objFV.validate("Photo1", "B", "Please select the Photo."))
		return false;

	if (objFV.value("Photo1") != "")
	{
		if (!checkImage(objFV.value("Photo1")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Photo1");
			objFV.select("Photo1");

			return false;
		}
	}

	if (objFV.value("Photo2") != "")
	{
		if (!checkImage(objFV.value("Photo2")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Photo2");
			objFV.select("Photo2");

			return false;
		}
	}

	if (objFV.value("Photo3") != "")
	{
		if (!checkImage(objFV.value("Photo3")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Photo3");
			objFV.select("Photo3");

			return false;
		}
	}

	if (objFV.value("Photo4") != "")
	{
		if (!checkImage(objFV.value("Photo4")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Photo4");
			objFV.select("Photo4");

			return false;
		}
	}

	if (objFV.value("Photo5") != "")
	{
		if (!checkImage(objFV.value("Photo5")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Photo5");
			objFV.select("Photo5");

			return false;
		}
	}

	return true;
}