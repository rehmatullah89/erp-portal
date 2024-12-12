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

	$PageId     = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$GuideLine  = IO::strValue("GuideLine");
	$Report     = IO::intValue("Report");
        $DefectType = IO::intValue("DefectType");

	$sReprotsList = getList("tbl_reports", "id", "report");
        $DefectTypes = getList("tbl_defect_types", "id", "type");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="MainDiv">
  <div id="PageLeftBorder">
    <div id="PageRightBorder">

<!--  Message Section Starts Here  -->
<?
	@include($sBaseDir."includes/messages.php");
?>
<!--  Message Section Ends Here  -->

      <div id="PageContents">

<!--  Header Section Starts Here  -->
<?
	@include($sBaseDir."includes/header.php");
?>
<!--  Header Section Ends Here  -->


<!--  Navigation Section Starts Here  -->
<?
	@include($sBaseDir."includes/navigation.php");
?>
<!--  Navigation Section Ends Here  -->


<!--  Body Section Starts Here  -->
	    <div id="Body">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr valign="top">
			  <td width="100%">
			    <h1>guidelines</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="80">Guide Line</td>
			          <td width="150"><input type="text" name="GuideLine" value="<?= $GuideLine ?>" class="textbox" maxlength="50" size="15" /></td>
			          <td width="50">Report</td>

			          <td width="150">
			            <select name="Report">
			              <option value="">All Reports</option>
<?
	foreach ($sReprotsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Report) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Post != "")
		$sConditions = " AND guidelines LIKE '%$GuideLine%' ";

	if ($Report > 0)
		$sConditions .= " AND report_id='$Report' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_defect_guidelines", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_defect_guidelines $sConditions ORDER BY report_id, type_id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="5%">#</td>
				      <td width="30%">Report</td>
				      <td width="50%">Defect Type</td>
                                      <td width="15%" class="center">Options</td>
				    </tr>
<?
		}

		$iReportId  = $objDb->getField($i, 'report_id');
                $iTypeId    = $objDb->getField($i, 'type_id');
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= @$sReprotsList[$iReportId] ?></td>
				      <td><?= $DefectTypes[$iTypeId] ?></td>

				      <td class="center">
<?

		if ($sUserRights['Edit'] == "Y")
		{
?>
                    <a href="quonda/edit-guideline.php?ReportId=<?= $iReportId ?>&TypeId=<?= $iTypeId ?>"><img src="images/icons/edit.gif" width="16" height="16" hspace="2" alt="Edit" title="Edit" /></a>
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
		    <a href="quonda/delete-guideline.php?ReportId=<?= $iReportId ?>&TypeId=<?= $iTypeId ?>" onclick="return confirm('Are you SURE, You want to Delete this Guideline?');"><img src="images/icons/delete.gif" width="16" height="16" hspace="2" alt="Delete" title="Delete" /></a>
<?
		}
?>
				        <a href="quonda/view-guideline.php?ReportId=<?= $iReportId ?>&TypeId=<?= $iTypeId ?>" class="lightview" rel="iframe" title="Guide Line for Report : <?= $sReprotsList[$iReportId] ?> :: :: width: 900, height: 650"><img src="images/icons/view.gif" width="16" height="16" hspace="1" alt="View" title="View" /></a>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Guideline Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&GuideLine={$GuideLine}&Report={$Report}");
?>

			  </td>
			</tr>
		  </table>

<?
	@include($sBaseDir."includes/my-profile.php");
?>
        </div>
<!--  Body Section Ends Here  -->


<!--  Footer Section Starts Here  -->
<?
	@include($sBaseDir."includes/footer.php");
?>
<!--  Footer Section Ends Here  -->

      </div>
    </div>
  </div>
</div>

<!--  Bottom Bar Section Starts Here  -->
<?
	@include($sBaseDir."includes/bottom-bar.php");
?>
<!--  Bottom Bar Section Ends Here  -->

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>