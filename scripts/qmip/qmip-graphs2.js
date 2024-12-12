
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


function updateUnitsGraph( )
{
	var sUrl = "ajax/qmip/get-vendor-units-graph.php";
	var sParams = $("frmSearch").serialize( );


	$('Processing').show( );
	$("LineGraphDiv").hide( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_updateUnitsGraph });
}

function _updateUnitsGraph(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		window.scrollTo(0, 500);


		var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", ("Units" + Math.random( )), "100%", "440", "0", "1");

		objChart.setXMLData(sResponse.responseText);
		objChart.render("UnitsChart");


		$('Processing').hide( );
	}
}


function showLinesGraph(sParams)
{
	var sUrl = "ajax/qmip/get-lines-graph.php";


	$('Processing').show( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_showLinesGraph });
}

function _showLinesGraph(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		$("LineGraphDiv").show( );

		window.scrollTo(0, 950);


		var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", ("LineGraph" + Math.random( )), "920", "500", "0", "1");

		objChart.setXMLData(sResponse.responseText);
		objChart.render("LinesChart");


		$('Processing').hide( );
	}
}


function showLinesGraph2(sParams)
{
	var sUrl = "ajax/qmip/get-lines-graph.php";


	$('Processing').show( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_showLinesGraph2 });
}

function _showLinesGraph2(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		$("LineGraphDivFixed").show( );

		window.scrollTo(0, 950);


		var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", ("LineGraph" + Math.random( )), "920", "500", "0", "1");

		objChart.setXMLData(sResponse.responseText);
		objChart.render("LinesChartFixed");


		$('Processing').hide( );
	}
}


function getLines(sVendor, sLine)
{
	var objLines = $(sLine);

	for (var i = (objLines.options.length - 1); i >= 0; i --)
		objLines.options[i] = null;


	var iVendor = $F(sVendor);
	var sUrl    = "ajax/get-vendor-lines.php";
	var sParams = ("Id=" + iVendor + "&List=" + sLine);

	if (iVendor == "")
		return;

	$(sLine).disable( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getLines });
}

function _getLines(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			var sChild = sParams[1];

			for (var i = 2; i < sParams.length; i ++)
			{
				var sOption = sParams[i].split("||");

				$(sChild).options[(i - 2)] = new Option(sOption[1], sOption[0], false, false);

			}

			$(sChild).enable( );
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}


function updateLinesGraph( )
{
	var sUrl = "ajax/qmip/get-vendor-lines-graph.php";
	var sParams = $("frmSubSearch").serialize( );


	$('Processing').show( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_updateLinesGraph });
}

function _updateLinesGraph(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		window.scrollTo(0, 3000);


		var objChart = new FusionCharts("scripts/fusion-charts/charts/MSLine.swf", ("Activities" + Math.random( )), "100%", "500", "0", "1");

		objChart.setXMLData(sResponse.responseText);
		objChart.render("ActivityChart");


		$('Processing').hide( );
	}
}