
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
**  Software Engineer:                                                                       **
**                                                                                           **
**      Name  :  Rehmat Ullah   	                                                     **
**      Email :  rehmatullah@3-tree.com 	                                             **
**      Phone :  +92 344 404 3675                                                            **
**      URL   :  http://www.apparelco.com                                                    **
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

	if (!objFV.validate("PreprationName", "B", "Please enter the Prepration Name."))
		return false;
	
	if (!objFV.validate("FormulationName", "B", "Please enter the Formulation Name."))
		return false;
	
	if (!objFV.validate("CompoundId", "B", "Please select the Compound."))
		return false;
        
        if (!objFV.validate("LocationId", "B", "Please select the Location."))
		return false;    
        

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("PreprationName", "B", "Please enter the Prepration Name."))
		return false;
	
	if (!objFV.validate("FormulationName", "B", "Please enter the Formulation Name."))
		return false;
	
	if (!objFV.validate("CompoundId", "B", "Please select the Compound."))
		return false;
		
        if (!objFV.validate("LocationId", "B", "Please select the Compound."))
		return false;

	$('Processing').show( );
		
	var sUrl    = "ajax/crc/update-chemical-inventory.php"; 
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

						$('PreprationName' + iId).innerHTML = sParams[3];
						$('FormulationName' + iId).innerHTML   = sParams[4];
						$('CompoundId' + iId).innerHTML   = sParams[5];
				    },				    
				    
				    2000
				  );
		}
		
		else if (sParams[0] == "INFO")
			_showError(sParams[2]);
			
		else
			_showError(sParams[1]);
			
		$('Processing').hide( );

		var objForm = $("frmData" + iId); 
		objForm.enable( );
	}
	
	else
		_showError( );
}