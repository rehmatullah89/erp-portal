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

	$PageId  = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Country = IO::strValue("Country");
        $Hours   = IO::strValue("Hours");
	$PostId  = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Country = IO::strValue("Country");
	}

	$sCountriesList = getList("tbl_countries", "id", "country", "matrix='N'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/countries.js"></script>
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
			    <h1>Countries Listing</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-country.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Country</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="60">Country<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>

					<td>
					  <select name="Country">
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
                                  <tr>  
                                        <td width="60">Hours<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
                                        <td><input type="text" name="Hours" value="<?= $Hours ?>" size="28" maxlength="255" class="textbox" /></td>
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
			          <td width="60">Country</td>
			          <td width="150"><input type="text" name="Country" value="<?= $Country ?>" class="textbox" maxlength="50" size="20" /></td>
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
	$sConditions = " AND matrix='Y' ";

	if ($Country != "")
		$sConditions .= " AND (country LIKE '%$Country%' OR code LIKE '$Country')";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_countries", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, country, hours FROM tbl_countries $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
                              <td width="60%">Country</td>
                              <td width="20%">Hours</td>
                              <td width="12%" class="center">Options</td>
                            </tr>
                          </table>
				    
<?
		}

		$iId = $objDb->getField($i, 'id');
?>

                                <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="8%"><?= ($iStart + $i + 1) ?></td>

                                      <td width="60%"><span id="Country<?= $iId ?>"><?= $objDb->getField($i, 'country') ?></span></td>
                                      <td width="20%"><span id="Hours<?= $iId ?>"><?= $objDb->getField($i, 'hours') ?></span></td>
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
				        <a href="data/delete-country.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Country?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
                                  <td width="60">Country<span class="mandatory">*</span></td>
                                  <td width="20" align="center">:</td>
                                  <td><input type="text" name="Country" value="<?= $objDb->getField($i, 'country') ?>" size="30" maxlength="50" class="textbox" /></td>
                            </tr>

                            <tr>
                                  <td>Hours<span class="mandatory">*</span></td>
                                  <td align="center">:</td>
                                  <td><input type="text" name="Hours" value="<?= $objDb->getField($i, 'hours') ?>" size="12" maxlength="10" class="textbox" /></td>
                            </tr>
                          </table>
                          
                          <table border="0" cellpadding="3" cellspacing="0" width="100%">
                            <tr>
                                  <td width="100%">
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
				      <td class="noRecord">No Country Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Country={$Country}");
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