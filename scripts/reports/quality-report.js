
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
 
function exportReport( )
{
	$('BtnSubmit').disabled = true;
	$('BtnExport').disabled = true;
	
	$('frmSearch').action = $('ExportUrl').value;
	$('frmSearch').submit( );
	
	setTimeout( function( )
	            {
	            	$('BtnSubmit').disabled = false;
	            	$('BtnExport').disabled = false;
	            	
	            	$('frmSearch').action = $('ReportUrl').value;
	            }
	            
	            , 10000);
}