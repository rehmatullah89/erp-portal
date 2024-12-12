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
        $objDb3      = new Database( );

	
	$Vendor = IO::strValue("Vendor");
	$iVendors = array( );
	
        function getColorCode($iAPoints, $iCPoints, $iPPoints, $iZPoints)
        {
                $Color = '';
                $Rating = formatNumber(($iAPoints/$iPPoints)*100);
                if($iZPoints > 0)
                    $Color = '#FF0000';
                else{
                    if($iCPoints == 0 && $Rating >= 85){
                        $Color = '#99CC00';
                    }else if($iCPoints == 0 && ($Rating >= 70 && $Rating <= 84)){
                        $Color = '#FFFF00';
                    }else if($iCPoints > 0 || $Rating <= 69){
                        $Color = '#FFCC00';
                    }                                            
                }
                if($iAPoints == "" || $iPPoints == "")
                    $Color = '';
            
            return $Color;
        }

        $sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_crc_audits WHERE vendor_id IN ({$_SESSION['Vendors']}) ";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
		$iVendors[] = $objDb->getField($i, 0);
        
	$sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_tnc_audits WHERE vendor_id IN ({$_SESSION['Vendors']}) ";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
		$iVendors[] = $objDb->getField($i, 0);
	
	
	$sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_compliance_audits WHERE vendor_id IN ({$_SESSION['Vendors']}) ";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendor = $objDb->getField($i, 0);
		
		if (!@in_array($iVendor, $iVendors))
			$iVendors[] = $iVendor;
	}
	
	
	$sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_quality_audits WHERE vendor_id IN ({$_SESSION['Vendors']}) ";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendor = $objDb->getField($i, 0);
		
		if (!@in_array($iVendor, $iVendors))
			$iVendors[] = $iVendor;
	}
	
	
	$sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_safety_audits WHERE vendor_id IN ({$_SESSION['Vendors']}) ";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendor = $objDb->getField($i, 0);
		
		if (!@in_array($iVendor, $iVendors))
			$iVendors[] = $iVendor;
	}
	
	
	$sSQL = "SELECT DISTINCT(vendor_id) FROM tbl_production_audits WHERE vendor_id IN ({$_SESSION['Vendors']}) ";
	$objDb->query($sSQL);
	
	$iCount = $objDb->getCount( );
	
	for ($i = 0; $i < $iCount; $i ++)
	{
		$iVendor = $objDb->getField($i, 0);
		
		if (!@in_array($iVendor, $iVendors))
			$iVendors[] = $iVendor;
	}
	
	
	$sVendors     = @implode(",", $iVendors);	
	$sVendorsList = getList("tbl_vendors v", "v.id", "CONCAT(COALESCE((SELECT CONCAT(vendor, ' &raquo;&raquo; ') FROM tbl_vendors WHERE id=v.parent_id), ''), v.vendor) AS _Vendor", "v.id IN ($sVendors) AND v.sourcing='Y'", "_Vendor");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/markerclusterer.js"></script>  
  <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&key=AIzaSyBKUixemX1jXoHwR7F4dsTUiGWwmRuZwDI"></script>
<style>
.div-table {
  display: table;         
  width: auto;         
  background-color: #eee;         
  border: 1px solid #666666;         
  border-spacing: 5px; /* cellspacing:poor IE support for  this */
}
.div-table-row {
  display: table-row;
  width: auto;
  clear: both;
}
.div1-table-col {
  float: left; /* fix for  buggy browsers */
  display: table-column;         
  width: 375px;         
  background-color: #ccc;  
  font-weight: bold;
  padding: 5px;
}
.div2-table-col {
  float: left; /* fix for  buggy browsers */
  display: table-column;         
  width: 100px;         
  background-color: #ccc;  
  font-weight: bold;
  padding: 5px;
}

.div-table-col2 {
  float: left; /* fix for  buggy browsers */
  display: table-column;         
  width: 375px;         
  padding: 5px;
}

.div-table-col3 {
  float: left; /* fix for  buggy browsers */
  display: table-column;         
  width: 100px;         
  padding: 5px;
}
.colorCircle {
        width: 20px;
        height: 20px;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, .2);
        margin-top: 5px;
    }
</style>
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
			    <h1>Vendors Map</h1>


			    <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			    <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="55">Vendor</td>

			          <td width="180">
					    <select name="Vendor">
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

			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			    </div>
			    </form>


			    <div class="tblSheet" style="position:relative;">
			      <div id="Vmap" style="width:100%; height:600px;"></div>

					  <script type="text/javascript">
					  <!--
						 var sStyles    = [{"featureType":"administrative","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"saturation":-100},{"lightness":"50"},{"visibility":"simplified"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"lightness":"30"}]},{"featureType":"road.local","elementType":"all","stylers":[{"lightness":"40"}]},{"featureType":"transit","elementType":"all","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]},{"featureType":"water","elementType":"labels","stylers":[{"lightness":-25},{"saturation":-100}]}];
						 var objLatLong = new google.maps.LatLng(31.3974864, 74.2207633);
						 var objOptions = { zoom:5, center:objLatLong, mapTypeId:google.maps.MapTypeId.ROADMAP, styles:sStyles };
						 var objMap     = new google.maps.Map(document.getElementById("Vmap"), objOptions);
						 var objPopups  = [];
                                                 var objCluster;
                                                 var objMarkers = [];
                                                 var objStyles  = [ { url:'images/crc/zoom1.png', textColor:'#ffffff', width:53, height:52, textSize:10 },
                                                                    { url:'images/crc/zoom2.png', textColor:'#ffffff', width:56, height:55, textSize:11 },
                                                                    { url:'images/crc/zoom3.png', textColor:'#ffffff', width:66, height:65, textSize:12 },
                                                                    { url:'images/crc/zoom4.png', textColor:'#ffffff', width:78, height:77, textSize:13 },
                                                                    { url:'images/crc/zoom5.png', textColor:'#ffffff', width:90, height:89, textSize:14 } ];
                                                                    

<?
	if ($Vendor > 0)
		$sSQL = "SELECT id, vendor, latitude, longitude, address FROM tbl_vendors WHERE id='$Vendor' AND latitude!='' AND longitude!='' ORDER BY vendor";

	else
		$sSQL = "SELECT id, vendor, latitude, longitude, address FROM tbl_vendors WHERE latitude!='' AND longitude!='' AND id IN ($sVendors) ORDER BY vendor";

	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );


	for ($i = 0; $i < $iCount; $i ++)
	{
                $iVendor    = $objDb->getField($i, "id");
		$sVendor    = $objDb->getField($i, "vendor");
		$sLatitude  = $objDb->getField($i, "latitude");
		$sLongitude = $objDb->getField($i, "longitude");
		$sAddress   = $objDb->getField($i, "address");
                
                $iLastAudit = getDbValue("id", "tbl_tnc_audits", "vendor_id='$iVendor'", "audit_date DESC");
                $sAuditDate = getDbValue("audit_date", "tbl_tnc_audits", "id='$iLastAudit'");
            
                $iLastCrcAudit = getDbValue("id", "tbl_crc_audits", "vendor_id='$iVendor'", "audit_date DESC");
                $sAuditCrcDate = getDbValue("audit_date", "tbl_crc_audits", "id='$iLastCrcAudit'");
                
                $TNC_CRC = "";
                
                if($sAuditCrcDate != "" && strtotime($sAuditCrcDate) > strtotime($sAuditDate))
                {
                    $sSQL = "SELECT SUM(IF(tp.nature='Z' AND cad.score=0, 1, 0)) as _ZeroTolerancePoints, 
						SUM(IF(tp.nature='C' AND cad.score=0, 1, 0)) as _CriticalPoints,
						SUM(IF(cad.score!=0 AND cad.score != '-1', 1, 0)) as _ActualPoints,
						SUM(IF(cad.score!='-1', 1, 0)) as  _PossiblePoints
				FROM tbl_crc_audits ca, tbl_crc_audit_details cad, tbl_tnc_points tp
				WHERE ca.id=cad.audit_id AND cad.point_id=tp.id AND ca.id='$iLastCrcAudit'";
                    
                    $sSQL1 = "SELECT SUM(IF(tp.nature='Z' AND cad.score=0, 1, 0)) as _ZeroTolerancePoints, 
                                                                                SUM(IF(tp.nature='C' AND cad.score=0, 1, 0)) as _CriticalPoints,
                                                                                SUM(IF(cad.score!=0 AND cad.score != '-1', 1, 0)) as _ActualPoints,
                                                                                SUM(IF(cad.score!='-1', 1, 0)) as  _PossiblePoints,
                                                                                tp.section_id as _SectionId,
                                                                                ts.parent_id as _ParentId  
                    FROM tbl_crc_audits ca, tbl_crc_audit_details cad, tbl_tnc_points tp, tbl_tnc_sections ts
                    WHERE ca.id = cad.audit_id AND cad.point_id = tp.id AND ts.id=tp.section_id AND ca.id = '$iLastCrcAudit'
                    Group By tp.section_id, ca.id
                    Order By tp.section_id";
                    
                    $TNC_CRC    = 'crc';
                    $iLastAudit = $iLastCrcAudit;
                    $sAuditDate = $sAuditCrcDate;
                }
                else
                {    
                    $sSQL = "SELECT SUM(IF(tp.nature='Z' AND tad.score=0, 1, 0)) as _ZeroTolerancePoints, 
                                                    SUM(IF(tp.nature='C' AND tad.score=0, 1, 0)) as _CriticalPoints,
                                                    SUM(IF(tad.score='1', 1, 0)) as _ActualPoints,
                                                    SUM(IF(tad.score!='-1', 1, 0)) as  _PossiblePoints
                                    FROM tbl_tnc_audits ta, tbl_tnc_audit_details tad, tbl_tnc_points tp
                                    WHERE ta.id=tad.audit_id AND tad.point_id=tp.id AND ta.id='$iLastAudit'";
                    
                    $sSQL1 = "SELECT SUM(IF(tp.nature='Z' AND tad.score=0, 1, 0)) as _ZeroTolerancePoints, 
                                                                                SUM(IF(tp.nature='C' AND tad.score=0, 1, 0)) as _CriticalPoints,
                                                                                SUM(IF(tad.score='1', 1, 0)) as _ActualPoints,
                                                                                SUM(IF(tad.score!='-1', 1, 0)) as  _PossiblePoints,
                                                                                tp.section_id as _SectionId,
                                                                                ts.parent_id as _ParentId  
                    FROM tbl_tnc_audits ta, tbl_tnc_audit_details tad, tbl_tnc_points tp, tbl_tnc_sections ts
                    WHERE ta.id = tad.audit_id AND tad.point_id = tp.id AND ts.id=tp.section_id AND ta.id = '$iLastAudit'
                    Group By tp.section_id, ta.id
                    Order By tp.section_id";
                    
                    $TNC_CRC = 'tnc';
                }
                
		$objDb2->query($sSQL);		

                $sMarker = "";
		
		if ($objDb2->getCount( ) == 1)
		{
			$iZTPoints       = $objDb2->getField(0, '_ZeroTolerancePoints');
			$iCriticalPoints = $objDb2->getField(0, '_CriticalPoints');
			$iActualPoints   = $objDb2->getField(0, '_ActualPoints');
			$iPossiblePoint  = $objDb2->getField(0, '_PossiblePoints');
											
			$Percentage = formatNumber(($iActualPoints/$iPossiblePoint)*100);
			
			if ($iZTPoints > 0)
				$sMarker = 'red.png';
			
			else
			{
				if ($iCriticalPoints == 0 && $Percentage >= 85)
					$sMarker = 'green.png';
				
				else if ($iCriticalPoints == 0 && ($Percentage >= 70 && $Percentage <= 84))
					$sMarker = 'yellow.png';
				
				else if ($iCriticalPoints > 0 || $Percentage <= 69)
					$sMarker = 'orange.png';
				
				else
					$sMarker = 'red.png';
			}
		}
                
                if ($sMarker == "")
			$sMarker = 'default.png';
                
                $objDb3->query($sSQL1);
                $iCount3 = $objDb3->getCount( );

                $SocialCompliancePoints         = array();
                $FireSafetyPoints               = array();
                $SubContractingPoints           = array();
                $FactorySecurityPoints          = array();
                $FactorProductivityPoints       = array();
                $FactoryEvaluationSweaters      = array();
                $FactoryEvaluationNonSweaters   = array();
                
                for($j=0; $j<$iCount3; $j++)
                {
                                    
                    if($objDb3->getField($j, '_SectionId') == 5){
                        $FireSafetyPoints = array('zPoints' => $objDb3->getField($j, '_ZeroTolerancePoints'),'cPoints' => $objDb3->getField($j, '_CriticalPoints'),'aPoints' => $objDb3->getField($j, '_ActualPoints'),'pPoints' => $objDb3->getField($j, '_PossiblePoints'));
                    }

                    if($objDb3->getField($j, '_SectionId') == 6){
                        $SubContractingPoints = array('zPoints' => $objDb3->getField($j, '_ZeroTolerancePoints'),'cPoints' => $objDb3->getField($j, '_CriticalPoints'),'aPoints' => $objDb3->getField($j, '_ActualPoints'),'pPoints' => $objDb3->getField($j, '_PossiblePoints'));
                    }
                    
                    if($objDb3->getField($j, '_SectionId') == 21){
                        $FactoryEvaluationSweaters = array('zPoints' => $objDb3->getField($j, '_ZeroTolerancePoints'),'cPoints' => $objDb3->getField($j, '_CriticalPoints'),'aPoints' => $objDb3->getField($j, '_ActualPoints'),'pPoints' => $objDb3->getField($j, '_PossiblePoints'));
                    }

                    if($objDb3->getField($j, '_SectionId') == 22){
                        $FactoryEvaluationNonSweaters = array('zPoints' => $objDb3->getField($j, '_ZeroTolerancePoints'),'cPoints' => $objDb3->getField($j, '_CriticalPoints'),'aPoints' => $objDb3->getField($j, '_ActualPoints'),'pPoints' => $objDb3->getField($j, '_PossiblePoints'));
                    }
                    
                    if($objDb3->getField($j, '_ParentId') == 7){

                        $SocialCompliancePoints['zPoints'] += @$objDb3->getField($j, '_ZeroTolerancePoints');
                        $SocialCompliancePoints['cPoints'] += @$objDb3->getField($j, '_CriticalPoints');
                        $SocialCompliancePoints['aPoints'] += @$objDb3->getField($j, '_ActualPoints');
                        $SocialCompliancePoints['pPoints'] += @$objDb3->getField($j, '_PossiblePoints'); 

                        $SocialCompliancePoints = array('zPoints' => $SocialCompliancePoints['zPoints'],'cPoints' =>$SocialCompliancePoints['cPoints'] ,'aPoints' => $SocialCompliancePoints['aPoints'],'pPoints' => $SocialCompliancePoints['pPoints']);
                    }

                    if($objDb3->getField($j, '_ParentId') == 8){

                        $FactorySecurityPoints['zPoints'] += @$objDb3->getField($j, '_ZeroTolerancePoints');
                        $FactorySecurityPoints['cPoints'] += @$objDb3->getField($j, '_CriticalPoints');
                        $FactorySecurityPoints['aPoints'] += @$objDb3->getField($j, '_ActualPoints');
                        $FactorySecurityPoints['pPoints'] += @$objDb3->getField($j, '_PossiblePoints'); 

                        $FactorySecurityPoints = array('zPoints' => $FactorySecurityPoints['zPoints'],'cPoints' =>$FactorySecurityPoints['cPoints'] ,'aPoints' => $FactorySecurityPoints['aPoints'],'pPoints' => $FactorySecurityPoints['pPoints']);
                    }

                    if($objDb3->getField($j, '_ParentId') == 17){

                        $FactorProductivityPoints['zPoints'] += @$objDb3->getField($j, '_ZeroTolerancePoints');
                        $FactorProductivityPoints['cPoints'] += @$objDb3->getField($j, '_CriticalPoints');
                        $FactorProductivityPoints['aPoints'] += @$objDb3->getField($j, '_ActualPoints');
                        $FactorProductivityPoints['pPoints'] += @$objDb3->getField($j, '_PossiblePoints'); 

                        $FactorProductivityPoints = array('zPoints' => $FactorProductivityPoints['zPoints'],'cPoints' =>$FactorProductivityPoints['cPoints'] ,'aPoints' => $FactorProductivityPoints['aPoints'],'pPoints' => $FactorProductivityPoints['pPoints']);
                    }

                }
                $str ="";
                $check = 0;
                
                
                if(!empty($SocialCompliancePoints) || !empty($FireSafetyPoints) || !empty($SubContractingPoints) || !empty($FactorySecurityPoints) || !empty($FactorProductivityPoints) || !empty($FactoryEvaluationSweaters) || !empty($FactoryEvaluationNonSweaters))
                {
                    $str .= "<div class='div-table'><div class='div-table-row'><div class='div1-table-col'>Section</div><div class='div2-table-col'>Rating</div></div></div>";
                
                    if(!empty($SocialCompliancePoints))
                    {
                        $Color = getColorCode($SocialCompliancePoints['aPoints'], $SocialCompliancePoints['cPoints'], $SocialCompliancePoints['pPoints'], $SocialCompliancePoints['zPoints']);
                        $str .= "<div class='div-table'><div class='div-table-row'><div class='div-table-col2'><a href='crc/view-".$TNC_CRC."-audit.php?Id=".$iLastAudit."' class='lightview' rel='iframe' title='CRC Audit :: :: width: 900, height: 650'>Social Compliance</a> <br /><span style='color:blue;'>Audit Date: {$sAuditDate}</span></div><div class='div-table-col3'><div class='colorCircle' style='background-color:".$Color."'></div></div></div></div>";
                    }
                    
                    if(!empty($FireSafetyPoints))
                    {
                        $Color = getColorCode($FireSafetyPoints['aPoints'], $FireSafetyPoints['cPoints'], $FireSafetyPoints['pPoints'], $FireSafetyPoints['zPoints']);
                        $str .= "<div class='div-table'><div class='div-table-row'><div class='div-table-col2'><a href='crc/view-".$TNC_CRC."-audit.php?Id=".$iLastAudit."' class='lightview' rel='iframe' title='CRC Audit :: :: width: 900, height: 650'>Fire Safety (Social Compliance)</a><br /><span style='color:blue;'>Audit Date: {$sAuditDate}</span></div><div class='div-table-col3'><div class='colorCircle' style='background-color:".$Color."'></div></div></div></div>";
                    }
                    
                    if(!empty($SubContractingPoints))
                    {
                        $Color = getColorCode($SubContractingPoints['aPoints'], $SubContractingPoints['cPoints'], $SubContractingPoints['pPoints'], $SubContractingPoints['zPoints']);
                        $str .= "<div class='div-table'><div class='div-table-row'><div class='div-table-col2'><a href='crc/view-".$TNC_CRC."-audit.php?Id=".$iLastAudit."' class='lightview' rel='iframe' title='CRC Audit :: :: width: 900, height: 650'>Subcontracting (Social Compliance)</a> <br /><span style='color:blue;'>Audit Date: {$sAuditDate}</span></div><div class='div-table-col3'><div class='colorCircle' style='background-color:".$Color."'></div></div></div></div>";
                    }
                    
                    if(!empty($FactorySecurityPoints))
                    {
                        $Color = getColorCode($FactorySecurityPoints['aPoints'], $FactorySecurityPoints['cPoints'], $FactorySecurityPoints['pPoints'], $FactorySecurityPoints['zPoints']);
                        $str .= "<div class='div-table'><div class='div-table-row'><div class='div-table-col2'><a href='crc/view-".$TNC_CRC."-audit.php?Id=".$iLastAudit."' class='lightview' rel='iframe' title='CRC Audit :: :: width: 900, height: 650'>Factory Security</a><br /><span style='color:blue;'>Audit Date: {$sAuditDate}</span></div><div class='div-table-col3'><div class='colorCircle' style='background-color:".$Color."'></div></div></div></div>";
                    }
                    
                    if(!empty($FactorProductivityPoints))
                    {
                        $Color = getColorCode($FactorProductivityPoints['aPoints'], $FactorProductivityPoints['cPoints'], $FactorProductivityPoints['pPoints'], $FactorProductivityPoints['zPoints']);
                        $str .= "<div class='div-table'><div class='div-table-row'><div class='div-table-col2'><a href='crc/view-".$TNC_CRC."-audit.php?Id=".$iLastAudit."' class='lightview' rel='iframe' title='CRC Audit :: :: width: 900, height: 650'>Factory Productivity</a><br /><span style='color:blue;'>Audit Date: {$sAuditDate}</span></div><div class='div-table-col3'><div class='colorCircle' style='background-color:".$Color."'></div></div></div></div>";
                    }
                    
                    if(!empty($FactoryEvaluationSweaters))
                    {
                        $Color = getColorCode($FactoryEvaluationSweaters['aPoints'], $FactoryEvaluationSweaters['cPoints'], $FactoryEvaluationSweaters['pPoints'], $FactoryEvaluationSweaters['zPoints']);
                        $str .= "<div class='div-table'><div class='div-table-row'><div class='div-table-col2'><a href='crc/view-".$TNC_CRC."-audit.php?Id=".$iLastAudit."' class='lightview' rel='iframe' title='CRC Audit :: :: width: 900, height: 650'>Factory Evaluation Sweaters</a><br /><span style='color:blue;'>Audit Date: {$sAuditDate}</span></div><div class='div-table-col3'><div class='colorCircle' style='background-color:".$Color."'></div></div></div></div>";
                    }
                    
                    if(!empty($FactoryEvaluationNonSweaters))
                    {
                        $Color = getColorCode($FactoryEvaluationNonSweaters['aPoints'], $FactoryEvaluationNonSweaters['cPoints'], $FactoryEvaluationNonSweaters['pPoints'], $FactoryEvaluationNonSweaters['zPoints']);
                        $str .= "<div class='div-table'><div class='div-table-row'><div class='div-table-col2'><a href='crc/view-".$TNC_CRC."-audit.php?Id=".$iLastAudit."' class='lightview' rel='iframe' title='CRC Audit :: :: width: 900, height: 650'>Factory Evaluation Non Sweaters</a><br /><span style='color:blue;'>Audit Date: {$sAuditDate}</span></div><div class='div-table-col3'><div class='colorCircle' style='background-color:".$Color."'></div></div></div></div>";
                    }
                    
                    $check = 1;
                }
               
                ?>
						 var objVendorLatLong<?= $i ?> = new google.maps.LatLng(<?= $sLatitude ?>, <?= $sLongitude ?>);
						 var objVendorMarker<?= $i ?>  = new google.maps.Marker({ position:objVendorLatLong<?= $i ?>, map:objMap, icon:'images/crc/<?= $sMarker ?>' });
						 var objVendorInfoWin<?= $i ?> = new google.maps.InfoWindow({ content:"<div style='<?=$check==0?"":"500px; margin-left:17px;"?>'><b style='font-weight:bold; padding-top:10px;'><?= $sVendor ?></b><br /><?= utf8_encode(str_replace("\n", "<br />", htmlentities($sAddress, ENT_QUOTES))) ?><br/><br/><div style='padding-bottom:10px;'><?= $str?></div></div>" });

						 google.maps.event.addListener(objVendorMarker<?= $i ?>, 'click', function( )
						 {
							for (var i = 0; i < objPopups.length; i ++)
								objPopups[i].close( );					

							
							 objVendorInfoWin<?= $i ?>.open(objMap, objVendorMarker<?= $i ?>);
						 });
						 
						 
						 objPopups.push(objVendorInfoWin<?= $i ?>);
                                                 objMarkers.push(objVendorMarker<?= $i ?>);
                                                 
                                                 if (objCluster)
                                                    objCluster.clearMarkers( );

                                                objCluster = new MarkerClusterer(objMap, objMarkers, { maxZoom:15, gridSize:70, styles:objStyles} )
<?
	}
	

	if ($iCount == 0)
	{
?>
						  alert("No Vendor Marked on Map, Lat/Long not available.");
<?
	}

	else if ($iCount == 1)
	{
?>
						  objMap.setCenter(new google.maps.LatLng(<?= $sLatitude ?>, <?= $sLongitude ?>));
<?
	}
?>

					  -->
					  </script>
				  </div>

			      <br />
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
	@include($sBaseDir."includes/colorCircleter.php");
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
        $objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>