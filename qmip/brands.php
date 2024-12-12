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

	$PageId = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$Brand  = IO::strValue("Brand");
	$Parent = IO::strValue("Parent");


	if (@strpos($_SESSION["Brands"], ",") === FALSE)
		$Brand = $_SESSION["Brands"];

	$sBrandsList = getList("tbl_brands", "id", "brand", "parent_id='0'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
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
			    <h1>Brands</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="$('BtnSearch').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td width="45">Brand</td>
			          <td width="200"><input type="text" name="Brand" value="<?= $Brand ?>" class="textbox" maxlength="50" /></td>
			          <td width="55">Parent</td>

			          <td width="200">
					    <select name="Parent">
						  <option value="">All Brands</option>
<?
	foreach ($sBrandsList as $sKey => $sValue)
	{
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $Parent) ? " selected" : "") ?>><?= $sValue ?></option>
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
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass      = array("evenRow", "oddRow");
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = " WHERE qmip='Y' AND id IN ({$_SESSION['Brands']}) AND parent_id>'0' ";

	if ($Brand != "")
		$sConditions .= " AND brand LIKE '%$Brand%' ";

	if ($Parent != "")
		$sConditions .= " AND parent_id='$Parent' ";


	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_brands", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT id, parent_id, brand, aql FROM tbl_brands $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="5%">#</td>
				      <td width="25%">Brand</td>
				      <td width="30%">Parent Brand</td>
				      <td width="30%">AQL</td>
				      <td width="10%" class="center">Options</td>
				    </tr>
<?
		}

		$iId     = $objDb->getField($i, 'id');
		$iParent = $objDb->getField($i, 'parent_id');
		$sBrand  = $objDb->getField($i, 'brand');
		$fAql    = $objDb->getField($i, 'aql');
?>
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= $sBrand ?></td>
				      <td><?= $sBrandsList[$iParent] ?></td>

				      <td>
				        <span id="Aql<?= $i ?>"><?= formatNumber($fAql) ?></span>
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>

						<script type="text/javascript">
						<!--
						    var objEditor<?= $i ?> = new Ajax.InPlaceEditor('Aql<?= $i ?>', 'ajax/qmip/update-brand.php', { cancelControl:'button', okText:'  Ok  ', cancelText:'Cancel', clickToEditText:'Click to Edit', externalControl:'Edit<?= $i ?>', highlightcolor:'<?= HOVER_ROW_COLOR ?>', highlightendcolor:'<?= $sColor[($i % 2)] ?>', callback:function(form, value) { return 'Id=<?= $iId ?>&Aql=' + encodeURIComponent(value) }, onEnterEditMode:function(form, value) { $('Aql<?= $i ?>').focus( ); } });
						-->
						</script>
<?
		}
?>
				      </td>

				      <td class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
                        <a href="./" id="Edit<?= $i ?>" onclick="objEditor<?= $i ?>.enterEditMode( ); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}
?>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Brand Record Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Brand={$Brand}&Parent={$Parent}");
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