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
	**  Software Engineer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmat Ullah                                                                **
	**      Email :  rehmatullah@3-tree.com                                                      **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id = IO::intValue('Id');

	$sSQL = "SELECT * 
                    FROM tbl_crc_audits WHERE id='$Id'";
	$objDb->query($sSQL);

	if ($objDb->getCount( ) == 1)
	{
		$sAuditDate     = $objDb->getField(0, "audit_date");
		$iVendor        = $objDb->getField(0, "vendor_id");
                $iAuditor       = $objDb->getField(0, "auditor_id");
                $sPSectionId    = $objDb->getField(0, "section_id");
                $sPoints        = $objDb->getField(0, "points");
                $iLanguage      = $objDb->getField(0, "language");
                $iDepartment    = $objDb->getField(0, "department");
                $iShift         = $objDb->getField(0, "no_of_shifts");
                $iPermMen       = $objDb->getField(0, "perm_male");
                $iPermWomen     = $objDb->getField(0, "perm_female"); 
                $iPermYoung     = $objDb->getField(0, "perm_young"); 
                $iTempMen       = $objDb->getField(0, "temp_male"); 
                $iTempWomen     = $objDb->getField(0, "temp_female"); 
                $iTempYoung     = $objDb->getField(0, "temp_young"); 
                $sMgtRep        = $objDb->getField(0, "mgt_representative");
                $sEndDate       = $objDb->getField(0, "audit_end_date");
                $sMgtRepEmail   = $objDb->getField(0, "mgt_rep_email");
                $Observations   = $objDb->getField(0, "observations");


		$sAuditor           = getDbValue("name", "tbl_users", "id='$iAuditor'");
		$sVendor            = getDbValue("vendor", "tbl_vendors", "id='$iVendor'");
                $sDepartmentsList   = getList("tbl_crc_departments", "id", "department");
                $sLanguagesList     = getList("tbl_languages", "id", "language");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">
	  <h2>Audit Details</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	    <tr>
		  <td width="300">Audit Date</td>
		  <td width="20" align="center">:</td>
		  <td><?= formatDate($sAuditDate) ?></td>
	    </tr>

	   <tr>
		  <td>Vendor</td>
		  <td align="center">:</td>
		  <td><?= $sVendor ?></td>
	    </tr>

	    <tr valign="top">
		  <td>Auditor</td>
		  <td align="center">:</td>
		  <td><?= $sAuditor ?></td>
	    </tr>
              
             <tr valign="top">
		  <td>Department</td>
		  <td align="center">:</td>
		  <td><?= $sDepartmentsList[$iDepartment] ?></td>
	    </tr>
              
            <tr valign="top">
		  <td>Language</td>
		  <td align="center">:</td>
		  <td><?= $sLanguagesList[$iLanguage] ?></td>
	    </tr>
              
            <tr valign="top">
		  <td>Number of Shifts</td>
		  <td align="center">:</td>
		  <td><?= "Shift :".$iShift; ?></td>
	    </tr> 
              
            <tr valign="top">
		  <td>Management Representative</td>
		  <td align="center">:</td>
		  <td><?= $sMgtRep ?></td>
	    </tr>
              
            <tr valign="top">
		  <td>Management Representative Email</td>
		  <td align="center">:</td>
		  <td><?= $sMgtRepEmail ?></td>
	    </tr>
              
            <tr valign="top">
		  <td>Audit End Date</td>
		  <td align="center">:</td>
		  <td><?= $sEndDate ?></td>
	    </tr>
              
              
            <tr valign="top">
		  <td>Total Number of Permanent Workers on Audit Date</td>
		  <td align="center">:</td>
		  <td><?= "Male: ".$iPermMen." / Female:".$iPermWomen." / Young:".$iPermYoung ?></td>
	    </tr>
              
              
            <tr valign="top">
		  <td>Total Number of Temporary Workers on Audit Date</td>
		  <td align="center">:</td>
		  <td><?= "Male: ".$iTempMen." / Female:".$iTempWoMen." / Young:".$iTempYoung ?></td>
	    </tr>  
              
            <tr valign="top">
		  <td>General Observations</td>
		  <td align="center">:</td>
		  <td><?= $Observations ?></td>
	    </tr>  
	  </table>

	  <br />

<?
                $sClass        = array("evenRow", "oddRow");
                $sSectionsList    = getList("tbl_tnc_sections s, tbl_tnc_points p", "s.id", "s.section", "s.id = p.section_id AND s.parent_id='$sPSectionId' AND p.id IN ($sPoints)");

                foreach($sSectionsList as $iSection => $sSection)
                {
?>
                    <h2><?=$sSection?></h2>
<? 
                    $sCategoriesList  = getList("tbl_tnc_categories c, tbl_tnc_points p", "c.id", "c.category", "c.id = p.category_id AND c.section_id='$iSection' AND p.id IN ($sPoints)", "c.position");

                    foreach ($sCategoriesList as $iCategory => $sCategory)
                    {
?>
                        <h3><?= $sCategory?></h3>
<?
                                    $sSQL = "SELECT cad.id, cad.score, cad.remarks, tp.point
                             FROM tbl_crc_audit_details cad, tbl_tnc_points tp
                             WHERE cad.point_id=tp.id AND cad.audit_id='$Id' AND tp.category_id='$iCategory'
                             ORDER BY tp.position";
			$objDb->query($sSQL);

			$iCount = $objDb->getCount( );
?>
			    <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
			      <tr bgcolor="#eaeaea">
					<td width="5%"><b>#</b></td>
					<td width="52%"><b>Point</b></td>
					<td width="8%" align="center"><b>Score</b></td>
					<td width="35%"><b>Remarks</b></td>
			      </tr>

<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iPoint   = $objDb->getField($i, 'id');
				$sPoint   = $objDb->getField($i, 'point');
				$iScore   = $objDb->getField($i, 'score');
				$sRemarks = $objDb->getField($i, 'remarks');
?>

				  <tr valign="top" class="<?= $sClass[($i % 2)] ?>">
				    <td align="center"><?= ($i + 1) ?></td>

				    <td>
					  <?= $sPoint ?>
				    </td>

				    <td align="center">
                                       <div style="background:<?= (($iScore == 1) ? '#00ff00' : (($iScore == 0) ? '#ff0000' : '#888888')) ?>; padding:5px; color:#ffffff;"><?= (($iScore == -1) ? "N/A" : $iScore) ?></div>
				    </td>

				    <td><?= $sRemarks ?></td>
				  </tr>
<?
			}
?>
	            </table>    
<?
                    }

                }
?>
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