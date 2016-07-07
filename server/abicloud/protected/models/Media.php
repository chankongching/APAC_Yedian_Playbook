<?php

/**
 * This is the model class for table "{{media}}".
 *
 * The followings are the available columns in table '{{media}}':
 * @property integer $id
 * @property string $songid
 * @property string $name
 * @property string $description
 * @property string $lyrics
 * @property integer $artist_id
 * @property integer $category_id
 * @property integer $albumn_id
 * @property integer $duration
 * @property string $bpic_filename
 * @property string $bpic_url
 * @property string $spic_filename
 * @property string $spic_url
 * @property string $light_tag
 * @property string $led_tag
 * @property string $video_filename
 * @property string $video_url
 * @property string $audio_filename
 * @property string $audio_url
 * @property string $audio_filename1
 * @property string $audio_url1
 * @property string $audio_filename2
 * @property string $audio_url2
 * @property integer $status
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property string $name_chinese
 * @property string $name_pinyin
 * @property string $lyrics_chinese
 * @property string $lyrics_pinyin
 * @property string $video_dir_name
 * @property string $video_real_url
 * @property string $video_codec
 * @property string $video_res
 * @property string $video_style
 * @property string $song_style
 * @property string $song_lang
 * @property string $video_version
 * @property integer $hot_grade
 * @property integer $play_count
 * @property integer $is_new
 * @property integer $origin_audio
 * @property integer $qc_grade
 * @property string $video_comment
 * @property integer $audio_volume1
 * @property integer $audio_volume2
 * @property integer $audio_count
 * @property integer $name_count
 *
 * The followings are the available model relations:
 * @property DevicePlaylist[] $devicePlaylists
 * @property Albumn $albumn
 * @property Artist $artist
 * @property Category $category
 */
class Media extends PSActiveRecord {

    // TODO: Step 1, for relation field filter and sort
    public $artist_name;
    public $artist_pinyin;
    public $category_name;
    // picture files
    public $bpic_file;
    public $spic_file;
    public $attach_path = '';
    public $attach_url = '';

    public function init() {
        parent::init();
        $_baseurl = Yii::app()->createAbsoluteUrl('/');
        $_mediabaseurl = (empty(Yii::app()->params['media_url']) ? $_baseurl . '/uploads/media' : Yii::app()->params['media_url']);
        
        $_basepath = Yii::getPathOfAlias('webroot.uploads');
        $_mediabasepath = (empty(Yii::app()->params['media_path']) ? $_basepath . DIRECTORY_SEPARATOR . 'media' : Yii::app()->params['media_path']);
        
        //$this->attach_path = (empty(Yii::app()->params['upload_folder']) ? Yii::getPathOfAlias('webroot.uploads') : Yii::app()->params['upload_folder']) . DIRECTORY_SEPARATOR . 'media';
        $this->attach_path = $_mediabasepath;
        if (!file_exists($this->attach_path)) {
            $this->_createFolder($this->attach_path);
        }
        //$this->attach_url = (empty(Yii::app()->params['upload_url']) ? (Yii::app()->createAbsoluteUrl('/') . '/uploads') : (Yii::app()->params['upload_url'])) . '/media';
        $this->attach_url = $_mediabaseurl;
    }
    
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{media}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('songid, name', 'required'),
            array('artist_id, category_id, albumn_id, duration, status, create_user_id, update_user_id, hot_grade, play_count, is_new, origin_audio, qc_grade, audio_volume1, audio_volume2, audio_count, name_count', 'numerical', 'integerOnly' => true),
            array('songid, video_codec, video_res, video_style, song_style, song_lang, video_version', 'length', 'max' => 32),
            array('name, bpic_filename, bpic_url, spic_filename, spic_url, video_filename, video_url, audio_filename, audio_url, audio_filename1, audio_url1, audio_filename2, audio_url2, name_chinese, name_pinyin, video_dir_name, video_real_url, video_comment', 'length', 'max' => 255),
            array('description, lyrics, light_tag, led_tag, create_time, update_time, lyrics_chinese, lyrics_pinyin', 'safe'),
            array('bpic_file', 'file', 'types' => 'jpg,jpeg,png,bmp,gif', 'maxSize' => 1048576 * 10, 'safe' => true, 'allowEmpty' => true),
            array('spic_file', 'file', 'types' => 'jpg,jpeg,png,bmp,gif', 'maxSize' => 1048576 * 10, 'safe' => true, 'allowEmpty' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('artist_name, artist_pinyin, category_name, id, songid, name, description, lyrics, artist_id, category_id, albumn_id, duration, bpic_filename, bpic_url, spic_filename, spic_url, light_tag, led_tag, video_filename, video_url, audio_filename, audio_url, audio_filename1, audio_url1, audio_filename2, audio_url2, status, create_time, create_user_id, update_time, update_user_id, name_chinese, name_pinyin, lyrics_chinese, lyrics_pinyin, video_dir_name, video_real_url, video_codec, video_res, video_style, song_style, song_lang, video_version, hot_grade, play_count, is_new, origin_audio, qc_grade, video_comment, audio_volume1, audio_volume2, audio_count, name_count', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'devicePlaylists' => array(self::HAS_MANY, 'DevicePlaylist', 'media_id'),
            'albumn' => array(self::BELONGS_TO, 'Albumn', 'albumn_id'),
            'artist' => array(self::BELONGS_TO, 'Artist', 'artist_id'),
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('Media', 'ID'),
            'songid' => Yii::t('Media', 'Songid'),
            'name' => Yii::t('Media', 'Name'),
            'description' => Yii::t('Media', 'Description'),
            'lyrics' => Yii::t('Media', 'Lyrics'),
            'artist_id' => Yii::t('Media', 'Artist ID'),
            'category_id' => Yii::t('Media', 'Category'),
            'albumn_id' => Yii::t('Media', 'Albumn'),
            'duration' => Yii::t('Media', 'Duration'),
            'bpic_filename' => Yii::t('Media', 'Bpic Filename'),
            'bpic_url' => Yii::t('Media', 'Bpic Url'),
            'spic_filename' => Yii::t('Media', 'Spic Filename'),
            'spic_url' => Yii::t('Media', 'Spic Url'),
            'light_tag' => Yii::t('Media', 'Light Tag'),
            'led_tag' => Yii::t('Media', 'Led Tag'),
            'video_filename' => Yii::t('Media', 'Video Filename'),
            'video_url' => Yii::t('Media', 'Video Url'),
            'audio_filename' => Yii::t('Media', 'Audio Filename'),
            'audio_url' => Yii::t('Media', 'Audio Url'),
            'audio_filename1' => Yii::t('Media', 'Audio Filename1'),
            'audio_url1' => Yii::t('Media', 'Audio Url1'),
            'audio_filename2' => Yii::t('Media', 'Audio Filename2'),
            'audio_url2' => Yii::t('Media', 'Audio Url2'),
            'status' => Yii::t('Media', 'Status'),
            'create_time' => Yii::t('Media', 'Create Time'),
            'create_user_id' => Yii::t('Media', 'Create User'),
            'update_time' => Yii::t('Media', 'Update Time'),
            'update_user_id' => Yii::t('Media', 'Update User'),
            'name_chinese' => Yii::t('Media', 'Name Chinese'),
            'name_pinyin' => Yii::t('Media', 'Name Pinyin'),
            'lyrics_chinese' => Yii::t('Media', 'Lyrics Chinese'),
            'lyrics_pinyin' => Yii::t('Media', 'Lyrics Pinyin'),
            'video_dir_name' => Yii::t('Media', 'Video Dir Name'),
            'video_real_url' => Yii::t('Media', 'Video Real Url'),
            'video_codec' => Yii::t('Media', 'Video Codec'),
            'video_res' => Yii::t('Media', 'Video Res'),
            'video_style' => Yii::t('Media', 'Video Style'),
            'song_style' => Yii::t('Media', 'Song Style'),
            'song_lang' => Yii::t('Media', 'Song Lang'),
            'video_version' => Yii::t('Media', 'Video Version'),
            'hot_grade' => Yii::t('Media', 'Hot Grade'),
            'play_count' => Yii::t('Media', 'Play Count'),
            'is_new' => Yii::t('Media', 'Is New'),
            'origin_audio' => Yii::t('Media', 'Origin Audio'),
            'qc_grade' => Yii::t('Media', 'Qc Grade'),
            'video_comment' => Yii::t('Media', 'Video Comment'),
            'audio_volume1' => Yii::t('Media', 'Audio Volume1'),
            'audio_volume2' => Yii::t('Media', 'Audio Volume2'),
            'audio_count' => Yii::t('Media', 'Audio Count'),
            'name_count' => Yii::t('Media', 'Name Count'),
            'artist_name' => Yii::t('Media', 'Artist Name'),
            'artist_pinyin' => Yii::t('Artist', 'Name Pinyin'),
            'category_name' => Yii::t('Media', 'Category'),
            'bpic_file' => Yii::t('Media', 'Select File'),
            'spic_file' => Yii::t('Media', 'Select File'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search($pagesize = 10) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        // TODO: Step 2, relation filter
        $criteria->with = array('artist' => array('select' => 'name, name_pinyin'), 'category' => array('select' => 'name'));
        $criteria->compare('artist.name', $this->artist_name, true);
        $criteria->compare('artist.name_pinyin', $this->artist_pinyin, true);
        $criteria->compare('category.name', $this->category_name, true);

        $criteria->compare('t.id', $this->id);
        $criteria->compare('songid', $this->songid, true);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('lyrics', $this->lyrics, true);
        $criteria->compare('artist_id', $this->artist_id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('albumn_id', $this->albumn_id);
        $criteria->compare('duration', $this->duration);
        $criteria->compare('bpic_filename', $this->bpic_filename, true);
        $criteria->compare('bpic_url', $this->bpic_url, true);
        $criteria->compare('spic_filename', $this->spic_filename, true);
        $criteria->compare('spic_url', $this->spic_url, true);
        $criteria->compare('light_tag', $this->light_tag, true);
        $criteria->compare('led_tag', $this->led_tag, true);
        $criteria->compare('video_filename', $this->video_filename, true);
        $criteria->compare('video_url', $this->video_url, true);
        $criteria->compare('audio_filename', $this->audio_filename, true);
        $criteria->compare('audio_url', $this->audio_url, true);
        $criteria->compare('audio_filename1', $this->audio_filename1, true);
        $criteria->compare('audio_url1', $this->audio_url1, true);
        $criteria->compare('audio_filename2', $this->audio_filename2, true);
        $criteria->compare('audio_url2', $this->audio_url2, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('create_user_id', $this->create_user_id);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('update_user_id', $this->update_user_id);
        $criteria->compare('t.name_chinese', $this->name_chinese, true);
        $criteria->compare('t.name_pinyin', $this->name_pinyin, true);
        $criteria->compare('lyrics_chinese', $this->lyrics_chinese, true);
        $criteria->compare('lyrics_pinyin', $this->lyrics_pinyin, true);
        $criteria->compare('video_dir_name', $this->video_dir_name, true);
        $criteria->compare('video_real_url', $this->video_real_url, true);
        $criteria->compare('video_codec', $this->video_codec, true);
        $criteria->compare('video_res', $this->video_res, true);
        $criteria->compare('video_style', $this->video_style, true);
        $criteria->compare('song_style', $this->song_style, true);
        $criteria->compare('song_lang', $this->song_lang, true);
        $criteria->compare('video_version', $this->video_version, true);
        $criteria->compare('hot_grade', $this->hot_grade);
        $criteria->compare('play_count', $this->play_count);
        $criteria->compare('is_new', $this->is_new);
        $criteria->compare('origin_audio', $this->origin_audio);
        $criteria->compare('qc_grade', $this->qc_grade);
        $criteria->compare('video_comment', $this->video_comment, true);
        $criteria->compare('audio_volume1', $this->audio_volume1);
        $criteria->compare('audio_volume2', $this->audio_volume2);
        $criteria->compare('audio_count', $this->audio_count);
        $criteria->compare('name_count', $this->name_count);

        // TODO: Step 2, relation sort
        $sort = new CSort();
        $sort->attributes = array(
            //'defaultOrder'=>'t.create_time',
            'artist_name' => array(
                'asc' => 'artist.name',
                'desc' => 'artist.name desc',
            ),
            'artist_pinyin' => array(
                'asc' => 'artist.name_pinyin',
                'desc' => 'artist.name_pinyin desc',
            ),
            'category_name' => array(
                'asc' => 'category.name',
                'desc' => 'category.name desc',
            ),
            'id',
            'name',
            'name_pinyin',
            'name_chinese',
            'duration',
            'artist_id',
        );

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => $pagesize),
            'sort' => $sort,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Media the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function countNew() {
        $pre3days = time() - 3600 * 24 * 7;
        $count = Media::model()->count('create_time > :pre3days', array(':pre3days' => date('Y-m-d H:i:s', $pre3days)));
        return $count;
    }

    /**
     * After find process
     * TODO: Step 3 for relation field filter and sort
     */
    public function afterFind() {
        parent::afterFind();
        $this->artist_name = (isset($this->artist) && isset($this->artist->name)) ? $this->artist->name : '';
        $this->artist_pinyin = (isset($this->artist) && isset($this->artist->name_pinyin)) ? $this->artist->name_pinyin : '';
        $this->category_name = (isset($this->category) && isset($this->category->name)) ? $this->category->name : '';
    }

    /**
     * Get the media picture url
     * @param Media $media
     * @param String $filename
     * @param integer $picsize
     * @return string
     */
    public function getMediaPicUrl($media, $filename = '', $picsize = 1) {
        //$_baseurl = Yii::app()->createAbsoluteUrl('/');
        //$_mediabaseurl = (empty(Yii::app()->params['media_url']) ? $_baseurl . '/uploads/media' : Yii::app()->params['media_url']);
        $_mediabaseurl = $this->attach_url;
        if (!empty($media->video_dir_name)) {
            if ("demo" == $media->video_dir_name) {
                $_mediaurl = $_mediabaseurl . '/' . $media->video_dir_name . '/images/';
            } else {
                $_mediaurl = $_mediabaseurl . '/' . $media->video_dir_name . '/';
            }
        } else {
            $_cate_id = $media->category_id;
            $_mediaurl = $_mediabaseurl . '/' . $_cate_id . '/images/';
        }

        $default_song_setting = empty(Yii::app()->params['song_default_setting']) ? array() : Yii::app()->params['song_default_setting'];
        if (1 == $picsize) {
            $default_song_pic = (isset($default_song_setting['bigpic']) && !empty($default_song_setting['bigpic'])) ? ($_mediabaseurl . '/' . $default_song_setting['bigpic']) : '';
        } else {
            $default_song_pic = (isset($default_song_setting['smallpic']) && !empty($default_song_setting['smallpic'])) ? ($_mediabaseurl . '/' . $default_song_setting['smallpic']) : '';
        }
        //$file_url = empty($filename) ? '' : str_replace('\\', '/', $filename);
        $_media_url = empty($filename) ? '' : $_mediaurl . str_replace('\\', '/', $filename);
        $_artist_pic_url = '';
        if (empty($_media_url)) {
            $_artist = Artist::model()->findByPk($media->artist_id);
            if (!is_null($_artist)) {
                if (1 == $picsize) {
                    $_artist_pic = $_artist->bpic_url;
                } else {
                    $_artist_pic = $_artist->spic_url;
                }
                $_artist_pic_url = empty($_artist_pic) ? '' : ($_artist->attach_url . '/' . str_replace('\\', '/', $_artist_pic));
            }
        }
        //$_media_full_url = empty($file_url) ? $default_song_pic : ($_mediaurl . $file_url);
        $_media_full_url = empty($_media_url) ? (empty($_artist_pic_url) ? $default_song_pic : $_artist_pic_url) : $_media_url;
        return $_media_full_url;
    }

}
