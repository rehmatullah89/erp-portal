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

    @include($sBaseDir.$sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/jquery.js"></script>
  <script type="text/javascript" src="scripts/jquery.tokeninput.js"></script>
  <link type="text/css" rel="stylesheet" href="css/jquery.tokeninput.css" />
<script>
            jQuery.noConflict();
</script>

<?

    $sSQL = "Select style_id, additional_styles, po_id, additional_pos, colors, sizes,
                (select inspection_level from tbl_brands WHERE id=tbl_qa_reports.brand_id) as _InspectionLevel
                FROM tbl_qa_reports WHERE id='$Id'";
    
    $objDb->query($sSQL);
    
    $Styles             = $objDb->getField(0, 'style_id');
    $additionalStyles   = $objDb->getField(0, 'additional_styles');
    $Pos                = $objDb->getField(0, 'po_id');
    $additionalPos      = $objDb->getField(0, 'additional_pos');
    $colors             = $objDb->getField(0, 'colors');
    $sizes              = $objDb->getField(0, 'sizes');
    $sInspecLevel       = $objDb->getField(0, '_InspectionLevel');

    $allStyles  = $Styles.",".$additionalStyles;
    $allStyles  = rtrim($allStyles,",");
    $allPos     = $Pos.",".$additionalPos;
    $allPos     = rtrim($allPos,",");

    $sStylesList = getList("tbl_styles", "id", "style", "id IN ($allStyles)");

    $sPoList = getList("tbl_po", "id", "order_no", "id IN ($allPos)");

    $sScheduledStyleList = $sStylesList;
    $sScheduledSizeList = getList("tbl_sizes", "id", "size", "id IN ($sizes)");
    $sScheduledColorsList = array();

    $colorArray = explode(",", $colors);

    foreach ($colorArray as $colorSingle) {
        $sScheduledColorsList[$colorSingle] = $colorSingle;
      }  

?>

    <input type="hidden" name="InspecLevel" value="<?=$sInspecLevel?>" id="InspecLevel"/>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
          <tr class="sdRowHeader">
            <td width="10" align="center"><b>#</b></td>
            <td width="90" align="center"><b>Styles</b></td>
            <td width="90" align="center"><b>Po(s)</b></td>
            <td width="90" align="center"><b>Colors</b></td>
            <td width="90" align="center"><b>Sizes</b></td>
            <td width="50" align="center"><b>Lot Size</b></td>
            <td width="50" align="center"><b>Sample Size</b></td>
            <td width="30" align="center"><b>Delete</b></td>
          </tr>
        </table>
        <?

            $sSQL = "SELECT * FROM tbl_qa_lot_sizes WHERE audit_id='$Id' ORDER BY id";

            $objDb->query($sSQL);

            $iLotCount = $objDb->getCount( );

            if($iLotCount > 0){

                $qopCount = 1;

                                $sAdditionalPos = getDbValue ("additional_pos", "tbl_qa_reports", "id = '$Id' ");
                                $sAdditionalStyles = getDbValue ("additional_styles", "tbl_qa_reports", "id = '$Id' ");
                                $sColors = getDbValue ("colors", "tbl_qa_reports", "id = '$Id' ");

                                $totalSampleSize = 0;
                                $totalLotSize = 0;

            for ($i=0; $i <$iLotCount ; $i++) { 

                $id        = $objDb->getField($i, 'id');
                $dbStylesText = $objDb->getField($i, 'styles');

                $dbPosText = $objDb->getField($i, 'pos');
                $dbColorsText = $objDb->getField($i, 'colors');
                $dbColors = explode(',',$dbColorsText);
                $dbLotSize = $objDb->getField($i, 'lot_size');
                $dbLotSampleSize = $objDb->getField($i, 'sample_size');
                
                $dbStyles = explode(',',$dbStylesText);
                $dbPos = explode(',',$dbPosText);
                $dbSizes = explode(',',$objDb->getField($i, 'sizes'));

                $totalLotSize = $totalLotSize + $dbLotSize;
                $totalSampleSize = $totalSampleSize + $dbLotSampleSize;

                $sColorsList  = getList("tbl_po po, tbl_po_colors pc", "pc.color", "pc.color", "po.id=pc.po_id AND (po.id IN($dbPosText) OR FIND_IN_SET(po.id, '$sAdditionalPos')) AND '$sColors' LIKE CONCAT('%', REPLACE(pc.color, ',', ' '), '%') AND pc.style_id='$dbStylesText'","","pc.color");

                $sSizesList  = getList("tbl_po po, tbl_po_colors pc, tbl_po_quantities pq", "pq.size_id", "(SELECT size FROM tbl_sizes WHERE id=pq.size_id)", "po.id=pc.po_id AND po.id=pq.po_id AND (po.id IN($dbPosText) OR FIND_IN_SET(po.id, '$sAdditionalPos')) AND pc.style_id='$dbStylesText' AND '$dbColorsText' LIKE CONCAT('%', REPLACE(pc.color, ',', ' '), '%') AND pq.quantity > 0","","pq.size_id");


                $styleListHTML = getListHTML('lotCombStyles'.$qopCount,'lotCombStyles'.$qopCount.'[]',$sStylesList,false,$dbStyles,'getPoList(lotCombStyles'.$qopCount.',lotCombPos'.$qopCount.')');

                $poListHTML = getMultipleListHTML('lotCombPos'.$qopCount,'lotCombPos'.$qopCount.'[]',$sPoList,false,$dbPos,'getPoList(lotCombStyles'.$qopCount.',lotCombPos'.$qopCount.',lotCombColor'.$qopCount.',)');

                $colorListHTML = getMultipleListHTML('lotCombColor'.$qopCount,'lotCombColors'.$qopCount.'[]',$sColorsList,false,$dbColors);

                $sizeListHTML = getMultipleListHTML('lotCombSizes'.$qopCount,'lotCombSizes'.$qopCount.'[]',$sSizesList,false,$dbSizes);
            ?>

            <div id="qopDeletedDiv"></div>
                <table id="table<?=$qopCount?>" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" class="lotRow">
                  <tr>
                    <input type="hidden" name="dbId<?=$qopCount?>" value="<?=$id?>">
                    <input type="hidden" name="RowId[]" value="<?=$qopCount?>">
                    <td width="10" align="center" class="lotIndex"><?=$qopCount?></td>
                    <td width="90" align="center">
                        <?=$styleListHTML?>
                    </td>
                    <td width="90" align="center">
                        <?=$poListHTML?>
                    </td>
                    <td width="90" align="center">
                        <?= $colorListHTML?>
                    </td>
                    <td width="90" align="center">
                        <?= $sizeListHTML?>
                    </td>
                    <td width="50" align="center">
                        <input type="text" required="" class="textbox lotSize" id="lotsize-<?=$qopCount?>"  name="lotSizes<?=$qopCount?>" value="<?=$dbLotSize?>" onchange="calculateSampleSize(<?=$qopCount?>)" style="width:60px;" sample-field="samplesize-<?=$qopCount?>"/>
                    </td>
                    <td width="50" align="center">
                        <input type="text" required="" class="textbox sampleSize" id="samplesize-<?=$qopCount?>" name="lotSampleSizes<?=$qopCount?>" value="<?=$dbLotSampleSize?>" readonly style="width:60px;"/>
                    </td>
                    <td width="30" align="center">
                        <a href="javascript:;"><img src="images/icons/delete.gif" onclick="deleteOldQOPRow(<?=$qopCount?>,<?=$id ?>)" alt="Delete" title="Delete" width="16" height="16"></a>       
                    </td>
                  </tr>
                </table>

            <?  
            $qopCount++;
            }
        }
        ?>              

    <input type="hidden" name="qopCount" id="qopCount" value="<?=$qopCount?>">

    <div id="qopMainDiv">
    </div>

    <div>
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
          <tr bgcolor="#f0f0f0">
            <td  style="text-align: right; margin: 20px;"><b>Total:</b></td>
            <td width="86" align="center"><input type="text" name="SumOfLotSize" id="SumOfLotSize" class="textbox" value="<?=$totalLotSize?>" style="background: none; border: 0px; color:#333333; font-weight: bold; text-align: center;" size="7" readonly=""/></td>
            <td width="86" align="center"><input type="text" class="textbox" name="SumOfSampleSize" id="SumOfSampleSize" value="<?=$totalSampleSize?>" size="7" style="background: none; border: 0px; color:#333333; font-weight: bold; text-align: center;" readonly=""/></td>
            <td width="53" align="center">&nbsp</td>
          </tr>
        </table>
    </div>

    <div class="qaButtons">
    <input type="button" value="" class="btnAdd" title="Add Quantity of Product" onclick="addQOPNewRow();" />
    </div>


<?
$defectStylesListHTML = getKeyValueListHTML("defectStyle","defectStyles[]",$sScheduledStyleList,true);
$defectSizesListHTML = getKeyValueListHTML("defectSize","defectSizes[]",$sScheduledSizeList,true);
$defectColorListHTML = getListHTML("defectColor","defectColors[]",$sScheduledColorsList,true,true);
?>
                    
    <script type="text/javascript">
    <!--
    
var auditId = "<?=$Id?>";
var defectStylesListHTML = '<?=$defectStylesListHTML?>';
var defectSizesListHTML = '<?=$defectSizesListHTML?>';
var defectColorListHTML = '<?=$defectColorListHTML?>';

function getPoList(sStyle, sPos)
{
    var poId = $(sPos).id;

    $(sPos).innerHTML = "";

    var iStyle = $(sStyle).value;

    var sUrl    = "ajax/get-style-pos.php";
    var sParams = ("Id=" + iStyle + "&PO=" + poId+"&AuditId="+auditId);
    
    if (iStyle == "")
        return;

    $(sPos).disable( );

    new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getResults });
}

function getColorList(sStyle, sPos, sColors)
{
    var colorId = $(sColors).id;

    $(sColors).innerHTML = "";

    var iStyle = $(sStyle).value;


    var objPo = $(sPos);
    var length = objPo.options.length;
    var poString = "";

    for (var i = 0; i < length; i++) {

        if(objPo.options[i].selected)
            poString += objPo.options[i].value + ',';
    }

    poString = poString.slice(0, -1);


    var sUrl    = "ajax/get-style-pos-colors.php";
    var sParams = ("Id=" + iStyle + "&Pos="+poString+"&Color=" + colorId+"&AuditId="+auditId);
    
    if (iStyle == "")
        return;

    $(sColors).disable( );

    new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getResults });
}

function getSizeList(sStyle, sPos, sColors, sSizes)
{
    var sizeId = $(sSizes).id;

    $(sSizes).innerHTML = "";

    var iStyle = $(sStyle).value;


    var objPo = $(sPos);
    var length = objPo.options.length;
    var poString = "";

    for (var i = 0; i < length; i++) {

        if(objPo.options[i].selected)
            poString += objPo.options[i].value + ',';
    }

    poString = poString.slice(0, -1);

    var objColor = $(sColors);
    var colorLength = objColor.options.length;
    var colorString = "";

    for (var i = 0; i < colorLength; i++) {

        if(objColor.options[i].selected)
            colorString += objColor.options[i].value + ',';
    }

    colorString = colorString.slice(0, -1);


    var sUrl    = "ajax/get-style-pos-colors-sizes.php";
    var sParams = ("Id=" + iStyle + "&Pos="+poString+"&Size=" + sizeId+"&AuditId="+auditId+"&Colors="+colorString);
    
    if (iStyle == "")
        return;

    $(sSizes).disable( );

    new Ajax.Request(sUrl, { method:'post', parameters:sParams, onFailure:_showError, onSuccess:_getResults });
}

function _getResults(sResponse)
{
    if (sResponse.status == 200 && sResponse.statusText == "OK")
    {
        var sParams = sResponse.responseText.split('|-|');

        if (sParams[0] == "OK")
        {
            var sChild = sParams[1];

            for (var i = 2; i < sParams.length; i ++)
            {
                var sOption = sParams[i].split("||");

                $(sChild).options[(i - 2)] = new Option(sOption[1], sOption[0], false, false);

            }

            $(sChild).enable( );
        }

        else
            _showError(sParams[1]);
    }

    else
        _showError( );
}

function calculateSampleSize(count)
{

    if(count != '0') {

            var lotSize = parseInt(jQuery("#lotsize-"+count).val());

            var dbTotalSampleSize = jQuery("#totalSampleSize").val();

            if(isNaN(lotSize) || lotSize == "" || lotSize == '0') {

                jQuery("#lotsize-"+count).val('');
                jQuery("#samplesize-"+count).val('');

                calculateSampleSize(0);

                return false;
            }

            jQuery(this).val(lotSize);

            var Samplesize = getSampleSize(lotSize);

            jQuery("#samplesize-"+count).val(Samplesize);

    }

    var totalLotSize = 0;
    var totalSampleSize = 0;
    var totalRecords = 0;
    var ids = [];

    jQuery(".lotSize").each(function() {

        var lotSize = parseInt(jQuery(this).val());

        if(!isNaN(lotSize) || lotSize == '0'){

        totalLotSize = parseInt(totalLotSize) + lotSize;

        totalRecords++;

        var fieldid = jQuery(this).attr('id');

        ids.push(fieldid); 
        }
    });

    var ComSamplesize = getSampleSize(totalLotSize);

    var totalRoundSampleSize = 0;
    var count = 0;

    var actualSampleSize = 0;

    jQuery(".lotSize").each(function() {

        var lotSize = parseInt(jQuery(this).val());

        if(!isNaN(lotSize) || lotSize == '0'){
    
        var sampleFieldId = jQuery(this).attr('sample-field');

    var wSampleSize = (parseInt(ComSamplesize)*lotSize)/totalLotSize;
    count++;

        if(wSampleSize < 2) {

            wSampleSize = 2;
        
        } else {
        
            wSampleSize = Math.round(wSampleSize);
            // wSampleSize = parseInt(wSampleSize);
        }

        actualSampleSize = wSampleSize;

        totalRoundSampleSize = parseInt(totalRoundSampleSize)+wSampleSize;

        var lastLoopdiff = 0;

        jQuery("#"+sampleFieldId).val(wSampleSize);

        totalSampleSize = parseInt(totalSampleSize) + wSampleSize;
        
        if(totalRecords == count) {

            if(ComSamplesize > totalRoundSampleSize) {

                var lastLoopdiff = ComSamplesize - totalRoundSampleSize;

                if(lastLoopdiff > 0) {

                    jQuery(".sampleSize").each(function() {

                        var sampleSize = parseInt(jQuery(this).val());

                        if(lastLoopdiff > 0) {

                            sampleSize = parseInt(sampleSize)+1;
                            lastLoopdiff--;
                            totalSampleSize++;
                            jQuery(this).val(sampleSize);
                        }
                    });
                }               
            } else if(totalRoundSampleSize > ComSamplesize) {

                var lastLoopdiff = totalRoundSampleSize-ComSamplesize;

                if(lastLoopdiff > 0) {

                    jQuery(".sampleSize").each(function() {

                        var bigValue  = getbiggerValue(totalRecords,ids);
                        var sampleSize = parseInt(jQuery(this).val());

                        if(lastLoopdiff > 0 && sampleSize > 2) {

                            if(sampleSize == bigValue) {
    
                                sampleSize = parseInt(sampleSize)-1;
                                lastLoopdiff--;
                                totalSampleSize--;

                                jQuery(this).val(sampleSize);
                                
                            }
                        }
                    });
                }   

            }
        }
    }
});

// console.log(totalSampleSize,ComSamplesize);
    if(totalSampleSize > ComSamplesize) {

        var lastDiff = parseInt(totalSampleSize)-parseInt(ComSamplesize);

        if(lastDiff > 0){

            var counter = 0;
            
            var allMinimum = 0;

            for(var i=0; i<totalRecords; i++) {
            
                counter++;
                var bigValue = getbiggerValue(totalRecords,ids);
                var currentId = ids[i];
                var sampleFieldId = jQuery("#"+currentId).attr('sample-field');

                var sampleSize = parseInt(jQuery("#"+sampleFieldId).val());

                if(sampleSize > 2) {

                    // var bigValue = getbiggerValue(totalRecords,ids);
// console.log("bigValue=>"+bigValue);
                    if(bigValue == sampleSize) {

                        // console.log("before=>"+totalSampleSize);
                        sampleSize = parseInt(sampleSize)-1;
                        totalSampleSize--;
                        // console.log("after=>"+totalSampleSize);
                        jQuery("#"+sampleFieldId).val(sampleSize);
                    allMinimum++;
                    }
                }

                if(counter == totalRecords && allMinimum > 0){
                    if(totalSampleSize != ComSamplesize) {
                        allMinimum = 0;
                        i=0;
                        counter=1;
                    }
                }

                if(totalSampleSize == ComSamplesize) {
                    jQuery("#SumOfSampleSize").val(totalSampleSize);
                    jQuery("#SumOfLotSize").val(totalLotSize);
                    return false;
                }
            }
        }
    }
console.log("last samplesize=>"+totalLotSize);
jQuery("#SumOfSampleSize").val(totalSampleSize);
jQuery("#SumOfLotSize").val(totalLotSize);

}
function getbiggerValue(totalRecords,ids) {

    var bigger;
    var biggerId;

    for(var i=0; i<totalRecords; i++) {

                var currentId = ids[i];
                var sampleFieldId = jQuery("#"+currentId).attr('sample-field');

                var sampleSize = parseInt(jQuery("#"+sampleFieldId).val());

                if(i==0){
                    bigger = sampleSize;
                    biggerId = currentId;
                }       

                if(sampleSize > bigger) {
                    bigger = sampleSize;
                    biggerId = currentId;
                }
    }

    return bigger;
}

function getSampleSize(quantity)
{
    var InspecLevel = document.getElementById("InspecLevel").value;
    
    if(InspecLevel == 1)
    {
        var sAqlChart = [
                { min:2,     max:8,    samples:2 },
                { min:9,    max:15,    samples:2 },
                { min:16,    max:25,    samples:3 },
                { min:26,    max:50,    samples:5 },
                { min:51,    max:90,   samples:5 },
                { min:91,   max:150,   samples:8 },
                { min:151,   max:280,   samples:13 },
                { min:281,   max:500,  samples:20 },
                { min:501,  max:1200,  samples:32 },
                { min:1201,  max:3200,  samples:50 },
                { min:3201,  max:10000, samples:80 },
                { min:10001, max:35000, samples:125 },
                { min:35001, max:150000, samples:200 },
                { min:150001, max:500000, samples:315 },
                { min:500001, max:10000001, samples:500 }
        ];
    }
    else
    {
            var sAqlChart = [
                        { min:2,     max:8,    samples:2 },
                        { min:9,    max:15,    samples:3 },
                        { min:16,    max:25,    samples:5 },
                        { min:26,    max:50,    samples:8 },
                        { min:51,    max:90,   samples:13 },
                        { min:91,   max:150,   samples:20 },
                        { min:151,   max:280,   samples:32 },
                        { min:281,   max:500,  samples:50 },
                        { min:501,  max:1200,  samples:80 },
                        { min:1201,  max:3200,  samples:125 },
                        { min:3201,  max:10000, samples:200 },
                        { min:10001, max:35000, samples:315 },
                        { min:35001, max:150000, samples:500 },
                        { min:150001, max:500000, samples:800 },
                        { min:500001, max:10000001, samples:1250 }
            ];
    }

        var iSampleSize = 0;

        for(var i=0; i<sAqlChart.length; i++) {

            if (quantity >= sAqlChart[i]['min'] && quantity <= sAqlChart[i]['max'])
            {

                iSampleSize = ((sAqlChart[i]['samples'] == 0) ? quantity : sAqlChart[i]['samples']);

                break;
            }
        }

        return iSampleSize;
}

    function addQOPNewRow()
{
    var count = jQuery("#qopCount").val();

    if(count == 0)
        count = 1;
    
    // var arrayIndex = parseInt(count)-1;

    var html = '<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="table'+count+'" class="lotRow">';
            html += '<tr>';
            html += '<input type="hidden" name="dbId'+count+'" value="?">';
            html += '<input type="hidden" name="RowId[]" value="'+count+'">';
            html += '<td width="10" align="center" class="lotIndex">'+ (jQuery(".lotRow").length + 1) +'</td>';
            html += '<td width="90">';
            html += '<?=getListHTML("lotCombStyles'+count+'","lotCombStyles'+count+'[]",$sStylesList,true,"","getPoList(lotCombStyles'+count+',lotCombPos'+count+')")?>';
            html += '</td>';
            html += '<td width="90" align="center">';
            html += '<?=getMultipleListHTML("lotCombPos'+count+'","lotCombPos'+count+'[]",array(),false,array(),"getColorList(lotCombStyles'+count+',lotCombPos'+count+',lotCombColor'+count+')")?>';
            html += '</td>';
            html += '<td width="90" align="center">';
            html += '<?=getMultipleListHTML("lotCombColor'+count+'","lotCombColors'+count+'[]",array(),false,array(),"getSizeList(lotCombStyles'+count+',lotCombPos'+count+',lotCombColor'+count+',lotCombSizes'+count+')")?>';
            html += '</td>';
            html += '<td width="90" align="center">';
            html += '<?=getMultipleListHTML("lotCombSizes'+count+'","lotCombSizes'+count+'[]")?>';
            html += '</td>';
            html += '<td width="50" align="center">';
            html += '<input type="text" class="textbox lotSize" id="lotsize-'+count+'" onchange="calculateSampleSize('+count+')" name="lotSizes'+count+'" required="" style="width:60px;" sample-field="samplesize-'+count+'" />';
            html += '</td>';
            html += '<td width="50" align="center">';
            html += '<input type="text" class="textbox sampleSize" id="samplesize-'+count+'" name="lotSampleSizes'+count+'" required="" readonly style="width:60px;"/>';
            html += '</td>';
            html += '<td width="30" align="center">';
            html += '<a href="javascript:;"><img src="images/icons/delete.gif" alt="Delete" title="Delete" width="16" height="16" onclick="deleteQOPRow('+count+')"></a>';
            html += '</td>';
            html += '</tr>';
            html += '</table>';

                jQuery('#qopMainDiv').append(html);

                count++;
                jQuery("#qopCount").val(count);

}
    function deleteQOPRow(id)
    {
        if(confirm("Are you sure you want to Delete?")){

                jQuery("#table"+id).remove();
                
                jQuery(".lotIndex").each(function(iIndex)
                {
                    jQuery(this).text(iIndex + 1);
                });

                calculateSampleSize(0);
        }
    }

    function deleteOldQOPRow(rowId,Id)
    {
        if(confirm("Are you sure you want to Delete?")){

                jQuery('#qopDeletedDiv').append('<input type="hidden" name="deleteRecords[]" value="'+Id+'">');

                jQuery("#table"+rowId).remove();
                
                jQuery(".lotIndex").each(function(iIndex)
                {
                    jQuery(this).text(iIndex + 1);
                });

                calculateSampleSize(0);
        }
    }

    




    -->
    </script> 

<?
function getMultipleListHTML($id,$name,$listInArray=array(),$simpleArray=false,$selectedValue=array(),$sFunction="")
{

        $onChangeString = "";

        if($sFunction != ""){
            $onChangeString = 'onchange="'.$sFunction.'"';
        }

    $listHTML = '<select required="" name="'.$name.'" id="'.$id.'" style="width:150px;" multiple '.$onChangeString.' >';
                                                                        
     foreach($listInArray as $key => $value)       
     {

        $selected = "";

        if($simpleArray){

            if(in_array($value, $selectedValue))
                $selected = 'selected';

            $listHTML .=   '  <option value="'.$value.'" '.$selected.'>'.$value.'</option>';

        } else {

            if(in_array($key, $selectedValue))
                $selected = 'selected';
            
            $listHTML .=   '  <option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }

     }   

    $listHTML .=   '</select>';

    return $listHTML;
}

function getListHTML($id,$name,$listInArray=array(),$empty=false,$selectedValue="", $sFunction="")
{

        $onChangeString = "";

        $selected = "";

        if($sFunction != ""){
            $onChangeString = 'onchange="'.$sFunction.'"';
        }

    $listHTML = '<select required="" name="'.$name.'" id="'.$id.'" style="width:150px;" '.$onChangeString.' >';

    if($empty){

      $listHTML .=   '<option value=""> </option>';
    }

   foreach($listInArray as $key => $value)       
   {

    $selected = "";

    if(in_array($key, $selectedValue))
        $selected = 'selected';

        $listHTML .=   '  <option value="'.$key.'" '.$selected.'>'.$value.'</option>';  

   }   

  $listHTML .=   '</select>';

  return $listHTML;
}

function getKeyValueListHTML($id,$name,$listInArray,$empty=false,$selectedValue=""){

    $listHTML = '<select required="" name="'.$name.'" id="'.$id.'" style="width:150px;">';

        if($empty){

            $listHTML .=   '<option value=""> </option>';
        }

     foreach($listInArray as $key => $value)       
     {

        $selected = "";


            if($key == $selectedValue)
                $selected = 'selected';
            
            $listHTML .=   '  <option value="'.$key.'" '.$selected.'>'.$value.'</option>'; 

}
  $listHTML .=   '</select>';

  return $listHTML;

}
?>