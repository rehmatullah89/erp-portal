
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

function UpdateTotal( )
{
	var iTotal      = 0;
	var iGrandTotal = 0;

	var iRows    = parseInt($('ColorsCount').value);
	var iColumns = parseInt($('SizesCount').value);
	
	
	for (var i = 0; i < iRows; i ++)
	{
		iTotal = 0;

		for (var j = 0; j < iColumns; j ++)
		{
			if ($F('Quantity' + i + '_' + j) != "")
				iTotal += parseFloat($F('Quantity' + i + '_' + j));
		}
		
		$('Total' + i).innerHTML = iTotal;
		
		iGrandTotal += iTotal;
	}

	$('GrandTotal').innerHTML = iGrandTotal;
}
