
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

	if (!objFV.validate("Section", "B", "Please enter Section Name."))
		return false;

	return true;
}


/*function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Section", "B", "Please enter Section Name."))
		return false;

	$('Processing').show( );

	var sUrl    = "ajax/quonda/update-statement.php";
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

					$('Statement' + iId).innerHTML   = sParams[3];
                                        $('Sections' + iId).innerHTML   = sParams[4];
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
}*/

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Section", "B", "Please enter Section Name."))
		return false;
    
	$('Processing').show( );
        
        var SectionsFormData = new FormData();     
        var formElements=document.getElementById('frmData' + iId).elements;    
     
        for (var i=0; i<formElements.length; i++)
            if (formElements[i].type!="submit" && formElements[i].type!="button")
            {
                var elementName = formElements[i].name;
                
                if (elementName.indexOf('IconImage') != -1)
                {
                    //console.log(jQuery('#IconImage'+iId)[0].files);
                   jQuery.each(jQuery('#IconImage'+iId)[0].files, function(j, file) {
                        SectionsFormData.append('IconImage'+j, file);
                    });
                    //SectionsFormData.append('IconImage', jQuery('#IconImage'+iId)[0].files);                     
                }
                else
                    SectionsFormData.append(formElements[i].name, formElements[i].value);                
            }
            
        (function($) {
          $(function() {

                $.ajax({
                    url: "ajax/quonda/update-section.php",
                    data: SectionsFormData,
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

						$('Section' + iId).innerHTML         = sParams[3];
				    },

				    2000
				  );
		}

		else if (sParams[0] == "INFO")
			_showError(sParams[2]);

		else
			_showError(sParams[1]);

		$('Processing').hide( );
                setTimeout(function(){
                    window.location.reload(1);
                 }, 3000);
	}

	else
		_showError( );
}