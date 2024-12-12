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

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Audit   = IO::intValue("Audit");
	$Section = IO::strValue("Section");

	$sSections = array("Workforce Management" => array("Hiring Practices"                => "2,3",
													   "Factory Documentation"           => "1,7",
													   "Workers/Management Relationship" => "4,5",
													   "Work Hours"                      => "6",
													   "Total Compensation"              => "8,9"),

					   "HSE Management"       => array("Safety"                          => "10,13,14,15,20",
													   "Health"                          => "16,17,21,22",
													   "Environment"                     => "11,12,18,19"));

	$sParentSection = "";

	foreach ($sSections as $sSection => $sSubSections)
	{
		foreach ($sSubSections as $sSubSection => $sQuestions)
		{
			if ($sSubSection == $Section)
				$sParentSection = $sSection;
		}
	}


	$fScores   = array( );
	$fAvgScore = 0;

	$sSQL = "SELECT IF(rating='1', '80', IF(rating='2', '79', IF(rating='3', '60', '40'))) AS _Score,
	                (SELECT title FROM tbl_compliance_questions WHERE id=tbl_compliance_audit_details.question_id) AS _Question
	         FROM tbl_compliance_audit_details
	         WHERE audit_id='$Audit' AND FIND_IN_SET(question_id, '{$sSections[$sParentSection][$Section]}')";
	$objDb->query($sSQL);

	$iCount = $objDb->getCount( );

	for ($i = 0; $i < $iCount; $i ++)
	{
		$fScore     = $objDb->getField($i, "_Score");
		$sQuestion  = $objDb->getField($i, "_Question");
		$fAvgScore += $fScore;

		$fScores[$sQuestion] = $fScore;
	}


	$fAvgScore /= $iCount;
	$sColor     = "#a8a9ad";

	if ($fAvgScore >= 80)
		$sColor = "#00a3dc";

	else if ($fAvgScore >= 61)
		$sColor = "#01526d";

	else if ($fAvgScore >= 41)
		$sColor = "#5f91a8";
?>
	<chart caption='<?= $Section ?>' numVDivLines='10' yAxisMinValue='0' yAxisMaxValue='100' formatNumberScale='0' showValues='0' showLabels='1' labelDisplay='ROTATE' showLegend='0' chartBottomMargin='5' legendPosition='BOTTOM'>
	<categories>
<?
	foreach ($fScores as $sQuestion => $fScore)
	{
?>
		<category label='<?= $sQuestion ?>' />
<?
	}
?>
		</categories>

		<dataset seriesName='Score'>
<?
	foreach ($fScores as $sQuestion => $fScore)
	{
		$sColor = "#a8a9ad";

		if ($fScore >= 80)
			$sColor = "#00a3dc";

		else if ($fScore >= 61)
			$sColor = "#01526d";

		else if ($fScore >= 41)
			$sColor = "#5f91a8";
?>
		<set value='<?= $fScore ?>' color='<?= $sColor ?>' />
<?
	}
?>
		</dataset>

		<trendlines>
		  <line toolText='Average Score: (<?= $fAvgScore ?>%)' startValue='<?= $fAvgScore ?>' displayValue='Avg. <?= $fAvgScore ?>' color='#0000ff' />
		</trendlines>
		</chart>
<?
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>