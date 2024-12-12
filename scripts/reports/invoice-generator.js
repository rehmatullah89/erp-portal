
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

	if (!objFV.validate("Brand", "B", "Please select a Brand."))
		return false;

	if (!objFV.validate("InvoiceNo", "B", "Please enter the Invoice No."))
		return false;

        if (!objFV.validate("BilledFrom", "B", "Please select the Billed From."))
		return false;
            
	if (!objFV.validate("BilledTo", "B", "Please select the Billed To."))
		return false;

	if (!objFV.validate("PaymentTerms", "B", "Please select Payment Terms."))
		return false;

	if (!objFV.validate("Description", "B", "Please the Invoice Description."))
		return false;

	if (objFV.isChecked("NabilaMatrix") == false)
	{
		if (!objFV.validate("Signatures", "B", "Please select the Billing Person Signatures."))
			return false;

		if (objFV.value("Signatures") != "")
		{
			if (!checkImage(objFV.value("Signatures")))
			{
				alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

				objFV.focus("Signatures");
				objFV.select("Signatures");

				return false;
			}
		}
	}

	return true;
}