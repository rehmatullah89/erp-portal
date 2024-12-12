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

	@require_once("../requires/session.php");
	@require_once($sBaseDir."requires/chart.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


        $Year     = (IO::strValue("Year") != ""?IO::strValue("Year"):date("Y"));
	$FromDate = "01/{$Year}";
	$ToDate   = "12/{$Year}";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
    <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css" />
    <link href="css/monthpicker.css" rel="stylesheet" type="text/css"/>
   
    <script type="text/javascript" src="scripts/jquery.js"></script>  
    <script type="text/javascript" src="scripts/reports/mgf-report.js"></script>    
    <script src="scripts/monthpicker.min.js"></script>


  <script>
      
    jQuery.noConflict( );
      
    jQuery(document).ready(function($)
    {
        $('#startDate').Monthpicker({
            onSelect: function () {
                $('#endDate').Monthpicker('option', { minValue: $('#startDate').val() });
            }
        });
        
        $('#endDate').Monthpicker({
            onSelect: function () {
                $('#startDate').Monthpicker('option', { maxValue: $('#endDate').val() });
            }
        });

    });
    
</script>
</head>

<body>

<div id="MainDiv">
  <div id="PageLeftBorder">
    <div id="PageRightBorder">

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

      <div id="PageContents">

<!--  Header Section Starts Here  -->
<?
	@include($sBaseDir."includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include($sBaseDir."includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
	    <div id="Body">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1>j.crew placements report</h1>

			    <form name="frmSearch" id="frmSearch" method="post" action="<?= SITE_URL.'reports/export-jcrew-placement-report.php' ?>" class="frmOutline" onsubmit="checkDoubleSubmission( );">
				<h2>J.Crew Placement Reports</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
                                    <tr valign="top">
					<td width="80">Factory</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Vendor[]" multiple size="10" style="width:300px;">
<?
        $sVendorsList = getList("tbl_vendors v", "v.id", "CONCAT(COALESCE((SELECT CONCAT(vendor, ' &raquo;&raquo; ') FROM tbl_vendors WHERE id=v.parent_id), ''), v.vendor) AS _Vendor", "FIND_IN_SET(v.id, '{$_SESSION['Vendors']}') AND v.mgf='N' AND v.levis='N'", "_Vendor");

	foreach($sVendorsList as $sKey=> $sValue)
	{
?>
	  	        		<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

                                    <!--<tr>
					<td>Report Date</td>
					<td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="320">
						<tr>
						  <td width="78"><input type="text" name="FromDate" value="<?// $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'mm/yyyy', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'mm/yyyy', this);" /></td>
						  <td width="30" align="center">to</td>
						  <td width="78"><input type="text" name="ToDate" value="<?// $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'mm/yyyy', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'mm/yyyy', this);" /></td>
						  <td align="right"></td>
						</tr>
					  </table>

					</td>
				  </tr>-->
                                    <tr>
                                        <td>Report Date</td>
					<td align="center">:</td>

                                        <td>
                                            <div class="container" style="color: white;">
                                                <input id="startDate" name="FromDate" value="<?= $FromDate ?>" type="text" />
                                            <input id="endDate" name="ToDate" value="<?= $ToDate ?>" type="text" />
                                            </div>
                                        </td>
                                    </tr>
                                  
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" value="" id="BtnExport" class="btnExport" title="Export" />
				</div>
			    </form>
			  </td>
			</tr>
		  </table>

<?
	@include($sBaseDir."includes/my-profile.php");
?>
        </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include($sBaseDir."includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

      </div>
    </div>
  </div>
</div>

<!--  Bottom Bar Section Starts Here  -->
<?
	@include($sBaseDir."includes/bottom-bar.php");
?>
<!--  Bottom Bar Section Ends Here  -->
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>