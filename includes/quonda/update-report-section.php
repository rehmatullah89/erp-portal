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

	@require_once("../../requires/session.php");
		
	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );
	$objDb3      = new Database( );

	$Id         = IO::intValue('Id');
        $SectionId  = IO::intValue('SectionId');
        $Styles     = IO::strValue('Styles');
	
        if ($SectionId == 1)
		@include("../".$sBaseDir."includes/quonda/general-sections/save-description-and-quantity-of-product.php");
        
        else if ($SectionId == 2)
		@include("../".$sBaseDir."includes/quonda/general-sections/save-product-conformity.php");
        
        else if ($SectionId == 3)
		@include("../".$sBaseDir."includes/quonda/general-sections/save-weight-conformity.php");
        
        else if ($SectionId == 4)
		@include("../".$sBaseDir."includes/quonda/general-sections/save-ean-code.php");
        
        else if ($SectionId == 5)
		@include("../".$sBaseDir."includes/quonda/general-sections/save-assortment.php");
        
        else if ($SectionId == 6)
		@include("../".$sBaseDir."includes/quonda/general-sections/save-master-cartons.php");
        
        else if ($SectionId == 8)
		@include("../".$sBaseDir."includes/quonda/general-sections/save-signatures.php");
        
        else if ($SectionId == 13)
		@include("../".$sBaseDir."includes/quonda/general-sections/save-airway-bill.php");

	/*

	else if ($SectionId == 3)
		@include("../".$sBaseDir."includes/quonda/general-sections/save-ean-code.php");

	else if ($SectionId == 5)
		@include("../".$sBaseDir."includes/quonda/general-sections/save-master-cartons.php");

	else if ($SectionId == 6)
		@include("../".$sBaseDir."includes/quonda/general-sections/save-child-labor.php");

	

	else if ($SectionId == 8)
		@include("../".$sBaseDir."includes/quonda/general-sections/save-description-and-quantity-of-product.php");*/
	
	
	if($bFlag == true)
	{
		$_SESSION['Flag'] = "SECTION_UPDATED";
		
		$objDb->execute("COMMIT");
                 
                header("Location: edit-report-section.php?AuditId={$Id}&Section={$SectionId}");
	}
	else
	{
		$_SESSION['Flag'] = "DB_ERROR";
		
		$objDb->execute("ROLLBACK");
		header("Location: edit-report-section.php?AuditId={$Id}&Section={$SectionId}");
	}

	
	$objDb->close( );
	$objDb2->close( );
	$objDb3->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>