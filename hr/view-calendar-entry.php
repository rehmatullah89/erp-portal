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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_calendar WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sUsers    = $objDb->getField(0, "users");
		$sTitle    = $objDb->getField(0, "title");
		$sFromDate = $objDb->getField(0, "from_date");
		$sToDate   = $objDb->getField(0, "to_date");
		$sDetails  = $objDb->getField(0, "details");
		$sPrivate  = $objDb->getField(0, "private");
	}

	$sSQL = "SELECT name FROM tbl_users WHERE id IN ($sUsers)";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
	$sUsers = "";

	for ($i = 0; $i < $iCount; $i ++)
		$sUsers .= (", ".$objDb->getField($i, 0));

	if ($sUsers != "")
		$sUsers = substr($sUsers, 2);
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
	<div id="Body" style="min-height:296px; height:296px;">
	  <h2>Entry Details</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	    <tr valign="top">
		  <td width="65">Employee(s)</td>
		  <td width="20" align="center">:</td>
		  <td><?= $sUsers ?></td>
	    </tr>

	    <tr>
		  <td>Title</td>
		  <td align="center">:</td>
		  <td><?= $sTitle ?></td>
	    </tr>

	    <tr>
		  <td>From Date</td>
		  <td align="center">:</td>
		  <td><?= formatDate($sFromDate) ?></td>
	    </tr>

	    <tr>
		  <td>To Date</td>
		  <td align="center">:</td>
		  <td><?= formatDate($sToDate) ?></td>
	    </tr>

	    <tr valign="top">
		  <td>Details</td>
		  <td align="center">:</td>
		  <td><?= nl2br($sDetails) ?></td>
	    </tr>

	    <tr>
	      <td>Private</td>
	      <td align="center">:</center>
	      <td><?= (($sPrivate == "Y") ? "Yes" : "No") ?></td>
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