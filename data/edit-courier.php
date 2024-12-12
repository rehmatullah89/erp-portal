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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");


	$Id      = IO::intValue('Id');
	$Referer = IO::strValue("Referer");

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];


	$sSQL = "SELECT * FROM tbl_couriers WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$AirwayBill = $objDb->getField(0, "awb_no");
		$Company    = $objDb->getField(0, "company");
		$Type      	= $objDb->getField(0, "type");
		$Employee   = $objDb->getField(0, "user_id");
		$Country  	= $objDb->getField(0, "country_id");
		$Address   = $objDb->getField(0, "address");
		$Date     	= $objDb->getField(0, "date");
	}

	else
		redirect($Referer, "DB_ERROR");

	$sEmployeesList = getList("tbl_users", "id", "name", "status='A'");
	$sCountriesList = getList("tbl_countries", "id", "country");
	$sCompaniesList = array("TCS", "DHL", "OCS", "FEDEX");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/edit-courier.js"></script>
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
			    <h1><img src="images/h1/data/couriers.jpg" width="132" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="data/update-courier.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />

				<h2>Edit Courier Item</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="100">Airway Bill #<span class="mandatory">*</span></td>
					<td  width="20" align="center">:</td>
					<td><input type="text" name="AirwayBill" value="<?= $AirwayBill ?>" maxlength="250" size="52" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Company<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Company">
					    <option value=""></option>
<?
	foreach ($sCompaniesList as $sCompany)
	{
?>
			            <option value="<?= $sCompany ?>"<?= (($sCompany == $Company) ? " selected" : "") ?>><?= $sCompany ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Courier Type<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Type">
					    <option value=""></option>
			            <option value="Sent"<?= (($Type == "Sent") ? " selected" : "") ?>>Sent</option>
			            <option value="Received"<?= (($Type == "Received") ? " selected" : "") ?>>Received</option>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Employee</td>
					<td align="center">:</td>

					<td>
					  <select name="Employee">
					    <option value=""></option>
<?
	foreach ($sEmployeesList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Employee) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Country<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Country">
					    <option value=""></option>
<?
	foreach ($sCountriesList as $sKey => $sValue)
	{
?>
			             <option value="<?= $sKey ?>"<?= (($sKey == $Country) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr valign="top">
					<td>Address</td>
					<td align="center">:</td>
					<td><textarea type="text" name="Address" rows="5" cols="50"><?= $Address ?></textarea></td>
				  </tr>

				  <tr>
					<td width="60">Date<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="Date" id="Date" value="<?= $Date ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='data/couriers.php';" />
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