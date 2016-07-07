<?php
(INAPP !== true) && die('Error !');

// 权限检查
$query = sprintf("select `id`, `uid`, `openid`, `nickname`, `name`, `status` from `%s%s` where `openid`='%s' limit 1;", 
    $C['db']['pfix'],
    'users',
    $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid'])
);
$source = $DB->query($query);
$DB->errno > 0 && die(json_encode(array(
    'status'    => 0, 
    'code'      => $DB->errno, 
    'error'     => $DB->error
)));
if ($source->num_rows < 1) {
    die(json_encode(array(
        'status'    => 0, 
        'error'     => '系统内部错误，请联系管理员。'
    )));
}
while ($row = $source->fetch_assoc()) {
    if($row['status'] == 0) {
        header('Location: index.php');exit;
        die('未绑定用户无法签到。');
    }
    $user_qr = $row;
}

$ktvs = '{"\u4e2d\u9910\u9986":[["ZCG000000","\u5176\u4ed6"],["ZCG000001","\u6f6e\u5174\u6f6e\u83dc\u9986"],["ZCG000002","\u5927\u897f\u95e8"],["ZCG000003","\u5510\u79be"],["ZCG000004","\u6d77\u73cd\u574a"],["ZCG000005","\u9f0e\u79be\u7c73"],["ZCG000006","\u5f3a\u54e5"],["ZCG000007","\u963f\u5f3a\u9178\u83dc\u9c7c"],["ZCG000008","\u6d77\u6ca3\u7f57\u8bb0"],["ZCG000009","\u9e4f\u5347"],["ZCG000010","\u6e29\u5dde\u7279\u8272"],["ZCG000011","\u68cb\u751f"],["ZCG000012","\u996e\u9971\u98df\u9189"],["ZCG000013","\u5929\u6cc9\u7f8e\u98df"],["ZCG000014","\u5929\u53f6\u9c7c\u5e84"],["ZCG000015","\u7cbe\u6b66\u70e7\u70e4"],["ZCG000016","\u6f6e\u6c55\u963f\u624d\u5927\u6392\u6863"]],"\u5176\u4ed6":[["XKTV00000","\u5176\u4ed6"]],"\u4ece\u5316\u533a":[["XKTV00037","\u5e7f\u84c4\u7535\u7ad9\u4e13\u5bb6\u6751\u9152\u5e97"],["XKTV00038","\u661f\u8f89KTV"],["XKTV00039","\u559c\u6ee1\u5802KTV"],["XKTV00040","\u62c9\u6590\u6b4cKTV"],["XKTV00041","\u7545\u60f3\u56fd\u5ea6(\u4ece\u5316\u5e97)"],["XKTV00046","\u9038\u6cc9\u56fd\u9645\u5927\u9152\u5e97"],["XKTV00047","\u51b2\u51fb\u6ce2\u9152\u57ceKTV"],["XKTV00048","\u9f99\u6c5fKTV"],["XKTV00049","\u7fe0\u5c9b\u5ea6\u5047\u6751"],["XKTV00050","\u559c\u6765\u7545\u91cf\u8d29\u5f0fKTV"]],"\u5357\u6c99\u533a":[["XKTV00008","\u5bb6\u4e50\u8fea\u91cf\u8d29\u5f0fKTV(\u9ec4\u9601\u5e97)"],["XKTV00009","\u80dc\u9f99\u5bcc\u6c27\u91cf\u8d29\u5f0fKTV(\u5927\u5c97\u5174\u4e1a\u8def\u5e97)"],["XKTV00010","\u597d\u58f0\u97f3KTV(\u5357\u6c99\u5e97)"],["XKTV00011","\u540c\u5e86\u6b4c\u4f1a\u91cf\u8d29\u5f0fKTV"]],"\u589e\u57ce\u533a":[["XKTV00051","\u5b9d\u4e50\u8fea\u91cf\u8d29\u5f0fKTV "],["XKTV00052","\u7f18\u4e50\u8fea\u91cf\u8d29\u5f0fKTV"],["XKTV00053","KING PARTY\u91cf\u8d29\u5f0fKTV(\u4e1c\u6c47\u57ce\u5e97) "],["XKTV00056","\u9b45\u529b\u6d3e\u5bf9\u91cf\u8d29\u5f0fKTV(\u65b0\u5858\u5e97) "],["XKTV00058","\u6e29\u838eKTV"],["XKTV00059","\u91d1\u551b\u91cf\u8d29\u5f0fKTV"],["XKTV00060","\u51ef\u4e50\u6c47KTV\u91cf\u8d29\u5f0f "],["XKTV00061","\u4e50\u9ea6\u91cf\u8d29\u5f0fKTV "]],"\u5929\u6cb3\u533a":[["XKTV00183","\u559c\u805a"],["XKTV00184","\u5802\u4f1a(\u5929\u6cb3\u5e97)"],["XKTV00185","\u5929\u9f99\u6b4c\u6c47\u91cf\u8d29\u5f0fKTV(\u4f53\u80b2\u4e2d\u5fc3\u5e97)"],["XKTV00186","\u5323\u5b50KBOX KTV(\u73e0\u6c5f\u65b0\u57ce\u5e97)"],["XKTV00187","K\u6b4c\u738b(\u5929\u6cb3\u4e1c\u8def\u5e97) "],["XKTV00189","\u5e7f\u4e1c\u6f6e\u6c47PTV(\u6f6e\u6c47PartyKTV)"],["XKTV00190","\u51ef\u4e50\u4f1a\u91cf\u8d29KTV"],["XKTV00193","\u6b4c\u835f\u91cf\u8d29KTV(\u5143\u5c97\u5e97)"],["XKTV00194","\u6b4c\u835fPTV(\u9f99\u6d1e\u5e97) "],["XKTV00195","\u62c9\u9614\u97f3\u4e50ptv KTV "],["XKTV00196","\u65b0\u529bKTV"],["XKTV00197","\u9f99\u4f1a\u9152\u5e97KTV"],["XKTV00198","\u62fc\u97f3\u91cf\u8d29\u5f0fKTV "],["XKTV00200","\u76db\u6b4cKTV(\u957f\u5174\u5e97\uff09"],["XKTV00202","\u6cb3\u68e0\u6708\u8272KTV"],["XKTV00203","\u90c1\u91d1\u9999KTV"],["XKTV00204","COCO KTV(\u8f66\u9642\u5e97)"],["XKTV00205","\u6f6e\u6d3eKTV"],["XKTV00206","\u7231\u5c1a\u91cf\u8d29KTV(\u4e1c\u5703\u5e97)"],["XKTV00213","\u91d1\u7545KTV(\u5929\u6cb3\u5e97)"],["XKTV00214","\u5e1d\u58f9\u56fd\u9645\u91cf\u8d29\u5f0fKTV"],["XKTV00221","\u8c6a\u60c5KTV"],["XKTV00222","\u68e0\u4f1a(\u5409\u5c71\u5e97)"],["XKTV00224","\u4e50\u9986\u4f1aKTV "],["XKTV00225","\u68e0\u4f1aKTV \u6216 \u54c6\u96f7\u54aa\u91cf\u7248\u5f0fKTV"],["XKTV00226","\u9999\u91cf\u8d29\u5f0fKTV"],["XKTV00227","\u7ec5\u5531\u6c47"],["XKTV00230","ALL IN KTV CLUB"],["XKTV00234","\u540d\u8c6aKTV(\u5929\u6cb3\u5e97)"],["XKTV00244","\u91d1\u5ea7KTV"],["XKTV00246","Crystal\u9e6d\u97f3\u670b\u4e3b\u9898KTV"],["XKTV00248","\u9177K KTV"]],"\u6d77\u73e0\u533a":[["XKTV00158","TOP\u661f\u6d3e\u5bf9KTV(\u5e7f\u5dde\u5e97) "],["XKTV00159","\u4e3d\u5f71\u76db\u4f1aKTV"],["XKTV00160","\u6b4c\u795eKTV(\u5de5\u4e1a\u5927\u9053\u5e97) "],["XKTV00161","\u661f\u6b4c\u4f1a\u91cf\u8d29\u5f0fKTV"],["XKTV00162","\u661f\u90fd\u4f1aKTV"],["XKTV00163","\u540d\u661f\u4f1a\u91cf\u8d29\u5f0fKTV"],["XKTV00164","\u6566\u7687KTV"],["XKTV00165","KK\u65b0\u65f6\u5c1a\u91cf\u8d29\u5f0fKTV"],["XKTV00166","\u7559\u58f0\u4f1a\u6240KTV"],["XKTV00167","\u732a\u7b3c\u57ce\u5be8\u91cf\u8d29\u5f0fKTV"],["XKTV00168","\u52b2\u6d3e\u91cf\u7248\u5f0fKTV"],["XKTV00169","\u795e\u66f2KTV"],["XKTV00173","KPARTY\u91cf\u8d29\u5f0fKTV(\u8d64\u5c97\u5e97) "],["XKTV00174","\u91d1\u77ff\u98df\u5531 "],["XKTV00175","\u5fc5\u7231\u6b4c\u91cf\u8d29KTV"],["XKTV00176","\u5802\u4f1a(\u6d77\u5370\u5e97)"],["XKTV00177","\u65b0\u805a\u70b9\u91cf\u8d29KTV"],["XKTV00178","\u9999\u5c9b\u5c0f\u7b51(\u6c5f\u5357\u5927\u9053\u4e2d\u5e97)"],["XKTV00179","\u9189\u5ba2\u5427KTV"],["XKTV00180","\u7cd6\u679cKTV(\u524d\u8fdb\u8def\u5e97)"],["XKTV00182","\u98de\u626c88\u9152\u5427(\u77f3\u6eaa\u5e97) "],["XKTV00254","\u6f6e\u6d3e\u91cf\u8d29KTV(\u5357\u6d32\u5e97) "],["XKTV01158","UFO-KTV"]],"\u756a\u79ba\u533a":[["XKTV00001","\u7231\u5c1aKTV(\u5e02\u6865\u5e97)"],["XKTV00002","\u7ef4\u7eb3\u65afKTV"],["XKTV00003","K\u5148\u751f\u91cf\u8d29\u5f0fKTV\uff08\u6865\u5357\u5e97\uff09"],["XKTV00004","\u7b2c\u4e03\u611f\u89c9\u91cf\u8d29\u5f0fKTV"],["XKTV00005","\u4f17\u6c47KTV"],["XKTV00006","\u6d3b\u529b\u65e0\u9650KTV(\u5927\u77f3\u5e97) "],["XKTV00012","\u97f3\u7687\u91cf\u8d29\u5f0fKTV"],["XKTV00013","\u98de\u626c88\u91cf\u8d29\u5f0fKTV(\u756a\u79ba\u5e97)"],["XKTV00015","\u5802\u4f1a(\u756a\u79ba\u5e97)"],["XKTV00016","\u6b4c\u795eKTV(\u6d1b\u6eaa\u65b0\u57ce\u5e97)"],["XKTV00018","\u540d\u661f\u4f1aKTV(\u6d1b\u6eaa\u5e97)"],["XKTV00019","\u540d\u661f\u4f1a\u91cf\u8d29\u5f0fKTV"],["XKTV00020","\u52b2\u6d3e\u91cf\u8d29\u5f0fKTV\uff08\u5357\u6751\u5e97\uff09"],["XKTV00021","\u52b2\u6d3e\u91cf\u8d29\u5f0fKTV\uff08\u949f\u6751\u5e97)"],["XKTV00022","\u52b2\u6d3e\u91cf\u8d29\u5f0fKTV\uff08\u4e1c\u6d8c\u5e97)"],["XKTV00023","\u52b2\u6d3e\u91cf\u8d29\u5f0fKTV\uff08\u5316\u9f99\u5e97)"],["XKTV00024","\u65b0\u6d3e\u6982\u5ff5KTV(\u60e0\u4fe1\u5927\u53a6\u5e97)"],["XKTV00025","\u4e50\u6d3e\u91cf\u8d29\u5f0fKTV"],["XKTV00026","\u54aa\u4e50\u91cf\u8d29KTV"],["XKTV00027","\u5b9d\u4e50\u8fea\u91cf\u8d29KTV(\u756a\u79ba\u5e97)"],["XKTV00029","\u6d77\u8c5a\u97f3\u6c27\u5427\u91cf\u8d29\u5f0fKTV "],["XKTV00030","\u661f\u5929\u5730\u91cf\u8d29\u5f0fKTV (\u5357\u6751\u5e97)"],["XKTV00031","\u80dc\u9f99\u5bcc\u6c27\u91cf\u8d29KTV(\u949f\u6751\u8857\u5e97)"],["XKTV00032","\u96c5\u51e1\u8fbeKTV"],["XKTV00033","\u6b22\u6b4c\u91cf\u8d29\u5f0fKTV"],["XKTV00034","\u6b22\u5531KTV(\u756a\u79ba\u5e97)"],["XKTV00035","K\u6b4c\u738b(\u756a\u79ba\u5e97)"],["XKTV00036","\u6b22\u805a\u91cf\u8d29KTV"],["XKTV00170","ABC\u91cf\u8d29\u5f0fKTV(\u5927\u5b66\u57ce\u5e97)"],["XKTV00171","\u65b0\u6d3eKTV(\u5317\u4ead\u5e7f\u573a\u5e97)"],["XKTV00172","\u76c8\u70b9V-BOX KTV"],["XKTV00247","\u9b54\u65b9KTV(\u5357\u6751\u4e07\u8fbe\u5e97)"],["XKTV00251","\u5c1a\u8fb0\u91cf\u8d29\u5f0fKTV(\u5e02\u6865\u5e97)"],["XKTV00253","\u4e94\u7ebf\u8c31\u91cf\u8d29\u5f0fKTV"]],"\u767d\u4e91\u533a":[["XKTV00092","\u7231\u5c1aKTV(\u767d\u4e91\u5e97)"],["XKTV00093","\u7545\u4eab123\u91cf\u8d29\u5f0fKTV"],["XKTV00094","\u548f\u4e50\u6c47\u91cf\u8d29KTV(\u4f70\u4e50\u5e97)"],["XKTV00095","\u6a31\u6843\u82b1KTV"],["XKTV00096","\u6b22\u4e50\u7545\u91cf\u8d29\u5f0fKTV(\u5c97\u8d1d\u5e97)"],["XKTV00097","\u6b22\u4e50\u7545\u91cf\u8d29\u5f0fKTV(\u897f\u69ce\u5e97)"],["XKTV00099","\u987a\u666fKTV"],["XKTV00100","K\u5f71\u65f6\u5c1a\u91cf\u8d29\u5f0fKTV"],["XKTV00101","\u91d1\u5ea7KTV\uff08\u68e0\u6eaa\u5e97\uff09"],["XKTV00102","\u6f6e\u6c47KTV(\u5bcc\u529b\u6843\u56ed\u5e97) "],["XKTV00103","\u9ea6\u9738KTV"],["XKTV00104","\u7545KTV"],["XKTV00105","ABC\u91cf\u8d29\u5f0fKTV(\u677e\u5357\u5e97) "],["XKTV00106","\u98d9\u6b4c\u91cf\u8d29\u5f0fKTV "],["XKTV00107","\u94f6\u67dcKTV"],["XKTV00108","\u65b0\u6b4c\u5feb\u7ebfKTV (\u65b0\u5e02\u5e97)"],["XKTV00109","\u559c\u6b4c\u91cf\u8d29\u5f0fKTV"],["XKTV00110","\u5802\u4f1a(\u673a\u573a\u5e97)"],["XKTV00111","\u548f\u4e50\u6c47\u91cf\u8d29KTV(\u5e7f\u56ed\u5e97)"],["XKTV00112","\u9f50\u4e50\u91cf\u8d29KTV "],["XKTV00113","\u91d1\u535aKTV"],["XKTV00114","K8 \u91cf\u8d29\u5f0fKTV"],["XKTV00115","\u5bb6\u4e50\u8fea\u91cf\u8d29KTV(\u767d\u4e91\u5e97)"],["XKTV00116","\u6d77\u61acKTV"],["XKTV00117","\u5929\u7a7a\u516c\u9986KTV"],["XKTV00118","\u9753\u58f0\u91cf\u8d29\u5f0fKTV(\u8d22\u667a\u5e7f\u573a\u5e97) "],["XKTV00119","\u9753\u58f0\u91cf\u8d29\u5f0fKTV(\u4e07\u6c11\u5e97)"],["XKTV00120","\u9753\u58f0\u91cf\u8d29\u5f0fKTV(\u6c5f\u9ad8\u745e\u9686\u5e97) "],["XKTV00121","\u97f3\u4e50\u6c47\u91cf\u8d29KTV"],["XKTV00122","\u6b4c\u805a\u91cf\u8d29\u5f0fKTV"],["XKTV00123","\u591c\u5c0f\u732b\u91cf\u8d29\u5f0fPTV"],["XKTV00124","\u548f\u4e50\u4f1a\u91cf\u8d29KTV(\u767d\u5bab\u65d7\u8230\u5e97) "],["XKTV00125","\u57ce\u5e02\u751f\u6d3b\u91cf\u8d29\u5f0fKTV"],["XKTV00126","\u6b4c\u57ce\u91cf\u8d29\u5f0fKTV "],["XKTV00127","\u96f6\u8ddd\u79bb\u91cf\u8d29KTV "],["XKTV00128","\u51ef\u6b4c\u91cf\u8d29\u5f0fKTV"],["XKTV00129","\u5927\u5496KTV"],["XKTV00130","\u9b45\u529b\u91d1\u838e"],["XKTV00131","\u97f3\u4e3a\u7231KTV"],["XKTV00132","\u80dc\u9f99\u6c47\u91cf\u8d29\u5f0fKTV"],["XKTV00133","\u548f\u4e50\u6c47\u91cf\u8d29KTV(\u592a\u548c\u5e97)"],["XKTV00134","\u65b0\u6b4cKTV"],["XKTV00135","\u54c6\u6765\u54aa\u9152\u5427KTV "],["XKTV00136","\u65f6\u5c1a100-KTV(\u767d\u4e91\u5e97)"],["XKTV00137","\u540d\u9986KTV"],["XKTV00139","\u551b\u738bKTV"],["XKTV00142","\u7cd6\u4e4b\u679c\u91cf\u8d29\u5f0fKTV"],["XKTV00143","\u51ef\u6b4c\u4f1a\u91cf\u8d29\u5f0fKTV"],["XKTV00144","\u4e50\u738b"],["XKTV00146","\u4e16\u7eaa\u6b4c\u6f6e"],["XKTV00147","\u5927\u90fd\u4f1aKTV"],["XKTV00148","\u91d1\u7545(\u767d\u4e91\u5e97)"],["XKTV00151","\u597d\u83b1\u575e\u91cf\u8d29\u5f0fKTV"],["XKTV00152","\u5361\u8fea\u91cf\u8d29\u5f0fKTV "],["XKTV00153","\u597d\u5ba2\u6765\u91cf\u8d29\u5f0fKTV"],["XKTV00155","\u4e50\u6ee1\u5802"],["XKTV00156","\u91d1\u5927\u949fKTV"],["XKTV00157","\u6b4c\u5229\u4e9aKTV"],["XKTV00192","\u5361\u5361KTV "],["XKTV00199","\u7231\u7434\u6d77KTV(\u6885\u82b1\u56ed\u5e97) "],["XKTV00232","\u4eae\u6b4cKTV"],["XKTV00233","\u540c\u60a6\u6c47KTV"],["XKTV00238","\u6765\u6d3e\u5bf9PTV"],["XKTV00240","\u52b2\u6d3e\u91cf\u8d29\u5f0fKTV\uff08\u767d\u4e91\u5e97\uff09"],["XKTV00241","\u548f\u4e50\u6c47\u91cf\u8d29KTV(\u767d\u5bab\u65d7\u8230\u5e97)"],["XKTV00242","\u7545\u6b4c"],["XKTV00243","\u6b4c\u5229\u4e9a"],["XKTV00249","\u84dd\u8272\u6d3e\u5bf9"],["XKTV00252","\u540d\u8c6aKTV(\u767d\u4e91\u5e97)"]],"\u82b1\u90fd\u533a":[["XKTV00062","\u6b4c\u89c6\u8fbe\u91cf\u8d29\u5f0fKTV "],["XKTV00063","\u6b4c\u8c23KTV"],["XKTV00064","\u534e\u590f\u5a31\u4e50\u57ce "],["XKTV00066","\u597d\u4e50\u8feaKTV(\u82b1\u90fd\u5e97)"],["XKTV00067","\u597d\u58f0\u97f3(\u82b1\u90fd\u5e97)"],["XKTV00068","\u83b1\u65af\u4e50"],["XKTV00069","\u5b9d\u4e50\u8fea\u91cf\u8d29KTV(\u82b1\u90fd\u5e97)"],["XKTV00070","\u6b27\u4e4b\u8bfa\u65f6\u5c1a\u6d3e\u5bf9KTV(\u72ee\u5cad\u5e97) "],["XKTV00071","\u6b22\u5531\u91cf\u8d29KTV (\u82b1\u90fd\u5e97)"],["XKTV00072","\u5c1a\u8fb0\u91cf\u8d29\u5f0fKTV(\u82b1\u90fd\u5e97)"],["XKTV00073","99\u91d1\u67dc\u91cf\u8d29\u5f0fKTV"],["XKTV00074","\u7545\u4e50\u8feaKTV"],["XKTV00075","\u7545\u4e50\u8feaKTV(\u72ee\u5cad\u5e97) "]],"\u8354\u6e7e\u533a":[["XKTV00080","Neway\u91cf\u8d29\u5f0fKTV(\u65b0\u5149\u5e97) "],["XKTV00083","823KTV"],["XKTV00088","\u77f3\u5934\u8bb0\u91cf\u8d29\u5f0fKTV"],["XKTV00090","\u597d\u6b4cKTV"],["XKTV00098","\u6b22\u4e50\u7545(\u9ec4\u5c90\u5e97)"]],"\u841d\u5c97\u533a":[["XKTV00042","\u661f\u9645\u9152\u5e97"],["XKTV00209","\u65f6\u5c1aK-100\u4e3b\u9898\u5f0fKTV(\u5f00\u53d1\u533a\u5e97)"],["XKTV00215","\u4e00\u4f11\u6b4c\u91cf\u8d29\u5f0fKTV "]],"\u8d8a\u79c0\u533a":[["XKTV00076","\u94f6\u6d77KTV"],["XKTV00077","COCO.K(\u8d8a\u79c0\u5e97)"],["XKTV00078","\u5802\u4f1a(\u7f24\u7f24\u5e97)"],["XKTV00081","\u7231\u97f3\u4e50\uff08I music\uff09\u91cf\u8d29\u5f0fKTV"],["XKTV00082","P-PASS \u6216 \u6d3e\u6b4cKTV(\u6c5f\u6e7e\u5e97)"],["XKTV00084","\u6b4c\u795eKTV(\u76d8\u798f\u8def\u5e97) "],["XKTV00085","\u65f6\u5c1a\u6d3e\u5bf9KTV"],["XKTV00086","\u6d3b\u529b\u65e0\u9650KTV(\u4e2d\u534e\u5e7f\u573a\u5e97)"],["XKTV00087","\u6d3b\u529b\u65e0\u9650KTV(\u5317\u4eac\u8def\u5e97) "],["XKTV00089","\u6b4c\u795eKTV(\u4e1c\u5cfb\u5e7f\u573a\u5e97) "],["XKTV00181","\u6b4c\u54e5\u91cf\u8d29\u5f0fKTV"],["XKTV00228","\u767e\u5a01CEO\u91cf\u8d29KTV "]],"\u9ec4\u57d4\u533a":[["XKTV00188","\u534e\u5a01\u8fbe\u91cf\u8d29KTV"],["XKTV00191","\u4f70\u97f3KTV"],["XKTV00208","\u7ef4\u4e5f\u7eb3KTV"],["XKTV00210","020KTV"],["XKTV00211","\u5c1a\u8fb0\u91cf\u8d29\u5f0fKTV(\u9ec4\u57d4\u5e97)"],["XKTV00212","\u6b4c\u6f6e\u91cf\u8d29\u5f0fKTV "],["XKTV00216","\u5e7f\u4e50\u7eafK\u65b0\u6982\u5ff5KTV"],["XKTV00217","\u7545\u60f3\u56fd\u5ea6(\u9ec4\u57d4\u65d7\u8230\u5e97)"],["XKTV00218","\u7545\u60f3\u56fd\u5ea6\uff08\u4e1c\u533a\u5e97\uff09"],["XKTV00219","\u661f\u5ba2\u4e50partyKTV(\u9ec4\u57d4\u5e97)"],["XKTV00220","\u57d4\u90fd\u91cf\u8d29\u5f0fKTV"],["XKTV00223","\u6b4c\u4e50\u4f1aKTV"],["XKTV00231","\u7231\u5c1a\u4e3b\u9898\u91cf\u8d29KTV(\u5f00\u53d1\u533a\u4e1c\u533a\u5e97)"],["XKTV00250","\u5c1a\u8fb0\u91cf\u8d29\u5f0fKTV(\u841d\u5c97\u4e07\u8fbe\u5e97)"]]}';

// 设置开始时间
if(intval((date('H', TIME))) >= 0 && intval((date('H', TIME))) < 8) {
    $dateline_start         = strtotime(date('Y-m-d 00:00:00', $_SERVER['REQUEST_TIME']));
    $dateline_start_checkin = strtotime(date('Y-m-d 08:00:00', strtotime('-1 day')));
}
if(intval((date('H', TIME))) >= 8 && intval((date('H', TIME))) < 24) {
//     $dateline_start = strtotime(date('Y-m-d 09:00:00', $_SERVER['REQUEST_TIME']));
    $dateline_start         = strtotime(date('Y-m-d 19:00:00', $_SERVER['REQUEST_TIME']));
    $dateline_start_checkin = strtotime(date('Y-m-d 08:00:00', $_SERVER['REQUEST_TIME']));
}

// 设置结束时间
if(intval((date('H', TIME))) >= 0 && intval((date('H', TIME))) < 8) {
    $expire                     = strtotime(date('Y-m-d 07:59:59', $_SERVER['REQUEST_TIME']));
    $dateline_expire            = strtotime(date('Y-m-d 00:59:59', $_SERVER['REQUEST_TIME']));
    $dateline_expire_checkin    = strtotime(date('Y-m-d 07:59:59', $_SERVER['REQUEST_TIME']));
}
if(intval((date('H', TIME))) >= 8 && intval((date('H', TIME))) < 24) {
    $expire                     = strtotime(date('Y-m-d 07:59:59', strtotime('+1 day')));
    $dateline_expire            = strtotime(date('Y-m-d 00:59:59', strtotime('+1 day')));
    $dateline_expire_checkin    = strtotime(date('Y-m-d 07:59:59', strtotime('+1 day')));
}

if(METHOD == 'GET') {
    $query = sprintf("select `ktv_id` from `%s%s` where `openid` = '%s' and `dateline` between '%s' and '%s' order by `dateline` desc limit 1;", 
        $C['db']['pfix'],
        'checkin_history',
        $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid']),
        $DB->real_escape_string(date('Y-m-d H:i:s', $dateline_start_checkin)),
        $DB->real_escape_string(date('Y-m-d H:i:s', $dateline_expire_checkin))
    );
    $source = $DB->query($query);
    $DB->errno > 0 && die(json_encode(array(
        'status'    => 0, 
        'error'     => $DB->error
    )));
    while ($row = $source->fetch_assoc()) {
        $ktvid = $row['ktv_id'];
    }
    $ktvs_array = json_decode($ktvs);
    foreach($ktvs_array as $k => $v) {
        foreach($v as $kk => $vv) {
            if($vv[0] == $ktvid) {
                $ktv_today = $vv[1];
            }
        }
    }
    require_once V.'header.php';
    require_once V.'checkin.php';
    require_once V.'footer.php';
} else {
    $query = sprintf("select count(1) as `count` from `%s%s` where `openid` = '%s' and `dateline` between '%s' and '%s';", 
        $C['db']['pfix'],
        'checkin_history',
        $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid']),
        $DB->real_escape_string(date('Y-m-d H:i:s', $dateline_start_checkin)),
        $DB->real_escape_string(date('Y-m-d H:i:s', $dateline_expire_checkin))
    );
    $source = $DB->query($query);
    $DB->errno > 0 && die(json_encode(array(
        'status'    => 0, 
        'error'     => $DB->error
    )));
    while ($row = $source->fetch_assoc()) {
        $count = $row['count'];
    }
    if ($count > 3) {
        $data = array(
            'status' => 0,
            'error'  => '您今天已经重置过签到数据了。'
        );
    } else {
        $query = sprintf("insert into `%s%s` set `ktv_id` = '%s', `openid` = '%s', `dateline` = '%s';", 
            $C['db']['pfix'],
            'checkin_history',
            $DB->real_escape_string($_POST['ktvid']),
            $DB->real_escape_string($_SESSION['letsktv_biz_promogirls_openid']),
            $DB->real_escape_string(date('Y-m-d H:i:s', TIME))
        );
        $source = $DB->query($query);
        $DB->errno > 0 && die(json_encode(array(
            'status' => 0, 
            'error'  => $DB->error
        )));
        $data = array(
            'status' => 1
        );
    }
    exit(json_encode($data));
}