<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlayerController
 *
 * @author wingsun
 */
class PlayerController extends ApiController {

    /**
     * Option for return new list after list updated
     */
    const RETURN_LIST_AFTER_UPDATE = true;
    const RETURN_CURRENT_PLAYING = true;
    const RETURN_CURRENT_PLAYING_AFTER_UPDATE = true;
    const NEED_SETUP_CURRENT_PLAYING_WHEN_UPDATE = false;
    const SEARCH_LIMIT = 1000;
    const PLAYLIST_LIMIT = 1000;

    private $search_maximum;
    private $playlist_maximum;

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function init() {
        parent::init();
        $this->search_maximum = self::SEARCH_LIMIT;
        $this->playlist_maximum = self::PLAYLIST_LIMIT;
    }

    public function actionMedialist() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_offset = Yii::app()->request->getQuery('offset');
        $_limit = Yii::app()->request->getQuery('limit');
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->search_maximum : intval($_limit);
        $_limit = ($_limit > $this->search_maximum) ? $this->search_maximum : $_limit;

        // log
        $_count = intval(Media::model()->count());
        $criteria = new CDbCriteria();
        $criteria->offset = $_offset;
        $criteria->limit = $_limit;
        $media_list = Media::model()->findAll($criteria);
        if (!empty($media_list)) {
            // Get list success
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get media list success!');
            $result_array['total'] = $_count;
            foreach ($media_list as $key => $_media) {
                if (!is_null($_media->artist)) {
                    $_pinyin = $this->getArtistFirstPY($_media->artist);
                } else {
                    $_pinyin = '';
                }
                $result_array['list'][] = array(
                    'songid' => $_media->songid,
                    'songname' => $_media->name,
                    'singername' => $_media->artist->name,
                    'duration' => intval($_media->duration),
                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                    'firstpinyin' => $_pinyin
                );
            }
        } else {
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Media list is empty!');
            $result_array['total'] = $_count;
        }

        // Set response information
        $this->sendResults($result_array);
    }

    public function actionMediainfo() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'songid' => '',
            'songname' => '',
            'singername' => '',
            'lyrics' => '',
            'duration' => 0,
            'smallpiclist' => array(),
            'bigpiclist' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_songid = Yii::app()->request->getQuery('songid');
        $_songid = empty($_songid) ? '' : $_songid;

        // log
        $_media = Media::model()->findByAttributes(array('songid' => $_songid));
        if (!empty($_media)) {
            // Get media info success
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get media info success!');
            $result_array['songid'] = $_media->songid;
            $result_array['songname'] = $_media->name;
            $result_array['singername'] = $_media->artist->name;
            $result_array['lyrics'] = $_media->lyrics;
            $result_array['duration'] = intval($_media->duration);
            $result_array['smallpiclist'][] = array('picname' => 'Small', 'picurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0));
            $result_array['bigpiclist'][] = array('picname' => 'Big', 'picurl' => $this->getMediaPicUrl($_media, $_media->bpic_url));
        } else {
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Media info does not exists!');
        }

        // Set response information
        $this->sendResults($result_array);
    }

    public function actionRecommendlist() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'list' => array(),
        );

        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_limit = Yii::app()->request->getQuery('limit');
        $_limit = empty($_limit) ? 3 : intval($_limit);
        $_limit = ($_limit > $this->search_maximum) ? $this->search_maximum : $_limit;

        // log
        //$media_list = Media::model()->findAll();
        $media_count = Media::model()->count();
        //if (!empty($media_list)) {
        if ($media_count > 0) {
            //$_count = count($media_list);
            $_count = $media_count;
            $_count = $_count - 1;
            // Get the random 3 item
            if ($_count >= 3) {
                $_r1 = rand(0, $_count);
                $_r2 = rand(0, $_count);
                while ($_r2 == $_r1) {
                    $_r2 = rand(0, $_count);
                }
                $_r3 = rand(0, $_count);
                while ($_r3 == $_r1 || $_r3 == $_r2) {
                    $_r3 = rand(0, $_count);
                }
                // get the recomment top 3 item
                //$_media = $media_list[$_r1];
                $criteria = new CDbCriteria();
                $criteria->limit = 1;
                $criteria->offset = $_r1;
                $_media = Media::model()->find($criteria);
                $result_array['list'][] = array(
                    'songid' => $_media->songid,
                    'songname' => $_media->name,
                    'singername' => $_media->artist->name,
                    'duration' => intval($_media->duration),
                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url)
                );
                //$_media = $media_list[$_r2];
                $criteria->offset = $_r2;
                $_media = Media::model()->find($criteria);
                $result_array['list'][] = array(
                    'songid' => $_media->songid,
                    'songname' => $_media->name,
                    'singername' => $_media->artist->name,
                    'duration' => intval($_media->duration),
                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url)
                );
                //$_media = $media_list[$_r3];
                $criteria->offset = $_r3;
                $_media = Media::model()->find($criteria);
                $result_array['list'][] = array(
                    'songid' => $_media->songid,
                    'songname' => $_media->name,
                    'singername' => $_media->artist->name,
                    'duration' => intval($_media->duration),
                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url)
                );
            } else {
                $media_list = Media::model()->findAll();
                foreach ($media_list as $key => $_media) {
                    $result_array['list'][] = array(
                        'songid' => $_media->songid,
                        'songname' => $_media->name,
                        'singername' => $_media->artist->name,
                        'duration' => intval($_media->duration),
                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url)
                    );
                }
            }

            // Get random 
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get recommend song list success!');
        } else {
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Recommend list is empty!');
        }

        // Set response information
        $this->sendResults($result_array);
    }

    public function actionToplist() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'list' => array(),
        );

        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_limit = Yii::app()->request->getQuery('limit');
        $_limit = empty($_limit) ? 3 : intval($_limit);
        $_limit = ($_limit > $this->search_maximum) ? $this->search_maximum : $_limit;

        // log
        //$media_list = Media::model()->findAll();
        $media_count = Media::model()->count();
        //if (!empty($media_list)) {
        if ($media_count > 0) {
            //$_count = count($media_list);
            $_count = $media_count;
            $_count = $_count - 1;
            // Get the random 3 item
            if ($_count >= 3) {
                $_r1 = rand(0, $_count);
                $_r2 = rand(0, $_count);
                while ($_r2 == $_r1) {
                    $_r2 = rand(0, $_count);
                }
                $_r3 = rand(0, $_count);
                while ($_r3 == $_r1 || $_r3 == $_r2) {
                    $_r3 = rand(0, $_count);
                }
                // get the recomment top 3 item
                //$_media = $media_list[$_r1];
                $criteria = new CDbCriteria();
                $criteria->limit = 1;
                $criteria->offset = $_r1;
                $_media = Media::model()->find($criteria);
                $result_array['list'][] = array(
                    'songid' => $_media->songid,
                    'songname' => $_media->name,
                    'singername' => $_media->artist->name,
                    'duration' => intval($_media->duration),
                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url)
                );
                //$_media = $media_list[$_r2];
                $criteria->offset = $_r2;
                $_media = Media::model()->find($criteria);
                $result_array['list'][] = array(
                    'songid' => $_media->songid,
                    'songname' => $_media->name,
                    'singername' => $_media->artist->name,
                    'duration' => intval($_media->duration),
                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url)
                );
                //$_media = $media_list[$_r3];
                $criteria->offset = $_r3;
                $_media = Media::model()->find($criteria);
                $result_array['list'][] = array(
                    'songid' => $_media->songid,
                    'songname' => $_media->name,
                    'singername' => $_media->artist->name,
                    'duration' => intval($_media->duration),
                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url)
                );
            } else {
                $media_list = Media::model()->findAll();
                foreach ($media_list as $key => $_media) {
                    $result_array['list'][] = array(
                        'songid' => $_media->songid,
                        'songname' => $_media->name,
                        'singername' => $_media->artist->name,
                        'duration' => intval($_media->duration),
                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url)
                    );
                }
            }

            // Get random 
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get top n song list success!');
        } else {
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Top n list is empty!');
        }

        // Set response information
        $this->sendResults($result_array);
    }

    /**
     * Get the play list
     * if RETURN_CURRENT_PLAYING set to be true, then return the current playing info
     */
    public function actionPlaylist() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'list' => array(),
        );
        if (self::RETURN_CURRENT_PLAYING) {
            $result_array['playing'] = array();
        }
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_offset = Yii::app()->request->getQuery('offset');
        $_limit = Yii::app()->request->getQuery('limit');
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->playlist_maximum : intval($_limit);
        $_limit = ($_limit > $this->playlist_maximum) ? $this->playlist_maximum : $_limit;

        // Get room device
        //$_device = DeviceState::model()->findByAttributes(array('room_id' => $this->_roomID, 'status' => 0));
        // Get STB
        $_device = Yii::app()->db->createCommand()
                ->select('ds.device_id')
                ->from('{{device_state}} ds')
                ->join('{{room}} r', 'ds.room_id = r.id')
                ->join('{{device}} d', 'ds.device_id = d.id')
                ->where('r.id=:id AND d.type=:type', array(':id' => $this->_roomID, ':type' => 1))
                ->queryRow();

        if (!is_null($_device) && !empty($_device)) {
            $_deviceid = $_device['device_id'];

            // log
            $criteria = new CDbCriteria();
            $criteria->condition = 'status = :status and device_id = :deviceid';

            if (self::RETURN_CURRENT_PLAYING) {
                // get current playing song
                $has_playing = false;
                $criteria->params = array(':deviceid' => $_deviceid, ':status' => 1);
                $_playing = DevicePlaylist::model()->find($criteria);
                if (!is_null($_playing) && !empty($_playing)) {
                    // get songid from media_id
                    $_media = $_playing->media;

                    $_post_time = $_playing->play_posttime;
                    $_timestamp = $_playing->play_timestamp;
                    $_play_status = $_playing->play_status;
                    $_play_status = empty($_play_status) ? 'PLAY' : $_play_status;
                    $_media_duration = $_media->duration;

                    // caculate the play time stamp
                    $_play_timestamp = (time() - $_post_time) + $_timestamp;
                    $_play_timestamp = ($_play_timestamp < 0) ? 0 : $_play_timestamp;
                    $_play_timestamp = ($_play_timestamp > $_media_duration) ? $_media_duration : $_play_timestamp;

                    // get user avatar information of song adder
                    $_userinfo = $this->getUserInfo($_playing->create_user_id);

                    $result_array['playing'] = array(
                        'songid' => $_media->songid,
                        'index_num' => intval($_playing->index_num),
                        'songname' => $_media->name,
                        'singername' => $_media->artist->name,
                        'duration' => intval($_media->duration),
                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                        'userid' => $_userinfo['uid'],
                        'nickname' => $_userinfo['nickname'],
                        'avatarurl' => $_userinfo['avatarurl'],
                        'status' => $_play_status,
                        'timestamp' => $_play_timestamp
                    );
                    $has_playing = true;
                }
            }

            // get play list
            $criteria->params = array(':deviceid' => $_deviceid, ':status' => 0);

            $_count = intval(DevicePlaylist::model()->count($criteria));

            $criteria->offset = $_offset;
            $criteria->limit = $_limit;
            $criteria->order = 'index_num asc';
            $play_list = DevicePlaylist::model()->findAll($criteria);
            if (!empty($play_list)) {
                // Get list success
                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'Get play list success!');
                $result_array['total'] = $_count;
                foreach ($play_list as $key => $_list) {
                    $_media = $_list->media;
                    // get user avatar information of song adder
                    $_userinfo = $this->getUserInfo($_list->create_user_id);

                    $result_array['list'][] = array(
                        'songid' => $_media->songid,
                        'index_num' => intval($_list->index_num),
                        'songname' => $_media->name,
                        'singername' => $_media->artist->name,
                        'duration' => intval($_media->duration),
                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                        'userid' => $_userinfo['uid'],
                        'nickname' => $_userinfo['nickname'],
                        'avatarurl' => $_userinfo['avatarurl'],
                    );
                }
            } else {
                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'Play list is empty!');
                $result_array['total'] = $_count;
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Room STB invalid!');
        }

        // Set response information
        $this->sendResults($result_array);
    }

    /**
     * Get the played list
     */
    public function actionPlayedlist() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_offset = Yii::app()->request->getQuery('offset');
        $_limit = Yii::app()->request->getQuery('limit');
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->playlist_maximum : intval($_limit);
        $_limit = ($_limit > $this->playlist_maximum) ? $this->playlist_maximum : $_limit;

        // Get room device
        //$_device = DeviceState::model()->findByAttributes(array('room_id' => $this->_roomID, 'status' => 0));
        // Get STB
        $_device = Yii::app()->db->createCommand()
                ->select('ds.device_id')
                ->from('{{device_state}} ds')
                ->join('{{room}} r', 'ds.room_id = r.id')
                ->join('{{device}} d', 'ds.device_id = d.id')
                ->where('r.id=:id AND d.type=:type', array(':id' => $this->_roomID, ':type' => 1))
                ->queryRow();

        if (!is_null($_device) && !empty($_device)) {
            $_deviceid = $_device['device_id'];

            // log
            $criteria = new CDbCriteria();
            $criteria->condition = 'status = :status and device_id = :deviceid';

            // get played list
            $criteria->params = array(':deviceid' => $_deviceid, ':status' => 2);

            $_count = intval(DevicePlaylist::model()->count($criteria));

            $criteria->offset = $_offset;
            $criteria->limit = $_limit;
            $criteria->order = 'index_num asc';
            $play_list = DevicePlaylist::model()->findAll($criteria);
            if (!empty($play_list)) {
                // Get list success
                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'Get played list success!');
                $result_array['total'] = $_count;
                foreach ($play_list as $key => $_list) {
                    $_media = $_list->media;
                    // get user avatar information of song adder
                    $_userinfo = $this->getUserInfo($_list->create_user_id);

                    $result_array['list'][] = array(
                        'songid' => $_media->songid,
                        'played_time' => intval($_list->index_num),
                        'songname' => $_media->name,
                        'singername' => $_media->artist->name,
                        'duration' => intval($_media->duration),
                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                        'userid' => $_userinfo['uid'],
                        'nickname' => $_userinfo['nickname'],
                        'avatarurl' => $_userinfo['avatarurl'],
                    );
                }
            } else {
                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'Played list is empty!');
                $result_array['total'] = $_count;
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Room STB invalid!');
        }

        // Set response information
        $this->sendResults($result_array);
    }

    /**
     * Import the upload play list
     * if current playing info does not exists, then select the first item as current playing item
     * if RETURN_LIST_AFTER_UPDATE set to be true, then return the updated play list
     * if RETURN_CURRENT_PLAYING set to be true, then return the current playing info
     */
    public function actionPlaylistupdate() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
        );
        if (self::RETURN_CURRENT_PLAYING_AFTER_UPDATE) {
            $result_array['playing'] = array();
        }
        if (self::RETURN_LIST_AFTER_UPDATE) {
            $result_array['total'] = 0;
            $result_array['list'] = array();
        }
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('POST' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }
        // Get post data
        $post_data = Yii::app()->request->getPost('PlayListUpdateRequest');
        if (empty($post_data)) {
            $post_data = file_get_contents("php://input");
        }
        // log
        self::log('Playlistupdate data: ' . print_r($post_data, TRUE), 'trace', $this->id);
        //Yii::trace(print_r($post_data, TRUE));
        // Decode post data
        $post_array = json_decode($post_data, true);
        $play_list = isset($post_array['list']) ? $post_array['list'] : array();
        $_offset = isset($post_array['offset']) ? intval($post_array['offset']) : 0;
        $_limit = isset($post_array['limit']) ? intval($post_array['limit']) : $this->playlist_maximum;
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->playlist_maximum : intval($_limit);
        $_limit = ($_limit > $this->playlist_maximum) ? $this->playlist_maximum : $_limit;

        // Get room device
        //$_device = DeviceState::model()->findByAttributes(array('room_id' => $this->_roomID, 'status' => 0));
        $_device = Yii::app()->db->createCommand()
                ->select('ds.device_id')
                ->from('{{device_state}} ds')
                ->join('{{room}} r', 'ds.room_id = r.id')
                ->join('{{device}} d', 'ds.device_id = d.id')
                ->where('r.id=:id AND d.type=:type', array(':id' => $this->_roomID, ':type' => 1))
                ->queryRow();

        if (!is_null($_device) && !empty($_device)) {
            $_deviceid = $_device['device_id'];

            // check uploaded list and update
            if (!empty($play_list) && is_array($play_list)) {
                $index_current = 1;
                $update_list = array();
                foreach ($play_list as $key => $_list) {
                    $_songid = isset($_list['songid']) ? $_list['songid'] : '';
                    if (empty($_songid)) {
                        continue;
                    }
                    $_index_num = isset($_list['index_num']) ? intval($_list['index_num']) : 0;
                    if ($_index_num < $index_current) {
                        $_index_num = $index_current;
                    } else {
                        $index_current = $_index_num;
                    }
                    //
                    $_status = 0;

                    // user id who added this song
                    $_create_user_id = isset($_list['userid']) ? intval($_list['userid']) : 0;

                    // TODO: import to device play list
                    // import(deviceid,songid,index_num,status);
                    if (isset(Yii::app()->user) && null !== Yii::app()->user) {
                        $_user_id = Yii::app()->user->id;
                    } else {
                        $_user_id = 0;
                    }
                    // get media id by songid
                    $_media = Media::model()->findByAttributes(array('songid' => $_songid));
                    if (!is_null($_media) && !empty($_media)) {
                        $update_list[] = array(
                            'device_id' => $_deviceid, 'media_id' => $_media->id, 'index_num' => $_index_num, 'status' => 0,
                            'create_time' => new CDbExpression('NOW()'),
                            'update_time' => new CDbExpression('NOW()'),
                            'create_user_id' => $_create_user_id,
                            'update_user_id' => $_user_id,
                        );
                        // process next list
                        $index_current++;
                    }
                }

                // TODO: batch import to device play list
                if (!empty($update_list)) {
                    $transaction = Yii::app()->db->beginTransaction();
                    try {
                        // TODO: first delete the old list
                        DevicePlaylist::model()->deleteAllByAttributes(array('device_id' => $_deviceid, 'status' => 0));

                        // then batch insert new list
                        $result_num = DevicePlaylist::model()->insertSeveral($update_list);

                        $transaction->commit();

                        // TODO: get the updated device play list
                        $criteria = new CDbCriteria();
                        $criteria->condition = 'status = :status and device_id = :deviceid';

                        // get current playing song
                        $has_playing = false;
                        $criteria->params = array(':deviceid' => $_deviceid, ':status' => 1);
                        $_playing = DevicePlaylist::model()->find($criteria);
                        if (!is_null($_playing) && !empty($_playing)) {
                            // get songid from media_id
                            if (self::RETURN_CURRENT_PLAYING_AFTER_UPDATE) {
                                $_media = $_playing->media;

                                $_post_time = $_playing->play_posttime;
                                $_timestamp = $_playing->play_timestamp;
                                $_play_status = $_playing->play_status;
                                $_play_status = empty($_play_status) ? 'PLAY' : $_play_status;
                                $_media_duration = $_media->duration;

                                // caculate the play time stamp
                                $_play_timestamp = (time() - $_post_time) + $_timestamp;
                                $_play_timestamp = ($_play_timestamp < 0) ? 0 : $_play_timestamp;
                                $_play_timestamp = ($_play_timestamp > $_media_duration) ? $_media_duration : $_play_timestamp;

                                // get user avatar information of song adder
                                $_userinfo = $this->getUserInfo($_playing->create_user_id);

                                $result_array['playing'] = array(
                                    'songid' => $_media->songid,
                                    'index_num' => intval($_playing->index_num),
                                    'songname' => $_media->name,
                                    'singername' => $_media->artist->name,
                                    'duration' => intval($_media->duration),
                                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                                    'userid' => $_userinfo['uid'],
                                    'nickname' => $_userinfo['nickname'],
                                    'avatarurl' => $_userinfo['avatarurl'],
                                    'status' => $_play_status,
                                    'timestamp' => $_play_timestamp
                                );
                            }
                            $has_playing = true;
                        }

                        // get play list
                        $criteria->params = array(':deviceid' => $_deviceid, ':status' => 0);

                        $_count = intval(DevicePlaylist::model()->count($criteria));

                        $criteria->offset = $_offset;
                        $criteria->limit = $_limit;
                        $criteria->order = 'index_num asc';

                        $play_list = DevicePlaylist::model()->findAll($criteria);
                        if (!empty($play_list)) {
                            // Get list success
                            $result_array['result'] = self::Success;
                            $result_array['msg'] = Yii::t('user', 'Play list updated success and get the new list!');
                            foreach ($play_list as $key => $_list) {
                                $_media = $_list->media;
                                // get user avatar information of song adder
                                $_userinfo = $this->getUserInfo($_list->create_user_id);

                                // set the first list as current playing list if needed
                                if (!$has_playing && self::NEED_SETUP_CURRENT_PLAYING_WHEN_UPDATE) {
                                    if (self::RETURN_CURRENT_PLAYING_AFTER_UPDATE) {
                                        $result_array['playing'] = array(
                                            'songid' => $_media->songid,
                                            'index_num' => 0,
                                            'songname' => $_media->name,
                                            'singername' => $_media->artist->name,
                                            'duration' => intval($_media->duration),
                                            'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                                            'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                                            'userid' => $_userinfo['uid'],
                                            'nickname' => $_userinfo['nickname'],
                                            'avatarurl' => $_userinfo['avatarurl'],
                                            'status' => 'PLAY',
                                            'timestamp' => 0
                                        );
                                    }
                                    $has_playing = true;
                                    // update current playing list to status 1
                                    $_playing = DevicePlaylist::model()->findByAttributes(array('device_id' => $_deviceid, 'media_id' => $_media->id, 'index_num' => $_list->index_num));
                                    if (!is_null($_playing) && !empty($_playing)) {
                                        $_playing->index_num = 0;
                                        $_playing->status = 1;
                                        $_playing->save();
                                    }
                                    $_count--;

                                    continue;
                                } else {
                                    if (!$has_playing && self::RETURN_CURRENT_PLAYING_AFTER_UPDATE) {
                                        $result_array['playing'] = array();
                                    }
                                }

                                if (self::RETURN_LIST_AFTER_UPDATE) {
                                    $result_array['list'][] = array(
                                        'songid' => $_media->songid,
                                        'index_num' => intval($_list->index_num),
                                        'songname' => $_media->name,
                                        'singername' => $_media->artist->name,
                                        'duration' => intval($_media->duration),
                                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                                        'userid' => $_userinfo['uid'],
                                        'nickname' => $_userinfo['nickname'],
                                        'avatarurl' => $_userinfo['avatarurl'],
                                    );
                                }
                            }
                            if (self::RETURN_LIST_AFTER_UPDATE) {
                                $result_array['total'] = $_count;
                            }

                            $result_array['result'] = self::Success;
                            $result_array['msg'] = Yii::t('user', 'Play list updated success!');
                        } else {
                            $result_array['result'] = self::Success;
                            $result_array['msg'] = Yii::t('user', 'Play list updated success, but can not get the new list!');
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        self::log($e->getMessage(), CLogger::LEVEL_ERROR, $this->id);
                        $result_array['msg'] = Yii::t('user', 'Play list updated failed!');
                    }
                } else {
                    // TODO: delete all list if uploaded list is empty
                    DevicePlaylist::model()->deleteAllByAttributes(array('device_id' => $_deviceid, 'status' => 0));

                    $result_array['result'] = self::Success;
                    $result_array['msg'] = Yii::t('user', 'Uploaded list is empty, and the play list be cleaned!');
                }
            } else {
                // TODO: delete all list if uploaded list is empty
                DevicePlaylist::model()->deleteAllByAttributes(array('device_id' => $_deviceid, 'status' => 0));

                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'Uploaded list is empty, and the play list be cleaned!');
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Room STB invalid!');
        }

        // Set response information
        $this->sendResults($result_array);
    }

    /**
     * Add one song to current play list
     * if current playing info does not exists, then select the first item as current playing item
     * if RETURN_LIST_AFTER_UPDATE set to be true, then return the updated play list
     * if RETURN_CURRENT_PLAYING set to be true, then return the current playing info
     */
    public function actionAddtoplaylist() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
        );
        if (self::RETURN_CURRENT_PLAYING_AFTER_UPDATE) {
            $result_array['playing'] = array();
        }
        if (self::RETURN_LIST_AFTER_UPDATE) {
            $result_array['total'] = 0;
            $result_array['list'] = array();
        }
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('POST' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }
        // Get post data
        $post_data = Yii::app()->request->getPost('PlayListAddRequest');
        if (empty($post_data)) {
            $post_data = file_get_contents("php://input");
        }
        // log
        self::log('Playlist Add data: ' . print_r($post_data, TRUE), 'trace', $this->id);
        //Yii::trace(print_r($post_data, TRUE));
        // Decode post data
        $post_array = json_decode($post_data, true);
        $_songid = isset($post_array['songid']) ? $post_array['songid'] : '';
        $_offset = isset($post_array['offset']) ? intval($post_array['offset']) : 0;
        $_limit = isset($post_array['limit']) ? intval($post_array['limit']) : $this->playlist_maximum;
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->playlist_maximum : intval($_limit);
        $_limit = ($_limit > $this->playlist_maximum) ? $this->playlist_maximum : $_limit;

        // Get room device
        //$_device = DeviceState::model()->findByAttributes(array('room_id' => $this->_roomID, 'status' => 0));
        $_device = Yii::app()->db->createCommand()
                ->select('ds.device_id')
                ->from('{{device_state}} ds')
                ->join('{{room}} r', 'ds.room_id = r.id')
                ->join('{{device}} d', 'ds.device_id = d.id')
                ->where('r.id=:id AND d.type=:type', array(':id' => $this->_roomID, ':type' => 1))
                ->queryRow();

        if (!is_null($_device) && !empty($_device)) {
            $_deviceid = $_device['device_id'];

            // Get media info
            $_media = Media::model()->findByAttributes(array('songid' => $_songid));
            // check uploaded list and update
            if (!is_null($_media) && !empty($_media)) {
                $insert_list = array();
                $_index_num = 101;
                // Get the max index number
                $_max_index_num = DevicePlaylist::model()->findBySql('select max(index_num) as index_num from {{device_playlist}} where device_id = :deviceid and status = :status', array(':deviceid' => $_deviceid, ':status' => 0));
                if (!is_null($_max_index_num) && !empty($_max_index_num)) {
                    $_index_num = intval($_max_index_num->index_num) + 1;
                }
                $_status = 0;
                // Get user id
                if (isset(Yii::app()->user) && null !== Yii::app()->user) {
                    $_user_id = Yii::app()->user->id;
                } else {
                    $_user_id = 0;
                }
                $insert_list[] = array(
                    'device_id' => $_deviceid, 'media_id' => $_media->id, 'index_num' => $_index_num, 'status' => 0,
                    'create_time' => new CDbExpression('NOW()'),
                    'update_time' => new CDbExpression('NOW()'),
                    'create_user_id' => $_user_id,
                    'update_user_id' => $_user_id,
                );

                // TODO: batch import to device play list
                if (!empty($insert_list)) {
                    $transaction = Yii::app()->db->beginTransaction();
                    try {
                        // then batch insert new list
                        $result_num = DevicePlaylist::model()->insertSeveral($insert_list);

                        $transaction->commit();

                        // TODO: get the updated device play list
                        $criteria = new CDbCriteria();
                        $criteria->condition = 'status = :status and device_id = :deviceid';

                        // get current playing song
                        $has_playing = false;
                        $criteria->params = array(':deviceid' => $_deviceid, ':status' => 1);
                        $_playing = DevicePlaylist::model()->find($criteria);
                        if (!is_null($_playing) && !empty($_playing)) {
                            // get songid from media_id
                            if (self::RETURN_CURRENT_PLAYING_AFTER_UPDATE) {
                                $_media = $_playing->media;

                                $_post_time = $_playing->play_posttime;
                                $_timestamp = $_playing->play_timestamp;
                                $_play_status = $_playing->play_status;
                                $_play_status = empty($_play_status) ? 'PLAY' : $_play_status;
                                $_media_duration = $_media->duration;

                                // caculate the play time stamp
                                $_play_timestamp = (time() - $_post_time) + $_timestamp;
                                $_play_timestamp = ($_play_timestamp < 0) ? 0 : $_play_timestamp;
                                $_play_timestamp = ($_play_timestamp > $_media_duration) ? $_media_duration : $_play_timestamp;

                                // get user avatar information of song adder
                                $_userinfo = $this->getUserInfo($_playing->create_user_id);

                                $result_array['playing'] = array(
                                    'songid' => $_media->songid,
                                    'index_num' => intval($_playing->index_num),
                                    'songname' => $_media->name,
                                    'singername' => $_media->artist->name,
                                    'duration' => intval($_media->duration),
                                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                                    'userid' => $_userinfo['uid'],
                                    'nickname' => $_userinfo['nickname'],
                                    'avatarurl' => $_userinfo['avatarurl'],
                                    'status' => $_play_status,
                                    'timestamp' => $_play_timestamp
                                );
                            }
                            $has_playing = true;
                        }

                        // get play list
                        $criteria->params = array(':deviceid' => $_deviceid, ':status' => 0);

                        $_count = intval(DevicePlaylist::model()->count($criteria));

                        $criteria->offset = $_offset;
                        $criteria->limit = $_limit;
                        $criteria->order = 'index_num asc';

                        $play_list = DevicePlaylist::model()->findAll($criteria);
                        if (!empty($play_list)) {
                            // Get list success
                            $result_array['result'] = self::Success;
                            $result_array['msg'] = Yii::t('user', 'Add song to play list success and get the new list!');
                            foreach ($play_list as $key => $_list) {
                                $_media = $_list->media;
                                // get user avatar information of song adder
                                $_userinfo = $this->getUserInfo($_list->create_user_id);

                                // set the first list as current playing list if needed
                                if (!$has_playing && self::NEED_SETUP_CURRENT_PLAYING_WHEN_UPDATE) {
                                    if (self::RETURN_CURRENT_PLAYING_AFTER_UPDATE) {
                                        $result_array['playing'] = array(
                                            'songid' => $_media->songid,
                                            'index_num' => 0,
                                            'songname' => $_media->name,
                                            'singername' => $_media->artist->name,
                                            'duration' => intval($_media->duration),
                                            'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                                            'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                                            'userid' => $_userinfo['uid'],
                                            'nickname' => $_userinfo['nickname'],
                                            'avatarurl' => $_userinfo['avatarurl'],
                                            'status' => 'PLAY',
                                            'timestamp' => 0
                                        );
                                    }
                                    $has_playing = true;
                                    // update current playing list to status 1
                                    $_playing = DevicePlaylist::model()->findByAttributes(array('device_id' => $_deviceid, 'media_id' => $_media->id, 'index_num' => $_list->index_num));
                                    if (!is_null($_playing) && !empty($_playing)) {
                                        $_playing->index_num = 0;
                                        $_playing->status = 1;
                                        $_playing->save();
                                    }
                                    $_count--;

                                    continue;
                                } else {
                                    if (!$has_playing && self::RETURN_CURRENT_PLAYING_AFTER_UPDATE) {
                                        $result_array['playing'] = array();
                                    }
                                }

                                if (self::RETURN_LIST_AFTER_UPDATE) {
                                    $result_array['list'][] = array(
                                        'songid' => $_media->songid,
                                        'index_num' => intval($_list->index_num),
                                        'songname' => $_media->name,
                                        'singername' => $_media->artist->name,
                                        'duration' => intval($_media->duration),
                                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                                        'userid' => $_userinfo['uid'],
                                        'nickname' => $_userinfo['nickname'],
                                        'avatarurl' => $_userinfo['avatarurl'],
                                    );
                                }
                            }
                            if (self::RETURN_LIST_AFTER_UPDATE) {
                                $result_array['total'] = $_count;
                            }

                            $result_array['result'] = self::Success;
                            $result_array['msg'] = Yii::t('user', 'Add song to play list success and get the new list!');
                        } else {
                            $result_array['result'] = self::Success;
                            $result_array['msg'] = Yii::t('user', 'Add song to play list success, but can not get the new list!');
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        self::log($e->getMessage(), CLogger::LEVEL_ERROR, $this->id);
                        $result_array['msg'] = Yii::t('user', 'Add song to play list failed!');
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'No added song!');
                }
            } else {
                $result_array['msg'] = Yii::t('user', 'Added song does not exists!');
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Room STB invalid!');
        }

        // Set response information
        $this->sendResults($result_array);
    }

    public function utf8Unescape($str) {
        if (empty($str)) {
            return $str;
        }
        $str = rawurldecode($str);
        preg_match_all("/%u.{4}|&#x.{4};|&#d+;|.+/U", $str, $r);
        $ar = $r[0];
        foreach ($ar as $k => $v) {
            if (substr($v, 0, 2) == "%u")
                $ar[$k] = mb_convert_encoding(pack("H4", substr($v, -4)), "utf-8", "UCS-2");
            elseif (substr($v, 0, 3) == "&#x")
                $ar[$k] = mb_convert_encoding(pack("H4", substr($v, 3, -1)), "utf-8", "UCS-2");
            elseif (substr($v, 0, 2) == "&#") {
                $ar[$k] = mb_convert_encoding(pack("H4", substr($v, 2, -1)), "utf-8", "UCS-2");
            }
        }
        return join("", $ar);
    }

    public function actionMediasearchOld() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_keyword = Yii::app()->request->getQuery('keyword');
        $_keyword = empty($_keyword) ? '' : trim($_keyword);
        $_keyword = $this->utf8Unescape($_keyword);
        self::log($_keyword);

        $_type = Yii::app()->request->getQuery('type');
        $_type = empty($_type) ? 1 : intval($_type);
        if (!in_array($_type, array(1, 2))) {
            $_type = 1;
        }

        $_offset = Yii::app()->request->getQuery('offset');
        $_limit = Yii::app()->request->getQuery('limit');
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->search_maximum : intval($_limit);
        $_limit = ($_limit > $this->search_maximum) ? $this->search_maximum : $_limit;

        // log
        $media_list = array();
        $media_ids_list = array();
        $_matched_count = 0;
        $_matched_total = 0;
        if (1 == $_type) {
            // TODO optimize media name search logic
            // 1 - acturely matched, like x
            // 2 - match begin, like x%
            // 3 - match partial, like %x%
            // 1-x----------------------------------------
            $media_result = $this->getMediaSearchList("name like '$_keyword'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 2-x%----------------------------------------
            $media_result = $this->getMediaSearchList("name like '$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 4-%x%----------------------------------------
            $media_result = $this->getMediaSearchList("name like '%$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 1-x----------------------------------------
            $media_result = $this->getMediaSearchList("name_pinyin like '$_keyword'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 2-x%----------------------------------------
            $media_result = $this->getMediaSearchList("name_pinyin like '$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 4-%x%----------------------------------------
            $media_result = $this->getMediaSearchList("name_pinyin like '%$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 11-x----------------------------------------
            $media_result = $this->getMediaSearchList("name_chinese like '$_keyword'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 12-x%----------------------------------------
            $media_result = $this->getMediaSearchList("name_chinese like '$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 14-%x%----------------------------------------
            $media_result = $this->getMediaSearchList("name_chinese like '%$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }
        }

        if (2 == $_type) {
            // TODO optimize artist search logic
            // 1 - acturely matched, like x
            // 2 - match begin, like x%
            // 3 - match partial, like %x%
            // 1-x----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name like '$_keyword'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 2-x%----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name like '$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 4-%x%----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name like '%$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 1-x----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name_pinyin like '$_keyword'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 2-x%----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name_pinyin like '$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 4-%x%----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name_pinyin like '%$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 11-x----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name_chinese like '$_keyword'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 12-x%----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name_chinese like '$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 14-%x%----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name_chinese like '%$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }
        }

        // 满足条件的中条数
        $_count = $_matched_total;

        if (!empty($media_list)) {
            // Get list success
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get searched media list success!');
            $result_array['total'] = $_count;
            foreach ($media_list as $key => $_media) {
                if (!is_null($_media->artist)) {
                    $_pinyin = $this->getArtistFirstPY($_media->artist);
                } else {
                    $_pinyin = '';
                }
                $result_array['list'][] = array(
                    'songid' => $_media->songid,
                    'songname' => $_media->name,
                    'singername' => $_media->artist->name,
                    'duration' => intval($_media->duration),
                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                    'firstpinyin' => $_pinyin
                );
            }
            $arrayList = $result_array['list'];
            if (1 == $_type) {
                // sort by firstpinyin
                //$result_array['list'] = $this->myArrayListSort($arrayList, "firstpinyin", SORT_ASC, SORT_REGULAR, 'songname', SORT_ASC, SORT_REGULAR);
                // sort by name , then firstpinyin
                $result_array['list'] = $this->myArrayListSort($arrayList, "songname", SORT_ASC, SORT_REGULAR, 'firstpinyin', SORT_ASC, SORT_REGULAR);
            } else {
                $result_array['list'] = $this->myArrayListSort($arrayList, 'songname', SORT_ASC, SORT_REGULAR);
            }
        } else {
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'No matched song!');
            $result_array['total'] = $_count;
        }

        // Set response information
        $this->sendResults($result_array);
    }

    public function actionMediasearch() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_keyword = Yii::app()->request->getQuery('keyword');
        $_keyword = empty($_keyword) ? '' : trim($_keyword);
        $_keyword = $this->utf8Unescape($_keyword);
        self::log($_keyword);

        $_type = Yii::app()->request->getQuery('type');
        $_type = empty($_type) ? 1 : intval($_type);
        if (!in_array($_type, array(1, 2))) {
            $_type = 1;
        }

        $_offset = Yii::app()->request->getQuery('offset');
        $_limit = Yii::app()->request->getQuery('limit');
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->search_maximum : intval($_limit);
        $_limit = ($_limit > $this->search_maximum) ? $this->search_maximum : $_limit;

        $_order = Yii::app()->request->getQuery('order');
        $_order = empty($_order) ? 0 : intval($_order);
        if ($_order > 0) {
            //$criteria->order = "name_pinyin DESC, name DESC";
            $song_order = "name_pinyin DESC, name_chinese DESC, name DESC";
        } else {
            //$criteria->order = "name_pinyin ASC, name ASC";
            $song_order = "name_pinyin ASC,name_chinese ASC, name ASC";
        }

        // log
        $media_list = array();
        $media_ids_list = array();
        $_matched_count = 0;
        $_matched_total = 0;
        if (1 == $_type) {
            // media like %keyword%, order by media name asc, name_chinese asc, name_pinyin asc
            // firstpinyin = name first
            $song_cond = "name like '$_keyword' OR name_chinese like '$_keyword' OR name_pinyin like '$_keyword'";
            $media_result = $this->getMediaSearchList($song_cond, $media_ids_list, $_offset, $_limit, $song_order);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            $song_cond = "name like '$_keyword%' OR name_chinese like '$_keyword%' OR name_pinyin like '$_keyword%'";
            $media_result = $this->getMediaSearchList($song_cond, $media_ids_list, $_offset, $_limit, $song_order);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            $song_cond = "name like '%$_keyword%' OR name_chinese like '%$_keyword%' OR name_pinyin like '%$_keyword%'";
            $media_result = $this->getMediaSearchList($song_cond, $media_ids_list, $_offset, $_limit, $song_order);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }
        }

        if (2 == $_type) {
            // artist like %keyword%, order by media name asc, name_chinese asc, name_pinyin asc
            // firstpinyin = name first
            $artist_cond = "name like '$_keyword' OR name_chinese like '$_keyword' OR name_pinyin like '$_keyword'";
            $media_result = $this->getArtistMediaSearchList($artist_cond, $media_ids_list, $_offset, $_limit, $song_order);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            $artist_cond = "name like '$_keyword%' OR name_chinese like '$_keyword%' OR name_pinyin like '$_keyword%'";
            $media_result = $this->getArtistMediaSearchList($artist_cond, $media_ids_list, $_offset, $_limit, $song_order);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            $artist_cond = "name like '%$_keyword%' OR name_chinese like '%$_keyword%' OR name_pinyin like '%$_keyword%'";
            $media_result = $this->getArtistMediaSearchList($artist_cond, $media_ids_list, $_offset, $_limit, $song_order);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }
        }

        // 满足条件的中条数
        $_count = $_matched_total;

        if (!empty($media_list)) {
            // Get list success
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get searched media list success!');
            $result_array['total'] = $_count;
            foreach ($media_list as $key => $_media) {
                //if (!is_null($_media->artist)) {
                //    $_pinyin = $this->getArtistFirstPY($_media->artist);
                //} else {
                //    $_pinyin = '';
                //}
                $_pinyin = $this->getSongFirstPY($_media);
                $result_array['list'][] = array(
                    'songid' => $_media->songid,
                    'songname' => $_media->name,
                    'singername' => $_media->artist->name,
                    'duration' => intval($_media->duration),
                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                    'firstpinyin' => $_pinyin
                );
            }
            $arrayList = $result_array['list'];
            //if (1 == $_type) {
            // sort by firstpinyin
            //$result_array['list'] = $this->myArrayListSort($arrayList, "firstpinyin", SORT_ASC, SORT_REGULAR, 'songname', SORT_ASC, SORT_REGULAR);
            // sort by name , then firstpinyin
            //$result_array['list'] = $this->myArrayListSort($arrayList, "songname", SORT_ASC, SORT_REGULAR, 'firstpinyin', SORT_ASC, SORT_REGULAR);
            //} else {
            //$result_array['list'] = $this->myArrayListSort($arrayList, 'songname', SORT_ASC, SORT_REGULAR);
            //}
        } else {
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'No matched song!');
            $result_array['total'] = $_count;
        }

        // Set response information
        $this->sendResults($result_array);
    }

    public function actionPinyinsearch() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_keyword = Yii::app()->request->getQuery('keyword');
        $_keyword = strtolower(empty($_keyword) ? '' : trim($_keyword));

        $_type = Yii::app()->request->getQuery('type');
        $_type = empty($_type) ? 1 : intval($_type);
        if (!in_array($_type, array(1, 2))) {
            $_type = 1;
        }

        $_offset = Yii::app()->request->getQuery('offset');
        $_limit = Yii::app()->request->getQuery('limit');
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->search_maximum : intval($_limit);
        $_limit = ($_limit > $this->search_maximum) ? $this->search_maximum : $_limit;

        // log
        $media_list = array();
        $media_ids_list = array();
        $_matched_count = 0;
        $_matched_total = 0;
        if (1 == $_type) {
            // TODO optimize media search logic, first name_pinyin, then name_chinese
            // 1 - acturely matched, like x
            // 2 - match begin, like x%
            // 3 - match partial, like %x%
            // 1-x----------------------------------------
            $media_result = $this->getMediaSearchList("name_pinyin like '$_keyword'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 2-x%----------------------------------------
            $media_result = $this->getMediaSearchList("name_pinyin like '$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 4-%x%----------------------------------------
            $media_result = $this->getMediaSearchList("name_pinyin like '%$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 11-x----------------------------------------
            $media_result = $this->getMediaSearchList("name_chinese like '$_keyword'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 12-x%----------------------------------------
            $media_result = $this->getMediaSearchList("name_chinese like '$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 14-%x%----------------------------------------
            $media_result = $this->getMediaSearchList("name_chinese like '%$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }
        }

        if (2 == $_type) {
            // TODO optimize artist search logic, first name_pinyin, then name_chinese
            // 1 - acturely matched, like x
            // 2 - match begin, like x%
            // 3 - match partial, like %x%
            // 1-x----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name_pinyin like '$_keyword'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 2-x%----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name_pinyin like '$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 4-%x%----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name_pinyin like '%$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 11-x----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name_chinese like '$_keyword'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 12-x%----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name_chinese like '$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }

            // 14-%x%----------------------------------------
            $media_result = $this->getArtistMediaSearchList("name_chinese like '%$_keyword%'", $media_ids_list, $_offset, $_limit);
            if ($media_result['count'] > 0) {
                $_matched_count += $media_result['count'];
                $_matched_total += $media_result['total'];
                $media_list = array_merge($media_list, $media_result['list']);
                $media_ids_list = array_merge($media_ids_list, $media_result['ids']);
                $_offset = ($_offset - $_matched_count) > 0 ? ($_offset - $_matched_count) : 0;
                $_limit = ($_limit - $_matched_count) > 0 ? ($_limit - $_matched_count) : 1;
            }
        }

        // 满足条件的中条数
        $_count = $_matched_total;

        if (!empty($media_list)) {
            // Get list success
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get searched media list success!');
            $result_array['total'] = $_count;
            foreach ($media_list as $key => $_media) {
                if (!is_null($_media->artist)) {
                    $_pinyin = $this->getArtistFirstPY($_media->artist);
                } else {
                    $_pinyin = '';
                }
                $result_array['list'][] = array(
                    'songid' => $_media->songid,
                    'songname' => $_media->name,
                    'singername' => $_media->artist->name,
                    'duration' => intval($_media->duration),
                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                    'firstpinyin' => $_pinyin
                );
            }
        } else {
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'No matched song!');
            $result_array['total'] = $_count;
        }

        // Set response information
        $this->sendResults($result_array);
    }

    public function actionArtistcategory() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'parentid' => 0,
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_parentid = Yii::app()->request->getQuery('parentid');
        $_parentid = empty($_parentid) ? 0 : intval($_parentid);
        $result_array['parentid'] = $_parentid;
        if ($_parentid < 0) {
            $this->sendResults($result_array, self::BadRequest);
        }

        $_offset = Yii::app()->request->getQuery('offset');
        $_limit = Yii::app()->request->getQuery('limit');
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->search_maximum : intval($_limit);
        $_limit = ($_limit > $this->search_maximum) ? $this->search_maximum : $_limit;

        // log
        $_count = intval(Artistcategory::model()->count());
        $criteria = new CDbCriteria();
        $criteria->offset = $_offset;
        $criteria->limit = $_limit;
        $_list = Artistcategory::model()->findAll($criteria);
        if (!empty($_list)) {
            // Get list success
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get artist category list success!');
            $result_array['total'] = $_count;
            foreach ($_list as $key => $_obj) {
                $result_array['list'][] = array(
                    'id' => intval($_obj->id),
                    'catename' => $_obj->name,
                    'smallpicurl' => $this->getPicAttachUrl($_obj->spic_url, 0),
                    'bigpicurl' => $this->getPicAttachUrl($_obj->bpic_url)
                );
            }
        } else {
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Artist category list is empty!');
            $result_array['total'] = $_count;
        }

        // Set response information
        $this->sendResults($result_array);
    }

    public function actionArtistsearch() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_keyword = Yii::app()->request->getQuery('keyword');
        $_keyword = empty($_keyword) ? '' : trim($_keyword);
        $_keyword = $this->utf8Unescape($_keyword);
        if (empty($_keyword)) {
            //$result_array['msg'] = '搜索关键字为空';
            $this->sendResults($result_array, self::BadRequest);
        }

        $_offset = Yii::app()->request->getQuery('offset');
        $_limit = Yii::app()->request->getQuery('limit');
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->search_maximum : intval($_limit);
        $_limit = ($_limit > $this->search_maximum) ? $this->search_maximum : $_limit;

        $artist_result = $this->getArtistSearchList("name_pinyin like '$_keyword%' or name like '$_keyword%'", array(), $_offset, $_limit);

        $result_total = $artist_result['total'];
        $result_count = $artist_result['count'];
        $need_more = $_limit - $result_count;
        if ($need_more > 0) {
            $artist_result_ext = $this->getArtistSearchList("name like '%$_keyword%'", array(), $_offset, $need_more);
            $result_total += $artist_result_ext['total'];
            $result_count += $artist_result_ext['count'];

            // TODO use + or array_merge?
            $artist_result_merge = ($artist_result['list'] + $artist_result_ext['list']);
            $result_array['list'] = $artist_result_merge;
        } else {
            $result_array['list'] = $artist_result['list'];
        }
        if ($result_count > 0) {
            $result_array['msg'] = Yii::t('user', 'Get artist search list success!');
        } else {
            $result_array['msg'] = Yii::t('user', 'Artist search list is empty!');
        }

        $result_array['result'] = self::Success;
        $result_array['total'] = $result_total;

        // Set response information
        $this->sendResults($result_array);
    }

    public function actionSongbyartist() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'artistid' => 0,
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_artistid = Yii::app()->request->getQuery('artistid');
        $_artistid = empty($_artistid) ? 0 : intval($_artistid);
        $result_array['artistid'] = $_artistid;
        if ($_artistid <= 0) {
            $this->sendResults($result_array, self::BadRequest);
        }
        $_keyword = Yii::app()->request->getQuery('keyword');
        $_keyword = empty($_keyword) ? '' : trim($_keyword);
        $_keyword = $this->utf8Unescape($_keyword);

        $_order = Yii::app()->request->getQuery('order');
        $_order = empty($_order) ? 0 : intval($_order);
        $_offset = Yii::app()->request->getQuery('offset');
        $_limit = Yii::app()->request->getQuery('limit');
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->search_maximum : intval($_limit);
        $_limit = ($_limit > $this->search_maximum) ? $this->search_maximum : $_limit;

        $criteria = new CDbCriteria();
        $_cond = "artist_id = $_artistid";
        if (!empty($_keyword)) {
            $song_cond = " and (name_pinyin like '$_keyword%' OR name like '$_keyword%' OR name_chinese like '$_keyword%')";
            $_cond .= $song_cond;
        }
        $criteria->condition = $_cond;
        $_count = intval(Media::model()->count($criteria));
        $criteria->offset = $_offset;
        $criteria->limit = $_limit;
        if ($_order > 0) {
            //$criteria->order = "name_pinyin DESC, name DESC";
            $criteria->order = "name_pinyin DESC, name_chinese DESC, name DESC";
        } else {
            //$criteria->order = "name_pinyin ASC, name ASC";
            $criteria->order = "name_pinyin ASC,name_chinese ASC, name ASC";
        }
        $media_list = Media::model()->findAll($criteria);
        if (!empty($media_list)) {
            // Get list success
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get song by artist list success!');
            $result_array['total'] = $_count;

            //$_artist = Artist::model()->findByPk($_artistid);
            //if (!is_null($_artist)) {
            //    $_pinyin = $this->getArtistFirstPY($_artist);
            //} else {
            //    $_pinyin = '';
            //}

            foreach ($media_list as $key => $_media) {
                $result_array['list'][] = array(
                    'songid' => $_media->songid,
                    'songname' => $_media->name,
                    'singername' => $_media->artist->name,
                    'duration' => intval($_media->duration),
                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                    'firstpinyin' => $this->getSongFirstPY($_media)
                );
            }
        } else {
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Song by artist list is empty!');
            $result_array['total'] = $_count;
        }

        // Set response information
        $this->sendResults($result_array);
    }

    public function actionSongbyalphabet() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_keyword = Yii::app()->request->getQuery('keyword');
        $_keyword = empty($_keyword) ? '' : trim($_keyword);
        if (empty($_keyword)) {
            $this->sendResults($result_array, self::BadRequest);
        }

        $_offset = Yii::app()->request->getQuery('offset');
        $_limit = Yii::app()->request->getQuery('limit');
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->search_maximum : intval($_limit);
        $_limit = ($_limit > $this->search_maximum) ? $this->search_maximum : $_limit;

        $_order = Yii::app()->request->getQuery('order');
        $_order = empty($_order) ? 0 : intval($_order);
        if ($_order > 0) {
            $song_order = "name_pinyin DESC, name_chinese DESC, name DESC";
        } else {
            $song_order = "name_pinyin ASC,name_chinese ASC, name ASC";
        }

        $criteria = new CDbCriteria();
        $criteria->condition = "name_pinyin like '$_keyword%' OR name_chinese like '$_keyword%' OR name like '$_keyword%'";
        $_count = intval(Media::model()->count($criteria));
        $criteria->offset = $_offset;
        $criteria->limit = $_limit;
        //$criteria->order = $song_order;
        $media_list = Media::model()->findAll($criteria);
        if (!empty($media_list)) {
            // Get list success
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get song by alphabet list success!');
            $result_array['total'] = $_count;

            foreach ($media_list as $key => $_media) {
                if (!is_null($_media->artist)) {
                    $_pinyin = $this->getArtistFirstPY($_media->artist);
                } else {
                    $_pinyin = '';
                }

                $result_array['list'][] = array(
                    'songid' => $_media->songid,
                    'songname' => $_media->name,
                    'singername' => $_media->artist->name,
                    'duration' => intval($_media->duration),
                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                    'firstpinyin' => $_pinyin
                );
            }
            // sort by firstpinyin
            $arrayList = $result_array['list'];
            $result_array['list'] = $this->myArrayListSort($arrayList, "firstpinyin", SORT_ASC, SORT_REGULAR, 'songname', SORT_ASC, SORT_REGULAR);
        } else {
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Song by alphabet list is empty!');
            $result_array['total'] = $_count;
        }

        // Set response information
        $this->sendResults($result_array);
    }

    public function actionMusiccharts() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_offset = Yii::app()->request->getQuery('offset');
        $_limit = Yii::app()->request->getQuery('limit');
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->search_maximum : intval($_limit);
        $_limit = ($_limit > $this->search_maximum) ? $this->search_maximum : $_limit;

        // log
        $_count = intval(Musiccharts::model()->count());
        $criteria = new CDbCriteria();
        $criteria->offset = $_offset;
        $criteria->limit = $_limit;
        $_list = Musiccharts::model()->findAll($criteria);
        if (!empty($_list)) {
            // Get list success
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get music chart list success!');
            $result_array['total'] = $_count;
            foreach ($_list as $key => $_obj) {
                $result_array['list'][] = array(
                    'id' => intval($_obj->id),
                    'chartname' => $_obj->name,
                    'smallpicurl' => $this->getPicAttachUrl($_obj->spic_url, 0),
                    'bigpicurl' => $this->getPicAttachUrl($_obj->bpic_url)
                );
            }
        } else {
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Music chart list is empty!');
            $result_array['total'] = $_count;
        }

        // Set response information
        $this->sendResults($result_array);
    }

    public function actionSongbychart() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'chartid' => 0,
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_chartid = Yii::app()->request->getQuery('chartid');
        $_chartid = empty($_chartid) ? 0 : intval($_chartid);
        $result_array['chartid'] = $_chartid;
        if ($_chartid <= 0) {
            $this->sendResults($result_array, self::BadRequest);
        }

        $_offset = Yii::app()->request->getQuery('offset');
        $_limit = Yii::app()->request->getQuery('limit');
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->search_maximum : intval($_limit);
        $_limit = ($_limit > $this->search_maximum) ? $this->search_maximum : $_limit;

        $criteria = new CDbCriteria();
        $criteria->condition = "chart_id = ${_chartid}";
        $_count = intval(Musicofcharts::model()->count($criteria));
        $criteria->offset = $_offset;
        $criteria->limit = $_limit;
        $criteria->order = 'rank ASC, id DESC';
        $media_list = Musicofcharts::model()->findAll($criteria);
        if (!empty($media_list)) {
            // Get list success
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get song by chart list success!');
            $result_array['total'] = $_count;

            foreach ($media_list as $key => $_obj) {
                if (!is_null($_obj->media)) {
                    $_media = $_obj->media;

                    if (!is_null($_media->artist)) {
                        $_pinyin = $this->getArtistFirstPY($_media->artist);
                    } else {
                        $_pinyin = '';
                    }
                    $result_array['list'][] = array(
                        'songid' => $_media->songid,
                        'songname' => $_media->name,
                        //'rank' => $_obj->rank,
                        'singername' => $_media->artist->name,
                        'duration' => intval($_media->duration),
                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                        'firstpinyin' => $_pinyin
                    );
                }
            }
            // sort by firstpinyin, sort by rank
            //$arrayList = $result_array['list'];
            //$result_array['list'] = $this->myArrayListSort($arrayList, "firstpinyin", SORT_ASC, SORT_REGULAR, 'songname', SORT_ASC, SORT_REGULAR);
        } else {
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Song by chart list is empty!');
            $result_array['total'] = $_count;
        }

        // Set response information
        $this->sendResults($result_array);
    }

    public function actionArtistbycategory() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'cateid' => 0,
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get query data
        $_cateid = Yii::app()->request->getQuery('cateid');
        $_cateid = empty($_cateid) ? 0 : intval($_cateid);
        $result_array['cateid'] = $_cateid;
        if ($_cateid <= 0) {
            $this->sendResults($result_array, self::BadRequest);
        }

        $_offset = Yii::app()->request->getQuery('offset');
        $_limit = Yii::app()->request->getQuery('limit');
        $_offset = empty($_offset) ? 0 : intval($_offset);
        $_limit = empty($_limit) ? $this->search_maximum : intval($_limit);
        $_limit = ($_limit > $this->search_maximum) ? $this->search_maximum : $_limit;

        $criteria = new CDbCriteria();
        $criteria->condition = "category_id = ${_cateid}";
        $_count = intval(Artistofcategory::model()->count($criteria));
        $criteria->offset = $_offset;
        $criteria->limit = $_limit;
        $media_list = Artistofcategory::model()->findAll($criteria);
        if (!empty($media_list)) {
            // Get list success
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get artist by category list success!');
            $result_array['total'] = $_count;

            foreach ($media_list as $key => $_obj) {
                if (!is_null($_obj->artist)) {
                    $_artist = $_obj->artist;

                    $_pinyin = $this->getArtistFirstPY($_artist);
                    $result_array['list'][] = array(
                        'id' => intval($_artist->id),
                        'name' => $_artist->name,
                        'smallpicurl' => $this->getArtistPicUrl($_artist->spic_url, 0),
                        'bigpicurl' => $this->getArtistPicUrl($_artist->bpic_url),
                        'firstpinyin' => $_pinyin,
                    );
                }
            }
            // sort by firstpinyin, sort by rank
            $arrayList = $result_array['list'];
            //$result_array['list'] = $this->myArrayListSort($arrayList, "firstpinyin", SORT_ASC, SORT_REGULAR, 'name', SORT_ASC, SORT_REGULAR);
            $result_array['list'] = $this->myArrayListSort($arrayList, "firstpinyin", SORT_ASC, SORT_REGULAR);
        } else {
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Artist by category list is empty!');
            $result_array['total'] = $_count;
        }

        // Set response information
        $this->sendResults($result_array);
    }

    /**
     * Get the media picture url
     * @param Media $media
     * @param String $filename
     * @param integer $picsize
     * @return string
     */
    public function getMediaPicUrl($media, $filename = '', $picsize = 1) {
        $_baseurl = Yii::app()->createAbsoluteUrl('/');
        $_mediabaseurl = (empty(Yii::app()->params['media_url']) ? $_baseurl . '/uploads/media' : Yii::app()->params['media_url']);
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
        $_artist_pic_url = '';
        if (!is_null($media->artist)) {
            $_artist = $media->artist;
            if (1 == $picsize) {
                $_artist_pic = $_artist->bpic_url;
            } else {
                $_artist_pic = $_artist->spic_url;
            }
            $_artist_pic_url = empty($_artist_pic) ? '' : ($_artist->attach_url . '/' . str_replace('\\', '/', $_artist_pic));
        }
        //$file_url = empty($filename) ? '' : str_replace('\\', '/', $filename);
        $_media_url = empty($filename) ? '' : $_mediaurl . str_replace('\\', '/', $filename);
        //$_media_full_url = empty($file_url) ? $default_song_pic : ($_mediaurl . $file_url);
        $_media_full_url = empty($_media_url) ? (empty($_artist_pic_url) ? $default_song_pic : $_artist_pic_url) : $_media_url;
        return $_media_full_url;
    }

    /**
     * Get the normal picture attach url
     * @param String $filename
     * @param integer $picsize
     * @return string
     */
    public function getPicAttachUrl($filename = '', $picsize = 1) {
        $_baseurl = Yii::app()->createAbsoluteUrl('/');
        $_mediabaseurl = (empty(Yii::app()->params['upload_url']) ? ($_baseurl . '/uploads') : Yii::app()->params['upload_url']) . '/attach';
        $_mediaurl = $_mediabaseurl . '/picture/';

        $default_picture_setting = empty(Yii::app()->params['picture_default_setting']) ? array() : Yii::app()->params['picture_default_setting'];
        if (1 == $picsize) {
            $default_pic = (isset($default_picture_setting['bigpic']) && !empty($default_picture_setting['bigpic'])) ? ($_mediabaseurl . '/' . $default_picture_setting['bigpic']) : '';
        } else {
            $default_pic = (isset($default_picture_setting['smallpic']) && !empty($default_picture_setting['smallpic'])) ? ($_mediabaseurl . '/' . $default_picture_setting['smallpic']) : '';
        }
        $file_url = empty($filename) ? '' : str_replace('\\', '/', $filename);
        $_media_full_url = empty($file_url) ? $default_pic : ($_mediaurl . $file_url);
        return $_media_full_url;
    }

    /**
     * Get the artist picture attach url
     * @param String $filename
     * @param integer $picsize
     * @return string
     */
    public function getArtistPicUrl($filename = '', $picsize = 1) {
        $_baseurl = Yii::app()->createAbsoluteUrl('/');
        $_mediabaseurl = (empty(Yii::app()->params['upload_url']) ? ($_baseurl . '/uploads') : Yii::app()->params['upload_url']) . '/attach';
        $_mediaurl = $_mediabaseurl . '/artist/';

        $default_picture_setting = empty(Yii::app()->params['picture_default_setting']) ? array() : Yii::app()->params['picture_default_setting'];
        if (1 == $picsize) {
            $default_pic = (isset($default_picture_setting['bigpic']) && !empty($default_picture_setting['bigpic'])) ? ($_mediabaseurl . '/' . $default_picture_setting['bigpic']) : '';
        } else {
            $default_pic = (isset($default_picture_setting['smallpic']) && !empty($default_picture_setting['smallpic'])) ? ($_mediabaseurl . '/' . $default_picture_setting['smallpic']) : '';
        }
        $file_url = empty($filename) ? '' : str_replace('\\', '/', $filename);
        $_media_full_url = empty($file_url) ? $default_pic : ($_mediaurl . $file_url);
        return $_media_full_url;
    }

    /**
     * get the qr code url
     * @param string $qrdata
     * @return string
     */
    public function getQRCodeUrl($qrdata) {
        $_baseurl = Yii::app()->createAbsoluteUrl('/');
        $qr_filename = 'QR_' . md5($qrdata) . '.png';
        $qr_filePath = (empty(Yii::app()->params['qrcode_folder']) ? (Yii::getPathOfAlias('webroot.uploads') . DIRECTORY_SEPARATOR . 'qr') : Yii::app()->params['qrcode_folder']);
        $qr_fileUrl = (empty(Yii::app()->params['qrcode_url']) ? $_baseurl . '/uploads/qr' : Yii::app()->params['qrcode_url']);
        $qr_fullFilePath = $qr_filePath . DIRECTORY_SEPARATOR . $qr_filename;
        $qr_fullUrl = $qr_fileUrl . '/' . $qr_filename;

        // check to create the qr code now
        if (!is_file($qr_fullFilePath)) {
            $code = new QRCode($qrdata);
            $code->create($qr_fullFilePath);
        }
        return $qr_fullUrl;
    }

    /**
     * Get user id, nickname and avatar url
     * @param type $uid
     * @return type
     */
    public function getUserInfo($uid) {
        // get user avatar information of song adder
        $_user_nickname = '';
        $_user_avatar_url = '';
        if (!empty(Yii::app()->params['user_default_setting']) && is_array(Yii::app()->params['user_default_setting'])) {
            $_user_nickname = Yii::app()->params['user_default_setting']['nickname'];
            $_user_avatar_url = Yii::app()->createAbsoluteUrl('//') . '/avatar/' . Yii::app()->params['user_default_setting']['avatarurl'];
        }
        $_user = PlatformUser::model()->findByPk($uid);
        if (!is_null($_user)) {
            $_auth_type = strtoupper($_user['auth_type']);
            if (in_array($_auth_type, $this->_other_auth_types)) {
                $_user_nickname = '';
                $_user_avatar_url = '';
            } else if ($_auth_type == ApiController::DEFAULT_AUTH_TYPE) {
                $_user_nickname = empty($_user->display_name) ? $_user_nickname : $_user->display_name;
                $_user_avatar_url = '';
            } else {
                $_user_nickname = empty($_user->display_name) ? $_user_nickname : $_user->display_name;
                $_user_avatar_url = empty($_user->avatar_url) ? $_user_avatar_url : $_user->avatar_url;
            }
        } else {
            $_user_nickname = '';
            $_user_avatar_url = '';
        }

        return array('uid' => intval($uid), 'nickname' => $_user_nickname, 'avatarurl' => $_user_avatar_url);
    }

    /**
     * 根据歌曲名，拼音等条件检索歌曲
     * @param string $condition 歌曲查询条件
     * @param array $params 需要排除的歌曲ID数组
     * @param int $offset 歌曲的开始序号
     * @param int $limit 歌曲的条数限制
     * @param string $order 排序
     * @return array 满足条件的歌曲列表
     */
    public function getMediaSearchList($condition = '', $params = array(), $offset = 0, $limit = 100, $order = '') {
        $result = array('total' => 0, 'count' => 0, 'list' => array());
        $criteria = new CDbCriteria();
        //$criteria->compare('name_pinyin', $_keyword, TRUE);
        $criteria->condition = $condition;
        if (!empty($params)) {
            $criteria->addNotInCondition('id', $params);
        }

        $_count = intval(Media::model()->count($criteria));
        if ($_count > 0) {
            $criteria->offset = $offset;
            $criteria->limit = $limit;
            if (!empty($order)) {
                $criteria->order = $order;
            }
            $media_list = Media::model()->findAll($criteria);
            $result['total'] = $_count;
            $result['count'] = count($media_list);
            $result['list'] = $media_list;
            $media_ids = array();
            foreach ($media_list as $key => $_media) {
                $media_ids[] = $_media->id;
            }
            $result['ids'] = $media_ids;
        }
        return $result;
    }

    /**
     * 根据歌手名，歌手拼音等条件检索歌曲
     * @param string $condition 歌手查询条件
     * @param array $params 需要排除的歌曲ID数组
     * @param int $offset 歌曲的开始序号
     * @param int $limit 歌曲的条数限制
     * @param string $order 排序
     * @return array 满足条件的歌曲列表
     */
    public function getArtistMediaSearchList($condition = '', $params = array(), $offset = 0, $limit = 100, $order = '') {
        $result = array('total' => 0, 'count' => 0, 'list' => array());

        $criteria = new CDbCriteria();
        //$criteria->compare('name_pinyin', $_keyword, TRUE);
        $criteria->condition = $condition;
        $criteria->offset = $offset;
        $criteria->limit = $limit;

        // find artist
        $artist_list = Artist::model()->findAll($criteria);
        $artist_ids = array();
        if (!empty($artist_list)) {
            foreach ($artist_list as $key => $_artist) {
                $artist_ids[] = $_artist->id;
            }
        }

        // find artist songs
        if (!empty($artist_ids)) {
            $criteria = new CDbCriteria();
            $criteria->addInCondition('artist_id', $artist_ids);
            if (!empty($params)) {
                $criteria->addNotInCondition('id', $params);
            }

            $_count = intval(Media::model()->count($criteria));
            if ($_count > 0) {
                $criteria->offset = $offset;
                $criteria->limit = $limit;
                if (!empty($order)) {
                    $criteria->order = $order;
                }
                $media_list = Media::model()->findAll($criteria);
                $result['total'] = $_count;
                $result['count'] = count($media_list);
                $result['list'] = $media_list;
                $media_ids = array();
                foreach ($media_list as $key => $_media) {
                    $media_ids[] = $_media->id;
                }
                $result['ids'] = $media_ids;
            }
        }

        return $result;
    }

    public function getArtistSearchList($condition = '', $params = array(), $offset = 0, $limit = 100) {
        $result = array('total' => 0, 'count' => 0, 'list' => array());

        $criteria = new CDbCriteria();
        //$criteria->compare('name_pinyin', $_keyword, TRUE);
        $criteria->condition = $condition;
        $_count = intval(Artist::model()->count($criteria));

        // find artist
        $criteria->offset = $offset;
        $criteria->limit = $limit;
        $artist_list = Artist::model()->findAll($criteria);
        if (!empty($artist_list)) {
            $_list = array();
            foreach ($artist_list as $key => $_artist) {
                $_pinyin = $this->getArtistFirstPY($_artist);
                $_list[] = array(
                    'id' => intval($_artist->id),
                    'name' => $_artist->name,
                    'smallpicurl' => $this->getArtistPicUrl($_artist->spic_url, 0),
                    'bigpicurl' => $this->getArtistPicUrl($_artist->bpic_url),
                    'firstpinyin' => $_pinyin,
                );
            }
            $result['total'] = $_count;
            $result['count'] = count($artist_list);
            $result['list'] = $_list;
        }

        return $result;
    }

    public function getArtistFirstPY($artist) {
        if (is_null($artist)) {
            return '';
        }
        //$_pinyin = substr($artist->name_pinyin, 0, 1);
        $_pinyin = mb_substr($artist->name_pinyin, 0, 1, 'utf-8');
        if (!empty($_pinyin)) {
            $_pinyin = strtoupper($_pinyin);
        } else {
            //$_pinyin = substr($artist->name, 0, 1);
            $_pinyin = mb_substr($artist->name, 0, 1, 'utf-8');
        }
        return $_pinyin;
    }

    public function getSongFirstPY($media) {
        if (is_null($media)) {
            return '';
        }
        //$_pinyin = substr($artist->name_pinyin, 0, 1);
        $_pinyin = mb_substr($media->name_pinyin, 0, 1, 'utf-8');
        if (!empty($_pinyin)) {
            $_pinyin = strtoupper($_pinyin);
        } else {
            $_pinyin = mb_substr($media->name, 0, 1, 'utf-8');
        }
        return $_pinyin;
    }

    function myArrayListSort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_REGULAR, $second_key = '', $second_order = SORT_ASC, $second_type = SORT_REGULAR) {
        $key_arrays = array();
        $second_arrays = array();
        if (is_array($arrays)) {
            foreach ($arrays as $array) {
                if (is_array($array)) {
                    if (isset($array[$sort_key])) {
                        $key_arrays[] = $array[$sort_key];
                    }
                    if (!empty($second_key)) {
                        if (isset($array[$second_key])) {
                            $second_arrays[] = $array[$second_key];
                        }
                    }
                } else {
                    return array();
                }
            }
        } else {
            return array();
        }
        if (!empty($key_arrays) && !empty($second_arrays)) {
            array_multisort($key_arrays, $sort_order, $sort_type, $second_arrays, $second_order, $second_type, $arrays);
        } else if (!empty($key_arrays)) {
            array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
        } else if (!empty($second_arrays)) {
            array_multisort($second_arrays, $second_order, $second_type, $arrays);
        }

        return $arrays;
    }

}
