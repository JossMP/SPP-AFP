<?php
    require ("curl.php");
    require ("spp.php");

    $search = new SPP();
    $dni="00000000";
    header('Content-type: application/json');
    echo json_encode( $search->BuscaDatoSPP($dni), JSON_PRETTY_PRINT );
?>
