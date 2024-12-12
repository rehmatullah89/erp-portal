<?

	@require_once("../requires/session.php");

	if ($sUserRights['Add'] != "Y")
		redirect(SITE_URL, "ACCESS_DENIED");

	$objDbGlobal = new Database( );
	$objDb       = new Database( );



	class Xml2ArrayParser
	{
		/** The array created by the parser can be assigned to any variable: $anyVarArr = $domObj->array.*/
		public  $array = array();
		public  $parse_error = false;
		private $parser;
		private $pointer;

		/** Constructor: $domObj = new Xml2ArrayParser($xml); */
		public function __construct($xml)
		{
			$this->pointer =& $this->array;
			$this->parser = xml_parser_create("UTF-8");

			xml_set_object($this->parser, $this);
			xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
			xml_set_element_handler($this->parser, "tag_open", "tag_close");
			xml_set_character_data_handler($this->parser, "cdata");

			$this->parse_error = xml_parse($this->parser, ltrim($xml))? false : true;
		}


		/** Free the parser. */
		public function __destruct( )
		{
			xml_parser_free($this->parser);
		}


		/** Get the xml error if an an error in the xml file occured during parsing. */
		public function get_xml_error()
		{
			if($this->parse_error)
			{
				$errCode = xml_get_error_code ($this->parser);
				$thisError =  "Error Code [". $errCode ."] \"<strong style='color:red;'>" . xml_error_string($errCode)."</strong>\", at char ".xml_get_current_column_number($this->parser) . "	on line ".xml_get_current_line_number($this->parser)."";
			}

			else
				$thisError = $this->parse_error;

			return $thisError;
		}


		private function tag_open($parser, $tag, $attributes)
		{
			$this->convert_to_array($tag, 'attrib');
			$idx=$this->convert_to_array($tag, 'cdata');

			if(isset($idx))
			{
				$this->pointer[$tag][$idx] = Array('@idx' => $idx,'@parent' => &$this->pointer);
				$this->pointer =& $this->pointer[$tag][$idx];
			}

			else
			{
				$this->pointer[$tag] = Array('@parent' => &$this->pointer);
				$this->pointer =& $this->pointer[$tag];
			}

			if (!empty($attributes))
			{
				$this->pointer['attrib'] = $attributes;
			}
		}


		/** Adds the current elements content to the current pointer[cdata] array. */
		private function cdata($parser, $cdata)
		{
			$this->pointer['cdata'] = trim($cdata);
		}


		private function tag_close($parser, $tag)
		{
			$current = & $this->pointer;

			if(isset($this->pointer['@idx']))
			{
				unset($current['@idx']);
			}

			$this->pointer = & $this->pointer['@parent'];
			unset($current['@parent']);

			if(isset($current['cdata']) && count($current) == 1)
			{
				$current = $current['cdata'];
			}

			else if(empty($current['cdata']))
			{
				unset($current['cdata']);
			}
		}


		/** Converts a single element item into array(element[0]) if a second element of the same name is encountered. */
		private function convert_to_array($tag, $item)
		{
			if(isset($this->pointer[$tag][$item]))
			{
				$content = $this->pointer[$tag];
				$this->pointer[$tag] = array((0) => $content);
				$idx = 1;
			}

			else if (isset($this->pointer[$tag]))
			{
				$idx = count($this->pointer[$tag]);

				if(!isset($this->pointer[$tag][0]))
				{
					foreach ($this->pointer[$tag] as $key => $value)
					{
						unset($this->pointer[$tag][$key]);
						$this->pointer[$tag][0][$key] = $value;
					}
				}
			}

			else
				$idx = null;

			return $idx;
		}
	}




	$sXml = "";

	if ($_FILES['XmlFile']['name'] != "")
		$sXml = @file_get_contents($_FILES['XmlFile']['tmp_name']);


	$sXml = str_replace("°", "", $sXml);
	$sXml = str_replace("&amp;", "-and-", $sXml);



	if ($sXml == "")
		$_SESSION["Flag"] = "ERROR";

	else
	{
		$objDom  = new Xml2ArrayParser($sXml);
		$objXml = $objDom->array;

		if ($objDom->parse_error)
			$_SESSION["Flag"] = "ERROR"; // $objDom->get_xml_error();

		else
		{
			$InspectionDate = $objXml['my:myFields']['my:head']['my:v1'];
			$Article        = $objXml['my:myFields']['my:head']['my:v2'];
			$Ian            = $objXml['my:myFields']['my:head']['my:v6'];
			$Service        = $objXml['my:myFields']['my:head']['my:v9'];
			$Quantity       = $objXml['my:myFields']['my:head']['my:v12'];
			$Shipments      = $objXml['my:myFields']['my:head']['my:v13'];

			$Brand          = $objXml['my:myFields']['my:cover']['my:coverv1'];
			$Supplier       = $objXml['my:myFields']['my:cover']['my:coverv2'];
			$Factory        = $objXml['my:myFields']['my:cover']['my:coverv3'];
			$ContactPerson  = $objXml['my:myFields']['my:cover']['my:coverv4'];
			$FactoryAddress = $objXml['my:myFields']['my:cover']['my:coverv5'];
			$FactoryCountry = $objXml['my:myFields']['my:cover']['my:coverv6'];
			$Email          = $objXml['my:myFields']['my:cover']['my:coverv8'];
			$Phone          = $objXml['my:myFields']['my:cover']['my:coverv10'];
			$InspectorEmail = $objXml['my:myFields']['my:cover']['my:coverv12'];
			$Notes          = $objXml['my:myFields']['my:cover']['my:coverv14'];
			$Latitude       = $objXml['my:myFields']['my:cover']['my:Longitude'];
			$Longitude      = $objXml['my:myFields']['my:cover']['my:Latitude'];



			$sStagesList       = getList("tbl_audit_stages", "id", "stage");
			$sDestinationsList = getList("tbl_shipping_ports", "id", "port_name");


            $iSupplier  = IO::strValue("Supplier");
			$Factory    = trim($Factory,".");
			$Brand      = trim($Brand, ".");

			$Factory = str_replace("-and-", "&", $Factory);
			$Brand = str_replace("-and-", "&", $Brand);

			$iBrand            = (int)getDbValue("id", "tbl_brands", "parent_id>'0' AND brand LIKE '$Brand'");
			$iFactory          = (int)getDbValue("id", "tbl_vendors", "parent_id='0' AND vendor LIKE '$Factory'");



			$iMainStage        = 0;
			$iSubStage         = 0;
			$sDestinations     = "";
			$Article           = "";
			$SampleFor         = "";
			$Others            = "";
			$ShippingDate      = $InspectionDate;


			foreach ($sStagesList as $iStage => $sStage)
			{
				if ($sStage == $Service)
				{
					$iMainStage = $iStage;

					break;
				}
			}


			foreach ($objXml['my:myFields']['my:descriptionOfProduct']['my:group3']['my:group4'] as $sCountryBlock)
			{
				foreach ($sDestinationsList as $iPort => $sPort)
				{
					if (strtolower($sPort) == strtolower($sCountryBlock['my:dopv3']))
					{
						if ($sDestinations != "")
							$sDestinations .= ",";

						$sDestinations .= $iPort;
					}
				}
			}



			if (getDbValue("COUNT(1)", "tbl_bookings", "brand_id='$iBrand' AND supplier_id='$iSupplier' AND factory_id='$iFactory' AND article='$Article' AND ian='$Ian' AND inspection_date='$InspectionDate' AND stage_id='$iMainStage' AND sub_stage_id='$iSubStage'") == 1)
				$_SESSION['Flag'] = "BOOKING_EXISTS";

			else
			{
				$sSQL  = "INSERT INTO tbl_bookings SET brand_id        = '$iBrand',
													   supplier_id     = '$iSupplier',
													   factory_id      = '$iFactory',
													   article         = '$Article',
													   ian             = '$Ian',
													   destinations    = '$sDestinations',
													   quantity        = '$Quantity',
													   shipments       = '$Shipments',
													   shipping_date   = '$ShippingDate',
													   inspection_date = '$InspectionDate',
													   stage_id        = '$iMainStage',
													   sub_stage_id    = '$iSubStage',
													   sample_for      = '$SampleFor',
													   others          = '$Others',
													   notes           = '$Notes',
													   device_id       = '',
													   created_at      = NOW( ),
													   created_by      = '{$_SESSION['UserId']}',
													   modified_at     = NOW( ),
													   modified_by     = '{$_SESSION['UserId']}'";

				if ($objDb->execute($sSQL) == true)
					redirect($_SERVER['HTTP_REFERER'], "BOOKING_ADDED");

				else
					$_SESSION['Flag'] = "DB_ERROR";
			}
		}
	}


	$objDb->close( );
	$objDbGlobal->close( );

	backToForm( );

	@ob_end_flush( );
?>