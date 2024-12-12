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
	$objDb2      = new Database( );

	$Id       = IO::intValue("Id");
	$ReportId = IO::intValue("ReportId");
	$StyleId  = IO::intValue("StyleId");

	$iBrand = getDbValue("brand_id", "tbl_styles", "id='$StyleId'");
?>

function addDefect( )
{
	var iCount = parseInt($('Count').value);

	var sHtml  = "				<div id=\"DefectRecord" + iCount + "\" style=\"display:none;\">";
        sHtml += "				  <div>";
        sHtml += "				    <input type=\"hidden\" id=\"DefectId" + iCount + "\" name=\"DefectId" + iCount + "\" value=\"\" />";

        sHtml += "					<table border=\"1\" bordercolor=\"#ffffff\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\">";
        sHtml += "					  <tr class=\"sdRowColor\" valign=\"top\">";
        sHtml += "						<td width=\"50\" align=\"center\">" + (iCount + 1) + "</td>";
        sHtml += "						<td>";
        sHtml += "						  <select id=\"Code" + iCount + "\" name=\"Code" + iCount + "\">";
        sHtml += "							<option value=\"\"></option>";
<?
	$sSQL = "SELECT DISTINCT(type_id),
	                (SELECT type FROM tbl_sampling_defect_types WHERE id=tbl_sampling_defect_codes.type_id)
	         FROM tbl_sampling_defect_codes
	         WHERE report_id='$ReportId' AND brand_id='$iBrand'
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
		$sSQL = "SELECT id, code, defect FROM tbl_sampling_defect_codes WHERE report_id='$ReportId' AND type_id='$iTypeId' AND brand_id='$iBrand' ORDER BY code";
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
        sHtml += "						<td width=\"100\" align=\"center\"><input type=\"text\" id=\"Defects" + iCount + "\" name=\"Defects" + iCount + "\" value=\"\" maxlength=\"3\" size=\"6\" class=\"textbox\" /></td>";

        sHtml += "						<td width=\"200\" align=\"center\">";
        sHtml += "						  <select id=\"Area" + iCount + "\" name=\"Area" + iCount + "\" style=\"width:200px;\">";
        sHtml += "							<option value=\"\"></option>";
<?
	$sSQL = "SELECT * FROM tbl_sampling_defect_areas ORDER BY area";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iAreaId = $objDb->getField($i, 0);
		$sArea   = $objDb->getField($i, 1);

		$sAreaId = str_pad($iAreaId, 2, '0', STR_PAD_LEFT);
?>
        sHtml += "		        			<option value=\"<?= $sAreaId ?>\"><?= $sAreaId ?> - <?= $sArea ?></option>";
<?
	}
?>
        sHtml += "						  </select>";
        sHtml += "						</td>";
        sHtml += "					  </tr>";
        sHtml += "					</table>";
        sHtml += "				  </div>";
        sHtml += "				</div>";


		new Insertion.Bottom('QaDefects', sHtml);

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

	if (!objFV.validate("AuditStage", "B", "Please select the Audit Stage."))
		return false;

	if (!objFV.validate("AuditResult", "B", "Please select the Audit Result."))
		return false;

	if ($('TotalGmts'))
	{
		if (!objFV.validate("TotalGmts", "B,N", "Please enter the Total GMTS Inspected (Pcs)."))
			return false;
	}

	for (var i = 1; i <= 10; i ++)
	{
		if (objFV.value("SpecsSheet" + i) != "")
		{
			if (!checkImage(objFV.value("SpecsSheet" + i)))
			{
				alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

				objFV.focus("SpecsSheet" + i);
				objFV.select("SpecsSheet" + i);

				return false;
			}
		}
	}

	return true;
}
<?
	@ob_end_flush( );
?>