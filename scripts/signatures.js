
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
	var objFV = new FormValidator("frmSignatures");
	
	if (!objFV.validate("Name", "B", "Please enter the Employee Full Name."))
		return false;
		
	if (!objFV.validate("Designation", "B", "Please select the Employee Designation."))
		return false;
		
	if (!objFV.validate("Office", "B", "Please select the Employee Office."))
		return false;
		
	if (!objFV.validate("Country", "B", "Please select the Employee Country."))
		return false;		
		
	if (!objFV.validate("Email", "B,E", "Please enter the Employee valid Email Address."))
		return false;

	if (!objFV.validate("Phone", "B", "Please enter the Phone Number."))
		return false;
		
	return true;
}

function showEmpInfo(iUserId)
{
	if (iUserId == "")
		return;
		
	$('Processing').show( );
	
	var sUrl    = "ajax/get-employee-info.php"; 
	var sParams = ("UserId=" + iUserId);
	
	$("frmSignatures").disable( );
	
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_showEmpInfo });
}

function _showEmpInfo(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		
		if (sParams[0] == "OK")
		{
			$('Name').value        = sParams[1];
			$('Designation').value = sParams[2];
			$('Country').value     = sParams[3];
			$('Office').value      = sParams[4];
			$('Email').value       = sParams[5];
			$('Cell').value        = sParams[6];
			$('Phone').value       = sParams[7];			
			$('Ext').value         = sParams[8];
			$('Fax').value         = sParams[9];
		}
			
		else
			_showError(sParams[1]);
			
		$('Processing').hide( );
		
		$("frmSignatures").enable( );
	}
	
	else
		_showError( );
}