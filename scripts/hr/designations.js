
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

	if (!objFV.validate("Department", "B", "Please select the Department."))
		return false;
		
	if (!objFV.validate("Designation", "B", "Please enter the Designation."))
		return false;

	return true;
}


function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Department", "B", "Please select the Department."))
		return false;

	if (!objFV.validate("Designation", "B", "Please enter the Designation."))
		return false;

	$('Processing').show( );
	
	var sUrl    = "ajax/hr/update-designation.php"; 
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

					$('Designation' + iId).innerHTML = sParams[3];
					$('Department' + iId).innerHTML  = sParams[4];
					$('ReportingTo' + iId).innerHTML = sParams[5];
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



$.noConflict( );

var sAbbreviations = [];
var sAutoOptions   = new Array( );

jQuery(document).ready(function($)
{
	$.ajax("ajax/get-abbreviations.php",
	       { success: function(data, textStatus, jqXHR)
			  {
				  sAbbreviations = data.replace(/\r/g, "" ).split("\n");

				  for( var i=0; i<sAbbreviations.length; i++ )
				  {
					var sTemp = sAbbreviations[i].split("|");

					sAutoOptions[i] = new Array();
					
					sAutoOptions[i][0] = sTemp[0];
					sAutoOptions[i][1] = sTemp[1];
				  }


				  $("form textarea").autocomplete(
				  {
							 wordCount : 1,
							 on        : { query: function(text, cb)
									      {
									      	if (text.length <= 1)
									      		return;

										  var words = [];

										  for( var i=0; i<sAutoOptions.length; i++ )
										  {
											  if( sAutoOptions[i][0].toLowerCase().indexOf(text.toLowerCase()) == 0 )
												  words.push(sAutoOptions[i][1]);

											  if ( words.length > 5 )
												  break;
										  }

										  cb(words);
										}
								       }
						});
		   }
	});
});