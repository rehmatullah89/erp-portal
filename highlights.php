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

	@require_once("requires/session.php");
	@require_once($sBaseDir."requires/fusion-charts.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$sBrandsList = getList("tbl_brands", "id", "brand", "parent_id!='0'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
</head>

<body style="background:#ffffff;">

  <table border="0" cellpadding="0" cellspacing="0" width="2400">
    <tr valign="top">
      <td width="800">
<?
	$sFromDate = date("Y-m-d", mktime(0, 0, 0, (date("m") - 11), "01", date("Y")));

	$iMonth = date("m");
	$iYear  = date("Y");
	$sMonth = str_pad($iMonth, 2, '0', STR_PAD_LEFT);
	$iDays  = @cal_days_in_month(CAL_GREGORIAN, $iMonth, $iYear);

	$sToDate = ($iYear."-".$sMonth."-".$iDays);


	$fOtp        = array(0,0,0,0,0,0,0,0,0,0,0,0);
	$iPlacements = array(0,0,0,0,0,0,0,0,0,0,0,0);
	$sLabels     = array( );


	$sSQL = "SELECT DATE_FORMAT(pc.etd_required, '%b%y'), COALESCE(SUM(pq.quantity), 0)
			 FROM tbl_po po, tbl_po_quantities pq, tbl_po_colors pc
			 WHERE po.id=pq.po_id AND po.id=pc.po_id AND pc.id=pq.color_id
			       AND po.brand_id NOT IN (57,43,63)
			       AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND pc.etd_required <= CURDATE( )
			 GROUP BY DATE_FORMAT(pc.etd_required, '%Y-%m')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sMonthYear = $objDb->getField($i, 0);
		$iQuantity  = $objDb->getField($i, 1);

		$sLabels[$i]     = $sMonthYear;
		$iPlacements[$i] = $iQuantity;
	}


	$sSQL = "SELECT DATE_FORMAT(pc.etd_required, '%b%y'), COALESCE(SUM(psq.quantity), 0)
			 FROM tbl_po po, tbl_po_colors pc, tbl_pre_shipment_detail psd, tbl_pre_shipment_quantities psq
			 WHERE po.id=pc.po_id AND po.id=psd.po_id AND po.id=psq.po_id AND psd.id=psq.ship_id AND pc.id=psq.color_id
			       AND po.brand_id NOT IN (57,43,63)
			       AND (pc.etd_required BETWEEN '$sFromDate' AND '$sToDate') AND pc.etd_required <= CURDATE( )
			       AND psd.handover_to_forwarder != '0000-00-00' AND NOT ISNULL(psd.handover_to_forwarder)
			       AND IF ( po.brand_id='32', (psd.handover_to_forwarder <= DATE_ADD(pc.etd_required, INTERVAL 2 DAY)),  (psd.handover_to_forwarder <= pc.etd_required) )
			 GROUP BY DATE_FORMAT(pc.etd_required, '%Y-%m')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sMonthYear = $objDb->getField($i, 0);
		$iQuantity  = $objDb->getField($i, 1);

		$fTemp = @round((($iQuantity / $iPlacements[$i]) * 100), 2);
		$fTemp = (($fTemp > 100) ? 100 : $fTemp);

		$fOtp[$i] = $fTemp;
	}
?>
    <div id="OtpChart">loading...</div>

    <script type="text/javascript">
    <!--
	    var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "Otp", "800", "570", "0", "1");

	    objChart.setXMLData("<chart caption='Month wise OTP (last 12 Months)' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='10'>" +
<?
	for ($i = 0; $i < 12; $i ++)
	{
?>
					        "<set label='<?= $sLabels[$i] ?>' value='<?= $fOtp[$i] ?>' />" +
<?
	}
?>
					        "</chart>");


	    objChart.render("OtpChart");
    -->
    </script>
      </td>

      <td width="800">
<?
	$sFromDate = date("Y-m-d", mktime(0, 0, 0, date("m"),  (date("d") - 30), date("Y")));
	$sToDate   = date("Y-m-d");


	$iTotalDefects = array( );
	$iTotalGmts    = array( );

	$sSQL = "SELECT po.brand_id,
					COALESCE(SUM(qa.total_gmts), 0) AS _Gmts,
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_qa_report_defects WHERE audit_id=qa.id) ) AS _Defects
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result='P' AND qa.report_id!='6' AND qa.audit_stage='F'
			       AND po.brand_id IN (65,61,47,43,38,32,20,21,29,28,26,67,75) AND (qa.audit_date BETWEEN '$sFromDate' AND '$sToDate')
			 GROUP BY po.brand_id

			 UNION

			 SELECT po.brand_id,
					SUM( (SELECT COALESCE(SUM(actual_1 + actual_2 + actual_3), 0) FROM tbl_gf_rolls_info WHERE audit_id=qa.id) ) AS _Gmts,
					SUM( (SELECT COALESCE(SUM(defects), 0) FROM tbl_gf_report_defects WHERE audit_id=qa.id) ) AS _Defects
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_result='P' AND qa.report_id='6' AND qa.audit_stage='F'
			       AND po.brand_id IN (65,61,47,43,38,32,20,21,29,28,26,67,75) AND (qa.audit_date BETWEEN '$sFromDate' AND '$sToDate')
			 GROUP BY po.brand_id";
	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrand   = $objDb->getField($i, "brand_id");
		$iDefects = $objDb->getField($i, "_Defects");
		$iGmts    = $objDb->getField($i, "_Gmts");

		$iTotalDefects[$iBrand] += $iDefects;
		$iTotalGmts[$iBrand]    += $iGmts;
	}
?>
    <div id="DrChart">loading...</div>

    <script type="text/javascript">
    <!--
	    var objChart = new FusionCharts("scripts/fusion-charts/charts/Column3D.swf", "Dr", "800", "570", "0", "1");

	    objChart.setXMLData("<chart caption='Defect Rate (Last 30 Days)' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='%' chartBottomMargin='10'>" +
<?
	foreach ($iTotalDefects as $iBrand => $iDefects)
	{
		$iGmts = $iTotalGmts[$iBrand];
		$fDhu  = @round((($iDefects / $iGmts) * 100), 2);
?>
					        "<set label='<?= $sBrandsList[$iBrand] ?>' value='<?= $fDhu ?>' />" +
<?
	}
?>
					        "</chart>");


	    objChart.render("DrChart");
    -->
    </script>
      </td>



      <td width="800">
<?
	$sFromDate = date("Y-m-d", mktime(0, 0, 0, date("m"),  (date("d") - 30), date("Y")));
	$sToDate   = date("Y-m-d");

	$iPass   = array( );
	$iFail   = array( );
	$iHold   = array( );
	$iBrands = array( );

	$sSQL = "SELECT po.brand_id, qa.audit_result, COUNT(DISTINCT(qa.id)) AS _TotalAudits
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id AND qa.audit_type='B' AND qa.audit_stage='F'
			       AND po.brand_id IN (65,61,47,43,38,32,20,21,29,28,26,67,75) AND (qa.audit_date BETWEEN '$sFromDate' AND '$sToDate')
			 GROUP BY po.brand_id, qa.audit_result";
	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iBrandId     = $objDb->getField($i, "brand_id");
		$sAuditResult = $objDb->getField($i, "audit_result");
		$iTotalAudits = $objDb->getField($i, "_TotalAudits");

		if (!@in_array($iBrandId, $iBrands))
			$iBrands[] = $iBrandId;

		switch ($sAuditResult)
		{
			case "P" : $iPass[$iBrandId] = $iTotalAudits;  break;
			case "F" : $iFail[$iBrandId] = $iTotalAudits;  break;
			case "H" : $iHold[$iBrandId] = $iTotalAudits;  break;
		}
	}
?>
    <div id="PfhChart">loading...</div>

    <script type="text/javascript">
    <!--
	    var objChart = new FusionCharts("scripts/fusion-charts/charts/StackedColumn3D.swf", "Pfh", "800", "570", "0", "1");

	    objChart.setXMLData("<chart caption='Audit Results (Last 30 Days)' showSum='1' numDivLines='10' formatNumberScale='0' showValues='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='10'>" +
                            "<categories>" +
<?
	foreach ($iBrands as $iBrand)
	{
		$sBrand = $sBrandsList[$iBrand];
?>
                            "<category label='<?= $sBrand ?>' />" +
<?
	}
?>
                            "</categories>" +

                            "<dataset seriesName='Pass' color='b2e223'>" +
<?
	foreach ($iBrands as $iBrand)
	{
?>
                            "<set value='<?= $iPass[$iBrand] ?>' />" +
<?
	}
?>
                            "</dataset>" +


                            "<dataset seriesName='Fail' color='e83d3d'>" +
<?
	foreach ($iBrands as $iBrand)
	{
?>
                            "<set value='<?= $iFail[$iBrand] ?>' />" +
<?
	}
?>
                            "</dataset>" +


                            "<dataset seriesName='Hold' color='04608f'>" +
<?
	foreach ($iBrands as $iBrand)
	{
?>
                            "<set value='<?= $iHold[$iBrand] ?>' />" +
<?
	}
?>
                            "</dataset>" +
					        "</chart>");


	    objChart.render("PfhChart");
    -->
    </script>
      </td>
    </tr>
  </table>


  <script type="text/javascript">
  <!--
	setTimeout( function( ) { document.location.reload( ); }, 600000);
  -->
  </script>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>