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

	$iPageCount  = 0;
	$PageId      = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$sConditions = "WHERE (parent_id='0' AND sender_id='{$_SESSION['UserId']}')
	                   OR (parent_id='0' AND FIND_IN_SET('$Id', recipients))
	                   OR (parent_id='0' AND FIND_IN_SET('{$_SESSION['UserId']}', recipients))";

	//$iHrId       = HR_MANAGER;
	$iHrId = getDbValue("id", "tbl_users", "designation_id='126'");

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_hr_messages", $sConditions, $iPageSize, $PageId);
?>
			    <div class="tblSheet">
		          <h1 class="darkGray small" style="margin:0px 1px 1px 0px;"><img src="images/h1/hr/my-messages.jpg" width="136" height="15" vspace="7" alt="" title="" /></h1>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
<?
	$sSQL = "SELECT *, (SELECT name FROM tbl_users WHERE id=tbl_hr_messages.sender_id) AS _Name
	         FROM tbl_hr_messages
	         $sConditions
	         ORDER BY id DESC
	         LIMIT $iStart, $iPageSize";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>
				    <tr class="headerRow" valign="top">
				      <td width="6%">#</td>
				      <td width="10%">Manager</td>
<?
			if ($iHrId == $_SESSION['UserId'])
			{
?>
				      <td width="20%">Employee</td>
<?
			}
?>
				      <td width="<?= (($iHrId == $_SESSION['UserId']) ? 34 : 54) ?>%">Subject</td>
				      <td width="16%" class="center">Date / Time</td>
				      <td width="8%" class="center">Status</td>
				      <td width="6%" class="center">View</td>
				    </tr>
<?
		}

		$iId       = $objDb->getField($i, 'id');
		$iParentId = $objDb->getField($i, 'parent_id');
		$sManager  = $objDb->getField($i, 'manager');
		$iStatus   = $objDb->getField($i, 'status');

		if ($iHrId == $_SESSION['UserId'])
		{
			switch ($iStatus)
			{
				case 0 : $sStatus = "New"; break;
				case 1 : $sStatus = "Read"; break;
				case 2 : $sStatus = "Replied"; break;
				case 3 : $sStatus = "Replied"; break;
			}
		}

		else
		{
			switch ($iStatus)
			{
				case 0 : $sStatus = "Sent"; break;
				case 1 : $sStatus = "Sent"; break;
				case 2 : $sStatus = "New"; break;
				case 3 : $sStatus = "Read"; break;
			}
		}
?>

				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td><?= ($iStart + $i + 1) ?></td>
				      <td><?= str_replace(" Manager", "", $sManager) ?></td>
<?
		if ($iHrId == $_SESSION['UserId'])
		{
?>
				      <td><?= $objDb->getField($i, '_Name') ?></td>
<?
		}
?>
				      <td><?= $objDb->getField($i, 'subject') ?></td>
				      <td class="center"><?= formatDate($objDb->getField($i, 'date_time'), "d-M-Y H:i A") ?></td>
				      <td class="center"<?= (($sStatus == "New") ? ' style="color:#ff0000;"' : '') ?>><?= $sStatus ?></td>
				      <td class="center"><a href="hr/messages.php?Id=<?= (($iParentId == 0) ? $iId : $iParentId) ?>"><img src="images/icons/view.gif" width="16" height="16" alt="View Details" title="View Details" /></a></td>
				    </tr>
<?
	}

	if ($iCount == 0)
	{
?>
				    <tr>
				      <td class="noRecord">No Mesage to HR Manager Sent!</td>
				    </tr>
<?
	}
?>
			      </table>
		        </div>
<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Tab={$Tab}");


	if ($iHrId != $_SESSION['UserId'])
	{
?>

			    <br style="line-height:4px;" />

			    <div class="tblSheet">
		          <h1 class="darkGray small" style="margin:0px 1px 5px 0px;"><img src="images/h1/hr/post-a-message.jpg" width="160" height="15" vspace="7" alt="" title="" /></h1>

			      <form name="frmContact" id="frmContact" method="post" action="hr/send-mail.php" onsubmit="$('BtnSubmit').disable( );">
			      <div style="padding:10px 10px 15px 10px;">
			        If you have any issue/grievance, you can conatct the HR/Grievance Manager. Please provide the required information below to send your message to the respective Manager.<br />
			      </div>

<?
		if ($_POST["Error"] != "")
		{
?>
				  <div class="error" style="padding-left:10px;">
				    <b>Please provide the valid values of following fields:</b><br />
				    <br style="line-height:5px;" />
				    <?= $_POST["Error"] ?><br />
				  </div>

				  <br />
<?
		}
?>

			      <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				    <tr>
				      <td width="60">To<span class="mandatory">*</span></td>
				      <td width="20" align="center">:</td>

				      <td>
					    <select name="To">
						  <option value="HR Manager"<?= ((IO::strValue("To") == "HR Manager") ? " selected" : "") ?>>HR Manager</option>
						  <option value="Grievances Manager"<?= ((IO::strValue("To") == "Grievances Manager") ? " selected" : "") ?>>Grievances Manager</option>
					    </select>
				      </td>
				    </tr>

				    <tr>
				      <td width="60">Subject<span class="mandatory">*</span></td>
				      <td width="20" align="center">:</td>
				      <td><input type="text" name="Subject" value="<?= IO::strValue('Subject') ?>" maxlength="255" class="textbox" style="width:99%;" /></td>
				    </tr>

				    <tr valign="top">
				      <td>Message<span class="mandatory">*</span></td>
				      <td align="center">:</td>
				      <td><textarea name="Message" style="width:99%; height:180px;"><?= IO::getFormValue('Message') ?></textarea></td>
				    </tr>
			      </table>

			      <div style="padding:10px;"><b>Note:</b> Fields marked with an asterisk (*) are required.</div>

			      <div class="buttonsBar">
			        <input type="submit" id="BtnSubmit" value="" class="btnSubmit" onclick="return validateContactForm( );" />
			      </div>
			      </form>
		        </div>
<?
	}
?>