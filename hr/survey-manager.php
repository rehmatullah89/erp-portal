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

	$Id     = IO::intValue("Id");
	$PostId = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$QuestionType   = IO::strValue("QuestionType");
		$McqType        = IO::strValue("McqType");
		$McqQuestion    = IO::strValue("McqQuestion");
		$McqChoices     = IO::strValue("McqChoices");
		$OpenType       = IO::strValue("OpenType");
		$OpenQuestion   = IO::strValue("OpenQuestion");
		$MatrixType     = IO::strValue("MatrixType");
		$MatrixQuestion = IO::strValue("MatrixQuestion");
		$MatrixColumns  = IO::strValue("MatrixColumns");
		$MatrixRows     = IO::strValue("MatrixRows");
		$DisplayOrder   = IO::strValue("DisplayOrder");
		$Validation     = IO::strValue("Validation");
		$Message        = IO::strValue("Message");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/hr/survey-manager.js"></script>
</head>

<body>

<div id="MainDiv">
  <div id="PageLeftBorder">
    <div id="PageRightBorder">

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

      <div id="PageContents">

<!--  Header Section Starts Here  -->
<?
	@include($sBaseDir."includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include($sBaseDir."includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
	    <div id="Body">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1><img src="images/h1/hr/survey-manager.jpg" width="235" height="20" vspace="10" alt="" title="" /></h1>

<?
	$sSQL = "SELECT title FROM tbl_surveys WHERE id='$Id' AND (user_id='{$_SESSION['UserId']}' OR '{$_SESSION['SurveyAdmin']}'='Y')";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		redirect($_SERVER['HTTP_REFERER'], "DB_ERROR");
?>
			    <div class="tblSheet" style="margin-bottom:4px; padding:1px;">
			      <h2 style="margin:0px;"><?= $objDb->getField(0, 0) ?></h2>
			    </div>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="hr/save-survey-question.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
			    <input type="hidden" name="SurveyId" value="<?= $Id ?>" />

				<h2>Add Survey Question</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="120">Question Type<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>

				    <td>
					  <select name="QuestionType" onchange="loadOptions(this.value, this.value, 'Mcq,Open,Matrix', 'Validation');">
					    <option value=""></option>
					    <option value="Mcq" <?= (($QuestionType == "Mcq") ? "selected" : "") ?>>Multiple Choice</option>
					    <option value="Open" <?= (($QuestionType == "Open") ? "selected" : "") ?>>Open Ended Text</option>
					    <option value="Matrix" <?= (($QuestionType == "Matrix") ? "selected" : "") ?>>Matrix Table</option>
					  </select>
				    </td>
				  </tr>
				</table>

				<div id="Mcq" style="display:<?= (($QuestionType == "Mcq") ? "block" : "none") ?>;">
				  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					<tr>
					  <td width="120">Answer Type<span class="mandatory">*</span></td>
					  <td width="20" align="center">:</td>

					  <td>
						<select name="McqType">
						  <option value=""></option>
						  <option value="Radio"<?= (($McqType == "Radio") ? "selected" : "") ?>>Radio Buttons (Single Selection)</option>
						  <option value="Checkbox"<?= (($McqType == "Checkbox") ? "selected" : "") ?>>Checkboxes (Multi Selection)</option>
						  <option value="Dropdown"<?= (($McqType == "Dropdown") ? "selected" : "") ?>>Dropdown List (Single Selection)</option>
						</select>
					  </td>
					</tr>

					<tr valign="top">
					  <td>Question Text<span class="mandatory">*</span></td>
					  <td align="center">:</td>
					  <td><textarea name="McqQuestion" style="width:395px; height:60px;"><?= $McqQuestion ?></textarea></td>
					</tr>

					<tr valign="top">
					  <td>Answer Choices<span class="mandatory">*</span><br /><small style="color:#888888;">(One Choice per line)<br /><br />Add weightage infront of each choice in braces e.g. (5)</small></td>
					  <td align="center">:</td>
					  <td><textarea name="McqChoices" style="width:395px; height:100px;"><?= $McqChoices ?></textarea></td>
					</tr>
				  </table>
				</div>

				<div id="Open" style="display:<?= (($QuestionType == "Open") ? "block" : "none") ?>;">
				  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					<tr>
					  <td width="120">Answer Type<span class="mandatory">*</span></td>
					  <td width="20" align="center">:</td>

					  <td>
						<select name="OpenType">
						  <option value=""></option>
						  <option value="Textbox" <?= (($OpenType == "Textbox") ? "selected" : "") ?>>Textbox (Single Line)</option>
						  <option value="Textarea" <?= (($OpenType == "Textarea") ? "selected" : "") ?>>Textarea (Multiple Lines)</option>
						</select>
					  </td>
					</tr>

					<tr valign="top">
					  <td>Question Text<span class="mandatory">*</span></td>
					  <td align="center">:</td>
					  <td><textarea name="OpenQuestion" style="width:395px; height:60px;"><?= $OpenQuestion ?></textarea></td>
					</tr>
				  </table>
				</div>

				<div id="Matrix" style="display:<?= (($QuestionType == "Matrix") ? "block" : "none") ?>;">
				  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					<tr>
					  <td width="120">Answer Type<span class="mandatory">*</span></td>
					  <td width="20" align="center">:</td>

					  <td>
						<select name="MatrixType">
						  <option value=""></option>
						  <option value="Radio" <?= (($MatrixType == "Radio") ? "selected" : "") ?>>Radio Buttons (Single Selection)</option>
						  <option value="Checkbox" <?= (($MatrixType == "Checkbox") ? "selected" : "") ?>>Checkboxes (Multi Selection)</option>
						  <option value="Textbox" <?= (($MatrixType == "Textbox") ? "selected" : "") ?>>Textbox (User Input)</option>
						</select>
					  </td>
					</tr>

					<tr valign="top">
					  <td>Question Text<span class="mandatory">*</span></td>
					  <td align="center">:</td>
					  <td><textarea name="MatrixQuestion" style="width:395px; height:60px;"><?= $MatrixQuestion ?></textarea></td>
					</tr>

					<tr valign="top">
					  <td>Column Choices<span class="mandatory">*</span><br /><small style="color:#888888;">(One Choice per line)</small></td>
					  <td align="center">:</td>
					  <td><textarea name="MatrixColumns" style="width:395px; height:100px;"><?= $MatrixColumns ?></textarea></td>
					</tr>

					<tr valign="top">
					  <td>Row Choices<span class="mandatory">*</span><br /><small style="color:#888888;">(One Choice per line)</small></td>
					  <td align="center">:</td>
					  <td><textarea name="MatrixRows" style="width:395px; height:100px;"><?= $MatrixRows ?></textarea></td>
					</tr>
				  </table>
				</div>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
				    <td width="120">Display Order<span class="mandatory">*</span></td>
				    <td width="20" align="center">:</td>

				    <td>
					  <select name="DisplayOrder">
<?
		$sSQL = "SELECT display_order FROM tbl_survey_questions WHERE survey_id='$Id' ORDER BY display_order";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );
		$iOrder = 0;

		for ($i = 0; $i < $iCount; $i ++)
		{
			$iOrder = $objDb->getField($i, 0);
?>
		                <option value="<?= $iOrder ?>"<?= (($DisplayOrder == $iOrder) ? ' selected' : '') ?>>Q - <?= ($i + 1) ?></option>
<?
		}
?>
					    <option value="<?= ($iOrder + 1) ?>"<?= (($DisplayOrder == ($iOrder + 1) || $DisplayOrder == "") ? ' selected' : '') ?>>Q - <?= ($i + 1) ?></option>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Javascript Validation</td>
				    <td align="center">:</td>

				    <td>
					  <select name="Validation" id="Validation" onchange="setMessage(this.value, 'Message');">
					    <option value="" <?= (($Validation == "") ? "selected" : "") ?>>Not Required</option>
<?
		if ($QuestionType == "Mcq")
		{
?>
		                <option value="S" <?= (($Validation == "S") ? "selected" : "") ?>>Must Select</option>
<?
		}

		else if ($QuestionType == "Open")
		{
?>
					    <option value="N" <?= (($Validation == "N") ? "selected" : "") ?>>Numeric</option>
					    <option value="A" <?= (($Validation == "A") ? "selected" : "") ?>>Alpha Numeric</option>
					    <option value="E" <?= (($Validation == "E") ? "selected" : "") ?>>Email Address</option>
<?
		}

		else if ($QuestionType == "Matrix")
		{
?>
					    <option value="S" <?= (($Validation == "S") ? "selected" : "") ?>>Must Select</option>
					    <option value="N" <?= (($Validation == "N") ? "selected" : "") ?>>Numeric</option>
					    <option value="A" <?= (($Validation == "A") ? "selected" : "") ?>>Alpha Numeric</option>
					    <option value="E" <?= (($Validation == "E") ? "selected" : "") ?>>Email Address</option>
<?
		}
?>
					  </select>
				    </td>
				  </tr>

				  <tr>
				    <td>Validation Message*</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Message" id="Message" value="<?= $Message ?>" size="30" style="width:395px;" class="textbox" <?= (($Validation == "") ? 'disabled="true"' : '') ?> /></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
				  <input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= SITE_URL ?>hr/surveys.php';" />
				</div>
			    </form>

			    <hr />
<?
	}
?>

			    <div class="tblSheet">
<?
	$sSQL = "SELECT * FROM tbl_survey_questions WHERE survey_id='$Id' ORDER BY display_order ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	if ($iCount > 0)
	{
?>
			      <h2>Survey Form</h2>
<?
	}

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId           = $objDb->getField($i, "id");
		$sQuestionType = $objDb->getField($i, "question_type");
		$sAnswerType   = $objDb->getField($i, "answer_type");
		$sQuestion     = $objDb->getField($i, "question");
		$iDisplayOrder = $objDb->getField($i, "display_order");
		$sValidation   = $objDb->getField($i, "validation");
		$sMessage      = $objDb->getField($i, "message");
?>
				  <table border="0" cellpadding="0" cellspacing="0" width="98%" align="center">
					<tr valign="top">
					  <td width="88%">
				        <div id="Q<?= $iId ?>">
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
                              <td width="22"><input type="radio" name="Q_<?= $iId ?>" value="<?= $sChoices[$j] ?>" /></td>
<?
					}

					else if ($sAnswerType == "Checkbox")
					{
?>
                              <td width="22"><input type="checkbox" id="Q_<?= $iId ?>" name="Q_<?= $iId ?>[]" value="<?= $sChoices[$j] ?>" /></td>
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
                          <select name="Q_<?= $iId ?>">
                            <option value=""></option>
<?
				for ($j = 0; $j < $jCount; $j ++)
				{
?>
                            <option value="<?= $sChoices[$j] ?>"><?= $sChoices[$j] ?></option>
<?
				}
?>
                          </select>
<?
			}
		}

		else if ($sQuestionType == "Open")
		{
			if ($sAnswerType == "Textbox")
			{
?>
                          <input type="text" name="Q_<?= $iId ?>" value="" class="textbox" />
<?
			}

			else if ($sAnswerType == "Textarea")
			{
?>
                          <textarea name="Q_<?= $iId ?>"></textarea>
<?
			}
		}

		else if ($sQuestionType == "Matrix")
		{
			$sColumnHeadings = $objDb->getField($i, "column_headings");
			$sRowHeadings    = $objDb->getField($i, "row_headings");

			$sColumnChoices  = @explode("\r\n", $sColumnHeadings);
			$cCount          = count($sColumnChoices);

			$sRowChoices     = @explode("\r\n", $sRowHeadings);
			$rCount          = count($sRowChoices);
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
							$sTdValue = ('<input type="radio" name="Q_'.$iId.'_'.$r.'" value="'.$sColumnChoices[$c].'" />');

						else if ($sAnswerType == "Checkbox")
							$sTdValue = ('<input type="checkbox" name="Q_'.$iId.'_'.$r.'_'.$c.'" value="'.$sColumnChoices[$c].'" />');

						else if ($sAnswerType == "Textbox")
							$sTdValue = ('<input type="text" name="Q_'.$iId.'_'.$r.'_'.$c.'" value="" size="6" />');
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
?>
                        </div>
                      </td>

                      <td width="12%" align="right">
<?
		if ($i > 0 && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="hr/update-survey-question-order.php?SurveyId=<?= $Id ?>&QuestionId=<?= $iId ?>&CurOrder=<?= $iDisplayOrder ?>&NewOrder=<?= ($iDisplayOrder - 1) ?>"><img src="images/icons/up.gif" width="16" height="16" alt="Up" title="Up" border="0" align="absmiddle"></a>
						&nbsp;
<?
		}

		if ($i < ($iCount - 1) && $sUserRights['Edit'] == "Y")
		{
?>
						<a href="hr/update-survey-question-order.php?SurveyId=<?= $Id ?>&QuestionId=<?= $iId ?>&CurOrder=<?= $iDisplayOrder ?>&NewOrder=<?= ($iDisplayOrder + 1) ?>"><img src="images/icons/down.gif" width="16" height="16" alt="Down" title="Down" border="0" align="absmiddle"></a>
						&nbsp;
<?
		}

		if ($sUserRights['Edit'] == "Y")
		{
?>
						<a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
						&nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
					    <a href="hr/delete-survey-question.php?SurveyId=<?= $Id ?>&QuestionId=<?= $iId ?>&DisplayOrder=<?= $iDisplayOrder ?>"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" border="0" align="absmiddle" onclick="return confirm('Are you SURE you want to DELETE this Question?');"></a>
<?
		}
?>
                      </td>
                    </tr>
                  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">
					  <br />

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="120">Question Type<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="QuestionType" onchange="loadOptions(this.value, (this.value + '<?= $iId ?>'), 'Mcq<?= $iId ?>,Open<?= $iId ?>,Matrix<?= $iId ?>', 'Validation<?= $iId ?>');">
							  <option value="Mcq" <?= (($sQuestionType == "Mcq") ? "selected" : "") ?>>Multiple Choice</option>
							  <option value="Open" <?= (($sQuestionType == "Open") ? "selected" : "") ?>>Open Ended Text</option>
							  <option value="Matrix" <?= (($sQuestionType == "Matrix") ? "selected" : "") ?>>Matrix Table</option>
						    </select>
						  </td>
					    </tr>
					  </table>

					  <div id="Mcq<?= $iId ?>" style="display:<?= (($sQuestionType == "Mcq") ? "block" : "none") ?>;">
					    <table border="0" cellpadding="3" cellspacing="0" width="100%">
						  <tr>
						    <td width="120">Answer Type<span class="mandatory">*</span></td>
						    <td width="20" align="center">:</td>

						    <td>
							  <select name="McqType">
							    <option value="Radio"<?= (($sAnswerType == "Radio") ? "selected" : "") ?>>Radio Buttons (Single Selection)</option>
							    <option value="Checkbox"<?= (($sAnswerType == "Checkbox") ? "selected" : "") ?>>Checkboxes (Multi Selection)</option>
							    <option value="Dropdown"<?= (($sAnswerType == "Dropdown") ? "selected" : "") ?>>Dropdown List (Single Selection)</option>
							  </select>
						    </td>
						  </tr>

						  <tr valign="top">
						    <td>Question Text<span class="mandatory">*</span></td>
						    <td align="center">:</td>
						    <td><textarea name="McqQuestion" style="width:395px; height:60px;"><?= $sQuestion ?></textarea></td>
						  </tr>

						  <tr valign="top">
						    <td>Answer Choices<span class="mandatory">*</span><br /><small style="color:#888888;"><br /><small style="color:#888888;">(One Choice per line)<br /><br />Add weightage infront of each choice in braces e.g. (5)</small></td>
						    <td align="center">:</td>
						    <td><textarea name="McqChoices" style="width:395px; height:100px;"><?= $sMcqChoices ?></textarea></td>
						  </tr>
					    </table>
					  </div>

					  <div id="Open<?= $iId ?>" style="display:<?= (($sQuestionType == "Open") ? "block" : "none") ?>;">
					    <table border="0" cellpadding="3" cellspacing="0" width="100%">
						  <tr>
						    <td width="120">Answer Type<span class="mandatory">*</span></td>
						    <td width="20" align="center">:</td>

						    <td>
							  <select name="OpenType">
							    <option value="Textbox" <?= (($sAnswerType == "Textbox") ? "selected" : "") ?>>Textbox (Single Line)</option>
							    <option value="Textarea" <?= (($sAnswerType == "Textarea") ? "selected" : "") ?>>Textarea (Multiple Lines)</option>
							  </select>
 						    </td>
						  </tr>

						  <tr valign="top">
						    <td>Question Text<span class="mandatory">*</span></td>
						    <td align="center">:</td>
						    <td><textarea name="OpenQuestion" style="width:395px; height:60px;"><?= $sQuestion ?></textarea></td>
						  </tr>
					    </table>
					  </div>

					  <div id="Matrix<?= $iId ?>" style="display:<?= (($sQuestionType == "Matrix") ? "block" : "none") ?>;">
					    <table border="0" cellpadding="3" cellspacing="0" width="100%">
						  <tr>
						    <td width="120">Answer Type<span class="mandatory">*</span></td>
						    <td width="20" align="center">:</td>

						    <td>
							  <select name="MatrixType">
							    <option value="Radio" <?= (($sAnswerType == "Radio") ? "selected" : "") ?>>Radio Buttons (Single Selection)</option>
							    <option value="Checkbox" <?= (($sAnswerType == "Checkbox") ? "selected" : "") ?>>Checkboxes (Multi Selection)</option>
							    <option value="Textbox" <?= (($sAnswerType == "Textbox") ? "selected" : "") ?>>Textbox (User Input)</option>
							  </select>
						    </td>
						  </tr>

						  <tr valign="top">
						    <td>Question Text<span class="mandatory">*</span></td>
						    <td align="center">:</td>
						    <td><textarea name="MatrixQuestion" style="width:395px; height:60px;"><?= $sQuestion ?></textarea></td>
						  </tr>

						  <tr valign="top">
						    <td>Column Choices<span class="mandatory">*</span><br /><small style="color:#888888;">(One Choice per line)</small></td>
						    <td align="center">:</td>
						    <td><textarea name="MatrixColumns" style="width:395px; height:100px;"><?= $sColumnHeadings ?></textarea></td>
						  </tr>

						  <tr valign="top">
						    <td>Row Choices<span class="mandatory">*</span><br /><small style="color:#888888;">(One Choice per line)</small></td>
						    <td align="center">:</td>
						    <td><textarea name="MatrixRows" style="width:395px; height:100px;"><?= $sRowHeadings ?></textarea></td>
						  </tr>
					    </table>
					  </div>

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="120">Javascript Validation</td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Validation" id="Validation<?= $iId ?>" onchange="setMessage(this.value, 'Message<?= $iId ?>');">
							  <option value="" <?= (($sValidation == "") ? "selected" : "") ?>>Not Required</option>
<?
		if ($sQuestionType == "Mcq")
		{
?>
		                	  <option value="S" <?= (($sValidation == "S") ? "selected" : "") ?>>Must Select</option>
<?
		}

		else if ($sQuestionType == "Open")
		{
?>
							  <option value="N" <?= (($sValidation == "N") ? "selected" : "") ?>>Numeric</option>
							  <option value="A" <?= (($sValidation == "A") ? "selected" : "") ?>>Alpha Numeric</option>
							  <option value="E" <?= (($sValidation == "E") ? "selected" : "") ?>>Email Address</option>
<?
		}

		else if ($sQuestionType == "Matrix")
		{
?>
							  <option value="S" <?= (($sValidation == "S") ? "selected" : "") ?>>Must Select</option>
							  <option value="N" <?= (($sValidation == "N") ? "selected" : "") ?>>Numeric</option>
							  <option value="A" <?= (($sValidation == "A") ? "selected" : "") ?>>Alpha Numeric</option>
							  <option value="E" <?= (($sValidation == "E") ? "selected" : "") ?>>Email Address</option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Validation Message*</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Message" id="Message<?= $iId ?>" value="<?= $sMessage ?>" size="30" style="width:395px;" class="textbox" <?= (($sValidation == "") ? 'disabled="true"' : '') ?> /></td>
					    </tr>

					    <tr>
					      <td></td>
					      <td></td>
						  <td>
						    <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
						  </td>
					    </tr>
					  </table>
					  </form>

				    </div>
				  </div>

				  <div id="Msg<?= $iId ?>" class="msgOk" style="display:none;"></div>

<?
		if ($i < ($iCount - 1))
		{
?>
                  <hr style="margin-left:10px; margin-right:10px;" />

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

	else
	{
?>
                  <br />
				  <div class="buttonsBar"><input type="button" value="" class="btnBack" title="Back" onclick="document.location='<?= SITE_URL ?>hr/surveys.php';" /></div>
<?
	}
?>
			    </div>
			  </td>
			</tr>
		  </table>

<?
	@include($sBaseDir."includes/my-profile.php");
?>
        </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include($sBaseDir."includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

      </div>
    </div>
  </div>
</div>

<!--  Bottom Bar Section Starts Here  -->
<?
	@include($sBaseDir."includes/bottom-bar.php");
?>
<!--  Bottom Bar Section Ends Here  -->

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>