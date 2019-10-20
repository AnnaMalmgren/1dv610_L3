<?php

namespace View;

require_once(__DIR__ . '/../model/UserCredentials.php');
require_once(__DIR__ . '/../model/Authentication.php');
require_once('Exceptions/UserCredentialsException.php');

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private $message = "";
	private $username = "";
	private $messageStyle = "";
	private $cookieExpiresIn;

	public function __construct() {
		$this->cookieExpiresIn = time() + (7 * 24 * 60 * 60);
	}

	public function userWantsToAuthenticate() : bool {
		return !empty($_COOKIE[self::$cookieName]) && !empty($_COOKIE[self::$cookiePassword]);
	}

	public function userWantsToLogin() : bool {
		return isset($_POST[self::$login]);
	}

	public function userWantsToLogout() : bool {
		return isset($_POST[self::$logout]);
	}

	public function rememberMe() : bool {
		return isset($_POST[self::$keep]);
	}
	
	public function getRequestName() : string {
		return trim($_POST[self::$name]);
	}

	public function getRequestPwd() : string {
		return trim($_POST[self::$password]);
	}

	public function getUserCredentials() : \Model\UserCredentials {
		if ($this->userWantsToLogin()) {
			return $this->getLoginCredentials();
		} else if ($this->userWantsToAuthenticate()) {
			return $this->getCookieCredentials();
		}
	}

	private function getLoginCredentials() : \Model\UserCredentials {
		if (empty($this->getRequestName())) {
			throw new UsernameMissingException();
		} 
		if (empty( $this->getRequestPwd())) {
			throw new PasswordMissingException();
		}
		return new \Model\UserCredentials($this->getRequestName(), $this->getRequestPwd());
	}


	private function getCookieCredentials() : \Model\UserCredentials {
		return new \Model\UserCredentials($_COOKIE[self::$cookieName], $_COOKIE[self::$cookiePassword]);
	}

	public function isLoggedIn() {
		$auth = new Authentication();
		return $auth->isUserLoggedIn();
	}

	private function setAlertDangerStyle() {
		$this->messageStyle = 'class="alert alert-danger"';
	}

	private function setAlertInfoStyle() {
		$this->messageStyle = 'class="alert alert-info"';
	}

	public function setNoUsernamegMsg() {
		$this->setAlertDangerStyle();
		$this->message = "Username is missing";
	}

	public function setNoPasswordMsg() {
		$this->setAlertDangerStyle();
		$this->message = "Password is missing";
	}

	public function setWrongNameOrPwdMsg() {
		$this->setAlertDangerStyle();
		$this->message = "Wrong name or password";
	}

	public function setWrongAuthCredentialsMsg() {
		$this->setAlertDangerStyle();
		$this->message = "Wrong information in cookies";
	}

	public function setUserRegisteredMsg() {
		$this->setAlertInfoStyle();
		$this->message = "Registered new user.";
	}

	public function setWelcomeBackMsg() {
		$this->setAlertInfoStyle();
		$this->message = "Welcome back with cookie";
	}

	public function setUsername($username) {
		$this->username = $username;
	}

	private function getUsername() : string {
		return $this->userWantsToLogin() ? $this->getRequestName() : $this->username;
	}

	public function setLoggedIn(\Model\User $user) {
		if ($this->rememberMe()) {
			$this->setCookies($user);
			$this->messageStyle = 'class="alert alert-info"';
			$this->message = "Welcome and you will be remembered";
		} else {
			$this->messageStyle = 'class="alert alert-info"';
			$this->message = "Welcome";
		}
	}

	private function setCookies(\Model\User $user) {
		setcookie(self::$cookieName, $this->getRequestName(), $this->cookieExpiresIn);
		setcookie(self::$cookiePassword, $user->getTempPassword(), $this->cookieExpiresIn);
	}

	public function setLogout() {
		$this->removeCookies();
		$this->setAlertInfoStyle();
		$this->message = "Bye bye!";
	}

	public function removeCookies() {
		setcookie(self::$cookieName, "", time() - 3600);
		setcookie(self::$cookiePassword, "", time() - 3600);
	}

	public function response() {
        if ($this->isLoggedIn()) {
			return $this->generateLogoutButtonHTML($this->message);
		} else {
			return $this->generateLoginFormHTML($this->message);
		}
	}
	
	private function generateLogoutButtonHTML($message) {
		return '
			<div class="d-flex justify-content-start">
				<form  method="post" >
					<p id="' . self::$messageId . '" ' . $this->messageStyle . '>' . $message .'</p>
					<input type="submit" name="' . self::$logout . '" value="logout" 
					class="btn btn-dark"/>
				</form>
			</div>';
	}

	private function generateLoginFormHTML($message) {
		return '
			<div class="container m-3">
				<a href="?register" class="text-decoration-none mt-3 mb-2 text-primary">Register a new user</a>
				<form method="post" action="?" class="w-50"> 
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '" ' . $this->messageStyle . '>' . $message . '</p>
					<div class="form-group">	
						<label for="' . self::$name . '">Username :</label>
						<input type="text" id="' . self::$name . '" name="' . self::$name . '" 
						value="' . $this->getUsername() . '" class="form-control"/>
					</div>
					<div class="form-group">	
						<label for="' . self::$password . '">Password :</label>
						<input type="password" id="' . self::$password . '" name="' . self::$password . '" 
						class="form-control"/>
					</div>
					<div class="form-group">	
						<label for="' . self::$keep . '">Keep me logged in  :</label>
						<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					</div>
						<input type="submit" name="' . self::$login . '" value="login" 
						class="btn btn-primary mt-2 mb-2"/>
				</form>
			</div>';
	}
}