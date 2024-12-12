<?
	@require_once("requires/session.php");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );
	$objDb2      = new Database( );

	
			$sSQL = ("SELECT id, customer from tbl_po where brand_id='365' AND customer != ''");			
			$objDb->query($sSQL);
			
			$iCount = $objDb->getCount( );
			
			for($i=0; $i<=$iCount; $i++)
			{
				$iPo        = $objDb->getField($i, "id"); 
                                $sCustomer  = $objDb->getField($i, "customer"); 
				
				if(@in_array($sCustomer, array('BBB')))
                                    $sSQL = ("UPDATE tbl_po SET customer='BBB' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('BHG')))
                                    $sSQL = ("UPDATE tbl_po SET customer='BHG' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('CM','MARSHALLS CANADA')))
                                    $sSQL = ("UPDATE tbl_po SET customer='MARSHALL CANADA' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('Costco')))
                                    $sSQL = ("UPDATE tbl_po SET customer='COSTCO' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('Costco Japan')))
                                    $sSQL = ("UPDATE tbl_po SET customer='COSTCO JAPAN' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('DG')))
                                    $sSQL = ("UPDATE tbl_po SET customer='DG (Dollar General)' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('Fiesta')))
                                    $sSQL = ("UPDATE tbl_po SET customer='FIESTA' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('Home Goods','HomeGoods')))
                                    $sSQL = ("UPDATE tbl_po SET customer='HOME GOODS' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('Home Sense','HomeSense','HomeSensf')))
                                    $sSQL = ("UPDATE tbl_po SET customer='HOME SENSE' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('KATE SPADE','KS')))
                                    $sSQL = ("UPDATE tbl_po SET customer='KS (KATE SPADE)' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('Marshalls')))
                                    $sSQL = ("UPDATE tbl_po SET customer='MARSHALLS' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('Meijer')))
                                    $sSQL = ("UPDATE tbl_po SET customer='MEIJER' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('T. J. MAX','T.J.MAX','T.J.MAXX','TJ Maxx')))
                                    $sSQL = ("UPDATE tbl_po SET customer='TJ MAXX' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('TJK MAXX','TJX','TJX (TK MAXX)','TJX EUROPE','TJX MAXX','TK MAXX')))
                                    $sSQL = ("UPDATE tbl_po SET customer='TK MAX' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('TNC')))
                                    $sSQL = ("UPDATE tbl_po SET customer='TNC (TOWN & COUNTRY)' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('VIV SN')))
                                    $sSQL = ("UPDATE tbl_po SET customer='VIV SN' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('Wal Mart','Wal Mart.Com','Wallmart','Walmart','WM','WM, TNC','WM.Com')))
                                    $sSQL = ("UPDATE tbl_po SET customer='WAL MART' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('WAL MART CANADA','Walmart Canada','WM Canada')))
                                    $sSQL = ("UPDATE tbl_po SET customer='WAL MART CANADA' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('Winners','WINNERS MERCHANTS','WINNERS MERCHANTS INC','Winners Merchants INC.')))
                                    $sSQL = ("UPDATE tbl_po SET customer='WINNERS' WHERE id = '$iPo'");
                                else if(@in_array($sCustomer, array('Winner, Marshalls Canada','Winners, Marshall Canada','Winners, Marshalls Canada','Winners, Marshals Canada','Winners/Marshalls CA','WMI (Winners & Marshall Canada)')))
                                    $sSQL = ("UPDATE tbl_po SET customer='WMI (WINNERS & MARSHALL CANADA)' WHERE id = '$iPo'");
                                                                
				$objDb2->query($sSQL);
			}

	
	$objDb->close( );
	$objDb2->close( );
	$objDbGlobal->close( );

	@ob_end_flush( );
?>