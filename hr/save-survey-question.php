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

	$SurveyId     = IO::intValue("SurveyId");
	$QuestionType = IO::strValue("QuestionType");
	$DisplayOrder = IO::intValue("DisplayOrder");
	$Validation   = IO::strValue("Validation");
	$Message      = IO::strValue("Message");

	if ($QuestionType == "Mcq")
	{
		$AnswerType = IO::strValue("McqType");
		$Question   = IO::strValue("McqQuestion");
		$McqChoices = IO::strValue("McqChoices");
	}

	if ($QuestionType == "Open")
	{
		$AnswerType = IO::strValue("OpenType");
		$Question   = IO::strValue("OpenQuestion");
	}

	if ($QuestionType == "Matrix")
	{
		$AnswerType     = IO::strValue("MatrixType");
		$Question       = IO::strValue("MatrixQuestion");
		$ColumnHeadings = IO::strValue("MatrixColumns");
		$RowHeadings    = IO::strValue("MatrixRows");
	}

	$bFlag = true;

	$objDb->execute("BEGIN");

	$sSQL = "SELECT MAX(display_order) FROM tbl_survey_questions WHERE survey_id='$SurveyId'";
	$objDb->query($sSQL);

	$iMaxOrder = $objDb->getField(0, 0);

	if ($iDisplayOrder <= $iMaxOrder)
	{
		$sSQL  = "UPDATE tbl_survey_questions SET display_order=(display_order + 1) WHERE survey_id='$SurveyId' AND display_order >= '$DisplayOrder'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$iId  = getNextId("tbl_survey_questions");

		$sSQL = ("INSERT INTO tbl_survey_questions (id, survey_id, question_type, answer_type, question, mcq_choices, column_headings, row_headings, display_order, validation, message, date_time) VALUES ('$iId', '$SurveyId', '$QuestionType', '$AnswerType', '$Question', '$McqChoices', '$ColumnHeadings', '$RowHeadings', '$DisplayOrder', '$Validation', '$Message', NOW( ))");
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$objDb->execute("COMMIT");

		redirect($_SERVER['HTTP_REFERER'], "SURVEY_QUESTION_ADDED");
	}

	else
	{
		$objDb->execute("ROLLBACK");

		$_SESSION['Flag'] = "DB_ERROR";
	}


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>