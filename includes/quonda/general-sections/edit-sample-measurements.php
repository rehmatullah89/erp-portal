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

	@require_once("../../../requires/session.php");
	
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	
	$AuditId  = IO::intValue('AuditId');
	$SampleId = IO::intValue('SampleId');
	$iStyle   = IO::intValue('StyleId');
        $iSize    = IO::intValue('SizeId');
        
	$sBaseDir = "../../../";
	
	
	$sSQL = "SELECT * FROM tbl_qa_report_samples WHERE audit_id='$AuditId' AND id='$SampleId'";        
	$objDb->query($sSQL);	
	
	if ($objDb->getCount( ) == 1)
	{
		$iStyle    = $objDb->getField(0, "style_id");
		$iSize     = $objDb->getField(0, "size_id");
		$sColor    = $objDb->getField(0, "color");
		$sNature   = $objDb->getField(0, "nature");
		$iSampleNo = $objDb->getField(0, "sample_no");
		
		if ($iStyle == 0)
			$iStyle = getDbValue("style_id", "tbl_qa_reports", "id='$AuditId'");
		
		
		$sStyle = getDbValue("style", "tbl_styles", "id='$iStyle'");
		$sSize  = getDbValue("size", "tbl_sampling_sizes", "id='$iSize'");
	}
        
        if ($_POST)
            @include("update-sample-measurements.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>
<?
	@include($sBaseDir."includes/messages.php");
?>
<div id="Msg" class="msgOk" style="overflow: visible; display: none;"></div>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:645px; height:645px;">
	  <h2><?= $sStyle ?> / <?= $sSize ?></h2>
	  
	  <!-- <form name="frmData" id="frmData" method="post" action="includes/quonda/general-sections/update-sample-measurements.php" class="frmOutline"> -->
          <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="" class="frmOutline">
		<input type="hidden" name="AuditId" id="AuditId" value="<?= $AuditId ?>" />
		<input type="hidden" name="SampleId" id="SampleId" value="<?= $SampleId ?>" />
                <input type="hidden" name="StyleId" id="StyleId" value="<?= $iStyle ?>" />
                <input type="hidden" name="SizeId" id="SizeId" value="<?= $iSize ?>" />            
                            
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr bgcolor="#ffffff">
			  <td width="100%">
			  
<?
			$sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings
					 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
					 WHERE qrs.audit_id='$AuditId' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSize' AND qrs.id='$SampleId'
					 ORDER BY qrs.sample_no, qrss.point_id";
			$objDb->query($sSQL);

			$iCount        = $objDb->getCount( );
			$sSizeFindings = array( );

			for($i = 0; $i < $iCount; $i ++)
			{
				$iSampleNo = $objDb->getField($i, 'sample_no');
				$iPoint    = $objDb->getField($i, 'point_id');
				$sFindings = $objDb->getField($i, 'findings');

				$sSizeFindings["{$iPoint}"] = $sFindings;
			}
?>
				<h2 style="margin:0px;">Measurement Points</h2>

				<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					<tr class="sdRowHeader">
					  <td width="40" align="center"><b>#</b></td>
					  <td><b>Measurement Point</b></td>
					  <td width="100" align="center"><b>Specs</b></td>
					  <td width="100" align="center"><b>Tolerance</b></td>
					  <td width="100" align="center"><b>Findings</b></td>
					</tr>
<?
			$sSQL = "SELECT point_id, specs,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point
					 FROM tbl_style_specs
					 WHERE style_id='$iStyle' AND size_id='$iSize' AND version='0' AND specs!='0' AND specs!=''
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
				  	<td align="center"><input type="text" name="Specs<?= $iSize ?>_<?= $iColor ?>_<?= $iPoint ?>" value="<?= $sSizeFindings["{$iPoint}"] ?>" size="5" maxlength="10" class="textbox" /></td>
			    	</tr>
<?
			}
?>
				</table>
				</div>

            </td>
            </tr>
          </table>
		  
		  
		  <div class="buttonsBar">
		    <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" />
		    <input type="button" value="" class="btnCancel" title="Cancel" onclick="parent.hideLightview();" />
		  </div>

      </form>
          
	  <br style="line-height:2px;" />
    </div>
<!--  Body Section Ends Here  -->

  </div>
</div>
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>