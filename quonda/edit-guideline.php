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
	$objDb2      = new Database( );

	$ReportId    = IO::intValue('ReportId');
        $TypeId      = IO::intValue('TypeId');
        
	$sReferer   = $_SERVER['HTTP_REFERER'];
	$sGuideLine = getDbValue("guidelines", "tbl_defect_guidelines", "type_id='$TypeId' AND report_id='$ReportId'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/quonda/edit-guideline.js"></script>
  <script type="text/javascript" src="scripts/jquery.js"></script>
  <script type="text/javascript" src="scripts/ckeditor/ckeditor.js"></script>
  <script type="text/javascript" src="scripts/ckeditor/adapters/jquery.js"></script>
  <script type="text/javascript" src="scripts/ckfinder/ckfinder.js"></script>

  <script type="text/javascript">
  <!--
  	jQuery.noConflict( );

	jQuery(document).ready(function( )
	{
  		jQuery("#GuideLine").ckeditor({ height:"400px", toolbar:"Basic" }, function( ) { CKFinder.setupCKEditor(this, (jQuery("base").attr("href") + "scripts/ckfinder/")); });
  	});
  -->
  </script>
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
			    <h1>guidelines</h1>

			    <form name="frmData" id="frmData" method="post" action="quonda/update-guideline.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disable( );">
			    <input type="hidden" name="TypeId" value="<?= $TypeId ?>" />
                            <input type="hidden" name="ReportId" value="<?= $ReportId ?>" />
			    <input type="hidden" name="Referer" value="<?= $sReferer ?>" />

			    <h2>Guidelines</h2>
                               <table width="98%" cellspacing="0" cellpadding="4" border="0" align="center">
				  <tr>
                                      <td width="100">Report Type<span class="mandatory">*</span></td>
                                      <td width="20" align="center">:</td>

				    <td>
					  <select name="Report" id="Report"  onchange="getListValues('Report', 'DefectType', 'ReportDefectTypes');">
					    <option value="">[ select Report ]</option>
<?
        $ReportsList = getList("tbl_reports", "id", "report");

	foreach ($ReportsList as $iRport => $sReport)
	{
?>
		            	<option value="<?= $iRport ?>"<?= (($ReportId == $iRport) ? " selected" : "") ?>><?= $sReport ?></option>
<?
	}
?>
					  </select>
				    </td>
				  </tr>
                                  <tr>
                                      <td width="65">Defect Type<span class="mandatory">*</span></td>
                                      <td width="20" align="center">:</td>

				    <td>
					  <select name="DefectType" id="DefectType">
					    <option value="">[ select Defect Type ]</option>
<?

    if($ReportId > 0)
    {
                    $DefectTypes = getList("tbl_defect_types dt ,tbl_defect_codes dc", "dt.id", "dt.type", "dt.id=dc.type_id AND report_id = '$ReportId'");

                    foreach ($DefectTypes as $iDefectType => $sDefectType)
                    {
?>
		            	<option value="<?= $iDefectType ?>"<?= (($TypeId == $iDefectType) ? " selected" : "") ?>><?= $sDefectType ?></option>
<?
                    }
    }
?>
					  </select>
				    </td>
				  </tr>  
				</table>

				<br />
			    <h2 style="margin:0px;">Guide Line</h2>

			    <table width="100%" cellspacing="0" cellpadding="5" border="0">
				  <tr>
				    <td width="100%"><textarea name="GuideLine" id="GuideLine" style="width:100%; height:400px;"><?= $sGuideLine ?></textarea></td>
				  </tr>
				</table>

			    <div class="buttonsBar">
			      <input type="submit" id="BtnSave" value="" class="btnSave" onclick="return validateForm( );" />
			      <input type="button" value="" class="btnBack" onclick="document.location='<?= $sReferer ?>';" />
			    </div>
			    </form>

			    <br />
			    <b>Note:</b> Fields marked with an asterisk (*) are required.<br/>
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