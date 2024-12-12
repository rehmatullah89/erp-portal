
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

function exportReport( )
{
	$('BtnExport').disabled = true;
	
	document.location = $('ExportUrl').value;
	
	setTimeout( function( ) { $('BtnExport').disabled = false; }, 10000);
}

function validateForm( )
{
	var objFV = new FormValidator("frmData");

        if (objFV.value("Report") == "44" || objFV.value("Report") == "45" )
        {
                if (!objFV.validate("AuditType", "B", "Please select the Audit Type."))
                        return false;
        }
        else
        {
                if (!objFV.validate("Report", "B", "Please select the Report Type."))
                    return false;
        }    
        
	if (!objFV.validate("AuditStage", "B", "Please select the Audit Stage."))
		return false;
	
	if (!objFV.validate("Auditor", "B", "Please select the Auditor."))
		return false;

	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

	if (objFV.value("Report") != "14" && objFV.value("Report") != "34" && objFV.value("Report") != "28" && objFV.value("Report") != "37" && objFV.value("Report") != "38" && objFV.value("Report") != "39" && objFV.value("Report") != "44" && objFV.value("Report") != "45" && objFV.value("Vendor") != "229")
	{
		if (!objFV.validate("Line", "B", "Please select the Vendor Line."))
			return false;
                    
		// if (!objFV.validate("Department", "B", "Please select the Department."))
		// 	return false;    
	}
	
	if (!objFV.validate("AuditDate", "B", "Please select the Audit Date."))
		return false;

	if (!objFV.validate("StartHour", "B", "Please select the Start Time (Hour)."))
		return false;

	if (!objFV.validate("StartMinutes", "B", "Please select the Start Time (Minutes)."))
		return false;

	if (!objFV.validate("EndHour", "B", "Please select the End Time (Hour)."))
		return false;

	if (!objFV.validate("EndMinutes", "B", "Please select the End Time (Minutes)."))
		return false;

	if (!objFV.validate("OrderNo", "B", "Please enter/select the Order No."))
		return false;

  if (objFV.value("Report") != "54" && !objFV.validate("StyleNo", "B", "Please select the Style No."))
    return false;

	if (objFV.value("Report") == "54" && !objFV.validate("StyleNo[]", "B", "Please select the Style No."))
		return false;
		
	if (objFV.value("Report") != "26")
	{
		if (!objFV.validate("Colors", "B", "Please select the Colors."))
			return false;

		if (!objFV.validate("Sizes", "B", "Please select the Sizes."))
			return false;		
        }
        
        if (objFV.value("Report") != "26" && objFV.value("Report") != "28" && objFV.value("Report") != "37" && objFV.value("Report") != "38" && objFV.value("Report") != "54")
	{
            try{
                if(objFV.value("AuditType") != 2)
                {
                    if (!objFV.validate("SampleSize", "B", "Please select the Sample Size."))
                        return false;
                }
            }
            catch(e)
            {
                if (!objFV.validate("SampleSize", "B", "Please select the Sample Size."))
                        return false;
            }
        }
        
        if(objFV.value("Report") == "26")
	{
		if (!objFV.validate("InspecType", "B", "Please select the Inspection Type."))
			return false;
		
		if (!objFV.validate("Maker", "B", "Please select the Maker."))
			return false;
        }

	if (objFV.value("Report") == "28" || objFV.value("Report") == "37")
	{
            if (!objFV.validate("OfferedQty", "B", "Please add the Offered Quantity."))
                            return false;
                        
            if (!objFV.validate("InspectionLevel", "B", "Please Select the Inspection Level."))
                            return false;            
        }
        
        if (objFV.value("Report") == "38")
	{
            if (!objFV.validate("OfferedQty", "B", "Please add the Offered Quantity."))
                            return false;
            
            if (!objFV.validate("CheckLevel", "B", "Please Select the Inspection Check Level."))
                            return false;
        }
        
	return true;
}


function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (objFV.value("Report") == "44" || objFV.value("Report") == "45" )
        {
                if (!objFV.validate("AuditType", "B", "Please select the Audit Type."))
                        return false;
        }
        else
        {
                if (!objFV.validate("Report", "B", "Please select the Report Type."))
                    return false;
        }    
	
	if (!objFV.validate("AuditStage", "B", "Please select the Audit Stage."))
		return false;

	if (!objFV.validate("Auditor", "B", "Please select the Auditor."))
		return false;

	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

	if (objFV.value("Report") != "14" && objFV.value("Report") != "34" && objFV.value("Report") != "28" && objFV.value("Report") != "37" && objFV.value("Report") != "38" && objFV.value("Report") != "39" && objFV.value("Vendor") != "229" && objFV.value("Report") != "44" && objFV.value("Report") != "45" && objFV.value("Report") != "46")
	{
		if (!objFV.validate("Line", "B", "Please select the Vendor Line."))
			return false;
                
          //       if (!objFV.validate("Department", "B", "Please select the Department."))
        		// return false;

	}

	if (!objFV.validate("AuditDate", "B", "Please select the Audit Date."))
		return false;

	if (!objFV.validate("StartHour", "B", "Please select the Start Time (Hour)."))
		return false;

	if (!objFV.validate("StartMinutes", "B", "Please select the Start Time (Minutes)."))
		return false;

	if (!objFV.validate("EndHour", "B", "Please select the End Time (Hour)."))
		return false;

	if (!objFV.validate("EndMinutes", "B", "Please select the End Time (Minutes)."))
		return false;

	if (!objFV.validate(("OrderNo" + iId), "B", "Please enter/select the Order No."))
		return false;

	if (objFV.value("Report") != "54" && !objFV.validate("StyleNo", "B", "Please select the Style No."))
		return false;

  if (objFV.value("Report") == "54" && !objFV.validate("StyleNo[]", "B", "Please select the Style No."))
    return false;

	if (objFV.value("Report") == "26")
	{            
		if (!objFV.validate("InspecType", "B", "Please select the Inspection Type."))
			return false;

		if (!objFV.validate("Maker", "B", "Please select the Maker."))
			return false;
	}
	
	else
	{
		if (!objFV.validate(("Colors" + iId), "B", "Please select the Colors."))
			return false;

		if (!objFV.validate(("Sizes" + iId), "B", "Please select the Sizes."))
			return false;

	}

        if (objFV.value("Report") != "26" && objFV.value("Report") != "28" && objFV.value("Report") != "37" && objFV.value("Report") != "38" && objFV.value("Report") != "54")
	{
            try{
                if(objFV.value("AuditType") != 2)
                {
                    if (!objFV.validate("SampleSize", "B", "Please select the Sample Size."))
                        return false;
                }
            }
            catch(e)
            {
                if (!objFV.validate("SampleSize", "B", "Please select the Sample Size."))
                        return false;
            }
        }
	
        if (objFV.value("Report") == "28" || objFV.value("Report") == "37")
	{
            if (!objFV.validate("OfferedQty", "B", "Please add the Offered Quantity."))
                            return false;
                        
            if (!objFV.validate("InspectionLevel", "B", "Please Select the Inspection Level."))
                            return false;             
        }
        
        if (objFV.value("Report") == "38")
	{
            if (!objFV.validate("OfferedQty", "B", "Please add the Offered Quantity."))
                            return false;
                        
            if (!objFV.validate("CheckLevel", "B", "Please Select the Inspection Check Level."))
                            return false;
        }
        
	$('Processing').show( );

	var sUrl    = "ajax/quonda/update-audit-code.php";
	var sParams = $('frmData' + iId).serialize( );

	var objForm = $("frmData" + iId);
	objForm.disable( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_updateData });
}

function _updateData(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		var iId     = sParams[1];

		if (sParams[0] == "OK")
		{
			$('Msg' + iId).innerHTML = sParams[2];
			$('Msg' + iId).show( );
			$('Edit' + iId).hide( );

			setTimeout(
						function( )
						{
							new Effect.SlideUp("Msg" + iId);

                                                        if ((document.getElementById("ThisReportId"+iId).value == "34" || document.getElementById("ThisReportId"+iId).value == "14") && sParams[12] == "Y")
                                                        {
                                                            document.getElementById("EditOpt"+iId).style.display = 'none';
                                                        }
                                                        
                                                        if (document.getElementById("ThisReportId"+iId).value != "14" || document.getElementById("ThisReportId"+iId).value != "34")
                                                        {
                                                            $('Auditor_' + iId).innerHTML   = sParams[3];
                                                            $('Date_' + iId).innerHTML      = sParams[6];
                                                            $('StartTime_' + iId).innerHTML = sParams[7];
                                                            $('EndTime_' + iId).innerHTML   = sParams[8];

                                                        }
                                                        
							$('Vendor_' + iId).innerHTML    = sParams[4];
							
							if ($('Line_' + iId))
								$('Line_' + iId).innerHTML      = sParams[5];
							
							if (sParams[9] == "N")
								$("Record" + iId).style.background = "#ffeaea";

							else
								$("Record" + iId).style.background = "#d6fad4";

							if(sParams[10] !== "" && sParams[10] != null)
								$('Maker' + iId).innerHTML   = sParams[10];

							if(sParams[11] !== "" && sParams[11] != null)
								$('InspecType' + iId).innerHTML   = sParams[11];
                                                        
						},

						2000
				  );
		}

		else
			_showError(sParams[1]);

		$('Processing').hide( );

		var objForm = $("frmData" + iId);
		objForm.enable( );
                
                if (document.getElementById("ThisReportId"+iId).value == "14" || document.getElementById("ThisReportId"+iId).value == "34")
                {
                    document.getElementById("Auditor"+iId).disabled = true;
                    document.getElementById("AuditDate"+iId).disabled = true;
                    document.getElementById("StartHour"+iId).disabled = true;
                    document.getElementById("StartMinutes"+iId).disabled = true;
                    document.getElementById("EndHour"+iId).disabled = true;
                    document.getElementById("EndMinutes"+iId).disabled = true;
                }
	}

	else
		_showError( );
}


function getUnitLines(sId, sList)
{
	clearList($(sList));

	var iVendor = $F("Vendor" + sId);
	var iUnit   = $F("Unit" + sId);

	if (iVendor == "")
		return;

	$(sList).disable( );


	var sUrl    = "ajax/get-lines.php";
	var sParams = ("Id=" + iVendor + "&Unit=" + iUnit + "&List=" + sList);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getUnitLines });
}


function _getUnitLines(sResponse)
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

function setAutoSampleSize(obj, Id)
{
    var Val = document.getElementById("AuditTypeId"+Id).value;
    
    var AuditStage = obj.value;
    var sampleSize = 32;
    
    if(AuditStage == 'TG')
        sampleSize = 20;
    else if(AuditStage == 'F')
        sampleSize = 5;
    
    if(Val == 2 && (AuditStage == 'TG' || AuditStage == 'F'))
    {
        document.getElementById("SampleSizeId"+Id).innerHTML = "<input type='hidden' name='SampleSize' value='"+sampleSize+"'>";
    }
    else
    {
        document.getElementById("SampleSizeId"+Id).innerHTML = "<td id='TNCSSizes'>Sample Size<span class='mandatory'>*</span></td><td align='center'>:</td><td><select name='SampleSize' id='SampleSize'><option value=''></option><option value='2'>2</option><option value='3'>3</option><option value='5'>5</option><option value='8'>8</option><option value='13'>13</option><option value='20'>20</option><option value='32'>32</option><option value='50'>50</option><option value='80'>80</option><option value='125'>125</option><option value='200'>200</option><option value='315'>315</option><option value='500'>500</option><option value='800'>800</option><option value='1250'>1250</option><option value='0'>Custom</option></select></td>";
    }
    
    
    return;
}

function setAuditStageEmpty()
{
    if(document.getElementById("AuditStage").value != "")
    {
        if(document.getElementById("AuditStage").value != 2)
            document.getElementById("SampleSizeId").innerHTML = "<td id='TNCSSizes'>Sample Size<span class='mandatory'>*</span></td><td align='center'>:</td><td><select name='SampleSize' id='SampleSize'><option value=''></option><option value='2'>2</option><option value='3'>3</option><option value='5'>5</option><option value='8'>8</option><option value='13'>13</option><option value='20'>20</option><option value='32'>32</option><option value='50'>50</option><option value='80'>80</option><option value='125'>125</option><option value='200'>200</option><option value='315'>315</option><option value='500'>500</option><option value='800'>800</option><option value='1250'>1250</option><option value='0'>Custom</option></select></td>";
            
        document.getElementById("AuditStage").value = "";
    }
}