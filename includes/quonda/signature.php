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

	
	$Id           = IO::intValue('AuditId');
	$SectionId    = IO::intValue('Section');
        $UserType     = IO::strValue('UserType');
        $AuditDate    = IO::strValue('AuditDate');
        $sSectionsList= getList("tbl_qa_sections", "id", "section");        
        
        if ($_POST)
            @include("save-signature.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir.$sBaseDir."includes/meta-tags.php");
?>
    <script type="text/javascript" src="scripts/quonda/signature.js"></script>
<style>
        body, canvas, div, form, input {
                margin: 0;
                padding: 0;
        }
        #wrapper {
                width: 100%;
                padding: 1px;
        }
        canvas {
                position: relative;
                margin: 1px;
                margin-left: 0px;
                border: 1px solid #3a87ad;
        }
        h1, p {
                padding-left: 2px;
                width: 100%;
                margin: 0 auto;
        }
        #controlPanel {
                margin: 2px;
        }		
</style>
</head>

<body>
<?
	@include($sBaseDir.$sBaseDir."includes/messages.php");
?>
<div id="Msg" class="msgOk" style="overflow: visible; display: none;"></div>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">
<?
	if ($SectionId == 3)
		$Class = "";
	
	else
		$Class = "frmOutline";
?>
<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:645px; height:645px;">
	  <h2><?= $UserType." ".$sSectionsList[$SectionId] ?></h2>
	  	<div id="wrapper">
			<div id="canvas">
				Canvas is not supported.
			</div>

			<script>
				rbSignature.capture();
			</script>
		</div>
                <div class="buttonsBar">
		    <input type="button" value="" class="btnBack" title="Back" onclick="document.location='includes/quonda/edit-report-section.php?AuditId=<?=$Id?>&Section=8';" />
		    <input type="button" value="" class="btnCancel" title="Clear" onclick="rbSignature.clear();" />                    
                    <input type="button" value="" class="btnSave" title="Save" onclick="rbSignature.save('<?=$Id?>', '<?=$UserType?>', '<?=$AuditDate?>');"/>
                </div>
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
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>