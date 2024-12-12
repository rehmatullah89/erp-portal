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

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * FROM tbl_production_questions WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sQuestion     = $objDb->getField(0, 'question');
		$iCategory     = $objDb->getField(0, 'category_id');
		$iQuestionType = $objDb->getField(0, 'question_type');
		$iNoOfOptions  = $objDb->getField(0, 'no_of_options');
		$sOptions      = explode("|-|" ,$objDb->getField(0, 'options'));
		$iWeightage    = explode("|-|" ,$objDb->getField(0, 'weightage'));
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
	<div id="Body" style="min-height:344px; height:344px;">
	  <h2>Question Details</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	    <tr>
		  <td width="115">Category</td>
		  <td width="20" align="center">:</td>
		  <td><?= getDbValue("title", "tbl_production_categories", "id='$iCategory'") ?></td>
	    </tr>

	    <tr>
		  <td>Question</td>
		  <td align="center">:</td>
		  <td><?= $sQuestion ?></td>
	    </tr>

	    <tr>
		  <td>Question Type</td>
		  <td align="center">:</td>
		  <td><?= (($iQuestionType == 1) ? "Single Selection" : "Multiple Selection") ?></td>
	    </tr>

	    <tr valign="top">
		  <td>Number of options</td>
		  <td align="center">:</td>
		  <td><?= $iNoOfOptions ?></td>
	    </tr>

		<tr>
		  <td colspan="3">
		    <br />
		    <b>Grading Options:</b><br />
<?
	for ($i = 0; $i < $iNoOfOptions; $i ++)
	{
?>
			<div>
			  <table border="0" cellpadding="3" cellspacing="0" width="100%">
				<tr>
				  <td width="45">Label :</td>
				  <td width="220"><?= $sOptions[$i] ?></td>
				  <td width="70"><span<?= (($iQuestionType == 2) ? ' style="display:none;"' : '') ?>>Weightage :</span></td>
				  <td><?= $iWeightage[$i] ?></td>
				</tr>
			  </table>
			</div>
<?
	}
?>
		  </td>
		</tr>
	  </table>
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