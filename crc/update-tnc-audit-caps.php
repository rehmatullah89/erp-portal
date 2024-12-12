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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
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

	$Id    = IO::intValue('Id');
        $Step  = IO::intValue('Step');
	$bFlag = true;


	$_SESSION['Flag'] = "";

	$objDb->execute("BEGIN");


	if (!empty(IO::getArray("Point"))){
            
		$Points = IO::getArray("Point");
            	foreach ($Points as $Point)
		{
			$Cap = IO::strValue("Cap{$Point}");

			$sSQL  = "UPDATE tbl_tnc_audit_details SET cap = '$Cap' WHERE id='$Point' AND audit_id='$Id'";
                        $bFlag = $objDb->execute($sSQL);

			if ($bFlag == false)
				break;
		}

		/*if ($bFlag == true)
		{
			$Step ++;

                        $ParentSectionArray = array(0 => '');
                        $sParentSectionsList = getList("tbl_tnc_sections", "id", "section", "parent_id='0'", "id");
                        foreach($sParentSectionsList as $ParentSectionId => $ParentSection){
                            $ParentSectionArray[] = $ParentSectionId;
                        }
                        
			if ($ParentSectionArray[$Step] == "")
				$Step = -1;
		}*/
	}


	if ($_SESSION['Flag'] == "")
	{
		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			//if ($Step == -1)
			redirect("tnc-audits.php", "TNC_AUDIT_UPDATED");
				//redirect("tnc-audit-caps.php?Id={$Id}&Step={$Step}", "TNC_AUDIT_UPDATED");
		}

		else
		{
			$_SESSION['Flag'] = "DB_ERROR";

			$objDb->execute("ROLLBACK");
		}
	}

	header("Location: tnc-audit-caps.php?Id={$Id}");


	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>