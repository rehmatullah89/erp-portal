
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
jQuery.noConflict();
function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Destination", "B", "Please enter the Destination."))
		return false;
		
	if (!objFV.validate("Region", "B", "Please select the Region."))
		return false;
		
	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;
		
	if (!objFV.validate("Type", "B", "Please select the Destination Type."))
		return false;

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Destination", "B", "Please enter the Destination."))
		return false;
		
	if (!objFV.validate("Region", "B", "Please select the Region."))
		return false;
		
	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;
		
	if (!objFV.validate("Type", "B", "Please select the Destination Type."))
		return false;

	$('Processing').show( );
/*
        var formElements=document.getElementById('frmData' + iId).elements;    
        var postData="";
        for (var i=0; i<formElements.length; i++)
            if (formElements[i].type!="submit" && formElements[i].type!="button")
                    postData += (formElements[i].name+"="+formElements[i].value+"&");
            
        postData = postData.replace(/&\s*$/, "");
        
	var sUrl    = "ajax/data/update-destination.php"; 
	var sParams = $('frmData' + iId).serialize( );
	
	var objForm = $("frmData" + iId); 
	objForm.disable( );
	
	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_updateData });*/

        var DestFormData = new FormData();     
        var formElements=document.getElementById('frmData' + iId).elements;    
     
        for (var i=0; i<formElements.length; i++)
            if (formElements[i].type!="submit" && formElements[i].type!="button")
            {
                var elementName = formElements[i].name;
                
                if (elementName.indexOf('Picture') != -1)
                {
                   DestFormData.append(formElements[i].name, jQuery("#Picture"+iId)[0].files[0]);
                }                    
                else
                    DestFormData.append(formElements[i].name, formElements[i].value);
                
                
            }
            
        (function($) {
          $(function() {

                $.ajax({
                    url: "ajax/data/update-destination.php",
                    data: DestFormData,
                    type: "POST",
                    processData: false,  
                    contentType: false, 
                    cache : false,
                    success: function(sResponse){

                       _updateData(sResponse);                       
                    },
                    error: function(){
                        _showError();
                    }                 
               });
          });
        })(jQuery);
           ////////////         
}

function _updateData(sResponse)
{
	if (sResponse != "")
	{
		var sParams = sResponse.split('|-|');
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

					$('Destination' + iId).innerHTML = sParams[3];
					$('Region' + iId).innerHTML      = sParams[4];
					$('Brand' + iId).innerHTML       = sParams[5];
					$('Type' + iId).innerHTML        = sParams[6];
				    },				    
				    
				    2000
				  );
		}
		
		else if (sParams[0] == "INFO")
			_showError(sParams[2]);
			
		else
			_showError(sParams[1]);
			
		$('Processing').hide( );
                location.reload();

		var objForm = $("frmData" + iId); 
		objForm.enable( );
	}
	
	else
		_showError( );
}