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

	@require_once("requires/session.php");

	checkLogin( );

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
	      <h1>Donations</h1>

	      <div class="tblSheet">
		    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			  <tr class="headerRow">
			    <td width="5%">#</td>
			    <td width="70%">Employee</td>
			    <td width="13%" class="center">Donation</td>
			    <td width="12%" class="center">Date</td>
			  </tr>
<?
	$sClass = array("evenRow", "oddRow");
	$fTotal = 0;

	$sSQL = "SELECT amount, date_time,
	                (SELECT name FROM tbl_users WHERE id=tbl_donation.user_id) AS _Name
	         FROM tbl_donation
	         ORDER BY id";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sName     = $objDb->getField($i, '_Name');
		$fAmount   = $objDb->getField($i, 'amount');
		$sDateTime = $objDb->getField($i, 'date_time');
?>

			  <tr class="<?= $sClass[($i % 2)] ?>">
			    <td><?= ($i + 1) ?></td>
			    <td><?= $sName ?></td>
			    <td class="right"><?= formatNumber($fAmount, false) ?></td>
			    <td class="center"><?= formatDate($sDateTime) ?></td>
			  </tr>
<?
		$fTotal += $fAmount;
	}
?>
			  <tr class="footerRow">
			    <td width="5%">#</td>
			    <td width="70%">Total</td>
			    <td class="right"><?= formatNumber($fTotal, false) ?></td>
			    <td width="12%" class="center"></td>
			  </tr>
		    </table>
          </div>
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

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>