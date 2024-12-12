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

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id           = IO::intValue("Id");
	$Category     = IO::intValue("Category");
	$Question     = IO::strValue("Question");
	$QuestionType = IO::intValue("QuestionType");
	$NoOfOptions  = IO::intValue("NoOfOptions");
	$Options      = IO::getArray("Options");
	$Weightage    = IO::getArray("Weightage");
	$sError       = "";

	$sSQL = "SELECT id FROM tbl_production_questions WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Question ID. Please select the proper Question to Edit.\n";
		exit( );
	}

	if ($Category > 0)
	{
		$sSQL = "SELECT title FROM tbl_production_categories WHERE id='$Category'";
		$objDb->query($sSQL);

		if ($objDb->getCount( ) != 1)
			$sError .= "- Invalid Category\n";

		else
			$sCategory = $objDb->getField(0, 0);
	}

	if ($Question == "")
		$sError .= "- Invalid Question\n";

	if ($QuestionType == 0)
		$sError .= "- Invalid Question Type\n";

	if ($NoOfOptions == 0)
		$sError .= "- Invalid No of Options\n";

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL  = "SELECT * FROM tbl_production_questions WHERE question LIKE '$Question' AND category_id='$Category' AND id!='$Id'";

	if ($objDb->query($sSQL) == true)
	{
		if ($objDb->getCount( ) == 0)
		{
			$sSQL = ("UPDATE tbl_production_questions SET category_id='$Category', question='$Question', question_type='$QuestionType', no_of_options='$NoOfOptions', options='".@implode("|-|", $Options)."', weightage='".@implode("|-|", $Weightage)."' WHERE id='$Id'");

			if ($objDb->execute($sSQL) == true)
				print ("OK|-|$Id|-|<div>The selected Question has been Updated successfully.</div>|-|$Question|-|$sCategory");

			else
				print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";
		}

		else
			print "INFO|-|$Id|-|The specified Category & Question already exists in the System";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>