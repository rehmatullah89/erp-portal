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

	$PageId     = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$PortName   = IO::strValue("PortName");
	$PostId     = IO::strValue("PostId");

	if ($PostId != "")
	{
            $_REQUEST   = @unserialize($_SESSION[$PostId]);
            $PortName   = IO::strValue("PortName");
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/jquery.js"></script>  
  <script type="text/javascript" src="scripts/data/ports.js"></script>
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
			    <h1><img src="images/h1/data/ports.jpg" vspace="10" alt="" title="" /></h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-port.php" enctype="multipart/form-data" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Shipping Port</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="140">Port Name<span class="mandatory">*</span></td>
					<td width="20" align="center">:</td>
					<td><input type="text" name="PortName" value="<?= $PortName ?>" maxlength="50" class="textbox" /></td>
				  </tr>

                                    <tr>
					<td>Symbol</td>
					<td align="center">:</td>
					<td>
                                            <input type="file" name="Picture" size="21" class="textbox" />
					</td>
				    </tr>
                                    
                                    <tr>
					<td>Symbol for Pdf</td>
					<td align="center">:</td>
					<td>
                                            <input type="file" name="PdfSymbol" size="21" class="textbox" />
					</td>
				    </tr>
                                    
                                    <tr>
					<td>Display at Booking Form</td>
					<td align="center">:</td>
					<td>
                                            <input type="checkbox" value="Y" name="DisplayAtBooking" size="21" class="textbox" />
					</td>
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
			          <td width="80">Port Name</td>
			          <td width="175"><input type="text" name="PortName" value="<?= $PortName ?>" class="textbox" maxlength="50" /></td>
			         
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

	if ($PortName != "")
		$sConditions .= " AND port_name LIKE '%$PortName%' ";

	if ($sConditions != "")
		$sConditions = (" WHERE ".@substr($sConditions, 5));

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_shipping_ports", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_shipping_ports $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="10%">#</td>
				      <td width="70%">Port Name</td>
				      <td width="20%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId                = $objDb->getField($i, 'id');
		$sPortName          = $objDb->getField($i, 'port_name');
                $sDisplayAtBooking  = $objDb->getField($i, 'booking_form');
                $sPdfSymbol         = $objDb->getField($i, 'pdf_symbol');
                $sPicture           = $objDb->getField($i, 'symbol');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="10%"><?= ($iStart + $i + 1) ?></td>
				      <td width="70%"><span id="Destination<?= $iId ?>"><?= $sPortName ?></span></td>
				      <td width="20%" class="center">
                                          
<?
		if ($sPicture != "" && @file_exists($sBaseDir.SHIPPING_PORTS_DIR.$sPicture))
		{
?>
				        <a href="<?= SHIPPING_PORTS_DIR.$sPicture ?>" class="lightview"><img src="images/icons/thumb.gif" width="16" height="16" hspace="2" alt="Port Icon" title="Port Icon" /></a>
<?
		}
                
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
				        <a href="data/delete-port.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Port?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
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
						  <td width="140">Port Name<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="PortName" value="<?= $sPortName ?>" maxlength="50" class="textbox" /></td>
					    </tr>

                                              <tr>
                                                <td>Symbol<span class="mandatory">*</span></td>
                                                <td align="center">:</td>
                                                <td>
                                                    <input type="file" name="Picture" id="Picture<?=$iId?>"  size="21" class="textbox" />
                                                </td>
                                                </tr>
					    <tr>
                                                <tr>
                                                    <td>Symbol for Pdf</td>
                                                    <td align="center">:</td>
                                                    <td>
                                                        <input type="file" name="PdfSymbol" id="PdfSymbol<?=$iId?>" size="21" class="textbox" />
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Display at Booking Form</td>
                                                    <td align="center">:</td>
                                                    <td>
                                                        <input type="checkbox" value="Y" name="DisplayAtBooking" <?=($sDisplayAtBooking == 'Y'?"checked":"")?> size="21" class="textbox" />
                                                    </td>
                                                </tr>
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
				      <td class="noRecord">No Shipping Port Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&PortName={$PortName}");
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