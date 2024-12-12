
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

	if (!objFV.validate("Market", "B", "Please select the Market."))
		return false;

	if (!objFV.validate("Season", "B", "Please select the Season."))
		return false;

	if (!objFV.validate("Fabric", "B", "Please select the Fabric."))
		return false;

	if (!objFV.validate("Category", "B", "Please select the Category."))
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

	if (objFV.value("ArtPhoto") != "")
	{
		if (!checkImage(objFV.value("ArtPhoto")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("ArtPhoto");
			objFV.select("ArtPhoto");

			return false;
		}
	}

	if (objFV.value("FabricPhoto") != "")
	{
		if (!checkImage(objFV.value("FabricPhoto")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("FabricPhoto");
			objFV.select("FabricPhoto");

			return false;
		}
	}

	if (objFV.value("WashPhoto") != "")
	{
		if (!checkImage(objFV.value("WashPhoto")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("WashPhoto");
			objFV.select("WashPhoto");

			return false;
		}
	}

	if (objFV.value("SilhouettePhoto") != "")
	{
		if (!checkImage(objFV.value("SilhouettePhoto")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("SilhouettePhoto");
			objFV.select("SilhouettePhoto");

			return false;
		}
	}

	return true;
}