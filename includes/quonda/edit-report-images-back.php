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

	@require_once("../../requires/session.php");
	
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	
	$Id            = IO::intValue('AuditId');
	$SectionId     = IO::strValue('Section');
        $AuditDate     = IO::strValue('AuditDate');
        
        // Import Pictures
	@list($sYear, $sMonth, $sDay) = @explode("-", $AuditDate);
	
	$sPicsDir   = (SITE_URL.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
	$sSpecsDir  = (SITE_URL.SPECS_SHEETS_DIR.$sYear."/".$sMonth."/".$sDay."/");
	$sQuondaDir = ($sBaseDir.$sBaseDir.QUONDA_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
        
        $sSectionsList = array('P'=>'Packaging List', 'L'=>'Measurement Report', 'M'=>'Misc Images', 'PFV'=>'Production Front View', 'PBV'=>'Production Back View', 'CW'=>'Colorway of Production');        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir.$sBaseDir."includes/meta-tags.php");
?>
    <script type="text/javascript" src="scripts/jquery.js"></script>    
</head>

<body>
<?
	@include($sBaseDir.$sBaseDir."includes/messages.php");
?>
<div id="Msg" class="msgOk" style="overflow: visible; display: none;"></div>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">
      
<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:645px; height:645px;">
	  <h2><?= $sSectionsList[$SectionId] ?></h2>
	  
	  <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="includes/quonda/save-report-images.php" class="<?=$Class?>">
		<input type="hidden" name="Id" id="Id" value="<?= $Id ?>" />
                <input type="hidden" name="AuditDate" id="AuditDate" value="<?= $AuditDate?>" />
		<input type="hidden" name="SectionId" id="SectionId" value="<?= $SectionId ?>" />
                                                        
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff">
		<td width="100%">
<?
                if($SectionId == 'L')
                {
                    $sSQL = "SELECT specs_sheet_1, specs_sheet_2, specs_sheet_3, specs_sheet_4, specs_sheet_5, specs_sheet_6, specs_sheet_7, specs_sheet_8, specs_sheet_9, specs_sheet_10
                        FROM tbl_qa_reports
                        WHERE id='$Id'";
                    $objDb->query($sSQL);

                    $SpecsSheets = array( );
                    for ($i = 1; $i <= 10; $i ++)
			$SpecsSheets[] = $objDb->getField(0, "specs_sheet_{$i}");

?>
<ul style="margin:20px 0px 0px 20px;">
<?                    
                    for ($i = 0; $i < 10; $i ++)
                    {
			$SpecsSheet = $SpecsSheets[$i];
                        
                        if ($SpecsSheet != "")
			{
				
?>
					  <li>( <a href="<?= ($sSpecsDir.$SpecsSheet) ?>" class="lightview">view lab report - <?=$i+1?></a> &nbsp;-&nbsp; <a href="./" class="deleteSpecs" rel="<?= $Id ?>" index="<?= $i ?>"><b>x</b></a> )</li>
<?
			}
                    }
                }
?>
</ul>
<?
		$sAttachments = getList("tbl_qa_report_images", "id", "image", "audit_id='$Id' AND `type`='$SectionId'");
			
		if (count($sAttachments) > 0)
		{
?>
				<ul style="margin:20px 0px 0px 20px;">
<?
			foreach ($sAttachments as $iPicture => $sPicture)
			{
?>
				  <li><a href="<?= ($SectionId == 'L')?$sSpecsDir:$sPicsDir ?><?= $sPicture ?>" class="lightview"><?= $sPicture ?></a> &nbsp; - &nbsp; <a href="./" file="<?= $sPicture ?>" date="<?= $AuditDate ?>" audit_id="<?=$Id?>" image_id="<?=$iPicture?>" class="deletePic"><b>x</b></a></li>
<?
			}
?>
				</ul>
<?
		}
?>
                </td>
            </tr>
              <tr><td>&nbsp;</td></tr>
              <tr>
                  <td>
                      <div style="padding: 5px;">
                            <div style="padding: 2px; font-size: 12px; font-weight: bold;">Upload Files &nbsp;&nbsp;<span style="color: grey; font-size: 9px;">(Jpg Only)</span></div>    
                            <div style="float: left; width: 33%;">

                           <div style="display: inline-block; margin-left: 5px;">
                            <label for="filePictures">
                                <img width="50" height="50" src="images/icons/upload.png"/><br/>
                                <input name="filePictures[]" id="filePictures" multiple="multiple" type="file" class="textbox" value="" maxlength="200" size="40" />
                            </label>
                        </div>
                        </div>
                      </div>
                  </td>
              </tr>
          </table>
                <br/>
		  
		  
		  <div class="buttonsBar">
		    <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
		    <input type="button" value="" class="btnCancel" title="Cancel" onclick="parent.hideLightview();" />
		  </div>

      </form>
          
	  <br style="line-height:2px;" />
    </div>
<!--  Body Section Ends Here  -->

  </div>
</div>
<script type="text/javascript">
  <!--
    jQuery.noConflict( );
    
    jQuery(document).ready(function($)
    {
        $("a.deletePic").click(function( )
        {
                var objLink = $(this);

                jQuery.post("ajax/quonda/delete-qa-image.php",
                        { File:$(this).attr("file"), AuditDate:$(this).attr("date"), AuditId:$(this).attr("audit_id"), ImageId:$(this).attr("image_id") },

                        function (sResponse)
                        {
                                if (sResponse == "DELETED")
                                        objLink.parent( ).remove( );

                                else
                                        alert("An ERROR occured while Deleting the selected Image.");
                        },

                        "text");

                return false;
        });
        
        $("a.deleteSpecs").click(function( )
        {
                var objLink = $(this);

                jQuery.post("ajax/quonda/delete-specs-sheet.php",
                        { Id:$(this).attr("rel"), Index:$(this).attr("index") },

                        function (sResponse)
                        {
                                if (sResponse == "DELETED")
                                        objLink.parent( ).remove( );

                                else
                                        alert("An ERROR occured while Deleting the Specs Sheet.");
                        },

                        "text");

                return false;
        });
        
    });
-->
</script>
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>