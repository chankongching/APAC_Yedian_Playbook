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
class StbController extends ApiController {

    /**
     * Option for return new list after list updated
     */
    const RETURN_LIST_AFTER_UPDATE = true;
    const RETURN_CURRENT_PLAYING = true;

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function actionStbmedialist() {
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
        $_limit = empty($_limit) ? 100 : intval($_limit);

        // log
        $_count = Media::model()->count();
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
                $result_array['list'][] = array(
                    'songid' => $_media->songid,
                    'songurl' => $this->getMediaVideoUrl($_media, $_media->video_url),
                    'songname' => $_media->name,
                    'singername' => $_media->artist->name,
                    'duration' => $_media->duration,
                    'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                    'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url)
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

    public function actionStbmediainfo() {
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
        if ('POST' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get post data
        $post_data = Yii::app()->request->getPost('SongInfoRequest');
        if (empty($post_data)) {
            $post_data = file_get_contents("php://input");
        }
        // log
        self::log(print_r($post_data, TRUE), 'trace', $this->id);
        // Decode post data
        $post_array = json_decode($post_data, true);

        // Get query data
        $_songid = isset($post_array['songid']) ? $post_array['songid'] : '';
        $_deviceid = isset($post_array['deviceid']) ? $post_array['deviceid'] : '';

        // log
        $_media = Media::model()->findByAttributes(array('songid' => $_songid));
        if (!empty($_media)) {
            // Get media info success
            $result_array['result'] = self::Success;
            $result_array['msg'] = Yii::t('user', 'Get media info success!');
            $result_array['songid'] = $_media->songid;
            $result_array['songurl'] = $this->getMediaVideoUrl($_media, $_media->video_url);
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

    /**
     * Get the play list
     * if current playing info does not exists, then select the first item as current playing item
     * if RETURN_CURRENT_PLAYING set to be true, then return the current playing info
     */
    public function actionStbplaylist() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'playlist' => array(),
        );
        if (self::RETURN_CURRENT_PLAYING) {
            $result_array['playing'] = array();
        }
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('POST' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get post data
        $post_data = Yii::app()->request->getPost('PlayListRequest');
        if (empty($post_data)) {
            $post_data = file_get_contents("php://input");
        }
        // log
        self::log(print_r($post_data, TRUE), 'trace', $this->id);
        // Decode post data
        $post_array = json_decode($post_data, true);

        // Get query data
        $_offset = isset($post_array['offset']) ? intval($post_array['offset']) : 0;
        $_limit = isset($post_array['limit']) ? intval($post_array['limit']) : 100;
        $_deviceid = isset($post_array['deviceid']) ? $post_array['deviceid'] : '';

        if (!empty($_deviceid)) {
            // Get the real device id
            $_device_id = 0;
            $_device = Device::model()->findByAttributes(array('imei' => $_deviceid, 'status' => 0));
            if (!is_null($_device) && !empty($_device)) {
                $_device_id = $_device->id;
            }

            // log
            $criteria = new CDbCriteria();
            $criteria->condition = 'status = :status and device_id = :deviceid';

            // get current playing song
            $has_playing = false;
            $criteria->params = array(':deviceid' => $_device_id, ':status' => 1);
            $_playing = DevicePlaylist::model()->find($criteria);
            if (!is_null($_playing) && !empty($_playing)) {
                // get songid from media_id
                if (self::RETURN_CURRENT_PLAYING) {
                    $_media = $_playing->media;
                    // get user avatar information of song adder
                    $_userinfo = $this->getUserInfo($_playing->create_user_id);

                    $result_array['playing'] = array(
                        'songid' => $_media->songid,
                        'songurl' => $this->getMediaVideoUrl($_media, $_media->video_url),
                        'index_num' => intval($_playing->index_num),
                        'songname' => $_media->name,
                        'singername' => $_media->artist->name,
                        'duration' => intval($_media->duration),
                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                        'nickname' => $_userinfo['nickname'],
                        'avatarurl' => $_userinfo['avatarurl'],
                        'tracks' => $_media->audio_count,
                        'origintrack' => $_media->origin_audio
                    );
                }
                $has_playing = true;
            }

            // get play list
            $criteria->params = array(':deviceid' => $_device_id, ':status' => 0);

            $_count = intval(DevicePlaylist::model()->count($criteria));

            $criteria->offset = $_offset;
            $criteria->limit = $_limit;
            $criteria->order = 'index_num asc';
            $play_list = DevicePlaylist::model()->findAll($criteria);
            if (!empty($play_list)) {
                // Get list success
                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'Get play list success!');
                foreach ($play_list as $key => $_list) {
                    $_media = $_list->media;
                    // get user avatar information of song adder
                    $_userinfo = $this->getUserInfo($_list->create_user_id);

                    // set the first list as current playing list if needed
                    if (!$has_playing) {
                        if (self::RETURN_CURRENT_PLAYING) {
                            $result_array['playing'] = array(
                                'songid' => $_media->songid,
                                'songurl' => $this->getMediaVideoUrl($_media, $_media->video_url),
                                'index_num' => 0,
                                'songname' => $_media->name,
                                'singername' => $_media->artist->name,
                                'duration' => intval($_media->duration),
                                'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                                'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                                'nickname' => $_userinfo['nickname'],
                                'avatarurl' => $_userinfo['avatarurl'],
                                'tracks' => $_media->audio_count,
                                'origintrack' => $_media->origin_audio
                            );
                        }
                        $has_playing = true;
                        // update current playing list to status 1
                        $_playing = DevicePlaylist::model()->findByAttributes(array('device_id' => $_device_id, 'media_id' => $_media->id, 'index_num' => $_list->index_num));
                        if (!is_null($_playing) && !empty($_playing)) {
                            $_playing->index_num = 0;
                            $_playing->status = 1;
                            $_playing->save();
                        }
                        $_count--;

                        continue;
                    }

                    $result_array['playlist'][] = array(
                        'songid' => $_media->songid,
                        'songurl' => $this->getMediaVideoUrl($_media, $_media->video_url),
                        'index_num' => intval($_list->index_num),
                        'songname' => $_media->name,
                        'singername' => $_media->artist->name,
                        'duration' => intval($_media->duration),
                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                        'nickname' => $_userinfo['nickname'],
                        'avatarurl' => $_userinfo['avatarurl'],
                        'tracks' => $_media->audio_count,
                        'origintrack' => $_media->origin_audio
                    );
                }
                $result_array['total'] = $_count;
            } else {
                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'Play list is empty!');
                $result_array['total'] = $_count;
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Room STB ID invalid!');
        }

        // log to trace
        self::log(print_r($result_array, TRUE), 'trace', $this->id);

        // Set response information
        $this->sendResults($result_array);
    }

    /**
     * Before continue play, check expire status and get the new play list
     * it should be checked every time when need to play the next item
     * if current playing info does not exists, then select the first item as current playing item
     * if RETURN_CURRENT_PLAYING set to be true, then return the current playing info
     */
    public function actionStbcheckexpire() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'status' => 1,
            'total' => 0,
            'playlist' => array(),
        );
        if (self::RETURN_CURRENT_PLAYING) {
            $result_array['playing'] = array();
        }
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('POST' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get post data
        $post_data = Yii::app()->request->getPost('CheckExpireRequest');
        if (empty($post_data)) {
            $post_data = file_get_contents("php://input");
        }
        // log
        self::log(print_r($post_data, TRUE), 'trace', $this->id);
        // Decode post data
        $post_array = json_decode($post_data, true);

        // Get query data
        $_offset = isset($post_array['offset']) ? intval($post_array['offset']) : 0;
        $_limit = isset($post_array['limit']) ? intval($post_array['limit']) : 100;
        $_deviceid = isset($post_array['deviceid']) ? $post_array['deviceid'] : '';

        if (!empty($_deviceid)) {
            // Get the real device id
            $_device_id = 0;
            $_device = Device::model()->findByAttributes(array('imei' => $_deviceid, 'status' => 0));
            if (!is_null($_device) && !empty($_device)) {
                $_device_id = $_device->id;
            }

            // TODO: get expired status, temporary set as ok
            // check check in expired or not
            if ($this->isCheckinExpired($_device_id)) {
                $result_array['status'] = 1;
                $result_array['msg'] = Yii::t('user', 'Check in expired!');
                $this->sendResults($result_array);
            }

            // now ready to get play list
            $result_array['status'] = 0;

            // first delete current playing list
            // TODO: set status to 2 as played item, and record played time to index_num
            DevicePlaylist::model()->updateAll(array('status' => 2, 'index_num' => time()), 'device_id = :did AND status = :status', array(':did' => $_device_id, ':status' => 1));
            // TODO: delete 3 hours ago list
            // 
            //DevicePlaylist::model()->deleteAllByAttributes(array('device_id' => $_device_id, 'status' => 1));
            $has_playing = false;
            $criteria = new CDbCriteria();
            $criteria->condition = 'status = :status and device_id = :deviceid';

            // get play list
            $criteria->params = array(':deviceid' => $_device_id, ':status' => 0);

            $_count = intval(DevicePlaylist::model()->count($criteria));

            $criteria->offset = $_offset;
            $criteria->limit = $_limit;
            $criteria->order = 'index_num asc';
            $play_list = DevicePlaylist::model()->findAll($criteria);
            if (!empty($play_list)) {
                // Get list success
                foreach ($play_list as $key => $_list) {
                    $_media = $_list->media;
                    // get user avatar information of song adder
                    $_userinfo = $this->getUserInfo($_list->create_user_id);

                    // set the first list as current playing info
                    if (!$has_playing) {
                        if (self::RETURN_CURRENT_PLAYING) {
                            $result_array['playing'] = array(
                                'songid' => $_media->songid,
                                'songurl' => $this->getMediaVideoUrl($_media, $_media->video_url),
                                'index_num' => 0,
                                'songname' => $_media->name,
                                'singername' => $_media->artist->name,
                                'duration' => intval($_media->duration),
                                'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                                'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                                'nickname' => $_userinfo['nickname'],
                                'avatarurl' => $_userinfo['avatarurl'],
                                'tracks' => $_media->audio_count,
                                'origintrack' => $_media->origin_audio
                            );
                        }
                        $has_playing = true;
                        // update current playing list to status 1
                        $_playing = DevicePlaylist::model()->findByAttributes(array('device_id' => $_device_id, 'media_id' => $_media->id, 'index_num' => $_list->index_num));
                        if (!is_null($_playing) && !empty($_playing)) {
                            $_playing->index_num = 0;
                            $_playing->status = 1;
                            $_playing->save();
                        }
                        $_count--;

                        continue;
                    }

                    $result_array['playlist'][] = array(
                        'songid' => $_media->songid,
                        'songurl' => $this->getMediaVideoUrl($_media, $_media->video_url),
                        'index_num' => intval($_list->index_num),
                        'songname' => $_media->name,
                        'singername' => $_media->artist->name,
                        'duration' => intval($_media->duration),
                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                        'nickname' => $_userinfo['nickname'],
                        'avatarurl' => $_userinfo['avatarurl'],
                        'tracks' => $_media->audio_count,
                        'origintrack' => $_media->origin_audio
                    );
                }
                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'Please check the status and play list to start!');
            } else {
                $result_array['msg'] = Yii::t('user', 'Play list is empty!');
            }
            $result_array['total'] = $_count;
        } else {
            $result_array['msg'] = Yii::t('user', 'Room STB ID invalid!');
        }

        // log to trace
        self::log(print_r($result_array, TRUE), 'trace', $this->id);

        // Set response information
        $this->sendResults($result_array);
    }

    /**
     * When STB try to play at the frst time, check expire status and get the new play list
     * only used for the first time
     * if current playing info does not exists, then select the first item as current playing item
     * if RETURN_CURRENT_PLAYING set to be true, then return the current playing info
     */
    public function actionStbcheckstart() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'status' => 1,
            'total' => 0,
            'playlist' => array(),
            'checkinqrurl' => '',
        );
        if (self::RETURN_CURRENT_PLAYING) {
            $result_array['playing'] = array();
        }
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('POST' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get post data
        $post_data = Yii::app()->request->getPost('CheckExpireRequest');
        if (empty($post_data)) {
            $post_data = file_get_contents("php://input");
        }
        // log
        self::log(print_r($post_data, TRUE), 'trace', $this->id);
        // Decode post data
        $post_array = json_decode($post_data, true);

        // Get query data
        $_offset = isset($post_array['offset']) ? intval($post_array['offset']) : 0;
        $_limit = isset($post_array['limit']) ? intval($post_array['limit']) : 100;
        $_deviceid = isset($post_array['deviceid']) ? $post_array['deviceid'] : '';

        if (!empty($_deviceid)) {
            // Get the real device id
            $_device_id = 0;
            $_device = Device::model()->findByAttributes(array('imei' => $_deviceid, 'status' => 0));
            if (!is_null($_device) && !empty($_device)) {
                $_device_id = $_device->id;
            }

            // TODO: get expired status, temporary set as ok
            // check check in expired or not
            if ($this->isCheckinExpired($_device_id)) {
                $result_array['status'] = 1;
                $result_array['msg'] = Yii::t('user', 'Check in expired!');
                $this->sendResults($result_array);
            }

            // now ready to get the play list
            $result_array['status'] = 0;

            // get play list
            $criteria = new CDbCriteria();
            $criteria->condition = 'status = :status and device_id = :deviceid';

            // get current playing song
            $has_playing = false;
            $criteria->params = array(':deviceid' => $_device_id, ':status' => 1);
            $_playing = DevicePlaylist::model()->find($criteria);
            if (!is_null($_playing) && !empty($_playing)) {
                // get songid from media_id
                if (self::RETURN_CURRENT_PLAYING) {
                    $_media = $_playing->media;
                    // get user avatar information of song adder
                    $_userinfo = $this->getUserInfo($_playing->create_user_id);

                    $result_array['playing'] = array(
                        'songid' => $_media->songid,
                        'songurl' => $this->getMediaVideoUrl($_media, $_media->video_url),
                        'index_num' => intval($_playing->index_num),
                        'songname' => $_media->name,
                        'singername' => $_media->artist->name,
                        'duration' => intval($_media->duration),
                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                        'nickname' => $_userinfo['nickname'],
                        'avatarurl' => $_userinfo['avatarurl'],
                        'tracks' => $_media->audio_count,
                        'origintrack' => $_media->origin_audio
                    );
                }
                $has_playing = true;
            }

            // get play list
            $criteria->params = array(':deviceid' => $_device_id, ':status' => 0);

            $_count = intval(DevicePlaylist::model()->count($criteria));

            $criteria->offset = $_offset;
            $criteria->limit = $_limit;
            $criteria->order = 'index_num asc';
            $play_list = DevicePlaylist::model()->findAll($criteria);
            if (!empty($play_list)) {
                foreach ($play_list as $key => $_list) {
                    $_media = $_list->media;
                    // get user avatar information of song adder
                    $_userinfo = $this->getUserInfo($_list->create_user_id);

                    // set the first list as current playing info
                    if (!$has_playing) {
                        if (self::RETURN_CURRENT_PLAYING) {
                            $result_array['playing'] = array(
                                'songid' => $_media->songid,
                                'songurl' => $this->getMediaVideoUrl($_media, $_media->video_url),
                                'index_num' => 0,
                                'songname' => $_media->name,
                                'singername' => $_media->artist->name,
                                'duration' => intval($_media->duration),
                                'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                                'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                                'nickname' => $_userinfo['nickname'],
                                'avatarurl' => $_userinfo['avatarurl'],
                                'tracks' => $_media->audio_count,
                                'origintrack' => $_media->origin_audio
                            );
                        }
                        $has_playing = true;
                        // update current playing list to status 1
                        $_playing = DevicePlaylist::model()->findByAttributes(array('device_id' => $_device_id, 'media_id' => $_media->id, 'index_num' => $_list->index_num));
                        if (!is_null($_playing) && !empty($_playing)) {
                            $_playing->index_num = 0;
                            $_playing->status = 1;
                            $_playing->save();
                        }
                        $_count--;

                        continue;
                    }

                    $result_array['playlist'][] = array(
                        'songid' => $_media->songid,
                        'songurl' => $this->getMediaVideoUrl($_media, $_media->video_url),
                        'index_num' => intval($_list->index_num),
                        'songname' => $_media->name,
                        'singername' => $_media->artist->name,
                        'duration' => intval($_media->duration),
                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                        'nickname' => $_userinfo['nickname'],
                        'avatarurl' => $_userinfo['avatarurl'],
                        'tracks' => $_media->audio_count,
                        'origintrack' => $_media->origin_audio
                    );
                }

                // get qR code and other url
                $result_array['checkinqrurl'] = $this->getCheckinQRUrl($_device_id);

                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'Please check the status and play list to start!');
            } else {
                $result_array['msg'] = Yii::t('user', 'Play list is empty!');
            }
            $result_array['total'] = $_count;
        } else {
            $result_array['msg'] = Yii::t('user', 'Room STB ID invalid!');
        }

        // log to trace
        self::log(print_r($result_array, TRUE), 'trace', $this->id);

        // Set response information
        $this->sendResults($result_array);
    }

    public function actionStbregister() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'deviceid' => '',
            'backgroundurl' => '',
            'downloadqrurl' => '',
            'checkinqrurl' => '',
            'wechaturl' => '',
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('POST' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get post data
        $post_data = Yii::app()->request->getPost('RegistrationRequest');
        if (empty($post_data)) {
            $post_data = file_get_contents("php://input");
        }
        // log
        self::log(print_r($post_data, TRUE), 'trace', $this->id);
        // Decode post data
        $post_array = json_decode($post_data, true);

        // Get query data
        $_stburl = isset($post_array['stburl']) ? ($post_array['stburl']) : '';
        $_imei = isset($post_array['mac']) ? ($post_array['mac']) : '';

        if (!empty($_imei) && !empty($_stburl)) {
            $_baseurl = Yii::app()->createAbsoluteUrl('/');
            $_backgroundUrl = (empty(Yii::app()->params['background_url']) ? $_baseurl . '/uploads/background.png' : Yii::app()->params['background_url']);
            $_downloadUrl = (empty(Yii::app()->params['download_url']) ? $_baseurl . '/uploads/download.png' : Yii::app()->params['download_url']);
            $_wechatUrl = (empty(Yii::app()->params['wechat_url']) ? $_baseurl . '/uploads/wechat.png' : Yii::app()->params['wechat_url']);
            $result_array['backgroundurl'] = $_backgroundUrl;
            $result_array['downloadqrurl'] = $_downloadUrl;
            $result_array['wechaturl'] = $_wechatUrl;
            // create wechat download qr code
            $qrdata = Yii::app()->createAbsoluteUrl('/') . '/download.html';
            $result_array['downloadqrurl'] = $this->getQRCodeUrl($qrdata);

            // log
            $_device = Device::model()->findByAttributes(array('imei' => $_imei, 'type' => 1, 'status' => 0));
            if (!is_null($_device) && !empty($_device)) {
                // Update exists device
                $_deviceid = $_device->id;
                $_device->ip = $_stburl;

                if ($_device->save()) {
                    $result_array['result'] = self::Success;
                    $result_array['deviceid'] = $_device->imei;
                    // Check device bund status
                    $_device_state = DeviceState::model()->findByAttributes(array('device_id' => $_deviceid, 'status' => 0));
                    if (!is_null($_device_state) && !empty($_device_state)) {
                        $result_array['msg'] = Yii::t('user', 'STB registration success!');

                        // get qR code and other url
                        $result_array['checkinqrurl'] = $this->getCheckinQRUrl($_deviceid);
                    } else {
                        $result_array['msg'] = Yii::t('user', 'STB registration success, but can not work before assign to a room!');
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'STB registration failed!');
                }
            } else {
                // Add new device
                $newdevice = new Device();
                $newdevice->imei = $_imei;
                $newdevice->ip = $_stburl;
                $newdevice->type = 1;
                $newdevice->status = 0;
                $newdevice->name = 'STB ' . $_imei;
                if ($newdevice->save()) {
                    $result_array['result'] = self::Success;
                    $result_array['msg'] = Yii::t('user', 'STB registration success, but can not work before assign to a room!');
                } else {
                    $result_array['msg'] = Yii::t('user', 'STB registration failed!');
                }
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Request parameters illegal!');
        }

        // log to trace
        self::log(print_r($result_array, TRUE), 'trace', $this->id);

        // Set response information
        $this->sendResults($result_array);
    }

    /**
     * STB send play status to server periodly, and get the new play list
     * 
     * if RETURN_CURRENT_PLAYING set to be true, then return the current playing info
     */
    public function actionStbstatus() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'playlist' => array(),
        );
        if (self::RETURN_CURRENT_PLAYING) {
            $result_array['playing'] = array();
        }
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('POST' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get post data
        $post_data = Yii::app()->request->getPost('StatusRequest');
        if (empty($post_data)) {
            $post_data = file_get_contents("php://input");
        }
        // log
        self::log(print_r($post_data, TRUE), 'trace', $this->id);
        // Decode post data
        $post_array = json_decode($post_data, true);

        // Get query data
        $_offset = isset($post_array['offset']) ? intval($post_array['offset']) : 0;
        $_limit = isset($post_array['limit']) ? intval($post_array['limit']) : 100;
        $_deviceid = isset($post_array['deviceid']) ? $post_array['deviceid'] : '';
        $_songid = isset($post_array['songid']) ? trim($post_array['songid']) : '';
        $_status = isset($post_array['status']) ? $post_array['status'] : '';
        $_duration = isset($post_array['duration']) ? intval($post_array['duration']) : 0;
        // TODO add music track number ?
        $_timestamp = isset($post_array['timestamp']) ? intval($post_array['timestamp']) : 0;
        $_posttime = isset($post_array['posttime']) ? intval($post_array['posttime']) : 0;

        if (!empty($_deviceid)) {
            // Get the real device id
            $_device_id = 0;
            $_device = Device::model()->findByAttributes(array('imei' => $_deviceid, 'status' => 0));
            if (!is_null($_device) && !empty($_device)) {
                $_device_id = $_device->id;
            }


            $criteria = new CDbCriteria();
            $criteria->condition = 'status = :status and device_id = :deviceid';

            // get current playing song
            $has_playing = false;
            $criteria->params = array(':deviceid' => $_device_id, ':status' => 1);
            $_playing = DevicePlaylist::model()->find($criteria);
            if (!is_null($_playing) && !empty($_playing)) {
                // get songid from media_id
                $_media = $_playing->media;
                // get user avatar information of song adder
                $_userinfo = $this->getUserInfo($_playing->create_user_id);

                // check if current play item == uploaded song id
                if (!empty($_songid) && $_songid == $_media->songid) {
                    // TODO: matched
                    $_playing->play_status = $_status;
                    $_playing->play_timestamp = $_timestamp;
                    $_playing->play_posttime = $_posttime;
                    $_playing->save();

                    // 更新media时长
                    if (!empty($_duration) && (empty($_media->duration) || $_media->duration == 0)) {
                        $_media->duration = intval($_duration);
                        $_media->save();
                    }
                } else {
                    // TODO: update the current play item ? 
                    ;
                }
                if (self::RETURN_CURRENT_PLAYING) {
                    $result_array['playing'] = array(
                        'songid' => $_media->songid,
                        'songurl' => $this->getMediaVideoUrl($_media, $_media->video_url),
                        'index_num' => intval($_playing->index_num),
                        'songname' => $_media->name,
                        'singername' => $_media->artist->name,
                        'duration' => intval($_media->duration),
                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                        'nickname' => $_userinfo['nickname'],
                        'avatarurl' => $_userinfo['avatarurl'],
                        'tracks' => $_media->audio_count,
                        'origintrack' => $_media->origin_audio
                    );
                }
                $has_playing = true;
            }

            // get play list
            $criteria->params = array(':deviceid' => $_device_id, ':status' => 0);

            $_count = intval(DevicePlaylist::model()->count($criteria));

            $criteria->offset = $_offset;
            $criteria->limit = $_limit;
            $criteria->order = 'index_num asc';
            $play_list = DevicePlaylist::model()->findAll($criteria);
            if (!empty($play_list)) {
                // Get list success
                foreach ($play_list as $key => $_list) {
                    $_media = $_list->media;
                    // get user avatar information of song adder
                    $_userinfo = $this->getUserInfo($_list->create_user_id);

                    $result_array['playlist'][] = array(
                        'songid' => $_media->songid,
                        'songurl' => $this->getMediaVideoUrl($_media, $_media->video_url),
                        'index_num' => intval($_list->index_num),
                        'songname' => $_media->name,
                        'singername' => $_media->artist->name,
                        'duration' => intval($_media->duration),
                        'smallpicurl' => $this->getMediaPicUrl($_media, $_media->spic_url, 0),
                        'bigpicurl' => $this->getMediaPicUrl($_media, $_media->bpic_url),
                        'nickname' => $_userinfo['nickname'],
                        'avatarurl' => $_userinfo['avatarurl'],
                        'tracks' => $_media->audio_count,
                        'origintrack' => $_media->origin_audio
                    );
                }
                $result_array['msg'] = Yii::t('user', 'Player status updated!');
            } else {
                $result_array['msg'] = Yii::t('user', 'Player status updated, but the play list is empty!');
            }
            $result_array['result'] = self::Success;
            $result_array['total'] = $_count;
        } else {
            $result_array['msg'] = Yii::t('user', 'Room STB ID invalid!');
        }

        // Set response information
        $this->sendResults($result_array);
    }

    /**
     * Get the media picture url
     * @param Media $media
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

        $file_url = empty($filename) ? '' : str_replace('\\', '/', $filename);
        $_media_full_url = empty($file_url) ? $default_song_pic : ($_mediaurl . $file_url);
        return $_media_full_url;
    }

    /**
     * Get the media video url
     * cate_id like 1, DVD5 should added into category
     * @param Media $media
     * @return string
     */
    public function getMediaVideoUrl($media, $filename = '') {
        if (!empty($media->video_real_url)) {
            return $media->video_real_url;
        }
        $_baseurl = Yii::app()->createAbsoluteUrl('/');
        $_mediabaseurl = (empty(Yii::app()->params['media_url']) ? $_baseurl . '/uploads/media' : Yii::app()->params['media_url']);
        if (!empty($media->video_dir_name)) {
            $_mediaurl = $_mediabaseurl . '/' . $media->video_dir_name . '/';
        } else {
            $_cate_id = $media->category_id;
            $_mediaurl = $_mediabaseurl . '/' . $_cate_id . '/video/';
        }

        $default_song_setting = empty(Yii::app()->params['song_default_setting']) ? array() : Yii::app()->params['song_default_setting'];
        $default_song_video = (isset($default_song_setting['video']) && !empty($default_song_setting['video'])) ? ($_mediabaseurl . '/' . $default_song_setting['video']) : '';

        $file_url = empty($filename) ? '' : str_replace('\\', '/', $filename);
        $_media_full_url = empty($file_url) ? $default_song_video : ($_mediaurl . $file_url);
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
     * Get device check in qr code
     * @param integer $device_id
     * @return string Check in QR code
     */
    public function getCheckinQRUrl($device_id, $only_code = false) {
        // Get room id by device id
        $_room_id = 0;
        $_devicestate = DeviceState::model()->findByAttributes(array('device_id' => $device_id));
        if (is_null($_devicestate) || empty($_devicestate)) {
            return '';
        }
        $_room_id = $_devicestate->room_id;
        // get qR code
        $qrcode = CheckinCode::model()->findByAttributes(array('room_id' => $_room_id));
        if (is_null($qrcode)) {
            return '';
        }

        // check in data
        if ($only_code) {
            $qrdata = $qrcode->code;
        } else {
            $qrdata = Yii::app()->createAbsoluteUrl('/') . '/user/checkin/?cid=' . $qrcode->code;
        }
        $siteurl = Yii::app()->createAbsoluteUrl('/');
        $downloadurl = Yii::app()->createAbsoluteUrl('/') . '/site/download/';
        $checkinurl = $qrdata;
        $qrcodeinfo = $downloadurl . 'SITEURL-' . base64url_encode($siteurl) . '/CHECKINURL-' . base64url_encode($checkinurl);
        return($this->getQRCodeUrl($qrcodeinfo));
    }

    /**
     * Check wheather check in expired or not
     * @param type $device_id STB device id
     * @return boolean Check in expired or not
     */
    public function isCheckinExpired($device_id) {
        // Get room id by device id
        $_room_id = 0;
        $_devicestate = DeviceState::model()->findByAttributes(array('device_id' => $device_id));
        if (!is_null($_devicestate) && !empty($_devicestate)) {
            $_room_id = $_devicestate->room_id;
            // get available qR code
            $qrcode = CheckinCode::model()->findByAttributes(array('room_id' => $_room_id));
            if (!is_null($qrcode) && !empty($qrcode)) {
                $_qr_code = $qrcode->code;
                $_expire_time = intval($qrcode->expire);
                $_current_time = time();

                // TODO: also check room check in status, get the largest expire time
                $_checkinstate = CheckinState::model()->findByAttributes(array('room_id' => $_room_id, 'code' => $_qr_code));
                if (!is_null($_checkinstate) && !empty($_checkinstate)) {
                    $_room_expire_time = $_checkinstate->expire;
                    if ($_expire_time < $_room_expire_time) {
                        $_expire_time = $_room_expire_time;
                    }
                }

                if ($_expire_time === 0 || $_expire_time > $_current_time) {
                    return false;
                }
            }
        }

        self::log('Check in expired!', CLogger::LEVEL_WARNING, $this->id);
        return true;
    }

    public function actionStbavatarlist() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'total' => 0,
            'avatarlist' => array(),
        );

        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('POST' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        // Get post data
        $post_data = Yii::app()->request->getPost('AvatarListRequest');
        if (empty($post_data)) {
            $post_data = file_get_contents("php://input");
        }
        // log
        Yii::trace(print_r($post_data, TRUE));
        // Decode post data
        $post_array = json_decode($post_data, true);

        // Get query data
        $_offset = isset($post_array['offset']) ? intval($post_array['offset']) : 0;
        $_limit = isset($post_array['limit']) ? intval($post_array['limit']) : 100;
        $_deviceid = isset($post_array['deviceid']) ? $post_array['deviceid'] : '';

        if (!empty($_deviceid)) {
            // Get the real device id
            $_device_id = 0;
            $_device = Device::model()->findByAttributes(array('imei' => $_deviceid, 'status' => 0));
            if (!is_null($_device) && !empty($_device)) {
                $_device_id = $_device->id;
                $_device_state = DeviceState::model()->findByAttributes(array('device_id' => $_device_id));
                if (!is_null($_device_state) && !empty($_device_state)) {
                    $cur_room = $_device_state->room;
                    if (!is_null($cur_room) && !empty($cur_room)) {
                        // Add demo user
                        if (!empty(Yii::app()->params['room_user_list'])) {
                            $room_demo_user_list = Yii::app()->params['room_user_list'];
                            if (is_array($room_demo_user_list)) {
                                foreach ($room_demo_user_list as $kkey => $kobj) {
                                    $room_demo_user = $kobj;
                                    if (!empty($room_demo_user) && is_array($room_demo_user)) {
                                        $user_list[] = array(
                                            'nickname' => $room_demo_user['nickname'],
                                            'avatarurl' => Yii::app()->createAbsoluteUrl('//') . '/avatar/' . $room_demo_user['avatarurl'],
                                        );
                                    }
                                }
                            }
                        }
                        // Add online user
                        $cur_checked_in_users = $cur_room->checkinUsers;
                        $user_list = array();
                        foreach ($cur_checked_in_users as $key => $obj) {
                            $cur_user = $obj->u;
                            $user_list[] = array('nickname' => empty($cur_user->display_name) ? $cur_user->username : $cur_user->display_name, 'avatarurl' => $cur_user->avatar_url);
                        }

                        $result_array['result'] = self::Success;
                        $result_array['total'] = count($user_list);
                        $result_array['avatarlist'] = $user_list;
                        $result_array['msg'] = Yii::t('user', 'Room {name} checked in user list got!', array('{name}' => $cur_room->roomid));
                    } else {
                        $result_array['msg'] = Yii::t('user', 'Room Id incorrect or no checked in user!');
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'Room STB ID not ready!');
                }
            } else {
                $result_array['msg'] = Yii::t('user', 'Room STB ID invalid!');
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Room STB ID must not be empty!');
        }

        // Set response information
        $this->sendResults($result_array);
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

}
