<?php
	class SPP
	{
		var $path = "";
		function __construct()
		{
			$this->path = dirname(__FILE__);
			$this->cc = new cURL();
			$this->cc->setCookiFileLocation("cookies.txt");
			$this->cc->setReferer( "https://www.sbs.gob.pe/app/spp/Reporte_Sit_Prev/afil_datos_documento.asp?p=1" );
		}
		function getDatosSPP( $dni )
		{
			if( $dni!="" )
			{
				$data = array(
					"cmdEnviar" 	=> "Aquí",
					"numdoc" 		=> "00".$dni
				);
				
				$url = "https://www.sbs.gob.pe/app/spp/Reporte_Sit_Prev/afil_formulario.asp";
				$response = $this->cc->send( $url, $data );
				$ar = explode("\n",$response);
				$ar = array_slice($ar, 42);
				$response = implode("\n", $ar);
				if( $response )
				{
					$doc = new DOMDocument();
					$doc->strictErrorChecking = FALSE;
					libxml_use_internal_errors(true);
					$doc->loadHTML( mb_convert_encoding( $response, 'HTML-ENTITIES',  'UTF-8' ) );
					libxml_use_internal_errors( false );
					libxml_clear_errors();

					$xml = simplexml_import_dom( $doc );
					$cussp = $xml->xpath("//input[@name='cussp']/@value");
					$nomb1 = $xml->xpath("//input[@name='lprinom']/@value");
					$nomb2 = $xml->xpath("//input[@name='lsegnom']/@value");
					$apapt = $xml->xpath("//input[@name='lapepat']/@value");
					$apmat = $xml->xpath("//input[@name='lapemat']/@value");
					$coafp = $xml->xpath("//input[@name='lafpact']/@value");
					
					$detalles = $xml->xpath("//table[@id='TABLE2']/tr/td[@colspan=9]/div/table/tr");
					if( isset($cussp[0]["value"]) )
					{
						$result = array(
							"DNI" 				=> $dni,
							"CUSPP" 			=> (string)$cussp[0]["value"],
							"Nombre" 			=> (string)$nomb1[0]["value"],
							"Nombre2" 			=> (string)$nomb2[0]["value"],
							"Paterno" 			=> (string)$apapt[0]["value"],
							"Materno" 			=> (string)$apmat[0]["value"],
							"CodigoAFP" 		=> (string)$coafp[0]["value"],
							"FechaSPP" 			=> (string)$detalles[0]->td[1],
							"NombAFP" 			=> (string)$detalles[1]->td[1],
							"FechaAFP" 			=> (string)$detalles[2]->td[1],
							"FechaNacimiento" 	=> (string)$detalles[3]->td[1],
							//"CUSPP" 			=> (string)$detalles[4]->td[1],
							"Situacion" 		=> (string)$detalles[5]->td[1],
							"TipoComision" 		=> (string)$detalles[6]->td[1],
						);
						return (object)$result;
					}
				}
			}
			return false;
		}
		function search( $dni )
		{
			$response = $this->getDatosSPP( $dni );
			if( $response!=false )
			{
				return (object)array(
					"success" => true,
					"message" => "Se encuentra incorporado al Sistema Privado de Pensiones.",
					"result" => $response
				);
			}
			return (object)array(
				"success" => false,
				"message" => "No se encuentra incorporado al Sistema Privado de Pensiones."
			);
		}
	}
