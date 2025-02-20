
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

function FormValidator(sForm)
{
	this.objForm = document.sForm;

	if (!this.objForm)
		this.objForm = document.getElementById(sForm);

	if (!this.objForm)
		alert("Error: Unable to create the Form Object.");

	this.validate      = validate;
	this.getObject     = getObject;
	this.value         = value;
	this.length        = length;
	this.text          = text;
	this.setValue      = setValue;
	this.select        = select;
	this.focus         = setFocus;
	this.checked       = checked;
	this.unchecked     = unchecked;
	this.enabled       = enabled;
	this.disabled      = disabled;
	this.selectedValue = selectedValue;
	this.selectedIndex = selectedIndex;
	this.valueAtIndex  = valueAtIndex;
	this.setIndex      = setIndex;
	this.reset         = reset;
	this.submit        = submit;
	this.isChecked     = isChecked;
	this.setAction     = setAction;
}

function isChecked(eField)
{
	return this.objForm[eField].checked;
}

function disabled(eField)
{
	this.objForm[eField].disabled = true;
}

function enabled(eField)
{
	this.objForm[eField].disabled = false;
}

function setAction(sAction)
{
	this.objForm.action = sAction;
}

function submit( )
{
	this.objForm.submit( );
}

function reset( )
{
	this.objForm.reset( );
}

function checked(eField)
{
	return this.objForm[eField].checked = true;
}

function unchecked(eField)
{
	return this.objForm[eField].checked = false;
}

function selectedValue(eField)
{
	var iLength = this.objForm[eField].length;

	if (iLength > 1)
	{
		for (var i = 0; i < iLength; i ++)
		{
			if (this.objForm[eField][i].checked == true)
				return this.objForm[eField][i].value;
		}
	}

	else
	{
			if (this.objForm[eField].checked == true)
				return this.objForm[eField].value;
	}

	return "";
}

function selectedIndex(eField)
{
	return this.objForm[eField].selectedIndex;
}

function setIndex(eField, iIndex)
{
	this.objForm[eField].selectedIndex = iIndex;
}

function valueAtIndex(eField, iIndex)
{
	return this.objForm[eField].options[iIndex].value;
}

function text(eField)
{
	return this.objForm[eField].options[this.objForm[eField].selectedIndex].text;
}

function value(eField)
{
	return this.objForm[eField].value;
}

function length(eField)
{
	return this.objForm[eField].value.length;
}

function getObject(eField)
{
	return this.objForm[eField];
}

function setValue(eField, sValue)
{
	this.objForm[eField].value = sValue;
}

function select(eField)
{
	this.objForm[eField].select( );
}

function setFocus(eField)
{
	this.objForm[eField].focus( );
}

////////////////////// Input Checks
//  B = BLANK
//  C = ALPHABETS
//  N = NUMBER
//  E = EMAIL
//  F = FLOATING NUMBER
//  S = SIGNED
//  U = URL
//  L(N) = Length (Minium)

function validate(eField, sChecks, sMsg)
{
	sChecks = trim(sChecks);

	var sCheckOptions = new Array( );

	var i = 0;
	var iLength = 0;
	var bSigned = false;

	while (sChecks != "")
	{
 		var sTemp = "";

 		if (sChecks.indexOf(',') == -1)
		{
 			sTemp = sChecks;

 			sChecks = "";
 		}

 		else
 		{
 			sTemp = sChecks.substring(0, sChecks.indexOf(','));

 			sChecks = sChecks.substring(sChecks.indexOf(',') + 1);
		}

 		if (sTemp.charAt(0) == "L")
 		{
 			iLength = parseInt(sTemp.substring(2, (sTemp.length - 1)));

 			sTemp = "L";
 		}

 		else if (sTemp.charAt(0) == "S")
 		{
 			bSigned = true;

 			continue;
 		}


		sCheckOptions.push(sTemp);
	}

	for (var i = 0; i < sCheckOptions.length; i ++)
	{
		switch(sCheckOptions[i])
		{
			case "B" : if (trim(this.objForm[eField].value) == "")
			           {
			           	alert(sMsg);

			           	this.objForm[eField].focus( );

			           	return false;
			           }

			           break;


			case "C" : if (!validateAlphabetFormat(this.objForm[eField].value))
			           {
			           	alert(sMsg);

			           	this.objForm[eField].focus( );
			           	this.objForm[eField].select( );

			           	return false;
			           }

			           break;


			case "N" : if (!validateNumberFormat(this.objForm[eField].value, bSigned, false))
			           {
			           	alert(sMsg);

			           	this.objForm[eField].focus( );
			           	this.objForm[eField].select( );

			           	return false;
			           }

			           break;


			case "F" : if (!validateNumberFormat(this.objForm[eField].value, bSigned, true))
			           {
			           	alert(sMsg);

			           	this.objForm[eField].focus( );
			           	this.objForm[eField].select( );

			           	return false;
			           }

			           break;


			case "E" : if (!validateEmailFormat(this.objForm[eField].value))
				   {
			           	alert(sMsg);

			           	this.objForm[eField].focus( );
			           	this.objForm[eField].select( );

			           	return false;
			           }

			           break;


			case "L" : if (this.objForm[eField].value.length < iLength)
				   {
			           	alert(sMsg);

			           	this.objForm[eField].focus( );
			           	this.objForm[eField].select( );

			           	return false;
			           }

			           break;


			case "U" : if (!validateUrlFormat(this.objForm[eField].value))
				   {
			           	alert(sMsg);

			           	this.objForm[eField].focus( );
			           	this.objForm[eField].select( );

			           	return false;
			           }

			           break;
		}
	}

	return true;
}

function trim(sValue)
{
	return sValue.replace(/^\s+|\s+$/g, "");
}


function validateEmailFormat(sEmail)
{
	var iLength = sEmail.length;

	if (iLength == 0)
		return true;

	if (iLength < 5)
		return false;

	var sValidChars = "abcdefghijklmnopqrstuvwxyz0123456789@.-_";

	for (var i = 0; i < iLength; i++)
	{
		var sLetter = sEmail.charAt(i).toLowerCase( );

		if (sValidChars.indexOf(sLetter) != -1)
			continue;

		return false;
	}

	var iPosition = sEmail.indexOf('@');

	if (iPosition == -1 || iPosition == 0)
		return false;

	var sFirstPart = sEmail.substring(0, iPosition);

	sEmail = sEmail.substring((iPosition + 1));

	iPosition = sEmail.indexOf('.');

	if (iPosition == -1 || iPosition == 0)
		return false;

	var sSecondPart = sEmail.substring(0, iPosition);

	var sThirdPart = sEmail.substring((iPosition + 1));

	if(sSecondPart.indexOf('@') != -1 || sSecondPart.indexOf('_') != -1)
		return false;

	if(sThirdPart.indexOf('@') != -1 || sThirdPart.indexOf('_') != -1 || sThirdPart.indexOf('-') != -1 || sThirdPart.length < 2)
		return false;

	return true;
}


function validateAlphabetFormat(sText)
{
	var iLength = sText.length;

	if (iLength == 0)
		return true;

	var sValidChars = "abcdefghijklmnopqrstuvwxyz. ";

	for (var i = 0; i < iLength; i++)
	{
		var sLetter = sText.charAt(i).toLowerCase( );

		if (sValidChars.indexOf(sLetter) != -1)
			continue;

		return false;
	}

	return true;
}


function validateNumberFormat(sNumber, bSigned, bFraction)
{
	var sValidCharacters = "0123456789";
	var i = 0;

	if (bSigned == true)
	{
		if (sNumber.charAt(0) == "-")
			i ++;
	}

	if (bFraction == true)
	{
		if (sNumber.indexOf(".") != sNumber.lastIndexOf("."))
			return false;

		sValidCharacters += ".";
	}

	for (; i < sNumber.length; i ++)
	{
		if (sValidCharacters.indexOf(sNumber.charAt(i)) == -1)
			return false;
	}

	return true;
}

function validateUrlFormat(sUrl)
{
	var sRegExp = /^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/;

	if(sRegExp.test(sUrl))
		return true;

	return false;
}

function isValidDate(iDay, iMonth, iYear)
{
   if (iDay == 31 && (iMonth == 4 || iMonth == 6 || iMonth == 9 || iMonth == 11))
      return false;

   else if (iMonth == 2)
   {
      iMaxDays = ((iYear%4 == 0 && (iYear% 100 != 0 || iYear%400 == 0)) ? 29 : 28);

      if (iDay > iMaxDays)
         return false;
   }

   return true;
}

function checkImage(sFile)
{
	var iDotPosition = sFile.lastIndexOf(".");

	if (iDotPosition == -1)
		return false;

	var sExtension = sFile.substring((iDotPosition + 1)).toLowerCase( );

	if (sExtension != "jpg" && sExtension != "jpeg" && sExtension != "gif" && sExtension != "png")
		return false;

	return true;
}

function checkVideo(sFile)
{
	var iDotPosition = sFile.lastIndexOf(".");

	if (iDotPosition == -1)
		return false;

	var sExtension = sFile.substring((iDotPosition + 1)).toLowerCase( );

	if (sExtension != "flv" && sExtension != "mpg" && sExtension != "mp4" && sExtension != "mpeg")
		return false;

	return true;
}

function checkExcelFile(sFile)
{
	var iDotPosition = sFile.lastIndexOf(".");

	if (iDotPosition == -1)
		return false;

	var sExtension = sFile.substring((iDotPosition + 1)).toLowerCase( );

	if (sExtension != "xlsx" && sExtension != "xls")
		return false;

	return true;
}

function checkCsvFile(sFile)
{
	var iDotPosition = sFile.lastIndexOf(".");

	if (iDotPosition == -1)
		return false;

	var sExtension = sFile.substring((iDotPosition + 1)).toLowerCase( );

	if (sExtension != "csv")
		return false;

	return true;
}

function checkPdfFile(sFile)
{
	var iDotPosition = sFile.lastIndexOf(".");

	if (iDotPosition == -1)
		return false;

	var sExtension = sFile.substring((iDotPosition + 1)).toLowerCase( );

	if (sExtension != "pdf")
		return false;

	return true;
}

function checkFlvFile(sFile)
{
	var iDotPosition = sFile.lastIndexOf(".");

	if (iDotPosition == -1)
		return false;

	var sExtension = sFile.substring((iDotPosition + 1)).toLowerCase( );

	if (sExtension != "flv")
		return false;

	return true;
}

function checkXmlFile(sFile)
{
	var iDotPosition = sFile.lastIndexOf(".");

	if (iDotPosition == -1)
		return false;

	var sExtension = sFile.substring((iDotPosition + 1)).toLowerCase( );

	if (sExtension != "xml")
		return false;

	return true;
}

function checkPptFile(sFile)
{
	var iDotPosition = sFile.lastIndexOf(".");

	if (iDotPosition == -1)
		return false;

	var sExtension = sFile.substring((iDotPosition + 1)).toLowerCase( );

	if (sExtension != "ppt" && sExtension != "pptx")
		return false;

	return true;
}