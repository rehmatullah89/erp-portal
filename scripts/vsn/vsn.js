
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

function refineSearch(sMode)
{
	if (sMode == "Vendors")
	{
		if ($('DepartmentsBlock').style.display == "block")
			Effect.toggle('DepartmentsBlock', 'slide');

		if ($('BrandsBlock').style.display == "block")
			Effect.toggle('BrandsBlock', 'slide');

		if ($('VendorsBlock').style.display != "block")
			setTimeout( function( ) { Effect.toggle('VendorsBlock', 'slide'); }, 1000);

		setTimeout( function( ) { $('VendorsBlock').style.display = "block"; }, 2100);


		var sCheckboxes = $$("input.brands");

		sCheckboxes.each( function(objElement) { objElement.checked = false; } );
	}

	else if (sMode == "Brands")
	{
		if ($('DepartmentsBlock').style.display == "block")
			Effect.toggle('DepartmentsBlock', 'slide');

		if ($('VendorsBlock').style.display == "block")
			Effect.toggle('VendorsBlock', 'slide');

		if ($('BrandsBlock').style.display != "block")
			setTimeout( function( ) { Effect.toggle('BrandsBlock', 'slide'); }, 1000);

		setTimeout( function( ) { $('BrandsBlock').style.display = "block"; }, 2100);


		var sCheckboxes = $$("input.vendors");

		sCheckboxes.each( function(objElement) { objElement.checked = false; } );
	}

	else if (sMode == "VendorsBrands")
	{
		if ($('DepartmentsBlock').style.display == "block")
			Effect.toggle('DepartmentsBlock', 'slide');

		if ($('BrandsBlock').style.display != "block")
			Effect.toggle('BrandsBlock', 'slide');

		if ($('VendorsBlock').style.display != "block")
			Effect.toggle('VendorsBlock', 'slide');

		setTimeout( function( ) { $('VendorsBlock').style.display = "block"; }, 1100);
		setTimeout( function( ) { $('BrandsBlock').style.display = "block"; }, 1100);

		var sCheckboxes = $$("input.vendors");

		sCheckboxes.each( function(objElement) { objElement.checked = false; } );


		var sCheckboxes = $$("input.brands");

		sCheckboxes.each( function(objElement) { objElement.checked = false; } );
	}

	else if (sMode == "Departments")
	{
		if ($('BrandsBlock').style.display == "block")
			Effect.toggle('BrandsBlock', 'slide');

		if ($('VendorsBlock').style.display == "block")
			Effect.toggle('VendorsBlock', 'slide');

		if ($('DepartmentsBlock').style.display != "block")
			setTimeout( function( ) { Effect.toggle('DepartmentsBlock', 'slide'); }, 1000);

		setTimeout( function( ) { $('DepartmentsBlock').style.display = "block"; }, 2100);


		var sCheckboxes = $$("input.departments");

		sCheckboxes.each( function(objElement) { objElement.checked = false; } );
	}
}

function checkAll( )
{
	if ($('Mode').value == "Vendors" || $('Mode').value == "VendorsBrands")
	{
		var sCheckboxes = $$("input.vendors");

		sCheckboxes.each( function(objElement) { objElement.checked = true; } );
	}

	if ($('Mode').value == "Brands" || $('Mode').value == "VendorsBrands")
	{
		var sCheckboxes = $$("input.brands");

		sCheckboxes.each( function(objElement) { objElement.checked = true; } );
	}

	if ($('Mode').value == "Departments")
	{
		var sCheckboxes = $$("input.departments");

		sCheckboxes.each( function(objElement) { objElement.checked = true; } );
	}
}

function clearAll( )
{
	if ($('Mode').value == "Vendors" || $('Mode').value == "VendorsBrands")
	{
		var sCheckboxes = $$("input.vendors");

		sCheckboxes.each( function(objElement) { objElement.checked = false; } );
	}

	if ($('Mode').value == "Brands" || $('Mode').value == "VendorsBrands")
	{
		var sCheckboxes = $$("input.brands");

		sCheckboxes.each( function(objElement) { objElement.checked = false; } );
	}

	if ($('Mode').value == "Departments")
	{
		var sCheckboxes = $$("input.departments");

		sCheckboxes.each( function(objElement) { objElement.checked = false; } );
	}
}

function setYear(iYear)
{
	if (iYear == "")
	{
		$('FromDate').value = "";
		$('ToDate').value   = "";
	}

	else
	{
		$('FromDate').value = (iYear + "-01-01");
		$('ToDate').value   = (iYear + "-12-31");
	}
}


function showYearlyStats(sMode, sBrands, sVendors, sFromDate, sToDate, iRegion, sPoType)
{
	var sUrl    = "ajax/vsn/generate-chart.php";
	var sParams = ("Mode=" + sMode + "&Brands=" + sBrands + "&Vendors=" + sVendors + "&FromDate=" + sFromDate + "&ToDate=" + sToDate + "&Region=" + iRegion + "&PoType=" + sPoType);


	$('Processing').show( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_showYearlyStats });
}


function _showYearlyStats(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		$('MonthlyStats').hide( );
		$("YearlyStats").show( );


		var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumn3D.swf", "YearStats", "500", "250", "0", "1");

		objChart.setXMLData(sResponse.responseText);
		objChart.render("YearlyStatsChart");


		$('Processing').hide( );
	}
}


function showIndividualStats(sType, sMode, sBrands, sVendors, sFromDate, sToDate, iRegion, sPoType)
{
	var sUrl    = "ajax/vsn/generate-chart.php";
	var sParams = ("Type=" + sType + "&Mode=" + sMode + "&Brands=" + sBrands + "&Vendors=" + sVendors + "&FromDate=" + sFromDate + "&ToDate=" + sToDate + "&Region=" + iRegion + "&PoType=" + sPoType);


	$('Processing').show( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_showIndividualStats });
}


function _showIndividualStats(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		$('IndividualStats').show( );
		$("YearlyStats").hide( );


		var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "IndividualTypeStats", "500", "250", "0", "1");

		objChart.setXMLData(sResponse.responseText);
		objChart.render("IndividualStatsChart");


		$('Processing').hide( );
	}
}


function showMainGraph( )
{
	$('MonthlyStats').show( );
	$("YearlyStats").hide( );
}


function showYearlyGraph( )
{
	$('IndividualStats').hide( );
	$("YearlyStats").show( );
}



function showLatePos(sMode, sBrands, sVendors, iYear, iMonth, iRegion, sPoType)
{
	var sUrl    = "ajax/vsn/get-late-pos.php";
	var sParams = ("Mode=" + sMode + "&Brands=" + sBrands + "&Vendors=" + sVendors + "&Year=" + iYear + "&Month=" + iMonth + "&Region=" + iRegion + "&PoType=" + sPoType);


	$('Processing').show( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_showLatePos });
}


function _showLatePos(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		$('LatePos').show( );
		$("MonthlyOtp").hide( );

		$('Pos').innerHTML = sResponse.responseText;

		$('Processing').hide( );
	}
}

function showOtpGraph( )
{
	$('MonthlyOtp').show( );
	$("LatePos").hide( );
}