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
	
	@header("Content-type: text/html; charset=utf-8");

	
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$Id         = IO::intValue('AuditId');
        $SectionId  = IO::intValue('Section');
        $sSectionsList = array(1=>'Product Conformity', 2=>'Weight Conformity', 3=>'EAN- Code', 4=>'Assortment', 5=>'Dimensions of Carton', 6=>'Child Labor', 7=>'Signatures', 8=>'Description/Quantity of Product')
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir.$sBaseDir."includes/meta-tags.php");
        @include($sBaseDir.$sBaseDir."includes/messages.php");
?>
    <div id="Msg" class="msgOk" style="overflow: visible; display: none;"></div>
	<style>
	.evenRow {
		background: #f6f4f5 none repeat scroll 0 0;
	}
	.oddRow {
		background: #dcdcdc none repeat scroll 0 0;
	}

	#Mytable tr:nth-child(even){
	   background-color: #f2f2f2
	}
		
	#Mytable2 {
	   font-size: 9px;
	}
	</style>    
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">
<?
if ($SectionId == 3){
  $Class = "";
} else {
  $Class = "frmOutline";
}
?>
<!--  Body Section Starts Here  -->
	<div id="Body" style="min-height:645px; height:645px;">
	  <h2><?=$sSectionsList[$SectionId]?></h2>
          <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="includes/quonda/update-general-report-section.php" class="<?=$Class?>">
            <input type="hidden" name="Id" id="Id" value="<?= $Id ?>" />
            <input type="hidden" name="SectionId" id="Id" value="<?= $SectionId ?>" />
                            
                            
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff">
		  <td width="100%">
                     <!-- <div id="RecordMsg" class="hidden"><? //($_SESSION["Flag1122"] != ""?"<span style='color:blue; background-color:yellow;'>{$_SESSION['Flag1122']}!</span>":"")?></div> -->
<?

	if ($SectionId == 1)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-product-conformity.php");
        
	else if ($SectionId == 2)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-weight-conformity.php");

	else if ($SectionId == 3)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-ean-code.php");

	else if ($SectionId == 4)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-assortment.php");

	else if ($SectionId == 5)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-master-cartons.php");

	else if ($SectionId == 6)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-child-labor.php");

        else if ($SectionId == 7)
          @include("../".$sBaseDir."includes/quonda/general-sections/edit-signatures.php");

	else if ($SectionId == 8)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-description-and-quantity-of-product.php");


?>		   

          <? if ($SectionId != 2 && $SectionId != 3) { ?>
            </td>
            </tr>
          </table>
            <div style="float: right; padding: 10px;">
            <input type="submit" id="BtnSave" value="Save" title="Save" />
            <input type="button" value="Cancel"  title="Cancel" onclick="parent.hideLightview();" />
            </div>
          <? } ?>
        </form>
          
	  <br style="line-height:2px;" />
    </div>
<!--  Body Section Ends Here  -->

  </div>
</div>
    <script type="text/javascript">
    <!-- 
     function alertMsg() {
        document.getElementById("RecordMsg").innerHTML = "";
        <?$_SESSION["Flag1122"] = "";?>
     }
     setTimeout(alertMsg,3000);    
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