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
**  Software Engineer:                                                                       **
**                                                                                           **
**      Name  :  Rehmat Ullah			                                             **
**      Email :  rehmatullah@3-tree.com		                                             **
**      Phone :  +92 344 404 3675                                                            **
**      URL   :  http://www.apparelco.com                                                    **
**                                                                                           **
***********************************************************************************************
\*********************************************************************************************/

	header("Expires: Tue, 01 Jan 2000 12:12:12 GMT");
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	@require_once("../../requires/session.php");

	if ($sUserRights['Edit'] != "Y")
	{
		print "ERROR|-|You havn\'t enough Rights to modify the selected Data.\n";
		exit( );
	}

	$objDbGlobal = new Database( );
	$objDb       = new Database( );

	$Id     = IO::intValue("Id");
	$List   = IO::strValue("List");
        $Type   = IO::strValue("Type");
        $Brand  = IO::strValue("Brand");

        $sBrandSections   = getList("tbl_tnc_points", "DISTINCT section_id", "section_id", "FIND_IN_SET('$Brand', brands)");
        $sSectionsList    = getList("tbl_tnc_sections", "id", "section", "parent_id='$Id' AND id IN (". implode(",", $sBrandSections).")");
        
	if ($Id == 0)
	{
		print "ERROR|-|Invalid Section. Please select the proper Section.\n";
		exit;
	}
        else
        {
        
            $str = "OK|-|".$List."|-|";

            if($Type == 'S')
            {
                foreach($sSectionsList as $iSection => $sSection)
                {
                    $str .= "<h3><div style='padding-bottom:1px;'><input type='checkbox' name=Sections[] id='".$iSection."' value='".$iSection."' checked/><label for='".$iSection."'>".$sSection."</label></div></h3><br/>";
                }
            }
            else
            {
                foreach($sSectionsList as $iSection => $sSection)
                {
                    $str .= "<h2>{$sSection}</h2>";

                    $sCategoriesList  = getList("tbl_tnc_categories", "id", "category", (($iSection > 0) ? "section_id='$iSection'" : ""), "position");

                    foreach ($sCategoriesList as $iCategory => $sCategory)
                    {
                        $str .= "<h3>{$sCategory}</h3>";
                        $sPointsList = getList("tbl_tnc_points", "id", "point", "category_id=$iCategory AND FIND_IN_SET('$Brand', brands)");

                        foreach ($sPointsList as $iPoint => $sPoint)
                        {     
                            $str .= "<div style='padding-bottom:1px;'><input type='checkbox' name=Points[] id='".$iPoint."' value='".$iPoint."' checked/><label for='".$iPoint."'>".$sPoint."</label></div><br/>";
                        }
                    }
                }
            }
            
            print $str;
        }

	$objDb->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>