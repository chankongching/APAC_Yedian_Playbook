<?php

class MediaCommand extends CConsoleCommand {

    const WEB_ROOT_URL = 'http://www.myapps.com/abiktv';

    public $interactive = true;

    /**
     * col32 - album, col33 - artist, col34 - category
     * col27 - small picture, col28 - big picture
     * 
     * col29 - kuwo album, col31 kuwo dirname
     * 
     * col30 - kuwo flag
     * 
     * @return type
     */
    public function getHelp() {

        $description = "DESCRIPTION\n";
        $description .= '    ' . "This command import song meta information from csv files.\n";
        $description .= "    convert       convert song meta information from file\n";
        $description .= "    singer        import singer information\n";
        $description .= "    category      import song category information\n";
        $description .= "    song          import song information\n";
        $description .= "    importall     import all song meta information\n";
        return parent::getHelp() . $description;
    }

    public function confirm($message, $default = false) {
        if (!$this->interactive)
            return true;
        return parent::confirm($message, $default);
    }

    /**
     * The default action.
     */
    public function actionIndex() {

        //provide the oportunity for the use to abort the request
        $message = "Please add following parameters to run: \n";
        $message .= "  convert       convert song meta information from file\n";
        $message .= "  singer        import singer information\n";
        $message .= "  category      import song category information\n";
        $message .= "  song          import song information\n";
        $message .= "  importall     import all song meta information\n";
        $message .= "Would you like to continue?";

        if ($this->confirm($message, true)) {
            echo "Please add parameters.\n";
        } else
            echo "Operation cancelled.\n";
    }

    public function actionConvert($args) {
        //provide the oportunity for the use to abort the request
        $message = "This command will convert song meta information from csv file.\n";
        $message .= "Would you like to continue?";

        //check the input from the user and continue if 
        //they indicated yes to the above question
        if ($this->confirm($message, true)) {

            if (isset($args[0]))
                $filename = $args[0];
            else
                $this->usageError('Please provide the name of the song meta file to convert.');

            $path = Yii::getPathOfAlias('application.import');
            $file = $path . DIRECTORY_SEPARATOR . $filename;
            $new_file = $path . DIRECTORY_SEPARATOR . 'new_' . $filename;
            if (!is_file($file))
                $this->usageError('Song meta file ' . $file . ' not exists.');

            // read from file line by line
            $fp = fopen($file, 'rb');
            if (FALSE !== $fp) {
                TempImport::model()->deleteAll();
                $total = 0;
                $insert_array = array();
                while (true) {
                    $result = fgets($fp);
                    if (false === $result)
                        break;

                    $line_array = explode(',', $result);
                    $array_count = count($line_array);
                    $array_count = ($array_count > 35) ? 35 : $array_count;
                    $field_array = array();
                    for ($i = 1; $i <= $array_count; $i++) {
                        $field_array['col' . $i] = $line_array[$i - 1];
                    }
                    for ($i = $array_count + 1; $i <= 29; $i++) {
                        $field_array['col' . $i] = '';
                    }
                    // 歌手
                    $field_array['col7'] = empty($field_array['col7']) ? '佚名' : trim($field_array['col7']);
                    // 类型
                    $field_array['col22'] = !empty($field_array['col22']) ? trim($field_array['col22']) : 'MTV';
                    // 歌曲分类
                    $field_array['col23'] = !empty($field_array['col23']) ? trim($field_array['col23']) : 'POP';
                    // 专辑
                    $field_array['col29'] = !empty($field_array['col29']) ? trim($field_array['col29']) : 'MTV';

                    $total++;

                    $temp_insert_array = array();
                    $temp_insert_array[] = $field_array;
                    // insert into table
                    TempImport::model()->insertSeveral($temp_insert_array);

                    echo "process " . $total . ' - ' . $line_array[0] . "\n";
                }
            } else {
                $this->usageError('Song meta file read error.');
            }
            //provide a message indicating success
            echo "Song meta information successfully converted.\n";
        } else
            echo "Operation cancelled.\n";
    }

    public function actionConvertKuwo($args) {
        //provide the oportunity for the use to abort the request
        $message = "This command will convert song meta information from csv file.\n";
        $message .= "Would you like to continue?";

        //check the input from the user and continue if 
        //they indicated yes to the above question
        if ($this->confirm($message, true)) {

            if (isset($args[0]))
                $filename = $args[0];
            else
                $this->usageError('Please provide the name of the song meta file to convert.');

            if (isset($args[1]))
                $dirname = $args[1];
            else
                $this->usageError('Please provide the name of the song meta directory to convert.');

            $path = Yii::getPathOfAlias('application.import');
            $file = $path . DIRECTORY_SEPARATOR . $filename;
            $new_file = $path . DIRECTORY_SEPARATOR . 'new_' . $filename;
            if (!is_file($file))
                $this->usageError('Song meta file ' . $file . ' not exists.');

            // read from file line by line
            $fp = fopen($file, 'rb');
            if (FALSE !== $fp) {
                TempImport::model()->deleteAll();
                $total = 0;
                $insert_array = array();
                while (true) {
                    $result = fgets($fp);
                    if (false === $result)
                        break;

                    $line_array = explode(',', $result);
                    $array_count = count($line_array);
                    $array_count = ($array_count > 35) ? 35 : $array_count;
                    $field_array = array();
                    //for ($i = 1; $i <= $array_count; $i++) {
                    //    $field_array['col' . $i] = $line_array[$i - 1];
                    //}
                    $field_array['col1'] = isset($line_array[0]) ? trim($line_array[0]) : '';
                    $field_array['col4'] = isset($line_array[1]) ? trim($line_array[1]) : '';
                    $field_array['col6'] = isset($line_array[1]) ? PinYinEx::letter(trim($line_array[1]), array('delimiter' => '')) : '';
                    $field_array['col7'] = !empty($line_array[2]) ? trim($line_array[2]) : '佚名';
                    $field_array['col29'] = !empty($line_array[3]) ? trim($line_array[3]) : 'MTV';
                    // TODO convert duration
                    $field_array['col14'] = isset($line_array[4]) ? trim($line_array[4]) : '';
                    // video path
                    $field_array['col19'] = isset($line_array[7]) ? trim($line_array[7]) : '';

                    $field_array['col27'] = isset($line_array[8]) ? trim($line_array[8]) : '';
                    $field_array['col28'] = isset($line_array[9]) ? trim($line_array[9]) : '';

                    // default setting
                    $field_array['col5'] = '0';
                    //$field_array['col6'] = '';
                    $field_array['col8'] = '0';
                    // 类型
                    $field_array['col22'] = 'MTV';
                    $field_array['col23'] = 'POP';
                    // kuwo flag
                    $field_array['col30'] = 'kuwo';
                    $field_array['col31'] = $dirname;

                    $total++;

                    $temp_insert_array = array();
                    $temp_insert_array[] = $field_array;
                    // insert into table
                    TempImport::model()->insertSeveral($temp_insert_array);

                    echo "process " . $total . ' - ' . $line_array[0] . "\n";
                }
            } else {
                $this->usageError('Song meta file read error.');
            }
            //provide a message indicating success
            echo "Song meta information successfully converted.\n";
        } else
            echo "Operation cancelled.\n";
    }

    public function actionUpdateduration($args) {
        //provide the oportunity for the use to abort the request
        $message = "This command will update song duration information from csv file.\n";
        $message .= "Would you like to continue?";

        //check the input from the user and continue if 
        //they indicated yes to the above question
        if ($this->confirm($message, true)) {

            if (isset($args[0]))
                $filename = $args[0];
            else
                $this->usageError('Please provide the name of the song duration file to convert.');

            $path = Yii::getPathOfAlias('application.import');
            $file = $path . DIRECTORY_SEPARATOR . $filename;
            if (!is_file($file))
                $this->usageError('Song duration file ' . $file . ' not exists.');

            // read from file line by line
            $fp = fopen($file, 'rb');
            if (FALSE !== $fp) {
                $total = 0;
                $insert_array = array();
                while (true) {
                    $result = fgets($fp);
                    if (false === $result)
                        break;

                    $line_array = explode(',', $result);
                    $array_count = count($line_array);
                    // get song id
                    $_song_id = '';
                    if (isset($line_array[0])) {
                        $_song_id = $line_array[0];
                    }
                    if (empty($_song_id)) {
                        echo "Song id error: $result \n";
                        continue;
                    }
                    // get songid
                    $_songid = '';
                    if (isset($line_array[1])) {
                        $_filepath = $line_array[1];
                        // get file path and filename
                        $pathheader_filter = '\\\\server\\';
                        $path_temp = str_replace($pathheader_filter, '', $_filepath);
                        $path_array = explode('\\', $path_temp);
                        $file_name = '';
                        $path_dir = '';
                        if (!empty($path_array) && isset($path_array[0]) && isset($path_array[1])) {
                            $path_dir = $path_array[0];
                            $file_name = $path_array[1];
                            $_songid = $path_dir . '-' . $_song_id;
                        }
                    }
                    if (empty($_songid)) {
                        echo "Update Song path error: $result \n";
                        continue;
                    }

                    // get duration
                    $song_duration = 0;
                    if (isset($line_array[2])) {
                        $song_duration = intval($line_array[2]);
                    } else {
                        echo "Update Song duration error: $result \n";
                        continue;
                    }

                    $total++;

                    $ret = Media::model()->updateAll(array('duration' => $song_duration), 'songid = :songid', array(':songid' => $_songid));
                    //if ($ret > 0) {
                    echo $total . ' - ' . $_songid . ' - ' . $song_duration . "\n";
                    //} else {
                    //    echo $total . ' - Error: ' . $_songid . ' - ' . $song_duration . "\n";
                    //}
                }
            } else {
                $this->usageError('Song duration file read error.');
            }
            //provide a message indicating success
            echo "Song duration information successfully converted.\n";
        } else
            echo "Operation cancelled.\n";
    }

    public function actionAlbumn($args) {
        $message = "This command will process and import albumn list.\n";
        $message .= "Would you like to continue?";

        //check the input from the user and continue if 
        //they indicated yes to the above question
        if ($this->confirm($message, true)) {
            $this->importAlbumn();
        } else
            echo "Operation cancelled.\n";
    }

    public function actionSinger($args) {
        $message = "This command will process and import singer list.\n";
        $message .= "Would you like to continue?";

        //check the input from the user and continue if 
        //they indicated yes to the above question
        if ($this->confirm($message, true)) {
            $this->importSinger();
        } else
            echo "Operation cancelled.\n";
    }

    public function actionCategory($args) {
        $message = "This command will process and import category list.\n";
        $message .= "Would you like to continue?";

        //check the input from the user and continue if 
        //they indicated yes to the above question
        if ($this->confirm($message, true)) {
            $this->importCategory();
        } else
            echo "Operation cancelled.\n";
    }

    public function actionSong($args) {
        $message = "This command will process and import song list.\n";
        $message .= "Would you like to continue?";

        //check the input from the user and continue if 
        //they indicated yes to the above question
        if ($this->confirm($message, true)) {
            if (isset($args[0]))
                $web_root_url = $args[0];
            else
                $this->usageError('Please provide the web root of abiktv such as http://yourdomain/abiktv to import.');

            $this->importSong($web_root_url);
        } else
            echo "Operation cancelled.\n";
    }

    public function actionImportall($args) {
        $message = "This command will process and import song list.\n";
        $message .= "Would you like to continue?";

        //check the input from the user and continue if 
        //they indicated yes to the above question
        if ($this->confirm($message, true)) {
            if (isset($args[0]))
                $web_root_url = $args[0];
            else
                $this->usageError('Please provide the web root of abiktv such as http://yourdomain/abiktv to import.');

            echo "\nProcess album...\n";
            $this->importAlbumn();
            echo "\nProcess artist...\n";
            $this->importSinger();
            echo "\nProcess category...\n";
            $this->importCategory();
            echo "\nProcess songs...\n";
            $this->importSong($web_root_url);
        } else
            echo "\nOperation cancelled.\n";
    }

    private function importSinger() {
        $check_command = Yii::app()->getDb()->createCommand();
        $singer_list = Yii::app()->getDb()->createCommand()->setText('SELECT DISTINCT col7 AS singer, col8 AS count FROM {{temp_import}}')->queryAll();
        $count = 0;
        $total = 0;
        $temp_insert_array = array();
        foreach ($singer_list as $key => $value) {
            $singer = empty($value['singer']) ? '佚名' : trim($value['singer']);
            $singer_count = intval($value['count']);
            if (empty($singer)) {
                continue;
            }
            $exist_songs = $check_command->setText('select id from {{artist}} where name = :name')->queryAll(true, array(':name' => $singer));
            if (!empty($exist_songs) && count($exist_songs) > 0) {
                $_singer_id = $exist_songs[0]['id'];
            } else {
                $total ++;
                $_insert_array = array('name' => $singer, 'name_chinese' => PinYinEx::pinyin($singer, array('delimiter' => '', 'accent' => false)), 'name_pinyin' => PinYinEx::letter($singer, array('delimiter' => '')), 'name_count' => $singer_count);
                $temp_insert_array[] = $_insert_array;
                if ($total > 100) {
                    Artist::model()->insertSeveral($temp_insert_array);
                    $total = 0;
                    $temp_insert_array = array();
                }
            }

            $count ++;
            //echo $count . ' ' . mb_convert_encoding($singer, 'GBK', 'UTF-8') . "\n";
            echo $count . ' ' . ($singer) . "\n";
        }
        if (count($temp_insert_array) > 0) {
            Artist::model()->insertSeveral($temp_insert_array);
        }
        // TODO: update tempimport col33 = artist_id where col7 = artist_name
        $sql = 'UPDATE {{temp_import}} a set col33 = (SELECT id FROM {{artist}} b WHERE b.name = a.col7 LIMIT 0,1 ) WHERE col33 IS NULL';
        Yii::app()->getDb()->createCommand()->setText($sql)->execute();
    }

    private function importCategory() {
        $check_command = Yii::app()->getDb()->createCommand();
        $singer_list = Yii::app()->getDb()->createCommand()->setText('SELECT DISTINCT col23 AS category FROM {{temp_import}}')->queryAll();
        $count = 0;
        $total = 0;
        $temp_insert_array = array();
        foreach ($singer_list as $key => $value) {
            $_category = empty($value['category']) ? 'POP' : trim($value['category']);
            if (empty($_category)) {
                continue;
            }

            $exist_songs = $check_command->setText('select id from {{category}} where name = :name')->queryAll(true, array(':name' => $_category));
            if (!empty($exist_songs) && count($exist_songs) > 0) {
                $_category_id = $exist_songs[0]['id'];
            } else {
                $total ++;
                $_insert_array = array('name' => $_category);
                $temp_insert_array[] = $_insert_array;
                if ($total > 100) {
                    Category::model()->insertSeveral($temp_insert_array);
                    $total = 0;
                    $temp_insert_array = array();
                }
            }

            $count ++;
            //echo $count . ' ' . mb_convert_encoding($_category, 'GBK', 'UTF-8') . "\n";
            echo $count . ' ' . ($_category) . "\n";
        }
        if (count($temp_insert_array) > 0) {
            Category::model()->insertSeveral($temp_insert_array);
        }
        // TODO: update tempimport col34 = category_id where col23 = category_name
        $sql = 'UPDATE {{temp_import}} a set col34 = (SELECT id FROM {{category}} b WHERE b.name = a.col23 LIMIT 0,1 ) WHERE col34 IS NULL';
        Yii::app()->getDb()->createCommand()->setText($sql)->execute();
    }

    private function importAlbumn() {
        $check_command = Yii::app()->getDb()->createCommand();
        $singer_list = Yii::app()->getDb()->createCommand()->setText('SELECT DISTINCT col29 AS albumn FROM {{temp_import}}')->queryAll();
        $count = 0;
        $total = 0;
        $temp_insert_array = array();
        foreach ($singer_list as $key => $value) {
            $_albumn = empty($value['albumn']) ? 'KTV' : trim($value['albumn']);
            if (empty($_albumn)) {
                continue;
            }

            $exist_songs = $check_command->setText('select id from {{albumn}} where name = :name')->queryAll(true, array(':name' => $_albumn));
            if (!empty($exist_songs) && count($exist_songs) > 0) {
                $_albumn_id = $exist_songs[0]['id'];
            } else {
                $total ++;
                $_insert_array = array('name' => $_albumn);
                $temp_insert_array[] = $_insert_array;
                if ($total > 100) {
                    Albumn::model()->insertSeveral($temp_insert_array);
                    $total = 0;
                    $temp_insert_array = array();
                }
            }
            $count ++;
            //echo $count . ' ' . mb_convert_encoding($_category, 'GBK', 'UTF-8') . "\n";
            echo $count . ' ' . ($_albumn) . "\n";
        }
        if (count($temp_insert_array) > 0) {
            Albumn::model()->insertSeveral($temp_insert_array);
        }
        // TODO: update tempimport col32 = albumn_id where col29 = albumn_name
        $sql = 'UPDATE {{temp_import}} a set col32 = (SELECT id FROM {{albumn}} b WHERE b.name = a.col29 LIMIT 0,1 ) WHERE col32 IS NULL';
        Yii::app()->getDb()->createCommand()->setText($sql)->execute();
    }

    private function importSong($web_root_url = self::WEB_ROOT_URL) {
        $query_command = Yii::app()->getDb()->createCommand();
        $update_command = Yii::app()->getDb()->createCommand();
        $check_command = Yii::app()->getDb()->createCommand();
        $temp_count = TempImport::model()->count();
        $pagesize = 5000;
        $temp_total = 0;
        for ($i = 0; $i <= $temp_count; $i += $pagesize) {

            $sql = 'SELECT col1 AS songid, col4 AS name, col5 AS name_count, col6 AS name_pinyin, col33 AS artist_id, col8 AS artist_name_count, col9 AS song_lang, col10 AS audio_count, col12 AS video_codec, col13 AS video_res, col14 AS duration, col16 AS audio_volume1, col17 AS audio_volume2, col18 AS origin_audio, col19 AS filepath, col20 AS qc_grade, col21 AS video_comment, col22 AS video_style, col23 AS song_style, col24 AS video_version, col25 AS hot_grade, col26 AS is_new, col27 AS small_pic, col28 AS big_pic, col34 AS category_id, col30 AS media_vendor, col32 AS albumn_id, col31 AS kuwo_dir, col11 AS play_count FROM {{temp_import}} limit ' . $i . ',' . $pagesize;
            $song_list = $query_command->setText($sql)->queryAll();
            $count = 0;
            $_baseurl = $web_root_url;
            $_mediaurl = (empty(Yii::app()->params['media_url']) ? $_baseurl . '/uploads/media' : Yii::app()->params['media_url']);

            $total = 0;
            $temp_insert_array = array();
            foreach ($song_list as $key => $value) {

                $_media_vendor = empty($value['media_vendor']) ? '' : $value['media_vendor'];
                $_albumn_id = empty($value['albumn_id']) ? 1 : intval($value['albumn_id']);
                $_kuwo_dir = empty($value['kuwo_dir']) ? '' : ($value['kuwo_dir']);

                $_songid = trim($value['songid']);
                $_name = trim($value['name']);
                $_name_count = $value['name_count'];
                $_name_pinyin = empty($value['name_pinyin']) ? (PinYinEx::letter($_name, array('delimiter' => ''))) : $value['name_pinyin'];
                $_name_chinese = PinYinEx::pinyin($_name, array('delimiter' => '', 'accent' => false));
                $_artist_id = empty($value['artist_id']) ? 0 : intval($value['artist_id']);
                $_artist_name_count = $value['artist_name_count'];
                $_song_lang = $value['song_lang'];
                $_audio_count = $value['audio_count'];
                $_video_codec = $value['video_codec'];
                $_video_res = $value['video_res'];
                $_duration = $value['duration'];
                $_audio_volume1 = $value['audio_volume1'];
                $_audio_volume2 = $value['audio_volume2'];
                $_origin_audio = $value['origin_audio'];
                $_filepath = $value['filepath'];
                $_qc_grade = $value['qc_grade'];
                $_video_comment = $value['video_comment'];
                $_video_style = $value['video_style'];
                $_song_style = $value['song_style'];
                $_video_version = $value['video_version'];
                $_hot_grade = $value['hot_grade'];
                $_is_new = $value['is_new'];
                $_category_id = empty($value['category_id']) ? 0 : intval($value['category_id']);

                $_play_count = empty($value['play_count']) ? 0 : intval($value['play_count']);
                $_small_pic = $value['small_pic'];
                $_big_pic = $value['big_pic'];

                if (empty($_category_id) || empty($_artist_id) || empty($_albumn_id)) {
                    echo "Import Song category or artist or albumn error: empty id!\n";
                    continue;
                }

                // get file path and filename
                if (!empty($_media_vendor) && "kuwo" == $_media_vendor) {
                    if (empty($_kuwo_dir)) {
                        echo "Import KUWO Song path error: empty directory!\n";
                        continue;
                    } else {
                        $path_dir = $_kuwo_dir;
                        $file_name = $_filepath;
                    }
                } else if (!empty($_media_vendor) && "GZKTV01" == $_media_vendor) {
                    if (empty($_kuwo_dir)) {
                        echo "Import GZKTV Song path error: empty directory!\n";
                        continue;
                    } else {
                        $path_dir = $_kuwo_dir;
                        $file_name = $_filepath;
                    }
                } else {
                    $pathheader_filter = '\\\\server\\';
                    $path_temp = str_replace($pathheader_filter, '', $_filepath);
                    $path_array = explode('\\', $path_temp);
                    $file_name = '';
                    $path_dir = '';
                    if (!empty($path_array) && count($path_array) > 1) {
                        $array_count = count($path_array);
                        $path_dir = $path_array[0];
                        $file_dir = '';
                        for ($p = 1; $p < ($array_count - 1); $p++) {
                            $file_dir .= ($path_array[$p] . DIRECTORY_SEPARATOR);
                        }

                        $file_name = $file_dir . $path_array[$array_count - 1];
                    } else {
                        echo "Import Song path error: $_filepath \n";
                        continue;
                    }
                }
                // duration
                if (!empty($_media_vendor) && "kuwo" == $_media_vendor) {
                    $_duration_second = empty($_duration) ? 0 : intval($_duration);
                } else if (!empty($_media_vendor) && "GZKTV01" == $_media_vendor) {
                    $_duration_second = empty($_duration) ? 0 : intval($_duration);
                } else {
                    $_duration_second = 0;
                    $duration_array = explode(':', $_duration);
                    if (!empty($duration_array) && isset($duration_array[0]) && isset($duration_array[1]) && isset($duration_array[2])) {
                        $_hour = intval($duration_array[0]);
                        $_minute = intval($duration_array[1]);
                        $_second = intval($duration_array[2]);
                        $_duration_second = $_hour * 3600 + $_minute * 60 + $_second;
                    }
                }

                // now process update or insert
                $count ++;
                $temp_total ++;
                //echo $count . ' ' . mb_convert_encoding($_name, 'GBK', 'UTF-8') . "\n";
                echo $temp_total . ' ' . ($_name) . "\n";

                $new_songid = $path_dir . '-' . $_songid;
                // check exists
                //$exist_songs = $check_command->setText('select id from {{media}} where name = :name and category_id = :categoryid and artist_id = :artistid')->queryAll(true, array(':name' => $_name, ':categoryid' => $_category_id, ':artistid' => $_artist_id));
                $exist_songs = $check_command->setText('select id from {{media}} where (songid = :new_songid OR songid like :like_songid)')->queryAll(true, array(':new_songid' => $new_songid, ':like_songid' => $new_songid . '%'));
                if (!empty($exist_songs) && count($exist_songs) > 0) {
                    $_song_id = $exist_songs[0]['id'];
                    $_update_sql = 'UPDATE {{media}} SET ' .
                            'name = :mf1, songid = :mf2, video_filename = :mf3, video_dir_name = :mf4, video_real_url = :mf5, name_count = :mf6, ' .
                            'name_pinyin = :mf7, category_id = :mf8, artist_id = :mf9, albumn_id = :mf10, ' .
                            'bpic_filename = :mf11, bpic_url = :mf12, spic_filename = :mf13, spic_url = :mf14, ' .
                            'song_lang = :mf15, audio_count = :mf16, video_codec = :mf17, video_res = :mf18, ' .
                            'duration = :mf19, audio_volume1 = :mf20, audio_volume2 = :mf21, origin_audio = :mf22, ' .
                            'qc_grade = :mf23, video_comment = :mf24, video_style = :mf25, song_style = :mf26, ' .
                            'video_version = :mf27, hot_grade = :mf28, is_new = :mf29, play_count = :mf30, name_chinese = :mf31 WHERE id = ' . $_song_id;
                    $_update_params = array(
                        ':mf1' => $_name,
                        ':mf2' => $new_songid,
                        ':mf3' => $file_name,
                        ':mf4' => $path_dir,
                        ':mf5' => $_mediaurl . '/' . $path_dir . '/' . $file_name,
                        ':mf6' => intval($_name_count),
                        ':mf7' => trim($_name_pinyin),
                        ':mf8' => $_category_id,
                        ':mf9' => $_artist_id,
                        ':mf10' => $_albumn_id,
                        ':mf11' => $_big_pic,
                        ':mf12' => $_big_pic,
                        ':mf13' => $_small_pic,
                        ':mf14' => $_small_pic,
                        ':mf15' => trim($_song_lang),
                        ':mf16' => intval($_audio_count),
                        ':mf17' => trim($_video_codec),
                        ':mf18' => trim($_video_res),
                        ':mf19' => $_duration_second,
                        ':mf20' => intval($_audio_volume1),
                        ':mf21' => intval($_audio_volume2),
                        ':mf22' => intval($_origin_audio),
                        ':mf23' => intval($_qc_grade),
                        ':mf24' => trim($_video_comment),
                        ':mf25' => trim($_video_style),
                        ':mf26' => trim($_song_style),
                        ':mf27' => trim($_video_version),
                        ':mf28' => intval($_hot_grade),
                        ':mf29' => intval($_is_new),
                        ':mf30' => intval($_play_count),
                        ':mf31' => $_name_chinese,
                    );
                    try {
                        $update_command->setText($_update_sql)->execute($_update_params);
                    } catch (Exception $ex) {
                        echo $temp_total . ' ' . ($_name) . " import error!\n";
                    }
                } else {
                    $total ++;
                    // insert array
                    $_insert_array = array(
                        'name' => $_name,
                        'songid' => $new_songid,
                        'video_filename' => $file_name,
                        'video_dir_name' => $path_dir,
                        'video_real_url' => $_mediaurl . '/' . $path_dir . '/' . $file_name,
                        'name_count' => intval($_name_count),
                        'name_pinyin' => trim($_name_pinyin),
                        'name_chinese' => trim($_name_chinese),
                        'category_id' => $_category_id,
                        'artist_id' => $_artist_id,
                        'albumn_id' => $_albumn_id,
                        'bpic_filename' => $_big_pic,
                        'bpic_url' => $_big_pic,
                        'spic_filename' => $_small_pic,
                        'spic_url' => $_small_pic,
                        'song_lang' => trim($_song_lang),
                        'audio_count' => intval($_audio_count),
                        'video_codec' => trim($_video_codec),
                        'video_res' => trim($_video_res),
                        'duration' => $_duration_second,
                        'audio_volume1' => intval($_audio_volume1),
                        'audio_volume2' => intval($_audio_volume2),
                        'origin_audio' => intval($_origin_audio),
                        'qc_grade' => intval($_qc_grade),
                        'video_comment' => trim($_video_comment),
                        'video_style' => trim($_video_style),
                        'song_style' => trim($_song_style),
                        'video_version' => trim($_video_version),
                        'hot_grade' => intval($_hot_grade),
                        'is_new' => intval($_is_new),
                    );

                    // insert into table
                    $temp_insert_array[] = $_insert_array;
                    if ($total > 100) {
                        Media::model()->insertSeveral($temp_insert_array);
                        $total = 0;
                        $temp_insert_array = array();
                    }
                }
            }
            if (count($temp_insert_array) > 0) {
                Media::model()->insertSeveral($temp_insert_array);
            }
        }
    }

    public function actionConvertGZKTV01($args) {
        //provide the oportunity for the use to abort the request
        $message = "This command will convert song meta information from csv file.\n";
        $message .= "Would you like to continue?";

        //check the input from the user and continue if 
        //they indicated yes to the above question
        if ($this->confirm($message, true)) {

            if (isset($args[0]))
                $filename = $args[0];
            else
                $this->usageError('Please provide the name of the song meta file to convert.');

            if (isset($args[1]))
                $dirname = $args[1];
            else
                $this->usageError('Please provide the name of the song meta directory to convert.');

            //if (isset($args[2]))
            //    $sharename = $args[2];
            //else
            //    $this->usageError('Please provide the name of the server share url to convert.');

            $path = Yii::getPathOfAlias('application.import');
            $file = $path . DIRECTORY_SEPARATOR . $filename;
            $new_file = $path . DIRECTORY_SEPARATOR . 'new_' . $filename;
            if (!is_file($file))
                $this->usageError('Song meta file ' . $file . ' not exists.');

            // read from file line by line
            $fp = fopen($file, 'rb');
            if (FALSE !== $fp) {
                TempImport::model()->deleteAll();
                $total = 0;
                $insert_array = array();
                while (true) {
                    $result = fgets($fp);
                    if (false === $result)
                        break;

                    $line_array = explode(',', $result);
                    $array_count = count($line_array);
                    $array_count = ($array_count > 35) ? 35 : $array_count;
                    $field_array = array();
                    //for ($i = 1; $i <= $array_count; $i++) {
                    //    $field_array['col' . $i] = $line_array[$i - 1];
                    //}
                    // 编码
                    $field_array['col1'] = isset($line_array[0]) ? trim($line_array[0]) : '';
                    // 歌名
                    $field_array['col4'] = isset($line_array[1]) ? trim($line_array[1]) : '';
                    // 歌名字数
                    $field_array['col5'] = isset($line_array[7]) ? trim($line_array[7]) : '0';
                    // 拼音
                    //$field_array['col6'] = isset($line_array[1]) ? PinYinEx::letter(trim($line_array[1]), array('delimiter' => '')) : '';
                    $field_array['col6'] = empty($line_array[2]) ? PinYinEx::letter($line_array[1], array('delimiter' => '')) : $line_array[2];
                    // 歌手
                    $field_array['col7'] = isset($line_array[3]) ? trim($line_array[3]) : '';
                    $field_array['col7'] .= ' ' . (isset($line_array[4]) ? trim($line_array[4]) : '');
                    $field_array['col7'] = empty($field_array['col7']) ? '佚名' : $field_array['col7'];
                    // 语言
                    $field_array['col9'] = isset($line_array[8]) ? trim($line_array[8]) : '';
                    // 点击次数
                    $field_array['col11'] = isset($line_array[14]) ? trim($line_array[14]) : '0';
                    // 1轨音量(音量)
                    $field_array['col16'] = isset($line_array[15]) ? trim($line_array[15]) : '0';
                    // 2轨音量(最高音量)
                    $field_array['col17'] = isset($line_array[16]) ? trim($line_array[16]) : '0';
                    // 原伴唱声道
                    $field_array['col18'] = isset($line_array[9]) ? trim($line_array[9]) : '0';
                    // 原唱声道
                    $field_array['col35'] = isset($line_array[22]) ? trim($line_array[22]) : '0';
                    // 
                    // 专辑
                    //$field_array['col29'] = isset($line_array[3]) ? trim($line_array[3]) : 'MTV';
                    $field_array['col29'] = 'MTV';
                    // TODO convert duration 时长
                    //$field_array['col14'] = isset($line_array[4]) ? trim($line_array[4]) : '';
                    $field_array['col14'] = '0';
                    // video path 路径
                    $field_array['col19'] = isset($line_array[5]) ? trim($line_array[5]) : '';
                    $field_array['col19'] .= '' . (isset($line_array[18]) ? trim($line_array[18]) : '');
                    // convert path
                    $_filepath = $field_array['col19'];
                    //$_filepath = str_replace($sharename, '', $_filepath);
                    //$_filepath = str_replace('\\', '/', $_filepath);
                    $_filepath = str_replace('\\\\', '', $_filepath);
                    //$field_array['col19'] = $_filepath;

                    $path_array = explode('\\', $_filepath);
                    $file_name = '';
                    $path_dir = '';
                    if (!empty($path_array) && count($path_array) > 2) {
                        $array_count = count($path_array);

                        // 防止相同编号歌曲
                        $_part_1 = empty($path_array[0]) ? rand(1, 9) : $path_array[0];
                        $_part_2 = empty($path_array[1]) ? rand(1, 9) : $path_array[1];
                        $_part_3 = rand(1, 9);
                        $_part_4 = rand(1, 9);
                        $_part_5 = rand(1, 9);
                        $_part_6 = rand(1, 9);

                        //$field_array['col1'] .= ('-' . $_part_1 . '-' . $_part_2 . '-' . $_part_3 . $_part_4 . $_part_5 . $_part_6);
                        // modified by wingsun 2015-04-10 for GZKTV update
                        $field_array['col1'] .= ('-' . $_part_1 . '-' . $_part_2);

                        $path_dir = $path_array[0];
                        $file_dir = '';
                        for ($p = 1; $p < $array_count; $p++) {
                            $file_dir .= ($path_array[$p] . DIRECTORY_SEPARATOR);
                        }
                        $file_dir = rtrim($file_dir, DIRECTORY_SEPARATOR);

                        if (!empty($file_dir)) {
                            $file_dir = DIRECTORY_SEPARATOR . $file_dir;
                        }

                        //$_filepath = DIRECTORY_SEPARATOR . $path_dir . $file_dir;
                        $_filepath = $path_dir . $file_dir;
                    } else {
                        echo "Convert Song path error: $_filepath \n";
                        continue;
                    }
                    $field_array['col19'] = $_filepath;

                    // 歌手图片1
                    //$field_array['col27'] = isset($line_array[8]) ? trim($line_array[8]) : '';
                    $field_array['col27'] = '';
                    // 专辑图片
                    //$field_array['col28'] = isset($line_array[9]) ? trim($line_array[9]) : '';
                    $field_array['col28'] = '';

                    // default setting
                    //$field_array['col5'] = '0';
                    //$field_array['col6'] = '';
                    //$field_array['col8'] = '0';
                    // 类型
                    $field_array['col22'] = !empty($line_array[11]) ? trim($line_array[11]) : 'MTV';
                    // 歌曲分类
                    $field_array['col23'] = !empty($line_array[12]) ? trim($line_array[12]) : 'POP';
                    // 版本
                    $field_array['col24'] = isset($line_array[10]) ? trim($line_array[10]) : '';
                    // GZKTV01 flag
                    $field_array['col30'] = 'GZKTV01';
                    $field_array['col31'] = $dirname;

                    $total++;

                    $temp_insert_array = array();
                    $temp_insert_array[] = $field_array;
                    // insert into table
                    TempImport::model()->insertSeveral($temp_insert_array);

                    echo "process " . $total . ' - ' . $line_array[0] . "\n";
                }
            } else {
                $this->usageError('Song meta file read error.');
            }
            //provide a message indicating success
            echo "Song meta information successfully converted.\n";
        } else
            echo "Operation cancelled.\n";
    }

    public function actionResetSongPY() {
        $message = "This command will reset all song name pinyin.\nNOTICE: DO NOT DO ANY OTHER OPERATING WHEN RUNNING!\n";
        $message .= "Would you like to continue?";

        //check the input from the user and continue if 
        //they indicated yes to the above question
        if ($this->confirm($message, true)) {
            $start_time = time();
            echo date('Y-m-d H:i:s', $start_time) . " Start updating ......\n";
            $count = 0;
            $list_command = Yii::app()->getDb()->createCommand();
            $update_command = Yii::app()->getDb()->createCommand();
            $total = Yii::app()->getDb()->createCommand()->setText('SELECT count(id) AS total FROM {{media}}')->queryAll();
            if (isset($total) && isset($total[0]) && isset($total[0]['total'])) {
                $list_count = intval($total[0]['total']);
            } else {
                $list_count = 1000000;
            }
            for ($i = 0; $i < $list_count; $i+=100) {
                $singer_list = $list_command->setText('SELECT id, name FROM {{media}}' . " LIMIT $i,100")->queryAll();
                if (!empty($singer_list)) {
                    $update_values = array();
                    foreach ($singer_list as $key => $singer) {
                        $singer_id = $singer['id'];
                        $singer_name = $singer['name'];
                        $singer_name_PY = PinYinEx::letter($singer_name, array('delimiter' => ''));
                        $singer_name_FPY = PinYinEx::pinyin($singer_name, array('delimiter' => '', 'accent' => false));

                        $update_values[] = "($singer_id, " . Yii::app()->getDb()->quoteValue($singer_name_PY) . ", " . Yii::app()->getDb()->quoteValue($singer_name_FPY) . ")";
                        // TODO: update artist pinyin
                        ////$sql = 'UPDATE {{media}} set name_pinyin = :name_pinyin, name_chinese = :name_chinese WHERE id = :id';
                        ////$update_command->setText($sql)->execute(array(':name_pinyin' => $singer_name_PY, ':name_chinese' => $singer_name_FPY, ':id' => $singer_id));

                        $count++;
                        echo $count . ' ' . "$singer_id - $singer_name - $singer_name_PY - $singer_name_FPY" . "\n";
                    }
                    if (!empty($update_values)) {
                        $insert_values = implode(',', $update_values);
                        $sql = 'INSERT INTO {{media}} (id, name_pinyin, name_chinese) VALUES ' . $insert_values . ' ON DUPLICATE KEY UPDATE name_pinyin = VALUES(name_pinyin), name_chinese = VALUES(name_chinese)';
                        $update_command->setText($sql)->execute();
                        echo "Updating ......\n";
                    }
                } else {
                    break;
                }
            }
            echo date('Y-m-d H:i:s', time()) . " Updating finished.\n" . (time() - $start_time) . "s\n";
        } else
            echo "Operation cancelled.\n";
    }

    public function actionResetSingerPY() {
        $message = "This command will reset all artist name pinyin.\nNOTICE: DO NOT DO ANY OTHER OPERATING WHEN RUNNING!\n";
        $message .= "Would you like to continue?";

        //check the input from the user and continue if 
        //they indicated yes to the above question
        if ($this->confirm($message, true)) {
            $start_time = time();
            echo date('Y-m-d H:i:s', $start_time) . " Start updating ......\n";
            $count = 0;
            $list_command = Yii::app()->getDb()->createCommand();
            $update_command = Yii::app()->getDb()->createCommand();
            $total = Yii::app()->getDb()->createCommand()->setText('SELECT count(id) AS total FROM {{artist}}')->queryAll();
            if (isset($total) && isset($total[0]) && isset($total[0]['total'])) {
                $list_count = intval($total[0]['total']);
            } else {
                $list_count = 1000000;
            }
            for ($i = 0; $i < $list_count; $i+=100) {
                $singer_list = $list_command->setText('SELECT id, name FROM {{artist}} ' . " LIMIT $i,100")->queryAll();
                if (!empty($singer_list)) {
                    $update_values = array();
                    foreach ($singer_list as $key => $singer) {
                        $singer_id = $singer['id'];
                        $singer_name = $singer['name'];
                        $singer_name_PY = PinYinEx::letter($singer_name, array('delimiter' => ''));
                        $singer_name_FPY = PinYinEx::pinyin($singer_name, array('delimiter' => '', 'accent' => false));

                        $update_values[] = "($singer_id, " . Yii::app()->getDb()->quoteValue($singer_name_PY) . ", " . Yii::app()->getDb()->quoteValue($singer_name_FPY) . ")";
                        // TODO: update artist pinyin
                        ////$sql = 'UPDATE {{artist}} set name_pinyin = :name_pinyin, name_chinese = :name_chinese WHERE id = :id';
                        ////$update_command->setText($sql)->execute(array(':name_pinyin' => $singer_name_PY, ':name_chinese' => $singer_name_FPY, ':id' => $singer_id));

                        $count++;
                        echo $count . ' ' . "$singer_id - $singer_name - $singer_name_PY - $singer_name_FPY" . "\n";
                    }
                    if (!empty($update_values)) {
                        $insert_values = implode(',', $update_values);
                        $sql = 'INSERT INTO {{artist}} (id, name_pinyin, name_chinese) VALUES ' . $insert_values . ' ON DUPLICATE KEY UPDATE name_pinyin = VALUES(name_pinyin), name_chinese = VALUES(name_chinese)';
                        $update_command->setText($sql)->execute();
                        echo "Updating ......\n";
                    }
                } else {
                    break;
                }
            }
            echo date('Y-m-d H:i:s', time()) . " Updating finished.\n" . (time() - $start_time) . "s\n";
        } else
            echo "Operation cancelled.\n";
    }

    public function actionTest2PY() {
        $message = "This command will convert chinese to pinyin.\nNOTICE: 你好,欢迎使用! 张靓颖 陈奕迅， 美好的明天向你招手， 走向幸福的终点。\n";
        $message .= "Would you like to continue?";

        //check the input from the user and continue if 
        //they indicated yes to the above question
        if ($this->confirm($message, true)) {
            $start_time = time();
            echo date('Y-m-d H:i:s', $start_time) . " Start converting ......\n";

            echo PinYinEx::pinyin('你好,欢迎使用!张靓颖 陈奕迅，Hello 美好的明天向你招手，走向幸福的终点。', array('delimiter' => '', 'accent' => false, 'only_chinese' => true));
            echo "\n";
            echo PinYinEx::letter('你好,欢迎使用!张靓颖 陈奕迅，Hello 美好的明天向你招手，走向幸福的终点。', array('delimiter' => '', 'only_chinese' => true));
            echo "\n";

            echo date('Y-m-d H:i:s', time()) . " Converting finished.\n" . (time() - $start_time) . "s\n";
        } else
            echo "Operation cancelled.\n";
    }

}
