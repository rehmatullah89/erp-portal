
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

function validateExcelForm( )
{
	var objFV = new FormValidator("frmExcel");
		
	if (!objFV.validate("XmlFile", "B", "Please select the XML File."))
		return false;
		
	if (!checkXmlFile(objFV.value("XmlFile")))
	{
		alert("Please select a valid XML File.");
		
		return false;
	}

	return true;
}



function validateXmlForm( )
{
	var objFV = new FormValidator("frmXml");
		
	if (!objFV.validate("XmlFile", "B", "Please select the XML File."))
		return false;
		
	if (!checkXmlFile(objFV.value("XmlFile")))
	{
		alert("Please select a valid XML File.");
		
		return false;
	}
	
	
	if (!objFV.validate("ExcelFile", "B", "Please select the Excel File."))
		return false;
		
	if (!checkExcelFile(objFV.value("ExcelFile")))
	{
		alert("Please select a valid Excel File.");
		
		return false;
	}

	return true;
}