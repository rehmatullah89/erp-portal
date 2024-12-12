
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

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("Category", "B", "Please select the Category."))
		return false;

	if (!objFV.validate("Question", "B", "Please enter the Question."))
		return false;

	if (!objFV.validate("QuestionType", "B", "Please select the Question Type."))
		return false;

	if (!objFV.validate("NoOfOptions", "B", "Please select the No of Options."))
		return false;

	var iQuestionType = parseInt($F('QuestionType'));
	var iNoOfOptions  = parseInt($F('NoOfOptions'));

	for (var i = 0; i < 4; i ++)
	{
		if (i < iNoOfOptions)
		{
			if (!objFV.validate(("Option" + i), "B", "Please enter the Option Label."))
				return false;

			if (iQuestionType == 1)
			{
				if (!objFV.validate(("Weightage" + i), "B", "Please select the Option Weightage."))
					return false;
			}
		}
	}

	return true;
}

function updateOptions(sIndex)
{
	var iQuestionType = parseInt($F('QuestionType' + sIndex));
	var iNoOfOptions  = parseInt($F('NoOfOptions' + sIndex));

	for (var i = 0; i < 4; i ++)
	{
		if (i < iNoOfOptions)
			$("Option" + sIndex + i).show( );

		else
			$("Option" + sIndex + i).hide( );


		if (iQuestionType == 1)
		{
			$("lblWeightage" + sIndex + i).show( );
			$("Weightage" + sIndex + i).show( );
		}

		else
		{
			$("lblWeightage" + sIndex + i).hide( );
			$("Weightage" + sIndex + i).hide( );
		}
	}
}


function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);


	if (!objFV.validate("Category", "B", "Please select the Category."))
		return false;

	if (!objFV.validate("Question", "B", "Please enter the Question."))
		return false;

	if (!objFV.validate("QuestionType", "B", "Please select the Question Type."))
		return false;

	if (!objFV.validate("NoOfOptions", "B", "Please select the No of Options."))
		return false;


	var iQuestionType = parseInt($F('QuestionType_' + iId + '_'));
	var iNoOfOptions  = parseInt($F('NoOfOptions_' + iId + '_'));

	for (var i = 0; i < 4; i ++)
	{
		if (i < iNoOfOptions)
		{
			if (!objFV.validate(("Option_" + iId + "_" + i), "B", "Please enter the Option Label."))
				return false;

			if (iQuestionType == 1)
			{
				if (!objFV.validate(("Weightage_" + iId + "_" + i), "B", "Please select the Option Weightage."))
					return false;
			}
		}
	}


	$('Processing').show( );

	var sUrl    = "ajax/crc/update-production-question.php";
	var sParams = $('frmData' + iId).serialize( );

	var objForm = $("frmData" + iId);
	objForm.disable( );

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_updateData });
}

function _updateData(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');
		var iId     = sParams[1];

		if (sParams[0] == "OK")
		{
			$('Msg' + iId).innerHTML = sParams[2];
			$('Msg' + iId).show( );
			$('Edit' + iId).hide( );

			setTimeout(
				    function( )
				    {
						new Effect.SlideUp("Msg" + iId);

						$('Question' + iId).innerHTML = sParams[3];
						$('Category' + iId).innerHTML = sParams[4];
				    },

				    2000
				  );
		}

		else if (sParams[0] == "INFO")
			_showError(sParams[2]);

		else
			_showError(sParams[1]);

		$('Processing').hide( );

		var objForm = $("frmData" + iId);
		objForm.enable( );
	}

	else
		_showError( );
}