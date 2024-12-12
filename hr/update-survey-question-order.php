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

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$SurveyId   = IO::intValue("SurveyId");
	$QuestionId = IO::intValue("QuestionId");
	$CurOrder   = IO::intValue("CurOrder");
	$NewOrder   = IO::intValue("NewOrder");

	$objDb->execute("BEGIN");

	$sSQL  = "UPDATE tbl_survey_questions SET display_order='$CurOrder' WHERE survey_id='$SurveyId' AND display_order='$NewOrder'";
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL  = "UPDATE tbl_survey_questions SET display_order='$NewOrder' WHERE survey_id='$SurveyId' AND id='$QuestionId'";
		$bFlag = $objDb->execute($sSQL);
	}

	if ($bFlag == true)
	{
		$_SESSION['Flag'] = "SURVEY_QUESTION_ORDER_UPDATED";

		$objDb->execute("COMMIT");
	}

	else
	{
		$_SESSION['Flag'] = "DB_ERROR";

		$objDb->execute("ROLLBACK");
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>