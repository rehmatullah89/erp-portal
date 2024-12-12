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

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id      = IO::strValue("Id");
	$Referer = urlencode(IO::strValue("Referer"));


	$sSQL  = ("UPDATE tbl_gf_specs SET greige_supplier     = '".IO::strValue("GreigeSupplier")."',
	                                   greige_construction = '".IO::strValue("GreigeConstruction")."',
	                                   greige_width        = '".IO::floatValue("GreigeWidth")."',
	                                   contact_pi_no       = '".IO::strValue("ContactPiNo")."',
	                                   btl_po              = '".IO::strValue("BtlPo")."',
	                                   date                = '".((IO::strValue("Date") == "") ? "0000-00-00" : IO::strValue("Date"))."',
	                                   capacity            = '".IO::intValue("Capacity")."',

	                                   warp_count          = '".IO::strValue("WarpCount")."',
	                                   spin_type           = '".IO::strValue("SpinType")."',
	                                   yarn_type           = '".IO::strValue("YarnType")."',
	                                   cotton_origin   	= '".IO::strValue("CottonOrigin")."',
	                                   warp_composition    = '".IO::strValue("WarpComposition")."',
	                                   supplier            = '".IO::strValue("Supplier")."',
	                                   weft_count          = '".IO::strValue("WeftCount")."',
	                                   spin_type_weft      = '".IO::strValue("SpinTypeWeft")."',
	                                   yarn_type_we        = '".IO::strValue("YarnTypeWe")."',
	                                   cotton_origin_we    = '".IO::strValue("CottonOriginWe")."',
	                                   weft_composition    = '".IO::strValue("WeftComposition")."',
	                                   supplier1           = '".IO::strValue("Supplier1")."',
	                                   tpi                 = '".IO::strValue("Tpi")."',
	                                   clsp                = '".IO::strValue("Clsp")."',

	                                   cover_factor        = '".IO::strValue("CoverFactor")."',
	                                   total_ends          = '".IO::strValue("TotalEnds")."',
	                                   reed_count          = '".IO::strValue("ReedCount")."',
	                                   ends_dent           = '".IO::strValue("EndsDent")."',
	                                   reed_space          = '".IO::strValue("ReedSpace")."',
	                                   cloth_width         = '".IO::strValue("ClothWidth")."',
	                                   ends                = '".IO::strValue("Ends")."',
	                                   picks               = '".IO::strValue("Picks")."',
	                                   weave               = '".IO::strValue("Weave")."',
	                                   gsm                 = '".IO::strValue("Gsm")."',
	                                   overall_composition = '".IO::strValue("OverallComposition")."',

	                                   sel_type            = '".IO::strValue("SelType")."',
	                                   normal_binding      = '".IO::strValue("NormalBinding")."',
	                                   sel_width           = '".IO::strValue("SelWidth")."',
	                                   sel_weave           = '".IO::strValue("SelWeave")."',
	                                   sel_ends_dent       = '".IO::strValue("SelEndsDent")."',
	                                   comments            = '".IO::strValue("Comments")."',

	                                   wp_rib_size         = '".IO::strValue("WpRibSize")."',
	                                   wf_rib_size         = '".IO::strValue("WfRibSize")."',
	                                   ends_rib            = '".IO::strValue("EndsRib")."',
	                                   picks_rib           = '".IO::strValue("PicksRib")."',

	                                   slub_length         = '".IO::strValue("SlubLength")."',
	                                   pause               = '".IO::strValue("Pause")."',
	                                   thickness           = '".IO::strValue("Thickness")."',

	                                   lycra_draft         = '".IO::strValue("LycraDraft")."',
	                                   lycra_yarn          = '".IO::strValue("LycraYarn")."',
	                                   lycra_fabric        = '".IO::strValue("LycraFabric")."',
	                                   boil_off_width      = '".IO::strValue("BoilOffWidth")."',
	                                   shrinkage           = '".IO::strValue("Shrinkage")."',

	                                   warp                = '".IO::strValue("Warp")."',
	                                   weft                = '".IO::strValue("Weft")."',

	                                   remarks             = '".IO::strValue("Remarks")."',

	                                   modified            = NOW( ),
	                                   modified_by         = '{$_SESSION['UserId']}'
			   WHERE style_id='$Id'");

	if ($objDb->execute($sSQL) == true)
		redirect(urldecode($Referer), "GF_SPECS_UPDATED");

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		header("Location: edit-specs.php?Id={$Id}&Referer={$Referer}");
	}


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>