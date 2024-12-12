
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

function doSearch( )
{
	$('BtnSearch').disabled = true;
	
	showProcessing( );
}

function updateChart(iIndex)
{
	var sUrl    = "ajax/vsn/generate-chart.php"; 
	var sParams = ("Index=" + iIndex);
	               
	if ($('Forecast' + iIndex).checked == true)
	       sParams = (sParams + "&Forecast=" + $('Forecast' + iIndex).value);
	       
	if ($('Ogac' + iIndex).checked == true)
	       sParams = (sParams + "&Ogac=" + $('Ogac' + iIndex).value);
	       
	if ($('Shipments' + iIndex).checked == true)
	       sParams = (sParams + "&Shipments=" + $('Shipments' + iIndex).value);
	       
	if ($('Placements' + iIndex).checked == true)
	       sParams = (sParams + "&Placements=" + $('Placements' + iIndex).value);
	       
	if ($('Revised' + iIndex).checked == true)
	       sParams = (sParams + "&Revised=" + $('Revised' + iIndex).value);


	$('Processing').show( );
	
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_updateChart }); 
}


function _updateChart(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split("|-|");

		if (sParams[0] == "OK")
			$('Chart' + sParams[1]).src = sParams[2];
			
		else
			_showError( );
	
		
		$('Processing').hide( );
	}
}