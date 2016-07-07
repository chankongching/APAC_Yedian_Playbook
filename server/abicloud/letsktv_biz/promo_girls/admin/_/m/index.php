<?php
(INAPP !== true) && die('Error !');

if(ROLE == 'admin') {
    $a      = (isset($_GET['a'])    && in_array(trim($_GET['a']), array('sprs'))) ? trim($_GET['a']) : 'sprs';
    $id     = (isset($_GET['id'])   && intval($_GET['id']) > 0) ? intval($_GET['id'])   : null;
    require_once M.$m.'_admin.php';
} elseif(ROLE == 'assistant') {
    $a      = (isset($_GET['a'])    && in_array(trim($_GET['a']), array('sprs'))) ? trim($_GET['a']) : 'sprs';
    $id     = (isset($_GET['id'])   && intval($_GET['id']) > 0) ? intval($_GET['id'])   : null;
    require_once M.$m.'_assistant.php';
}