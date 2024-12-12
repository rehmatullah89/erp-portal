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

	@require_once("../requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Ids   = IO::strValue("Ids");
	$StyleFieldId   = IO::strValue("Style");

	if ($Ids == "")
	{
		print "ERROR|-|Invalid Brand. Please select the proper Brand.\n";
		exit;
	}

    $sStyles = array();
    
    $sSQL = "SELECT DISTINCT po.styles as _STYLE FROM tbl_qa_reports qa, tbl_po po WHERE qa.po_id = po.id AND po.styles != '' AND qa.brand_id IN ($Ids) ";

    $objDb->query($sSQL);

    $iCount = $objDb->getCount( );

    if($iCount > 0) {

	    for ($i = 0; $i < $iCount; $i ++){

	      array_push($sStyles, $objDb->getField($i, "_STYLE"));
	    }

	    $sAStyles = implode(",", $sStyles);
	    $stylesArray = explode(",", $sAStyles);
	    $stylesFilterArray = array();
	    $styleString = "";

	    foreach ($stylesArray as $styleId) {
	      
	      if(!in_array($styleId, $stylesFilterArray)){

	        $styleString .= $styleId.", ";
	        
	        array_push($stylesFilterArray, $styleId);

	      }
	    }

	    $styleString = rtrim($styleString,", ");

	    $sStylesList  = getList("tbl_styles", "id", "CONCAT(style, ' (',(select season from tbl_seasons where id=tbl_styles.sub_season_id),')')", "id IN ($styleString)");

	    print ("OK|-|".$StyleFieldId);

		  foreach($sStylesList as $id => $sStyle)
		  {

		  	if($sStyle != "")
		  		print ("|-|".$id."||".$sStyle);

		  }

    } else {
    	print ("OK|-|".$StyleFieldId."|-|");
			// print "ERROR|-|A Database Error occured. Please reload your webpage and try again.";
    }

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>