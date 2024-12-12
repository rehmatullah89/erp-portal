
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
	showProcessing( );
	
	$('BtnSearch').disabled = true;
}

function refineSearch(sMode)
{
	if (sMode == "Vendors")
	{
		Effect.toggle('BrandsBlock', 'slide');
	
		setTimeout( function( ) { Effect.toggle('VendorsBlock', 'slide'); }, 1000);
		

		var sCheckboxes = $$("input.brands");
	
		sCheckboxes.each( function(objElement) { objElement.checked = false; } );
	}
	
	else if (sMode == "Brands")
	{
		Effect.toggle('VendorsBlock', 'slide');
	
		setTimeout( function( ) { Effect.toggle('BrandsBlock', 'slide'); }, 1000);
		
		
		var sCheckboxes = $$("input.vendors");
	
		sCheckboxes.each( function(objElement) { objElement.checked = false; } );
	}
}

function checkAll( )
{
	var sClass = $('Mode').value.toLowerCase( );
	
	var sCheckboxes = $$("input." + sClass);
	
	sCheckboxes.each( function(objElement) { objElement.checked = true; } );
}

function clearAll( )
{
	var sClass = $('Mode').value.toLowerCase( );
	
	var sCheckboxes = $$("input." + sClass);
	
	sCheckboxes.each( function(objElement) { objElement.checked = false; } );
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

function doSubSearch(iIndex)
{
	showProcessing( );

	$('BtnGo' + iIndex).disabled = true;
	$('frmSearch' + iIndex).submit( );		
}

function exportReport(sType, iIndex)
{
	if ($('BtnExportActualVsr'))
		$('BtnExportActualVsr').disabled = true;
	
	if ($('BtnExportPlannedVsr'))
		$('BtnExportPlannedVsr').disabled = true;
	
	if ($('BtnExportComparativeVsr'))
		$('BtnExportComparativeVsr').disabled = true;

	$('Type' + iIndex).value = sType;

	
//	if (document.getElementById('FileFormatPdf').checked == true)
//		$('Format' + iIndex).value = "pdf";
	
//	else
		$('Format' + iIndex).value = "xlsx";

	
	$('frmReport' + iIndex).submit( );
	
	setTimeout( function( ) {
					if ($('BtnExportActualVsr'))
						$('BtnExportActualVsr').disabled = false;
					
					if ($('BtnExportPlannedVsr'))
						$('BtnExportPlannedVsr').disabled = false;
					
					if ($('BtnExportComparativeVsr'))
						$('BtnExportComparativeVsr').disabled = false;
				},
				
				10000);
}

function checkAllPos(iIndex)
{
	var sCheckboxes = $$("input.po" + iIndex);
	var bFlag       = false;
	
	sCheckboxes.each( function(objElement)
			  {
			  	if (objElement.checked == false)
			  	{
			  		bFlag = true;
			  	}
			  }
			);
			
	sCheckboxes.each( function(objElement) { objElement.checked = bFlag; } );
}

function saveSearch( )
{
	if ($('Title').value == "")
	{
		alert("Please enter the Search Title to Save your Search.");
		
		$('Title').focus( );
		
		return;
	}


	$('Processing').show( );
		
	var sUrl    = "ajax/vsr/save-vsr-search.php"; 
	var sParams = $('frmSave').serialize( );

	var objForm = $("frmSave"); 
	objForm.disable( );
	
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_saveSearch });
}

function _saveSearch(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		
		if (sParams[0] == "OK")
			alert(sParams[1]);
			
		else
			_showError(sParams[1]);
			
		$('Processing').hide( );

		var objForm = $("frmSave");
		objForm.enable( );
	}
	
	else
		_showError( );
}