
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
	
	clearSubSearch( );
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

function clearSubSearch( )
{
	for (var i = 1; i <= $('Charts').value; i ++)
	{
		if ($('VendorBrand' + i))
		{
			$('VendorBrand' + i).value = "";
			$('ReportType' + i).value  = "";
			$('FromDate' + i).value    = "";
			$('ToDate' + i).value      = "";
		}
	}
	
	$('Charts').value = 0;
}

function duplicate( )
{
	$('Charts').value = (eval($('Charts').value) + 2);
}