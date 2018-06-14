<?php
    require ("curl.php");
    require ("spp.php");
    $search = new SPP();
    $dni = "44274795";
    var_dump( $search->search( $dni ) );
?>
