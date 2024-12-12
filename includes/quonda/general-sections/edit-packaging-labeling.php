                                <!--- Packaging Defects Starts-->                       
<?

                                    $sSQL = "SELECT *
                                            FROM tbl_qa_packaging_defects
                                            WHERE audit_id='$Id'";
                                    $objDb->query($sSQL);

                                    $iPackingCount = $objDb->getCount( );
                                   
?>
                                <h3>A- Packaging Defects</h3>
                                <div id="PackingDefects">
				<input type="hidden" id="PCountRows" name="PCountRows" value="<?= $iPackingCount ?>" />

                                <table>
                                    <tr>
                                        <td width="100"><b>Total Cartons</b></td>
                                        <td width="20">:</td>
                                        <td width="100"><input type="text" name="TotalCartons" size='5' value="<?=$TotalCartons?>"></td>                                    
                                    </tr>
                                    <tr>
                                        <td width="120"><b>Inspected Cartons</b></td>
                                        <td width="20">:</td>
                                        <td width="150"><input type="text" name="InspectedCartons"  size='5' value="<?=$InspectedCartons?>"></td>
                                    </tr>
                                </table>
                                <br/>

			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="55" align="center"><b>#</b></td>
					<td><b>Defect</b></td>
					<td width="100" align="center"><b>Carton No</b></td>
					<td width="200" align="center"><b>Picture</b></td>
					<td width="50" align="center"><b>Delete</b></td>
			      </tr>
			    </table>
                                <?
                                if($ReportId == 60)
                                    $sLabelingDefectsList = getList("tbl_defect_codes", "id", "CONCAT(code, ' - ', defect)", "report_id='$ReportId' AND type_id='135'" , "id");
                                else                                    
                                    $sLabelingDefectsList = getList("tbl_defect_codes", "id", "CONCAT(code, ' - ', defect)", "report_id='$ReportId' AND type_id='6'" , "id");
?>
                                 <div id="PDefect" style="display:none;">
                                                <option value=""></option>
<?
                                               foreach($sLabelingDefectsList as $iPId => $sPDefect)
                                               {
?>
                                                <option value="<?=$iPId?>"><?=$sPDefect?></option> 
<?
                                               }
?>
                                            </div>

                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="PDefectsTable">
<?
                        if($iPackingCount > 0)
                        {
                                @list($sPkYear, $sPkMonth, $sPkDay) = @explode("-", $AuditDate);
                                $sPkPicsDir   = (SITE_URL.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                                $sPackagingDir = ($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                
                                for($i = 0; $i < $iPackingCount; $i ++)
                                {
                                    $iTableId      = $objDb->getField($i, 'id');
                                    $iDefectCodeId = $objDb->getField($i, 'defect_code_id');
                                    $iSampleNumber = $objDb->getField($i, 'sample_no');
                                    $sDefectPicture= $objDb->getField($i, 'picture');
?>
                                    <tr id="PRowNo<?=$i+1?>">
                                        <td width="50" align="center"><b><?=$i+1?></b><input type="hidden" name="PDefectRows[]" value='0'></td>
                                        <td>
                                            <select name="PDefect[]" required="">
                                                <option value=""></option>
<?
                                               foreach($sLabelingDefectsList as $iPId => $sPDefect)
                                               {
?>
                                                <option value="<?=$iPId?>" <?=($iDefectCodeId == $iPId)?'selected':''?>><?=$sPDefect?></option> 
<?
                                               }
?>
                                            </select>
                                        </td>
					<td width="100" align="center">
                                            <input type="text" name="PSamples[]" size="5" value="<?=$iSampleNumber?>" required="">
                                        </td>
                                        <td width="200" align="center">
                                            <input type="file" name="PImages[]">
<?
                                                if ($sDefectPicture != "" && @file_exists($sPackagingDir.$sDefectPicture))
                                                {
?>
                                            <br/><span>(<a href="<?= $sPkPicsDir ?><?= $sDefectPicture ?>" class="lightview"><?= $sDefectPicture ?></a>)&nbsp;</span>
						  <input type="hidden" name="PPrevPicture[]" value="<?= $sDefectPicture ?>">
<?
                                                } else {
?>
                                                  <input type="hidden" name="PPrevPicture[]" value="">
<?
                                                }
?>
                                        </td>
                                        <td width="50" align="center"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" style="cursor:pointer;" onclick="DeletePLDefect(<?=$i+1?>,<?=$iTableId?>,'P')" /></td>
			      </tr>
<?
                                }
                        }
                        else
                        {
?>
                                <tr style="line-height:0px;">
                                    <td width="50" align="center">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td width="100" align="center">&nbsp;</td>
                                    <td width="200" align="center">&nbsp;</td>
                                    <td width="50" align="center">&nbsp;</td>
                                </tr>
<?
                        }
?>
			    </table>
                                </div>
                            
                                <div class="qaButtons">
				  <input type="button" value="" class="btnAdd" title="Add Packaging Defect" onclick="addPLDefect('P');" />
                                  <input id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" type="submit">
				</div>
                                                    <!-- Packaging Defects Ends -->
                                
                                <!-- ---------------------------------------------------------------------------------------------------------- -->
                                                    <!--- Labeling Defects Starts-->                       
<?

                                    $sSQL = "SELECT *
                                            FROM tbl_qa_labeling_defects
                                            WHERE audit_id='$Id'";
                                    $objDb->query($sSQL);

                                    $iLabelingCount = $objDb->getCount( );
                                   
?>
                              <h3>B- Labeling Defects</h3>                      
                                <div id="LabelingDefects">
				<input type="hidden" id="LCountRows" name="LCountRows" value="<?= $iLabelingCount ?>" />

                                <table>
                                    <tr>
                                        <td width="100"><b>Total Cartons</b></td>
                                        <td width="20">:</td>
                                        <td width="100"><input type="text" name="LabelingTotalCartons" size='5' value="<?= getDbValue("labeling_total_cartons", "tbl_qa_report_details", "audit_id='$Id'");?>"></td>                                    
                                    </tr>
                                    <tr>
                                        <td width="120"><b>Inspected Cartons</b></td>
                                        <td width="20">:</td>
                                        <td width="150"><input type="text" name="LabelingInspectedCartons"  size='5' value="<?=getDbValue("labeling_sample_size", "tbl_qa_report_details", "audit_id='$Id'")?>"></td>
                                    </tr>
                                </table>
                                <br/>

			    <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
			      <tr class="sdRowHeader">
					<td width="55" align="center"><b>#</b></td>
					<td><b>Defect</b></td>
					<td width="100" align="center"><b>Carton No</b></td>
					<td width="200" align="center"><b>Picture</b></td>
					<td width="50" align="center"><b>Delete</b></td>
			      </tr>
			    </table>
                                <?
                                if($ReportId == 60)
                                    $sLabelingDefectsList = getList("tbl_defect_codes", "id", "CONCAT(code, ' - ', defect)", "report_id='$ReportId' AND type_id='24'" , "id");
                                else                                    
                                    $sLabelingDefectsList = getList("tbl_defect_codes", "id", "CONCAT(code, ' - ', defect)", "report_id='$ReportId' AND type_id='6'" , "id");

?>
                                 <div id="LDefect" style="display:none;">
                                                <option value=""></option>
<?
                                               foreach($sLabelingDefectsList as $iPId => $sPDefect)
                                               {
?>
                                                <option value="<?=$iPId?>"><?=$sPDefect?></option> 
<?
                                               }
?>
                                            </div>

                            <table border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%" id="LDefectsTable">
<?
                        if($iLabelingCount > 0)
                        {
                                @list($sPkYear, $sPkMonth, $sPkDay) = @explode("-", $AuditDate);
                                $sPkPicsDir   = (SITE_URL.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                                $sPackagingDir = ($sBaseDir.PACKAGING_PICS_DIR.$sPkYear."/".$sPkMonth."/".$sPkDay."/");
                
                                for($i = 0; $i < $iLabelingCount; $i ++)
                                {
                                    $iTableId      = $objDb->getField($i, 'id');
                                    $iDefectCodeId = $objDb->getField($i, 'defect_code_id');
                                    $iSampleNumber = $objDb->getField($i, 'sample_no');
                                    $sDefectPicture= $objDb->getField($i, 'picture');
?>
                                    <tr id="LRowNo<?=$i+1?>">
                                        <td width="50" align="center"><b><?=$i+1?></b><input type="hidden" name="LDefectRows[]" value='0'></td>
                                        <td>
                                            <select name="LDefect[]" required="">
                                                <option value=""></option>
<?
                                               foreach($sLabelingDefectsList as $iPId => $sPDefect)
                                               {
?>
                                                <option value="<?=$iPId?>" <?=($iDefectCodeId == $iPId)?'selected':''?>><?=$sPDefect?></option> 
<?
                                               }
?>
                                            </select>
                                        </td>
					<td width="100" align="center">
                                            <input type="text" name="LSamples[]" size="5" value="<?=$iSampleNumber?>" required="">
                                        </td>
                                        <td width="200" align="center">
                                            <input type="file" name="LImages[]">
<?
                                                if ($sDefectPicture != "" && @file_exists($sPackagingDir.$sDefectPicture))
                                                {
?>
                                            <br/><span>(<a href="<?= $sPkPicsDir ?><?= $sDefectPicture ?>" class="lightview"><?= $sDefectPicture ?></a>)&nbsp;</span>
						  <input type="hidden" name="LPrevPicture[]" value="<?= $sDefectPicture ?>">
<?
                                                } else {
?>
                                                  <input type="hidden" name="LPrevPicture[]" value="">
<?
                                                }
?>
                                        </td>
                                        <td width="50" align="center"><img src="images/icons/delete.gif" width="16" height="16" alt="Delete" title="Delete" style="cursor:pointer;" onclick="DeletePLDefect(<?=$i+1?>,<?=$iTableId?>,'L')" /></td>
			      </tr>
<?
                                }
                        }
                        else
                        {
?>
                                <tr style="line-height:0px;">
                                    <td width="50" align="center">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td width="100" align="center">&nbsp;</td>
                                    <td width="200" align="center">&nbsp;</td>
                                    <td width="50" align="center">&nbsp;</td>
                                </tr>
<?
                        }
?>
			    </table>
                                </div>
                            
                                <div class="qaButtons">
				  <input type="button" value="" class="btnAdd" title="Add Packaging Defect" onclick="addPLDefect('L');" />
                                 <input id="BtnSave" value="" class="btnSave" title="Save" onclick="return validateForm( );" type="submit">
				</div>
                                <!-- Labeling Defects Ends -->