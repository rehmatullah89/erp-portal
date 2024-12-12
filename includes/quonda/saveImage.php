<?php

$iAudit = $_GET["id"];
$sType  = $_GET["type"];
$sDate  = $_GET["date"];
$sAuditCode = ("S".str_pad($iAudit, 4, 0, STR_PAD_LEFT));

@list($sYear, $sMonth, $sDay) = @explode("-", $sDate);
    
@mkdir(("../../files/qa-signatures/".$sYear), 0777);
@mkdir(("../../files/qa-signatures/".$sYear."/".$sMonth), 0777);
@mkdir(("../../files/qa-signatures/".$sYear."/".$sMonth."/".$sDay), 0777);

$sSignsDir = ("../../files/qa-signatures/".$sYear."/".$sMonth."/".$sDay."/");

if(isset($GLOBALS["HTTP_RAW_POST_DATA"])){
	
	$imageData = $GLOBALS["HTTP_RAW_POST_DATA"];
	$filteredData = substr($imageData, strpos($imageData, ",")+1);
	$unencodedData = base64_decode($filteredData);
	$fp= fopen($sSignsDir.$sAuditCode."_". strtolower($sType).".jpg", 'wb');
	
	if(fwrite($fp, $unencodedData)){
		
		fclose($fp);
		echo "ok";
	}
}
?>