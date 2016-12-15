<?php
	class SPP
	{
		var $path = "";
		function __construct()
		{
			$this->path = dirname(__FILE__);
			$this->cc = new cURL(false);
		}
		function BuscaDatoSPP($dni="")
		{
			$rtn = array("DNI"=>$dni);
			if( $dni!="" )
			{
				$data = array(
					"numdoc"=>"00".$dni
				);
				$url = "http://www.sbs.gob.pe/app/spp/Reporte_Sit_Prev/afil_formulario.asp";
				$this->cc->referer($url);
				$Page = $this->cc->post($url,$data);
				if($Page)
				{
					$Page = utf8_encode($Page);
					$busca = array(
						"CUSPP"=>"cussp",
						"Nombre"=>"lprinom",
						"Nombre2"=>"lsegnom",
						"Paterno"=>"lapepat",
						"Materno"=>"lapemat",
						"CodigoAFP"=>"lafpact"
					);
					foreach($busca as $i=>$v)
					{
						$patron='/<input type="hidden" name="'.$v.'" value="(.*)" \/>/';
						$output = preg_match_all($patron, $Page, $matches, PREG_SET_ORDER);
						if(isset($matches[0]))
						{
							$rtn += array($i=>trim($matches[0][1]));
						}
					}
					$busca = array(
						"FechaAfiliacion" 		=> "Se encuentra afiliado\(a\) al SPP desde",
						"NombreAFP"	 			=> "Actualmente se encuentra afiliado\(a\) a",
						"FechaNacimiento"		=> "Registra como fecha de nacimiento",
						"Situacion" 			=> "Su situaci&oacute;n actual es",
						"TipoComision" 			=> "Su Tipo de Comisi&oacute;n es"
					);
					foreach($busca as $i=>$v)
					{
						$patron='/'.$v.'[ ]*<\/td>[\t]+[ ]+\r\n[ ]+<td colspan="1" align=("right"|right) class="APLI_txtActualizado" >(.*)<\/td>/';
						$output = preg_match_all($patron, $Page, $matches, PREG_SET_ORDER);
						if(isset($matches[0]))
						{
							$rtn[$i] = trim(preg_replace( "[\s+]"," ", ($matches[0][2]) ) );
						}
					}
				}
				if(count($rtn) > 2)
				{
					return $rtn;
				}
			}
			return false;
		}
	}
