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
	$objDb2      = new Database( );

	$Id       = IO::intValue("Id");
	$SurveyId = IO::intValue("SurveyId");
	$iScore   = 0;

	$sSQL = "SELECT * FROM tbl_survey_questions WHERE survey_id='$SurveyId' ORDER BY display_order ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId           = $objDb->getField($i, "id");
		$sQuestionType = $objDb->getField($i, "question_type");
		$sAnswerType   = $objDb->getField($i, "answer_type");

		$sAnswers = array( );

		if ($sQuestionType == "Mcq")
		{
			$sMcqChoices = $objDb->getField($i, "mcq_choices");

			$sChoices    = @explode("\r\n", $sMcqChoices);
			$jCount      = count($sChoices);

			for ($j = 0; $j < $jCount; $j ++)
			{
				if (@strrpos($sChoices[$j], '(') === FALSE)
				{
					$sChoice    = $sChoices[$j];
					$iWeightage = 0;
				}

				else
				{
					$sChoice    = substr($sChoices[$j], 0, (@strrpos($sChoices[$j], '(') - 1));
					$iWeightage = (int)substr($sChoices[$j], (@strrpos($sChoices[$j], '(') + 1), -1);
				}


				if ($j == 0)
					$iMaxScore = $iWeightage;

				else if ($iWeightage > $iMaxScore)
					$iMaxScore = $iWeightage;


				$sAnswers['Score'][$j]  = $iWeightage;
				$sAnswers['Choice'][$j] = $sChoice;
			}


			$sSQL = "SELECT answer FROM tbl_survey_answers WHERE question_id='$iId' AND feedback_id='$Id'";
			$objDb2->query($sSQL);

			if ($objDb2->getCount( ) == 1)
			{
				$sAnswer = $objDb2->getField(0, 0);

				for ($j = 0; $j < count($sAnswers['Choice']); $j ++)
				{
					if ($sAnswers['Choice'][$j] == $sAnswer)
						$iScore += $sAnswers['Score'][$j];
				}
			}
		}

		else if ($sQuestionType == "Open")
		{

		}

		else if ($sQuestionType == "Matrix")
		{
			$sColumnHeadings = $objDb->getField($i, "column_headings");
			$sRowHeadings    = $objDb->getField($i, "row_headings");

			$sColumnChoices  = @explode("\r\n", $sColumnHeadings);
			$cCount          = count($sColumnChoices);

			$sRowChoices     = @explode("\r\n", $sRowHeadings);
			$rCount          = count($sRowChoices);
		}
	}


	$sSQL = "UPDATE tbl_survey_feedback SET score='$iScore' WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
		$_SESSION['Flag'] = "SURVEY_FEEDBACK_SCORE_UPDATED";

	else
		$_SESSION['Flag'] = "DB_ERROR";

	header("Location: {$_SERVER['HTTP_REFERER']}");

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>