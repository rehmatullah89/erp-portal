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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$iReport = IO::intValue("Id");
	$List    = IO::strValue("List");

	if ($iReport == 0)
	{
		print "ERROR|-|Invalid Report. Please select the proper Report.\n";
		exit;
	}

        if($iReport == '14')
            $sBrandsList = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']}) AND id != '483'");

        else if($iReport == '47')
            $sBrandsList = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']}) AND id = '483'");  

        else
            $sBrandsList = getList("tbl_brands", "id", "brand", "id IN ({$_SESSION['Brands']})"); 
        
        if(count($sBrandsList) != 0)
        {
            print ("OK|-|".$List);
            
            foreach($sBrandsList as $iBrand => $sBrand)
            {
                print ("|-|".$iBrand."||".$sBrand);
            }
        }
        else
            print ("OK|-|".$List);

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>