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
        
        $Vendor     = IO::intValue("Vendor");
        $AuditDate  = IO::strValue("AuditDate");
        
       
        if (@strpos($_SESSION["Email"], "selimpex") !== FALSE)
		$Vendor = 13;
	
	else if (@strpos($_SESSION["Email"], "globalexports") !== FALSE)
		$Vendor = 229;

        if ($AuditDate == "")
            $AuditDate = date("Y-m-d");
         
	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];
	
	if ($Vendor > 0)
		$_SESSION["QmipVendor"] = $Vendor;
	
	else
		$Vendor = $_SESSION["QmipVendor"];	

        if(empty($sQmipVendors))
            $sQmipVendors = '13,229';

	if ($Vendor > 0)
		$sVendorBrands = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "vendor_id='$Vendor'");
	
	else
		$sVendorBrands = getDbValue("GROUP_CONCAT(DISTINCT(brand_id) SEPARATOR ',')", "tbl_po", "FIND_IN_SET(vendor_id, '$sQmipVendors')");
	
        $sReportTypes     = getDbValue("report_types", "tbl_users", "id='{$_SESSION['UserId']}'");
        $sAuditStages     = getDbValue("audit_stages", "tbl_users", "id='{$_SESSION['UserId']}'");
        $sVendorsList     = getList("tbl_vendors", "id", "vendor", "FIND_IN_SET(id, '$sQmipVendors') AND id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
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
			    <h1>QMIP Daily Reports</h1>
                               
                            <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <div id="SearchBar">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="52">Vendor</td>

			          <td width="140">
                                    <select id="Vendor"  style="color: black;" name="Vendor">
                                       <option value=""> All Vendors</option>
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
                                  <td width="40" style="line-height:18px;">Date</td>
            			  <td width="78"><input type="text" name="AuditDate" value="<?= $AuditDate ?>" id="AuditDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
				  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('AuditDate'), 'yyyy-mm-dd', this);" /></td>
					  
			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </div>
			    </form>
  
                            <div class="tblSheet">
                                  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" style="text-align: center;">
                                  <tr class="headerRow">
                                      <td width="10%">Line</td>
                                      <td width="10%"># Pieces</td>
  				      <td width="20%">Names of Brands</td>
                                      <td width="10%"># Correlation audits</td>
                                      <td width="10%"># Correlation audits (End Line)</td>
                                      <td width="10%"># Correlation audits (Finishing)</td>
                                      <td width="10%"># Correlation audits (Cutting)</td>
                                      <td width="10%"># Correlation audits (Embellish..)</td>
                                      <td width="10%"># Correlation audits (Final)</td>
                                  </tr>
<?
				$sSQL = "SELECT l.line, qa.report_id, (SELECT brand from tbl_brands where id=qa.brand_id) AS _Brand, u.name, qa.total_gmts, u.auditor_type
                                 FROM tbl_lines l, tbl_qa_reports qa, tbl_users u
                                 WHERE l.id = qa.line_id AND qa.user_id=u.id AND qa.vendor_id = '$Vendor' AND qa.audit_date='$AuditDate' AND qa.report_id IN (15, 17, 18, 21, 22) 
                                        AND qa.report_id IN ($sReportTypes) AND qa.audit_stage IN ('".implode("','", explode(',', $sAuditStages))."') AND qa.brand_id IN ($sVendorBrands)
                                       AND qa.audit_result!='' AND u.auditor_type IN (2,3) ORDER By l.line";
                                //echo $sSQL;exit;
                                $objDb->query($sSQL);
                                $iCount = $objDb->getCount( );
                                $AuditsArray  = array();
                                $PreviousLine = '';
                                
                                for($i=0; $i < $iCount; $i++){
                                    
                                    $CurrentLine = $objDb->getField($i, 'l.line');
                                    if($PreviousLine != $CurrentLine){
                                       $PreviousLine =  $CurrentLine;
                                       $TotalGarments = 0;
                                       $NoOfCrAudits  = 0;
                                       $CrEndllines    = '';
                                       $CrFinishing    = '';
                                       $CrCutting      = '';
                                       $CrEmbalishment = '';
                                       $CrFinal        = '';
                                       $BrandsArray = array();
                                    }
                                        
                                    if($objDb->getField($i, 'u.auditor_type') == 2){
                                        
                                        $TotalGarments += $objDb->getField($i, 'qa.total_gmts');
                                        if(!in_array($objDb->getField($i, '_Brand'), $BrandsArray))
                                            $BrandsArray[] = $objDb->getField($i, '_Brand');
                                        
                                        $AuditsArray[$PreviousLine] = array($TotalGarments, $BrandsArray, $NoOfCrAudits,$CrEndllines,$CrFinishing,$CrCutting,$CrEmbalishment,$CrFinal);
                                        
                                    }
                                    
                                    if($objDb->getField($i, 'u.auditor_type') == 3){
                                        
                                         if(!in_array($objDb->getField($i, '_Brand'), $BrandsArray))
                                            $BrandsArray[] = $objDb->getField($i, '_Brand');
                                        
                                         if($objDb->getField($i, 'qa.report_id') == 15)
                                                $CrEndllines .= $objDb->getField($i, 'u.name').':'.$objDb->getField($i, 'qa.total_gmts').',';
                                         if($objDb->getField($i, 'qa.report_id') == 17)
                                                $CrFinishing .= $objDb->getField($i, 'u.name').':'.$objDb->getField($i, 'qa.total_gmts').',';
                                         if($objDb->getField($i, 'qa.report_id') == 18)
                                                $CrCutting .= $objDb->getField($i, 'u.name').':'.$objDb->getField($i, 'qa.total_gmts').','; 
                                         if($objDb->getField($i, 'qa.report_id') == 21)
                                                $CrEmbalishment .= $objDb->getField($i, 'u.name').':'.$objDb->getField($i, 'qa.total_gmts').','; 
                                         if($objDb->getField($i, 'qa.report_id') == 22)
                                                $CrFinal .= $objDb->getField($i, 'u.name').':'.$objDb->getField($i, 'qa.total_gmts').',';
                                         $NoOfCrAudits ++;
                                         $AuditsArray[$PreviousLine] = array($TotalGarments, $BrandsArray, $NoOfCrAudits,$CrEndllines,$CrFinishing,$CrCutting,$CrEmbalishment,$CrFinal);
                                    }
                                }
                                $c = 0;
                                foreach($AuditsArray as $Line => $Value){?>
                                    <tr class="<?=($c%2==0)?'evenRow':'oddRow'?>">
                                        <td><?=$Line?></td><td><?=$Value[0]?></td><td><?=implode(',',$Value[1])?></td><td><?=$Value[2]?></td><td><?=$Value[3]?></td><td><?=rtrim($Value[4],",")?></td><td><?=rtrim($Value[5],",")?></td><td><?=rtrim($Value[6],",")?></td><td><?=rtrim($Value[7],",")?></td>
                                    </tr>
<?                                
                                    $c++;
                                }                                  
?>
                                

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