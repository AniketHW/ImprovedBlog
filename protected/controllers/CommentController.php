<?php
class CommentController extends Controller {

	public $_comment;

	public function filters() {
		return array(
			'checkPost + delete, update',
			'checkDeletedPost + restore'
			);
	}

	public function filterCheckPost($filterChain) {
		if(!isset($_GET['id'])) {
			$this->renderError('Enter Comment ID.');
		}
		else {
			$this->_comment = Comment::model()->active()->findByPk($_GET['id']);
		}
		$filterChain->run();		
	} 

	public function filterCheckDeletedPost($filterChain) {
		if(!isset($_GET['id'])) {
			$this->renderError('Enter Comment ID.');
		}
		else {
			$this->_comment = Comment::model()->inactive()->findByPk($_GET['id']);
		}
		$filterChain->run();		
	} 


	public function actionIndex() {
		$this->renderSuccess(array('message'=>"Enter a Comment using /create."));
	}

	public function actionCreate() {
		if(isset($_POST['Comment'])) {
			$comment = Comment::create($_POST['Comment']);
			if(!$comment->errors) {
				$this->renderSuccess(array('comment_id'=>$comment->id,'content'=>$comment->content,'user_id'=>$comment->user_id,'post_id'=>$comment->post_id));
			} else {
				$this->renderError($this->getErrorMessageFromModelErrors($comment));
			}
		} else {
			$this->renderError('Invalid Comment.');
		}
	}

	public function actionDelete($id){
		if(!$this->_comment)
		{
			$this->renderError('Comment ID does not exist.');
		}
		else {       
			$this->_comment->deactivate();
			$this->renderSuccess(array('success'=>"Comment deleted."));
		}
	}

	public function actionRestore($id){
		if(!$this->_comment) {
			$this->renderError('Comment ID does not exist.');
		}
		else { 
			if($this->_comment->status!=Comment::STATUS_ACTIVE){
				$this->_comment->activate();
				$this->renderSuccess(array('message'=>"Comment restored."));
			}
			else {
				$this->renderSuccess(array('message'=>"Comment already exists."));
			}

		}
	}

	public function actionUpdate($id,$content){
		if(!$this->_comment)
		{
			$this->renderError('Comment ID does not exist.');
		}
		else {       
			$this->_comment->content = $content;
			$this->_comment->save();
			$this->renderSuccess(array('message'=>"Comment successfully updated."));
		}
	}
}