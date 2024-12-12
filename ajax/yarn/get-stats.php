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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Date     = IO::strValue("Date");
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$Week     = IO::intValue("Week");
	$Month    = IO::intValue("Month");
	$Year     = IO::intValue("Year");
	$sDates   = array( );


	if ($FromDate != "" && $ToDate != "")
	{
    	$sFromDate = $FromDate;
    	$sToDate   = $ToDate;
		$iMonth    = intval(substr($FromDate, 5, 2));
		$iYear     = intval(substr($FromDate, 0, 4));

		for ($i = 0; $i < 3; $i ++)
			$sDates[] = array("From" => date("Y-m-01", mktime(0, 0, 0, ($iMonth + $i), 1, $iYear)), "To" => date("Y-m-t", mktime(0, 0, 0, ($iMonth + $i), 1, $iYear)), "Label" => date("F Y", mktime(0, 0, 0, ($iMonth + $i), 1, $iYear)));
	}

	else if ($Month > 0)
	{
		$sFromDate = date("Y-m-01", mktime(0, 0, 0, $Month, "01", $Year));
		$sToDate   = date("Y-m-t", mktime(0, 0, 0, $Month, "01", $Year));
		$iWeeks    = array( );

		for ($iDate = strtotime($sFromDate); $iDate <= strtotime($sToDate); $iDate += 86400)
		{
			$iWeek = date("W", $iDate);

			if (!@in_array($iWeek, $iWeeks))
			{
				$sDates[] = array("From" => date("Y-m-d", $iDate), "To" => date("Y-m-d", ($iDate + (86400 * 6))), "Label" => "Week {$iWeek}");
				$iWeeks[] = $iWeek;
			}
		}
	}

    else if ($Date != "")
    {
    	$sFromDate = $Date;
    	$sToDate   = $Date;

		$sDates[] = array("From" => $Date, "To" => $Date, "Label" => formatDate($Date));
    }

	else
	{
    	$iTime  = strtotime("{$Year}-01-01", @time( ));
    	$iDay   = date('w', $iTime);
    	$iTime += (((7 * $Week) + 1 - $iDay) * 24 * 3600);

    	$sFromDate = date("Y-m-d", $iTime);

    	if ($Week == 0)
    		$sFromDate = "{$Year}-01-01";

    	$iTime  += (6 * 24 * 3600);
    	$sToDate = date("Y-m-d", $iTime);

    	if (strtotime("{$Year}-12-31") < $iTime)
    		$sToDate = "{$Year}-12-31";


		for ($iDate = strtotime($sFromDate); $iDate <= strtotime($sToDate); $iDate += 86400)
			$sDates[] = array("From" => date("Y-m-d", $iDate), "To" => date("Y-m-d", $iDate), "Label" => date("j-M", $iDate));
    }


	$sSQL = "SELECT po.id, CONCAT(po.order_no, ' ', po.order_status) AS _Po, po.vendor_id,
	                (SELECT style FROM tbl_styles WHERE FIND_IN_SET(id, po.styles) LIMIT 1) AS _Style,
	                (SELECT vendor FROM tbl_vendors WHERE id=po.vendor_id) AS _Vendor
	         FROM tbl_po po, tbl_loom_plan lp, tbl_loom_plan_details lpd
	         WHERE po.id=lp.po_id AND lpd.po_id=po.id AND (lpd.date BETWEEN '$sFromDate' AND '$sToDate')
	         GROUP BY po.id
	         HAVING SUM(lpd.production)>'0'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount == 0)
	{
		print "<br />No Loom Plan Found<br /><br />";
	}

	else
	{
?>
		    <table border="1" bordercolor="#cccccc" cellpadding="6" cellspacing="0" width="100%">
			  <tr bgcolor="#e3e3e3">
			    <td width="35" rowspan="2" align="center"><b>#</b></td>
			    <td width="70" rowspan="2"><b>D #</b></td>
			    <td width="90" rowspan="2"><b>PO #</b></td>
			    <td width="160" rowspan="2"><b>Vendor</b></td>
			    <td colspan="<?= count($sDates) ?>" align="center"><b>Total No. of Looms Assigned</b></td>
			  </tr>

			  <tr bgcolor="#e9e9e9">
<?
		foreach ($sDates as $sDate)
		{
?>
			    <td align="center"><?= $sDate['Label'] ?></td>
<?
		}
?>
			  </tr>
<?
		$iTotal = array( );
		$iIndex = 1;

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iPo     = $objDb->getField($i, "id");
			$sPo     = $objDb->getField($i, "_Po");
			$sStyle  = $objDb->getField($i, "_Style");
			$sVendor = $objDb->getField($i, "_Vendor");

			if (getDbValue("SUM(production)", "tbl_loom_plan_details", "po_id='$iPo' AND (`date` BETWEEN '$sFromDate' AND '$sToDate')") == 0)
				continue;
?>
			  <tr bgcolor="#f6f6f6">
			    <td align="center"><?= $iIndex++ ?></td>
			    <td><?= $sStyle ?></td>
			    <td><?= $sPo ?></td>
			    <td><?= $sVendor ?></td>
<?
			foreach ($sDates as $sDate)
			{
				$iLooms = getDbValue("COUNT(*)", "tbl_loom_plan_details", "po_id='$iPo' AND (`date` BETWEEN '{$sDate['From']}' AND '{$sDate['To']}') AND production>'0'");
?>
			    <td align="center"><?= formatNumber($iLooms, false) ?></td>
<?
				$iTotal[$sDate['Label']] += $iLooms;
			}
?>
			  </tr>
<?
		}
?>

			  <tr bgcolor="#f0f0f0">
			    <td colspan="4" align="right"><b>Total Assigned Looms</b></td>
<?
		foreach ($sDates as $sDate)
		{
?>
			    <td align="center"><b><?= formatNumber($iTotal[$sDate['Label']], false) ?></b></td>
<?
		}
?>
			  </tr>
		    </table>
<?
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>