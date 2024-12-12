
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

var bSound = false;

/*
soundManager.url = "sounds/";

soundManager.onready(function(oStatus)
{
	if (!oStatus.success)
		return false;


  	bSound = true;

	soundManager.createSound( { id:'Sent', url:'sounds/sent.mp3' } );
	soundManager.createSound( { id:'Received', url:'sounds/received.mp3' } );
});
*/

Cookie.init( { name:'Chat', path:'/' }, { UserId:'0' } );



/*
function adjustChatWins( )
{
	var objChatWins = $$('div.chatWin');

	objChatWins.each( function(objElement)
			  {
				if ($('Chat').style.display == "none" && $('Notifications').style.display == "none")
				{
					if ( (sBrowser.indexOf("MSIE 6.0") != -1 || sBrowser.indexOf("MSIE 5.5") != -1) && sBrowser.indexOf("MSIE 8.0") == -1 && sBrowser.indexOf("MSIE 7.0") == -1)
						objElement.style.left = "-78px";

					else
						objElement.style.left = "-117px";

					objElement.style.top  = "-313px";
				}

				else
				{
					objElement.style.left = "-137px";
					objElement.style.top  = "-340px";
				}
			  } );
}


function showChatWin(sUserId)
{
	hideAllChatWins( );
	Cookie.setData('UserId', sUserId);

	if ($('ChatWin' + sUserId))
	{
		adjustChatWins( );

		$('ChatWin' + sUserId).show( );
	}

	else
	{
		var sUrl    = "ajax/create-chat-window.php";
		var sParams = ("Id=" + sUserId);

		new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_showChatWin });
	}
}


function _showChatWin(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sResponse.responseText == "" || sParams.length < 2)
			return;

		var sUserId  = sParams[0];
		var sMessage = sParams[1];

		new Insertion.Bottom('TabsArea', sMessage);

		$('ChatWinArea' + sUserId).scrollTop = $('ChatWinArea' + sUserId).scrollHeight;
		$('Message' + sUserId).focus( );

		adjustChatWins( );
	}
}


function hideChatWin(sWinId)
{
	$('ChatWin' + sWinId).hide( );
	Cookie.setData('UserId', '0');
}


function hideAllChatWins( )
{
	var objChatWins = $$('div.chatWin');

	objChatWins.each( function(objElement) { objElement.hide( ); } );
}


function sendChatMessage(sUserId)
{
	if ($('Message' + sUserId).value == "")
		return false;

	var sUrl    = "ajax/save-chat-message.php";
	var sParams = $('frmChatWin' + sUserId).serialize( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_sendChatMessage });

	return false;
}


function _sendChatMessage(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		var sUserId  = sParams[0];
		var sMessage = sParams[1];
		var sError   = sParams[2];

		if (sUserId != "ERROR")
		{
			new Insertion.Bottom(('ChatWinArea' + sUserId), sMessage);

			$('ChatWinArea' + sUserId).scrollTop = $('ChatWinArea' + sUserId).scrollHeight;

			$('Message' + sUserId).value = "";

//			if (bSound == true)
//				soundManager.play('Sent');
		}

		else
			_showError(sError);


		$('Message' + sUserId).focus( );
	}

	else
		_showError( );
}


setInterval( function( )
	     {
			if ($('Chat'))
			{
				var sUrl    = "ajax/check-chat-messages.php";
				var sParams = "";

				new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_checkChatMessages });
			}
	     },

	     60000);


function _checkChatMessages(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		if (sResponse.responseText != "No-Msg")
		{
			var sParams = sResponse.responseText.split('|--|');

			for (var i = 0; i < sParams.length; i ++)
			{
				var sData    = sParams[i].split('|-|');
				var sUserId  = sData[0];
				var sMessage = sData[1];


				if ($('ChatWin' + sUserId))
				{
					hideAllChatWins( );

					$('ChatWin' + sUserId).show( );

					new Insertion.Bottom(('ChatWinArea' + sUserId), sMessage);

					$('ChatWinArea' + sUserId).scrollTop = $('ChatWinArea' + sUserId).scrollHeight;
				}

				else
					showChatWin(sUserId);

//				if (bSound == true)
//					soundManager.play('Received');
			}
		}
	}
}


setInterval(function( ) {
				if ($('Chat'))
				{
					var sUrl    = "ajax/check-online-users.php";
					var sParams = "";

					new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_checkOnlineUsers });
				}
			},

			60000);


function _checkOnlineUsers(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		$('OnlineUsers').innerHTML = sParams[0];
		$('ChatArea').innerHTML    = sParams[1];
	}
}


function restoreChatSession( )
{
	if (Cookie.getData('UserId') != "0")
		showChatWin(Cookie.getData('UserId'));

	else
		hideAllChatWins( );
}


document.observe('dom:loaded', function( )
{
	restoreChatSession( );
});
*/