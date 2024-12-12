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


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/reports/mgf-report.js"></script>
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
			    <h1>OQL Report</h1>

			    <form name="frmSearch" id="frmSearch" method="post" action="<?= SITE_URL.'reports/export-oql-report.php' ?>" class="frmOutline" onsubmit="checkDoubleSubmission( );">
				<h2>Export OQL Reports</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="80">Vendor</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Vendor[]" multiple size="10" style="width:300px;">
<?
       $sVendors = "";
        $sSQL = "SELECT vendor_id FROM tbl_qa_reports WHERE brand_id='365'";        
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
            $sVendors .= ($objDb->getField($i, "vendor_id").",");
        
        $sVendors = rtrim($sVendors, ",");
        
	$sSQL = "SELECT id, vendor FROM tbl_vendors WHERE id IN ($sVendors) AND parent_id='0' AND sourcing='Y' ORDER BY vendor";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, "id");
		$sValue = $objDb->getField($i, "vendor");
?>
	  	        		<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

                                    <tr valign="top">
					<td width="80">Customers</td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Customers[]" multiple size="10" style="width:300px;">
<?
        $sCustomers = array();
        $sSQL = "SELECT DISTINCT p.customer as _CUSTOMER FROM tbl_qa_reports qa, tbl_po p WHERE qa.po_id = p.id AND p.customer != '' AND qa.brand_id = '365' ORDER BY _CUSTOMER";  
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
            array_push($sCustomers, $objDb->getField($i, "_CUSTOMER"));
        
	foreach($sCustomers as $sCustomer)
	{
?>
	  	        		<option value="<?= $sCustomer ?>"><?= $sCustomer ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>
                                    
				  <tr>
					<td>Brand</td>
					<td align="center">:</td>

					<td>
					  <select name="Brand" style="width:300px;">
<?
	$sSQL = "SELECT id, brand FROM tbl_brands WHERE id='365'";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sKey   = $objDb->getField($i, 0);
		$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>
                                    
                                <tr>
					<td>Year</td>
					<td align="center">:</td>

					<td>
                                            <select name="Year" style="width:300px;" onchange="toggleMonth(this.value);">
                                              <option value="">Select Year</option>
<?
        $sReportIds = getDbValue("GROUP_CONCAT(DISTINCT report_id SEPARATOR ',')", "tbl_qa_reports", "brand_id='365'");
        
	$sSQL = "SELECT DISTINCT YEAR(audit_date) as Year FROM tbl_qa_reports WHERE report_id IN ($sReportIds) AND audit_result != '' AND audit_stage IN ('B','O','F')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$sYear   = $objDb->getField($i, "Year");
?>
	  	        		<option value="<?= $sYear ?>" <?=($sYear == IO::strValue("Year")?'selected':'')?>><?= $sYear ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>  
                                    
                                    <tr id='MonthId' style="display:none;">
					<td>Month</td>
					<td align="center">:</td>

                                    <td>
					  <select id='Month' name='Month' style="width:300px;">
                                            <option value=''> Select Month </option>
                                            <option value='01'>Janaury</option>
                                            <option value='02'>February</option>
                                            <option value='03'>March</option>
                                            <option value='04'>April</option>
                                            <option value='05'>May</option>
                                            <option value='06'>June</option>
                                            <option value='07'>July</option>
                                            <option value='08'>August</option>
                                            <option value='09'>September</option>
                                            <option value='10'>October</option>
                                            <option value='11'>November</option>
                                            <option value='12'>December</option>
                                        </select>
                                    </td>
				  </tr>   
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" value="" id="BtnExport" class="btnExport" title="Export" />
				</div>
			    </form>
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
<script>
    function toggleMonth(VAL)
    {
        if(VAL > 0)
            document.getElementById("MonthId").style.display = "";
        else
        {
            document.getElementById("MonthId").style.display = "none";
            document.getElementById("Month").value = "";
        }        
    }
</script>
</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>