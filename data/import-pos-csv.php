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

        ini_set('max_execution_time', 0);
        set_time_limit(0);
        
        $objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Brand       = IO::intValue("Brand");
	$Season      = IO::intValue("Season");
	$Destination = IO::intValue("Destination");
	$Delete      = IO::strValue("Delete");
	$sCsvFile    = "";
	$sOutput     = "";
	$iIndex      = 1;
	$sClass      = array("evenRow", "oddRow");


	if ($_FILES['CsvFile']['name'] != "")
	{
		$sCsvFile = IO::getFileName($_FILES['CsvFile']['name']);

		if (!@move_uploaded_file($_FILES['CsvFile']['tmp_name'], ($sBaseDir.TEMP_DIR.$sCsvFile)))
				$sCsvFile = "";
	}

	if ($Brand == 0 || $sCsvFile == "")
		redirect("pos-import.php", "NO_POS_CSV_FILE");


	$hFile   = @fopen(($sBaseDir.TEMP_DIR.$sCsvFile), "r");
	$sRecord = @fgetcsv($hFile, 10000);


	if (getDbValue("parent_id", "tbl_brands", "id='$Brand'") == 367)
		@include("import-mgf-pos-csv.php");
	
	else if ($Brand == 372)
		@include("import-leverstyle-pos-csv.php");


	@fclose($hFile);

	@unlink($sRootDir.TEMP_DIR.$sFile);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
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
			    <h1><img src="images/h1/data/pos-import.jpg" width="156" height="20" vspace="10" alt="" title="" /></h1>

			    <div class="tblSheet">
			      <h2 style="margin-bottom:1px; margin-right:1px;">CSV Import Status</h2>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="6%" class="center">#</td>
				      <td width="20%">Vendor</td>
				      <td width="12%">Order #</td>
				      <td width="12%">Style #</td>
				      <td width="15%">Color</td>
				      <td width="15%">Size</td>
				      <td width="20%">Status</td>
				    </tr>

				    <?= $sOutput ?>
			      </table>
			    </div>

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