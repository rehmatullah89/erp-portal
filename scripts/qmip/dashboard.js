
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

function resetDates( )
{
	if (($("Auditor") && $("Auditor").value != "") || $("Brand").value != "" || $("Vendor").value != "")
	{
		$("StartDate").innerHTML = "From";
		$("EndDate").style.display = "block";
	}

	else
	{
		$("StartDate").innerHTML = "Audits";
		$("EndDate").style.display = "none";

		$("FromDate").value = $("ToDate").value;
	}
}


function approveAudit(sAuditCode)
{
	$('Processing').show( );


	var sUrl = "ajax/quonda/update-audit-code-status.php";

	new Ajax.Request(sUrl, { method:'post', parameters:("AuditCode=" + sAuditCode), onFailure:_showError, onSuccess:_approveAudit });


	return false;
}


function _approveAudit(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		$('Processing').hide( );


		var sParams = sResponse.responseText.split('|-|');
		var iId     = sParams[1];

		if (sParams[0] == "OK")
		{
			$("Status" + iId).innerHTML  = "";
			$("Approve" + iId).innerHTML = "&nbsp;";
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}