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
	$objDb2      = new Database( );

	$Id      = IO::intValue('Id');
	$Referer = IO::strValue("Referer");

	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];

	$sSQL = "SELECT * FROM tbl_gf_specs WHERE style_id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
		redirect($Referer, "DB_ERROR");

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

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/yarn/edit-specs.js"></script>
</head>

<body>

<div id="MainDiv">
  <div id="PageLeftBorder">
    <div id="PageRightBorder">

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

      <div id="PageContents">

<!--  Header Section Starts Here  -->
<?
	@include($sBaseDir."includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include($sBaseDir."includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
	    <div id="Body">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1><img src="images/h1/yarn/specs.jpg" width="124" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmData" id="frmData" method="post" action="yarn/update-specs.php" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />
			    <input type="hidden" name="Referer" value="<?= $Referer ?>" />

			    <h2>Edit GF Specs</h2>
			    <table width="98%" cellspacing="0" cellpadding="3" border="0" align="center">
				  <tr>
				    <td width="200">Greige Supplier</td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="GreigeSupplier" value="<?= $sGreigeSupplier ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Greige Construction</td>
				    <td align="center">:</td>
				    <td><input type="text" name="GreigeConstruction" value="<?= $sGreigeConstruction ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Greige Width</td>
				    <td align="center">:</td>
				    <td><input type="text" name="GreigeWidth" value="<?= $fGreigeWidth ?>" size="30" maxlength="10" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Contact PI #</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ContactPiNo" value="<?= $sContactPiNo ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>BTL PO</td>
				    <td align="center">:</td>
				    <td><input type="text" name="BtlPo" value="<?= $sBtlPo ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Date</td>
				    <td align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="Date" id="Date" value="<?= (($sDate == "0000-00-00") ? "" : $sDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>
				  </tr>

				  <tr>
				    <td>Capacity</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Capacity" value="<?= $icapacity ?>" size="9" maxlength="3" class="textbox" /> (meters/day)</td>
				  </tr>

				  <tr>
				    <td colspan="3"><h3>Yarn</h3></td>
				  </tr>

				  <tr>
				    <td>Warp Count ( origin )</td>
				    <td align="center">:</td>
				    <td><input type="text" name="WarpCount" value="<?= $sWarpCount ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Spin. Type (RS/OE)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="SpinType" value="<?= $sSpinType ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Yarn Type (CD/CM/CMCOMP)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="YarnType" value="<?= $sYarnType ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Cotton Origin</td>
				    <td align="center">:</td>
				    <td><input type="text" name="CottonOrigin" value="<?= $sCottonOrigin ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Warp Composition</td>
				    <td align="center">:</td>
				    <td><input type="text" name="WarpComposition" value="<?= $sWarpComposition ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Supplier</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Supplier" value="<?= $sSupplier ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Weft Count ( origin )</td>
				    <td align="center">:</td>
				    <td><input type="text" name="WeftCount" value="<?= $sWeftCount ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Spin. Type (RS/OE)_Weft</td>
				    <td align="center">:</td>
				    <td><input type="text" name="SpinTypeWeft" value="<?= $sSpinTypeWeft ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Yarn Type (CD/CM/CMCOMP)_We</td>
				    <td align="center">:</td>
				    <td><input type="text" name="YarnTypeWe" value="<?= $sYarnTypeWe ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Cotton Origin_We</td>
				    <td align="center">:</td>
				    <td><input type="text" name="CottonOriginWe" value="<?= $sCottonOriginWe ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Weft Composition</td>
				    <td align="center">:</td>
				    <td><input type="text" name="WeftComposition" value="<?= $sWeftComposition ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Supplier1</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Supplier1" value="<?= $sSupplier1 ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>T.P.I ( Warp/Weft )</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Tpi" value="<?= $sTpi ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>CLSP ( Warp/Weft )</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Clsp" value="<?= $sClsp ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td colspan="3"><h3>Fabric</h3></td>
				  </tr>

				  <tr>
				    <td>Cover Factor</td>
				    <td align="center">:</td>
				    <td><input type="text" name="CoverFactor" value="<?= $sCoverFactor ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Total Ends</td>
				    <td align="center">:</td>
				    <td><input type="text" name="TotalEnds" value="<?= $sTotalEnds ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Reed Count</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ReedCount" value="<?= $sReedCount ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Ends/Dent</td>
				    <td align="center">:</td>
				    <td><input type="text" name="EndsDent" value="<?= $sEndsDent ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Reed Space (mm)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ReedSpace" value="<?= $sReedSpace ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Cloth Width-inch (off Loom)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="ClothWidth" value="<?= $sClothWidth ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Ends/1"</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Ends" value="<?= $sEnds ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Picks/1"</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Picks" value="<?= $sPicks ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Weave</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Weave" value="<?= $sWeave ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>GSM</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Gsm" value="<?= $sGsm ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Overall Composition</td>
				    <td align="center">:</td>
				    <td><input type="text" name="OverallComposition" value="<?= $sOverallComposition ?>" size="30" maxlength="100" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td colspan="3"><h3>Selvedge</h3></td>
				  </tr>

				  <tr>
				    <td>SEL. Type</td>
				    <td align="center">:</td>
				    <td><input type="text" name="SelType" value="<?= $sSelType ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Normal / Binding</td>
				    <td align="center">:</td>
				    <td><input type="text" name="NormalBinding" value="<?= $sNormalBinding ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>SEL. Width</td>
				    <td align="center">:</td>
				    <td><input type="text" name="SelWidth" value="<?= $sSelWidth ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>SEL. Weave</td>
				    <td align="center">:</td>
				    <td><input type="text" name="SelWeave" value="<?= $sSelWeave ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>SEL. Ends/Dent</td>
				    <td align="center">:</td>
				    <td><input type="text" name="SelEndsDent" value="<?= $sSelEndsDent ?>" size="30" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr valign="top">
				    <td>Comments</td>
				    <td align="center">:</td>
				    <td><textarea name="Comments" rows="5" cols="60"><?= $sComments ?></textarea></td>
				  </tr>

				  <tr>
				    <td colspan="3"><h3>RIB</h3></td>
				  </tr>

				  <tr>
				    <td>Wp. Rib Size (mm)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="WpRibSize" value="<?= $sWpRibSize ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Wf. Rib Size (mm)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="WfRibSize" value="<?= $sWfRibSize ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Ends/Rib (Wp.)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="EndsRib" value="<?= $sEndsRib ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Picks/Rib (Wf.)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="PicksRib" value="<?= $sPicksRib ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td colspan="3"><h3>Slub</h3></td>
				  </tr>

				  <tr>
				    <td>Slub Length (cm)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="SlubLength" value="<?= $sSlubLength ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Pause (cm)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Pause" value="<?= $sPause ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Thickness (times)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Thickness" value="<?= $sThickness ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td colspan="3"><h3>Lycra</h3></td>
				  </tr>

				  <tr>
				    <td>Lycra Draft</td>
				    <td align="center">:</td>
				    <td><input type="text" name="LycraDraft" value="<?= $sLycraDraft ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Lycra % ( yarn)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="LycraYarn" value="<?= $sLycraYarn ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Lycra % ( Fabric)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="LycraFabric" value="<?= $sLycraFabric ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Boil-off width</td>
				    <td align="center">:</td>
				    <td><input type="text" name="BoilOffWidth" value="<?= $sBoilOffWidth ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Shrinkage %</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Shrinkage" value="<?= $sShrinkage ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td colspan="3"><h3>TearingStr</h3></td>
				  </tr>

				  <tr>
				    <td>Warp (g)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Warp" value="<?= $sWarp ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td>Weft (g)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Weft" value="<?= $sWeft ?>" size="30" maxlength="20" class="textbox" /></td>
				  </tr>

				  <tr>
				    <td colspan="3"><h3>Remarks</h3></td>
				  </tr>

				  <tr>
				    <td colspan="3"><textarea name="Remarks" rows="5" cols="50" style="width:98%;"><?= $sRemarks ?></textarea></td>
				  </tr>
				</table>

				<br />

			    <div class="buttonsBar">
			      <input type="submit" id="BtnSave" value="" class="btnSave" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnBack" onclick="document.location='<?= $Referer ?>';" />
			    </div>
			    </form>
			  </td>
			</tr>
		  </table>

<?
	@include($sBaseDir."includes/my-profile.php");
?>
        </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include($sBaseDir."includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

      </div>
    </div>
  </div>
</div>

<!--  Bottom Bar Section Starts Here  -->
<?
	@include($sBaseDir."includes/bottom-bar.php");
?>
<!--  Bottom Bar Section Ends Here  -->

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>