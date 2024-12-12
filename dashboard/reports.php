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

	$AuditStage = IO::strValue("AuditStage");
	$Vendor     = IO::intValue("Vendor");
	$Date       = IO::strValue("Date");

	if ($Date == "")
		$Date = date("Y-m-d");

	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "parent_id='0' AND sourcing='Y'");
	$sDefectColors    = getList("tbl_defect_types", "id", "color");
	$sAuditStagesList = getList("tbl_audit_stages", "code", "stage");
	$sStageColorsList = getList("tbl_audit_stages", "code", "color");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/fusion-charts/FusionCharts.js"></script>
  <script type="text/javascript" src="scripts/fusion-charts/FusionChartsExportComponent.js"></script>
  <script type="text/javascript" src="scripts/glider.js"></script>
<!--
  <meta http-equiv="refresh" content="600" />
-->
</head>

<body style="margin:0px; background:#ffffff;">

<div>
  <table border="0" cellspacing="0" cellpadding="10" width="100%">
    <tr valign="top">
      <td width="33.3%">
        <div class="tblSheet">
<?
	$sFromDate = $Date;
	$sToDate   = $Date;

	@include($sBaseDir."includes/dashboard/cumulative-graph.php");
?>
	    </div>
      </td>


      <td width="33.4%">
        <div class="tblSheet">
<?
	$sFromDate = $Date;
	$sToDate   = $Date;
	$iIndex    = 1;

	@include($sBaseDir."includes/dashboard/defect-types-graph.php");
?>
	    </div>
      </td>


      <td width="33.3%">
        <div class="tblSheet">
<?
	$iIndex = 1;

	@include($sBaseDir."includes/dashboard/defect-code-graph.php");
?>
	    </div>
      </td>
    </tr>


    <tr valign="top">
      <td>
        <div class="tblSheet">
<?
	$sFromDate = date("Y-m-01", strtotime($Date));
	$sToDate   = date("Y-m-0t", strtotime($Date));
	$iIndex    = 2;

	@include($sBaseDir."includes/dashboard/defect-types-graph.php");
?>
	    </div>
      </td>


      <td>
        <div class="tblSheet">
<?
	$iIndex = 2;

	@include($sBaseDir."includes/dashboard/defect-code-graph.php");
?>
	    </div>
      </td>


      <td>
        <div class="tblSheet">
<?
	$sFromDate = $Date;
	$sToDate   = $Date;

	@include($sBaseDir."includes/dashboard/defect-images.php");
?>
	    </div>
      </td>
    </tr>
  </table>
</div>
<!--
<br />

<form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
<div id="SearchBar">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	  <td width="40">Date</td>
	  <td width="78"><input type="text" name="Date" value="<?= $Date ?>" id="Date" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
	  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
	  <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
	</tr>
  </table>
</div>
</form>
-->

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>