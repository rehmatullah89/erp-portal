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
        //section#A
        $sFabric                = implode('|-|', IO::getArray('fabric'));
        $sInspecLevel           = implode('|-|', IO::getArray('inspection_level'));
        $sResult                = implode('|-|', IO::getArray('result'));
        $sFabricPoints          = implode('|-|', IO::getArray('fabric_points'));
        //section#B
        $sWeavingDefect         = IO::strValue('weaving_defect');
        $sMiskPack              = IO::strValue('misc_pack');
        $sShadingEnd            = IO::strValue('shading_end');
        $sEndOutrun             = IO::strValue('end_outrun');
        $sSlubs                 = IO::strValue('slubs');
        $sShadingBack           = IO::strValue('shading_back');
        $sStainMarks            = IO::strValue('stain_marks');     
        $sdoublePick            = IO::strValue('double_pick');
        $sShadingSide           = IO::strValue('shading_side');     
        $sShadeBars             = IO::strValue('shade_bars');
        $sKnots                 = IO::strValue('knots');
        $sBowing                = IO::strValue('bowing');
        $sContamination         = IO::strValue('contamination');          
        $sSpot                  = IO::strValue('spot');
        $sSnaging               = IO::strValue('snaging');           
        $sThinFabric            = IO::strValue('thin_fabric');
        $sHoles                 = IO::strValue('holes');
        $sGrainPrint            = IO::strValue('grain_print');//
        $sRejectedQtyDetails    = IO::strValue('rejected_qty_details');    
        $sPreCalculatedLoss     = IO::strValue('pre_calculated_loss');       
        $sBlackTest             = IO::strValue('color_black_test');
        $sWearerTest            = IO::strValue('wearer_test');
        $sSpecialTest           = IO::strValue('special_test');
        $sAnyUpdate             = IO::strValue('any_update');
        //section#C
        $RejectedPoints         = implode('|-|', IO::getArray('rejectedPoint'));
        $sInspectionYardage     = IO::strValue('inspection_yardage');
        $sYarnPlaces            = IO::strValue('yarn_places');
        $sComments              = IO::strValue('comments');
        //section#D
        $sNFabricResult         = IO::strValue('nfabric_result');       
        $sNGarmentResult        = IO::strValue('ngarment_result');
        $sIFabricResult         = IO::strValue('ifabric_result');
        $sIGarmentResult        = IO::strValue('igarment_result');       
        $sShrinkSteamShellWarp  = IO::strValue('shrink_steam_shell_warp');
        $sShrinkSteamShellWeft  = IO::strValue('shrink_steam_shell_weft');
        $sShrinkPressShellWarp  = IO::strValue('shrink_press_shell_warp');
        $sShrinkPressShellWeft  = IO::strValue('shrink_press_shell_weft');
        $sShrinkSteamLaceWarp   = IO::strValue('shrink_steam_lace_warp');
        $sShrinkSteamLaceWeft   = IO::strValue('shrink_steam_lace_weft');
        $sShrinkPressLaceWarp   = IO::strValue('shrink_press_lace_warp');
        $sShrinkPressLaceWeft   = IO::strValue('shrink_press_lace_weft');
        $sRequiredTest          = IO::strValue('required_test');
        $sCompleteTest          = IO::strValue('complete_test');
        $sInCompleteTest        = IO::strValue('incomplete_test');
        //section#E
        $sBlockFusing           = IO::strValue('block_fusing');
        $sBTmLeveling           = IO::strValue('btm_leveling');
        $sTailorsChalks         = IO::strValue('tailors_chalks');
        $sPenStains             = IO::strValue('pen_stains');
        $sStickerMarks          = IO::strValue('sticker_marks');
        $sPassStickerMarks      = IO::strValue('pass_sticker_marks');
  
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_inspection_details", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_inspection_details SET      fabric                  = '".$sFabric."',
                                                                            inspection_level        = '".$sInspecLevel."',
                                                                            result                  = '".$sResult."',                                                                                  
                                                                            fabric_points           = '".$sFabricPoints."',
                                                                            weaving_defect          = '".$sWeavingDefect."',
                                                                            misc_pack               = '".$sMiskPack."',
                                                                            shading_end             = '".$sShadingEnd."',
                                                                            end_outrun              = '".$sEndOutrun."',
                                                                            slubs                   = '".$sSlubs."',
                                                                            shading_back            = '".$sShadingBack."',
                                                                            stain_marks             = '".$sStainMarks."',
                                                                            double_pick             = '".$sdoublePick."',
                                                                            shading_side            = '".$sShadingSide."',
                                                                            shade_bars              = '".$sShadeBars."',
                                                                            knots                   = '".$sKnots."',
                                                                            bowing                  = '".$sBowing."',
                                                                            contamination           = '".$sContamination."',
                                                                            spot                    = '".$sSpot."',
                                                                            snaging                 = '".$sSnaging."',
                                                                            thin_fabric             = '".$sThinFabric."',
                                                                            holes                   = '".$sHoles."',
                                                                            grain_print             = '".$sGrainPrint."',
                                                                            rejected_qty_details    = '".$sRejectedQtyDetails."',
                                                                            pre_calculated_loss     = '".$sPreCalculatedLoss."',
                                                                            color_black_test        = '".($sBlackTest == 'Y'?'Y':'N')."',                                                                                  
                                                                            wearer_test             = '".($sWearerTest == 'Y'?'Y':'N')."',
                                                                            special_test            = '".($sSpecialTest == 'Y'?'Y':'N')."',
                                                                            any_update              = '".$sAnyUpdate."',
                                                                            rejectedPoint           = '".$RejectedPoints."',
                                                                            inspection_yardage      = '".$sInspectionYardage."',                                                                                  
                                                                            yarn_places             = '".$sYarnPlaces."',
                                                                            comments                = '".$sComments."',    
                                                                            nfabric_result          = '".$sNFabricResult."',
                                                                            ngarment_result         = '".$sNGarmentResult."',
                                                                            ifabric_result          = '".$sIFabricResult."',
                                                                            igarment_result         = '".$sIGarmentResult."',
                                                                            shrink_steam_shell_warp = '".$sShrinkSteamShellWarp."',
                                                                            shrink_steam_shell_weft = '".$sShrinkSteamShellWeft."',
                                                                            shrink_press_shell_warp = '".$sShrinkPressShellWarp."',
                                                                            shrink_press_shell_weft = '".$sShrinkPressShellWeft."',
                                                                            shrink_steam_lace_warp  = '".$sShrinkSteamLaceWarp."',
                                                                            shrink_steam_lace_weft  = '".$sShrinkSteamLaceWeft."',
                                                                            shrink_press_lace_warp  = '".$sShrinkPressLaceWarp."',
                                                                            shrink_press_lace_weft  = '".$sShrinkPressLaceWeft."',
                                                                            required_test           = '".$sRequiredTest."',
                                                                            complete_test           = '".$sCompleteTest."',
                                                                            incomplete_test         = '".$sInCompleteTest."',
                                                                            block_fusing            = '".($sBlockFusing == 'Y'?'Y':'N')."',
                                                                            btm_leveling            = '".($sBTmLeveling == 'Y'?'Y':'N')."',
                                                                            tailors_chalks          = '".($sTailorsChalks == 'Y'?'Y':'N')."',
                                                                            pen_stains              = '".($sPenStains == 'Y'?'Y':'N')."',
                                                                            sticker_marks           = '".($sStickerMarks == 'Y'?'Y':'N')."',
                                                                            pass_sticker_marks      = '".($sPassStickerMarks == 'Y'?'Y':'N')."'
                                                                                
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_inspection_details SET audit_id              = '$Id',
                                                                            fabric                  = '".$sFabric."',
                                                                            inspection_level        = '".$sInspecLevel."',
                                                                            result                  = '".$sResult."',                                                                                  
                                                                            fabric_points           = '".$sFabricPoints."',
                                                                            weaving_defect          = '".$sWeavingDefect."',
                                                                            misc_pack               = '".$sMiskPack."',
                                                                            shading_end             = '".$sShadingEnd."',
                                                                            end_outrun              = '".$sEndOutrun."',
                                                                            slubs                   = '".$sSlubs."',
                                                                            shading_back            = '".$sShadingBack."',
                                                                            stain_marks             = '".$sStainMarks."',
                                                                            double_pick             = '".$sdoublePick."',
                                                                            shading_side            = '".$sShadingSide."',
                                                                            shade_bars              = '".$sShadeBars."',
                                                                            knots                   = '".$sKnots."',
                                                                            bowing                  = '".$sBowing."',
                                                                            contamination           = '".$sContamination."',
                                                                            spot                    = '".$sSpot."',
                                                                            snaging                 = '".$sSnaging."',
                                                                            thin_fabric             = '".$sThinFabric."',
                                                                            holes                   = '".$sHoles."',
                                                                            grain_print             = '".$sGrainPrint."',
                                                                            rejected_qty_details    = '".$sRejectedQtyDetails."',
                                                                            pre_calculated_loss     = '".$sPreCalculatedLoss."',
                                                                            color_black_test        = '".($sBlackTest == 'Y'?'Y':'N')."',                                                                                  
                                                                            wearer_test             = '".($sWearerTest == 'Y'?'Y':'N')."',
                                                                            special_test            = '".($sSpecialTest == 'Y'?'Y':'N')."',
                                                                            any_update              = '".$sAnyUpdate."',
                                                                            rejectedPoint           = '".$RejectedPoints."',
                                                                            inspection_yardage      = '".$sInspectionYardage."',                                                                                  
                                                                            yarn_places             = '".$sYarnPlaces."',
                                                                            comments                = '".$sComments."',    
                                                                            nfabric_result          = '".$sNFabricResult."',
                                                                            ngarment_result         = '".$sNGarmentResult."',
                                                                            ifabric_result          = '".$sIFabricResult."',
                                                                            igarment_result         = '".$sIGarmentResult."',
                                                                            shrink_steam_shell_warp = '".$sShrinkSteamShellWarp."',
                                                                            shrink_steam_shell_weft = '".$sShrinkSteamShellWeft."',
                                                                            shrink_press_shell_warp = '".$sShrinkPressShellWarp."',
                                                                            shrink_press_shell_weft = '".$sShrinkPressShellWeft."',
                                                                            shrink_steam_lace_warp  = '".$sShrinkSteamLaceWarp."',
                                                                            shrink_steam_lace_weft  = '".$sShrinkSteamLaceWeft."',
                                                                            shrink_press_lace_warp  = '".$sShrinkPressLaceWarp."',
                                                                            shrink_press_lace_weft  = '".$sShrinkPressLaceWeft."',
                                                                            required_test           = '".$sRequiredTest."',
                                                                            complete_test           = '".$sCompleteTest."',
                                                                            incomplete_test         = '".$sInCompleteTest."',
                                                                            block_fusing            = '".($sBlockFusing == 'Y'?'Y':'N')."',
                                                                            btm_leveling            = '".($sBTmLeveling == 'Y'?'Y':'N')."',
                                                                            tailors_chalks          = '".($sTailorsChalks == 'Y'?'Y':'N')."',
                                                                            pen_stains              = '".($sPenStains == 'Y'?'Y':'N')."',
                                                                            sticker_marks           = '".($sStickerMarks == 'Y'?'Y':'N')."',
                                                                            pass_sticker_marks      = '".($sPassStickerMarks == 'Y'?'Y':'N')."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
        
        if($bFlag == false)
            exit($sSql);
  
?>
