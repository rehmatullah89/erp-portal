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
        
        $Id         = IO::intValue('Id');
        $sMonthList = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        
	$sSQL  = ("SELECT * FROM tbl_sgt_inspections WHERE factory_id='".IO::intValue("Factory")."' AND year = '".IO::strValue("Year")."' AND id!='$Id'");
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		
                $sMonths = array();
                foreach($sMonthList as $sMonth)
                {
                    $iMonths = IO::getArray("{$sMonth}");
                    
                    $sSubList = array();
                    foreach($iMonths as $iKey => $iMonth)
                        $sSubList[] = (int)$iMonth;
                    
                    $sMonths[] = implode(",",$sSubList);
                }
                
		$sSQL = ("UPDATE tbl_sgt_inspections SET factory_id='".IO::intValue("Factory")."', year='".IO::intValue("Year")."', january='".$sMonths[0]."', february='".$sMonths[1]."', march='".$sMonths[2]."', april='".$sMonths[3]."', may='".$sMonths[4]."', june='".$sMonths[5]."', july='".$sMonths[6]."', august='".$sMonths[7]."', september='".$sMonths[8]."', october='".$sMonths[9]."', november='".$sMonths[10]."', december='".$sMonths[11]."' WHERE id ='$Id'");

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