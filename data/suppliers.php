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

	$PageId         = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Supplier       = IO::strValue("Supplier");
	$Country        = IO::intValue("Country");
        $Code           = IO::strValue("Code"); 
        $City           = IO::strValue("City");
        $Address        = IO::strValue("Address");
        $Latitude       = IO::strValue("Latitude");
        $Longitude      = IO::strValue("Longitude");
	$Email          = IO::strValue("Email");
        $Phone          = IO::strValue("Phone");
        $Fax            = IO::strValue("Fax");        
        $PersonName     = IO::strValue("PersonName");
        $PersonEmail    = IO::strValue("PersonEmail");
        $PersonPhone    = IO::strValue("PersonPhone");
        $PersonFax      = IO::strValue("PersonFax");
        $PersonPicture  = IO::strValue("PersonPicture");
        $PortRequired   = IO::strValue("PortRequired");
        $PostId         = IO::strValue("PostId");
        

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

                $Supplier       = IO::strValue("Supplier");
                $Code           = IO::strValue("Code"); 
                $City           = IO::strValue("City");
                $Address        = IO::strValue("Address");
                $Country        = IO::intValue("Country");
                $Latitude       = IO::strValue("Latitude");
                $Longitude      = IO::strValue("Longitude");
                $Email          = IO::strValue("Email");
                $Phone          = IO::strValue("Phone");
                $Fax            = IO::strValue("Fax");        
                $PersonName     = IO::strValue("PersonName");
                $PersonEmail    = IO::strValue("PersonEmail");
                $PersonPhone    = IO::strValue("PersonPhone");
                $PersonFax      = IO::strValue("PersonFax");
                $PortRequired   = IO::strValue("PortRequired");
                $PersonPicture  = IO::strValue("PersonPicture");
	}

	$sCountriesList  = getList("tbl_countries", "id", "country", "matrix='Y'");
	$sSuppliersList    = getList("tbl_suppliers", "id", "supplier", "parent_id='0'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/suppliers.js"></script>
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
			    <h1><img src="images/h1/data/suppliers.jpg" vspace="10" alt="" title="" /></h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-supplier.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Supplier</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="48%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
                                                <td width="100">Supplier<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Supplier" value="<?= $Supplier ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>Code<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Code" value="<?= $Code ?>" size="30" maxlength="25" class="textbox" /></td>
					    </tr>

                                              <tr valign="top">
						  <td>Phone<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Phone" value="<?= $Phone ?>" size="30" class="textbox" /></td>
					    </tr>
                                              
                                            <tr valign="top">
						  <td>Email</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Email" value="<?= $Email ?>" size="30" class="textbox" /></td>
					    </tr> 
                                              
                                            <tr valign="top">
						  <td>Fax</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Fax" value="<?= $Fax ?>" size="30" class="textbox" /></td>
					    </tr>   
                                            
					    <tr>
						  <td>City<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="City" value="<?= $City ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Country<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
                                                      <select name="Country" style="width:230px;">
							  <option value=""></option>
<?
		foreach ($sCountriesList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $Country) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>
                                              
                                            <tr valign="top">
						  <td>Address</td>
						  <td align="center">:</td>
						  <td><textarea name="Address" rows="5" cols="28" ><?= $Address ?></textarea></td>
					    </tr>
					  </table>

					</td>

					<td width="52%">

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                              
                                            <tr>
						  <td width="180">Contact Person</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="PersonName" value="<?= $PersonName ?>" size="30" class="textbox" /></td>
					    </tr>
                                              

					    <tr>
						  <td width="165">Contact Person Email</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="PersonEmail" value="<?= $PersonEmail ?>" size="30" maxlength="50" class="textbox" /></td>
					    </tr>
                                              
                                            <tr>
						  <td width="165">Contact Person Phone</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="PersonPhone" value="<?= $PersonPhone ?>" size="30" maxlength="50" class="textbox" /></td>
					    </tr>
                                              
                                            <tr>
						  <td width="165">Contact Person Fax</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="PersonFax" value="<?= $PersonFax ?>" size="30" maxlength="50" class="textbox" /></td>
					    </tr>  

					    <tr>
						  <td width="165">Contact Person picture</td>
						  <td width="20" align="center">:</td>
						  <td><input type="file" name="PersonPicture" value="" size="30" maxlength="50" class="textbox" style="width:224px;"/></td>
					    </tr> 

					    <tr>
						  <td>Latitude</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Latitude" id="Latitude" value="<?= $Latitude ?>" size="30" maxlength="35" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Longitude</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Longitude" id="Longitude" value="<?= $Longitude ?>" size="30" maxlength="35" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td colspan="3">* <a href="data/google-latlong.php?Lat=Latitude&Lon=Longitude" class="lightview" rel="iframe" title="Supplier Latitude/Longitude :: :: width:780, height:561">Find Latitude/Longitude</a></td>
					    </tr>
                                            <tr>
						  <td>Port Selection Required for Booking?</td>
						  <td align="center">:</td>
						  <td><input type="checkbox" name="PortRequired" id="PortRequired" value="Y" <?= ($PortRequired == 'Y'?'checked':'') ?> /></td>
					    </tr>
					  </table>

					</td>
				  </tr>
				</table>

				<div style="padding:10px;">
				  Supplier Profile<br />
				  <textarea name="Profile" rows="8" cols="30" style="width:99%;"><?= $Profile ?></textarea><br />
				</div>

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />
<?
	}
?>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="55">Supplier</td>
			          <td width="170"><input type="text" name="Supplier" value="<?= $Supplier ?>" class="textbox" maxlength="50" /></td>
			          
			          <td width="60">Country</td>

			          <td width="150">
					    <select name="Country">
						  <option value="">All Countries</option>
<?
	foreach ($sCountriesList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Country) ? " selected" : "") ?>><?= $sValue ?></option>
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

	if ($Supplier != "")
		$sConditions .= " AND (supplier LIKE '%$Supplier%' OR city LIKE '%$Supplier%') ";

	if ($Country > 0)
		$sConditions .= " AND country_id='$Country' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_suppliers", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_suppliers $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="20%">Supplier</td>
				      <td width="10%">Code</td>
				      <td width="18%">Phone</td>
				      <td width="15%">City</td>
				      <td width="17%">Country</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}


		$iId                         = $objDb->getField($i, 'id');
		$sSupplier                   = $objDb->getField($i, 'supplier');
		$sCode                       = $objDb->getField($i, 'code');
		$sCity                       = $objDb->getField($i, 'city');
		$sAddress                    = $objDb->getField($i, 'address');
		$iCountry                    = $objDb->getField($i, 'country_id');
		$sLatitude                   = $objDb->getField($i, 'latitude');
		$sLongitude                  = $objDb->getField($i, 'longitude');
                $sPortRequired               = $objDb->getField($i, 'port_required');
		$sProfile                    = $objDb->getField($i, 'profile');
                $sFax                        = $objDb->getField($i, 'fax');
                $sPhone                      = $objDb->getField($i, 'phone');
                $sEmail                      = $objDb->getField($i, 'email');
                $sPersonName                 = $objDb->getField($i, "contact_person");
                $sPersonEmail                = $objDb->getField($i, "person_email");
                $sPersonPhone                = $objDb->getField($i, "person_phone");
                $sPersonFax                  = $objDb->getField($i, "person_fax");
                $sPersonPicture              = $objDb->getField($i, "person_picture");
                
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>
				      <td width="20%"><span id="Supplier<?= $iId ?>"><?= $sSupplier ?></span></td>
				      <td width="10%"><span id="Code<?= $iId ?>"><?= $sCode ?></span></td>
				      <td width="18%"><span id="Category<?= $iId ?>"><?= $sPhone ?></span></td>
				      <td width="15%"><span id="City<?= $iId ?>"><?= $sCity ?></span></td>
				      <td width="17%"><span id="Country<?= $iId ?>"><?= $sCountriesList[$iCountry] ?></span></td>

				      <td width="12%" class="center">
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
				        <a href="data/delete-supplier.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Supplier?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				        &nbsp;
<?
		}
?>
				        <a href="data/view-supplier.php?Id=<?= $iId ?>" class="lightview" rel="iframe" title="Supplier : <?= $sSupplier ?> :: :: width:700, height:550"><img src="images/icons/view.gif" width="16" height="16" alt="View" title="View" /></a>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr valign="top">
						  <td width="48%">
                                                      					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
                                                <td width="100">Supplier<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="Supplier" value="<?= $sSupplier ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr valign="top">
						  <td>Code<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Code" value="<?= $sCode ?>" size="30" maxlength="25" class="textbox" /></td>
					    </tr>

                                              <tr valign="top">
						  <td>Phone<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Phone" value="<?= $sPhone ?>" size="30" class="textbox" /></td>
					    </tr>
                                              
                                            <tr valign="top">
						  <td>Email</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Email" value="<?= $sEmail ?>" size="30" class="textbox" /></td>
					    </tr> 
                                              
                                            <tr valign="top">
						  <td>Fax</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Fax" value="<?= $sFax ?>" size="30" class="textbox" /></td>
					    </tr>   
                                            
					    <tr>
						  <td>City<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="City" value="<?= $sCity ?>" size="30" maxlength="100" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Country<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
                                                      <select name="Country" style="width:230px;">
							  <option value=""></option>
<?
		foreach ($sCountriesList as $sKey => $sValue)
		{
?>
			            	  <option value="<?= $sKey ?>"<?= (($sKey == $iCountry) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>
                                              
                                            <tr valign="top">
						  <td>Address</td>
						  <td align="center">:</td>
						  <td><textarea name="Address" rows="5" cols="28" ><?= $sAddress ?></textarea></td>
					    </tr>
					  </table>                                                      
						  </td>

                                        <td width="52%">
                                            <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                              
                                            <tr>
						  <td width="130">Contact Person</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="PersonName" value="<?= $sPersonName ?>" size="30" class="textbox" /></td>
					    </tr>
                                              

					    <tr>
						  <td width="165">Contact Person Email</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="PersonEmail" value="<?= $sPersonEmail ?>" size="30" maxlength="50" class="textbox" /></td>
					    </tr>
                                              
                                            <tr>
						  <td width="165">Contact Person Phone</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="PersonPhone" value="<?= $sPersonPhone ?>" size="30" maxlength="50" class="textbox" /></td>
					    </tr>
                                              
                                            <tr>
						  <td width="165">Contact Person Fax</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="PersonFax" value="<?= $sPersonFax ?>" size="30" maxlength="50" class="textbox" /></td>
					    </tr>  

					    <tr>
						  <td width="165">Contact Person picture</td>
						  <td width="20" align="center">:</td>
						  <td><input type="file" name="PersonPicture" value="" size="30" maxlength="50" class="textbox" style="width:224px;"/></td>
					    </tr> 

					    <tr>
						  <td>Latitude</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Latitude" id="Latitude" value="<?= $sLatitude ?>" size="30" maxlength="35" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>Longitude</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Longitude" id="Longitude" value="<?= $sLongitude ?>" size="30" maxlength="35" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td colspan="3">* <a href="data/google-latlong.php?Lat=Latitude&Lon=Longitude" class="lightview" rel="iframe" title="Supplier Latitude/Longitude :: :: width:780, height:561">Find Latitude/Longitude</a></td>
					    </tr>
                                            <tr>
						  <td>Port Selection Required for Booking?</td>
						  <td align="center">:</td>
						  <td><input type="checkbox" name="PortRequired" id="PortRequired" value="Y" <?= ($sPortRequired == 'Y'?'checked':'') ?> /></td>
					    </tr>
					  </table>

                                        </td>
					    </tr>
					  </table>

					  <div style="padding:10px;">
					    Supplier Profile<br />
					    <textarea name="Profile" rows="8" cols="30" style="width:99%;"><?= $sProfile ?></textarea><br />
					  </div>

					  <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
					  <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />
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
				      <td class="noRecord">No Supplier Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Supplier={$Supplier}&Category={$Category}&Country={$Country}");
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