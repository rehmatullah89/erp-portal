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
        $sEdgeCutting       = IO::strValue('edge_cutting');
        $sEdgeTearing       = IO::strValue('edge_tearing');
        $sRelaxation        = IO::strValue('relaxation');
        $sTensionFree       = IO::strValue('tension_free');
        $sLotNoFollowed     = IO::strValue('lot_no_followed');
        $sDefectsRemove     = IO::strValue('defects_remove');
        $sLayingPins        = IO::strValue('laying_pins');
        $sRelayPins         = IO::strValue('relay_pins');
        $sBorderPrintPins   = IO::strValue('border_print_pins');
        $sFaceUp            = IO::strValue('face_up');
        $sFaceToFace        = IO::strValue('face_to_face');
        $sNapUp             = IO::strValue('nap_up');
        $sNapDown           = IO::strValue('nap_down');
        $sTwillDirection    = IO::strValue('twill_direction');
        $sMaxLayHeight      = IO::strValue('max_lay_height');
        $sMarkerNumber      = IO::strValue('marker_number');
        $sStandard          = IO::strValue('standard');
        $sMaxPly            = IO::strValue('maximum_ply');
        $sBundleSize        = IO::strValue('bundle_size');
        $sManualSpreading   = IO::strValue('manual_spreading');
        $sRequireBundleSize = IO::strValue('require_bundle_size');
        $sMachineSpreading  = IO::strValue('machine_spreading');   
  
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_laying_spreading", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_laying_spreading SET    edge_cutting        = '".($sEdgeCutting == 'Y'?'Y':'N')."',
                                                                            edge_tearing        = '".($sEdgeTearing == 'Y'?'Y':'N')."',
                                                                            relaxation          = '".($sRelaxation == 'Y'?'Y':'N')."',
                                                                            tension_free        = '".($sTensionFree == 'Y'?'Y':'N')."',
                                                                            lot_no_followed     = '".($sLotNoFollowed == 'Y'?'Y':'N')."',
                                                                            defects_remove      = '".($sDefectsRemove == 'Y'?'Y':'N')."',                                                                                  
                                                                            laying_pins         = '".($sLayingPins == 'Y'?'Y':'N')."',
                                                                            relay_pins          = '".($sRelayPins == 'Y'?'Y':'N')."',
                                                                            border_print_pins   = '".($sBorderPrintPins == 'Y'?'Y':'N')."',
                                                                            face_up             = '".($sFaceUp == 'Y'?'Y':'N')."',
                                                                            face_to_face        = '".($sFaceToFace == 'Y'?'Y':'N')."',
                                                                            nap_up              = '".($sNapUp == 'Y'?'Y':'N')."',
                                                                            nap_down            = '".($sNapDown == 'Y'?'Y':'N')."',                                                                                  
                                                                            twill_direction     = '".($sTwillDirection == 'Y'?'Y':'N')."',    
                                                                            max_lay_height      = '".($sMaxLayHeight == 'Y'?'Y':'N')."',    
                                                                            marker_number       = '".($sMarkerNumber == 'Y'?'Y':'N')."',    
                                                                            standard            = '".($sStandard == 'Y'?'Y':'N')."',        
                                                                            maximum_ply         = '".($sMaxPly == 'Y'?'Y':'N')."',    
                                                                            bundle_size         = '".($sBundleSize == 'Y'?'Y':'N')."',    
                                                                            manual_spreading    = '".($sManualSpreading == 'Y'?'Y':'N')."',    
                                                                            require_bundle_size = '".($sRequireBundleSize == 'Y'?'Y':'N')."',            
                                                                            machine_spreading   = '".($sMachineSpreading == 'Y'?'Y':'N')."'                
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_laying_spreading SET audit_id         = '$Id',
                                                                           edge_cutting        = '".($sEdgeCutting == 'Y'?'Y':'N')."',
                                                                            edge_tearing        = '".($sEdgeTearing == 'Y'?'Y':'N')."',
                                                                            relaxation          = '".($sRelaxation == 'Y'?'Y':'N')."',
                                                                            tension_free        = '".($sTensionFree == 'Y'?'Y':'N')."',
                                                                            lot_no_followed     = '".($sLotNoFollowed == 'Y'?'Y':'N')."',
                                                                            defects_remove      = '".($sDefectsRemove == 'Y'?'Y':'N')."',                                                                                  
                                                                            laying_pins         = '".($sLayingPins == 'Y'?'Y':'N')."',
                                                                            relay_pins          = '".($sRelayPins == 'Y'?'Y':'N')."',
                                                                            border_print_pins   = '".($sBorderPrintPins == 'Y'?'Y':'N')."',
                                                                            face_up             = '".($sFaceUp == 'Y'?'Y':'N')."',
                                                                            face_to_face        = '".($sFaceToFace == 'Y'?'Y':'N')."',
                                                                            nap_up              = '".($sNapUp == 'Y'?'Y':'N')."',
                                                                            nap_down            = '".($sNapDown == 'Y'?'Y':'N')."',                                                                                  
                                                                            twill_direction     = '".($sTwillDirection == 'Y'?'Y':'N')."',    
                                                                            max_lay_height      = '".($sMaxLayHeight == 'Y'?'Y':'N')."',    
                                                                            marker_number       = '".($sMarkerNumber == 'Y'?'Y':'N')."',    
                                                                            standard            = '".($sStandard == 'Y'?'Y':'N')."',        
                                                                            maximum_ply         = '".($sMaxPly == 'Y'?'Y':'N')."',    
                                                                            bundle_size         = '".($sBundleSize == 'Y'?'Y':'N')."',    
                                                                            manual_spreading    = '".($sManualSpreading == 'Y'?'Y':'N')."',    
                                                                            require_bundle_size = '".($sRequireBundleSize == 'Y'?'Y':'N')."',            
                                                                            machine_spreading   = '".($sMachineSpreading == 'Y'?'Y':'N')."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
      
        if($bFlag == false)
            exit($sSql);
  
?>
