<?
	@require_once("../requires/session.php");
	
	if ($sUserRights['Add'] != "Y" && $sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	
	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	
	$iCategory         = IO::intValue("Category");
	$iProgram          = IO::intValue("Program");	
	$iBrand            = IO::intValue("Brand");
	$iSubBrand         = IO::intValue("SubBrand");
	$iSeason           = IO::intValue("Season");
	$iSubSeason        = IO::intValue("SubSeason");
	$iSamplingCategory = IO::intValue("SamplingCategory");	


	$sSizesList = getList("tbl_sampling_sizes", "id", "size", "", "id");
	$iPosition  = getDbValue("MAX(position)", "tbl_style_specs")+1;
	
	$bFlag = $objDb->execute("BEGIN", false);	
	$sCsv  = "";
	
	if ($_FILES['CsvFile']['name'] != "")
		$sCsv = $_FILES['CsvFile']['tmp_name'];

	
	$hFile = @fopen($sCsv, "r");
        
	while (($sRecord = @fgetcsv($hFile, 10000)) !== FALSE)
	{
		if ($sRecord[0] == "PC5 Code" && $sRecord[1] == "Product Name")
		{
			$sRecord = @fgetcsv($hFile, 10000);
			
			$sStyleNo    = trim($sRecord[0]);
			$sStyleName  = utf8_encode(addslashes(trim($sRecord[1])));
			$sStyleSizes = trim(trim($sRecord[5], "|~*~|")); 
			
			print $sStyleNo." - ".$sStyleName."<bR>";
			@flush( );

                        
			
			$iStyleId = (int)getDbValue("id", "tbl_styles", "style LIKE '$sStyleNo' AND brand_id='$iBrand' AND sub_brand_id IN (436,527,528,529) AND season_id='$iSeason' AND sub_season_id='$iSubSeason'");
			
                        if ($iStyleId == 0)
			{
				$iStyle = getNextId("tbl_styles");
						
				$sSQL   = ("INSERT INTO tbl_styles (id, category_id, style, style_name, reference, brand_id, sub_brand_id, season_id, sub_season_id, program_id, design_no, design_name, block_no, division, carry_over_id, fabric_width, specs_file, sketch_file, measurement_points, sizes, created, created_by, modified, modified_by)
										    VALUES ('$iStyle', '$iCategory', '$sStyleNo', '$sStyleName', '', '$iBrand', '$iSubBrand', '$iSeason', '$iSubSeason', '$iProgram', '', '', '', '', '0', '0', '', '', '', '', NOW( ), '{$_SESSION['UserId']}', NOW( ), '{$_SESSION['UserId']}')");
				$bFlag  = $objDb->execute($sSQL, false);
print $sSQL."<br>";
				
				if ($bFlag == false)
				{
					print "Style No: {$sStyleNo}<br />";
					print "Style Name: {$sStyleName}<br />";
					
					if ($bFlag == false)
						print ("SQL: {$sSQL}<br /><br />ERROR: ".mysql_error( ));

					
					$objDb->execute("ROLLBACK", false);
					exit( );
				}
			}			
			else 
				$iStyle = $iStyleId;
			
			$sRecord     = @fgetcsv($hFile, 10000);
			$sRecord     = @fgetcsv($hFile, 10000);			
			
			$sStyleSizes =  @explode("|~*~|", $sStyleSizes);			
			$iStyleSizes = array( );
			$sSizes      = array( );			
	
			
			for ($i = 0; $i < count($sStyleSizes); $i ++)
			{
				$sSize = trim($sStyleSizes[$i]);

				if ($sSize == "")
					break;
			

				$iSizeIndex = @array_search($sSize, $sSizesList, TRUE); 
				
				if ($iSizeIndex !== FALSE)
					$sSizes["{$i}"] = $iSizeIndex;
				
				else
				{
					$iSize = getNextId("tbl_sampling_sizes");
					
					$sSQL  = "INSERT INTO tbl_sampling_sizes (id, size, display_order) VALUES ('$iSize', '{$sSize}', '$iSize')";
					$bFlag = $objDb->execute($sSQL, false);
print $sSQL."<br>";
					if ($bFlag == false)
					{
						print "Style No: {$sStyleNo}<br />";
						print "Style Name: {$sStyleName}<br />";
						print "Size: {$sSize}<br />";
						
						if ($bFlag == false)
							print ("SQL: {$sSQL}<br /><br />ERROR: ".mysql_error( ));

						
						$objDb->execute("ROLLBACK", false);
						exit( );
					}
					
					
					$sSizesList[$iSize] = $sSize;
					$sSizes["{$i}"]     = $iSize;
				}

				$iStyleSizes[] = $sSizes["{$i}"];
			}
			
			
					
			/*if ($bFlag == true)
			{
                            $sSQL  = "UPDATE tbl_style_specs SET version=(version + '1') WHERE style_id='$iStyle' ORDER BY version DESC";
                            $bFlag = $objDb->execute($sSQL);
			}
			else
                            break;*/

			$iStylePoints = array( );
			$bEmpty       = false;
			$iIndex       = ((int)getDbValue("MAX(id)", "tbl_style_specs", "style_id='$iStyle' AND version='0'") + 1); // AND point_id='$iPoint'
			
			while (($sRecord = @fgetcsv($hFile, 10000)) !== FALSE)
			{
				if (@strpos($sRecord[5], "|~*~|") !== FALSE)
					break;
				
				if (trim($sRecord[0]) == "" && trim($sRecord[1]) == "")
				{
					if ($bEmpty == true)
						break;

					
					$bEmpty = true;
					
					continue;
				}
			
				$sPointId        = trim($sRecord[0]);
				$sPointName      = @utf8_encode(addslashes(trim($sRecord[1])));
				//$sNature         = trim($sRecord[2]);
				$sPlusTolerance  = trim($sRecord[3]);
				$sMinusTolerance = trim($sRecord[4]);


				$iPoint = getDbValue("id", "tbl_measurement_points", "point_id LIKE '$sPointId' AND `point` LIKE '$sPointName' AND category_id='$iSamplingCategory' AND brand_id='$iSubBrand'");
				
				if ($iPoint == 0)
					$iPoint = getDbValue("id", "tbl_measurement_points", "point_id LIKE 'P%' AND `point` LIKE '$sPointName' AND category_id='$iSamplingCategory' AND brand_id='$iSubBrand'");
				
				if ($iPoint == 0)
				{
					$iPoint   = getNextId("tbl_measurement_points");
					$sPointId = (($sPointId == "") ? ("P".str_pad($iPoint, 5, '0', STR_PAD_LEFT)) : $sPointId);
					
					
					$sSQL   = ("INSERT INTO tbl_measurement_points (id, point_id, `point`, tolerance, category_id, brand_id, date_time) VALUES ('$iPoint', '$sPointId', '$sPointName', '-{$sMinusTolerance}/+{$sPlusTolerance}', '$iSamplingCategory', '$iSubBrand', NOW( ))");
					$bFlag  = $objDb->execute($sSQL, false);
print $sSQL."<br>";
					if ($bFlag == false)
					{
						print "Style No: {$sStyleNo}<br />";
						print "Style Name: {$sStyleName}<br />";
						print "Point: {$sPointId} - {$sPointName}<br />";
						
						if ($bFlag == false)
							print ("SQL: {$sSQL}<br /><br />ERROR: ".mysql_error( ));

						
						$objDb->execute("ROLLBACK", false);
						exit( );
					}
				}

				/*$CheckDiscardLines = array();
                                foreach ($sSizes as $iSizeIndex => $iSize)
				{
					$fSpecsValue = floatval(trim($sRecord[$iSizeIndex + 6]));										
                                        $CheckDiscardLines[] = $fSpecsValue;					
				}*/
				
                                if(@in_array($sPointId, array("INS1","INSEC")) && ((int)getDbValue("count(1)", "tbl_style_specs", "style_id='$iStyle' AND point_id='$iPoint'")) == 0)
                                {
                                    foreach ($sSizes as $iSizeIndex => $iSize)
                                    {
                                            $fSpecsValue = floatval(trim($sRecord[$iSizeIndex + 6]));
                                            
                                            $sNature = "S"; // Standard for all other
                                            $iFbPosition = 0; // full body
                                            $iCrPosition = 0; // critical
                                            $iOtPosition = 0; // Other position
                                            
//                                            if (@in_array($sPointId, array("INS1","INSEC")))
                                            {
                                                    
                                                    $sNature = "C";
                                                    $iFbPosition = 2;
                                                    $iCrPosition = 1;
                                                            
                                                    if($sPlusTolerance == "")
                                                        $sPlusTolerance = 0;

                                                    if($sMinusTolerance == "")
                                                        $sMinusTolerance = 0;
                                                
                                                   
                                                $sSQL  = "INSERT INTO tbl_style_specs (id, style_id, point_id, size_id, specs, version, nature, fb_position, cr_position, position) VALUES ('$iIndex', '$iStyle', '$iPoint', '$iSize', '$fSpecsValue', '0', '$sNature', '$iFbPosition', '$iCrPosition', '$iOtPosition')";
                                                $bFlag = $objDb->execute($sSQL, false);		
print $sSQL."<br>";
                                                if ($bFlag == false)
                                                        break;

                                                $iIndex ++;
                                            }
                                    }
                                    
                                    $iStylePoints[] = $iPoint;
                                    $bEmpty         = false;
                                }
			}
			
			
			if ($bFlag == true)
			{
				$sSQL  = ("UPDATE tbl_styles SET measurement_points='".@implode(",", $iStylePoints)."', sizes='".@implode(",", $iStyleSizes)."' WHERE id='$iStyle'");
				$bFlag = $objDb->execute($sSQL, false);					
print $sSQL."<br>";
			}			
			
			if ($bFlag == true && $iStyleId > 0)
			{
				$iLog  = getNextId("tbl_style_log");

				$sSQL  = ("INSERT INTO tbl_style_log (id, style_id, user_id, date_time, reason, remarks, specs_file) VALUES ('$iLog', '$iStyle', '{$_SESSION['UserId']}', NOW( ), 'R', 'Specs Revision - Import Script', '')");
				$bFlag = $objDb->execute($sSQL);
print $sSQL."<br>";
			}

			if ($bFlag == false)
				break;
		}
                
                break;
	}
	

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT", false);
echo "DONE";exit;
                redirect($_SERVER['HTTP_REFERER'], "STYLE_ADDED");
	}
	
	else
	{
		print "Style No: {$sStyleNo}<br />";
		print "Style Name: {$sStyleName}<br />";
		print "Point: {$sPointId} - {$sPointName}<br />";		
		
		print $sSQL."<br><br>".mysql_error();
		$objDb->execute("ROLLBACK", false);
		exit;
        
		$_SESSION['Flag'] = "DB_ERROR";
	}

    
	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>