
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

var sLocations = new Array( );

document.observe('dom:loaded', function( )
{
	for (var i = 0; i < $('Location1').length; i ++)
		sLocations[(i - 1)] = new Array($('Location1').options[i].value, $('Location1').options[i].text);
});


function setLocation(iIndex)
{
	for (var i = (iIndex + 1); i <= 8; i ++)
	{
		if ($('Block' + i).style.display != "none")
		{
			$('Location' + i).selectedIndex  = 0;
			$('VisitLocation' + i).innerHTML = "-";

			new Effect.SlideUp("Block" + i);
		}
	}

	if (iIndex < 8 &&  $('Location' + iIndex).value != "")
	{
		if (iIndex == 1 || (iIndex > 1 && $('Location' + iIndex).value != $('Location' + (iIndex - 1)).value))
		{
			$('VisitLocation' + (iIndex + 1)).innerHTML = $('Location' + iIndex).options[$('Location' + iIndex).selectedIndex].text;

			new Effect.SlideDown("Block" + (iIndex + 1));
		}
	}
}


function filterList(sLetter)
{
	var iIndex = 0;

	for (var i = 1; i <= 8; i ++)
	{
		if ($('Block' + i).style.display != "none")
		{
			iIndex ++;

			continue;
		}

		else
			break;
	}


	var objLocations = $('Location' + iIndex);

	for (var i = (objLocations.options.length - 1); i > 0; i --)
		objLocations.options[i] = null;


	for (var i = 0, j = 1; i < sLocations.length; i ++)
	{
		if (sLocations[i][1].toLowerCase( ).charAt(0).toLowerCase( ) == sLetter.toLowerCase( ))
			objLocations.options[j ++] = new Option(sLocations[i][1], sLocations[i][0], false, false);
	}
}