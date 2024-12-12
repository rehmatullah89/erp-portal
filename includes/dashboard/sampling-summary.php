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

	$sConditions = "";

	if ($sBrands != "")
		$sConditions .= " AND FIND_IN_SET(s.sub_brand_id, '$sBrands') ";

	else if ($iDepartment > 0)
	{
		$sBrands = getDbValue("brands", "tbl_departments", "id='$iDepartment'");

		$sConditions .= " AND AND FIND_IN_SET(s.sub_brand_id, '$sBrands') ";
	}

	if ($iBrand > 0)
		$sConditions .= " AND s.sub_brand_id='$iBrand' ";



	$sSQL = "SELECT SUM(IF(m.status='R',1,0)) AS _Failed, SUM(IF(m.status='W',0,1)) AS _Inspected
			 FROM tbl_comment_sheets c, tbl_merchandisings m, tbl_styles s
			 WHERE m.style_id=s.id AND m.id=c.merchandising_id AND DATE_FORMAT(c.created, '%Y-%m-%d')=CURDATE( ) $sConditions";
	$objDb->query($sSQL);

	$iFailed    = $objDb->getField(0, "_Failed");
	$iInspected = $objDb->getField(0, "_Inspected");
?>
<div>
  <div style="padding:15px 0px 10px 15px;">
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
      <tr valign="top">
        <td width="330"><?= $sLogo ?></td>
        <td bgcolor="#9dc01c" align="center"><img src="images/dashboard/sampling-summary.jpg" width="550" height="50" vspace="18" alt="" title="" /></td>

        <td width="400" bgcolor="#9dc01c">

          <div style="padding-top:5px;">
            <table border="0" cellspacing="0" cellpadding="0" width="75%">
              <tr>
                <td width="45%" align="center" style="color:#ffffff; font-size:42px;"><?= formatNumber($iFailed, false) ?></td>
                <td width="10%" align="center" rowspan="2" style="color:#ffffff; font-size:56px;">/</td>
                <td width="45%" align="center" style="color:#ffffff; font-size:42px;"><?= formatNumber($iInspected, false) ?></td>
              </tr>

              <tr>
                <td align="center" style="color:#ffffff; font-size:14px;">Failed Today</td>
                <td align="center" style="color:#ffffff; font-size:14px;">Inspected Today</td>
              </tr>
            </table>
          </div>

        </td>
      </tr>
    </table>
  </div>
