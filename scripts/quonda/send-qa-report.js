
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
	var sCheckboxes = $$("input.recipients");
	var bSelected   = false;

	sCheckboxes.each(function(objElement)
	{
		if (objElement.checked == true)
			bSelected = true;
	});
	

	if (bSelected == false)
	{
		alert("Please select at-least one Recipient.");

		return false;
	}

	return true;
}


function checkAll( )
{
	var sCheckboxes = $$("input.recipients");

	sCheckboxes.each( function(objElement) { objElement.checked = true; } );


	return false;
}


function clearAll( )
{
	var sCheckboxes = $$("input.recipients");

	sCheckboxes.each( function(objElement) { objElement.checked = false; } );


	return false;
}