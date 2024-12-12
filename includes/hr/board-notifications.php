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

	$sVendorsList     = getList("tbl_vendors", "id", "vendor", "id IN ({$_SESSION['Vendors']}) AND parent_id='0' AND sourcing='Y'");
	$sBrandsList      = getList("tbl_brands", "id", "brand", "parent_id!='0' AND id IN ({$_SESSION['Brands']})");
	$sDepartmentsList = getList("tbl_departments", "id", "department", "notifications='Y'");
	$sAlertTypesList  = getList("tbl_notification_types", "id", "`type`");
?>
			    <div class="tblSheet">
		          <h1 class="darkGray small" style="margin:0px 1px 1px 0px;"><img src="images/h1/hr/notifications.jpg" width="141" height="15" vspace="7" alt="" title="" /></h1>
<?
	$sSQL = "SELECT *,
	                (SELECT `trigger` FROM tbl_notification_triggers WHERE id=tbl_notifications.trigger_id) AS _Trigger
	         FROM tbl_notifications
	         WHERE user_id='{$_SESSION['UserId']}'
	         ORDER BY date_time";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		if (($i % 10) == 0)
		{
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="headerRow">
				      <td width="5%">#</td>
				      <td width="15%">Department</td>
				      <td width="22%">Trigger</td>
				      <td width="16%">Vendor</td>
				      <td width="16%">Brand</td>
				      <td width="14%">Alert Types</td>
				      <td width="12%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId         = $objDb->getField($i, 'id');
		$iDepartment = $objDb->getField($i, 'department_id');
		$iTrigger    = $objDb->getField($i, 'trigger_id');
		$sTrigger    = $objDb->getField($i, '_Trigger');
		$iVendor     = $objDb->getField($i, 'vendor_id');
		$iBrand      = $objDb->getField($i, 'brand_id');
		$sAlertTypes = $objDb->getField($i, 'alert_types');
		$sStatus     = $objDb->getField($i, 'status');

		$iAlertTypes = @explode(",", $sAlertTypes);
		$sAlertTypes = "";

		for ($j = 0; $j < count($iAlertTypes); $j ++)
			$sAlertTypes .= (", ".$sAlertTypesList[$iAlertTypes[$j]]);

		$sAlertTypes = @substr($sAlertTypes, 2);
?>

			      <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>">
				      <td width="5%"><?= ($i + 1) ?></td>
				      <td width="15%"><span id="Department_<?= $iId ?>"><?= $sDepartmentsList[$iDepartment] ?></span></td>
				      <td width="22%"><span id="Trigger_<?= $iId ?>"><?= $sTrigger ?></span></td>
				      <td width="16%"><span id="Vendor<?= $iId ?>"><?= $sVendorsList[$iVendor] ?></span></td>
				      <td width="16%"><span id="Brand<?= $iId ?>"><?= $sBrandsList[$iBrand] ?></span></td>
				      <td width="14%"><span id="AlertTypes<?= $iId ?>"><?= $sAlertTypes ?></span></td>

				      <td width="12%" class="center">
				        <a href="hr/toggle-notification-status.php?Id=<?= $iId ?>&Status=<?= (($sStatus == 'A') ? 'I' : 'A') ?>"><img src="images/icons/<?= (($sStatus == 'A') ? 'yes' : 'no') ?>.png" width="16" height="16" alt="Toggle Status" title="Toggle Status" /></a>
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        &nbsp;
				        <a href="./" onclick="Effect.SlideDown('NotificationEdit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        &nbsp;
				        <a href="hr/delete-notification.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Notification?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
				  </table>

				  <div id="NotificationEdit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmNotification<?= $iId ?>" id="frmNotification<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
						  <td width="80">Department<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
							<select name="Department" id="Department<?= $iId ?>" onchange="getTriggers('<?= $iId ?>');">
							  <option value=""></option>
<?
		foreach ($sDepartmentsList as $sKey => $sValue)
		{
?>
			              	  <option value="<?= $sKey ?>"<?= (($sKey == $iDepartment) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
							</select>
						  </td>
						</tr>

						<tr>
						  <td>Trigger<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
							<select name="Trigger" id="Trigger<?= $iId ?>">
							  <option value=""></option>
<?
		$sSQL = "SELECT id, `trigger` FROM tbl_notification_triggers WHERE department_id='$iDepartment' ORDER BY `trigger`";
		$objDb2->query($sSQL);

		$iCount2 = $objDb2->getCount( );

		for ($j = 0; $j < $iCount2; $j ++)
		{
			$sKey   = $objDb2->getField($j, 0);
			$sValue = $objDb2->getField($j, 1);
?>
	  	        			  <option value="<?= $sKey ?>"<?= (($sKey == $iTrigger) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
							</select>
						  </td>
						</tr>

						<tr>
						  <td>Vendor*</td>
						  <td align="center">:</td>

						  <td>
							<select name="Vendor">
							  <option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
	  	        		  	  <option value="<?= $sKey ?>"<?= (($sKey == $iVendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
							</select>
						  </td>
						</tr>

						<tr>
						  <td>Brand*</td>
						  <td align="center">:</td>

						  <td>
							<select name="Brand">
							  <option value=""></option>
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
	  	        		  	  <option value="<?= $sKey ?>"<?= (($sKey == $iBrand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
							</select>
						  </td>
						</tr>

						<tr>
						  <td>Alert Type<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>

							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							  <tr>
<?
		foreach ($sAlertTypesList as $sKey => $sValue)
		{
?>
								<td width="25"><input type="checkbox" class="alerts<?= $iId ?>" name="AlertTypes[]" value="<?= $sKey ?>"<?= ((@in_array($sKey, $iAlertTypes)) ? " checked" : "") ?> /></td>
								<td width="60"><?= $sValue ?></td>
<?
		}
?>
								<td></td>
							  </tr>
							</table>

						  </td>
						</tr>

						<tr>
						  <td></td>
						  <td></td>

						  <td>
						    <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditNotificationForm(<?= $iId ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('NotificationEdit<?= $iId ?>');" />
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
				  <div class="noRecord">No Notification Setup Yet!</div>
<?
	}


	if ($sUserRights['Add'] == "Y")
	{
		$PostId   = IO::strValue("PostId");

		if ($PostId != "")
		{
			$_REQUEST = @unserialize($_SESSION[$PostId]);

			$Department = IO::intValue("Department");
			$Trigger    = IO::intValue("Trigger");
			$Vendor     = IO::intValue("Vendor");
			$Brand      = IO::intValue("Brand");
			$AlertTypes = IO::getArray("AlertTypes");
		}
?>
		          <h2>Setup New Notification</h2>

			      <form name="frmNotification" id="frmNotification" method="post" action="hr/save-notification.php" onsubmit="$('BtnSaveNotification').disable( );">
			      <table width="95%" cellspacing="0" cellpadding="4" border="0" align="center">
				    <tr>
				      <td width="80">Department<span class="mandatory">*</span></td>
				      <td width="20" align="center">:</td>

				      <td>
					    <select name="Department" id="Department0" onchange="getTriggers('0');">
					      <option value=""></option>
<?
		foreach ($sDepartmentsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Department) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						</select>
				      </td>
				    </tr>

				    <tr>
				      <td>Trigger<span class="mandatory">*</span></td>
				      <td align="center">:</td>

				      <td>
					    <select name="Trigger" id="Trigger0">
					      <option value=""></option>
<?
		$sSQL = "SELECT id, `trigger` FROM tbl_notification_triggers WHERE department_id='$Department' ORDER BY `trigger`";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			$sKey   = $objDb->getField($i, 0);
			$sValue = $objDb->getField($i, 1);
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Trigger) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
						</select>
				      </td>
				    </tr>

				    <tr>
					  <td>Vendor*</td>
					  <td align="center">:</td>

					  <td>
					    <select name="Vendor">
						  <option value=""></option>
<?
		foreach ($sVendorsList as $sKey => $sValue)
		{
?>
	  	        		  <option value="<?= $sKey ?>"<?= (($sKey == $Vendor) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					    </select>
					  </td>
				    </tr>

				    <tr>
					  <td>Brand*</td>
					  <td align="center">:</td>

					  <td>
					    <select name="Brand">
						  <option value=""></option>
<?
		foreach ($sBrandsList as $sKey => $sValue)
		{
?>
	  	        		  <option value="<?= $sKey ?>"<?= (($sKey == $Brand) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
					    </select>
					  </td>
				    </tr>

				    <tr>
				      <td>Alert Types<span class="mandatory">*</span></td>
				      <td align="center">:</td>

				      <td>

				        <table border="0" cellpadding="0" cellspacing="0" width="100%">
				          <tr>
<?
		foreach ($sAlertTypesList as $sKey => $sValue)
		{
?>
				            <td width="25"><input type="checkbox" class="alerts" name="AlertTypes[]" value="<?= $sKey ?>"<?= ((@in_array($sKey, $AlertTypes)) ? " checked" : "") ?> /></td>
				            <td width="60"><?= $sValue ?></td>
<?
		}
?>
				            <td></td>
				          </tr>
				        </table>

				      </td>
				    </tr>
			      </table>

			      <div class="buttonsBar" style="margin-top:5px;">
			        <input type="submit" id="BtnSaveNotification" value="" class="btnSave" onclick="return validateNotificationForm( );" />
			      </div>
			      </form>
<?
	}
?>
		        </div>
