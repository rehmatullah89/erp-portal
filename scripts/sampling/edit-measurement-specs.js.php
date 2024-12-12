<?
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

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$ReportId = IO::intValue("ReportId");
	$BrandId  = IO::intValue("BrandId");
?>

function showMPs( )
{
	Effect.toggle('MPsBlock', 'slide');

	setTimeout(function( ) { Effect.ScrollTo('MPsBlock'); }, 1000);
}

function moveRight( )
{
	var iChecked = $('MPs').selectedIndex;

	if (iChecked == -1)
	{
		alert("Please select a Measurement Point to Add.");
	}

	else
	{
		var iCount = $('MPs').length;

		for (var i = 0; i < iCount; i ++)
		{
			if ($('MPs').options[i].selected != false)
				$('SelectedMPs').options[$('SelectedMPs').length] = new Option($('MPs').options[i].text, $('MPs').options[i].value, false, false);
		}

		for (i = (iCount - 1); i >= 0; i --)
		{
			if ($('MPs').options[i].selected != false)
				$('MPs').options[i] = null;
		}

		$('MPs').selectedIndex = -1;
	}
}

function moveLeft( )
{
	var iChecked = $('SelectedMPs').selectedIndex;

	if (iChecked == -1)
	{
		alert("Please select a Measurement Point to Remove.");
	}

	else
	{
		var iCount = $('SelectedMPs').length;

		for (var i = 0; i < iCount; i ++)
		{
			if ($('SelectedMPs').options[i].selected != false)
				$('MPs').options[$('MPs').length] = new Option($('SelectedMPs').options[i].text, $('SelectedMPs').options[i].value, false, false);
		}

		for (i = (iCount - 1); i >= 0; i --)
		{
			if ($('SelectedMPs').options[i].selected != false)
				$('SelectedMPs').options[i] = null;
		}

		$('SelectedMPs').selectedIndex = -1;
	}
}

function checkSelection( )
{
	var iCount = $('SelectedMPs').length;

	if (iCount == 0)
	{
		alert("Please select atleast One Measurement Point.");

		return false;
	}

	for (var i = 0; i < iCount; i ++)
		$('SelectedMPs').options[i].selected = true;

	return true;
}

function addDefect( )
{
	var iCount = parseInt($('Count').value);

	var sHtml  = "				<div id=\"DefectRecord" + iCount + "\" style=\"display:none\">";
        sHtml += "				  <div>";
        sHtml += "				    <input type=\"hidden\" id=\"DefectId" + iCount + "\" name=\"DefectId" + iCount + "\" value=\"\" />";

        sHtml += "					<table border=\"1\" bordercolor=\"#ffffff\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\">";
        sHtml += "					  <tr class=\"sdRowColor\" valign=\"top\">";
        sHtml += "						<td width=\"50\" align=\"center\">" + (iCount + 1) + "</td>";

        sHtml += "						<td>";
        sHtml += "						  <select id=\"Code" + iCount + "\" name=\"Code" + iCount + "\">";
        sHtml += "							<option value=\"\"></option>";
<?
	$iMainBrand = getDbValue("parent_id", "tbl_brands", "id='$BrandId'");


	$sSQL = "SELECT DISTINCT(type_id),
	                (SELECT type FROM tbl_sampling_defect_types WHERE id=tbl_sampling_defect_codes.type_id)
	         FROM tbl_sampling_defect_codes
	         WHERE report_id='$ReportId' AND brand_id='$iMainBrand'
	         ORDER BY type_id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iTypeId = $objDb->getField($i, 0);
		$sType   = $objDb->getField($i, 1);
?>
        sHtml += "		        			<optgroup label=\"<?= $sType ?>\">";
<?
		$sSQL = "SELECT id, code, defect FROM tbl_sampling_defect_codes WHERE report_id='$ReportId' AND brand_id='$iMainBrand' AND type_id='$iTypeId' ORDER BY code";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$iCodeId = $objDb2->getField($j, 0);
			$sCode   = $objDb2->getField($j, 1);
			$sDefect = $objDb2->getField($j, 2);

?>
        sHtml += "		        			  <option value=\"<?= $iCodeId ?>\"><?= $sCode ?> - <?= $sDefect ?></option>";
<?
		}
?>
        sHtml += "		        			</optgroup>";
<?
	}
?>
        sHtml += "						  </select>";
        sHtml += "						</td>";

        sHtml += "						<td width=\"200\">";
        sHtml += "						  <select id=\"Area" + iCount + "\" name=\"Area" + iCount + "\" style=\"width:190px;\">";
        sHtml += "							<option value=\"\"></option>";
<?
	$sAreasList = getList("tbl_sampling_defect_areas", "id", "CONCAT(LPAD(id, 2, '0'), ' - ', area)");

	foreach ($sAreasList as $iArea => $sArea)
	{
?>
        sHtml += "		       			    <option value=\"<?= $iArea ?>\"><?= $sArea ?></option>";
<?
	}
?>
        sHtml += "						  </select>";
        sHtml += "						</td>";

        sHtml += "						<td width=\"100\" align=\"center\"><input type=\"text\" id=\"Defects" + iCount + "\" name=\"Defects" + iCount + "\" value=\"\" maxlength=\"3\" size=\"8\" class=\"textbox\" /></td>";
        sHtml += "					  </tr>";
        sHtml += "					</table>";
        sHtml += "				  </div>";
        sHtml += "				</div>";


	new Insertion.Bottom('Defects', sHtml);

	Effect.SlideDown('DefectRecord' + iCount);

	$('Count').value = (iCount + 1);
}

function deleteDefect( )
{
	var iCount = parseInt($('Count').value);

	if (iCount >= 1)
	{
		iCount --;


		Effect.SlideUp('DefectRecord' + iCount);

		setTimeout(function( ) { $('DefectRecord' + iCount).remove( ); }, 1000);

		$('Count').value = iCount;
	}
}

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Report", "B", "Please select the Report Type."))
		return false;

	if (checkSelection( ) == false)
		return false;

	if (objFV.value("OldFront") == "" && objFV.value("Front") == "")
	{
		if (!objFV.validate("Front", "B", "Please select the Front Picture of Sample."))
			return false;

		if (!checkImage(objFV.value("Front")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Front");
			objFV.select("Front");

			return false;
		}
	}

	if (objFV.value("OldBack") == "" && objFV.value("Back") == "")
	{
		if (!objFV.validate("Back", "B", "Please select the Back Picture of Sample."))
			return false;

		if (!checkImage(objFV.value("Back")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Back");
			objFV.select("Back");

			return false;
		}
	}

	if (objFV.value("BuyerOffice") != "" || objFV.value("BuyerComments") != "")
	{
		if (!objFV.validate("BuyerOffice", "B", "Please select the Buyer Office."))
			return false;

		if (!objFV.validate("BuyerComments", "B", "Please enter the Buyer Comments."))
			return false;
	}

	return true;
}


function validateImportForm( )
{
	var objFV = new FormValidator("frmImport");

	if (!objFV.validate("ExcelFile", "B", "Please select the Measurement Details Excel File."))
		return false;

	if (objFV.value("ExcelFile") != "")
	{
		if (!checkXlsx(objFV.value("ExcelFile")))
		{
			alert("Invalid File Format. Please select a valid MS Excel File.");

			objFV.focus("ExcelFile");
			objFV.select("ExcelFile");

			return false;
		}
	}

	$('Processing').show( );

	return true;
}
<?
	@ob_end_flush( );
?>