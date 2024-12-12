				<table border="1" bordercolor="#ffffff" cellpadding="0" cellspacing="0" width="100%">
				  <tr valign="top">
					<td width="50%">

					  <h2>Work-ManShip</h2>
					  <input type="hidden" name="MaxDefects" id="MaxDefects" value="<?= $MaxDefects ?>" />

					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
					    <tr>
						  <td width="140">Total GMTS Inspected<span class="mandatory">*</span></td>
						  <td width="20" align="center">:</td>
						  <td><input type="text" name="TotalGmts" id="TotalGmts" value="<?= $TotalGmts ?>" size="10" class="textbox" /> (Pcs)</td>
					    </tr>

					    <tr>
						  <td># of GMTS Defective</td>
						  <td align="center">:</td>
						  <td><?= $GmtsDefective ?> (Pcs)</td>
					    </tr>
<?
                        if($ReportId != 55)
                        {
?>
					    <tr>
						  <td>Max Allowable Defects</td>
						  <td align="center">:</td>
						  <td><?= $MaxDefects ?></td>
					    </tr>

					    <tr>
						  <td>Number of Defects</td>
						  <td align="center">:</td>
						  <td><?= (int)$iDefects ?></td>
					    </tr>

					    <tr>
						  <td>D.H.U</td>
						  <td align="center">:</td>
						  <td><?= formatNumber($fDhu) ?>%</td>
					    </tr>
<?
                        }
?>
					  </table>

					</td>

                                    <td width="50%">
                                    <h2>Quantities</h2>
<?
	$sSQL = "SELECT SUM(pc.order_qty)
	         FROM tbl_po po, tbl_po_colors pc
			 WHERE po.id=pc.po_id AND FIND_IN_SET(po.id, '$sSelectedPos') AND '$Colors' LIKE CONCAT('%', REPLACE(pc.color, ',', ' '), '%') AND pc.style_id='$Style'";
	$objDb->query($sSQL);
	
	$iOrderQty = $objDb->getField(0, 0);
?>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
                                    <tr>
                                          <td width="140">Order Qty</td>
                                          <td width="20" align="center">:</td>
                                          <td><?= $iOrderQty ?></td>                                          
                                    </tr>

                                    <tr>
                                          <td>Ship Qty</td>
                                          <td align="center">:</td>
                                          <td><input type="text" name="ShipQty" value="<?= $ShipQty ?>" size="10" class="textbox" /></td>                                          
                                    </tr>
<?
                        if($ReportId != 55)
                        {
?>
                                    <tr>
                                          <td>Re-Screen Qty</td>
                                          <td align="center">:</td>
                                          <td><input type="text" name="ReScreenQty" value="<?= $ReScreenQty ?>" size="10" class="textbox" /></td>                                          
                                    </tr>

                                    <tr>
                                          <td>Deviation</td>
                                          <td align="center">:</td>
                                          <td><?= @round((($ShipQty / $iOrderQty) * 100), 2) ?>%</td>
                                    </tr>
<?
                        }
?>
				</table>
					  

					</td>
				  </tr>
				</table>

				<br />
<?
                        if($ReportId != 55)
                        {
?>
                                <h2>Assortment</h2>
					  <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                              <tr>
                                                  <td>
                                                      <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                                        <tr>
                                                              <td width="140">Total Cartons Inspected</td>
                                                              <td width="20" align="center">:</td>
                                                              <td><?= $TotalCartons ?></td>
                                                        </tr>

                                                        <tr>
                                                              <td># of Cartons Rejected</td>
                                                              <td align="center">:</td>
                                                              <td><input type="text" name="CartonsRejected" value="<?= $CartonsRejected ?>" size="10" class="textbox" /></td>
                                                        </tr>

                                                        <tr>
                                                              <td>% Defective</td>
                                                              <td align="center">:</td>
                                                              <td><input type="text" name="PercentDecfective" value="<?= $PercentDecfective ?>" size="10" class="textbox" /></td>
                                                        </tr>

                                                        <tr>
                                                              <td>Acceptable Standard</td>
                                                              <td align="center">:</td>
                                                              <td><input type="text" name="Standard" value="<?= $Standard ?>" size="10" class="textbox" /> %</td>
                                                        </tr>

                                                        <tr>
                                                              <td>D.H.U</td>
                                                              <td align="center">:</td>
                                                              <td><?= @round((($CartonsRejected / $TotalCartons) * 100), 2) ?>%</td>
                                                        </tr>
                                                    </table>
                                                  </td>
                                                  <td>
                                                      <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td width="140">Total Cartons Required</td>
                                                            <td width="20" align="center">:</td>
                                                            <td><input type="text" name="CartonsRequired" value="<?= $CartonsRequired ?>" size="10" class="textbox" /></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Total Cartons Shipped</td>
                                                            <td align="center">:</td>
                                                            <td><input type="text" name="CartonsShipped" value="<?= $CartonsShipped ?>" size="10" class="textbox" /></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Deviation</td>
                                                            <td align="center">:</td>
                                                            <td><?= @round((($CartonsShipped / $CartonsRequired) * 100), 2) ?>%</td>
                                                        </tr>
                                                    </table>
                                                  </td>
                                              </tr>
                                          </table>
<?
                        }
?>
				<br />
				<h2>Status & Comments</h2>

				<table border="0" cellpadding="3" cellspacing="0" width="100%">
                                    <tr>
					<td width="140">Approved Sample</td>
					<td width="20" align="center">:</td>
					<td>
					  <select name="ApprovedSample">
						<option value=""></option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.ApprovedSample.value = "<?= (($ApprovedSample == "Y") ? "Yes" : (($ApprovedSample == "N") ? "No" : $ApprovedSample)); ?>";
					  -->
					  </script>
					</td>
				  </tr>
<?
                        if($ReportId != 55)
                        {
?>                                    
				  

				  <tr>
					<td width="140">Shipping Mark</td>
					<td width="20" align="center">:</td>
					<td><input type="checkbox" name="ShippingMark" value="Y" <?= (($ShippingMark == "Y") ? "checked" : "") ?> /></td>
				  </tr>

				  <tr>
					<td>Packing Check</td>
					<td align="center">:</td>
					<td><input type="checkbox" name="PackingCheck" value="Y" <?= (($PackingCheck == "Y") ? "checked" : "") ?> /></td>
				  </tr>

				  <tr>
					<td>Carton Size</td>
					<td align="center">:</td>

					<td>
					  <input type="text" name="Length" value="<?= $Length ?>" size="3" maxlength="5" class="textbox" />
					  x
					  <input type="text" name="Width" value="<?= $Width ?>" size="3" maxlength="5" class="textbox" />
					  x
					  <input type="text" name="Height" value="<?= $Height ?>" size="3" maxlength="5" class="textbox" />
					  &nbsp;
					  <select name="Unit">
						<option value="in">Inches</option>
						<option value="cm">Centimeters</option>
					  </select>

					  <script type="text/javascript">
					  <!--
						document.frmData.Unit.value = "<?= $Unit ?>";
					  -->
					  </script>
					</td>
				  </tr>
<?
}
?>
			      <tr>
				    <td width="140">Knitted (%)</td>
				    <td width="20" align="center">:</td>
				    <td><input type="text" name="Knitted" value="<?= $Knitted ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Dyed (%)</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Dyed" value="<?= $Dyed ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Cutting</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Cutting" value="<?= $Cutting ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Sewing</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Sewing" value="<?= $Sewing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Finishing</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Finishing" value="<?= $Finishing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

			      <tr>
				    <td>Packing</td>
				    <td align="center">:</td>
				    <td><input type="text" name="Packing" value="<?= $Packing ?>" size="8" maxlength="10" class="textbox" /></td>
			      </tr>

				  <tr>
				    <td>Final Audit Date</td>
				    <td align="center">:</td>

				    <td>

					  <table border="0" cellpadding="0" cellspacing="0" width="116">
					    <tr>
					  	  <td width="82"><input type="text" name="FinalAuditDate" id="FinalAuditDate" value="<?= (($FinalAuditDate != "0000-00-00") ? $FinalAuditDate : "") ?>" readonly class="textbox" style="width:70px;"  onclick="displayCalendar($('FinalAuditDate'), 'yyyy-mm-dd', this);" /></td>
						  <td width="34"><img src="images/icons/calendar.gif" width="34" height="22" alt="Pick Date" title="Pick Date" style="cursor:pointer;"  onclick="displayCalendar($('FinalAuditDate'), 'yyyy-mm-dd', this);" /></td>
					    </tr>
					  </table>

				    </td>
				  </tr>

				  <tr valign="top">
					<td>QA Comments</td>
					<td align="center">:</td>
					<td><textarea name="Comments" class="textarea" style="width:98%; height:80px;"><?= $Comments ?></textarea></td>
				  </tr>
				</table>