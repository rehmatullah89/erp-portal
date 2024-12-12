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

	$sConditions = " AND approved='Y' AND audit_result!='' AND audit_type='B' AND report_id!='6' AND NOT FIND_IN_SET(report_id, '$sQmipReports') ";

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



	$sLastAudits  = "0";
	$sTodayAudits = "0";
	$iSamples     = 0;


	$sSQL = "SELECT id FROM tbl_qa_reports WHERE (audit_date BETWEEN '$sFromDate' AND '$sToDate') $sConditions ORDER BY date_time DESC LIMIT 15";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
		$sLastAudits .= (",".$objDb->getField($i, "id"));



	$sSQL = "SELECT id, total_gmts FROM tbl_qa_reports WHERE audit_date=CURDATE( ) $sConditions ORDER BY date_time DESC LIMIT 15";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sTodayAudits .= (",".$objDb->getField($i, "id"));
		$iSamples     += $objDb->getField($i, "total_gmts");
	}


	$iDefects = getDbValue("COALESCE(SUM(defects), 0)", "tbl_qa_report_defects", "FIND_IN_SET(audit_id, '$sTodayAudits')");
?>
<div>
  <div style="padding:15px 0px 10px 15px;">
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
      <tr valign="top">
        <td width="330"><?= $sLogo ?></td>
        <td bgcolor="#9dc01c" align="center"><img src="images/dashboard/quality-outlook.png" width="446" height="42" vspace="22" alt="" title="" /></td>

        <td width="400" bgcolor="#9dc01c">

          <div style="padding-top:5px;">
            <table border="0" cellspacing="0" cellpadding="0" width="75%">
              <tr>
                <td width="45%" align="center" style="color:#ffffff; font-size:42px;"><?= formatNumber($iDefects, false) ?></td>
                <td width="10%" align="center" rowspan="2" style="color:#ffffff; font-size:56px;">/</td>
                <td width="45%" align="center" style="color:#ffffff; font-size:42px;"><?= formatNumber($iSamples, false) ?></td>
              </tr>

              <tr>
                <td align="center" style="color:#ffffff; font-size:14px;">Defective Today</td>
                <td align="center" style="color:#ffffff; font-size:14px;">Evaluated Today</td>
              </tr>
            </table>
          </div>

        </td>
      </tr>
    </table>
  </div>
