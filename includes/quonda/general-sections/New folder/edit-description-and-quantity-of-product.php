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
?>
<?
    @include($sBaseDir.$sBaseDir."includes/meta-tags.php");
?>
  <script type="text/javascript" src="scripts/jquery.js"></script>
  <script type="text/javascript" src="scripts/jquery.tokeninput.js"></script>
  <link type="text/css" rel="stylesheet" href="css/jquery.tokeninput.css" />
<script>
            jQuery.noConflict();
</script>
<?
        $TotalGmts = getDbValue('total_gmts', 'tbl_qa_reports', "id='$Id'");
        $BundleType= getDbValue('bundle', 'tbl_qa_reports', "id='$Id'");
        $iHoIONo = getDbValue("po_id", "tbl_qa_reports", "id='$Id'");
        
        $allStyles = getDbValue('GROUP_CONCAT(DISTINCT(style_id))', '`tbl_hoh_order_details` hod INNER JOIN `tbl_hoh_orders` ho ON ho.id = hod.hoh_order_id', "hod.hoh_order_id='$iHoIONo'");
        
        $allSizes = getDbValue('GROUP_CONCAT(DISTINCT(size_id))', '`tbl_hoh_order_details` hod INNER JOIN `tbl_hoh_orders` ho ON ho.id = hod.hoh_order_id', "hod.hoh_order_id='$iHoIONo'");

        $allColors = getDbValue('GROUP_CONCAT(DISTINCT(color))', '`tbl_hoh_order_details` hod INNER JOIN `tbl_hoh_orders` ho ON ho.id = hod.hoh_order_id', "hod.hoh_order_id='$iHoIONo'");

        $sStylesList = getList("tbl_styles", "id", "CONCAT(style_name, '-',style)", "id IN ($allStyles)");
        $sSizesList = getList("tbl_sampling_sizes", "id", "size", "id IN ($allSizes)");

        $colorArray = explode(',', $allColors);

        $sCountryBlocksList = getList("tbl_country_blocks", "id", "country_block");        
?>

    <h3>Description/Quantity of Product</h3>
        <input type="hidden" id="lotCount" name="lotCount" value="<?= $iCount ?>" />
        <div style="padding: 10px;">Total Sample Size: <input type="text" value="<?=$TotalGmts?>" name="TotalGmtsSize" id="TotalGmtsSize" size="5" readonly=""/><span style="float: right;"><input type="radio" name="BundleType" value="P" <?=($BundleType == 'P'?'checked':'')?> />Pieces &nbsp;<input type="radio" name="BundleType" value="S" <?=($BundleType == 'S'?'checked':'')?> />Sets &nbsp;<input type="radio" name="BundleType" value="R" <?=($BundleType == 'R'?'checked':'')?> />Pairs &nbsp;</span></div>
        
        <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
          <tr class="sdRowHeader">
            <td width="10" align="center"><b>#</b></td>
            <td width="55"><b>Country Block</b></td>
            <td width="100"><b>Country</b></td>
            <td width="90" align="center"><b>Styles</b></td>
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
                $TotalLotSize = 0;
                $qopRowCount = 0;

            for ($i=0; $i <$iLotCount ; $i++) { 

                $id        = $objDb->getField($i, 'id');
                $dbStyles = explode(',',$objDb->getField($i, 'styles'));
                $dbColors = explode(',',$objDb->getField($i, 'colors'));
                $dbSizes = explode(',',$objDb->getField($i, 'sizes'));
                $dbLotSize = $objDb->getField($i, 'lot_size');
                $dbLotSampleSize = $objDb->getField($i, 'sample_size');
                $dbCountryBlock = $objDb->getField($i, 'cb_id');
                $dbCountry      = $objDb->getField($i, 'country_id');

                $TotalLotSize += $dbLotSize;
                
                $styleListHTML = getMultipleListHTML('lotCombStyles','lotCombStyles'.$qopRowCount.'[]',$sStylesList,false,$dbStyles);
                $sizeListHTML = getMultipleListHTML('lotCombSizes','lotCombSizes'.$qopRowCount.'[]',$sSizesList,false,$dbSizes);

                $colorArray = explode(',', $allColors);

                $colorListHTML = getMultipleListHTML('lotCombColor','lotCombColors'.$qopRowCount.'[]',$colorArray,true,$dbColors);

                $sFunction = "onchange=\"getListValues('countryBlock', 'country', 'CountriesFromBlock')\"";
                $sCountryCodes = getDbValue("country_codes", "tbl_country_blocks", "id='$dbCountryBlock'");
                $sCountryList  = getList("tbl_country_codes cc, tbl_countries c", "c.id", "CONCAT(c.country, ' (', cc.code, ')')", "c.id=cc.country_id AND cc.code IN ('". implode("','", explode(",", $sCountryCodes))."')"); 

                $countryBlockListHTML = getListHTML('countryBlock','countryBlocks[]',$sCountryBlocksList,false,false,$dbCountryBlock, $sFunction);
                $countryListHTML      = getListHTML('country','country[]',$sCountryList,false,false,$dbCountry);
            ?>

                    <div id="qopDeletedDiv"></div>
                        <table id="table<?=$qopCount?>" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                          <tr>
                            <input type="hidden" name="dbId[]" value="<?=$id?>">
                            <td width="10" align="center"><?=$qopCount?></td>
                            <td width="60">
                                <?=$countryBlockListHTML?>                  
                            </td>
                            <td width="100">
                                <?=$countryListHTML?>                  
                            </td>
                            <td width="90" align="center">
                                <?=$styleListHTML?>
                            </td>
                            <td width="90" align="center">
                                <?= $colorListHTML?>
                            </td>
                            <td width="90" align="center">
                                <?= $sizeListHTML?>
                            </td>
                            <td width="50" align="center">
                                <input type="text" required="" class="textbox lotSize" id="lotsize-<?=$qopCount?>"  name="lotSizes[]" value="<?=$dbLotSize?>" onchange="calculateSampleSize(<?=$qopCount?>)" style="width:60px;"/>
                            </td>
                            <td width="50" align="center">
                                <input type="text" required="" class="textbox sampleSize" id="samplesize-<?=$qopCount?>" name="lotSampleSizes[]" value="<?=$dbLotSampleSize?>" style="width:60px;"/>
                            </td>
                            <td width="30" align="center">
                                <a href="javascript:;"><img src="images/icons/delete.gif" onclick="deleteOldQOPRow(<?=$qopCount?>,<?=$id ?>)" alt="Delete" title="Delete" width="16" height="16"></a>       
                            </td>
                          </tr>
                        </table>


            <?  
            $qopCount++;
            $qopRowCount++;
            }
        }
        ?>              

    <input type="hidden" name="qopCount" id="qopCount" value="<?=$qopCount?>">

    <div id="qopMainDiv">
    </div>
    <div>
        <table>
            <tr style="background: gray;"><td colspan="6" width="660" style="text-align: right; margin: 20px;"><b>Totals</b></td><td width="90" align="right"><input type="text" name="SumOfLotSize" id="SumOfLotSize" class="textbox" value="<?=$TotalLotSize?>" size="7" readonly=""/></td><td width="90" align="center"><input type="text" class="textbox" name="SumOfSampleSize" id="SumOfSampleSize" value="<?=$TotalGmts?>" size="7" readonly=""/></td><td width="50">&nbsp;</td></tr>
        </table>
    </div>
    
    <div class="qaButtons">
    <input type="button" value="" class="btnAdd" title="Add Quantity of Product" onclick="addQOPNewRow();" />
    </div>
                    
	<script type="text/javascript">

    var totalGmts  = '<?=$TotalGmts?>';

    function getSampleSize(lotSize)
    {
        //var lotSize = jQuery("#lotsize-"+rowId).val();
        var sampleSize = 0;
        
        if(lotSize>2 && lotSize<=8)
            sampleSize = 2;
        else if(lotSize>8 && lotSize<=15)
            sampleSize = 3;
        else if(lotSize>15 && lotSize<=25)
            sampleSize = 5;
        else if(lotSize>25 && lotSize<=50)
            sampleSize = 8;
        else if(lotSize>50 && lotSize<=90)
            sampleSize = 13;
        else if(lotSize>90 && lotSize<=150)
            sampleSize = 20;
        else if(lotSize>150 && lotSize<=280)
            sampleSize = 32;
        else if(lotSize>280 && lotSize<=500)
            sampleSize = 50;
        else if(lotSize>500 && lotSize<=1200)
            sampleSize = 80;
        else if(lotSize>1200 && lotSize<=3200)
            sampleSize = 125;
        else if(lotSize>3200 && lotSize<=10000)
            sampleSize = 200;
        else if(lotSize>10000 && lotSize<=35000)
            sampleSize = 315;
        else if(lotSize>35000 && lotSize<=150000)
            sampleSize = 500;
        else if(lotSize>150000 && lotSize<=500000)
            sampleSize = 800;
        else if(lotSize>500000)
            sampleSize = 1250;
        
        //jQuery("#samplesize-"+rowId).val(sampleSize);
        return sampleSize;
    }
    
    function addQOPNewRow()
    {
        var count = jQuery("#qopCount").val();

        if(count == 0)
            count = 1;
        
        var arrayIndex = parseInt(count)-1;

        var CountryBlocks = "<select name='countryBlocks[]' id='countryBlock"+count+"' onchange='getListValues(\"countryBlock"+count+"\", \"country"+count+"\", \"CountriesFromBlock\")'><option value=''></option>";
        var Countries = "<select name='country[]' id='country"+count+"'><option value=''></option></select>";
        
<?
            $ItemsSelect = "";
 
            foreach ($sCountryBlocksList as $iBlock => $sBlock)
            {                                                
                   $ItemsSelect .= "<option value='".$iBlock."' ".((in_array($iBlock, IO::getArray("countryBlock")))?'selected':'').">".$sBlock."</option>";
            }

                $ItemsSelect .= "</select>";
?>
        var html = '<table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="table'+count+'">';
                html += '<tr>';
                html += '<input type="hidden" name="dbId[]" value="?">'
                html += '<td width="10" align="center">'+count+'</td>';
                html += '<td width="60">';
                html += CountryBlocks+"<?=$ItemsSelect?>";
                html += '</td>';
                html += '<td width="100">';
                html += Countries;
                html += '</td>';
                html += '<td width="90">';
                html += '<?=getMultipleListHTML("lotCombStyles","lotCombStyles'+arrayIndex+'[]",$sStylesList)?>';
                html += '</td>';
                html += '<td width="90" align="center">';
                html += '<?=getMultipleListHTML("lotCombColor","lotCombColors'+arrayIndex+'[]",$colorArray,true)?>';
                html += '</td>';
                html += '<td width="90" align="center">';
                html += '<?=getMultipleListHTML("lotCombSizes","lotCombSizes'+arrayIndex+'[]",$sSizesList)?>';
                html += '</td>';
                html += '<td width="50" align="center">';
                html += '<input type="text" class="textbox lotSize" id="lotsize-'+count+'" onchange="calculateSampleSize('+count+')" name="lotSizes[]" required="" style="width:60px;" />';
                html += '</td>';
                html += '<td width="50" align="center">';
                html += '<input type="text" class="textbox sampleSize" id="samplesize-'+count+'" name="lotSampleSizes[]" required="" style="width:60px;"/>';
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

    function calculateSampleSize(rowId)
    {

        var toatlSample = 0;
        var totalLotSize = 0;
        var rowIds = [];

            var activeRowValue = jQuery('#lotsize-'+rowId).val();

            jQuery( ".lotSize" ).each(function() {
                
                var singleSize = jQuery( this ).val();

                rowIds.push(jQuery( this ).attr('id'));

                totalLotSize = parseInt(totalLotSize)+parseInt(singleSize) ;

            });
        
        if(totalLotSize > 0)
        {
            toatlSample = getSampleSize(totalLotSize);
            jQuery('#TotalGmtsSize').val(toatlSample);
        }

        if(toatlSample >0 && parseInt(totalGmts) > 0)
        {
            calculateAndUpdate(toatlSample, totalLotSize);
            //jQuery('#samplesize-'+rowId).val(activeRowValue);
        } 
    }

    function deleteQOPRow(id){

        if(confirm("Are you sure you want to Delete?")){

                jQuery("#table"+id).remove();

                //calculateAndUpdate();
                calculateSampleSize(1);
        }
    }

    function deleteOldQOPRow(rowId,Id)
    {
        if(confirm("Are you sure you want to Delete?")){

                jQuery('#qopDeletedDiv').append('<input type="hidden" name="deleteRecords[]" value="'+Id+'">');

                jQuery("#table"+rowId).remove();

                //calculateAndUpdate();
                calculateSampleSize(1);
        }
    }

    function calculateAndUpdate(totalSampleSize, totalLotSize)
    {
        var incTotal        = 0;
        var incCount        = 1;
        var singleSample    = 0;
        var singleLotSize   = 0;
        var TotalRows       = jQuery( ".lotSize" ).length;
        
        if(TotalRows == 1)
        {
            jQuery('#samplesize-1').val(totalSampleSize);
        }
        else
        {
            jQuery( ".lotSize" ).each(function() {
                
                singleLotSize = jQuery( this ).val();
                
                var activeRowId = jQuery( this ).attr('id');
                var splitResults = activeRowId.split('-');
                var parentId = splitResults[1];
                    
                if(parseInt(incCount) == TotalRows)
                {
                    singleSample = parseInt(totalSampleSize) - parseInt(incTotal);
                    jQuery('#samplesize-'+parentId).val(singleSample);
                }
                else
                {
                    singleSample = Math.round((singleLotSize/totalLotSize)*totalSampleSize);
                    incTotal = parseInt(incTotal) + parseInt(singleSample);
                
                    jQuery('#samplesize-'+parentId).val(singleSample);
                }
                
                incCount = parseInt(incCount) + parseInt(1);

            });
        }
      /*var totalRatio = 0;
      var totalLotSize = 0;

        jQuery( ".lotSize" ).each(function() {
                
                var singleSize = jQuery( this ).val();

                if(singleSize) {

                        totalLotSize = parseInt(totalLotSize)+parseInt(singleSize) ;
                }

            });


                if(parseInt(totalLotSize) <= parseInt(totalGmts)){

                    jQuery( ".lotSize" ).each(function() {
                            
                            var singleSize = jQuery( this ).val();
                            var activeRowId = jQuery( this ).attr('id');
                            var splitResults = activeRowId.split('-');
                            var parentId = splitResults[1];

                            if(singleSize){

                                    jQuery('#samplesize-'+parentId).val(singleSize);
                            }

                        });

                } else {

                    jQuery( ".lotSize" ).each(function() {
                            
                            var singleSize = jQuery( this ).val();
                            var activeRowId = jQuery( this ).attr('id');
                            var splitResults = activeRowId.split('-');
                            var parentId = splitResults[1];

                if(singleSize) {

                            var ratio = (parseInt(singleSize) * parseInt(totalGmts)) / parseInt(totalLotSize);
                            var roundRatio = Math.round(ratio);

                            totalRatio = parseInt(totalRatio)+parseInt(roundRatio) ;

                                if(parseInt(totalRatio) > parseInt(totalGmts)){

                                roundRatio = parseInt(roundRatio) - 1;

                            } else if(parseInt(totalGmts)-parseInt(totalRatio) == 1){

                                roundRatio = parseInt(roundRatio) + 1;
                            }

                            jQuery('#samplesize-'+parentId).val(Math.round(roundRatio));
                }

                        });                     
                }*/

    }

</script> 

<?
function getMultipleListHTML($id,$name,$listInArray,$simpleArray=false,$selectedValue=array())
{
    $listHTML = '<select required="" name="'.$name.'" id="'.$id.'" style="width:150px;" multiple>';
                                                                        
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

function getListHTML($id,$name,$listInArray,$simpleArray=false,$empty=false,$selectedValue="", $sFunction)
{
        //var count = jQuery("#qopCount").val();

        
    if($id == "countryBlock")
    {        
        $listHTML = '<select required="" name="'.$name.'" id="'.$id.'" style="width:150px;" '.$sFunction.'>';        
    }
    else if(@strpos($id, "countryBlock" !== FALSE))
    {
        $sFunction = "onchange=\"getListValues('countryBlock', 'country', 'CountriesFromBlock')\"";
        $listHTML = '<select required="" name="'.$name.'" id="'.$id.'" style="width:150px;" '.addslashes($sFunction).'>';
    }
    else
        $listHTML = '<select required="" name="'.$name.'" id="'.$id.'" style="width:150px;" >';

    if($empty){

      $listHTML .=   '<option value=""> </option>';
    }

    $counter = 1;

   foreach($listInArray as $key => $value)       
   {

    $selected = "";

    if($simpleArray){

      if($value == $selectedValue)
        $selected = 'selected';

      
      if(@strpos($id, "countryBlock" !== FALSE))
        $listHTML .=   '  <option value="'.$value.'" '.$selected.'>'.$value.'</option>';
      else
        $listHTML .=   '  <option value="'.$key.'" '.$selected.'>'.$value.'</option>';  

    } else {

      if($counter == $selectedValue)
        $selected = 'selected';
      
      if(@strpos($id, "countryBlock" !== FALSE))
        $listHTML .=   '  <option value="'.$counter.'" '.$selected.'>'.$value.'</option>';
      else
        $listHTML .=   '  <option value="'.$key.'" '.$selected.'>'.$value.'</option>';  
    }

    $counter++;

   }   

  $listHTML .=   '</select>';

  return $listHTML;
}


?>