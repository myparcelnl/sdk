<?php

if(!function_exists('dump')) {
    function dump($var) {
        var_dump($var);
        exit;
    }
}

include_once ('Helper/MyParcelCollection.php');
include_once ('Helper/MyParcelCurl.php');
include_once ('Model/MyParcelRequest.php');
include_once ('Model/MyParcelConsignment.php');
include_once ('Model/Repository/MyParcelConsignmentRepository.php');
include_once ('Model/MyParcelCustomsItem.php');