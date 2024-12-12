
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

	if (objFV.value("Type") != "Category")
		objFV.enabled("File");

	else
		objFV.disabled("File");

	if (!objFV.validate("Type", "B", "Please select the Item Type."))
		return false;

	if (!objFV.validate("Title", "B", "Please enter the Item Title."))
		return false;

	if (objFV.value("Type") != "Category")
	{
		if (!objFV.validate("File", "B", "Please select the File to upload."))
			return false;

		if (objFV.value("File") != "")
		{
			if (objFV.value("Type") == "Image")
			{
				if (!checkImage(objFV.value("File")))
				{
					alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

					objFV.focus("File");
					objFV.select("File");

					return false;
				}
			}

			else if (objFV.value("Type") == "Pdf")
			{
				if (!checkPdfFile(objFV.value("File")))
				{
					alert("Invalid File Format. Please select a valid PDF File.");

					objFV.focus("File");
					objFV.select("File");

					return false;
				}
			}

			else if (objFV.value("Type") == "Video")
			{
				if (!checkFlvFile(objFV.value("File")))
				{
					alert("Invalid File Format. Please select a valid FLV Video File.");

					objFV.focus("File");
					objFV.select("File");

					return false;
				}
			}

			else if (objFV.value("Type") == "Presentation")
			{
				if (!checkPptFile(objFV.value("File")))
				{
					alert("Invalid File Format. Please select a valid PPT File.");

					objFV.focus("File");
					objFV.select("File");

					return false;
				}
			}
		}
	}

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (objFV.value("Type") != "Category")
		objFV.enabled("File");

	else
		objFV.disabled("File");

	if (!objFV.validate("Type", "B", "Please select the Item Type."))
		return false;

	if (!objFV.validate("Title", "B", "Please enter the Item Title."))
		return false;

	if (objFV.value("Type") != "Category")
	{
		if (objFV.value("File") != "")
		{
			if (objFV.value("Type") == "Image")
			{
				if (!checkImage(objFV.value("File")))
				{
					alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

					objFV.focus("File");
					objFV.select("File");

					return false;
				}
			}

			else if (objFV.value("Type") == "Pdf")
			{
				if (!checkPdfFile(objFV.value("File")))
				{
					alert("Invalid File Format. Please select a valid PDF File.");

					objFV.focus("File");
					objFV.select("File");

					return false;
				}
			}

			else if (objFV.value("Type") == "Video")
			{
				if (!checkFlvFile(objFV.value("File")))
				{
					alert("Invalid File Format. Please select a valid FLV Video File.");

					objFV.focus("File");
					objFV.select("File");

					return false;
				}
			}

			else if (objFV.value("Type") == "Presentation")
			{
				if (!checkPptFile(objFV.value("File")))
				{
					alert("Invalid File Format. Please select a valid PPT File.");

					objFV.focus("File");
					objFV.select("File");

					return false;
				}
			}
		}
	}

	return true;
}