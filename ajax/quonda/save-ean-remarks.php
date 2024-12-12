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
	$objDb2       = new Database( );

	$Id             = IO::intValue("odId");
	$AuditId        = IO::intValue("auditId");
	$StyleId        = IO::intValue("styleId");
	$SizeId         = IO::intValue("sizeId");
	$EAN            = IO::strValue("ean");
	$Positions      = IO::getArray("Position");
	$EanCodes       = IO::getArray("EanCodes");

	$counter = 1;
	$successCount = 0;
	
	$objDb->execute("BEGIN");

        $sSQL = "DELETE FROM tbl_qa_ean_codes WHERE audit_id='$AuditId' AND style_id='$StyleId' AND size_id='$SizeId'";

        $objDb->execute($sSQL);

	for($i=0; $i<count($Positions); $i++)
        {

		$position = $Positions[$i];
		$eanCode = $EanCodes[$i];

		$result = ("0123456789" == $eanCode) ? 'P' : 'F';
		
                $sSQL = "INSERT INTO `tbl_qa_ean_codes` (`audit_id`, `serial`, `style_id`, `size_id`, `position`, `code`, `result`) VALUES ('$AuditId', '$counter', '$StyleId', '$SizeId', '$position', '$eanCode', '$result')";

                $done = $objDb->execute($sSQL);

                $counter++;

                if($done)
                    $successCount++;
	}

        if ($successCount == count($Positions))
        {
                $objDb->execute("COMMIT");

                        print "OK|-|".$Id."|-|<div>The selected EAN Codes has been saved successfully.</div>";

                        exit();
        }

        else
        {
                $objDb->execute("ROLLBACK");

                print "ERROR|-|Record not added .\n";
                exit( );			
        }	

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>