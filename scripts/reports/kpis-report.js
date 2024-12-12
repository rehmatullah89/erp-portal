
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
 
function checkDoubleSubmission( )
{
	$('BtnExport').disabled = true;
	
	setTimeout( function( ) { $('BtnExport').disabled = false; }, 10000);
}


function validateForm( )
{
	var objFV = new FormValidator("frmSearch");

	if (!objFV.validate("Region", "B", "Please select a Region."))
		return false;
		
	if (objFV.selectedIndex("Brands") == -1)
	{
		alert("Please select a Brand.");

		objFV.focus("Brands");
		
		return false;
	}	
	
	return true;
}