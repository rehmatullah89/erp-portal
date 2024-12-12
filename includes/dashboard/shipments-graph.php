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

	$sConditions = " AND qa.audit_type='B' AND qa.audit_result='P' AND (qa.audit_date BETWEEN '$sFromDate' AND '$sToDate') AND qa.audit_stage='F' ";

	if ($Vendor > 0)
		$sConditions .= " AND qa.vendor_id='$Vendor' ";


	$iPlanned = array( );
	$iShip    = array( );
	$iReady   = array( );
	$sVendors = array( );
	$iVendors = array( );


	$sSQL = "SELECT po.vendor_id, SUM(qa.ship_qty) AS _Qty, po.status AS _Status, SUM(po.quantity) AS _OrderQty
			 FROM tbl_po po, tbl_qa_reports qa
			 WHERE po.id=qa.po_id $sConditions
			 GROUP BY po.vendor_id, po.id
	         ORDER BY po.vendor_id";
	$objDb->query($sSQL);


	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendorId = $objDb->getField($i, "vendor_id");
		$sStatus   = $objDb->getField($i, "_Status");
		$iQuantity = $objDb->getField($i, "_Qty");
		$iOrderQty = $objDb->getField($i, "_OrderQty");

		if (!@in_array($iVendorId, $iVendors))
		{
			$sVendors[] = $sVendorsList[$iVendorId];
			$iVendors[] = $iVendorId;

			$iShip[]    = 0;
			$iReady[]   = 0;
			$iPlanned[] = 0;
		}


		$iIndex = @array_search($iVendorId, $iVendors);

		switch ($sStatus)
		{
			case "C" : $iShip[$iIndex]  += $iQuantity;  break;
			case "W" : $iReady[$iIndex] += $iQuantity;  break;
		}

		$iPlanned[$iIndex] += $iOrderQty;
	}
?>
				  <div id="ShipmentChart">loading...</div>
				  <br />

				  <script type="text/javascript">
				  <!--
						var objChart = new FusionCharts("scripts/fusion-charts/charts/MSColumnLine3D.swf", "Shipment", "100%", "500", "0", "1");

						objChart.setXMLData("<chart caption='Shipment Stats' formatNumberScale='0' showValues='0' showSum='1' showLabels='1' decimals='1' numberSuffix='' chartBottomMargin='5' plotFillAlpha='95' labelDisplay='ROTATE' slantLabels='1' exportEnabled='1' exportShowMenuItem='1' exportAtClient='0' exportHandler='scripts/fusion-charts/PHP/FCExporter.php' exportAction='download' exportFileName='status-wise-graph'>" +

											"<categories>" +
<?
	for ($i = 0; $i < count($iVendors); $i ++)
	{
?>
											"<category label='<?= $sVendorsList[$iVendors[$i]] ?>' />" +
<?
	}
?>
											"</categories>" +


											"<dataset seriesName='Shipped' color='b6e500'>" +
<?
	for ($i = 0; $i < count($iVendors); $i ++)
	{
?>
											"<set value='<?= $iShip[$i] ?>' />" +
<?
	}
?>
											"</dataset>" +

											"<dataset seriesName='Ready to Ship' color='fcbf04'>" +
<?
	for ($i = 0; $i < count($iVendors); $i ++)
	{
?>
											"<set value='<?= $iReady[$i] ?>' />" +
<?
	}
?>
											"</dataset>" +

											"<dataset seriesName='Planned' renderAs='Line'>" +
<?
	for ($i = 0; $i < count($iVendors); $i ++)
	{
?>
											"<set value='<?= $iPlanned[$i] ?>' />" +
<?
	}
?>
											"</dataset>" +

											"<dataset seriesName='Balanced' renderAs='Line'>" +
<?
	for ($i = 0; $i < count($iVendors); $i ++)
	{
?>
											"<set value='<?= ($iPlanned[$i] - ($iShip[$i] + $iReady[$i])) ?>' />" +
<?
	}
?>
											"</dataset>" +

										"</chart>");


						objChart.render("ShipmentChart");
				  -->
				  </script>
