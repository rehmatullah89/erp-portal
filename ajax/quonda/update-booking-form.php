<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Id                     = IO::intValue("Id");
        $Brand          	= IO::intValue("Brand");
        $Factory        	= IO::intValue("Factory");
        $Ports                  = implode(",", IO::getArray("Ports"));
        $Vendor         	= IO::intValue("Vendor");
        $Article        	= IO::strValue("Article");
        $Ian        		= IO::strValue("Ian");
        $LotSize        	= IO::strValue("LotSize");
        $Shipments      	= IO::strValue("Shipments");
        $ReqInspectionDate      = IO::strValue("ReqInspectionDate");
        $ShippingDate           = IO::strValue("ShippingDate");
        $Service  		= IO::intValue("Service");
        $SamplePickFor          = IO::strValue("SamplePickFor");
        $Remarks      		= IO::strValue("Remarks");
        $StatusComments		= IO::strValue("StatusComments");
        $Status      		= IO::strValue("Status");
        $ReTest      		= (IO::strValue("ReTest") == 'Y'?'Y':'N');
	$sError     = "";

        $sVendorsList    = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList     = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})");
        $sStagesList  = getList("tbl_audit_stages", "id", "stage");
        
        $sBrand  = $sBrandsList[$Brand];
        $sVendor = $sVendorsList[$Factory];
        $sStage  = $sStagesList[$Service];

	$sSQL = "SELECT id FROM tbl_bookings WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Booking Form ID. Please select the proper Booking Form to Edit.\n";
		exit( );
	}

	if ($Brand == "")
		$sError .= "- Invalid Brand\n";
        
        if ($Factory == "")
		$sError .= "- Invalid Factory\n";
        
        if ($Ports == "")
		$sError .= "- Invalid Ports Selected\n";
        
        if ($Vendor == "")
		$sError .= "- Invalid Vendor\n";
        
        if ($Article == "")
		$sError .= "- Invalid Article\n";
        
        if ($Ian == "")
		$sError .= "- Invalid Ian\n";
        
        if ($LotSize == "")
		$sError .= "- Invalid LotSize\n";
        
        if ($ReqInspectionDate == "")
		$sError .= "- Invalid Requested Inspection Date\n";
        
        if ($ShippingDate == "")
		$sError .= "- Invalid Shipping Date\n";
        
        if ($SamplePickFor == "")
		$sError .= "- Invalid Sample Pick For\n";
        
        if ($Service == "")
		$sError .= "- Invalid Service Selected\n";
        
	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}

        $sSubSql = "";        
        $sPrevStatus = getDbValue("status", "tbl_bookings", "id='$Id'");
        
        if($Status != $sPrevStatus && $Status == 'A')
            $sSubSql = " status='A', accepted_by='{$_SESSION['UserId']}', accepted_at = NOW(), ";     
        else if($Status != $sPrevStatus && $Status == 'R')
            $sSubSql = " status='R', rejected_by='{$_SESSION['UserId']}', rejected_at = NOW(), ";         
        
        $sSQL = "UPDATE tbl_bookings SET brand_id = '$Brand', supplier_id = '$Vendor', factory_id = '$Factory', ports='$Ports', article = '$Article', ian = '$Ian', quantity = '$LotSize', shipments = '$Shipments', inspection_date = '$ReqInspectionDate', shipping_date='$ShippingDate', service_id = '$Service', sample_for = '$SamplePickFor', notes = '$Remarks', $sSubSql status_comments='$StatusComments', re_test='$ReTest', modified_at = NOW(), modified_by = '{$_SESSION['UserId']}' WHERE id='$Id'";
        $Flag = $objDb->execute($sSQL);
        
        if ($Flag == true)
        {
            @list($sYear, $sMonth, $sDay) = @explode("-", $ReqInspectionDate);

            @mkdir(($sBaseDir.$sBaseDir.BOOKINGS_DIR.$sYear), 0777);
            @mkdir(($sBaseDir.$sBaseDir.BOOKINGS_DIR.$sYear."/".$sMonth), 0777);
            @mkdir(($sBaseDir.$sBaseDir.BOOKINGS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);
                
            for($k=0; $k<10; $k++)
            {
                $sFileName = $_FILES["Attachment-{$k}"]['name'];
                
                if ($sFileName != "")
                {
                    $exts = explode('.', $sFileName);
                    $extension = end($exts);

                    if(@in_array(strtolower($extension), array('jpg','jpeg','gif','png','pdf','doc','docx','csv','xls','xlsx','txt','ppt','pptx','zip','rar')))
                    {
                        $sPicture = ("BOOKING_".$Id."_".rand(1, 100).'.'.$extension);

                        if (@move_uploaded_file($_FILES["Attachment-{$k}"]['tmp_name'], ($sBaseDir.$sBaseDir.BOOKINGS_DIR.$sYear.'/'.$sMonth.'/'.$sDay.'/'.$sPicture)))
                        {
                                $FileId = getNextId("tbl_booking_files");
                                $sSQL   = "INSERT INTO tbl_booking_files SET id = '$FileId', booking_id='$Id', file = '$sPicture'";
                                $Flag   = $objDb->execute($sSQL);
                        }

                        if($Flag == false)
                            break;
                    }
                }
                else
                    break;                
            }

        }
        
        if ($Flag == true)
            print ("OK|-|$Id|-|<div>The selected Booking Form has been Updated successfully.</div>|-|$sBrand|-|$sVendor|-|$ReqInspectionDate|-|$sStage");
        else
                print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>