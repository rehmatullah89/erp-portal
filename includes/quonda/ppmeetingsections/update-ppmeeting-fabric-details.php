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
        $MillInfo         = IO::strValue("millInfo");
        $FabricCode       = IO::strValue("fabricCode");
        $OneWay           = IO::strValue("oneWay");
        $YarnDyed         = IO::strValue("yarnDyed");
        $WovenType        = IO::strValue("wovenType");
        $TwoWay           = IO::strValue("twoWay");
        $Printed          = IO::strValue("printed");
        $KnitType         = IO::strValue("knitType");
        $Solid            = IO::strValue("solid");
        $PieceDyed        = IO::strValue("pieceDyed");
        $PileFabric       = IO::strValue("pileFabric");
        $CheckFab         = IO::strValue("checkFab");
        $StripeYarn       = IO::strValue("stripeYarn");
        $RelaxedSpread    = IO::strValue("relaxedSpread");
        $Interlining      = IO::strValue("interlining");
        $Engineered       = IO::strValue("engineered");
        $GsmApproved      = IO::strValue("gsmApproved");
  
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_fabric_details", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_fabric_details SET      one_way         = '".($OneWay == 'Y'?'Y':'N')."',
                                                                            yarn_dyed       = '".($YarnDyed == 'Y'?'Y':'N')."',
                                                                            woven_type      = '".($WovenType == 'Y'?'Y':'N')."',                                                                                  
                                                                            two_way         = '".($TwoWay == 'Y'?'Y':'N')."',
                                                                            printed         = '".($Printed == 'Y'?'Y':'N')."',
                                                                            knit_type       = '".($KnitType == 'Y'?'Y':'N')."',
                                                                            solid           = '".($Solid == 'Y'?'Y':'N')."',
                                                                            piece_dyed      = '".($PieceDyed == 'Y'?'Y':'N')."',                                                                                  
                                                                            pile_fabric     = '".($PileFabric == 'Y'?'Y':'N')."',
                                                                            check_fabric    = '".($CheckFab == 'Y'?'Y':'N')."',
                                                                            stripe_yarn_dyed = '".($StripeYarn == 'Y'?'Y':'N')."',
                                                                            relaxed_spread   = '".($RelaxedSpread == 'Y'?'Y':'N')."',
                                                                            interlining     = '".($Interlining == 'Y'?'Y':'N')."',                                                                                  
                                                                            engineered      = '".($Engineered == 'Y'?'Y':'N')."',
                                                                            gsm_approved    = '".($GsmApproved == 'Y'?'Y':'N')."',    
                                                                            mill_info       = '".$MillInfo."',    
                                                                            fabric_code     = '".$FabricCode."'
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_fabric_details SET audit_id         = '$Id',
                                                                            one_way         = '".($OneWay == 'Y'?'Y':'N')."',
                                                                            yarn_dyed       = '".($YarnDyed == 'Y'?'Y':'N')."',
                                                                            woven_type      = '".($WovenType == 'Y'?'Y':'N')."',                                                                                  
                                                                            two_way         = '".($TwoWay == 'Y'?'Y':'N')."',
                                                                            printed         = '".($Printed == 'Y'?'Y':'N')."',
                                                                            knit_type       = '".($KnitType == 'Y'?'Y':'N')."',
                                                                            solid           = '".($Solid == 'Y'?'Y':'N')."',
                                                                            piece_dyed      = '".($PieceDyed == 'Y'?'Y':'N')."',                                                                                  
                                                                            pile_fabric     = '".($PileFabric == 'Y'?'Y':'N')."',
                                                                            check_fabric    = '".($CheckFab == 'Y'?'Y':'N')."',
                                                                            stripe_yarn_dyed = '".($StripeYarn == 'Y'?'Y':'N')."',
                                                                            relaxed_spread   = '".($RelaxedSpread == 'Y'?'Y':'N')."',
                                                                            interlining     = '".($Interlining == 'Y'?'Y':'N')."',                                                                                  
                                                                            engineered      = '".($Engineered == 'Y'?'Y':'N')."',
                                                                            gsm_approved    = '".($GsmApproved == 'Y'?'Y':'N')."',    
                                                                            mill_info       = '".$MillInfo."',    
                                                                            fabric_code     = '".$FabricCode."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
        
        if($bFlag == false)
            exit($sSql);
  
?>
