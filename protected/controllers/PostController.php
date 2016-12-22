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
		echo "Create new posts by using /create.<br>";
		echo "View an existing post by using /view.<br>";
		echo "View details regarding comments and likes by using /comments and /likes.";
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
			echo "$no_of_comments different comment(s) about this post have been posted.<br>";
			foreach ($comments as $comment) {
				$this->renderSuccess(array('user_id'=>$comment->user_id,'content'=>$comment->content));
				echo "<br>";
			}

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
			echo "This post has received $no_of_likes like(s).<br>";
			foreach ($likes as $like) {
				$this->renderSuccess(array('user_id'=>$like->user_id));
				echo "<br>";
			}
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
			$this->_post->status = 2;
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
			$post->status = 1;
			$post->save();
			$this->renderSuccess(array('success'=>"Post restored."));
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
			$this->renderSuccess(array('success'=>"Title successfully updated."));
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
			$this->renderSuccess(array('success'=>"Content successfully updated."));
		}
	}

}



