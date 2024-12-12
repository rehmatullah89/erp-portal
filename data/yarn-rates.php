<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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
	$FromDate = IO::strValue("FromDate");
	$ToDate   = IO::strValue("ToDate");
	$PostId   = IO::strValue("PostId");

	if ($PostId != "")
	{
		$_REQUEST = @unserialize($_SESSION[$PostId]);

		$Date      = IO::strValue("Date");
		$S10       = IO::floatValue("S10");
		$S20       = IO::floatValue("S20");
		$S30       = IO::floatValue("S30");
		$Cd10      = IO::floatValue("Cd10");
		$Cd12      = IO::floatValue("Cd12");
		$Cd14      = IO::floatValue("Cd14");
		$Cd16      = IO::floatValue("Cd16");
		$Cd20      = IO::floatValue("Cd20");
		$Cd21      = IO::floatValue("Cd21");
		$Cd30      = IO::floatValue("Cd30");
		$Cm30Cpt   = IO::floatValue("Cm30Cpt");
		$Cm40      = IO::floatValue("Cm40");
		$Cd12Spndx = IO::floatValue("Cd12Spndx");
		$Dsp1670   = IO::floatValue("Dsp1670");
	}


	$sBrandsList   = getList("tbl_brands", "id", "brand", "parent_id='0'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/data/yarn-rates.js"></script>
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
			    <h1>yarn Rates</h1>

<?
	if ($sUserRights['Add'] == "Y")
	{
?>
			    <form name="frmData" id="frmData" method="post" action="data/save-yarn-rate.php" class="frmOutline" onsubmit="$('BtnSave').disabled=true;">
				<h2>Add Yarn Rates</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="40">Date</td>
					<td width="20" align="center">:</td>

					<td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
						<tr>
						  <td width="82"><input type="text" name="Date" id="Date" value="<?= (($Date == "") ? date("Y-m-d") : $Date) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('Date'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

					</td>

					<td width="140"></td>
					<td width="20"></td>
					<td></td>
				  </tr>

				  <tr>
					<td>10cd</td>
					<td align="center">:</td>
					<td><input type="text" name="Cd10" value="<?= $Cd10 ?>" size="10" class="textbox" /></td>
					<td>10s</td>
					<td align="center">:</td>
					<td><input type="text" name="S10" value="<?= $S10 ?>" size="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>12cd</td>
					<td align="center">:</td>
					<td><input type="text" name="Cd12" value="<?= $Cd12 ?>" size="10" class="textbox" /></td>
					<td>20s</td>
					<td align="center">:</td>
					<td><input type="text" name="S20" value="<?= $S20 ?>" size="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>14cd</td>
					<td align="center">:</td>
					<td><input type="text" name="Cd14" value="<?= $Cd14 ?>" size="10" class="textbox" /></td>
					<td>30s</td>
					<td align="center">:</td>
					<td><input type="text" name="S30" value="<?= $S30 ?>" size="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>16cd</td>
					<td align="center">:</td>
					<td><input type="text" name="Cd16" value="<?= $Cd16 ?>" size="10" class="textbox" /></td>
					<td>30cm cpt</td>
					<td align="center">:</td>
					<td><input type="text" name="Cm30Cpt" value="<?= $Cm30Cpt ?>" size="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>20cd</td>
					<td align="center">:</td>
					<td><input type="text" name="Cd20" value="<?= $Cd20 ?>" size="10" class="textbox" /></td>
					<td>40cm</td>
					<td align="center">:</td>
					<td><input type="text" name="Cm40" value="<?= $Cm40 ?>" size="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>21cd</td>
					<td align="center">:</td>
					<td><input type="text" name="Cd21" value="<?= $Cd21 ?>" size="10" class="textbox" /></td>
					<td>12/1 CD +70 DN Spndx</td>
					<td align="center">:</td>
					<td><input type="text" name="Cd12Spndx" value="<?= $Cd12Spndx ?>" size="10" class="textbox" /></td>
				  </tr>

				  <tr>
					<td>30cd</td>
					<td align="center">:</td>
					<td><input type="text" name="Cd30" value="<?= $Cd30 ?>" size="10" class="textbox" /></td>
					<td>16+70Dsp</td>
					<td align="center">:</td>
					<td><input type="text" name="Dsp1670" value="<?= $Dsp1670 ?>" size="10" class="textbox" /></td>
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
					  <td width="40">From</td>
					  <td width="78"><input type="text" name="FromDate" value="<?= $FromDate ?>" id="FromDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="20"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30" align="center">To</td>
					  <td width="78"><input type="text" name="ToDate" value="<?= $ToDate ?>" id="ToDate" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="30"><img src="images/icons/calendar2.gif" width="16" height="16" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
					  <td width="100">[ <a href="#" onclick="$('FromDate').value=''; $('ToDate').value=''; return false;">Clear</a> ]</td>
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

	if ($FromDate != "" && $ToDate != "")
		$sConditions = " WHERE (day BETWEEN '$FromDate' AND '$ToDate') ";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_yarn_rates", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_yarn_rates $sConditions ORDER BY day DESC LIMIT $iStart, $iPageSize";
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
				      <td width="18%">Date</td>
				      <td width="10%">10cd</td>
				      <td width="10%">12cd</td>
				      <td width="10%">14cd</td>
				      <td width="10%">16cd</td>
				      <td width="10%">20cd</td>
				      <td width="10%">21cd</td>
				      <td width="16%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$sDate      = $objDb->getField($i, 'day');
		$fS10       = $objDb->getField($i, 's10');
		$fS20       = $objDb->getField($i, 's20');
		$fS30       = $objDb->getField($i, 's30');
		$fCd10      = $objDb->getField($i, 'cd10');
		$fCd12      = $objDb->getField($i, 'cd12');
		$fCd14      = $objDb->getField($i, 'cd14');
		$fCd16      = $objDb->getField($i, 'cd16');
		$fCd20      = $objDb->getField($i, 'cd20');
		$fCd21      = $objDb->getField($i, 'cd21');
		$fCd30      = $objDb->getField($i, 'cd30');
		$fCm30Cpt   = $objDb->getField($i, 'cm30_cpt');
		$fCm40      = $objDb->getField($i, 'cm40');
		$fCd12Spndx = $objDb->getField($i, 'cd12_spndx');
		$fDsp1670   = $objDb->getField($i, 'dsp16_70');
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="6%"><?= ($iStart + $i + 1) ?></td>
				      <td width="18%"><span id="Date<?= $i ?>"><?= formatDate($sDate) ?></span></td>
				      <td width="10%"><span id="Cd10<?= $i ?>"><?= formatNumber($fCd10, false) ?></span></td>
				      <td width="10%"><span id="Cd12<?= $i ?>"><?= formatNumber($fCd12, false) ?></span></td>
				      <td width="10%"><span id="Cd14<?= $i ?>"><?= formatNumber($fCd14, false) ?></span></td>
				      <td width="10%"><span id="Cd16<?= $i ?>"><?= formatNumber($fCd16, false) ?></span></td>
				      <td width="10%"><span id="Cd20<?= $i ?>"><?= formatNumber($fCd20, false) ?></span></td>
				      <td width="10%"><span id="Cd21<?= $i ?>"><?= formatNumber($fCd21, false) ?></span></td>

				      <td width="16%" class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('Edit<?= $i ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
				        &nbsp;
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        <a href="data/delete-yarn-rate.php?Date=<?= $sDate ?>" onclick="return confirm('Are you SURE, You want to Delete this Rates Record?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				        <a href="data/view-yarn-rate.php?Date=<?= $sDate ?>" class="lightview" rel="iframe" title="Date : <?= formatDate($sDate) ?> :: :: width: 400, height:400"><img src="images/icons/view.gif" width="16" height="16" hspace="2" alt="View" title="View" /></a>
				      </td>
				    </tr>
				  </table>

				  <div id="Edit<?= $i ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmData<?= $i ?>" id="frmData<?= $i ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $i ?>" />
					  <input type="hidden" name="Date" value="<?= $sDate ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="40">Date</td>
						  <td width="20" align="center">:</td>
						  <td><?= formatDate($sDate) ?></td>
						  <td width="140"></td>
						  <td width="20" align="center"></td>
						  <td></td>
					    </tr>

					    <tr>
						  <td>10cd</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Cd10" value="<?= $fCd10 ?>" size="10" class="textbox" /></td>
						  <td width="50">10s</td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="S10" value="<?= $fS10 ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>12cd</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Cd12" value="<?= $fCd12 ?>" size="10" class="textbox" /></td>
						  <td>20s</td>
						  <td align="center">:</td>
						  <td><input type="text" name="S20" value="<?= $fS20 ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>14cd</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Cd14" value="<?= $fCd14 ?>" size="10" class="textbox" /></td>
						  <td>30s</td>
						  <td align="center">:</td>
						  <td><input type="text" name="S30" value="<?= $fS30 ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>16cd</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Cd16" value="<?= $fCd16 ?>" size="10" class="textbox" /></td>
						  <td>30cm cpt</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Cm30Cpt" value="<?= $fCm30Cpt ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>20cd</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Cd20" value="<?= $fCd20 ?>" size="10" class="textbox" /></td>
						  <td>40cm</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Cm40" value="<?= $fCm40 ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>21cd</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Cd21" value="<?= $fCd21 ?>" size="10" class="textbox" /></td>
						  <td>12/1 CD +70 DN Spndx</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Cd12Spndx" value="<?= $fCd12Spndx ?>" size="10" class="textbox" /></td>
					    </tr>

					    <tr>
						  <td>30cd</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Cd30" value="<?= $fCd30 ?>" size="10" class="textbox" /></td>
						  <td>16+70Dsp</td>
						  <td align="center">:</td>
						  <td><input type="text" name="Dsp1670" value="<?= $fDsp1670 ?>" size="10" class="textbox" /></td>
					    </tr>

						<tr>
						  <td></td>
						  <td></td>

						  <td colspan="4">
						    <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditForm(<?= $i ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $i ?>');" />
						  </td>
					    </tr>
					  </table>
					  </form>

				    </div>
				  </div>

				  <div id="Msg<?= $i ?>" class="msgOk" style="display:none;"></div>

<?
	}

	if ($iCount == 0)
	{
?>
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr>
				      <td class="noRecord">No Yarn Rates Record Found!</td>
				    </tr>
			      </table>
<?
	}
?>

			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&FromDate={$FromDate}&ToDate={$ToDate}");
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