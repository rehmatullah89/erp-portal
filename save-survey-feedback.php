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

	@require_once("requires/session.php");

	checkLogin( );

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id = IO::intValue("Id");

	$objDb->query("BEGIN");

	$iId = getNextId("tbl_survey_feedback");

	$sSQL  = ("INSERT INTO tbl_survey_feedback (id, survey_id, user_id, score, date_time) VALUES ('$iId', '$Id', '{$_SESSION['UserId']}', '0', NOW( ))");
	$bFlag = $objDb->execute($sSQL);

	if ($bFlag == true)
	{
		$sSQL = "SELECT * FROM tbl_survey_questions WHERE survey_id='$Id' ORDER BY display_order ASC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$iScore = 0;

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iQuestionId   = $objDb->getField($i, "id");
			$sQuestionType = $objDb->getField($i, "question_type");
			$sAnswerType   = $objDb->getField($i, "answer_type");
			$Answer        = "";

			if ($sQuestionType == "Mcq")
			{
				$sMcqChoices = $objDb->getField($i, "mcq_choices");

				$sChoices    = @explode("\r\n", $sMcqChoices);
				$jCount      = count($sChoices);


				if ($sAnswerType == "Radio" || $sAnswerType == "Dropdown")
				{
					@list($iWeightage, $sChoice) = @explode("|-|", IO::strValue("Q_".$iQuestionId));

					$Answer  = $sChoice;
					$iScore += $iWeightage;
				}

				else if ($sAnswerType == "Checkbox")
				{
					$sMcqChoices = $objDb->getField($i, "mcq_choices");

					$sChoices = @explode("\r\n", $sMcqChoices);
					$jCount   = @count($sChoices);

					if ($jCount > 1)
					{
						$sChoices = IO::getArray("Q_".$iQuestionId);

						for ($j = 0; $j < count($sChoices); $j ++)
						{
							@list($iWeightage, $sChoice) = @explode("|-|", $sChoices[$j]);

							if ($Answer != "")
								$Answer .= "|-|";

							$Answer .= $sChoice;
							$iScore += $iWeightage;
						}
					}

					else
					{
						@list($iWeightage, $sChoice) = @explode("|-|", IO::strValue("Q_".$iQuestionId));

						$Answer  = $sChoice;
						$iScore += $iWeightage;
					}
				}
			}

			else if ($sQuestionType == "Open")
				$Answer = IO::strValue("Q_".$iQuestionId);

			if ($sQuestionType == "Matrix")
			{
				$sColumnHeadings = $objDb->getField($i, "column_headings");
				$sRowHeadings    = $objDb->getField($i, "row_headings");

				$sColumnChoices  = @explode("\r\n", $sColumnHeadings);
				$cCount          = count($sColumnChoices);

				$sRowChoices     = @explode("\r\n", $sRowHeadings);
				$rCount          = count($sRowChoices);

				if ($sAnswerType == "Radio")
				{
					for ($r = 0; $r < $rCount; $r ++)
					{
						if ($Answer != "")
							$Answer .= "|-|";

						$Answer .= IO::strValue("Q_".$iQuestionId."_".$r);
					}
				}

				else if ($sAnswerType == "Checkbox" || $sAnswerType == "Textbox")
				{
					for ($r = 0; $r < $rCount; $r ++)
					{
						if ($Answer != "")
							$Answer .= "|--|";

						for ($c = 0; $c < $cCount; $c ++)
						{
							if ($Answer != "" && $c > 0)
								$Answer .= "|-|";

							$Answer .= IO::strValue("Q_".$iQuestionId."_".$r."_".$c);
						}
					}
				}
			}


			$iAnswerId = getNextId("tbl_survey_answers");

			$sSQL  = "INSERT INTO tbl_survey_answers (id, feedback_id, question_id, answer) VALUES ('$iAnswerId', '$iId', '$iQuestionId', '$Answer')";
			$bFlag = $objDb2->execute($sSQL);

			if ($bFlag == false)
				break;
		}


		if ($bFlag == true)
		{
			$sSQL  = "UPDATE tbl_survey_feedback SET score='$iScore' WHERE id='$iId'";
			$bFlag = $objDb->execute($sSQL);
		}
	}

	if ($bFlag == true)
	{
		$objDb->query("COMMIT");

		$_SESSION['Referer'] = "";


		if ($_SESSION['CardId'] != "" && checkUserRights("board.php", "HR", "view"))
			redirect("hr/board.php", "SURVEY_FEEDBACK_SAVED");

		else
			redirect("./", "SURVEY_FEEDBACK_SAVED");
	}

	else
	{
		$objDb->query("ROLLBACK");

		redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");
	}

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>