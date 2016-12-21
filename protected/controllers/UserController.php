<?php
class UserController extends Controller {

	public $_user;
	public $_posts;
	public $_comments;
	public $_likes;

	public function filters() {
		return array(
			'checkUser + profile, login, posts, comments, likes, history, status, delete, restore',
			'findPosts + posts',
			'findComments + comments',
			'findLikes + likes'
			);
	}

	public function filterCheckUser($filterChain) {
		if(!isset($_GET['id'])) {
			$this->renderError('Please enter User ID.');
		}
		else {
			$this->_user = User::model()->active()->findByPk($_GET['id']);        
		}
		$filterChain->run();
	}
	

	public function filterFindPosts($filterChain) {
		if(!isset($_GET['id'])) {
			$this->renderError('Please enter User ID.');
		}
		else {
			$this->_posts = Post::model()->active()->findAllByAttributes(
				array('user_id'=>$_GET['id']));;
		}
		$filterChain->run();
	}

	public function filterFindComments($filterChain) {
		if(!isset($_GET['id'])) {
			$this->renderError('Please enter User ID.');
		}
		else {
			$this->_comments = Comment::model()->active()->findAllByAttributes(
				array('user_id'=>$_GET['id']));;
		}
		$filterChain->run();
	}

	public function filterFindLikes($filterChain) {
		if(!isset($_GET['id'])) {
			$this->renderError('Please enter User ID.');
		}
		else {
			$this->_likes = Like::model()->active()->findAllByAttributes(
				array('user_id'=>$_GET['id']));;
		}
		$filterChain->run();
	}

	public function actionIndex() {
		echo "If you're a new user use /create to Sign Up.<br>";
		echo "If not, login using /login&id=[Your user ID] to Login.<br>";
	}

	public function actionCreate() {
		if(isset($_POST['User'])) {
			$user = User::create($_POST['User']);
			if(!$user->errors) {
				$this->renderSuccess(array('user_id'=>$user->id,'name'=>$user->name,'password'=>$user->password,'email'=>$user->email));
			} else {
				$this->renderError($this->getErrorMessageFromModelErrors($user));
			}
		} else {
			$this->renderError('Please enter relevant User data.');
		}
	}

	public function actionLogin($id) {

		if(!$this->_user) {
			$this->renderError('The Account ID you entered does not exist. Please try again later.');
		}
		else {
			$this->renderSuccess(array('user_name'=>$this->_user->name));
		}
	}

	public function actionProfile($id) {

		if(!$this->_user) {
			$this->renderError('The Account ID you entered does not exist. Please try again later.');
		}
		else {
			$this->renderSuccess(array('user_id'=>$this->_user->id,'name'=>$this->_user->name,'email'=>$this->_user->email));
		}
	}

	public function actionPosts($id) {

		if(!$this->_posts) {
			if(!$this->_user) {
				$this->renderError('The Account ID you entered does not exist. Please try again later.');
			}
			else {
				$this->renderSuccess(array('This user has not published any posts yet.')); 
			}

		}
		else {
			$posts_data = array($id);
			foreach ($this->_posts as $post) {
				$posts_data[] = array('id'=>$post->id, 'title'=>$post->title);        
			}
			$no_of_posts = $this->_user->post_count;
			echo "Number of posts = $no_of_posts<br>";
			$this->renderSuccess(array('posts_data'=>$posts_data));
		}
	}

	public function actionComments($id) {

		if(!$this->_comments) {
			if(!$this->_user) {
				$this->renderError('The Account ID you entered does not exist. Please try again later.');
			}
			else {
				$this->renderSuccess(array('This user has not commented on any posts yet.')); 
			}

		}
		else {
			$comments_data = array();
			foreach ($this->_comments as $comment) {
				$comments_data[] = array('id'=>$comment->id, 'content'=>$comment->content);
				
			}
			$no_of_comments = $this->_user->comment_count;
			echo "Number of comments = $no_of_comments<br>";
			$this->renderSuccess(array('comments_data'=>$comments_data));
		}
	}

	public function actionLikes($id) {

		if(!$this->_likes) {
			if(!$this->_user) {
				$this->renderError('The Account ID you entered does not exist. Please try again later.');
			}
			else {
				$this->renderSuccess(array('This user has not liked any posts yet.')); 
			}

		}

		else {
			$likes_data = array();
			foreach ($this->_likes as $like) {
				$likes_data[] = array('id'=>$like->id, 'post_id'=>$like->post_id);
			}
			$no_of_likes = $this->_user->like_count;
			echo "Number of likes = $no_of_likes<br>";
			$this->renderSuccess(array('likes_data'=>$likes_data));
		}
	}

	public function actionHistory($id) {

		if(!$this->_user) {
			$this->renderError('The Account ID you entered does not exist. Please try again later.');
		}
		else {
			$name = $this->_user->name;
			$no_of_posts = $this->_user->post_count;
			$no_of_comments = $this->_user->comment_count;
			$no_of_likes = $this->_user->like_count;
			echo "$name has published $no_of_posts post(s).<br>He has posted $no_of_comments comment(s) and has liked $no_of_likes different post(s).";
		}
	}

	public function actionSearch($str) { 
		$users = User::model()->findAll(array('condition'=>"name LIKE :str AND status = 1",'params'=>array('str'=>"%$str%")));
		if(!$users)
		{
			$this->renderError('No matches found.');
		}
		else {
			foreach ($users as $user) {
				$this->renderSuccess(array('user_id'=>$user->id,'name'=>$user->name,'email'=>$user->email));
				echo "<br>";
			}
		}
	} 

	public function actionStatus($id) {
		if(!$this->_user)
		{
			$this->renderError('User ID does not exist.');
		}
		else {
			$status=$this->_user->status;
			echo "$status";
		}

	}

	public function actionDelete($id){
		if(!$this->_user)
		{
			$this->renderError('User ID does not exist.');
		}
		else {       
			$this->_user->status = 2;
			$this->_user->save();
			$this->renderSuccess(array('success'=>"Account deleted."));
		}
	}



	public function actionRestore($id){
		/*if(!$this->_post)
		{
			$this->renderError('Post ID does not exist.');
		}*/
		$user = User::model()->findByPk($id);
		if(!$user) {
			$this->renderError('Post ID does not exist.');
		}
		else {       
			$user->status = 1;
			$user->save();
			$this->renderSuccess(array('success'=>"Account restored."));
		}
	}

}
