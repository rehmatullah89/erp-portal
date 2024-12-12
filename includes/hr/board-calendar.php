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

	$sStartDate = (date("Y")."-01-01");
	$sEndDate   = (date("Y")."-12-31");

	$sSQL = "SELECT id, title, from_date, to_date FROM tbl_calendar WHERE FIND_IN_SET('{$_SESSION['UserId']}', users) AND ((from_date BETWEEN '$sStartDate' AND '$sEndDate') OR (to_date BETWEEN '$sStartDate' AND '$sEndDate')) ORDER BY from_date, title";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );
?>
			    <div class="tblSheet">
		          <h1 class="darkGray small" style="margin:0px 1px 1px 0px;"><span>( Year : <?= date("Y") ?> )</span><img src="images/h1/hr/calendar2.jpg" width="99" height="15" vspace="7" alt="" title="" /></h1>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow" valign="top">
				      <td width="5%">#</td>
				      <td width="67%">Title</td>
				      <td width="20%">Dates</td>
				      <td width="8%" class="center">Details</td>
				    </tr>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$sTitle    = $objDb->getField($i, 'title');
		$sFromDate = $objDb->getField($i, 'from_date');
		$sToDate   = $objDb->getField($i, 'to_date');
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($i + 1) ?></td>
				      <td><?= $sTitle ?></td>
				      <td><?= formatDate($sFromDate) ?> <b>to</b> <?= formatDate($sToDate) ?></td>
				      <td class="center"><a href="hr/calendar-entry-details.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Calendar Entry # <?= ($i + 1) ?> :: ::"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a></td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Calendar Entry Found!</td>
				    </tr>
<?
	}
?>
			      </table>
		        </div>
