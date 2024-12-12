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
?>
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
		 	  <tr>
			    <td width="175">Factory</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sVendor ?></td>
			  </tr>

		 	  <tr>
			    <td>Sub Contractor</td>
			    <td align="center">:</td>
			    <td><?= $sSubContractor ?></td>
			  </tr>

		 	  <tr>
			    <td>Item</td>
			    <td align="center">:</td>
			    <td><?= $sItem ?></td>
			  </tr>

		 	  <tr>
			    <td>Label</td>
			    <td align="center">:</td>
			    <td><?= $sBrand ?></td>
			  </tr>

		 	  <tr>
			    <td>Style #</td>
			    <td align="center">:</td>
			    <td><?= $sStyle ?></td>
			  </tr>

		 	  <tr>
			    <td>Style Name</td>
			    <td align="center">:</td>
			    <td><?= $sStyleName ?></td>
			  </tr>

		 	  <tr>
			    <td>Season</td>
			    <td align="center">:</td>
			    <td><?= $sSeason ?></td>
			  </tr>

		 	  <tr>
			    <td>Quantity</td>
			    <td align="center">:</td>
			    <td><?= formatNumber($iQuantity, false) ?></td>
			  </tr>

		 	  <tr>
			    <td>Price</td>
			    <td align="center">:</td>
			    <td><?= formatNumber($fPrice) ?></td>
			  </tr>

		 	  <tr>
			    <td>Original ETD</td>
			    <td align="center">:</td>
			    <td><?= $sEtdRequired ?></td>
			  </tr>
<!--
		 	  <tr>
			    <td>Revised ETD</td>
			    <td align="center">:</td>
			    <td><?= $sRevisedEtd ?></td>
			  </tr>
-->
			  <tr>
			    <td>Mode</td>
			    <td align="center">:</td>
			    <td><?= $sMode ?></td>
			  </tr>

			  <tr>
			    <td>Trims</td>
			    <td align="center">:</td>
			    <td><?= $sTrims ?></td>
			  </tr>

			  <tr valign="top">
			    <td>Yarn/Fabric</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sYarnFabric) ?></td>
			  </tr>

			  <tr>
			    <td>Knitting</td>
			    <td align="center">:</td>
			    <td><?= getBtxVsrValue($iKnitting) ?></td>
			  </tr>

			  <tr>
			    <td>Dyeing</td>
			    <td align="center">:</td>
			    <td><?= getBtxVsrValue($iDyeing) ?></td>
			  </tr>

			  <tr>
			    <td>Cutting</td>
			    <td align="center">:</td>
			    <td><?= getBtxVsrValue($iCutting) ?></td>
			  </tr>

			  <tr>
			    <td>Print/Embroidery</td>
			    <td align="center">:</td>
			    <td><?= getBtxVsrValue($iPrintEmbroidery) ?></td>
			  </tr>

			  <tr>
			    <td>Sweing/Linking</td>
			    <td align="center">:</td>
			    <td><?= getBtxVsrValue($iLinking) ?></td>
			  </tr>

			  <tr>
			    <td>Print/Embroidery Start Date</td>
			    <td align="center">:</td>
			    <td><?= getBtxVsrValue($sPrintEmbroideryStartDate) ?></td>
			  </tr>

			  <tr>
			    <td>Washing</td>
			    <td align="center">:</td>
			    <td><?= getBtxVsrValue($iWashing) ?></td>
			  </tr>

			  <tr>
			    <td>Packing</td>
			    <td align="center">:</td>
			    <td><?= getBtxVsrValue($iPacking) ?></td>
			  </tr>

			  <tr>
			    <td>Final Audit Date</td>
			    <td align="center">:</td>
			    <td><?= $sFinalAuditDate ?></td>
			  </tr>

			  <tr>
			    <td>Production Status</td>
			    <td align="center">:</td>
			    <td><?= $sProductionStatus ?></td>
			  </tr>

			  <tr>
			    <td>Shipped Qty</td>
			    <td align="center">:</td>
			    <td><?= formatNumber($iShippedQty, false) ?></td>
			  </tr>

			  <tr valign="top">
			    <td>Remarks</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sRemarks) ?></td>
			  </tr>
		    </table>
