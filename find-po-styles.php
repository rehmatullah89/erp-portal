<?
	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


			$sArr = array();
			$sSQL = ("SELECT id, category, order_no, styles  FROM tbl_po WHERE brand_id IN (436,527,528,529,551) AND category='TOPS'");			
			$objDb->query($sSQL);
			
			$iCount = $objDb->getCount( );
			
			for($i=0; $i<=$iCount; $i++)
			{
				$iId       = $objDb->getField($i, "id"); 
				$sCategory = $objDb->getField($i, "category"); 
				$iOrderNo  = $objDb->getField($i, "order_no"); 
				$iStyle    = $objDb->getField($i, "styles"); 
				
				$sSQL2 = ("SELECT id, category, order_no, styles  FROM tbl_po WHERE brand_id IN (436,527,528,529,551) AND category != 'TOPS' AND FIND_IN_SET($iStyle, styles)");
				$objDb2->query($sSQL2);
			
				$iCount2 = $objDb2->getCount( );
				
				if($iCount2 > 0)
				{
					for($j=0; $j<=$iCount2; $j++)
					{
						$iId2       = $objDb2->getField($j, "id"); 
						$sCategory2 = $objDb2->getField($j, "category"); 
						$iOrderNo2  = $objDb2->getField($j, "order_no"); 
						$iStyle2    = $objDb2->getField($j, "styles"); 
						
						if($iOrderNo2 != "")
							$sArr[$iStyle] = array('order1'=>$iOrderNo, 'order2'=>$iOrderNo2, 'cat1'=>$sCategory, 'cat2'=>$sCategory2, 'styles'=>$iStyle);
						
					}
				}
			}

			echo "<pre>";
			print_r($sArr);exit;
	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>