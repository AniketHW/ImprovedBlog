<?php

class PostController extends Controller {

	public $_post;

	public function filters() {
		return array(
			'checkPost + view, comments, likes, status, delete, title, content'
			);
	}

	public function filterCheckPost($filterChain) {
		if(!isset($_GET['id'])) {
			$this->renderError('Enter Post ID.');
		}
		else {
			$this->_post = Post::model()->active()->findByPk($_GET['id']);
			$filterChain->run();
		}
	} 

	public function actionIndex() {
		$this->renderSuccess(array('message'=>"Use /create to make a new post and /view to read existing posts."));
	}

	public function actionCreate() {
		if(isset($_POST['Post'])) {
			$post = Post::create($_POST['Post']);
			if(!$post->errors) {
				$this->renderSuccess(array('post_id'=>$post->id));
			} else {
				$this->renderError($this->getErrorMessageFromModelErrors($post));
			}
		} else {
			$this->renderError('Invalid Post Data.');
		}
	}

	public function actionView($id) {
		
		if(!$this->_post) {
			$this->renderError('Post ID does not exist.');
		}
		else {
			$this->renderSuccess(array('post_id'=>$this->_post->id,'title'=>$this->_post->title,'content'=>$this->_post->content));
		}
	}



	public function actionComments($id) {
		
		if(!$this->_post)
		{
			$this->renderError('Post ID does not exist.');
		}
		else {
			$comments = $this->_post->comments;
			$no_of_comments = $this->_post->comment_count;
			foreach ($comments as $comment) {
				$comments_data[] = array('id'=>$comment->id, 'content'=>$comment->content);
			}
			$this->renderSuccess(array('no_of_comments'=>$no_of_comments,'comments_data'=>$comments_data));
		}

	}

	public function actionLikes($id) {

		if(!$this->_post)
		{
			$this->renderError('Post ID does not exist.');
		}
		else {
			$likes = $this->_post->likes;
			$no_of_likes = $this->_post->like_count;
			foreach ($likes as $like) {
				$likes_data[]= array('id'=>$like->id,'user_id'=>$like->user_id);
			}
			$this->renderSuccess(array('no_of_likes'=>$no_of_likes,'likes_data'=>$likes_data));			
		}
	} 	

	public function actionSearch($str) { 
		$posts = Post::model()->findAll(array('condition'=>"content LIKE :str AND status=1",'params'=>array('str'=>"%$str%")));
		if(!$posts)
		{
			$this->renderError('No matches found.');
		}
		else {
			foreach ($posts as $post) {
				$posts_data[] = array('post_id'=>$post->id,'title'=>$post->title,'content'=>$post->content);				
			}
			$this->renderSuccess(array('post_match'=>$posts_data));	
		}
	} 

	public function actionStatus($id) {
		if(!$this->_post)
		{
			$this->renderError('Post ID does not exist.');
		}
		else {
			$this->renderSuccess(array('status'=>$this->_post->status));
		}

	}

	public function actionDelete($id) {
		if(!$this->_post)
		{
			$this->renderError('Post ID does not exist.');
		}
		else {       
			$this->_post->deactivate();
			$this->_post->save();
			$this->renderSuccess(array('success'=>"Post deleted."));
		}
	}

	public function actionRestore($id){
		$post = Post::model()->findByPk($id);
		if(!$post) {
			$this->renderError('Post ID does not exist.');
		}
		else {       
			if($post->status!=Post::STATUS_ACTIVE){
				$post->activate();
				$post->save();
				$this->renderSuccess(array('message'=>"Post restored."));
			}
			else {
				$this->renderSuccess(array('message'=>"Post already exists."));
			}
		}
	}

	public function actionTitle($id,$title){
		if(!$this->_post)
		{
			$this->renderError('Post ID does not exist.');
		}
		else {       
			$this->_post->title = $title;
			$this->_post->save();
			$this->renderSuccess(array('message'=>"Title successfully updated."));
		}
	}

	public function actionContent($id,$content){
		if(!$this->_post)
		{
			$this->renderError('Post ID does not exist.');
		}
		else {       
			$this->_post->content = $content;
			$this->_post->save();
			$this->renderSuccess(array('message'=>"Content successfully updated."));
		}
	}

}



