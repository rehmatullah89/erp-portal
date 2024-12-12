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

	// check user session
	function checkLogin($bFlag = true)
	{
		// Logging user login time
		if ($_SESSION['UserId'] != "")
		{
			if (!$objDbGlobal)
				$objDbGlobal = new Database( );

			$sSQL = "SELECT name, status, admin, brands, vendors FROM tbl_users WHERE id='{$_SESSION['UserId']}'";

			if ($objDbGlobal->query($sSQL) == true)
			{
				if ($objDbGlobal->getCount( ) == 1)
				{
					if ($objDbGlobal->getField(0, "status") == "A")
					{
						$_SESSION['Name']    = $objDbGlobal->getField(0, "name");
						$_SESSION['Admin']   = $objDbGlobal->getField(0, "admin");
						$_SESSION['Vendors'] = $objDbGlobal->getField(0, "vendors");
						$_SESSION['Brands']  = $objDbGlobal->getField(0, "brands");
					}

					else if ($objDbGlobal->getField(0, "status") == "P")
					{
						$_SESSION = array( );
						@session_destroy( );

						redirect(SITE_URL, "ACCOUNT_NOT_ACTIVE");
					}

					else if ($objDbGlobal->getField(0, "status") == "D")
					{
						$_SESSION = array( );
						@session_destroy( );

						redirect(SITE_URL, "ACCOUNT_DISABLED");
					}
				}

				else
					redirect(SITE_URL, "INVALID_LOGIN");
			}

			else
				redirect(SITE_URL, "DB_ERROR");
		}

		if ($bFlag == true)
		{
			if ($_SESSION['UserId'] == "")
			{
				$_SESSION['Referer'] = ($_SERVER['PHP_SELF'].(($_SERVER['QUERY_STRING'] != "") ? "?" : "").$_SERVER['QUERY_STRING']);

				redirect(SITE_URL, "LOGIN");
			}
		}

		else if ($bFlag == false)
		{
			if ($_SESSION['UserId'] != "")
				redirect(SITE_URL, "ALREADY_LOGGED_IN");
		}
	}


	// maintain user session log
	function logSession( )
	{
		// Logging user login time
		if ($_SESSION['UserId'] != "")
		{
			if (!$objDbGlobal)
				$objDbGlobal = new Database( );


			if ($_SESSION['StatsId'] == "")
			{
				$iId = getNextId("tbl_user_stats");

				$sSQL = "INSERT INTO tbl_user_stats (id, user_id, ip_address, user_agent, login_date_time, logout_date_time, status) VALUES ('$iId', '{$_SESSION['UserId']}', '{$_SERVER['REMOTE_ADDR']}', '{$_SERVER['HTTP_USER_AGENT']}', NOW( ), NOW( ), '1')";

				if ($objDbGlobal->execute($sSQL) == true)
					$_SESSION['StatsId'] = $iId;
			}

			else
			{
				$sSQL = "SELECT DATE_FORMAT(login_date_time, '%Y-%m-%d') AS _LoginDate, DATE_FORMAT(logout_date_time, '%Y-%m-%d') AS _LogoutDate FROM tbl_user_stats WHERE id='{$_SESSION['StatsId']}' AND user_id='{$_SESSION['UserId']}'";
				$objDbGlobal->query($sSQL);

				$sLoginDate  = $objDbGlobal->getField(0, '_LoginDate');
				$sLogoutDate = $objDbGlobal->getField(0, '_LogoutDate');

				if ($sLoginDate != $sLogoutDate)
				{
					$iId = getNextId("tbl_user_stats");

					$sSQL = "INSERT INTO tbl_user_stats (id, user_id, ip_address, user_agent, login_date_time, logout_date_time, status) VALUES ('$iId', '{$_SESSION['UserId']}', '{$_SERVER['REMOTE_ADDR']}', '{$_SERVER['HTTP_USER_AGENT']}', NOW( ), NOW( ), '1')";

					if ($objDbGlobal->execute($sSQL) == true)
						$_SESSION['StatsId'] = $iId;
				}

				else
				{
					$sSQL = "UPDATE tbl_user_stats SET status='1', logout_date_time=NOW( ) WHERE id='{$_SESSION['StatsId']}' AND user_id='{$_SESSION['UserId']}'";
					$objDbGlobal->execute($sSQL, false);
				}
			}
		}
	}


	// checking user rights for current page
	function getUserRights( )
	{
		global $objDbGlobal;
		global $sPage;
		global $sModule;

		if (!$objDbGlobal)
			$objDbGlobal = new Database( );

		$sSQL = "SELECT id FROM tbl_pages WHERE scripts LIKE '%\'$sPage\'%' AND module='$sModule'";
		$objDbGlobal->query($sSQL);

		$iPageId = $objDbGlobal->getField(0, 0);


		$sSQL = "SELECT `view`, `add`, `edit`, `delete` FROM tbl_user_rights WHERE user_id='{$_SESSION['UserId']}' AND page_id='$iPageId'";
		$objDbGlobal->query($sSQL);
		

		$sRights = array( );

		$sRights['Add']    = (($objDbGlobal->getField(0, 'add') != "Y") ? "N" : "Y");
		$sRights['Edit']   = (($objDbGlobal->getField(0, 'edit') != "Y") ? "N" : "Y");
		$sRights['Delete'] = (($objDbGlobal->getField(0, 'delete') != "Y") ? "N" : "Y");
		$sRights['View']   = (($objDbGlobal->getField(0, 'view') != "Y") ? "N" : "Y");

		if (@strpos($sPage, "get-") !== FALSE || @strpos($sPage, "export-") !== FALSE)
			$sRights['View'] = "Y";

		return $sRights;
	}


	// checking specified user right for provided page
	function checkUserRights($sPage, $sModule, $sAction)
	{
		global $objDbGlobal;

		if (!$objDbGlobal)
			$objDbGlobal = new Database( );

		$sSQL = "SELECT id FROM tbl_pages WHERE scripts LIKE '%\'$sPage\'%' AND module='$sModule'";
		$objDbGlobal->query($sSQL);

		$iPageId = $objDbGlobal->getField(0, 0);


		$sSQL = "SELECT `{$sAction}` FROM tbl_user_rights WHERE user_id='{$_SESSION['UserId']}' AND page_id='$iPageId'";
		$objDbGlobal->query($sSQL);

		return (($objDbGlobal->getField(0, 0) == "Y") ? true : false);
	}


	// form back redirecing with data
	function backToForm($sError = "")
	{
		$sPostId = @uniqid(rand( ));
		$_SESSION[$sPostId] = @serialize($_POST);
?>
		<html>
			<head>
				<title>Redirecting back...</title>
			</head>

			<body>
				<div style="display:none;">
					<form name="frmData" id="frmData" method="post" action="<?= $_SERVER['HTTP_REFERER'] ?>">
						<input type="hidden" name="PostId" value="<?= $sPostId ?>">
						<input type="hidden" name="Error" value="<?= $sError ?>">
					</form>
				</div>

				<script language="javascript" type="text/javascript">
				<!--
					document.frmData.submit( );
				-->
				</script>
			</body>
		</html>
<?
		exit( );
	}


	// paging function
	function showPaging($iPageId, $iPageCount, $iCount, $iStart, $iTotalRecords, $sParams = "", $bRecords = true)
	{
		if ($iPageCount >= 1 || $iCount > 0)
		{
?>

    <div id="Paging">
	  <table border="0" width="100%" cellpadding="0" cellspacing="0">
	    <tr valign="top">
<?
			if ($bRecords == true)
			{
?>
		  <td width="38%" align="left">
<?
				if ($iCount > 0)
				{
?>
		    Displaying <b><?= ($iStart + 1) ?></b> to <b><?= ($iStart + $iCount) ?></b> (of <b><?= $iTotalRecords ?></b> record<?= (($iTotalRecords != 1) ? 's' : '') ?>)
<?
				}
?>
          </td>
<?
			}
?>
		  <td width="<?= (($bRecords == true) ? 62 : 100) ?>%" align="right">
<?
			if ($iPageCount > 1)
			{
?>
		    <a href="<?= $_SERVER['PHP_SELF'] ?>?PageId=1<?= $sParams ?>"><b>&laquo; First</b></a> |
<?
				if ($iPageId > 1)
				{
?>
		    <a href="<?= $_SERVER['PHP_SELF'] ?>?PageId=<?= ($iPageId - 1) ?><?= $sParams ?>">Back</a> |
<?
				}

				$iStart = 1;
				$iEnd   = $iPageCount;

				if (($iPageId - 4) > 1)
					$iStart = ($iPageId - 4);

				if (($iStart + 8) < $iPageCount)
					$iEnd = ($iStart + 8);

				else
				{
					if (($iPageCount - 8) > 1)
						$iStart = ($iPageCount - 8);
				}

				for ($i = $iStart; $i <= $iEnd; $i ++)
				{
					if ($i == $iPageId)
					{
?>
		          <b><?= $i ?></b> |
<?
					}

					else
					{
?>
		    <a href="<?= $_SERVER['PHP_SELF'] ?>?PageId=<?= $i ?><?= $sParams ?>"><?= $i ?></a> |
<?
					}
				}

				if ($iPageId < $iPageCount)
				{
?>
		    <a href="<?= $_SERVER['PHP_SELF'] ?>?PageId=<?= ($iPageId + 1) ?><?= $sParams ?>">Next</a> |
<?
				}
?>
		    <a href="<?= $_SERVER['PHP_SELF'] ?>?PageId=<?= $iPageCount ?><?= $sParams ?>"><b>Last &raquo;</b></a>
<?
			}
?>
		  </td>
	    </tr>
	  </table>
    </div>

<?
		}
	}


	function getPagingInfo($sTable, $sConditions, $iPageSize, $iPageId)
	{
		global $objDbGlobal;

		if (!$objDbGlobal)
			$objDbGlobal = new Database( );

		if (@strpos($sTable, "SELECT") !== FALSE)
			$sSQL = $sTable;

		else
			$sSQL = "SELECT COUNT(*) FROM $sTable $sConditions;";

		$objDbGlobal->query($sSQL);

		$iTotalRecords = $objDbGlobal->getField(0, 0);

		if ($iTotalRecords > 0)
		{
			$iPageCount = @floor($iTotalRecords / $iPageSize);

			if (($iTotalRecords % $iPageSize) > 0)
				$iPageCount += 1;
		}

		$iStart = (($iPageId * $iPageSize) - $iPageSize);

		return array($iTotalRecords, $iPageCount, $iStart);
	}


	function formatDate($sDate, $sFormat = "d-M-Y")
	{
		if ($sDate == "" || $sDate == "0000-00-00" || $sDate == "1970-01-01" || $sDate == "0000-00-00 00:00:00" || $sDate == "1970-01-01 00:00:00")
			return "";

		else
		{
			if (strlen($sDate) == 19 && $_SESSION["UserType"] == "MGF")
				return date($sFormat, (strtotime($sDate) + 10800));

			return date($sFormat, strtotime($sDate));
		}
	}


	function formatTime($sTime, $sFormat = "h:i A")
	{
		if ($sTime == "" || $sTime == "00:00:00")
			return "";

		else
		{
			if ($_SESSION["UserType"] == "MGF")
				return date($sFormat, (strtotime($sTime) + 10800));

			return date($sFormat, strtotime($sTime));
		}
	}


	function parseDate($sDate)
	{
		if ($sDate{2} == "-" && $sDate{6} == "-" && strlen($sDate) == 11)
		{
			@list($iDay, $sMonth, $iYear) = @explode("-", $sDate);

			$iMonths = array("Jan" => "01", "Feb" => "02", "Mar" => "03", "Apr" => "04", "May" => "05", "Jun" => "06", "Jul" => "07", "Aug" => "08", "Sep" => "09", "Oct" => "10", "Nov" => "11", "Dec" => "12");

			$sDate = ($iYear."-".$iMonths[ucwords($sMonth)]."-".str_pad($iDay, 2, '0', STR_PAD_LEFT));
		}

		else if (@is_numeric($sDate))
		{
			$iDays = intval($sDate);
			$sDate = date("Y-m-d", mktime(0, 0, 0, 1, ($iDays - 1), 1900));
		}

		else
			$sDate = date("Y-m-d", strtotime($sDate));


		if ($sDate == "1970-01-01" || $sDate == "")
			return "0000-00-00";

		return $sDate;
	}


	function formatNumber($sNumber, $bFlag = true, $iDecimals = 2, $bSeparator = true)
	{
		if ($bFlag == false)
			$iDecimals = 0;

		return @number_format($sNumber, $iDecimals, '.', (($bSeparator == true) ? ',' : ''));
	}


	function getIcNo($sInvoiceNo)
	{
		global $objDbGlobal;

		if (!$objDbGlobal)
			$objDbGlobal = new Database( );

		$sSQL = "SELECT id FROM tbl_invoices WHERE invoice_no='$sInvoiceNo'";
		$objDbGlobal->query($sSQL);

		if ($objDbGlobal->getCount( ) == 1)
			return $objDbGlobal->getField(0, 0);

		else
		{
			$iId = getNextId("tbl_invoices");

			$sSQL = "INSERT INTO tbl_invoices (id, invoice_no) VALUES ('$iId', '$sInvoiceNo');";
			$objDbGlobal->execute($sSQL);

			return $iId;
		}
	}


	function getNextId($sTable)
	{
		global $objDbGlobal;

		if (!$objDbGlobal)
			$objDbGlobal = new Database( );

		$sSQL = "SELECT MAX(id) FROM $sTable";
		$objDbGlobal->query($sSQL);

		return ($objDbGlobal->getField(0, 0) + 1);
	}


	function getList($sTable, $sKey, $sValue, $sConditions = "", $sOrderBy = "", $sGroupBy = "")
	{
		global $objDbGlobal;
		$sList = array( );

		if (!$objDbGlobal)
			$objDbGlobal = new Database( );

		if ($sConditions != "")
			$sConditions = (" WHERE ".$sConditions);

		if ($sOrderBy == "")
			$sOrderBy = $sValue;

		if ($sGroupBy != "")
			$sGroupBy = " GROUP BY {$sGroupBy} ";


		$sSQL = "SELECT $sKey, $sValue FROM $sTable $sConditions $sGroupBy ORDER BY $sOrderBy";
		$objDbGlobal->query($sSQL);

		$iCount = $objDbGlobal->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{
			if ($_SESSION['Guest'] == "Y" && $sTable == "tbl_users")
				$sList[$objDbGlobal->getField($i, 0)] = (($objDbGlobal->getField($i, 0) == 584) ? $objDbGlobal->getField($i, 1) : ("User - ".$objDbGlobal->getField($i, 0)));

			else if ($_SESSION['Guest'] == "Y" && $sTable == "tbl_brands")
				$sList[$objDbGlobal->getField($i, 0)] = (($objDbGlobal->getField($i, 0) == 256) ? $objDbGlobal->getField($i, 1) : ("Brand - ".$objDbGlobal->getField($i, 0)));

			else if ($_SESSION['Guest'] == "Y" && $sTable == "tbl_vendors")
				$sList[$objDbGlobal->getField($i, 0)] = (($objDbGlobal->getField($i, 0) == 246) ? $objDbGlobal->getField($i, 1) : ("Vendor - ".$objDbGlobal->getField($i, 0)));

			else
				$sList[$objDbGlobal->getField($i, 0)] = $objDbGlobal->getField($i, 1);
		}

		return $sList;
	}


	function getDbValue($sField, $sTable, $sConditions, $sOrderBy = "", $sLimit = "")
	{
		if (!$objDbGlobal)
			$objDbGlobal = new Database( );

		if ($sConditions != "")
			$sConditions = " WHERE $sConditions";

		if ($sOrderBy != "")
			$sOrderBy = " ORDER BY $sOrderBy ";

		if ($sLimit != "")
			$sLimit = " LIMIT $sLimit ";

		if ($_SESSION['Guest'] == "Y" && @strpos($sField, "_id") === FALSE)
		{
			if (@strpos($sTable, "tbl_users") !== FALSE && @strpos($sField, "name") !== FALSE)
				return "User X";

			if (@strpos($sTable, "tbl_brands") !== FALSE && @strpos($sField, "brand") !== FALSE)
				return "Brand X";

			if (@strpos($sTable, "tbl_vendors") !== FALSE && @strpos($sField, "vendor") !== FALSE)
				return "Vendor X";
		}


		$sSQL = "SELECT {$sField} FROM {$sTable} $sConditions $sOrderBy $sLimit";
		$objDbGlobal->query($sSQL);

		return $objDbGlobal->getField(0, 0);
	}


	function redirect($sPage, $sError = "")
	{
		if ($sError != "")
			$_SESSION['Flag'] = $sError;

		if ($sPage == "")
			$sPage = SITE_URL;

		header("Location: $sPage");
		exit( );
	}

	function showForm($sForm, $sFilesDir = "", $iLabelColWidth = 200)
	{
?>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
<?
		for ($i = 0; $i < count($sForm); $i ++)
		{
			$sLabel  = $sForm[$i]['Label'];
			$sType   = (($sForm[$i]['Type'] == "") ? "TEXT" : $sForm[$i]['Type']);
			$sName   = $sForm[$i]['Field'];
			$sId     = (($sForm[$i]['Id'] == "") ? $sName : $sForm[$i]['Id']);
			$sValue  = $sForm[$i]['Value'];
			$iSize   = (($sForm[$i]['Size'] == "") ? 30 : $sForm[$i]['Size']);
			$iRows   = (($sForm[$i]['Rows'] == "") ? 4 : $sForm[$i]['Rows']);
			$iCols   = (($sForm[$i]['Columns'] == "") ? 30 : $sForm[$i]['Columns']);
			$sScript = $sForm[$i]['Script'];
			$sValues = $sForm[$i]['Values'];
			$sLabels = $sForm[$i]['Labels'];
?>
				  <tr<?= (($sType == "TEXTAREA") ? ' valign="top"' : '') ?>>
					<td width="<?= $iLabelColWidth ?>"><?= $sLabel ?></td>
					<td width="20" align="center">:</td>

					<td>
<?
			if ($sType == "TEXT")
			{
?>
					  <div>
						<input type="text" name="<?= $sName ?>" value="<?= $sValue ?>" id="<?= $sId ?>" size="<?= $iSize ?>" autocomplete="off" class="textbox" />
<?
				if ($sScript != "")
				{
?>
					    <div id="Choices_<?= $sId ?>" class="autocomplete"></div>

					    <script type="text/javascript">
					    <!--
						    new Ajax.Autocompleter("<?= $sId ?>", "Choices_<?= $sId ?>", "ajax/<?= $sScript ?>", { paramName:"Keywords", minChars:3 } );
					    -->
					    </script>
<?
				}
?>
					  </div>
<?
			}

			else if ($sType == "READONLY")
			{
?>
		      		<?= $sValue ?>
<?
			}

			else if ($sType == "FILE")
			{
?>
		      		<input type="file" name="<?= $sName ?>" id="<?= $sId ?>" size="<?= $iSize ?>" class="textbox" /><? if ($sValue != "") { ?> ( <a href="<?= $sFilesDir.$sValue ?>" target="_blank">view</a> )<? } ?>
<?
			}

			else if ($sType == "TEXTAREA")
			{
?>
		      		<textarea name="<?= $sName ?>" id="<?= $sId ?>" rows="<?= $iRows ?>" cols="<?= $iCols ?>"><?= $sValue ?></textarea>
<?
			}

			else if ($sType == "DROPDOWN")
			{
?>
		      		<select name="<?= $sName ?>" id="<?= $sId ?>">
		      		   <option value=""></option>
<?
				for ($j = 0; $j < count($sValues); $j ++)
				{
?>
		      		  <option value="<?= $sValues[$j] ?>"<?= (($sValues[$j] == $sValue) ? ' selected' : '') ?>><?= $sLabels[$j] ?></option>
<?
				}
?>
		      		</select>
<?
			}

			else if ($sType == "DATE")
			{
?>

					  <table border="0" cellpadding="0" cellspacing="0" width="113">
						<tr>
						  <td width="79"><input type="text" name="<?= $sName ?>" id="<?= $sId ?>" value="<?= (($sValue == '' || $sValue == '0000-00-00' || $sValue == '1970-01-01') ? '' : $sValue) ?>" readonly class="textbox" style="width:70px;" onclick="displayCalendar($('<?= $sId ?>'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('<?= $sId ?>'), 'yyyy-mm-dd', this);" /></td>
						</tr>
					  </table>

<?
			}
?>
					</td>
				  </tr>
<?
		}
?>
	    		</table>
<?
	}

	function getExcelCol($iIndex)
	{
		$iIndex -= 64;

		$iQuotient  = @floor($iIndex / 26);
		$iRemainder = @($iIndex % 26);

		if ($iRemainder == 0 && $iQuotient > 0)
		{
			$iQuotient --;
			$iRemainder = 26;
		}

		$sCol = "";

		if ($iQuotient > 0)
			$sCol = chr($iQuotient + 64);

		$sCol .= chr($iRemainder + 64);

		return $sCol;
	}

	function authenticateUser( )
	{
		if (!isset($_SERVER['PHP_AUTH_USER']))
		{
			header('WWW-Authenticate: Basic realm="Matrix Attendance System"');
			header('HTTP/1.0 401 Unauthorized');

			print  "Sorry, you don't have the rights to access this System.";

			exit( );
		}

		else
		{
			if (!$objDbGlobal)
				$objDbGlobal = new Database( );

			$Username = $_SERVER['PHP_AUTH_USER'];
			$Password = $_SERVER['PHP_AUTH_PW'];

			$sSQL = "SELECT id, name, email, status, attendance, country_id FROM tbl_users WHERE username='$Username' AND password=PASSWORD('$Password')";

			if ($objDbGlobal->query($sSQL) == false || $objDbGlobal->getCount( ) != 1 || $objDbGlobal->getField(0, "status") != "A" || $objDbGlobal->getField(0, "attendance") != "Y")
			{
				header('WWW-Authenticate: Basic realm="Matrix Attendance System"');
				header('HTTP/1.0 401 Unauthorized');

				print  "Sorry, you don't have the rights to access this System.";

				exit( );
			}

			$_SESSION['Country'] = $objDbGlobal->getField(0, "country_id");
		}
	}


	function getPercentage($sStartDate, $sEndDate, $iLeadTime)
	{
		$iPercentage = 0;

		if (strtotime($sEndDate) <= strtotime(date("Y-m-d")))
			$iPercentage = 100;

		else if (strtotime($sStartDate) <= strtotime(date("Y-m-d")) && strtotime($sEndDate) > strtotime(date("Y-m-d")))
		{
			$iTime  = (strtotime(date("Y-m-d")) - strtotime($sStartDate));
			$iTime /= 24;
			$iTime /= 60;
			$iTime /= 60;
			$iTime += 1;

			$iPercentage = @round(($iTime / $iLeadTime) * 100);
		}

		return $iPercentage;
	}


	function getBtxVsrValue($iPercentage)
	{
		if ($iPercentage == 100)
			return "Completed";

		else if ($iPercentage > 0)
			return "Started";

		else
			return "Not Started";
	}


	function getBtxVsrPercentage($sValue)
	{
		if (strtolower($sValue) == "completed")
			return 100;

		else if (strtolower($sValue) == "started")
			return 50;

		else
			return 0;
	}


    function getWorkingDays($sStartDate, $sEndDate)
    {
       if ($sStartDate == $sEndDate)
       	return 1;

       // The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
       $fDays = (abs(strtotime($sEndDate) - strtotime($sStartDate)) / 86400);

       $iFullWeeks         = @floor($fDays / 7);
       $iWeekRemainingDays = @fmod($fDays, 7);

       // It will return 1 if it's Monday,.. ,7 for Sunday
       $iWeekFirstDay = date("N", strtotime($sStartDate));
       $iWeekLastDay  = date("N", strtotime($sEndDate));

       // The two can be equal in leap years when february has 29 days, the equal sign is added here
       // In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
       if ($iWeekFirstDay <= $iWeekLastDay)
       {
          if ($iWeekFirstDay <= 6 && 6 <= $iWeekLastDay)
             $iWeekRemainingDays --;

          if ($iWeekFirstDay <= 7 && 7 <= $iWeekLastDay)
             $iWeekRemainingDays --;
       }

       else
       {
          if ($iWeekFirstDay <= 6)
          {
             // In the case when the interval falls in two weeks, there will be a Sunday for sure
             $iWeekRemainingDays --;
          }
       }

       // The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
       // february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
       $fWorkingDays = $iFullWeeks * 5;

       if ($iWeekRemainingDays > 0)
         $fWorkingDays += $iWeekRemainingDays;

       return @ceil($fWorkingDays);
    }


    function seconds2Time($iSeconds)
    {
    	return (str_pad(intval(intval($iSeconds / 3600)), 2, "0", STR_PAD_LEFT).":".str_pad(intval(($iSeconds / 60) % 60), 2, "0", STR_PAD_LEFT).":".str_pad(intval($iSeconds % 60), 2, "0", STR_PAD_LEFT));
    }


	function xml2array($sXml, $sAttributes = 1, $sPriority = "tag")
	{
		if (!$sXml)
			return array();

		if (!function_exists('xml_parser_create'))
			return array();

		//Get the XML parser of PHP - PHP must have this module for the parser to work
		$objParser = xml_parser_create('');

		xml_parser_set_option($objParser, XML_OPTION_TARGET_ENCODING, "UTF-8");
		xml_parser_set_option($objParser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($objParser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($objParser, trim($sXml), $sXmlData);
		xml_parser_free($objParser);

		if (!$sXmlData)
			return;

		//Initializations
		$sXmlArray = array( );
		$sParents  = array( );
		$sOpenTags = array( );
		$sArray    = array( );

		$sCurrent = &$sXmlArray;

		// Go through the tags.
		$repeated_tag_index = array();//Multiple tags with same name will be turned into an array
		foreach($sXmlData as $data) {
		unset($attributes,$value);//Remove existing values, or there will be trouble

		//This command will extract these variables into the foreach scope
		// tag(string), type(string), level(int), attributes(array).
		extract($data);//We could use the array by itself, but this cooler.

		$result = array();
		$attributes_data = array();

		if (isset($value))
		{
			if ($sPriority == 'tag')
				$result = $value;

			else
				$result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
		}

		//Set the attributes too.
		if (isset($attributes) and $sAttributes)
		{
			foreach($attributes as $attr => $val)
			{
				if ($sPriority == 'tag')
					$attributes_data[$attr] = $val;

				else
					$result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
			}
		}

		//See tag status and do the needed.
		if ($type == "open")
		{//The starting of the tag '<tag>'
			$parent[$level-1] = &$sCurrent;

			if (!is_array($sCurrent) or (!in_array($tag, array_keys($sCurrent))))
			{ //Insert New tag
				$sCurrent[$tag] = $result;

				if ($attributes_data)
					$sCurrent[$tag. '_attr'] = $attributes_data;

				$repeated_tag_index[$tag.'_'.$level] = 1;

				$sCurrent = &$sCurrent[$tag];

			}

			else
			{ //There was another element with the same tag name

				if (isset($sCurrent[$tag][0]))
				{//If there is a 0th element it is already an array
					$sCurrent[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
					$repeated_tag_index[$tag.'_'.$level]++;
				}

				else
				{//This section will make the value an array if multiple tags with the same name appear together
					$sCurrent[$tag] = array($sCurrent[$tag],$result);//This will combine the existing item and the new item together to make an array
					$repeated_tag_index[$tag.'_'.$level] = 2;

					if (isset($sCurrent[$tag.'_attr']))
					{ //The attribute of the last(0th) tag must be moved as well
						$sCurrent[$tag]['0_attr'] = $sCurrent[$tag.'_attr'];
						unset($sCurrent[$tag.'_attr']);
					}

				}

				$last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
				$sCurrent = &$sCurrent[$tag][$last_item_index];
			}

		}

		else if ($type == "complete")
		{ //Tags that ends in 1 line '<tag />'
			//See if the key is already taken.

			if (!isset($sCurrent[$tag]))
			{ //New Key
				$sCurrent[$tag] = $result;
				$repeated_tag_index[$tag.'_'.$level] = 1;

				if ($sPriority == 'tag' and $attributes_data)
					$sCurrent[$tag. '_attr'] = $attributes_data;

			}

			else
			{ //If taken, put all things inside a list(array)

				if (isset($sCurrent[$tag][0]) and is_array($sCurrent[$tag]))
				{//If it is already an array...

					// ...push the new element into that array.
					$sCurrent[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

					if ($sPriority == 'tag' and $sAttributes and $attributes_data)
						$sCurrent[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;

					$repeated_tag_index[$tag.'_'.$level]++;

				}

				else
				{ //If it is not an array...
					$sCurrent[$tag] = array($sCurrent[$tag],$result); //...Make it an array using using the existing value and the new value
					$repeated_tag_index[$tag.'_'.$level] = 1;

					if ($sPriority == 'tag' and $sAttributes)
					{
						if (isset($sCurrent[$tag.'_attr']))
						{ //The attribute of the last(0th) tag must be moved as well

							$sCurrent[$tag]['0_attr'] = $sCurrent[$tag.'_attr'];
							unset($sCurrent[$tag.'_attr']);
						}

						if ($attributes_data)
							$sCurrent[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
					}

					$repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
				}
			}

			}

			elseif ($type == 'close')
			{ //End of tag '</tag>'
				$sCurrent = &$parent[$level-1];
			}
		}

		return($sXmlArray);
	}



	function createImage($sSrcFile, $sDestFile, $iImgWidth, $iImgHeight, $iR = 255, $iG = 255, $iB = 255)
	{
		@list($iWidth, $iHeight, $sType, $sAttributes) = @getimagesize($sSrcFile);

		$fRatio     = @($iWidth / $iHeight);
		$iNewWidth  = $iImgWidth;
		$iNewHeight = $iImgHeight;
		$iLeft      = 0;
		$iTop       = 0;

		if (@($iNewWidth / $iNewHeight) > $fRatio)
		   $iNewWidth = ($iNewHeight * $fRatio);

		else
		   $iNewHeight = @($iNewWidth / $fRatio);


		if ($iNewWidth < $iImgWidth)
			$iLeft = @ceil(($iImgWidth - $iNewWidth) / 2);

		if ($iNewHeight < $iImgHeight)
			$iTop = @ceil(($iImgHeight - $iNewHeight) / 2);

		$iPosition  = @strrpos($sSrcFile, '.');
		$sExtension = @substr($sSrcFile, $iPosition);

		switch(strtolower($sExtension))
		{
			case '.jpg'  : $sSource = @imagecreatefromjpeg($sSrcFile);
						   break;

			case '.jpeg' : $sSource = @imagecreatefromjpeg($sSrcFile);
						   break;

			case '.png'  : $sSource = @imagecreatefrompng($sSrcFile);
						   break;

			case '.gif'  : $sSource = @imagecreatefromgif($sSrcFile);
						   break;
		}

		$sTemp = @imagecreatetruecolor($iNewWidth, $iNewHeight);
		@imagecopyresampled($sTemp, $sSource, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $iWidth, $iHeight);

		$sThumb   = @imagecreatetruecolor($iImgWidth, $iImgHeight);
		$sBgColor = @imagecolorallocate($sThumb, $iR, $iG, $iB);

		@imagefill($sThumb, 0, 0, $sBgColor);
		@imagecopy($sThumb, $sTemp, $iLeft, $iTop, 0, 0, $iNewWidth, $iNewHeight);


		if ($sExtension == ".png")
			@imagepng($sThumb, $sDestFile, 9);

		else if ($sExtension == ".gif")
			@imagegif($sThumb, $sDestFile);

		else
			@imagejpeg($sThumb, $sDestFile, 100);


		@imagedestroy($sTemp);
		@imagedestroy($sThumb);
		@imagedestroy($sSource);
	}


	function formValue($sValue)
	{
		return htmlentities(html_entity_decode($sValue, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
	}


    function currencyInWords($fNumber, $sCurrency = "USD")
    {
    	if (@strpos($fNumber, ".") !== FALSE)
    	{
    		$iNumber   = substr($fNumber, 0, strpos($fNumber, "."));
    		$iFraction = substr($fNumber, (strpos($fNumber, ".") + 1));
    	}

    	else
    	{
    		$iNumber   = $fNumber;
    		$iFraction = 0;
		}


        if (($iNumber < 0) || ($iNumber > 999999999))
            return $iNumber;

        $iMillions = @floor($iNumber / 1000000);  /* Millions */
            $iNumber -= ($iMillions * 1000000);

        $iLacs = @floor($iNumber / 100000);       /* Lac */
            $iNumber -= ($iLacs * 100000);

        $iThousands = @floor($iNumber / 1000);    /* Thousands */
            $iNumber -= ($iThousands * 1000);

        $iHundreds = @floor($iNumber / 100);      /* Hundreds */
            $iNumber -= ($iHundreds * 100);

        $iTens = @floor($iNumber / 10);           /* Tens (deca) */
        $iOnes = ($iNumber % 10);               /* Ones */


        $sNumber = "";

        if ($iMillions)
            $sNumber .= (currencyInWords($iMillions)." Million");

        if ($iLacs)
            $sNumber .= (((empty($sNumber) ? "" : " ").currencyInWords($iLacs)." Lac"));

        if ($iThousands)
            $sNumber .= (((empty($sNumber) ? "" : " ").currencyInWords($iThousands)." Thousand"));

        if ($iHundreds)
            $sNumber .= (((empty($sNumber) ? "" : " ").currencyInWords($iHundreds)." Hundred"));


        $sOnes = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $sTens = array("", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");

        if ($iTens || $iOnes)
        {
            if (!empty($sNumber) && $iFraction == 0)
                $sNumber .= " and ";

            else
            	$sNumber .= " ";


            if ($iTens < 2)
                $sNumber .= $sOnes[($iTens * 10) + $iOnes];

            else
            {
                $sNumber .= $sTens[$iTens];

                if ($iOnes)
                    $sNumber .= (" ".$sOnes[$iOnes]);
            }
        }


        if ($iFraction > 0)
        {
			if ($sCurrency == "GBP")
				$sCurrency = "Pounds";

			else if ($sCurrency == "EUR")
				$sCurrency = "Euros";

			else
				$sCurrency = "Dollars";


			$sNumber .= " {$sCurrency} and ";

        	$iTens = @floor($iFraction / 10);
        	$iOnes = ($iFraction % 10);


            if ($iTens < 2)
                $sNumber .= $sOnes[($iTens * 10) + $iOnes];

            else
            {
                $sNumber .= $sTens[$iTens];

                if ($iOnes)
                    $sNumber .= (" ".$sOnes[$iOnes]);
            }

            $sNumber .= " cents";
        }


        if (empty($sNumber))
            $sNumber = "zero";

        return str_replace("  ", " ", $sNumber);
    }


	function getSampleSize($iQuantity, $iReport = 0, $iInspectionLevel = 2, $iCheckLevel = 1)
	{
		$iSampleSize = $iQuantity;
		$sAqlChart   = array( );

		if ($iReport == 28)
		{
			$sAqlChart[] = array("Min" => 2,    "Max" => 90,    "Samples" => 0);
			$sAqlChart[] = array("Min" => 91,   "Max" => 150,   "Samples" => 20);
			$sAqlChart[] = array("Min" => 151,  "Max" => 280,   "Samples" => 32);
			$sAqlChart[] = array("Min" => 281,  "Max" => 500,   "Samples" => 50);
			$sAqlChart[] = array("Min" => 501,  "Max" => 1200,  "Samples" => 80);
			$sAqlChart[] = array("Min" => 1201, "Max" => 3200,  "Samples" => 125);
			$sAqlChart[] = array("Min" => 3201, "Max" => 10000, "Samples" => 200);
		}

		else if ($iReport == 37)
		{
			if ($iInspectionLevel == 1)
			{
				$sAqlChart[] = array("Min" => 2,   "Max" => 8,   "Samples" => 2);
				$sAqlChart[] = array("Min" => 9,   "Max" => 15,  "Samples" => 2);
				$sAqlChart[] = array("Min" => 16,  "Max" => 25,  "Samples" => 3);
				$sAqlChart[] = array("Min" => 26,  "Max" => 50,  "Samples" => 5);
				$sAqlChart[] = array("Min" => 51,  "Max" => 90,  "Samples" => 5);
				$sAqlChart[] = array("Min" => 91,  "Max" => 150, "Samples" => 8);
				$sAqlChart[] = array("Min" => 151, "Max" => 280, "Samples" => 13);
			}

			else
			{
				$sAqlChart[] = array("Min" => 2,    "Max" => 90,    "Samples" => 0);
				$sAqlChart[] = array("Min" => 91,   "Max" => 150,   "Samples" => 20);
				$sAqlChart[] = array("Min" => 151,  "Max" => 280,   "Samples" => 32);
				$sAqlChart[] = array("Min" => 281,  "Max" => 500,   "Samples" => 50);
				$sAqlChart[] = array("Min" => 501,  "Max" => 1200,  "Samples" => 80);
				$sAqlChart[] = array("Min" => 1201, "Max" => 3200,  "Samples" => 125);
				$sAqlChart[] = array("Min" => 3201, "Max" => 10000, "Samples" => 200);
			}
		}

		else if ($iReport == 38)
		{
			if ($iCheckLevel == 2)
			{
				$sAqlChart[] = array("Min" => 1,     "Max" => 15,    "Samples" => 0);
				$sAqlChart[] = array("Min" => 16,    "Max" => 25,    "Samples" => 6);
				$sAqlChart[] = array("Min" => 26,    "Max" => 50,    "Samples" => 10);
				$sAqlChart[] = array("Min" => 51,    "Max" => 90,    "Samples" => 16);
				$sAqlChart[] = array("Min" => 91,    "Max" => 150,   "Samples" => 26);
				$sAqlChart[] = array("Min" => 151,   "Max" => 280,   "Samples" => 40);
				$sAqlChart[] = array("Min" => 281,   "Max" => 500,   "Samples" => 64);
				$sAqlChart[] = array("Min" => 501,   "Max" => 1200,  "Samples" => 100);
				$sAqlChart[] = array("Min" => 1201,  "Max" => 3200,  "Samples" => 160);
				$sAqlChart[] = array("Min" => 3201,  "Max" => 10000, "Samples" => 250);
				$sAqlChart[] = array("Min" => 10001, "Max" => 35000, "Samples" => 400);
			}

			else
			{
				$sAqlChart[] = array("Min" => 1,     "Max" => 15,    "Samples" => 0);
				$sAqlChart[] = array("Min" => 16,    "Max" => 25,    "Samples" => 3);
				$sAqlChart[] = array("Min" => 26,    "Max" => 50,    "Samples" => 5);
				$sAqlChart[] = array("Min" => 51,    "Max" => 90,    "Samples" => 8);
				$sAqlChart[] = array("Min" => 91,    "Max" => 150,   "Samples" => 13);
				$sAqlChart[] = array("Min" => 151,   "Max" => 280,   "Samples" => 20);
				$sAqlChart[] = array("Min" => 281,   "Max" => 500,   "Samples" => 32);
				$sAqlChart[] = array("Min" => 501,   "Max" => 1200,  "Samples" => 50);
				$sAqlChart[] = array("Min" => 1201,  "Max" => 3200,  "Samples" => 80);
				$sAqlChart[] = array("Min" => 3201,  "Max" => 10000, "Samples" => 125);
				$sAqlChart[] = array("Min" => 10001, "Max" => 35000, "Samples" => 200);
			}
		}

		else
		{
			$sAqlChart[] = array("Min" => 1,      "Max" => 1,       "Samples" => 1);
			$sAqlChart[] = array("Min" => 2,      "Max" => 8,       "Samples" => 2);
			$sAqlChart[] = array("Min" => 9,      "Max" => 15,      "Samples" => 3);
			$sAqlChart[] = array("Min" => 16,     "Max" => 25,      "Samples" => 5);
			$sAqlChart[] = array("Min" => 26,     "Max" => 50,      "Samples" => 8);
			$sAqlChart[] = array("Min" => 51,     "Max" => 90,      "Samples" => 13);
			$sAqlChart[] = array("Min" => 91,     "Max" => 150,     "Samples" => 20);
			$sAqlChart[] = array("Min" => 151,    "Max" => 280,     "Samples" => 32);
			$sAqlChart[] = array("Min" => 281,    "Max" => 500,     "Samples" => 50);
			$sAqlChart[] = array("Min" => 501,    "Max" => 1200,    "Samples" => 80);
			$sAqlChart[] = array("Min" => 1201,   "Max" => 3200,    "Samples" => 125);
			$sAqlChart[] = array("Min" => 3201,   "Max" => 10000,   "Samples" => 200);
			$sAqlChart[] = array("Min" => 10001,  "Max" => 35000,   "Samples" => 315);
			$sAqlChart[] = array("Min" => 35001,  "Max" => 150000,  "Samples" => 500);
			$sAqlChart[] = array("Min" => 150001, "Max" => 500000,  "Samples" => 800);
			$sAqlChart[] = array("Min" => 500001, "Max" => 9999999, "Samples" => 1250);
		}


		foreach ($sAqlChart as $iChartRow)
		{
			if ($iQuantity >= $iChartRow['Min'] && $iQuantity <= $iChartRow['Max'])
			{
				$iSampleSize = (($iChartRow['Samples'] == 0) ? $iQuantity : $iChartRow['Samples']);

				break;
			}
		}


		return $iSampleSize;
	}


	function getAqlDefects($iSampleSize, $fAql, $iReport = 0, $iInspectionLevel = 2, $iCheckLevel = 1)
	{
		$iMajor = 0;
		$iMinor = 0;


		if ($iReport == 28)
		{
			$sAqlChart        = array( );
			$sAqlChart["0"]   = array("2.5" => array(0, 0));
			$sAqlChart["20"]  = array("2.5" => array(1, 2));
			$sAqlChart["32"]  = array("2.5" => array(2, 3));
			$sAqlChart["50"]  = array("2.5" => array(3, 5));
			$sAqlChart["80"]  = array("2.5" => array(5, 7));
			$sAqlChart["125"] = array("2.5" => array(7, 10));
			$sAqlChart["200"] = array("2.5" => array(10, 14));


			if (@isset($sAqlChart["{$iSampleSize}"]["{$fAql}"]))
				@list($iMajor, $iMinor) = $sAqlChart["{$iSampleSize}"]["{$fAql}"];
		}

		else if ($iReport == 37)
		{
			$sAqlChart = array( );

			if ($iInspectionLevel == 1)
			{
				$sAqlChart["2"]  = array("2.5" => array(0, 0));
				$sAqlChart["3"]  = array("2.5" => array(0, 0));
				$sAqlChart["5"]  = array("2.5" => array(0, 0));
				$sAqlChart["8"]  = array("2.5" => array(0, 1));
				$sAqlChart["13"] = array("2.5" => array(1, 2));
			}

			else
			{
				$sAqlChart["0"]   = array("2.5" => array(0, 0));
				$sAqlChart["20"]  = array("2.5" => array(1, 2));
				$sAqlChart["32"]  = array("2.5" => array(2, 3));
				$sAqlChart["50"]  = array("2.5" => array(3, 5));
				$sAqlChart["80"]  = array("2.5" => array(5, 7));
				$sAqlChart["125"] = array("2.5" => array(7, 10));
				$sAqlChart["200"] = array("2.5" => array(10, 14));
			}

			if (@isset($sAqlChart["{$iSampleSize}"]["{$fAql}"]))
				@list($iMajor, $iMinor) = $sAqlChart["{$iSampleSize}"]["{$fAql}"];
		}

		else if ($iReport == 38)
		{
			if ($iCheckLevel == 2)
			{
				$sAqlChart["0"]   = 0;
				$sAqlChart["6"]   = 0;
				$sAqlChart["10"]  = 0;
				$sAqlChart["16"]  = 1;
				$sAqlChart["26"]  = 3;
				$sAqlChart["40"]  = 4;
				$sAqlChart["64"]  = 6;
				$sAqlChart["100"] = 8;
				$sAqlChart["160"] = 12;
				$sAqlChart["250"] = 18;
				$sAqlChart["400"] = 26;
			}

			else
			{
				$sAqlChart["0"]   = 0;
				$sAqlChart["3"]   = 0;
				$sAqlChart["4"]   = 0;
				$sAqlChart["8"]   = 0;
				$sAqlChart["13"]  = 0;
				$sAqlChart["20"]  = 1;
				$sAqlChart["32"]  = 2;
				$sAqlChart["50"]  = 3;
				$sAqlChart["80"]  = 5;
				$sAqlChart["125"] = 7;
				$sAqlChart["200"] = 11;
			}

			if (@isset($sAqlChart["{$iSampleSize}"]))
				$iMajor = $sAqlChart["{$iSampleSize}"];
		}

		else
		{
			$sAqlChart         = array( );
			$sAqlChart["2"]    = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 0, "F" => 2, "T" => 8);
			$sAqlChart["3"]    = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 0, "F" => 9, "T" => 15);
			$sAqlChart["5"]    = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 0, "F" => 16, "T" => 25);
			$sAqlChart["8"]    = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 0, "F" => 26, "T" => 50);
			$sAqlChart["13"]   = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 0, "4" => 1, "F" => 51, "T" => 90);
			$sAqlChart["20"]   = array("0.65" => 0, "1" => 0, "1.5" => 0, "2.5" => 1, "4" => 2, "F" => 91, "T" => 150);
			$sAqlChart["32"]   = array("0.65" => 0, "1" => 0, "1.5" => 1, "2.5" => 2, "4" => 3, "F" => 151, "T" => 280);
			$sAqlChart["50"]   = array("0.65" => 0, "1" => 1, "1.5" => 2, "2.5" => 3, "4" => 5, "F" => 281, "T" => 500);
			$sAqlChart["80"]   = array("0.65" => 1, "1" => 2, "1.5" => 3, "2.5" => 5, "4" => 7, "F" => 501, "T" => 1200);
			$sAqlChart["125"]  = array("0.65" => 2, "1" => 3, "1.5" => 5, "2.5" => 7, "4" => 10, "F" => 1201, "T" => 3200);
			$sAqlChart["200"]  = array("0.65" => 3, "1" => 5, "1.5" => 7, "2.5" => 10, "4" => 14, "F" => 3201, "T" => 10000);
			$sAqlChart["315"]  = array("0.65" => 5, "1" => 7, "1.5" => 10, "2.5" => 14, "4" => 21, "F" => 10001, "T" => 35000);
			$sAqlChart["500"]  = array("0.65" => 7, "1" => 10, "1.5" => 14, "2.5" => 21, "4" => 21, "F" => 35001, "T" => 150000);
			$sAqlChart["800"]  = array("0.65" => 10, "1" => 14, "1.5" => 21, "2.5" => 21, "4" => 21, "F" => 150001, "T" => 500000);
			$sAqlChart["1250"] = array("0.65" => 14, "1" => 21, "1.5" => 21, "2.5" => 21, "4" => 21, "F" => 500001, "T" => 50000000);

			if (@isset($sAqlChart["{$iSampleSize}"]["{$fAql}"]))
				$iMajor = $sAqlChart["{$iSampleSize}"]["{$fAql}"];
		}


		return array($iMajor, $iMinor);
	}


    function calculateDistance($fLatitudeA, $fLongitudeA, $fLatitudeB, $fLongitudeB, $sUnit = "K")
    {
            $fTheta    = ($fLongitudeA - $fLongitudeB);
            $fDistance = @sin(deg2rad($fLatitudeA)) * sin(deg2rad($fLatitudeB)) +  cos(deg2rad($fLatitudeA)) * cos(deg2rad($fLatitudeB)) * cos(deg2rad($fTheta));
            $fDistance = acos($fDistance);
            $fDistance = rad2deg($fDistance);
            $fMiles    = ($fDistance * 60 * 1.1515);


            if ($sUnit == "K")
            {
                    $fKiloMeters = @round(($fMiles * 1.609344), 2);

                    if ($fKiloMeters < 1)
                            return (@round($fKiloMeters * 1000)." Meters");

                    return "{$fKiloMeters} Km";
            }

            else if ($sUnit == "N")
                    return (@round(($fMiles * 0.8684), 2)." NM");

            else
                    return (@round($fMiles, 2)." Miles");
    }



	function parseTolerance($sTolerance)
	{
		if (@strpos($sTolerance, "(+/-)") !== FALSE || @strpos($sTolerance, "+/-") !== FALSE || @strpos($sTolerance, "-/+") !== FALSE)
		{
			$sTolerance = trim(str_replace(array("+/-", "-/+", "(+/-)", "(-/+)"), "", $sTolerance));

			if (@strpos($sTolerance, "/") !== FALSE)
			{
				$fTolerance = 0;

				if (@strpos($sTolerance, " ") !== FALSE)
				{
					@list($sLeft, $sRight) = @explode(" ", $sTolerance);

					$sLeft  = trim($sLeft);
					$sRight = trim($sRight);

					$fTolerance = floatval($sLeft);

					@list($sNumerator, $sDenominator) = @explode("/", $sRight);
				}

				else
					@list($sNumerator, $sDenominator) = @explode("/", $sTolerance);


				$sNumerator   = trim($sNumerator);
				$sDenominator = trim($sDenominator);

				$fTolerance  += @(floatval($sNumerator) / floatval($sDenominator));

				$fMinusTolerance = $fTolerance;
				$fPlusTolerance  = $fTolerance;
			}

			else
			{
				$fMinusTolerance = floatval($sTolerance);
				$fPlusTolerance  = floatval($sTolerance);
			}
		}

		else if (@strpos($sTolerance, "/") !== FALSE)
		{
			@list($sLeft, $sRight) = @explode("/", $sTolerance);

			$sLeft  = trim($sLeft);
			$sRight = trim($sRight);


			if (@strpos($sTolerance, "+") !== FALSE || @strpos($sTolerance, "-") !== FALSE)
			{
				if (@strpos($sRight, "+") !== FALSE)
				{
					$sLeft  = trim(str_replace(array("+", "-"), "", $sLeft));
					$sRight = trim(str_replace(array("+", "-"), "", $sRight));

					$fMinusTolerance = floatval($sLeft);
					$fPlusTolerance  = floatval($sRight);
				}

				else
				{
					$sLeft  = trim(str_replace(array("+", "-"), "", $sLeft));
					$sRight = trim(str_replace(array("+", "-"), "", $sRight));

					$fMinusTolerance = floatval($sRight);
					$fPlusTolerance  = floatval($sLeft);
				}
			}

			else
			{
				$fMinusTolerance = floatval($sLeft);
				$fPlusTolerance  = floatval($sRight);
			}
		}

		else
		{
			$sTolerance = trim(str_replace(array("+", "-"), "", $sTolerance));

			$fMinusTolerance = floatval($sTolerance);
			$fPlusTolerance  = floatval($sTolerance);
		}


		return array($fMinusTolerance, $fPlusTolerance);
	}
?>