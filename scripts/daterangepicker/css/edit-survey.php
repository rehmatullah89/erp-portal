<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  SCRP - School Construction and Rehabilitation Programme                                  **
	**  Version 1.0                                                                              **
	**                                                                                           **
	**  http://www.humdaqam.pk                                                                   **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree Solutions                                                 **
	**  http://www.3-tree.com                                                                    **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**  Project Manager:                                                                         **
	**                                                                                           **
	**      Name  :  Muhammad Tahir Shahzad                                                      **
	**      Email :  mtshahzad@sw3solutions.com                                                  **
	**      Phone :  +92 333 456 0482                                                            **
	**      URL   :  http://www.mtshahzad.com                                                    **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/common.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	if ($sUserRights["Edit"] != "Y")
		exitPopup(true);


	$iSurveyId = IO::intValue("SurveyId");
	$iIndex    = IO::intValue("Index");

	if ($_POST)
		@include("update-survey.php");


	$sSQL = "SELECT * FROM tbl_surveys WHERE id='$iSurveyId'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) != 1)
		exitPopup( );

	$iSchool           = $objDb->getField(0, "school_id");
	$sEnumerator       = $objDb->getField(0, "enumerator");
	$sDate             = $objDb->getField(0, "date");
	$sMerged           = $objDb->getField(0, "merged_denotified");
	$sLnadAvailable    = $objDb->getField(0, "land_available");
	$sLandDispute      = $objDb->getField(0, "land_dispute");
	$sOtherFunding     = $objDb->getField(0, "other_funding");
	$iClassRooms       = $objDb->getField(0, "class_rooms");
	$sEducationPurpose = $objDb->getField(0, "education_purpose");
	$sShelterLess      = $objDb->getField(0, "shelter_less");
	$sMultiGrading     = $objDb->getField(0, "multi_grading");
	$iAvgAttendance    = $objDb->getField(0, "avg_attendance");
	$sComments         = $objDb->getField(0, "comments");
	$sStatus           = $objDb->getField(0, "status");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("{$sAdminDir}includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/<?= $sCurDir ?>/edit-survey.js"></script>
</head>

<body class="popupBg">

<div id="PopupDiv">
<?
	@include("{$sAdminDir}includes/messages.php");
?>
  <form name="frmRecord" id="frmRecord" method="post" action="<?= @htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
	<input type="hidden" name="SurveyId" id="SurveyId" value="<?= $iSurveyId ?>" />
	<input type="hidden" name="Index" value="<?= $iIndex ?>" />
	<div id="RecordMsg" class="hidden"></div>

	<label for="txtCode">EMIS Code</label>
	<div><input type="text" name="txtCode" id="txtCode" value="<?= getDbValue("code", "tbl_schools", "id='$iSchool'") ?>" maxlength="10" size="30" class="textbox" /></div>

	<div class="br10"></div>
	
	<label for="txtEnumerator">Enumerator Name</label>
	<div><input type="text" name="txtEnumerator" id="txtEnumerator" value="<?= formValue($sEnumerator) ?>" maxlength="100" size="30" class="textbox" /></div>

	<div class="br10"></div>
	
	<label for="txtDate">Survey Date</label>
	<div class="date"><input type="text" name="txtDate" id="txtDate" value="<?= $sDate ?>" maxlength="10" size="10" class="textbox" readonly /></div>

	<div class="br10"></div>
		  
	<label for="ddMerged">Is the school denotified or a ghost school? </label>

	<div>
	  <select name="ddMerged" id="ddMerged">
		<option value=""></option>
		<option value="Y"<?= (($sMerged == "Y") ? ' selected' : '') ?>>Yes</option>
		<option value="N"<?= (($sMerged == "N") ? ' selected' : '') ?>>No</option>
	  </select>
	</div>

	<div class="br10"></div>
		  
	<label for="ddLnadAvailable">Does the school have enough land for new construction?</label>

	<div>
	  <select name="ddLnadAvailable" id="ddLnadAvailable" rel="land|N">
		<option value=""></option>
		<option value="Y"<?= (($sLnadAvailable == "Y") ? ' selected' : '') ?>>Yes</option>
		<option value="N"<?= (($sLnadAvailable == "N") ? ' selected' : '') ?>>No</option>
	  </select>
	</div>

	<div class="br10"></div>
		  
	<label for="ddLandDispute">Is the school having any land dispute?</label>

	<div>
	  <select name="ddLandDispute" id="ddLandDispute" class="land"  rel="dispute|Y">
		<option value=""></option>
		<option value="Y"<?= (($sLandDispute == "Y") ? ' selected' : '') ?>>Yes</option>
		<option value="N"<?= (($sLandDispute == "N") ? ' selected' : '') ?>>No</option>
	  </select>
	</div>
	
	<div class="br10"></div>
		  
	<label for="ddOtherFunding">Is the school involved in any other project providing funding for classroom infrastructure?</label>

	<div>
	  <select name="ddOtherFunding" id="ddOtherFunding" class="land dispute" rel="funding|Y">
		<option value=""></option>
		<option value="Y"<?= (($sOtherFunding == "Y") ? ' selected' : '') ?>>Yes</option>
		<option value="N"<?= (($sOtherFunding == "N") ? ' selected' : '') ?>>No</option>
	  </select>
	</div>

	<div class="br10"></div>
		  
	<label for="txtClassRooms">How many classrooms does your school have?</label>
	<div><input type="text" name="txtClassRooms" id="txtClassRooms" value="<?= $iClassRooms ?>" maxlength="10" size="10" class="textbox land dispute funding" /></div>

	<div class="br10"></div>
		  
	<label for="ddEducationPurpose">Are all classrooms in school being used for education purpose?</label>

	<div>
	  <select name="ddEducationPurpose" id="ddEducationPurpose" class="land dispute funding">
		<option value=""></option>
		<option value="Y"<?= (($sEducationPurpose == "Y") ? ' selected' : '') ?>>Yes</option>
		<option value="N"<?= (($sEducationPurpose == "N") ? ' selected' : '') ?>>No</option>
	  </select>
	</div>

	<div class="br10"></div>
		  
	<label for="ddShelterLess">Are there any shelter-less grades being taught?</label>

	<div>
	  <select name="ddShelterLess" id="ddShelterLess" class="land dispute funding">
		<option value=""></option>
		<option value="Y"<?= (($sShelterLess == "Y") ? ' selected' : '') ?>>Yes</option>
		<option value="N"<?= (($sShelterLess == "N") ? ' selected' : '') ?>>No</option>
	  </select>
	</div>

	<div class="br10"></div>
		  
	<label for="ddMultiGrading">Are there more than 2 grades being taught in one classroom (multi-grading)?</label>

	<div>
	  <select name="ddMultiGrading" id="ddMultiGrading" class="land dispute funding">
		<option value=""></option>
		<option value="Y"<?= (($sMultiGrading == "Y") ? ' selected' : '') ?>>Yes</option>
		<option value="N"<?= (($sMultiGrading == "N") ? ' selected' : '') ?>>No</option>
	  </select>
	</div>			

	<div class="br10"></div>
	
	<label for="txtAvgAttendance">What is the average attendance of school?</label>
	<div><input type="text" name="txtAvgAttendance" id="txtAvgAttendance" value="<?= $iAvgAttendance ?>" maxlength="10" size="10" class="textbox land dispute funding" /></div>

	<div class="br10"></div>			

	<label for="txtComments">Any other relevant Comments <span>(Optional)</span></label>
	<div><textarea name="txtComments" id="txtComments" rows="10" style="width:500px;"><?= $sComments ?></textarea></div>
	
	<div class="br10"></div>

	<label for="ddStatus">Status</label>

	<div>
	  <select name="ddStatus" id="ddStatus">
		<option value="C"<?= (($sStatus == 'C') ? ' selected' : '') ?>>Completed</option>
		<option value="I"<?= (($sStatus == 'I') ? ' selected' : '') ?>>In-Complete</option>
	  </select>
	</div>

    <br />
    <button id="BtnSave">Save Survey</button>
    <button id="BtnCancel">Cancel</button>
  </form>
</div>


</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>