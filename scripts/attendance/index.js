
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

setInterval("setDateTime( )", 500);

function setDateTime( )
{
	var iCountry = $('Country').value;

	var sDays   = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
	var sMonths = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

	var objDate = new Date( );
	
	var iDay     = eval(objDate.getDay( ));
	var iDate    = eval(objDate.getDate( ));
	var iMonth   = eval(objDate.getMonth( ));
	var iYear    = eval(objDate.getFullYear( ));
	var iHours   = eval(objDate.getHours( ));
	var iMinutes = eval(objDate.getMinutes( ));
	var iSeconds = eval(objDate.getSeconds( ));
	var sPostfix = "th";
	var sHours   = iHours;
	var sMinutes = iMinutes;
	var sSeconds = iSeconds;
	var sAmPm    = "AM";
/*	
	if (iHours == 23 && iMinutes == 59 && iSeconds == 1 && iCountry == 162)
	{
		document.location = $('SignOffUrl').value;

		return;
	}
*/
	
	if (iDate == 1 || iDate == 21 || iDate ==31)
		sPostfix = "st";

	else if (iDate == 2 || iDate == 22)
		sPostfix = "nd";

	else if (iDate == 3 || iDate == 23)
		sPostfix = "rd";
		

	if (iHours >= 12)
		sAmPm = "PM";

	if (iHours == 0)
		sHours = 12;

	else if (iHours > 12)
		sHours = (iHours - 12);

	if (eval(sHours) < 10)
		sHours = ("0" + sHours);

	if (iMinutes < 10)
		sMinutes = ("0" + iMinutes);
		
	if (iSeconds < 10)
		sSeconds = ("0" + iSeconds);


	if ($('Today'))
	{
		$('Today').innerHTML = (sDays[iDay] + " " + iDate + "<sup style='font-weight:normal;'>" + sPostfix + "</sup> " + sMonths[iMonth] + ", " + iYear);
		$('Time').innerHTML  = (sHours + ":" + sMinutes + ":" + sSeconds);
		$('AmPm').innerHTML  = sAmPm;
	}
	
	if ($('CardId'))
		$('CardId').focus( );
}

setTimeout( function( ) { document.location.reload( ); }, 600000);