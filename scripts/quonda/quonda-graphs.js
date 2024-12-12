
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

function clearDates( )
{
	$('FromDate').value = "";
	$('ToDate').value   = "";
}

function setDates(sDates)
{
	sDates = sDates.split(":");

	$('FromDate').value = sDates[0];
	$('ToDate').value   = sDates[1];
}


function showInlinesGraph(sParams)
{
	var sUrl = "ajax/quonda/get-inlines-graph.php";


	$('Processing').show( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_showInlinesGraph });
}

function _showInlinesGraph(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		$("InlineGraphDiv").show( );

		window.scrollTo(0, 950);


		var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", ("InlineGraph" + Math.random( )), "920", "500", "0", "1");

		objChart.setXMLData(sResponse.responseText);
		objChart.render("InlinesChart");


		$('Processing').hide( );
	}
}