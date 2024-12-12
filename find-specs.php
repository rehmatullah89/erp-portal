<?
	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	$List = array();

	$cfile = fopen('find_styles.csv', 'r');
	while (($line = fgetcsv($cfile)) !== FALSE) 
	{

		$sOrder  = $line[0];
		$sStyle  = $line[1];
		$sSeason = $line[2];
		
		$List[$sSeason][$sStyle] = $sStyle;
	}

//	foreach($List as $iList)
//		echo count($iList)."<br/>";
	echo "<pre>";
	print_r($List);
	exit;

	fclose($cfile);

	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>