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

        $ParentStages = getList("tbl_audit_stages", "id", "stage");
        
	$Id         = IO::intValue("Id");
	$AuditStage = IO::strValue("AuditStage");
	$ParentStage= IO::intValue("ParenStage");
	$sError     = "";

        $sParentStage= $ParentStages[$ParentStage];
        
	$sSQL = "SELECT id FROM tbl_audit_sub_stages WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Audit Sub Stage ID. Please select the proper Audit Sub Stage to Edit.\n";
		exit( );
	}

	if ($AuditStage == "")
		$sError .= "- Invalid Audit Stage\n";

	if ($ParentStage == "" || $ParentStage == 0)
		$sError .= "- Invalid Audit Parent Stage\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = "SELECT * FROM tbl_audit_sub_stages WHERE (stage LIKE '$AuditStage') AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_audit_sub_stages SET stage='$AuditStage', parent_id='$ParentStage' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Audit Sub Stage has been Updated successfully.</div>|-|$AuditStage|-|$sParentStage");

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Audit Sub Stage already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>