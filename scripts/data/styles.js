
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

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Category", "B", "Please select the Style Category."))
		return false;

	if (!objFV.validate("Style", "B", "Please enter the Style No."))
		return false;

	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;

	if (!objFV.validate("SubBrand", "B", "Please select the Sub-Brand."))
		return false;

	if (!objFV.validate("Season", "B", "Please select the Season."))
		return false;

	if (!objFV.validate("SubSeason", "B", "Please select the Sub-Season."))
		return false;

	if (objFV.value("SpecsFile") != "")
	{
		if (!checkPdfFile(objFV.value("SpecsFile")))
		{
			alert("Invalid File Format. Please select a valid PDF File.");

			objFV.focus("SpecsFile");
			objFV.select("SpecsFile");

			return false;
		}
	}

	if (objFV.value("SketchFile") != "")
	{
		if (!checkImage(objFV.value("SketchFile")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("SketchFile");
			objFV.select("SketchFile");

			return false;
		}
	}

	return true;
}

function validateImportForm()
{
    var objFV = new FormValidator("frmImport");

        if (!objFV.validate("Category", "B", "Please select the Style Category."))
		return false;
            
	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;
 
	if (!objFV.validate("SubBrand", "B", "Please select the Sub-Brand."))
		return false;
            
        if (!objFV.validate("SamplingCategory", "B", "Please select the a sampling Category."))
		return false;

	if (!objFV.validate("Season", "B", "Please select the Season."))
		return false;
            
        if (!objFV.validate("SubSeason", "B", "Please select the Sub-Season."))
		return false;    
            
        if (!objFV.validate("CsvFile", "B", "Please select a CSV File."))
		return false;    

	if (objFV.value("CsvFile") != "")
	{
		if (!checkCsvFile(objFV.value("CsvFile")))
		{
			alert("Invalid File Format. Please select a valid CSV File.");

			objFV.focus("CsvFile");
			objFV.select("CsvFile");

			return false;
		}
	}

	return true;
}

function exportReport( )
{
	$('BtnExport').disabled = true;

	document.location = $('ExportUrl').value;

	setTimeout( function( ) { $('BtnExport').disabled = false; }, 10000);
}


function getStylesList(sParent, sList)
{
	clearList($("CarryOver"));

	var iBrandId = $F("SubBrand");

	if (iBrandId == "")
		return;

	$("CarryOver").disable( );


	var sUrl    = "ajax/get-styles.php";
	var sParams = ("Brand=" + iBrandId + "&List=" + "CarryOver");

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getStylesList });
}


function _getStylesList(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			var sList = sParams[1];

			for (var i = 2; i < sParams.length; i ++)
			{
				var sOption = sParams[i].split("||");

				$(sList).options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);
			}

			$(sList).enable( );
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}