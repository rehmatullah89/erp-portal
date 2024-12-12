<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Salamat School Systems                                                                   **
	**  Version 1.0                                                                              **
	**                                                                                           **
	**  Copyright 2010 (C) Salamat School Systems                                                **
	**  http://www.sss.edu.pk                                                                    **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**  Project Manager:                                                                         **
	**                                                                                           **
	**      Name  :  Muhammad Tahir Shahzad                                                      **
	**      Email :  mtahirshahzad@hotmail.com                                                   **
	**      Phone :  +92 333 456 0482                                                            **
	**      URL   :  http://mts.sw3solutions.com                                                 **
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/index.js"></script>
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
			    <h1>Dashboard</h1>

			    <div class="tblSheet divDashboard">
			      <div style="margin:0px 1px 1px 0px; padding:15px 3px 15px 3px;">
				  
			        <table border="0" cellpadding="0" cellspacing="0" width="100%">
			          <tr>  
						<td width="120" align="center"><img src="images/dashboard/data/new-po.svg" width="70" height="70" vspace="10" alt="" title="" /></td>
						<td width="200"><b><a href="data/add-purchase-order.php" class="link">Add Purchase Order</a></b></td>
						<td></td>
			          </tr>
					  
					  <tr>
					    <td colspan="3" height="15"></td>
					  </tr>

			          <tr>  
						<td align="center"><img src="images/dashboard/data/edit-po.svg" width="70" height="70" vspace="10" alt="" title="" /></td>
						<td><b class="link">Edit Purchase Order</b></td>

						<td>
						  <form name="frmEditPo" id="frmEditPo" method="get" action="data/edit-purchase-order.php">
						    <input type="text" name="OrderNo" id="OrderNo" value="" size="15" maxlength="50" class="textbox" style="padding:4px;" placeholder="PO No" />
							<input type="submit" value="Go" class="button" onclick="return validateEditPo( );" />
						  </form>
						</td>
			          </tr>
					  
					  <tr>
					    <td colspan="3" height="15"></td>
					  </tr>
					  
			          <tr>  
						<td align="center"><img src="images/dashboard/data/view-po.svg" width="70" height="70" vspace="10" alt="" title="" /></td>
						<td><b class="link">View Purchase Order</b></td>

						<td>
						  <form name="frmViewPo" id="frmViewPo" method="get" action="data/purchase-orders.php">
						    <input type="text" name="OrderNo" id="OrderNo" value="" size="15" maxlength="50" class="textbox" style="padding:4px;" placeholder="PO No" />
							<input type="submit" value="Go" class="button" onclick="return validateViewPo( );" />
						  </form>
						</td>
			          </tr>
					  
					  <tr>
					    <td colspan="3" height="15"></td>
					  </tr>
					  
			          <tr>  
						<td  align="center"><img src="images/dashboard/data/new-style.svg" width="70" height="70" vspace="10" alt="" title="" /></td>
						<td><b><a href="data/styles.php" class="link">Enter a Style</a></b></td>
						<td></td>
			          </tr>
					  
					  <tr>
					    <td colspan="3" height="15"></td>
					  </tr>
					  
			          <tr>  
						<td  align="center"><img src="images/dashboard/data/new-season.svg" width="70" height="70" vspace="10" alt="" title="" /></td>
						<td><b><a href="data/seasons.php" class="link">Enter a Season</a></b></td>
						<td></td>
			          </tr>
					  
					  <tr>
					    <td colspan="3" height="15"></td>
					  </tr>
					  
			          <tr>  
						<td align="center"><img src="images/dashboard/data/new-brand.svg" width="70" height="70" vspace="10" alt="" title="" /></td>
						<td><b><a href="data/brands.php" class="link">Enter a Brand</a></b></td>
						<td></td>
			          </tr>
					  
					  <tr>
					    <td colspan="3" height="15"></td>
					  </tr>
					  
			          <tr>  
						<td align="center"><img src="images/dashboard/data/new-vendor.svg" width="70" height="70" vspace="10" alt="" title="" /></td>
						<td><b><a href="data/vendors.php" class="link">Enter a Vendor</a></b></td>						
						<td></td>
			          </tr>
					  
					  <tr>
					    <td colspan="3" height="15"></td>
					  </tr>
					  
			          <tr>  
						<td align="center"><img src="images/dashboard/data/new-size.svg" width="70" height="70" vspace="10" alt="" title="" /></td>
						<td><b><a href="data/sizes.php" class="link">Enter a Size</a></b></td>						
						<td></td>
			          </tr>
			        </table>
					
			      </div>
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

</body>
</html>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>