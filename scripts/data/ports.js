
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

	if (!objFV.validate("PortName", "B", "Please enter the Port Name."))
		return false;
		
	if (!objFV.validate("Picture", "B", "Please select the Port Icon."))
		return false;
        
        if (objFV.value("PdfSymbol") != "")
	{
		if (!checkImage(objFV.value("PdfSymbol")))
		{
			alert("Invalid File Format. Please select a jpg File.");

			objFV.focus("XmlFile");
			objFV.select("XmlFile");

			return false;
		}
	}
        
	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("PortName", "B", "Please enter the Port Name."))
		return false;
            
        if (objFV.value("PdfSymbol") != "")
	{
		if (!checkImage(objFV.value("PdfSymbol")))
		{
			alert("Invalid File Format. Please select a jpg File.");

			objFV.focus("XmlFile");
			objFV.select("XmlFile");

			return false;
		}
	}    

	$('Processing').show( );


        var DestFormData = new FormData();     
        var formElements=document.getElementById('frmData' + iId).elements;    
     
        for (var i=0; i<formElements.length; i++)
            if (formElements[i].type!="submit" && formElements[i].type!="button")
            {
                var elementName = formElements[i].name;
                
                if (elementName.indexOf('PdfSymbol') != -1)
                {
                   DestFormData.append(formElements[i].name, jQuery("#PdfSymbol"+iId)[0].files[0]);
                }
                
                if (elementName.indexOf('Picture') != -1)
                {
                   DestFormData.append(formElements[i].name, jQuery("#Picture"+iId)[0].files[0]);
                }
                
                if (elementName.indexOf('PdfSymbol') == -1 && elementName.indexOf('PdfSymbol') == -1)
                    DestFormData.append(formElements[i].name, formElements[i].value);
                
            }
            
        (function($) {
          $(function() {

                $.ajax({
                    url: "ajax/data/update-port.php",
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

					$('PortName' + iId).innerHTML = sParams[3];
				    },				    
				    
				    2000
				  );
		}
		
		else if (sParams[0] == "INFO")
			_showError(sParams[2]);
			
		else
			_showError(sParams[1]);
			
		$('Processing').hide( );
                //location.reload();

		var objForm = $("frmData" + iId); 
		objForm.enable( );
	}
	
	else
		_showError( );
}