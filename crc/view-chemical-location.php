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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_chemical_locations WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iType      = $objDb->getField(0, 'type_id');
		$sLocation  = $objDb->getField(0, 'location');
        $sAddress   = $objDb->getField(0, 'address');
		$sPerson    = $objDb->getField(0, 'person');
		$sContactNo = $objDb->getField(0, 'contact_no');
	}
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
	<div id="Body" style="min-height:344px; height:344px;">
	  <h2>Location Details</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	    <tr>
		  <td width="100">Location Title</td>
		  <td width="20" align="center">:</td>
		  <td><?= $sLocation ?></td>
	    </tr>

	    <tr>
		  <td>Location Type</td>
		  <td align="center">:</td>
		  <td><?= getDbValue("type", "tbl_chemical_location_types", "id='$iType'") ?></td>
	    </tr>

	    <tr valign="top">
		  <td>Address</td>
		  <td align="center">:</td>
		  <td><?= nl2br($sAddress) ?></td>
	    </tr>

	    <tr>
		  <td>Contact Person</td>
		  <td align="center">:</td>
		  <td><?= $sPerson ?></td>
	    </tr>

	    <tr>
		  <td>Contact No</td>
		  <td align="center">:</td>
		  <td><?= $sContactNo ?></td>
	    </tr>
	  </table>
	</div>
<!--  Body Section Ends Here  -->


  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>