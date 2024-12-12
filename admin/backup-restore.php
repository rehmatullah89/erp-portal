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

	$PageId = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<?
	@include($sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/admin/backup-restore.js"></script>
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
			    <h1>Backup / Restore</h1>

			    <div id="SearchBar">
			      <form name="frmSearch" id="frmSearch" method="get" action="admin/backup-database.php" onsubmit="$('BtnBackup').disabled=true;">
			      <table border="0" cellpadding="0" cellspacing="0" width="100%">
			        <tr>
			          <td></td>
			          <td width="10" bgcolor="#494949"></td>
			          <td width="103" align="right"><input type="submit" id="BtnBackup" value="" class="btnBackup" title="Backup" onclick="showProcessing( );" /></td>
			        </tr>
			      </table>
			      </form>
			    </div>

			    <div class="tblSheet">
			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sClass     = array("evenRow", "oddRow");
	$iPageSize  = PAGING_SIZE;
	$iPageCount = 0;

    $sAllFiles     = @glob($sBaseDir.DB_BACKUP_PATH."*.*");
	$iTotalRecords = count($sAllFiles);

	if ($iTotalRecords > 0)
	{
		$iPageCount = @floor($iTotalRecords / $iPageSize);

		if (($iTotalRecords % $iPageSize) > 0)
			$iPageCount += 1;
	}

	$iStart = (($PageId * $iPageSize) - $iPageSize);

	$sFiles = @array_slice($sAllFiles, $iStart, $iPageSize);
	$iCount = count($sFiles);

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow">
				      <td width="5%">#</td>
				      <td width="40%">File Name</td>
				      <td width="20%">File Size</td>
				      <td width="20%">Date / Time</td>
				      <td width="15%" class="center">Options</td>
				    </tr>
<?
		}

		$sFileName = $sFiles[$i];
		$fFileSize = @number_format((@filesize($sFiles[$i])/1024), 2, '.', ',');
		$sDateTime = date("Y-m-d h:i A", @filemtime($sFileName));
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= @basename($sFileName) ?></td>
				      <td><?= $fFileSize ?> KB</td>
				      <td><?= $sDateTime ?></td>

				      <td class="center">
				        <a href="admin/download-database.php?File=<?= @basename($sFileName) ?>"><img src="images/icons/download.gif" width="16" height="16" border="0" alt="Download" title="Download" /></a>
				        &nbsp;
				        <a href="admin/restore-database.php?File=<?= @basename($sFileName) ?>" onclick="return restoreDatabase( );"><img src="images/icons/restore.gif" width="16" height="16" alt="Restore" title="Restore" /></a>
				        &nbsp;
				        <a href="admin/delete-database.php?File=<?= @basename($sFileName) ?>"  onclick="return confirm('Are you SURE you want to DELETE this Backup File?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
				      </td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Database Backup Found!</td>
				    </tr>
<?
	}
?>
			      </table>
			    </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords);
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