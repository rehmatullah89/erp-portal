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
    $Id = IO::strValue("Id");
    $BundleType = IO::strValue("BundleType");
    $countryBlocks = IO::getArray("countryBlocks");
    $countries = IO::getArray("country");
    $lotSizes = IO::getArray("lotSizes");
    $lotSampleSizes = IO::getArray("lotSampleSizes");
    $lotUpdateIds = IO::getArray("dbId");
    $lotdeleteRecordIds = IO::getArray("deleteRecords");

  if(count($countryBlocks) > 0) {

      $bFlag = $objDb->execute("BEGIN");

      for($i=0; $i<count($countryBlocks); $i++){

          $lotCombStyles = implode(',', IO::getArray("lotCombStyles".$i));
          $lotCombColors = implode(',', IO::getArray("lotCombColors".$i));
          $lotCombSizes = implode(',', IO::getArray("lotCombSizes".$i));
          $countryBlock = $countryBlocks[$i];
          $country      = $countries[$i];
          $lotSize = $lotSizes[$i];
          $lotSampleSize = $lotSampleSizes[$i];
          $lotUpdateId = $lotUpdateIds[$i];

          if($lotUpdateId == '?'){

              $sSQL = "INSERT INTO `tbl_qa_lot_sizes` (`audit_id`, `country_id`, `cb_id`, `styles`, `colors`, `sizes`, `lot_size`, `sample_size`) VALUES ('$Id', '$country', '$countryBlock', '$lotCombStyles', '$lotCombColors', '$lotCombSizes', '$lotSize', '$lotSampleSize')"; 

              $bFlag = $objDb->execute($sSQL);  

          } else {

              $sSQL = "UPDATE `tbl_qa_lot_sizes` SET `cb_id`= '$countryBlock', `country_id`= '$country', `styles`= '$lotCombStyles', `colors`= '$lotCombColors', `sizes`= '$lotCombSizes', `lot_size`= '$lotSize', `sample_size`= '$lotSampleSize' WHERE id= '$lotUpdateId' ";

              $bFlag = $objDb->execute($sSQL);

          }


      }
  }

    if($bFlag == true)
    {
        $sSampleSize = array_sum($lotSampleSizes);

        $sSQL = "UPDATE tbl_qa_reports SET total_gmts='$sSampleSize',bundle='$BundleType'  WHERE id='$Id'";
        $bFlag = $objDb->execute($sSQL);
    }
    
    if(count($lotdeleteRecordIds) > 0)
    {

          $sLotdeleteRecordIds = implode(',', $lotdeleteRecordIds);

          $sSQL = "DELETE FROM `tbl_qa_lot_sizes` WHERE id IN(".$sLotdeleteRecordIds.") ";

          $bFlag = $objDb->execute($sSQL);
      }

?>
