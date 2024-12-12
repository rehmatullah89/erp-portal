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

	$Id        = IO::intValue("Id");
	$ReportId  = IO::intValue("ReportId");
	$AuditDate = IO::strValue("AuditDate");
        $sColors   = IO::strValue("Colors");

	$iColumns  = 5;
?>

function addDefect( )
{
	var iCount = parseInt($('Count').value);

	var sHtml  = "				<div id=\"DefectRecord" + iCount + "\" class=\"defectRecords\" style=\"display:none;\">";
        sHtml += "				  <div>";
        sHtml += "				    <input type=\"hidden\" id=\"DefectId" + iCount + "\" name=\"DefectId" + iCount + "\" value=\"\" class=\"defectId\" />";

        sHtml += "					<table border=\"1\" bordercolor=\"#ffffff\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\">";
        sHtml += "					  <tr class=\"sdRowColor\" valign=\"top\">";
        sHtml += "						<td width=\"<?= (($ReportId == 6) ? 40 : 50) ?>\" align=\"center\">" + (iCount + 1) + "</td>";

<?
	if ($ReportId == 6)
	{
?>
        sHtml += "						<td width=\"80\" align=\"center\">";
        sHtml += "						  <select id=\"Roll" + iCount + "\" name=\"Roll" + iCount + "\" class=\"defectRoll\" onchange=\"$('Sms').value='1';\">";
        sHtml += "							<option value=\"\"></option>";
        sHtml += "							<option value=\"1\">01</option>";
        sHtml += "							<option value=\"2\">02</option>";
        sHtml += "							<option value=\"3\">03</option>";
        sHtml += "							<option value=\"4\">04</option>";
        sHtml += "							<option value=\"5\">05</option>";
        sHtml += "						  </select>";
        sHtml += "						</td>";

        sHtml += "						<td width=\"80\" align=\"center\">";
        sHtml += "						  <select id=\"Panel" + iCount + "\" name=\"Panel" + iCount + "\" class=\"defectPanel\" onchange=\"$('Sms').value='1';\">";
        sHtml += "							<option value=\"\"></option>";
        sHtml += "							<option value=\"1\">01</option>";
        sHtml += "							<option value=\"2\">02</option>";
        sHtml += "							<option value=\"3\">03</option>";
        sHtml += "							<option value=\"4\">04</option>";
        sHtml += "							<option value=\"5\">05</option>";
        sHtml += "						  </select>";
        sHtml += "						</td>";
<?
	}
?>
        sHtml += "						<td <?=(in_array($ReportId, array(41,42)))?"width='235'":""?>>";
        sHtml += "						  <select id=\"Code" + iCount + "\" name=\"Code" + iCount + "\" class=\"defectCode\" required onchange=\"$('Sms').value='1';\">";
        sHtml += "							<option value=\"\"></option>";
<?
        $sLanguage      = getDbValue("language", "tbl_users", "id='{$_SESSION['UserId']}'");
        $sDefectQuery   = ($sLanguage == 'en'?'defect':"defect_".$sLanguage);
        $sTypeQuery     = ($sLanguage == 'en'?'type':"type_".$sLanguage);
        $sAreaQuery     = ($sLanguage == 'en'?'area':"area_".$sLanguage);

        if (in_array($ReportId, array(41,42)))
            $sSQL = "SELECT DISTINCT(type_id), (SELECT IF($sTypeQuery IS NULL, type, $sTypeQuery) FROM tbl_defect_types WHERE id=tbl_defect_codes.type_id) FROM tbl_defect_codes WHERE report_id='$ReportId' ORDER BY type_id";
        else
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

                else if (in_array($ReportId, array(41,42)))
                    $sSQL = "SELECT id, code, IF($sDefectQuery IS NULL, defect, $sDefectQuery) FROM tbl_defect_codes WHERE report_id='$ReportId' AND type_id='$iTypeId' ORDER BY code";

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
        sHtml += "		        			  <option value=\"<?= $iCodeId ?>\"><?= $sCode ?> - <?= addslashes($sDefect) ?></option>";
<?
		}
?>
        sHtml += "		        			</optgroup>";
<?
	}
?>
        sHtml += "						  </select>";
        sHtml += "						</td>";

<?
	if ($ReportId == 6)
	{
?>
        sHtml += "						<td width=\"80\" align=\"center\">";
        sHtml += "						  <select id=\"Grade" + iCount + "\" name=\"Grade" + iCount + "\" class=\"defectGrade\" onchange=\"$('Sms').value='1';\">";
        sHtml += "							<option value=\"\"></option>";
        sHtml += "							<option value=\"1\">1</option>";
        sHtml += "							<option value=\"2\">2</option>";
        sHtml += "							<option value=\"3\">3</option>";
        sHtml += "							<option value=\"4\">4</option>";
        sHtml += "						  </select>";
        sHtml += "						</td>";
<?
	}
?>

        sHtml += "						<td width=\"100\" align=\"center\"><input type=\"text\" id=\"Defects" + iCount + "\" name=\"Defects" + iCount + "\" value=\"1\" maxlength=\"3\" size=\"<?= (($ReportId == 6) ? 5 : 3) ?>\" class=\"textbox defectsCount\" required onchange=\"$('Sms').value='1';\" /></td>";
<?
	if (@in_array($ReportId, array(28,37,38)))
	{
		$TotalGmts = getDbValue("total_gmts", "tbl_qa_reports", "id='$Id'");
?>
        sHtml += "						<td width=\"100\" align=\"center\"><input type=\"text\" id=\"SampleNo" + iCount + "\" name=\"SampleNo" + iCount + "\" value=\"\" maxlength=\"3\" size=\"<?= (($ReportId == 6) ? 5 : 3) ?>\" class=\"textbox sampleNos\" onblur=\"getMaxAllowed(" + iCount + ",<?=$TotalGmts?>);\" onchange=\"$('Sms').value='1';\" /></td>";
<?
        }

	if ($ReportId != 6)
	{
?>
        sHtml += "						<td width=\"200\" align=\"center\">";
            
<?
                    if ($ReportId == 46)
                    {
?>
            sHtml += "						  <select id=\"Area" + iCount + "\" name=\"Area" + iCount + "\" class=\"defectArea\" onchange=\"$('Sms').value='1';\" style=\"width:200px;\" required=''>";
<?
                    }
                    else
                    {
?>
        sHtml += "						  <select id=\"Area" + iCount + "\" name=\"Area" + iCount + "\" class=\"defectArea\" onchange=\"$('Sms').value='1';\" style=\"width:200px;\">";
<?
                    }
?>
        sHtml += "							<option value=\"\"></option>";
<?
		if (strtotime($AuditDate) <= strtotime("2015-06-18"))
			$sSQL = "SELECT * FROM tbl_defect_areas ORDER BY area";

                else if($ReportId == 28 || $ReportId == 37 || $ReportId == 38)
                        $sSQL = "SELECT * FROM tbl_defect_areas WHERE status='A' AND id IN (593,594,595,596,597,598) ORDER BY area";

                else if(in_array($ReportId, array(41,42)))
			$sSQL = "SELECT id, IF($sAreaQuery IS NULL, area, $sAreaQuery) FROM tbl_defect_areas WHERE status='A' ORDER BY area";
                else
			$sSQL = "SELECT * FROM tbl_defect_areas WHERE status='A' ORDER BY area";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iAreaId = $objDb->getField($i, 0);
			$sArea   = $objDb->getField($i, 1);

			$sAreaId = str_pad($iAreaId, 2, '0', STR_PAD_LEFT);
?>
        sHtml += "		        			<option value=\"<?= $sAreaId ?>\"><?= addslashes($sArea) ?></option>";
<?
		}
?>
        sHtml += "						  </select>";
        sHtml += "						</td>";
<?
	}

	if ($ReportId == 10 || $ReportId == 33)
	{
?>
        sHtml += "						<td width=\"100\" align=\"center\">";
        sHtml += "						  <select id=\"Nature" + iCount + "\" name=\"Nature" + iCount + "\" class=\"defectNature\" required onchange=\"$('Sms').value='1';\">";
        sHtml += "							<option value=\"\"></option>";
        sHtml += "		        			<option value=\"1\">Major</option>";
        sHtml += "		        			<option value=\"0\">Minor</option>";
        sHtml += "						  </select>";
        sHtml += "						</td>";
<?
	}

        if ($ReportId == 33)
	{
?>
        sHtml += "						<td width=\"100\" align=\"center\">";
        sHtml += "						  <select id=\"DColor" + iCount + "\" name=\"DColor" + iCount + "\" class=\"defectColor\" onchange=\"$('Sms').value='1';\">";
        sHtml += "							<option value=\"\"></option>";
        <?
                                                $iColors = explode(",", $sColors);
                                                foreach($iColors as $sColor)
                                                {
        ?>
            sHtml += "		        			<option value=\"<?=$sColor?>\"><?=$sColor?></option>";
        <?
                                                }
        ?>

        sHtml += "						  </select>";
        sHtml += "						</td>";
<?
	}

        else if ($ReportId == 20 || $ReportId == 23 || $ReportId == 46)
	{
?>
        sHtml += "						<td width=\"100\" align=\"center\">";
        sHtml += "						  <select id=\"Nature" + iCount + "\" name=\"Nature" + iCount + "\" class=\"defectNature\" required onchange=\"$('Sms').value='1'; checkDefectNature("+iCount+");\">";
        sHtml += "							<option value=\"\"></option>";
        sHtml += "		        			<option value=\"2\">Critical</option>";
        sHtml += "		        			<option value=\"1\">Major</option>";
        sHtml += "		        			<option value=\"0\">Minor</option>";
        sHtml += "						  </select>";
        sHtml += "						</td>";
<?
	}

	else if (@in_array($ReportId, array(11,14,29,34,36,37,38,39,47)))
	{
?>
        sHtml += "						<td width=\"100\" align=\"center\">";
        sHtml += "						  <select id=\"Nature" + iCount + "\" name=\"Nature" + iCount + "\" class=\"defectNature\" required onchange=\"$('Sms').value='1';\">";
        sHtml += "							<option value=\"\"></option>";
        sHtml += "		        			<option value=\"2\">Critical</option>";
        sHtml += "		        			<option value=\"1\">Major</option>";
        sHtml += "		        			<option value=\"0\">Minor</option>";
        sHtml += "						  </select>";
        sHtml += "						</td>";
<?
	}

        else if (@in_array($ReportId, array(25,44,45)) &&  $ReportId != 33)
	{
?>
        sHtml += "						<td width=\"100\" align=\"center\">";
        sHtml += "						  <select id=\"Nature" + iCount + "\" name=\"Nature" + iCount + "\" class=\"defectNature\" required onchange=\"$('Sms').value='1';\">";
        sHtml += "							<option value=\"\"></option>";
        sHtml += "		        			<option value=\"2\">Critical</option>";
        sHtml += "		        			<option value=\"1\">Major</option>";
        sHtml += "						  </select>";
        sHtml += "						</td>";
<?
	}

	else
	{
?>
        sHtml += "						<td width=\"100\" align=\"center\">";
        sHtml += "						  <select id=\"Nature" + iCount + "\" name=\"Nature" + iCount + "\" class=\"defectNature\" required onchange=\"$('Sms').value='1';\">";
        sHtml += "							<option value=\"\"></option>";
        sHtml += "		        			<option value=\"1\">Major</option>";
        sHtml += "		        			<option value=\"0\">Minor</option>";
        sHtml += "						  </select>";
        sHtml += "						</td>";
<?
	}

        if (in_array($ReportId, array(41,42)))
	{
?>
        sHtml += "						<td width=\"80\" align=\"center\">";
        sHtml += "						  <select id=\"Status" + iCount + "\" name=\"Status" + iCount + "\" class=\"workStatus\" onchange=\"$('Sms').value='1';\">";
        sHtml += "							<option value=\"\"></option>";
        sHtml += "							<option value=\"R\">Re-Work</option>";
        sHtml += "							<option value=\"W\">Wasted</option>";
        sHtml += "						  </select>";
        sHtml += "						</td>";
<?
	}
?>
        sHtml += "						<td width=\"50\" align=\"center\"><img src=\"images/icons/delete.gif\" width=\"16\" height=\"16\" alt=\"Delete\" title=\"Delete\" style=\"cursor:pointer;\" class=\"deleteDefect\" rel=\"" + iCount + "\" /></td>";
        sHtml += "					  </tr>";

<?
	if (@in_array($ReportId, array(14,25,29,34,47)))
	{
?>
        sHtml += "					  <tr>";
        sHtml += "						<td align=\"center\">CAP</td>";
        sHtml += "						<td colspan=\"<?= $iColumns ?>\"><input type=\"text\" id=\"Cap" + iCount + "\" name=\"Cap" + iCount + "\" value=\"\" maxlength=\"250\" class=\"textbox defectCap\" style=\"width:97.5%;\" onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "					  </tr>";
<?
	}
?>
        <?
	if (@in_array($ReportId, array(25,28,37,38)))
	{
?>
        sHtml += "					  <tr>";
        sHtml += "						<td align=\"center\">Remarks</td>";
        sHtml += "						<td colspan=\"<?= $iColumns ?>\"><input type=\"text\" id=\"Remarks" + iCount + "\" name=\"Remarks" + iCount + "\" value=\"\" maxlength=\"250\" class=\"textbox defectCap\" style=\"width:97.5%;\" onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "					  </tr>";
<?
	}
?>
        sHtml += "					  <tr>";
        sHtml += "						<td align=\"center\"><img src=\"images/icons/pictures.gif\" width=\"16\" height=\"16\" alt=\"Defect Picture\" title=\"Defect Picture\" /></td>";
        sHtml += "						<td colspan=\"<?= $iColumns ?>\"><input type=\"file\" id=\"Picture" + iCount + "\" name=\"Picture" + iCount + "\" value=\"\" size=\"30\" class=\"textbox defectPicture\" required /></td>";
        sHtml += "					  </tr>";
        sHtml += "					</table>";
        sHtml += "				  </div>";
        sHtml += "				</div>";


		new Insertion.Bottom('QaDefects', sHtml);

		Effect.SlideDown('DefectRecord' + iCount);

		$('Count').value = (iCount + 1);
}

function addTnCDefect( )
{
	var iCount = parseInt($('Count').value);

	var sHtml  = "				<div id=\"DefectRecord" + iCount + "\" class=\"defectRecords\" style=\"display:none;\">";
        sHtml += "				  <div>";
        sHtml += "				    <input type=\"hidden\" id=\"DefectId" + iCount + "\" name=\"DefectId" + iCount + "\" value=\"\" class=\"defectId\" />";

        sHtml += "					<table border=\"1\" bordercolor=\"#ffffff\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\">";
        sHtml += "					  <tr class=\"sdRowColor\" valign=\"top\">";
        sHtml += "						<td width=\"<?= (($ReportId == 26 || $ReportId == 30) ? 20 : 50) ?>\" align=\"center\">" + (iCount + 1) + "</td>";

        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"LotNo" + iCount + "\" name=\"LotNo" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
	sHtml += "				<td width=\"50\" align=\"center\"><input type=\"text\" id=\"RollNo" + iCount + "\"  name=\"RollNo" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"Width" + iCount + "\"  name=\"Width" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"TicketMeters" + iCount + "\" name=\"TicketMeters" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"ActualMeters" + iCount + "\" name=\"ActualMeters" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"Holes" + iCount + "\" name=\"Holes" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"Slubs" + iCount + "\" name=\"Slubs" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"Stains" + iCount + "\" name=\"Stains" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"Fly" + iCount + "\" name=\"Fly" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"Other" + iCount + "\" name=\"Other" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";
<?
	if ($ReportId == 30)
	{
?>
        sHtml += "                              <td width=\"50\" align=\"center\"><input type=\"text\" id=\"AllowedDefects" + iCount + "\" name=\"AllowedDefects" + iCount + "\" value='' maxlength='3' size='3' class='textbox defectsCount' onchange=\"$('Sms').value='1';\" /></td>";

<?      }
?>
<?
	if ($ReportId == 26)
	{
?>
            sHtml += "						<td width=\"50\" align=\"center\">";
            sHtml += "						  <select id=\"Result" + iCount + "\" name=\"Result" + iCount + "\" class=\"defectRoll\" onchange=\"$('Sms').value='1';\">";
            sHtml += "							<option value=\"\"></option>";
            sHtml += "							<option value=\"P\">Pass</option>";
            sHtml += "							<option value=\"F\">Fail</option>";
            sHtml += "						  </select>";
            sHtml += "						</td>";
<?      }
?>
        sHtml += "                              <td width=\"50\" align=\"center\"><img src=\"images/icons/delete.gif\" width=\"16\" height=\"16\" alt=\"Delete\" title=\"Delete\" style=\"cursor:pointer;\" class=\"deleteDefect\" rel=\"" + iCount + "\" /></td>";

        sHtml += "					  </tr><tr>";
        sHtml += "						<td align=\"center\"><img src=\"images/icons/pictures.gif\" width=\"16\" height=\"16\" alt=\"Defect Picture\" title=\"Defect Picture\" /></td>";
        sHtml += "						<td colspan=\"<?= $iColumns ?>\"><input type=\"file\" id=\"Picture" + iCount + "\" name=\"Picture" + iCount + "\" value=\"\" size=\"30\" class=\"textbox defectPicture\" /></td>";
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
		$('Sms').value   = "1";
	}
}


function getColorsList(sPo)
{
	for (var i = 0 ; i < 5; i ++)
		$("StyleColor" + i).value = "";

	if (sPo == "")
		return;

	var sUrl    = "ajax/quonda/get-po-colors.php";
	var sParams = ("Po=" + sPo);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getColorsList });
}


function _getColorsList(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			for (var i = 1; i < sParams.length; i ++)
				$("StyleColor" + (i - 1)).value = sParams[i];
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

	if (!objFV.validate("PO", "B", "Please select the PO."))
		return false;

	if (!objFV.validate("Style", "B", "Please select the Style."))
		return false;

	if (!objFV.validate("AuditStage", "B", "Please select the Audit Stage."))
		return false;
<?
        if ($ReportId == 46)
	{
?>
                if (objFV.value("ApprovedSample") == "No")
                {
                    if(!confirm("Are you sure no PP Sample available during the audit?"))
                        return false;
                }
<?    
        }
?>
	if (!objFV.validate("AuditResult", "B", "Please select the Audit Result."))
		return false;

	if ($('TotalGmts'))
	{
		if (!objFV.validate("TotalGmts", "B,N", "Please enter the Total GMTS Inspected (Pcs)."))
			return false;
	}

<?
	if ($ReportId == 14 || $ReportId == 34 || $ReportId == 47)
	{
?>
            if (!objFV.validate("MeasurementSampleQty", "B", "Please enter the Measurement Inspected Qty."))
		return false;

            if (!objFV.validate("MeasurementDefectQty", "B", "Please enter the Measurement Defective Qty."))
		return false;

            if (!objFV.validate("GacDate", "B", "Please enter the Gac Date."))
		return false;

            if (!objFV.validate("ShipQty", "B", "Please enter Ship Qty."))
		return false;

            if (!objFV.validate("TotalCartons", "B", "Please enter Total Cartons Inspected."))
		return false;

            if (!objFV.validate("CartonsShipped", "B", "Please enter Total Cartons Shipped."))
		return false;

            if (!objFV.validate("Cutting", "B", "Please enter Cutting/Knitting (%)."))
		return false;

            if (!objFV.validate("Sewing", "B", "Please enter Sewing/Linking (%)."))
		return false;

            if (!objFV.validate("Finishing", "B", "Please enter Finishing (%)."))
		return false;

            if (!objFV.validate("Packing", "B", "Please enter Packing (%)."))
		return false;

<?
        }

        if (in_array($ReportId, array(41,42)))
	{
?>
            var counter = $('Count').value;

            for(i=0; i < counter; i++)
            {
                if (!objFV.validate("Status"+i, "B", "Please select garment status."))
                    return false;
            }
<?
        }
?>
	return true;
}


function validatePackingForm( )
{
	var objFV = new FormValidator("frmPacking");
	var bFlag = false;

	for (var i = 1; i <= 5; i ++)
	{
		if (objFV.value("Packing" + i) != "")
		{
			if (!checkImage(objFV.value("Packing" + i)))
			{
				alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

				objFV.focus("Packing" + i);
				objFV.select("Packing" + i);

				return false;
			}

			bFlag = true;
		}
	}

	if (bFlag == false)
	{
		alert("Please select a Packing Image to Upload.");

		return false;
	}

	return true;
}


function validateSpecsForm( )
{
	var objFV = new FormValidator("frmSpecs");
	var bFlag = false;

	for (var i = 1; i <= 10; i ++)
	{
		if (objFV.value("SpecsSheet" + i) != "")
		{
                        var fext = objFV.value("SpecsSheet" + i);
                        fext = fext.substring(fext.lastIndexOf(".")+1);
                        fext = fext.toLowerCase();

			if (!checkImage(objFV.value("SpecsSheet" + i)) && fext != 'pdf')
			{
				alert("Invalid File Format. Please select an image file of type jpg, gif, png or pdf.");

				objFV.focus("SpecsSheet" + i);
				objFV.select("SpecsSheet" + i);

				return false;
			}

			bFlag = true;
		}
	}

	if (bFlag == false)
	{
		alert("Please select a Specs Sheet File to Upload.");

		return false;
	}

	return true;
}

function getMaxAllowed(Index, MaxValue)
{
    var SampleValue = parseInt(document.getElementById("SampleNo"+Index).value);
	var MaxValue    = parseInt(document.getElementById("TotalGmts").value);

    if(SampleValue <= 0 || SampleValue > MaxValue)
    {
        document.getElementById("SampleNo"+Index).value = 1;

        alert("Sample No. Value should be between 1 - " + MaxValue);

        return false;
    }

    return true;
}

function checkDefectNature(counter)
{
    if(document.getElementById("Nature"+counter).value == 0)
    {
       document.getElementById("Picture"+counter).removeAttribute("required");
    }
    else
        document.getElementById("Picture"+counter).setAttribute("required", true);
}

<?
	@ob_end_flush( );
?>