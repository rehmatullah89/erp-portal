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
?>
<?
        @require_once("../../requires/session.php");	
	@header("Content-type: text/html; charset=utf-8");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	
        $iColor         = 0; 
	$AuditId        = IO::intValue('AuditId');
        $sSampleNature  = IO::strValue('Nature'); 
        $QaSampleId     = IO::intValue('QaSampleId');
        $Style          = IO::intValue('Style');        
        $sColor         = IO::strValue('Color');
        $sSize          = IO::strValue('Size');
        $iSize          = IO::intValue('SizeId');
        $SampleNo       = IO::intValue('SampleNo');
        $iSamplingSize  = IO::intValue('SamplingSize');
        
        $iReportId = getDbValue("report_id", "tbl_qa_reports", "id='$AuditId'");
/*        $sSizesList   = getList("tbl_sizes", "id", "size", "FIND_IN_SET(id, '$Sizes')", "size");*/
        
        if ($_POST)
		@include("update-measurement-specs.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("../../includes/meta-tags.php");
?>
	<style>
	.evenRow {
		background: #f6f4f5 none repeat scroll 0 0;
	}
	.oddRow {
		background: #dcdcdc none repeat scroll 0 0;
	}

	#Mytable tr:nth-child(even){
	   background-color: #f2f2f2
	}
		
	#Mytable2 {
	   font-size: 9px;
	}
	</style>    
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:544px; height:544px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr bgcolor="#ffffff">
                  <td width="100%">
<?
			$sSQL = "SELECT qrs.sample_no, qrss.point_id, qrss.findings, qrss.specs
					 FROM tbl_qa_report_samples qrs, tbl_qa_report_sample_specs qrss
					 WHERE qrs.audit_id='$AuditId' AND qrs.sample_no= '$SampleNo' AND qrs.id=qrss.sample_id AND qrs.size_id='$iSamplingSize' AND qrs.color='$sColor' AND (qrs.size='' OR qrs.size='$sSize')
					 ORDER BY qrs.sample_no, qrss.point_id";
                        $objDb->query($sSQL);

			$iCount        = (int)$objDb->getCount( );
			
                        $sSizeFindings = array( );
                        $sSizeSpecs    = array( );
                        
                         
			for($i = 0; $i < $iCount; $i ++)
			{
				$iSampleNo      = $objDb->getField($i, 'sample_no');
				$iPoint         = $objDb->getField($i, 'point_id');
				$sFindings      = $objDb->getField($i, 'findings');
                                $sSizeSpec      = $objDb->getField($i, 'specs');

				$sSizeFindings["{$iPoint}"] = (($sFindings == '' || $sFindings == '0' || strtolower($sFindings) == 'ok')?'-':$sFindings);
                                $sSizeSpecs["{$iPoint}"] = $sSizeSpec;
			}
?>
			<h2 style="margin:0px;">Measurement Sheet (Size: <?= $sSize ?>, Color: <?= $sColor ?>)</h2>
                        <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="" class="frmOutline">
                                <input type="hidden" name="AuditId" value="<?=$AuditId?>"/>
                                <input type="hidden" name="Style" value="<?=$Style?>"/>
                                <input type="hidden" name="Color" value="<?=$sColor?>"/>
                                <input type="hidden" name="Size" value="<?=$sSize?>"/>
                                <input type="hidden" name="SizeId" value="<?=$iSize?>"/>
                                <input type="hidden" name="SampleNo" value="<?=$SampleNo?>"/>
                                <input type="hidden" name="QaSampleId" value="<?=$QaSampleId?>"/>                                
                                <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
					<tr class="sdRowHeader">
					  <td width="25" align="center"><b>#</b></td>
<?
                                    if(@in_array($iReportId, array(44,45))){
?>
                                          <td width="50" align="center"><b>POM</b></td>
<?
                                    }
?>
					  <td><b>Measurement Point</b></td>
					  <td width="80" align="center"><b>Specs</b></td>
					  <td width="80" align="center"><b>Tolerance</b></td>
					  <td width="45" align="center"><b>1</b></td>
					</tr>
<?
			$sSQL = "SELECT point_id, specs, nature,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
                                                        (SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
					 FROM tbl_style_specs
					 WHERE style_id='$Style' AND size_id='$iSamplingSize' AND version='0'
					 ORDER BY FIELD(nature, 'C') DESC";
			$objDb->query($sSQL);
			$iCount = $objDb->getCount( );
                        
                        if ($iCount == 0 && $sSize == "XXL")
                        {
                                $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '2XL'");

                                if ($iSamplingSize > 0)
                                {
                                        $sSQL = "SELECT point_id, specs, nature,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
                                                        (SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
					 FROM tbl_style_specs
					 WHERE style_id='$Style' AND size_id='$iSamplingSize' AND version='0'
					 ORDER BY FIELD(nature, 'C') DESC";
                                        $objDb->query($sSQL);

                                        $iCount = $objDb->getCount( );
                                }
                        }
                        
                        if ($iCount == 0 && strpos($sSize, " ") !== FALSE)
                        {
                                $sSize         = str_replace(" ", "", $sSize);
                                $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");

                                if ($iSamplingSize == 0 && substr($sSize, -2) == " S")
                                {
                                        $sSize         = str_replace(" S", "W", $sSize);
                                        $iSamplingSize = getDbValue("id", "tbl_sampling_sizes", "size LIKE '$sSize'");
                                }

                                if ($iSamplingSize > 0)
                                {
                                        $sSQL = "SELECT point_id, specs, nature,
							(SELECT tolerance FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Tolerance,
							(SELECT point FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _Point,
                                                        (SELECT point_id FROM tbl_measurement_points WHERE id=tbl_style_specs.point_id) AS _PointId
					 FROM tbl_style_specs
					 WHERE style_id='$Style' AND size_id='$iSamplingSize' AND version='0'
					 ORDER BY FIELD(nature, 'C') DESC";
                                        $objDb->query($sSQL);

                                        $iCount = $objDb->getCount( );
                                }
                        }

			for($i = 0; $i < $iCount; $i ++)
			{
				$iPoint     = $objDb->getField($i, 'point_id');
                                $sNature    = $objDb->getField($i, 'nature');
				$sSpecs     = $objDb->getField($i, 'specs');
				$sPoint     = $objDb->getField($i, '_Point');
                                $iPointId   = $objDb->getField($i, '_PointId');
				$sTolerance = $objDb->getField($i, '_Tolerance');
                                
                                if($sSampleNature == 'C' && $sNature != 'C')
                                    continue;
?>
					<tr class="sdRowColor">
					  <td align="center"><?= ($i + 1) ?></td>
<?
                                    if(@in_array($iReportId, array(44,45))){
?>
                                          <td <?=(strtolower($sNature) == 'c'?'style="color:red;"':'')?>><?=$iPointId?></td>
<?
                                    }
?>
                                          <td <?=(strtolower($sNature) == 'c'?'style="color:red;"':'')?>><?= $sPoint ?></td>
                                          <td align="center">
                                              <?
                                                if(@in_array($iPointId, array("INS1","INSEC")))
                                                {
?>
                                              <input type="text" style="width:50px;" name="ReplaceSpecs<?= $iSamplingSize ?>_<?= $iPoint ?>" value="<?=(@$sSizeSpecs[$iPoint] != ""?$sSizeSpecs[$iPoint]:$sSpecs)?>">
<?
                                                }
                                                else
                                                    echo $sSpecs;
                                                ?>
                                          </td>
					  <td align="center"><?= $sTolerance ?></td>
				  	<td align="center"><input type="text" name="Specs<?= $iSamplingSize ?>_<?= $iColor ?>_<?= $iPoint ?>" value="<?= $sSizeFindings["{$iPoint}"] ?>" size="4" maxlength="10" class="textbox" /></td>
			    	</tr>
<?
			}
?>
				</table>
                        </div>
                                <div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" />
				  <input type="button" value="" class="btnCancel" title="Cancel" onclick="parent.hideLightview();" />
				</div>
                                <input type="hidden" name="SamplingSize" value="<?=$iSamplingSize?>"/>
                  </form>
                  </td>
              </tr>
            </table>

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
	$objDbGlobal->close( );
	@ob_end_flush( );
?>
