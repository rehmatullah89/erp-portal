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

	$Id       = IO::intValue("Id");
	$Po       = IO::intValue("Po");
	$ReportId = IO::intValue("ReportId");


	$sPoStylesList = array( );

	$sSQL = "SELECT DISTINCT(style_id),
	                (SELECT style FROM tbl_styles WHERE id=tbl_po_colors.style_id) AS _Style
	         FROM tbl_po_colors
	         WHERE po_id='$Po'
	         ORDER BY _Style";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sPoStylesList[$objDb->getField($i, 0)] = $objDb->getField($i, 1);
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

        sHtml += "						<td width=\"200\">";
        sHtml += "						  <select id=\"Style" + iCount + "\" name=\"Style" + iCount + "\">";
        sHtml += "							<option value=\"\"></option>";
<?
		foreach ($sPoStylesList as $sKey => $sValue)
		{
?>
        sHtml += "		        			<option value=\"<?= $sKey ?>\"><?= $sValue ?></option>";
<?
		}
?>
        sHtml += "						  </select>";
        sHtml += "						</td>";

        sHtml += "						<td>";
        sHtml += "						  <select id=\"Code" + iCount + "\" name=\"Code" + iCount + "\">";
        sHtml += "							<option value=\"\"></option>";
<?
		$sSQL = "SELECT DISTINCT(type_id), (SELECT type FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) FROM tbl_defect_codes WHERE report_id='$ReportId' ORDER BY type_id";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iTypeId = $objDb->getField($i, 0);
			$sType   = $objDb->getField($i, 1);
?>
        sHtml += "		        			<optgroup label=\"<?= $sType ?>\">";
<?
			if ($ReportId == 7)
				$sSQL = "SELECT id, buyer_code, defect FROM tbl_defect_codes WHERE report_id='$ReportId' AND type_id='$iTypeId' ORDER BY code";

			else
				$sSQL = "SELECT id, code, defect FROM tbl_defect_codes WHERE report_id='$ReportId' AND type_id='$iTypeId' ORDER BY code";

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

        sHtml += "						<td width=\"100\" align=\"center\"><input type=\"text\" id=\"Defects" + iCount + "\" name=\"Defects" + iCount + "\" value=\"\" maxlength=\"3\" size=\"<?= (($ReportId == 6) ? 5 : 3) ?>\" class=\"textbox\" /></td>";
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

	if (!objFV.validate("AuditResult", "B", "Please select the Audit Result."))
		return false;

	if (!objFV.validate("SampleSize", "B,N", "Please enter the Sample Size."))
		return false;

	if (!objFV.validate("Quantity", "B,N", "Please enter the PO Quantity."))
		return false;

	return true;
}
<?
	@ob_end_flush( );
?>