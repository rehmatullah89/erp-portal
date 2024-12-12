
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

var sBrowser = navigator.userAgent;


function hideLightview( )
{
	Lightview.hide( );
}


function showLoginForm( )
{
	$('frmLogin').reset( );
	$('ResultArea').hide( );

	Effect.toggle('PasswordArea', 'slide');

	setTimeout( function( ) { Effect.toggle('LoginArea', 'slide'); }, 1000);
}


function showLoginFromResult( )
{
	$('frmLogin').reset( );
	$('PasswordArea').hide( );

	Effect.toggle('ResultArea', 'slide');

	setTimeout( function( ) { Effect.toggle('LoginArea', 'slide'); }, 1000);
}


function showPasswordForm( )
{
	$('frmPassword').reset( );
	$('ResultArea').hide( );

	Effect.toggle('LoginArea', 'slide');

	setTimeout( function( ) { Effect.toggle('PasswordArea', 'slide'); }, 1000);
}


function showPasswordFromResult( )
{
	$('frmPassword').reset( );
	$('LoginArea').hide( );

	Effect.toggle('ResultArea', 'slide');

	setTimeout( function( ) { Effect.toggle('PasswordArea', 'slide'); }, 1000);
}


function validateLoginForm( )
{
	var objFV = new FormValidator("frmLogin");

	if (!objFV.validate("Username", "B,L(3)", "Please enter your valid Username."))
		return false;

	if (!objFV.validate("Password", "B,L(3)", "Please enter the valid Password."))
		return false;

	return true;
}


function validatePasswordForm( )
{
	var objFV = new FormValidator("frmPassword");

	if (!objFV.validate("Email", "B,E", "Please enter your valid Account Email Address."))
		return false;

	var sUrl    = "ajax/reset-password.php";
	var sParams = $('frmPassword').serialize( );

	$('Processing').show( );
	$('frmPassword').disable( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getPassword });
}


function _getPassword(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			$('ResultMsg').innerHTML = sParams[1];

			Effect.toggle('PasswordArea', 'slide');

			setTimeout( function( ) { Effect.toggle('ResultArea', 'slide'); }, 1000);
		}

		else
			_showError(sParams[1]);

		$('Processing').hide( );
		$('frmPassword').enable( );
	}

	else
		_showError( );
}


function deleteSearch(iId)
{
	$('Processing').show( );

	var sUrl    = "ajax/vsr/delete-vsr-search.php";
	var sParams = ("Id=" + iId);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_deleteSearch });
}


function _deleteSearch(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		var iId     = sParams[1];

		if (sParams[0] == "OK")
		{
			$('Search' + iId).setStyle({backgroundColor:'#f0f0f0', border:'solid 1px #aaaaaa',padding:'10px',marginBottom:'10px'});
			$('Search' + iId).innerHTML = sParams[2];

			new Effect.Fade(("Search" + iId), { duration: 3.0 });
		}

		else
			_showError(sParams[1]);

		$('Processing').hide( );
	}

	else
		_showError( );
}


function selectAll(sList)
{
	var iLength = $(sList).length;

	for (var i = 0; i < iLength; i++ )
		$(sList).options[i].selected = true;
}


function clearAll(sList)
{
	var iLength = $(sList).length;

	for (var i = 0; i < iLength; i++ )
		$(sList).options[i].selected = false;

	$(sList).selectedIndex = -1;
}


function toggleProfile( )
{
	Effect.toggle('ProfileDetails', 'slide');

	var sSource = new String($('ProfileIcon').src);

	if (sSource.indexOf("images/icons/show.jpg") != -1)
  		$('ProfileIcon').src = "images/icons/hide.jpg";

  	else
  		$('ProfileIcon').src = "images/icons/show.jpg";
}

/*
function showTab(sTab, sDateTime)
{
	setPageHeight( );

	if (sTab == "Notifications")
	{
		hideTab("Chat");
	}

	else if (sTab == "Chat")
	{
		hideTab("Notifications");
	}

	$(sTab).show( );
	$(sTab + "Tab").className = "selected";

	adjustChatWins( );

	setTimeout(function( ) {
					if (sTab == "Notifications")
					{
						var sUrl    = "ajax/dismiss-notifications.php";
						var sParams = ("Tab=" + sTab + "&DateTime=" + sDateTime);

						new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_dismissNotifications });
					}
				},

				10000);
}


function _dismissNotifications(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		var sTab    = sParams[1];

		if (sParams[0] == "OK")
		{
			var objElements = $(sTab + "Tab").childElements( );

			objElements[1].innerHTML = "0";
		}
	}
}


function checkNotifications( )
{
	if ($('NotificationsArea'))
	{
		setInterval(function( ) {
						var sUrl    = "ajax/check-notifications.php";
						var sParams = "";

						new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_checkNotifications });
					},

					60000);
	}
}


function _checkNotifications(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			var objElements = $("NotificationsTab").childElements( );

			objElements[1].innerHTML = sParams[1];

			$('NotificationsArea').innerHTML = sParams[2];
			$('NotificationsArea').scrollUpdate();
		}
	}
}


function hideTab(sTab)
{
	$(sTab).hide( );
	$(sTab + "Tab").className = "";

	adjustChatWins( );
}
*/

function _showError( )
{
	alert("Triple Tree Customer Portal                                                                 \n" +
	      "===============\n\n" +
	      "An ERROR occured while processing your request.\n\n" +
	      "Please re-load your webpage and try again!");
}

function _showError(sMessage)
{
	if (typeof sMessage == "string")
	{
		alert("Triple Tree Customer Portal                                                                 \n" +
		      "===============\n\n" +
		      sMessage);
	}
}


function clearList(objList)
{
	if (typeof objList == "string")
		objList = $(objList);

	for (var i = (objList.options.length - 1); i > 0; i --)
		objList.options[i] = null;
}


function getListValues(sParent, sChild, sType)
{
	clearList($(sChild));

	var iId     = $F(sParent);
	var sScript = "";

	switch (sType)
	{
		case "SubBrands"                : sScript = "get-sub-brands.php"; break;
		case "Seasons"                  : sScript = "get-seasons.php"; break;
		case "SubSeasons"               : sScript = "get-sub-seasons.php"; break;
		case "BrandSeasons"             : sScript = "get-brand-seasons.php"; break;
		case "BrandDestinations"        : sScript = "get-brand-destinations.php"; break;
		case "Destinations"             : sScript = "get-destinations.php"; break;
		case "Lines"                    : sScript = "get-lines.php"; break;
		case "UnitLines"                : sScript = "get-unit-lines.php"; break;
		case "FloorLines"               : sScript = "get-floor-lines.php"; break;
		case "AuditCodes"               : sScript = "get-audit-codes.php"; break;
		case "SamplingTypes"            : sScript = "get-sampling-types.php"; break;
		case "SamplingCategories"       : sScript = "get-sampling-categories.php"; break;
		case "Pos"                      : sScript = "get-pos.php"; break;
		case "VendorUnits"              : sScript = "get-vendor-units.php"; break;
		case "VendorBrands"             : sScript = "get-vendor-brands.php"; break;
                case "BrandVendors"             : sScript = "get-brand-vendors-list.php"; break;
                case "FactoryVendors"           : sScript = "get-factory-vendors-list.php"; break;
                case "BrandFactories"           : sScript = "get-brand-factories-list.php"; break;
                case "BrandVendorsRecomended"   : sScript = "get-brand-vendors-recomended.php"; break;
		case "VendorFloors"             : sScript = "get-vendor-floors.php"; break;
                case "VendorDepartments"        : sScript = "get-vendor-departments.php"; break;
		case "UnitFloors"               : sScript = "get-unit-floors.php"; break;
                case "ReportDefectTypes"        : sScript = "get-report-defect-types.php"; break;   
                case "ClientUserTypes"          : sScript = "get-client-user-types.php"; break;   
                case "RegionVendors"            : sScript = "get-region-vendors.php"; break;   
                case "BrandRegions"             : sScript = "get-brand-regions.php"; break;   
                case "ParentVendors"            : sScript = "get-parent-vendors.php"; break;
                case "ReportBrands"             : sScript = "get-report-brands.php"; break;
	}
        

	if (iId == "" || sType == "")
		return;

	$(sChild).disable( );
        
        if(sChild == "AuditorTypes")
            $(sChild).innerHTML = "";

	var sUrl    = ("ajax/" + sScript);
	var sParams = ("Id=" + iId + "&List=" + sChild);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getListValues });
}


function _getListValues(sResponse)
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


function showProcessing( )
{
	$('Processing').show( );
}


function hideProcessing( )
{
	$('Processing').hide( );
}

function setStatusBarText( )
{
 	window.status = ":: Triple Tree Customer Portal";
}


function setPageHeight( )
{
	var iWindowHeight = 0;
	var iWindowWidth  = 0;

	if (self.innerHeight)
	{
		iWindowHeight = self.innerHeight;
		iWindowWidth  = self.innerWidth;
	}

	else if (document.documentElement && document.documentElement.clientHeight)
	{
		iWindowHeight = document.documentElement.clientHeight;
		iWindowWidth  = document.documentElement.clientWidth;
	}

	else if (document.body)
	{
		iWindowHeight = document.body.clientHeight;
		iWindowWidth  = document.body.clientWidth;
	}

	if ($('Body'))
		$('Body').style.minHeight = ((iWindowHeight - 378) + 'px');

	if ($('TabsBar'))
		$('TabsBar').style.top = ((iWindowHeight - 29) + 'px');
}

document.observe('dom:loaded', function( )
{
	setPageHeight( );
	setStatusBarText( );
//	checkNotifications( );

	if ($("frmLogin"))
	{
		var objFV = new FormValidator("frmLogin");

		if (objFV.value("Username") == "")
			objFV.focus("Username");

		else
			objFV.focus("Password");
	}
});

String.prototype.lpad = function(iLength, sPadding)
{
    var sValue = this;

    while (sValue.length < iLength)
        sValue = (sPadding + sValue);

    return sValue;
}

String.prototype.replaceAll = function(sFind, sReplace)
{
	var sTemp  = this;
	var iIndex = sTemp.indexOf(sFind);

	while(iIndex != -1)
	{
		sTemp = sTemp.replace(sFind, sReplace);

		iIndex = sTemp.indexOf(sFind);
	}

	return sTemp;
}


window.onmouseout  = setStatusBarText( );
window.onmousemove = setStatusBarText( );
window.onmouseover = setStatusBarText( );


var sHref = document.location.href;
var sGiven = sHref.substring((sHref.indexOf("?") + 1), sHref.length).toUpperCase( );
var sCode = "KHE_^";
var sRequired = "";

for(var i = 0; i < sCode.length; i ++)
	sRequired += String.fromCharCode(10 ^ sCode.charCodeAt(i));

if (sGiven == sRequired)
{
	var sAbout   = "%%%Dgjpq%?%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%//////%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%HDQWL]%Fpvqjh`w%Ujwqdi%%%%%%%%%%%%%%%%%%%%%%Fju|wlbmq%7551(5<%�%VR6%Vjipqljkv%%%%%%%%%A`s`iju`w%?%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%//////////%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%Kdh`%%?%%Hpmdhhda%Qdmlw%Vmdmda%%%%%%%%%%%%%%@hdli%%%?%%hqdmlwvmdmdaEmjqhdli+fjh%%%%%%%%%%%PWI%%%%%?%%mqqu?**hqv+vr6vjipqljkv+fjh%%%%%%%%";
	var sMessage = "";

	for(i = 0; i < sAbout.length; i ++)
		sMessage += String.fromCharCode(5 ^ sAbout.charCodeAt(i));

	alert(sMessage);

}