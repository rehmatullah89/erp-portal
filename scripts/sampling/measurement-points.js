
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

	if (!objFV.validate("PointId", "B", "Please enter the Point ID."))
		return false;

	if (!objFV.validate("Point", "B", "Please enter the Measurement Point."))
		return false;
		
	if (!objFV.validate("Tolerance", "B", "Please enter the Tolerance."))
		return false;
		
	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;
		
	if (!objFV.validate("Category", "B", "Please select the Category."))
		return false;

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);
	
	if (!objFV.validate("PointId", "B", "Please enter the Point ID."))
		return false;

	if (!objFV.validate("Point", "B", "Please enter the Measurement Point."))
		return false;
		
	if (!objFV.validate("Tolerance", "B", "Please enter the Tolerance."))
		return false;
	
	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;
		
	if (!objFV.validate("Category", "B", "Please select the Category."))
		return false;

	$('Processing').show( );
	
	var sUrl    = "ajax/sampling/update-measurement-point.php"; 
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

					$('PointId' + iId).innerHTML   = sParams[3];
					$('Point' + iId).innerHTML     = sParams[4];
					$('Tolerance' + iId).innerHTML = sParams[5];					
					$('Category' + iId).innerHTML  = sParams[6];
					$('Brand' + iId).innerHTML     = sParams[7];
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