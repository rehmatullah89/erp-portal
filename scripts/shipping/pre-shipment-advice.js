
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
	var sCheckboxes = $$("input.po");
	var bFlag       = false;

	sCheckboxes.each( function(objElement)
	{
		if (objElement.checked == true)
			bFlag = true;
	});
	
	if (bFlag == false)
	{
		alert("Please select some POs to Mark them as Shipped.");
		
		return false;
	}
	
	return true;
}

function checkAll( )
{
	var sCheckboxes = $$("input.po");

	sCheckboxes.each( function(objElement) { objElement.checked = true; } );
}

function clearAll( )
{
	var sCheckboxes = $$("input.po");
	
	sCheckboxes.each( function(objElement) { objElement.checked = false; } );
}

function exportReport( )
{
	$('BtnExport').disabled = true;
	
	document.location = $('ExportUrl').value;
	
	setTimeout( function( ) { $('BtnExport').disabled = false; }, 10000);
}