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

    $Result         = IO::strValue("Result");
    $Comments       = IO::strValue("Comments");
    $Statements     = IO::getArray("Statements");
    $sStatementList = getList("tbl_statements", "id", "statement", "FIND_IN_SET('1', sections)");
    $sAuditDate     = getDbValue("audit_date", "tbl_qa_reports", "id='$Id'");
    
    $sAuditCode     = "S".str_pad($Id, 4, '0', STR_PAD_LEFT);
    
    
    $bFlag = $objDb->execute("BEGIN");

    $sAllSerials = implode(",",getList("tbl_qa_product_conformity", "serial", "serial", "audit_id='$Id'"));
    
    $sSQL  = "DELETE FROM tbl_qa_product_conformity WHERE audit_id='$Id'";
    $bFlag = $objDb->execute($sSQL);

    if($bFlag ==  true)
    {
          $Counter = 1;
          foreach($Statements as $key => $Statement)
          {
              if($Statement != "")
              {
                    $sStatement = $sStatementList[$Statement];

                    $sSQL  = ("INSERT INTO tbl_qa_product_conformity SET audit_id       = '$Id',
                                                                          serial        = '".$Counter."',
                                                                          observation   = '".$sStatement."'");
                    $bFlag = $objDb->execute($sSQL);
 
                    if($bFlag == false)
                        break;
                   
                    $Counter ++;
                    
              }
          }
    }

    if($bFlag == true && $Id > 0)
    {
        if(getDbValue("COUNT(1)", "tbl_qa_report_details", "audit_id='$Id'") > 0)
        {
          $sSQL  = ("UPDATE tbl_qa_report_details SET  product_conformity_result    = '".$Result."',
                                                   product_conformity_comments  = '".$Comments."'
                                                   WHERE audit_id='$Id'");
        }
        else
        {
            $sSQL  = ("INSERT INTO tbl_qa_report_details SET audit_id         = '$Id',
                                              product_conformity_result   = '".$Result."',
                                              product_conformity_comments = '".$Comments."'");
        }
          $bFlag = $objDb->execute($sSQL);
    }
    
  
?>