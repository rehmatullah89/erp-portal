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
	$QuestionType = IO::strValue("QuestionType");
	$Validation   = IO::strValue("Validation");
	$Message      = IO::strValue("Message");

	if ($QuestionType == "Mcq")
	{
		$AnswerType = IO::strValue("McqType");
		$Question   = IO::strValue("McqQuestion");
		$McqChoices = str_replace("\n", "\r\n", IO::strValue("McqChoices"));
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
		$ColumnHeadings = str_replace("\n", "\r\n", IO::strValue("MatrixColumns"));
		$RowHeadings    = str_replace("\n", "\r\n", IO::strValue("MatrixRows"));
	}

	$sError    = "";

	$sSQL = "SELECT id FROM tbl_survey_questions WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($Id == 0 || $objDb->getCount( ) != 1)
	{
		print "ERROR|-|Invalid Survey Question ID. Please select the proper Survey to Edit.\n";
		exit( );
	}

	if ($QuestionType == "Mcq")
	{
		if ($Question == "")
			$sError .= "- Invalid Question\n";

		if ($McqChoices == "")
			$sError .= "- Invalid MCQ Choices\n";
	}

	if ($QuestionType == "Open")
	{
		if ($Question == "")
			$sError .= "- Invalid Question\n";
	}

	if ($QuestionType == "Matrix")
	{
		if ($Question == "")
			$sError .= "- Invalid Question\n";

		if ($ColumnHeadings == "")
			$sError .= "- Invalid Column Choices\n";

		if ($RowHeadings == "")
			$sError .= "- Invalid Row Choices\n";
	}

	if ($Validation != "")
	{
		if ($Message == "")
			$sError .= "- Invalid Validation Message\n";
	}

	if ($sError != "")
	{
		print "ERROR|-|Please provide the values of following field(s):\n\n$sError";
		exit( );
	}


	$sSQL = "UPDATE tbl_survey_questions SET question_type='$QuestionType', answer_type='$AnswerType', question='$Question', mcq_choices='$McqChoices', column_headings='$ColumnHeadings', row_headings='$RowHeadings', validation='$Validation', message='$Message' WHERE id='$Id'";

	if ($objDb->execute($sSQL) == true)
	{
		$sHtml = ('<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
			  <td><div style="padding-bottom:5px;"><b style="color:#555555;">'.nl2br($Question).'</b></div></td>
			  <td width="80" align="right"><b>Weightage</b></td>
			</tr>
		  </table>');

		if ($QuestionType == "Mcq")
		{
			$sChoices = @explode("\r\n", $McqChoices);
			$iCount   = count($sChoices);

			if ($AnswerType == "Radio" || $AnswerType == "Checkbox")
			{
				$sHtml .= '<table border="0" cellpadding="2" cellspacing="0" width="100%">';

				for ($i = 0; $i < $iCount; $i ++)
				{
					if (@strrpos($sChoices[$i], '(') === FALSE)
					{
						$sChoice    = $sChoices[$i];
						$iWeightage = 0;
					}

					else
					{
						$sChoice    = substr($sChoices[$i], 0, (@strrpos($sChoices[$i], '(') - 1));
						$iWeightage = (int)substr($sChoices[$i], (@strrpos($sChoices[$i], '(') + 1), -1);
					}


                    $sHtml .= '<tr>';

					if ($AnswerType == "Radio")
                    	$sHtml .= ('<td width="22"><input type="radio" name="Q_'.$Id.'" value="'.$sChoices[$i].'" /></td>');

					else if ($AnswerType == "Checkbox")
						$sHtml .= ('<td width="22"><input type="checkbox" id="Q_'.$Id.'" name="Q_'.$Id.'[]" value="'.$sChoices[$i].'" /></td>');

					$sHtml .= ('<td>'.$sChoice.'</td>');
					$sHtml .= ('<td width="80" align="right">'.$iWeightage.'</td>');
					$sHtml .= '</tr>';
				}

				$sHtml .= '</table>';
			}

			else if ($AnswerType == "Dropdown")
			{
				$sHtml .= ('<select name="Q_'.$Id.'">');
				$sHtml .= '<option value=""></option>';

				for ($i = 0; $i < $iCount; $i ++)
					$sHtml .= ('<option value="'.$sChoices[$i].'">'.$sChoices[$i].'</option>');

				$sHtml .= '</select>';
			}
		}

		else if ($QuestionType == "Open")
		{
			if ($AnswerType == "Textbox")
				$sHtml .= ('<input type="text" name="Q_'.$Id.'" value="" class="textbox" />');

			else if ($AnswerType == "Textarea")
				$sHtml .= ('<textarea name="Q_'.$Id.'"></textarea>');
		}

		else if ($QuestionType == "Matrix")
		{
			$sColumnChoices = @explode("\r\n", $ColumnHeadings);
			$cCount         = count($sColumnChoices);

			$sRowChoices    = @explode("\r\n", $RowHeadings);
			$rCount         = count($sRowChoices);

			$sHtml .= '<table border="0" cellpadding="2" cellspacing="0" width="100%">';

			for ($r = -1; $r < $rCount; $r ++)
			{
				$sHtml .= '<tr>';

				for ($c = -1; $c < $cCount; $c ++)
				{
					$sTdValue = "";

					if ($r == -1 && $c == -1)
						$sTdValue = "&nbsp;";

					else if ($r == -1 && $c >= 0)
						$sTdValue = $sColumnChoices[$c];


					else if ($r >= 0 && $c == -1)
						$sTdValue = $sRowChoices[$r];

					else
					{
						if ($AnswerType == "Radio")
							$sTdValue = ('<input type="radio" name="Q_'.$Id.'_'.$r.'" value="'.$sColumnChoices[$c].'" />');

						else if ($AnswerType == "Checkbox")
							$sTdValue = ('<input type="checkbox" name="Q_'.$Id.'_'.$r.'_'.$c.'" value="'.$sColumnChoices[$c].'" />');

						else if ($AnswerType == "Textbox")
							$sTdValue = ('<input type="text" name="Q_'.$Id.'_'.$r.'_'.$c.'" value="" size="6" />');
					}

					$sHtml .= ('<td>'.$sTdValue.'</td>');
				}

				$sHtml .= '</tr>';
			}

			$sHtml .= '</table>';
		}

		print "OK|-|$Id|-|<div>The selected Survey Question has been Updated successfully.</div>|-|$sHtml";
	}

	else
		print "ERROR|-|A Database ERROR occured while processing your request.  \n\nPlease re-load your webpage and try again.";

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>