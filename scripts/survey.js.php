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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
?>

function validateForm( )
{
	var objFV = new FormValidator("frmSurvey");

<?
	$sSQL = "SELECT * FROM tbl_survey_questions WHERE survey_id='$Id' ORDER BY display_order ASC";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iId           = $objDb->getField($i, "id");
		$sQuestionType = $objDb->getField($i, "question_type");
		$sAnswerType   = $objDb->getField($i, "answer_type");
		$sValidation   = $objDb->getField($i, "validation");
		$sMessage      = $objDb->getField($i, "message");

		if ($sValidation != "")
		{
			$sChecks = "B";

			switch ($sValidation)
			{
				case "N" : $sChecks .= ",F,S";
						   break;

				case "E" : $sChecks .= ",E";
						   break;
			}

			if ($sQuestionType == "Mcq")
			{
				if ($sAnswerType == "Radio")
				{
?>
	if (objFV.selectedValue("Q_<?= $iId ?>") == "")
	{
		alert("<?= $sMessage ?>");

		return false;
	}

<?
				}

				else if ($sAnswerType == "Checkbox")
				{
					$sMcqChoices = $objDb->getField($i, "mcq_choices");

					$sChoices    = @explode("\r\n", $sMcqChoices);
					$jCount      = count($sChoices);

					if ($jCount > 1)
					{
?>
	var bFlag  = false;
	var iCount = $('Q_<?= $iId ?>').length;

	for (var i = 0; i < iCount; i ++)
	{
		if (document.frmFeedback.Q_<?= $iId ?>[i].checked == true)
		{
			bFlag = true;
			break;
		}
	}

	if (bFlag == false)
	{
		alert("<?= $sMessage ?>");

		return false;
	}
<?
					}

					else
					{
?>
	if (document.frmFeedback.Q_<?= $iId ?>.checked == false)
	{
		alert("<?= $sMessage ?>");

		return false;
	}
<?
					}
				}

				else if ($sAnswerType == "Dropdown")
				{
?>
	if (!objFV.validate("Q_<?= $iId ?>", "<?= $sChecks ?>", "<?= $sMessage ?>"))
		return false;

<?
				}
			}

			else if ($sQuestionType == "Open")
			{
?>
	if (!objFV.validate("Q_<?= $iId ?>", "<?= $sChecks ?>", "<?= $sMessage ?>"))
		return false;

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
				{
					for ($r = 0; $r < $rCount; $r ++)
					{
?>
	if (objFV.selectedValue("Q_<?= $iId ?>_<?= $r ?>") == "")
	{
		alert("(Row#<?= ($r + 1)?>) <?= $sMessage ?>");

		return false;
	}

<?
					}
				}

				else if ($sAnswerType == "Checkbox")
				{
					for ($r = 0; $r < $rCount; $r ++)
					{
						for ($c = 0; $c < $cCount; $c ++)
						{
?>
	var bFlag  = false;

	if (document.frmFeedback.Q_<?= $iId ?>_<?= $r ?>_<?= $c ?>.checked == true)
		bFlag = true;
<?
						}
?>
	if (bFlag == false)
	{
		alert("(Row#<?= ($r + 1)?>) <?= $sMessage ?>");

		return false;
	}
<?
					}
				}

				else if ($sAnswerType == "Textbox")
				{
					for ($r = 0; $r < $rCount; $r ++)
					{
						for ($c = 0; $c < $cCount; $c ++)
						{
?>
	if (!objFV.validate("Q_<?= $iId ?>_<?= $r ?>_<?= $c ?>", "<?= $sChecks ?>", "<?= $sMessage ?>"))
		return false;

<?
						}
					}

				}
			}
		}
	}
?>

	return true;
}


function disableLinks( )
{
	var objLinks = document.links;

	for(var i = 0; i < objLinks.length; i ++)
			objLinks[i].onclick = function( ) { return false; };
}

document.observe('dom:loaded', function( )
{
	disableLinks( );
});
<?
	@ob_end_flush( );
?>