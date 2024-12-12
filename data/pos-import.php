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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/pos-import.js"></script>
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
			    <h1>PO Import</h1>

			    <form name="frmData" id="frmData" method="post" action="data/import-pos-csv.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnImport').disabled=true;">
				<input type="hidden" name="MAX_FILE_SIZE" value="20971520" />
				<h2>Import POs File</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="60">Brand<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Brand" id="Brand" style="width:200px;" onchange="getListValues('Brand', 'Season', 'BrandSeasons');  getListValues('Brand', 'Destination', 'BrandDestinations');">
						<option value=""></option>
<?
	$sSQL = "SELECT id, brand FROM tbl_brands WHERE id IN ({$_SESSION['Brands']}) AND id IN (368,372) ORDER BY brand";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
				    <td>Season<span class="mandatory">*</span></td>
				    <td align="center">:</td>

				    <td>
					  <select name="Season" id="Season" style="width:200px;">
					    <option value=""></option>
		              </select>
		            </td>
				  </tr>

				  <tr>
				    <td>Destination<span class="mandatory">*</span></td>
				    <td align="center">:</td>

				    <td>
					  <select name="Destination" id="Destination" style="width:200px;">
					    <option value=""></option>
		              </select>
		            </td>
				  </tr>

				  <tr>
					<td>CSV File<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="file" name="CsvFile" value="" size="30" class="file" /></td>
				  </tr>

<?
	if ($_SESSION["UserId"] == 1 || $_SESSION["UserId"] == 319)
	{
?>
				  <tr>
					<td align="right"><input type="checkbox" name="Delete" id="Delete" value="Y" /></td>
					<td align="center">:</td>
					<td>Delete CSV File POs/Styles</td>
				  </tr>
<?
	}
?>
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