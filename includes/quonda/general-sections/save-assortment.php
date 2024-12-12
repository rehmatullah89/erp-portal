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

    $Result           = IO::strValue("Result");
    $Comments         = IO::strValue("Comments");
    $WrongAssorted    = IO::intValue("WrongAssortedCartons");
    $AllCartons       = IO::intValue("TotalCartons");

    if(!empty($Result))
    {
        $bFlag = $objDb->execute("BEGIN");
        
        $iCountCartons = (int)getDbValue("COUNT(1)", "tbl_qa_assortment", "audit_id='$Id'");

        if($iCountCartons == 0)
        {
             $sSQL  = ("INSERT INTO tbl_qa_assortment SET audit_id    = '$Id',
                                                  total_cartons_tested = '$AllCartons',  
                                                  wrong_assorted_cartons = '".$WrongAssorted."',
                                                  result                 = '".$Result."',
                                                  comments               = '".$Comments."'");        
        }
        else
        {
             $sSQL  = ("UPDATE tbl_qa_assortment SET wrong_assorted_cartons = '".$WrongAssorted."',
                                                    total_cartons_tested = '$AllCartons',
                                                    result                 = '".$Result."',
                                                    comments               = '".$Comments."'
                                                WHERE audit_id = '$Id'");
        }

        $bFlag = $objDb->execute($sSQL);

    }
?>
