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

	$Id         = IO::intValue('Id');
        $Completed  = IO::strValue('Completed');

        $sSQL = "SELECT * FROM tbl_crc_audit_supply_chain Where audit_id = '$Id'";
	$objDb->query($sSQL);

        $cIsAnotherPSite        = $objDb->getField(0, 'is_another_production_site');
        $sProductionTier        = $objDb->getField(0, 'production_tier');
        $sProductionSiteType    = $objDb->getField(0, 'production_site_type');
        $sProductionSiteName    = $objDb->getField(0, 'production_site_name');
        $sProductionSiteAddress = $objDb->getField(0, 'production_site_address');
        $cIsAnotherCompany      = $objDb->getField(0, 'is_another_company');
        $sCompanyTier           = $objDb->getField(0, 'company_tier');
        $sCompanyType           = $objDb->getField(0, 'company_type');
        $sCompanyName           = $objDb->getField(0, 'company_name');
        $sCompanyAddress        = $objDb->getField(0, 'company_address');
        $iNoOfBuildings          = $objDb->getField(0, 'no_of_buildings');
        $sBuildingPurpose       = $objDb->getField(0, 'building_purpose');
        $sBuildingFloors        = $objDb->getField(0, 'building_floors');
        $sFireCertificates      = $objDb->getField(0, 'fire_certificate');
        $sBuildingApprovals     = $objDb->getField(0, 'building_approvals');
        $iTotalFarms            = $objDb->getField(0, 'total_farms');
        $iCustomerTurnOver      = $objDb->getField(0, 'customers_turn_over');
        $sLastOrderDate         = $objDb->getField(0, 'last_order_date');
        $sOtherFactoryInfo      = $objDb->getField(0, 'other_factory_info');
        
        if($iNoOfBuildings > 0 && $iNoOfBuildings <=5)
        {
            $iNoOfBuildings = 5;
        }
        else if($iNoOfBuildings > 5 && $iNoOfBuildings <=10)
        {
            $iNoOfBuildings = 10;
        }
        else if($iNoOfBuildings > 10 && $iNoOfBuildings <=15)
        {
            $iNoOfBuildings = 15;
        }
        else if($iNoOfBuildings > 15 && $iNoOfBuildings <=20)
        {
            $iNoOfBuildings = 20;
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
		background: #f6f4f5 none repeat scroll 0 0;
	}
	.oddRow {
		background: #dcdcdc none repeat scroll 0 0;
	}
	</style> 
</head>

<body>

<div id="PopupDiv" style="width:auto; margin-left:2px; margin-right:2px;">
  <div id="PageContents">
      <div id="RecordMsg" class="hidden" style="width:100%; <?=($_SESSION["Flag1122"] != "")?'background-color:#FFFACD;':'';?>"><?=($_SESSION["Flag1122"] != ""?"<span style='color:black; background-color:#FFFACD; font-size:14px; font-weight:bold;'>Audit Supply Chain Transparency Updated Successfully!</span>":"")?></div>
    <form name="frmData1" id="frmData1" method="post" action="crc/update-crc-schain.php" class="frmOutline">
        <input type="hidden" name="AuditId" value="<?=$Id?>"/>
<!--  Body Section Starts Here  -->
	<div id="Body">
	  <h2>Supply Chain Transparency</h2>
<?
    if($Completed != 'Y')
    {
        print "<fieldset disabled>";
    }
?>
	  <table border="0" cellpadding="2" cellspacing="2" width="100%" style="padding-bottom:10px;">
            <tr class="evenRow">
                <td width="60%">"Supply Chain below Audit Location: If the Audit Location uses other production sites/units or sub-contractors to produce this product:
                    Does the factory/farm use other production sites/units or sub contractors to produce this product?  <br/><font style="color:red;">If yes, please specify name, location and production steps handled.</font>			
                </td>
                <td><input type="radio" name="IsProductionSite" value="Y" onclick="ToggleDiv(this.value, 'SiteDiv');" <?=($cIsAnotherPSite == 'Y')?'checked':''?>/>Yes &nbsp;<input type="radio" name="IsProductionSite" value="N" onclick="ToggleDiv(this.value, 'SiteDiv');" <?=($cIsAnotherPSite == 'N' || $cIsAnotherPSite == '')?'checked':''?>/>No</td>  
            </tr>
            <tr>
                <td colspan="2">
                    <div style="<?=($cIsAnotherPSite == 'Y')?'display:block;':'display:none;';?>" id="SiteDiv">
                        <table border="0" cellpadding="2" cellspacing="2" width="100%">
                            <tr>
                                <td width="100"><h3>Tier</h3></td>
                                <td width="150"><h3>Company Type</h3></td>
                                <td width="285"><h3>Name</h3></td>
                                <td><h3>Address</h3></td>
                            </tr>
<?
                            $iProductionTier        = explode("|-|", $sProductionTier); 
                            $iProductionSiteType    = explode("|-|", $sProductionSiteType); 
                            $iProductionSiteName    = explode("|-|", $sProductionSiteName); 
                            $iProductionSiteAddress = explode("|-|", $sProductionSiteAddress); 
                            
                            for($i=0; $i<4; $i++)
                            {
?>
                            <tr>
                            <td><input style="width:95%;" type="text" name="SitetTier[<?=$i?>]" value="<?=$iProductionTier[$i]?>"/></td>
                            <td><input style="width:97%;" type="text" name="SitetType[<?=$i?>]" value="<?=$iProductionSiteType[$i]?>"/></td>
                            <td><input style="width:98%;" type="text" name="SitetName[<?=$i?>]" value="<?=$iProductionSiteName[$i]?>"/></td>
                            <td><input style="width:98%;" type="text" name="SitetAddress[<?=$i?>]" value="<?=$iProductionSiteAddress[$i]?>"/></td>
                            </tr>
<?
                            }
?>
                        </table>
                    </div>
                </td>
            </tr>  
            <tr class="oddRow">
                <td width="80%">"Supply Chain between Supplier and Audit Location: If any other companies such as traders are involved to deliver the procucts:
                    Are there any other companies in the supply chain between supplier and audit location (as specified in the Pre Audit Information)?
                    <br/><font style="color:red;">If yes, please specify the supply chain between supplier and audit location only.</font>			
                </td>
                <td><input type="radio" name="IsAnotherCompany" value="Y" onclick="ToggleDiv(this.value, 'CompanyDiv');" <?=($cIsAnotherCompany == 'Y')?'checked':''?>/>Yes &nbsp;<input type="radio" name="IsAnotherCompany" value="N" onclick="ToggleDiv(this.value, 'CompanyDiv');" <?=($cIsAnotherCompany == 'N' || $cIsAnotherCompany == '')?'checked':''?>/>No</td>  
            </tr>
            <tr>
                <td colspan="2">
                    <div style="<?=($cIsAnotherCompany == 'Y')?'display:block;':'display:none;';?>" id="CompanyDiv">
                        <table border="0" cellpadding="2" cellspacing="2" width="100%">
                            <tr>
                                <td width="100"><h3>Tier</h3></td>
                                <td width="150"><h3>Company Type</h3></td>
                                <td width="285"><h3>Name</h3></td>
                                <td><h3>Address</h3></td>
                            </tr>
<?    
                            $iCompanyTier    = explode("|-|", $sCompanyTier); 
                            $iCompanyType    = explode("|-|", $sCompanyType); 
                            $iCompanyName    = explode("|-|", $sCompanyName); 
                            $iCompanyAddress = explode("|-|", $sCompanyAddress); 
                            
                            for($i=0; $i<4; $i++)
                            {
?>
                            <tr>
                            <td><input style="width:95%;" type="text" name="CompanyTier[<?=$i?>]" value="<?=$iCompanyTier[$i]?>"/></td>
                            <td><input style="width:97%;" type="text" name="CompanyType[<?=$i?>]" value="<?=$iCompanyType[$i]?>"/></td>
                            <td><input style="width:98%;" type="text" name="CompanyName[<?=$i?>]" value="<?=$iCompanyName[$i]?>"/></td>
                            <td><input style="width:98%;" type="text" name="CompanyAddress[<?=$i?>]" value="<?=$iCompanyAddress[$i]?>"/></td>
                            </tr>
<?
                            }
?>
                        </table>
                    </div>
                </td>
            </tr> 
            <tr class="evenRow">
                <td width="80%">No Of Buildings</td>
                <td>
                    <select name="NoOfBuildings" onchange="CreateBuildingRows(this.value)">
                       <option value="0"></option> 
                       <option value="5" <?=($iNoOfBuildings == '5')?'selected':''?>>1-5</option>
                        <option value="10" <?=($iNoOfBuildings == '10')?'selected':''?>>6-10</option>
                        <option value="15" <?=($iNoOfBuildings == '15')?'selected':''?>>11-15</option>
                        <option value="20" <?=($iNoOfBuildings == '20')?'selected':''?>>16-20</option>
                    </select>
                </td>  
            </tr>
            <tr>
                <td colspan="2">
<?
                    if($iNoOfBuildings > 0)
                    {
?>
                      <div id="BuildingsDiv">
                        <table border="0" cellpadding="2" cellspacing="2" width="100%" id="MyTable">
                            <tr>
                                <td width="100"><h3>&nbsp;</h3></td>
                                <td width="300"><h3>Purpose</h3></td>
                                <td width="150"><h3>Floors</h3></td>
                                <td width="150"><h3>Fire Certificate</h3></td>
                                <td><h3>Building Approvals</h3></td>
                            </tr>
                                      
<?
                        $iBuildingPurpose   = explode("|-|", $sBuildingPurpose); 
                        $iBuildingFloors    = explode("|-|", $sBuildingFloors);
                        $iFireCertificates  = explode("|-|", $sFireCertificates);
                        $iBuildingApprovals = explode("|-|", $sBuildingApprovals);
                                
                        for($b=0; $b<$iNoOfBuildings; $b++)
                        {
?>
                            <tr>
                                <td>Building #:<?=$b+1?></td>
                                <td><input type='text' style='width:98%;' name='Purpose[]' value="<?=$iBuildingPurpose[$b]?>"/></td>
                                <td><input type='text' style='width:98%;' name='Floors[]' value="<?=$iBuildingFloors[$b]?>"/></td>
                                <td><input type='text' style='width:98%;' name='FireCertificate[]' value="<?=$iFireCertificates[$b]?>"/></td>
                                <td><input type='text' style='width:98%;' name='Approvals[]' value="<?=$iBuildingApprovals[$b]?>"/></td>
                            </tr>
<?
                        }
?>
                         </table>
                        </div>
<?
                    }
                    else
                    {
?>
                    <div id="BuildingsDiv" style="display:none;">
                        <table border="0" cellpadding="2" cellspacing="2" width="100%" id="MyTable">
                            <tr>
                                <td width="100"><h3>&nbsp;</h3></td>
                                <td width="300"><h3>Purpose</h3></td>
                                <td width="150"><h3>Floors</h3></td>
                                <td width="150"><h3>Fire Certificate</h3></td>
                                <td><h3>Building Approvals</h3></td>
                            </tr>
                        </table>
                    </div>
<?
                    }
?>
                </td>
            </tr>   
            <tr class="oddRow">   
                <td><font style="color:red;">"Only for Farm Audits:"</font><br/>Total Nr. of fields/farms belonging to the exporter/cooperative that produce:			
                </td>
                <td><input type="text" style="width:98%" name="TotalFarms" value="<?=$iTotalFarms?>"/></td>  
            </tr>
            <tr class="evenRow">
                <td>Main Customers (Turnover in %):</td>
                  <td><input type="text" style="width:98%" name="MainTurnOver" value="<?=$iCustomerTurnOver?>"/></td>  
            </tr>
            <tr class="oddRow">
                <td>When did the factory/farm handle the last Order?</td>
                  <td><input type="text" style="width:98%" name="LastOrderDate" value="<?=$sLastOrderDate?>"/></td>  
            </tr>
            <tr class="evenRow">
                <td>Other Important factory/farm Information:</td>
                  <td><input type="text" style="width:98%" name="OtherFactoryInfo" value="<?=$sOtherFactoryInfo?>"/></td>  
            </tr>
           </table>    
<?
    if($Completed != 'Y')
    {
        print "</fieldset>";
    }
    else
    {
?>
          <input style="float:right; margin: 5px;" type="submit" value="Submit"/>
<?
    }
?>
          <br/><br/><br/><br/>
	</div>
<!--  Body Section Ends Here  -->

  </form>
  </div>
</div>
<script type="text/javascript">
    <!-- 
     function alertMsg() {
        document.getElementById("RecordMsg").innerHTML = "";
        <?$_SESSION["Flag1122"] = "";?>
     }
     setTimeout(alertMsg,3000); 
     
     function ToggleDiv(Status, DivName)
     {
        if(Status == 'Y')
            document.getElementById(DivName).style.display = 'block';
        else
            document.getElementById(DivName).style.display = 'none';
     }
     
      function CreateBuildingRows(NoOfRows) 
      {
            if(NoOfRows > 0)
                document.getElementById("BuildingsDiv").style.display = 'block';
            else
                document.getElementById("BuildingsDiv").style.display = 'none';

            var table = document.getElementById("MyTable");
            
            var rows = table.rows;
            var i = rows.length;
            while (--i) {
              rows[i].parentNode.removeChild(rows[i]);
            }
            
            for(var i=0; i<NoOfRows; i++)
            {
                var rowCount = table.rows.length;
                var row = table.insertRow(rowCount);
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);
                var cell4 = row.insertCell(3);
                var cell5 = row.insertCell(4);

                cell1.innerHTML = "Building #: "+(i+1);
                cell2.innerHTML = "<input type='text' style='width:98%;' name='Purpose[]' value=''/>";
                cell3.innerHTML = "<input type='text' style='width:98%;' name='Floors[]' value=''/>";
                cell4.innerHTML = "<input type='text' style='width:98%;' name='FireCertificate[]' value=''/>";
                cell5.innerHTML = "<input type='text' style='width:98%;' name='Approvals[]' value=''/>";
            }
        }
    -->
    </script>    
</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>