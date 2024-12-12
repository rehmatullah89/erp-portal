<?
	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$file = fopen("empty-specs.csv","w");
	
			$sEmptyStyles = array();
			
			$sSQL = ("SELECT id FROM `tbl_styles` WHERE brand_id = 434 AND (measurement_points = '' OR measurement_points IS NULL)");

			$objDb->query($sSQL);
			$iCount = $objDb->getCount( );
			
			for($i=0; $i<=$iCount; $i++)
			{
				$iStyle = $objDb->getField($i, "id"); 
				$sEmptyStyles[$iStyle]	= $iStyle;
			}
			
			//$iEmptyStyles = implode(",", $sEmptyStyles);
			
		
			$sSQL = ("SELECT order_no, styles,
							(SELECT GROUP_CONCAT(DISTINCT style SEPARATOR ',') FROM tbl_styles WHERE id IN (tbl_po.styles)) as _Style,
							(SELECT GROUP_CONCAT(s.season SEPARATOR ',') FROM tbl_seasons s, tbl_styles t where s.id=t.season_id AND t.id IN (tbl_po.styles)) as _Season
							FROM `tbl_po` WHERE brand_id IN (436,527,528,529,551)");
						
			$objDb->query($sSQL);
			$iCount = $objDb->getCount( );
			
			for($i=0; $i<=$iCount; $i++)
			{
				$sSeason  = $objDb->getField($i, "_Season");
				$sOrderNo = $objDb->getField($i, "order_no");
				$sStyles  = $objDb->getField($i, "styles");
				$sStyle   = $objDb->getField($i, "_Style");
				
				$sLine = array($sOrderNo, $sStyle, $sSeason);
				
				$iStyles = explode(",",$sStyles);
				
				foreach($iStyles as $iStyle)
				{
					if(@in_array($iStyle, $sEmptyStyles))
					{
						fputcsv($file,$sLine);
						break;
					}
				}
				
			}
			

	fclose($file);

	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>