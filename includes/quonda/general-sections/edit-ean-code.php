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

    $Styles     = getDbValue("style_id", "tbl_qa_reports", "id='$Id'");
    $Sizes      = getDbValue("sizes", "tbl_qa_reports", "id='$Id'");
    $SizesList  = getList("tbl_sizes", "id", "size", "id IN ($Sizes)");
    
    $sBarCodeFormat = getDbValue("barcode_format", "tbl_qa_report_details", "audit_id='$Id'");
    $sResult        = getDbValue("ean_result", "tbl_qa_report_details", "audit_id='$Id'");
    $sComments      = getDbValue("ean_comments", "tbl_qa_report_details", "audit_id='$Id'");
    
    $iCounter      = 1;           
?>
    </td>
    </tr>
  </table>
</form>

<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
    <tr class="sdRowHeader">
      <td width="25" align="center"><b>#</b></td>
      <td width="80" align="center"><b>Style</b></td>
      <td width="50" align="center"><b>Size</b></td>
      <td width="80" align="center"><b>EAN</b></td>
      <td width="20" align="center"><b>Result</b></td>
      <td width="100" align="center"><b>Options</b></td>
    </tr>
</table>    
<?
    $iStyles = explode(",", $Styles);
    $iSizes  = explode(",", $Sizes);
    $sEan    = "0123456789";
    $iId     = 0;
    
    foreach($iStyles as $iStyle)
    {
        foreach($iSizes as $iSize)
        {        
            $iId ++;
?>
    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" >
        <tr class="sdRowColor">
          <td width="25" align="center"><?=$iCounter?></td>
          <td width="80" align="center"><?= getDbValue("style", "tbl_styles", "id='$iStyle'")?></td>
          <td width="50" align="center"><?=$SizesList[$iSize]?></td>
          <td width="80" align="center"><?=$sEan?></td>
          <td width="20" align="center"><span id="result<?= $iId ?>"><?=getDbValue("result", "tbl_qa_ean_codes", "style_id='$iStyle' AND size_id='$iSize' AND audit_id='$Id'")?></span></td>
          <td width="100" align="center"><a href="javascript:;" onclick="Effect.SlideDown(Edit<?=$iId?>); return false;"><img src="images/icons/edit.gif" alt="Edit" title="Edit" width="16" hspace="1" height="16"></a></td>
        </tr>
    </table>

  <div id="Msg<?= $iId ?>" class="msgOk" style="overflow: visible; display: none;" ></div>
  <div id="Edit<?= $iId ?>" style="display: none;">
    <div style="padding:1px;">

          <form name="frmData<?= $iId ?>" id="frmData<?= $iId ?>" onsubmit="return false;" class="frmInlineEdit">

              <input type="hidden" name="odId" value="<?= $iId ?>" />
              <input type="hidden" name="auditId" value="<?= $Id ?>" />
              <input type="hidden" name="styleId" value="<?= $iStyle ?>" />
              <input type="hidden" name="sizeId" value="<?= $iSize ?>" />
              <input type="hidden" id="pEan<?= $iId ?>" name="ean" value="<?= $sEan ?>" />
            
            <div style="padding-top: 10px;">

                    <?
                        $sSQL = "SELECT * FROM tbl_qa_ean_codes WHERE audit_id='$Id' AND style_id='$iStyle' AND size_id='$iSize'";

                        $objDb2->query($sSQL);

                        $iCount2  = (int)$objDb2->getCount( );
                        $rowCount = 1;

                        for($j=0;$j<$iCount2;$j++)
                        {

                            $position   = $objDb2->getField($j, 'position');
                            $code       = $objDb2->getField($j, 'code');
                            
                            $fieldStyle = "";

                            if($code != $sEan){
                                $fieldStyle = 'style="border-color: red;"';
                            }

                    ?>
                    <table cellpadding="3" cellspacing="0" width="80%" id="table<?= $iId ?><?=$rowCount?>">
                    <tr id="row<?=$rowCount?>">
                        <td width="200">Position : <input type="text" class="textbox" name="Position[]" value="<?=$position?>"></td>
                        <td width="200">EAN Code : <input type="text" <?=$fieldStyle?> class="textbox eanField" name="EanCodes[]" value="<?=$code?>" id="ean-<?= $iId ?>-<?=$rowCount?>"></td>
                        <td><img src="images/icons/delete.gif" alt="Delete" title="Delete" width="16" hspace="1" height="16" class="delete" onclick="deleteEanRow(<?= $iId ?>,<?=$rowCount?>)"></td>
                    </tr>
                    </table>
                    <?
                            $rowCount++;
                        }
                    ?>
                <div id="newRowDiv<?= $iId ?>"></div>
                <table cellpadding="3" cellspacing="0" width="50%">
                    <tr>
                        <td colspan="6" style="padding: 10px;">
                         <input type="hidden" name="Counter" id="Counter<?= $iId ?>" value="<?=$rowCount?>">
                         <input type="button" value="Add [+]" class="btnSmall" onclick="addEanRow(<?= $iId ?>)" />
                         <div style="float: right; ">
                            <input type="button" value="SAVE" class="btnSmall submitForm" id="<?= $iId ?>" />
                           <input type="button" value="CANCEL" class="btnSmall" onclick="Effect.SlideUp('Edit<?= $iId ?>');" />    
                         </div>
                        </td>
                    </tr>                            
                </table>
                <div>

                   
                </div>
            </div>                  
          </form>

      </div>
    </div>
<?
$iCounter++;
    }
}

?>
    <form name="frmData" id="frmData" method="post" enctype="multipart/form-data" action="includes/quonda/update-report-section.php">
            <input type="hidden" name="Id" id="Id" value="<?= $Id ?>" />
            <input type="hidden" name="SectionId" id="Id" value="4" />
            <input type="hidden" name="Styles" value="<?= $Styles ?>" />
            
            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                      <td width="100">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td>&nbsp;</td>
                </tr>
                 <tr class="sdRowHeader">
                     <td colspan="3"><b>Ean Code Result & Comments</b></td>
                </tr>
                <tr>
                    <td width="120">Result</td>
                    <td width="20">:</td>
                    <td>    
                        <select name="Result">
                            <option value="">Select Result</option>
                            <option value="P" <?=($sResult == 'P'?'selected':'')?>>Pass</option>
                            <option value="F" <?=($sResult == 'F'?'selected':'')?>>Fail</option>
                            <option value="N" <?=($sResult == 'N'?'selected':'')?>>N/A</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="120">Barcode Format</td>
                    <td width="20">:</td>
                    <td id="ResultsRow">    
                        <select name="BarcodeFormat">
                            <option value="">Select Format</option>
                            <option value="1" <?=($sBarCodeFormat == 1?'selected':'')?>>EAN-8</option>
                            <option value="2" <?=($sBarCodeFormat == 2?'selected':'')?>>EAN-13</option>
                        </select>
                    </td>
                </tr> 
                <tr>
                    <td width="120">Comments</td>
                    <td width="20">:</td>
                    <td>    
                        <textarea name="Comments" Style="width:98%;" rows="5"><?=$sComments?></textarea>
                    </td>
                </tr>                
            </table>                        
                   

  <script type="text/javascript" src="scripts/jquery.js"></script>

  <script type="text/javascript">
  <!--
        jQuery.noConflict( );
  -->
  </script>

  <script type="text/javascript" src="scripts/quonda/audit-codes.js"></script>
  <script type="text/javascript" src="scripts/jquery.tokeninput.js"></script>
  <link type="text/css" rel="stylesheet" href="css/jquery.tokeninput.css" />

<script type="text/javascript">

    function validateForm( )
    {
            var objFV = new FormValidator("frmData");

            return true;
    }
	
    function addEanRow(id) 
    {
        var i = jQuery("#Counter"+id).val();

        $html = '<table cellpadding="3" cellspacing="0" width="80%" id="table'+id+i+'">';
        $html += '<tr>';
        $html += '<td  width="200">Position : <input type="text" name="Position[]" class="textbox" /></td>'
        $html += '<td  width="200">EAN Code : <input type="text" id="ean-'+id+'-'+i+'" name="EanCodes[]" class="textbox eanField" /></td>'
        $html += '<td><img src="images/icons/delete.gif" alt="Delete" title="Delete" width="16" hspace="1" height="16" class="delete" onclick="deleteEanRow('+id+','+i+')"></td>'
        $html += '</tr>';
        $html += '</table>';

        i++;

        jQuery("#newRowDiv"+id).append($html);
        jQuery("#Counter"+id).val(i);
    }

    function deleteEanRow(parentId,rowId) {

        jQuery("#table"+parentId+rowId).remove();

        var pEan = jQuery('#pEan'+parentId).val();

        var recordCount = 0;
        var correctCount = 0;
        var failCount = 0;

        jQuery("#frmData"+parentId+" input[name='EanCodes[]']").each(function() {
          
          recordCount++;

          if(this.value == pEan){
            correctCount++;
          } else {
            failCount++;
          }
        });

        if(recordCount!='0' && recordCount == correctCount){

            jQuery('#result'+parentId).html('P');

        } else if(recordCount == '0'){

            jQuery('#result'+parentId).html('-');

        } else if(failCount > 0) {

            jQuery('#result'+parentId).html('F');
        }       
    }

    $(document).on('keyup','.eanField',function(){

        var rowId = $(this).activeElement.id;
        var value = $(this).activeElement.value;
        var splitResults = rowId.split('-');
        var parentId = splitResults[1];
        var fieldCount = splitResults[2];


        if('0123456789' != value)
        {
            jQuery('#ean-'+parentId+'-'+fieldCount).css('border-color','red');
            jQuery('#result'+parentId).html('F');
        } 
        else 
        {
            jQuery('#ean-'+parentId+'-'+fieldCount).css('border-color','');
            jQuery('#result'+parentId).html('P');
        }

        var recordCount = 0;
        var correctCount = 0;

        jQuery("#frmData"+parentId+" input[name='EanCodes[]']").each(function() {
          
          recordCount++;

          if(this.value == pEan){
            correctCount++;
          }
        });

        if(recordCount!='0' && recordCount == correctCount){
            jQuery('#result'+parentId).html('P');
        }
    });

    <!---------------->

    $(document).on('click','.submitForm',function(){

        var rowId = $(this).activeElement.id;

        var emptyCount = 0;

        jQuery("#frmData"+rowId+" input[name='Position[]']").each(function() {
          
          if(this.value == ""){
            emptyCount++;
          }
        });

        jQuery("#frmData"+rowId+" input[name='EanCodes[]']").each(function() {
          
          if(this.value == ""){
            emptyCount++;
          }
        });

        if(emptyCount > 0){
            
            alert("Please Fill all the required fields");

            return false;
        }

        var sUrl    = "ajax/quonda/save-ean-remarks.php";
        var sParams = jQuery('#frmData' + rowId).serialize( );

        // var objForm = $("frmData" + rowId);
        // objForm.disable( );

        new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_addData });
});

function _addData(sResponse)
{
    if (sResponse.status == 200 && sResponse.statusText == "OK")
    {
        var sParams = sResponse.responseText.split('|-|');
        var iId     = sParams[1];

        if (sParams[0] == "OK")
        {

            $('Msg'+iId).innerHTML = sParams[2];
            $('Msg'+iId).show( );

            $('Edit' + iId).hide( );

            // jQuery('.delete').remove();

            setTimeout(
                    function( )
                    {
                    new Effect.SlideUp("Msg"+iId);

                    },

                    2000
                  );
        }

        else if (sParams[0] == "INFO")
            _showError(sParams[2]);

        else
            _showError(sParams[1]);

        // $('Processing').hide( );
    }

    else
        _showError( );
} 

</script>
<form>