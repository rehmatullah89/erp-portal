
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


	var sCheckboxes = $$("input.products");
	var bChecked    = false;

	sCheckboxes.each( function(objElement)
	{
			if (objElement.checked == true)
				bChecked = true;
	});

	if (bChecked == false)
	{
		alert("Please select at-least 1 Product to email.");

		return false;
	}


	sCheckboxes = $$("input.recipients");
	bSelected   = false;

	sCheckboxes.each(function(objElement)
	{
		if (objElement.checked == true)
			bSelected = true;
	});


	if (bSelected == false)
	{
		if (!objFV.validate("Others", "B", "Please select/enter Recipients."))
			return false;
	}



	if (!objFV.validate("Subject", "B", "Please enter the Subject."))
		return false;

	if (!objFV.validate("Message", "B", "Please enter your Message."))
		return false;

	return true;
}