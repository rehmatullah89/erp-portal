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

	$PageId             = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Client             = IO::strValue("Client");
	$UserType           = IO::getArray("UserType");
    $AuditorAppSections = IO::getArray("AuditorAppSections");
	$PostId             = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST           = @unserialize($_SESSION[$PostId]);
		
		$Client             = IO::strValue("Client");
		$UserType           = IO::getArray("UserType");
		$AuditorAppSections = IO::getArray("AuditorAppSections");
	}

	$sUserTypesList = getList("tbl_user_types", "id", "type");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/admin/clients.js"></script>
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
			    <h1>Clients</h1>

			    <form name="frmData" id="frmData" method="post" action="admin/save-client.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;" enctype="multipart/form-data">
				<input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
				<h2>Add Client</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
                                  <tr>
					<td width="120">Client</td>
					<td  width="20" align="center">:</td>
					<td><input type="text" name="Client" value="<?= $Client ?>" maxlength="100" size="26" class="textbox" /></td>
				  </tr>
                                    
                                  <tr>
					<td>Code</td>
					<td align="center">:</td>
					<td><input type="text" name="Code" value="<?= $Code ?>" maxlength="100" size="26" class="textbox" /></td>
				  </tr>
                                    
				  <tr>
                                        <td width="60">User Type</td>
					<td width="20" align="center">:</td>

					<td>
                                            <select name="UserType[]" style="min-width:200px; min-height: 140px;" multiple>                                               
<?

                            foreach ($sUserTypesList as $sKey => $sValue)
                            {
?>
                                <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $UserType)) ? " selected" : "") ?>><?= $sValue ?></option>
<?                          }
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Logo (Source)</td>
					<td align="center">:</td>
					<td><input type="file" name="Logo" value="" size="30" class="file" /></td>
				  </tr>
				  
				  <tr>
					<td>Logo (PNG)</td>
					<td align="center">:</td>
					<td><input type="file" name="LogoPng" value="" size="30" class="file" /></td>
				  </tr>
				  
				  <tr>
					<td>Logo (JPG)</td>
					<td align="center">:</td>
					<td><input type="file" name="LogoJpg" value="" size="30" class="file" /></td>
				  </tr>
				  
				  <tr>
					<td>Logo (SVG)</td>
					<td align="center">:</td>
					<td><input type="file" name="LogoSvg" value="" size="30" class="file" /></td>
				  </tr>
				</table>

				<br />

				<div class="buttonsBar"><input type="submit" id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" /></div>
			    </form>

			    <hr />

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="50">Client</td>
			          <td width="180"><input type="text" name="Client" value="<?= $Client ?>" class="textbox" maxlength="50" /></td>

			          <td width="80">User Type</td>

			          <td width="150">
					    <select name="UserType">
						  <option value="">All User Types</option>
                        <?
                            foreach ($sUserTypesList as $sKey => $sValue)
                            {?>
                                <option value="<?= $sKey ?>"<?= ((@in_array($sKey, $UserType)) ? " selected" : "") ?>><?= $sValue ?></option>
<?                          }
                        ?>        
					    </select>
			          </td>


			          <td align="right"><input type="submit" id="BtnSearch" value="" class="btnSearch" title="Search" /></td>
			        </tr>                                  
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "";

	if ($Client != "")
		$sConditions .= " AND title LIKE '%$Client%' ";

	if (!empty($UserType))
		$sConditions .= " AND FIND_IN_SET('".implode(",", $UserType)."', user_types) ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_clients", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_clients $sConditions ORDER BY position LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="6%">#</td>
				      <td width="25%">Client</td>
                      <td width="15%">Code</td>
				      <td width="40%">User Types</td>
				      <td width="14%" class="center">Options</td>
				    </tr>
<?
		}

		$iId        = $objDb->getField($i, 'id');
		$sUserTypes = $objDb->getField($i, 'user_types');
		$sClient    = $objDb->getField($i, 'title');
                $sCode      = $objDb->getField($i, 'code');
                $sAuditorAppSections = $objDb->getField($i, 'auditor_app_sections');
		$iPosition  = $objDb->getField($i, 'position');

		$iNextId     = $objDb->getField(($i + 1), 'id');
		$iPreviousId = $objDb->getField(($i - 1), 'id');

		$iNextPosition     = $objDb->getField(($i + 1), 'position');
		$iPreviousPosition = $objDb->getField(($i - 1), 'position');
?>
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="6%"><?= ($iStart + $i + 1) ?></td>
				      <td width="25%"><span id="Client<?= $iId ?>"><?= $sClient ?></span></td>
                      <td width="15%"><span id="Code<?= $iId ?>"><?= $sCode ?></span></td>
				      <td width="40%"><span id="UserType<?= $iId ?>"><?= getDbValue("GROUP_CONCAT(type SEPARATOR ', ')", "tbl_user_types", "id IN ($sUserTypes)") ?></span></td>

				      <td width="14%" class="right">

						<a href="admin/update-client-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iPreviousId ?>&NewOrder=<?= $iPreviousPosition ?>"><img src="images/icons/up.gif" width="16" height="16" alt="Up" title="Up" border="0" align="absmiddle"></a>
						&nbsp;
<?
		if ($i < ($iCount - 1))
		{
?>
						<a href="admin/update-client-position.php?CurId=<?= $iId ?>&CurOrder=<?= $iPosition ?>&NewId=<?= $iNextId ?>&NewOrder=<?= $iNextPosition ?>"><img src="images/icons/down.gif" width="16" height="16" alt="Down" title="Down" border="0" align="absmiddle"></a>
						&nbsp;
<?
		}

?>
				        <a href="admin/edit-client.php?Id=<?= $iId ?>"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
?>
				        <a href="admin/delete-client.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Client?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Client Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Client={$Client}&UserType={$UserType}");
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