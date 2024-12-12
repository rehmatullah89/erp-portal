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

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

        $sMonthList  = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        
	$sSQL  = ("SELECT * FROM tbl_sgt_inspections WHERE factory_id='".IO::intValue("Factory")."' AND year = '".IO::strValue("Year")."'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_sgt_inspections");
 
                $sMonths = array();
                foreach($sMonthList as $sMonth)
                {
                    $iMonths = IO::getArray("{$sMonth}");
                    
                    $sSubList = array();
                    foreach($iMonths as $iKey => $iMonth)
                        $sSubList[] = (int)$iMonth;
                    
                    $sMonths[] = implode(",",$sSubList);
                }
              
		$sSQL = ("INSERT INTO tbl_sgt_inspections (id, factory_id, year, january, february, march, april, may, june, july, august, september, october, november, december)
		                           VALUES ('$iId', '".IO::intValue("Factory")."', '".IO::intValue("Year")."', '".$sMonths[0]."', '".$sMonths[1]."', '".$sMonths[2]."', '".$sMonths[3]."', '".$sMonths[4]."', '".$sMonths[5]."', '".$sMonths[6]."', '".$sMonths[7]."', '".$sMonths[8]."', '".$sMonths[9]."', '".$sMonths[10]."', '".$sMonths[11]."')");

                $Flag = $objDb->execute($sSQL);
                
                if ($Flag == true)
                    redirect($_SERVER['HTTP_REFERER'], "SGT_INSPECTION_ADDED");
		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "SGT_INSPECTION_EXISTS";

	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>