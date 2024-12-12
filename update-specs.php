<?
	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	
			$sSQL = ("SELECT id from tbl_styles where brand_id='434'");			
			$objDb->query($sSQL);
			
			$iCount = $objDb->getCount( );
			
			for($i=0; $i<=$iCount; $i++)
			{
				$iStyle = $objDb->getField($i, "id"); 
				
				$sSQL2 = ("SELECT DISTINCT point_id FROM tbl_style_specs WHERE style_id ='$iStyle'");
				$objDb2->query($sSQL2);
			
				$iCount2 = $objDb2->getCount( );
				
				$sPoints = array();
				for($j=0; $j<=$iCount2; $j++)
					$sPoints[] = $objDb2->getField($j, "point_id");
				
				$sPoints = implode(",", $sPoints);
				$sPoints = rtrim($sPoints, ",");
				
				
				$sSQL = ("UPDATE tbl_styles SET measurement_points='$sPoints' WHERE id = '$iStyle'");
				$objDb3->query($sSQL);
			}

	
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>