
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

function validateForm( )
{
	var objFV = new FormValidator("frmData");

        if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;

        if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

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

        if (!objFV.validate("SampleSize", "B", "Please select the Sample Size."))
                return false;
                   
        if (!objFV.validate("StyleNo", "B", "Please select the Style No."))
		return false;
            
	if (!objFV.validate("OrderNo[]", "B", "Please enter/select the Order No."))
		return false;

        if (!objFV.validate("Colors[]", "B", "Please select the Colors."))
			return false;

        if (!objFV.validate("Sizes[]", "B", "Please select the Sizes."))
                return false;        
	
	return true;
}


function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;

        if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

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

        if (!objFV.validate("SampleSize", "B", "Please select the Sample Size."))
                return false;
                   
        if (!objFV.validate("StyleNo", "B", "Please select the Style No."))
		return false;
            
	if (!objFV.validate("OrderNo[]", "B", "Please enter/select the Order No."))
		return false;

        if (!objFV.validate("Colors[]", "B", "Please select the Colors."))
			return false;

        if (!objFV.validate("Sizes[]", "B", "Please select the Sizes."))
                return false; 
        
	$('Processing').show( );

	var sUrl    = "ajax/quonda/update-booking.php";
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

                                                        
                                                            $('Brand_' + iId).innerHTML    = sParams[4];
                                                            $('Vendor_' + iId).innerHTML    = sParams[5];
                                                            $('Date_' + iId).innerHTML      = sParams[6];
                                                            $('StartTime_' + iId).innerHTML = sParams[7];
                                                            $('EndTime_' + iId).innerHTML   = sParams[8];
                                                        
						},

						2000
				  );
		}

		else
			_showError(sParams[1]);

		$('Processing').hide( );

		var objForm = $("frmData" + iId);
		objForm.enable( );                
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
