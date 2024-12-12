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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
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

	$sSQL = "SELECT * FROM tbl_tnc_audits WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sAuditDate = $objDb->getField(0, "audit_date");
		$iVendor    = $objDb->getField(0, "vendor_id");
		$sAuditors  = $objDb->getField(0, "auditors");


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
	<div id="Body">
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
	  </table>

	  <br />

<?
	$sClass        = array("evenRow", "oddRow");
	$sSectionsList = getList("tbl_tnc_sections", "id", "section");

	foreach ($sSectionsList as $iSection => $sSection)
	{
?>
	  <h2 style="margin-bottom:1px;"><?= $sSection ?></h2>
<?
		$sCategoryList = getList("tbl_tnc_categories", "id", "category", "section_id='$iSection'");

		foreach ($sCategoryList as $iCategory => $sCategory)
		{
?>
	  <h3><?= $sCategory ?></h3>
<?
			$sSQL = "SELECT tad.id, tad.score, tad.not_applicable, tad.remarks, tad.cap, tp.point
					 FROM tbl_tnc_audit_details tad, tbl_tnc_points tp
					 WHERE tad.point_id=tp.id AND tad.audit_id='$Id' AND tp.category_id='$iCategory'
					 ORDER BY tp.position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
?>
	  <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
		<tr bgcolor="#eaeaea">
		  <td width="5%"><b>#</b></td>
		  <td width="37%"><b>Point</b></td>
		  <td width="8%" align="center"><b>Score</b></td>
		  <td width="25%"><b>Remarks</b></td>
                  <td width="25%"><b>CAP</b></td>
		</tr>

<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPoint   = $objDb->getField($i, 'id');
				$sPoint   = $objDb->getField($i, 'point');
				$iScore   = $objDb->getField($i, 'score');
				$sRemarks = $objDb->getField($i, 'remarks');
                                $sCap     = $objDb->getField($i, 'cap');
?>

	    <tr valign="top" class="<?= $sClass[($i % 2)] ?>">
		  <td align="center"><?= ($i + 1) ?></td>
		  <td><?= $sPoint ?></td>
		  <td align="center"><div style="background:<?= (($iScore == 1) ? '#00ff00' : (($iScore == 0) ? '#ff0000' : '#888888')) ?>; padding:5px; color:#ffffff;"><?= (($iScore == -1) ? "N/A" : $iScore) ?></div></td>
		  <td><?= nl2br($sRemarks) ?></td>
                  <td><?= nl2br($sCap) ?></td>
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