
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

	if (!objFV.validate("Category", "B", "Please select the Category."))
		return false;
		
	if (!objFV.validate("BtxDivision", "B", "Please select the BTX Division Check."))
		return false;
		
	if (!objFV.validate("VsrFile", "B", "Please select the VSR File."))
		return false;
		
	if (!checkExcelFile(objFV.value("VsrFile")))
	{
		alert("Please select a valid MS Excel File.");
		
		return false;
	}

	return true;
}