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

    $AirwayBill      = IO::strValue("AirwayBill");
    $BillNumber      = IO::strValue("BillNumber");
    $AirwayComments  = IO::strValue("AirwayComments");
  
    $objDb->execute("BEGIN");

    if(getDbValue("COUNT(1)", "tbl_qa_report_details", "audit_id='$Id'") > 0)
    {
        $sSQL  = ("UPDATE tbl_qa_report_details SET  airway_bill_applicable   = '".$AirwayBill."',
                                                    airway_bill_number        = '".$BillNumber."',
                                                    airway_bill_comments      = '".$AirwayComments."'
                                                    WHERE audit_id='$Id'");
    }
    else
    {
        $sSQL  = ("INSERT INTO tbl_qa_report_details SET audit_id             = '$Id',
                                                    airway_bill_applicable    = '".$AirwayBill."',
                                                    airway_bill_number        = '".$BillNumber."',
                                                    airway_bill_comments      = '".$AirwayComments."'");
    }

    $bFlag = $objDb->execute($sSQL);
    
?>
