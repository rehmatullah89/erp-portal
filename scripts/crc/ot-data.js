
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
 
function exportReport( )
{
	$('BtnExport').disabled = true;
	
	document.location = $('ExportUrl').value;
	
	setTimeout( function( ) { $('BtnExport').disabled = false; }, 10000);
}

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;
		
	if (!objFV.validate("Month", "B", "Please select the Month."))
		return false;
		
	if (!objFV.validate("Year", "B", "Please select the Year."))
		return false;
		
	if (!objFV.validate("OtFile", "B", "Please select the OT File."))
		return false;
		
	if (!checkExcelFile(objFV.value("OtFile")))
	{
		alert("Please select a valid MS Excel File.");
		
		return false;
	}

	return true;
}