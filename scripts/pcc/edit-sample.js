
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

/*
	if (!objFV.validate("Company", "B", "Please select the Company."))
		return false;

	if (!objFV.validate("SampleType", "B", "Please select the Sample Type."))
		return false;

	if (!objFV.validate("Name", "B", "Please enter the Name."))
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

	if (objFV.value("Image2") != "")
	{
		if (!checkImage(objFV.value("Image2")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Image2");
			objFV.select("Image2");

			return false;
		}
	}

	if (objFV.value("Image3") != "")
	{
		if (!checkImage(objFV.value("Image3")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Image3");
			objFV.select("Image3");

			return false;
		}
	}

	if (objFV.value("Image4") != "")
	{
		if (!checkImage(objFV.value("Image4")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Image4");
			objFV.select("Image4");

			return false;
		}
	}

	if (objFV.value("Image5") != "")
	{
		if (!checkImage(objFV.value("Image5")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Image5");
			objFV.select("Image5");

			return false;
		}
	}
*/
	return true;
}