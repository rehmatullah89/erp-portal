
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


		var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", ("LineGraph" + Math.random( )), "920", "500", "0", "1");

		objChart.setXMLData(sResponse.responseText);
		objChart.render("LinesChart");


		window.scrollTo(0, (parseInt($("LineGraphDiv").cumulativeOffset( )[1]) + 500));

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
		var objChart = new FusionCharts("scripts/fusion-charts/charts/MSLine.swf", ("Activities" + Math.random( )), "100%", "500", "0", "1");

		objChart.setXMLData(sResponse.responseText);
		objChart.render("ActivityChart");


		$("LineDetails").hide( );

		var iTop = (parseInt($("ActivityGraphDiv").cumulativeOffset( )[1]) + 500);

		window.scrollTo(0, iTop);


		$('Processing').hide( );
	}
}



function showDefectsGraph(sParams)
{
	var sUrl = "ajax/qmip/get-line-defects-graph.php";


	$('Processing').show( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_showDefectsGraph });
}


function _showDefectsGraph(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split("|-|");


		$("LineDetails").show( );

		var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", ("Defects" + Math.random( )), "100%", "400", "0", "1");

		objChart.setXMLData(sParams[0]);
		objChart.render("DefectsChart");


		$("AuditorsDiv").innerHTML = sParams[2];


		var iTop = (parseInt($("LineDetails").cumulativeOffset( )[1]) + 500);

		if ($("LineGraphDiv").style.display == "block")
			iTop += 500;

		window.scrollTo(0, iTop);


//		$('Processing').hide( );


		var sUrl = "ajax/qmip/get-line-areas-graph.php";

		new Ajax.Request(sUrl, { method:'post', parameters:sParams[1], onSuccess:_showAreasGraph });
	}
}


function showAreasGraph(sParams)
{
	var sUrl = "ajax/qmip/get-line-areas-graph.php";


	$('Processing').show( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_showAreasGraph });
}

function _showAreasGraph(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		$("LineDetails").show( );

		var objChart = new FusionCharts("scripts/fusion-charts/charts/Pie2D.swf", ("Areas" + Math.random( )), "100%", "400", "0", "1");

		objChart.setXMLData(sResponse.responseText);
		objChart.render("AreasChart");


		var iTop = (parseInt($("LineDetails").cumulativeOffset( )[1]) + 1000);

		window.scrollTo(0, iTop);


		$('Processing').hide( );
	}
}



function duplicate( )
{
	var objFV  = new FormValidator("frmSubSearch");
	var objFV2 = new FormValidator("frmDuplicate");


	var iLength = document.getElementById("Line2").length;
	var sLines  = "";

	for (var i = 0; i < iLength; i ++)
	{
		if (document.getElementById("Line2").options[i].selected == true)
		{
			if (sLines != "")
				sLines += ",";

			sLines = (sLines + document.getElementById("Line2").options[i].value);
		}
	}


	objFV2.setValue("Vendor2", objFV.value("Vendor"));
	objFV2.setValue("Brand2", objFV.value("Brand"));
	objFV2.setValue("Date2", objFV.value("Date"));
	objFV2.setValue("Report2", objFV.value("Report"));
	objFV2.setValue("AuditStage2", objFV.value("AuditStage"));
	objFV2.setValue("Lines2", sLines);
}