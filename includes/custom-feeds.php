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

	if ($_SESSION['UserId'] == "")
	{
?>
			    <h1 class="gray">The New Face of Sourcing!</h1>

			    <div class="block">
			      <div class="blockBottom">
			        <div class="blockTop" style="padding:0px;">
			          <div style="padding:2px 2px 5px 2px;">
                                      <video width="320" height="535" autoplay loop>
                                        <source src="images/video.mp4" type="video/mp4">
                                            Your browser does not support the video tag.
                                      </video>
			          </div>
			        </div>
			      </div>
			    </div>
<?
	}

	else
	{
?>

			    <h1 class="gray">Custom Feeds</h1>

			    <div class="block">
			      <div class="blockBottom">
			        <div class="blockTop">
			          <div class="flexcroll feeds">
			            <h4 style="margin:2px 0px 10px 0px;">PO/Style Status</h4>
			            Enter the Order No or Style No to check its status in the Portal.<br /><br />

                                            <form name="frmSearch" id="frmSearch" method="get" action="<?=($_SESSION["UserType"] == "HOHENSTEIN"?'hoh-order-status.php':'po-status.php')?>" onsubmit="$('BtnSubmit').disable( );">
					      <table border="0" cellpadding="10" cellspacing="0" width="100%">
					        <tr>
					          <td width="80" align="right"><?=($_SESSION["UserType"] == "HOHENSTEIN"?"Hoh Internal":"")?> Order No :</td>
					          <td><div class="textboxBg"><input type="text" name="Po" value="" maxlength="50" /></div></td>
					        </tr>

					        <tr>
					          <td align="center" style="padding:0px;">OR</td>
					          <td style="padding:0px;"></td>
					        </tr>

					        <tr>
					          <td align="right">Style No :</td>
					          <td><div class="textboxBg"><input type="text" name="Style" value="" maxlength="50" /></div></td>
					        </tr>
					      </table>

						  <table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr class="grayBar">
							  <td width="100%" align="right"><input type="submit" id="BtnSubmit" value="" class="btnSearch" title="Search" onclick="return validatePoSearchForm( );" /></td>
							</tr>
						  </table>
						</form>

<?
		$sSQL = "SELECT id, title, date_time FROM tbl_user_searches WHERE user_id='{$_SESSION['UserId']}' ORDER BY title DESC";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		if ($iCount > 0)
		{
?>
			            <h4 style="margin:25px 0px 10px 0px;">Saved Searches</h4>
<?
			for ($i = 0; $i < $iCount; $i ++)
			{
				$iId       = $objDb->getField($i, 'id');
				$sTitle    = $objDb->getField($i, 'title');
				$sDateTime = $objDb->getField($i, 'date_time');
?>
			            <div id="Search<?= $iId ?>">
			              <b><a href="vsr/vsr-details.php?SearchId=<?= $iId ?>"><?= $sTitle ?></a></b><br />

			              <table border="0" cellpadding="0" cellspacing="0" width="100%">
			                <tr>
			                  <td width="60%"><small><i class="dateTime"><?= formatDate($sDateTime, "F j, Y H:i A") ?></i></small></td>
			                  <td width="40%" align="right"><small>[ <a href="vsr/vsr-details.php?SearchId=<?= $iId ?>">Search</a> | <a href="#" onclick="deleteSearch(<?= $iId ?>); return false;">Delete</a> ]</small></td>
			                </tr>
			              </table>

			              <br />
			            </div>
<?
			}
		}
?>
			          </div>
			        </div>
			      </div>
			    </div>
<?
	}
?>
