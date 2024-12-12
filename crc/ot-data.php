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
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$sVendorsList = getList("tbl_vendors v", "v.id", "CONCAT(COALESCE((SELECT CONCAT(vendor, ' &raquo;&raquo; ') FROM tbl_vendors WHERE id=v.parent_id), ''), v.vendor)", "v.id IN ({$_SESSION['Vendors']}) AND (v.category_id='4' OR v.category_id='5') AND v.sourcing='Y'");
	$sMonthsList  = array('January','February','March','April','May','June','July','August','September','October','November','December');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/ot-data.js"></script>
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
			    <h1>Over Time Data</h1>


			    <form name="frmData" id="frmData" method="post" action="crc/import-ot-data.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnImport').disabled=true;">
				<h2>Import OT File</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="55">Vendor<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Vendor">
						<option value=""></option>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
	  	        		<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Month<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Month">
						<option value=""></option>
<?
	for ($i = 1; $i <= 12; $i ++)
	{
?>
			            <option value="<?= $i ?>"><?= $sMonthsList[($i - 1)] ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Year<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
			          <select name="Year">
			            <option value=""></option>
<?
	for ($i = 2010; $i <= date("Y"); $i ++)
	{
?>
			            <option value="<?= $i ?>"><?= $i ?></option>
<?
	}
?>
		              </select>
					</td>
				  </tr>

				  <tr>
					<td>OT File<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="file" name="OtFile" value="" size="30" class="file" /></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnImport" value="" class="btnImport" title="Import" onclick="return validateForm( );" /></div>
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