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

    $sFacricColor     = IO::getArray("FabColor");
    $sZipperColors    = IO::getArray("ZipperColor");
    $sZipperSize      = IO::getArray("ZipperSize");
    $sZipperMaker     = IO::getArray("ZipperMaker");
    $sZipperLength    = IO::getArray("ZipperLength");

  if(!empty($sFacricColor) && !empty($sZipperColors))
  {
      foreach($sZipperColors as $key => $sZipperColor)
      {
          if($sZipperColor != "" && $sFacricColor[$key] != "")
          {
                $sSQL  = ("INSERT INTO tbl_ppmeeting_zipper_application SET audit_id  = '$Id',
		                                                      fabric_color    = '".$sFacricColor[$key]."',
                                                                      zipper_color    = '".$sZipperColor."',
                                                                      zipper_size     = '".$sZipperSize[$key]."',
                                                                      zipper_maker    = '".$sZipperMaker[$key]."',
                                                                      zipper_length   = '".$sZipperLength[$key]."'");
		$bFlag = $objDb->execute($sSQL);
                
                if($bFlag == false)
                {
                    break;
                }
          }
      }
      
  }
?>
