
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
var SampleSize;
var DefectsAllowed;

function getAqlInfo()
{
        var AQL = document.getElementById("AQL");
        var AQLVal  = AQL.options[AQL.selectedIndex].value;

        var LotSize = document.getElementById("LotSize");
        var LotVal  = LotSize.options[LotSize.selectedIndex].value;
        
        var InspecLevel = document.getElementById("InspecLevel");
        var InspecLevelVal  = InspecLevel.options[InspecLevel.selectedIndex].value;
        
        //InspecLevel = 1 
        if(InspecLevelVal == 1)
        {
            DefectsAllowed = 0;
            if(LotVal == 8 || LotVal == 15)
                SampleSize = 2;
            else if(LotVal == 25 && AQLVal == '6.5')
            {
                DefectsAllowed = 1;
                SampleSize = 3;
            }
            else if(LotVal == 25)
                SampleSize = 3;
            else if((LotVal == 50 || LotVal == 90) && (AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 1;
                SampleSize = 5;
            }
            else if(LotVal == 50 || LotVal == 90)
                SampleSize = 5;
            else if(LotVal == 150 && (AQLVal == '2.5' || AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 1;
                SampleSize = 8;
            }
            else if(LotVal == 150)
                SampleSize = 8;
            else if(LotVal == 280 && (AQLVal == '1.5' || AQLVal == '2.5' || AQLVal == '4.0'))
            {
                DefectsAllowed = 1;
                SampleSize = 13;
            }
            else if(LotVal == 280 && AQLVal == '6.5')
            {
                DefectsAllowed = 2;
                SampleSize = 13;
            }
            else if(LotVal == 280)
                SampleSize = 13;
            else if(LotVal == 500 && AQLVal == '6.5')
            {
                DefectsAllowed = 3;
                SampleSize = 20;
            }
            else if(LotVal == 500 && AQLVal == '4.0')
            {
                DefectsAllowed = 2;
                SampleSize = 20;
            }
            else if(LotVal == 500 && (AQLVal == '1.0' || AQLVal == '1.5' || AQLVal == '2.5'))
            {
                DefectsAllowed = 1;
                SampleSize = 20;
            }
            else if(LotVal == 500)
                SampleSize = 20;
            else if(LotVal == 1200 && (AQLVal == '0.65' || AQLVal == '1.0' || AQLVal == '1.5'))
            {
                DefectsAllowed = 1;
                SampleSize = 32;
            }
            else if(LotVal == 1200 && AQLVal == '2.5')
            {
                DefectsAllowed = 2;
                SampleSize = 32;
            }
            else if(LotVal == 1200 && AQLVal == '4.0')
            {
                DefectsAllowed = 3;
                SampleSize = 32;
            }
            else if(LotVal == 1200 && AQLVal == '6.5')
            {
                DefectsAllowed = 5;
                SampleSize = 32;
            }
            else if(LotVal == 1200)
                SampleSize = 32;
            else if(LotVal == 3200 && (AQLVal == '0.40' || AQLVal == '0.65' || AQLVal == '1.0'))
            {
                DefectsAllowed = 1;
                SampleSize = 50;            
            }
            else if(LotVal == 3200 && AQLVal == '1.5')
            {
                DefectsAllowed = 2;
                SampleSize = 50;            
            }
            else if(LotVal == 3200 && AQLVal == '2.5')
            {
                DefectsAllowed = 3;
                SampleSize = 50;            
            }
            else if(LotVal == 3200 && AQLVal == '4.0')
            {
                DefectsAllowed = 5;
                SampleSize = 50;            
            }
            else if(LotVal == 3200 && AQLVal == '6.5')
            {
                DefectsAllowed = 7;
                SampleSize = 50;            
            }
            else if(LotVal == 3200)
                SampleSize = 50;                        
            else if(LotVal == 10000 && AQLVal == '6.5')
            {
                DefectsAllowed = 10;
                SampleSize = 80;
            }
            else if(LotVal == 10000 && AQLVal == '4.0')
            {
                DefectsAllowed = 7;
                SampleSize = 80;
            }
            else if(LotVal == 10000 && AQLVal == '2.5')
            {
                DefectsAllowed = 5;
                SampleSize = 80;
            }
            else if(LotVal == 10000 && AQLVal == '1.5')
            {
                DefectsAllowed = 3;
                SampleSize = 80;
            }
            else if(LotVal == 10000 && AQLVal == '1.0')
            {
                DefectsAllowed = 2;
                SampleSize = 80;
            }
            else if(LotVal == 10000 && (AQLVal == '0.25' || AQLVal == '0.40' || AQLVal == '0.65'))
            {
                DefectsAllowed = 1;
                SampleSize = 80;
            }
            else if(LotVal == 10000)
                SampleSize = 80;            
            else if(LotVal == 35000 && (AQLVal == '0.065' || AQLVal == '0.10'))
                SampleSize = 125;
            else if(LotVal == 35000 && (AQLVal == '0.15' || AQLVal == '0.25' || AQLVal == '0.40'))
            {
                DefectsAllowed = 1;
                SampleSize = 125;
            }
            else if(LotVal == 35000 && AQLVal == '0.65')
            {
                DefectsAllowed = 2;
                SampleSize = 125;
            }
            else if(LotVal == 35000 && AQLVal == '1.0')
            {
                DefectsAllowed = 3;
                SampleSize = 125;
            }
            else if(LotVal == 35000 && AQLVal == '1.5')
            {
                DefectsAllowed = 5;
                SampleSize = 125;
            }
            else if(LotVal == 35000 && AQLVal == '2.5')
            {
                DefectsAllowed = 7;
                SampleSize = 125;
            }
            else if(LotVal == 35000 && AQLVal == '4.0')
            {
                DefectsAllowed = 10;
                SampleSize = 125;
            }
            else if(LotVal == 35000 && AQLVal == '6.5')
            {
                DefectsAllowed = 14;
                SampleSize = 125;
            }
            else if(LotVal == 150000 && AQLVal == '6.5')
            {
                DefectsAllowed = 21;
                SampleSize = 200;
            }
            else if(LotVal == 150000 && AQLVal == '4.0')
            {
                DefectsAllowed = 14;
                SampleSize = 200;
            }
            else if(LotVal == 150000 && AQLVal == '2.5')
            {
                DefectsAllowed = 10;
                SampleSize = 200;
            }
            else if(LotVal == 150000 && AQLVal == '1.5')
            {
                DefectsAllowed = 7;
                SampleSize = 200;
            }
            else if(LotVal == 150000 && AQLVal == '1.0')
            {
                DefectsAllowed = 5;
                SampleSize = 200;
            }
            else if(LotVal == 150000 && AQLVal == '0.65')
            {
                DefectsAllowed = 3;
                SampleSize = 200;
            }
            else if(LotVal == 150000 && AQLVal == '0.40')
            {
                DefectsAllowed = 2;
                SampleSize = 200;
            }
            else if(LotVal == 150000 && (AQLVal == '0.15' || AQLVal == '0.25'))
            {
                DefectsAllowed = 1;
                SampleSize = 200;
            }
            else if(LotVal == 150000 && (AQLVal == '0.065' || AQLVal == '0.10'))
                SampleSize = 200;
            else if(LotVal == 500000 && AQLVal == '0.15')
            {
                DefectsAllowed = 1;
                SampleSize = 315;
            }
            else if(LotVal == 500000 && AQLVal == '0.25')
            {
                DefectsAllowed = 2;
                SampleSize = 315;
            }
            else if(LotVal == 500000 && AQLVal == '0.40')
            {
                DefectsAllowed = 3;
                SampleSize = 315;
            }
            else if(LotVal == 500000 && AQLVal == '0.65')
            {
                DefectsAllowed = 5;
                SampleSize = 315;
            }
            else if(LotVal == 500000 && AQLVal == '1.0')
            {
                DefectsAllowed = 7;
                SampleSize = 315;
            }
            else if(LotVal == 500000 && AQLVal == '1.5')
            {
                DefectsAllowed = 10;
                SampleSize = 315;
            }
            else if(LotVal == 500000 && AQLVal == '2.5')
            {
                DefectsAllowed = 14;
                SampleSize = 315;
            }
            else if(LotVal == 500000 && (AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 21;
                SampleSize = 315;
            }
            else if(LotVal == 500000 && (AQLVal == '0.065' || AQLVal == '0.10'))
                SampleSize = 315;
            else if(LotVal >= 500001 && AQLVal == '0.065')
                SampleSize = 500;
            else if(LotVal >= 500001 && AQLVal == '0.10')
            {
                DefectsAllowed = 1;
                SampleSize = 500;
            }
            else if(LotVal >= 500001 && AQLVal == '0.15')
            {
                DefectsAllowed = 2;
                SampleSize = 500;
            }
            else if(LotVal >= 500001 && AQLVal == '0.25')
            {
                DefectsAllowed = 3;
                SampleSize = 500;
            }
            else if(LotVal >= 500001 && AQLVal == '0.40')
            {
                DefectsAllowed = 5;
                SampleSize = 500;
            }
            else if(LotVal >= 500001 && AQLVal == '0.65')
            {
                DefectsAllowed = 7;
                SampleSize = 500;
            }
            else if(LotVal >= 500001 && AQLVal == '1.0')
            {
                DefectsAllowed = 10;
                SampleSize = 500;
            }
            else if(LotVal >= 500001 && AQLVal == '1.5')
            {
                DefectsAllowed = 14;
                SampleSize = 500;
            }
            else if(LotVal >= 500001 && (AQLVal == '2.5' || AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 21;
                SampleSize = 500;
            }
        }        
        
        //InspecLevel = 2
        else if(InspecLevelVal == 2 )
        {
            DefectsAllowed = 0;
            
            if(LotVal == 8)
                SampleSize = 2;
            else if(LotVal == 15 && AQLVal == '6.5')
            {
                DefectsAllowed = 1;
                SampleSize = 3;
            }
            else if(LotVal == 15)
                SampleSize = 3;            
            else if(LotVal == 25 && (AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 1;
                SampleSize = 5;
            }
            else if(LotVal == 25)
                SampleSize = 5;
            else if(LotVal == 50 && (AQLVal == '2.5' || AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 1;
                SampleSize = 8;
            }
            else if(LotVal == 50)
                SampleSize = 8;
            else if(LotVal == 90 && (AQLVal == '1.5' || AQLVal == '2.5' || AQLVal == '4.0'))
            {
                DefectsAllowed = 1;
                SampleSize = 13;
            }
            else if(LotVal == 90 && AQLVal == '6.5')
            {
                DefectsAllowed = 2;
                SampleSize = 13;
            }
            else if(LotVal == 90)
                SampleSize = 13;
            else if(LotVal == 150 && (AQLVal == '1.0' || AQLVal == '1.5' || AQLVal == '2.5'))
            {
                DefectsAllowed = 1;
                SampleSize = 20;
            }
            else if(LotVal == 150 && AQLVal == '4.0')
            {
                DefectsAllowed = 2;
                SampleSize = 20;
            }
            else if(LotVal == 150 && AQLVal == '6.5')
            {
                DefectsAllowed = 3;
                SampleSize = 20;
            }
            else if(LotVal == 150)
                SampleSize = 20;
            else if(LotVal == 280 && (AQLVal == '0.65' || AQLVal == '1.0' || AQLVal == '1.5'))
            {
                DefectsAllowed = 1;
                SampleSize = 32;
            }
            else if(LotVal == 280 && AQLVal == '2.5')
            {
                DefectsAllowed = 2;
                SampleSize = 32;
            }
            else if(LotVal == 280 && AQLVal == '4.0')
            {
                DefectsAllowed = 3;
                SampleSize = 32;
            }
            else if(LotVal == 280 && AQLVal == '6.5')
            {
                DefectsAllowed = 5;
                SampleSize = 32;
            }
            else if(LotVal == 280)
                SampleSize = 32;
            else if(LotVal == 500 && (AQLVal == '0.40' || AQLVal == '0.65' || AQLVal == '1.0'))
            {
                DefectsAllowed = 1;
                SampleSize = 50;
            }
            else if(LotVal == 500 && AQLVal == '1.5')
            {
                DefectsAllowed = 2;
                SampleSize = 50;
            }
            else if(LotVal == 500 && AQLVal == '2.5')
            {
                DefectsAllowed = 3;
                SampleSize = 50;
            }
            else if(LotVal == 500 && AQLVal == '4.0')
            {
                DefectsAllowed = 5;
                SampleSize = 50;
            }
            else if(LotVal == 500 && AQLVal == '6.5')
            {
                DefectsAllowed = 7;
                SampleSize = 50;
            }
            else if(LotVal == 500)
                SampleSize = 50;
            else if(LotVal == 1200 && (AQLVal == '0.25' || AQLVal == '0.40' || AQLVal == '0.65'))
            {
                DefectsAllowed = 1;
                SampleSize = 80;
            }
            else if(LotVal == 1200 && AQLVal == '1.0')
            {
                DefectsAllowed = 2;
                SampleSize = 80;
            }
            else if(LotVal == 1200 && AQLVal == '1.5')
            {
                DefectsAllowed = 3;
                SampleSize = 80;
            }
            else if(LotVal == 1200 && AQLVal == '2.5')
            {
                DefectsAllowed = 5;
                SampleSize = 80;
            }
            else if(LotVal == 1200 && AQLVal == '4.0')
            {
                DefectsAllowed = 7;
                SampleSize = 80;
            }
            else if(LotVal == 1200 && AQLVal == '6.5')
            {
                DefectsAllowed = 10;
                SampleSize = 80;
            }
            else if(LotVal == 1200)
                SampleSize = 80;
            else if(LotVal == 3200 && (AQLVal == '0.15' || AQLVal == '0.25' || AQLVal == '0.40'))
            {
                DefectsAllowed = 1;
                SampleSize = 125;
            }
            else if(LotVal == 3200 && AQLVal == '0.65')
            {
                DefectsAllowed = 2;
                SampleSize = 125;
            }
            else if(LotVal == 3200 && AQLVal == '1.0')
            {
                DefectsAllowed = 3;
                SampleSize = 125;
            }
            else if(LotVal == 3200 && AQLVal == '1.5')
            {
                DefectsAllowed = 5;
                SampleSize = 125;
            }
            else if(LotVal == 3200 && AQLVal == '2.5')
            {
                DefectsAllowed = 7;
                SampleSize = 125;
            }
            else if(LotVal == 3200 && AQLVal == '4.0')
            {
                DefectsAllowed = 10;
                SampleSize = 125;
            }
            else if(LotVal == 3200 && AQLVal == '6.5')
            {
                DefectsAllowed = 14;
                SampleSize = 125;
            }
            else if(LotVal == 3200)
                SampleSize = 125;            
            else if(LotVal == 10000 && (AQLVal == '0.10' || AQLVal == '0.15' || AQLVal == '0.25'))
            {
                DefectsAllowed = 1;
                SampleSize = 200;
            }
            else if(LotVal == 10000 && AQLVal == '0.40')
            {
                DefectsAllowed = 2;
                SampleSize = 200;
            }
            else if(LotVal == 10000 && AQLVal == '0.65')
            {
                DefectsAllowed = 3;
                SampleSize = 200;
            }
            else if(LotVal == 10000 && AQLVal == '1.0')
            {
                DefectsAllowed = 5;
                SampleSize = 200;
            }
            else if(LotVal == 10000 && AQLVal == '1.5')
            {
                DefectsAllowed = 7;
                SampleSize = 200;
            }
            else if(LotVal == 10000 && AQLVal == '2.5')
            {
                DefectsAllowed = 10;
                SampleSize = 200;
            }
            else if(LotVal == 10000 && AQLVal == '4.0')
            {
                DefectsAllowed = 14;
                SampleSize = 200;
            }
            else if(LotVal == 10000 && AQLVal == '6.5')
            {
                DefectsAllowed = 21;
                SampleSize = 200;
            }
            else if(LotVal == 10000)
                SampleSize = 200;
            else if(LotVal == 35000 && (AQLVal == '0.065' || AQLVal == '0.10' || AQLVal == '0.15'))
            {
                DefectsAllowed = 1;
                SampleSize = 315;
            }
            else if(LotVal == 35000 && AQLVal == '0.25')
            {
                DefectsAllowed = 2;
                SampleSize = 315;
            }
            else if(LotVal == 35000 && AQLVal == '0.40')
            {
                DefectsAllowed = 3;
                SampleSize = 315;
            }
            else if(LotVal == 35000 && AQLVal == '0.65')
            {
                DefectsAllowed = 5;
                SampleSize = 315;
            }
            else if(LotVal == 35000 && AQLVal == '1.0')
            {
                DefectsAllowed = 7;
                SampleSize = 315;
            }
            else if(LotVal == 35000 && AQLVal == '1.5')
            {
                DefectsAllowed = 10;
                SampleSize = 315;
            }
            else if(LotVal == 35000 && AQLVal == '2.5')
            {
                DefectsAllowed = 14;
                SampleSize = 315;
            }
            else if(LotVal == 35000 && (AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 21;
                SampleSize = 315;
            }
            else if(LotVal == 150000 && (AQLVal == '0.065' || AQLVal == '0.10'))
            {
                DefectsAllowed = 1;
                SampleSize = 500;
            }
            else if(LotVal == 150000 && AQLVal == '0.15')
            {
                DefectsAllowed = 2;
                SampleSize = 500;
            }
            else if(LotVal == 150000 && AQLVal == '0.25')
            {
                DefectsAllowed = 3;
                SampleSize = 500;
            }
            else if(LotVal == 150000 && AQLVal == '0.40')
            {
                DefectsAllowed = 5;
                SampleSize = 500;
            }
            else if(LotVal == 150000 && AQLVal == '0.65')
            {
                DefectsAllowed = 7;
                SampleSize = 500;
            }
            else if(LotVal == 150000 && AQLVal == '1.0')
            {
                DefectsAllowed = 10;
                SampleSize = 500;
            }
            else if(LotVal == 150000 && AQLVal == '1.5')
            {
                DefectsAllowed = 14;
                SampleSize = 500;
            }
            else if(LotVal == 150000 && (AQLVal == '2.5' || AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 21;
                SampleSize = 500;
            }
            else if(LotVal == 500000 && AQLVal == '0.065')
            {
                DefectsAllowed = 1;
                SampleSize = 800;
            }
            else if(LotVal == 500000 && AQLVal == '0.10')
            {
                DefectsAllowed = 2;
                SampleSize = 800;
            }
            else if(LotVal == 500000 && AQLVal == '0.15')
            {
                DefectsAllowed = 3;
                SampleSize = 800;
            }
            else if(LotVal == 500000 && AQLVal == '0.25')
            {
                DefectsAllowed = 5;
                SampleSize = 800;
            }
            else if(LotVal == 500000 && AQLVal == '0.40')
            {
                DefectsAllowed = 7;
                SampleSize = 800;
            }
            else if(LotVal == 500000 && AQLVal == '0.65')
            {
                DefectsAllowed = 10;
                SampleSize = 800;
            }
            else if(LotVal == 500000 && AQLVal == '1.0')
            {
                DefectsAllowed = 14;
                SampleSize = 800;
            }
            else if(LotVal == 500000 && (AQLVal == '1.5' || AQLVal == '2.5' || AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 21;
                SampleSize = 800;
            }
            else if(LotVal >= 500001  && AQLVal == '0.065')
            {
                DefectsAllowed = 2;
                SampleSize = 1250;
            }
            else if(LotVal >= 500001  && AQLVal == '0.10')
            {
                DefectsAllowed = 3;
                SampleSize = 1250;
            }
            else if(LotVal >= 500001  && AQLVal == '0.15')
            {
                DefectsAllowed = 5;
                SampleSize = 1250;
            }
            else if(LotVal >= 500001  && AQLVal == '0.25')
            {
                DefectsAllowed = 7;
                SampleSize = 1250;
            }
            else if(LotVal >= 500001  && AQLVal == '0.40')
            {
                DefectsAllowed = 10;
                SampleSize = 1250;
            }
            else if(LotVal >= 500001  && AQLVal == '0.65')
            {
                DefectsAllowed = 14;
                SampleSize = 1250;
            }
            else if(LotVal >= 500001  && (AQLVal == '1.0' || AQLVal == '1.5' || AQLVal == '2.5' || AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 21;
                SampleSize = 1250;
            }
        }        
        
        //InspecLevel = 3
        else if(InspecLevelVal == 3)
        {
            DefectsAllowed = 0;
            
            if(LotVal == 8 && AQLVal == '6.5')
            {
                DefectsAllowed = 1;
                SampleSize = 3;
            }
            else if(LotVal == 8)
                SampleSize = 3;
            else if(LotVal == 15 && (AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 1;
                SampleSize = 5;
            }
            else if(LotVal == 15)
                SampleSize = 5;
            else if(LotVal == 25 && (AQLVal == '2.5' || AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 1;
                SampleSize = 8;
            }
            else if(LotVal == 25)
                SampleSize = 8;
            else if(LotVal == 50 && (AQLVal == '1.5' || AQLVal == '2.5' || AQLVal == '4.0'))
            {
                DefectsAllowed = 1;
                SampleSize = 13;
            }
            else if(LotVal == 50 && AQLVal == '6.5')
            {
                DefectsAllowed = 2;
                SampleSize = 13;
            }
            else if(LotVal == 50)
                SampleSize = 13;
            else if(LotVal == 90 && (AQLVal == '1.0' || AQLVal == '1.5' || AQLVal == '2.5'))
            {
                DefectsAllowed = 1;
                SampleSize = 20;
            }
            else if(LotVal == 90 && AQLVal == '4.0')
            {
                DefectsAllowed = 2;
                SampleSize = 20;
            }
            else if(LotVal == 90 && AQLVal == '6.5')
            {
                DefectsAllowed = 3;
                SampleSize = 20;
            }
            else if(LotVal == 90)
                SampleSize = 20;
            else if(LotVal == 150 && (AQLVal == '0.65' || AQLVal == '1.0' || AQLVal == '1.5'))
            {
                DefectsAllowed = 1;
                SampleSize = 32;
            }
            else if(LotVal == 150 && AQLVal == '2.5')
            {
                DefectsAllowed = 2;
                SampleSize = 32;
            }
            else if(LotVal == 150 && AQLVal == '4.0')
            {
                DefectsAllowed = 3;
                SampleSize = 32;
            }
            else if(LotVal == 150 && AQLVal == '6.5')
            {
                DefectsAllowed = 5;
                SampleSize = 32;
            }
            else if(LotVal == 150)
                SampleSize = 32;
            else if(LotVal == 280 && (AQLVal == '0.40' || AQLVal == '0.65' || AQLVal == '1.0'))
            {
                DefectsAllowed = 1;
                SampleSize = 50;
            }
            else if(LotVal == 280 && AQLVal == '1.5')
            {
                DefectsAllowed = 2;
                SampleSize = 50;
            }
            else if(LotVal == 280 && AQLVal == '2.5')
            {
                DefectsAllowed = 3;
                SampleSize = 50;
            }
            else if(LotVal == 280 && AQLVal == '4.0')
            {
                DefectsAllowed = 5;
                SampleSize = 50;
            }
            else if(LotVal == 280 && AQLVal == '6.5')
            {
                DefectsAllowed = 7;
                SampleSize = 50;
            }
            else if(LotVal == 280)
                SampleSize = 50;
            else if(LotVal == 500 && (AQLVal == '0.25' || AQLVal == '0.40' || AQLVal == '0.65'))
            {
                DefectsAllowed = 1;
                SampleSize = 80;
            }
            else if(LotVal == 500 && AQLVal == '1.0')
            {
                DefectsAllowed = 2;
                SampleSize = 80;
            }
            else if(LotVal == 500 && AQLVal == '1.5')
            {
                DefectsAllowed = 3;
                SampleSize = 80;
            }
            else if(LotVal == 500 && AQLVal == '2.5')
            {
                DefectsAllowed = 5;
                SampleSize = 80;
            }
            else if(LotVal == 500 && AQLVal == '4.0')
            {
                DefectsAllowed = 7;
                SampleSize = 80;
            }
            else if(LotVal == 500 && AQLVal == '6.5')
            {
                DefectsAllowed = 10;
                SampleSize = 80;
            }
            else if(LotVal == 500)
                SampleSize = 80;
            else if(LotVal == 1200 && (AQLVal == '0.15' || AQLVal == '0.25' || AQLVal == '0.40'))
            {
                DefectsAllowed = 1;
                SampleSize = 125;
            }
            else if(LotVal == 1200 && AQLVal == '0.65')
            {
                DefectsAllowed = 2;
                SampleSize = 125;
            }
            else if(LotVal == 1200 && AQLVal == '1.0')
            {
                DefectsAllowed = 3;
                SampleSize = 125;
            }
            else if(LotVal == 1200 && AQLVal == '1.5')
            {
                DefectsAllowed = 5;
                SampleSize = 125;
            }
            else if(LotVal == 1200 && AQLVal == '2.5')
            {
                DefectsAllowed = 7;
                SampleSize = 125;
            }
            else if(LotVal == 1200 && AQLVal == '4.0')
            {
                DefectsAllowed = 10;
                SampleSize = 125;
            }
            else if(LotVal == 1200 && AQLVal == '6.5')
            {
                DefectsAllowed = 14;
                SampleSize = 125;
            }
            else if(LotVal == 1200)
                SampleSize = 125;
            else if(LotVal == 3200  && (AQLVal == '0.10' || AQLVal == '0.15' || AQLVal == '0.25'))
            {
                DefectsAllowed = 1;
                SampleSize = 200; 
            }
            else if(LotVal == 3200  && AQLVal == '0.40')
            {
                DefectsAllowed = 2;
                SampleSize = 200; 
            }
            else if(LotVal == 3200  && AQLVal == '0.65')
            {
                DefectsAllowed = 3;
                SampleSize = 200; 
            }
            else if(LotVal == 3200  && AQLVal == '1.0')
            {
                DefectsAllowed = 5;
                SampleSize = 200; 
            }
            else if(LotVal == 3200  && AQLVal == '1.5')
            {
                DefectsAllowed = 7;
                SampleSize = 200; 
            }
            else if(LotVal == 3200  && AQLVal == '2.5')
            {
                DefectsAllowed = 10;
                SampleSize = 200; 
            }
            else if(LotVal == 3200  && AQLVal == '4.0')
            {
                DefectsAllowed = 14;
                SampleSize = 200; 
            }
            else if(LotVal == 3200  && AQLVal == '6.5')
            {
                DefectsAllowed = 21;
                SampleSize = 200; 
            }
            else if(LotVal == 3200)
                SampleSize = 200; 
            else if(LotVal == 10000 && (AQLVal == '0.065' || AQLVal == '0.10' || AQLVal == '0.15'))
            {
                DefectsAllowed = 1;
                SampleSize = 315;
            }
            else if(LotVal == 10000 && AQLVal == '0.25')
            {
                DefectsAllowed = 2;
                SampleSize = 315;
            }
            else if(LotVal == 10000 && AQLVal == '0.40')
            {
                DefectsAllowed = 3;
                SampleSize = 315;
            }
            else if(LotVal == 10000 && AQLVal == '0.65')
            {
                DefectsAllowed = 5;
                SampleSize = 315;
            }
            else if(LotVal == 10000 && AQLVal == '1.0')
            {
                DefectsAllowed = 7;
                SampleSize = 315;
            }
            else if(LotVal == 10000 && AQLVal == '1.5')
            {
                DefectsAllowed = 10;
                SampleSize = 315;
            }
            else if(LotVal == 10000 && AQLVal == '2.5')
            {
                DefectsAllowed = 14;
                SampleSize = 315;
            }
            else if(LotVal == 10000 && (AQLVal == '4.0'  || AQLVal == '6.5'))
            {
                DefectsAllowed = 21;
                SampleSize = 315;
            }
            else if(LotVal == 35000 && (AQLVal == '0.065' || AQLVal == '0.10'))
            {
                DefectsAllowed = 1;
                SampleSize = 500;
            }
            else if(LotVal == 35000 && AQLVal == '0.15')
            {
                DefectsAllowed = 2;
                SampleSize = 500;
            }
            else if(LotVal == 35000 && AQLVal == '0.25')
            {
                DefectsAllowed = 3;
                SampleSize = 500;
            }
            else if(LotVal == 35000 && AQLVal == '0.40')
            {
                DefectsAllowed = 5;
                SampleSize = 500;
            }
            else if(LotVal == 35000 && AQLVal == '0.65')
            {
                DefectsAllowed = 7;
                SampleSize = 500;
            }
            else if(LotVal == 35000 && AQLVal == '1.0')
            {
                DefectsAllowed = 10;
                SampleSize = 500;
            }
            else if(LotVal == 35000 && AQLVal == '1.5')
            {
                DefectsAllowed = 14;
                SampleSize = 500;
            }
            else if(LotVal == 35000 && (AQLVal == '2.5' || AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 21;
                SampleSize = 500;
            }
            else if(LotVal == 150000 && AQLVal == '0.065')
            {
                DefectsAllowed = 1;
                SampleSize = 800;
            }
            else if(LotVal == 150000 && AQLVal == '0.10')
            {
                DefectsAllowed = 2;
                SampleSize = 800;
            }
            else if(LotVal == 150000 && AQLVal == '0.15')
            {
                DefectsAllowed = 3;
                SampleSize = 800;
            }
            else if(LotVal == 150000 && AQLVal == '0.25')
            {
                DefectsAllowed = 5;
                SampleSize = 800;
            }
            else if(LotVal == 150000 && AQLVal == '0.40')
            {
                DefectsAllowed = 7;
                SampleSize = 800;
            }
            else if(LotVal == 150000 && AQLVal == '0.65')
            {
                DefectsAllowed = 10;
                SampleSize = 800;
            }
            else if(LotVal == 150000 && AQLVal == '1.0')
            {
                DefectsAllowed = 14;
                SampleSize = 800;
            }
            else if(LotVal == 150000 && (AQLVal == '1.5' || AQLVal == '2.5' || AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 21;
                SampleSize = 800;
            }
            else if(LotVal == 500000 && AQLVal == '0.065')
            {
                DefectsAllowed = 2;
                SampleSize = 1250;
            }
            else if(LotVal == 500000 && AQLVal == '0.10')
            {
                DefectsAllowed = 3;
                SampleSize = 1250;
            }
            else if(LotVal == 500000 && AQLVal == '0.15')
            {
                DefectsAllowed = 5;
                SampleSize = 1250;
            }
            else if(LotVal == 500000 && AQLVal == '0.25')
            {
                DefectsAllowed = 7;
                SampleSize = 1250;
            }
            else if(LotVal == 500000 && AQLVal == '0.40')
            {
                DefectsAllowed = 10;
                SampleSize = 1250;
            }
            else if(LotVal == 500000 && AQLVal == '0.65')
            {
                DefectsAllowed = 14;
                SampleSize = 1250;
            }
            else if(LotVal == 500000 && (AQLVal == '1.0' || AQLVal == '1.5' || AQLVal == '2.5' || AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 21;
                SampleSize = 1250;
            }
            else if(LotVal == 500001 && AQLVal == '0.065')
            {
                DefectsAllowed = 3;
                SampleSize = 2000;            
            }
            else if(LotVal == 500001 && AQLVal == '0.10')
            {
                DefectsAllowed = 5;
                SampleSize = 2000;            
            }
            else if(LotVal == 500001 && AQLVal == '0.15')
            {
                DefectsAllowed = 7;
                SampleSize = 2000;            
            }
            else if(LotVal == 500001 && AQLVal == '0.25')
            {
                DefectsAllowed = 10;
                SampleSize = 2000;            
            }
            else if(LotVal == 500001 && AQLVal == '0.40')
            {
                DefectsAllowed = 14;
                SampleSize = 2000;            
            }
            else if(LotVal == 500001 && (AQLVal == '0.65' || AQLVal == '1.0' || AQLVal == '1.5' || AQLVal == '2.5' || AQLVal == '4.0' || AQLVal == '6.5'))
            {
                DefectsAllowed = 21;
                SampleSize = 2000;            
            }
        }   
        
        document.getElementById("SampleSizeId").innerHTML = SampleSize;
        document.getElementById("DefectsAllowedId").innerHTML = DefectsAllowed;
        
}

function resetValue()
{
    document.getElementById("SampleSizeId").innerHTML = "";
    document.getElementById("DefectsAllowedId").innerHTML = "";
}