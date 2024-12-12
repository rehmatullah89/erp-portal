
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

	if (!objFV.validate("Month", "B", "Please select the Month."))
		return false;
		
	if (!objFV.validate("Year", "B", "Please select the Year."))
		return false;
		
	if (!objFV.validate("Region", "B", "Please select the Region."))
		return false;
		
	if (objFV.value("Vendor") == "" && objFV.value("Brand") == "")
	{
		alert("Please select Vendor or Brand");
		
		objFV.focus("Vendor");

		return false;
	}
		
	if (!objFV.validate("Season", "B", "Please select the Season."))
		return false;
		
	if (!objFV.validate("Quantity", "B,N", "Please enter the Quantity."))
		return false;

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Month", "B", "Please select the Month."))
		return false;
		
	if (!objFV.validate("Year", "B", "Please select the Year."))
		return false;
		
	if (!objFV.validate("Region", "B", "Please select the Region."))
		return false;
		
	if (objFV.value("Vendor") == "" && objFV.value("Brand") == "")
	{
		alert("Please select Vendor or Brand");
		
		objFV.focus("Vendor");

		return false;
	}
		
	if (!objFV.validate("Season", "B", "Please select the Season."))
		return false;
		
	if (!objFV.validate("Quantity", "B,N", "Please enter the Quantity."))
		return false;

	$('Processing').show( );
	
	var sUrl    = "ajax/data/update-revised-forecast.php"; 
	var sParams = $('frmData' + iId).serialize( );
	
	var objForm = $("frmData" + iId); 
	objForm.disable( );
	
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_updateData });
}

function _updateData(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		var iId     = sParams[1];
		
		if (sParams[0] == "OK")
		{
			$('Msg' + iId).innerHTML = sParams[2];
			$('Msg' + iId).show( );
			$('Edit' + iId).hide( );

			setTimeout( 
				    function( )
				    { 			
					new Effect.SlideUp("Msg" + iId);

					$('Month' + iId).innerHTML    = sParams[3];
					$('Year' + iId).innerHTML     = sParams[4];
					$('Category' + iId).innerHTML = sParams[5];
					$('Vendor' + iId).innerHTML   = sParams[6];
					$('Brand' + iId).innerHTML    = sParams[7];
					$('Season' + iId).innerHTML   = sParams[8];
					$('Style' + iId).innerHTML    = sParams[9];
					$('Quantity' + iId).innerHTML = sParams[10];
				    },				    
				    
				    2000
				  );
		}
		
		else if (sParams[0] == "INFO")
			_showError(sParams[2]);
			
		else
			_showError(sParams[1]);
			
		$('Processing').hide( );
		
		var objForm = $("frmData" + iId); 
		objForm.enable( );
	}
	
	else
		_showError( );
}

function getStylesList(sBrand, sSeason, sList)
{
	clearList($(sList));
	
	var iBrand  = $F(sBrand);
	var iSeason = $F(sSeason);

	if (iBrand == "" || iSeason == "")
		return;

	$(sList).disable( );

	var sParams = ("Brand=" + iBrand + "&Season=" + iSeason + "&List=" + sList);

	new Ajax.Request("ajax/get-styles.php", { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getStylesList });
}

function _getStylesList(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		
		if (sParams[0] == "OK")
		{
			var sList = sParams[1];

			for (var i = 2; i < sParams.length; i ++)
			{			
				var sOption = sParams[i].split("||");
			
				$(sList).options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);

			}

			$(sList).enable( );
		}
			
		else
			_showError(sParams[1]);
	}
	
	else
		_showError( );
}