<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PinYin
 *
 * @author WINGSUN
 */

/**
 * +------------------------------------------------------
 *    PHP 汉字转拼音
 * +------------------------------------------------------
 *    使用方法:
 *        $py = new PinYin();
 *        echo $py->getAllPY("输出汉字所有拼音"); //shuchuhanzisuoyoupinyin
 *        echo $py->getFirstPY("输出汉字首拼音"); //schzspy
 * +------------------------------------------------------
 */
class PinYin {

    private static $pylist = array(
        'a' => -20319, 'ai' => -20317, 'an' => -20304, 'ang' => -20295, 'ao' => -20292,
        'ba' => -20283, 'bai' => -20265, 'ban' => -20257, 'bang' => -20242, 'bao' => -20230, 'bei' => -20051, 'ben' => -20036, 'beng' => -20032, 'bi' => -20026, 'bian' => -20002, 'biao' => -19990, 'bie' => -19986, 'bin' => -19982, 'bing' => -19976, 'bo' => -19805, 'bu' => -19784,
        'ca' => -19775, 'cai' => -19774, 'can' => -19763, 'cang' => -19756, 'cao' => -19751, 'ce' => -19746, 'ceng' => -19741, 'cha' => -19739, 'chai' => -19728, 'chan' => -19725, 'chang' => -19715, 'chao' => -19540, 'che' => -19531, 'chen' => -19525, 'cheng' => -19515, 'chi' => -19500, 'chong' => -19484, 'chou' => -19479, 'chu' => -19467, 'chuai' => -19289, 'chuan' => -19288, 'chuang' => -19281, 'chui' => -19275, 'chun' => -19270, 'chuo' => -19263, 'ci' => -19261, 'cong' => -19249, 'cou' => -19243, 'cu' => -19242, 'cuan' => -19238, 'cui' => -19235, 'cun' => -19227, 'cuo' => -19224,
        'da' => -19218, 'dai' => -19212, 'dan' => -19038, 'dang' => -19023, 'dao' => -19018, 'de' => -19006, 'deng' => -19003, 'di' => -18996, 'dian' => -18977, 'diao' => -18961, 'die' => -18952, 'ding' => -18783, 'diu' => -18774, 'dong' => -18773, 'dou' => -18763, 'du' => -18756, 'duan' => -18741, 'dui' => -18735, 'dun' => -18731, 'duo' => -18722,
        'e' => -18710, 'en' => -18697, 'er' => -18696,
        'fa' => -18526, 'fan' => -18518, 'fang' => -18501, 'fei' => -18490, 'fen' => -18478, 'feng' => -18463, 'fo' => -18448, 'fou' => -18447, 'fu' => -18446,
        'ga' => -18239, 'gai' => -18237, 'gan' => -18231, 'gang' => -18220, 'gao' => -18211, 'ge' => -18201, 'gei' => -18184, 'gen' => -18183, 'geng' => -18181, 'gong' => -18012, 'gou' => -17997, 'gu' => -17988, 'gua' => -17970, 'guai' => -17964, 'guan' => -17961, 'guang' => -17950, 'gui' => -17947,
        'gun' => -17931, 'guo' => -17928,
        'ha' => -17922, 'hai' => -17759, 'han' => -17752, 'hang' => -17733, 'hao' => -17730, 'he' => -17721, 'hei' => -17703, 'hen' => -17701, 'heng' => -17697, 'hong' => -17692, 'hou' => -17683, 'hu' => -17676, 'hua' => -17496, 'huai' => -17487, 'huan' => -17482, 'huang' => -17468, 'hui' => -17454,
        'hun' => -17433, 'huo' => -17427,
        'ji' => -17417, 'jia' => -17202, 'jian' => -17185, 'jiang' => -16983, 'jiao' => -16970, 'jie' => -16942, 'jin' => -16915, 'jing' => -16733, 'jiong' => -16708, 'jiu' => -16706, 'ju' => -16689, 'juan' => -16664, 'jue' => -16657, 'jun' => -16647,
        'ka' => -16474, 'kai' => -16470, 'kan' => -16465, 'kang' => -16459, 'kao' => -16452, 'ke' => -16448, 'ken' => -16433, 'keng' => -16429, 'kong' => -16427, 'kou' => -16423, 'ku' => -16419, 'kua' => -16412, 'kuai' => -16407, 'kuan' => -16403, 'kuang' => -16401, 'kui' => -16393, 'kun' => -16220, 'kuo' => -16216,
        'la' => -16212, 'lai' => -16205, 'lan' => -16202, 'lang' => -16187, 'lao' => -16180, 'le' => -16171, 'lei' => -16169, 'leng' => -16158, 'li' => -16155, 'lia' => -15959, 'lian' => -15958, 'liang' => -15944, 'liao' => -15933, 'lie' => -15920, 'lin' => -15915, 'ling' => -15903, 'liu' => -15889,
        'long' => -15878, 'lou' => -15707, 'lu' => -15701, 'lv' => -15681, 'luan' => -15667, 'lue' => -15661, 'lun' => -15659, 'luo' => -15652,
        'ma' => -15640, 'mai' => -15631, 'man' => -15625, 'mang' => -15454, 'mao' => -15448, 'me' => -15436, 'mei' => -15435, 'men' => -15419, 'meng' => -15416, 'mi' => -15408, 'mian' => -15394, 'miao' => -15385, 'mie' => -15377, 'min' => -15375, 'ming' => -15369, 'miu' => -15363, 'mo' => -15362, 'mou' => -15183, 'mu' => -15180,
        'na' => -15165, 'nai' => -15158, 'nan' => -15153, 'nang' => -15150, 'nao' => -15149, 'ne' => -15144, 'nei' => -15143, 'nen' => -15141, 'neng' => -15140, 'ni' => -15139, 'nian' => -15128, 'niang' => -15121, 'niao' => -15119, 'nie' => -15117, 'nin' => -15110, 'ning' => -15109, 'niu' => -14941,
        'nong' => -14937, 'nu' => -14933, 'nv' => -14930, 'nuan' => -14929, 'nue' => -14928, 'nuo' => -14926,
        'o' => -14922, 'ou' => -14921,
        'pa' => -14914, 'pai' => -14908, 'pan' => -14902, 'pang' => -14894, 'pao' => -14889, 'pei' => -14882, 'pen' => -14873, 'peng' => -14871, 'pi' => -14857, 'pian' => -14678, 'piao' => -14674, 'pie' => -14670, 'pin' => -14668, 'ping' => -14663, 'po' => -14654, 'pu' => -14645,
        'qi' => -14630, 'qia' => -14594, 'qian' => -14429, 'qiang' => -14407, 'qiao' => -14399, 'qie' => -14384, 'qin' => -14379, 'qing' => -14368, 'qiong' => -14355, 'qiu' => -14353, 'qu' => -14345, 'quan' => -14170, 'que' => -14159, 'qun' => -14151,
        'ran' => -14149, 'rang' => -14145, 'rao' => -14140, 're' => -14137, 'ren' => -14135, 'reng' => -14125, 'ri' => -14123, 'rong' => -14122, 'rou' => -14112, 'ru' => -14109, 'ruan' => -14099, 'rui' => -14097, 'run' => -14094, 'ruo' => -14092,
        'sa' => -14090, 'sai' => -14087, 'san' => -14083, 'sang' => -13917, 'sao' => -13914, 'se' => -13910, 'sen' => -13907, 'seng' => -13906, 'sha' => -13905, 'shai' => -13896, 'shan' => -13894, 'shang' => -13878, 'shao' => -13870, 'she' => -13859, 'shen' => -13847, 'sheng' => -13831, 'shi' => -13658, 'shou' => -13611, 'shu' => -13601, 'shua' => -13406, 'shuai' => -13404, 'shuan' => -13400, 'shuang' => -13398, 'shui' => -13395, 'shun' => -13391, 'shuo' => -13387, 'si' => -13383, 'song' => -13367, 'sou' => -13359, 'su' => -13356, 'suan' => -13343, 'sui' => -13340, 'sun' => -13329, 'suo' => -13326,
        'ta' => -13318, 'tai' => -13147, 'tan' => -13138, 'tang' => -13120, 'tao' => -13107, 'te' => -13096, 'teng' => -13095, 'ti' => -13091, 'tian' => -13076, 'tiao' => -13068, 'tie' => -13063, 'ting' => -13060, 'tong' => -12888, 'tou' => -12875, 'tu' => -12871, 'tuan' => -12860, 'tui' => -12858, 'tun' => -12852, 'tuo' => -12849,
        'wa' => -12838, 'wai' => -12831, 'wan' => -12829, 'wang' => -12812, 'wei' => -12802, 'wen' => -12607, 'weng' => -12597, 'wo' => -12594, 'wu' => -12585,
        'xi' => -12556, 'xia' => -12359, 'xian' => -12346, 'xiang' => -12320, 'xiao' => -12300, 'xie' => -12120, 'xin' => -12099, 'xing' => -12089, 'xiong' => -12074, 'xiu' => -12067, 'xu' => -12058, 'xuan' => -12039, 'xue' => -11867, 'xun' => -11861,
        'ya' => -11847, 'yan' => -11831, 'yang' => -11798, 'yao' => -11781, 'ye' => -11604, 'yi' => -11589, 'yin' => -11536, 'ying' => -11358, 'yo' => -11340, 'yong' => -11339, 'you' => -11324, 'yu' => -11303, 'yuan' => -11097, 'yue' => -11077, 'yun' => -11067,
        'za' => -11055, 'zai' => -11052, 'zan' => -11045, 'zang' => -11041, 'zao' => -11038, 'ze' => -11024, 'zei' => -11020, 'zen' => -11019, 'zeng' => -11018, 'zha' => -11014, 'zhai' => -10838, 'zhan' => -10832, 'zhang' => -10815, 'zhao' => -10800, 'zhe' => -10790, 'zhen' => -10780, 'zheng' => -10764, 'zhi' => -10587, 'zhong' => -10544, 'zhou' => -10533, 'zhu' => -10519, 'zhua' => -10331, 'zhuai' => -10329, 'zhuan' => -10328, 'zhuang' => -10322, 'zhui' => -10315, 'zhun' => -10309, 'zhuo' => -10307, 'zi' => -10296, 'zong' => -10281, 'zou' => -10274, 'zu' => -10270, 'zuan' => -10262,
        'zui' => -10260, 'zun' => -10256, 'zuo' => -10254
    );

    /**
      +---------------------------------------------------------------------------------
     *    获取全部拼音
      +---------------------------------------------------------------------------------
     *    @return string 
      +---------------------------------------------------------------------------------
     */
    public static function getAllPY($chinese, $delimiter = '', $length = 0, $charset = 'utf-8') {
        if ($charset != 'gb2312')
            $chinese = self::_U2_Utf8_Gb($chinese);
        $py = self::zh_to_pys($chinese, $delimiter);
        if ($length) {
            $py = substr($py, 0, $length);
        }
        return $py;
    }

    /**
      +--------------------------------------------------------------------------------
     *    获取拼音首字母
      +--------------------------------------------------------------------------------
     *    @return string
      +--------------------------------------------------------------------------------
     */
    public static function getFirstPY($chinese, $charset = 'utf-8') {
        if ($charset != 'gb2312')
            $chinese = self::_U2_Utf8_Gb($chinese);
        $result = '';
        for ($i = 0; $i < strlen($chinese); $i++) {
            $p = ord(substr($chinese, $i, 1));
            if ($p > 160) {
                $q = ord(substr($chinese, ++$i, 1));
                $p = $p * 256 + $q - 65536;
            }
            $result .= substr(self::zh_to_py($p), 0, 1);
        }
        return $result;
    }

    private static function _U2_Utf8_Gb($_C) {
        $_String = '';
        if ($_C < 0x80)
            $_String .= $_C;
        elseif ($_C < 0x800) {
            $_String .= chr(0xC0 | $_C >> 6);
            $_String .= chr(0x80 | $_C & 0x3F);
        } elseif ($_C < 0x10000) {
            $_String .= chr(0xE0 | $_C >> 12);
            $_String .= chr(0x80 | $_C >> 6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        } elseif ($_C < 0x200000) {
            $_String .= chr(0xF0 | $_C >> 18);
            $_String .= chr(0x80 | $_C >> 12 & 0x3F);
            $_String .= chr(0x80 | $_C >> 6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        }
        $_str = @iconv('UTF-8', 'GB2312', $_String);
        return $_str;
    }

    private static function zh_to_py($num, $blank = '') {
        if ($num > 0 && $num < 160) {
            return chr($num);
        } elseif ($num < -20319 || $num > -10247) {
            return $blank;
        } else {
            foreach (self::$pylist as $py => $code) {
                if ($code > $num)
                    break;
                $result = $py;
            }
            return $result;
        }
    }

    private static function zh_to_pys($chinese, $delimiter = ' ', $first = 0) {
        $result = array();
        for ($i = 0; $i < strlen($chinese); $i++) {
            $p = ord(substr($chinese, $i, 1));
            if ($p > 160) {
                $q = ord(substr($chinese, ++$i, 1));
                $p = $p * 256 + $q - 65536;
            }
            $result[] = self::zh_to_py($p);
            if ($first) {
                return $result[0];
            }
        }
        return implode($delimiter, $result);
    }

    /**
     * 
     * @param int $_Num 汉字编码
     * @param array $_Data 编码对照表
     * @param string $blank 空串
     * @return string 汉字拼音
     */
    private static function _PinYin($_Num, $_Data, $blank = '') {
        if ($_Num > 0 && $_Num < 160) {
            return chr($_Num);
        } elseif ($_Num < -20319 || $_Num > -10247) {
            return $blank;
        } else {
            foreach ($_Data as $k => $v) {
                if ($v <= $_Num)
                    break;
            }
            return $k;
        }
    }

    /**
     * 中文汉字转拼音
     * @param string $_String 中文字符串
     * @param boolean $first 是否只返回拼音首字母
     * @param string $delimiter 转换后的分隔符
     * @param int $length 转换后的长度
     * @param string $_Code 语言编码 UTF-8， gb2312
     * @return string 转换后的拼音
     */
    public static function getPinYin($_String, $first = false, $delimiter = '', $length = 0, $_Code = 'UTF-8') {
        $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha" .
                "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|" .
                "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er" .
                "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui" .
                "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang" .
                "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang" .
                "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue" .
                "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne" .
                "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen" .
                "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang" .
                "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|" .
                "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|" .
                "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu" .
                "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you" .
                "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|" .
                "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";

        $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990" .
                "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725" .
                "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263" .
                "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003" .
                "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697" .
                "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211" .
                "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922" .
                "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468" .
                "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664" .
                "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407" .
                "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959" .
                "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652" .
                "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369" .
                "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128" .
                "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914" .
                "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645" .
                "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149" .
                "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087" .
                "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658" .
                "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340" .
                "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888" .
                "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585" .
                "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847" .
                "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055" .
                "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780" .
                "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274" .
                "|-10270|-10262|-10260|-10256|-10254";

        $_TDataKey = explode('|', $_DataKey);
        $_TDataValue = explode('|', $_DataValue);
        $_Data = array_combine($_TDataKey, $_TDataValue);
        arsort($_Data);
        reset($_Data);
        if ($_Code != 'gb2312') {
            $_String = self::_U2_Utf8_Gb($_String);
        }
        $result = array();
        for ($i = 0; $i < strlen($_String); $i++) {
            $_P = ord(substr($_String, $i, 1));
            if ($_P > 160) {
                $_Q = ord(substr($_String, ++$i, 1));
                $_P = $_P * 256 + $_Q - 65536;
            }
            if ($first) {
                $result[] = substr(self::_PinYin($_P, $_Data), 0, 1);
            } else {
                $result[] = self::_PinYin($_P, $_Data);
            }
        }
        $_Res = implode($delimiter, $result);
        //$_Res = preg_replace("/[^A-Za-z0-9]*/", '', $_Res);
        if ($length > 0) {
            $_Res = substr($_Res, 0, $length);
        }

        return $_Res;
    }

    /**
     * 获取中文字串首字的拼音首字母
     * @param type $s0
     * @return string
     */
    public static function getFirstLetter($s0, $blank = '#') {
        $firstchar_ord = ord(strtoupper($s0{0}));
        if ($firstchar_ord >= 65 and $firstchar_ord <= 91) {
            return strtoupper($s0{0});
        }
        if ($firstchar_ord >= 48 and $firstchar_ord <= 57) {
            return $blank;
        }
        $s = iconv("UTF-8", "gb2312", $s0);
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 and $asc <= -20284)
            return "A";
        if ($asc >= -20283 and $asc <= -19776)
            return "B";
        if ($asc >= -19775 and $asc <= -19219)
            return "C";
        if ($asc >= -19218 and $asc <= -18711)
            return "D";
        if ($asc >= -18710 and $asc <= -18527)
            return "E";
        if ($asc >= -18526 and $asc <= -18240)
            return "F";
        if ($asc >= -18239 and $asc <= -17923)
            return "G";
        if ($asc >= -17922 and $asc <= -17418)
            return "H";
        if ($asc >= -17417 and $asc <= -16475)
            return "J";
        if ($asc >= -16474 and $asc <= -16213)
            return "K";
        if ($asc >= -16212 and $asc <= -15641)
            return "L";
        if ($asc >= -15640 and $asc <= -15166)
            return "M";
        if ($asc >= -15165 and $asc <= -14923)
            return "N";
        if ($asc >= -14922 and $asc <= -14915)
            return "O";
        if ($asc >= -14914 and $asc <= -14631)
            return "P";
        if ($asc >= -14630 and $asc <= -14150)
            return "Q";
        if ($asc >= -14149 and $asc <= -14091)
            return "R";
        if ($asc >= -14090 and $asc <= -13319)
            return "S";
        if ($asc >= -13318 and $asc <= -12839)
            return "T";
        if ($asc >= -12838 and $asc <= -12557)
            return "W";
        if ($asc >= -12556 and $asc <= -11848)
            return "X";
        if ($asc >= -11847 and $asc <= -11056)
            return "Y";
        if ($asc >= -11055 and $asc <= -10247)
            return "Z";
        return $blank;
    }

    /**
     * 获得视频文件的缩略图和视频长度 
     * 需要ffmpeg支持 
     * ffmpeg-php模块
     * 获得视频文件的总长度时间和创建时间 
     * //调用方法
     * $duration = getVideoTime('/usr/local/apache/htdocs/test.flv');
     * echo $duration['vtime'].'<br/>';//总长度
     * echo $duration['ctime'].'<br/>';//创建时间
     * $videoCoverName = getVideoCover('/usr/local/apache/htdocs/test.flv', 6);
     * echo $videoCoverName;//获得缩略图名称
     * 
     * @param string $file
     * @return array
     */
    public static function getVideoTime($file) {
        $vtime = exec("ffmpeg -i " . $file . " 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//"); //总长度 
        $ctime = date("Y-m-d H:i:s", filectime($file)); //创建时间 
        //$duration = explode(":",$time); 
        //$duration_in_seconds = $duration[0]*3600 + $duration[1]*60+ round($duration[2]);//转化为秒 
        return array('vtime' => $vtime,
            'ctime' => $ctime
        );
    }

    /**
     * 获得视频文件的缩略图
     * @param string $file
     * @param string $time
     * @return string|boolean
     */
    public static function getVideoCover($file, $time) {
        if (empty($time))
            $time = '1'; //默认截取第一秒第一帧 
        $strlen = strlen($file);
        $videoCover = substr($file, 0, $strlen - 4);
        $videoCoverName = $videoCover . '.jpg'; //缩略图命名 
        exec("ffmpeg -i " . $file . " -y -f mjpeg -ss " . $time . " -t 0.001 -s 320x240 " . $videoCoverName . "", $out, $status);
        if ($status == 0)
            return $videoCoverName;
        elseif ($status == 1)
            return FALSE;
    }

    /**
     *  本文作者：chinesehero@163.com
     *  获取一个汉字的拼音  
     *  用法如下：
     *  include('Pinyin.php');
     *  echo getPinyin("你好，吃了吗？");
     */
    public static function getPinyinTest($keyWord) {
        $hz['腌'] = "yan";
        $hz['嗄'] = "a";
        $hz['迫'] = "po";
        $hz['捱'] = "ai";
        $hz['艾'] = "ai";
        //此处省略6900余字
        $hz['蜱'] = "pi";
        $hz['螋'] = "sou";
        $hz['螗'] = "tang";
        $hz['螵'] = "piao";
        $hz['蟛'] = "peng";


        $result = "";
        $charArray = self::str_split_php5_utf8($keyWord);
        foreach ($charArray as $char) {
            if (!empty($hz[$char])) {
                $result = $result . $hz[$char];
            } else {
                $result = $result . $char;
            }
        }
        return $result;
    }

    private static function str_split_php5_utf8($str) {
        // place each character of the string into and array 
        $split = 1;
        $array = array();
        for ($i = 0; $i < strlen($str);) {
            $value = ord($str[$i]);
            if ($value > 127) {
                if ($value >= 192 && $value <= 223)
                    $split = 2;
                elseif ($value >= 224 && $value <= 239)
                    $split = 3;
                elseif ($value >= 240 && $value <= 247)
                    $split = 4;
            }else {
                $split = 1;
            }
            $key = NULL;
            for ($j = 0; $j < $split; $j++, $i++) {
                $key .= $str[$i];
            }
            array_push($array, $key);
        }
        return $array;
    }

}
