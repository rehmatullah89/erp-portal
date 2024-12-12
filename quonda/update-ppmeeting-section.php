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
	$objDb3      = new Database( );

	$Id         = IO::intValue('Id');
        $SectionId  = IO::intValue('SectionId');
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
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-attendees.php");

	else if ($SectionId == 2)
		@include($sBaseDir."includes/quonda/ppmeetingsections/udpate-ppmeeting-review-details.php");

	else if ($SectionId == 3)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-focuspoints-comments.php");

	else if ($SectionId == 5)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-fabric-details.php");

	else if ($SectionId == 6)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-inspection-details.php");

	else if ($SectionId == 12)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-laying-spreading.php");

	else if ($SectionId == 13)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-cutting-details.php");

	else if ($SectionId == 14)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-block-fusing.php");

	else if ($SectionId == 15)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-numbering.php");
	
	else if ($SectionId == 16)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-panel-inspection.php");

	else if ($SectionId == 17)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-fusing-interlining.php");

	else if ($SectionId == 18)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-pocketing-lining.php");

	else if ($SectionId == 19)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-sewing-details.php");
	
	else if ($SectionId == 20)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-button-holes.php");
	
	else if ($SectionId == 21)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-label-trim.php");

	else if ($SectionId == 22)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-shoulder-padding.php");
	
	else if ($SectionId == 23)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-shoulder-tape.php");
	
	else if ($SectionId == 24)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-elastic-tape.php");

	else if ($SectionId == 25)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-zipper-application.php");
	
	else if ($SectionId == 26)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-embroidery.php");
        
        else if ($SectionId == 27)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-fabric-plied.php");
        
        else if ($SectionId == 28)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-special-centerline.php");
       
        else if ($SectionId == 29)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-garment-wash.php");
        
        else if ($SectionId == 32)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-garment-construction.php");
        
        else if ($SectionId == 35)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-ironing-pressing.php");
        
        else if ($SectionId == 36)
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-machine-layout.php");
        
	else
		@include($sBaseDir."includes/quonda/ppmeetingsections/update-ppmeeting-representatives.php");
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
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
        $_SESSION["Flag1122"] = "Updated Successfully"; 
        redirect($_SERVER['HTTP_REFERER'], "Section Updated");
?>