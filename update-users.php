<?
	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	
			$sSQL = ("SELECT id, auditor_type from tbl_users where user_type != 'MGF'");			
			$objDb->query($sSQL);
			
			$iCount = $objDb->getCount( );
			
			for($i=0; $i<=$iCount; $i++)
			{
				$iUser 			= $objDb->getField($i, "id"); 
				$iAuditorType 	= $objDb->getField($i, "auditor_type"); 
				
				switch ($iAuditorType)
				{
					case 1 : $iNewAuditorType = 3; break;
					case 2 : $iNewAuditorType = 4; break;
					case 3 : $iNewAuditorType = 5; break;
					case 4 : $iNewAuditorType = 1; break;
					case 5 : $iNewAuditorType = 2; break;
					
				}
				
				$sSQL = ("UPDATE tbl_users SET auditor_type='$iNewAuditorType' WHERE id = '$iUser' AND auditor_type='$iAuditorType'");
				$objDb3->query($sSQL);
			}

	
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );
echo "DONE";exit;
	@ob_end_flush( );
?>