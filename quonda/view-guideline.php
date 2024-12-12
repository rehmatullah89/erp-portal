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

	$ReportId    = IO::intValue('ReportId');
        $TypeId      = IO::intValue('TypeId');
        
	$sReferer       = $_SERVER['HTTP_REFERER'];
        $ReportsList    = getList("tbl_reports", "id", "report");
        $DefectTypes    = getList("tbl_defect_types dt ,tbl_defect_codes dc", "dt.id", "dt.type", "dt.id=dc.type_id AND report_id = '$ReportId'");
	$sGuideLine     = getDbValue("guidelines", "tbl_defect_guidelines", "type_id='$TypeId' AND report_id='$ReportId'");
        
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
	<div id="Body" style="min-height:544px; height:544px;">
	  <h2>Guide Lines</h2>

	  <table border="0" cellpadding="2" cellspacing="0" width="100%">
	    	      <tr class="evenRow">
                          <td width="100">Report Type</td>
                        <td  width="20" align="center">:</td>
                        <td><?= $ReportsList[$ReportId] ?></td>
                      </tr>
              
                      <tr class="evenRow">
                        <td>Defect Type</td>
                        <td align="center">:</td>
                        <td><?= $DefectTypes[$TypeId] ?></td>
                      </tr>    

                      <tr class="evenRow">
                        <td>Guidelines</td>
                        <td align="center">:</td>
                        <td><?= $sGuideLine ?></td>
                      </tr>
	  </table>

	  <br style="line-height:2px;" />
    </div>
<!--  Body Section Ends Here  -->

  </div>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>