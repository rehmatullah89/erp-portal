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

	$Id         = IO::intValue('Id');
        $SectionId  = IO::intValue('SectionId');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir.$sBaseDir."includes/meta-tags.php");
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
	<div id="Body" style="min-height:645px; height:645px;">
	  <h2><?=$sSection?></h2>

	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr bgcolor="#ffffff">
		  <td width="100%">

<?
        if ($SectionId == 1)
		@include("../".$sBaseDir."includes/quonda/hohenstein/save-product-conformity.php");

	else if ($SectionId == 2)
		@include("../".$sBaseDir."includes/quonda/hohenstein/save-weight-conformity.php");

	else if ($SectionId == 3)
		@include("../".$sBaseDir."includes/quonda/hohenstein/save-ean-code.php");

	else if ($SectionId == 4)
		@include("../".$sBaseDir."includes/quonda/hohenstein/save-assortment.php");

	else if ($SectionId == 5)
		@include("../".$sBaseDir."includes/quonda/hohenstein/save-master-cartons.php");

	else if ($SectionId == 6)
		@include("../".$sBaseDir."includes/quonda/hohenstein/save-child-labor.php");

	else if ($SectionId == 7)
		@include("../".$sBaseDir."includes/quonda/hohenstein/save-signatures.php");
	
?>		   
		  </td>
	    </tr>
	  </table>

    </div>
<!--  Body Section Ends Here  -->

</div>
</div>   
    <script type="text/javascript">
    <!-- 
    // parent.hideLightview();   
    -->
    </script>    
</body>
</html>
<?
        if($bFlag == true)
        {
            $objDb->execute("COMMIT");
        }
        else
        {
            $objDb->execute("ROLLBACK");
        }
        
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
        $_SESSION["Flag1122"] = "Section Updated Successfully"; 
        redirect($_SERVER['HTTP_REFERER'], "Section Updated Successfully");
?>