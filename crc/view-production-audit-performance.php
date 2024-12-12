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

	$CatId = IO::intValue('Cat');
	$Standard = IO::strValue('Standard'); //green,yellow,red


	$options = '';
	if($Standard == "green") $options="pad.weightage>'3'";
	if($Standard == "yellow") $options="pad.weightage>'1' and pad.weightage<'3'";
	if($Standard == "red") $options="pad.weightage<'2'";
	//green
	//cat

	$sSQL = "SELECT * FROM tbl_production_audits WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sAuditDate      = $objDb->getField(0, "audit_date");
		$sAuditTime      = $objDb->getField(0, "audit_time");
		$sRepresentative = $objDb->getField(0, "representative");
		$iVendor         = $objDb->getField(0, "vendor_id");
		$sAuditors       = $objDb->getField(0, "auditors");


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
	<div id="Body" style="height:396px;">
<!--	  <h2>Audit Details</h2>

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
	  </table>

	  <br />-->

<?
	$sCategoriesList = getList("tbl_production_categories", "id", "title", "", "position");

	foreach ($sCategoriesList as $iCategory => $sCategory)
	{
		if($iCategory!=$CatId) continue;
?>
	<h2 style="margin-bottom:1px;"> <?= $sCategory ?></h2>

<?

		$sSQL = "SELECT pad.*, pq.*
				 FROM tbl_production_audit_details pad, tbl_production_questions pq
				 WHERE pad.audit_id='$Id' AND pad.question_id=pq.id AND pq.category_id='$iCategory'
				 and $options
				 ORDER BY pq.position";


		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sQuestion     = $objDb->getField($i, 'pq.question');
			$iQuestionType = $objDb->getField($i, 'pq.question_type');
			$iNoOfOptions  = $objDb->getField($i, 'pq.no_of_options');
			$sOptions      = $objDb->getField($i, 'pq.options');
			$iWeightages   = $objDb->getField($i, 'pq.weightage');
			$iWeightage    = $objDb->getField($i, 'pad.weightage');
			$sDetails      = $objDb->getField($i, 'pad.details');


			$sOptions    = @explode("|-|", $sOptions);
			$iWeightages = @explode("|-|", $iWeightages);
			$sDetails    = @explode("|-|", $sDetails);
?>
	  <h3><?= ($i + 1) ?>. <?= $sQuestion ?></h3>

			      <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
			        <tr>
			          <td width="50">Score</td>

<?
			if ($iQuestionType == 1)
			{
?>
			          <td>
<?
				for ($j = 0; $j < $iNoOfOptions; $j ++)
				{
					if ($iWeightages[$j] == $iWeightage)
						print ($iWeightage." (".$sOptions[$j].")");
				}
?>
			          </td>
<?
			}

			else
			{
?>
			          <td width="150">
			            <?= $iWeightage ?><br />
			          </td>
<?
				for ($j = 0; $j < $iNoOfOptions; $j ++)
				{
?>
			          <td width="120" align="center"><?= $sOptions[$j] ?> : <?= $sDetails[$j] ?></td>
<?
				}
?>
			          <td></td>
<?
			}
?>
			        </tr>
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