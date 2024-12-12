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

	$PageId      = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Customer    = IO::strValue("Customer");
	$Brand       = IO::strValue("Brand");
        $Details     = IO::strValue("Details");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Customer    = IO::strValue("Customer");
                $Brand       = IO::strValue("Brand");
                $Details     = IO::strValue("Details");
	}

	$sBrandsList    = getList("tbl_brands", "id", "brand", "parent_id='0' AND id IN (SELECT parent_id FROM tbl_brands WHERE FIND_IN_SET(id, '{$_SESSION['Brands']}'))");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/jquery.js"></script>  
  <script type="text/javascript" src="scripts/data/customers.js"></script>
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
			    <h1>Customers</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-customer.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Customer</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="110">Customer<span class="mandatory">*</span></td>
					<td width="25" align="center">:</td>
					<td><input type="text" name="Customer" value="<?= $Customer ?>" maxlength="50" class="textbox" style="width:200px;"/></td>
				  </tr>

				  <tr>
					<td>Brand<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
                                            <select name="Brand" style="width:205px;">
						<option value=""></option>
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>
                                    
				  </tr>
                                  <tr>
					<td width="110">Other Details</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Details" value="<?= $Details ?>" maxlength="20" class="textbox" style="width:200px;"/></td>
				  </tr>  
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>"  onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="80">Customer</td>
			          <td width="175"><input type="text" name="Customer" value="<?= $Customer ?>" class="textbox" maxlength="50" /></td>
			          
			          <td width="45">Brand</td>

			          <td width="150">
					    <select name="Brand">
						  <option value="">All Brands</option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>


			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Customer != "")
		$sConditions .= " AND customer LIKE '%$Customer%' ";

	if ($Brand != "")
		$sConditions .= " AND brand_id='$Brand' ";

	else
		$sConditions .= " AND brand_id IN (SELECT parent_id FROM tbl_brands WHERE FIND_IN_SET(id, '{$_SESSION['Brands']}')) ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_customers", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_customers $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="5%">#</td>
				      <td width="40%">Customer</td>
				      <td width="20%">Brand</td>
				      <td width="25%">Details</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId          = $objDb->getField($i, 'id');
                $sCustomer    = $objDb->getField($i, 'customer');
		$iBrand       = $objDb->getField($i, 'brand_id');
		$sDetails     = $objDb->getField($i, 'details');                
		
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="5%"><?= ($iStart + $i + 1) ?></td>
				      <td width="40%"><span id="Customer<?= $iId ?>"><?= $sCustomer ?></span></td>
				      <td width="20%"><span id="Brand<?= $iId ?>"><?= $sBrandsList[$iBrand] ?></span></td>
				      <td width="25%"><span id="Details<?= $iId ?>"><?= $sDetails ?></span></td>

				      <td width="10%" class="center">
                                          
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-customer.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Customer?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" enctype="multipart/form-data" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="110">Customer<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
                                                  <td><input type="text" name="Customer" value="<?= $sCustomer ?>" maxlength="50" class="textbox" style="width:200px;" readonly=""/></td>
					    </tr>

					    <tr>
						  <td>Brand<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="Brand" style="width:205px;">
							  <option value=""></option>
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $iBrand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

                                    <tr>
					<td width="110">Other Details</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Details" value="<?= $sDetails ?>" maxlength="20" class="textbox" style="width:200px;"/></td>
				    </tr>
					    <tr>
						  <td></td>
						  <td></td>

						  <td>
						    <input type="submit" id="BtnSave<?= $i ?>" value="SAVE" class="btnSmall" onclick="return validateEditForm(<?= $iId ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
						  </td>
					    </tr>
					  </table>
					  </form>

				    </div>
				  </div>

				  <div id="Msg<?= $iId ?>" class="msgOk" style="display:none;"></div>

<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Customer Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Customer={$Customer}&Brand={$Brand}");
?>

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