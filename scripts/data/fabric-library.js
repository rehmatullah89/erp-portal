
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
	var bFlag = false;
	
	if (!objFV.validate("Category", "B", "Please select the Fabric Category."))
		return false;
	
	for (var i = 1; i <= 5; i ++)
	{
		if (objFV.value("Caption" + i) != "" || objFV.value("Picture" + i) != "")
		{
			if (!objFV.validate(("Caption" + i), "B", "Please enter the Picture Title."))
				return false;
				
			if (!objFV.validate(("Picture" + i), "B", "Please select the Picture."))
				return false;

			if (objFV.value("Picture" + i) != "")
			{
				if (!checkImage(objFV.value("Picture" + i)))
				{
					alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

					objFV.focus("Picture" + i);
					objFV.select("Picture" + i);

					return false;
				}
			}
			
			bFlag = true;
		}
	}
	
	if (bFlag == false)
	{
		alert("Please select atleast one Picture to Upload.");
		
		objFV.focus("Caption1");
		
		return false;
	}

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);
	
	if (!objFV.validate("Category", "B", "Please select the Fabric Category."))
		return false;
		
	if (!objFV.validate("Caption", "B", "Please enter the Picture Title."))
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