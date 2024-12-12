
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

function doSearch(sTab)
{
	$('Tab').value = sTab;

	$('BtnSearch').disabled = true;
	$('frmSearch').submit( );
}

function rowClicked(h,d)
{

    $(d).toggle(10000);
    $(h).toggle(10000);
		
	//setTimeout( function( ) { $('Handle' + iIndex).hide( ); }, 900);
}

function hideSummary(iIndex)
{
	Effect.toggle(('Summary' + iIndex), 'slide');
	
	setTimeout( function( ) { $('Handle' + iIndex).show( ); }, 900);
}


function getPoList( vesseldate)
{
	/*
	
	clearList($("Season"));

	var sBrands = "";

	$$("input.brand").each(function(objCheckbox)
	{
		if (objCheckbox.checked == true)
		{
			if (sBrands == "")
				sBrands = objCheckbox.value;

			else
				sBrands += ("," + objCheckbox.value);
		}
	});

	if (sBrands == "")
		return;

	$("Season").disable( );
	
	*/
//alert($("Brand").value);
	$('Processing').show( );
	var iBrand =  $("Brand").value;
	var iSeason =  $("Season").value;
	var iVendor = $("Vendor").value;
	var iRegion = $("Region").value;
	
	var sUrl    = "ajax/vsr/po-fetch.php";
	var sParams = ("VesselDate=" + vesseldate+"&User=312"+"&Brand="+iBrand+"&Vendor="+iVendor+"&Season="+iSeason+"&Region="+iRegion);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getSeasonsList });
}



function _getSeasonsList(sResponse)
{	
	
	
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{	
		//alert(sResponse.responseText);
		$('hiddenDiv').update(sResponse.responseText);
		$('Processing').hide( );
		
	}

	else
		_showError( );
}

/*

new Tip('hover0', { 
  ajax: {
    url: 'ajax/vsr/new-vsr-tooltip.php',
    options: {
    parameters:'VesselDate='+document.getElementById('hover0').title+'&User=312',
    method:'post',
    onSuccess: function(Response){
   
  		}
      
    }
  }
});
new Tip('hover1', { 
  ajax: {
    url: 'ajax/vsr/new-vsr-tooltip.php',
    options: {
    parameters:'VesselDate='+document.getElementById('hover1').title+'&User=312',
    method:'post',
    onSuccess: function(Response){
   
  		}
      
    }
  }
});
new Tip('hover2', { 
  ajax: {
    url: 'ajax/vsr/new-vsr-tooltip.php',
    options: {
    parameters:'VesselDate='+document.getElementById('hover2').title+'&User=312',
    method:'post',
    onSuccess: function(Response){
    
  		}
      
    }
  }
});

new Tip('hover3',  { 
  ajax: {
    url: 'ajax/vsr/new-vsr-tooltip.php',
    options: {
    parameters:'VesselDate='+document.getElementById('hover3').title+'&User=312',
    method:'post',
    onSuccess: function(Response){
    
  		}
      
    }
  }
});

*/

function fetchDepartment(v){

	$('Processing').show( );
	var iBrand =  $("Brand").value;
	var iSeason =  $("Season").value;
	var iVendor = $("Vendor").value;
	var iRegion = $("Region").value;
	
	$('hiddenDiv2').update('');
	
	var res = v.split("?"); 
		
	var sUrl    = res[0];
		//var sParams = ("VesselDate=" + vesseldate+"&User=312"+"&Brand="+iBrand+"&Vendor="+iVendor+"&Season="+iSeason+"&Region="+iRegion);
	var sParams = res[1];
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getDepartList });
}

function _getDepartList(sResponse)
{	
	
	
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{	
		//alert(sResponse.responseText);
		$('hiddenDiv').update(sResponse.responseText);
		$('Processing').hide( );
		
	}

	else
		_showError( );
}

function fetchStyles(url){
	
	window.location=url;
	
}

function fetchBrand(v){

	$('Processing').show( );
	var iBrand =  $("Brand").value;
	var iSeason =  $("Season").value;
	var iVendor = $("Vendor").value;
	var iRegion = $("Region").value;
	
	var sUrl    = v;
	//var sParams = ("VesselDate=" + vesseldate+"&User=312"+"&Brand="+iBrand+"&Vendor="+iVendor+"&Season="+iSeason+"&Region="+iRegion);
	var sParams = '';
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getBrandList });

}

function _getBrandList(sResponse)
{	
	
	
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{	
		//alert(sResponse.responseText);
		$('hiddenDiv2').update(sResponse.responseText);
		$('Processing').hide( );
		
	}

	else
		_showError( );
}


function fetchGates(v){

	$('Processing').show( );
	var iBrand =  $("Brand").value;
	var iSeason =  $("Season").value;
	var iVendor = $("Vendor").value;
	var iRegion = $("Region").value;
	
	var sUrl   = v;
	//var sParams = ("VesselDate=" + vesseldate+"&User=312"+"&Brand="+iBrand+"&Vendor="+iVendor+"&Season="+iSeason+"&Region="+iRegion);
	var sParams = '';
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getGatesList });

}

function _getGatesList(sResponse)
{	
	
	
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{	
		//alert(sResponse.responseText);
		$('hiddenDiv3').update(sResponse.responseText);
		$('Processing').hide( );
		
	}

	else
		_showError( );
}

