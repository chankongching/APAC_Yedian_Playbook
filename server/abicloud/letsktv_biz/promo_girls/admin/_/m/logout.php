<?php
(INAPP !== true) && die('Error !');

$_SESSION = array();
session_destroy();

header('Location: '.URL);
exit;