<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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
	**   Project Developer:                                                                      **
	**                                                                                           **
	**      Name  :  Rehmatullah Bhatti                                                          **
	**      Email :  rehmatullahbhatti@gmail.com                                                 **
	**      Phone :  +92 344 404 3675                                                            **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$Id         = IO::intValue('Id');
        $sBrand     = IO::strValue('Brand');
        $iVendor    = IO::intValue('VendorId');
        $sVendor    = IO::strValue('Vendor');
        $sAuditDate = IO::strValue('AuditDate');
        $sStatus    = IO::strValue('Status');
        $iAuditor   = IO::intValue('Auditor');
        
        if (@in_array($_SESSION["UserType"], array("MATRIX", "TRIPLETREE", "LULUSAR", "GAIA")))
            $sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A'");
        else if(@in_array($_SESSION["UserType"], array("TPH")) && @strpos($_SESSION["Email"], "@triumph.com") === FALSE)
        {
                $sAuditorSubQuery = " AND ";
                $sMyVendors = explode(",", $_SESSION['Vendors']);

                if(count($sMyVendors) > 1)
                    $sAuditorSubQuery .= " ( ";
                
                $iIndex = 0;
                foreach($sMyVendors as $iMyVendor)
                {
                    if($iMyVendor != 0)
                    {
                        if($iIndex == 0)
                            $sAuditorSubQuery .= " FIND_IN_SET(".trim($iMyVendor).", vendors) ";
                        else
                            $sAuditorSubQuery .= " OR FIND_IN_SET(".trim($iMyVendor).", vendors) ";

                        $iIndex ++;    
                    } 
                }
                
                if(count($sMyVendors) > 1)
                   $sAuditorSubQuery .= " ) ";
            
                //$sAuditorSubQuery   = " AND FIND_IN_SET(".trim($iVendor).", vendors) ";    
                 
                $sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A' AND email NOT LIKE '%@3-tree.com%' AND email NOT LIKE '%@triumph.com' AND email NOT LIKE '%@apparelco.com' AND user_type='{$_SESSION['UserType']}' $sAuditorSubQuery");
        }
        else
            $sActiveAuditorsList = getList("tbl_users", "id", "name", "auditor='Y' AND status='A' AND user_type='{$_SESSION['UserType']}' AND FIND_IN_SET(".trim($iVendor).", vendors)");
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
<!--  Body Section Starts Here  -->
			    <form name="frmData" id="frmData" method="post" action="quonda/save-booking-status.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Update Booking Status</h2>
                                <input type="hidden" name="Id" value="<?=$Id?>"/>
                                
				<table border="0" cellpadding="5" cellspacing="0" width="100%">
                                            <tr valign="top">
                                                          <td width="95" >Booking No.</td>
                                                          <td width="20" align="center">:</td>
                                                          <td><?= "B".str_pad($Id, 5, 0, STR_PAD_LEFT) ?></td>
                                            </tr>
                                    
                                            <tr valign="top">
                                                          <td width="95" >Brand</td>
                                                          <td width="20" align="center">:</td>
                                                          <td><?= $sBrand ?></td>
                                            </tr>

                                            <tr valign="top">
                                                          <td width="95" >Vendor</td>
                                                          <td width="20" align="center">:</td>
                                                          <td><?=$sVendor?></td>
                                            </tr>
                                    
                                            <tr valign="top">
                                                          <td width="95" >Audit Date</td>
                                                          <td width="20" align="center">:</td>
                                                          <td><?=$sAuditDate?></td>
                                            </tr>
                                                                                                           
                                            <tr valign="top">
						  <td width="95" >Status<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td>
                                                            <select name="Status" style="max-width: 100%;" onchange="toggleOrderStatus(this.value)">
                                                                <option value="P" <?= ($sStatus == 'P')?'selected':''?>>Pending</option>
                                                                <option value="A" <?= ($sStatus == 'A')?'selected':''?>>Approved</option>                                                                
                                                                <option value="C" <?= ($sStatus == 'C')?'selected':''?>>Cancelled/ Rejected</option>
                                                            </select>
						  </td>
					    </tr>
                                    
                                            <tr valign="top" style="display:<?=($iAuditor > 0?'':'none')?>;" id="DisplayAuditorId">
                                                <td width="75">Auditor<span class="mandatory">*</span></td>
                                                <td width="20" align="center">:</td>
                                                <td width="130">
                                                        <select name="Auditor">
                                                            <option value="">All Auditors</option>
<?
                                                    foreach ($sActiveAuditorsList as $sKey => $sValue)
                                                    {
?>
                                                            <option value="<?= $sKey ?>"<?= (($sKey == $iAuditor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
                                                    }
?>
                                                        </select>
                                                </td>
                                            </tr>
				</table>
                               <br />

                                <div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" />
                                <input type="button" value="" class="btnCancel" onclick="parent.hideLightview();"/></div>
			    </form>
    </div>
</div>
</div>
<!--  Body Section Ends Here  -->

<script type="text/javascript">
<!-- 
            function toggleOrderStatus(Val)
            {
                if(Val != 'A')
                    document.getElementById("DisplayAuditorId").style.display = 'none';
                else
                    document.getElementById("DisplayAuditorId").style.display = '';        
            }
            
            function validateForm( )
            {
                    var objFV = new FormValidator("frmData");

                    if (!objFV.validate("Status", "B", "Please select the Status."))
                            return false;

                    if (objFV.value("Status") == 'A')
                    {
                        if (!objFV.validate("Auditor", "B", "Please select the Auditor."))
                                return false;
                    }
                    
                    return true;
            }
-->
</script>    
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>