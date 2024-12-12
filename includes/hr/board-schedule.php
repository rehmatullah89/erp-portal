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

	$sLocationsList = getList("tbl_visit_locations", "id", "location");
?>
			    <div class="tblSheet">
		          <h1 class="darkGray small" style="margin:0px 1px 1px 0px;"><img src="images/h1/hr/user-schedule.jpg" width="155" height="15" vspace="7" alt="" title="" /></h1>

<?
	$PageId      = ((IO::intValue("PageId") == 0) ? 1 : IO::intValue("PageId"));
	$iPageSize   = PAGING_SIZE;
	$iPageCount  = 0;
	$sConditions = "WHERE user_id='{$_SESSION['UserId']}'";

	@list($iTotalRecords, $iPageCount, $iStart) = getPagingInfo("tbl_user_schedule", $sConditions, $iPageSize, $PageId);


	$sSQL = "SELECT * FROM tbl_user_schedule $sConditions ORDER BY id DESC LIMIT $iStart, $iPageSize";
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
				      <td width="20%">Location</td>
				      <td width="48">Task Details</td>
				      <td width="10%" class="center">From/To<br />Date</td>
				      <td width="8%" class="center">Start/End<br />Time</td>
				      <td width="8%" class="center">Options</td>
				    </tr>
				  </table>
<?
		}

		$iId        = $objDb->getField($i, 'id');
		$iLocation  = $objDb->getField($i, 'location_id');
		$sFromDate  = $objDb->getField($i, 'from_date');
		$sToDate    = $objDb->getField($i, 'to_date');
		$sDetails   = $objDb->getField($i, 'details');
		$sStartTime = $objDb->getField($i, 'start_time');
		$sEndTime   = $objDb->getField($i, 'end_time');

		@list($iStartHour, $iStartMinutes) = @explode(":", $sStartTime);
		@list($iEndHour, $iEndMinutes)     = @explode(":", $sEndTime);
?>

				  <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
				    <tr class="<?= $sClass[($i % 2)] ?>" valign="top">
				      <td width="6%"><?= ($iStart + $i + 1) ?></td>
				      <td width="20%"><span id="Location<?= $iId ?>"><?= $sLocationsList[$iLocation] ?></span></td>
				      <td width="48%"><span id="Details<?= $iId ?>"><?= nl2br($sDetails) ?></span></td>
				      <td width="10%" class="center"><span id="Date<?= $iId ?>"><?= formatDate($sFromDate) ?><br />-<br /><?= formatDate($sToDate) ?></span></td>
				      <td width="8%" class="center"><span id="Time<?= $iId ?>"><?= formatTime($sStartTime) ?><br />-<br /><?= formatTime($sEndTime) ?></span></td>

				      <td width="8%" class="center">
<?
		if ($sUserRights['Edit'] == "Y")
		{
?>
				        <a href="./" onclick="Effect.SlideDown('ScheduleEdit<?= $iId ?>'); return false;"><img src="images/icons/edit.gif" width="16" height="16" alt="Edit" title="Edit" /></a>
<?
		}

		if ($sUserRights['Delete'] == "Y")
		{
?>
				        &nbsp;
				        <a href="hr/delete-schedule.php?Id=<?= $iId ?>" onclick="return confirm('Are you SURE, You want to Delete this Schedule?');"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" /></a>
<?
		}
?>
				      </td>
				    </tr>
				  </table>


				  <div id="ScheduleEdit<?= $iId ?>" style="display:none;">
				    <div style="padding:1px;">

					  <form name="frmSchedule<?= $iId ?>" id="frmSchedule<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">
					  <input type="hidden" name="Id" value="<?= $iId ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
						  <td width="70">Location<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>

						  <td>
							<select name="Location">
							  <option value=""></option>
<?
		foreach ($sLocationsList as $sKey => $sValue)
		{
?>
			              	  <option value="<?= $sKey ?>"<?= (($sKey == $iLocation) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
							</select>
						  </td>
						</tr>

					    <tr>
						  <td>From Date<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>

						    <table border="0" cellpadding="0" cellspacing="0" width="116">
							  <tr>
							    <td width="82"><input type="text" name="FromDate" id="FromDate<?= $iId ?>" value="<?= $sFromDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							  </tr>
						    </table>

						  </td>
					    </tr>

					    <tr>
						  <td>To Date<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>

						    <table border="0" cellpadding="0" cellspacing="0" width="116">
							  <tr>
							    <td width="82"><input type="text" name="ToDate" id="ToDate<?= $iId ?>" value="<?= $sToDate ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
							    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate<?= $iId ?>'), 'yyyy-mm-dd', this);" /></td>
						  	  </tr>
						    </table>

						  </td>
					    </tr>

					    <tr>
						  <td>Start Time<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="StartHour">
<?
		for ($j = 0; $j <= 23; $j ++)
		{
?>
	  	        			  <option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iStartHour == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
						    </select>

						    <select name="StartMinutes">
							  <option value="00">00</option>
<?
		for ($j = 5; $j <= 59; $j += 5)
		{
?>
	  	        			  <option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iStartMinutes == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr>
						  <td>End Time<span class="mandatory">*</span></td>
						  <td align="center">:</td>

						  <td>
						    <select name="EndHour">
							  <option value="00">00</option>
<?
		for ($j = 0; $j <= 23; $j ++)
		{
?>
	  	        			  <option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iEndHour == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
						    </select>

						    <select name="EndMinutes">
							  <option value="00">00</option>
<?
		for ($j = 5; $j <= 59; $j += 5)
		{
?>
	  	        			  <option value="<?= str_pad($j, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$iEndMinutes == $j) ? " selected" : "") ?>><?= str_pad($j, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
						    </select>
						  </td>
					    </tr>

					    <tr valign="top">
						  <td>Details</td>
						  <td align="center">:</td>
						  <td><textarea name="Details" rows="5" cols="50"><?= $sDetails ?></textarea></td>
					    </tr>

						<tr>
						  <td></td>
						  <td></td>

						  <td>
						    <input type="submit" value="SAVE" class="btnSmall" onclick="validateEditScheduleForm(<?= $iId ?>);" />
						    <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('ScheduleEdit<?= $iId ?>');" />
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
				      <td class="noRecord">No Schedule Task Found!</td>
				    </tr>
			      </table>
<?
	}
?>
		        </div>

<?
	showPaging($PageId, $iPageCount, $iCount, $iStart, $iTotalRecords, "&Tab={$Tab}");


	if ($sUserRights['Add'] == "Y")
	{
		$PostId = IO::strValue("PostId");

		if ($PostId != "")
		{
			$_REQUEST = @unserialize($_SESSION[$PostId]);

			$Location     = IO::intValue("Location");
			$FromDate     = IO::strValue("FromDate");
			$ToDate       = IO::strValue("ToDate");
			$FromTime     = IO::strValue("FromTime");
			$ToTime       = IO::strValue("ToTime");
			$Details      = IO::strValue("Details");
			$StartHour    = IO::strValue("StartHour");
			$StartMinutes = IO::strValue("StartMinutes");
			$EndHour      = IO::strValue("EndHour");
			$EndMinutes   = IO::strValue("EndMinutes");
		}

		else
		{
			$StartHour    = date("H");
			$StartMinutes = date("i");
			$EndHour      = date("H");
			$EndMinutes   = date("i");

			while (($StartMinutes % 5) > 0)
			{
				$StartMinutes --;
				$EndMinutes --;
			}
		}
?>
			    <br style="line-height:4px;" />

			    <div class="tblSheet">
		          <h1 class="darkGray small" style="margin:0px 1px 5px 0px;"><img src="images/h1/hr/add-user-task.jpg" width="149" height="15" vspace="7" alt="" title="" /></h1>

			      <form name="frmSchedule" id="frmSchedule" method="post" action="hr/save-schedule.php" onsubmit="$('BtnSubmit').disable( );">
			      <table width="99%" cellspacing="0" cellpadding="4" border="0" align="center">
				    <tr>
					  <td width="70">Location<span class="mandatory">*</span></td>
					  <td width="20" align="center">:</td>

					  <td>
			            <select name="Location">
			              <option value=""></option>
<?
		foreach ($sLocationsList as $sKey => $sValue)
		{
?>
			              <option value="<?= $sKey ?>"<?= (($sKey == $Location) ? " selected" : "") ?>><?= $sValue ?></option>
<?
		}
?>
		                </select>
					  </td>
				    </tr>

				    <tr>
					  <td>From Date<span class="mandatory">*</span></td>
					  <td align="center">:</td>

					  <td>

					    <table border="0" cellpadding="0" cellspacing="0" width="116">
						  <tr>
						    <td width="82"><input type="text" name="FromDate" id="FromDate" value="<?= (($FromDate == "") ? date("Y-m-d") : $FromDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
						    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FromDate'), 'yyyy-mm-dd', this);" /></td>
						  </tr>
 					    </table>

					  </td>
			        </tr>

				    <tr>
					  <td>To Date<span class="mandatory">*</span></td>
					  <td align="center">:</td>

					  <td>

					    <table border="0" cellpadding="0" cellspacing="0" width="116">
						  <tr>
						    <td width="82"><input type="text" name="ToDate" id="ToDate" value="<?= (($ToDate == "") ? date("Y-m-d") : $ToDate) ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
 						    <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('ToDate'), 'yyyy-mm-dd', this);" /></td>
						  </tr>
					    </table>

					  </td>
			        </tr>

				    <tr>
					  <td>Start Time<span class="mandatory">*</span></td>
					  <td align="center">:</td>

					  <td>
					    <select name="StartHour">
						  <option value=""></option>
<?
		for ($i = 0; $i <= 23; $i ++)
		{
?>
	  	        		  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$StartHour == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
					    </select>

					    <select name="StartMinutes">
						  <option value="00">00</option>
<?
		for ($i = 5; $i <= 59; $i += 5)
		{
?>
	  	        		  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$StartMinutes == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
					    </select>
					  </td>
				    </tr>

				    <tr>
					  <td>End Time<span class="mandatory">*</span></td>
					  <td align="center">:</td>

					  <td>
					    <select name="EndHour">
						  <option value=""></option>
						  <option value="00">00</option>
<?
		for ($i = 0; $i <= 23; $i ++)
		{
?>
	  	        		  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$EndHour == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
					    </select>

					    <select name="EndMinutes">
						  <option value="00">00</option>
<?
		for ($i = 5; $i <= 59; $i += 5)
		{
?>
	  	        		  <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"<?= (((int)$EndMinutes == $i) ? " selected" : "") ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
<?
		}
?>
					    </select>
					  </td>
				    </tr>

				    <tr valign="top">
					  <td>Details<span class="mandatory">*</span></td>
					  <td align="center">:</td>
					  <td><textarea name="Details" rows="5" cols="50"><?= $Details ?></textarea></td>
				    </tr>
			      </table>

			      <br />

			      <div class="buttonsBar">
			        <input type="submit" id="BtnSubmit" value="" class="btnSubmit" onclick="return validateScheduleForm( );" />
			      </div>
			      </form>
		        </div>
<?
	}
?>