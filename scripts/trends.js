
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


function updateChart(sFromDate, sToDate)
{
	var sUrl    = "ajax/trends.php";
	var sParams = ("FromDate=" + sFromDate + "&ToDate=" + sToDate);


	$('Processing').show( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_showChart });
}

function _showChart(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var objChart = new FusionCharts("scripts/fusion-charts/charts/ZoomLine.swf", "Cotton", "100%", "500", "0", "1");

		objChart.setXMLData(sResponse.responseText);
		objChart.render("CottonTrends");


		$('Processing').hide( );
	}
}