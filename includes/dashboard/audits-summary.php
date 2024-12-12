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
        <h2 style="background:#898989; font-size:24px; font-weight:normal; text-align:center; margin:0px; padding:8px;">LAST <?= $sDays ?> DAYS AUDIT SUMMARY</h2>

<?
	$sConditions = " (audit_date BETWEEN '$sFromDate' AND '$sToDate') AND approved='Y' AND NOT FIND_IN_SET(report_id, '$sQmipReports') ";

	if ($iDepartment > 0)
		$sConditions .= " AND department_id='$iDepartment' ";

	if ($sBrands != "")
		$sConditions .= " AND FIND_IN_SET(brand_id, '$sBrands') ";

	if ($iBrand > 0)
		$sConditions .= " AND brand_id='$iBrand' ";

	if ($sVendors != "")
		$sConditions .= " AND FIND_IN_SET(vendor_id, '$sVendors') ";

	if ($iVendor > 0)
		$sConditions .= " AND vendor_id='$iVendor' ";


	$iPlanned   = getDbValue("COUNT(*)", "tbl_qa_reports", $sConditions);
	$iCompleted = getDbValue("COUNT(*)", "tbl_qa_reports", "$sConditions AND audit_result!=''");
	$iFailed    = getDbValue("COUNT(*)", "tbl_qa_reports", "$sConditions AND FIND_IN_SET(audit_result, 'F,C,R')");
	$iPassed    = getDbValue("COUNT(*)", "tbl_qa_reports", "$sConditions AND FIND_IN_SET(audit_result, 'P,A,B')");
?>
        <div style="margin-bottom:1px;">
          <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr valign="top">
              <td width="25%" align="center">
                <h2 style="background:#b0b0b0; font-size:18px; font-weight:normal; text-align:center; margin:0px; color:#222222; padding:8px;">PLANNED</h2>
                <div style="padding:10px 0px 10px 0px;"><a href="quonda/audit-codes.php?FromDate=<?= $sDate ?>&ToDate=<?= $sDate ?>&Department=<?= $iDepartment ?>&Approved=Y" target="_blank" style="font-size:72px; color:#585858;"><?= str_pad($iPlanned, 2, '0', STR_PAD_LEFT) ?></a></div>
              </td>

              <td width="25%" align="center">
                <h2 style="background:#b0b0b0; font-size:18px; font-weight:normal; text-align:center; margin:0px; color:#222222; padding:8px;">COMPLETED</h2>
                <div style="padding:10px 0px 10px 0px;"><a href="quonda/qa-reports.php?FromDate=<?= $sDate ?>&ToDate=<?= $sDate ?>&Department=<?= $iDepartment ?>" target="_blank" style="font-size:72px; color:#585858;"><?= str_pad($iCompleted, 2, '0', STR_PAD_LEFT) ?></a></div>
              </td>

              <td width="25%" align="center" bgcolor="#ff0f00">
                <h2 style="background:#b0b0b0; font-size:18px; font-weight:normal; text-align:center; margin:0px; color:#222222; padding:8px;">FAILED</h2>
                <div style="padding:10px 0px 10px 0px;"><a href="quonda/qa-reports.php?FromDate=<?= $sDate ?>&ToDate=<?= $sDate ?>&Department=<?= $iDepartment ?>&AuditResult=F" target="_blank" style="font-size:72px; color:#ffffff;"><?= str_pad($iFailed, 2, '0', STR_PAD_LEFT) ?></a></div>
              </td>

              <td width="25%" align="center">
                <h2 style="background:#b0b0b0; font-size:18px; font-weight:normal; text-align:center; margin:0px; color:#222222; padding:8px;">PASSED</h2>
                <div style="padding:10px 0px 10px 0px;"><a href="quonda/qa-reports.php?FromDate=<?= $sDate ?>&ToDate=<?= $sDate ?>&Department=<?= $iDepartment ?>&AuditResult=P" target="_blank" style="font-size:72px; color:#63b200;"><?= str_pad($iPassed, 2, '0', STR_PAD_LEFT) ?></a></div>
              </td>
            </tr>
          </table>
        </div>
