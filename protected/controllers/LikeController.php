<?php
class LikeController extends Controller {

	public function actionIndex() {
		$this->renderSuccess(array('message'=>"Like a post? Show your appreciation using /like!"));
	}

	public function actionCreate() {
		if(isset($_POST['Like'])) {
			$like = Like::create($_POST['Like']);
			if(!$like->errors) {
				$this->renderSuccess(array('post_id'=>$like->post_id,'user_id'=>$like->user_id));
			} else {
				$this->renderError($this->getErrorMessageFromModelErrors($like));
			}
		} else {
			$this->renderError('ERROR.');
		}
	}

	public function actionDelete($id){
		$like = Like::model()->findByPk($id);
		if(!$like) {
			$this->renderError('Like ID does not exist.');
		}
		else {       
			$like->deactivate();
			$like->save();
			$this->renderSuccess(array('success'=>"Like removed."));
		}
	}

}