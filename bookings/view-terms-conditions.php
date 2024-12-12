<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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
	**   Project Developer:                                                                      **
	**                                                                                           **
	**      Name  :  Rehmatullah Bhatti                                                          **
	**      Email :  rehmatullahbhatti@gmail.com                                                 **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id = IO::intValue('Id');
        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:544px; height:544px;">
	  <h2>Our Terms & Conditions</h2>

          <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="50%" valign="top">
				<table border="0" cellpadding="3" cellspacing="0" width="100%">

                                    <tr>
                                      <td width="10"><b style="color: darkgrey;">1</b></td>
					<td  width="10" align="center">:</td>
					<td>
                                            <p style="color: darkgrey;">Inspection booking must be made at least in Asia 5 working days and EMEA 7 days
                                            prior to the scheduled date of inspection. If air travelling is required, at least 10 working
                                            days’ notice is needed (travelling with Visa application have to be agreed).
                                            Please note working days’ means: Saturday, Sunday & Public Holiday is not included!</p>
					</td>
                                    </tr>
                                    <tr>
                                     <td><b style="color: darkgrey;">2</b></td>
                                       <td align="center">:</td>
                                       <td>
                                           <p style="color: darkgrey;">Late Booking up to the day (normal working day) before the requested service date, upon
                                           agreement only (fee will be charged)</p>
                                       </td>
                                    </tr>
                                    <tr>
                                     <td><b style="color: darkgrey;">3</b></td>
                                       <td align="center">:</td>
                                       <td>
                                           <p style="color: darkgrey;">Working Days: Mon-Sat but variable, based on local practice. On Sunday one extra
man-day will be charged.</p>
                                       </td>
                                    </tr>
                                    
                                    <tr>
                                     <td><b style="color: darkgrey;">4</b></td>
                                       <td align="center">:</td>
                                       <td>
                                           <p style="color: darkgrey;">Overtime will be charged: Hours worked outside of normal working hours (one Man-day
rate/8 hours).</p>
                                       </td>
                                    </tr>
                                    
                                    <tr>
                                     <td><b style="color: darkgrey;">5</b></td>
                                       <td align="center">:</td>
                                       <td>
                                           <p style="color: darkgrey;">There will be no inspections on Public Holidays.</p>
                                       </td>
                                    </tr>
                                    
                                     <tr>
                                     <td><b style="color: darkgrey;">6</b></td>
                                       <td align="center">:</td>
                                       <td>
                                           <p style="color: darkgrey;">Once inspection date is confirmed, any postponement or cancellation must be made at
least within 2 working days. Relevant costs will be charged.</p>
                                       </td>
                                    </tr>
                                    
                                    <tr>
                                     <td><b style="color: darkgrey;">7</b></td>
                                       <td align="center">:</td>
                                       <td>
                                           <p style="color: darkgrey;">The maximum liability of Hohenstein is confined to the charge of five (5) man-days for
the specific inspection/factory assessment service.</p>
                                       </td>
                                    </tr>
                                    
                                    <tr>
                                     <td><b style="color: darkgrey;">8</b></td>
                                       <td align="center">:</td>
                                       <td>
                                           <p style="color: darkgrey;">In case of defaults regarding the requirement for PPC, 20%PSI and 100%PSI or
inaccessible goods/ cartons an abortive report will be written.</p>
                                       </td>
                                    </tr>
                                </table><br/>
    </div>
<!--  Body Section Ends Here  -->

  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>