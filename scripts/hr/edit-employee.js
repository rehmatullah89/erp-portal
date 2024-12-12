
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
	
	if (!objFV.validate("Name", "B", "Please enter your Full Name."))
		return false;

	if (!objFV.validate("City", "B", "Please enter your City."))
		return false;
		
	if (!objFV.validate("Country", "B", "Please select your Country."))
		return false;
		
	if (objFV.value("Picture") != "")
	{
		if (!checkImage(objFV.value("Picture")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Picture");
			objFV.select("Picture");

			return false;
		}
	}
		
	if (!objFV.validate("Mobile", "B", "Please enter your Mobile Number."))
		return false;				

	return true;
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


				  $("#RoutineActivities, #NonRoutineActivities").autocomplete(
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