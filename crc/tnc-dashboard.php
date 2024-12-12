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
	$objDb2      = new Database( );

	
	if ($Referer == "")
		$Referer = $_SERVER['HTTP_REFERER'];
        
        $Vendor        = IO::intValue("Vendor");
        $Section       = IO::intValue("Section");
        $AuditVendors  = getList("tbl_tnc_audits", "id", "vendor_id");
        $sSectionsList = getList("tbl_tnc_sections", "id", "section", "parent_id='0'", "id");
        $sVendorsList  = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND id IN (".implode(',', $AuditVendors).") AND parent_id='0' AND sourcing='Y'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

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
			    <h1>CRC Dashboard</h1>
                               
                            <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="52">Vendor</td>

			          <td width="180">
			            <select name="Vendor" style="width:180px;">
			              <option value="">All Vendors</option>
<?
	foreach ($sVendorsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>
                                  
                                <td width="52">Section</td>
                                  <td width="180">
			            <select name="Section" style="width:180px;">
			              <option value="">All Sections</option>
<?
	foreach ($sSectionsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Section) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
			            </select>
			          </td>
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </div>
			    </form>
  
                            <div class="tblSheet">
<?                          if($Vendor > 0){ ?>
                                  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" style="text-align: center;">
                                  <tr class="headerRow">
                                      <td width="25%"  rowspan="2">SECTION</td>
				      <td width="25%" rowspan="2">ZERO TOLERANCE ISSUES FOUND</td>
				      <td width="19%" rowspan="2">CRITICAL ISSUES FOUND</td>
				      <td width="22%" colspan="2">Points</td>
  				      <td width="9%">RATING%</td>
				  </tr>
                                  <tr class="headerRow">
                                      <td width="11%">ACTUAL (A)</td>
                                      <td width="11%">POSSIBLE (P)</td>
  				      <td width="10%">(A/P X100)</td>
				  </tr>
<?
				//$iAuditId = getDbValue("id", "tbl_tnc_audits", "vendor_id='$Vendor'  AND (follow_up_audit = '0' OR follow_up_audit = '' OR follow_up_audit IS NULL)", "audit_date DESC");
								
                                $sSQL = "SELECT ta.id, ta.audit_date, ta.follow_up_audit, SUM(IF(tp.nature='Z' AND tad.score=0, 1, 0)) as _ZeroTolerancePoints, 
												SUM(IF(tp.nature='C' AND tad.score=0, 1, 0)) as _CriticalPoints,
												SUM(IF(tad.score='1', 1, 0)) as _ActualPoints,
												SUM(IF(tad.score!='-1', 1, 0)) as  _PossiblePoints,
												tp.section_id as _SectionId,
												ts.parent_id as _ParentId  
                                 FROM tbl_tnc_audits ta, tbl_tnc_audit_details tad, tbl_tnc_points tp, tbl_tnc_sections ts
                                 WHERE ta.id = tad.audit_id AND tad.point_id = tp.id AND ts.id=tp.section_id AND ta.vendor_id = '$Vendor'
				 Group By tp.section_id, ta.id
                                 Order By tp.section_id";
                                
                                $objDb->query($sSQL);
                                $iCount = $objDb->getCount( );

                                $SocialCompliancePoints = array();
                                $FireSafetyPoints = array();
                                $SubContractingPoints = array();
                                $FactorySecurityPoints = array();
                                $FactorProductivityPoints = array();
                                $sAuditsList = array();

                                for($i=0; $i<$iCount; $i++){
                                    
                                    $iAuditId   = $objDb->getField($i, 'id');
                                    $sAuditDate = $objDb->getField($i, 'audit_date');
                                    $iFollowUp  = $objDb->getField($i, 'follow_up_audit');
                                    
                                    $sAuditsList[$iAuditId] = array('audit_date' => $sAuditDate, 'follow_up' => $iFollowUp);
                                    
                                    if($objDb->getField($i, '_SectionId') == 5){
                                        $FireSafetyPoints[$iAuditId] = array('zPoints' => $objDb->getField($i, '_ZeroTolerancePoints'),'cPoints' => $objDb->getField($i, '_CriticalPoints'),'aPoints' => $objDb->getField($i, '_ActualPoints'),'pPoints' => $objDb->getField($i, '_PossiblePoints'));
                                    }
                                    
                                    if($objDb->getField($i, '_SectionId') == 6){
                                        $SubContractingPoints[$iAuditId] = array('zPoints' => $objDb->getField($i, '_ZeroTolerancePoints'),'cPoints' => $objDb->getField($i, '_CriticalPoints'),'aPoints' => $objDb->getField($i, '_ActualPoints'),'pPoints' => $objDb->getField($i, '_PossiblePoints'));
                                    }
                                    
                                    if($objDb->getField($i, '_ParentId') == 7){
                                        
                                        $SocialCompliancePoints[$iAuditId]['zPoints'] += @$objDb->getField($i, '_ZeroTolerancePoints');
                                        $SocialCompliancePoints[$iAuditId]['cPoints'] += @$objDb->getField($i, '_CriticalPoints');
                                        $SocialCompliancePoints[$iAuditId]['aPoints'] += @$objDb->getField($i, '_ActualPoints');
                                        $SocialCompliancePoints[$iAuditId]['pPoints'] += @$objDb->getField($i, '_PossiblePoints'); 
                                                
                                        $SocialCompliancePoints[$iAuditId] = array('zPoints' => $SocialCompliancePoints[$iAuditId]['zPoints'],'cPoints' =>$SocialCompliancePoints[$iAuditId]['cPoints'] ,'aPoints' => $SocialCompliancePoints[$iAuditId]['aPoints'],'pPoints' => $SocialCompliancePoints[$iAuditId]['pPoints']);
                                    }
                                    
                                    if($objDb->getField($i, '_ParentId') == 8){
                                        
                                        $FactorySecurityPoints[$iAuditId]['zPoints'] += @$objDb->getField($i, '_ZeroTolerancePoints');
                                        $FactorySecurityPoints[$iAuditId]['cPoints'] += @$objDb->getField($i, '_CriticalPoints');
                                        $FactorySecurityPoints[$iAuditId]['aPoints'] += @$objDb->getField($i, '_ActualPoints');
                                        $FactorySecurityPoints[$iAuditId]['pPoints'] += @$objDb->getField($i, '_PossiblePoints'); 
                                                
                                        $FactorySecurityPoints[$iAuditId] = array('zPoints' => $FactorySecurityPoints[$iAuditId]['zPoints'],'cPoints' =>$FactorySecurityPoints[$iAuditId]['cPoints'] ,'aPoints' => $FactorySecurityPoints[$iAuditId]['aPoints'],'pPoints' => $FactorySecurityPoints[$iAuditId]['pPoints']);
                                    }
                                    
                                    if($objDb->getField($i, '_ParentId') == 17){
                                        
                                        $FactorProductivityPoints[$iAuditId]['zPoints'] += @$objDb->getField($i, '_ZeroTolerancePoints');
                                        $FactorProductivityPoints[$iAuditId]['cPoints'] += @$objDb->getField($i, '_CriticalPoints');
                                        $FactorProductivityPoints[$iAuditId]['aPoints'] += @$objDb->getField($i, '_ActualPoints');
                                        $FactorProductivityPoints[$iAuditId]['pPoints'] += @$objDb->getField($i, '_PossiblePoints'); 
                                                
                                        $FactorProductivityPoints[$iAuditId] = array('zPoints' => $FactorProductivityPoints[$iAuditId]['zPoints'],'cPoints' =>$FactorProductivityPoints[$iAuditId]['cPoints'] ,'aPoints' => $FactorProductivityPoints[$iAuditId]['aPoints'],'pPoints' => $FactorProductivityPoints[$iAuditId]['pPoints']);
                                    }
                                    
                                }
                                
?>
<?              foreach($sAuditsList as $iAuditId => $sAudit){    ?>                                      
                                <tr class="evenRow">
                                    <? 
                                        $Color = '';
                                        $Percentage = formatNumber(($SocialCompliancePoints[$iAuditId]['aPoints']/$SocialCompliancePoints[$iAuditId]['pPoints'])*100);
                                        if($SocialCompliancePoints[$iAuditId]['zPoints'] > 0)
                                            $Color = '#FF0000';
                                        else{
                                            if($SocialCompliancePoints[$iAuditId]['cPoints'] == 0 && $Percentage >= 85){
                                                $Color = '#99CC00';
                                            }else if($SocialCompliancePoints[$iAuditId]['cPoints'] == 0 && ($Percentage >= 70 && $Percentage <= 84)){
                                                $Color = '#FFFF00';
                                            }else if($SocialCompliancePoints[$iAuditId]['cPoints'] > 0 || $Percentage <= 69){
                                                $Color = '#FFCC00';
                                            }                                            
                                        }
                                        
                                        if($SocialCompliancePoints[$iAuditId]['aPoints'] == "" || $SocialCompliancePoints[$iAuditId]['pPoints'] == "")
                                            $Color = '';
                                    
                                    ?>
                                    <td><a href="crc/export-tnc-points.php?ParentId=0&SectionId=7&VendorId=<?=$Vendor?>&AuditId=<?=$iAuditId?>">Social Compliance</a><br/><span style="color:red;"> <?= ($sAudit['follow_up']>0?"Follow-Up Date:":"").$sAudit['audit_date']?></span><?=($sAudit['follow_up']>0?"<br/>Orignal Date: ".getDbValue("audit_date", "tbl_tnc_audits", "id={$sAudit['follow_up']}"):"")?></td>
                                    
                                    <td><?
                                        if($SocialCompliancePoints[$iAuditId]['zPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=Z&ParentId=0&SectionId=7" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$SocialCompliancePoints[$iAuditId]['zPoints'];?></a>
    <?                                  }else{
                                            echo $SocialCompliancePoints[$iAuditId]['zPoints'];
                                        }?>
                                    </td>
                                    <td>
                                        <?
                                        if($SocialCompliancePoints[$iAuditId]['cPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=C&ParentId=0&SectionId=7" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$SocialCompliancePoints[$iAuditId]['cPoints'];?></a>
    <?                                  }else{
                                            echo $SocialCompliancePoints[$iAuditId]['cPoints'];
                                        }?>
                                    </td>
                                    
                                    <td>
                                        <?
                                        if($SocialCompliancePoints[$iAuditId]['aPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=A&ParentId=0&SectionId=7" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$SocialCompliancePoints[$iAuditId]['aPoints'];?></a>
    <?                                  }else{
                                            echo $SocialCompliancePoints[$iAuditId]['aPoints'];
                                        }?>
                                    </td>
                                    <td><?=$SocialCompliancePoints[$iAuditId]['pPoints']?></td>
                                    <td style="background: <?= $Color?>"><?= $Percentage.'%' ?></td>
                                </tr>
<?          }      ?>   
                                      
<?              foreach($sAuditsList as $iAuditId => $sAudit){    ?> 
                                <tr class="oddRow">
                                    <? 
                                        $Color = '';
                                        $Percentage = formatNumber(($FireSafetyPoints[$iAuditId]['aPoints']/$FireSafetyPoints[$iAuditId]['pPoints'])*100);
                                        if($FireSafetyPoints[$iAuditId]['zPoints'] > 0)
                                            $Color = '#FF0000';
                                        else{
                                            if($FireSafetyPoints[$iAuditId]['cPoints'] == 0 && $Percentage >= 85){
                                                $Color = '#99CC00';
                                            }else if($FireSafetyPoints[$iAuditId]['cPoints'] == 0 && ($Percentage >= 70 && $Percentage <= 84)){
                                                $Color = '#FFFF00';
                                            }else if($FireSafetyPoints[$iAuditId]['cPoints'] > 0 || $Percentage <= 69){
                                                $Color = '#FFCC00';
                                            }                                            
                                        }
                                        
                                        if($FireSafetyPoints[$iAuditId]['aPoints'] == "" || $FireSafetyPoints[$iAuditId]['pPoints'] == "")
                                            $Color = '';
                                    
                                    ?>
                                    <td><a href="crc/export-tnc-points.php?ParentId=7&SectionId=5&VendorId=<?=$Vendor?>&AuditId=<?=$iAuditId?>">Fire Safety (Social Compliance)</a><br/><span style="color:red;"> <?= ($sAudit['follow_up']>0?"Follow-Up Date:":"").$sAudit['audit_date']?></span><?=($sAudit['follow_up']>0?"<br/>Orignal Date: ".getDbValue("audit_date", "tbl_tnc_audits", "id={$sAudit['follow_up']}"):"")?></td>
                                    <td><?
                                        if($FireSafetyPoints[$iAuditId]['zPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=Z&ParentId=7&SectionId=5" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$FireSafetyPoints[$iAuditId]['zPoints'];?></a>
    <?                                  }else{
                                            echo $FireSafetyPoints[$iAuditId]['zPoints'];
                                        }?>
                                    </td>
                                    <td>
                                        <?
                                        if($FireSafetyPoints[$iAuditId]['cPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=C&ParentId=7&SectionId=5" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$FireSafetyPoints[$iAuditId]['cPoints'];?></a>
    <?                                  }else{
                                            echo $FireSafetyPoints[$iAuditId]['cPoints'];
                                        }?>
                                    </td>
                                    <td>
                                        <?
                                        if($FireSafetyPoints[$iAuditId]['aPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=A&ParentId=7&SectionId=5" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$FireSafetyPoints[$iAuditId]['aPoints'];?></a>
    <?                                  }else{
                                            echo $FireSafetyPoints[$iAuditId]['aPoints'];
                                        }?>
                                    </td>
                                    <td><?=$FireSafetyPoints[$iAuditId]['pPoints']?></td><td style="background: <?= $Color?>"><?= $Percentage.'%' ?></td>
                                </tr>
 <?          }      ?>             
                                      
 <?         foreach($sAuditsList as $iAuditId => $sAudit){    ?>                                
                                <tr class="evenRow">
                                    <? 
                                        $Color = '';
                                        $Percentage = formatNumber(($SubContractingPoints[$iAuditId]['aPoints']/$SubContractingPoints[$iAuditId]['pPoints'])*100);
                                        if($SubContractingPoints[$iAuditId]['zPoints'] > 0)
                                            $Color = '#FF0000';
                                        else{
                                            if($SubContractingPoints[$iAuditId]['cPoints'] == 0 && $Percentage >= 85){
                                                $Color = '#99CC00';
                                            }else if($SubContractingPoints[$iAuditId]['cPoints'] == 0 && ($Percentage >= 70 && $Percentage <= 84)){
                                                $Color = '#FFFF00';
                                            }else if($SubContractingPoints[$iAuditId]['cPoints'] > 0 || $Percentage <= 69){
                                                $Color = '#FFCC00';
                                            }                                            
                                        }
                                        
                                        if($SubContractingPoints[$iAuditId]['aPoints'] == "" || $SubContractingPoints[$iAuditId]['pPoints'] == "")
                                            $Color = '';
                                    
                                    ?>
                                    <td><a href="crc/export-tnc-points.php?ParentId=7&SectionId=6&VendorId=<?=$Vendor?>&AuditId=<?=$iAuditId?>">Subcontracting (Social Compliance)</a><br/><span style="color:red;"> <?= ($sAudit['follow_up']>0?"Follow-Up Date:":"").$sAudit['audit_date']?></span><?=($sAudit['follow_up']>0?"<br/>Orignal Date: ".getDbValue("audit_date", "tbl_tnc_audits", "id={$sAudit['follow_up']}"):"")?></td>
                                    <td><?
                                        if($SubContractingPoints[$iAuditId]['zPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=Z&ParentId=7&SectionId=6" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$SubContractingPoints[$iAuditId]['zPoints'];?></a>
    <?                                  }else{
                                            echo $SubContractingPoints[$iAuditId]['zPoints'];
                                        }?>
                                    </td>
                                    <td>
                                        <?
                                        if($SubContractingPoints[$iAuditId]['cPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=C&ParentId=7&SectionId=6" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$SubContractingPoints[$iAuditId]['cPoints'];?></a>
    <?                                  }else{
                                            echo $SubContractingPoints[$iAuditId]['cPoints'];
                                        }?>
                                    </td>
                                    <td>
                                        <?
                                        if($SubContractingPoints[$iAuditId]['aPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=A&ParentId=7&SectionId=6" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$SubContractingPoints[$iAuditId]['aPoints'];?></a>
    <?                                  }else{
                                            echo $SubContractingPoints[$iAuditId]['aPoints'];
                                        }?>
                                    </td>
                                    <td><?=$SubContractingPoints[$iAuditId]['pPoints']?></td><td style="background: <?= $Color?>"><?= $Percentage.'%' ?></td>
                                </tr>
<?          }      ?>         
                                      
<?              foreach($sAuditsList as $iAuditId => $sAudit){    ?>                                
                                <tr class="oddRow">
                                    <? 
                                        $Color = '';
                                        $Percentage = formatNumber(($FactorySecurityPoints[$iAuditId]['aPoints']/$FactorySecurityPoints[$iAuditId]['pPoints'])*100);
                                        if($FactorySecurityPoints[$iAuditId]['zPoints'] > 0)
                                            $Color = '#FF0000';
                                        else{
                                            if($FactorySecurityPoints[$iAuditId]['cPoints'] == 0 && $Percentage >= 85){
                                                $Color = '#99CC00';
                                            }else if($FactorySecurityPoints[$iAuditId]['cPoints'] == 0 && ($Percentage >= 70 && $Percentage <= 84)){
                                                $Color = '#FFFF00';
                                            }else if($FactorySecurityPoints[$iAuditId]['cPoints'] > 0 || $Percentage <= 69){
                                                $Color = '#FFCC00';
                                            }                                            
                                        }
                                    
                                        if($FactorySecurityPoints[$iAuditId]['aPoints'] == "" || $FactorySecurityPoints[$iAuditId]['pPoints'] == "")
                                            $Color = '';
                                    ?>
                                    <td><a href="crc/export-tnc-points.php?ParentId=0&SectionId=8&VendorId=<?=$Vendor?>&AuditId=<?=$iAuditId?>">Factory Security</a><br/><span style="color:red;"> <?= ($sAudit['follow_up']>0?"Follow-Up Date:":"").$sAudit['audit_date']?></span><?=($sAudit['follow_up']>0?"<br/>Orignal Date: ".getDbValue("audit_date", "tbl_tnc_audits", "id={$sAudit['follow_up']}"):"")?></td>
                                    <td><?
                                        if($FactorySecurityPoints[$iAuditId]['zPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=Z&ParentId=0&SectionId=8" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$FactorySecurityPoints[$iAuditId]['zPoints'];?></a>
    <?                                  }else{
                                            echo $FactorySecurityPoints[$iAuditId]['zPoints'];
                                        }?>
                                    </td>
                                    <td>
                                        <?
                                        if($FactorySecurityPoints[$iAuditId]['cPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=C&ParentId=0&SectionId=8" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$FactorySecurityPoints[$iAuditId]['cPoints'];?></a>
    <?                                  }else{
                                            echo $FactorySecurityPoints[$iAuditId]['cPoints'];
                                        }?>
                                    </td>
                                    <td>
                                        <?
                                        if($FactorySecurityPoints[$iAuditId]['aPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=A&ParentId=0&SectionId=8" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$FactorySecurityPoints[$iAuditId]['aPoints'];?></a>
    <?                                  }else{
                                            echo $FactorySecurityPoints[$iAuditId]['aPoints'];
                                        }?>
                                    </td>
                                    <td><?=$FactorySecurityPoints[$iAuditId]['pPoints']?></td><td style="background: <?= $Color?>"><?= $Percentage.'%' ?></td>
                                </tr >
<?          }      ?> 
                                
<?              foreach($sAuditsList as $iAuditId => $sAudit){    ?>                                   
                                <tr class="evenRow">
                                                                        <? 
                                        $Color = '';
                                        $Percentage = formatNumber(($FactorProductivityPoints[$iAuditId]['aPoints']/$FactorProductivityPoints[$iAuditId]['pPoints'])*100);
                                        if($FactorProductivityPoints[$iAuditId]['zPoints'] > 0)
                                            $Color = '#FF0000';
                                        else{
                                            if($FactorProductivityPoints[$iAuditId]['cPoints'] == 0 && $Percentage >= 85){
                                                $Color = '#99CC00';
                                            }else if($FactorProductivityPoints[$iAuditId]['cPoints'] == 0 && ($Percentage >= 70 && $Percentage <= 84)){
                                                $Color = '#FFFF00';
                                            }else if($FactorProductivityPoints[$iAuditId]['cPoints'] > 0 || $Percentage <= 69){
                                                $Color = '#FFCC00';
                                            }                                            
                                        }
                                        
                                        if($FactorProductivityPoints[$iAuditId]['aPoints'] == "" || $FactorProductivityPoints[$iAuditId]['pPoints'] == "")
                                            $Color = '';
                                    
                                    ?>
                                    <td><a href="crc/export-tnc-points.php?ParentId=0&SectionId=17&VendorId=<?=$Vendor?>&AuditId=<?=$iAuditId?>">Factory Productivity</a><br/><span style="color:red;"> <?= ($sAudit['follow_up']>0?"Follow-Up Date:":"").$sAudit['audit_date']?></span><?=($sAudit['follow_up']>0?"<br/>Orignal Date: ".getDbValue("audit_date", "tbl_tnc_audits", "id={$sAudit['follow_up']}"):"")?></td>
                                    <td><?
                                        if($FactorProductivityPoints[$iAuditId]['zPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=Z&ParentId=0&SectionId=17" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$FactorProductivityPoints[$iAuditId]['zPoints'];?></a>
    <?                                  }else{
                                            echo $FactorProductivityPoints[$iAuditId]['zPoints'];
                                        }?>
                                    </td>
                                    <td>
                                        <?
                                        if($FactorProductivityPoints[$iAuditId]['cPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=C&ParentId=0&SectionId=17" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$FactorProductivityPoints[$iAuditId]['cPoints'];?></a>
    <?                                  }else{
                                            echo $FactorProductivityPoints[$iAuditId]['cPoints'];
                                        }?>
                                    </td>
                                    <td>
                                        <?
                                        if($FactorProductivityPoints[$iAuditId]['aPoints'] > 0 ){ ?>
                                            <a href="crc/view-tnc-points.php?Id=<?= $iAuditId ?>&Tolerance=A&ParentId=0&SectionId=17" class="lightview" rel="iframe" title="T&C Audit :: :: width: 900, height: 650"><?=$FactorProductivityPoints[$iAuditId]['aPoints'];?></a>
    <?                                  }else{
                                            echo $FactorProductivityPoints[$iAuditId]['aPoints'];
                                        }?>
                                    </td>
                                    <td><?=$FactorProductivityPoints[$iAuditId]['pPoints']?></td><td style="background: <?= $Color?>"><?= $Percentage.'%' ?></td>
                                </tr>
<?          }      ?>                                 
                                  
                              </table>
<?                          }  
                            else{
                                
                                $sClass  = array("evenRow", "oddRow");
                                if($Section > 0)
                                    $sSectionsList = getList("tbl_tnc_sections", "id", "section", "parent_id='0' AND id={$Section}");

                                foreach ($sSectionsList as $SectionId => $SectionName){
                                    
                                    $sSQL = "SELECT SUM(IF(tp.nature='Z' AND tad.score=0, 1, 0)) as _ZeroTolerancePoints, 
                                                    SUM(IF(tp.nature='C' AND tad.score=0, 1, 0)) as _CriticalPoints,
                                                    SUM(IF(tad.score='1', 1, 0)) as _ActualPoints,
                                                    SUM(IF(tad.score!='-1', 1, 0)) as  _PossiblePoints,
                                                    ta.vendor_id,
                                                    (Select vendor from tbl_vendors where id=ta.vendor_id) AS _Vendor
                                    FROM tbl_tnc_audits ta, tbl_tnc_audit_details tad, tbl_tnc_points tp, tbl_tnc_sections ts
                                    WHERE ta.id = tad.audit_id AND tad.point_id = tp.id AND ts.id=tp.section_id AND ts.parent_id='$SectionId' AND ta.id IN (SELECT MAX(id) from tbl_tnc_audits where vendor_id=ta.vendor_id)
                                    Group By  ta.vendor_id ";
                                    
                                $objDb->query($sSQL);
                                $iCount = $objDb->getCount( );
                                
?>
                                    <h2 style="margin-bottom:0px;"><?= $SectionName ?></h2>
                                    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" style="text-align: center;">
                                        <tr style="background: grey; font-weight: bold;">
                                          <td width="25%">Vendor</td>
                                          <td width="25%">Score</td>
                                          <td width="19%">Total Score</td>
                                          <td width="9%">RATING%</td>
                                      </tr>
<?
                                    for($i=0; $i < $iCount ; $i++){ 
                                        
                                        $iZTPoints       = $objDb->getField($i, '_ZeroTolerancePoints');
                                        $iCriticalPoints = $objDb->getField($i, '_CriticalPoints');
                                        $iActualPoints   = $objDb->getField($i, '_ActualPoints');
                                        $iPossiblePoint  = $objDb->getField($i, '_PossiblePoints');
                                        $sVendor         = $objDb->getField($i, '_Vendor');
                                        $iVendor         = $objDb->getField($i, 'vendor_id');
                                        
                                        $Color = '';
                                        $Percentage = formatNumber(($iActualPoints/$iPossiblePoint)*100);
                                        if($iZTPoints > 0)
                                            $Color = '#FF0000';
                                        else{
                                            if($iCriticalPoints == 0 && $Percentage >= 85){
                                                $Color = '#99CC00';
                                            }else if($iCriticalPoints == 0 && ($Percentage >= 70 && $Percentage <= 84)){
                                                $Color = '#FFFF00';
                                            }else if($iCriticalPoints > 0 || $Percentage <= 69){
                                                $Color = '#FFCC00';
                                            }                                            
                                        }
                                        
                                        if($iActualPoints == "" || $iPossiblePoint == "")
                                            $Color = '';
                                        ?>
                                        <tr class="<?= $sClass[($i % 2)] ?>">
                                            <td><a href="<?='crc/tnc-dashboard.php?Vendor='.$iVendor?>"><?=$sVendor?></a></td>
                                            <td><?=$iActualPoints?></td>
                                            <td><?=$iPossiblePoint?></td>
                                            <td  style="background: <?= $Color?>"><?= $Percentage.'%'?></td>
                                        </tr>
<?                                  }
?>
                                    </table>
                                    <br/>
<?                              }  

                            }?>
                                <br/>
                                <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" style="text-align: center;">
                                  <tr>
                                      <td style="background:#99CC00;">Acceptable </td><td>Score &gt;=85% without critical issue</td><td>Follow-up audit within 2 months</td>
                                  </tr>
                                    <tr>
                                        <td style="background:#FFFF00;">Acceptable with issues</td><td><?= htmlspecialchars("Score =70% to 84% without critical issue", ENT_NOQUOTES,"UTF-8")?></td><td>Follow-up audit within 1 month</td>
                                    </tr>
                                    <tr>
				      <td style="background:#FFCC00;">Needs Improvement</td><td>Score &lt;=69% or fire safety/critical issues found</td><td>Re Audit within 2 weeks</td>
                                    </tr>
                                    <tr>
				      <td style="background:#FF0000;">Unacceptable</td><td>Zero tolerance issues found</td><td>Re Audit within 1 week</td>
				  </tr>
                                 </table>
                              </div>    
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
	$_SESSION['Message'] = "";

	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>