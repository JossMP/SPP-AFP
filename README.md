# SPP
Busca Información de Afiliado en el Sistema Privado de Pensiones (Perú)
```sh
<?php
    require ("curl.php");
    require ("spp.php");

    $search = new SPP();
    $dni="00000000";
    header('Content-type: application/json');
    echo json_encode( $search->BuscaDatosSPP($dni), JSON_PRETTY_PRINT );
?>
```
