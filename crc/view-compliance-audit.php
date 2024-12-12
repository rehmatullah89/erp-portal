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

	$sSQL = "SELECT * FROM tbl_compliance_audits WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sAuditDate      = $objDb->getField(0, "audit_date");
		$sAuditTime      = $objDb->getField(0, "audit_time");
		$sRepresentative = $objDb->getField(0, "representative");
		$iVendor         = $objDb->getField(0, "vendor_id");
		$iAuditType      = $objDb->getField(0, "type_id");
		$sAuditors       = $objDb->getField(0, "auditors");
		$iSalariedStaff  = $objDb->getField(0, "salaried_staff");
		$iContractStaff  = $objDb->getField(0, "contract_staff");
		$iMaleStaff      = $objDb->getField(0, "male_staff");
		$iFemaleStaff    = $objDb->getField(0, "female_staff");


		$sAuditorsList = getList("tbl_users", "id", "name", "designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (5,15,41))");

		$iAuditors = @explode(",", $sAuditors);
		$sAuditors = "";

		foreach ($iAuditors as $iAuditor)
			$sAuditors .= ($sAuditorsList[$iAuditor]."<br />");


		$sAuditType = getDbValue("title", "tbl_compliance_types", "id='$iAuditType'");
		$sVendor    = getDbValue("vendor", "tbl_vendors", "id='$iVendor'");
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
		  <td>Audit Time</td>
		  <td align="center">:</td>
		  <td><?= formatTime($sAuditTime) ?></td>
	    </tr>

	    <tr>
		  <td>Audit Type</td>
		  <td align="center">:</td>
		  <td><?= $sAuditType ?></td>
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

	    <tr valign="top">
		  <td>Factory Representative</td>
		  <td align="center">:</td>
		  <td><?= $sRepresentative ?></td>
	    </tr>

	    <tr>
		  <td>Salaried Staff</td>
		  <td align="center">:</td>
		  <td><?= formatNumber($iSalariedStaff, false) ?></td>
	    </tr>

	    <tr>
		  <td>Contract Staff</td>
		  <td align="center">:</td>
		  <td><?= formatNumber($iContractStaff, false) ?></td>
	    </tr>

	    <tr>
		  <td>Total</td>
		  <td align="center">:</td>
		  <td><?= formatNumber(($iSalariedStaff + $iContractStaff), false) ?></td>
	    </tr>

 	    <tr valign="top">
		  <td>Male Staff (%)</td>
		  <td align="center">:</td>
		  <td><?= formatNumber($iMaleStaff, false) ?></td>
	    </tr>

	    <tr>
		  <td>Female Staff (%)</td>
		  <td align="center">:</td>
		  <td><?= formatNumber($iFemaleStaff, false) ?></td>
	    </tr>
	  </table>

	  <br />

<?
	$sCategoriesList = getList("tbl_compliance_categories", "id", "title", "", "position");

	foreach ($sCategoriesList as $iCategory => $sCategory)
	{
?>
	<h2 style="margin-bottom:1px;"><?= $iCategory ?>. <?= $sCategory ?></h2>

<?
		$sSQL = "SELECT cad.*, cq.title, cq.details
				 FROM tbl_compliance_audit_details cad, tbl_compliance_questions cq
				 WHERE cad.audit_id='$Id' AND cad.question_id=cq.id AND cq.category_id='$iCategory'
				 ORDER BY cq.position";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iQuestion = $objDb->getField($i, 'cad.id');
			$sQuestion = $objDb->getField($i, 'title');
			$sDetails  = $objDb->getField($i, 'details');
			$iRating   = $objDb->getField($i, 'rating');
			$sComments = $objDb->getField($i, 'comments');

			$sPictures = array( );
			$sPicField = "";

			for ($j = 1; $j <= 15; $j ++)
			{
				$sPicture = $objDb->getField($i, "picture{$j}");

				if ($sPicture != "" && @file_exists($sBaseDir.COMPLIANCE_AUDITD_DIR.$sPicture))
					$sPictures["picture{$j}"] = $sPicture;

				else if ($sPicField == "")
					$sPicField = "picture{$j}";
			}


			switch ($iRating)
			{
				case 0 : $sRating = "N/A";  $sColor="#404040"; break;
				case 1 : $sRating = "80%"; $sColor="#00b050"; break;
				case 2 : $sRating = "61-79%"; $sColor="#ffff00"; break;
				case 3 : $sRating = "41-60%"; $sColor="#f79646"; break;
				case 4 : $sRating = "0-40%"; $sColor="#ff0000"; break;
			}
?>
	  <h3><?= ($i + 1) ?>. <?= $sQuestion ?></h3>

	  <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
		<tr bgcolor="#eaeaea">
		  <td width="55%"><b>Requirements</b></td>
		  <td width="12%" align="center"><b>Performance</b></td>
		  <td width="33%"><b>Comments</b></td>
		</tr>

		<tr valign="top">
		  <td><?= nl2br($sDetails) ?></td>
		  <td align="center" style="background:<?= $sColor ?>;"><?= $sRating ?></td>
		  <td><?= nl2br($sComments) ?></td>
		</tr>
<?
			if (count($sPictures) > 0)
			{
?>
		<tr>
		  <td colspan="3">
			<div style="position:relative;">
<?
				foreach ($sPictures as $sField => $sPicture)
				{
?>
			  <div style="float:left; margin:10px 10px 0px 0px;"><img src="<?= (COMPLIANCE_AUDITD_DIR.$sPicture) ?>" width="100" height="80" alt="" title="" /></a></div>
<?
				}
?>
			</div>
		  </td>
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