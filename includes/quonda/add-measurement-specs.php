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
?>
<?
        @require_once("../../requires/session.php");	
	@header("Content-type: text/html; charset=utf-8");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	
	$AuditId     = IO::intValue('AuditId');
        $Style       = IO::intValue('Style');
        $Sizes       = IO::strValue('Sizes');
        $Colors      = IO::strValue('Colors');
        
        if ($_POST)
        {
            $AuditId     = IO::intValue('AuditId');
            $Color       = IO::strValue('Color');
            $Style       = IO::intValue('Style');
            $Size        = IO::strValue('Size');
                    
            @include("save-measurement-specs.php");
        }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include("../../includes/meta-tags.php");
?>
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

<!--  Body Section Starts Here  -->
	<div id="Body">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr bgcolor="#ffffff">
                  <td width="100%">
			<h2 style="margin:0px;">Add Measurements</h2>
                        <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="" class="frmOutline">
                            <input type="hidden" name="AuditId" value="<?=$AuditId?>"/>
                            <input type="hidden" name="Style" value="<?=$Style?>"/>
                            <table border="0" cellpadding="5" cellspacing="0" width="100%" style="margin-top:20px;">
                                <tr valign="top">
                                      <td width="100"><b>Color</b></td>
                                      <td width="20" align="center">:</td>
                                      <td>
                                          <select name="Color" style="width:170px;">
<?
                                                   $iColors = explode(",", $Colors);
                                                   foreach ($iColors as $sColor)
                                                   {
                                                       if($sColor != "")
                                                       {
?>
                                              <option value="<?=$sColor?>"><?=$sColor?></option>
<?
                                                       }
                                                   }
?>
                                          </select>
                                      </td>
                                </tr>
                                <tr valign="top">
                                      <td width="100"><b>Size</b></td>
                                      <td width="20" align="center">:</td>
                                      <td>
                                          <select name="Size" style="width:170px;">
<?
                                                    $iSizes = explode(",", $Sizes);
                                                    foreach ($iSizes as $iSize)
                                                    {
?>
                                                            <option value="<?=$iSize?>"><?=getDbValue("size", "tbl_sizes", "id='$iSize'");?></option>
<?
                                                    }
?>
                                          </select>
                                      </td>
                                </tr>                                
                                <tr><td colspan="3">&nbsp;</td></tr>
                            </table>
                            <div class="buttonsBar">
				  <input type="submit" id="BtnSave" value="" class="btnSave" title="Save" />
				  <input type="button" value="" class="btnCancel" title="Cancel" onclick="parent.hideLightview();" />
				</div>
                        </form>
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
	$objDb2->close( );
	$objDbGlobal->close( );
	@ob_end_flush( );
?>
