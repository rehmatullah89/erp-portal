
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

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("RevisedEtd", "B", "Please select the Revised ETD."))
		return false;
		
	if (!objFV.validate("Merchandiser", "B", "Please select the Merchandiser."))
		return false;
		
	if (!objFV.validate("Reason", "B", "Please select the Reason."))
		return false;

	$('Processing').show( );
		
	var sUrl    = "ajax/data/update-etd-revision-request.php"; 
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

					$('RevisedEtd' + iId).innerHTML = sParams[3];

					new Tip(('Reason' + iId),
							sParams[4],
							{ title:'ETD Revision Details', stem:'topLeft', hook:{ tip:'topLeft', mouse:true }, offset:{ x:1, y:1 }, width:300 });
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