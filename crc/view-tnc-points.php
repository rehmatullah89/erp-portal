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
        $objDb2       = new Database( );

        $Id                 = IO::intValue('Id');
        $Nature             = IO::strValue('Tolerance');
        $ParentId           = IO::strValue('ParentId');
        $SectionId          = IO::intValue('SectionId');
        $OrignalAuditId     = getDbValue("follow_up_audit", "tbl_tnc_audits", "id = '$Id'");
        $sAuditorsList      = getList("tbl_users", "id", "name", "designation_id IN (SELECT id FROM tbl_designations WHERE department_id IN (5,15,41))");
        
        $sSections = "";
        $sConditions = "";
        
        if($ParentId > 0)
            $sSections = $SectionId;
        else{
            $SectionsList = getList("tbl_tnc_sections", "id", "id", "parent_id='$SectionId'");
            $sSections    = implode(',', $SectionsList);
        }

        if(in_array($Nature, array('C','Z')))
            $sConditions = " AND tp.nature='$Nature' AND tad.score='0' ";
        else if($Nature == 'A')
            $sConditions = " AND tad.score = '0' ";
        
        $iCurrentPointsList = getList("tbl_tnc_audits ta, tbl_tnc_audit_details tad, tbl_tnc_points tp, tbl_tnc_sections ts", "tp.id", "tp.id", "ta.id = tad.audit_id AND tad.point_id = tp.id AND ts.id=tp.section_id AND ta.id='$Id' $sConditions AND tp.section_id IN ($sSections)");
        
        if($OrignalAuditId > 0){
            
            $FollowUpAuditDate   = getDbValue("audit_date", "tbl_tnc_audits", "id = '$Id'");
            $FollowUpAuditor     = getDbValue("auditors", "tbl_tnc_audits", "id = '$Id'");
            $iFollowUpAuditors   = explode(",", $FollowUpAuditor);
            $sFollowUpAuditors   = "";
            
            foreach ($iFollowUpAuditors as $iAuditor)
                $sFollowUpAuditors .= ($sAuditorsList[$iAuditor]."<br />");
            
            $iPreviousPointsList = getList("tbl_tnc_audits ta, tbl_tnc_audit_details tad, tbl_tnc_points tp, tbl_tnc_sections ts", "tp.id", "tp.id", "ta.id = tad.audit_id AND tad.point_id = tp.id AND ts.id=tp.section_id AND ta.id='$OrignalAuditId' $sConditions AND tp.section_id IN ($sSections)");
            $Union      = array_merge($iCurrentPointsList, $iPreviousPointsList);
            
            //for older & new points comparison recevied for both 
            $sSQL = "SELECT ta.audit_date, tp.point, tad.score, tad.remarks, tad.cap, tp.point_no, tp.id as _PointId, ta.vendor_id, ta.auditors,
                            (SELECT section from tbl_tnc_sections where id=tp.section_id) as _Section,
                            (Select GROUP_CONCAT(picture SEPARATOR ', ') from tbl_tnc_audit_pictures where audit_id IN ({$OrignalAuditId},{$Id}) AND point_id=_PointId) AS _Pictures  
                            FROM tbl_tnc_audits ta, tbl_tnc_audit_details tad, tbl_tnc_points tp
                            WHERE ta.id = tad.audit_id AND tad.point_id = tp.id AND tad.audit_id IN({$OrignalAuditId},{$Id}) AND tp.id IN (".implode(",", $Union).")
                            Group By tad.audit_id, _PointId
                            Order By _PointId";
            
        }else{
            
            $sSQL = "SELECT ta.audit_date, ta.follow_up_audit, tp.id as _PointId,ta.vendor_id, ta.auditors, tp.point_no, tp.point, ts.section as _Section, tad.score, tad.remarks, tad.cap,
                    (Select GROUP_CONCAT(picture SEPARATOR ', ') from tbl_tnc_audit_pictures where audit_id='$Id' AND point_id=_PointId) AS _Pictures  
                    FROM tbl_tnc_audits ta, tbl_tnc_audit_details tad, tbl_tnc_points tp, tbl_tnc_sections ts
                    WHERE ta.id = tad.audit_id AND tad.point_id = tp.id AND ts.id=tp.section_id AND ta.id='$Id' $sConditions AND tp.section_id IN ($sSections)";

        }
        $objDb->query($sSQL);
        $iCount = $objDb->getCount( );
        
        $sAuditDate     = $objDb->getField(0, "audit_date");
        $iVendor        = $objDb->getField(0, "vendor_id");
        $iAuditors      = $objDb->getField(0, "auditors");
        
        $sVendor        = getDbValue("vendor", "tbl_vendors", "id='$iVendor'");
        $iAuditors      = @explode(",", $iAuditors);
        $sAuditors      = "";
        
        
        foreach ($iAuditors as $iAuditor)
                $sAuditors .= ($sAuditorsList[$iAuditor]."<br />");
            
        if(empty($iCount)){
            echo "<h4>No Data Found</h4>";
            exit;
        }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>

<style>
    .evenRow {
        background-color: lightgrey;
        color: black;
      }

    .oddRow {
        background-color: #ADEBAD;
        color: black;
    }    
    
    .foo {
        width: 20px;
        height: 20px;
        margin: 5px;
        border: 1px solid rgba(0, 0, 0, .2);
    }

</style>
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">

<!--  Body Section Starts Here  -->
	<div id="Body">
	  <h2>Audit Details</h2>

	  <table border="0" cellpadding="3" cellspacing="0" width="100%">

<?      if($OrignalAuditId > 0){    ?>
            <tr>
		  <td width="165">Original Audit Date</td>
		  <td width="20" align="center">:</td>
		  <td width="130"><?= formatDate($sAuditDate) ?></td>
                  <td><div class="foo evenRow"></div></td>
                  
                  <td width="165">Follow-Up Audit Date</td>
		  <td width="20" align="center">:</td>
		  <td width="130"><?= formatDate($FollowUpAuditDate) ?></td>
                  <td><div class="foo oddRow"></div></td>
	    </tr>

	    <tr valign="top">
		  <td>Original Auditor(s)</td>
		  <td align="center">:</td>
		  <td><?= $sAuditors ?></td>
                  <td>&nbsp;</td>
                  
                  <td>Follow-Up Auditor(s)</td>
		  <td align="center">:</td>
		  <td><?= $sFollowUpAuditors ?></td>
                  <td>&nbsp;</td>
	    </tr>
              
            <tr>
		  <td>Vendor</td>
		  <td align="center">:</td>
		  <td><?= $sVendor ?></td>
	    </tr>  
<?      }else{?>              
	    <tr>
		  <td width="165">Audit Date</td>
		  <td width="20" align="center">:</td>
		  <td><?= formatDate($sAuditDate) ?></td>
	    </tr>

	   <tr>
		  <td>Vendor</td>
		  <td align="center">:</td>
		  <td><?= $sVendor ?></td>
	    </tr>

	    <tr valign="top">
		  <td>Auditor(s)</td>
		  <td align="center">:</td>
		  <td><?= $sAuditors ?></td>
	    </tr>
<?      }?>              
	  </table>

	  <br />

          <table border="1" bordercolor="#aaaaaa" cellpadding="5" cellspacing="0" width="100%">
		<tr bgcolor="#eaeaea">
		  <td width="5%"><b>#</b></td>
                  <td width="15%"><b>Section</b></td> 
		  <td width="32%"><b>Point</b></td>
		  <td width="8%" align="center"><b>Score</b></td>
		  <td width="20%"><b>Remarks</b></td>
                  <td width="20%"><b>CAP</b></td>
		</tr>

<?
        $sFlag         = true;
	$sClass        = array("evenRow", "oddRow");
        
        for ($i = 0; $i < $iCount; $i ++)
	{
            $iPoint    = $objDb->getField($i, '_PointId');
            $sPoint    = $objDb->getField($i, 'point');
            $iPointNo  = $objDb->getField($i, 'point_no');
            $sSection  = $objDb->getField($i, '_Section');
            $iScore    = $objDb->getField($i, 'score');
            $sRemarks  = $objDb->getField($i, 'remarks');
            $sCap      = $objDb->getField($i, 'cap');
            $sPictures = $objDb->getField($i, '_Pictures');
            $iPictures = explode(", ", $sPictures);
            $sAuditDate = $objDb->getField($i, "audit_date");
            
            @list($sYear, $sMonth, $sDay) = @explode("-", $sAuditDate);

            @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear), 0777);
            @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth), 0777);
            @mkdir(($sBaseDir.TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay), 0777);

            $sTncDir = (TNC_PICS_DIR.$sYear."/".$sMonth."/".$sDay."/");
            
?>

	    <tr valign="top" class="<?= ($OrignalAuditId > 0)?($sClass[($i % 2)]):"evenRow"; ?>">
                <td align="center"><?= ($OrignalAuditId > 0)?ceil(($i + 1)/2):($i + 1); ?></td>
                  <td><?= $sSection ?></td>
		  <td><?= "(P#{$iPointNo})- ".$sPoint ?></td>
		  <td align="center"><div style="background:<?= (($iScore == 1) ? '#99CC00' : (($iScore == 0) ? '#ff0000' : '#888888')) ?>; padding:5px; color:#ffffff;"><?= (($iScore == -1) ? "N/A" : $iScore) ?></div></td>
		  <td><? echo  nl2br($sRemarks).'<br/><br/>';
                  foreach($iPictures as $sPicture){
                    if(!@file_exists($sTncDir.$sPicture) && !empty($sPicture)){
                    ?>
                    <a href="<?= ($sTncDir.$sPicture) ?>" class="lightview" rel="gallery[defects]" title="<?= 'Point#'.$sPoint ?> :: :: topclose: true"><img src="<?= ($sTncDir.$sPicture) ?>" alt="" title="" width="100%"/></a><br/><br/>
                    <?}
                  }?>
                  </td>
                  <td><?=  nl2br($sCap)?></td>
	    </tr>
<?
	}
?>
	  </table>
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