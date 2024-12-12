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

	if ($_POST['PostId'] != "")
		$_REQUEST = @unserialize($_SESSION[$_POST['PostId']]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/survey.js.php?Id=<?= $Id ?>"></script>
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
			  <td width="585">
			    <h1><img src="images/h1/survey.jpg" width="203" height="20" vspace="10" alt="" title="" /></h1>

			    <form name="frmSurvey" id="frmSurvey" method="post" action="save-survey-feedback.php" class="frmOutline" onsubmit="$('BtnSubmit').disable( );">
			    <input type="hidden" name="Id" value="<?= $Id ?>" />

<?
	$sSQL = "SELECT title FROM tbl_surveys WHERE id='$Id' AND status='A' AND (users='{$_SESSION['UserId']}' OR users LIKE '%,{$_SESSION['UserId']}' OR users LIKE '{$_SESSION['UserId']},%' OR users LIKE '%,{$_SESSION['UserId']},%') AND id NOT IN (SELECT survey_id FROM tbl_survey_feedback WHERE user_id='{$_SESSION['UserId']}') AND (CURDATE( ) BETWEEN from_date AND to_date)";
	$objDb->query($sSQL);
?>
			    <h2><?= $objDb->getField(0, 0) ?></h2>

			    <div style="padding:10px;">

<?
	$sSQL = "SELECT * FROM tbl_survey_questions WHERE survey_id='$Id' ORDER BY display_order ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId           = $objDb->getField($i, "id");
		$sQuestionType = $objDb->getField($i, "question_type");
		$sAnswerType   = $objDb->getField($i, "answer_type");
		$sQuestion     = $objDb->getField($i, "question");
?>

				  <div style="padding-bottom:5px;"><b style="color:#555555;"><?= nl2br($sQuestion) ?></b></div>

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
                      <td width="22"><input type="radio" name="Q_<?= $iId ?>" value="<?= ($iWeightage.'|-|'.$sChoice) ?>" /></td>
<?
					}

					else if ($sAnswerType == "Checkbox")
					{
?>
                      <td width="22"><input type="checkbox" id="Q_<?= $iId ?>" name="Q_<?= $iId ?><?= (($jCount > 1) ? '[]' : '') ?>" value="<?= ($iWeightage.'|-|'.$sChoice) ?>" /></td>
<?
					}
?>
					  <td><?= $sChoice ?></td>
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
					<option value="<?= ($iWeightage.'|-|'.$sChoice) ?>"><?= $sChoice ?></option>
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

                  <br />
				  <div class="buttonsBar"><input type="button" value="" class="btnCancel" title="Cancel" onclick="document.location='<?= SITE_URL ?>hr/';" /></div>
<?
	}

	else
	{
?>
				</div>

			    <div class="buttonsBar"><input type="submit" id="BtnSubmit" value="" class="btnSubmit" onclick="return validateForm( );" /></div>
<?
	}
?>
			    </form>
			  </td>

			  <td width="5"></td>

			  <td>
<?
	@include($sBaseDir."includes/sign-in.php");
?>

			    <div style="height:5px;"></div>

<?
	@include($sBaseDir."includes/custom-feeds.php");
?>
			  </td>
			</tr>
		  </table>
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