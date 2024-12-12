
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

function getStylesList(sParent, sList)
{
	clearList($(sList));

	var iBrandId = $F(sParent);

	if (iBrandId == "")
		return;

	$(sList).disable( );


	var sUrl    = "ajax/pcc/get-styles.php";
	var sParams = ("Brand=" + iBrandId + "&List=" + sList);

	new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getStylesList });
}


function _getStylesList(sResponse)
{
	if (sResponse.status == 200 && sResponse.statusText == "OK")
	{
		var sParams = sResponse.responseText.split('|-|');

		if (sParams[0] == "OK")
		{
			var sList = sParams[1];

			for (var i = 2; i < sParams.length; i ++)
			{
				var sOption = sParams[i].split("||");

				$(sList).options[(i - 1)] = new Option(sOption[1], sOption[0], false, false);
			}

			$(sList).enable( );
		}

		else
			_showError(sParams[1]);
	}

	else
		_showError( );
}

function validateForm( )
{
	var objFV = new FormValidator("frmData");

        if (!objFV.validate("ProductName", "B", "Please enter the Product Name."))
		return false;
            
        if (!objFV.validate("ProductCode", "B", "Please enter the Product Code."))
		return false;    
            
        if (!objFV.validate("PStatus", "B", "Please enter the Product Status."))
		return false;
            
	if (!objFV.validate("Brand", "B", "Please select the Brand."))
		return false;

        if (!objFV.validate("StyleNo", "B", "Please enter the Style."))
		return false;
        
        if (!objFV.validate("Season", "B", "Please enter the Season."))
		return false;
            
	if (!objFV.validate("Vendor", "B", "Please select the Vendor."))
		return false;
            
        /*if (!objFV.validate("Gender", "B", "Please select the Gender."))
		return false;*/
            
        if (!objFV.validate("Price", "B", "Please enter the Price."))
		return false;
            
        if (!objFV.validate("FabricType", "B", "Please enter the Fabric Type."))
		return false;    

        if (!objFV.validate("Category", "B", "Please select the Fabric Class."))
		return false;
            
        if(objFV.value("Category") == 4 || objFV.value("Category") == 6)
        {           
            if (!objFV.validate("FabricWeightUnit", "B", "Please select the Fabric Weight Unit."))
		return false;
            
            if(objFV.value("FabricWeightUnit") == 'gm')
            {
                if (!objFV.validate("FabricWeightGm", "B", "Please select the Fabric Weight in grams."))
                    return false;
            }
            else
            {
                if (!objFV.validate("FabricWeightOz", "B", "Please select the Fabric Weight in ounces."))
                    return false;
            }
            
            if (!objFV.validate("Color", "B", "Please select the Fabric Color."))
		return false;
            
            if (objFV.value("Category") == 6 && !objFV.validate("Weave", "B", "Please enter the Weave."))
		return false;
        }
            
        if (!objFV.validate("ProductDetails", "B", "Please enter the Product Details."))
		return false;
            
        if (!objFV.validate("ProductTags", "B", "Please enter the Product Tags."))
		return false;
            
        if (!objFV.validate("Content", "B", "Please enter the Product Content."))
		return false;   
            
        if (!objFV.validate("Status", "B", "Please select the Product Status."))
		return false;
            
	return true;
}

function addPicture( )
{
	var iCount = parseInt($('Count').value);

	if (iCount < 20)
	{
		iCount ++;

		Effect.SlideDown('PictureBox' + iCount);

		$('Count').value = iCount;
	}
}


function deletePicture( )
{
	var iCount = parseInt($('Count').value);
	var iMax   = parseInt($('Max').value);

	if (iCount > iMax)
	{
		Effect.SlideUp('PictureBox' + iCount);

		iCount --;

		$('Count').value = iCount;
	}
}

function makeImportantField(Field, FieldValue)
{
    if(Field == 'FabricWeightUnitId')
    {
        if(FieldValue == 4 || FieldValue == 6)
        {
            document.getElementById(Field).style.display = '';
            document.getElementById("ColorId").style.display = '';
        }
        else
        {
            document.getElementById(Field).style.display = 'none'; 
            document.getElementById("ColorId").style.display = 'none';
        }
    }
    
    if(Field == 'FabricWeightUnit')
    {
        if(FieldValue == 'gm')
        {
            document.getElementById("FabricWeightGmId").style.display = '';
            document.getElementById("FabricWeightOzId").style.display = 'none';
            document.getElementById("FabricWeightMmId").style.display = 'none';
        }
        else if(FieldValue == 'Oz')
        {
            document.getElementById("FabricWeightGmId").style.display = 'none';
            document.getElementById("FabricWeightMmId").style.display = 'none';
            document.getElementById("FabricWeightOzId").style.display = '';
        }
        else
        {
            document.getElementById("FabricWeightGmId").style.display = 'none';
            document.getElementById("FabricWeightOzId").style.display = 'none';
            document.getElementById("FabricWeightMmId").style.display = '';
        }
    }
    
}