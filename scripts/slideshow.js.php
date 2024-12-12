<?
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

	@ini_set('display_errors', 0);

print '
// Permalinks option
function flashPutHref(sHref)
{
	location.href = sHref;
}

// slideshowpro methods
function showAlbum(sAlbumId, iImageNo)
{
	thisMovie("ssp").loadAlbum(sAlbumId, iImageNo);
}

function showImage(iImageNo)
{
	thisMovie("ssp").loadImageNumber(iImageNo);
}

// swf finder
function thisMovie(movieName)
{
	if (navigator.appName.indexOf("Microsoft") != -1)
		return window[movieName]

	else
	    return document[movieName]
}

// SWFObject embed
var sFlashVars = {
			paramXMLPath : "param.xml.php?Id='.$_REQUEST['Id'].'",
			initialURL   : escape(document.location)
		 };

var sParams = {
		base            : ".",
		quality         : "best",
		bgcolor         : "#121212",
		allowfullscreen : "true",
		wmode           : "transparent"
	      };

var sAttributes = {
			id : "ssp"
		  };

document.observe("dom:loaded", function( )
{
	initSlideShow( );
});

function initSlideShow( )
{
	setTimeout(function( ) { swfobject.embedSWF("movies/slideshow.swf", "SlideShow", "581", "471", "9.0.0", false, sFlashVars, sParams, sAttributes); }, 2000);
}';
?>