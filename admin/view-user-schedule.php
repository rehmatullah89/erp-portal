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

	$Id   = IO::intValue('Id');
	$Date = IO::strValue('Date');


	$sSQL = "SELECT name FROM tbl_users WHERE id='$Id'";
	$objDb->query($sSQL);

	$sName = $objDb->getField(0, "name");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin:0px 2px 0px 2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr valign="top">
		  <td width="100%">
			<h2>Non Production Schedule</h2>

			<div class="tblSheet">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				<tr class="headerRow">
				  <td width="5%">#</td>
				  <td width="25%">Location</td>
				  <td width="50">Task Details</td>
				  <td width="10%" class="center">From/To<br />Date</td>
				  <td width="10%" class="center">Start/End<br />Time</td>
				</tr>
<?
	$sClass         = array("evenRow", "oddRow");
	$sLocationsList = getList("tbl_visit_locations", "id", "location");


	if ($Date != "")
		$sSQL = "SELECT * FROM tbl_user_schedule WHERE user_id='$Id' AND ('$Date' BETWEEN from_date AND to_date) ORDER BY id DESC";

	else
		$sSQL = "SELECT * FROM tbl_user_schedule WHERE user_id='$Id' AND (CURDATE( ) BETWEEN from_date AND to_date) ORDER BY id DESC";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iLocation  = $objDb->getField($i, 'location_id');
		$sFromDate  = $objDb->getField($i, 'from_date');
		$sToDate    = $objDb->getField($i, 'to_date');
		$sDetails   = $objDb->getField($i, 'details');
		$sStartTime = $objDb->getField($i, 'start_time');
		$sEndTime   = $objDb->getField($i, 'end_time');

		@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
		@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);
?>
				<tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				  <td><?= ($i + 1) ?></td>
				  <td><?= $sLocationsList[$iLocation] ?></td>
				  <td><?= nl2br($sDetails) ?></td>
				  <td class="center"><?= formatDate($sFromDate) ?><br />-<br /><?= formatDate($sToDate) ?></td>
				  <td class="center"><?= formatTime($sStartTime) ?><br />-<br /><?= formatTime($sEndTime) ?></td>
				</tr>
<?
	}

	if ($iCount == 0)
	{
?>
				<tr class="<?= $sClass[($i % 2)] ?>">
				  <td colspan="5">No Schedule Entry Found</td>
				</tr>
<?
	}
?>
			  </table>
			</div>

			<br />
			<h2>Production Audits</h2>

			<div class="tblSheet">
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				<tr class="headerRow">
				  <td width="5%">#</td>
				  <td width="10%">Audit Code</td>
				  <td width="30%">Vendor</td>
				  <td width="10%">Line</td>
				  <td width="15%">Audit Stage</td>
				  <td width="15%">Start Time</td>
				  <td width="15%">End Time</td>
				</tr>

<?
	if ($Date != "")
	{
		$sSQL = "SELECT audit_code, audit_stage, start_time, end_time,
						(SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
						(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line
				 FROM tbl_qa_reports
				 WHERE audit_date='$Date' AND (user_id='$Id' OR (group_id>'0' AND group_id IN (SELECT id FROM tbl_auditor_groups WHERE FIND_IN_SET('$Id', users))))
				 ORDER BY id DESC";
	}

	else
	{
		$sSQL = "SELECT audit_code, audit_stage, start_time, end_time,
						(SELECT vendor FROM tbl_vendors WHERE id=tbl_qa_reports.vendor_id) AS _Vendor,
						(SELECT line FROM tbl_lines WHERE id=tbl_qa_reports.line_id) AS _Line
				 FROM tbl_qa_reports
				 WHERE audit_date=CURDATE( ) AND (user_id='$Id' OR (group_id>'0' AND group_id IN (SELECT id FROM tbl_auditor_groups WHERE FIND_IN_SET('$Id', users))))
				 ORDER BY id DESC";
	}

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sAuditCode  = $objDb->getField($i, 'audit_code');
		$sVendor     = $objDb->getField($i, '_Vendor');
		$sLine       = $objDb->getField($i, '_Line');
		$sAuditStage = $objDb->getField($i, 'audit_stage');
		$sStartTime  = $objDb->getField($i, 'start_time');
		$sEndTime    = $objDb->getField($i, 'end_time');

		@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
		@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);
?>
				<tr class="<?= $sClass[($i % 2)] ?>">
				  <td><?= ($i + 1) ?></td>
				  <td><?= $sAuditCode ?></td>
				  <td><?= $sVendor ?></td>
				  <td><?= $sLine ?></td>
				  <td><?= $sAuditStage ?></td>
				  <td class="center"><?= formatTime($sStartTime) ?></td>
				  <td class="center"><?= formatTime($sEndTime) ?></td>
				</tr>
<?
	}

	if ($iCount == 0)
	{
?>
				<tr class="<?= $sClass[($i % 2)] ?>">
				  <td colspan="7">No Audit Entry Found</td>
				</tr>
<?
	}
?>
			  </table>
			</div>

			<br />
		  </td>
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