
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

function loadOptions(sQuestionType, sSelectedBlock, sOptionIds, sValidation)
{
	var sBlockIds = sOptionIds.split(",");
	var iLength   = sBlockIds.length;
	
	for (var i = 0; i < iLength; i++)
		$(sBlockIds[i]).hide( );
	
	$(sSelectedBlock).show( );
	
	
	iLength = $(sValidation).options.length;
	
	for (var iIndex = (iLength - 1); iIndex > 0; iIndex --)
		$(sValidation).options[iIndex] = null;
	
	if (sQuestionType == "Mcq")
	{
		$(sValidation).options[1] = new Option("Must Select", "S", false, false);
	}
	
	else if (sQuestionType == "Open")
	{
		$(sValidation).options[1] = new Option("Numeric", "N", false, false);
		$(sValidation).options[2] = new Option("Alpha Numeric", "A", false, false);
		$(sValidation).options[3] = new Option("Email Address", "E", false, false);
	}
	
	else if (sQuestionType == "Matrix")
	{
		$(sValidation).options[1] = new Option("Must Select", "S", false, false);
		$(sValidation).options[2] = new Option("Numeric", "N", false, false);
		$(sValidation).options[3] = new Option("Alpha Numeric", "A", false, false);
		$(sValidation).options[4] = new Option("Email Address", "E", false, false);
	}
}

function setMessage(sValidation, sMessage)
{
	if (sValidation != "")
		$(sMessage).disabled = false;
	
	else
	{
		$(sMessage).disabled = true;
		$(sMessage).value    = "";
	}
}

function validateForm( )
{
	var objFV = new FormValidator("frmData");

	if (!objFV.validate("QuestionType", "B", "Please select the Question Type."))
		return false;
		
	if (objFV.value("QuestionType") == "Mcq")
	{
		if (!objFV.validate("McqType", "B", "Please select the Answer Type."))
			return false;		

		if (!objFV.validate("McqQuestion", "B", "Please enter the Question Text."))
			return false;
			
		if (!objFV.validate("McqChoices", "B", "Please enter the Answer Choices (One Per Line)."))
			return false;
	}
	
	if (objFV.value("QuestionType") == "Open")
	{
		if (!objFV.validate("OpenType", "B", "Please select the Answer Type."))
			return false;		

		if (!objFV.validate("OpenQuestion", "B", "Please enter the Question Text."))
			return false;
	}
	
	if (objFV.value("QuestionType") == "Matrix")
	{
		if (!objFV.validate("MatrixType", "B", "Please select the Answer Type."))
			return false;		

		if (!objFV.validate("MatrixQuestion", "B", "Please enter the Question Text."))
			return false;
			
		if (!objFV.validate("MatrixColumns", "B", "Please enter the Matrix Column Headings (One Per Line)."))
			return false;
			
		if (!objFV.validate("MatrixRows", "B", "Please enter the Matrix Row Headings (One Per Line)."))
			return false;
	}

	if (objFV.value("Validation") != "")
	{
		if (!objFV.validate("Message", "B", "Please enter the Validation Message."))
			return false;
	}

	return true;
}

function validateEditForm(iId)
{
	var objFV = new FormValidator("frmData" + iId);

	if (!objFV.validate("QuestionType", "B", "Please select the Question Type."))
		return false;
		
	if (objFV.value("QuestionType") == "Mcq")
	{
		if (!objFV.validate("McqType", "B", "Please select the Answer Type."))
			return false;		

		if (!objFV.validate("McqQuestion", "B", "Please enter the Question Text."))
			return false;
			
		if (!objFV.validate("McqChoices", "B", "Please enter the Answer Choices (One Per Line)."))
			return false;
	}
	
	if (objFV.value("QuestionType") == "Open")
	{
		if (!objFV.validate("OpenType", "B", "Please select the Answer Type."))
			return false;		

		if (!objFV.validate("OpenQuestion", "B", "Please enter the Question Text."))
			return false;
	}
	
	if (objFV.value("QuestionType") == "Matrix")
	{
		if (!objFV.validate("MatrixType", "B", "Please select the Answer Type."))
			return false;		

		if (!objFV.validate("MatrixQuestion", "B", "Please enter the Question Text."))
			return false;
			
		if (!objFV.validate("MatrixColumns", "B", "Please enter the Matrix Column Headings (One Per Line)."))
			return false;
			
		if (!objFV.validate("MatrixRows", "B", "Please enter the Matrix Row Headings (One Per Line)."))
			return false;
	}

	if (objFV.value("Validation") != "")
	{
		if (!objFV.validate("Message", "B", "Please enter the Validation Message."))
			return false;
	}
		
	$('Processing').show( );
	
	var sUrl    = "ajax/hr/update-survey-question.php"; 
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

					$('Q' + iId).innerHTML = sParams[3];
				    },				    
				    
				    2000
				  );
		}
			
		else
			_showError(sParams[1]);
			
		$('Processing').hide( );
		
		var objForm = $("frmData" + iId); 
		objForm.enable( );
	}
	
	else
		_showError( );
}