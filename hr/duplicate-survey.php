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
	$objDb2      = new Database( );

	$Id  = IO::intValue("Id");

	$objDb->execute("BEGIN");

	$sSQL = "SELECT * FROM tbl_surveys WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$iSurveyId = getNextId("tbl_surveys");

		$sSQL  = ("INSERT INTO tbl_surveys (id, user_id, title, purpose, users, from_date, to_date, status, date_time) VALUES ('$iSurveyId', '{$_SESSION['UserId']}', '".$objDb->getField(0, "title")." - Copy', '".$objDb->getField(0, "purpose")."', '".$objDb->getField(0, "users")."', '".$objDb->getField(0, "from_date")."', '".$objDb->getField(0, "to_date")."', '".$objDb->getField(0, "status")."', NOW( ))");
		$bFlag = $objDb->execute($sSQL);

		if ($bFlag == true)
		{
			$sSQL = "SELECT * FROM tbl_survey_questions WHERE survey_id='$Id' ORDER BY display_order";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );

			for ($i = 0; $i < $iCount; $i ++)
			{
				$iId = getNextId("tbl_survey_questions");

				$sSQL  = ("INSERT INTO tbl_survey_questions (id, survey_id, question_type, answer_type, question, mcq_choices, column_headings, row_headings, display_order, validation, message, date_time) VALUES ('$iId', '$iSurveyId', '".$objDb->getField($i, "question_type")."', '".$objDb->getField($i, "answer_type")."', '".$objDb->getField($i, "question")."', '".$objDb->getField($i, "mcq_choices")."', '".$objDb->getField($i, "column_headings")."', '".$objDb->getField($i, "row_headings")."', '".$objDb->getField($i, "display_order")."', '".$objDb->getField($i, "validation")."', '".$objDb->getField($i, "message")."', NOW( ))");
				$bFlag = $objDb2->execute($sSQL);

				if ($bFlag == false)
					break;
			}
		}

		if ($bFlag == true)
		{
			$objDb->execute("COMMIT");

			redirect($_SERVER['HTTP_REFERER'], "SURVEY_DUPLICATED");
		}
	}

	$objDb->execute("ROLLBACK");

	redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>