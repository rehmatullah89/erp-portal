<?
	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

			$sArr = array();
			$sArr2 = array();
			
			$sSQL = ("SELECT DISTINCT style_id as _iStyle FROM `tbl_style_specs` WHERE point_id IN ( select id from tbl_measurement_points WHERE brand_id IN (436,527,528,529,551)) Order By style_id");			
			$objDb->query($sSQL);
			
			$iCount = $objDb->getCount( );
			
			for($i=0; $i<=$iCount; $i++)
			{
				$iStyle    = $objDb->getField($i, "_iStyle"); 
				
				$sSQL2 = ("select category_id, count(1) as _Total from tbl_measurement_points WHERE brand_id IN (436,527,528,529,551) AND id IN (SELECT point_id from tbl_style_specs WHERE style_id = '$iStyle') Group By category_id");

				$objDb2->query($sSQL2);
			
				$iCount2 = $objDb2->getCount( );
				
				if($iCount2 > 1)
				{
					$iMaxCat   = "";
					$iMaxValue = 0;
					$iSecondMax = 0;

					for($j=0; $j<$iCount2; $j++)
					{
						$iCategory = $objDb2->getField($j, "category_id"); 
						$iTotal    = $objDb2->getField($j, "_Total"); 
						
						$sArr[$iStyle] = array('cat'=>$iCategory,'total'=>$iTotal);
						/*if($iTotal > $iMaxValue)
						{
							$iMaxValue = $iTotal;
							$iMaxCat   = $iCategory;
						}
						else if($iTotal == $iMaxValue && $iMaxCat == 261 && in_array($iCategory, array(218,219)))
							$iMaxCat = $iCategory;
						
						if($iCategory != 261)
							$iSecondMax = $iCategory;
						
						$sArr[$iStyle] = $iMaxCat;	*/
					}
					
/*					if($iMaxCat == 261)
						$sArr[$iStyle] = $iSecondMax;
					
					$iAllCategory = $sArr[$iStyle];
					
					
					$sSQL3 = ("UPDATE tbl_measurement_points SET category_id='$iAllCategory' WHERE brand_id IN (436,527,528,529,551) AND id IN (SELECT point_id from tbl_style_specs Where style_id = '$iStyle')");
					
					$objDb3->query($sSQL3);*/
			
				}
			}

			/*echo implode(",", $sArr2);*/
			echo "Total:".count($sArr)."<br/><pre>";
			print_r($sArr);
			echo "Done";exit;
	
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>