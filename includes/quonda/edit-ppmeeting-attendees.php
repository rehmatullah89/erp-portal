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
	<td width="140">Total Cartons Inspected</td>
	<td width="20" align="center">:</td>
	<td><?= $iTotalCartons ?></td>
  </tr>

  <tr>
	<td># of Cartons Rejected</td>
	<td align="center">:</td>
	<td><?= $iCartonsRejected ?></td>
  </tr>

  <tr>
	<td>% Defective</td>
	<td align="center">:</td>
	<td><?= $fPercentDecfective ?></td>
  </tr>

  <tr>
	<td>Acceptable Standard</td>
	<td align="center">:</td>
	<td><?= $fStandard ?> %</td>
  </tr>

  <tr>
	<td>D.H.U</td>
	<td align="center">:</td>
	<td><?= @round(( ($fCartonsRejected / $fTotalCartons) * 100), 2) ?>%</td>
  </tr>
</table>

