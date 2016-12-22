<?php
class LikeController extends Controller {

	public function actionIndex() {
		$this->renderSuccess(array('message'=>"Like a post? Show your appreciation using /like!"));
	}

	public function actionCreate() {
		if(isset($_POST['Like'])) {
			$existing_like = Like::model()->active()->findByAttributes(array('user_id'=>$_POST['Like']['user_id'],'post_id'=>$_POST['Like']['post_id']));
			if(!$existing_like)
			{
				$existing_like = Like::model()->inactive()->findByAttributes(array('user_id'=>$_POST['Like']['user_id'],'post_id'=>$_POST['Like']['post_id']));
				if(!$existing_like) {
					$like = Like::create($_POST['Like']);
					if(!$like->errors) {
						$this->renderSuccess(array('post_id'=>$like->post_id,'user_id'=>$like->user_id));
					}
				}
				else {
					$existing_like->activate();
					$this->renderSuccess(array('liked_id'=>$existing_like->id,'post_id'=>$existing_like->post_id,'user_id'=>$existing_like->user_id));
				}

			}
			else {
				$existing_like->deactivate();
				$this->renderSuccess(array('success'=>"Like removed."));
			}

		}
		else {
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