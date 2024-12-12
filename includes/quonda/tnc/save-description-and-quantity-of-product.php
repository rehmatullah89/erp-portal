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
    $Id = IO::intValue("Id");
    $rowIds = IO::getArray("RowId");
    $lotdeleteRecordIds = IO::getArray("deleteRecords");

    $bFlag = true;

    $totalSampleSize = 0;

  if(count($rowIds) > 0) {

      for($i=0; $i<count($rowIds); $i++){

          $rowId = $rowIds[$i];

          $lotCombStyles = implode(',', IO::getArray("lotCombStyles".$rowId));
          $lotCombColors = implode(',', IO::getArray("lotCombColors".$rowId));
          $lotCombSizes = implode(',', IO::getArray("lotCombSizes".$rowId));
          $lotCombPos = implode(',', IO::getArray("lotCombPos".$rowId));

          $lotSize = IO::intValue("lotSizes".$rowId);
          $lotSampleSize = IO::intValue("lotSampleSizes".$rowId);
          $lotUpdateId = IO::intValue("dbId".$rowId);

          $totalSampleSize += $lotSampleSize;

          if($lotUpdateId == '?'){

              $sSQL = "INSERT INTO `tbl_qa_lot_sizes` (`audit_id`, `styles`,`pos`, `colors`, `sizes`, `lot_size`, `sample_size`) VALUES ('$Id', '$lotCombStyles', '$lotCombPos', '$lotCombColors', '$lotCombSizes', '$lotSize', '$lotSampleSize')"; 

              $bFlag = $objDb->execute($sSQL);  

          } else {

              $sSQL = "UPDATE `tbl_qa_lot_sizes` SET `styles`= '$lotCombStyles', `pos`= '$lotCombPos', `colors`= '$lotCombColors', `sizes`= '$lotCombSizes', `lot_size`= '$lotSize', `sample_size`= '$lotSampleSize' WHERE id= '$lotUpdateId' ";

              $bFlag = $objDb->execute($sSQL);

          }

          if($bFlag == false){
            break;
          }
      }
  }
    if($bFlag == true && count($lotdeleteRecordIds) > 0)
    {

          $sLotdeleteRecordIds = implode(',', $lotdeleteRecordIds);

          $sSQL = "DELETE FROM `tbl_qa_lot_sizes` WHERE id IN(".$sLotdeleteRecordIds.") ";

          $bFlag = $objDb->execute($sSQL);
      }

    if($bFlag == true)
    {
        $sSampleSize = array_sum($lotSampleSizes);

        $sSQL = "UPDATE tbl_qa_reports SET total_gmts='$totalSampleSize' WHERE id='$Id'";
        $bFlag = $objDb->execute($sSQL);
    }      
?>