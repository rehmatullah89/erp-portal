
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

function selectAll(sClass)
{
	var sCheckboxes = $$("input." + sClass);

	sCheckboxes.each(function(objCheckbox)
	{
		objCheckbox.checked = true;
	});
}


function clearAll(sClass)
{
	var sCheckboxes = $$("input." + sClass);

	sCheckboxes.each(function(objCheckbox)
	{
		objCheckbox.checked = false;
	});
}


function validateForm1( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("WorkOrder", "B", "Please enter the Factory Work Order #."))
		return false;


	var sPOs = "";

	$$("input.po").each(function(objCheckbox)
	{
		if (objCheckbox.checked == true)
		{
			if (sPOs == "")
				sPOs = objCheckbox.value;

			else
				sPOs += ("," + objCheckbox.value);
		}
	});

	if (sPOs == "")
	{
		alert("Please select at-least one PO");

		return false;
	}



	var sStyles = "";

	$$("input.style").each(function(objCheckbox)
	{
		if (objCheckbox.checked == true)
		{
			if (sStyles == "")
				sStyles = objCheckbox.value;

			else
				sStyles += ("," + objCheckbox.value);
		}
	});

	if (sStyles == "")
	{
		alert("Please select at-least one Style");

		return false;
	}


	return true;
}


function checkAll( )
{
	if ($("All").checked == true)
		selectAll('poColor');

	else
		clearAll('poColor');
}


function reCheckSelection( )
{
	var sCheckboxes = $$("input.poColor");


	$("All").checked = true;

	sCheckboxes.each(function(objCheckbox)
	{
		if (objCheckbox.checked == false)
			$("All").checked = false;
	});
}


function validateForm2( )
{
	var objFV = new FormValidator("frmData");

	var sColors = "";

	$$("input.poColor").each(function(objCheckbox)
	{
		if (objCheckbox.checked == true)
		{
			if (sColors == "")
				sColors = objCheckbox.value;

			else
				sColors += ("," + objCheckbox.value);
		}
	});

	if (sColors == "")
	{
		alert("Please select at-least one PO Color");

		return false;
	}


	return true;
}
