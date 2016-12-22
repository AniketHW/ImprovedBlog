<?php
class CommentController extends Controller {

	public $_comment;

	public function filters() {
		return array(
			'checkPost + delete, update'
			);
	}

	public function filterCheckPost($filterChain) {
		if(!isset($_GET['id'])) {
			$this->renderError('Enter Comment ID.');
		}
		else {
			$this->_comment = Comment::model()->active()->findByPk($_GET['id']);
			$filterChain->run();
		}
	} 

	public function actionIndex() {
		echo "Enter a Comment using /create.";
	}

	public function actionCreate() {
		if(isset($_POST['Comment'])) {
			$comment = Comment::create($_POST['Comment']);
			if(!$comment->errors) {
				$this->renderSuccess(array('comment_id'=>$comment->id));
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
			$this->_comment->status = 2;
			$this->_comment->save();
			$this->renderSuccess(array('success'=>"Comment deleted."));
		}
	}

	public function actionRestore($id){
		$comment = Comment::model()->findByPk($id);
		if(!$comment) {
			$this->renderError('Comment ID does not exist.');
		}
		else {       
			$comment->status = 1;
			$comment->save();
			$this->renderSuccess(array('success'=>"Comment restored."));
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
			$this->renderSuccess(array('success'=>"Comment successfully updated."));
		}
	}
}