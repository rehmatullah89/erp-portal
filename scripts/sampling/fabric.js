
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

var objRms;

function getRms(iBrandId, sRmsId)
{
	objRms = $(sRmsId);

	clearList(objRms);

	if (iBrandId == "")
		return;
	
	var sUrl    = "ajax/sampling/get-rms-list.php"; 
	var sParams = ("BrandId=" + iBrandId);
	
	$('Processing').show( );
	$('frmData').disable( );
	
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getRms });
}

function _getRms(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		
		if (sParams[0] == "OK")
		{
			for (var i = 1; i < sParams.length; i ++)
			{
				var sOption = sParams[i].split("||");

				objRms.options[i] = new Option(sOption[1], sOption[0], false, false);
			}
		}
			
		else
			_showError(sParams[1]);
			
		$('Processing').hide( );
		$("frmData").enable( );
	}
	
	else
		_showError( );
}

function checkDelay(sIndex)
{
	var sReadyDate    = $("BuyReadyDate" + sIndex).value;
	var sApprovalDate = $("ApprovalDate" + sIndex).value;

	if (sReadyDate != "" && sApprovalDate != "")
	{
		var sReadDateFields     = sReadyDate.split("-");
		var sApprovalDateFields = sApprovalDate.split("-");
		
		var objReadyDate    = new Date(sReadDateFields[0], sReadDateFields[1], sReadDateFields[2]);
		var objApprovalDate = new Date(sApprovalDateFields[0], sApprovalDateFields[1], sApprovalDateFields[2]);
		
		if (objApprovalDate >= objReadyDate)
			$("Reason" + sIndex).style.display = "block";
			
		else
			$("Reason" + sIndex).style.display = "none";
	}
}

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;
		
	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;
		
	if (!objFV.validate("Rms", "B", "Please select the RMS #"))
		return false;
		
	if (!objFV.validate("Category", "B", "Please select the Category."))
		return false;

	if (!objFV.validate("Price", "B", "Please enter the Fabric Price."))
		return false;
		
	if (!objFV.validate("Width", "B", "Please enter the Fabric Width."))
		return false;
		
	if (!objFV.validate("BuyReadyDate", "B", "Please select the Buy Ready Date."))
		return false;
	
	if ($("Reason").style.display == "block")
	{
		if (!objFV.validate("Reason", "B", "Please select the Delay Reason."))
			return false;

		if (!objFV.validate("Comments", "B", "Please enter the Delay Comments."))
			return false;
	}

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);
	
	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;
		
	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;
		
	if (!objFV.validate("Rms", "B", "Please select the RMS #"))
		return false;
		
	if (!objFV.validate("Category", "B", "Please select the Category."))
		return false;

	if (!objFV.validate("Price", "B", "Please enter the Fabric Price."))
		return false;
		
	if (!objFV.validate("Width", "B", "Please enter the Fabric Width."))
		return false;
		
	if (!objFV.validate("BuyReadyDate", "B", "Please select the Buy Ready Date."))
		return false;
	
	if ($("Reason" + iId).style.display == "block")
	{
		if (!objFV.validate("Reason", "B", "Please select the Delay Reason."))
			return false;

		if (!objFV.validate("Comments", "B", "Please enter the Delay Comments."))
			return false;
	}

	return true;
}