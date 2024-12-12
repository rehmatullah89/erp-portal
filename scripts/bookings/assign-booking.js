
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

        if (!objFV.validate("Auditor", "B", "Please select the Auditor."))
		return false;
     
        if (!objFV.validate("OrderNo", "B", "Please enter/select the Order No."))
		return false;
            
        if (!objFV.validate("Sizes", "B", "Please select the Sizes."))
                return false;
        
        if (!objFV.validate("SampleSize", "B", "Please select the Sample Size."))
                        return false;
        
         if (!objFV.validate("AuditDate", "B", "Please select the Audit Date."))
		return false;
            
        if (!objFV.validate("StartHour", "B", "Please select the Start Time (Hour)."))
		return false;

	if (!objFV.validate("StartMinutes", "B", "Please select the Start Time (Minutes)."))
		return false;
        
        return true;
}