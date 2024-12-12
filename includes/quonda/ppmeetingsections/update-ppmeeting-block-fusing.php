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

  $Components   = IO::getArray("Component");
  $ArticleNo    = IO::getArray("ArticleNo");
  $SupplierCode = IO::getArray("SupplierCode");

  if(!empty($Components) && !empty($ArticleNo) && !empty($SupplierCode))
  {
      foreach($Components as $key => $Component)
      {
          if($Component != "" && $ArticleNo[$key] != "" && $SupplierCode[$key] != "")
          {
                $sSQL  = ("INSERT INTO tbl_ppmeeting_block_fusing SET audit_id         = '$Id',
		                                                      component          = '".$Component."',
                                                                      article_no         = '".$ArticleNo[$key]."',
		                                                      supplier_code      = '".$SupplierCode[$key]."'");
		$bFlag = $objDb->execute($sSQL);
                
                if($bFlag == false)
                {
                    echo $sSQL;
                    break;
                }
          }
      }
      
  }
?>
