
/*********************************************************************************************\
***********************************************************************************************
**                                                                                           **
**  Triple Tree Customer Portal                                                              **
**  Version 2.0                                                                              **
**                                                                                           **
**  http://portal.3-tree.com                                                                 **
**                                                                                           **
**  Copyright 2015 (C) Triple Tree                                                           **
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

function showTypeGraph(iType)
{
	$('Processing').show( );

	var sParams = $('frmSearch').serialize( );

	sParams += ("&Type=" + iType);

	new Ajax.Request("ajax/quonda/get-vendor-type-graph.php", { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_TypeGraph });
}

function _TypeGraph(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "DefectTypeCodes", "100%", "350", "0", "1");

		objChart.setXMLData(sResponse.responseText);
		objChart.render("DefectCodeChart");

		$('DefectType').show( );
		$('Processing').hide( );
	}

	else
		_showError( );
}



function showCodeGraph(iType, iCode)
{
	$('Processing').show( );

	var sParams = $('frmSearch').serialize( );

	sParams += ("&Type=" + iType);
	sParams += ("&Code=" + iCode);

	new Ajax.Request("ajax/quonda/get-vendor-code-graph.php", { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_CodeGraph });
}

function _CodeGraph(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "DefectCodeAreas", "100%", "350", "0", "1");

		objChart.setXMLData(sResponse.responseText);
		objChart.render("DefectAreaChart");

		$('DefectCode').show( );
		$('Processing').hide( );
	}

	else
		_showError( );
}