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
        
        $sPartsNumbering    = IO::strValue('parts_numbering');
        $sFaceSide          = IO::strValue('face_side');
        $sSpecialNumbering  = IO::strValue('special_numbering');
        $sReverseSide       = IO::strValue('reverse_side');
        $sSpecialCutMarks   = IO::strValue('special_cut_marks');
        $sSpecialNotches    = IO::strValue('spcial_notches'); 
  
        if(getDbValue("COUNT(1)", "tbl_ppmeeting_numbering", "audit_id='$Id'") > 0)
        {
                    $sSQL  = ("UPDATE tbl_ppmeeting_numbering SET     parts_numbering         = '".($sPartsNumbering == 'Y'?'Y':'N')."',
                                                                            face_side         = '".($sFaceSide == 'Y'?'Y':'N')."',
                                                                            special_numbering = '".($sSpecialNumbering == 'Y'?'Y':'N')."',
                                                                            reverse_side      = '".($sReverseSide == 'Y'?'Y':'N')."',
                                                                            special_cut_marks = '".($sSpecialCutMarks == 'Y'?'Y':'N')."',
                                                                            spcial_notches    = '".($sSpecialNotches == 'Y'?'Y':'N')."'                                                                                                                                                            
                            WHERE audit_id='$Id'");

        }else
        {
                      $sSQL  = ("INSERT INTO tbl_ppmeeting_numbering SET audit_id         = '$Id',
                                                                            parts_numbering         = '".($sPartsNumbering == 'Y'?'Y':'N')."',
                                                                            face_side         = '".($sFaceSide == 'Y'?'Y':'N')."',
                                                                            special_numbering = '".($sSpecialNumbering == 'Y'?'Y':'N')."',
                                                                            reverse_side      = '".($sReverseSide == 'Y'?'Y':'N')."',
                                                                            special_cut_marks = '".($sSpecialCutMarks == 'Y'?'Y':'N')."',
                                                                            spcial_notches    = '".($sSpecialNotches == 'Y'?'Y':'N')."'");
        }
      
        $bFlag = $objDb->execute($sSQL);
      
        if($bFlag == false)
            exit($sSql);
  
?>
