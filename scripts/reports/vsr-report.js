
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
		
	if (!objFV.validate("Category", "B", "Please select a Category."))
		return false;
		
	if (objFV.selectedIndex("Vendor") == -1)
	{
		alert("Please select a Vendor.");

		objFV.focus("Vendor");
		
		return false;
	}	
	
	return true;
}

function getVendorsList( )
{
	for (var i = ($("Vendor").options.length - 1); i > 0; i --)
		$("Vendor").options[i] = null;	
	
	var iRegion   = $F("Region");
	var iCategory = $F("Category");
	
	if (iRegion == "" && iCategory == "")
		return;

	$("Vendor").disable( );

	var sUrl    = "ajax/reports/get-vendors.php";
	var sParams = ("Region=" + iRegion + "&Category=" + iCategory);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getVendorsList });
}

function _getVendorsList(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		
		if (sParams[0] == "OK")
		{
			for (var i = 1; i < sParams.length; i ++)
			{			
				var sOption = sParams[i].split("||");
			
				$('Vendor').options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);

			}

			$('Vendor').enable( );
		}

		else
			_showError(sParams[1]);
	}
	
	else
		_showError( );
}


function getSeasonsList( )
{
	clearList($("Seasons"));
	
	var sBrands = $F("Brands");
	
	if (sBrands == "")
		return;

	$("Seasons").disable( );
	

	var sUrl    = "ajax/reports/get-brands-seasons.php";
	var sParams = ("Brands=" + sBrands);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getSeasonsList });
}


function _getSeasonsList(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		
		if (sParams[0] == "OK")
		{
			for (var i = 1; i < sParams.length; i ++)
			{			
				var sOption = sParams[i].split("||");
			
				$("Seasons").options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);
			}

			$("Seasons").enable( );
		}
			
		else
			_showError(sParams[1]);
	}
	
	else
		_showError( );
}