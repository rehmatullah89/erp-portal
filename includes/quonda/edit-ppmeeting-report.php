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
?>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr>
					<td width="130"><b>Audit Code</b></td>
					<td width="20" align="center">:</td>
					<td><b><?= $AuditCode ?></b></td>
				  </tr>

				  <tr>
					<td>Vendor</td>
					<td align="center">:</td>
					<td><?= $sVendor ?></td>
				  </tr>

				  <tr>
					<td>Auditor</td>
					<td align="center">:</td>
					<td><?= $sAuditor ?></td>
				  </tr>
                                  
                                  <tr>
					<td>Brand</td>
					<td align="center">:</td>
                                        <td><?= getDbValue("brand", "tbl_brands", "id='$Brand'"); ?></td>
				  </tr>

				   <tr>
					<td>Audit / Meeting Date</td>
					<td align="center">:</td>
					<td><?= $AuditDate ?></td>
				  </tr>

				  <tr valign="top">
					<td>PO<span class="mandatory">*</span></td>
					<td align="center">:</td>
					<td><input type="text" name="PO" id="PO" value="" class="textbox" size="30" maxlength="200" /></td>
				  </tr>

				  <tr valign="top">
					<td>Style<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="Style" id="Style">
						<option value=""></option>
<?
	$sStyles = getList("tbl_styles s, tbl_po_colors pc", "DISTINCT(s.id)", "s.style", "s.id=pc.style_id AND FIND_IN_SET(pc.po_id, '$sSelectedPos')", "s.style");

	foreach ($sStyles as $sKey => $sValue)
	{
?>
	  	        		<option value="<?= $sKey ?>"<?= (($sKey == $Style) ? ' selected' : '') ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Audit Stage<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AuditStage" onchange="$('Sms').value='1';">
						<option value=""></option>
<?
	foreach ($sAuditStagesList as $sKey => $sValue)
	{
		if (@strpos($_SESSION["Email"], "marksnspencer.com") !== FALSE && $sValue == "Final")
			$sValue = "Firewall";

		if ( (@strpos($_SESSION["Email"], "pelknit.com") !== FALSE || @strpos($_SESSION["Email"], "fencepostproductions.com") !== FALSE) &&
			 !@in_array($sKey, array("B", "C", "O", "F")) )
			continue;
?>
			            <option value="<?= $sKey ?>"<?= (($sKey == $AuditStage) ? " selected" : "") ?>><?= $sValue ?></option>
<?
	}
?>
					  </select>
					</td>
				  </tr>

				  <tr>
					<td>Audit Status</td>
					<td align="center">:</td>

					<td>
					  <select name="AuditStatus">
						<option value=""></option>
						<option value="1st">1st</option>
						<option value="2nd">2nd</option>
						<option value="3rd">3rd</option>
						<option value="4th">4th</option>
						<option value="5th">5th</option>
						<option value="6th">6th</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditStatus.value = "<?= $AuditStatus ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Audit Result<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AuditResult" onchange="$('Sms').value='1';">
						<option value=""></option>
						<option value="P">Pass</option>
						<option value="F">Fail</option>
						<option value="H">Hold</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditResult.value = "<?= $AuditResult ?>";
					  -->
					  </script>
					</td>
				  </tr>
				  <tr>
					<td>QA Type<span class="mandatory">*</span></td>
					<td align="center">:</td>

					<td>
					  <select name="AuditType">
						<option value="B">Bulk</option>
						<option value="BG">B-Grade</option>
						<option value="SS">Sales Sample</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.AuditType.value = "<?= $AuditType ?>";
					  -->
					  </script>
					</td>
				  </tr>

				  <tr>
					<td>Colors</td>
					<td align="center">:</td>
					<td><input type="text" name="Colors" value="<?= $Colors ?>" size="30" class="textbox" /></td>
				  </tr>
                                  
                                  <tr>
					<td>Audit Sizes</td>
					<td align="center">:</td>
                                        <td>
                                            <div id="SizesList">
                                                <div style="padding:5px;">
                                                  <table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
                                                $iSizes = @explode(",", $Sizes);


                                                $sSQL = "SELECT id, size FROM tbl_sizes WHERE id IN (SELECT DISTINCT(size_id) FROM tbl_po_quantities WHERE po_id='$PO'";

                                                if ($AdditionalPos != "")
                                                        $sSQL .= " OR po_id IN ($AdditionalPos)";

                                                $sSQL .= ") ORDER BY position";

                                                $objDb->query($sSQL);

                                                $iCount = $objDb->getCount( );

                                                for ($i = 0; $i < $iCount;)
                                                {
?>
					  <tr>
<?
                                                    for ($j = 0; $j < 10; $j ++, $i ++)
                                                    {
                                                            if ($i < $iCount)
                                                            {
                                                                    $sKey   = $objDb->getField($i, 0);
                                                                    $sValue = $objDb->getField($i, 1);
?>
                                                        <td width="25"><input type="checkbox" name="Sizes[]" value="<?= $sKey ?>" <?= ((@in_array($sKey, $iSizes)) ? "checked" : "") ?> /></td>
                                                        <td><?= $sValue ?></td>
<?
                                                            }

                                                            else
                                                            {
?>
					    <td></td>
					    <td></td>
<?
                                                            }
                                                    }
?>
					  </tr>
<?
                                                }
?>
				    </table>
				  </div>
				</div>
                                        </td>
				  </tr>
				</table>
<br />
				<h2 id="Sections" style="margin-bottom:0px;">Pre Production Planning Sections</h2>
                                 <table id="SectionsTable" border="1" bordercolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">
                                    <tr class="sdRowHeader">
					<td width="50" align="center"><b>#</b></td>
                                        <td ><b>Sections</b></td>
                                        <td width="100" ><b>Options</b></td>
                                    </tr>
                                    <?
                                $ppmeetingSections = getList("tbl_ppmeeting_sections", "id", "name", "parent_id='0' AND status='A'", "position");
                                $iCounter = 1;

                                foreach($ppmeetingSections as $iSectionId => $sSection){
?>
                                    <tr>
					<td align="center"><?=$iCounter++?></td>
					<td><?=$sSection?></td>
                                        <td>
                                            <a href="quonda/edit-ppmeeting-section.php?SectionId=<?= $iSectionId ?>&Id=<?=$Id?>&Edit=Y" class="lightview" rel="iframe" title="Section : <?= $sSection ?> :: :: width: 900, height: 650"><img src="images/icons/edit.gif" width="16" height="16" hspace="1" alt="Edit:<?=$sSection?>" title="Edit :<?=$sSection?>" /></a>&nbsp;
                                        </td>
                                    </tr>
<?
                        }
?>
                                 </table>   

                                <br/>
				<h2>Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="140">QA Comments</td>
					<td width="20" align="center">:</td>
					<td><textarea name="Comments" class="textarea" style="width:98%; height:80px;"><?= $Comments ?></textarea></td>
				  </tr>
				</table>
