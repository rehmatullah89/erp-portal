<?
	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$count = 1;
	
			$sSQL = ("SELECT id, style, brand_id, sub_brand_id, season_id, sub_season_id FROM `tbl_styles` WHERE brand_id = 434 AND measurement_points = ''");			
			$objDb->query($sSQL);
			
			$iCount = $objDb->getCount( );
			
			for($i=0; $i<=$iCount; $i++)
			{
				$iStyle 	= $objDb->getField($i, "id"); 
				$sStyle 	= $objDb->getField($i, "style"); 
				$iSubBrand  = $objDb->getField($i, "sub_brand_id"); 
				$iSeason 	= $objDb->getField($i, "season_id"); 
				
				
				$sSQL2 = ("SELECT measurement_points, sizes FROM tbl_styles WHERE style LIKE '$sStyle' AND season_id='$iSeason'");
				$objDb2->query($sSQL2);
			
				$iCount2 = $objDb2->getCount( );
				
				if($iCount2 > 0)
				{					
					$sMeasurementPts = $objDb2->getField(0, "measurement_points");
					$sSizes          = $objDb2->getField(0, "sizes");
					
					$sSQL3 = ("UPDATE tbl_styles SET measurement_points='$sMeasurementPts', sizes='$sSizes' WHERE id = '$iStyle'");
					$objDb3->query($sSQL3);
					
					$count ++;
				}
				
			}

	
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );
	
	echo "DONE:{$count}";exit;

	@ob_end_flush( );
?>