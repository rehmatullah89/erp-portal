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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Category = IO::intValue("Category");
	$Question = IO::strValue("Question");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Category     = IO::intValue("Category");
		$Question     = IO::strValue("Question");
		$QuestionType = IO::intValue("QuestionType");
		$NoOfOptions  = IO::intValue("NoOfOptions");
		$Options      = IO::getArray("Options");
		$Weightage    = IO::getArray("Weightage");
	}


	$sCategoriesList = getList("tbl_production_categories", "id", "title");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/production-questions.js"></script>
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
			    <h1>Production Questions</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="crc/save-production-question.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Production Question</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="115">Category<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Category">
					    <option value=""></option>
<?
		foreach ($sCategoriesList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Type) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Question<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Question" value="<?= $Question ?>" maxlength="250" size="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Question Type<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select id="QuestionType" name="QuestionType" onchange="updateOptions('');">
					    <option value=""></option>
					    <option value="1"<?= (($QuestionType == 1) ? " selected" : "") ?>>Single Selection</option>
					    <option value="2"<?= (($QuestionType == 2) ? " selected" : "") ?>>Multiple Selection</option>
					  </select>
					</td>
				  </tr>

				  <tr> <td>Number of options<span class="mandatory">*</span></td>
				    <td align="center">:</td>

				    <td>
				      <select id="NoOfOptions" name="NoOfOptions" onchange="updateOptions('');">
				        <option value=""></option>
				        <option value="2"<?= (($NoOfOptions == 2) ? " selected" : "") ?>>2</option>
				        <option value="3"<?= (($NoOfOptions == 3) ? " selected" : "") ?>>3</option>
				        <option value="4"<?= (($NoOfOptions == 4) ? " selected" : "") ?>>4</option>
				      </select>
				    </td>
				  </tr>

				  <tr>
				    <td valign="top">Grading Options<span class="mandatory">*</span></td>
				    <td valign="top" align="center">:</td>

				    <td>
<?
		for ($i = 0; $i < 4; $i ++)
		{
?>
					  <div id="Option<?= $i ?>" style="display:<?= (($i < $NoOfOptions) ? 'block' : 'none') ?>;">
					    <table border="0" cellpadding="3" cellspacing="0" width="100%">
					      <tr>
					        <td width="35"><?= ($i + 1) ?>.</td>
					        <td width="45">Label :</td>
					        <td width="230"><input type="text" name="Options[]" id="Option<?= $i ?>" value="<?= $Options[$i] ?>" maxlength="100" size="25" class="textbox" /></td>
					        <td width="70"><span id="lblWeightage<?= $i ?>"<?= (($QuestionType == 2) ? ' style="display:none;"' : '') ?>>Weightage :</span></td>

					        <td>
					          <select name="Weightage[]" id="Weightage<?= $i ?>"<?= (($QuestionType == 2) ? ' style="display:none;"' : '') ?>>
					            <option value=""></option>
					            <option value="1"<?= (($Weightage[$i] == 1) ? " selected" : "") ?>>1</option>
					            <option value="2"<?= (($Weightage[$i] == 2) ? " selected" : "") ?>>2</option>
					            <option value="3"<?= (($Weightage[$i] == 3) ? " selected" : "") ?>>3</option>
					            <option value="4"<?= (($Weightage[$i] == 4) ? " selected" : "") ?>>4</option>
					            <option value="5"<?= (($Weightage[$i] == 5) ? " selected" : "") ?>>5</option>
					          </select>
					        </td>
					      </tr>
					    </table>
					  </div>
<?
		}
?>
				    </td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="70">Keywords</td>
			          <td width="180"><input type="text" name="Question" value="<?= $Question ?>" class="textbox" maxlength="250" /></td>
			          <td width="65">Category</td>

			          <td width="200">
					    <select name="Category">
						  <option value=""></option>
<?
	foreach ($sCategoriesList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Category) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>


			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Question != "")
		$sConditions .= " AND question LIKE '%$Question%' ";

	if ($Category > 0)
		$sConditions .= " AND category_id='$Category' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_production_questions", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_production_questions $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="5%">#</td>
				      <td width="65%">Question</td>
				      <td width="18%">Category</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId           = $objDb->getField($i, 'id');
		$sQuestion     = $objDb->getField($i, 'question');
		$iCategory     = $objDb->getField($i, 'category_id');
		$iQuestionType = $objDb->getField($i, 'question_type');
		$iNoOfOptions  = $objDb->getField($i, 'no_of_options');
		$sOptions      = explode("|-|" ,$objDb->getField($i, 'options'));
		$iWeightage    = explode("|-|" ,$objDb->getField($i, 'weightage'));
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="5%" valign="top"><?= ($iStart + $i + 1) ?></td>
				      <td width="65%"><span id="Question<?= $iId ?>"><?= $sQuestion ?></span></td>
				      <td width="18%"><span id="Category<?= $iId ?>"><?= $sCategoriesList[$iCategory] ?></span></td>

				      <td width="12%" class="center">
<?

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
				        <a href="crc/delete-production-question.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Production Question?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="crc/view-production-question.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Production Question :: :: width: 500, height: 350"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
						  <td width="115">Category<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Category">
<?
		foreach ($sCategoriesList as $sKey => $sValue)
		{
?>
			                  <option value="<?= $sKey ?>"<?= (($sKey == $iCategory) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
						</tr>

					    <tr>
						  <td>Question<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Question" value="<?= $sQuestion ?>" maxlength="250" size="50" class="textbox" /></td>
						</tr>

					    <tr>
						  <td>Question Type<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select id="QuestionType_<?= $iId ?>_" name="QuestionType" onchange="updateOptions('_<?= $iId ?>_');">
							  <option value=""></option>
							  <option value="1"<?= (($iQuestionType == 1) ? " selected" : "") ?>>Single Selection</option>
							  <option value="2"<?= (($iQuestionType == 2) ? " selected" : "") ?>>Multiple Selection</option>
						    </select>
						  </td>
					    </tr>

					    <tr>
					      <td>Number of options<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select id="NoOfOptions_<?= $iId ?>_" name="NoOfOptions" onchange="updateOptions('_<?= $iId ?>_');">
							  <option value=""></option>
							  <option value="2"<?= (($iNoOfOptions == 2) ? " selected" : "") ?>>2</option>
							  <option value="3"<?= (($iNoOfOptions == 3) ? " selected" : "") ?>>3</option>
							  <option value="4"<?= (($iNoOfOptions == 4) ? " selected" : "") ?>>4</option>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td valign="top">Grading Options<span class="mandatory">*</span></td>
						  <td valign="top" align="center">:</td>

						  <td>
<?
		for ($j = 0; $j < 4; $j ++)
		{
?>
						    <div id="Option_<?= $iId ?>_<?= $j ?>" style="display:<?= (($j < $iNoOfOptions) ? 'block' : 'none') ?>;">
							  <table border="0" cellpadding="3" cellspacing="0" width="100%">
							    <tr>
								  <td width="35"><?= ($j + 1) ?>.</td>
								  <td width="45">Label :</td>
								  <td width="230"><input type="text" name="Options[]" id="Option_<?= $iId ?>_<?= $j ?>" value="<?= $sOptions[$j] ?>" maxlength="100" size="25" class="textbox" /></td>
								  <td width="70"><span id="lblWeightage_<?= $iId ?>_<?= $j ?>"<?= (($iQuestionType == 2) ? ' style="display:none;"' : '') ?>>Weightage :</span></td>

								  <td>
								    <select name="Weightage[]" id="Weightage_<?= $iId ?>_<?= $j ?>"<?= (($iQuestionType == 2) ? ' style="display:none;"' : '') ?>>
									  <option value=""></option>
									  <option value="1"<?= (($iWeightage[$j] == 1) ? " selected" : "") ?>>1</option>
									  <option value="2"<?= (($iWeightage[$j] == 2) ? " selected" : "") ?>>2</option>
									  <option value="3"<?= (($iWeightage[$j] == 3) ? " selected" : "") ?>>3</option>
									  <option value="4"<?= (($iWeightage[$j] == 4) ? " selected" : "") ?>>4</option>
									  <option value="5"<?= (($iWeightage[$j] == 5) ? " selected" : "") ?>>5</option>
								    </select>
								  </td>
							    </tr>
							  </table>
						    </div>
<?
		}
?>
						  </td>
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
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Question Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Category={$Category}&Question={$Question}");
?>

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