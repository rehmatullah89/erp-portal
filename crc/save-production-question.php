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

	@require_once("../requires/session.php");

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );


	$Category     = IO::intValue("Category");
	$Question     = IO::strValue("Question");
	$QuestionType = IO::intValue("QuestionType");
	$NoOfOptions  = IO::intValue("NoOfOptions");
	$Options      = IO::getArray("Options");
	$Weightage    = IO::getArray("Weightage");


	$sSQL = "SELECT * FROM tbl_production_questions WHERE category_id='$Category' AND question LIKE '{$Question}'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 0)
	{
		$iId = getNextId("tbl_production_questions");


		$sSQL = ("INSERT INTO tbl_production_questions (id, question, category_id, question_type, no_of_options , options, weightage, position) VALUES
		                                               ('$iId', '$Question' , '$Category', '$QuestionType', '$NoOfOptions', '".@implode("|-|", $Options)."' , '".@implode("|-|", $Weightage)."', '$iId')");

		if ($objDb->execute($sSQL) == true)
			redirect($_SERVER['HTTP_REFERER'], "PRODUCTION_QUESTION_ADDED");

		else
			$_SESSION['Flag'] = "DB_ERROR";
	}

	else
		$_SESSION['Flag'] = "PRODUCTION_QUESTION_EXISTS";


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>