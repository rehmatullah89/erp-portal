
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
	var objFV = new FormValidator("frmComments");
		
	if (objFV.value("PostId") == "")
	{
		alert("Invalid Blog Post to make comments.")
		
		return false;
	}
		
	if (!objFV.validate("Comments", "B", "Please enter your Comments."))
		return false;
		
	return true;
}


function validateDonationForm( )
{
	var objFV = new FormValidator("frmDonation");
	
	if (!objFV.validate("Amount", "B,N", "Please enter the Donation Amount."))
		return false;
		
	return confirm("Are you sure, You want to Donate Rs." + objFV.value("Amount") + " into MATRIX Sourcing Flood Relief Fund?");
}