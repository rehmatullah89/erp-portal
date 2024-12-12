
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

function exportReport( )
{
	$('BtnExport').disabled = true;

	document.location = $('ExportUrl').value;

	setTimeout( function( ) { $('BtnExport').disabled = false; }, 10000);
}

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;

	if (!objFV.validate("Season", "B", "Please select the Season."))
		return false;

	if (!objFV.validate("Destination", "B", "Please select the Destination."))
		return false;

	if (!objFV.validate("CsvFile", "B", "Please select the POs CSV File."))
		return false;

	if (!checkCsvFile(objFV.value("CsvFile")))
	{
		alert("Please select a valid CSV File.");

		return false;
	}


	return true;
}