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
	
	@header("Content-type: text/html; charset=utf-8");

	
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	
	$Brand      = IO::intValue('Id');
        $Categories = IO::strValue('Categories');

	$sSQL = "SELECT *			
	         FROM tbl_brand_stages
	         WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
                $iBrand           = $objDb->getField(0, "brand_id");
		$iCategory        = $objDb->getField(0, "category_id");
		$sStages          = $objDb->getField(0, "stages");
	}

	$sStagesList    = getList("tbl_production_stages", "id", "title", "", "position");
        
        if($Categories != "")
            $sCategoriesList= getList("tbl_categories", "id", "category", "id IN ($Categories)", "category");
        else
            $sCategoriesList= array();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
	<style>
	.evenRow {
		background: #f6f4f5 none repeat scroll 0 0;
	}
	.oddRow {
		background: #dcdcdc none repeat scroll 0 0;
	}
	</style>    
</head>

<body>
<?
	@include($sBaseDir."includes/messages.php");
?>
<div id="Msg" class="msgOk" style="overflow: visible; display: none;"></div>
<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
<div id="Body" style="min-height:544px;">
 <form name="frmData" id="frmData" method="post" action="data/save-category-stages.php" class="frmOutline">
     <input type="hidden" name="Brand" value="<?=$Brand?>"/>
     <input type="hidden" name="Categories" value="<?=$Categories?>"/>
          <h2>Add Category Stages </h2>
	 
<?
                    if($Categories != "")
                    {
                        $iCategories = explode(",", $Categories);
?>
               <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
		  <tr class="sdRowHeader">
                    <td width="20" align="center"><b>#</b></td>
                    <td width="300" align="center"><b>Category</b></td>
                    <td align="center"><b>Stages</b></td>
                    <td align="center" width="100"><b>Days</b></td>
                  </tr>
<?
                    $iCounter = 1;
                    foreach($iCategories as $iCategory)
                    {
                        $Stages = explode(",", getDbValue("stages", "tbl_brand_stages", "brand_id='$Brand' AND category_id='$iCategory'"));
                        $Days   = explode(",", getDbValue("days", "tbl_brand_stages", "brand_id='$Brand' AND category_id='$iCategory'"));
?>
                   <tr class="<?=$iCounter%2==0?'oddRow':'evenRow'?>">
                    <td width="20" align="center"><b><?=$iCounter++?></b></td>
                    <td align="center"><b><?=$sCategoriesList[$iCategory]?><input type="hidden" name="Category[]" value="<?=$iCategory?>"/></b></td>
                    <td align="center"  width="150" colspan="2">                        
                        <table border="0" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                            
<?
		foreach ($sStagesList as $iStage => $sStage)
		{
                        if(@in_array($iStage, $Stages))
                            $iIndex = array_search($iStage, $Stages);
?>
                        <tr>    
                                <td width="100" ><b><?= $sStage ?></b></td>
                                <td width="20" align="center"><input type="text" name="Stage<?=$iCategory?>_<?=$iStage?>" value="<?=@in_array($iStage, $Stages)?$Days[$iIndex]:''?>" size="5" /></td>
                        </tr>
<?
		}
?>
                                
                            
                        </table>
                    </td>
                  </tr>
<?
                    }
?>
                   
              </table>
<?
                    }
                    else
                    {
?>
               <div class="noRecord">No Category Selected against selected Brand!</div> 
<?
                    }
                    
                    if($Categories != "")
                    {
?>
                <div class="buttonsBar">
		    <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
		    <input type="button" value="" class="btnCancel" title="Cancel" onclick="parent.hideLightview();" />
                </div>
<?
                    }
?>
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
        $objDb4->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>