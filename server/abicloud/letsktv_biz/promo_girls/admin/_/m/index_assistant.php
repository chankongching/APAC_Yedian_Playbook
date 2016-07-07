<?php
(INAPP !== true) && die('Error !');

switch($a) {
    case 'spr':
        
    break;
    case 'sprs':
        $query = sprintf("select `d`.`id` as `uid`, `d`.`name`, `d`.`assistant`, `d`.`phone`, `d`.`status` as `d_status`, `d`.`role`, `u`.`id`, `u`.`status`, `u`.`point__all`, `point__used` from `%s%s` as `d` left join `%s%s` as `u` on `d`.`phone` = `u`.`phone` where `d`.`assistant` = '%s' and `d`.`name` != '%s' and `d`.`role` not in ('test');", 
            $C['db']['pfix'], 
            'userdata', 
            $C['db']['pfix'], 
            'users', 
            $DB->real_escape_string($_SESSION['spr_admin_name']), 
            $DB->real_escape_string($_SESSION['spr_admin_name'])
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die('code: '.$DB->errno.', error:'.$DB->error);
        while ($row = $source->fetch_assoc()) {
            $sprs[] =  $row;
        }
    default:
    break;
}

require_once V.'sprs.php';