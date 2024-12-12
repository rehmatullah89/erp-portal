
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

function validateForm( )
{
	var objFV = new FormValidator("frmAccount");
	
	if (!objFV.validate("Name", "B", "Please enter your Full Name."))
		return false;
		
	if (!objFV.validate("Gender", "B", "Please choose your Gender."))
		return false;
		
	if (!objFV.validate("Month", "B", "Please choose your Date of Birth (Month)."))
		return false;
		
	if (!objFV.validate("Day", "B", "Please choose your Date of Birth (Day)."))
		return false;
		
	if (!objFV.validate("Year", "B", "Please choose your Date of Birth (Year)."))
		return false;
		
	if (!isValidDate($F('Day'), $F('Month'), $F('Year')))
	{
		alert("Please select a valid Date of Birth.");
		
		return false;
	}
	
	if (!objFV.validate("City", "B", "Please enter your City."))
		return false;
		
	if (!objFV.validate("Country", "B", "Please select your Country."))
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
		
	if (!objFV.validate("Mobile", "B", "Please enter your Mobile Number."))
		return false;
		
	if (!objFV.validate("MaritalStatus", "B", "Please select your Marital Status."))
		return false;
		
	if (!objFV.validate("BloodGroup", "B", "Please select your Blood Group."))
		return false;

	if (objFV.value("Password") != "")
	{
		if (!objFV.validate("Password", "B,L(5)", "Please enter the valid Password (Min Length = 5)."))
			return false;

		if (!objFV.validate("RetypePassword", "B,L(5)", "Please re-type the correct Password (Min Length = 5)."))
			return false;		

		if (objFV.value("Password") != objFV.value("RetypePassword"))
		{
			alert("The Passwords does not MATCH. Please re-type the correct Password.");

			objFV.focus("RetypePassword");
			objFV.select("RetypePassword");

			return false;
		}
	}
	
	return true;
}
