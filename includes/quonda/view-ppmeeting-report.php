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
		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			    <td width="90">Vendor</td>
			    <td width="20" align="center">:</td>
			    <td><?= $sVendor ?></td>
			  </tr>

			  <tr>
			    <td>Auditor</td>
			    <td align="center">:</td>
			    <td><?= $sAuditor ?></td>
			  </tr>

			  <tr>
                            <td>Brand</td>
                            <td align="center">:</td>
                            <td><?= getDbValue("brand", "tbl_brands", "id='$Brand'"); ?></td>
                          </tr>

			  <tr>
				<td>Style</td>
				<td align="center">:</td>
				<td><?= getDbValue("style", "tbl_styles", "id='$iStyle'") ?></td>
			  </tr>

<?
	$sPos = "";

	$sSQL = "SELECT CONCAT(order_no, ' ', order_status) FROM tbl_po WHERE id IN ($sAdditionalPos) ORDER BY order_no";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sPos .= (", ".$objDb->getField($i, 0));
	}
?>
			  <tr valign="top">
			    <td>PO(s)</td>
			    <td align="center">:</td>
			    <td><?= ($sPO.$sPos) ?></td>
			  </tr>

			  <tr>
			    <td>Audit Stage</td>
			    <td align="center">:</td>
			    <td><?= $sAuditStagesList[$sAuditStage] ?></td>
			  </tr>

<?
	switch ($sAuditStatus)
	{
		case "1st" : $sAuditStatus = "1st"; break;
		case "2nd" : $sAuditStatus = "2nd"; break;
		case "3rd" : $sAuditStatus = "3rd"; break;
		case "4th" : $sAuditStatus = "4th"; break;
		case "5th" : $sAuditStatus = "5th"; break;
		case "6th" : $sAuditStatus = "6th"; break;
	}
?>
			  <tr>
			    <td>Audit Status</td>
			    <td align="center">:</td>
			    <td><?= $sAuditStatus ?></td>
			  </tr>

<?
	switch ($sAuditResult)
	{
		case "P" : $sAuditResult = "Pass"; break;
		case "F" : $sAuditResult = "Fail"; break;
		case "H" : $sAuditResult = "Hold"; break;
	}
?>
			  <tr>
			    <td>Audit Result</td>
			    <td align="center">:</td>
			    <td><?= $sAuditResult ?></td>
			  </tr>

<?
	switch ($sAuditType)
	{
		case "B"  : $sAuditType  = "Bulk"; break;
		case "BG" : $sAuditType = "B-Grade"; break;
		case "SS" : $sAuditType = "Sales Sample"; break;
	}


	if ($iReportId != 8)
	{
?>
			  <tr>
			    <td>QA Type</td>
			    <td align="center">:</td>
			    <td><?= $sAuditType ?></td>
			  </tr>
<?
	}
?>

			  <tr>
			   <td>Colors</td>
			    <td align="center">:</td>
			    <td><?= $sColors ?></td>
			  </tr>

<?
	$sSizeTitles = "";

	$sSQL = "SELECT size FROM tbl_sizes WHERE id IN ($sSizes) ORDER BY position";

	if ($objDb->query($sSQL) == true)
	{
		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sSizeTitles .= (", ".$objDb->getField($i, 0));

		$sSizeTitles = substr($sSizeTitles, 2);
	}
?>
			  <tr>
			   <td><?= (($iReportId != 8) ? 'Sizes' : 'Range') ?></td>
			    <td align="center">:</td>
			    <td><?= $sSizeTitles ?></td>
			  </tr>
		    </table>

		    <br />
		    <h2 style="margin:0px;">Pre Production Planning Sections</h2>

		    <div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
			  <table id="SectionsTable" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			    <tr class="sdRowHeader">
				  <td width="50" align="center"><b>#</b></td>
				  <td><b>Section</b></td>
				  <td width="100"><b>options</b></td>
			    </tr>
                            <?
                                $ppmeetingSections = getList("tbl_ppmeeting_sections", "id", "name", "parent_id='0' AND status='A'", "position");
                                $iCounter = 1;

                                foreach($ppmeetingSections as $iSectionId => $sSection){
?>
                                    <tr>
					<td align="center"><?=$iCounter++?></td>
					<td><?=$sSection?></td>
                                        <td>
                                            <a href="quonda/view-ppmeeting-section.php?SectionId=<?= $iSectionId ?>&Id=<?=$Id?>&Edit=N" title="Section : <?= $sSection ?> :: :: width: 900, height: 650"><img src="images/icons/view.gif" width="16" height="16" hspace="1" alt="View:<?=$sSection?>" title="View :<?=$sSection?>" /></a>
                                        </td>
                                    </tr>
<?
                        }
?>
			  </table>
		    </div>

		    <br />
		    <h2>Comments</h2>

		    <table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr valign="top">
			    <td width="90">QA Comments</td>
			    <td width="10" align="center">:</td>
			    <td><?= nl2br($sComments) ?></td>
			  </tr>
		    </table>
