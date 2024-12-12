
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

	if (!objFV.validate("BlockName", "B", "Please enter the Block Name."))
		return false;
		
	if (!objFV.validate("CountryCodes", "B", "Please select Country Codes."))
		return false;
	
	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("BlockName", "B", "Please enter the Block Name."))
            return false;
		
	 if (jQuery("#CountryCodes"+iId+" option:selected").val() == "")
            return false;

	$('Processing').show( );

        var sCountryCodes = "";
        var iCountryCodes = jQuery('#CountryCodes'+iId).val(); 
        
        for(i=0; i<iCountryCodes.length; i++)
        {
            if(i == (iCountryCodes.length-1))
                sCountryCodes = (sCountryCodes + iCountryCodes[i]);
            else
                sCountryCodes = (sCountryCodes + (iCountryCodes[i]+","));
        }
        
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
                else if(elementName.indexOf('CountryCodes') != -1)
                {
                   DestFormData.append(formElements[i].name, sCountryCodes);
                } 
                else
                    DestFormData.append(formElements[i].name, formElements[i].value);
                                
            }
            
        (function($) {
          $(function() {

                $.ajax({
                    url: "ajax/data/update-country-block.php",
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

					$('BlockName' + iId).innerHTML = sParams[3];
					$('CountryCodes[]' + iId).innerHTML  = sParams[4];
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