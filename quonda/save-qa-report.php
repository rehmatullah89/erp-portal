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

	@require_once("../requires/session.php");
	@require_once("../requires/image-functions.php");

	if ($sUserRights['Edit'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	$Id         = IO::intValue('Id');
	$Referer    = urlencode(IO::strValue('Referer'));
	$Sms        = IO::intValue('Sms');
	$Step       = IO::intValue('Step');
        $iReportId  = IO::intValue('Report');
	$iUserId = getDbValue("user_id", "tbl_qa_reports", "id='$Id'");
	$sAuditsManager = getDbValue("audits_manager", "tbl_users", "id='{$_SESSION['UserId']}'");

	
	if(($iReportId == 14 || $iReportId == 34) && ($iUserId != $_SESSION['UserId'] || $sAuditsManager != "Y"))
		redirect(SITE_URL, "ACCESS_DENIED");

	
	$bFlag = $objDb->execute("BEGIN");

	
	$ReportId       = IO::intValue("Report");
	$Pos            = IO::strValue("PO");
	$Style          = IO::intValue("Style");
	$iPos           = @explode(",", $Pos);
	$MaxDefects     = IO::floatValue("MaxDefects");
	$TotalGmts      = IO::floatValue("TotalGmts");
	$AuditStage     = IO::strValue("AuditStage");
	$Published      = IO::strValue("Published");
	$PoId           = $iPos[0];
	$sAdditionalPos = "";
	$fDhu           = 0;
	$iDefectiveGmts = 0;
        

	for ($i = 1; $i < count($iPos); $i ++)
		$sAdditionalPos .= ((($i > 1) ? "," : "").$iPos[$i]);

	if ($Style == 0)
		$Style = getDbValue("style_id", "tbl_po_colors", "po_id='$PoId'");

	$iBrand = getDbValue("brand_id", "tbl_styles", "id='$Style'");


	if ($MaxDefects == 0 && $Style > 0 && $TotalGmts > 0)
	{
		$fAql = getDbValue("aql", "tbl_brands", "id='$iBrand'");
		$fAql = (($fAql == 0) ? 2.5 : $fAql);

		if (@isset($iAqlChart["{$TotalGmts}"]["{$fAql}"]))
			$MaxDefects = $iAqlChart["{$TotalGmts}"]["{$fAql}"];
	}
	
	
	$sSQL = "SELECT audit_date, audit_code FROM tbl_qa_reports WHERE id='$Id'";
	$objDb->query($sSQL);

	$sAuditCode = $objDb->getField(0, "audit_code");
	$sAuditDate = $objDb->getField(0, "audit_date");
	

	@list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

	@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear), 0777);
	@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth), 0777);
	@mkdir(($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

	$sQuondaDir = ($sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
	


        
	// Greige Fabric
	if ($ReportId == 6)
		@include($sBaseDir."includes/quonda/save-gf-report.php");

	// Adidas / Reebok
	else if ($ReportId == 7)
		@include($sBaseDir."includes/quonda/save-ar-report.php");

	// Yarn
	else if ($ReportId == 9)
		@include($sBaseDir."includes/quonda/save-yarn-report.php");

	// Jako
	else if ($ReportId == 10)
		@include($sBaseDir."includes/quonda/save-jako-report.php");

	// M&S
	else if ($ReportId == 11)
		@include($sBaseDir."includes/quonda/save-ms-report.php");

	// MGF
	else if ($ReportId == 14 || $ReportId == 34)
		@include($sBaseDir."includes/quonda/save-mgf-report.php");

        // Adidas / Reebok ---------- New
	else if ($ReportId == 19)
		@include($sBaseDir."includes/quonda/save-adidas-report.php");

	// KIK
	else if ($ReportId == 20 || $ReportId == 23)
		@include($sBaseDir."includes/quonda/save-kik-report.php");
        
	//BillaBong
	else if ($ReportId == 25 && $AuditStage != 'F')
		@include($sBaseDir."includes/quonda/save-inline-billabong-report.php");

	else if ($ReportId == 25 && $AuditStage == 'F')
		@include($sBaseDir."includes/quonda/save-final-billabong-report.php");

      	// TNC
	else if ($ReportId == 26)
		@include($sBaseDir."includes/quonda/save-tnc-report.php");

	// Controlist
	else if ($ReportId == 28)
		@include($sBaseDir."includes/quonda/save-controlist-report.php");
	
	//LeverStyle
	else if ($ReportId == 29)
		@include($sBaseDir."includes/quonda/save-leverstyle-report.php");
	
	//Towels
	else if ($ReportId == 30)
		@include($sBaseDir."includes/quonda/save-towel-report.php");
	
        // Hybrid Apparel
	else if ($ReportId == 31)
		@include($sBaseDir."includes/quonda/save-hybrid-apparel-report.php");
        
        // Arcadia & Hohenstein
	else if ($ReportId == 32 /*|| $ReportId == 39*/)
		@include($sBaseDir."includes/quonda/save-arcadia-report.php");
    
        // GMS
	else if ($ReportId == 33)
 		@include($sBaseDir."includes/quonda/save-gms-report.php");
        
        // TimeZone
	else if ($ReportId == 35)
 		@include($sBaseDir."includes/quonda/save-timezone-report.php");
        
        // Hybrid Link
	else if ($ReportId == 36)
 		@include($sBaseDir."includes/quonda/save-hybrid-link-report.php");
        
        // Armed Angels
	else if ($ReportId == 37)
 		@include($sBaseDir."includes/quonda/save-armedangels-report.php");
        
        // TM Clothing
	else if ($ReportId == 38)
 		@include($sBaseDir."includes/quonda/save-tmclothing-report.php");
        
        // Levis Report
	else if ($ReportId == 44 || $ReportId == 45)
 		@include($sBaseDir."includes/quonda/save-levis-report.php");
        
        // JCrew Report
	else if (@in_array($ReportId, array(46)))
 		@include($sBaseDir."includes/quonda/save-jcrew-report.php");
        
        // TriBurg Report
	else if (@in_array($ReportId, array(48)))
 		@include($sBaseDir."includes/quonda/save-triburg-report.php");
        
        //HOHENSTEIN
        else if ($ReportId == 39)
 		@include($sBaseDir."includes/quonda/save-hohenstein-report.php");       
        
        else if ($ReportId == 47 || $ReportId >= 53)
                    @include($sBaseDir."includes/quonda/save-general-report.php");
        //TOWN & COUNTRY
		/*else if ($ReportId == 54)
			@include($sBaseDir."includes/quonda/save-new-tnc-report.php");*/
       // Knits & Other
	else
		@include($sBaseDir."includes/quonda/save-knits-report.php");


	
	if ($bFlag == true && !@in_array($ReportId, array(6,26,30)))
	{
		$iCount = IO::intValue("Count");

		for ($i = 0; $i < $iCount; $i ++)
		{
			$Code      = IO::intValue("Code".$i);
			$Area      = IO::intValue("Area".$i);
			$DefectId  = IO::intValue("DefectId".$i);
			$PrevImage = IO::strValue("PrevPicture".$i);			
			$bRemove   = true;
			$PicCount  = 0;
                        
                        
                        if($ReportId == 47 || $ReportId >= 53)   
                        {
                            $Picture  = $_FILES["Picture{$i}"]['name'][0];  
                            $PicCount = count($_FILES["Picture{$i}"]['name']); 
                        }
                        else 
                            $Picture   = IO::getFileName($_FILES["Picture{$i}"]['name']);
                        
                        if($PicCount > 1 && $DefectId > 0)
                        {
                            $sPicsStr    = "";
                            $iPicCounter = 0;
                            foreach($_FILES["Picture{$i}"]['name'] as $iFile => $sFileName)
                            {
                                $iPicCounter ++;
                                
                                if($iPicCounter == 1)
                                    continue;
                                
                                if ($sFileName != "")
                                {
                                    $sExtension  = substr($sFileName, strrpos($sFileName, "."));
                                    $sPicture   = "{$Id}_{$DefectId}_{$iPicCounter}{$sExtension}";

                                    if (@move_uploaded_file($_FILES["Picture{$i}"]['tmp_name'][$iFile], ($sQuondaDir.$sPicture)))
                                        $sPicsStr .= ($sPicture.",");
                                }
                            }
                            
                            if($sPicsStr != "")
                            {
                                $sPicsStr = rtrim($sPicsStr, ',');
                                
                                $sSQL  = "UPDATE tbl_qa_report_defects SET pictures='$sPicsStr' WHERE audit_id='$Id' AND id='$DefectId'";
                                $bFlag = $objDb->execute($sSQL);
                            }
                        }
                        
			if ($Picture != "" && $Code > 0)
			{
				if ($DefectId == 0)
					$DefectId = getDbValue("id", "tbl_qa_report_defects", "audit_id='$Id' AND picture LIKE '%-{$Picture}'", "id ASC");
				
				if ($DefectId == 0)
					continue;
			
				$sDefectCode = getDbValue("code", "tbl_defect_codes", "id='$Code'");
				$sDefectArea = str_pad($Area, 2, '0', STR_PAD_LEFT);
					
				$sExtension  = substr($Picture, strrpos($Picture, "."));
				$sDefectPic  = "{$sAuditCode}_{$sDefectCode}_{$sDefectArea}_{$DefectId}{$sExtension}";

				
				if(strtolower($sExtension) == '.jpg' || strtolower($sExtension) == '.jpeg' || strtolower($sExtension) == '.png')
				{
                                        if($ReportId == 47 || $ReportId >= 53)
                                            $Moved = @move_uploaded_file($_FILES["Picture{$i}"]['tmp_name'][0], ($sBaseDir.TEMP_DIR.$sDefectPic));
                                        else 
                                            $Moved = @move_uploaded_file($_FILES["Picture{$i}"]['tmp_name'], ($sBaseDir.TEMP_DIR.$sDefectPic));
                                        
					if ($Moved)
					{
						@list($iWidth, $iHeight) = @getimagesize($sBaseDir.TEMP_DIR.$sDefectPic);

						$bResize = false;

						if ($iWidth > $iHeight && $iWidth > 800)
						{
								$bResize = true;
								$fRatio  = (800 / $iWidth);

								$iWidth  = 800;
								$iHeight = @ceil($fRatio * $iHeight);
						}

						else if ($iWidth < $iHeight && $iHeight > 800)
						{
								$bResize = true;
								$fRatio  = (800 / $iHeight);

								$iWidth  = @ceil($fRatio * $iWidth);
								$iHeight = 800;
						}


						if ($bResize == true && ($iWidth > 800 || $iHeight > 800))
							makeImage(($sBaseDir.TEMP_DIR.$sDefectPic), ($sQuondaDir.$sDefectPic), $iWidth, $iHeight);

						else
							@copy(($sBaseDir.TEMP_DIR.$sDefectPic), ($sQuondaDir.$sDefectPic));


						@unlink($sBaseDir.TEMP_DIR.$sDefectPic);


						if (!empty($PrevImage) && @file_exists($sQuondaDir.$PrevImage) && $PrevImage != $sDefectPic)
							@unlink($sQuondaDir.$PrevImage);

						
						$sSQL  = "UPDATE tbl_qa_report_defects SET picture='$sDefectPic' WHERE audit_id='$Id' AND id='$DefectId'";
						$bFlag = $objDb->execute($sSQL);
						
						if ($bFlag == false)
							break;
						
						$bRemove = false;
					}
					
					
					else if (LOG_DB_TRANSACTIONS == TRUE)
					{
						$sDbLogDir  = (DB_LOGS_DIR.date("Y-m"));
						$sDbLogFile = ($sDbLogDir."/".date("d-m-Y").".sql");

						@mkdir($sDbLogDir, 0777);

						$hFile = @fopen($sDbLogFile, "a+");

						if ($hFile)
						{
							@flock($hFile, LOCK_EX);
							@fwrite($hFile, "\n-- \n");

							@fwrite($hFile, ("-- User ID    : {$_SESSION[LOG_SESSION_USER_ID]}\n"));
							@fwrite($hFile, ("-- User Name  : {$_SESSION[LOG_SESSION_USER_NAME]}\n"));
							@fwrite($hFile, ("-- Query Time : ".date('h:i A')."\n"));
							@fwrite($hFile, ("-- IP Address : ".$_SERVER['REMOTE_ADDR']."\n"));
							@fwrite($hFile, ("-- Web Page   : ".$_SERVER['PHP_SELF']."\n"));
							@fwrite($hFile, ("-- Referer    : ".$_SERVER['HTTP_REFERER']."\n"));
							@fwrite($hFile, "-- \n\n");
							@fwrite($hFile, "-- Defect Picture Upload Failed - {$Picture} - {$sDefectPic}");
							@fwrite($hFile, "\n\n-- ----------------------------------------------------------------------------\n");

							@flock($hFile, LOCK_UN);
							@fclose($hFile);
						}
					}						
				}
				
				else if (LOG_DB_TRANSACTIONS == TRUE)
				{
					$sDbLogDir  = (DB_LOGS_DIR.date("Y-m"));
					$sDbLogFile = ($sDbLogDir."/".date("d-m-Y").".sql");

					@mkdir($sDbLogDir, 0777);

					$hFile = @fopen($sDbLogFile, "a+");

					if ($hFile)
					{
						@flock($hFile, LOCK_EX);
						@fwrite($hFile, "\n-- \n");

						@fwrite($hFile, ("-- User ID    : {$_SESSION[LOG_SESSION_USER_ID]}\n"));
						@fwrite($hFile, ("-- User Name  : {$_SESSION[LOG_SESSION_USER_NAME]}\n"));
						@fwrite($hFile, ("-- Query Time : ".date('h:i A')."\n"));
						@fwrite($hFile, ("-- IP Address : ".$_SERVER['REMOTE_ADDR']."\n"));
						@fwrite($hFile, ("-- Web Page   : ".$_SERVER['PHP_SELF']."\n"));
						@fwrite($hFile, ("-- Referer    : ".$_SERVER['HTTP_REFERER']."\n"));
						@fwrite($hFile, "-- \n\n");
						@fwrite($hFile, "-- Invalid Defect Picture - {$Picture}");
						@fwrite($hFile, "\n\n-- ----------------------------------------------------------------------------\n");

						@flock($hFile, LOCK_UN);
						@fclose($hFile);
					}
				}
				
				
				if ($bRemove == true && $DefectId > 0)
				{
					$sSQL  = "UPDATE tbl_qa_report_defects SET picture='' WHERE audit_id='$Id' AND id='$DefectId'";
					$bFlag = $objDb->execute($sSQL);
				}				
			}
			
			
			else if ($Picture != "" && LOG_DB_TRANSACTIONS == TRUE)
			{
				$sDbLogDir  = (DB_LOGS_DIR.date("Y-m"));
				$sDbLogFile = ($sDbLogDir."/".date("d-m-Y").".sql");

				@mkdir($sDbLogDir, 0777);

				$hFile = @fopen($sDbLogFile, "a+");

				if ($hFile)
				{
					@flock($hFile, LOCK_EX);
					@fwrite($hFile, "\n-- \n");

					@fwrite($hFile, ("-- User ID    : {$_SESSION[LOG_SESSION_USER_ID]}\n"));
					@fwrite($hFile, ("-- User Name  : {$_SESSION[LOG_SESSION_USER_NAME]}\n"));
					@fwrite($hFile, ("-- Query Time : ".date('h:i A')."\n"));
					@fwrite($hFile, ("-- IP Address : ".$_SERVER['REMOTE_ADDR']."\n"));
					@fwrite($hFile, ("-- Web Page   : ".$_SERVER['PHP_SELF']."\n"));
					@fwrite($hFile, ("-- Referer    : ".$_SERVER['HTTP_REFERER']."\n"));
					@fwrite($hFile, "-- \n\n");
					@fwrite($hFile, "-- Invalid Defect Code - {$Picture}");
					@fwrite($hFile, "\n\n-- ----------------------------------------------------------------------------\n");

					@flock($hFile, LOCK_UN);
					@fclose($hFile);
				}
			}
		}
	}

	if ($bFlag == true && !in_array($iReportId, array(6,9,30)))
	{
		$sSQL  = "UPDATE tbl_qa_reports SET dhu='$fDhu', defective_gmts='$iDefectiveGmts', date_time=NOW( ), modified_by='{$_SESSION['UserId']}' WHERE id='$Id'";
		$bFlag = $objDb->execute($sSQL);
	}

/*
	if ($bFlag == true && (IO::strValue("AuditStage") == "F" || IO::strValue("AuditResult") == "P" || IO::strValue("AuditResult") == "A" || IO::strValue("AuditResult") == "B"))
	{
		$sSQL  = ("UPDATE tbl_qa_reports SET status='".IO::strValue("AuditResult")."' WHERE id='$Id' AND status=''");
		$bFlag = $objDb->execute($sSQL);
	}
*/

	if ($bFlag == true)
	{
		$_SESSION['Flag'] = "QA_REPORT_SAVED";


		$sSQL = "UPDATE tbl_qa_reports SET style_id='$Style' WHERE id='$Id'";
		$objDb->execute($sSQL);

		if (getDbValue("brand_id", "tbl_qa_reports", "id='$Id'") == 0)
		{
			$iSubBrand = getDbValue("sub_brand_id", "tbl_styles", "id='$Style'");

			$sSQL = "UPDATE tbl_qa_reports SET brand_id='$iSubBrand' WHERE id='$Id'";
			$objDb->execute($sSQL);
		}


		// Updating FAD in VSR
		$sSQL  = "SELECT audit_date, po_id, additional_pos FROM tbl_qa_reports WHERE id='$Id' AND audit_stage='F' AND audit_result IN ('P','A','B')";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) == 1)
		{
			$sDate       = $objDb->getField(0, 0);
			$sPos        = $objDb->getField(0, 1);
			$sAdditional = $objDb->getField(0, 2);

			if ($sAdditional != "")
				$sPos .= (",".$sAdditional);

			$sSQL = "UPDATE tbl_vsr SET final_audit_date='$sDate' WHERE po_id IN ($sPos)";
			$objDb->execute($sSQL);
		}

		$objDb->execute("COMMIT");
	}

	else
	{	
		$objDb->execute("ROLLBACK");

		if ($_SESSION['Flag'] != "")
			$_SESSION['Flag'] = "DB_ERROR";

		backToForm( );
	}


	if ($_SESSION['Flag'] == "QA_REPORT_SAVED")
	{
		if ($Step == 1)
			redirect("edit-qa-report.php?Id={$Id}&Sms=1&Step=2&Referer={$Referer}".(($Published == "N") ? ("&Options=".@md5($Id)) : ""));

		else
		{
			if (IO::strValue("Publish") == "Y")
				redirect(urldecode($Referer));

			else
			{
				if ($Sms == 1)
					redirect("send-qa-report-notifications.php?Id={$Id}&Referer={$Referer}");

				else
					redirect("edit-qa-report.php?Id={$Id}&Referer={$Referer}");
			}
		}
	}


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>