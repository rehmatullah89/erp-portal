
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

function showGraph(sGraphId)
{
	$($('LastGraphId').value).hide( );
	$(sGraphId).show( );

	$('LastGraphId').value = sGraphId;
}

function validateNotificationForm( )
{
	var objFV = new FormValidator("frmNotification");

	if (!objFV.validate("Department", "B", "Please select the Department."))
		return false;

	if (!objFV.validate("Trigger", "B", "Please select the Notification Trigger."))
		return false;

	if (objFV.value("Vendor") == "" && objFV.value("Brand") == "")
	{
		alert("Please select Vendor, Brand or both.");

		return false;
	}

	var sCheckboxes = $$("input.alerts");
	var bFlag       = false;

	for (var i = 0; i < sCheckboxes.length; i ++)
	{
		if (sCheckboxes[i].checked == true)
		{
			bFlag = true;
			break;
		}
	}

	if (bFlag == false)
	{
		alert("Please select the Alert Types.");

		return false;
	}

	return true;
}

function validateContactForm( )
{
	var objFV = new FormValidator("frmContact");

	if (!objFV.validate("To", "B", "Please select the Message Recipient."))
		return false;

	if (!objFV.validate("Subject", "B", "Please enter the Message Subject."))
		return false;

	if (!objFV.validate("Message", "B", "Please enter your Message."))
		return false;

	return true;
}


function getTriggers(sListId)
{
	clearList($('Trigger' + sListId));

	var iId = $F("Department" + sListId);

	if (iId == "")
		return;

	$("Trigger" + sListId).disable( );

	var sUrl    = "ajax/hr/get-triggers.php";
	var sParams = ("Id=" + iId + "&ListId=" + sListId);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getTriggers });
}

function _getTriggers(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			var sListId = sParams[1];

			for (var i = 2; i < sParams.length; i ++)
			{
				var sOption = sParams[i].split("||");

				$('Trigger' + sListId).options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);

			}

			$('Trigger' + sListId).enable( );
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}

function validateEditNotificationForm(iId)
{
	var objFV = new FormValidator("frmNotification" + iId);

	if (!objFV.validate("Department", "B", "Please select the Department."))
		return false;

	if (!objFV.validate("Trigger", "B", "Please select the Notification Trigger."))
		return false;

	if (objFV.value("Vendor") == "" && objFV.value("Brand") == "")
	{
		alert("Please select Vendor, Brand or both.");

		return false;
	}

	var sCheckboxes = $$("input.alerts" + iId);
	var bFlag       = false;

	for (var i = 0; i < sCheckboxes.length; i ++)
	{
		if (sCheckboxes[i].checked == true)
		{
			bFlag = true;
			break;
		}
	}

	if (bFlag == false)
	{
		alert("Please select the Alert Types.");

		return false;
	}

	$('Processing').show( );

	var sUrl    = "ajax/hr/update-notification.php";
	var sParams = $('frmNotification' + iId).serialize( );

	var objForm = $("frmNotification" + iId);
	objForm.disable( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_updateNotificationData });
}

function _updateNotificationData(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		var iId     = sParams[1];

		if (sParams[0] == "OK")
		{
			$('Msg' + iId).innerHTML = sParams[2];
			$('Msg' + iId).show( );
			$('NotificationEdit' + iId).hide( );

			setTimeout(
				    function( )
				    {
					new Effect.SlideUp("Msg" + iId);

					$('Department_' + iId).innerHTML = sParams[3];
					$('Trigger_' + iId).innerHTML    = sParams[4];
					$('Vendor' + iId).innerHTML      = sParams[5];
					$('Brand' + iId).innerHTML       = sParams[6];
					$('AlertTypes' + iId).innerHTML  = sParams[7];
				    },

				    2000
				  );
		}

		else if (sParams[0] == "INFO")
			_showError(sParams[2]);

		else
			_showError(sParams[1]);

		$('Processing').hide( );

		var objForm = $("frmNotification" + iId);
		objForm.enable( );
	}

	else
		_showError( );
}


function validateAlbumForm( )
{
	var objFV = new FormValidator("frmAlbum");

	if (!objFV.validate("Album", "B", "Please enter the Album Title."))
		return false;

	if (objFV.value("Picture") != "")
	{
		if (!checkImage(objFV.value("Picture")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Picture");
			objFV.select("Picture");

			return false;
		}
	}

	return true;
}

function validateEditAlbumForm(iId)
{
	var objFV = new FormValidator("frmAlbum" + iId);

	if (!objFV.validate("Album", "B", "Please enter the Album Title."))
		return false;

	if (objFV.value("Picture") != "")
	{
		if (!checkImage(objFV.value("Picture")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Picture");
			objFV.select("Picture");

			return false;
		}
	}

	return true;
}


function validatePhotoForm( )
{
	var objFV = new FormValidator("frmPhoto");
	var bFlag = false;

	if (!objFV.validate("Album", "B", "Please select the Photo Album."))
		return false;

	for (var i = 1; i <= 5; i ++)
	{
		if (objFV.value("Caption" + i) != "" || objFV.value("Picture" + i) != "")
		{
			if (!objFV.validate(("Caption" + i), "B", "Please enter the Photo Title."))
				return false;

			if (!objFV.validate(("Picture" + i), "B", "Please select the Photo."))
				return false;

			if (objFV.value("Picture" + i) != "")
			{
				if (!checkImage(objFV.value("Picture" + i)))
				{
					alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

					objFV.focus("Picture" + i);
					objFV.select("Picture" + i);

					return false;
				}
			}

			bFlag = true;
		}
	}

	if (bFlag == false)
	{
		alert("Please select atleast one Photo to Upload.");

		objFV.focus("Caption1");

		return false;
	}

	return true;
}

function validateEditPhotoForm(iId)
{
	var objFV = new FormValidator("frmPhoto" + iId);

	if (!objFV.validate("Album", "B", "Please select the Photo Album."))
		return false;

	if (!objFV.validate("Caption", "B", "Please enter the Photo Title."))
		return false;

	if (objFV.value("Picture") != "")
	{
		if (!checkImage(objFV.value("Picture")))
		{
			alert("Invalid File Format. Please select an image file of type jpg, gif or png.");

			objFV.focus("Picture");
			objFV.select("Picture");

			return false;
		}
	}

	return true;
}


function validateScheduleForm( )
{
	var objFV = new FormValidator("frmSchedule");

	if (!objFV.validate("Location", "B", "Please select the Location."))
		return false;

	if (!objFV.validate("StartHour", "B", "Please select the Start Time (Hour)."))
		return false;

	if (!objFV.validate("StartMinutes", "B", "Please select the Start Time (Minutes)."))
		return false;

	if (!objFV.validate("EndHour", "B", "Please select the End Time (Hour)."))
		return false;

	if (!objFV.validate("EndMinutes", "B", "Please select the End Time (Minutes)."))
		return false;

	if (!objFV.validate("Details", "B", "Please enter the Task Details."))
		return false;

	return true;
}

function validateEditScheduleForm(iId)
{
	var objFV = new FormValidator("frmSchedule" + iId);

	if (!objFV.validate("Location", "B", "Please select the Location."))
		return false;

	if (!objFV.validate("StartHour", "B", "Please select the Start Time (Hour)."))
		return false;

	if (!objFV.validate("StartMinutes", "B", "Please select the Start Time (Minutes)."))
		return false;

	if (!objFV.validate("EndHour", "B", "Please select the End Time (Hour)."))
		return false;

	if (!objFV.validate("EndMinutes", "B", "Please select the End Time (Minutes)."))
		return false;

	if (!objFV.validate("Details", "B", "Please enter the Task Details."))
		return false;

	$('Processing').show( );

	var sUrl    = "ajax/hr/update-schedule.php";
	var sParams = $('frmSchedule' + iId).serialize( );

	var objForm = $("frmSchedule" + iId);
	objForm.disable( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_updateScheduleData });
}

function _updateScheduleData(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		var iId     = sParams[1];

		if (sParams[0] == "OK")
		{
			$('Msg' + iId).innerHTML = sParams[2];
			$('Msg' + iId).show( );
			$('ScheduleEdit' + iId).hide( );

			setTimeout(
				    function( )
				    {
					new Effect.SlideUp("Msg" + iId);

					$('Location' + iId).innerHTML = sParams[3];
					$('Details' + iId).innerHTML  = sParams[4];
					$('Date' + iId).innerHTML     = sParams[5];
					$('Time' + iId).innerHTML     = sParams[6];
				    },

				    2000
				  );
		}

		else
		{
			_showError(sParams[1]);
			iId = sParams[2];
		}

		$('Processing').hide( );

		var objForm = $("frmSchedule" + iId);
		objForm.enable( );
	}

	else
		_showError( );
}