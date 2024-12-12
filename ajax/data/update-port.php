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
     ini_set('display_errors', 1);
     error_reporting(E_ALL);
	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id                 = IO::intValue("Id");
	$PortName           = IO::strValue("PortName");
        $sDisplayAtBooking  = (IO::strValue('DisplayAtBooking') == 'Y'?'Y':'N');
	$sError             = "";

	$sSQL = "SELECT id FROM tbl_shipping_ports WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Shipping Port ID. Please select the proper Shipping Port to Edit.\n";
		exit( );
	}

	if ($PortName == "")
		$sError .= "- Invalid Port Name\n";


	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}

	$sSQL  = "SELECT * FROM tbl_shipping_ports WHERE port_name LIKE '$PortName' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
                        $sPdfSymbol = $_FILES["PdfSymbol"]['name'];
                        if ($sPdfSymbol != "")
                        {
                                $exts = explode('.', $sPdfSymbol);
                                $extension = end($exts);

                                if(@in_array(strtolower($extension), array('jpg','jpeg','gif','png')))
                                {
                                    $sPdfPicture = ("PDF_SYMBOL_".$Id.'.'.$extension);

                                    if (@move_uploaded_file($_FILES["PdfSymbol"]['tmp_name'], ($sBaseDir.$sBaseDir.SHIPPING_PORTS_DIR.$sPdfPicture)))
                                    {
                                            $sSQL  = "UPDATE tbl_shipping_ports SET port_name='$PortName', booking_form='$sDisplayAtBooking', pdf_symbol = '$sPdfPicture' WHERE id='$Id'";
                                    }
                                }
                                else
                                    $_SESSION['Flag'] = "DB_ERROR";
                        }
                    
                        $sFileName = $_FILES["Picture"]['name'];
                        if ($sFileName != "")
                        {
                                $exts = explode('.', $sFileName);
                                $extension = end($exts);

                                if(@in_array(strtolower($extension), array('jpg','jpeg','gif','png')))
                                {
                                    $sPicture = ("PORT_SYMBOL_".$Id.'.'.$extension);

                                    if (@move_uploaded_file($_FILES["Picture"]['tmp_name'], ($sBaseDir.$sBaseDir.SHIPPING_PORTS_DIR.$sPicture)))
                                    {
                                        $sSQL = "UPDATE tbl_shipping_ports SET port_name='$PortName', symbol = '$sPicture' WHERE id='$Id'";
                                    }
                                }
                        }                    
                        
                        if ($sPdfSymbol == "" && $sFileName == "")
                            $sSQL = "UPDATE tbl_shipping_ports SET port_name='$PortName', booking_form='$sDisplayAtBooking' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
                            print ("OK|-|$Id|-|<div>The selected Destination has been Updated successfully.</div>|-|$PortName");
                        else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Port Name already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>