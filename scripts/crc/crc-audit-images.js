
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
**  Software Engineer:                                                                         **
**                                                                                           **
**      Name  :  Rehmat Ullah			                                                     **
**      Email :  rehmatullah@3-tree.com		                                                 **
**      Phone :  +92 344 404 3675                                                            **
**      URL   :  http://www.apparelco.com                                                    **
**                                                                                           **
**  ***************************************************************************************  **
**                                                                                           **
**                                                                                           **
**                                                                                           **
**                                                                                           **
***********************************************************************************************
\*********************************************************************************************/


function getCategoriesList(iId, sChild, ScheduleId)
{
	if (iId == "")
		return;
        
        var sUrl    = "ajax/crc/get-crc-categories-list.php";
	var sParams = ("Id=" + iId + "&List=" + sChild+ "&ScheduleId=" + ScheduleId);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getResponseList });
}

function getPointsList(iId, sChild, ScheduleId)
{
	if (iId == "")
		return;

        var pChild  = document.getElementById('Section').value; 
        var sUrl    = "ajax/crc/get-crc-points-list.php";
	var sParams = ("Id=" + iId + "&List=" + sChild + "&Section=" + pChild+ "&ScheduleId=" + ScheduleId);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getResponseList });
}

function _getResponseList(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			var sChild = sParams[1];

			for (var i = 2; i < sParams.length; i ++)
			{
				var sOption = sParams[i].split("||");

				$(sChild).options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);

			}

			$(sChild).enable( );
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}