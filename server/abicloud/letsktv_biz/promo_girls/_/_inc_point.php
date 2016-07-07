<?php
(INAPP !== true) && die('Error !');

function getConfig($date = '') {
    
    $date = (isset($date) && !empty($date)) ? $date : date('Y-m-d H:i:s', TIME);
    
    if($date < '2015-12-07 08:00:00') {
        $config = array(
            'point' => 5000, 
            'limit' => 20
        );
    } elseif($date >= '2015-12-07 08:00:00' && $date < '2015-12-14 08:00:00') {
        $config = array(
            'point' => 50, 
            'limit' => 30
        );
    } elseif($date >= '2015-12-14 08:00:00' && $date < '2015-12-21 08:00:00') {
        $config = array(
            'point' => 50, 
            'limit' => 30
        );
    } elseif($date >= '2015-12-21 08:00:00' && $date < '2015-12-28 08:00:00') {
        $config = array(
            'point' => 50, 
            'limit' => 30
        );
    } elseif($date >= '2015-12-28 08:00:00' && $date < '2016-01-04 08:00:00') {
        $config = array(
            'point' => 50, 
            'limit' => 30
        );
    } elseif($date >= '2016-02-01 08:00:00' && $date < '2016-02-22 08:00:00') {
        $config = array(
            'point' => 50, 
            'limit' => 10
        );
    } elseif($date >= '2016-05-09 08:00:00' && $date < '2016-05-16 08:00:00') {
        $config = array(
            'point' => 0, 
            'limit' => 10
        );
    } else {
        $config = array(
            'point' => 50, 
            'limit' => 30
        );
    }
    return $config;
}
