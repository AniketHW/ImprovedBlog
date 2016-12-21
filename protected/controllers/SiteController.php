<?php
class SiteController extends Controller {

	public function actionIndex() {
		echo "Welcome.<br>";
		echo "If you're a new user use /user/create to Sign Up.<br>";
		echo "If not, login using /user/login&id=[Your user ID] to Login.<br>";
		echo "Happy Blogging!";
	}

}