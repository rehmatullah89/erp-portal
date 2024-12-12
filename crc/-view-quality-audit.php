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

	$Id   = IO::intValue('Id');
	$Area = IO::intValue('Area');

	$sSQL = "SELECT * FROM tbl_quality_audits WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sAuditDate = $objDb->getField(0, "audit_date");
		$iVendor    = $objDb->getField(0, "vendor_id");
		$sAuditors  = $objDb->getField(0, "auditors");
		$iCutting   = $objDb->getField(0, "cutting");
		$iSewing    = $objDb->getField(0, "sewing");
		$iPacking   = $objDb->getField(0, "packing");
		$iFinishing = $objDb->getField(0, "finishing");


		$sAuditorsList = getList("tbl_users", "id", "name", "designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (5,15,41))");

		$iAuditors = @explode(",", $sAuditors);
		$sAuditors = "";

		foreach ($iAuditors as $iAuditor)
			$sAuditors .= ($sAuditorsList[$iAuditor]."<br />");

		$sVendor = getDbValue("vendor", "tbl_vendors", "id='$iVendor'");
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
	<div id="Body" style="height:596px;">
<?
	if ($Area == 0)
	{
?>
	  <h2>Audit Details</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	    <tr>
		  <td width="165">Audit Date</td>
		  <td width="20" align="center">:</td>
		  <td><?= formatDate($sAuditDate) ?></td>
	    </tr>

	    <tr>
		  <td>Vendor</td>
		  <td align="center">:</td>
		  <td><?= $sVendor ?></td>
	    </tr>

	    <tr valign="top">
		  <td>Auditor(s)</td>
		  <td align="center">:</td>
		  <td><?= $sAuditors ?></td>
	    </tr>

	    <tr>
	      <td colspan="3"><h3>No. of Employees</h3></td>
	    </tr>

	    <tr>
		  <td>Cutting</td>
		  <td align="center">:</td>
		  <td><?= formatNumber($iCutting, false) ?></td>
	    </tr>

	    <tr>
		  <td>Sewing</td>
		  <td align="center">:</td>
		  <td><?= formatNumber($iSewing, false) ?></td>
	    </tr>

 	    <tr valign="top">
		  <td>Packing</td>
		  <td align="center">:</td>
		  <td><?= formatNumber($iPacking, false) ?></td>
	    </tr>

	    <tr>
		  <td>Finishing</td>
		  <td align="center">:</td>
		  <td><?= formatNumber($iFinishing, false) ?></td>
	    </tr>

	    <tr>
		  <td>Total</td>
		  <td align="center">:</td>
		  <td><?= formatNumber(($iCutting + $iSewing + $iPacking + $iFinishing), false) ?></td>
	    </tr>
	  </table>

	  <br />

<?
		$sAreasList    = getList("tbl_quality_areas", "id", "title", "", "position");
	}

	else
		$sAreasList    = getList("tbl_quality_areas", "id", "title", "id='$Area'", "position");




	$sSectionsList = getList("tbl_quality_sections", "id", "title", "", "position");

	foreach ($sAreasList as $iArea => $sArea)
	{
?>
	<h2 style="margin-bottom:1px;"><?= $iArea ?>. <?= $sArea ?></h2>

<?
		foreach ($sSectionsList as $iSection => $sSection)
		{
			$sSQL = "SELECT qad.id, qp.point, qad.rating, qad.remarks
					 FROM tbl_quality_audit_details qad, tbl_quality_points qp
					 WHERE qad.audit_id='$Id' AND qad.point_id=qp.id AND qp.area_id='$iArea' AND qp.section_id='$iSection'
					 ORDER BY qp.position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			if ($iCount == 0)
				continue;
?>
	<h3><?= $sSection ?></h3>

	  <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
		<tr bgcolor="#eaeaea">
		  <td width="3%" align="center"><b>#</b></td>
		  <td width="60%"><b>Inspection Point</b></td>
		  <td width="10%" align="center"><b>Rating</b></td>
		  <td width="32%"><b>Remarks</b></td>
		</tr>

<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPoint   = $objDb->getField($i, 'id');
				$sPoint   = $objDb->getField($i, 'point');
				$iRating  = $objDb->getField($i, 'rating');
				$sRemarks = $objDb->getField($i, 'remarks');

				switch ($iRating)
				{
					case 0 : $sRating = "";  $sColor="#e9e1c5"; break;
					case 1 : $sRating = "A"; $sColor="#00ff00"; break;
					case 2 : $sRating = "B"; $sColor="#99cc00"; break;
					case 3 : $sRating = "C"; $sColor="#ff9900"; break;
					case 4 : $sRating = "D"; $sColor="#ff0000"; break;
				}
?>
				    <tr valign="top" bgcolor="<?= ((($i % 2) == 1) ? '#fafafa' : '#ffffff') ?>">
				      <td align="center"><?= ($i + 1) ?></td>
				      <td><?= $sPoint ?></td>
				      <td align="center" style="background:<?= $sColor ?>;"><?= $sRating ?></td>
				      <td><?= nl2br($sRemarks) ?></td>
				    </tr>
<?
			}
?>
			      </table>
<?
		}
	}
?>
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