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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_gf_specs WHERE style_id='$Id'";
	$objDb->query($sSQL);

	$sGreigeSupplier     = $objDb->getField(0, 'greige_supplier');
	$sGreigeConstruction = $objDb->getField(0, 'greige_construction');
	$fGreigeWidth        = $objDb->getField(0, 'greige_width');
	$sContactPiNo        = $objDb->getField(0, 'contact_pi_no');
	$sBtlPo              = $objDb->getField(0, 'btl_po');
	$sDate               = $objDb->getField(0, 'date');
	$iCapacity           = $objDb->getField(0, 'capacity');

	$sWarpCount          = $objDb->getField(0, 'warp_count');
	$sSpinType           = $objDb->getField(0, 'spin_type');
	$sYarnType           = $objDb->getField(0, 'yarn_type');
	$sCottonOrigin       = $objDb->getField(0, 'cotton_origin');
	$sWarpComposition    = $objDb->getField(0, 'warp_composition');
	$sSupplier           = $objDb->getField(0, 'supplier');
	$sWeftCount          = $objDb->getField(0, 'weft_count');
	$sSpinTypeWeft       = $objDb->getField(0, 'spin_type_weft');
	$sYarnTypeWe         = $objDb->getField(0, 'yarn_type_we');
	$sCottonOriginWe     = $objDb->getField(0, 'cotton_origin_we');
	$sWeftComposition    = $objDb->getField(0, 'weft_composition');
	$sSupplier1          = $objDb->getField(0, 'supplier1');
	$sTpi                = $objDb->getField(0, 'tpi');
	$sClsp               = $objDb->getField(0, 'clsp');

	$sCoverFactor        = $objDb->getField(0, 'cover_factor');
	$sTotalEnds          = $objDb->getField(0, 'total_ends');
	$sReedCount          = $objDb->getField(0, 'reed_count');
	$sEndsDent           = $objDb->getField(0, 'ends_dent');
	$sReedSpace          = $objDb->getField(0, 'reed_space');
	$sClothWidth         = $objDb->getField(0, 'cloth_width');
	$sEnds               = $objDb->getField(0, 'ends');
	$sPicks              = $objDb->getField(0, 'picks');
	$sWeave              = $objDb->getField(0, 'weave');
	$sGsm                = $objDb->getField(0, 'gsm');
	$sOverallComposition = $objDb->getField(0, 'overall_composition');

	$sSelType            = $objDb->getField(0, 'sel_type');
	$sNormalBinding      = $objDb->getField(0, 'normal_binding');
	$sSelWidth           = $objDb->getField(0, 'sel_width');
	$sSelWeave           = $objDb->getField(0, 'sel_weave');
	$sSelEndsDent        = $objDb->getField(0, 'sel_ends_dent');
	$sComments           = $objDb->getField(0, 'comments');

	$sWpRibSize          = $objDb->getField(0, 'wp_rib_size');
	$sWfRibSize          = $objDb->getField(0, 'wf_rib_size');
	$sEndsRib            = $objDb->getField(0, 'ends_rib');
	$sPicksRib           = $objDb->getField(0, 'picks_rib');

	$sSlubLength         = $objDb->getField(0, 'slub_length');
	$sPause              = $objDb->getField(0, 'pause');
	$sThickness          = $objDb->getField(0, 'thickness');

	$sLycraDraft         = $objDb->getField(0, 'lycra_draft');
	$sLycraYarn          = $objDb->getField(0, 'lycra_yarn');
	$sLycraFabric        = $objDb->getField(0, 'lycra_fabric');
	$sBoilOffWidth       = $objDb->getField(0, 'boil_off_width');
	$sShrinkage          = $objDb->getField(0, 'shrinkage');

	$sWarp               = $objDb->getField(0, 'warp');
	$sWeft               = $objDb->getField(0, 'weft');

	$sRemarks            = $objDb->getField(0, 'remarks');
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
	<div id="Body" style="min-height:694px; height:694px;">
	  <h2>Style Details</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%" bgcolor="#ffffff">
	  <tr>
		<td width="200">Greige Supplier</td>
		<td width="20" align="center">:</td>
		<td><?= $sGreigeSupplier ?></td>
	  </tr>

	  <tr>
		<td>Greige Construction</td>
		<td align="center">:</td>
		<td><?= $sGreigeConstruction ?></td>
	  </tr>

	  <tr>
		<td>Greige Width</td>
		<td align="center">:</td>
		<td><?= $fGreigeWidth ?></td>
	  </tr>

	  <tr>
		<td>Contact PI #</td>
		<td align="center">:</td>
		<td><?= $sContactPiNo ?></td>
	  </tr>

	  <tr>
		<td>BTL PO</td>
		<td align="center">:</td>
		<td><?= $sBtlPo ?></td>
	  </tr>

	  <tr>
		<td>Date</td>
		<td align="center">:</td>
		<td><?= formatDate($sDate) ?></td>
	  </tr>

	  <tr>
		<td>Capacity</td>
		<td align="center">:</td>
		<td><?= formatNumber($iCapacity, false) ?> meters/day</td>
	  </tr>

	  <tr>
		<td colspan="3"><h3>Yarn</h3></td>
	  </tr>

	  <tr>
		<td>Warp Count ( origin )</td>
		<td align="center">:</td>
		<td><?= $sWarpCount ?></td>
	  </tr>

	  <tr>
		<td>Spin. Type (RS/OE)</td>
		<td align="center">:</td>
		<td><?= $sSpinType ?></td>
	  </tr>

	  <tr>
		<td>Yarn Type (CD/CM/CMCOMP)</td>
		<td align="center">:</td>
		<td><?= $sYarnType ?></td>
	  </tr>

	  <tr>
		<td>Cotton Origin</td>
		<td align="center">:</td>
		<td><?= $sCottonOrigin ?></td>
	  </tr>

	  <tr>
		<td>Warp Composition</td>
		<td align="center">:</td>
		<td><?= $sWarpComposition ?></td>
	  </tr>

	  <tr>
		<td>Supplier</td>
		<td align="center">:</td>
		<td><?= $sSupplier ?></td>
	  </tr>

	  <tr>
		<td>Weft Count ( origin )</td>
		<td align="center">:</td>
		<td><?= $sWeftCount ?></td>
	  </tr>

	  <tr>
		<td>Spin. Type (RS/OE)_Weft</td>
		<td align="center">:</td>
		<td><?= $sSpinTypeWeft ?></td>
	  </tr>

	  <tr>
		<td>Yarn Type (CD/CM/CMCOMP)_We</td>
		<td align="center">:</td>
		<td><?= $sYarnTypeWe ?></td>
	  </tr>

	  <tr>
		<td>Cotton Origin_We</td>
		<td align="center">:</td>
		<td><?= $sCottonOriginWe ?></td>
	  </tr>

	  <tr>
		<td>Weft Composition</td>
		<td align="center">:</td>
		<td><?= $sWeftComposition ?></td>
	  </tr>

	  <tr>
		<td>Supplier1</td>
		<td align="center">:</td>
		<td><?= $sSupplier1 ?></td>
	  </tr>

	  <tr>
		<td>T.P.I ( Warp/Weft )</td>
		<td align="center">:</td>
		<td><?= $sTpi ?></td>
	  </tr>

	  <tr>
		<td>CLSP ( Warp/Weft )</td>
		<td align="center">:</td>
		<td><?= $sClsp ?></td>
	  </tr>

	  <tr>
		<td colspan="3"><h3>Fabric</h3></td>
	  </tr>

	  <tr>
		<td>Cover Factor</td>
		<td align="center">:</td>
		<td><?= $sCoverFactor ?></td>
	  </tr>

	  <tr>
		<td>Total Ends</td>
		<td align="center">:</td>
		<td><?= $sTotalEnds ?></td>
	  </tr>

	  <tr>
		<td>Reed Count</td>
		<td align="center">:</td>
		<td><?= $sReedCount ?></td>
	  </tr>

	  <tr>
		<td>Ends/Dent</td>
		<td align="center">:</td>
		<td><?= $sEndsDent ?></td>
	  </tr>

	  <tr>
		<td>Reed Space (mm)</td>
		<td align="center">:</td>
		<td><?= $sReedSpace ?></td>
	  </tr>

	  <tr>
		<td>Cloth Width-inch (off Loom)</td>
		<td align="center">:</td>
		<td><?= $sClothWidth ?></td>
	  </tr>

	  <tr>
		<td>Ends/1"</td>
		<td align="center">:</td>
		<td><?= $sEnds ?></td>
	  </tr>

	  <tr>
		<td>Picks/1"</td>
		<td align="center">:</td>
		<td><?= $sPicks ?></td>
	  </tr>

	  <tr>
		<td>Weave</td>
		<td align="center">:</td>
		<td><?= $sWeave ?></td>
	  </tr>

	  <tr>
		<td>GSM</td>
		<td align="center">:</td>
		<td><?= $sGsm ?></td>
	  </tr>

	  <tr>
		<td>Overall Composition</td>
		<td align="center">:</td>
		<td><?= $sOverallComposition ?></td>
	  </tr>

	  <tr>
		<td colspan="3"><h3>Selvedge</h3></td>
	  </tr>

	  <tr>
		<td>SEL. Type</td>
		<td align="center">:</td>
		<td><?= $sSelType ?></td>
	  </tr>

	  <tr>
		<td>Normal / Binding</td>
		<td align="center">:</td>
		<td><?= $sNormalBinding ?></td>
	  </tr>

	  <tr>
		<td>SEL. Width</td>
		<td align="center">:</td>
		<td><?= $sSelWidth ?></td>
	  </tr>

	  <tr>
		<td>SEL. Weave</td>
		<td align="center">:</td>
		<td><?= $sSelWeave ?></td>
	  </tr>

	  <tr>
		<td>SEL. Ends/Dent</td>
		<td align="center">:</td>
		<td><?= $sSelEndsDent ?></td>
	  </tr>

	  <tr>
		<td>Comments</td>
		<td align="center">:</td>
		<td><?= $sComments ?></td>
	  </tr>

	  <tr>
		<td colspan="3"><h3>RIB</h3></td>
	  </tr>

	  <tr>
		<td>Wp. Rib Size (mm)</td>
		<td align="center">:</td>
		<td><?= $sWpRibSize ?></td>
	  </tr>

	  <tr>
		<td>Wf. Rib Size (mm)</td>
		<td align="center">:</td>
		<td><?= $sWfRibSize ?></td>
	  </tr>

	  <tr>
		<td>Ends/Rib (Wp.)</td>
		<td align="center">:</td>
		<td><?= $sEndsRib ?></td>
	  </tr>

	  <tr>
		<td>Picks/Rib (Wf.)</td>
		<td align="center">:</td>
		<td><?= $sPicksRib ?></td>
	  </tr>

	  <tr>
		<td colspan="3"><h3>Slub</h3></td>
	  </tr>

	  <tr>
		<td>Slub Length (cm)</td>
		<td align="center">:</td>
		<td><?= $sSlubLength ?></td>
	  </tr>

	  <tr>
		<td>Pause (cm)</td>
		<td align="center">:</td>
		<td><?= $sPause ?></td>
	  </tr>

	  <tr>
		<td>Thickness (times)</td>
		<td align="center">:</td>
		<td><?= $sThickness ?></td>
	  </tr>

	  <tr>
		<td colspan="3"><h3>Lycra</h3></td>
	  </tr>

	  <tr>
		<td>Lycra Draft</td>
		<td align="center">:</td>
		<td><?= $sLycraDraft ?></td>
	  </tr>

	  <tr>
		<td>Lycra % ( yarn)</td>
		<td align="center">:</td>
		<td><?= $sLycraYarn ?></td>
	  </tr>

	  <tr>
		<td>Lycra % ( Fabric)</td>
		<td align="center">:</td>
		<td><?= $sLycraFabric ?></td>
	  </tr>

	  <tr>
		<td>Boil-off width</td>
		<td align="center">:</td>
		<td><?= $sBoilOffWidth ?></td>
	  </tr>

	  <tr>
		<td>Shrinkage %</td>
		<td align="center">:</td>
		<td><?= $sShrinkage ?></td>
	  </tr>

	  <tr>
		<td colspan="3"><h3>TearingStr</h3></td>
	  </tr>

	  <tr>
		<td>Warp (g)</td>
		<td align="center">:</td>
		<td><?= $sWarp ?></td>
	  </tr>

	  <tr>
		<td>Weft (g)</td>
		<td align="center">:</td>
		<td><?= $sWeft ?></td>
	  </tr>

	  <tr>
		<td colspan="3"><h3>Remarks</h3></td>
	  </tr>

	  <tr valign="top">
		<td colspan="3"><?= nl2br($sRemarks) ?></td>
	  </tr>
	  </table>
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