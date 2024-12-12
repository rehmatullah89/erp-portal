
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

function checkPoType( )
{
	var iBrandId = $F("Brand");

	if (iBrandId == 32)
		$("PoType").show( );

	else
	{
		$("PoType").hide( );
		$("OrderType").value = "";
	}
}


function getStylesList(sBrand, sStyleNo, sAdditionalStyles)
{
	clearList($(sStyleNo));
	clearList($(sAdditionalStyles));

	var iBrandId = $F(sBrand);

	if (iBrandId == "")
		return;

	$(sStyleNo).disable( );
	$(sAdditionalStyles).disable( );

	var sUrl    = "ajax/data/get-styles.php";
	var sParams = ("Brand=" + iBrandId + "&StyleNo=" + sStyleNo + "&AdditionalStyles=" + sAdditionalStyles);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getStylesList });
}


function _getStylesList(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			var sStyleNo          = sParams[1];
			var sAdditionalStyles = sParams[2];

			for (var i = 3; i < sParams.length; i ++)
			{
				var sOption = sParams[i].split("||");

				$(sStyleNo).options[(i - 2)]          = new Option(sOption[1], sOption[0], false, false);
				$(sAdditionalStyles).options[(i - 3)] = new Option(sOption[1], sOption[0], false, false);
			}

			$(sStyleNo).enable( );
			$(sAdditionalStyles).enable( );
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}


function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;

	if (!objFV.validate("OrderNo", "B", "Please enter the Order No."))
		return false;

	if (!objFV.validate("StyleNo", "B", "Please enter the Style No."))
		return false;

	var sCheckboxes = $$("input.sizes");
	var bFlag       = false;

	for (var i = 0; i < sCheckboxes.length; i ++)
	{
		if (sCheckboxes[i].checked == true)
		{
			bFlag = true;
			break;
		}
	}

	if (bFlag == false)
	{
		alert("Please select the Size Specifications of the Purchase Order.");

		return false;
	}

	checkPurchaseOrder( );

	return false;
}


function checkPurchaseOrder( )
{
	var sUrl    = "ajax/data/check-purchase-order.php";
	var sParams = ("OrderNo=" + $F('OrderNo') + "&OrderStatus=" + $F('OrderStatus') + "&Vendor=" + $F('Vendor') + "&Style=" + $F('StyleNo'));

	$('Processing').show( );
	$("frmData").disable( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onSuccess:_checkPurchaseOrder });
}


function _checkPurchaseOrder(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			$("frmData").enable( );
			$("frmData").submit( );
		}

		else if (sParams[0] == "EXISTS")
		{
			if (confirm("The specified PO # " + $F('OrderNo') + " (" + sParams[2] + " Pcs) already Exists in the System. Do you want to enter the current PO as " + sParams[1]) == true)
			{
				$('OrderNo').value = sParams[1];
				$("frmData").enable( );
				$("frmData").submit( );
			}
		}

		else if (sParams[0] == "ERROR")
			_showError(sParams[1]);

		$('Processing').hide( );
		$("frmData").enable( );
	}

	else
		_showError( );
}


document.observe('dom:loaded', function( )
{
	if ($("frmData"))
	{
		var objFV = new FormValidator("frmData");

		objFV.focus("Vendor");
	}
});