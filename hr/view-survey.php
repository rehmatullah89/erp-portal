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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_surveys WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sTitle     = $objDb->getField(0, 'title');
		$sPurpose   = $objDb->getField(0, 'purpose');
		$iEmployees = @explode(",", $objDb->getField(0, 'users'));
		$sFromDate  = $objDb->getField(0, "from_date");
		$sToDate    = $objDb->getField(0, "to_date");

		$sEmployees     = "";
		$sEmployeesList = getList("tbl_users", "id", "name", "(email LIKE '%@apparelco.com' OR email LIKE '%@3-tree.com' OR email LIKE '%@lulusar.com') AND status='A'");

		for ($i = 0; $i < count($iEmployees); $i ++)
			$sEmployees .= ("- ".$sEmployeesList[$iEmployees[$i]]."<br />");
	}
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
	  <h2>Survey Details</h2>

	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff">
		  <td width="100%">

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr valign="top">
			    <td width="65">Title</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sTitle ?></td>
			  </tr>

			  <tr valign="top">
			    <td>Purpose</td>
			    <td align="center">:</td>
			    <td><?= nl2br($sPurpose) ?></td>
			  </tr>

			  <tr valign="top">
			    <td>Employees</td>
			    <td align="center">:</td>
			    <td><?= $sEmployees ?></td>
			  </tr>

			  <tr>
			    <td>From Date</td>
			    <td align="center">:</td>
			    <td><?= formatDate($sFromDate) ?></td>
			  </tr>

			  <tr>
			    <td>To Date</td>
			    <td align="center">:</td>
			    <td><?= formatDate($sToDate) ?></td>
			  </tr>
		    </table>

		    <br />
		    <h2>Survey Form</h2>

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
		    	  <td width="22"><input type="radio" name="Q_<?= $iId ?>" value="<?= $sChoice ?>" /></td>
<?
					}

					else if ($sAnswerType == "Checkbox")
					{
?>
				  <td width="22"><input type="checkbox" id="Q_<?= $iId ?>" name="Q_<?= $iId ?>[]" value="<?= $sChoice ?>" /></td>
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
	$objDbGlobal->close( );

	@ob_end_flush( );
?>