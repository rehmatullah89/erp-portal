<?php
	@require_once("../../requires/session.php");
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2       = new Database( );

	$User   = IO::intValue('User');
	$Vendor = IO::intValue("Vendor");


	$aResponse = array( );


	$sSQL = "SELECT * FROM tbl_users WHERE (email LIKE '%@apparelco.com%' OR email LIKE '%@3-tree.com%') AND status='A' ORDER BY name";

		$objDb->query($sSQL);

		$iCount = $objDb->getCount( );

		for ($i = 0; $i < $iCount; $i ++)
		{

			$userData = array();


			$userData["userName"] = $objDb->getField($i, 'name');
			$userData["userID"]   = $objDb->getField($i, 'id');

			$iUser = $objDb->getField($i, 'id');

			$iDesignation   = $objDb->getField($i, 'designation_id');


			$sSQL = "SELECT designation, department_id, reporting_to FROM tbl_designations WHERE id='$iDesignation'";
			$objDb2->query($sSQL);

			$iDepartment   = $objDb2->getField(0, 'department_id');

			$sDepartment  = getDbValue("department", "tbl_departments", "id='$iDepartment'");

			$sDepartment = $sDepartment=="0"?"Not Available":$sDepartment;

			$userData["userDepartment"]   = $sDepartment;

			$sPicture = $objDb->getField($i, 'picture');

			//print "../".$sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture;

			if ($sPicture == "" || !@file_exists("../".$sBaseDir.USERS_IMG_PATH.'thumbs/'.$sPicture))
			$sPicture = "default.jpg";

			$today = date('Y-m-d');

			//$sSQL = "SELECT time_in, time_out FROM tbl_attendance WHERE user_id='$iUser' and date='$today'";

			$timeIn = getDbValue("time_in", "tbl_attendance", "user_id='$iUser' and date='$today'");
			$timeOut = getDbValue("time_out", "tbl_attendance", "user_id='$iUser' and date='$today'");

			//print $sSQL;
			//exit;

			if($timeIn == "" && $timeOut ==""){

				$userData["timeOut"]   = "00:00:00" ;
				$userData["timeIn"]    = "00:00:00" ;

			}else{

				$userData["timeOut"]   = $timeOut;
				$userData["timeIn"]    = $timeIn;
			}


			$userData["userImage"] = 'http://portal.3-tree.com/'.USERS_IMG_PATH."thumbs/".$sPicture;

			$aResponse[] = $userData;
		}

	print json_encode($aResponse);
	$objDb2->close( );
	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>