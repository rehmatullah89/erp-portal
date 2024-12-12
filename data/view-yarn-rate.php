<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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

	$Date = IO::strValue('Date');

	$sSQL = "SELECT * FROM tbl_yarn_rates WHERE day='$Date'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$fS10       = $objDb->getField(0, 's10');
		$fS20       = $objDb->getField(0, 's20');
		$fS30       = $objDb->getField(0, 's30');
		$fCd10      = $objDb->getField(0, 'cd10');
		$fCd12      = $objDb->getField(0, 'cd12');
		$fCd14      = $objDb->getField(0, 'cd14');
		$fCd16      = $objDb->getField(0, 'cd16');
		$fCd20      = $objDb->getField(0, 'cd20');
		$fCd21      = $objDb->getField(0, 'cd21');
		$fCd30      = $objDb->getField(0, 'cd30');
		$fCm30Cpt   = $objDb->getField(0, 'cm30_cpt');
		$fCm40      = $objDb->getField(0, 'cm40');
		$fCd12Spndx = $objDb->getField(0, 'cd12_spndx');
		$fDsp1670   = $objDb->getField(0, 'dsp16_70');
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
	<div id="Body" style="min-height:394px; height:394px;">
	  <h2>Yarn Rates</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	    <tr>
		  <td width="140">Date</td>
		  <td width="20" align="center">:</td>
		  <td><?= formatDate($Date) ?></td>
	    </tr>

	  <tr>
		<td>10s</td>
		<td align="center">:</td>
		<td><?= formatNumber($fS10, false) ?></td>
	  </tr>

	  <tr>
		<td>20s</td>
		<td align="center">:</td>
		<td><?= formatNumber($fS20, false) ?></td>
	  </tr>

	  <tr>
		<td>30s</td>
		<td align="center">:</td>
		<td><?= formatNumber($fS30, false) ?></td>
	  </tr>

	  <tr>
		<td>10cd</td>
		<td align="center">:</td>
		<td><?= formatNumber($fCd10, false) ?></td>
	  </tr>

	  <tr>
		<td>12cd</td>
		<td align="center">:</td>
		<td><?= formatNumber($fCd12, false) ?></td>
	  </tr>

	  <tr>
		<td>14cd</td>
		<td align="center">:</td>
		<td><?= formatNumber($fCd14, false) ?></td>
	  </tr>

	  <tr>
		<td>16cd</td>
		<td align="center">:</td>
		<td><?= formatNumber($fCd16, false) ?></td>
	  </tr>

	  <tr>
		<td>20cd</td>
		<td align="center">:</td>
		<td><?= formatNumber($fCd20, false) ?></td>
	  </tr>

	  <tr>
		<td>21cd</td>
		<td align="center">:</td>
		<td><?= formatNumber($fCd21, false) ?></td>
	  </tr>

	  <tr>
		<td>30cd</td>
		<td align="center">:</td>
		<td><?= formatNumber($fCd30, false) ?></td>
	  </tr>

	  <tr>
		<td>30cm cpt</td>
		<td align="center">:</td>
		<td><?= formatNumber($fCm30Cpt, false) ?></td>
	  </tr>

	  <tr>
		<td>40cm</td>
		<td align="center">:</td>
		<td><?= formatNumber($fCm40, false) ?></td>
	  </tr>

	  <tr>
		<td>12/1 CD +70 DN Spndx</td>
		<td align="center">:</td>
		<td><?= formatNumber($fCd12Spndx, false) ?></td>
	  </tr>

	  <tr>
		<td>16+70Dsp</td>
		<td align="center">:</td>
		<td><?= formatNumber($fDsp1670, false) ?></td>
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