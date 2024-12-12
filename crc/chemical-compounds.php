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

	$PageId   = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Type     = IO::intValue("Type");
	$Compound = IO::strValue("Compound");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Type     = IO::intValue("Type");
		$Compound = IO::strValue("Compound");
		$CasNo    = IO::strValue("CasNo");
	}

	$sTypeList = getList("tbl_chemical_types", "id", "type");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/crc/chemical-compounds.js"></script>
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
			    <h1>Chemical Compounds</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="crc/save-chemical-compound.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Chemical Compound</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="130">Chemical Type<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Type">
					    <option value="">Select Chemical Type</option>
<?
		foreach ($sTypeList as $sKey => $sValue)
		{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Type) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					  </select>
					</td>
				  </tr>
  
				  <tr>
					<td>Chemical Compound<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="Compound" value="<?= $Compound ?>" maxlength="250" size="30" class="textbox" /></td>
				  </tr>
  
				  <tr>
					<td>CAS No.<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="CasNo" value="<?= $CasNo ?>" maxlength="100" size="30" class="textbox" /></td>
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
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="85">Compound</td>
			          <td width="200"><input type="text" name="Compound" value="<?= $Compound ?>" class="textbox" maxlength="50" /></td>				  
			          <td width="40">Type</td>
					  
			          <td width="200">
					    <select name="Type">
						  <option value="">All Types</option>
<?
	foreach ($sTypeList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Type) ? " selected" : "") ?>><?= $sValue ?></option>
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

	if ($Compound != "")
		$sConditions .= " AND (compound LIKE '%$Compound%' OR cas_no LIKE '%$CasNo%') ";

	if ($Type != "")
		$sConditions .= " AND type_id='$Type' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_chemical_compounds", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_chemical_compounds $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="6%">#</td>
				      <td width="32%">Chemical Compound</td>
				      <td width="20%">Cas No.</td>
					  <td width="34%">Chemical Type</td>					  
				      <td width="8%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$iType     = $objDb->getField($i, 'type_id');
		$sCompound = $objDb->getField($i, 'compound');
        $sCasNo    = $objDb->getField($i, 'cas_no');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="6%"><?= ($iStart + $i + 1) ?></td>
				      <td width="32%"><span id="Compound<?= $iId ?>"><?= $sCompound ?></span></td>
                      <td width="20%"><span id="CasNo<?= $iId ?>"><?= $sCasNo ?></span></td>
				      <td width="34%"><span id="Type<?= $iId ?>"><?= $sTypeList[$iType] ?></span></td>					  
				      <td width="8%" class="right">
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
						<a href="crc/delete-chemical-compound.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Compound?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
                        <tr>
						  <td width="130">Chemical Type<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
						    <select name="Type">
						      <option value="">Select Chemical Type</option>
<?
		foreach ($sTypeList as $sKey => $sValue)
		{
?>
			                  <option value="<?= $sKey ?>"<?= (($sKey == $iType) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						    </select>
						  </td>
						</tr>
						
						<tr>
						  <td>Chemical Compound<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="Compound" value="<?= $sCompound ?>" maxlength="250" size="30" class="textbox" /></td>
						</tr>
					  
						<tr>
						  <td>Cas No.<span class="mandatory">*</span></td>
						  <td align="center">:</td>
						  <td><input type="text" name="CasNo" value="<?= $sCasNo ?>" maxlength="100" size="30" class="textbox" /></td>
						</tr>

						<tr>
						  <td></td>
						  <td></td>

						  <td>
						    <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $iId ?>);" />
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
				      <td class="noRecord">No Chemical Compound Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Compound={$Compound}&Type={$Type}");
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