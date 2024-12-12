
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
	
	if (!objFV.validate("Title", "B", "Please enter the Video Title."))
		return false;
		
	if (!objFV.validate("Video", "B", "Please select the Video File."))
		return false;
		
	if (objFV.value("Video") != "")
	{
		if (!checkVideo(objFV.value("Video")))
		{
			alert("Invalid File Format. Please select a video file of type flv, mp4 or mpg.");

			objFV.focus("Video");
			objFV.select("Video");

			return false;
		}
	}

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);
	
	if (!objFV.validate("Title", "B", "Please enter the Video Title."))
		return false;
		
	if (objFV.value("Video") != "")
	{
		if (!checkVideo(objFV.value("Video")))
		{
			alert("Invalid File Format. Please select a video file of type flv, mp4 or mpg.");

			objFV.focus("Video");
			objFV.select("Video");

			return false;
		}
	}
		
	return true;
}