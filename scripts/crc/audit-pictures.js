
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

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

	if (!objFV.validate("Category", "B", "Please select the Category."))
		return false;

	if (!objFV.validate("Question", "B", "Please select the Question."))
		return false;

	if (!objFV.validate("AuditDate", "B", "Please select the Audit Date."))
		return false;

	if (!objFV.validate("Title", "B", "Please enter the Picture Title."))
		return false;

	if (!objFV.validate("Picture", "B", "Please select the Picture."))
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


function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;

	if (!objFV.validate("Category", "B", "Please select the Category."))
		return false;

	if (!objFV.validate("Question", "B", "Please select the Question."))
		return false;

	if (!objFV.validate("AuditDate", "B", "Please select the Audit Date."))
		return false;

	if (!objFV.validate("Title", "B", "Please enter the Picture Title."))
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


function getQuestionsList(sId)
{
	clearList($("Question" + sId));

	var iCategoryId = $F("Category" + sId);

	if (iCategoryId == "")
		return;

	$("Question" + sId).disable( );


	var sUrl    = "ajax/crc/get-safety-questions.php";
	var sParams = ("Category=" + iCategoryId + "&Id=" + sId);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getQuestionsList });
}


function _getQuestionsList(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			for (var i = 2; i < sParams.length; i ++)
			{
				var sOption = sParams[i].split("||");

				$("Question" + sParams[1]).options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);
			}

			$("Question" + sParams[1]).enable( );
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}