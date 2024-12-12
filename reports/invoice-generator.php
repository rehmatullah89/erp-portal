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
  <script type="text/javascript" src="scripts/reports/invoice-generator.js"></script>
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
			    <h1>Invoice Generator</h1>

			    <form name="frmSearch" id="frmSearch" method="post" action="reports/export-invoice.php" class="frmOutline" onsubmit="checkDoubleSubmission( );" enctype="multipart/form-data">
				<h2>Invoice Generator</h2>

				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
				    <td width="45%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					   <tr valign="top">
					 	  <td width="80">
					 	    Brand<br />
					 	    [ <a href="./" onclick="selectAll('Brand'); return false;">ALL</a> | <a href="./" onclick="clearAll('Brand'); return false;">None</a> ]<br />
					 	  </td>

						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Brand[]" id="Brand" size="10" multiple style="min-width:200px;">
<?
	$sSQL = "SELECT id, brand FROM tbl_brands WHERE id IN ({$_SESSION['Brands']}) ORDER BY brand";
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

					    <tr valign="top">
						  <td>
						    Vendor<br />
						    [ <a href="./" onclick="selectAll('Vendor'); return false;">ALL</a> | <a href="./" onclick="clearAll('Vendor'); return false;">None</a> ]<br />
						  </td>

						  <td align="center">:</td>

						  <td>
						    <select name="Vendor[]" id="Vendor" size="10" multiple style="min-width:200px;">
<?
	$sSQL = "SELECT id, vendor FROM tbl_vendors WHERE id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y' ORDER BY vendor";
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
						  <td>Region</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Region" style="min-width:200px;">
							  <option value="">All Regions</option>
<?
	$sSQL = "SELECT id, country FROM tbl_countries WHERE matrix='Y' ORDER BY country";
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

					    <tr valign="top">
						  <td>Audit Dates</td>
						  <td align="center">:</td>

						  <td>

						    <table border="0" cellpadding="2" cellspacing="0" width="320">
							  <tr>
							    <td width="78"><input type="text" name="FromDate" value="<?= date("Y-m-01", strtotime("-1 month")) ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
							    <td width="40"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
							    <td>(From Date)</td>
							  </tr>

							  <tr>
							    <td><input type="text" name="ToDate" value="<?= date("Y-m-t", strtotime("-1 month")) ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
							    <td><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
							    <td>(To Date)</td>
							  </tr>
						    </table>

						  </td>
					    </tr>
					  </table>

					</td>

					<td width="55%">

					  <table width="100%" cellspacing="0" cellpadding="3" border="0">
					    <tr>
						  <td width="130">Invoice #</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="InvoiceNo" value="TT-PK-00-<?= date("Y") ?>" maxlength="50" size="15" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Invoice Date</td>
						  <td align="center">:</td>

						  <td>

						    <table border="0" cellpadding="0" cellspacing="0" width="116">
							  <tr>
							    <td width="82"><input type="text" name="InvoiceDate" id="InvoiceDate" value="<?= date('Y-m-d') ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('InvoiceDate'), 'yyyy-mm-dd', this);" /></td>
							    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('InvoiceDate'), 'yyyy-mm-dd', this);" /></td>
							  </tr>
 						    </table>

						  </td>
					    </tr>

					    <tr>
						  <td>Due Date</td>
						  <td align="center">:</td>

						  <td>

						    <table border="0" cellpadding="0" cellspacing="0" width="116">
							  <tr>
							    <td width="82"><input type="text" name="DueDate" id="DueDate" value="<?= date('Y-m-d', strtotime("+15 days")) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('DueDate'), 'yyyy-mm-dd', this);" /></td>
							    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('DueDate'), 'yyyy-mm-dd', this);" /></td>
							  </tr>
 						    </table>

						  </td>
					    </tr>

                        <tr valign="top">
						  <td>Billed From</td>
						  <td align="center">:</td>

						  <td>
						    <select name="BilledFrom" style="width:92%; max-width:92%;">
						      <option value=""></option>
	  	        			  <option value="Triple Tree">Triple Tree</option>
	  	        			  <option value="Matrix Sourcing">Matrix Sourcing</option>
	  	        			</select>
						  </td>
					    </tr>
                                              
					    <tr valign="top">
						  <td>Billed To</td>
						  <td align="center">:</td>

						  <td>
						    <select name="BilledTo" style="width:92%; max-width:92%;">
						      <option value=""></option>
	  	        			  <option value="MGF Sourcing Far East Limited
3/F, Pioneer Place
33 Hoi Yuen Road, Kwun Tong
Hong Kong">MGF Sourcing Far East Limited</option>
	  	        			  <option value="Bonobos Inc
45 W 25th Street, Floor 4
New York, NY 10010
USA">Bonobos Inc</option>
	  	        			  <option value="HIS Textil GmbH
OsterfeldstraBe 12-14
22529 Hamburg
Germany">HIS Textil GmbH</option>
                                                      <option value="Social Fashion Company GmbH, Thebaerstr.17, 50823 Cologne, GDR">
                                                          Social Fashion Company GmbH
                                                      </option>
                                                      <option value="TIMEZONE ESCAPE CLOTHING GMBH Flintsbacher Str. 1, 83098 Brannenburg, GERMANY">
                                                          Timezone
                                                      </option>
                                                      <option value="CWS-Boco Supply Chain Management GmbH
Pankstrasse 8, 13127 Berlin
DE 111134283">
                                                          Modelnstitut Berlin
                                                      </option>
						    </select>
						  </td>
					    </tr>

					    <tr valign="top">
						  <td>Payment Terms</td>
						  <td align="center">:</td>

						  <td>
						    <select name="PaymentTerms" style="width:92%; max-width:92%;">
	  	        			  <option value="Immediate">Immediate</option>
	  	        			  <option value="Payment is due 15 days from date of invoice" selected>Payment is due 15 days from date of invoice</option>
	  	        			  <option value="Payment is due 30 days from date of invoice">Payment is due 30 days from date of invoice</option>
						    </select>
						  </td>
					    </tr>

					    <tr valign="top">
						  <td>Description</td>
						  <td align="center">:</td>
						  <td><textarea name="Description" rows="3" cols="50" style="width:90%;">Quality inspection and production coordination services charges for the month of <?= date("F Y", strtotime("-1 month")) ?></textarea></td>
					    </tr>

					    <tr>
						  <td>Commission Rate (%)</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Rate">
							  <option value="1.0">1.0</option>
							  <option value="1.50">1.50</option>
							  <option value="1.75" selected>1.75</option>
                                                          <option value="2.0">2.0</option>
						    </select>
						  </td>
					    </tr>
						
					    <tr valign="top">
						  <td>Inspections based on</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Inspections">
	  	        			  <option value="Audit">Audit Date</option>
	  	        			  <option value="GAC">PO GAC Date</option>							  
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>POs</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Duplicate">
	  	        			  <option value="Y" selected>All Inspected POs</option>
	  	        			  <option value="N">Unique Inspected POs</option>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Quantity</td>
						  <td align="center">:</td>

						  <td>
						    <select name="Quantity">
	  	        			  <option value="O" selected>Order Qty</option>
	  	        			  <option value="S">Shipped Qty</option>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Discount (%)</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Discount" value="0" maxlength="5" size="5" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Signatures</td>
						  <td align="center">:</td>
						  <td><input type="file" name="Signatures" value="" size="30" class="file" /></td>
					    </tr>
						
					    <tr>
						  <td align="right"><input type="radio" name="NabilaMatrix" value="Y" /></td>						  
						  <td align="center">:</td>
						  <td>Use the NABILA MATRIX DMCC Signatures</td>
        				    </tr>	
                                             <tr>
	                                          <td align="right"><input type="radio" name="NabilaMatrix" value="Y2" /></td>						  
						  <td align="center">:</td>
						  <td>Use the 3-TREE SOLUTIONS Signatures</td>
					    </tr>	  
					  </table>

					</td>
				  </tr>

				  <tr>
				    <td colspan="2">
				      <div style="padding:10px;">
				        Terms & Conditions<br />

					    <textarea name="Terms" rows="8" cols="100" style="width:98%;">1. Commission on FOB is charged at [CommissionRate]% of the Total FOB of the Purchase Order (Section 1: Object and Obligations - Clause 1.3 of Contract)
2. 100% Inspections are charged at $10/Man-Hour (As per Electronic Communication)
3. All values in US$
4. Client Dashboard on the Triple Tree Customer Portal can be used to verify Man-Hour calculations for 100% inspections
5. This is a system generated invoice</textarea>
				      </div>
				    </td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar">
				  <input type="submit" value="" id="BtnExport" class="btnExport" title="Export" onclick="return validateForm( );" />
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

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>