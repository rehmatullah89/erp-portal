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
	$SectionId     = IO::intValue('Section');
        $sSectionsList = getList("tbl_qa_sections", "id", "section");        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir.$sBaseDir."includes/meta-tags.php");
?>
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
	  <h2><?= $sSectionsList[$SectionId] ?></h2>
	  
	  <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="includes/quonda/update-report-section.php" class="<?=$Class?>">
		<input type="hidden" name="Id" id="Id" value="<?= $Id ?>" />
		<input type="hidden" name="SectionId" id="SectionId" value="<?= $SectionId ?>" />
                            
                            
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff">
		  <td width="100%">
<?
	if ($SectionId == 1)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-description-and-quantity-of-product.php");
        
        else if ($SectionId == 2)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-product-conformity.php");
        
        else if ($SectionId == 3)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-weight-conformity.php");
        
        else if ($SectionId == 4)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-ean-code.php");
        
        else if ($SectionId == 5)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-assortment.php");
        
        else if ($SectionId == 6)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-master-cartons.php");
        
        else if ($SectionId == 8)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-signatures.php");
        
        if ($SectionId == 13)
		@include("../".$sBaseDir."includes/quonda/general-sections/edit-airway-bill.php");


?>
            </td>
            </tr>
          </table>
		  
		  
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
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>