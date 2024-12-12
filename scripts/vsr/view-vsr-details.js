
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

function saveComments(iPoId)
{
	if ($("Comments" + iPoId).value == "")
	{
		alert("Please enter your Comments.");
		
		$("Comments" + iPoId).focus( );
		
		return;
	}

	$("Comments" + iPoId).disable( );
		
	var sUrl    = "ajax/vsr/save-vsr-comments.php"; 
	var sParams = ("PoId=" + iPoId + "&Comments=" + escape($("Comments" + iPoId).value));

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_saveComments });
}

function _saveComments(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		
		if (sParams[0] == "OK")
		{
			var iPoId     = sParams[1];
			var sComments = sParams[2];
			
			if (document.getElementById('NoRecord' + iPoId))
				$('NoRecord' + iPoId).hide( );
			
			new Insertion.Bottom(('Discussions' + iPoId), sComments);
		}
			
		else
			_showError(sParams[1]);

		$("Comments" + iPoId).enable( );
		$("Comments" + iPoId).value = "";
	}
	
	else
		_showError( );
}