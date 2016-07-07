<?php
(INAPP !== true) && die('Error !');

function getConfig($data = '') {
    
    $date = (isset($date) && !empty($date)) ? $data : date('Y-m-d H:i:s', TIME);
    
    if($date < '2015-11-30 08:00:00') {
        $config = array(
            'point' => 10, 
            'limit' => 10
        );
    } elseif($date >= '2015-11-30 08:00:00' && $date < '2015-12-14 08:00:00') {
        $config = array(
            'point' => 50, 
            'limit' => 20
        );
    } elseif($date >= '2015-12-14 08:00:00' && $date < '2015-12-21 08:00:00') {
        $config = array(
            'point' => 75, 
            'limit' => 30
        );
    } elseif($date >= '2015-12-21 08:00:00' && $date < '2015-12-18 08:00:00') {
        $config = array(
            'point' => 75, 
            'limit' => 30
        );
    } else {
        $config = array(
            'point' => 0, 
            'limit' => 10000000
        );
    }
    return $config;
}