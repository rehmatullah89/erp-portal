<?
    // Measurement Sheet starts here
        $sColors = @explode(",", $Colors);
	$iColor  = 0;

	foreach ($sColors as $sColor)
	{
		foreach ($iSizes as $iSize)
		{
			$sSize         = getDbValue("size", "tbl_sizes", "id='$iSize'");
			$iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");


			$sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings
					 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
					 WHERE qrs.audit_id='$Id' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSamplingSize' AND qrs.color='$sColor'
					 ORDER BY qrs.sample_no, qrss.point_id";
			$objDb->query($sSQL);

			$iCount        = $objDb->getCount( );
			$sSizeFindings = array( );

			for($i = 0; $i < $iCount; $i ++)
			{
				$iSampleNo = $objDb->getField($i, 'sample_no');
				$iPoint    = $objDb->getField($i, 'point_id');
				$sFindings = $objDb->getField($i, 'findings');

				$sSizeFindings["{$iSampleNo}-{$iPoint}"] = $sFindings;
			}
?>
				<h2 style="margin:0px;">Measurement Sheet (Size: <?= $sSize ?>, Color: <?= $sColor ?>)</h2>

				<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					<tr class="sdRowHeader">
					  <td width="40" align="center"><b>#</b></td>
					  <td><b>Measurement Point</b></td>
					  <td width="90" align="center"><b>Specs</b></td>
					  <td width="90" align="center"><b>Tolerance</b></td>
					  <td width="60" align="center"><b>1</b></td>
					  <td width="60" align="center"><b>2</b></td>
					  <td width="60" align="center"><b>3</b></td>
					  <td width="60" align="center"><b>4</b></td>
					  <td width="60" align="center"><b>5</b></td>
					</tr>
<?
			$sSQL = "SELECT point_id, specs,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point
					 FROM tbl_style_specs
					 WHERE style_id='$Style' AND size_id='$iSamplingSize' AND version='0' AND specs!='0' AND specs!=''
					 ORDER BY id";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for($i = 0; $i < $iCount; $i ++)
			{
				$iPoint     = $objDb->getField($i, 'point_id');
				$sSpecs     = $objDb->getField($i, 'specs');
				$sPoint     = $objDb->getField($i, '_Point');
				$sTolerance = $objDb->getField($i, '_Tolerance');
?>

					<tr class="sdRowColor">
					  <td align="center"><?= ($i + 1) ?></td>
					  <td><?= $sPoint ?></td>
					  <td align="center"><?= $sSpecs ?></td>
					  <td align="center"><?= $sTolerance ?></td>
<?
				for ($j = 1; $j <= 5; $j ++)
				{
?>
				  	<td align="center"><input type="text" name="Specs<?= $iSamplingSize ?>_<?= $iColor ?>_<?= $iPoint ?>_<?= $j ?>" value="<?= $sSizeFindings["{$j}-{$iPoint}"] ?>" size="5" maxlength="10" class="textbox" /></td>
<?
				}
?>
			    	</tr>
<?
			}
?>
				</table>
				</div>
<?
		}


		$iColor ++;
	}
?>