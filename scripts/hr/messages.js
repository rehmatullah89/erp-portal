
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
	var objFV = new FormValidator("frmMessage");

	if (objFV.value("Hr") != "")
	{
		if (checkSelection( ) == false)
		{
			alert("Please select a Employee to reply.");

			return false;
		}
	}

	if (!objFV.validate("Message", "B", "Please enter your Message."))
		return false;

	return true;
}

function moveRight( )
{
	var iChecked = $('Recipients').selectedIndex;

	if (iChecked == -1)
	{
		alert("Please select an Employee to Remove from Recipients List.");
	}

	else
	{
		var iCount = $('Recipients').length;

		for (var i = 0; i < iCount; i ++)
		{
			if ($('Recipients').options[i].selected != false)
				$('Employees').options[$('Employees').length] = new Option($('Recipients').options[i].text, $('Recipients').options[i].value, false, false);
		}

		for (i = (iCount - 1); i >= 0; i --)
		{
			if ($('Recipients').options[i].selected != false)
				$('Recipients').options[i] = null;
		}

		$('Recipients').selectedIndex = -1;
	}
}

function moveLeft( )
{
	var iChecked = $('Employees').selectedIndex;

	if (iChecked == -1)
	{
		alert("Please select an Employee to Add into the Recipients List.");
	}

	else
	{
		var iCount = $('Employees').length;

		for (var i = 0; i < iCount; i ++)
		{
			if ($('Employees').options[i].selected != false)
				$('Recipients').options[$('Recipients').length] = new Option($('Employees').options[i].text, $('Employees').options[i].value, false, false);
		}

		for (i = (iCount - 1); i >= 0; i --)
		{
			if ($('Employees').options[i].selected != false)
				$('Employees').options[i] = null;
		}

		$('Employees').selectedIndex = -1;
	}
}

function checkSelection( )
{
	var iCount = $('Recipients').length;

	if (iCount == 0)
	{
		alert("Please select atleat One Employee to Reply.");

		return false;
	}

	for (var i = 0; i < iCount; i ++)
		$('Recipients').options[i].selected = true;

	return true;
}