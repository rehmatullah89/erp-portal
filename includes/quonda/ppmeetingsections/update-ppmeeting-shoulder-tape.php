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

  $ArticleNos   = IO::getArray("ArticleNo");
  $Color        = IO::getArray("Color");
  $Type         = IO::getArray("Type");
  $Conusumption = IO::getArray("Conusumption");

  if(!empty($ArticleNos) && !empty($Color))
  {
      foreach($ArticleNos as $key => $ArticleNo)
      {
          if($ArticleNo != "" && $Color[$key] != "")
          {
                $sSQL  = ("INSERT INTO tbl_ppmeeting_shoulder_tape SET audit_id         = '$Id',
		                                                      article_no          = '".$ArticleNo."',
                                                                      color          = '".$Color[$key]."',
                                                                      article_type          = '".$Type[$key]."',
		                                                      consumption   = '".$Conusumption[$key]."'");
		$bFlag = $objDb->execute($sSQL);
                
                if($bFlag == false)
                {
                    break;
                }
          }
      }
      
  }
?>
