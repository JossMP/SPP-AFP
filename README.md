# SPP
Busca Información de Afiliado en el Sistema Privado de Pensiones (Perú)
```sh
<?php
    require ("curl.php");
    require ("spp.php");

    $afiliado = new SPP();
    $dni="00000000";
    header('Content-type: application/json');
    $response = $afiliado->search($dni);
    
    if( $response->success = true )
    {
		var_dump( $response->result );
    }
    else
    {
		echo "No afiliado";
    }
?>
```
