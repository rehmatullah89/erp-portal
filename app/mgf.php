<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree QUONDA App                                                                   **
	**  Version 3.0                                                                              **
	**                                                                                           **
	**  http://app.3-tree.com                                                                    **
	**                                                                                           **
	**  Copyright 2008-17 (C) Triple Tree                                                        **
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
	***********************************************************************************************
	\*********************************************************************************************/

	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );


	authenticateUser( );
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
	<div style="width:600px; background:#cccccc; margin:50px auto 0px auto;">
		<h1 align="center" style="text-transform:none; margin-bottom:0px;">QUONDA&reg; App Notifications</h1>

<?
	$Title      = IO::strValue("Title");
	$Summary    = IO::strValue("Summary");
	$Message    = IO::strValue("Message");
	$Recipients = IO::getArray("Recipients");


	if ($_POST && $Title != "" && $Summary != "" && $Message != "" && count($Recipients) > 0)
	{
		$sRecipients      = @implode(",", $Recipients);
		$sRegistrationIds = array( );


		$sSQL = "SELECT device_id FROM tbl_users WHERE status='A' AND device_id!='' AND FIND_IN_SET(id,'$sRecipients')";
		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
			$sRegistrationIds[] = $objDb->getField($i, 0);


		if (count($sRegistrationIds) > 0)
		{
			$sApiKey  = "AIzaSyBKUixemX1jXoHwR7F4dsTUiGWwmRuZwDI";

			$sParams  = array("registration_ids" => $sRegistrationIds,

							  "data"             => array("Title"   => @utf8_encode($Title),
													 	  "Summary" => @utf8_encode($Summary),
														  "Message" => @utf8_encode($Message)));

			$sHeaders = array("Authorization: key={$sApiKey}",
							  "Content-Type: application/json");


			$objCurl = @curl_init("https://android.googleapis.com/gcm/send");

			@curl_setopt($objCurl, CURLOPT_HTTPHEADER, $sHeaders);
			@curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, TRUE);
			@curl_setopt($objCurl, CURLOPT_POST, TRUE);
			@curl_setopt($objCurl, CURLOPT_POSTFIELDS, @json_encode($sParams));
			@curl_setopt($objCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
			@curl_setopt($objCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

			$sResponse = @curl_exec($objCurl);
			$sError    = "";


			if (@curl_errno($objCurl))
				$sError = @curl_error($objCurl);

			else if (@strpos($sResponse, "multicast_id") === FALSE)
				$sError = $sResponse;

			else
			{
				$sParams = @json_decode($sResponse);

				if ($sParams->success > 0)
					$sError = "";

				else if ($sParams->failure == 1)
					$sError = ("API ERROR: ".$sParams->results[0]->error);

				else
					$sError = "An ERROR occured while processing your request.";
			}
		}

		else
			$sError = "No Device Registered for Push Notifications.";


		if ($sError != "")
		{
?>
		<div id="Error" style="padding:15px; border:solid 1px #000000;"><?= $sError ?></div>
<?
		}

		else
		{
?>
		<div id="Alert" style="padding:15px; border:solid 1px #000000;">Your Notification has been Sent successfully.</div>
<?
		}

		@curl_close($objCurl);
	}
?>

		<form name="frmNotification" id="frmNotification" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
		<table border="1" bordercolor="#bbbbbb" cellspacing="1" cellpadding="8" width="100%">
		  <tr>
		  	<td width="100">Title</td>
		  	<td><input type="text" name="Title" id="Title" value="<?= (($Title == "") ? "QUONDA® by Triple Tree" : $Title) ?>" size="50" maxlength="100" class="textbox" style="width:98%;" /></td>
		  </tr>

		  <tr>
		  	<td>Summary</td>
		  	<td><input type="text" name="Summary" id="Summary" value="<?= $Summary ?>" size="50" maxlength="200" class="textbox" style="width:98%;" /></td>
		  </tr>

		  <tr valign="top">
		  	<td>Message</td>
		  	<td><textarea name="Message" id="Message" rows="5" cols="50" style="width:98%;"><?= $Message ?></textarea></td>
		  </tr>


		  <tr valign="top">
		  	<td>
		  	  Recipients<br />
		  	  ( <a href="#" onclick="return checkAll( );">ALL</a> | <a href="#" onclick="return clearAll( );">NONE</a> )<br />
		  	</td>

		  	<td>
		  	  <div style="height:200px; overflow-y:scroll;">
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
<?
	$sSQL = "SELECT id, name, email FROM tbl_users WHERE status='A' AND device_id!='' AND user_type='MGF' ORDER BY name";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$iUser  = $objDb->getField($i, "id");
		$sName  = $objDb->getField($i, "name");
		$sEmail = $objDb->getField($i, "email");
?>

				  <tr>
					<td width="25"><input type="checkbox" id="Recipient<?= $iUser ?>" class="recipients" name="Recipients[]" value="<?= $iUser ?>" <?= ((@in_array($iUser, $Recipients)) ? ' checked' : '') ?> /></td>
					<td><label for="Recipient<?= $iUser ?>"><?= $sName ?> &lt;<?= $sEmail ?>&gt;</label></td>
				  </tr>
<?
	}
?>
				</table>
			  </div>
		  	</td>
		  </tr>

		  <tr>
		  	<td></td>
		  	<td><input type="submit" value="Send Notification" onclick="return validateForm( );" /></td>
		  </tr>
		</table>
		</form>
	</div>

	<script type="text/javascript">
	<!--
		function checkAll( )
		{
			var sCheckboxes = $$("input.recipients");

			sCheckboxes.each( function(objElement) { objElement.checked = true; } );


			return false;
		}


		function clearAll( )
		{
			var sCheckboxes = $$("input.recipients");

			sCheckboxes.each( function(objElement) { objElement.checked = false; } );


			return false;
		}

		function validateForm( )
		{
			var objFV = new FormValidator("frmNotification");

			if (!objFV.validate("Title", "B", "Please enter Notification Title."))
				return false;

			if (!objFV.validate("Summary", "B", "Please enter the Notification Summary."))
				return false;

			if (!objFV.validate("Message", "B", "Please enter your Message."))
				return false;

			return true;
		}
	-->
	</script>
</div>

</body>
</html>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>