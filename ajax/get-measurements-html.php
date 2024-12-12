<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2015 (C) Triple Tree                                                           **
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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2       = new Database( );

	$Id         = IO::intValue("AuditId");
        $iStyle     = IO::intValue("StyleId");
        $sSize      = IO::strValue("Size");
        $iSampleId  = IO::intValue("SampleId");
	$iSampleNo  = IO::intValue("SampleNo");
?>
<tr bgcolor='#808080'>
            <td align="center" style="color:gray;"><?= (int)getDbValue("COUNT(1)", "tbl_qa_report_samples", "audit_id='$Id'")?></td>
            <td><?= getDbValue("style", "tbl_styles", "id='$iStyle'")?></td>
            <td><?=$sSize?></td>
            <td align="center"><?=$iSampleNo?></td>
            <td align="center">
                <a href="includes/quonda/general-sections/edit-sample-measurements.php?AuditId=<?=$Id?>&SampleId=<?=$iSampleId?>" class="lightview" rel="iframe" title="Sample Measurements :: :: width: 900, height: 650"><img src="images/icons/edit.gif" alt="Edit" title="Edit" width="16" hspace="1" height="16"></a>                                                                                                                                                                                                                                <a href="includes/quonda/delete-measurement-specs.php?QaSampleId=332184&amp;AuditId=508937" title="Delete Measurement Specs" onclick="return confirm('Are your sure, you want to delete sample specs for specified color/size and style?');"><img src="images/icons/delete.gif" alt="Delete" title="Delete" style="cursor:pointer;" width="16" height="16"></a>&nbsp;
            </td>
</tr>
<?
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>