<?
	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	
	$iCountries = array();
	$sCountries =  getList("tbl_countries", "country", "id");
	foreach($sCountries as $sCountry => $iCountry)
	{
		$iCountries[strtolower($sCountry)] = $iCountry;
	}

$objDb->query("BEGIN");

	$cfile = fopen('new_vendors.csv', 'r');
	while (($line = fgetcsv($cfile)) !== FALSE) 
	{
				$sVendor = str_replace("'", "", $line[1]);
				$sCode   = trim($line[0]);
				$sCountry= trim(strtolower($line[2]));
				
				if($sCountry == 'usa')
					$sCountry = 'united states';
				else if($sCountry == 'south korea')
					$sCountry = 'korea, republic of';
				
				$iId = (int)getDbValue("id", "tbl_vendors", "code='$sCode' AND vendor LIKE '$sVendor'");
				
				if($iId == 0)
				{
					$iId = getNextId("tbl_vendors");
					$sSQL = ("INSERT INTO tbl_vendors (id, sourcing, parent_id, vendor, code, city, category_id, country_id, daily_capacity, levis)
									   VALUES ('$iId', 'Y', '0', '".$sVendor."', '".$sCode."', 'Not Provided', '4', '".$iCountries[$sCountry]."', '1000', 'Y')");

					$bFlag = $objDb->execute($sSQL);
					
					if($bFlag == false)
						break;
				}
	}
        if($bFlag == true)
        {
            $objDb->query("COMMIT");
            echo "DONE";
        }
        else
        {
            $objDb->query("ROLLBACK");
            echo $sSQL;
            exit;
        }

	fclose($cfile);

	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>