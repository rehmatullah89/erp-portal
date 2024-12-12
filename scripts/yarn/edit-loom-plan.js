
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

	if (!objFV.validate("FromDate", "B", "Please select the From Date."))
		return false;

	if (!objFV.validate("ToDate", "B", "Please select the To Date."))
		return false;

	if (!objFV.validate("Looms", "B", "Please select the Available Looms."))
		return false;

	return true;
}


function updateTotal( )
{
	var iCount = $("Count").value;
	var iTotal = 0;

	for (var i = 1; i <= iCount; i ++)
	{
		var sProduction = $$("input.production" + i);

		sProduction.each(function(objElement)
		{
			if (objElement.value != "" && !isNaN(objElement.value))
				iTotal += parseInt(objElement.value);

			else
				objElement.value = "0";
		});


		$("Total" + i).innerHTML = iTotal;
	}
}