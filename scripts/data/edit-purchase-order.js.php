<?
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

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
?>

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
	clearList($(sAdditionalStyles));

	var iBrandId = $F(sBrand);

	if (iBrandId == "")
		return;

	$("PoStyles").hide( );
	$('StylesList').show( );


	$(sAdditionalStyles).disable( );

	var sUrl    = "ajax/data/get-styles.php";
	var sParams = ("Brand=" + iBrandId + "&StyleNo=0&AdditionalStyles=" + sAdditionalStyles);

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

				$(sAdditionalStyles).options[(i - 3)] = new Option(sOption[1], sOption[0], false, false);
			}

			$(sAdditionalStyles).enable( );
			updateStyles( );
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}

function updateStyles( )
{
	var iChecked = $('Styles').selectedIndex;

	var iCount   = $('Styles').length;
	var iRecords = $('ColorsCount').value;

	for (var i = 0; i < iRecords; i ++)
	{
		var iSelected = $('Style' + i).value;


		clearList($("Style" + i));

		for (var j = 0, k = 0; j < iCount; j ++)
		{
			if ($('Styles').options[j].selected != false)
			{
				$('Style' + i).options[k] = new Option($('Styles').options[j].text, $('Styles').options[j].value, false, false);

				k ++;
			}
		}

		$('Style' + i).value = iSelected;
	}
}

function showSizeList( )
{
	Effect.toggle('SizesList', 'slide');

	setTimeout(function( ) { Effect.ScrollTo('SizeRequirements'); }, 1000);
}


function addColor( )
{
        var iBrandId = $('Brand').value;
	var iRows    = parseInt($('ColorsCount').value);
	var iColumns = parseInt($('SizesCount').value);

<?
	$Id    = IO::intValue('Id');
	$Price = IO::floatValue("Price");

	$sSQL = "SELECT styles, sizes, destinations, shipping_dates FROM tbl_po WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$Styles         = $objDb->getField(0, "styles");
		$Sizes          = $objDb->getField(0, "sizes");
		$Destination    = $objDb->getField(0, "destinations");
		$DateOfShipment = $objDb->getField(0, "shipping_dates");

		if (@strpos($Destination, ",") !== FALSE)
		{
			$Destination = @explode(",", $Destination);
			$Destination = $Destination[0];
		}

		if (@strpos($DateOfShipment, ",") !== FALSE)
		{
			$DateOfShipment = @explode(",", $DateOfShipment);
			$DateOfShipment = $DateOfShipment[0];
		}
	}


	$SizesList  = @explode(",", $Sizes);

	if (count($SizesList) > 0)
	{
		$sPoDestinations = array( );
		$sPoSizes        = array( );
		$iSubTotal       = 0;
		$iTotal          = 0;


		$sSQL = "SELECT id, destination FROM tbl_destinations WHERE brand_id IN (SELECT brand_id FROM tbl_styles WHERE id IN ($Styles)) ORDER BY destination";
		$objDb->query($sSQL);

		$iPoDestinationsCount = $objDb->getCount( );

		for ($i = 0; $i < $iPoDestinationsCount; $i ++)
		{
			$sPoDestinations[$i][0] = $objDb->getField($i, 0);
			$sPoDestinations[$i][1] = $objDb->getField($i, 1);
		}


		$sSQL = "SELECT id, size FROM tbl_sizes WHERE id IN ($Sizes) ORDER BY position";
		$objDb->query($sSQL);

		$iPoSizesCount = $objDb->getCount( );

		for ($i = 0; $i < $iPoSizesCount; $i ++)
		{
			$sPoSizes[$i][0] = $objDb->getField($i, 0);
			$sPoSizes[$i][1] = $objDb->getField($i, 1);
		}
?>
	var sHtml =  "<div id=\"ColorRecord" + iRows + "\" style=\"margin:0px 4px 0px 4px; display:none;\">";
        sHtml += " <div>";
        sHtml += "  <input type=\"hidden\" name=\"ColorId" + iRows + "\" id=\"ColorId" + iRows + "\" value=\"\" />";

        sHtml += "  <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
        sHtml += "		<tr class=\"poRowHeader\">";
        sHtml += "		  <td width=\"230\">&nbsp; <b>Color</b></td>";
        
        if(iBrandId == 588 || iBrandId == 597){
            sHtml += "		  <td width=\"110\"><b>Commission#</b></td>";
        }
        else{
            sHtml += "		  <td width=\"110\"><b>Line</b></td>";
        }        
        sHtml += "		  <td width=\"90\"><b>Price (" + $("Currency").value + ")</b></td>";
        sHtml += "		  <td width=\"190\"><b>Style</b></td>";
        sHtml += "		  <td width=\"140\"><b>Date of Shipment</b></td>";
        sHtml += "		  <td><b>Destination</b></td>";
        sHtml += "		</tr>";

        sHtml += "		<tr class=\"poRowColor\">";
        sHtml += "		  <td>";
        sHtml += "		    <div>";
        sHtml += "		      <input type=\"text\" id=\"Color" + iRows + "\" name=\"Color" + iRows + "\" value=\"\" size=\"32\" maxlength=\"250\" autocomplete=\"off\" class=\"textbox\" />";
        sHtml += "			    <div id=\"Choices_" + iRows + "\" class=\"autocomplete\" style=\"display:none;\"></div>";

        sHtml += "			    <" + "script type=\"text/" + "javascript\">";
        sHtml += "			    <!--";
        sHtml += "				   new Ajax.Autocompleter(\"Color" + iRows + "\", \"Choices_" + iRows + "\", \"ajax/get-purchase-order-colors.php\", { paramName:\"Keywords\", minChars:3 } );";
        sHtml += "			    -->";
        sHtml += "			    </" + "script" + ">";
        sHtml += "			  </div>";
        sHtml += "		  </td>";

        sHtml += "		  <td><input type=\"text\" id=\"Line" + iRows + "\" name=\"Line" + iRows + "\" value=\"\" size=\"12\" maxlength=\"50\" class=\"textbox\" /></td>";
        sHtml += "		  <td><input type=\"text\" id=\"Price" + iRows + "\" name=\"Price" + iRows + "\" value=\"<?= @round($Price, 3) ?>\" size=\"8\" maxlength=\"10\" class=\"textbox\" /></td>";

        sHtml += "		  <td>";
        sHtml += "		    <select id=\"Style" + iRows + "\" name=\"Style" + iRows + "\">";
        sHtml += "		    </select>";
        sHtml += "		  </td>";

        sHtml += "		  <td>";

        sHtml += "			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"116\">";
        sHtml += "			    <tr>";
        sHtml += "				<td width=\"82\"><input type=\"text\" name=\"DateOfShipment" + iRows + "\" id=\"DateOfShipment" + iRows + "\" value=\"<?= $DateOfShipment ?>\" readonly class=\"textbox\" style=\"width:70px;\" onclick=\"displayCalendar($('DateOfShipment" + iRows + "'), 'yyyy-mm-dd', this);\" /></td>";
        sHtml += "				<td width=\"34\"><img src=\"images/icons/calendar.gif\" width=\"34\" height=\"22\" alt=\"Pick Date\" title=\"Pick Date\" style=\"cursor:pointer;\"  onclick=\"displayCalendar($('DateOfShipment" + iRows + "'), 'yyyy-mm-dd', this);\" /></td>";
        sHtml += "			    </tr>";
        sHtml += "			</table>";

        sHtml += "		  </td>";

        sHtml += "		  <td>";
        sHtml += "		    <select id=\"Destination" + iRows + "\" name=\"Destination" + iRows + "\">";
        sHtml += "		      <option value=\"\"></option>";
<?
		for ($i = 0; $i < $iPoDestinationsCount; $i ++)
		{
?>
        sHtml += "		      <option value=\"<?= $sPoDestinations[$i][0] ?>\" <?= (($Destination == $sPoDestinations[$i][0]) ? 'selected' : '') ?>><?= $sPoDestinations[$i][1] ?></option>";
<?
		}
?>
        sHtml += "		    </select>";
        sHtml += "		  </td>";
        sHtml += "		</tr>";
        sHtml += "	    </table>";

        sHtml += "	    <br style=\"line-height:5px;\" />";

        sHtml += "	    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
<?
		for ($i = 0; $i < $iPoSizesCount;)
		{
?>
        sHtml += "		<tr>";
<?
			for ($j = 0; $j < 10; $j ++)
			{
				if ($i < $iPoSizesCount)
				{
?>
        sHtml += "		  <td width=\"100\" align=\"left\">";
        sHtml += "		    <b><?= addslashes($sPoSizes[$i][1]) ?></b><br />";
        sHtml += "		    <input type=\"text\" id=\"Quantity" + iRows + "_<?= $i ?>\" name=\"Quantity" + iRows + "_<?= $sPoSizes[$i][0] ?>\" value=\"0\" size=\"10\" maxlength=\"10\" class=\"textbox\" onblur=\"UpdateTotal( );\" />";
        sHtml += "		  </td>";
<?
					$i ++;
				}

				else
				{
?>
        sHtml += "		  <td width=\"60\"></td>";
<?
				}
			}
?>
        sHtml += "		  </tr>";
<?
		}
?>
        sHtml += "	    </table>";

        sHtml += "	    <div class=\"poRowTotal\">";
        sHtml += "		  Total: <span id=\"Total" + iRows + "\">0</span>";
        sHtml += "	    </div>";
        sHtml += "	  </div>";
        sHtml += "	</div>";
<?
	}
?>

	new Insertion.Bottom('PoColors', sHtml);

	new Ajax.Autocompleter(("Color" + iRows), ("Choices_" + iRows), "ajax/get-purchase-order-colors.php", { paramName:"Keywords", minChars:3 } );


	Effect.SlideDown('ColorRecord' + iRows);

	setTimeout(function( ) { Effect.ScrollTo('ColorRecord' + iRows); }, 1000);

	$('ColorsCount').value = (iRows + 1);



	var iChecked = $('Styles').selectedIndex;

	if (iChecked != -1)
	{
		var iCount = $('Styles').length;

		for (var i = 0, j= 0; i < iCount; i ++)
		{
			if ($('Styles').options[i].selected != false)
			{
				$('Style' + iRows).options[j] = new Option($('Styles').options[i].text, $('Styles').options[i].value, false, false);

				j ++;
			}
		}
	}
}

function deleteColor( )
{
	var iRows       = parseInt($('ColorsCount').value);
	var iColumns    = parseInt($('SizesCount').value);
	var iDeleteAble = parseInt($('RecordCount').value);

	if (iRows > 1 && iRows > iDeleteAble)
	{
		iRows --;

		Effect.SlideUp('ColorRecord' + iRows);

		setTimeout(function( ) { $('ColorRecord' + iRows).remove( ); }, 1000);

		$('ColorsCount').value = iRows;
	}

	UpdateTotal( );
}

function UpdateTotal( )
{
	var iTotal      = 0;
	var iGrandTotal = 0;

	var iRows       = parseInt($('ColorsCount').value);
	var iColumns    = parseInt($('SizesCount').value);


	for (var i = 0; i < iRows; i ++)
	{
		iTotal = 0.0;

		for (var j = 0; j < iColumns; j ++)
		{
			if ($F('Quantity' + i + '_' + j) != "")
				iTotal += parseFloat($F('Quantity' + i + '_' + j));
		}

		$('Total' + i).innerHTML = formatNumber(iTotal);

		iGrandTotal += iTotal;
	}

	$('GrandTotal').innerHTML = formatNumber(iGrandTotal);
}

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

	if (!objFV.validate("OrderNo", "B", "Please enter the Order No."))
		return false;

	if (objFV.selectedIndex("Styles") == -1)
	{
		alert("Please select the Styles.");

		return false;
	}


	if (objFV.value("CartonLabeling") != "")
	{
		if (!checkImage(objFV.value("CartonLabeling")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("CartonLabeling");
			objFV.select("CartonLabeling");

			return false;
		}
	}

	
	if (objFV.value("PdfFile") != "")
	{
		if (!checkPdfFile(objFV.value("PdfFile")))
		{
			alert("Invalid File Format. Please select a valid PDF File.");

			objFV.focus("PdfFile");
			objFV.select("PdfFile");

			return false;
		}
	}

	
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


	var iRows = parseInt($('ColorsCount').value);

	for (var i = 0; i < iRows; i ++)
	{
		if (!objFV.validate(("Color" + i), "B", "Please enter the Color."))
			return false;

		if (!objFV.validate(("Price" + i), "B", "Please enter the Price."))
			return false;

		if (!objFV.validate(("Style" + i), "B", "Please select the Style."))
			return false;

//		if (!objFV.validate(("Destination" + i), "B", "Please select the Destination."))
//			return false;

		if (!objFV.validate(("DateOfShipment" + i), "B", "Please select the Date of Shipment."))
			return false;
	}

	checkPurchaseOrder( );

	return false;
}

function checkPurchaseOrder( )
{
	var sUrl    = "ajax/data/check-purchase-order.php";
	var sParams = ("OrderNo=" + $F('OrderNo') + "&OrderStatus=" + $F('OrderStatus') + "&Vendor=" + $F('Vendor') + "&Style=" + $F('Styles') + "&Id=" + $F('Id'));

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


document.onkeyup = function(event)
{
	if (!event.ctrlKey)
		return;

	if (event.keyCode == 77 || event.keyCode == 108)
	{
		document.location = "data/add-purchase-order.php";

		return;
	}
}


document.observe('dom:loaded', function( )
{
	if ($("frmData"))
	{
		var objFV = new FormValidator("frmData");

		objFV.focus("Vendor");
	}
});
<?
	@ob_end_flush( );
?>