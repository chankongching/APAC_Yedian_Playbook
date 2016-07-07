<?php
namespace Business\Controller;
use Think\Controller;
class CommonController extends Controller {
    public function ssp_lists_ajax($type,$table,$columns,$primaryKey='id'){
        $sql_details = array(
            'user' => C('DB_USER'),
            'pass' =>C('DB_PWD' ),
            'db' =>C('DB_NAME'),
            'host' =>C( 'DB_HOST'),
        );
        vendor('DataTables/ssp');
        echo json_encode(\SSP::simple($type, $sql_details, $table, $primaryKey, $columns));
    }
}