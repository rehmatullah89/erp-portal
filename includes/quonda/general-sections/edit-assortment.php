<?
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
	**  Project Developer:                                                                       **
	**                                                                                           **
	**      Name  :  Rehmatullah Bhatti                                                          **
	**      Email :  rehmatullahbhatti@gmail.com                                                 **
	**      Phone :  +92 344 40 43 675                                                           **
	**      URL   :  http://www.rehmatullahbhatti.com                                            **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/

            $sSQL = "SELECT *
                    FROM tbl_qa_assortment
	         WHERE audit_id='$Id'";
        
	$objDb->query($sSQL);
        
	$iTotalAssorted  = $objDb->getField(0, "total_cartons_tested");
	$iWrongAssorted  = $objDb->getField(0, "wrong_assorted_cartons");
        $sResult         = $objDb->getField(0, "result");
        $sComments       = $objDb->getField(0, "comments");
            
?>
	<div style="margin:1px 0px 0px 1px; _margin:0px; #margin:0px;">
	<h3>Assortment</h3>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="AssortmentTable">
                <tr>
                    <td width="150">Total Assorted Cartons</td>
                    <td width="20">:</td>
                    <td>
                        <input type="text" name="TotalCartons" id="TotalCartons" size="10" value="<?=$iTotalAssorted?>"/>
                    </td>
                </tr>
                <tr>
                    <td width="140">Wrong Assorted Cartons</td>
                    <td width="20">:</td>
                    <td>
                        <input type="text" name="WrongAssortedCartons" size="10" value="<?=$iWrongAssorted?>" onchange="updateResult(this.value);"/>
                    </td>
                </tr>
        </table>

            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                      <td width="140">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                </tr>
                 <tr class="sdRowHeader">
                     <td colspan="3"><b>Assortment Result & Comments</b></td>
                </tr>

                <tr>
                    <td width="80">Result</td>
                    <td width="20">:</td>
                    <td>    
                        <select name="Result" id="Result" required="">
                            <option value="">Select Result</option>
                            <option value="P" <?=($sResult == 'P'?'selected':'')?>>Pass</option>
                            <option value="F" <?=($sResult == 'F'?'selected':'')?>>Fail</option>
                            <option value="N" <?=($sResult == 'N'?'selected':'')?>>N/A</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="80">Comments</td>
                    <td width="20">:</td>
                    <td>    
                        <textarea name="Comments" Style="width:98%;" rows="5"><?=$sComments?></textarea>
                    </td>
                </tr>

            </table>
	</div>
<script type="text/javascript">
<!--

	function validateForm( )
	{
		var objFV = new FormValidator("frmData");

        

		return true;
	}

	
  function updateResult(WrongAssort)
  {        
        var valueToSelect = "N";
        var TotalCartons = document.getElementById("TotalCartons").value;
        
        if(TotalCartons > 0)
        {
            var percent = ((WrongAssort/TotalCartons)*100);
            
            if(percent > 3)
                valueToSelect = "F";
            else
                valueToSelect = "P";
                
        }
        else if(TotalCartons == 0 && WrongAssort>0)
            valueToSelect = "F";
        
        var Result = document.getElementById("Result");
        Result.value = valueToSelect;
  }
-->
</script> 