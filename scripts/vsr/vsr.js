
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

function doSearch(sTab)
{
	$('Tab').value = sTab;

	$('BtnSearch').disabled = true;
	$('frmSearch').submit( );
}

function showSummary(iIndex)
{
	setTimeout( function( ) { $('Handle' + iIndex).hide( ); }, 900);
	
	Effect.toggle(('Summary' + iIndex), 'slide');
}

function hideSummary(iIndex)
{
	Effect.toggle(('Summary' + iIndex), 'slide');
	
	setTimeout( function( ) { $('Handle' + iIndex).show( ); }, 900);
}