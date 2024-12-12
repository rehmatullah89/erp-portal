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

	$Id       = IO::intValue('Id');
	$SurveyId = IO::intValue('SurveyId');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:544px; height:544px;">
	  <h2>Survey Feedback</h2>

	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff">
		  <td width="100%">
		    <div style="padding:10px;">
<?
	$sSQL = "SELECT * FROM tbl_survey_questions WHERE survey_id='$SurveyId' ORDER BY display_order ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId           = $objDb->getField($i, "id");
		$sQuestionType = $objDb->getField($i, "question_type");
		$sAnswerType   = $objDb->getField($i, "answer_type");
		$sQuestion     = $objDb->getField($i, "question");


		$sSQL = "SELECT answer FROM tbl_survey_answers WHERE feedback_id='$Id' AND question_id='$iId'";
		$objDb2->query($sSQL);

		$sAnswer = $objDb2->getField(0, 0);
?>
			  <table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
				  <td><div style="padding-bottom:5px;"><b style="color:#555555;"><?= nl2br($sQuestion) ?></b></div></td>
				  <td width="80" align="right"><b>Weightage</b></td>
				</tr>
			  </table>

<?
		if ($sQuestionType == "Mcq")
		{
			$sMcqChoices = $objDb->getField($i, "mcq_choices");

			$sChoices    = @explode("\r\n", $sMcqChoices);
			$jCount      = count($sChoices);

			if ($sAnswerType == "Radio" || $sAnswerType == "Checkbox")
			{
				$sAnswers = @explode("|-|", $sAnswer);
?>
			  <table border="0" cellpadding="2" cellspacing="0" width="100%">
<?
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
?>
		  	    <tr>
<?
					if ($sAnswerType == "Radio")
					{
?>
		    	  <td width="22"><input type="radio" <?= (($sChoice == $sAnswer) ? "checked" : "") ?> disabled /></td>
<?
					}

					else if ($sAnswerType == "Checkbox")
					{
?>
				  <td width="22"><input type="checkbox" <?= ((@in_array($sChoice, $sAnswers)) ? "checked" : "") ?> disabled /></td>
<?
					}
?>
				  <td><?= $sChoice ?></td>
				  <td width="80" align="right"><?= $iWeightage ?></td>
		  	    </tr>
<?
				}
?>
			  </table>
<?
			}

			else if ($sAnswerType == "Dropdown")
			{
?>
			  <select disabled>
			    <option value=""></option>
<?
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
?>
		  	    <option<?= (($sChoice == $sAnswer) ? " selected" : "") ?>><?= $sChoice ?></option>
<?
				}
?>
			  </select>
<?
			}
		}

		else if ($sQuestionType == "Open")
		{
?>
			  <div style="color:#333333; border:solid 1px #cac8bb; background:#f5f4eb; padding:3px;"><?= nl2br($sAnswer) ?>&nbsp;</div>
<?
		}

		else if ($sQuestionType == "Matrix")
		{
			$sColumnHeadings = $objDb->getField($i, "column_headings");
			$sRowHeadings    = $objDb->getField($i, "row_headings");

			$sColumnChoices  = @explode("\r\n", $sColumnHeadings);
			$cCount          = count($sColumnChoices);

			$sRowChoices     = @explode("\r\n", $sRowHeadings);
			$rCount          = count($sRowChoices);


			if ($sAnswerType == "Radio")
				$sAnswers = @explode("|-|", $sAnswer);

			else
			{
				$sRows    = @explode("|--|", $sAnswer);
				$sAnswers = array( );

				for ($j = 0; $j < count($sRows); $j ++)
					$sAnswers[$j] = @explode("|-|", $sRows[$j]);
			}
?>

			  <table border="0" cellpadding="2" cellspacing="0" width="100%">
<?
			for ($r = -1; $r < $rCount; $r ++)
			{
?>
		  	    <tr>
<?
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
						if ($sAnswerType == "Radio")
							$sTdValue = ('<input type="radio"'.(($sColumnChoices[$c] == $sAnswers[$r]) ? ' checked' : '').' disabled />');

						else if ($sAnswerType == "Checkbox")
							$sTdValue = ('<input type="checkbox"'.(($sColumnChoices[$c] == $sAnswers[$r]) ? ' checked' : '').' disabled />');

						else if ($sAnswerType == "Textbox")
							$sTdValue = ('<div style="color:#333333; border:solid 1px #cac8bb; background:#f5f4eb; padding:3px;">'.$sAnswers[$r][$c].'&nbsp;</div>');
					}
?>
		    	  <td><?= $sTdValue ?></td>
<?
				}
?>
		  	    </tr>
<?
			}
?>
			  </table>

<?
		}

		if ($i < ($iCount - 1))
		{
?>
	    	  <hr />

<?
		}
	}

	if ($iCount == 0)
	{
?>
			  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr>
				  <td class="noRecord">No Survey Question Found!</td>
			    </tr>
			  </table>
<?
	}
?>
      	    </div>

		  </td>
	    </tr>
	  </table>

	  <br style="line-height:2px;" />
	</div>
<!--  Body Section Ends Here  -->


  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>