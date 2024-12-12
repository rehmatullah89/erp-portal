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
	$Destination = IO::strValue("Destination");
	$Region      = IO::strValue("Region");
	$Brand       = IO::strValue("Brand");
        $Port        = IO::strValue("Port");
        $BlockNo     = IO::strValue("BlockNo");
	$PostId      = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Destination = IO::strValue("Destination");
		$Region      = IO::strValue("Region");
		$Brand       = IO::strValue("Brand");
                $Port        = IO::strValue("Port");
		$Type        = IO::strValue("Type");
	}

	$sRegionsList   = getList("tbl_destinations", "DISTINCT(region)", "region");
        $sPortsList     = getList("tbl_shipping_ports", "id", "port_name");
	$sBrandsList    = getList("tbl_brands", "id", "brand", "parent_id='0' AND id IN (SELECT parent_id FROM tbl_brands WHERE FIND_IN_SET(id, '{$_SESSION['Brands']}')) OR id = '520'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/jquery.js"></script>  
  <script type="text/javascript" src="scripts/data/destinations.js"></script>
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
			    <h1>Destinations</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-destination.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Destination</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="110">Destination<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="Destination" value="<?= $Destination ?>" maxlength="50" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>Region<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Region">
						<option value=""></option>
<?
		foreach ($sRegionsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Region) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Brand<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Brand">
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

				  <tr>
					<td>Type<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Type">
						<option value=""></option>
			            <option value="W"<?= (($Type == "W") ? " selected" : "") ?>>Warehouse</option>
			            <option value="D"<?= (($Type == "D") ? " selected" : "") ?>>Direct</option>
					  </select>
					</td>
				  </tr>
                                    
                                    <tr>
					<td>Port</td>
					<td align="center">:</td>

					<td>
					  <select name="Port">
						<option value=""></option>
<?
                                                foreach($sPortsList as $key => $value)
                                                {
?>
                                                    <option value="<?=$key?>" <?=($key == $Port?'selected':'')?>><?=$value?></option>    
<?
                                                }
?>
					  </select>
					</td>
				  </tr>
                                  <tr>
					<td width="110">Block</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="BlockNo" value="<?= $BlockNo ?>" maxlength="20" class="textbox" /></td>
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
			          <td width="80">Destination</td>
			          <td width="175"><input type="text" name="Destination" value="<?= $Destination ?>" class="textbox" maxlength="50" /></td>
			          <td width="52">Region</td>

			          <td width="150">
					    <select name="Region">
						  <option value="">All Regions</option>
<?
	foreach ($sRegionsList as $sKey => $sValue)
	{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Region) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					    </select>
			          </td>

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

	if ($Destination != "")
		$sConditions .= " AND destination LIKE '%$Destination%' ";

	if ($Region != "")
		$sConditions .= " AND region='$Region' ";

	if ($Brand != "")
		$sConditions .= " AND brand_id='$Brand' ";

	else
		$sConditions .= " AND brand_id IN (SELECT parent_id FROM tbl_brands WHERE FIND_IN_SET(id, '{$_SESSION['Brands']}')) ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_destinations", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_destinations $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="8%">#</td>
				      <td width="35%">Destination</td>
				      <td width="20%">Region</td>
				      <td width="15%">Brand</td>
				      <td width="14%">Type</td>
				      <td width="8%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId          = $objDb->getField($i, 'id');
		$iBrand       = $objDb->getField($i, 'brand_id');
		$iRegion      = $objDb->getField($i, 'region');
		$sDestination = $objDb->getField($i, 'destination');
                $iPort        = $objDb->getField($i, 'port_id');
                $iBlockNo     = $objDb->getField($i, 'block_no');
		$sType        = $objDb->getField($i, 'type');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="35%"><span id="Destination<?= $iId ?>"><?= $sDestination ?></span></td>
				      <td width="20%"><span id="Region<?= $iId ?>"><?= $sRegionsList[$iRegion] ?></span></td>
				      <td width="15%"><span id="Brand<?= $iId ?>"><?= $sBrandsList[$iBrand] ?></span></td>
				      <td width="14%"><span id="Type<?= $iId ?>"><?= (($sType == "D") ? "Direct" : (($sType == "W") ? "Warehouse" : "")) ?></span></td>

				      <td width="8%" class="center">
                                          
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
				        <a href="data/delete-destination.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Destination?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
						  <td width="110">Destination<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Destination" value="<?= $sDestination ?>" maxlength="50" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Region<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="Region">
							  <option value=""></option>
<?
		foreach ($sRegionsList as $sKey => $sValue)
		{
?>
			              	  <option value="<?= $sKey ?>"<?= (($sKey == $iRegion) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>Brand<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="Brand">
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
						  <td>Type<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="Type">
							  <option value=""></option>
							  <option value="W"<?= (($sType == "W") ? " selected" : "") ?>>Warehouse</option>
							  <option value="D"<?= (($sType == "D") ? " selected" : "") ?>>Direct</option>
						    </select>
						  </td>
					    </tr>
                                              <tr>
					<td>Port</td>
					<td align="center">:</td>

					<td>
					  <select name="Port">
						<option value=""></option>
<?
                                                foreach($sPortsList as $key => $value)
                                                {
?>
                                                    <option value="<?=$key?>" <?=($key == $iPort?'selected':'')?>><?=$value?></option>    
<?
                                                }
?>
					  </select>
					</td>
				  </tr>
                                              <tr>
					<td width="110">Block</td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="BlockNo" value="<?= $iBlockNo ?>" maxlength="20" class="textbox" /></td>
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
				      <td class="noRecord">No Destination Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Destination={$Destination}&Region={$Region}&Brand={$Brand}");
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