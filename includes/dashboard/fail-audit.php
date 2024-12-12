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
          <h2 style="background:#ff0f00; font-size:21px; font-weight:normal; text-align:center; color:#ffffff; padding:8px; margin:15px 0px 2px 0px;">FAILED AUDITS</h2>
<?
	$sConditions = " AND (qa.audit_date BETWEEN '$sFromDate' AND '$sToDate') AND qa.approved='Y' AND NOT FIND_IN_SET(qa.report_id, '$sQmipReports') AND (qa.audit_result='F' OR qa.audit_result='C') AND NOT FIND_IN_SET(qa.report_id,'4,6,12') ";

	if ($iDepartment > 0)
		$sConditions .= " AND qa.department_id='$iDepartment' ";

	if ($sBrands != "")
		$sConditions .= " AND FIND_IN_SET(qa.brand_id, '$sBrands') ";

	if ($iBrand > 0)
		$sConditions .= " AND qa.brand_id='$iBrand' ";

	if ($sVendors != "")
		$sConditions .= " AND FIND_IN_SET(qa.vendor_id, '$sVendors') ";

	if ($iVendor > 0)
		$sConditions .= " AND qa.vendor_id='$iVendor' ";


	$sSQL = "SELECT qa.id, qa.audit_code, qa.audit_date, qa.audit_stage, s.style, s.sketch_file
			 FROM tbl_qa_reports qa, tbl_styles s
			 WHERE qa.style_id=s.id AND qa.audit_date>=DATE_SUB(CURDATE( ), INTERVAL 6 MONTH) $sConditions
			 ORDER BY qa.id DESC
			 LIMIT 12";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	if ($iCount > 0)
	{
?>
		  <div class="scroll" style="margin-top:10px;">
		    <ul style="margin:0px; padding:0px; list-style:none; width:100%; height:188px; overflow:hidden;">
<?
		for ($i = 0; $i < $iCount; $i ++)
		{

			$sAuditCode  = $objDb->getField($i, 'audit_code');
			$sAuditDate  = $objDb->getField($i, 'audit_date');
			$sAuditStage = $objDb->getField($i, 'audit_stage');
			$sStyle      = $objDb->getField($i, 'style');
			$sPicture    = $objDb->getField($i, 'sketch_file');

			if ($sPicture == "" || !@file_exists($sBaseDir.STYLES_SKETCH_DIR.$sPicture))
				$sPicture = (STYLES_SKETCH_DIR."default.jpg");

			else
			{
				if (!@file_exists($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sPicture))
					createImage(($sBaseDir.STYLES_SKETCH_DIR.$sPicture), ($sBaseDir.STYLES_SKETCH_DIR.'thumbs/'.$sPicture), 160, 160);

				$sPicture = (STYLES_SKETCH_DIR.'thumbs/'.$sPicture);
			}
?>
		      <li id="Audit<?= $i ?>" style="padding:0px; margin:0px 15px 0px 0px; width:161px; float:left;" rel="#<?= (($sAuditStage == "F") ? 'ff0f00' : 'ff8400') ?>">
		        <div class="pic" style="border:solid 5px #<?= (($i == 0) ? (($sAuditStage == "F") ? 'ff0f00' : 'ff8400') : 'aaaaaa') ?>; padding:0px;"><img src="<?= $sPicture ?>" width="151" height="151" alt="" title="" /></div>
		        <div class="style" style="text-align:center; font-weight:bold; font-size:15px; color:#<?= (($i == 0) ? (($sAuditStage == "F") ? 'ff0f00' : 'ff8400') : '333333') ?>; padding:5px 0px 5px 0px;"><?= $sStyle ?></div>
		      </li>
<?
		}
?>
		    </ul>
		  </div>


		  <div style="height:200px; margin-top:20px;">
<?
		if ($iCount > 8)
			$iCount = 8;

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sAuditCode = $objDb->getField($i, 'audit_code');
			$sAuditDate = $objDb->getField($i, 'audit_date');


			@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

			$sPictures = @glob($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/?".substr($sAuditCode, 1)."_*.*");
			$sPictures = @array_map("strtoupper", $sPictures);
			$sPictures = @array_unique($sPictures);

			shuffle($sPictures);
			$iPictures = count($sPictures);
?>
		  <div id="PictureAudit<?= $i ?>" style="display:<?= (($i == 0) ? 'block' : 'none') ?>">
<?
			if ($iPictures == 0)
			{
?>
			<div style="padding:10px; font-size:17px;">
			  No Defect Image Found!<br />
			</div>
<?
			}

			else
			{
?>
		    <ul style="margin:0px; padding:0px; list-style:none; width:100%; height:200px; overflow:hidden;">
<?
				$iPictures = (($iPictures > 10) ? 10 : $iPictures);

				for ($j = 0; $j < $iPictures; $j ++)
				{
?>
		      <li style="padding:0px; margin:0px 10px 10px 0px; width:130px; float:left;">
			    <div style="border:solid 1px #aaaaaa; padding:4px;"><img src="<?= $sPictures[$j] ?>" width="120" height="85" alt="" title="" /></div>
		      </li>
<?
				}
?>
		    </ul>
<?
			}
?>
		  </div>
<?
		}
?>
		  </div>

<?
		if ($iCount > 1)
		{
?>
		  <script type="text/javascript">
		  <!--
				jQuery.noConflict( );

				jQuery(document).ready(function($)
				{
					$(".scroll").jCarouselLite(
					{
						scroll      : "",
						btnPrev     : "",
						circular    : true,
						visible     : <?= (($iCount >= 4) ? '4' : $iCount) ?>,
						start       : 0,
						scroll      : 1,
						auto        : 15000,
						speed       : 1000,

						beforeStart : function(a)
						{
							$("#" + a[0].id + " .pic").css("border", "solid 5px #aaaaaa");
							$("#" + a[0].id + " .style").css("color", "#333333");

							$("#Picture" + a[0].id).hide('blind');
						},

						afterEnd    : function(a)
						{
							$("#" + a[0].id + " .pic").css("border", ("solid 5px " + $("#" + a[0].id).attr("rel")));
							$("#" + a[0].id + " .style").css("color", $("#" + a[0].id).attr("rel"));

							$("#Picture" + a[0].id).show('blind');
						}
					});
				});
		  -->
		  </script>
<?
		}
	}

	else
	{
?>
		  <div style="padding:10px; font-size:17px;">
		    No Fail Audit Found!<br />
		  </div>
<?
	}
?>