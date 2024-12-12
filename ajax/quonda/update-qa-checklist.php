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

	$Id         = IO::intValue("Id");
	$Item       = IO::strValue("Item");
        $FieldType  = IO::strValue("FieldType");
        $Mandatory  = IO::strValue("Mandatory");
	$sError     = "";
        
        $sSQL = "SELECT id FROM tbl_qa_checklist WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Qa Checklist ID. Please select the proper Qa Checklist item to Edit.\n";
		exit( );
	}

	if ($Item == "")
		$sError .= "- Invalid Qa Checklist Item\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = "SELECT * FROM tbl_qa_checklist WHERE (item LIKE '$Item') AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = "UPDATE tbl_qa_checklist SET item='$Item', field_type='$FieldType', mandatory='$Mandatory' WHERE id='$Id'";

			if ($objDb->execute($sSQL) == true)
                        {                                
                                if($FieldType == 'YN')
                                    $FieldType = 'Radio (Yes/No/NA)';
                                else if($FieldType == 'TF')
                                    $FieldType = 'Text Field';
                                else if($FieldType == 'NF')
                                    $FieldType = 'Number Field';
                                else if($FieldType == 'CB')
                                    $FieldType = 'Check Box';
                                else if($FieldType == 'CC')
                                    $FieldType = 'Checkbox with Comments';  
                                
                                $Mandatory = ($Mandatory == 'Y')?'Yes':($Mandatory == 'N'?'No':'');
                                
				print ("OK|-|$Id|-|<div>The selected Qa Checklist Item has been Updated successfully.</div>|-|$Item|-|$FieldType|-|$Mandatory");
                        }
			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Qa Checklist Item already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";


	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>