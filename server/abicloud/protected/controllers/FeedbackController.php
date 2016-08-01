<?php

class FeedbackController extends ApiController {
	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function actionFeedback() {
		// die();
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);

		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}

		// Get post data
		$post_data = Yii::app()->request->getPost('LoginRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents('php://input');
		}
		$post_array = json_decode($post_data, true);
		$feedback = new feedback();
		$feedback->ktvid = isset($post_array['ktvid']) ? $post_array['ktvid'] : '';
//        $feedback->openid    = isset($post_array['openid']) ? $post_array['openid'] : '';
		$feedback->openid = Yii::app()->user->getId();
		$feedback->userid = Yii::app()->user->getID();
		$feedback->errortype = isset($post_array['errortype']) ? $post_array['errortype'] : '';
		if ($feedback->ktvid === '' || $feedback->openid === '' || $feedback->errortype === '') {
			$result_array['msg'] = Yii::t('user', 'params error');
			$this->sendResults($result_array, self::BadRequest);
		}
		$feedback->save();
		$result_array['result'] = self::Success;
		$result_array['msg'] = Yii::t('feedback', 'feedback send success');
		// Set response information
		$this->sendResults($result_array);
	}

	public function actionComment() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('LoginRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents('php://input');
		}
		$post_array = json_decode($post_data, true);
		$comment = new comment();
		$comment->ktvid = isset($post_array['ktvid']) ? $post_array['ktvid'] : '';
		$comment->openid = 0;
		$comment->userid = Yii::app()->user->getID();
		$comment->orderid = 0;
		$comment->DecorationRating = isset($post_array['DecorationRating']) ? $post_array['DecorationRating'] : '';
		$comment->SoundRating = isset($post_array['SoundRating']) ? $post_array['SoundRating'] : '';
		$comment->ServiceRating = isset($post_array['ServiceRating']) ? $post_array['ServiceRating'] : '';
		$comment->ConsumerRating = isset($post_array['ConsumerRating']) ? $post_array['ConsumerRating'] : '';
		$comment->FoodRating = isset($post_array['FoodRating']) ? $post_array['FoodRating'] : '';
		$comment->appRating = isset($post_array['appRating']) ? $post_array['appRating'] : '';
		if ($comment->ktvid === '' || $comment->DecorationRating === '' || $comment->SoundRating === '' || $comment->ServiceRating === '' || $comment->ConsumerRating === '' || $comment->FoodRating === '' || $comment->appRating === '') {
			$this->sendResults($result_array, self::BadRequest);
		}
		$com_result = $comment->save();
//        var_dump($comment);die();
		if ($com_result) {
			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('feedback', 'comment send success');
			// Set response information
			$this->sendResults($result_array);
		} else {
			$result_array['msg'] = Yii::t('feedback', 'save failed');
			// Set response information
			$this->sendResults($result_array);
		}

	}

	public function actionCommentApp() {
		$result_array = array(
			'result' => self::BadRequest,
			'msg' => Yii::t('user', 'Request method illegal!'),
		);
		$request_type = Yii::app()->request->getRequestType();
		if ('POST' != $request_type) {
			$this->sendResults($result_array, self::BadRequest);
		}
		// Get post data
		$post_data = Yii::app()->request->getPost('LoginRequest');
		if (empty($post_data)) {
			$post_data = file_get_contents('php://input');
		}
		$post_array = json_decode($post_data, true);
		$comment = new comment();
		$comment->ktvid = isset($post_array['ktvid']) ? $post_array['ktvid'] : '';
		$comment->orderid = isset($post_array['orderid']) ? $post_array['orderid'] : '';
		$comment->openid = 0;
		$comment->userid = Yii::app()->user->getID();
		$comment->appRating = isset($post_array['appRating']) ? intval($post_array['appRating']) : 0;
		if ($comment->ktvid === '' || $comment->appRating === 0) {
			$result_array['msg'] = '参数不能为空';
			$this->sendResults($result_array, self::BadRequest);
		}
		$comment->DecorationRating = isset($post_array['DecorationRating']) ? $post_array['DecorationRating'] : 0;
		$comment->SoundRating = isset($post_array['SoundRating']) ? $post_array['SoundRating'] : 0;
		$comment->ServiceRating = isset($post_array['ServiceRating']) ? $post_array['ServiceRating'] : 0;
		$comment->ConsumerRating = isset($post_array['ConsumerRating']) ? $post_array['ConsumerRating'] : 0;
		$comment->FoodRating = isset($post_array['FoodRating']) ? $post_array['FoodRating'] : 0;
		$com_result = $comment->save();
		if ($com_result) {
			$changed_pingjia = RoomBooking::model()->updateRatingStatus($comment->orderid);
			if ($changed_pingjia['status'] == 0) {
				$result_array['pingjia_status'] = 1;
			} else {
				$result_array['pingjia_status'] = 0;
			}

			$result_array['result'] = self::Success;
			$result_array['msg'] = Yii::t('feedback', 'comment send success');
			// Set response information
			$this->sendResults($result_array);
		} else {
			var_dump($comment);
			die();
			$result_array['msg'] = Yii::t('feedback', 'save failed');
			// Set response information
			$this->sendResults($result_array);
		}

	}

	// public function actionScore() {
	// 	$result_array = array(
	// 		'result' => self::BadRequest,
	// 		'msg' => Yii::t('user', 'Request method illegal!'),
	// 	);
	// 	$request_type = Yii::app()->request->getRequestType();
	// 	if ('POST' != $request_type) {
	// 		$this->sendResults($result_array, self::BadRequest);
	// 	}

	// }
}

// {
// "ktvid":
// "openid":
// "DecorationRating": 0,
// "SoundRating": 0,
// "ServiceRating": 0,
// "ConsumerRating": 0,
// "FoodRating": 0,
//     地址有误
//     电话有误
//     房型有误
//     价格有误
//     服务信息有误
//     KTV已关
// }

// {"ktvid":0,"openid":0,"DecorationRating": 0,"SoundRating": 0,"ServiceRating": 0,"FoodRatingConsumerRating": 0,"": 0}
// {"ktvid":0,"openid":0,"errortype": 0} // errortype 1-6 分别：1.地址有误2.电话有误3.房型有误4.价格有误5.服务信息有误6.KTV已关
